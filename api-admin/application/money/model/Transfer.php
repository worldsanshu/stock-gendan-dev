<?php
namespace app\money\model;

use think\Db;
use think\model;
use app\money\model\Record as Recordmodel;
use app\common\service\UserService;

class Transfer extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MONEY_TRANSFER__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function setAddtimeAttr()
    {
        return time();
    }

    public function setAddIpAttr()
    {
        return get_client_ip(1);
    }

    public static function getAll($map = [], $order = '')
    {
        $data_list = self::view('money_transfer', true)
          ->view('member', 'mobile, name, id_card,role_name', 'member.id=money_transfer.mid', 'left')
          ->view('admin_user', 'username', 'admin_user.id=money_transfer.user_id', 'left')
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $item->money = money_convert($item->money);
          });
        return $data_list;
    }

    /**
     * 转账信息保存
     * @return [type] [description]
     */
    public static function saveData()
    {
        $res1 = $res2 = $res3 = true;
        $mobile = input('post.mobile');
        $money = input('post.money');
        $remark = input('post.info');
        if (!$mobile || !$money || !$remark) {
            return '信息有误';
        }
        $member_info = Db("member")->where('mobile', $mobile)->find();
        if (!$member_info) {
            return '该会员手机号暂未注册！';
        }
        Db::startTrans();
        try {
            $money *= 100;

            $money_info = Db('money')->where('mid', $member_info['id'])->lock(true)->find();
            $up_money['account'] = bcadd($money_info['account'], $money);
            $order_no = 'zz' . generate_rand_str(10, 3);
            $info = "转账单号：" . $order_no;

            $res1 = Db('money')->where('mid', $member_info['id'])->update($up_money);
//                $res3 = $record->saveData($member_info['id'], $money, $up_money['account'], 18, $info);
            $obj = ['affect' => $money, 'account' => $up_money['account'], 'affect_activity' => 0, 'activity_account' => $money_info['activity_account'], 'sn' => ''];

            $res3 = Recordmodel::saveData($member_info['id'], $money, $up_money['account'], 18, $info, '', '', $obj);

            $transfer['order_no'] = $order_no;
            $transfer['mid'] = $member_info['id'];
            $transfer['money'] = $money;
            $transfer['user_id'] = UID;
            // $transfer['create_time'] = time();
            $transfer['create_ip'] = get_client_ip(1);
            $transfer['info'] = $remark;
            $res2 = self::create($transfer);
            if ($res1 && $res2 && $res3) {
                Db::commit();
            } else {
                Db::rollback();
                return '数据提交失败';
            }
        } catch (\Exception $e) {
            Db::rollback();
            return '数据异常';
        }
        $details = $mobile . '  单号：' . $order_no . ' 备注：' . $remark;
        action_log('transfer_add', 'money_transfer', $member_info['id'], UID, $details);
        return true;
    }
}

?>
