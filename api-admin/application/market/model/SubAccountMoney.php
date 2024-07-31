<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
namespace app\market\model;

use think\Db;
use think\Model;
use think\facade\Log;

class SubAccountMoney extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBACCOUNT_MONEY__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function get_account_money($subaccount_id)
    {
        $result = Db::name('stock_subaccount_money')->field(true)->where(['stock_subaccount_id' => $subaccount_id])->find();
        if (empty($result)) return null;
        return $result;
    }

    public function get_account_money_inf($subaccount_id)
    {
        $result = Db::view('stock_subaccount_money m')
          ->view('stock_subaccount_risk r', 'loss_warn,loss_close,renewal', 'r.stock_subaccount_id=m.stock_subaccount_id', 'left')
          ->view('stock_borrow b', 'type,init_money,borrow_money,deposit_money,end_time,id as borrow_id,status as b_status', 'b.stock_subaccount_id=m.stock_subaccount_id', 'left')
          ->where(['m.stock_subaccount_id' => $subaccount_id])
          ->find();
        if (isset($result['b_status']) && $result['b_status'] === 2) {
            $result['available_amount'] = 0;
        }
        $result['return_money'] = tixian($subaccount_id, true);
        $result['init_money'] = round($result['init_money'] / 100, 2);
        $result['loss_warn_money'] = round(($result['borrow_money'] + $result['loss_warn'] * $result['deposit_money'] / 100) / 100, 2);
        $result['loss_close_money'] = round(($result['borrow_money'] + $result['loss_close'] * $result['deposit_money'] / 100) / 100, 2);
        $result['end_time'] = date('Y-m-d H:i:s', $result['end_time']);
        if (empty($result)) return null;
        return $result;
    }

    /** #子账号资金使用log
     * 传入 $sub_id子账户id
     * $affect_money 变化金额
     * $type 类型
     * $Balance 未知
     * $return_money盈亏金额
     * $code 股票代码
     * $stock_count 股票数量
     */
    public function up_moneylog($sub_id, $affect_money, $type, $return_money = 0, $Balance = 0, $code = "600000", $stock_count = 0, $info = '')
    {
        $ty = "";
        $vm = Db::name('stock_subaccount_money')->field(true)->where(['stock_subaccount_id' => $sub_id])->find();
        if (empty($vm)) {
            return false;
        }
        //$nowTime = time();
        switch ($type) {
            case 31://委托成功处理冻结金额
                $mmoney['freeze_amount'] = $vm['freeze_amount'] - $affect_money;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $vm['avail'], 6, "(-)委托限价购买成功，释放冻结金额：" . $fee . "元，冻结金额余额为" . ($mmoney['freeze_amount']) / 100, $code);
                break;
            case 1:
                //购买股票扣款
                if ($vm['avail'] < $affect_money) {
                    return false;
                }
                $mmoney['avail'] = $vm['avail'] - $affect_money;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $mmoney['avail'], 1, "(-)-1购买股票扣款,可用减少" . $fee . "元,股票代码：{$code},数量：{$stock_count}股", $code);
                break;
            case 2:
                //卖出股票回款
                $mmoney['avail'] = $vm['avail'] + $affect_money;
                $mmoney['return_money'] = $vm['return_money'] + $return_money;
                $mmoney['return_rate'] = $mmoney['return_money'] / $vm['deposit_money'] * 100;
                $mmoney['available_amount'] = $vm['available_amount'] + $return_money;//将盈亏加到可提盈字段
                #      $mmoney['available_amount'] =tixian($sub_id);//将盈亏加到可提盈字段
                $fee = $affect_money / 100;
                if ($return_money > 0) {
                }
                $return_money = round($return_money / 100, 2);
                #$available_amount = round($mmoney['available_amount'] / 100, 2);
