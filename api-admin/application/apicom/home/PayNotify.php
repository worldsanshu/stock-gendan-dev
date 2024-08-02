<?php
namespace app\apicom\home;

use app\apicom\model\Money;
use app\apicom\model\MoneyRecharge;
use app\apicom\service\CBpayService;
use app\apicom\service\HZpayService;
use app\apicom\service\JDpayService;
use app\apicom\service\KdpayService;
use app\member\model\Member;
use app\money\model\Record;
use app\statistics\model\DataReport;
use app\statistics\model\RechargeWithdrawal;
use app\statistics\model\UserStatistics;
use think\Controller;
use think\Exception;
use think\facade\Log;
use app\apicom\service\MpayService;
use app\apicom\service\USDTpayService;
use app\apicom\service\TOpayService;
use app\apicom\service\OKpayService;
use app\apicom\service\WDpayService;
use app\apicom\service\BFpayService;
use app\apicom\service\SXYpayService;

class PayNotify extends Controller
{
    public function sxyPay()
    {
        $params = request()->param();
        Log::debug('sxy回调' . json_encode($params));
        $orderNo = (new SXYpayService())->checkNotify($params);
        
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    
    public function hzPay()
    {
        $params = request()->param();
        printlog($params, 'hz回调', 'log');
        $orderNo = (new HZpayService())->checkNotify($params);
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    public function bfPay()
    {
        $params = request()->param();
        printlog($params, 'bf回调', 'log');
        $orderNo = (new BFpayService())->checkNotify($params);
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    
    public function kdPay()
    {
        $params = request()->param();
        Log::debug('kd回调' . json_encode($params));
        $orderNo = (new KdpayService())->checkNotify($params);
       
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    public function cbPay()
    {
        $params = request()->param();
        Log::debug('cb回调' . json_encode($params));
        $orderNo = (new CBpayService())->checkNotify($params);
        
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    public function jdPay()
    {
        $params = request()->param();
        Log::debug('jd回调' . json_encode($params));
        $orderNo = (new JDpayService())->checkNotify($params);
        
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    public function mPay()
    {
        $params = request()->param();
        Log::debug('m回调' . json_encode($params));
        $orderNo = (new MpayService())->checkNotify($params);
        
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    public function usdtPay()
    {
        $params = request()->param();
        Log::debug('usdt回调' . json_encode($params));
        $orderNo = (new USDTpayService())->checkNotify($params);
        
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    public function toPay()
    {
        $params = request()->param();
        Log::debug('to回调' . json_encode($params));
        $orderNo = (new TOpayService())->checkNotify($params);
        
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    public function okPay()
    {
        $params = request()->param();
        Log::debug('ok回调' . json_encode($params));
        $orderNo = (new OKpayService())->checkNotify($params);
        
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
    public function wdPay()
    {
        $params = request()->param();
        Log::debug('wd回调' . json_encode($params));
        $orderNo = (new WDpayService())->checkNotify($params);
        
        if($orderNo){
            $this->handleOrder($orderNo);
            echo 'success';exit();
        };
    }
  
    private function handleOrder($orderNo)
    {
        try {
             
            $charge = MoneyRecharge::where('order_no', $orderNo)->find();
            if ($charge->is_pay == 1 || $charge->status == 1) { //已后台处理或者回调过
                return;
            }
            $charge->status = 1;
            $charge->remark = '充值成功';
            $charge->is_pay = 1;
            $charge->pay_time = time();
            $charge->save();//设置订单状态
            
            $money = Money::where('mid',$charge->mid)->find();
            $money->account = bcadd($money->account, $charge->money);
            $money->recharge_time = $charge->create_time;
            $money->save();//保存用户资金
             
            $record = new Record();//记录流水
            $info = '充值单号：' . $charge->order_no;
            $obj = ['affect' => $charge->money, 'account' => $money->account, 'affect_activity' => 0, 'activity_account' => $money->activity_account, 'sn' => ''];
            $record->saveData($charge->mid, $charge->money,$money->account, 1, $info, '', '', $obj);
            
            $this->updateMemberData($charge['mid'], $money->account, $charge['money']);//原先统计数据
            $this->statisticsData($charge->id);//原先统计数据 
            
        } catch (Exception $exception) {
            Log::error('【支付回调错误】' . $exception->getMessage());
            return false;
        }
    }
    
    //原先的更新用户数据
    private function updateMemberData($mid = '', $balance = 0, $moeny = 0)
    {
        if ($moeny > 0 && $mid && $balance > 0) {
            Member::where('id', $mid)->update(['balance' => $balance, 'user_property' => $balance]);
            Member::where('id', $mid)->setInc('recharge_num', 1);
            Member::where('id', $mid)->setInc('user_recharge', $moeny);
        }
    }
    //原先的统计内容
    private function statisticsData($id) { 
        
        //充值成功
        $recharge_data = MoneyRecharge::where('id', $id)->field('*')->find();
        //更新充值时间
        $update_data['recharge_time'] = $recharge_data['create_time'];
        Money::where('mid', $recharge_data['mid'])->update($update_data);
        $for_user = Member::where('id', $recharge_data['mid'])->value('agent_far');
        //添加到充值提现统计
        $post_data['recharge'] = $recharge_data['money'] / 100;
        $post_data['create_time'] = $recharge_data['create_time'];
        $post_data['recharge_user'] = 1;
        $post_data['for_user'] = $for_user;
        RechargeWithdrawal::appendRechargeWithdrawal($post_data);
        //用户统计
        UserStatistics::appendUserStatistics(['recharge_withdrawal' => 1, 'create_time' => $recharge_data['create_time'], 'for_user' => $for_user]);
        //数据报表
        $data_report ['user_recharge'] = 1;
        $data_report ['create_time'] = $recharge_data['create_time'];
        $data_report ['day_recharge'] = $recharge_data['money'] / 100;
        $mid = $recharge_data['mid'];
        $map = [];
        $map['mid'] = $mid;
        $map['status'] = 1;
        $count = MoneyRecharge::where($map)->count();
        if ($count == 1) {
            $create_time = Member::where('id', $mid)->value('create_time');
            if (date('Y-m-d', $create_time) == date('Y-m-d', $recharge_data['create_time'])) {
                $data_report['effective_user'] = 1;
            }
            $data_report['user_first_recharge'] = 1;
            MoneyRecharge::where('id', $id)->update(['is_first' => 1]);//标记为首充
        }
        DataReport::appendDataReport($data_report);
    }
    
    public function getIp()
    {
        $clientIP = null; 
        if (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
            // 使用代理的情况
            $clientIP = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
            // X-Forwarded-For 头信息可能会包含多个IP地址，第一个非unknown的IP为真实IP
            $clientIPs = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($clientIPs as $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $clientIP = $ip;
                    break;
                }
            }
        } else {
            // 没有代理的情况
            $clientIP = $_SERVER['REMOTE_ADDR'];
        } 
        ajaxmsg('success',1,$clientIP);
    }
}