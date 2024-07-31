<?php
namespace app\fund\model;

use think\Db;
use think\model;

class Fund extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;

  public static function getList($page = 1, $map = [], $order = 'id desc')
  {
    $field     = '*,type type_text,index_type index_type_text,cate_id cate_text,establish_date run_time_text,video_id video_url,content content_text,id income,id count';
    $page_size = 10;
    $list      = self::where($map)->field($field)->order($order)->paginate(['page' => $page, 'list_rows' => $page_size]);
    return empty($list) ? $list : $list->toArray();
  }

  /**
   * 基金详情
   *
   * @param $id
   *
   * @return Fund|array
   */
  public static function getDetailData($id)
  {

    $field = '*,type type_text,index_type index_type_text,cate_id cate_text,video_id video_url,content content_text,id count';
    $form  = self::where('id', $id)->field($field)->find();

    $today_date = date('Y-m-d');
    $map        = [];

    $map[]          = ['fund_id', '=', $id];
    $day_start_time = strtotime(date('Y-m-d'));//0点之后
    $end_time       = $day_start_time + 15 * 3600;
    if (time() >= $end_time) {
      $map[] = ['fund_date', 'elt', $today_date];
    } else {
      $map[] = ['fund_date', 'lt', $today_date];
    }
    $list_three_days = Db('fund_line')->where($map)->order('fund_date desc')->limit(3)->column('price');
    $last_three_days = $last_seven_days = $last_month_days = 0;
    foreach ($list_three_days as $v1) {
      $last_three_days += $v1;
    }
    $last_three_days = round($last_three_days, 2);
    $list_seven_days = Db('fund_line')->where($map)->order('fund_date desc')->limit(7)->column('price');
    foreach ($list_seven_days as $v2) {
      $last_seven_days += $v2;
    }
    $last_seven_days = round($last_seven_days, 2);
    $list_month_days = Db('fund_line')->where($map)->order('fund_date desc')->limit(30)->column('price');
    foreach ($list_month_days as $v3) {
      $last_month_days += $v3;
    }
    $last_month_days         = round($last_month_days, 2);
    $form['last_three_days'] = $last_three_days ?: 0.00;
    $form['last_seven_days'] = $last_seven_days ?: 0.00;
    $form['last_month_days'] = $last_month_days ?: 0.00;
    return $form ?: [];
  }

  //更新基金数据
  public static function updateFundData($fund_id)
  {
    $max_retreat                     = FundOrder::where('fund_id', $fund_id)->where('type', 2)->where('status', 1)->count();
    $use_num                         = FundOrder::where('fund_id', $fund_id)->where('type', 1)->where('status', 1)->count();
    $recharge                        = FundOrder::where('fund_id', $fund_id)->where('type', 1)->sum('money');                                                 //买入金额
    $reality_recharge                = FundOrder::where('fund_id', $fund_id)->where('type', 1)->where('status', 1)->where('balance', '>', 0)->sum('balance'); //买入金额
    $sell_money                      = FundOrder::where('fund_id', $fund_id)->where('type', 2)->where('status', 1)->sum('money');
    $update_data['max_retreat']      = $max_retreat ?: 0;     //回撤人数
    $update_data['use_num']          = $use_num ?: 0;         //使用人数
    $update_data['recharge']         = $recharge ?: 0;        //买入总金额
    $update_data['reality_recharge'] = $reality_recharge ?: 0;//实际参与收益金额
    $update_data['sell_money']       = $sell_money ?: 0;      //实际卖出金额
    self::where('id', $fund_id)->update($update_data);
  }

  public static function getCountAttr($value)
  {
    $max_retreat                     = FundOrder::where('fund_id', $value)->where('type', 2)->where('status', 1)->group('uid')->count();
    $use_num                         = FundOrder::where('fund_id', $value)->where('type', 1)->where('status', 1)->group('uid')->count();
    $recharge                        = FundOrder::where('fund_id', $value)->where('type', 1)->sum('money');                                                 //买入金额
    $reality_recharge                = FundOrder::where('fund_id', $value)->where('type', 1)->where('status', 1)->where('balance', '>', 0)->sum('balance'); //买入金额
    $sell_money                      = FundOrder::where('fund_id', $value)->where('type', 2)->where('status', 1)->sum('money');
    $update_data['max_retreat']      = $max_retreat ?: 0;     //回撤人数
    $update_data['use_num']          = $use_num ?: 0;         //使用人数
    $update_data['recharge']         = $recharge ?: 0;        //买入总金额
    $update_data['reality_recharge'] = $reality_recharge ?: 0;//实际参与收益金额
    $update_data['sell_money']       = $sell_money ?: 0;      //实际卖出金额
    self::where('id', $value)->update($update_data);
  }

  public static function getIncomeAttr($value)
  {
    $today_date     = date('Y-m-d'); //今天
    $map            = [];
    $map[]          = ['fund_id', '=', $value];
    $day_start_time = strtotime(date('Y-m-d'));//0点之后
    $end_time       = $day_start_time + 15 * 3600;
    if (time() >= $end_time) { //超过下午3点才显示当天的收益率,否则显示昨天的收益率

      $map[]  = ['fund_date', '=', $today_date];
      $income = Db('fund_line')->where($map)->value('price');
    } else {
      $map[]  = ['fund_date', 'lt', $today_date];
      $income = Db('fund_line')->where($map)->order('fund_date desc')->value('price');
    }
    $income = round($income, 2);
    return $income ?: 0.00;
  }

  public static function getRuntimeTextAttr($value)
  {
    $end_time   = time();
    $begin_time = strtotime($value);
    $run_time   = timediff($begin_time, $end_time);
    return $run_time;
  }

  public static function getTypeTextAttr($value)
  {
    $name = Db::name('invest_type')->where('id', $value)->value('name');
    return $name ?: '';
  }

  public static function getIndexTypeTextAttr($value)
  {
    $name = Db::name('index_type')->where('id', $value)->value('name');
    return $name ?: '';
  }

  public static function getCateTextAttr($value)
  {
    $name = Db::name('fund_cate')->where('id', $value)->value('name');
    return $name ?: '';
  }

  public static function getVideoUrlAttr($value)
  {
    $path   = Db::name('admin_attachment')->where('id', $value)->value('path');
    $domain = request()->domain();
    return $path ? $domain . '/' . $path : '';
  }

  public static function getContentTextAttr($value)
  {
    $content = $value ? json_decode($value, true) : [];
    return $content;
  }
}

?>