//                    echo "卖出股票回款<br>\r\n";
//                    echo "available_amount:" . $available_amount;
//                    echo "<br>\r\n";
                $ty = ",盈亏金额增加：" . $return_money . "元";
                self::insert_ProfitLoss($sub_id, $return_money, $mmoney['return_money'], $stock_count, $code);//插入盈亏
                self::record($sub_id, $affect_money, $mmoney['avail'], 2, "(+)【市价交易】卖出股票回款,可用增加" . $fee . "元,股票代码：{$code},数量：{$stock_count}股{$ty};", $code);
                break;
            case 3://实盘或限价购买股票扣款
                if ($vm['avail'] < $affect_money) {
                    return false;
                }
                $mmoney['avail'] = $vm['avail'] - $affect_money;
                $mmoney['freeze_amount'] = $vm['freeze_amount'] + $affect_money;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $mmoney['avail'], 3, "(-)-3购买股票扣款-可用减少" . $fee . "元,股票代码：{$code},数量：{$stock_count}股", $code);
                break;
            case 4://实盘卖出股票回款  限价交易   发起委托
                $mmoney['freeze_amount'] = $vm['freeze_amount'] + $affect_money;
                $affect_money2 = $affect_money / 100;
                self::record($sub_id, $affect_money, $vm['avail'], 4, "(=)【限价交易】发起委托交易，冻结卖出股票回款,股票代码：{$code}，冻结：{$affect_money2}元");
                break;
            case 5://实盘或限价卖出成功解除冻结
                $mmoney['avail'] = $vm['avail'] + $affect_money + $Balance;
                $mmoney['freeze_amount'] = $vm['freeze_amount'] - $affect_money;
                $mmoney['return_money'] = $vm['return_money'] + $return_money;
                $mmoney['return_rate'] = $mmoney['return_money'] / ($vm['deposit_money'] / 100);
                $mmoney['available_amount'] = $vm['available_amount'] + $return_money;//将盈亏加到可提盈字段
                #    $mmoney['available_amount'] =tixian($sub_id);//将盈亏加到可提盈字段
