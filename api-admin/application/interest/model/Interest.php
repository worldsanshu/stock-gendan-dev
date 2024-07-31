<?php
namespace app\interest\model;

use app\member\model\Bank as BankModel;
use app\money\model\Record;
use think\Db;
use think\model;
use app\common\service\UserService;

class Interest extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;

  public static function getList($page = 1, $map = [], $order = 'id desc')
  {
    $field = 'i.*,m.mobile,m.name as username,c.name as cname';
    $list  = self::where($map)
      ->field($field)
      ->alias('i')
      ->join('member m', 'm.id = i.uid')
      ->join('interest_config c', 'c.id = i.interest_record_id')
      ->order($order)
      ->paginate();

    $res['list'] = $list;
    return $res ?: [];
  }



//  收益明细
  public static function getInterestList($page = 1, $map = [], $order = 'id desc')
  {
    $field = 'interest, FROM_UNIXTIME(rebate_time, "%Y-%m-%d") as rebate_time_text';
    $list  = self::where($map)
        ->field($field)
        ->order($order)
        ->paginate();

    $res['list'] = $list;
    return $res ?: [];
  }

//  金额操作
  public static function saveData($parameter)
  {

    $data['uid'] = $parameter['mid'];
    $money = $parameter['money'] * 100;  //金额
    $data['money'] = $parameter['money'];  //扣除手续费用之后的金额
    $data['order_number'] = 'yeb' . generate_rand_str(10, 3);
    $data['create_time'] = time();
    $data['buy_time'] = time();
    $data['interest_record_id'] = $parameter['interest_record_id'];

    $record = new Record();

    Db::startTrans();

    $money_info = Db('money')->where('mid', $data['uid'])->lock(true)->find();
    $basic = Db('interest_basic')->find();

    $account = bcsub($money_info['account'], $money);
    $up_money['freeze_balance_bao'] = bcadd($money_info['freeze_balance_bao'], $money);
    $up_money['account'] = $account;
    try {
//      记录订单
      $res1 = self::create($data);

      $info = $basic['name']."买入" . ($data['money']) . "元,购买单号：" . $data['order_number'];
      $obj = ['affect' => -$money, 'account' => $account, 'affect_activity' => 0, 'activity_account' => 0, 'sn' => $data['order_number']];
      $res2 = $record->saveData($data['uid'], -$money, $account, 104, $info, '', '', $obj);

//      $info = "余额宝冻结金" . ($data['money']) . "元";
//      $obj = ['affect' => $money, 'account' => $up_money['freeze_balance_bao'], 'affect_activity' => 0, 'activity_account' => 0, 'sn' => $data['order_number']];
//      $res3 = $record->saveData($data['uid'], -$money, $up_money['freeze_balance_bao'], 105, $info, '', '', $obj);

//      更新钱包
      $result = Db('money')->where('mid', $data['uid'])->update($up_money);
      if ($result) {
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


  public static function setDailyRebate()
  {
    // 获取昨天的日期（00:00:00的时间戳）
    $yesterday = strtotime(date('Y-m-d', strtotime('-1 day')));

// 将时间戳转换为日期格式（只包含日期部分，时间默认为00:00:00）
    $yesterdayDate = date('Y-m-d', $yesterday);

// 使用ThinkPHP的查询构造器查询昨天的数据
    $list = Db::name('interest')
        ->where('status', 1)
        ->whereTime('create_time', '>=', $yesterdayDate . ' 00:00:00') // 从今天开始时间
        ->whereTime('create_time', '<=', $yesterdayDate . ' 23:59:59') // 到今天结束时间的前一秒（其实这里可以简化为昨天的任意时间之后，但为了明确性保留）
        ->select();
    $num = 0;
    foreach ($list as $k => $v) {
      if(self::settlementRebate($v['id'])){
        $num++;
      }
    }

    var_dump($num);
  }

  public static function settlementRebate($id)
  {
    Db::startTrans();

    try {
      $get_one = Interest::where('id', $id)->find();
        //        计算利息
        $interest = self::setInterest($get_one['interest_record_id'],$get_one['money']);
        $get_one['interest'] =$interest['interest'];
        $get_one['interest_rate'] =$interest['interest_rate'];

        $record = new Record();
        $money_info = Db::name('money')->where('mid', $get_one['uid'])->find();
        $new_money = $get_one['money'] * 100; //钱包以分为单位
        $up_money['freeze_balance_bao'] = bcsub($money_info['freeze_balance_bao'], $new_money); //扣除冻结
        $new_interest = $interest['interest'] * 100;
        $up_money['account'] = bcadd($money_info['account'], $new_money); //加本金
        $up_money['account'] = bcadd($up_money['account'], $new_interest); //加返利

        Db('money')->where('mid', $get_one['uid'])->update($up_money);

        $info = "返还本金：" . $get_one['order_number'];
        $affect = $get_one['money']  * 100;
        $type = 106;
        $account = bcadd($money_info['account'], $affect);
        $obj = ['affect' => $affect, 'account' => $account, 'affect_activity' => 0, 'activity_account' => 0, 'sn' => $get_one['order_number']];
        $record->saveData($get_one['uid'], $affect, $account, $type, $info, '', '', $obj);

        $info1 = "返还利息：" . $get_one['order_number'];
        $affect1 = $interest['interest'] * 100;
        $type1 = 107;
        $account1 = bcadd($account, $affect1);
        $obj1 = ['affect' => $affect1, 'account' => $account1, 'affect_activity' => 0, 'activity_account' => 0, 'sn' => $get_one['order_number']];
        $record->saveData($get_one['uid'], $affect1, $account1, $type1, $info1, '', '', $obj1);
//        $result = Interest::where('id', $id)->update(['status' => 2, 'rebate_time' => time(), 'interest' => $interest]);
      $result = Interest::where('id', $id)->update(['status' => 2, 'rebate_time' => time(), 'interest' => $get_one['interest'], 'interest_rate' => $get_one['interest_rate']]);

      if ($result) {
          Db::commit();
          return 1;
        } else {
          Db::rollback();
          return 0;
        }
    } catch (\Exception $e) {
      Db::rollback();
      return $e->getMessage();
    }
  }

  //    计算利息
  public function setInterest($id,$money)
  {
    $get_config = Db::name('interest_daily_float')
        ->where('interest_record_id',$id)
        ->where('date',date('Y-m-d',strtotime('-1 day')))->find();
//        $get_config = Db::name('interest_config')->->find();
//    获取固定比例
    $get_one = Db::name('interest_config')->select();
    if($id == 1){
//      七日年化收益
//      设置固定利率
      if($get_one[0]['interest_rate'] > 0){
        $result['interest_rate'] = $get_one[0]['interest_rate'];
        $interest = $money * $result['interest_rate'] /100;
        $oneRemission = sprintf("%.2f", $interest);// 保留两位小数
//        $result['interest_rate'] = $get_config['interest_rate'];
        $result['interest'] = $oneRemission;
      }
//      else{
//        $result['interest_rate'] = $get_config['interest_rate'];
//      }
      else if ($get_config && isset($get_config['interest_rate'])) {
        $result['interest_rate'] = $get_config['interest_rate'];
        //        如果设置了固定比例

//        存入金额 * 利率 *7 /365
//        七日年化收益 = 200000 × 1.93% × 7 / 365 = 73.34元
//        日收益 = 73.34元 / 7 = 10.48元
//        $interest = $money * ($get_config['interest_rate'] / 100) * 7 / 365;
//        $interest = $interest / 7;
//        客服要求
//        存入金额  * 利率 /100
        $interest = $money * $result['interest_rate'] /100;
        $oneRemission = sprintf("%.2f", $interest);// 保留两位小数
//        $result['interest_rate'] = $get_config['interest_rate'];
        $result['interest'] = $oneRemission;
//        return $result;
      } else {
        // 处理$get_config不存在或'interest_rate'键不存在的情况
        // 你可以返回一个错误码、错误消息或默认值等
        $result['interest_rate'] = 0;
        $result['interest'] = 0;
      }
      return $result; // 或者抛出异常等

    }else{
      if($get_one[1]['interest_rate'] > 0){
        $result['interest_rate'] = $get_one[1]['interest_rate'];
        $interest = $money * $result['interest_rate'] /100;
        $oneRemission = sprintf("%.2f", $interest);// 保留两位小数
        $result['interest'] = $oneRemission;
      }
//      else{
//        $result['interest_rate'] = $get_config['interest_rate'];
//      }
//      万份收益
      else if ($get_config && isset($get_config['interest_rate'])) {
        $result['interest_rate'] = $get_config['interest_rate'];
//        如果设置了固定比例

//        存入金额 / 10000 * 利率
//        $interest = $money / 10000 * $get_config['interest_rate'];
//        客服要求
//        存入金额  * 利率 /100
        $interest = $money * $result['interest_rate'] /100;
        $oneRemission = sprintf("%.2f", $interest);// 保留两位小数
        $result['interest'] = $oneRemission;
//        return $result;
      } else {
        // 处理$get_config不存在或'interest_rate'键不存在的情况
        // 你可以返回一个错误码、错误消息或默认值等
        $result['interest_rate'] = 0;
        $result['interest'] = 0;
      }
      return $result; // 或者抛出异常等

    }

//    单位：元） $principal = 1000; // 万份收益（即投资1万元一天所获得的收益，单位：元） $perWanProfit = 0.6;

// 计算每份的收益（单位：元/份） $profitPerUnit = $perWanProfit / 10000;

// 计算日收益（单位：元） $dailyProfit = $profitPerUnit * $principal;

// 计算七日总收益（单位：元） $sevenDayProfit = $dailyProfit * 7;

// 计算七日年化收益 $sevenDayAnnualizedRate = ($sevenDayProfit / $principal) * (365 / 7) * 100;

  }


  public static function setDailyFloat()
  {
    $get_config = Db::name('interest_config')->select();

//    如果有设置七日年化收益率--------------------------------------------------
    if($get_config[0]){
      $interestRecordId = $get_config[0]['id'];
//      $interestRate = // 这里设置您的interest_rate值
      $currentTime = time(); // 当前时间戳
      $startDate = date('Y-m-d', strtotime('-1 day')); // 当天日期
      $rand_q = $get_config[0]['daily_floating_range'];

// 生成一周内的日期数组
      $dates = [];
      for ($i = 0; $i < 7; $i++) {
        $dates[] = date('Y-m-d', strtotime("-$i day", strtotime($startDate)));
      }

// 循环插入数据
      $q = 0;
      foreach ($dates as $date) {
        $get_one = Db::name('interest_daily_float')->where('interest_record_id',$interestRecordId)->where('date',$date)->find();
        if(!$get_one){
          $add= Db::name('interest_daily_float')->insert(
              [
                  'interest_record_id' => $interestRecordId,
                  'interest_rate' => self::daily_floating_range($rand_q), // 这里应该是一个合理的interest_rate值
                  'date' => $date,
                  'create_time' => $currentTime, // 或者您可以为每条记录设置不同的create_time
              ]
          );
          if($add){
            $q++;
          }
        }

      }
      var_dump($q);
    }

    //    如果有设置万份收益--------------------------------------------------
    if($get_config[0]){
      $interestRecordId = $get_config[1]['id'];
//      $interestRate = // 这里设置您的interest_rate值
      $currentTime = time(); // 当前时间戳
      $startDate = date('Y-m-d', strtotime('-1 day')); // 当天日期
      $rand_w = $get_config[1]['daily_floating_range'];
// 生成一周内的日期数组
      $dates = [];
      for ($i = 0; $i < 7; $i++) {
        $dates[] = date('Y-m-d', strtotime("-$i day", strtotime($startDate)));
      }

// 循环插入数据
      $w = 0;
      foreach ($dates as $date) {

        $get_one1 = Db::name('interest_daily_float')->where('interest_record_id',$interestRecordId)->where('date',$date)->find();
        if(!$get_one1){
          $add1 = Db::name('interest_daily_float')->insert(
              [
                  'interest_record_id' => $interestRecordId,
                  'interest_rate' => self::daily_floating_range($rand_w), // 这里应该是一个合理的interest_rate值
                  'date' => $date,
                  'create_time' => $currentTime, // 或者您可以为每条记录设置不同的create_time
              ]
          );
          if($add1){
            $w++;
          }
        }
      }
      var_dump($w);
    }

  }

  public static function setYesterDailyFloat()
  {
    $get_config = Db::name('interest_config')->select();

//    如果有设置七日年化收益率--------------------------------------------------
    if($get_config[0]){
      $interestRecordId = $get_config[0]['id'];
      $yesterday = date('Y-m-d', strtotime('-1 day'));
      $rand_q = $get_config[0]['daily_floating_range'];


// 循环插入数据
      $q = 0;
        $get_one = Db::name('interest_daily_float')->where('interest_record_id',$interestRecordId)->where('date',$yesterday)->find();
        if(!$get_one){
          $add= Db::name('interest_daily_float')->insert(
              [
                  'interest_record_id' => $interestRecordId,
                  'interest_rate' => $get_config[0]['interest_rate'], // 这里应该是一个合理的interest_rate值
//                  'interest_rate' => self::daily_floating_range($rand_q), // 这里应该是一个合理的interest_rate值
                  'date' => $yesterday,
                  'create_time' => time(), // 或者您可以为每条记录设置不同的create_time
              ]
          );
          if($add){
            $q++;
          }
        }


      var_dump($q);
    }

    //    如果有设置万份收益--------------------------------------------------
    if($get_config[1]){
      $interestRecordId = $get_config[1]['id'];
      $yesterday = date('Y-m-d', strtotime('-1 day'));
      $rand_w = $get_config[1]['daily_floating_range'];

      $w = 0;

      $get_one1 = Db::name('interest_daily_float')->where('interest_record_id',$interestRecordId)->where('date',$yesterday)->find();
      if(!$get_one1){
        $add1 = Db::name('interest_daily_float')->insert(
            [
                'interest_record_id' => $interestRecordId,
                'interest_rate' => $get_config[1]['interest_rate'],
//                'interest_rate' => self::daily_floating_range($rand_w), // 这里应该是一个合理的interest_rate值
                'date' => $yesterday,
                'create_time' => time(), // 或者您可以为每条记录设置不同的create_time
            ]
        );
        if($add1){
          $w++;
        }
      }

      var_dump($w);
    }

  }


  public static function daily_floating_range($date)
  {
    $range_str = $date;
    list($min, $max) = explode(',', $range_str);
    if($min == 0){
      $min = 0.1;
    }
// 将范围的最小值和最大值转换为浮点数
    $min = (float)$min;
    $max = (float)$max;

// 计算整数范围内的步长（这里我们选择100作为一个例子，代表两位小数的精度）
    $precision = 100; // 两位小数的精度
    $range = ($max - $min) * $precision;

// 生成一个在整数范围内的随机数
    $random_int = mt_rand(0, (int)$range);

// 将整数随机数转换为浮点数，并缩放到原始范围
    $rand_q = $min + ($random_int / $precision);

// 确保浮点数有两位小数（尽管这步可能是可选的，因为PHP在输出时通常会自动处理）
    $rand_q = number_format($rand_q, 2, '.', '');

// 输出结果
    return $rand_q;
  }


}

?>
