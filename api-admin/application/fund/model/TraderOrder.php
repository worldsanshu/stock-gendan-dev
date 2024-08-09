<?php
namespace app\fund\model;

use app\fund\model\FundDayline as FundDaylineModel;
use think\Db;
use think\model;
use app\member\model\Member as MemberModel;
class TraderOrder extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;

  public function getHeadimgurlTextAttr($value)
  {
    $path   = Db::name('admin_attachment')->where('id', $value)->value('path');
    $domain = request()->domain();
    return $path ? $domain . '/' . $path : '';
  }

  /**
   * Desc : 获取用户等级的仓位数据

   * Date : 2024-07-19 00:15
   *
   * @param $uid 用户id
   * @param $order_id 是否传入订单id
   *
   * @return FundDayline|array|\PDOStatement|string|Model|null
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\exception\DbException
   */
  public static function getcw($uid='', $order_id='')
  {
    if($order_id){
      //获取该用户这个订单的信息余额；
      $FundDaylineinfo = FundDaylineModel::where(['f.id' => $order_id])
          ->alias('f')
          ->join('member m', 'f.uid = m.id')
          ->join('fund_userlevel l', 'l.level = m.level')
          ->field('f.*,l.max_position_ratio,l.min_position_ratio')
          ->find();
    }else{
    //获取该用户这个订单的信息余额；
    $FundDaylineinfo = MemberModel::where(['m.id' => $uid])
        ->alias('m')
        ->join('fund_userlevel l', 'l.level = m.level')
        ->field('l.max_position_ratio,l.min_position_ratio')
        ->find();}
    return $FundDaylineinfo;
  }
}

?>
