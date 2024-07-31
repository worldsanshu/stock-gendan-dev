<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | @author 伊冰华 <2851336094@qq.com>
// +----------------------------------------------------------------------
namespace app\fund\home;

use app\common\controller\Common;
use app\fund\model\FundProfitRecord;
use think\Db;
use think\helper\Hash;


class Profit extends Common
{

    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function initialize()
    {// 系统开关
        if (!config('web_site_status')) {
            ajaxmsg('站点已经关闭，请稍后访问', 0, '', true, ['msgCode' => 'L0015']);
        }
        $req   = request();
        $token = input("token");
        defined('MID') or define('MID', isLogin($token));
        if (!MID) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);

        $this->level_array = Funduserlevelarray();
    }

    public function getProfitConfig()
    {
//        $get_one = Db::name('fund_profit_config')->field('min_account,profit_start_time,profit_end_time')->find();
        $get_one['min_account'] = config('proflt_min_account');
        $get_one['buy_num'] = config('proflt_buy_num');
        $get_one['profit_start_time'] = date('H:i', config('profit_start_time'));
        $get_one['profit_end_time'] = date('H:i', config('profit_end_time'));
//        剩余可以提盈次数
        if($get_one['buy_num'] != '0'){
            $todayStart = date('Y-m-d 00:00:00'); // 今天开始时间
            $todayEnd = date('Y-m-d 23:59:59'); // 今天结束时间
            $withdraw_info = Db::name('fund_profit_record')
                ->where(['uid' => MID])
                ->whereTime('create_time', 'between', [$todayStart, $todayEnd]) // 使用查询表达式
                ->count();
            if($withdraw_info > $get_one['buy_num']){
                $get_one['residue_num'] = 0;
            }else{
                $get_one['residue_num'] = $get_one['buy_num'] - $withdraw_info;
            }
        }else{
            $get_one['residue_num'] = '-1';
        }
        return json(array(
            'data'    => $get_one,
            'status'  => 1,
            'message' => '获取配置'
        ));
    }


    /*
* 提取操作
*/
    public function doProfit()
    {
        $data = input();

        $data['mid'] = MID;
        $member_info = Db::name('member')->where(["id" => MID])->find();
//        $interest_config = Db::name('fund_profit_config')->field('min_account,profit_start_time,profit_end_time,buy_num')->find();
        // 查询交易日
        $record = Db::name('stock_trade_date')
            ->where('date', '=', date('Y-m-d', time()))
            ->find();
        if (!$record) {

            ajaxmsg('提盈必须在交易日内', 0);
        }

        $interest_config['min_account'] = config('proflt_min_account');
        $interest_config['buy_num'] = config('proflt_buy_num');
        $interest_config['profit_start_time'] = config('profit_start_time');
        $interest_config['profit_end_time'] = config('profit_end_time');
        $currentTime = strtotime(date('H:i:s', time()));


        if($interest_config['profit_start_time'] > $currentTime || $interest_config['profit_end_time'] < $currentTime){
            ajaxmsg('非提盈时间', 0);
        }
        if ($data['money'] < 0) {
            ajaxmsg('提取金额错误！', 0);
        }
        if ($data['money'] < $interest_config['min_account']) {
            ajaxmsg('提取金额必须大于'.$interest_config['min_account'].'元', 0);
        }


        $money_res = Db::name('fund_order_gs')->where(['uid' => MID,'id' => $data['order_id']])->find();
        if(!$money_res){
            ajaxmsg('订单不存在！', 0);
        }

        if ($money_res['status'] != 1) {
            ajaxmsg('订单状态错误', 0);
        }
        if ($money_res['balance'] < $data['money']) {
            ajaxmsg('订单盈利不足', 0);
        }

        $todayStart = date('Y-m-d 00:00:00'); // 今天开始时间
        $todayEnd = date('Y-m-d 23:59:59'); // 今天结束时间

        $withdraw_info = Db::name('fund_profit_record')
            ->where(['uid' => MID])
            ->whereTime('create_time', 'between', [$todayStart, $todayEnd]) // 使用查询表达式
            ->count();

        if($interest_config['buy_num'] != '0'){
            if ($withdraw_info >= $interest_config['buy_num']){
                ajaxmsg('今日提取次数已上限', 0);
            }
        }
        if (Hash::check((string)$data['paywd'], $member_info['paywd'])) {
            $res = FundProfitRecord::saveData($data);
        } else {
            ajaxmsg('支付密码错误', 0);
        }
        if ($res['status'] == 1) {
            ajaxmsg('已提交', 1);
        } else {
            ajaxmsg('提取失败', 0);
        }
    }
}