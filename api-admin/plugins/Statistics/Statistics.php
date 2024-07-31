<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace plugins\Statistics;

use app\common\controller\Plugin;
use think\Db;

/**
 * 系统环境信息插件
 * @package plugins\DevTeam
 * @author 蔡伟明 <314013107@qq.com>
 */
class Statistics extends Plugin
{
    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
      'name' => 'Statistics',
        // 插件标题[必填]
      'title' => '后台配资统计',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
      'identifier' => 'Statistics.ming.plugin',
        // 插件图标[选填]
      'icon' => 'fa fa-fw fa-users',
        // 插件描述[选填]
      'description' => '在后台首页显示配资统计',
        // 插件作者[必填]
      'author' => '蔡伟明 <314013107@qq.com>',
        // 作者主页[选填]
      'author_url' => 'http://www.lurenjiayi.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
      'version' => '1.0.0',
        // 是否有后台管理功能[选填]
      'admin' => '0',
    ];
    /**
     * @var array 插件钩子
     */
    public $hooks = [
      'adminIndex',
      'my_statistics' => 'dddd'
    ];

    /**
     * 后台首页钩子
     * @author 蔡伟明 <314013107@qq.com>
     * @rq 1日，2周，3月
     */
    public function adminIndex($params)
    {
        if (isset($params['rq']) && $params['rq'] == 1) {
            $rq = 1;
            $rq1 = '今日';
            $rq2 = '昨日';
        } elseif (isset($params['rq']) && $params['rq'] == 2) {
            $rq = 2;
            $rq1 = '本周';
            $rq2 = '上周';
        } elseif (isset($params['rq']) && $params['rq'] == 3) {
            $rq = 3;
            $rq1 = '本月';
            $rq2 = '上月';
        } else {
            $rq = 1;
            $rq1 = '今日';
            $rq2 = '昨日';
        }
        $config = $this->getConfigValue();
        $p_total = $trust_day = $num_u_day = $trade_day = $num_a_day = $win = 0;
        $webtype = webType();
        $wherem = [];
        if ($webtype == 2) {
            $wherem['for_user'] = for_user();
        }
        if ($config['display']) {
            //累计总操盘资金
            $deposit_money = Db::name('stock_borrow')
              ->where('status', '>', 0)
              ->where($wherem)
              ->sum('deposit_money');
            $borrow_money = Db::name('stock_borrow')
              ->where('status', '>', 0)
              ->where($wherem)
              ->sum('borrow_money');
            $total_operate_account = $deposit_money + $borrow_money;
            $total_operate_account = sprintf("%.2f", ($total_operate_account / 1000000));
            //累计总股票持仓
            $res = Db::name('stock_position')
              ->where('stock_count', '>', 0)
              ->where($wherem)
              ->select();
            foreach ($res as $k => $v) {
                $p_total += $v['stock_count'] * $v['now_price'];
            }
            $p_total = sprintf("%.2f", ($p_total / 10000));
            $time = strtotime(date('Y-m-d', time()));
            //当日成交总额
            $trade_day = Db::name('stock_deal_stock')
              ->where('deal_date', '>=', $time)
              ->where($wherem)
              ->sum('amount');
            //当日成交次数
            $num_a_day = Db::name('stock_deal_stock')
              ->where('deal_date', '>=', $time)
              ->where($wherem)
              ->count();
            //累计赚取收益
            $wherem1 = [];
            if ($webtype == 2) {
                $wherem1['a.for_user'] = for_user();
            }
            $ck_profit = Db::name('stock_subaccount_money')->alias('a')
              ->join('stock_subaccount s', 's.id=a.stock_subaccount_id', 'LEFT')
              ->join('member m', 'm.id=s.uid', 'LEFT')
              ->where(['m.status' => 1, 'a.return_money' => ['>', 0]])
              ->where($wherem1)
              ->sum('a.return_money');
            $ck_profit = sprintf("%.2f", ($ck_profit / 1000000));
            //累计充值总额
            $r_total = Db::name('money_recharge')
              ->where(['status' => 1])
              ->where($wherem)
              ->sum('money');
            $r_total = sprintf("%.2f", ($r_total / 1000000));
            //总用户数
            $member_count = Db::name('member')
              ->where(['status' => 1])
              ->where($wherem)
              ->count();
            /*$yestoday_register = Db::name('member')->query("select count(*) as counts from `lmq_member` where to_days(now()) <= 1 + to_days(`create_time`)");
            $yestoday_register =  $yestoday_register[0]['counts'];
            $this->assign('yestoday_register',$yestoday_register);*/
            $week = get_lastweek();
            $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
            $endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
            $startToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
            $begin7day = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
            $beginThisWeek = mktime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 7, date("Y"))));//本周开始
            $endThisWeek = mktime(date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7 - 7, date("Y"))));//本周结束
            $beginLastweek = $week['beginLastweek'];//上周开始时间
            $endLastweek = $week['endLastweek'];//上周结束时间
            $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
            $beginLastmonth = mktime(0, 0, 0, date("m", strtotime("-1 month")), 1, date("Y", time()));//上月开始时间
            $endLastmonth = mktime(23, 59, 59, date("m", strtotime("-1 month")), date('t', strtotime("-1 month")), date("Y", time()));//上月结束时间
            $today_time = strtotime(date('Y-m-d', time()));
            //日-周-月 切换
            if ($rq == 1) {
                $today_time = strtotime(date('Y-m-d', time()));
                $begin = strtotime(date('Y-m-d', time()));//$today_time
                $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));//beginYesterday
                $endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;//endYesterday
            } elseif ($rq == 2) {
                $today_time = $beginThisWeek;
                $beginYesterday = $beginLastweek;
                $endYesterday = $endLastweek;
            } elseif ($rq == 3) {
                $today_time = $beginThismonth;
                $beginYesterday = $beginLastmonth;
                $endYesterday = $endLastmonth;
            } else {
                $today_time = strtotime(date('Y-m-d', time()));
                $beginYesterday = $beginYesterday;//beginYesterday
                $endYesterday = $endYesterday;//endYesterday
            }
            $Yesterday = Db::name('member')
              ->where('create_time', '>=', $beginYesterday)
              ->where('create_time', '<=', $endYesterday)
              ->where($wherem)
              ->count();
            $new7day = Db::name('member')
              ->where('create_time', '>=', $begin7day)
              ->where('create_time', '<=', $endToday)
              ->where($wherem)
              ->count();
            $ThisMonth = Db::name('member')
              ->where('create_time', '>=', $beginThismonth)
              ->where('create_time', '<=', $endThismonth)
              ->where($wherem)
              ->count();
            //日周月切换当日注册会员
            $today_member_num = Db::name('member')
              ->where(['is_del' => 0])
              ->where('create_time', '>', $today_time)
              ->where($wherem)
              ->count();
            $today_member_num = $today_member_num > 10000 ? ($today_member_num / 10000) . '万个' : $today_member_num . '个';
            //昨日注册会员
            $yestoday_member_num = Db::name('member')
              ->where(['is_del' => 0])
              ->where('create_time', '>=', $beginYesterday)
              ->where('create_time', '<=', $endYesterday)
              ->where($wherem)
              ->count();
            $yestoday_member_num = $yestoday_member_num > 10000 ? ($yestoday_member_num / 10000) . '万个' : $yestoday_member_num . '个';
            //累计配资人数
            if ($webtype == 2) {
                $for_user = for_user();
                $borrow_count = Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where status=1 and for_user=' . $for_user . ' GROUP BY member_id ) aa');
                $borrow_count = $borrow_count[0]['nums'];
            } else {
                $borrow_count = Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where status=1 GROUP BY member_id ) aa');
                $borrow_count = $borrow_count[0]['nums'];
            }
            //占比
            // $borrow_proportion = sprintf("%01.2f",($borrow_count/$member_count)*100).'%';
            //盈亏总额
            $win = Db::name('stock_position')
              ->where('stock_count', '>', 0)
              ->where($wherem)
              ->sum('ck_profit');
            //总配资单数
            $stock_nums = Db::name('stock_borrow')
              ->where('status=1')
              ->where($wherem)
              ->count('id');
            $today_stock_nums = Db::name('stock_borrow')
              ->where('status=1')
              ->where($wherem)
              ->where('verify_time', '>', $today_time)
              ->count('id');
            $yes_stock_nums = Db::name('stock_borrow')
              ->where('status=1')
              ->where($wherem)
              ->where('verify_time', '>=', $beginYesterday)
              ->where('verify_time', '<=', $endYesterday)
              ->count('id');
            //配资总额
            $stock_money = Db::name('stock_borrow')
              ->where(['status' => ['>', 0]])
              ->where($wherem)
              ->sum('borrow_money');
            $stock_money += Db::name('stock_borrow')
              ->where(['status' => ['>', 0]])
              ->where($wherem)
              ->sum('deposit_money');
            // $stock_money =  $stock_money>1000000?sprintf("%.2f",($stock_money/1000000)).'万元':sprintf("%.2f",$stock_money).'元';
            $today_stock_money = Db::name('stock_borrow')
              ->where(['status' => ['>', 0]])
              ->where($wherem)
              ->where('verify_time', '>', $today_time)
              ->sum('borrow_money');
            $today_stock_money += Db::name('stock_borrow')
              ->where(['status' => ['>', 0]])
              ->where($wherem)
              ->where('verify_time', '>', $today_time)
              ->sum('deposit_money');
            $yes_stock_money = Db::name('stock_borrow')
              ->where(['status' => ['>', 0]])
              ->where($wherem)
              ->where('verify_time', '>=', $beginYesterday)
              ->where('verify_time', '<=', $endYesterday)
              ->sum('borrow_money');
            $yes_stock_money += Db::name('stock_borrow')
              ->where(['status' => ['>', 0]])
              ->where($wherem)
              ->where('verify_time', '>=', $beginYesterday)
              ->where('verify_time', '<=', $endYesterday)
              ->sum('deposit_money');
            $stock_money = $stock_money > 1000000 ? sprintf("%.2f", ($stock_money / 1000000)) . '万元' : sprintf("%.2f", $stock_money ? $stock_money / 100 : 0) . '元';
            $today_stock_money = $today_stock_money > 1000000 ? sprintf("%.2f", ($today_stock_money / 1000000)) . '万元' : sprintf("%.2f", $today_stock_money ? $today_stock_money / 100 : 0) . '元';
            $yes_stock_money = $yes_stock_money > 1000000 ? sprintf("%.2f", ($yes_stock_money / 1000000)) . '万元' : sprintf("%.2f", $yes_stock_money ? $yes_stock_money / 100 : 0) . '元';
            //免息体验数
            $free_counts = Db::name('stock_borrow')
              ->where(['status' => 1, 'type' => 5])
              ->where($wherem)
              ->count();
            //按天配资数
            $day_counts = Db::name('stock_borrow')
              ->where(['status' => 1, 'type' => 1])
              ->where($wherem)
              ->count();
            //免费 体验
            $experience_counts = Db::name('stock_borrow')
              ->where(['status' => 1, 'type' => 4])
              ->where($wherem)
              ->count();
            //模拟操盘
            $simulation_counts = Db::name('stock_borrow')
              ->where(['status' => 1, 'type' => 6])
              ->where($wherem)
              ->count();
            //按周配资数
            $week_counts = Db::name('stock_borrow')
              ->where(['status' => 1, 'type' => 2])
              ->where($wherem)
              ->count();
            //按月配资数
            $month_counts = Db::name('stock_borrow')
              ->where(['status' => 1, 'type' => 3])
              ->where($wherem)
              ->count();
            //昨日登录用户数
            $Yesterday_login_count = Db::name('member')
              ->where('last_login_time', '>=', $beginYesterday)
              ->where('last_login_time', '<=', $endYesterday)
              ->where($wherem)
              ->count();
            //昨日登录数占比
            //$yesterday_login_proportion =  sprintf("%01.2f",($Yesterday_login_count/$member_count)*100).'%';
            //7天内登录用户数
            $new7day_login_count = Db::name('member')
              ->where('last_login_time', '>=', $begin7day)
              ->where('last_login_time', '<=', $endToday)
              ->where($wherem)
              ->count();
            //七天内登录数占比
            //$new7day_login_proportion = sprintf("%01.2f",($new7day_login_count/$member_count)*100).'%';
            // 代理商总数
            $agent_counts = Db::name('admin_user')->where('role', 'in', [2, 3, 4])->where($wherem)->count();
            //1级
            $agent_one_counts = Db::name('admin_user')
              ->where(['status' => 1, 'role' => 2])
              ->count();
            //2级
            $agent_two_counts = Db::name('admin_user')
              ->where(['status' => 1, 'role' => 3])
              ->count();
            //3ji
            $agent_tree_counts = Db::name('admin_user')
              ->where(['status' => 1, 'role' => 4])
              ->count();
            //用户充值
            $money_recharge = Db::name('money_recharge')
              ->where(['status' => 1])
              ->where($wherem)
              ->sum('money');
            $money_recharge = $money_recharge > 1000000 ? sprintf("%.2f", ($money_recharge / 1000000)) . '万元' : sprintf("%.2f", $money_recharge ? $money_recharge / 100 : 0) . '元';
            $today_money_recharge = Db::name('money_recharge')
              ->where(['status' => 1])
              ->where($wherem)
              ->where('create_time', '>', $today_time)
              ->sum('money');
            $today_money_recharge = $today_money_recharge > 1000000 ? sprintf("%.2f", ($today_money_recharge / 1000000)) . '万元' : sprintf("%.2f", $today_money_recharge ? $today_money_recharge / 100 : 0) . '元';
            $yes_money_recharge = Db::name('money_recharge')
              ->where(['status' => 1])
              ->where($wherem)
              ->where('create_time', '>=', $beginYesterday)
              ->where('create_time', '<=', $endYesterday)
              ->sum('money');
            $yes_money_recharge = $yes_money_recharge > 1000000 ? sprintf("%.2f", ($yes_money_recharge / 1000000)) . '万元' : sprintf("%.2f", $yes_money_recharge ? $yes_money_recharge / 100 : 0) . '元';
            //用户提现
            $money_withdraw = Db::name('money_withdraw')
              ->where(['status' => 1])
              ->where($wherem)
              ->sum('money');
            $money_withdraw = $money_withdraw > 1000000 ? sprintf("%.2f", ($money_withdraw / 1000000)) . '万元' : sprintf("%.2f", $money_withdraw ? $money_withdraw / 100 : 0) . '元';
            $today_money_withdraw = Db::name('money_withdraw')
              ->where(['status' => 1])
              ->where($wherem)
              ->where('create_time', '>', $today_time)
              ->sum('money');
            $today_money_withdraw = $today_money_withdraw > 1000000 ? sprintf("%.2f", ($today_money_withdraw / 1000000)) . '万元' : sprintf("%.2f", $today_money_withdraw ? $today_money_withdraw / 100 : 0) . '元';
            $yes_money_withdraw = Db::name('money_withdraw')
              ->where(['status' => 1])
              ->where($wherem)
              ->where('create_time', '>=', $beginYesterday)
              ->where('create_time', '<=', $endYesterday)
              ->sum('money');
            $yes_money_withdraw = $yes_money_withdraw > 1000000 ? sprintf("%.2f", ($yes_money_withdraw / 1000000)) . '万元' : sprintf("%.2f", $yes_money_withdraw ? $yes_money_withdraw / 100 : 0) . '元';
            //累计返佣
            $wherem2 = [];
            if ($webtype == 2) {
                $wherem2['aid'] = for_user();
            }
            $accumulateRemission = Db::name('agent_distributionamount')
              ->where($wherem2)
              ->sum('amount');
            //排名SELECT for_user,sum(affect) FROM `lmq_agents_back_money` group by for_user//key+1即为排名
            $accumulateRemission_list = Db::query("SELECT aid,sum(amount) as yj FROM `lmq_agent_distributionamount` group by aid");
            $accumulateRemission_pm = 0;
            if ($accumulateRemission_list) {
                if ($webtype == 2) {
                    $for_user = for_user();
                    foreach ($accumulateRemission_list as $k => $v) {
                        if ($webtype == 2 && $v['yj'] > 0 && $for_user == $v['aid']) {
                            $accumulateRemission_pm = $k + 1;
                        }
                    }
                }//else{
                //$accumulateRemission_pm = 1;
                //}
            }
            $accumulateRemission = $accumulateRemission > 1000000 ? sprintf("%.2f", ($accumulateRemission / 1000000)) . '万元' : sprintf("%.2f", $accumulateRemission ? $accumulateRemission / 100 : 0) . '元';
            //今日返佣
            $today_accumulateRemission = Db::name('agents_back_money')
              ->where('create_time', '>', $today_time)
              ->where($wherem)
              ->sum('affect');
            $today_accumulateRemission = $today_accumulateRemission > 1000000 ? sprintf("%.2f", ($today_accumulateRemission / 1000000)) . '万元' : sprintf("%.2f", $today_accumulateRemission ? $today_accumulateRemission / 100 : 0) . '元';
            $yestoday_accumulateRemission = Db::name('agents_back_money')
              ->where('create_time', '>=', $beginYesterday)
              ->where('create_time', '<=', $endYesterday)
              ->where($wherem)
              ->sum('affect');
            $yestoday_accumulateRemission = $yestoday_accumulateRemission > 1000000 ? sprintf("%.2f", ($yestoday_accumulateRemission / 1000000)) . '万元' : sprintf("%.2f", $yestoday_accumulateRemission ? $yestoday_accumulateRemission : 0) . '元';
            //$this->assign('yesterday_login_proportion',$yesterday_login_proportion);//
            //$this->assign('new7day_login_proportion',$new7day_login_proportion);//
            //追加配资
            $tody_jjpz = Db::name('stock_addmoney')->where(['status' => 1, 'verify_time' => ['>=', $today_time]])->sum('money');
            $tody_jjpz = $tody_jjpz > 1000000 ? sprintf("%.2f", ($tody_jjpz / 1000000)) . '万元' : sprintf("%.2f", $tody_jjpz ? $tody_jjpz / 100 : 0) . '元';
            $yes_jjpz = Db::name('stock_addmoney')->where(['status' => 1, 'verify_time' => ['>=', $beginYesterday], 'verify_time' => ['<=', $endYesterday]])->sum('money');
            $yes_jjpz = $yes_jjpz > 1000000 ? sprintf("%.2f", ($yes_jjpz / 1000000)) . '万元' : sprintf("%.2f", $yes_jjpz ? $yes_jjpz / 100 : 0) . '元';
            //扩大配资
            $tody_kdpz = Db::name('stock_addfinancing')->where(['status' => 1, 'verify_time' => ['>=', $today_time]])->sum('money');
            $tody_kdpz = $tody_kdpz > 1000000 ? sprintf("%.2f", ($tody_kdpz / 1000000)) . '万元' : sprintf("%.2f", $tody_kdpz ? $tody_kdpz / 100 : 0) . '元';
            $yes_kdpz = Db::name('stock_addfinancing')->where(['status' => 1, 'verify_time' => ['>=', $beginYesterday], 'verify_time' => ['<=', $endYesterday]])->sum('money');
            $yes_kdpz = $yes_kdpz > 1000000 ? sprintf("%.2f", ($yes_kdpz / 1000000)) . '万元' : sprintf("%.2f", $yes_kdpz ? $yes_kdpz / 100 : 0) . '元';
            //终止
            $tody_zz_num = Db::name('stock_renewal')->where(['status' => ['<>', 0], 'type' => 2])->where(['verify_time' => ['>=', $today_time]])->count();
            $yes_zz_num = Db::name('stock_renewal')->where(['status' => ['<>', 0], 'type' => 2])->where(['verify_time' => ['>=', $beginYesterday]])->where(['verify_time' => ['<=', $endYesterday]])->count();
            //操盘活跃用户
            //今天活跃用户数
            $tody_cp_num = Db::query("SELECT DISTINCT
	                b.member_id
                   FROM
	               lmq_stock_submoney_record r
                   LEFT JOIN lmq_stock_borrow b ON b.stock_subaccount_id = r.sub_id
                   WHERE
	               r.type IN (1, 3) and r.create_time >= {$today_time}");
            $tody_cp_num = $tody_cp_num ? count($tody_cp_num) : 0;
            //昨日活跃用户数
            $yes_cp_num = Db::query("SELECT DISTINCT
	                b.member_id
                   FROM
	               lmq_stock_submoney_record r
                   LEFT JOIN lmq_stock_borrow b ON b.stock_subaccount_id = r.sub_id
                   WHERE
	               r.type IN (1, 3) and r.create_time >= {$beginYesterday} and r.create_time <= $endYesterday");
            $yes_cp_num = $yes_cp_num ? count($yes_cp_num) : 0;
            //平台盈利=交易金额+盈亏金额相加的和
            $tody_ptyl = Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $today_time]])->sum('residual_quantity');//成交金额
            #交易盈亏=（成交价-买入价）*成交数量
            $tody_list = Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $today_time]])->select();
            $tody_return_money = 0;//交易盈亏
            if ($tody_list) {
                foreach ($tody_list as $k => $v) {
                    $tody_return_money += round(($v['deal_price'] - $v['buy_price']) * $v['volume'], 2);
                }
            }
            $tody_ptyl = $tody_ptyl + $tody_return_money;
            $tody_ptyl = $tody_ptyl > 1000000 ? sprintf("%.2f", ($tody_ptyl / 1000000)) . '万元' : sprintf("%.2f", $tody_ptyl ? $tody_ptyl / 100 : 0) . '元';
            //$yes_ptyl = Db::name('stock_delivery_order')->where(['status'=>1,'deal_date'=>['>=',$beginYesterday],'deal_date'=>['<=',$endYesterday]])->sum('residual_quantity');
            $yes_ptyl = Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $beginYesterday]])->where(['deal_date' => ['<=', $endYesterday]])->sum('residual_quantity');
            $yes_list = Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $beginYesterday]])->where(['deal_date' => ['<=', $endYesterday]])->select();
            $yes_return_money = 0;//交易盈亏
            if ($yes_list) {
                foreach ($yes_list as $k1 => $v1) {
                    $yes_return_money += round(($v1['deal_price'] - $v1['buy_price']) * $v1['volume'], 2);
                }
            }
            $yes_ptyl = $yes_ptyl + $yes_return_money;
            $yes_ptyl = $yes_ptyl > 1000000 ? sprintf("%.2f", ($yes_ptyl / 1000000)) . '万元' : sprintf("%.2f", $yes_ptyl ? $yes_ptyl / 100 : 0) . '元';
            //平台服务费=卖出股票记录印花税+ 过户费+净佣金 的总和
            $tody_ptfww = Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $today_time]])->sum('stamp_duty');//印花税
            $tody_ptfww += Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $today_time]])->sum('transfer_fee');//过户费
            $tody_ptfww += Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $today_time]])->sum('commission');//净佣金
            $tody_ptfww = $tody_ptfww > 1000000 ? sprintf("%.2f", ($tody_ptfww / 1000000)) . '万元' : sprintf("%.2f", $tody_ptfww ? $tody_ptfww / 100 : 0) . '元';
            $yes_ptfww = Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $today_time]])->sum('stamp_duty');//印花税
            $yes_ptfww += Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $today_time]])->sum('transfer_fee');//过户费
            $yes_ptfww += Db::name('stock_delivery_order')->where(['status' => 1, 'deal_date' => ['>=', $today_time]])->sum('commission');//净佣金
            $yes_ptfww = $yes_ptfww > 1000000 ? sprintf("%.2f", ($yes_ptfww / 1000000)) . '万元' : sprintf("%.2f", $yes_ptfww ? $yes_ptfww / 100 : 0) . '元';
            /////////////////////////////代理商统计（总平台新增）///////////////////////////////
            //代理总数
            $proxy_total = Db::name('admin_user')->where('for_user', '>', 1)->count();
            //周活跃代理商（本周登录或本周有发展新用户）
            $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
            $begin7day = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
            //本周登录过的代理用户id
            $proxy_users1 = Db::name('admin_user')->where('for_user', '>', 0)->where('last_login_time', '>', $today_time)->column('id');
            //本周代理有发展新用户的id
            $proxy_users2 = Db::name('member')->where('for_user', '>', 0)->where('create_time', '>', $today_time)->column('for_user');
            $proxy_users = 0;
            if ($proxy_users1 && $proxy_users2) {
                $proxy_users2 = array_unique($proxy_users2);
                $proxy_users = $proxy_users1 + $proxy_users2;
                $proxy_users = array_unique($proxy_users);
                $proxy_users = count($proxy_users);
            } elseif (!$proxy_users1 && !$proxy_users2) {
            } elseif ($proxy_users1 && !$proxy_users2) {
                $proxy_users = count($proxy_users1);
            } elseif (!$proxy_users1 && $proxy_users2) {
                $proxy_users = array_unique($proxy_users2);
                $proxy_users = count($proxy_users);
            }
            //代理新用户充值个数（代理发展的所有用户充值个数）
