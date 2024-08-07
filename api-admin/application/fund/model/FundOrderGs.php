<?php
namespace app\fund\model;

use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundUserlevelAward as FundUserlevelawardModel;
use app\member\model\Member;
use app\money\model\Money;
use app\money\model\Record;
use think\Db;
use think\model;

class FundOrderGs extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //结算买入佣金
    public static function settlementBonus($id, $money, $order_type, $order_id)
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
                self::appendIncomeLog($bonus, $name = '', $code = '', $re_id, 0, 4, 1, $order_type, $order_id, $level, $rebate, $id);
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

    //合伙人补贴
    public static function recommendeDaward($id, $name, $order_id, $order_type)
    {

        $re_id = Member::where('id', $id)->where('is_buy', 1)->value('re_id');
        if (!$re_id) {
            return true;
        }
        $num = Member::where('re_id', $re_id)->where('is_buy', 1)->count();

        // 从数据库中获取区间值
        $numValues = FundUserlevelawardModel::order('num', 'asc')->select();

        // 初始化默认值
        $money = 0; // 默认采用第一档值

        // 逻辑判断区间
        foreach ($numValues as $numValue) {
            if ($num >= $numValue['num']) {
                $money = $numValue['ratio'];
                $type  = $numValue['num'];
            } else {
                break; //一旦找到大于$a的num，退出循环
            }
        }

        $fund_recommended_award = Db::name('fund_recommended_award')->where('uid', $re_id)->where('type', $type)->count();
        if (!$fund_recommended_award) {
            if ($money > 0) {

                $data                = [];
                $data['name']        = $name;
                $data['uid']         = $re_id;
                $data['money']       = $money;
                $data['fund_id']     = 0;
                $data['type']        = $type;
                $data['create_time'] = time();
                $user_balance        = Money::getMoney($re_id);
                $insertdata          = Db('fund_recommended_award')->insert($data);

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
//                Db('fund_income_log') -> insert($add_data);
                //$appendIncomeLog = self::appendIncomeLog($money, $name = '', $code = '', $re_id, $fund_id = '', 3, 1, $order_type, $order_id, '', '', $id);
                #echo "insertdata:".$appendIncomeLog;
                $info        = '直推：' . $num . '人，合伙补贴：' . $money . '元';
                $money       = $money * 100;
                $setIncMoney = Money::where('mid', $re_id)->setInc('account', $money);
                #  echo "setIncMoney:".$setIncMoney;
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

                return;
            }

        }

    }

    //写入资金记录
    public static function appendIncomeLog($money, $fund_name, $code, $uid, $fund_id = 0, $type, $status, $order_type, $order_id = '', $level = '', $rebate = '', $offline_uid = '')
    {
        $user_data                   = Member::where('id', $uid)->field('mobile,name,agent_far')->find();
        $agent_id                    = $user_data['agent_far'];//代理id
        $offline_account             = Member::where('id', $offline_uid)->value('mobile');
        $add_data                    = [];
        $add_data['name']            = $fund_name;//基金名称
        $add_data['code']            = $code;     //基金代码
        $add_data['money']           = $money;
        $add_data['uid']             = $uid; //获取佣金的账号ID
        $add_data['fund_id']         = 0;    //
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
        Db('fund_income_log')->insert($add_data);
    }

    /**
     * 优投待确认列表
     *
     * @param $page
     * @param $map
     * @param $order
     *
     * @return array
     */
    public static function getFollowList($page = 1, $map = [], $order = 'id desc')
    {
        $field = 'o.*,o.trader_id,o.create_time create_time_text
        , FROM_UNIXTIME(o.create_time, "%Y-%m-%d") as create_time_text
        , t.name trader_texta,t.min_money,t.max_money,t.divide_into,t.product_name,m.name as username,m.role_name';
        $list  = self::where($map)->field($field)
            ->alias('o')
            ->join('trader t', 't.id = o.trader_id')
            ->join('member m', 'm.id = o.uid')
            ->order($order)
            ->paginate()
            ->each(function ($iteam) {
                $iteam->initial_money = $iteam->money - $iteam->add_money;
            });
        if (!$list) {
            return [];
        }
        $res['list'] = $list;
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
        $field = '*,create_time, FROM_UNIXTIME(create_time, "%Y-%m-%d") as create_time_text, 
        FROM_UNIXTIME(confirm_time, "%Y-%m-%d") as confirm_time_text,
               FROM_UNIXTIME(settlement_time, "%Y-%m-%d") as settlement_time_text,
        FROM_UNIXTIME(fundendtime, "%Y-%m-%d") as fundendtime_time_text,confirm_time';
        $form  = self::where('id', $id)->field($field)->find();
        if ($form['settlement_time'] == 0) {
            $form['settlement_time_text'] = '未结算';
        }
        return $form ?: [];
    }

