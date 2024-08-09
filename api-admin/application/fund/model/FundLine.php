<?php
namespace app\fund\model;

use think\Db;
use think\model;

class FundLine extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;

  //获取日期列表
  public function getDateList($count = 0, $node_price = 0, $node_date, $current_price = 0, $current_date, $fund_id = 0)
  {
    if ($count == 1) {
      return;
    }
    $difference      = $current_price - $node_price;//差值
    $trade_date_list = Db::name('stock_trade_date')->order('date asc')->where("`date`>'" . $node_date . "' and `date`<'" . $current_date . "'")->column('date');
//        echo Db::name('stock_trade_date') -> getLastSql();exit;
    $num               = count($trade_date_list);
    $average           = $difference / $num;//平均值
    $average           = round($average, 2);
    $float_value       = 0.02;
    $float_value_array = ['-', '+'];
    $rand              = rand(0, 1);
    foreach ($trade_date_list as $k => $v) {
      $price = $node_price + $average * ($k + 1);
      $price . $float_value_array[$rand] = $float_value;
      if ($price >= $current_price && $difference > 0) {
        $price = $current_price - $average / 2;
      }
      if ($price <= $current_price && $difference < 0) {
        $price = $current_price - $average / 2;
      }
      $data['fund_id']   = $fund_id;
      $data['price']     = $price;
      $data['fund_date'] = $v;
      $array[]           = $data;
    }
    if ($array) {
      Db::name('fund_line')->insertAll($array);
    }
  }

  //批量添加收益率
  public static function addFundData($new_fund_date, $price_array, $fund_id)
  {
    $num             = count($price_array);
    $trade_date_list = Db::name('stock_trade_date')->order('date asc')->where("`date`>'" . $new_fund_date . "'")->limit($num)->column('date');
    foreach ($trade_date_list as $k => $v) {
      $data['fund_id']   = $fund_id;
      $data['price']     = $price_array[$k] ?? 0;
      $data['fund_date'] = $v;
      $array[]           = $data;
    }
    if ($array) {
      $res                      = Db::name('fund_line')->insertAll($array);
      $fund_date                = Db::name('fund_line')->order('fund_date desc')->value('fund_date');
      $update_data              = [];
      $update_data['fund_date'] = $fund_date;
      Db::name('fund')->where('id', $fund_id)->update($update_data);
      return $res;
    }
  }
}

?>