//            $today_proxy_money_recharge_num = Db::name('money_recharge')
//                ->where(['status'=>1])
//                ->where('for_user','>',0)
//                ->where('create_time','>',$today_time)
//                ->count();
            $today_proxy_money_recharge = Db::query("select distinct mid from lmq_money_recharge where status=1 and for_user>0 and create_time > {$today_time}");
            $today_proxy_money_recharge_num = count($today_proxy_money_recharge);
            //所有代理商的会员新用户
            $tody_proxy_member_num = Db::name('member')
              ->where(['is_del' => 0])
              ->where('create_time', '>', $today_time)
              ->where('for_user', '>', 0)
              ->count();
            //今日代理商名下充值的总和
            $today_proxy_money_recharge = Db::name('money_recharge')
              ->where(['status' => 1])
              ->where('for_user', '>', 0)
              ->where('create_time', '>', $today_time)
              ->sum('money');
            $today_proxy_money_recharge = $today_proxy_money_recharge > 1000000 ? sprintf("%.2f", ($today_proxy_money_recharge / 1000000)) . '万元' : sprintf("%.2f", $today_proxy_money_recharge ? $today_proxy_money_recharge / 100 : 0) . '元';
            //今日代理用户提现总和
            $today_proxy_money_withdraw = Db::name('money_withdraw')
              ->where(['status' => 1])
              ->where('for_user', '>', 0)
              ->where('create_time', '>', $today_time)
              ->sum('money');
            $today_proxy_money_withdraw = $today_proxy_money_withdraw > 1000000 ? sprintf("%.2f", ($today_proxy_money_withdraw / 1000000)) . '万元' : sprintf("%.2f", $today_proxy_money_withdraw ? $today_proxy_money_withdraw / 100 : 0) . '元';
            $this->assign('new7day_login_count', $new7day_login_count);//
            $this->assign('Yesterday_login_count', $Yesterday_login_count);//
            $this->assign('free_counts', $free_counts);//免息体验数
            $this->assign('day_counts', $day_counts);  //按天配资数
            $this->assign('week_counts', $week_counts);//按周配资数
            $this->assign('month_counts', $month_counts);//按月配资数
            //$this->assign('borrow_proportion',$borrow_proportion); //占比
            $this->assign('stock_money', $stock_money);//配资总额
            $this->assign('today_stock_money', $today_stock_money);//今日配资总额
            $this->assign('yes_stock_money', $yes_stock_money);//昨日配资总额
            $this->assign('win', $win);
            $this->assign('agent_one_counts', $agent_one_counts);
            $this->assign('agent_two_counts', $agent_two_counts);
            $this->assign('agent_tree_counts', $agent_tree_counts);
            $this->assign('borrow_count', $borrow_count);
            $this->assign('new7day', $new7day);
            $this->assign('ThisMonth', $ThisMonth);
            $this->assign('Yesterday', $Yesterday);
            $this->assign('member_count', $member_count);
            $this->assign('r_total', $r_total);
            $this->assign('ck_profit', $ck_profit);
            $this->assign('agent_counts', $agent_counts);
            $this->assign('num_a_day', $num_a_day);
            $this->assign('simulation_counts', $simulation_counts);
            $this->assign('trade_day', $trade_day);
            $this->assign('experience_counts', $experience_counts);
            $this->assign('p_total', $p_total);
            $this->assign('stock_nums', $stock_nums); //配资总单数
            $this->assign('today_stock_nums', $today_stock_nums);
            $this->assign('yes_stock_nums', $yes_stock_nums);
            $this->assign('total_operate_account', $total_operate_account); //累计总操盘资金
            $this->assign('money_recharge', $money_recharge);//用户提现金额
            $this->assign('today_money_recharge', $today_money_recharge);//用户今日值金额
            $this->assign('yes_money_recharge', $yes_money_recharge);//用户今日值金额
            $this->assign('money_withdraw', $money_withdraw);
            $this->assign('today_money_withdraw', $today_money_withdraw);//用户值金额
            $this->assign('yes_money_withdraw', $yes_money_withdraw);//用户值金额
            $this->assign('today_member_num', $today_member_num);//今天新增用户
            $this->assign('yestoday_member_num', $yestoday_member_num);//昨天新增用户
            $this->assign('accumulateRemission', $accumulateRemission);//累计返佣
            $this->assign('accumulateRemission_pm', $accumulateRemission_pm);//累计返佣
            $this->assign('today_accumulateRemission', $today_accumulateRemission);//今日累计返佣
            $this->assign('yestoday_accumulateRemission', $yestoday_accumulateRemission);//昨天累计返佣
            //总平台
            $this->assign('tody_jjpz', $tody_jjpz);//今日追加配资金额
            $this->assign('yes_jjpz', $tody_jjpz);//昨日追加配资金额
            $this->assign('tody_kdpz', $tody_kdpz);//今日扩大配资
            $this->assign('yes_kdpz', $tody_kdpz);//昨日扩大配资
            $this->assign('tody_zz_num', $tody_zz_num);//今日终止操盘
            $this->assign('yes_zz_num', $yes_zz_num);//昨日终止操盘
            $this->assign('tody_cp_num', $tody_zz_num);//今操盘活路用户
            $this->assign('yes_cp_num', $yes_cp_num);//昨操盘活路用户
            $this->assign('tody_ptyl', $tody_ptyl);//今日平台盈利
            $this->assign('yes_ptyl', $yes_ptyl);//昨日平台盈利
            //平台服务费
            $this->assign('tody_ptfww', $tody_ptfww);//今日平台服务费
            $this->assign('yes_ptfww', $yes_ptfww);//昨日平台服务费
            //总平台代理商数据统计
            $this->assign('proxy_total', $proxy_total);//代理商总数
            $this->assign('proxy_users', $proxy_users);//周活跃代理商
            $this->assign('today_proxy_money_recharge_num', $today_proxy_money_recharge_num);//代理商用户今日充值个数
            //
            $this->assign('today_proxy_money_recharge', $today_proxy_money_recharge);//代理商用户今日充值总和
            $this->assign('today_proxy_money_withdraw', $today_proxy_money_withdraw);//代理商用户今日提现总和
            $this->assign('today_time', $today_time);
            $this->assign('rq1', $rq1);
            $this->assign('rq2', $rq2);
            if ($webtype == 2) {
                $this->fetch('widget2', $config);
            } else {
                $this->fetch('widget1', $config);
            }
        }
    }

    /**
     * 安装方法
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function install()
    {
        return true;
    }

    /**
     * 卸载方法必
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function uninstall()
    {
        return true;
    }
}