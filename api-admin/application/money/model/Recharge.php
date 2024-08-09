<?php
namespace app\money\model;

use app\member\model\Member as MemberModel;
use app\member\model\MemberMessage as MemberMessageModel;
use think\Db;
use think\Model;

class Recharge extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MONEY_RECHARGE__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function setCreateTimeAttr()
    {
        return time();
    }

    public function setCreateIpAttr()
    {
        return get_client_ip(1);
    }

    public function getStatusAttr($value, $data)
    {
        $status = [0 => '待处理', 1 => '成功', 2 => '失败', 3 => '签名不符'];
        return $status[$data['status']];
    }

    public static function getAll($map = [], $order = '')
    {
        $data_list = self::view('money_recharge', true)
            ->view('member', 'mobile, name, id_card,role_name', 'member.id=money_recharge.mid', 'left')
            ->view('money_payment', 'name as payment_name,unit', 'money_payment.id=money_recharge.payment_id', 'left')
            ->where($map)
            ->order($order)
            ->paginate()
            ->each(function ($item, $key) {
                $item->money = money_convert($item->money);
                $item->fee   = money_convert($item->fee);
            });
        return $data_list;
    }

    public static function gettotalsum($map = [])
    {
        $data_list = self::view('money_recharge', true)
            ->view('member', 'mobile, name, id_card,role_name', 'member.id=money_recharge.mid', 'left')
            ->view('money_payment', 'name as payment_name,unit', 'money_payment.id=money_recharge.payment_id', 'left')
            ->where($map)
            ->sum('money_recharge.money');
        return $data_list;
    }

    /**
     * 充值审核信息保存
     * @return [type] [description]
     */
    public static function saveAudit()
    {
        $res1   = $res3 = true;
        $status = input('post.status');
        $id     = input('post.id');
        $remark = input('post.remark');
        if (!$id) {
            return ['status' => false, 'msg' => '缺少主键'];
        }
        $charge              = Db::name('money_recharge')->where('id', $id)->find();
        if($charge['status'] != 0)return ['status' => false, 'msg' => '订单已审核'];
        $up_charge['status'] = $status;
        $up_charge['remark'] = $remark;
        $up_charge['id']     = $id;
        $contents            = "充值审核未通过";
        Db::startTrans();
        try {

            if ($status == 1) {
                $contents                  = "充值审核通过";
                $account                   = Db::name('money')->where('mid', $charge['mid'])->lock(true)->value('account');
                $up_money['account']       = bcadd($account, $charge['money']);
                $up_money['recharge_time'] = $charge['create_time'];
                $res1 = Db::name('money')->where('mid', $charge['mid'])->update($up_money);
                self::updateMemberData($charge['mid'], $up_money['account'], $charge['money']);
                $info   = '充值单号：' . $charge['order_no'];
                $record = new Record;

                $obj = ['affect' => $charge['money'], 'account' => $up_money['account'], 'affect_activity' => 0, 'activity_account' => $account['activity_account'], 'sn' => ''];

                $res3 = $record->saveData($charge['mid'], $charge['money'], $up_money['account'], 1, $info, '', '', $obj);
            }
            $res2 = Db::name('money_recharge')->update($up_charge);
            if ($res1 && $res2 && $res3) {
                Db::commit();
                $user_mobile = $account = Db::name('member')->where('id', $charge['mid'])->value('mobile');
                switch ($status) {
                    case 1:
                        /*$content = \think\Config::get('sms_template')['stock_offline_auditing_success'];
                        $content = str_replace(array("#var#","#amount#"),array($user_mobile,money_convert($charge['money'])), $content);
                        $res = sendsms_mandao('',$content,'');*/
                        self::sms_recharge('stock_offline_auditing_success', $user_mobile, $charge['money']);
                        break;
                    case 2:
                        self::sms_recharge('stock_offline_auditing_fail', $user_mobile, $charge['money']);
                        break;
                }

                //添加站内信信息
                $MemberMessageModel = new MemberMessageModel();
                $MemberMessageModel->addInnerMsg($charge['mid'], '充值审核通知', $contents, 1);//站内信
            } else {
                Db::rollback();

                return ['status' => false, 'msg' => '数据更新失败-2'];
            }
        } catch (\Exception $e) {
            Db::rollback();

            return ['status' => false, 'msg' => '数据异常-1'];
        }
        $mobile  = Db::name('member')->where('id', $charge['mid'])->value('mobile');
        $details = $mobile . ' 字段(status)，原值：(0)新值：(' . $status . ') 备注：' . $remark;
        action_log('recharge_edit', 'money_recharge', $id, UID, $details);
        return ['status' => true, 'msg' => '数据更新成功'];
    }

    public static function sms_recharge($type, $mobile, $money)
    {
        $contentarr = getconfigSms_status(['name' => $type]);
        $content    = str_replace(array("#var#", "#amount#"), array($mobile, money_convert($money)), $contentarr['value']);
        if ($contentarr['status'] == 1) {
            $res = sendsms_mandao($mobile, $content, 'user');
        }
        return $res;
    }

    /**
     * 保存充值数据到数据库
     *
     * @param float $money 充值金额
     * @param int $mid 用户id
     * @param string $type 充值类型
     * @param string $line_bank 线下转账充值的银行信息
     *
     * @return string            返回订单号
     */
    public static function saveData($money, $mid, $type, $line_bank = '', $receipt_img = null, $charge_type_id, $transfer)
    {
        $for_user = Db::name('member')->where('id', $mid)->value('agent_far'); //代理id/推荐人id
        //$data['order_no'] = 'cz'.generate_rand_str(10, 3);
        $data['order_no']       = 'cz' . date('YmdHis') . generate_rand_str(4, 3);
        $data['mid']            = $mid;
        $data['money']          = $money * 100;
        $data['type']           = $type;
        $data['line_bank']      = $line_bank;
        $data['create_time']    = time();
        $data['create_ip']      = time();
        $data['status']         = 0;
        $data['receipt_img']    = $receipt_img;
        $data['charge_type_id'] = $charge_type_id;
        $data['form_name']      = $transfer;
        $data['for_user']       = $for_user;//归属代理/推荐人
        $result                 = self::create($data);
        return $result->order_no;
    }

    /**
     * 数字货币保存充值数据到数据库
     *
     * @param float $money 充值金额
     * @param int $mid 用户id
     * @param string $type 充值类型
     * @param string $line_bank 线下转账充值的银行信息
     *
     * @return string            返回订单号
     */
    public static function saveDataCurrency($money, $mid, $type, $payment_id, $num, $txid, $address, $currency, $is_auto, $transfer_username, $transfer_number)
    {
        $for_user = Db::name('member')->where('id', $mid)->value('agent_far'); //代理id/推荐人id
        //$data['order_no'] = 'cz'.generate_rand_str(10, 3);
        $data['order_no']          = $mid . 'cz' . date('YmdHis') . generate_rand_str(4, 3);
        $data['mid']               = $mid;
        $data['money']             = $money;
        $data['type']              = $type;
        $data['payment_id']        = $payment_id;
        $data['create_time']       = time();
        $data['create_ip']         = get_client_ip();
        $data['status']            = 0;
        $data['num']               = $num;
        $data['txid']              = $txid;
        $data['address']           = $address;
        $data['currency']          = $currency;
        $data['is_auto']           = $is_auto;
        $data['transfer_username'] = $transfer_username;
        $data['transfer_number']   = $transfer_number;
        $data['for_user']          = $for_user;//归属代理/推荐人
        $result                    = self::create($data);
        return $result->id;
    }

    //更新用户数据
    public static function updateMemberData($mid = '', $balance = 0, $moeny = 0)
    {
        if ($moeny > 0 && $mid && $balance > 0) {

            MemberModel::where('id', $mid)->update(['balance' => $balance, 'user_property' => $balance]);
            MemberModel::where('id', $mid)->setInc('recharge_num', 1);
            MemberModel::where('id', $mid)->setInc('user_recharge', $moeny);
        }
    }
}

?>
