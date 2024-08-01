<?php
namespace app\money\model;

use app\apicom\home\Member;
use app\apicom\home\Wallet;
use app\apicom\model\MoneyRecharge;
use app\member\model\Bank as BankModel;
use app\member\model\MemberMessage as MemberMessageModel;
use app\money\service\CBpayService;
use app\money\service\JDpayService;
use app\money\service\KdpayService;
use app\money\service\MpayService;
use app\money\service\OKpayService;
use app\money\service\TOpayService;
use think\Db;
use think\Model;

class Withdraw extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MONEY_WITHDRAW__';
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
        $status = [0 => '待处理', 1 => '成功', 2 => '失败', 3 => '退回'];
        return $status[$data['status']];
    }

    public static function getAll($map = [], $order = '')
    {
        $data_list = self::view('money_withdraw', true)
          ->view('member', 'mobile, name, id_card,email', 'member.id=money_withdraw.mid', 'left')
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $item->money = money_convert($item->money);
              $item->fee = money_convert($item->fee);
          });
        return $data_list;
    }

    public static function getAll2($map = [], $order = '', $start_time = '', $end_time = '')
    {
        $data_list = self::view('money_withdraw', true)
          ->view('member', 'mobile, name, id_card', 'member.id=money_withdraw.mid', 'left')
          ->where($map)
          ->order($order)
//                ->where('money_withdraw.create_time','>=',$start_time)
//                ->where('money_withdraw.create_time','<=',$end_time)
          ->paginate()
          ->each(function ($item, $key) {
              $item->money = money_convert($item->money);
              $item->fee = money_convert($item->fee);
          });
        return $data_list;
    }

    /**
     * 提现审核信息保存
     * @return [type] [description]
     */
    public static function saveAudit()
    {
        $res1 = $res2 = $res3 = true;
        $status = input('post.status');
        $id = input('post.id');
        $remark = input('post.remark');
        if (!$id) {
            return false;
        }
        $withdraw = Db('money_withdraw')->where('id', $id)->find();
        $user_mobile = Db('member')->where('id', $withdraw['mid'])->value('mobile');
        $up_withdraw['status'] = $status;
        $up_withdraw['id'] = $id;
        $up_withdraw['examine_time'] = time();
        $up_withdraw['remark'] = $remark;
        Db::startTrans();
        try {
//            $record = new record;

            $record = new Record();
            $money_info = Db('money')->where('mid', $withdraw['mid'])->lock(true)->find();
            if ($status == 1) {// 提现通过 减去冻结金额

                self::sms_withdraw('stock_withdraw_auditing_success', $user_mobile, $withdraw['money']);
                $contents = "提现审核通过";
                $up_money['freeze'] = bcsub($money_info['freeze'], $withdraw['money']);
                $info = "提现单号：" . $withdraw['order_no'];
                $affect = $withdraw['money'];
                $type = 3;
                $account = $money_info['account'];
            } elseif ($status == 2) { // 提现失败 解冻冻结金额
                self::sms_withdraw('stock_withdraw_auditing_fail', $user_mobile, $withdraw['money']);
                $contents = "提现审核未通过";
                $up_money['freeze'] = bcsub($money_info['freeze'], $withdraw['money']);
//                $up_money['freeze'] = bcsub($up_money['freeze'], $withdraw['fee']);//扣除手续费
                $up_money['account'] = bcadd($money_info['account'], $withdraw['money']);
//                $up_money['account'] = bcadd($up_money['account'], $withdraw['fee']); //加上手续费
                $info = "解冻金额" . ($withdraw['money'] / 100) . "元,提现单号：" . $withdraw['order_no'];
//                $info = "解冻金额" . (bcadd($withdraw['money'], $withdraw['fee']) / 100) . "元,提现单号：" . $withdraw['order_no'];
                $affect = $withdraw['money'];
//                $affect = bcadd($withdraw['money'], $withdraw['fee']);
                $type = 4;
                $account = $up_money['account'];
            } elseif ($status == 3) {// 提现退回 补充可用金额
                $contents = "提现退回";
                $up_money['account'] = bcadd($money_info['account'], $withdraw['money']);
                $info = "退回金额" . ($withdraw['money'] / 100) . "元,提现单号：" . $withdraw['order_no'];
                $affect = $withdraw['money'];
                $type = 6;
                $account = $up_money['account'];
            } else {
                return '状态有误';
            }
            $res1 = Db('money')->where('mid', $withdraw['mid'])->update($up_money);
//                $res3 = $record->saveData($withdraw['mid'], $affect, $account, $type, $info);
            $obj = ['affect' => $affect, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $up_money['activity_account'], 'sn' => ''];
            $res3 = $record->saveData($withdraw['mid'], $affect, $account, $type, $info, '', '', $obj);

            $res2 = self::update($up_withdraw);
            if ($res1 && $res2 && $res3) {
                Db::commit();
                //添加站内信信息
                $MemberMessageModel = new MemberMessageModel();
                $MemberMessageModel->addInnerMsg($withdraw['mid'], '提现审核通知', $contents, 6);//站内信
            } else {
                Db::rollback();
                return '数据更新失败';
            }
        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        $mobile = Db('member')->where('id', $withdraw['mid'])->value('mobile');
        $details = $mobile . ' 字段(status)，原值：(0)新值：(' . $status . ') 备注：' . $remark . $info;
        action_log('withdraw_edit', 'money_withdraw', $id, UID, $details);
        return true;
    }

    static public function sms_withdraw($type, $mobile, $money)
    {
        $contentarr = getconfigSms_status(['name' => $type]);
        $content = str_replace(array("#var#", "#amount#"), array($mobile, money_convert($money)), $contentarr['value']);
        if ($contentarr['status'] == 1) {
            $res = sendsms_mandao('', $content, '');
        }
        return $res;
    }

    public static function saveData($parameter)
    {
        $where1['id'] = $parameter['mid'];//会员ID
        $where1['status'] = 1;//会员状态
        $names = Db::name('member')->field('name,withdrawal_commission')
          ->where($where1)
          ->find();
        $type = isset($parameter['type']) ? $parameter['type'] : 0;
        $bank_id = $parameter['bank_id'];
        //提现到银行卡
        if ($type == 0) {
            $bank = BankModel::bankInfo($bank_id);
            $data['bank'] = $bank['bank'] . "|" . $bank['card'] . '|' . $bank['province'] . $bank['city'] . $bank['branch'] . "|" . $names['name'];
        }
        //提现到数字货币账号
        if ($type == 1) {
            $wallet = Db::name('wallet')
              ->alias('w')
              ->join('money_payment p', 'w.payment_id=p.id', 'LEFT')
              ->field('p.name,w.address')
              ->where(['w.id' => $bank_id])
              ->find();
            $data['bank'] = $wallet['name'] . "|" . $wallet['address'] . "|" . $names['name'];
        }
        $data['mid'] = $parameter['mid'];
        $money = $parameter['money'] * 100;  //提现金额
//        $data['money'] = $parameter['money'] * 100;  //提现金额
//        查询手续费,如果用户单独设置暗扣
        if($names['withdrawal_commission'] > 0){
            $data['fee'] = $parameter['money'] * $names['withdrawal_commission'];
        }else{
//            否则就用提现默认
            $result = Db::name('money_withdraw_config')->where("id", 1)->find();
            $data['fee'] = $parameter['money'] * $result['ratio'];
        }
        $data['money'] = $money;
        $data['real_money'] = bcsub($money, $data['fee']);  //扣除手续费用之后的金额
        $data['order_no'] = 'tx' . generate_rand_str(10, 3);
        $data['create_time'] = time();
        $data['create_ip'] = get_client_ip(1);
        $data['for_user'] = $parameter['for_user'];
        $data['payment_code'] = $parameter['payment_code'];
        $data['wallet_id'] = $bank_id; //钱包id
        $data['wallet_type'] = $type; //钱包类型
//        $record = new record;
        $record = new Record();
        Db::startTrans();
        $money_info = Db('money')->where('mid', $data['mid'])->lock(true)->find();
        $account = bcsub($money_info['account'], $money);
        $up_money['freeze'] = bcadd($money_info['freeze'], $money);
        $up_money['account'] = $account;
        $info = "提现冻结金额" . ($money / 100) . "元,提现单号：" . $data['order_no'];
        try {
            $res1 = self::create($data);
//                $res2 = $record->saveData($data['mid'], -$money, $account, 2, $info);
            $obj = ['affect' => -$money, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $up_money['activity_account'], 'sn' => '','fee'=>$data['fee']];
            $res2 = $record->saveData($data['mid'], -$money, $account, 2, $info, '', '', $obj);
            $res3 = Db('money')->where('mid', $data['mid'])->update($up_money);
            if ($res1 && $res2 && $res3) {
                Db::commit();
                return ['status' => 1, 'message' => '提交成功'];
            } else {
                Db::rollback();
                return ['status' => 0, 'message' => '提交失败'];
            }
        } catch (\Exception $e) {
            Db::rollback();
            return ['status' => 0, 'message' => '数据异常'];
        }
    }




    /**
     * 提现审核信息保存
     * @return [type] [description]
     */
    public static function saveAuditOnline($id='')
    {
        if (!$id) {
            return false;
        }
        $withdraw = Db('money_withdraw')->where('id', $id)->find();
        $user_mobile = Db('member')->where('id', $withdraw['mid'])->value('mobile');
        $up_withdraw['status'] = 1;
        $up_withdraw['id'] = $id;
        $up_withdraw['examine_time'] = time();
        if($withdraw['wallet_type'] != 1){
            return '不支持银行卡类型';
        }
        if(!empty($withdraw['wallet_id']) && $withdraw['wallet_id'] == 0){
            return '钱包参数异常，请联系客服';
        }
        Db::startTrans();
        try {

            $record = new Record();
            $money_info = Db('money')->where('mid', $withdraw['mid'])->lock(true)->find();
            $rem = self::withdrawRemit($withdraw);
            if($rem['code'] != 200){
                return $rem['message'];
            }

            self::sms_withdraw('stock_withdraw_auditing_success', $user_mobile, $withdraw['money']);
            $contents = "提现审核通过";
            $up_money['freeze'] = bcsub($money_info['freeze'], $withdraw['money']);
            $info = "提现单号：" . $withdraw['order_no'];
            $affect = $withdraw['money'];
            $type = 3;
            $account = $money_info['account'];

            $res1 = Db('money')->where('mid', $withdraw['mid'])->update($up_money);
//                $res3 = $record->saveData($withdraw['mid'], $affect, $account, $type, $info);
            $obj = ['affect' => $affect, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $up_money['activity_account'], 'sn' => ''];
            $res3 = $record->saveData($withdraw['mid'], $affect, $account, $type, $info, '', '', $obj);

            $res2 = self::update($up_withdraw);
            if ($res1 && $res2 && $res3) {
                Db::commit();
                //添加站内信信息
                $MemberMessageModel = new MemberMessageModel();
                $MemberMessageModel->addInnerMsg($withdraw['mid'], '提现审核通知', $contents, 6);//站内信
            } else {
                Db::rollback();
                return '数据更新失败';
            }
        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        $mobile = Db('member')->where('id', $withdraw['mid'])->value('mobile');
        $details = $mobile . ' 字段(status)，原值：(0)新值：(' . 1 . ') 备注：' . '' . $info;
        action_log('withdraw_edit', 'money_withdraw', $id, UID, $details);
        return true;
    }



//    通过地址去转账
    public static function withdrawRemit($parameter)
    {
        $get_wallet = Db::name('wallet')
            ->where('mid',$parameter['mid'])
            ->where('id',$parameter['wallet_id'])->find();
        if(!$get_wallet){
            return [
                'code'=>500,
                'message'=>'钱包异常'
            ];
        }
        $payment = Db::name('money_payment')->where('id',$get_wallet['payment_id'])->find();
        if(!$payment){
            return [
                'code'=>500,
                'message'=>'支付类型异常'
            ];
        }
        if($payment['support_type'] <= '1'){
            return [
                'code'=>500,
                'message'=>'支付类型不支持'
            ];
        }
//        是否自动到账0=手动，1=自动'
//        if($payment['is_auto'] == '0'){
//            return [
//                'code'=>200,
//            ];
//        }

        $orderInfo = [
            'app_id' => $payment['app_id'],
            'order_no' =>$parameter['order_no'],
            'real_money' => $parameter['real_money'] / 100,
            'type' => $payment['type'],
            'address' => $get_wallet['address'],
            'app_secret' => $payment['app_secret'],
            'app_public_key' => $payment['app_public_key'],
            'specific_address' => $payment['specific_address'],

        ];
        return self::onlineRemit($orderInfo);
    }

    public static function onlineRemit($orderInfo) { //线上
        switch ($orderInfo['type']) {
            case 'kdpay':
                $res = (new KdpayService())->Remit($orderInfo);
                break;
            case 'okpay':
                $res = (new OKpayService())->Remit($orderInfo);
                break;
            case 'topay':
                $res = (new TOpayService())->Remit($orderInfo);
                break;
            case 'jdpay':
                $res = (new JDpayService())->Remit($orderInfo);
                break;
            case 'mpay':
                $res = (new MpayService())->Remit($orderInfo);
                break;
            case 'cbpay':
                $res = (new CBpayService())->Remit($orderInfo);
                break;
            default:;
                break;
        }
        return $res;

    }
}

?>
