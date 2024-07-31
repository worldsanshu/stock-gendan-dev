<?php
namespace app\market\home;

use think\Db;

class Trade extends Common
{
    public function index()
    {
        return $this->view->fetch('index/index');
    }

    protected function initialize()
    {
        parent::initialize();
        if (!MID) {
        }
    }

    /**
     * 购买股票
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function buy()
    {
        if (!get_trading_time('','time')) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_1')//非交易时间
            ));
        }
        $req = request();
        $sub_id = intval(input('id'));
        printlog($sub_id, "code", 'buy');
        $code = input('code');
        $codecount = $count = input('count');
        $price = input('price');
        $model = input('model');
        $count = abs($count);
        $list_res = Db::name('stock_list')->where(array(
          'code' => $code
        ))->find();
        $stockinfo = z_market($code);
        //限制股票，688开头股票只有超过50w配资金额才可以有权限购买
        //判断科创板  和  创业板
        $isBuyrestriction = Buyrestriction($code, $sub_id, $list_res, $stockinfo);
        printlog($code, "code", 'buy');
        printlog($isBuyrestriction, "是否有配资限制2", 'buy');
        switch ($isBuyrestriction) {
            case 0:
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_16')//'不存在的子账号'
                ));
            case -1:
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_2') //'该子账户操盘金不足50W无法买入'
                ));
            case -2:
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_3')//'该股票上市未满五个交易，无法交易'
                ));
            case -3:
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_35')//'该子账户操盘金不足10W无法买入'
                ));
            case -4:
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_36')//'该子账户操盘金不足10W无法买入'
                ));
            case -5:
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_10')//'涨跌幅过大，禁止购买！'
                ));
            default:
                break;
        }
        //end
        printlog($list_res, "model", 'buy');
        if ($list_res['status'] == 0) {
            return json(array(
              'status' => 0,
              'message' => $code . lang('msg_4')// '为禁买股票'
            ));
        }
        $etype = \app\stock\model\Borrow::market_type($code);
        // if ($etype == 5) {
        //     return json(array(
        //         'status' => 0,
        //         'message' => lang('msg_5')// '您只能购买沪深A股股票'
        //     ));
        // }
        if ($count % 100 != 0) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_6')// '购买量错误,必须是100的整数倍'
            ));
        }
        if (empty($stockinfo)) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_7')//'系统错误请联系管理员处理'
            ));
        }
        if (intval($stockinfo['yesterday_price']) == 0) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_8')// '请检查该股是否退市'
            ));
        }
        if (intval($stockinfo['open_price']) == 0) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_9') //'该股今日还没开盘'
            ));
        }
        if (config("risk_of_increase") !== 0 && config("risk_of_increase") <= abs($stockinfo['changeRatio'])) {
            // if (0.080000000000000002 <= abs($stockinfo['yesterday_price'] - $stockinfo['current_price']) / $stockinfo['yesterday_price']) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_10')//'涨跌幅过大，禁止购买！'
            ));
        }
        if ($model == 1 && config("risk_of_decline") / 100 <= abs($stockinfo['yesterday_price'] - $price) / $stockinfo['yesterday_price']) {
            // if ($model == 1 && 0.080000000000000002 <= abs($stockinfo['yesterday_price'] - $price) / $stockinfo['yesterday_price']) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_11') // '委托价格超过风控限制，禁止购买！'
            ));
        }
        if (intval($stockinfo['sell_one_price']) <= 0) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_12') //'系统错误，请稍后再试！'
            ));
        }
        //"model":"2"现价
        //"model":"1"限价
        //	$newdate=	aliyun_market($code);
        if ($price >= $stockinfo['sell_one_price']) {
            $model = 2;
        }
        printlog($model, "model", 'buy');
        //model 为1并且价格不为空 是限价，model为2并价格为空是市价
        $trade_money = 0;
        if (!empty($price) && $model == 1) {
            $trade_money = $count * $price;
        } else if ($model == 2) {
            $price = 0;
            $arr[1] = $stockinfo['sell_one_amount'] * 100;
            $arr[2] = $stockinfo['sell_two_amount'] * 100;
            $arr[3] = $stockinfo['sell_three_amount'] * 100;
            $arr[4] = $stockinfo['sell_four_amount'] * 100;
            $arr[5] = $stockinfo['sell_five_amount'] * 100;
            $p_arr[1] = $stockinfo['sell_one_price'];
            $p_arr[2] = $stockinfo['sell_two_price'];
            $p_arr[3] = $stockinfo['sell_three_price'];
            $p_arr[4] = $stockinfo['sell_four_price'];
            $p_arr[5] = $stockinfo['sell_five_price'];
            $tmd = 0;
            printlog($arr, "arr", 'buy');
            foreach ($arr as $key => $v) {
                $tmd = $tmd + $v;
                printlog($count, "count", 'buy');
                printlog($tmd, "tmd", 'buy');
                if ($count <= $tmd) {
                    $sum_money = 0;
                    $sum_count = $count;
                    for ($i = 1; $i < $key; $i++) {
                        $sum_money += $arr[$i] * $p_arr[$i];
                        $sum_count -= $arr[$i];
                    }
                    $trade_money = $sum_money + $sum_count * $p_arr[$key];
                    $price = round($trade_money / $count, 2);
                    $trade_money = $count * $price;
                    break;
                }
                printlog($key, "key", 'buy');
                if ($key == 5) {
                    return json(array(
                      'status' => 0,
                      'message' => lang('msg_13') // '卖量不足，无法成交'
                    ));
                }
            }
        } else {
            return json(array(
              'status' => 0,
              'message' => lang('msg_14')// '参数错误'
            ));
        }
        if ($trade_money <= 0) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_15')//'购买量或网络错误'
            ));
        }
        // 加了一个容错方式
        // 判断 实时价格 的上下幅度是否超过 委托价格 的15%
        $a_current_price = $stockinfo['current_price']; // 实时价格
        printlog($stockinfo, "买-实时价格", "buy-sell");
        printlog($a_current_price, "买-实时价格", "buy-sell");
        $b_trust_price = $price;  // 委托价格
        printlog($b_trust_price, "买-委托价格", "buy-sell");
        // 计算 $b_trust_price 的15%的范围
        $threshold = $b_trust_price * 0.2;
        printlog($threshold, "计算 b_trust_price 的20%的范围", "buy-sell");
        if ($a_current_price < $b_trust_price - $threshold || $a_current_price > $b_trust_price + $threshold) {
            // 超过了15%的范围
            printlog('超过了20%的范围', "判断", "buy-sell");
            // return json(array(
            //     'status' => 0,
            //     'message' => '价格震荡期建议稍后再试'// '价格异常'
            // ));
        }
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($sub_id);
        printlog($res, "res", 'buy');
        if (empty($res['account_id']) || $res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_16')//'不存在的子账号'
            ));
        }
        $pos_sum = Db::name('stock_position')->where(array(
          'sub_id' => $sub_id
        ))->where(array(
          'gupiao_code' => $code
        ))->where(array(
          'buying' => 0
        ))->sum('stock_count');
        if ($list_res['quota'] != 0 && $list_res['quota'] < $pos_sum * $stockinfo['current_price'] + $trade_money) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_17')//'这只股票在平台上的购买量超过了平台单支股票最大购买限额'
            ));
        }
        printlog($res['uid'], "uid", 'buy');
        if ($res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18')//'登录超时请重新登录'
            ));
        }
        $moneymodel = new \app\market\model\SubAccountMoney();
        $moneyinfo = $moneymodel->get_account_money($sub_id);
        if ($moneyinfo['avail'] < $trade_money * 100) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_19')// '购买资金不足'
            ));
        }
        printlog($moneyinfo, "购买资金不足", 'buy');
        $bs_res = Db::name('stock_borrow')->where(array(
          'stock_subaccount_id' => $sub_id
        ))->find();
        if (empty($bs_res)) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_20')//'没有对应的配资'
            ));
        }
        printlog($bs_res, "没有对应的配资", 'buy');
        $risk = new \app\market\model\StockSubAccountRisk();
        $risk_res = $risk->get_risk($sub_id);
        if ($risk_res['prohibit_open'] == 0) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_21')//'您被禁止开新仓，请联系管理员咨询原因。'
            ));
        }
        printlog($risk_res, "您被禁止开新仓", 'buy');
        if ($bs_res['end_time'] <= time()) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_22')//'配资已经到期，不允许买入。'
            ));
        }
        printlog($bs_res, "配资已经到期", 'buy');
        $position = new \app\market\model\Position();
        //$position_res = $position->get_position($sub_id);
        $position_res = Db::name('stock_position')->where(['sub_id' => $sub_id])->where(['buying' => 0])->select();
        $sum = 0;
        if (1 <= count($position_res)) {
            $code_b = '';
            $counts = array();
            foreach ($position_res as $k => $v) {
                $code_b = $code_b . $v['gupiao_code'] . ',';
                $counts[$v['gupiao_code']] = $v['stock_count'];
            }
            $code_b = substr($code_b, 0, -1);
            $p_res = z_market_bat($code_b);
            foreach ($p_res as $key => $vv) {
                $sum += $counts[$vv['code']] * $vv['current_price'];
            }
        }
        $binfo = Db::name('stock_borrow')->where(array(
          'stock_subaccount_id' => $sub_id
        ))->find();
        $type_arr = array(1, 2, 3, 5);
        if (in_array($binfo['type'], $type_arr)) {
            // 方式二：预警线(配资金额+保证金*预警线比例）
            if ($moneyinfo['stock_subaccount_id'] == "376") {
                // $losswarn = ($sum * 100 + money_convert($moneyinfo['avail']) + money_convert($moneyinfo['freeze_amount'])) - (money_convert($moneyinfo['borrow_money']) + money_convert($moneyinfo['deposit_money']) * $risk_res['loss_warn'] / 100);
                //实际持仓市值+可用余额+委托买入金额大于预警线
                $where = array(
                  "sub_id" => $sub_id,
                  "flag2" => "买入委托",
                  "status" => "已委托",
                );
                $market_freeze_amount_array2 = Db::name('stock_trust')->where($where)->field('trust_price,gupiao_code,trust_count')->select();
                //在委托中的金额
                $market_freeze_amount_buy = 0;
                if (!empty($market_freeze_amount_array2)) {
                    foreach ($market_freeze_amount_array2 as $value) {
                        $market_freeze_amount_buy += $value['trust_price'] * $value['trust_count'];
                    }
                }
                $losswarn = $sum * 100 + money_convert($moneyinfo['avail']) + $market_freeze_amount_buy;
                $losswarn = $losswarn - (money_convert($moneyinfo['borrow_money']) + money_convert($moneyinfo['deposit_money']) * $risk_res['loss_warn'] / 100);
                if ($losswarn <= 0) {
                    return json(array(
                      'status' => 0,
                      'message' => lang('msg_23') //'您的亏损已达预警线，系统禁止开新仓。'
                    ));
                }
            }
            //方式一： $p_rate =保证金*预警线/100+保证金-市值*100-余额-冻结金额
            $p_rate = $moneyinfo['deposit_money'] * $risk_res['loss_warn'] / 100 + $moneyinfo['borrow_money'] - $sum * 100 - $moneyinfo['avail'] - $moneyinfo['freeze_amount'];
            if (0 <= $p_rate) {
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_24')//'您的亏损已达预警线，系统禁止开新仓。'
                ));
            }
        }
        if ($risk_res['position'] / 100 < $trade_money * 100 / ($sum * 100 + $moneyinfo['avail'] + $moneyinfo['stock_addmoney'])) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_25')// '您的购买量超过了单股最大持仓比例。'
            ));
        }
        $commission = $trade_money * $moneyinfo['commission_scale'] / 10000;
        if ($commission < $moneyinfo['min_commission'] / 100) {
            $commission = $moneyinfo['min_commission'] / 100;
        }
        //判断股票类型(这里区分股票类型来扣除过户费，暂时不做限制)
//        if ($etype == 2) {
//            $Transfer = round($trade_money / 50000, 2);
//            if ($Transfer < config('transfer_fee')) {
//                $Transfer = config('transfer_fee');
//            }
//        } else {
//            $Transfer = 0;
//        }
        $Transfer = round($trade_money / 50000, 2);
        if ($Transfer < config('transfer_fee')) {
            $Transfer = config('transfer_fee');
        }
        //交易金额+过户费+佣金+规费
        $fees = round($trade_money * config('fees'), 2);//规费
        $effect_money = $trade_money + $Transfer + $commission + $fees;
        $broker = $submodel->get_broker($res['account_id']);
        Db::startTrans();
        if ($broker['broker'] === -1) {
            $type = 1;
        } else {
            $type = 3;
        }
        if ($model == 1) {
            $type = 3;
        }
        #子账号资金使用log
        $ret = $moneymodel->up_moneylog($sub_id, $effect_money * 100, $type, '', '', $code, $codecount);
        if (!$ret) {
            Db::rollback();
            return json(array(
              'status' => 0,
              'message' => lang('msg_26')//'交易失败!'
            ));
        }
        printlog($ret, "交易失败1", 'buy');
        $lid = $broker['lid'];
        $user = $broker['user'];
        $soure = $broker['stockjobber'];
        printlog($soure, "交易失败2", 'buy');
        $Trust_no = mt_rand(101010, 999999) . substr(time(), 1);
        printlog($Trust_no, "交易失败3", 'buy');
        $Trust = new \app\market\model\Trust();
//        存储模拟买入委托记录
        $Trust_res = $Trust->add_m_trust($stockinfo, $count, $price, $sub_id, $lid, $user, $soure, $Trust_no, $broker, $model);
        printlog($Trust_res, "存储模拟买入委托记录", 'buy');
        if (!$Trust_res) {
            Db::rollback();
            return json(array(
              'status' => 0,
              'message' => lang('msg_26')//'交易失败!!'
            ));
        }
        $deal_stack = new \app\market\model\Deal_stock();
        //存储模拟买入成交记录
        $deal_res = $deal_stack->add_m_deal_stock($stockinfo, $count, $price, $sub_id, $lid, $user, $soure, $Trust_no, $model);
        printlog($deal_res, "存储模拟买入成交记录", 'buy');
        if (!$deal_res) {
            Db::rollback();
            return json(array(
              'status' => 0,
              'message' => lang('msg_26')//'交易失败'
            ));
        }
        $position = new \app\market\model\Position();
        //（交易金额+过户费+佣金）/数量
        $ck_price = round($effect_money / $count, 3);
//        存储模拟持仓记录
        $pos_res = $position->add_m_position($stockinfo, $count, $sub_id, $lid, $user, $soure, $ck_price, $model, $Trust_no);
        printlog($pos_res, "存储模拟持仓记录", 'buy');
        if (!$pos_res) {
            Db::rollback();
            return json(array(
              'status' => 0,
              'message' => lang('msg_26')//'交易失败'
            ));
        }
        $Delivery = new \app\market\model\Delivery();
        $avail = $moneyinfo['avail'] / 100 - $effect_money;
        $position_res_b = Db::name('stock_position')->where(array('sub_id' => $sub_id))->where(array('gupiao_code' => $code))
          ->where(array('buying' => 0))->find();
        if ($model == 2 && !empty($position_res_b)) {
            $amount = $position_res_b['canbuy_count'];
            $average_price = $position_res_b['buy_average_price'];
        }
        if ($model == 1 && !empty($position_res_b)) {
            $amount = $position_res_b['canbuy_count'] + $count;
        }
        if (empty($position_res_b)) {
            $amount = $count;
        }
        if (!empty($price) && $model == 1) {
            $average_price = $price;
        }
        //else{
        //     $lis = Db::name('stock_position')->where('gupiao_code',$stockinfo["code"])->field('buy_average_price')->find();
        //     $average_price = $lis['buy_average_price'];
        // }
//        存储模拟交割单记录
        $pos_res = $Delivery->add_m_delivery_order($stockinfo, $count, $average_price, $sub_id, $lid, $user, $soure, $commission, $Transfer, $Trust_no, $avail, $amount, $model, $fees);
        printlog($pos_res, "存储模拟交割单记录", 'buy');
        if (!$pos_res) {
            Db::rollback();
            return json(array(
              'status' => 0,
              'message' => lang('msg_26')//'交易失败'
            ));
        }
        if ($broker['broker'] != -1) {
            $trade_id = $broker['id'];
            if (config('web_real_api') == 1) {
                $otype = 1;
                if ($model == 1) {
                    $ptype = 1;
                } else {
                    $ptype = 5;
                }
                $data = gs('commitOrder', array($trade_id, $code, $count, $etype, $otype, $ptype, $price));
                printlog($data, "gs('commitOrder'", 'buy');
                if (isset($data['error'])) {
                    Db::rollback();
                    return json(array(
                      'status' => 0,
                      'message' => $data['error']
                    ));
                }
                if (isset($data['ErrInfo'])) {
                    Db::rollback();
                    return json(array(
                      'status' => 0,
                      'message' => $data['ErrInfo']
                    ));
                }
                Db::commit();
            }
            if (config('web_real_api') == 2) {
                $otype = 0;
                if ($model == 1) {
                    $ptype = 0;
                } else {
                    $ptype = 4;
                }
                $data = \app\market\model\Grid::grid_order($otype, $ptype, $code, $price, $count, $trade_id);
                printlog($data, "grid_order'", 'buy');
                if (!preg_match('/^\\d*$/', $data)) {
                    Db::rollback();
                    return json(array(
                      'status' => 0,
                      'message' => $data
                    ));
                } else {
                    Db::commit();
                }
            }
        } else {
            Db::commit();
        }
        return json(array(
          'status' => 1,
          'message' => lang('msg_27')// '买入委托已提交'
        ));
    }

    /**
     * 卖出股票
     * @return \think\response\Json
     */
    public function sell()
    {
        if (!get_trading_time('','time')) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_28') //'非交易时间'
            ));
        }
        $req = request();
        $id = input('id');
        $code = input('code');
        $codecount = $count = input('count');
        $price = input('price');
        $model = input('model');
        $count = abs($count);
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (!isset($res['uid']) || $res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18')//'登录超时请重新登录'
            ));
        }
        //		$lis = Db::name('stock_position')->where('gupiao_code',$code)->where('sub_id',$id)->field('buy_average_price')->find();
        //		$average_price = $lis['buy_average_price'];
        $borrow = new \app\stock\model\Borrow();
        $res = $borrow->savesell($id, $code, $count, 0, $price, $model);
        return json($res);
    }

    public function deal_stock()
    {
        $req = request();
        $id = input('id');
        $beginday = input('beginday');
        $endday = input('endday');
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (empty($res['account_id'])) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_16')//'不存在的子账号'
            ));
        }
        if ($res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18')//'登录超时请重新登录'
            ));
        }
        $deal_stack = new \app\market\model\Deal_stock();
        $res = $deal_stack->get_deal_stock($id, $beginday, $endday);
        return json(array(
          'data' => $res,
          'status' => 1,
          'message' => lang('msg_29')// '操作成功'
        ));
    }

    //成交记录详情
    public function deal_stock_detail()
    {
        $id = input('id');
        $res = Db::name('stock_deal_stock')->where('id', $id)->find();
        $add_time = Db::name('stock_trust')->where('trust_no', $res['trust_no'])->value('add_time');
        $sub_account = Db::name('stock_subaccount')->where('id', $res['sub_id'])->value('sub_account');
        $res['add_time_text'] = date('Y-m-d H:i:s', $add_time); //创建时间
        $res['deal_date_text'] = date('Y-m-d', $res['deal_date']) . ' ' . $res['deal_time'];//成交时间
        $res['sub_account'] = $sub_account; //子账户
        $stock_delivery_order = Db::name('stock_delivery_order')->where('trust_no', $res['trust_no'])->find();
        $res['stamp_duty'] = $stock_delivery_order['stamp_duty']; //印花税
        $res['transfer_fee'] = $stock_delivery_order['transfer_fee']; //过户费
        $res['commission'] = $stock_delivery_order['commission']; //净佣金
        $res['fees'] = $stock_delivery_order['fees']; //净佣金
        return json(array(
          'data' => $res,
          'status' => 1,
          'message' => lang('msg_29')// '操作成功'
        ));
    }

    public function canbuy_count()
    {
        $req = request();
        $sub_id = input('id');
        $code = input('code');
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($sub_id);
        if (empty($res['account_id']) || $res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_16')//'不存在的子账号'
            ));
        }
        if ($res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18')// '登录超时请重新登录'
            ));
        }
        $position = new \app\market\model\Position();
        $data = $position->get_canbuy_count($sub_id, $code);
        return json(array(
          'data' => $data,
          'status' => 1,
          'message' => lang('msg_29')//'操作成功'
        ));
    }

    public function position()
    {
        $req = request();
        $id = input('id');
        $page = input('page');
        $page = empty($page) ? 1 : $page;
        $pnum = input('pnum');
        $pnum = empty($pnum) ? 15 : $pnum;
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (empty($res['account_id'])) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_16') //'不存在的子账号'
            ));
        }
        if ($res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18') //'登录超时请重新登录'
            ));
        }
        $sub_id = $id;
        $total = Db::name('stock_position')->where(array(
          'sub_id' => $sub_id
        ))->where(array(
          'buying' => 0
        ))->count();
        $total_page = ceil($total / $pnum);
        $start = $page * $pnum - $pnum;
        $res = Db::name('stock_position')->where(array(
          'sub_id' => $sub_id
        ))->where(array(
          'buying' => 0
        ))->limit($start, $pnum)->order('id desc')->select();
        if (!empty($res)) {
            $code = '';
            foreach ($res as $k => $v) {
                $code = $code . $v['gupiao_code'] . ',';
            }
            $code = substr($code, 0, -1);
            $info = z_market_bat($code);
            foreach ($res as $k => $v) {
                foreach ($info as $kk => $vv) {
                    if ($res[$k]['gupiao_code'] === $vv['code']) {
                        $res[$k]['ck_price_new'] = $v['ck_price_new'] = empty($v['ck_price_new']) ? $v['ck_price'] : $v['ck_price_new'];
                        if (!$vv['current_price']) { //如果现价为空则再去请求接口获取
                            $stock_data = z_market($vv['code']);
                            $vv['current_price'] = $stock_data['current_price'];
                        }
                        $res[$k]['now_price'] = $vv['current_price'];
                        $res[$k]['market_value'] = $vv['current_price'] * $v['canbuy_count'];
                        //盈亏 (现价-成本价)*数量
                        //  $res[$k]['ck_profit'] = round(($vv['current_price'] - $v['buy_average_price']) * $v['stock_count'], 2);
                        $res[$k]['ck_profit'] = round(($vv['current_price'] - $v['ck_price_new']) * $v['stock_count'], 2);
//                            $res[$k]['ck_profit'] = round(($info['current_price'] - $v['ck_price']) * $v['stock_count'], 2);//当前价减去成本价
                        $time = array(
                          "current_price" => $vv['current_price'],
                          "ck_price_new" => $v['ck_price_new'],
                          "stock_count" => $v['stock_count'],
                          "gupiao_code" => $vv['code'],
                          "sud" => $sub_id
                        );
                        //参考成本价被新写的方法替换
                        $res[$k]['ck_price'] = empty($v['ck_price_new']) ? $v['ck_price'] : $v['ck_price_new'];
                        $res[$k]['profit_rate'] = round($res[$k]['ck_profit'] / ($v['buy_average_price'] * $v['stock_count']) * 100, 2);
                        $res_d1 = Db::name('stock_delivery_order')->where(array(
                          'sub_id' => $sub_id,
                          'gupiao_code' => $vv['code']
                        ))->where('deal_date', '>', mktime(0, 0))->where(array(
                          'status' => 1
                        ))->where(array(
                          'business_name' => '证券买入'
                        ))->sum('volume');
                        $res[$k]['canbuy_count'] = $res[$k]['canbuy_count'] - $res_d1;
                        $res[$k]['market_value'] = round($res[$k]['market_value'], 2);
                        $res[$k] = array_merge($res[$k], $vv);
                    }
                }
            }
        }
        $data['page'] = $page;
        $data['pnum'] = $pnum;
        $data['all_page'] = $total_page;
        $data['total'] = $total;
        $data['list'] = $res;
        return json(array(
          'data' => $data,
          'status' => 1,
          'message' => lang('msg_29')// '操作成功'
        ));
    }

    public function delivery()
    {
        $req = request();
        $id = input('id');
        $beginday = input('beginday');
        $endday = input('endday');
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (empty($res['account_id'])) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_16')// '不存在的子账号'
            ));
        }
        if ($res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18') //'登录超时请重新登录'
            ));
        }
        $Delivery = new \app\market\model\Delivery();
        $res = $Delivery->get_delivery_order($id, $beginday, $endday);
        return json(array(
          'data' => $res,
          'status' => 1,
          'message' => lang('msg_29')// '操作成功'
        ));
    }

    //交割单详情
    public function deliveryDetail()
    {
        $id = input('id');
        $res = Db::name('stock_delivery_order')->where('id', $id)->find();
        $add_time = Db::name('stock_trust')->where('trust_no', $res['trust_no'])->value('add_time');
        $sub_account = Db::name('stock_subaccount')->where('id', $res['sub_id'])->value('sub_account');
        $res['add_time_text'] = date('Y-m-d H:i:s', $add_time); //创建时间
        $res['deal_date_text'] = date('Y-m-d H:i:s', $res['deal_date']);//成交日期
        $res['sub_account'] = $sub_account; //子账户
        return json(array(
          'data' => $res,
          'status' => 1,
          'message' => lang('msg_29')// '操作成功'
        ));
    }

    public function account_info()
    {
        $req = request();
        $subaccount_id = input('id');
        //临时为了解决前端推送跳转问题---开始
        if (!$subaccount_id) {
            $subaccount_id = Db::name('stock_borrow')->where(array(
              'member_id' => MID,
              'status' => 1
            ))->max('id');
        }
        //--结束
        //计算总盈亏（防止定时器执行不成功，在前端页面看不到总盈亏）start
        $uaa = subtixian_new($subaccount_id);
        $return_money = round(tixian($subaccount_id, true) / 100, 2);
        $available_amount = round($uaa, 2);
        Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $subaccount_id])->update(['return_money' => $return_money, 'available_amount' => $available_amount, 'market' => money_convert(lifting($subaccount_id)['market'])]);
