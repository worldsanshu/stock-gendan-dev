<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 孟辉 <1690611599@qq.com>
// +----------------------------------------------------------------------
namespace app\stock\home;

use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundOrder;
use app\fund\model\FundOrderGs as FundOrderGsModel;
use app\index\controller\Home;
use app\interest\model\Interest as InterestModel;
use app\market\home\Heart;
use app\market\model\Grid;
use app\market\model\Position;
use app\market\model\StockSubAccount;
use app\market\model\StockSubAccountRisk;
use app\market\model\SubAccountMoney;
use app\member\model\Member;
use app\member\model\MemberMessage as MemberMessageModel;
use app\money\model\Money;
use app\money\model\Record;
use app\statistics\model\DataReport as DataReportModel;
use app\stock\admin\Operate as Operate;
use app\stock\model\Borrow;
use app\stock\model\Renewal;
use app\stock\model\StockList;
use app\stock\model\Subaccount;
use app\stock\service\PartnerSettleService;
use think\Db;
use think\facade\Lang;
use think\facade\Log;

/*
 * 定时任务
*/

class Crond extends Home
{
    public static function test()
    {
        $res = self::aliyun_market('sz600178');
        $res = self::aliyun_to_api($res[0]);
    }

    public static function config($parm)
    {
        $name = Db::name("admin_config")->where(["name" => $parm])->value("value");
        if (is_numeric($name)) {
            return $name;
        }
        $name = strtolower($name);
        $name = str_replace(array(
            "\r\n",
            "\r",
            "\n"
        ), ",", $name);
        $name = explode(',', $name);
        $res  = [];
        foreach ($name as $k => $v) {
            $tmd         = explode(':', $v);
            $res[$k + 1] = $tmd[1];
        }
        return $res;
    }

    /**
     * 实时 获取预警线  平仓线
     * @return void
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function operations()
    {
        $num = date("w");
        if ($num >= 1 and $num <= 5) {
            if (!get_trading_time('','time')) {
                $borrow_arr = Db::name('stock_borrow')->where(['status' => 1])->order('verify_time', 'desc')->select();
                // print_r($borrow_arr);die;//->where('id=272')->where('stock_subaccount_id=222')
                if (!empty($borrow_arr)) {
                    foreach ($borrow_arr as $key => $val) {
                        if ($val['type'] != 4) {
                            $position_stock_arr = Db::name('stock_position')->where('sub_id', $val['stock_subaccount_id'])->field('gupiao_code,stock_count')->select();
                            $market_val         = '';
                            $gupiao_code_arr    = array();
                            $code_arr           = array();
                            if (!empty($position_stock_arr)) {
                                foreach ($position_stock_arr as $value) {
                                    $gupiao_code_arr[$value['gupiao_code']] = $value['stock_count'];
                                    $code_arr[]                             = $value['gupiao_code'];
                                }
                            }
                            $new_code_arr = z_market_bat(implode(',', $code_arr));
                            foreach ($new_code_arr as $k => $v) {
                                //股票累计的现价
                                $market_val += $gupiao_code_arr[$v['code']] * $v['current_price'];
                            }
                            $subaccount_money_arr = Db::name('stock_subaccount_money')->where('stock_subaccount_id', $borrow_arr[$key]['stock_subaccount_id'])->field('avail,freeze_amount,deposit_money,borrow_money')->find();
                            //  print_r($subaccount_money_arr);
                            $subaccount_loss = Db::name('stock_subaccount_risk')->where('stock_subaccount_id', $borrow_arr[$key]['stock_subaccount_id'])->field('loss_warn,loss_close')->find();
                            //  print_r($subaccount_loss);
                            $subaccount_loss_warn  = $subaccount_loss['loss_warn'] / 100;
                            $subaccount_loss_close = $subaccount_loss['loss_close'] / 100;
                            $loss_warn             = money_convert($subaccount_money_arr['borrow_money']) + money_convert($subaccount_money_arr['deposit_money']) * $subaccount_loss_warn;
                            //股票累计的现价总和+子账号可用金额+子账号冻结金额
                            $now_init_amount = $market_val + money_convert($subaccount_money_arr['avail']) + money_convert($subaccount_money_arr['freeze_amount']);
                            //止损线=保证金*止损比例+配资金额-市值-可用余额-冻结资金
                            $loss_close = money_convert($subaccount_money_arr['borrow_money']) + money_convert($subaccount_money_arr['deposit_money']) * $subaccount_loss_close;
                            //预警线
                            $loss_warn = sprintf("%.2f", ($now_init_amount - $loss_warn));
                            //平仓线
                            $close = sprintf("%.2f", ($now_init_amount - $loss_close));
                            //保证金*扩大倍数=配资金额
                            //配资金额+保证金=操盘金额
                            //配资金额+保证金的50%=等于警戒线
                            //配资金额+保证金的20%=平仓线
                            //$loss_warn = 1;
                            //平仓线
                            //      $close = 2;
                            Db::name('stock_borrow')->where("id", $val['id'])->update(['loss_warn' => $loss_warn, 'close' => $close, 'uptime' => time()]);
                        }
                    }
                }
            }
        }
    }

    public function operations_bak()
    {
        $num = date("w");
        if ($num >= 1 and $num <= 5) {
            if (!get_trading_time('','time')) {
                $borrow_arr = Db::name('stock_borrow')->where(['status' => 1])->order('verify_time', 'desc')->select();
                // print_r($borrow_arr);die;//->where('id=272')->where('stock_subaccount_id=222')
                if (!empty($borrow_arr)) {
                    foreach ($borrow_arr as $key => $val) {
                        if ($val['type'] != 4) {
                            $position_stock_arr = Db::name('stock_position')->where('sub_id', $val['stock_subaccount_id'])->field('gupiao_code,stock_count')->select();
                            $market_val         = '';
                            $gupiao_code_arr    = array();
                            $code_arr           = array();
                            if (!empty($position_stock_arr)) {
                                foreach ($position_stock_arr as $value) {
                                    $gupiao_code_arr[$value['gupiao_code']] = $value['stock_count'];
                                    $code_arr[]                             = $value['gupiao_code'];
                                }
                            }
                            $new_code_arr = z_market_bat(implode(',', $code_arr));
                            foreach ($new_code_arr as $k => $v) {
                                $market_val += $gupiao_code_arr[$v['code']] * $v['current_price'];
                            }
                            $subaccount_money_arr = Db::name('stock_subaccount_money')->where('stock_subaccount_id', $borrow_arr[$key]['stock_subaccount_id'])->field('avail,freeze_amount,deposit_money,borrow_money')->find();
                            //  print_r($subaccount_money_arr);
                            $subaccount_loss = Db::name('stock_subaccount_risk')->where('stock_subaccount_id', $borrow_arr[$key]['stock_subaccount_id'])->field('loss_warn,loss_close')->find();
                            //  print_r($subaccount_loss);
                            $subaccount_loss_warn  = $subaccount_loss['loss_warn'] / 100;
                            $subaccount_loss_close = $subaccount_loss['loss_close'] / 100;
                            $loss_warn             = money_convert($subaccount_money_arr['borrow_money']) + money_convert($subaccount_money_arr['deposit_money']) * $subaccount_loss_warn;
                            //echo $loss_warn;
                            //echo "</br>";
                            $now_init_amount = $market_val + money_convert($subaccount_money_arr['avail']) + money_convert($subaccount_money_arr['freeze_amount']);
                            //	echo $now_init_amount;
                            //echo "</br>";
                            //止损线=保证金*止损比例+配资金额-市值-可用余额-冻结资金
                            $loss_close = money_convert($subaccount_money_arr['borrow_money']) + money_convert($subaccount_money_arr['deposit_money']) * $subaccount_loss_close;
                            //预警线
                            //  $operation = sprintf("%.2f", ($now_init_amount - $loss_warn));
                            //平仓线
                            //$close = sprintf("%.2f", ($now_init_amount - $loss_close));
                            //保证金*扩大倍数=配资金额
                            //配资金额+保证金=操盘金额
                            //配资金额+保证金的50%=等于警戒线
                            //配资金额+保证金的20%=平仓线
                            //配资金额
                            $peizi = $val['deposit_money'] * $val['multiple'];
                            //预警线
                            $operation = sprintf("%.2f", ($peizi + $val['deposit_money'] * 0.5));
                            //平仓线
                            $close = sprintf("%.2f", ($peizi + $val['deposit_money'] * 0.2));
                            Db::name('stock_borrow')->where("id", $val['id'])->update(['operation' => $operation, 'close' => $close, 'uptime' => time()]);
                        }
                    }
                }
            }
        }
    }

    /*
     * 股权登记
    */
    public static function register()
    {
        $binfo = Db::name("stock_bonus")->where('addtime', ">=", mktime(0, 0, 0))->where(['type' => 1])->find();
        if (!empty($binfo)) {
            return;
        }
        $info         = substr(qq_bonus(), 13);
        $info         = json_decode($info, true);
        $stock_record = $info['data']['076015']['data']; //登记日
        $res          = [];
        $rul          = '((\\-|\\+)?\\d+(\\.\\d+)?)';
        foreach ($stock_record as $k => $v) { //echo $k."</br>";
            $res[$k] = Handle($v, $k, $rul) [$k];
            if ($res[$k] == false) {
                unset($res[$k]);
                continue;
            }
            $res[$k]['date']    = $v['date'];
            $res[$k]['code']    = $v['zqdm'];
            $res[$k]['name']    = $v['zqjc'];
            $res[$k]['info']    = "登记日";
            $res[$k]['type']    = 1;
            $res[$k]['addtime'] = time();
            $p_info             = Db::name("stock_position")->where(["gupiao_code" => $res[$k]['code']])->select();
            if (empty($p_info)) {
                continue;
            } else {
                $data = $res[$k];
                foreach ($p_info as $kk => $vv) {
                    $data['lid']               = $vv['lid'];
                    $data['soruce']            = $vv['soruce'];
                    $data['login_name']        = $vv['login_name'];
                    $data['trust_no']          = $vv['trust_no']; //盈亏比例
                    $data['type']              = $vv['type'];     //帐号类别
                    $data['jigou_type']        = $vv['jigou_type'];
                    $data['jiyisuo']           = $vv['jiyisuo']; //交易所
                    $data['sub_id']            = $vv['sub_id'];
                    $data['position_id']       = $vv['id'];
                    $data['status']            = 1; //状态，1、未除权
                    $data['stock_count']       = $vv['stock_count'];
                    $data['buy_average_price'] = $vv['buy_average_price'];
                    $bp_res                    = Db::name("stock_bonus_position")->insert($data); //dump($bp_res);
                }
                unset($data);
            }
        }
        $result = Db::name("stock_bonus")->insertAll($res);
    }

    /*
        * 分红公告
       */
    public static function dividend()
    {
        $info         = substr(qq_bonus(), 13);
        $info         = json_decode($info, true);
        $stock_record = $info['data']['076015']['data']; //登记日
        $res          = [];
        $rul          = '((\\-|\\+)?\\d+(\\.\\d+)?)';
        foreach ($stock_record as $k => $v) { //echo $k."</br>";
            $data = Handle($v, $k, $rul)[$k];

            if ($data == false) {
                unset($res[$k]);
                continue;
            }
            $find = Db::name("stock_bonus")->where(['code' => $v['zqdm'], 'date' => $v['date']])->find();
            if ($find) {
                continue;
            }
            $res[$k]['date']    = $v['date'];
            $res[$k]['code']    = $v['zqdm'];
            $res[$k]['name']    = $v['zqjc'];
            $res[$k]['info']    = "登记日";
            $res[$k]['type']    = 1;
            $res[$k]['addtime'] = time();
        }
        if (empty($res)) {
            return '';
        }

        $result = Db::name("stock_bonus")->insertAll($res);
    }