// 在 settlementFundgs 方法中使用 chunk
    public static function settlementFundgs($ids = null)
    {

        if ($ids) {
            return FundDaylineModel::where('id', 'in', $ids)->where('status', 2)->chunk(50, [__CLASS__, 'calculate']);
        } else {
            return FundDaylineModel::where('status', 2)->chunk(50, [__CLASS__, 'calculate']);
        }
    }

    /**
     * Desc : 结算分配处理函数
     * Date : 2024-07-31 02:44
     *
     * @param $res
     *
     * @throws \think\Exception
     */
    public function calculate($res)
    {

        try {
            // 开始事务
            Db::startTrans();
            printlog(count($res), "条数据待结算：", 'jiesuan');
            #结算逻辑：
            #1、买的时候需要扣除券商佣金（交金额的3%）、过户费（交金额的0.001%）、规费（交金额的0.002%
            #2、卖的时候需要扣除印花税（成交金额的0.1%）、过户费（成交金额的0.01%）、券商佣金（成交金额的3%)
            #3、扣除本次的佣金交易所得佣金
            foreach ($res as $value) {
                $uid          = $value['uid'];
                $user_balance = Money::getMoney($uid);
                printlog($value['order_sn'], '单号', 'jiesuan');
                // 第一步计算订单利润：卖出减去买入总金额-减去买入成本-卖出成本
                $sellprice_sum = $value['sell_price'] * $value['num'];
                $buyprice_sum  = $value['buy_price'] * $value['num'];
                printlog($sellprice_sum, '卖出总价', 'jiesuan');
                printlog($buyprice_sum, '买入总价', 'jiesuan');

                #印花税
                $stamp_duty = config('stamp_duty') / 10000;
                #过户费
                $transfer_fee = config('transfer_fee') / 1000;
                #券商佣金
                $commission = config('commission') / 10000;
                #规费
                $stock_trading_fees = config('stock_trading_fees') / 1000;
                if (config('buy_cost')) {
                    #买入成本=买入总价*券商佣金+买入总价*过户费+买入总价*规费
                    $buy_cost = $buyprice_sum * $commission + $buyprice_sum * $transfer_fee + $buyprice_sum * $stock_trading_fees;
                } else {
                    $buy_cost = 0;
                }

                printlog($buy_cost, '买入成本', 'jiesuan');
                if (config('sell_cost')) {
                    #卖出成本
                    $sell_cost = $sellprice_sum * $commission + $sellprice_sum * $stamp_duty + $sellprice_sum * $transfer_fee;
                } else {
                    $sell_cost = 0;
                }

                printlog($sell_cost, '卖出成本', 'jiesuan');
                $money = $sellprice_sum - $buyprice_sum - $buy_cost - $sell_cost;
                printlog($money, '扣除成本后', 'jiesuan');
                $money = round($money, 2);
                printlog($money, '取整', 'jiesuan');
                #计算讲师佣金
                printlog($value['commission_ratio'], '佣金比例', 'jiesuan');
                #讲师佣金
                $commission = max(0, $money * $value['commission_ratio'] / 100);
                printlog($commission, '讲师佣金', 'jiesuan');
                #平台佣金
                $system_commission = max(0, $money * config('system_commission') / 100);
                printlog($system_commission, '平台佣金', 'jiesuan');

                #打开注释的话就变成稳赚了
//            $money = max(0, $money - $commission);
                printlog($money, '最后的利润', 'jiesuan');

                if ($buyprice_sum == 0) {
                    continue;
                }
                #最后利润=买入总价-卖出总价-买入成本-卖出成本-讲师佣金-平台佣金
                #收益率=最后利润/买入总价*100%
                $rebate = (($money - $commission - $system_commission) / $buyprice_sum) * 100;
                printlog($rebate, '收益率', 'jiesuan');

                #第二步 扣除买卖成本 如果利润还大于0 在优先扣除讲师佣金
                printlog($money, '扣除买卖成本 如果利润还大于0 在优先扣除讲师佣金', 'jiesuan');
                if ($money > 0) {
                    $activity_account = $user_balance['activity_account'] / 100;
                    printlog($activity_account, '活动金余额', 'jiesuan');
                    #扣除佣金-优先获取活动金
                    if ($activity_account >= $commission) {
                        #活动金余额
                        $activity_account -= $commission;
                        #变化的活动金
                        $Variety_activity_account = $commission;

                    } else {
                        #变化的活动金
                        $Variety_activity_account = $activity_account;
                        #活动金余额消化完
                        $activity_account = 0;
                        #佣金减去剩余的活动，不够的金额在从利润扣
                        $money = $money - ($commission - $user_balance['activity_account'] / 100);
                    }

                } else {
                    $commission = 0;
                }
                #第二步 当利润还大于0时在扣 平台佣金
                printlog($money, '当利润还大于0时在扣 平台佣金', 'jiesuan');
                if ($money > 0) {
                    $activity_account = $user_balance['activity_account'] / 100;
                    printlog($activity_account, '活动金余额', 'jiesuan');
                    #扣除佣金-优先获取活动金
                    if ($activity_account >= $system_commission) {
                        #活动金余额
                        $activity_account -= $system_commission;
                        #变化的活动金
                        $Variety_activity_account = $system_commission;

                    } else {
                        #变化的活动金
                        $Variety_activity_account = $activity_account;
                        #活动金余额消化完
                        $activity_account = 0;
                        #佣金减去剩余的活动，不够的金额在从利润扣
                        $money = $money - ($system_commission - $user_balance['activity_account'] / 100);
                    }

                } else {
                    $system_commission = 0;
                }

                #第二步 跟新订单数据
                $FundDaylineModelupdate = FundDaylineModel::where(['id' => $value['id']])->update([
                    'buy_cost'          => $buy_cost,
                    'sell_cost'         => $sell_cost,
                    'profit'            => $money,
                    'commission'        => $commission,
                    'system_commission' => $system_commission,
                    'status'            => 3
                ]);

                #更新收益到用户表
                $order_id        = $value['order_id'];
                $orderinfo       = self::where('id', $order_id)->find();
                $balance         = (float)$orderinfo['balance'];
                $order_type      = $orderinfo['order_type'];
                $map             = [];
                $map['order_id'] = $order_id;
                $map['uid']      = $uid;
                printlog($map, '更新基金收益表', 'jiesuan');
                printlog($money, '金额', 'jiesuan');
                #第三步 更新收益到跟买收益表
                $moneySign = $money < 0 ? 'dec' : 'inc';
                printlog($moneySign, 'moneySign', 'jiesuan');
                printlog(abs($money), 'abs($money)', 'jiesuan');
                $FundIncomeupdate = FundIncome::where($map)
                    ->{$moneySign}('total_money', abs($money))
                    ->{$moneySign}('income_money', abs($money))
                    ->update(); //更新到收益表

                #更改订单状态
                $update_data                         = [];
                $update_data['status']               = 1;
                $update_data['balance']              = $balance + $money;
                $update_data['is_sell']              = 1;
                $update_data['settlement_last_time'] = time();
                $update_data['settlement_days']      = $orderinfo['settlement_days'] + 1;//结算次数
                #第四步 跟新订单数据
                $order_update_data   = self::where('id', $order_id)->update($update_data);
                $data                = [];
                $data['name']        = $value['stockname'];
                $data['code']        = $value['stockcode'];
                $data['money']       = $money;
                $data['rebate']      = $rebate;//收益率
                $data['uid']         = $uid;
                $data['fund_id']     = 0;
                $data['balance']     = $balance + $money;
                $data['old_balance'] = $balance;
                $data['order_id']    = $order_id;
                $data['order_type']  = $order_type;
                $data['status']      = 1;
                $data['create_time'] = time();
                #第五步 插入收益记录
                $fund_income_log = Db('fund_income_log')->insert($data);

                if ($Variety_activity_account > 0) {
                    #扣除活动金
                    Money::where('mid', $uid)->setDec('activity_account', $Variety_activity_account * 100);
                    $user_balance = Money::getMoney($uid);
                    $account      = $user_balance['account'];
                    $info         = '跟投结算,活动金' . $Variety_activity_account . '元，抵扣讲师佣金';
                    $obj          = ['affect' => 0, 'account' => $account, 'affect_activity' => $Variety_activity_account * 100, 'activity_account' => $activity_account * 100, 'sn' => ''];
                    Record::saveData($uid, $money * 100, $account, 98, $info, '', '', $obj);
                }

                printlog($fund_income_log, '第五步 插入收益记录', 'jiesuan');
                printlog($FundIncomeupdate, '第三步 更新收益到跟买收益表', 'jiesuan');
                printlog($FundDaylineModelupdate, '第二步 跟新订单数据', 'jiesuan');
                printlog($order_update_data, '第四步 跟新订单数据', 'jiesuan');
                printlog(date("Y-m-d", $orderinfo['fundendtime']), '结束时间', 'jiesuan');

                #是今天结束的直接就结算合约
                if ($orderinfo['fundendtime'] <= strtotime(date("Y-m-d 23:59:59"))) {
                    printlog($value['id'], '去执行jiesu', 'jiesuan');
                    processing_settlement($value['order_id'], $orderinfo);
                }

            }
            // 处理完一个块的数据后，手动关闭数据库连接

            #不知道为什么用使用事务之后就不成功

            if (
                $fund_income_log &&
                $FundIncomeupdate &&
                $FundDaylineModelupdate &&
                $order_update_data) {

                Db::commit();
                return true;
            } else {
                // 如果有任何操作失败，回滚事务
                Db::rollback();
                return false;
            }
        } catch (Exception $e) {
            // 捕获异常并回滚事务
            Db::rollback();
            return false;
        }
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
    public static function getList($page = 1, $map = [], $order = 'o.id desc')
    {
        $field     = '*,fund_id yesterday_income';
        $page_size = 10;
        $list      = self::alias('o')
            ->leftJoin('fund_income f', 'f.order_id = o.id')
            ->leftJoin('trader t', 't.id = o.trader_id')
            ->field('o.*,f.fund_id yesterday_income,f.total_money,f.income_money,f.recharge_money,t.name as trader_name')
            ->where($map)->order($order)->paginate(['page' => $page, 'list_rows' => $page_size]);
        #echo self::getLastSql();
        $total_hold_money       = 0;//总投资金额(余额)
        $total_yesterday_income = 0;//总的昨日收益
        $total_hold_income      = 0;//总的收益(累计收益)
        $total_amount_available = 0;//可用总金额
        if (!$list) {
            return [];
        }
        foreach ($list as $key => $value) {
            $hold_money             = $value['money'];
            $total_hold_money       += $hold_money;
            $yesterday_income       = $value['yesterday_income'];
            $total_yesterday_income += $yesterday_income;
            $hold_income            = $value['income_money'];
            $total_hold_income      += $hold_income;
            $orderbalance           = orderbalance($value['id'], 'FundOrderGs');
            $total_amount_available += $orderbalance;
//      if ($value['recharge_money'] == 0) {
//        $earning_rate = 0; //收益率 = 收益金额/总买入金额
//      } else {
//        $earning_rate = $hold_income / $value['recharge_money'] * 100; //收益率 = 收益金额/总买入金额
//      }
//      $earning_rate               = round($earning_rate, 2);

            $earning_rate               = round((($value['money'] + $value['balance']) / $value['money'] - 1) * 100, 2);
            $list[$key]['hold_money']   = $hold_money;                        //金额
            $list[$key]['hold_income']  = convertToTenThousand($hold_income); //持有收益
            $list[$key]['earning_rate'] = $earning_rate;                      //收益率
        }
        $statistics                           = [];
        $statistics['total_hold_money']       = round($total_hold_money, 2);      //总投资金额
        $statistics['total_yesterday_income'] = round($total_yesterday_income, 2);//总的昨日收益
        $statistics['total_amount_available'] = $total_amount_available;          //可用总金额
        $statistics['total_hold_income']      = round($total_hold_income, 2);     //总的收益
//        $statistics['total_income'] = round($total_income,2);//总的累计收益(累计收益)
        $res['statistics'] = $statistics;
        $res['list']       = $list;
        return $res ?: [];
    }

    /**
     * 结算
     * @return void
     */
    public static function settlementorders($id = '',$special=false)
    {
        $specificDate = date('Y-m-d');

        // 获取次日的时间戳
        $nextDayTimeStamp = strtotime($specificDate . ' +1 day');

        if ($id) {
            #只有未持仓状态才可以结算
            $where         = [
                ['id', '=', $id],
                ['status', '=', 1],

            ];
            if($special==false){
                $where['fundendtime']      =$nextDayTimeStamp;
            }


            $orderinfolist = self::where($where)->find();

            if (!$orderinfolist) {
                return ['code' => false, 'msg' => '只有未持仓状态且已到合约结束日期才可以结算'];
            }
            processing_settlement($id, $orderinfolist);
        } else {
            $orderinfolist = self::where([['status', '=', 1], ['fundendtime', '<=', $nextDayTimeStamp]])->select();
            if ($orderinfolist) {
                echo '有' . count($orderinfolist) . "条数据\r\n";
                foreach ($orderinfolist as $v) {

                    echo processing_settlement($v['id'], $v);
                    echo "\r\n";
                }
            }

        }
        return ['code' => true, 'msg' => "结算成功"];

    }

    /*
        *
        * 生成当日记录待处理待买记录
        */

    /**
     * Desc : 生成当日记录待处理待买记录
     * Date : 2024-07-23 03:25
     *
     * @param $data
     *
     * @return array
     */
    public static function autobuildgsrecord($data = [])
    {
        $uid = $date = '';

        if (isset($data['uid'])) {
            $uid = $data['uid'];
        }
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $wherelist = [['status', 'in', "1,6"],
                      ['confirm_time', '<', strtotime(date($data['buytime'] . " 23:59:59"))],
                      ['fundendtime', '>', strtotime(date($data['buytime'] . " 00:00:00"))]
        ];

        if (isset($data['trader']) && !empty($data['trader'])) {
            $wherelist[] = ['trader_id', '=', $data['trader']];
        }
        if ($uid) {
            $wherelist[] = ['uid', '=', $uid];
            $list = self::where($wherelist)->select();
        } else {
            $list = self::where($wherelist)->select();
        }

        if (empty($date)) {
            $date = date("Y-m-d");
        }
        $fundDayline = [];
        foreach ($list as $v) {

            //排查可能用户通过其他方式生成的情况
            $where    = [
                ['uid', '=', $v['uid']],
                ['trader_id', '=', $v['trader_id']],
                ['order_sn', '=', $v['order_sn']],
                ["date", '=', date("Y-m-d")],

            ];
            $infodata = FundDaylineModel::where($where)->find();
            //如果有则跳过
            if ($infodata) {
                printlog($where, "已经存在数据", 'buy');
                continue;
            }
            $data          = [
                'order_id'         => $v['id'],
                'commission_ratio' => $v['commission'],
                'trader_id'        => $v['trader_id'],
                'order_sn'         => $v['order_sn'],
                'date'             => $date,
                'create_time'      => time(),
                'uid'              => $v['uid']
            ];
            $data['id']    = FundDaylineModel::insertGetId($data);
            $fundDayline[] = $data;
        }
        return $fundDayline;
    }

    /*
      *
      * 生成当日记录待处理待买记录-针对特殊用户
      */
    public static function buildgsrecordspecial($mobile)
    {
        $list = self::where([['status', 'in', "1,6"], ['fundendtime', '>', time()], ['mobile', '=', $mobile]])->select();
        printlog($list, "需要更新列表：", 'buy');
        $ids = [];
        foreach ($list as $k => $v) {
            #如果还有未买的订单不在生成
            $infodata = FundDaylineModel::where('order_sn', $v['order_sn'])->where("status", 0)->where('date', date("Y-m-d"))->find();
            if ($infodata) {
                $ids[$k] = $infodata['id'];
            }

        }
        return $ids;
    }
}

?>
