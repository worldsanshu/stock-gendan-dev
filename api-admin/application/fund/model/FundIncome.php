<?php
namespace app\fund\model;

use think\model;

class FundIncome extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;

  public function getYesterdayIncomeAttr($value, $data)
  {
    $yesterday_start_time = strtotime(date('Y-m-d', strtotime("-1 day")));
    $yesterday_end_time   = $yesterday_start_time + 24 * 3600;
    $uid                  = $data['uid'];
    $where                = 'uid = ' . $uid . ' and fund_id =' . $value . ' and create_time >=' . $yesterday_start_time . ' and create_time<' . $yesterday_end_time;
    $money                = Db('fund_income_log')->where($where)->sum('money');
    $money                = round($money, 2);
    return $money;
  }

  public function getNoConfirmMoneyAttr($value, $data)
  {
    $uid            = $data['uid'];
    $map            = [];
    $map['uid']     = $uid;
    $map['fund_id'] = $value;
    $map['status']  = 0;
    $map['type']    = 1;
    $money          = FundOrder::where($map)->sum('money');
    return $money;
  }
}

?>