    /*
     * 除权日
    */
    public static function ex_dividend()
    {
        $binfo = Db::name("stock_bonus")->where('addtime', ">=", mktime(0, 0, 0))->where(['type' => 2])->find();

        if (!empty($binfo)) {
            return;
        }
        $info         = substr(qq_bonus(), 13);
        $info         = json_decode($info, true);
        $stock_record = $info['data']['076016']['data']; //除权
        $res          = [];
        $rul          = '((\\-|\\+)?\\d+(\\.\\d+)?)';
        foreach ($stock_record as $k => $v) {
            $res[$k] = Handle($v, $k, $rul) [$k];
            if ($res[$k] == false) {
                unset($res[$k]);
                continue;
            }
            $res[$k]['date']    = $v['date'];
            $res[$k]['code']    = $v['zqdm'];
            $res[$k]['name']    = $v['zqjc'];
            $res[$k]['info']    = "除权日";
            $res[$k]['type']    = 2;
            $res[$k]['addtime'] = time();
            $bp_info            = Db::name("stock_bonus_position")->where(["code" => $res[$k]['code']])->where(["status" => 1])->select();
            if (empty($bp_info)) {
                continue;
            } else {
                $subaccount = new SubAccountMoney();
                foreach ($bp_info as $kk => $vv) {
                    Db::startTrans();
                    $submoney_info = Db::name("stock_subaccount_money")->where(['stock_subaccount_id' => $vv['sub_id']])->lock()->find();
                    $sm_res        = $sp_res = $rec_1 = $zp_res = $rec_2 = $bp_res = $bp_ret = true;
                    if ($vv['bonus'] > 0) {
                        if (!empty($submoney_info)) {
//                            echo $vv['bonus'];
//                            echo "\r\n";
                            //除息公式 10股红利/10*100（转为分）*登记股票数
                            $fee    = $vv['bonus'] * 10 * $vv['stock_count'];
                            $sm_res = $subaccount->up_moneylog($vv['sub_id'], $fee, 13, 0, 0, $v['zqdm'], $vv['stock_count']);
                            $p_info = Db::name("stock_position")->where(['id' => $vv['position_id']])->find();
                            if (!empty($p_info)) {
                                $p1data['ck_price']          = ($p_info['buy_average_price'] * $p_info['stock_count'] - $vv['bonus'] * $vv['stock_count'] / 10) / $p_info['stock_count'];
                                $p1data['buy_average_price'] = $p1data['ck_price'];
                                $p1data['ck_profit_price']   = $p1data['ck_price'];
                                $bp_ret                      = Db::name("stock_position")->where(['id' => $vv['position_id']])->update($p1data);
                                unset($p1data);
                            }
                        }
                    }
                    if ($vv['song'] > 0) {
                        $p_info = Db::name("stock_position")->where(['id' => $vv['position_id']])->find();
                        if (!empty($p_info)) {
                            $bcount                      = $vv['song'] / 10 * $vv['stock_count'];
                            $p2data['stock_count']       = $p_info['stock_count'] + $bcount;
                            $p2data['count']             = $p_info['stock_count'] + $bcount;
                            $p2data['canbuy_count']      = $p_info['stock_count'] + $bcount;
                            $p2data['ck_price']          = $p_info['buy_average_price'] * $p_info['stock_count'] / $p2data['stock_count'];
                            $p2data['buy_average_price'] = $p2data['ck_price'];
                            $p2data['ck_profit_price']   = $p2data['ck_price'];
                            $sp_res                      = Db::name("stock_position")->where(['id' => $vv['position_id']])->update($p2data);
                            $info                        = "(=)用户股票" . $vv["code"] . "除权除息送股共计" . $bcount . "股";
                            $rec_1                       = $subaccount::record($vv['sub_id'], 0, $submoney_info['avail'], 14, $info);
                        } else {
                            $bcount                      = $vv['zuan'] / 10 * $vv['stock_count'];
                            $p2data['sub_id']            = $vv['sub_id'];
                            $p2data['lid']               = $vv['lid'];
                            $p2data['soruce']            = $vv['soruce'];
                            $p2data['login_name']        = $vv['login_name'];
                            $p2data['gupiao_code']       = $vv["code"];
                            $p2data['gupiao_name']       = $vv["name"];
                            $p2data['stock_count']       = $bcount;
                            $p2data['count']             = $bcount;
                            $p2data['canbuy_count']      = $bcount;
                            $p2data['ck_price']          = 0;
                            $p2data['buy_average_price'] = $p2data['ck_price'];
                            $p2data['ck_profit_price']   = $p2data['ck_price'];
                            $p2data['now_price']         = $vv['buy_average_price'];           //'当前价'
                            $p2data['market_value']      = $vv['buy_average_price'] * $bcount; //最新市值
                            $p2data['ck_profit']         = 0;                                  //参考浮动盈亏
                            $p2data['profit_rate']       = 0;                                  //盈亏比例
                            $p2data['trust_no']          = $vv['trust_no'];                    //盈亏比例
                            $p2data['buying']            = 0;                                  //买入成功
                            $p2data['selling']           = 0;                                  //1、在途卖出
                            $p2data['gudong_code']       = "";                                 //股东代码 无法模拟暂时空
                            $p2data['type']              = $vv['type'];                        //帐号类别
                            $p2data['jigou_type']        = $vv['jigou_type'];
                            $p2data['jiyisuo']           = $vv['jiyisuo']; //交易所
                            $p2data['info']              = "";
                            $zp_res                      = Db::name("stock_position")->insert($p2data);
                            $info                        = "(=)用户股票" . $vv["code"] . "除权除息送股共计" . $bcount . "股";
                            $rec_1                       = $subaccount::record($vv['sub_id'], 0, $submoney_info['avail'], 14, $info);
                        }
                        unset($p2data);
                        unset($bcount);
                    }
                    if ($vv['zuan'] > 0) {
                        $p_info = Db::name("stock_position")->where(['id' => $vv['position_id']])->find();
                        if (!empty($p_info)) {
                            $b3count                     = $vv['zuan'] / 10 * $vv['stock_count'];
                            $p3data['stock_count']       = $p_info['stock_count'] + $b3count;
                            $p3data['count']             = $p_info['stock_count'] + $b3count;
                            $p3data['canbuy_count']      = $p_info['stock_count'] + $b3count;
                            $p3data['ck_price']          = $p_info['buy_average_price'] * $p_info['stock_count'] / $p3data['stock_count'];
                            $p3data['buy_average_price'] = $p3data['ck_price'];
                            $p3data['ck_profit_price']   = $p3data['ck_price'];
                            $zp_res                      = Db::name("stock_position")->where(['id' => $vv['position_id']])->update($p3data);
                            $info                        = "(=)用户股票" . $vv["code"] . "除权除息转增共计" . $b3count . "股";
                            $rec_2                       = $subaccount::record($vv['sub_id'], 0, $submoney_info['avail'], 14, $info);
                        } else {
                            $bcount                      = $vv['zuan'] / 10 * $vv['stock_count'];
                            $p3data['sub_id']            = $vv['sub_id'];
                            $p3data['lid']               = $vv['lid'];
                            $p3data['soruce']            = $vv['soruce'];
                            $p3data['login_name']        = $vv['login_name'];
                            $p3data['gupiao_code']       = $vv["code"];
                            $p3data['gupiao_name']       = $vv["name"];
                            $p3data['stock_count']       = $bcount;
                            $p3data['count']             = $bcount;
                            $p3data['canbuy_count']      = $bcount;
                            $p3data['ck_price']          = 0;
                            $p3data['buy_average_price'] = $p3data['ck_price'];
                            $p3data['ck_profit_price']   = $p3data['ck_price'];
                            $p3data['now_price']         = $vv['buy_average_price'];           //'当前价'
                            $p3data['market_value']      = $vv['buy_average_price'] * $bcount; //最新市值
                            $p3data['ck_profit']         = 0;                                  //参考浮动盈亏
                            $p3data['profit_rate']       = 0;                                  //盈亏比例
                            $p3data['trust_no']          = $vv['trust_no'];                    //盈亏比例
                            $p3data['buying']            = 0;                                  //买入成功
                            $p3data['selling']           = 0;                                  //1、在途卖出
                            $p3data['gudong_code']       = "";                                 //股东代码 无法模拟暂时空
                            $p3data['type']              = $vv['type'];                        //帐号类别
                            $p3data['jigou_type']        = $vv['jigou_type'];
                            $p3data['jiyisuo']           = $vv['jiyisuo']; //交易所
                            $p3data['info']              = "";
                            $zp_res                      = Db::name("stock_position")->insert($p3data);
                            $info                        = "(=)用户股票" . $vv["code"] . "除权除息转增共计" . $bcount . "股";
                            $rec_2                       = $subaccount::record($vv['sub_id'], 0, $submoney_info['avail'], 14, $info);
                        }
                        unset($p3data);
                        unset($bcount);
                    }
                    $data              = $vv;
                    $ex_data['status'] = 2;
                    $ex_res            = Db::name("stock_bonus_position")->where(["id" => $vv['id']])->update($ex_data);
                    unset($data['id']);
                    $m_info     = Db::view("stock_subaccount s", 'sub_account,uid')->view('member m', 'mobile,name', 'm.id=s.uid', 'left')->where(['s.id' => $vv['sub_id']])->find();
                    $trade_time = Db::name("stock_deal_stock")->where(['sub_id' => $vv['sub_id']])->where(['gupiao_code' => $vv["code"]])->where(['flag2' => '证券买入'])->max('deal_date');
                    if (empty($trade_time)) {
                        $data['trade_time'] = time() - 86400;
                    } else {
                        $data['trade_time'] = $trade_time;
                    }
                    $data['date']        = $v['date'];
                    $data['addtime']     = time();
                    $data['status']      = 2;
                    $data['ex_count']    = 0;
                    $data['mobile']      = $m_info['mobile'];
                    $data['sub_account'] = $m_info['sub_account'];
                    $data['uname']       = $m_info['name'];
                    $bp_res              = Db::name("stock_bonus_dividend")->insert($data);
                    if ($sm_res && $sp_res && $rec_1 && $zp_res && $rec_2 && $bp_res && $ex_res && $bp_ret) {
                        Db::commit();
                    } else {
                        Db::rollback();
                    }
                }
            }
        }
        $result = Db::name("stock_bonus")->insertAll($res);
    }

    /**
     * 除权除夕相关   12点
     * @return void
     */
    public static function ex()
    {
        self::dividend();
        self::updatestockTrust();//委托订单清零
        if (time() > mktime(16, 0, 0)) {
            self::register();
        }
        if (time() < mktime(8, 0, 0)) {
            self::ex_dividend();
        }
    }

