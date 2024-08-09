<?php
namespace app\fund\model;

use app\fund\model\Fund as FundModel;
use app\fund\model\FundDayline as FundDaylineModel;
use app\member\model\Member;
use app\money\model\Money;
use app\money\model\Record;
use think\Db;
use think\model;

class FundOrder extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;

  public static function getList($page = 1, $map = [], $order = 'id desc')
  {
    $field = '*,create_time create_time_text,confirm_time confirm_time_text,income_time income_time_text,account_time account_time_text';

    $list = self::where($map)->field($field)->order($order)->paginate();

    $res['list'] = $list;
    return $res ?: [];
  }

  public function getTraderAttr($value)
  {
    $name = Trader::where('id', $value)->value('name');
    return $name ?: '--';
  }

  public function getUsernameAttr($value)
  {
    $name = Member::where('id', $value)->value('name');
    return $name ?: '--';
  }

  /**
   * 订单列表和订单收益汇总计算
   *
   * @param $page
   * @param $map
   * @param $order
   *
   * @return array
   */
  public static function getList2($page = 1, $map = [], $order = 'o.id desc')
  {
    $field     = '*,fund_id yesterday_income';
    $page_size = 10;
    $list      = self::alias('o')
      ->leftJoin('fund_income f', 'f.order_id = o.id')
      ->field('o.*,f.fund_id yesterday_income,f.total_money,f.income_money,f.recharge_money')
      ->where($map)->order($order)->paginate(['page' => $page, 'list_rows' => $page_size]);
    #echo self::getLastSql();
    $total_hold_money       = 0;//总的金额(余额)
    $total_yesterday_income = 0;//总的昨日收益
    $total_hold_income      = 0;//总的收益(累计收益)
    if (!$list) {
      return [];
    }
    foreach ($list as $key => $value) {
      $hold_money             = $value['total_money'];
      $total_hold_money       += $hold_money;
      $yesterday_income       = $value['yesterday_income'];
      $total_yesterday_income += $yesterday_income;
      $hold_income            = $value['income_money'];
      $total_hold_income      += $hold_income;
      if ($value['recharge_money'] == 0) {
        $earning_rate = 0; //收益率 = 收益金额/总买入金额
      } else {
        $earning_rate = $hold_income / $value['recharge_money'] * 100; //收益率 = 收益金额/总买入金额
      }
      $earning_rate               = round($earning_rate, 2);
      $list[$key]['hold_money']   = $hold_money;  //金额
      $list[$key]['hold_income']  = $hold_income; //持有收益
      $list[$key]['earning_rate'] = $earning_rate;//收益率
    }
    $statistics                           = [];
    $statistics['total_hold_money']       = round($total_hold_money, 2);      //总的金额
    $statistics['total_yesterday_income'] = round($total_yesterday_income, 2);//总的昨日收益
    $statistics['total_hold_income']      = round($total_hold_income, 2);     //总的收益
//        $statistics['total_income'] = round($total_income,2);//总的累计收益(累计收益)
    $res['statistics'] = $statistics;
    $res['list']       = $list;
    return $res ?: [];
  }

  /**
   * 优投详情
   *
   * @param $id
   *
   * @return FundOrderGs|array
   */
  public static function getDetailData($id)
  {
    $field = '*,create_time create_time_text,confirm_time confirm_time_text,income_time income_time_text,account_time account_time_text';
    $form  = self::where('id', $id)->field($field)->find();

    return $form ?: [];
  }

  /**
   * 订单列表和订单收益汇总计算
   *
   * @param $page
   * @param $map
   * @param $order
   *
   * @return array
   */
  public static function getList2bak($page = 1, $map = [], $order = 'id desc')
  {
    $field                  = '*,fund_id yesterday_income';
    $page_size              = 10;
    $list                   = FundIncome::where($map)->field($field)->order($order)->paginate(['page' => $page, 'list_rows' => $page_size]);
    $total_hold_money       = 0;//总的金额(余额)
    $total_yesterday_income = 0;//总的昨日收益
    $total_hold_income      = 0;//总的收益(累计收益)
    if (!$list) {
      return [];
    }
    foreach ($list as $key => $value) {
      $hold_money             = $value['total_money'];
      $total_hold_money       += $hold_money;
      $yesterday_income       = $value['yesterday_income'];
      $total_yesterday_income += $yesterday_income;
      $hold_income            = $value['income_money'];
      $total_hold_income      += $hold_income;
      if ($value['recharge_money'] == 0) {
        $earning_rate = 0; //收益率 = 收益金额/总买入金额
      } else {
        $earning_rate = $hold_income / $value['recharge_money'] * 100; //收益率 = 收益金额/总买入金额
      }
      $earning_rate               = round($earning_rate, 2);
      $list[$key]['hold_money']   = $hold_money;  //金额
      $list[$key]['hold_income']  = $hold_income; //持有收益
      $list[$key]['earning_rate'] = $earning_rate;//收益率
    }
    $statistics                           = [];
    $statistics['total_hold_money']       = round($total_hold_money, 2);      //总的金额
    $statistics['total_yesterday_income'] = round($total_yesterday_income, 2);//总的昨日收益
    $statistics['total_hold_income']      = round($total_hold_income, 2);     //总的收益
//        $statistics['total_income'] = round($total_income,2);//总的累计收益(累计收益)
    $res['statistics'] = $statistics;
    $res['list']       = $list;
    return $res ?: [];
  }

  public static function getSellTextAttr($value, $data)
  {
    return $value > 0 ? '部分卖出' : '已卖完';
  }

  public static function getCreateTimeTextAttr($value)
  {
    return $value ? date('Y-m-d H:i:s', $value) : '';
  }

  public static function getIncomeTimeTextAttr($value)
  {
    return $value ? date('Y-m-d H:i', $value) : '';
  }

  public static function getConfirmTimeTextAttr($value)
  {
    return $value ? date('Y-m-d H:i', $value) : '';
  }

  public static function getAccountTimeTextAttr($value)
  {
    return $value ? date('Y-m-d H:i', $value) : '';
  }

  //确认买入的状态
  public static function autoUpdateBuyStatus($list = [], $type = 1)
  {
    $map = [];

    $map[] = ['status', '=', 0];
    $map[] = ['type', '=', 1];
    if ($type == 1) { //为1时按规定时间去确认
      $map[] = ['confirm_time', 'elt', time()];
      $list  = self::where($map)->select();
    }
    if (!$list) {
      return;
    }
    foreach ($list as $key => $value) {
      $id                                   = $value['id'];
      $uid                                  = $value['uid'];
      $fund_id                              = $value['fund_id'];
      $money                                = $value['money'];
      $map                                  = [];
      $map['fund_id']                       = $fund_id;
      $map['uid']                           = $uid;
      $fund_income_data                     = FundIncome::where($map)->find();
      $total_money                          = $fund_income_data['total_money'];
      $recharge_money                       = $fund_income_data['recharge_money'];
      $update_income_data['total_money']    = $total_money + $money;
      $update_income_data['recharge_money'] = $recharge_money + $money;
      FundIncome::where($map)->update($update_income_data); //更新到收益表
      $update_data           = [];
      $update_data['status'] = 1;
      self::where('id', $id)->update($update_data);
      FundModel::updateFundData($fund_id);
      FundModel::where('id', $fund_id)->inc('use_num')->inc('follow_funds', $money)->update();
    }
    return;
  }

  //确认卖出的状态 （第二天9点半到账）
  public static function autoUpdateSellStatus($list = [], $type = 1)
  {
    $map = [];

    $map[] = ['status', '=', 0];
    $map[] = ['type', '=', 2];
    if ($type == 1) { //为1时按规定时间去确认
      $map[] = ['account_time', 'elt', time()];
      $list  = self::where($map)->select(); //查询没到账的数据
    }
    if (!$list) {
      return;
    }
    foreach ($list as $key => $value) {
      $id      = $value['id'];
      $uid     = $value['uid'];
      $fund_id = $value['fund_id'];
      $money   = $value['money'];
      $name    = $value['name'];
      $money   = $money * 100;//转为分
      Db::name('money')->where('mid', $uid)->setInc('account', $money);
      $update_data           = [];
      $update_data['status'] = 1;
      self::where('id', $id)->update($update_data);
      $user_balance = Money::getMoney($uid);
      $account      = $user_balance['account'];
      $info         = '卖出基金：' . $name . ',到账金额：' . $value['money'] . '元';
//            Record::saveData($uid, $money, $account, 89, $info);
      FundModel::updateFundData($fund_id);
      $obj = ['affect' => $money, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
      Record::saveData($uid, $money, $account, 89, $info, '', '', $obj);
    }
    return;
  }

  //结算基金（每天三点后结算）--新股模式
  public static function settlementFund()
  {

    $type           = input('type', '');
    $day_start_time = strtotime(date('Y-m-d'));//当天的开始时间戳
    $map            = [['status', 'eq', 1], ['type', 'eq', 1], ['balance', 'gt', 0], ['balance', 'gt', 0]];
//        $map['order_type'] = ['eq',1]; //订单类型（1=普通订单，2=优投，3=vip优投）
    if ($type != 1) {
      $map[] = ['settlement_time', 'lt', $day_start_time];
    }
    $list = self::where($map)->select();
    if (!$list) {
      return;
    }
    $day_date = date('Y-m-d');
    foreach ($list as $key => $value) {
      $id         = $value['id'];
      $uid        = $value['uid'];
      $fund_id    = $value['fund_id'];
      $balance    = $value['balance'];//余额
      $order_type = $value['order_type'];
      $price      = Db::name('fund_line')->where('fund_id', $fund_id)->where('fund_date', $day_date)->value('price');//当天的收益率
      $rebate     = $price;
      $money      = $balance * $price / 100;
      $money      = round($money, 2);//收益金额
      if ($money == 0) {
        continue;
      }
      $map              = [];
      $map['fund_id']   = $fund_id;
      $map['uid']       = $uid;
      $fund_income_data = FundIncome::where($map)->find();
      $total_money      = $fund_income_data['total_money'];
      $income_money     = $fund_income_data['income_money'];
//            if($value['order_type'] != 3){
      $update_income_data['total_money']  = $total_money + $money;
      $update_income_data['income_money'] = $income_money + $money;
      FundIncome::where($map)->update($update_income_data); //更新到收益表
//            }
//            FundIncome::where($map) -> inc('total_money',$money) -> inc('income_money',$money) -> update(); //更新到收益表
      $settlement_days                = self::where('id', $id)->value('settlement_days');
      $update_data                    = [];
      $update_data['settlement_time'] = time();//结算时间
      $update_data['balance']         = $balance + $money;
      $update_data['settlement_days'] = $settlement_days + 1;//结算次数
      self::where('id', $id)->update($update_data);
//            $data = [];
//            $data['name'] = $value['name'];
//            $data['code'] = $value['code'];
//            $data['money'] = $money;
//            $data['uid'] = $uid;
//            $data['fund_id'] = $fund_id;
//            $data['balance'] = $balance + $money;
//            $data['old_balance'] = $balance;
//            $data['order_id'] = $id;
//            $data['order_type'] = $order_type;
//            $data['type'] = 1;
//            $data['status'] = 1;
//            $data['create_time'] = time();
//            Db::name('fund_income_log') -> insert($data);
      self::appendIncomeLog($money, $value['name'], $value['code'], $uid, $fund_id, 1, 1, $order_type, $id, '', $rebate, $uid);
//            $user_balance = Money::getMoney($uid);
//            $account = $user_balance['account'];
//            $info = '基金：'.$value['name'] .',收益金额：'.$money.'元';
//            Record::saveData($uid, $money*100,$account, 88, $info);
    }
    return;
  }

  //获取优投记录
  public static function getFollowList($page = 1, $map = [], $order = 'id desc')
  {
    $field     = 'o.*,o.trader_id,o.create_time create_time_text, t.name trader_texta,t.min_money,t.max_money,t.divide_into';
    $page_size = 20;
    $list      = self::where($map)->field($field)
      ->alias('o')
      ->join('trader t', 't.id = o.trader_id')
//            ->field('u.*, t.name,t.min_money,t.max_money,t.divide_into')
      ->order($order)
      ->paginate(['page' => $page, 'list_rows' => $page_size]);
    if (!$list) {
      return [];
    }
    $res['list'] = $list;
    return $res ?: [];
  }

  public static function getTraderTextAttr($value)
  {
    if ($value) {
      return Trader::where('id', $value)->value('name');
    }
  }

  //结算买入佣金
  public static function settlementBonus($id, $money, $fund_id, $name, $code, $order_type, $order_id)
  {
    $proportion = config('proportion');
    $array      = explode('|', $proportion);
    $sql        = 'SELECT id,re_id,is_buy from lmq_member where FIND_IN_SET(id,queryChildrenAreaInfo1(' . $id . '))  and `id` != ' . $id . ' ORDER BY `id` DESC LIMIT 0,3';
    $list       = Db::query($sql);
    foreach ($list as $key => $value) {
      $rebate = $array[$key] ?? 0;
      $bonus  = $money * $rebate / 100;
      $bonus  = round($bonus, 2);
      $re_id  = $value['id'];
      $level  = ($key + 1) . '级';
      if (!$re_id || $value['is_buy'] != 1) {
        continue;
      }
      $user_balance = Money::getMoney($re_id);
      if ($bonus > 0) {
        self::appendIncomeLog($bonus, $name, $code, $re_id, $fund_id, 4, 1, $order_type, $order_id, $level, $rebate, $id);
        $info  = '分销佣金：收益金额：' . $bonus . '元';
        $bonus = $bonus * 100;
        Money::where('mid', $re_id)->setInc('account', $bonus);
        $account = $user_balance['account'] + $bonus;
//                Record::saveData($re_id, $bonus,$account, 90, $info);
        $obj = ['affect' => $bonus, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
        Record::saveData($re_id, $bonus, $account, 90, $info, '', '', $obj);
      }
    }
  }

  //写入奖金记录
  public static function appendIncomeLog($money, $fund_name, $code, $uid, $fund_id, $type, $status, $order_type, $order_id = '', $level = '', $rebate = '', $offline_uid = '')
  {
    $user_data                   = Member::where('id', $uid)->field('mobile,name,agent_far')->find();
    $agent_id                    = $user_data['agent_far'];//代理id
    $offline_account             = Member::where('id', $offline_uid)->value('mobile');
    $add_data                    = [];
    $add_data['name']            = $fund_name;//基金名称
    $add_data['code']            = $code;     //基金代码
    $add_data['money']           = $money;
    $add_data['uid']             = $uid;     //获取佣金的账号ID
    $add_data['fund_id']         = $fund_id; //
    $add_data['order_id']        = $order_id;
    $add_data['status']          = $status;
    $add_data['type']            = $type;//奖金类型 1=交易日收益，2=周工资，3= 合伙人补贴，4= 分销奖励
    $add_data['order_type']      = $order_type;
    $add_data['level']           = $level;
    $add_data['rebate']          = $rebate;     //分佣比例
    $add_data['offline_uid']     = $offline_uid;//下级账号UID
    $add_data['offline_account'] = $offline_account;
    $add_data['mobile']          = $user_data['mobile'];
    $add_data['username']        = $user_data['name'];
    $add_data['agent_id']        = $agent_id;
    $add_data['create_time']     = time();
    Db::name('fund_income_log')->insert($add_data);
  }

  //合伙人补贴
  public static function recommendeDaward($id, $name, $fund_id, $order_id, $code, $order_type)
  {
    $partner_subsidy   = config('partner_subsidy');
    $array             = explode('|', $partner_subsidy);
    $partner_num       = config('partner_num');
    $partner_num_array = explode('|', $partner_num);

    $re_id = Member::where('id', $id)->where('is_buy', 1)->value('re_id');
    if (!$re_id) {
      return;
    }
    $num   = Member::where('re_id', $re_id)->where('is_buy', 1)->count();
    $money = 0;
    if ($num == $partner_num_array[0]) {
      $money = $array[0];
      $type  = 1;
    } elseif ($num == $partner_num_array[1]) {
      $money = $array[1];
      $type  = 2;
    } elseif ($num == $partner_num_array[2]) {
      $money = $array[2];
      $type  = 3;
    } elseif ($num == $partner_num_array[3]) {
      $money = $array[3];
      $type  = 4;
    } elseif ($num == $partner_num_array[4]) {
      $money = $array[4];
      $type  = 5;
    }
    if (!Db::name('fund_recommended_award')->where('uid', $re_id)->where('type', $type)->count()) {
      if ($money > 0) {
        $data                = [];
        $data['name']        = $name;
        $data['uid']         = $re_id;
        $data['money']       = $money;
        $data['fund_id']     = $fund_id;
        $data['type']        = $type;
        $data['create_time'] = time();
        $user_balance        = Money::getMoney($re_id);
        Db::name('fund_recommended_award')->insert($data);
//                $add_data = [];
//                $add_data['name'] = $name;
//                $add_data['code'] = $code;
//                $add_data['money'] = $money;
//                $add_data['uid'] = $re_id;
//                $add_data['fund_id'] = $fund_id;
//                $add_data['order_id'] = $order_id;
//                $add_data['status'] = 1;
//                $add_data['create_time'] = time();
//                $add_data['order_type'] = $order_type;
//                $add_data['type'] = 3;
//                Db::name('fund_income_log') -> insert($add_data);
        self::appendIncomeLog($money, $name, $code, $re_id, $fund_id, 3, 1, $order_type, $order_id, '', '', $id);
        $info  = '直推：' . $num . '人，合伙补贴：' . $money . '元';
        $money = $money * 100;
        Money::where('mid', $re_id)->setInc('account', $money);
        $account = $user_balance['account'] + $money;
//                Record::saveData($re_id, $money,$account, 91, $info);
        $obj = ['affect' => $money, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
        Record::saveData($re_id, $money, $account, 91, $info, '', '', $obj);

        //添加一条合伙人记录
        $addData = [
          'uid'         => $re_id,
          'price'       => $money / 100,
          'type'        => 3,
          'desc'        => $info,
          'custom_desc' => "[]",
          'info_id'     => 0,
          'is_settle'   => 1,
          'settle_time' => time()
        ];
        (new FundSalaryLog())->save($addData);
      }
    }
  }

  //结算周工资
  public static function weekAward()
  {
    $type           = input('type', '');
    $day_start_time = strtotime(date('Y-m-d'));//当天的开始时间戳
    $map            = [];

    $map[]     = ['status', '=', 1];
    $map[]     = ['type', '=', 1];
    $map[]     = ['balance', 'gt', 0];
    $map[]     = ['order_type', 'neq', 1];//只结算优投的订单  1=普通订单，2=优投，3=vip优投
    $map[]     = ['is_sell', '=', 0];
    $week_time = time() - 7 * 24 * 3600;//一周结算一次
    if ($type != 1) {

      $map[] = ['week_settlement_time', 'elt', $week_time];

    }
    $list = self::where($map)->select();
    if (!$list) {
      return;
    }
    foreach ($list as $key => $value) {
      $id      = $value['id'];
      $uid     = $value['uid'];
      $fund_id = $value['fund_id'];
      $balance = $value['balance'];//余额
      $money   = $value['money'];  //买入金额
      if ($balance <= $money) {
        continue;
      }
      $commission = $value['commission'] / 100;//手续费
      //收益
      $profit                              = ($balance - $money) * (1 - $commission);
      $update_data                         = [];
      $update_data['week_settlement_time'] = $day_start_time;//结算时间
      self::where('id', $id)->update($update_data);
      self::grantWeekBonus($uid, $profit, $value['name'], $value['code'], $fund_id, $value['id'], $value['order_type']);
    }
    return;
  }

  //发放周工资
  public function grantWeekBonus($id, $money, $name, $code, $fund_id, $order_id, $order_type)
  {
    $sql             = 'SELECT id,re_id,level from lmq_member where FIND_IN_SET(id,queryChildrenAreaInfo1(' . $id . ')) and `is_buy`=1 and `id` !=' . $id . ' ORDER BY `id` DESC';
    $list            = Db::query($sql);//查找上级
    $week_proportion = config('week_proportion');
    $array           = explode('|', $week_proportion);
    foreach ($list as $key => $value) {
      $level = $value['level'];
      if ($level < 1) {
        continue;
      }
      $level_text   = ($key + 1) . '级';
      $rebate       = $array[$level - 1] ?? 0;//比例
      $bonus        = $money * $rebate / 100;
      $bonus        = round($bonus, 2);
      $re_id        = $value['id'];
      $user_balance = Money::getMoney($re_id);
      if ($bonus > 0) {
//                $info = '周工资：收益金额：'.$bonus.'元';
//                $bonus = $bonus*100;
//                Money::where('mid',$re_id) -> setInc('account',$bonus);
//                $account = $user_balance['account']+$bonus;
//                Record::saveData($re_id, $bonus,$account, 92, $info);
//                $data = [];
//                $data['name'] = $name;
//                $data['code'] = $code;
//                $data['money'] = $bonus;
//                $data['uid'] = $re_id;
//                $data['fund_id'] = $fund_id;
//                $data['order_id'] = $order_id;
//                $data['order_type'] = $order_type;
//                $data['type'] = 2; //奖金类型 1=交易日收益，2=周工资，3= 合伙人补贴，4= 分销奖励
//                $data['status'] = 0;
//                $data['create_time'] = time();
//                Db::name('fund_income_log') -> insert($data);
        self::appendIncomeLog($bonus, $name, $code, $re_id, $fund_id, 2, 0, $order_type, $order_id, $level_text, $rebate, $id);
      }
    }
  }

  //自动卖出vip优投
  public static function autoSell()
  {
    $map               = [];
    $map['status']     = 1;
    $map['type']       = 1;
    $map['is_sell']    = 0;
    $map['order_type'] = 3; //只卖出vip优投的订单  1=普通订单，2=优投，3=vip优投
    $list              = self::where($map)->select();
    $test              = input('test', '');
    if (!$list) {
      return;
    }
    foreach ($list as $key => $value) {
      if ($value['cycle'] != $value['settlement_days'] && $test != 1) {
        continue;
      }//结算周期到的订单卖出
      $id         = $value['id'];
      $uid        = $value['uid'];
      $fund_id    = $value['fund_id'];
      $profit     = $value['balance'] - $value['money'];//盈利部分
      $name       = $value['name'];
      $commission = $value['commission'] / 100;//手续费
      if ($profit > 0) {
        $profit = $profit * (1 - $commission); //实际收益
        $bonus  = $value['money'] + $profit;
        self::grantWeekBonus($uid, $profit, $value['name'], $value['code'], $fund_id, $value['id'], $value['order_type']);
      } else {
        $bonus = $value['balance'];
      }
      $user                          = Member::where('id', $uid)->find();
      $account_time                  = (24 + 9.5) * 3600 + strtotime(date('Y-m-d'));//第二天9点半到账
      $order_sn                      = $uid . date("YmdHis");
      $order_data['name']            = $name;
      $order_data['code']            = $value['code'];
      $order_data['uid']             = $uid;
      $order_data['type']            = 2;
      $order_data['money']           = $bonus;
      $order_data['email']           = $user['email'];
      $order_data['mobile']          = $user['mobile'];
      $order_data['fund_id']         = $fund_id;
      $order_data['order_sn']        = $order_sn;
      $order_data['status']          = 0;
      $order_data['account_time']    = $account_time;
      $order_data['create_time']     = time();
      $order_data['order_type']      = $value['order_type'];
      $order_data['commission']      = $value['commission'];
      $order_data['cycle']           = $value['cycle'];
      $order_data['settlement_days'] = $value['settlement_days'];
      self::create($order_data);
      $update_data            = [];
      $update_data['balance'] = 0;
      $update_data['is_sell'] = 1;
      self::where('id', $id)->update($update_data);
      FundIncome::where('uid', $uid)->where('fund_id', $fund_id)->setDec('total_money', $value['balance']);
//                $user_balance = Money::getMoney($uid);
//                $account = $user_balance['account'];
//                $info = '卖出VIP优投基金：'.$name.',到账金额：'.$bonus.'元';
//                Record::saveData($uid, $bonus*100, $account, 89, $info);
    }
    return;
  }
}

?>