//        end
        $tag = input('tag');
        $submodel = new \app\market\model\SubAccountMoney();
        $res = $submodel->get_account_money_inf($subaccount_id);
        // $result=Db::name('stock_subaccount_money')->where(['stock_subaccount_id'=>$subaccount_id])->find();
        if ($res['available_amount'] < 0) {
            $res['available_amount'] = 0;
        }
        //可返回计算子账户价值
        $lifting = lifting($subaccount_id);
        if ($tag == 'ty') {
            //强制使用新的可提盈金额
            //$res['available_amount']=tixian($subaccount_id);
            $res['available_amount'] = round(subtixian_new($subaccount_id), 2);
        }
        $where = array(
          "sub_id" => $subaccount_id,
          "flag2" => "买入委托",
          "status" => "已委托",
        );
        $market_freeze_amount_array = Db::name('stock_trust')->where($where)->field('trust_price,gupiao_code,trust_count')->select();
        //在委托中的金额
        $market_freeze_amount = 0;
        if (!empty($market_freeze_amount_array)) {
            foreach ($market_freeze_amount_array as $value) {
                $market_freeze_amount += $value['trust_price'] * $value['trust_count'];
            }
        }
        $res['market_value'] = round($lifting['market'] / 100, 2);
        //$res['total_money'] = round($lifting['market'] / 100 + $lifting['avail'] / 100 + $lifting['freeze_amount'] / 100, 2);
        $res['total_money'] = round($lifting['market'] / 100 + $lifting['avail'] / 100 + $market_freeze_amount / 100, 2);
        $res['available_amount'] = round(subtixian_new($subaccount_id), 2) < 0 ? '0' : round(subtixian_new($subaccount_id), 2);
        //单位换算
        $res = \app\market\model\SubAccountMoney::ftoy($res);
        return json(array(
          'data' => $res,
          'status' => 1,
          'message' => lang('msg_29') // '操作成功'
        ));
    }

    public function account_info_bak()
    {
        $req = request();
        $subaccount_id = input('id');
        $tag = input('tag');
        $submodel = new \app\market\model\SubAccountMoney();
        $res = $submodel->get_account_money_inf($subaccount_id);
        // $result=Db::name('stock_subaccount_money')->where(['stock_subaccount_id'=>$subaccount_id])->find();
        if ($res['available_amount'] < 0) {
            $res['available_amount'] = 0;
        }
        $sum = 0;
        //
        $lifting = lifting($subaccount_id);
        if ($tag == 'ty') {
            //强制使用新的可提盈金额
            //$res['available_amount']=tixian($subaccount_id);
            $res['available_amount'] = round(subtixian_new($subaccount_id), 2);
        }
        $res['market_value'] = round($lifting['market'], 2);
        $res['total_money'] = round($lifting['market'] + $lifting['avail'] / 100 + $lifting['freeze_amount'] / 100, 2);
        $res['available_amount'] = round($res['total_money'] - $res['init_money'], 2);
        echo "\r\n总市值2:" . $res['total_money'];
        //   echo "\r\navail:".$res['avail'] / 100;
        echo "\r\n可提赢金额2:" . $res['available_amount'];
        $res = \app\market\model\SubAccountMoney::ftoy($res);
        return json(array(
          'data' => $res,
          'status' => 1,
          'message' => lang('msg_29') // '操作成功'
        ));
    }

    public function user_sub_id()
    {
        $req = request();
        $uid = input('uid');
        $submodel = new \app\market\model\StockSubAccount();
        $account = $submodel->get_account_by_uid($uid);
        $time = time() + 604800;
        $borrow_info = Db::name('stock_borrow')->field('stock_subaccount_id')->where(array(
          'status' => 2,
          'member_id' => $uid
        ))->where('end_time', '<', $time)->select();
        if (!empty($borrow_info)) {
            $res = array();
            foreach ($borrow_info as $k => $v) {
                array_push($res, $v['stock_subaccount_id']);
            }
            foreach ($account as $key => $value) {
                if (in_array($value['id'], $res)) {
                    unset($account[$key]);
                }
            }
            $account = array_values($account);
        }
        return json(array(
          'data' => $account,
          'status' => 1,
          'message' => lang('msg_29') // '操作成功'
        ));
    }

    public function subaccount_id()
    {
        $req = request();
        $sub_account = input('sub_account');
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_name($sub_account);
        return json(array(
          'data' => $res['id'],
          'status' => 1,
          'message' => lang('msg_29') // '操作成功'
        ));
    }

    public function subaccount_info()
    {
        $req = request();
        $id = input('id');
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        return $res;
    }

    public function trust()
    {
        $req = request();
        $id = input('id');
        $page = input('page');
        $beginday = input('beginday');
        $endday = input('endday');
        $page = empty($page) ? 1 : $page;
        $pnum = input('pnum');
        $pnum = empty($pnum) ? 15 : $pnum;
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (empty($res['account_id'])) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_16') // '不存在的子账号'
            ));
        }
        if ($res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18') // '登录超时请重新登录'
            ));
        }
        $start = $page * $pnum - $pnum;
        if (empty($endday)) {
            $endday = time();
        } else {
            $endday = strtotime($endday);
        }
        if (empty($beginday)) {
            $beginday = mktime(0, 0, 0);
        } else {
            $beginday = strtotime($beginday);
        }
        $total = Db::name('stock_trust')->where(array(
          'sub_id' => $id
        ))->where('trust_date', '>=', $beginday)->where('trust_date', '<=', $endday)->where('state', '=', 1)->count();
        $total_page = ceil($total / $pnum);
        $res = Db::name('stock_trust')->where(array(
          'sub_id' => $id
        ))->where('trust_date', '>=', $beginday)->where('trust_date', '<=', $endday)->where('state', '=', 1)->limit($start, $pnum)->order('id desc')->select();
        foreach ($res as $k => $v) {
            $res[$k]['trust_date'] = date('Y-m-d', $v['trust_date']);
        }
        $data['page'] = $page;
        $data['pnum'] = $pnum;
        $data['all_page'] = $total_page;
        $data['total'] = $total;
        $data['list'] = $res;
        return json(array(
          'data' => $data,
          'status' => 1,
          'message' => lang('msg_29')//'操作成功'
        ));
    }

    //委托详情
    public function trustDetail()
    {
        $id = input('id');
        $res = Db::name('stock_trust')->where('id', $id)->find();
        $sub_account = Db::name('stock_subaccount')->where('id', $res['sub_id'])->value('sub_account');
        $res['add_time_text'] = date('Y-m-d H:i:s', $res['add_time']); //创建时间
        $res['trust_date_text'] = date('Y-m-d', $res['trust_date']) . ' ' . $res['trust_time'];//委托时间
        $res['sub_account'] = $sub_account; //子账户
        $stock_delivery_order = Db::name('stock_delivery_order')->where('trust_no', $res['trust_no'])->find();
        $res['stamp_duty'] = $stock_delivery_order['stamp_duty']; //印花税
        $res['transfer_fee'] = $stock_delivery_order['transfer_fee']; //过户费
        $res['commission'] = $stock_delivery_order['commission']; //净佣金
        $res['fees'] = $stock_delivery_order['fees']; //规费
        return json(array(
          'data' => $res,
          'status' => 1,
          'message' => lang('msg_29')// '操作成功'
        ));
    }

    public function cancel_trust()
    {
        $req = request();
        $id = input('id');
        $page = input('page');
        $page = empty($page) ? 1 : $page;
        $pnum = input('pnum');
        $pnum = empty($pnum) ? 15 : $pnum;
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (empty($res['account_id'])) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_16') // '不存在的子账号'
            ));
        }
        if ($res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18') //'登录超时请重新登录'
            ));
        }
        $time = mktime(0, 0, 0);
        //原来只能查当天d
        //$total = Db::name('stock_trust')->where(array('sub_id' => $id, 'trust_date' => $time, 'status' => '已委托'))->count();
        $total = Db::name('stock_trust')->where(array(
          'sub_id' => $id,
          'status' => '已委托'
        ))->count();
        $start = $page * $pnum - $pnum;
        $total_page = ceil($total / $pnum);
        //原来只能查当天d
        //$res = Db::name('stock_trust')->where(array('sub_id' => $id, 'trust_date' => $time, 'status' => '已委托'))->limit($start, $pnum)->select();
        $res = Db::name('stock_trust')->where(array(
          'sub_id' => $id,
          'status' => '已委托'
        ))->limit($start, $pnum)->select();
        $data['page'] = $page;
        $data['pnum'] = $pnum;
        $data['all_page'] = $total_page;
        $data['total'] = $total;
        $data['list'] = $res;
        return json(array(
          'data' => $data,
          'status' => 1,
          'message' => lang('msg_29') //'操作成功'
        ));
    }

    public function cancel_order()
    {
        if (!get_trading_time('','time')) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_28')  //'非交易时间'
            ));
        }
        $req = request();
        $id = input('id');
        $trust_no = input('trust_no');
        $submodel = new \app\market\model\StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (empty($res['account_id'])) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_16')//'不存在的子账号'
            ));
        }
        if ($res['uid'] != MID) {
            return json(array(
              'status' => 0,
              'message' => lang('msg_18')// '登录超时请重新登录'
            ));
        }
        $subaccount = new \app\market\model\SubAccountMoney();
        Db::startTrans();
        $yes = false;
        $tempinfo = Db::name('stock_temp')->where(array(
          'trust_no' => $trust_no
        ))->lock(true)->find();
        if (!empty($tempinfo)) {
            switch (substr($tempinfo['gupiao_code'], 0, 1)) {
                case '0':
                    $d = 1;
                    break;
                case '3':
                    $d = 1;
                    break;
                case '1':
                    $d = 1;
                    break;
                case '2':
                    $d = 1;
                    break;
                case '6':
                    $d = 2;
                    break;
                case '5':
                    $d = 2;
                    break;
                case '9':
                    $d = 2;
                    break;
                default:
                    Db::rollback();
                    return json(array(
                      'status' => 0,
                      'message' => lang('msg_30')//'股票代码不对，撤单失败'
                    ));
                    break;
            }
            $type = $d;
        } else {
            Db::rollback();
            return json(array(
              'status' => 0,
              'message' => lang('msg_31')// '没找到对应委托，撤单失败'
            ));
        }
        $affect_money = Db::name('stock_delivery_order')->where(array(
          'trust_no' => $trust_no
        ))->value('liquidation_amount');
        if ($tempinfo['deal_no'] == null) {
            $trust['status'] = '已撤';
            $trust['cancel_order_flag'] = '1';
            $trust['cancel_order_count'] = $tempinfo['trust_count'];
            $trust_res = Db::name('stock_trust')->where(array(
              'trust_no' => $trust_no
            ))->update($trust);
            $deal_res = Db::name('stock_deal_stock')->where(array(
              'trust_no' => $trust_no
            ))->delete();
            $delivery_res = Db::name('stock_delivery_order')->where(array(
              'trust_no' => $trust_no
            ))->delete();
            $ret = Db::name('stock_temp')->where(array(
              'trust_no' => $trust_no
            ))->delete();
            $position = Db::name('stock_position')->where(array(
              'sub_id' => $id
            ))->where(array(
              'gupiao_code' => $tempinfo['gupiao_code']
            ))->find();
            $subm_res = false;
            $position_in = false;
            if ($tempinfo['flag2'] == '买入委托') {
                $subm_res = $subaccount->up_moneylog($id, $affect_money * 100, 8);
                $position_in = true;
            } else if ($tempinfo['flag2'] == '卖出委托') {
                $position['canbuy_count'] = $position['canbuy_count'] + $tempinfo['trust_count'];
                $position_in = Db::name('stock_position')->where(array(
                  'sub_id' => $id
                ))->where(array(
                  'gupiao_code' => $tempinfo['gupiao_code']
                ))->update($position);
                $subm_res = $subaccount->up_moneylog($id, $affect_money * 100, 9);
            }
            if ($trust_res && $deal_res && $delivery_res && $ret && $subm_res && $position_in) {
                $yes = true;
            } else {
                Db::rollback();
                $num = $trust_res . $deal_res . $delivery_res . $ret . $subm_res . $position_in;
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_32'),//'撤单失败',
                  'data' => $num
                ));
            }
        }
        $broker = $submodel->get_broker($res['account_id']);
        $trade_id = $broker['id'];
        $res = Db::name('admin_plugin')->where(array(
          'name' => 'GreenSparrow'
        ))->find();
        if (!empty($res) && $yes && $broker['broker'] != -1) {
            $day_re = array();
            if (config('web_real_api') == 1) {
                $day_re = gs('queryTradeData', array(
                  $broker['id'],
                  3
                ));
            }
            if (config('web_real_api') == 2) {
                $day_re = \app\market\model\Grid::grid_category_trust($broker['id']);
            }
            unset($day_re[0]);
            $orderid = '';
            foreach ($day_re as $k => $v) {
                if ($v[4] == $tempinfo['gupiao_code'] && $v[9] == $tempinfo['trust_count'] && $v[7] == $tempinfo['flag2']) {
                    $orderid = $v[10];
                }
            }
            $data = array();
            if (!empty($orderid)) {
                if (config('web_real_api') == 1) {
                    $data = gs('cancelOrder', array(
                      $trade_id,
                      $orderid,
                      $type
                    ));
                }
                if (config('web_real_api') == 2) {
                    if ($type = 1) {
                        $type = 0;
                    } else {
                        $type = 1;
                    }
                    $data = \app\market\model\Grid::grid_cancel($type, $orderid, $trade_id);
                }
            }
            if ($data) {
                Db::commit();
                return json(array(
                  'status' => 1,
                  'message' => lang('msg_33')//'撤单成功'
                ));
            }
        } else if ($yes) {
            if ($broker['broker'] != -1) {
                Db::rollback();
                return json(array(
                  'status' => 0,
                  'message' => lang('msg_34')// '请安装股票实盘交易插件'
                ));
            } else {
                Db::commit();
                return json(array(
                  'status' => 1,
                  'message' => lang('msg_33')  // '撤单成功'
                ));
            }
        }
        Db::rollback();
        return json(array(
          'status' => 0,
          'message' => lang('msg_32') //'撤单失败'
        ));
    }
}

?>