    /*
     * 日处理任务
     *  需要两点46分执行
     *
    */
    public static function day()
    {
        printlog('', '开始执行需求', 'Crond-day');
        self::ex();
        //自动续期定时任务
        $b_res    = Db::view('stock_borrow b')->view('stock_subaccount_risk r', 'renewal', 'r.stock_subaccount_id=b.stock_subaccount_id', 'left')->view('money m', 'account', 'm.mid=b.member_id', 'left')
            //   ->where(['b.status' => 1])
            ->where('b.status', 'in', [1, 3])->select();
        $borrow   = new Borrow();
        $position = new Position();
        //$res=$position->field('sub_id')->where(['buying'=>0])->group('sub_id')->select();
        if (!empty($b_res)) {
            foreach ($b_res as $k => $v) {
//                配资类型 5:免费配资 1:按天配资 2:按周配资 3:按月配资 4:试用配资
//                4:试用配资
//                5:免费配资
//                6：模拟操盘
                $type_arr = [4, 5, 6];
                if (in_array($v['type'], $type_arr) && $v['end_time'] <= time() && $v['end_time'] != 0 && $v['status'] != 2) {
                    printlog($v, '需要处理列表', 'Crond-day');
                    //免息配资、试用配资
                    //当前时间需要小于14点45 或者 当前时间大于15点
                    if (time() < mktime(14, 45, 0) || time() > mktime(15, 0, 0)) {
                        printlog('只能在2点45到3点间执行', '判断是否可以执行', 'Crond-day');
                        #   continue;
                    }
                    switch ($v['type']) {
                        case 4:
                            $typeinfo = "试用配资";
                            break;
                        case 5:
                            $typeinfo = "免息配资";
                            break;
                        case 6:
                            $typeinfo = "模拟操盘";
                            break;
                        default:
                            $typeinfo = "免息配资";
                            break;
                    }
                    $subname = Db::name('stock_subaccount')->where(['id' => $v['stock_subaccount_id']])->find();
                    //self::sendsms_to_admin($typeinfo."子账户:".$subname['sub_account'].",已到期，已强制平仓");
                    //$name=Db::name('member')->where(['id'=>$subname['uid']])->find();
                    //self::send_sms($name['mobile'],'','您的'.$typeinfo.'子账户:'.$subname['sub_account'].',已到期，已强制平仓');
                    $res = $position->where(['sub_id' => $v['stock_subaccount_id']])->where(['buying' => 0])->select();
                    if (empty($res)) {
                        $time = date('Y-m-d H:i:s', time()) . '子账户“' . $v['stock_subaccount_id'] . '”持仓为空' . PHP_EOL;
                        printlog($time, '子账户持仓信息', 'Crond-day');
                    } else {
                        $time = date('Y-m-d H:i:s', time()) . '子账户“' . $v['stock_subaccount_id'] . '”持仓不为空' . PHP_EOL;
                        printlog($time, '子账户持仓信息', 'Crond-day');
                        foreach ($res as $key => $val) {
                            //                        $lis = Db::name('stock_position')->where('gupiao_code',$code)->where('sub_id',$val['sub_id'])->field('buy_average_price')->find();
                            //                        $average_price = $lis['buy_average_price'];
                            $sell_res = $borrow->savesell($val['sub_id'], $val['gupiao_code'], $val['canbuy_count'], $sys = 1);

//                        $sell_res['message'] = iconv("UTF-8", "GB2312//IGNORE", $sell_res['message']);
                            if (isset($sell_res['status']) && $sell_res['status'] == 0) {
                                $msg = $val['gupiao_code'] . '卖出时产生错误：' . $sell_res['message'];
                                Log::write($msg, 'notice');
                                printlog($msg, '卖出时产生错误', 'Crond-day');
                            } else {
                                $msg = $typeinfo . "子账户:" . $subname['sub_account'] . ",已到期，已强制平仓";
                                Log::write($msg);
                                printlog($msg, '卖出时产生错误', 'Crond-day');
                            }
                        }
                    }
                    //去结算
                    $actionEnd = $borrow->actionEnd($v['stock_subaccount_id'], $v['member_id'], $v['id']);
                    if ($actionEnd) {
                        echo $v['b.stock_subaccount_id'] . "结算2成功\r\n";
                    } else {
                        echo "提交失败\r\n";
                    }
//                    continue;
                }
                //判断是否需要自动续期
                if ($v['end_time'] <= time() && $v['renewal'] === 1 && $v['end_time'] != 0) {
                    printlog('', '进入处理需要续期的账号', 'Crond-day');
                    // if ( date('Ymd',$v['end_time']) ==  date('Ymd',time()) && $v['renewal'] === 1 && $v['end_time'] != 0) {
                    //收取利息
                    $peiziMoney      = $v['borrow_money'];
                    $multiple        = $v['multiple'];
                    $borrow_duration = 1;
                    switch ($v['type']) {
                        case 1:
                            $rateSet = self::config('day_rate');
                            $rate    = $rateSet[$multiple] / 100;
                            break;
                        case 2:
                            $rateSet = self::config('week_rate');
                            $rate    = $rateSet[$multiple] / 100;
                            break;
                        case 3:
                            $rateSet = self::config('month_rate');
                            $rate    = $rateSet[$multiple] / 100;
                            break;
                        default:
                            $rate = 0;
                            break;
                    }
                    //利息 * 时间 * 配资金额
                    // 延期一次性计算利息
                    $minfo = Member::getMemberInfoByID($v['member_id']);
                    //bcmu 用于将两个任意精度的数字相乘
                    $borrow_fee = bcmul($peiziMoney, $rate) * $borrow_duration;
                    //可还利息余额=实际用户余额+活动资金余额
                    $amoney = $minfo['account'] + $minfo['activity_account'];
                    if ($borrow_fee > $amoney) {
                        Db::startTrans();
                        $bdata['status']         = 3; //
                        $bs_res                  = Db::name("stock_borrow")->where(['id' => $v['id']])->update($bdata);
                        $rdata['prohibit_open']  = 0;
                        $rdata['prohibit_close'] = 0;
                        $risk_res                = Db::name("stock_subaccount_risk")->where(['stock_subaccount_id' => $v['stock_subaccount_id']])->update($rdata);
                        if ($bs_res && $risk_res) {
                            Db::commit();
                            $msg = "配资" . $v['order_id'] . "已到期，已强制平仓\r\n";
                            Log::write($msg, 'info');
                            //file_put_contents(ROOT_PATH . '/public/log/xuqi-' . date("y-m-d") . '.txt', $msg, FILE_APPEND);
                        } else {
                            Db::rollback();
                            $borrow_fee = $borrow_fee / 100;
                            $amoney     = $amoney / 100;
                            $msg        = "配资" . $v['order_id'] . "资金不足无法续期进入逾期状态失败，延期一次性计算利息:{$borrow_fee}元,余额：{$amoney}元\r\n";
                            Log::write($msg, 'info');
                            //file_put_contents(ROOT_PATH . '/public/log/xuqi-' . date("y-m-d") . '.txt', $msg, FILE_APPEND);
                        }
                        //file_put_contents(ROOT_PATH . '/public/log/xuqi-' . date("y-m-d") . '.txt', "余额不足先跳过\r\n", FILE_APPEND);
                        continue; //余额不足先跳过
                    }
                    $infomoney                = $borrow_fee / 100; //转化为元
                    $sdata['borrow_duration'] = $borrow_duration + $v['borrow_duration'];
                    $sdata['borrow_interest'] = $v['borrow_interest'] + $borrow_fee;
                    Db::startTrans();
                    $sdata['end_time'] = Renewal::getAddTime($v['type'], $v['end_time'], $borrow_duration);
                    $borrow_res        = Db::name("stock_borrow")->where("id={$v['id']}")->update($sdata);
                    $minfo_a           = Db::name("money")->lock(true)->where(['mid' => $v['member_id']])->find();
                    if ($minfo_a['activity_account'] >= $borrow_fee) {
                        //如果活动金额大于 则直接全部扣除活动金额
                        //$account = $minfo_a['activity_account'] - $borrow_fee;
                        $account          = $minfo_a['account'];
                        $account_activity = $minfo_a['activity_account'] - $borrow_fee;
                        $kc_mode          = "活动资金扣除:" . ($borrow_fee / 100) . "元";
                        $money_ret        = Money::money_freeze_activity_account($v['member_id'], $account_activity); //直接扣费
                        $obj              = ['affect'          => 0, 'account' => $account,
                                             'affect_activity' => -abs($borrow_fee), 'activity_account' => $minfo_a['activity_account'], 'sn' => ''
                        ];
                    } else {
                        //包含合并扣除，先扣除活动资金
                        //扣除活动自己能扣的金额
                        $subtraction_activity_account = $borrow_fee - $minfo_a['activity_account'];
                        //更新活动资金
                        $money_ret = Money::money_freeze_activity_account($v['member_id'], 0); //更新活动资金
                        $account   = $minfo_a['account'] - $subtraction_activity_account;
                        $kc_mode   = "余额扣除，活动资金扣除:" . $minfo_a['activity_account'] . "元,系统余额扣除:" . $subtraction_activity_account / 100 . "元";
                        $money_ret = Money::money_freeze($v['member_id'], $minfo_a['freeze'], $account); //直接扣费
                        $obj       = ['affect'          => -abs($subtraction_activity_account), 'account' => $account,
                                      'affect_activity' => -abs($minfo_a['activity_account']), 'activity_account' => 0, 'sn' => ''
                        ];
                    }
                    $info       = "配资" . $v['order_id'] . "自动续期，扣除续期利息{$infomoney}元，{$kc_mode}";
                    $record     = new Record;
                    $Record_Ret = $record->saveData($v['member_id'], -abs($borrow_fee), $account, 34, $info, '', '', $obj); //23:配资续期
                    if ($Record_Ret && $money_ret && $borrow_res) {
                        //self::send_sms($minfo['mobile'],'', $info);
                        $MemberMessageModel = new MemberMessageModel();
                        $MemberMessageModel->addInnerMsg($minfo_a['mid'], '自动续期成功通知', $info, 7, $v['id']); //站内信
                        Db::commit();
                        //根据佣金比例分配佣金 用户id 配资id 配资管理费
                        $BorrowModel = new Borrow();
                        $res_agent   = $BorrowModel->agentToRateMoney($v['member_id'], $v['id'], $infomoney, 4);
                        Log::write("配资" . $v['order_id'] . "自动续期成功", 'info');
                    } else {
                        Db::rollback();
                        Log::write("配资" . $v['order_id'] . "自动续期失败", 'info');
                    }
                } elseif ($v['end_time'] <= time() && $v['renewal'] === 0 && $v['end_time'] != 0) {
                    printlog('', '进入处理不需要续期的账号', 'Crond-day');
                    Db::startTrans();
                    $bdata['status']         = 3; //
                    $bs_res                  = Db::name("stock_borrow")->where(['id' => $v['id']])->update($bdata);
                    $rdata['prohibit_open']  = 0;
                    $rdata['prohibit_close'] = 1;
                    $risk_res                = Db::name("stock_subaccount_risk")->where(['stock_subaccount_id' => $v['stock_subaccount_id']])->update($rdata);
                    if ($bs_res && $risk_res) {
                        Db::commit();
                        Log::write("配资" . $v['order_id'] . "进入逾期状态", 'info');
                    } else {
                        Db::rollback();
                        Log::write("配资" . $v['order_id'] . "进入逾期状态失败", 'info');
                    }
                }
            }
        }
        //按月配资自动扣费
        $now        = time();
        $stock_list = Db::view('stock_detail sd', true)->view('stock_borrow b', 'stock_subaccount_id', 'sd.borrow_id=b.id', 'left')->where(["sd.status" => 0])->select();
        $tempArr    = [];
        $submoney   = new SubAccountMoney();
        $record     = new Record();
        foreach ($stock_list as $k => $v) {
            if ($v['deadline'] < $now) {
                $tempArr[$k] = $v;
            }
        }
        //执行还款
        foreach ($tempArr as $k => $v) {
            // 扣除手续费，优先从余额扣除，如果余额不足，从保证金里扣除
            Db::startTrans();
            $user_money = Db::name("money")->field('freeze,account,activity_account')->lock(true)->where("mid = " . $v['mid'])->find();
            //直接从余额扣除
            //if($user_money['account'] >= $v['interest']){
            //    $data['account'] = $user_money['account'] - $v['interest'];
            //  $sub_ret = true;
            //            }else{
            //                $data['account'] =0;
            //                $sub_id=$v['stock_subaccount_id'];
            //                $sub_res=$submoney->get_account_money($sub_id);
            //                $affect_money = $sub_res['deposit_money'] + $user_money['account'] - $v['interest'];
            //                $sub_ret =$submoney->up_moneylog($sub_id,$affect_money,7);
            //            }
            if ($user_money['activity_account'] >= $v['interest']) {
                $data['activity_account'] = $user_money['activity_account'] - $v['interest'];
                $sub_ret                  = true;
                $kc_mode                  = "活动资金扣除";
                $obj                      = ['affect' => 0, 'account' => $data['account'], 'affect_activity' => $v['interest'], 'activity_account' => $data['activity_account'], 'sn' => ''];
            } else {
                $data['account'] = $user_money['account'] - $v['interest'];
                $sub_ret         = true;
                $kc_mode         = "余额扣除";
                $obj             = ['affect' => $v['interest'], 'account' => $data['account'], 'affect_activity' => 0, 'activity_account' => $user_money['activity_account'], 'sn' => ''];
            }
            //更新用户资金
            $updateMoney = Db::name('money')->where("mid=" . $v['mid'])->update($data);
            //增加用户变动记录
            $info = "按月配资手续费自动扣款，扣除方式：{$kc_mode}";
//            $record_ret = $record->saveData($v['mid'], $v['interest'], $data['account'], 32, $info);
            $record_ret = $record->saveData($v['mid'], $v['interest'], $data['account'], 32, $info, '', '', $obj);
            if ($record_ret && $updateMoney && $sub_ret) {
                $d_data['status']         = 1;
                $d_data['repayment_time'] = $now;
                $stock_detail_update      = Db::name("stock_detail")->where("id=" . $v['id'])->update($d_data);
                if ($stock_detail_update) {
                    Db::commit();
                    Log::write("会员" . $v['id'] . "自动扣除手续费成功，扣除方式：{$kc_mode}", 'info');
                } else {
                    Db::rollback();
                    Log::write("会员" . $v['id'] . "自动扣除手续费失败，扣除方式：{$kc_mode}", 'info');
                }
            } else {
                Db::rollback();
                Log::write("会员" . $v['id'] . "自动扣除手续费失败，扣除方式：{$kc_mode}", 'info');
            }
        }
        return;
    }

    /**
     * 处理平仓 自动补子账户余额任务
     */
    public static function samount()
    {
        if (!get_trading_time('','time')) {
            echo '交易时间不能操作';
            return;
        }
        //获取全部系统卖出订单  以获得子帐号
        $orderlist = Db::view('stock_delivery_order d')->view('stock_borrow b', 'stock_subaccount_id', 'b.stock_subaccount_id=d.sub_id', 'left')->where(['d.sys' => 1, 'b.status' => 1])->group("sub_id")->paginate(1000);
        $sub_id    = $sub_id2 = '';
        $listsid   = array();
        foreach ($orderlist as $v) {
            $sub_id .= $v['sub_id'] . ',';
            array_push($listsid, $v['sub_id']);
        }
        $sub_id = substr($sub_id, 0, strlen($sub_id) - 1);
        //获得系统平仓后 还有有持仓的信息
        $map[]      = ['sub_id', 'in', $sub_id];
        $data_list2 = Db::view('stock_position p')->where($map)->select();
        //移除有持仓的子帐号
        foreach ($data_list2 as $v) {
            $sid = $v['sub_id'];
            foreach ($listsid as $k => $vv) {
                if ($sid == $vv) {
                    unset($listsid[$k]);
                    $sub_id2 .= $v['sub_id'] . ',';
                }
            }
        }
        $sub_id2                     = substr($sub_id2, 0, strlen($sub_id2) - 1);
        $map2['stock_subaccount_id'] = array(
            'in',
            $sub_id2
        );
        //查出需要补钱的账户金额信息
        $data_list = Db::view('stock_subaccount_money')->where($map2)->select();
        foreach ($data_list as $v) {
            if ($v['avail'] < $v['borrow_money']) {
                $updata = array(
                    "avail" => $v['borrow_money']
                );
                //进行补钱操作
                $updatainfo = Db::name('stock_subaccount_money')->where("stock_subaccount_id={$v['stock_subaccount_id']}")->update($updata);
                if ($updatainfo) {
                    $affect           = $v['borrow_money'] - $v['avail'];
                    $hs_avail         = $v['avail'] / 100;
                    $hs_deposit_money = $v['borrow_money'] / 100;
                    $info             = "系统自动执行，对被平仓子账户进行补余额，原数值为{$hs_avail},调整后为{$hs_deposit_money}.";
                    //插入额外子账户调整纪录
                    Operate::insetsubmoney_record($v['stock_subaccount_id'], $affect, $v['borrow_money'], 22, $info);
                }
            }
        }
        echo "补钱成功";
    }

