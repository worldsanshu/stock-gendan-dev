<?php
namespace app\money\model;

use think\Db;
use think\model;
use app\money\model\Record as RecordModel;

class Money extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MONEY__';

    public static function getAll($map = [], $order = '')
    {
        $data_list = self::view('money', true)
          ->view('member', 'mobile, name, id_card,email', 'member.id=money.mid', 'left')
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $item->account = money_convert($item->account);
              $item->freeze = money_convert($item->freeze);
              $item->activity_account = money_convert($item->activity_account);
              $item->operate_account = money_convert($item->operate_account);
              $item->bond_account = money_convert($item->bond_account);
              $item->freeze_activity = money_convert($item->freeze_activity);
          });
        return $data_list;
    }

    public static function getMoney($mid)
    {

        $result = Db::name('money')->where(['mid' => $mid])->find();
        if (empty($result)) {
            $result['account'] = 0;
            $result['freeze'] = 0;
            $result['operate_account'] = 0;
            $result['bond_account'] = 0;
            $result['total'] = 0;
        } else {
            $result['total'] = bcadd($result['bond_account'], bcadd($result['account'], $result['freeze']));
        }
        return $result;
    }

    /*
     * 申请时冻结资金
     * @mid 会员ID
     * @minfo 会员资金信息
     * @money 冻结金额
     * @freeze_activity 活动金冻结资金
     * @activity_account 活动金余额
     *
     * */
    public static function money_freeze($mid, $freeze= '', $account= '', $freeze_activity = '', $activity_account = '')
    {
        $data = array();
        if (is_numeric($freeze)) {
            $data['freeze'] = $freeze;
        }
        if (is_numeric($account)) {
            $data['account'] = $account;
        }
        if (is_numeric($activity_account)) {
            $data['activity_account'] = $activity_account;
        }
        if (is_numeric($freeze_activity)) {
            $data['freeze_activity'] = $freeze_activity;
        }
        return self::where(['mid' => $mid])->update($data);
    }

    /*
  * 更新活动资金
  * @mid 会员ID
  * @account 更新金额
  * */
    public static function money_freeze_activity_account($mid, $account)
    {
        return self::where(['mid' => $mid])->update(['activity_account' => $account]);
    }

    /*
     * 申请通过更新资金
     *
     */
    public static function money_up($mid, $mmoney)
    {
        return self::where(["mid" => $mid])->update($mmoney);
    }


    //    资金批量操作
    public static function moneyUpdate($data)
    {
        // 保存数据
        // 将字符串转换为数组，并去除每个元素前后的空格
        $mobile_list = array_map('trim', explode(PHP_EOL, $data['mobile_list']));
// 过滤掉空字符串（虽然在这个例子中可能不需要，但通常是一个好习惯）
        $mobile_list = array_filter($mobile_list);
//        $userlist     = Db::name('member')->whereIn(、'mobile', $mobile_list)->select();
//        echo Db::name('member')->getLastSql();die;
//        print_r($userlist);die;
        Db::startTrans();
        try {
            foreach ($mobile_list as $key => $value){

                $user_info             = Db::name('member')->where("mobile", $value)->find();
                if(!$user_info){
//                    $this->error($value . '：号码不存在');
                    return [
                        'code'=>1,
                        'message'=>$value . '：号码不存在'
                    ];
                }

                $data['new_account'] = $data['new_account']?: 0;
                $data['new_activity_account'] = $data['new_activity_account'] ?: 0;
                $info             = Db::name('money')->where("mid", $user_info['id'])->find();
                $account          = $data['new_account'] * 100;  //允许负数
                $activity_account = $data['new_activity_account'] * 100;   //允许负数

                $money1           = ($account + $info['account']);//允许负数
                $money2           = $activity_account + $info['activity_account'];//允许负数
                if ($money1 < 0 || $money2 < 0) {
//                    $this->error('金额不能为负数');
                    return [
                        'code'=>1,
                        'message'=>'金额不能为负数'
                    ];
                }

                $type = 18; //默认系统转入

                if($account <0 || $activity_account <0){
                    $type = 19; //管理员操作
                }

                if($data['new_activity_account'] > 0){
                    $type = 111;
                }
                if(isset($data['winnings'])){
                    if($data['winnings'] < 0){
//                        $this->error('彩金不能为负数');
                        return [
                            'code'=>1,
                            'message'=>'彩金不能为负数'
                        ];
                    }
                    if ($data['winnings'] > 0){
                        $money1 += $data['winnings'] * 100;
                        $account += $data['winnings'] * 100;
                        $type = 110;
                    }
                }
                $infos  = Db::name('money')->where("mid", $user_info['id'])->update(['account' => $money1 ?? 0, 'activity_account' => $money2 ?? 0]);

                $remark = $data['remark'];
                if ($infos) {
                    Db::commit();

                    if ($info['account'] != $money1) {
                        $straccount = "余额的变化金额：" . ($info['account'] / 100) . "=>" . ($money1 / 100) . "元";
                    } else {
                        $straccount = "余额未变化";
                    }
                    if ($info['activity_account'] != $money2) {
                        $activity_account_text = "活动资金的变化金额：" . ($info['activity_account'] / 100) . "=>" . ($money2 / 100) . "元";
                    } else {
                        $activity_account_text = "活动金余额未变化";
                    }

                    $obj     = ['affect' => $account, 'account' => $money1, 'affect_activity' => $activity_account, 'activity_account' => $money2, 'sn' => ''];
                    $details = "(管理员：" . UID . " ，向uid为：" . $user_info['id'] . " 的资金账户做操作，{$straccount}，{$activity_account_text}, 备注：" . $remark . ')';
                    RecordModel::saveData($user_info['id'], '', '', $type, $details, '', 0, $obj);
                    action_log('transfer_add', 'money_transfer', $user_info['id'], UID, $details);
//                    $this->success('编辑成功', cookie('__forward__'));

                }else{

                    Db::rollback();
//                    $this->error($value.'编辑失败');
                    return [
                        'code'=>1,
                        'message'=>$value.'金额修改失败'
                    ];
                }
            }
        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return [
            'code'=>0,
            'message'=>'编辑成功'
        ];

    }

}

?>
