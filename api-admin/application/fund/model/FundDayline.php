<?php
namespace app\fund\model;

use think\model;
use app\common\service\UserService;

class FundDayline extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;

  public static function getList($page = 1, $map = [], $order = 'id desc')
  {
    $field = 'o.*,m.mobile,m.name as username,fo.order_sn sn, fo.uid,fo.order_type,t.name trader_texta,m.role_name';
    $list  = self::where($map)
      ->field($field)
      ->alias('o')
      ->join('fund_order_gs fo', 'fo.id = o.order_id')
      ->join('member m', 'm.id = fo.uid')
      ->join('trader t', 't.id = fo.trader_id')
      ->order($order)
      ->paginate();

    foreach ($list as $v) {
      $v['fy'] = round($v['num'] * $v['buy_price'], 2);
    }
    $res['list'] = $list;
    return $res ?: [];
  }
}

?>