    /**
     * 自动重算 批量操作成本价
     ***
     */
    public static function cb_pire()
    {
        if (!get_trading_time('','time')) {
            echo '非交易时间';
            Log::write('非交易时间');
            return;
        }
        $SubAccountMoney = new SubAccountMoney();
        $SubAccountMoney->more_cb_pire();       //更新成本价
        $SubAccountMoney->account_profit_loss();//更新子账户的账户总盈亏和账户账户可提赢金额
    }

    /**
     * 处理平仓 任务
     */
    public static function minute()
    {
        if (!get_trading_time('','time')) {
            echo '非交易时间';
            Log::write('非交易时间');
            return;
        }
        //处理成本价
        self::cb_pire();
        //自动平仓开始
        $position = new Position();
        //股票仓储表
        $res        = Db::name("stock_position")->field('sub_id')->where(['buying' => 0])->group('sub_id')->select();
        $moneymodel = new SubAccountMoney();
        $risk       = new StockSubAccountRisk();
        $borrow     = new Borrow();
        printlog($res, '需要平仓列表', 'crondTask-pc');
        if (!empty($res)) {
            foreach ($res as $k => $v) {
                $sub_id = $v['sub_id'];
                //查出对于股票仓促表对应的子账号
                $binfo = Db::name("stock_borrow")->where(['stock_subaccount_id' => $sub_id, 'type' => 4])->find();
                if (!empty($binfo)) {
                    continue;
                }
                //获取子账号配资信息
                $moneyinfo = $moneymodel->get_account_money($sub_id);
                //查询子账号风控信息
                $risk_res = $risk->get_risk($sub_id);
                //如果关闭了自动平仓则跳过
                if ($risk_res['autoclose'] === 0) {
                    continue;
                }
                //如果关闭了自动平仓则跳过
                //$position_res = $position->get_position($sub_id);
                $position_res = Db::name('stock_position')->where(['sub_id' => $sub_id])->where(['buying' => 0])->select();
                printlog($position_res, '$position_res', 'crondTask-pc');
                $sum        = 0;
                $totalprice = 0;
                if (count($position_res) >= 2) {
                    $code   = "";
                    $counts = array();
                    foreach ($position_res as $key => $val) {
                        $code                        = $code . $val['gupiao_code'] . ",";
                        $counts[$val['gupiao_code']] = $val['stock_count'];
                        $totalprice                  += $val['buy_average_price'] * $val['stock_count'];
                    }
                    $code = substr($code, 0, -1);
                    printlog($code, '股票代码', 'crondTask-pc');
                    $p_res = z_market_bat($code);
                    if (empty($p_res)) {
                        printlog($p_res, '请求接口数据有误，数据为空1', 'crondTask-pc');
                        die();
                    }
                    printlog($p_res, '数据为接口', 'crondTask-pc');
                    printlog($p_res, '数据为接口', 'crondTask-pc');
                    printlog($p_res, '数据为接口', 'crondTask-pc');
                    foreach ($p_res as $key => $vv) {
                        if (empty($vv["current_price"]) || $vv["current_price"] < 1) {
                            continue;
                        }
                        $sum += $counts[$vv["code"]] * $vv["current_price"];
                        //   $vv["current_price"]=0;
                        if (empty($vv["current_price"])) {
                            $str = "股票数据有误:\r\n\t股票代码:" . $vv["code"] . "\r\n\t实时价格:" . $vv["current_price"] . "\r\n\t持仓总数:" . $counts[$vv["code"]] . "\r\n";
                            printlog($str, '操作数据提示', 'crondTask-pc');
                            die();
                        }
                        printlog($vv["code"], 't股票代码', 'crondTask-pc');
                        printlog($vv["current_price"], 't实时价格', 'crondTask-pc');
                        printlog($counts[$vv["code"]], 't持仓总数', 'crondTask-pc');
                    }
                } elseif (count($position_res) == 1) {
                    $code  = $position_res[0]['gupiao_code'];
                    $p_res = z_market($code);
                    if (empty($p_res)) {
                        printlog($p_res, '请求接口数据有误，数据为空2', 'crondTask-pc');
                        die();
                    }
                    //当前持仓股票总价格=实时价格*持仓总数
                    $sum = $p_res["current_price"] * $position_res[0]['stock_count'];
                    //  $p_res["current_price"]=0;
                    if (empty($p_res["current_price"])) {
                        $str = "股票数据有误:\r\n\t股票代码:" . $code . "\r\n\t实时价格:" . $p_res["current_price"] . "\r\n\t持仓总数:" . $position_res[0]['stock_count'] . "\r\n";
                        printlog($str, '股票数据有误,tishi ', 'crondTask-pc');
                        die();
                    }
                    printlog($p_res["current_price"], '股票数据 ', 'crondTask-pc');
                    printlog($code, 't股票代码', 'crondTask-pc');
                    printlog($p_res["current_price"], 't实时价格', 'crondTask-pc');
                    printlog($position_res[0]['stock_count'], 't持仓总数', 'crondTask-pc');
                    //均价总数=买入均价*持仓总数
                    $totalprice = $position_res[0]['buy_average_price'] * $position_res[0]['stock_count'];
                }
                $subaccount_money = Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $sub_id])->find();
                //保证金*扩大倍数=配资金额
                //配资金额+保证金=操盘金额
                //配资金额+保证金的50%=等于警戒线
                //配资金额+保证金的20%=平仓线
                //止损线=保证金*止损比例+配资金额 累计追加保证金 不考虑累计提取盈利
                $p_ress = ($moneyinfo["deposit_money"] * $risk_res['loss_close'] / 100 + $moneyinfo["borrow_money"]) / 100;
                //     echo " p_ress : $p_ress <br>\r\n";
                //单个子账户的余额=市值+子账户余额+冻结金额
                #$pc = $sum + money_convert($subaccount_money['avail']) + money_convert($subaccount_money['freeze_amount']);
                $where                       = array(
                    "sub_id" => $sub_id,
                    "flag2"  => "买入委托",
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
                $pc = $sum + money_convert($subaccount_money['avail']) + $market_freeze_amount_buy;
                //        echo "pc:$pc<br>\r\n";
                //     echo "<br>\r\n";          echo "<br>\r\n";          echo "<br>\r\n";
                printlog($sub_id, '子账户', 'crondTask-pc');
                printlog($p_ress, 't平仓线', 'crondTask-pc');
                printlog($pc, 't市值', 'crondTask-pc');
                printlog($code, 't股票代码', 'crondTask-pc');
                printlog($sum, 't市值SUM', 'crondTask-pc');
                printlog(money_convert($subaccount_money['avail']), 't子账户余额', 'crondTask-pc');
                printlog(money_convert($subaccount_money['freeze_amount']), 't冻结金额', 'crondTask-pc');
                $jl = $pc - $p_ress;
                printlog($jl, '距离平仓线', 'crondTask-pc');
                printlog($sub_id, '单个子账户的余额，stock_subaccount_id', 'crondTask-pc');
                printlog($p_ress, '平仓线', 'crondTask-pc');
                //                echo "用户：" . $sub_id;
                //                echo "<br>";
                //                echo "平仓线：" . $p_ress;
                //                echo "<br>";
                //                echo "单个子账户的余额：" . $pc;
                //
                //                echo "<br>";
                //                echo "<br>";
                //                die();
                if ($pc < $p_ress) {
                    printlog('可以平仓', '可以平仓', 'crondTask-pc');
                    $str = "平仓记录-stock_subaccount_id:" . $sub_id . ",平仓线：" . $p_ress . ",子账户的总值：" . $pc;
                    printlog($str, '平仓记录', 'crondTask-pc');
                    printlog($sub_id, 'stock_subaccount_id', 'crondTask-pc');
                    printlog($p_ress, '平仓线', 'crondTask-pc');
                    printlog($pc, '子账户的余额', 'crondTask-pc');
                    $subname      = Db::name('stock_subaccount')->where(['id' => $sub_id])->find();
                    $order_id_sms = Db::name('stock_borrow')->where('stock_subaccount_id', $v['sub_id'])->value('order_id');
                    //sendsms_to_admin("子账户:".$subname['sub_account'].",达止损线，已强制平仓");
                    //短信提醒
                    $name    = Db::name('member')->where(['id' => $subname['uid']])->find();
                    $content = \think\Config::get('sms_template') ['stock_loss_close'];
                    $content = str_replace(array(
                        "#var#",
                        "#order_id#"
                    ), array(
                        $name['mobile'],
                        $order_id_sms
                    ), $content);
                    $num     = 1;
                    foreach ($position_res as $key => $val) {
                        //平仓数据入库
                        $info                 = array();
                        $info['sub_id']       = $val['sub_id'];
                        $info['gupiao_code']  = $val['gupiao_code'];
                        $info['canbuy_count'] = $val['canbuy_count'];
                        $info['addtime']      = time();
                        Db::name('stock_position_pc')->insert($info, true);
                        echo "\r\n";
                        echo $val['sub_id'];
                        echo "\r\n";
                        echo $val['gupiao_code'];
                        echo "\r\n";
                        echo $val['canbuy_count'];
                        echo "\r\n";
                        if (empty($val['canbuy_count'])) {
                            continue;
                        }
                        $sell_res = $borrow->savesell($val['sub_id'], $val['gupiao_code'], $val['canbuy_count'], $sys = 1);
                        if (!isset($sell_res['status'])) {
                            printlog('卖出失败', '平仓报错', 'crondTask-pc');
                            continue;
                        }
                        printlog($sell_res, '卖出状态', 'crondTask-pc');
                        if ($sell_res['status'] == 0) {
                            printlog('卖出子账号:' . $v['sub_id'] . '的' . $val['canbuy_count'] . "股" . $val['gupiao_code'] . '股票时产生错误：' . $sell_res['message'], '平仓报错', 'crondTask-pc');
                        } elseif ($num == 1 && $sell_res['status'] == 1) {
                            //    sendsms_mandao($name['mobile'], $content, 'user');
                            $num += 1;
                        }
                    }
                }
                printlog('不可以可以平仓', '不可以可以平仓', 'crondTask-pc');
            }
        }
        printlog('', '结束', 'crondTask-pc');
        //自动平仓结束
        return;
    }

    /*
     * 分处理任务
    */
    public static function minute11()
    {
        if (!get_trading_time('','time')) {
            Log::write('非交易时间');
            return;
        }
        //自动平仓开始
        $position   = new Position();
        $res        = Db::name("stock_position")->field('sub_id')->where(['buying' => 0])->group('sub_id')->select();
        $moneymodel = new SubAccountMoney();
        $risk       = new StockSubAccountRisk();
        $borrow     = new Borrow();
        if (!empty($res)) {
            foreach ($res as $k => $v) {
                $sub_id = $v['sub_id'];
                $binfo  = Db::name("stock_borrow")->where(['stock_subaccount_id' => $sub_id, 'type' => 4])->find();
                if (!empty($binfo)) {
                    continue;
                }
                $moneyinfo = $moneymodel->get_account_money($sub_id);
                $risk_res  = $risk->get_risk($sub_id);
                if ($risk_res['autoclose'] === 0) {
                    continue;
                }                                                                                                                                                                    //如果关闭了自动平仓则跳过
                $position_res = $position->get_position($sub_id);
                $sum          = 0;
                if (count($position_res) >= 2) {
                    $code   = "";
                    $counts = array();
                    foreach ($position_res as $key => $val) {
                        $code                        = $code . $val['gupiao_code'] . ",";
                        $counts[$val['gupiao_code']] = $val['stock_count'];
                    }
                    $code  = substr($code, 0, -1);
                    $p_res = z_market_bat($code);
                    foreach ($p_res as $key => $vv) {
                        $sum += $counts[$vv["code"]] * $vv["current_price"];
                    }
                } elseif (count($position_res) == 1) {
                    $code  = $position_res[0]['gupiao_code'];
                    $p_res = z_market($code);
                    $sum   = $p_res["current_price"] * $position_res[0]['stock_count'];
                }
                //止损线=保证金*止损比例+配资金额-市值-可用余额-累计追加保证金 不考虑累计提取盈利
                $p_rate = $moneyinfo["deposit_money"] * $risk_res['loss_close'] / 100 + $moneyinfo["borrow_money"] - $sum * 100 - $moneyinfo["avail"] - $moneyinfo["freeze_amount"]; //- $moneyinfo["stock_addmoney"];
                if ($p_rate > 0) {
                    $subname      = Db::name('stock_subaccount')->where(['id' => $sub_id])->find();
                    $order_id_sms = Db::name('stock_borrow')->where('stock_subaccount_id', $v['sub_id'])->value('order_id');
                    //sendsms_to_admin("子账户:".$subname['sub_account'].",达止损线，已强制平仓");
                    $name    = Db::name('member')->where(['id' => $subname['uid']])->find();
                    $content = \think\Config::get('sms_template') ['stock_loss_close'];
                    $content = str_replace(array(
                        "#var#",
                        "#order_id#"
                    ), array(
                        $name['mobile'],
                        $order_id_sms
                    ), $content);
                    $num     = 1;
                    foreach ($position_res as $key => $val) {
                        $lis           = Db::name('stock_position')->where('gupiao_code', $code)->where('sub_id', $val['sub_id'])->field('buy_average_price')->find();
                        $average_price = $lis['buy_average_price'];
                        $sell_res      = $borrow->savesell($val['sub_id'], $val['gupiao_code'], $val['canbuy_count'], $sys = 1, $average_price);
                        if (!isset($sell_res['status'])) {
                            continue;
                        }
                        if ($sell_res['status'] == 0) {
                            Log::write('卖出子账号:' . $v['sub_id'] . '的' . $val['canbuy_count'] . "股" . $val['gupiao_code'] . '股票时产生错误：' . $sell_res['message'], 'notice');
                        } elseif ($num == 1 && $sell_res['status'] == 1) {
                            sendsms_mandao($name['mobile'], $content, 'user');
                            $num += 1;
                        }
                    }
                }
            }
        }
        //自动平仓结束
        return;
    }

    public function detail($code = '600000')
    {
        $d  = fenxi($code);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://qt.gtimg.cn/q=" . $d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $t2       = explode('~', mb_convert_encoding($output, "utf-8", "gbk"));
        $t2['29'] = explode('|', $t2['29']);
        $ret      = [];
        foreach ($t2['29'] as $k => $v) {
            $ret[$k]    = explode('/', $v);
            $tmd[$k]    = $ret[$k][1];
            $ret[$k][1] = $ret[$k][2];
            $ret[$k][2] = $tmd[$k];
        }
        if ($ret == []) {
            return null;
        }
        return $ret;
    }

    public function detail_23()
    {
        $d     = fenxi(600000);
        $d     = fenxi(600000);
        $randm = time() . mt_rand(100, 999);
        echo "http://vip.stock.finance.sina.com.cn/quotes_service/view/CN_TransListV2.php?num=10&symbol=" . $d . "&rn=" . $randm;
    }

    public function detail_($code = '600000')
    {
        $d     = fenxi($code);
        $randm = time() . mt_rand(100, 999);
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://vip.stock.finance.sina.com.cn/quotes_service/view/CN_TransListV2.php?num=10&symbol=" . $d . "&rn=" . $randm);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $tmd = explode(';', $output);
        if (count($tmd) == 3) {
            return null;
        }
        unset($tmd[0]);
        unset($tmd[11]);
        unset($tmd[12]);
        $res  = [];
        $ret  = [];
        $time = date('h:i:s', time() - 12);
        foreach ($tmd as $k => $v) {
            $num     = strpos($v, 'Array(') + 7;
            $res[$k] = explode(',', str_replace('\'', '', substr($v, $num, -2)));
            if ($res[$k][0] > $time) {
                $ret[$k][0] = trim($res[$k][0]);
                $ret[$k][1] = trim($res[$k][1]);
                $ret[$k][2] = trim($res[$k][2]);
                $ret[$k][3] = trim($res[$k][3]);
            }
        }
        if ($ret == []) {
            return null;
        }
        return $ret;
    }

    /*
      * 处理持仓当前价格
     */
    public static function position()
    {
        $data_list = Db::name('stock_position')->where(['buying' => 0])->select();
        $update    = array();
        foreach ($data_list as $k => $v) {
            $res = self::aliyun_market($v['gupiao_code']);
            // $res = self::aliyun_to_api($res[0]);
            $update[$k]['now_price']    = $res["current_price"];
            $update[$k]['market_value'] = $res["current_price"] * $v['stock_count'];                                                //最新市值
            $update[$k]['ck_profit']    = round(($res["current_price"] - $v['buy_average_price']) * $v['stock_count'], 2);          //参考浮动盈亏
            $update[$k]['profit_rate']  = round($update[$k]['ck_profit'] / ($v['buy_average_price'] * $v['stock_count']) * 100, 2); //盈亏比例
            echo "\r\n";
            echo $updatainfo = Db::name('stock_position')->where(["sub_id" => $v['sub_id'], "gupiao_code" => $v['gupiao_code']])->update(array(
                "now_price" => $res['current_price']
            ));
            echo "\r\n";
            echo Db::name('stock_position')->getLastSql();
            echo "\r\n";
        }
    }

    /*
     * 限价交易处理任务+
    */
    public static function temp()
    {
        if (!get_trading_time('','time')) {
            Log::write('非交易时间');
            return;
        }
        $data = Db::name('stock_temp')->where("trust_date", ">=", time() - 154001)->where(["deal_no" => null])->select();
        //没有未经处理的任务
        if ($data === false || count($data) === 0) {
            Log::write('没有未经处理的委托');
            echo '没有未经处理的委托';
            return;
        }
        $subaccount = new SubAccountMoney();
        foreach ($data as $k => $v) {
            if (self::config('trust_active_time') == 0) {
                if (time() >= mktime(15, 0, 0)) {
                    $cancel_res = self::cancel_order($v); //撤单
                    if ($cancel_res['status']) {
                        Log::write('子账号' . $v['sub_id'] . '的' . $v['flag2'] . $v['trust_count'] . '股' . $v['gupiao_code'] . '自动撤单成功');
                        echo '子账号' . $v['sub_id'] . '的' . $v['flag2'] . $v['trust_count'] . '股' . $v['gupiao_code'] . "自动撤单成功\n\r";
                        continue;
                    } else {
                        Log::write('子账号' . $v['sub_id'] . '的' . $v['flag2'] . $v['trust_count'] . '股' . $v['gupiao_code'] . '自动撤单失败');
                        echo '子账号' . $v['sub_id'] . '的' . $v['flag2'] . $v['trust_count'] . '股' . $v['gupiao_code'] . "自动撤单失败:(\n\r";
                        continue;
                    }
                }
            } elseif ((time() - $v['add_time']) / 60 >= self::config('trust_active_time') || time() >= mktime(15, 0, 0)) {
                $cancel_res = self::cancel_order($v); //撤单
                if ($cancel_res['status']) {
                    Log::write('子账号' . $v['sub_id'] . '的' . $v['flag2'] . $v['trust_count'] . '股' . $v['gupiao_code'] . '自动撤单成功');
                    echo '子账号' . $v['sub_id'] . '的' . $v['flag2'] . $v['trust_count'] . '股' . $v['gupiao_code'] . "自动撤单成功\n\r";
                    continue;
                } else {
                    Log::write('子账号' . $v['sub_id'] . '的' . $v['flag2'] . $v['trust_count'] . '股' . $v['gupiao_code'] . '自动撤单失败');
                    echo '子账号' . $v['sub_id'] . '的' . $v['flag2'] . $v['trust_count'] . '股' . $v['gupiao_code'] . "自动撤单失败:(\n\r";
                    continue;
                }
            }
            $res = self::aliyun_market($v['gupiao_code']);
            if (empty($res)) {
                continue;
            }
            if (self::array_depth($res) < 2) {
                $res = array(
                    $res
                );
            }
            if ($v['flag2'] == "买入委托") {
                foreach ($res as $kk => $vv) {
                    if ($vv['current_price'] <= 0) {  //最新价
                        continue;
                    }
                    // 加了一个容错方式
                    // 判断 实时价格 的上下幅度是否超过 委托价格 的15%
                    printlog($vv, "买-行情数据", "crond-buy-sell");
                    $a_current_price = $vv['current_price']; // 实时价格
                    printlog($a_current_price, "买-实时价格", "crond-buy-sell");
                    $b_trust_price = $v['trust_price'];  // 委托价格
                    printlog($b_trust_price, "买-委托价格", "crond-buy-sell");
                    // 计算 $b 的15%的范围
                    $threshold = $b_trust_price * 0.15;
                    printlog($threshold, "买-15%的范围", "crond-buy-sell");
                    if ($a_current_price < $b_trust_price - $threshold || $a_current_price > $b_trust_price + $threshold) {
                        // 超过了15%的范围
                        printlog('超过了15%的范围', "判断", "crond-buy-sell");
                        continue;
                    }
                    if ($vv['current_price'] <= $v['trust_price']) {
                        Db::startTrans();
                        $trust                      = [];
                        $trust['status']            = "已成";
                        $trust['cancel_order_flag'] = "1";
                        $trust['volume']            = $v['volume'];                                                                //成交量
                        $trust['amount']            = $vv['current_price'] * $v['trust_count'];
                        $trust_res                  = Db::name('stock_trust')->where(['trust_no' => $v['trust_no']])->update($trust);
                        //  print_r("\r\ntrust_res:".$trust_res);
                        $data['status']            = "已成";
                        $data['cancel_order_flag'] = "1";
                        $data['deal_no']           = $v['trust_no'] + 521;
                        $demp_res                  = Db::name('stock_temp')->where(['id' => $v['id']])->update($data);
                        // print_r("\r\ndemp_res:".$demp_res);
                        $trade_money = Db::name('stock_delivery_order')->where(['trust_no' => $v['trust_no']])->value('liquidation_amount');
                        if (empty($trade_money)) {
                            Db::rollback();
                            Log::write('买入确认时未查询到交易费用信息');
                            return;
                        }
                        $moneymodel = new SubAccountMoney();
                        $moneyinfo  = $moneymodel->get_account_money($v['sub_id']);
                        //佣金的算法=数量*现价*佣金比例/10000
                        $commission = round($v['trust_count'] * $vv['current_price'] * $moneyinfo['commission_scale'] / 10000, 2); //佣金
                        if ($commission < $moneyinfo['min_commission'] / 100) {
                            $commission = $moneyinfo['min_commission'] / 100;
                        }
                        $type = self::typefenxi($v['gupiao_code']);
                        if ($type == 2) {                                                           //上海交易所
                            //过户费=数量*现价/50000
                            $Transfer = round($v['trust_count'] * $vv['current_price'] / 50000, 2); //过户费,只有上海交易所收
                            if ($Transfer < self::config('transfer_fee')) {
                                $Transfer = self::config('transfer_fee');
                            }
                        } else {
                            $Transfer = 0;
                        }
                        //印花税+佣金
                        $fee      = $commission + $Transfer;
                        $dev_info = Db::name('stock_delivery_order')->where(['trust_no' => $v['trust_no']])->find();
                        //净佣金+交易费-（$fee：印花税+佣金）
                        $cha = $dev_info['commission'] + $dev_info['transfer_fee'] - $fee;
                        //(委托数量*委托价格)-股票当前价格+（$cha：净佣金+交易费-（$fee：印花税+佣金））
                        $Balance = round($v['trust_count'] * ($v['trust_price'] - $vv['current_price']) + $cha, 2);
                        $sm_res  = $subaccount->up_moneylog($v['sub_id'], $trade_money * 100, 31, $return_money = 0, $Balance * 100, $v['gupiao_code'], $v['trust_count']);
                        //  print_r("\r\nsm_res:".$sm_res);
                        $deal                      = [];
                        $deal['status']            = "买入成交"; //状态说明
                        $deal['cancel_order_flag'] = '1';        //撤单标志 1、已成交
                        $deal['deal_price']        = $vv['current_price'];
                        $deal['deal_time']         = date("H:i:s", time());
                        $deal['amount']            = $vv['current_price'] * $v['trust_count'];
                        printlog($v['trust_no'], 'trust_no', 'CROND-stock_deal_stock');
                        printlog($deal, '委托买入成交', 'CROND-stock_deal_stock');
                        printlog($vv, '行情信息', 'CROND-stock_deal_stock');
                        $deal_res = Db::name('stock_deal_stock')->where(['trust_no' => $v['trust_no']])->update($deal);
                        //   print_r("\r\ndeal_res:".$deal_res);
                        $ptmd                           = Db::name('stock_position')->where(['sub_id' => $v['sub_id'], 'gupiao_code' => $v['gupiao_code']])->find();
                        $delivery                       = [];
                        $delivery['status']             = '1';
                        $delivery['deal_price']         = $vv['current_price'];
                        $delivery['deal_date']          = time();
                        $delivery['transfer_fee']       = $Transfer;
                        $delivery['commission']         = $commission;
                        $delivery['residual_quantity']  = $deal['amount'];
                        $delivery['liquidation_amount'] = $v['trust_count'] * $vv['current_price'] + $fee;
                        $delivery['residual_amount']    = $dev_info['residual_amount'] + $dev_info['liquidation_amount'] - $delivery['liquidation_amount'];
                        if (!empty($ptmd)) {
                            $delivery['amount'] = $ptmd['canbuy_count'] + $v['trust_count'];
                        } else {
                            $delivery['amount'] = $v['trust_count'];
                        }
                        $delivery_res = Db::name('stock_delivery_order')->where(['trust_no' => $v['trust_no']])->update($delivery);
                        //   print_r("\r\ndelivery_res:".$delivery_res);
                        if (empty($ptmd)) {
                            $continue = false;
                            switch (substr($v['gupiao_code'], 0, 1)) {
                                case '0':
                                    $d = 0;
                                    break;
                                case '3':
                                    $d = 0;
                                    break;
                                case '1':
                                    $d = 0;
                                    break;
                                case '2':
                                    $d = 0;
                                    break;
                                case '6':
                                    $d = 1;
                                    break;
                                case '5':
                                    $d = 1;
                                    break;
                                case '9':
                                    $d = 1;
                                    break;
                                default:
                                    Db::rollback();
                                    echo "买入确认失败\n\r";
                                    $continue = true;
                                    break;
                            }
                            if ($continue) {
                                continue;
                            }
                            //盈亏成本价=(股票数量*当前价格+产生交易费用（印花税+交易服务费）)/数量
                            $ck_price                      = round(($v['trust_count'] * $vv['current_price'] + $fee) / $v['trust_count'], 3);
                            $position                      = array();
                            $position['sub_id']            = $v['sub_id'];
                            $position['lid']               = $v['lid'];
                            $position['soruce']            = $v['soruce'];
                            $position['login_name']        = $v['login_name'];
                            $position['gupiao_code']       = $v["gupiao_code"];
                            $position['gupiao_name']       = $v["gupiao_name"];
                            $position['count']             = $v['trust_count'];
                            $position['stock_count']       = $v['trust_count'];
                            $position['canbuy_count']      = $v['trust_count'];
                            $position['ck_price']          = $ck_price;
                            $position['now_price']         = $vv['current_price'];
                            $position['buy_average_price'] = $ck_price;
                            $position['ck_profit_price']   = $ck_price;                                                                //参考盈亏成本价
                            $position['market_value']      = $vv['current_price'] * $v['trust_count'];                                 //最新市值
                            $position['ck_profit']         = round(($vv['current_price'] - $ck_price) * $v['trust_count'], 2);         //参考浮动盈亏
                            $position['profit_rate']       = round($position['ck_profit'] / ($ck_price * $v['trust_count']) * 100, 2); //盈亏比例
                            $position['buying']            = 0;
                            $position['selling']           = 0;  //1、在途卖出
                            $position['gudong_code']       = ""; //股东代码 无法模拟暂时空
                            $position['type']              = $d; //帐号类别
                            $position['jigou_type']        = 1;
                            $position['jiyisuo']           = $d == 0 ? "深交所" : "上交所"; //交易所
                            $position['info']              = "";
                            $position_res                  = Db::name('stock_position')->insert($position, true);
                            //   print_r("\r\nposition_res:".$position_res);
                        } else {
                            $position           = $ptmd;
                            $position['buying'] = 0;
                            $canbuy_count       = $ptmd['canbuy_count'] + $v['trust_count'];
                            $stock_count        = $ptmd['stock_count'] + $v['trust_count'];
                            //买入均价=（实际持仓数量*买入均价+当前委托数量*当前股票价格+过户费+佣金）/（实际持仓数量+当前委托数量）
                            $new_price = round(($ptmd['stock_count'] * $ptmd['buy_average_price'] + $v['trust_count'] * $vv['current_price'] + $fee) / ($ptmd['stock_count'] + $v['trust_count']), 3);
                            //$new_price = $ptmd['buy_average_price'];
                            $position['count']             = $stock_count;
                            $position['stock_count']       = $stock_count;
                            $position['canbuy_count']      = $canbuy_count;
                            $position['ck_price']          = $new_price;                                                            //参考成本价
                            $position['buy_average_price'] = $new_price;                                                            //买入均价
                            $position['ck_profit_price']   = $new_price;                                                            //参考盈亏成本价
                            $position['now_price']         = $vv['current_price'];                                                  //'当前价'
                            $position['market_value']      = $vv['current_price'] * $canbuy_count;                                  //最新市值
                            $position['ck_profit']         = round(($vv['current_price'] - $new_price) * $canbuy_count, 2);         //参考浮动盈亏
                            $position['profit_rate']       = round($position['ck_profit'] / ($new_price * $canbuy_count) * 100, 2); //盈亏比例
                            $position_res                  = Db::name('stock_position')->where(['sub_id' => $v['sub_id']])->where(['gupiao_code' => $v['gupiao_code']])->update($position);
                        }
                        if ($sm_res & $trust_res && $demp_res && $deal_res && $delivery_res && $position_res) {
                            Db::commit();
                            echo "买入确认成功\n\r";
                        } else {
                            Db::rollback();
                            echo "买入确认失败\n\r";
                        }
                        break;
                    } else {
                        continue;
                        //dump("买入价:".$v['trust_price'].'|现价:'.$vv['current_price'].'|时间:'.$vv[0]);
                    }
                }
            } elseif ($v['flag2'] == "卖出委托") {
                foreach ($res as $kk => $vv) {
                    if ($vv['current_price'] <= 0) {
                        continue;
                    }
                    // 加了一个容错方式
                    printlog($vv, "买-行情数据", "crond-buy-sell");
                    // 判断 实时价格 的上下幅度是否超过 委托价格 的15%
                    $a_current_price = $vv['current_price']; // 实时价格
                    printlog($a_current_price, "卖-实时价格", "crond-buy-sell");
                    $b_trust_price = $v['trust_price'];  // 委托价格
                    printlog($b_trust_price, "卖-委托价格", "crond-buy-sell");
                    // 计算 $b 的15%的范围
                    $threshold = $b_trust_price * 0.15;
                    printlog($threshold, "卖-15%的范围", "crond-buy-sell");
                    if ($a_current_price < $b_trust_price - $threshold || $a_current_price > $b_trust_price + $threshold) {
                        // 超过了15%的范围
                        printlog('超过了15%的范围', "判断", "crond-buy-sell");
                        continue;
                    }
                    if ($vv['current_price'] >= $v['trust_price']) {
                        Db::startTrans();
                        $trust                      = [];
                        $trust['status']            = "已成";
                        $trust['cancel_order_flag'] = "1";
                        $trust['volume']            = $v['trust_count']; //$v['volume'];
                        $trust['amount']            = $vv['current_price'] * $v['trust_count'];
                        $trust_res                  = Db::name('stock_trust')->where(['trust_no' => $v['trust_no']])->update($trust);
                        $data['status']             = "已成";
                        $data['cancel_order_flag']  = "1";
                        $data['deal_no']            = $v['trust_no'] + 521;
                        $demp_res                   = Db::name('stock_temp')->where(['id' => $v['id']])->update($data);
                        $trade_money                = Db::name('stock_delivery_order')->where(['trust_no' => $v['trust_no']])->value('liquidation_amount');
                        $stamp                      = round($v['trust_count'] * $vv['current_price'] * self::config('stamp_duty') / 1000, 2); //印花税
                        $moneymodel                 = new SubAccountMoney();
                        $moneyinfo                  = $moneymodel->get_account_money($v['sub_id']);
                        $commission                 = round($v['trust_count'] * $vv['current_price'] * $moneyinfo['commission_scale'] / 10000, 2); //佣金
                        if ($commission < $moneyinfo['min_commission'] / 100) {
                            $commission = $moneyinfo['min_commission'] / 100;
                        }
                        $type = self::typefenxi($v['gupiao_code']);
                        if ($type == 2) {                                                           //上海交易所
                            $Transfer = round($v['trust_count'] * $vv['current_price'] / 50000, 2); //过户费,只有上海交易所收
                            if ($Transfer < self::config('transfer_fee')) {
                                $Transfer = self::config('transfer_fee');
                            }
                        } else {
                            $Transfer = 0;
                        }
                        $fee      = $commission + $Transfer + $stamp;
                        $dev_info = Db::name('stock_delivery_order')->where(['trust_no' => $v['trust_no']])->find();
                        $cha      = $dev_info['commission'] + $dev_info['transfer_fee'] + $dev_info['stamp_duty'] - $fee;
                        $Balance  = round(($vv['current_price'] - $v['trust_price']) * $v['trust_count'] + $cha, 2);
                        if (empty($trade_money)) {
                            Db::rollback();
                            Log::write('卖出确认时未查询到交易费用信息');
                            return;
                        }
                        $type = 5;                                                                                                                                                                                                                                                                                       //限价卖出股票回款
                        $ptmd = Db::name('stock_position')->where(['sub_id' => $v['sub_id'], 'gupiao_code' => $v['gupiao_code']])->find();
                        //零散股票处理开始
                        $lll = $v['trust_count'] - $v['volume'];
                        if ($lll > 0 && $lll % 100 > 0 && !empty($ptmd)) {
                            $bonus = $ptmd;
                            unset($bonus['sub_id']);
                            $count_tmd                = $lll % 100;
                            $bonus['count'] = $count_tmd;
                            $bonus['stock_count'] = $count_tmd;
                            $bonus['canbuy_count'] = $count_tmd;
                            $bonus['ck_price'] = $vv['current_price'];
                            $bonus['buy_average_price'] = $vv['current_price'];
                            $bonus['ck_profit_price'] = $vv['current_price'];
                            $bonus['now_price']       = $vv['current_price'];
                            $bonus['market_value']    = $vv['current_price'] * $bonus['stock_count'];
                            $sp_info                  = Db::name('stock_bonus_surplus')->where(['gupiao_code' => $v['gupiao_code']])->find();
                            if (empty($sp_info)) {
                                $bonus_res = Db::name('stock_bonus_surplus')->insert($bonus);
                            } else {
                                $bonus['count']           = $bonus['count'] + $sp_info['count'];
                                $bonus['stock_count'] = $bonus['stock_count'] + $sp_info['stock_count'];
                                $bonus['canbuy_count'] = $bonus['canbuy_count'] + $sp_info['canbuy_count'];
                                $bonus['ck_price'] = ($vv['current_price'] * $count_tmd + $sp_info['ck_price'] * $sp_info['stock_count']) / $bonus['stock_count'];
                                $bonus['buy_average_price'] = $bonus['ck_price'];
                                $bonus['ck_profit_price'] = $bonus['ck_price'];
                                $bonus['now_price']       = $vv['current_price'];
                                $bonus['market_value']    = $vv['current_price'] * $bonus['stock_count'];
                                $bonus_res                = Db::name('stock_bonus_surplus')->where(['gupiao_code' => $v['gupiao_code']])->update($bonus);
                            }
                        }
                        //零散股票处理结束
                        //参考成本价=（实际持仓数量*买入均价+当前委托数量*当前股票价格+过户费+佣金）/（实际持仓数量+当前委托数量）
                        //盈亏金额=（现价-参考成本价）*委托数量- 过户费（只有上海交易所收）-印花税-佣金
                        $return_money = ($vv['current_price'] - $ptmd['ck_price']) * $v['trust_count'] - $Transfer - $stamp - $commission;
                        $Cost         = $Transfer + $stamp + $commission;
                        $strino       = $v['trust_count'] . "，账面未体现金额{$Cost}，已被当成交易费用从余额扣除";
                        $sm_res       = $subaccount->up_moneylog($v['sub_id'], $trade_money * 100, $type, $return_money * 100, $Balance * 100, $v['gupiao_code'], $strino);
                        //这里本应该要把交易过程产生的费用单独这一条记录，暂时先这样入库
                        //  $sm_res = $subaccount->up_moneylog($v['sub_id'], $trade_money * 100, 16, $return_money * 100, $Balance * 100, $v['gupiao_code'],$strino);
                        //扣除利息税开始
                        $div_info = Db::name("stock_bonus_dividend")->where(['sub_id' => $v['sub_id']])->where(['code' => $v['gupiao_code']])->find();
                        if (!empty($div_info) && $div_info['ex_count'] < $div_info['stock_count']) {
                            $tmd = mktime(0, 0, 0) - $div_info['trade_time'];
                            //默认20%利息税
                            $rate = 0.2;
                            //30天内收取10%
                            if ($tmd > 86400 * 30) {
                                $rate = 0.1;
                            }
                            if ($tmd <= 86400 * 365) {
                                $ex_cha = $div_info['stock_count'] - $div_info['ex_count'];
                                $tmd_count = $ex_cha <= $v['trust_count'] ? $ex_cha : $v['trust_count'];
                                $feen   = round($tmd_count * $div_info['bonus'] / 10 * $rate + $tmd_count * $div_info['song'] / 10 * $vv['current_price'] * $rate, 2);
                                if ($feen > 0) {
                                    $div_res = $subaccount->up_moneylog($v['sub_id'], $feen * 100, 15, 0, 0, $v['gupiao_code'], $tmd_count);
                                    if ($div_res) {
                                        $div_data['ex_count'] = $div_info['ex_count'] + $tmd_count;
                                        $ret                  = Db::name("stock_bonus_dividend")->where(['id' => $div_info['id']])->update($div_data);
                                    }
                                } else {
                                    //如果$fee=0说明是转增，没有分红和送股，不用扣利息税
                                }
                            } else {
                                //持有超一年，不用扣利息税
                            }
                        }
                        //扣除利息税结束
                        $deal                      = [];                        //状态说明
                        $deal['status'] = "卖出成交";                                                                                                                                                                                                                                  //状态说明
                        $deal['cancel_order_flag'] = '1';                                                                                                                                                                                                                              //撤单标志 1、已成交
                        $deal['deal_price']        = $vv['current_price'];
                        $deal['deal_time']         = date("H:i:s", time());
                        $deal['amount']            = $vv['current_price'] * $v['trust_count'];
                        $deal_res                  = Db::name('stock_deal_stock')->where(['trust_no' => $v['trust_no']])->update($deal);
                        $delivery                  = [];
                        $delivery['status']        = '1';
                        if (!empty($ptmd)) {
                            $delivery['amount'] = $ptmd['canbuy_count'];
                        }
                        $delivery['deal_price']         = $vv['current_price'];       //成交价
                        $delivery['buy_price']          = $ptmd['buy_average_price']; //买入价
                        $delivery['deal_date']          = time();
                        $delivery['stamp_duty']         = $stamp;
                        $delivery['transfer_fee']       = $Transfer;
                        $delivery['commission']         = $commission;
                        $delivery['residual_quantity']  = $deal['amount'];
                        $delivery['liquidation_amount'] = $v['trust_count'] * $vv['current_price'] - $fee;
                        $delivery['residual_amount']    = $moneyinfo['avail'] / 100 + $Balance;
                        $delivery_res                   = Db::name('stock_delivery_order')->where(['trust_no' => $v['trust_no']])->update($delivery);
                        if (empty($ptmd)) {
                            $position_res = false;
                        } else {
                            $stock_count = $ptmd['stock_count'] - $v['trust_count'];
                            if ($stock_count == 0) {
                                $position_res = Db::name('stock_position')->where(['sub_id' => $v['sub_id']])->where(['gupiao_code' => $v['gupiao_code']])->delete();
                                log::write("子账户" . $v['sub_id'] . "清空" . $v['gupiao_code']);
                            } elseif ($stock_count > 0) {
                                $new_price = round(($ptmd['stock_count'] * $ptmd['buy_average_price'] - $v['trust_count'] * $vv['current_price'] - $fee) / $stock_count, 3);
                                // $new_price= $ptmd['buy_average_price'];
                                $position                      = array();
                                $position['count']             = $stock_count;
                                $position['stock_count']       = $stock_count;
                                $position['canbuy_count']      = $ptmd['canbuy_count'];
                                $position['ck_price']          = $new_price;                                                                    //参考成本价
                                $position['buy_average_price'] = $new_price;                                                                    //买入均价
                                $position['ck_profit_price']   = $new_price;                                                                    //参考盈亏成本价
                                $position['now_price']         = $vv['current_price'];                                                          //'当前价'
                                $position['market_value']      = $vv['current_price'] * $ptmd['canbuy_count'];                                  //最新市值
                                $position['ck_profit']         = round(($vv['current_price'] - $new_price) * $ptmd['canbuy_count'], 3);         //参考浮动盈亏
                                $position['profit_rate']       = round($position['ck_profit'] / ($new_price * $ptmd['canbuy_count']) * 100, 2); //盈亏比例
                                $position['selling']           = 0;
                                $position['buying']            = 0;
                                $position_res                  = Db::name('stock_position')->where(['sub_id' => $v['sub_id']])->where(['gupiao_code' => $v['gupiao_code']])->update($position);
                            } else {
                                $position_res = false;
                            }
                        }
                        if ($sm_res & $trust_res && $demp_res && $deal_res && $delivery_res && $position_res) {
                            Db::commit();
                            echo "卖出确认成功\n\r";
                        } else {
                            Db::rollback();
                            echo "卖出确认失败\n\r";
                        }
                        break;
                    } else {
                        continue;
                        //dump("卖出价:".$v['trust_price'].'|现价:'.$vv['current_price'].'|时间:'.$vv[0]);
                    }
                }
            }
        }
        return;
    }

    //应该是取消订到
    public static function cancel_order($v)
    {
        $subaccount = new SubAccountMoney();
        Db::startTrans();
        $yes      = false;
        $tempinfo = Db::name('stock_temp')->where(['trust_no' => $v['trust_no']])->lock(true)->find();
        if (!empty($tempinfo)) {
            $type = self::typefenxi($tempinfo['gupiao_code']);
            if ($type == 0) {
                Db::rollback();
                return ['status' => 0, 'message' => '股票代码不对，撤单失败'];
            }
        } else {
            Db::rollback();
            return ['status' => 0, 'message' => '没找到对应委托，撤单失败'];
        }
        $affect_money = Db::name('stock_delivery_order')->where(['trust_no' => $v['trust_no']])->value('liquidation_amount');
        if ($tempinfo['deal_no'] == null) {
            $trust['status']             = "已撤";
            $trust['cancel_order_flag']  = "1";
            $trust['cancel_order_count'] = $v['trust_count'];
            $trust_res                   = Db::name('stock_trust')->where(['trust_no' => $v['trust_no']])->update($trust);
            $deal_res                    = Db::name('stock_deal_stock')->where(['trust_no' => $v['trust_no']])->delete();
            $delivery_res                = Db::name('stock_delivery_order')->where(['trust_no' => $v['trust_no']])->delete();
            $ret                         = Db::name('stock_temp')->where(['trust_no' => $v['trust_no']])->delete();
            $position                    = Db::name('stock_position')->where(['sub_id' => $v['sub_id']])->where(['gupiao_code' => $tempinfo['gupiao_code']])->find();
            $subm_res                    = false;
            $position_in                 = false;
            if ($tempinfo['flag2'] == '买入委托') {
                //解冻并转入子账户可用余额
                $subm_res    = $subaccount->up_moneylog($v['sub_id'], $affect_money * 100, 8);
                $position_in = true;
            } elseif ($tempinfo['flag2'] == '卖出委托') {
                $position['canbuy_count'] = $position['canbuy_count'] + $v['trust_count'];
                $position_in              = Db::name('stock_position')->where(['sub_id' => $v['sub_id']])->where(['gupiao_code' => $tempinfo['gupiao_code']])->update($position);
                $subm_res                 = $subaccount->up_moneylog($v['sub_id'], $affect_money * 100, 9);
            }
            if ($trust_res && $deal_res && $delivery_res && $ret && $subm_res && $position_in) {
                $yes = true;
            } else {
                Db::rollback();
                return ['status' => 0, 'message' => '撤单失败'];
            }
        }
        $submodel = new StockSubAccount();
        $res      = $submodel->get_account_by_id($v['sub_id']);
        $broker   = $submodel->get_broker($res['account_id']);
        $trade_id = $broker['id'];
        $res      = Db::name('admin_plugin')->where(['name' => "GreenSparrow"])->find();
        if (!empty($res) && $yes && $broker['broker'] != -1) {
            $day_re = [];
            if (config('web_real_api') == 1) {
                $day_re = gs('queryTradeData', [$broker['id'], 3]);
            }
            if (config('web_real_api') == 2) {
                $day_re = Grid::grid_category_trust($broker['id']);
            }
            unset($day_re[0]);
            $orderid = "";
            foreach ($day_re as $kt => $vt) {
                if ($vt[4] == $tempinfo['gupiao_code'] && $vt[9] == $tempinfo['trust_count'] && $vt[7] == $tempinfo['flag2']) {
                    $orderid = $vt[10]; //得到实盘委托编号
                }
            }
            $data = [];
            if (!empty($orderid)) {
                if (config('web_real_api') == 1) {
                    $data = gs('cancelOrder', [$trade_id, $orderid, $type]);
                }
                if (config('web_real_api') == 2) {
                    //将交易所类型转换为网格模式
                    if ($type = 1) {
                        $type = 0;
                    } else {
                        $type = 1;
                    }
                    $data = grid::grid_cancel($type, $orderid, $trade_id);
                }
            }
            if ($data) {
                Db::commit();
                return ['status' => 1, 'message' => '撤单成功'];
            } else {
            }
        } elseif ($yes) {
            if ($broker['broker'] != -1) {
                Db::rollback();
                return ['status' => 0, 'message' => '请安装股票实盘交易插件'];
            } else {
                Db::commit();
                return ['status' => 1, 'message' => '撤单成功'];
            }
        }
        Db::rollback();
        return ['status' => 0, 'message' => '撤单失败'];
    }

    //预警线
    public function precautious_line()
    {
        //预警线= 配资金额+保证金*比例
        //  先搜索 配资表(条件操盘中) 搜子账号ID  去持仓表 查询股票数量 如何数量是0 不继续做判断
        // 搜到的股票数量 用z_market_bat 函数，查询  股票 返回值
        if (!get_trading_time('','time')) {
            Log::write('非交易时间');
            return;
        }
        $borrow_arr = Db::name('stock_borrow')->where('status', '1')->select();
        if (!empty($borrow_arr)) {
            foreach ($borrow_arr as $key => $val) {
                $position_stock_arr = Db::name('stock_position')->where('sub_id', $val['stock_subaccount_id'])->field('gupiao_code,stock_count')->select();
                $market_val         = '';
                if (!empty($position_stock_arr)) {
                    foreach ($position_stock_arr as $value) {
                        $current_price = z_market($value['gupiao_code']) ['current_price']; //股票实时价格
                        $market_val    += $value['stock_count'] * $current_price;           //最新市值
                    }
                } else {
                    continue;
                    echo "此配资无股票持仓\n\r";
                }
                if (!empty($market_val)) {
                    $subaccount_money_arr = Db::name('stock_subaccount_money')->where('stock_subaccount_id', $borrow_arr[$key]['stock_subaccount_id'])->field('avail,freeze_amount,deposit_money,borrow_money')->find();
                    $subaccount_loss_warn = Db::name('stock_subaccount_risk')->where('stock_subaccount_id', $borrow_arr[$key]['stock_subaccount_id'])->value('loss_warn');
                    $subaccount_loss_warn = sprintf("%.2f", ($subaccount_loss_warn / 100));
                    $loss_warn            = money_convert($subaccount_money_arr['borrow_money']) + (money_convert($subaccount_money_arr['deposit_money']) * $subaccount_loss_warn);
                    $now_init_amount      = $market_val + money_convert($subaccount_money_arr['avail']) + money_convert($subaccount_money_arr['freeze_amount']);
                    if ($now_init_amount < $loss_warn && $borrow_arr[$key]['loss_warn_sms_send'] == 0) {
                        $content      = \think\Config::get('sms_template') ['stock_loss_warn'];
                        $mobile[$key] = Db::name('member')->where('id', $borrow_arr[$key]['member_id'])->value('mobile');
                        $content      = str_replace(array(
                            "#var#",
                            "#order_id#"
                        ), array(
                            $mobile[$key],
                            $borrow_arr[$key]['order_id']
                        ), $content);
                        $res          = sendsms_mandao($mobile[$key], $content, 'user');
                        if ($res) {
                            Db::name('stock_borrow')->where('id', $borrow_arr[$key]['id'])->setField('loss_warn_sms_send', 1);
                            echo "预警线提醒短信发送成功\n\r";
                        }
                    } else {
                        if ($subaccount_money_arr && $loss_warn && $now_init_amount && $borrow_arr[$key]['loss_warn_sms_send'] == 1) {
                            Db::name('stock_borrow')->where('id', $borrow_arr[$key]['id'])->setField('loss_warn_sms_send', 0);
                            echo "该配资没有低于预警线\n\r";
                        }
                    }
                } else {
                    echo "最新总市值获取失败\n\r";
                }
            }
        } else {
            echo "无配资记录\n\r";
        }
    }
    /************下方为工具方法************/
    /*
  * 批量实时行情数据
  * $code 股票代码
  */
    public function aliyun_market_old($code = '600000')
    {
        $code    = fenxi($code);
        $host    = config(market_url);
        $path    = "/query/comrms";
        $method  = "GET";
        $appcode = config(market_appcode);
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "symbols=" . $code;
        $bodys  = "";
        $url    = $host . $path . "?" . $querys;
        $curl   = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($curl);
        $arr    = json_decode($output, true);
        $arr    = $arr['Obj'];
        curl_close($curl);
        return $arr;
    }

    //应该是分析股票类型
    public static function typefenxi($code)
    {
        switch (substr($code, 0, 1)) {
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
                $d = 0;
                break;
        }
        return $d;
    }

    //实盘接口心跳
    public static function heart()
    {
        $heart = new Heart();
        $heart->heart();
        return;
    }

    /*
       * 阿里云 实时行情转实盘API
       * $res 初始数据
      */
    function aliyun_to_api($res)
    {
        return array(
            "time"          => date("h:i:s", $res['Tick']),
            "volume"        => $res['V'],
            "current_price" => $res['P'],
            "ZD"            => $res['ZF'],
            "B1"            => $res['B1'],
            "B2"            => $res['B2'],
            "S1"            => $res['S1'],
            "S2"            => $res['S2']
        );
    }

    /**检查数组维度*/
    public static function array_depth($array)
    {
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = array_depth($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    public static function send_sms($mobile, $template, $info = null)
    {
        // 读取一条短信插件
        $sms_plugin = Db('admin_plugin')->where("name like '%Sms' and status = 1")->find();
        //$develop_mode=config('develop_mode');//判断是否开发模式
        if (!$sms_plugin) { // 如果没有开启短信接口则发送默认验证码 0000
            $arr['code'] = md5('000000');
            $arr['time'] = time();
            setRegSmsCache($mobile, $arr);
            $res = true;
        } else {
            $str = trim($info);
            $res = plugin_action($sms_plugin['name'], $sms_plugin['name'], 'sendSms', [$mobile, $str]);
            if ($res == false) return false;
        }
        return $res;
    }

    /**
     * 发短信给管理员  不知道干啥用
     *
     * @param $msg
     *
     * @return bool|mixed
     */
    public static function sendsms_to_admin($msg)
    {
        $mobile = $avatar = Db::name('admin_user')->where('username', 'admin')->value('mobile');
        $res    = self::send_sms($mobile, "", $msg);
        return $res;
    }

    //定时任务更新股票数据
    public function autoScript()
    {
        $data            = input();
        $stock_list      = $data['stock_list'];
        $stock_list_name = $data['stock_list_name'];
        StockList::updateStockData($stock_list);
    }

    public function autoUpdateListTime()
    {
        $data       = input();
        $stock_list = $data;
//        file_put_contents('autoUpdateListTime.txt', date('Y-m-d H:i:s') . "\r\n", FILE_APPEND);
//        file_put_contents('autoUpdateListTime.txt', var_export($stock_list, true) . "\r\n", FILE_APPEND);
        StockList::updateListTime($stock_list);
    }

    // 更新深沪A股
    public function updateShenHu()
    {
        $searchstring = '北交所';
        $searchtype   = 'stock';
        $act          = 'smart_stock_picking';
        $res          = StockList::updateStockData2($searchstring, $searchtype, $act);
        dump($res);
        exit;
        if ($res) {
            return 'success';
        } else {
            return 'error';
        }
    }

    // 更新科创板
    public function updateKcb()
    {
        $searchstring = '科创板';
        $searchtype   = 'stock';
        $act          = 'smart_stock_picking';
        $res          = StockList::updateStockData($searchstring, $searchtype, $act);
        if ($res) {
            return 'success';
        } else {
            return 'error';
        }
    }

    // 更新创业板
    public function updateCyb()
    {
        $searchstring = '创业板';
        $searchtype   = 'stock';
        $act          = 'smart_stock_picking';
        $res          = StockList::updateStockData($searchstring, $searchtype, $act);
        if ($res) {
            return 'success';
        } else {
            return 'error';
        }
    }

    public function testdata()
    {
        $code         = '600000';
        $data['code'] = $code;
        $data['lang'] = Lang::range();
        $data['key']  = 'B8AB5729D1BCCE8928D840F1F37722F9';
//        $url = 'http://jdf10.0p0.cn/stock/crond/getStockDetail';
//        $url = 'http://localhost.pzjddata.com/stock/crond/getStockDetail';
//        $json_data = json_encode($data);
//
//        $res =  http_post_json($url, $json_data);
//
        $stockinfo = z_market($code);
        dump($stockinfo);
        exit;
//        $field = 'tradeDate,tradeTime,preClose,open,high,low,latest,latestAmount,latestVolume,avgPrice,change,changeRatio,upperLimit,downLimit,amount,volume,turnoverRatio,sellVolume,buyVolume,totalBidVol,totalAskVol,totalShares,totalCapital,pb,riseDayCount,suspensionFlag,tradeStatus,chg_1min,chg_3min,chg_5min,chg_5d,chg_10d,chg_20d,chg_60d,chg_120d,chg_250d,chg_year,mv,vol_ratio,committee,commission_diff,pe_ttm,pbr_lf,swing,lastest_price,af_backward';
//        $act = 'real_time_quotation';
//        $post_data['codes'] = code_suffix($code);
//        $post_data['indicators'] = $field;
//        $res = iApi_curl($act,$post_data);
//        $table = $res['tables'][0]['table'];
//        $array[0]['current_price'] = $table['latest'][0];//最新价
//        $array[0]['volume'] = $table['volume'][0]; //成交量
//        dump($table);exit;
    }

    //获取股票实时行情
    public function aliyun_market($code = '')
    {
        $res = z_market($code);
        return $res;
    }

    //更新股票实时数据
    public function updateStockRealTime()
    {
        set_time_limit(0);
        $start_time                     = strtotime(date('Y-m-d'));
        $updata_time['stock_real_time'] = $start_time;
        $res                            = get_trading_time(); //在规定的时间内更新 返回2不更新
        if ($res == 2) {
            return;
        }
        $end_time = $start_time + 15 * 3600;//每天下午三点后更新数据
        if (time() < $end_time) {
            return;
        }
        $stock_list = Db::name('stock_list')->where('stock_real_time', '<', $start_time)->limit(100)->column('code');
        if (!$stock_list) {
            return;
        }
        $data      = [];
        $new_codes = [];
        foreach ($stock_list as $key => $value) {
            $data = get_market($value);
            if (!$data) {
                continue;
            }
            update_stock_real_time($data, $value);//写入到数据表
            $new_codes[] = $value;
        }
        Db::name('stock_list')->where('code', 'in', $new_codes)->update($updata_time);
        return;
    }

    //获取交易日期  一个月获取一次
    public function getTradeDate()
    {
        get_stock_trade_dates();
    }

    //更新日报数据 (每晚11：50更新)
    public function updateDayData()
    {
        DataReportModel::getDayData(2);
    }

    //更新基金买入状态(每天凌晨12点执行)
    public function autoUpdateFundStatus()
    {
        $type = input('type', 1);
        FundOrder::autoUpdateBuyStatus([], $type);
    }

    //确认卖出的状态 （第二天9点半到账）每天九点半后执行
    public function autoUpdateFundSellStatus()
    {
        FundOrder::autoUpdateSellStatus();
    }

    //结算基金（每天下午三点后结算）
    public function autoSettlementFund()
    {
       //交易日结算
        if (get_trading_time()) {
            FundOrder::settlementFund();
        }
    }

    //股票模式 0点的时候生成当日 股市跟买记录待处理待买记录
    //http://www.a1.com/index.php/stock/Crond/autobuildgsrecord
    public function autobuildgsrecord()
    {

        $this->error('当前操作已经不再使用，改用讲师添加操盘后-》上股直接买入记录');
        #只有在交易日的时候才能生成

        if (get_trading_time()) {
             FundOrderGsModel::autobuildgsrecord();

        }


    }

    //更新客户盈亏数据（每天下午三点后执行）
    public function updateSubaccountMoneyLog()
    {

        if (get_trading_time()) {
            Subaccount::getSubaccountMoneyLog();
        }
    }


    //vip跟单自动卖出(每天0点执行)
    public function autoSell()
    {

        if (get_trading_time()) {
            FundOrder::autoSell();
        }
    }

    //凌晨12点委托单自动清零，
    public function updatestockTrust()
    {
        $day_start_time    = strtotime(date('Y-m-d'));//当天的起始时间
        $end_time          = $day_start_time + 24 * 3600 - 1;
        $where['type']     = array('eq', 1);
        $where['status']   = array('eq', '已委托');
        $where['add_time'] = array('elt', $end_time);
        Db('stock_trust')->where($where)->delete();
        echo "success";
        exit;
    }

    /**
     * 获取新闻快讯 半小时获取一次
     * @return void
     * http://www.a1.com/index.php/stock/Crond/getkuaixun
     */
    public function getkuaixun()
    {

        $url  = 'https://zhibo.sina.com.cn/api/zhibo/feed?zhibo_id=152&id=&tag_id=0&page=1&page_size=20&type=0';
        $data = http_request($url);
        if ($data) {
            $data      = json_decode($data, true);
            $data      = $data['result']['data']['feed']['list'];
            $insetlist = [];
            foreach ($data as $k => $v) {
                $list['rich_text']   = $v['rich_text'];
                $list['update_time'] = $v['update_time'];
                $list['top_value']   = $v['top_value'];
                $list['url']         = $v['docurl'];
                $list['addtime']     = time();
                $insetlist[]         = $list;
            }
            echo Db::name('cms_kuaixun')->insertAll($insetlist);
            echo '采集成功';
        }
    }

    /**
     * 获取财经新闻 半小时获取一次
     * @return void
     * http://www.a1.com/index.php/stock/Crond/getnaws
     */
    public function getnaws()
    {
        $url  = 'http://news.cj.sina.cn/wap/v1/headline?action=1&up=3&down=0&_=' . time();
        $data = http_request($url);
        if ($data) {
            $data = json_decode($data, true);
            $data = $data['data']['feed'];
            foreach ($data as $k => $v) {
                $list                = [];
                $list['cid']         = 11;
                $list['model']       = 2;
                $list['title']       = $v['title'];
                $list['shorttitle']  = $v['title'];
                $list['uid']         = 31;
                $list['flag']        = 'c';
                $list['language']    = 'cn';
                $list['status']      = 1;
                $list['cj_thumbs']   = $v['thumbs'][0];
                $list['cj_url']      = $v['url'];
                $list['create_time'] = time();
                #获取到详情了才能插入
                $content = gethtml($v['url']);

                if ($content) {
                    $get_one = Db::name('cms_document')->where('title', $v['title'])->find();
                    if (!$get_one) {
                        $aid = Db::name('cms_document')->insert($list, false, true);
                        echo Db::name('cms_document_news')->insert(['aid' => $aid, 'content' => $content, 'summary' => $v['title'], 'language' => 'cn']);
                        echo '采集成功';
                    }

                };
                if ($k > 5) {
                    die();
                }
            }
        }
    }

    /**
     * 团队合伙人-计算用户跟单团队收益和直属收益业绩
     */
    public function partnerCrond()
    {

        if (get_trading_time()) {
            (new PartnerSettleService)->crond();
        }
    }

    /**
     * 团队合伙人-结算团队分成
     */
    public function partnerTeamSharing()
    {
        (new PartnerSettleService)->settleTeamSharing();
    }

    /**
     * 团队合伙人-结算周工资
     */
    public function partnerWeekSalary()
    {
        (new PartnerSettleService)->settleWeekSalary();
    }

    /**
     * Desc : 每日操盘自动结算  每日下点3点执行
     * User : you name
     * Date : 2024-07-02 03:32
     */
    public function autoDailyTrading()
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
//        if ($ids) {
//            $list = FundDaylineModel::where(['id' => $ids])->where('status', 2)->limit(50)->select();
//        } else {
//            $list = FundDaylineModel::where('status', 2)->limit(50)->select();
//        }

        //计算收入
        echo FundOrderGsModel::settlementFundgs($ids);
        echo "\r\n结算成功\r\n";
    }

    /**
     * Desc : 合约订单结算
     * Date : 2024-07-16 12:57
     */
    public function endfundorder()
    {
        // 获取当前日期的时间戳
        $currentTimestamp = strtotime(date('Y-m-d  23:59:59'));

        // 查询数据
        $list = FundOrderGsModel::where('fundendtime', '<=', $currentTimestamp)->select();
        foreach ($list as $v) {

            if ($v['status'] == 1 || $v['status'] == 7) {
                echo "开始处理数据\r\n";
                #已确认
              $settlementorders=  FundOrderGsModel::settlementorders();
              echo "\r\n". $settlementorders['msg'];


            }
            if ($v['status'] == 6) {
                #持仓中  强制改状态   卖出后第二就不会生成新订单了
                FundOrderGsModel::where('id', $v['id'])->update(['status' => 7]);
            }
        }
    }

    /**
     * Desc : 每日余额宝返利  每日执行  事件---待定
     * User : yfn
     */
    public function autoDailyRebate()
    {
        InterestModel::setDailyRebate();
    }

    /**
     * Desc : 生成昨日收益率
     * User : yfn
     */
    public function autoYesterDailyFloat()
    {
        InterestModel::setYesterDailyFloat();
    }

    public function setDailyFloat()
    {
        InterestModel::setDailyFloat();
    }

}

?>