//                echo "实盘或限价卖出成功解除冻结<br>\r\n";
//                echo "available_amount:" . tixian($sub_id);
//                echo "<br>\r\n";
                $fee = round(($Balance + $affect_money) / 100, 2);
                if ($return_money > 0) {
                    $return_money = round($return_money / 100, 2);
                    $available_amount = round($mmoney['available_amount'] / 100, 2);
                    $ty = ",提盈金额在这里增加：" . $return_money . "元，增加后可提盈金额为：" . $available_amount;
                }
                self::insert_ProfitLoss($sub_id, $return_money, $mmoney['return_money'], $stock_count, $code);//插入盈亏
                self::record($sub_id, $affect_money, $mmoney['avail'], 5, "(+)【限价交易】卖出成功解除冻结,可用增加" . $fee . "元{$ty}");
                break;
            case 6://实盘买入成功解除冻结
                $mmoney['avail'] = $vm['avail'] + $Balance;
                $mmoney['freeze_amount'] = $vm['freeze_amount'] - $affect_money;
                $fee = round($Balance / 100, 2);
                self::record($sub_id, $affect_money, $mmoney['avail'], 6, "(+)买入成功解除冻结,可用增加" . $fee . "元");
                break;
            case 7://在子账户保证金中扣费用
                $mmoney['deposit_money'] = $vm['deposit_money'] - $affect_money;
                self::record($sub_id, $affect_money, $vm['avail'], 7, "(=)扣费用,可用不变");
                break;
            case 8://购买股票撤单
                $mmoney['avail'] = $vm['avail'] + $affect_money;
                $mmoney['freeze_amount'] = ($vm['freeze_amount'] - $affect_money) > 0 ? ($vm['freeze_amount'] - $affect_money) : 0;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $mmoney['avail'], 8, "(+)撤单解除冻结,可用增加" . $fee . "元");
                break;
            case 9://卖出股票撤单
                $mmoney['avail'] = $vm['avail'];
                $mmoney['freeze_amount'] = ($vm['freeze_amount'] - $affect_money) > 0 ? ($vm['freeze_amount'] - $affect_money) : 0;;
                self::record($sub_id, $affect_money, $mmoney['avail'], 9, "(=)卖出撤单解除冻结,可用不变");
                break;
            case 10://追加保证金
                $mmoney['avail'] = $vm['avail'] + $affect_money;
                $mmoney['stock_addmoney'] = $vm['stock_addmoney'] + $affect_money;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $mmoney['avail'], 10, "(+)用户追加保证金,可用增加" . $fee . "元");
                break;
            case 11://扩大配资
                $applyMoney = $return_money;
                $mmoney['avail'] = $vm['avail'] + $affect_money + $applyMoney;
                $mmoney['deposit_money'] = $vm['deposit_money'] + $affect_money;
                $mmoney['borrow_money'] = $vm['borrow_money'] + $applyMoney;
                $mmoney['stock_addfinancing'] = $vm['stock_addfinancing'] + $affect_money;
                $fee = ($applyMoney + $affect_money) / 100;
                self::record($sub_id, $fee * 100, $mmoney['avail'], 11, "(+)用户扩大配资,可用增加" . $fee . "元");
                break;
            case 12://提取盈利
                $mmoney['avail'] = $vm['avail'] - $affect_money;
                $mmoney['available_amount'] = $vm['available_amount'] - $affect_money;
                $mmoney['stock_drawprofit'] = $vm['stock_drawprofit'] + $affect_money;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $mmoney['avail'], 12, "(-)用户提取盈利,可用减少" . $fee . "元");
                break;
            case 13://股票分红
                $mmoney['avail'] = $vm['avail'] + $affect_money;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $mmoney['avail'], 13, "(+)用户股票" . $code . "分红,可用增加" . $fee . "元");
                break;
            case 15://收取股票红利税
                $mmoney['avail'] = $vm['avail'] - $affect_money;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $mmoney['avail'], 15, "(-)用户股票" . $code . "分红代扣利息税,可用减少" . $fee . "元");
                break;
            case 16://收取股票交易费用
                $mmoney['avail'] = $vm['avail'] - $affect_money;
                $fee = $affect_money / 100;
                self::record($sub_id, $affect_money, $mmoney['avail'], 15, "(-)用户股票" . $code . "分红代扣利息税,可用减少" . $fee . "元");
                break;
            default:
                return false;
        }
        self::alculation_ck_price($sub_id, $code);//计算成本价
        //file_put_contents(ROOT_PATH . '/public/log/datalog-' . date("Y-m-d") . '.txt', "\r\nstock_subaccount_money-stock_subaccount_id：" . $sub_id . var_export($mmoney, true), FILE_APPEND);
        Log::debug('datalog-'."\r\nstock_subaccount_money-stock_subaccount_id：" . $sub_id . var_export($mmoney, true));
        $ret = Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $sub_id])->update($mmoney);
        return $ret;
    }

    #计算账户总盈亏和可提赢金额
    public function account_profit_loss()
    {
        $data_list = Db::name('stock_subaccount s')
          ->join('stock_subaccount_money m', 'm.stock_subaccount_id=s.id')
          ->chunk(100, function ($data) {
              foreach ($data as $item) {
                  $uaa = subtixian_new($item['id']);
                  $return_money = round(tixian($item['id'], true) / 100, 2);
                  $available_amount = round($uaa, 2);
                  Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $item['id']])->update(['return_money' => $return_money, 'available_amount' => $available_amount, 'market' => money_convert(lifting($item['stock_subaccount_id'])['market'])]);
              }
          }, 's.id');
        return $data_list;
    }

    #单一计算成本价
    public function alculation_ck_price($sub_id, $code)
    {
        $buyprice = $sellprice = $stock_count = 0;
        $data = Db::name('stock_position')
          ->where(["sub_id" => $sub_id, "gupiao_code" => $code])
          ->select();
        if (!$data) {
            return false;
        }
        // printlog($stock_count, "stock_count：", "SubAccountMoney");
        //股票交割单
        $data_list = Db::name('stock_delivery_order')
          ->where('status', '<>', '0')
          ->where(["sub_id" => $sub_id, "gupiao_code" => $code])
          ->select();
        // printlog($data_list, "列表：", "SubAccountMoney");
        foreach ($data_list as $k => $v) {
            if ($v['business_name'] == "证券卖出") {
                //清算金额，包含过户费和交易佣金+印花税
                $sellprice += $v['liquidation_amount'];
                $stock_count += $v['volume'];
            }
            if ($v['business_name'] == "证券买入") {
                //清算金额，包含过户费和交易佣金
                $buyprice += $v['liquidation_amount'];
                $stock_count += $v['volume'];
            }
        }
        #(买入总价-卖出总价)/现有数量=成本价
        $ck_price = round(($buyprice - $sellprice) / $stock_count, 3);
        printlog($sellprice, "sellprice：", "SubAccountMoney");
        printlog($buyprice, "buyprice：", "SubAccountMoney");
        printlog($stock_count, "stock_count：", "SubAccountMoney");
        printlog($ck_price, "ck_price：", "SubAccountMoney");
        //差距过大不进行修正   第一次是1元  第二次是6元   ---修正开始
        foreach ($data as $value) {
            $upd = [];
            if ($value['fix_ck_price_times'] > 0) {
                if (abs($value['ck_price'] - $ck_price) > 6) {
                    $upd['fix_ck_price_info'] .= date('Y-m-d H:i:s') . "新值($ck_price),旧值(" . $value['ck_price_new'] . ")大于6不修正|";
                } else {
                    $upd['fix_ck_price_info'] .= date('Y-m-d H:i:s') . "新值($ck_price),旧值(" . $value['ck_price_new'] . ")修正|";
                    $upd['ck_price_new'] = $ck_price;
                }
            } else {
                if (abs($value['ck_price'] - $ck_price) > 1) {
                    $upd['fix_ck_price_info'] .= date('Y-m-d H:i:s') . "新值($ck_price),旧值(" . $value['ck_price_new'] . ")大于1不修正|";
                } else {
                    $upd['fix_ck_price_info'] .= date('Y-m-d H:i:s') . "新值($ck_price),旧值(" . $value['ck_price_new'] . ")修正|";
                    $upd['ck_price_new'] = $ck_price;
                }
            }
            $upd ['fix_ck_price_times'] = $value['fix_ck_price_times'] + 1;
            $ret = Db::name('stock_position')->where('id', $value['id'])->update($upd);
        }
        //$ret = Db::name('stock_position')->where(['sub_id' => $sub_id, "gupiao_code" => $code])->update(array('ck_price_new' => $ck_price));
        //printlog($ret, "ret：", "SubAccountMoney");
        //  修正结束
        return $ret;
    }

    /**
     * 自动重算  批量成本价
     *
     ***
     */
    public function more_cb_pire()
    {
        $buyprice = $sellprice = $stock_count = 0;
        $data = Db::name('stock_position')
          ->select();
        if (count($data) < 1) {
            return false;
        }
        foreach ($data as $v) {
            $stock_count = $stock_count + $v['stock_count'];
            $sub_id = $v['sub_id'];
            $code = $v['gupiao_code'];
            self::alculation_ck_price($sub_id, $code);
        }
        return true;
    }

    #插入每一次盈亏金额
    private static function insert_ProfitLoss($sub_id, $affect_money, $return_money, $stock_count, $code)
    {
        $record['sub_id'] = $sub_id;
        $record['affect'] = $affect_money * 100;
        $record['return_money'] = $return_money;
        $record['stock_count'] = $stock_count;
        $record['addtime'] = time();
        $record['code'] = $code;
        $res = Db::name('stock_submoney_profit_loss_record')->insert($record);
        return $res;
    }

    public static function record($sub_id, $affect_money, $account, $type, $info, $code = '')
    {
        $uid = Db('stock_subaccount')->where('id', $sub_id)->value('uid'); //主账号id
        $for_user = Db('member')->where('id', $uid)->value('agent_far');
        $record['sub_id'] = $sub_id;
        $record['affect'] = $affect_money;
        $record['account'] = $account;
        $record['type'] = $type;
        $record['info'] = $info;
        $record['create_time'] = time();
        $record['create_ip'] = get_client_ip(1);
        $record['code'] = $code;
        $record['for_user'] = $for_user;
        printlog($record,"stock_submoney_record-stock_subaccount_id：" . $sub_id);

        $res = Db::name('stock_submoney_record')->insert($record);
        return $res;
    }

    //子账户资金单位由分转为元
    public static function ftoy($res)
    {
        if (!empty($res)) {
            $res["min_commission"] = money_convert($res["min_commission"]);
            $res["avail"] = money_convert($res["avail"]);
            //  $res["available_amount"] = money_convert($res["available_amount"]);
            # $res["freeze_amount"] = money_convert($res["freeze_amount"]);
            $where = array(
              "sub_id" => $res['stock_subaccount_id'],
              "flag2" => "买入委托",
              "status" => "已委托",
            );
            $market_freeze_amount_array = Db::name('stock_trust')->where($where)->field('trust_price,gupiao_code,trust_count')->select();
            #在委托中的金额
            $market_freeze_amount = 0;
            if (!empty($market_freeze_amount_array)) {
                foreach ($market_freeze_amount_array as $value) {
                    $market_freeze_amount += $value['trust_price'] * $value['trust_count'];
                }
            }
            $res["freeze_amount"] = $market_freeze_amount;
            $res["return_money"] = money_convert($res["return_money"]);
            //$res["return_rate"] = money_convert($res["return_rate"]);
            $res["deposit_money"] = money_convert($res["deposit_money"]);
            $res["borrow_money"] = money_convert($res["borrow_money"]);
            $res["stock_addfinancing"] = money_convert($res["stock_addfinancing"]);
            $res["stock_addmoney"] = money_convert($res["stock_addmoney"]);
            $res["stock_drawprofit"] = money_convert($res["stock_drawprofit"]);
        }
        return $res;
    }
}