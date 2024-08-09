<?php
// +----------------------------------------------------------------------
// | 对接文档： https://dev.aa.im
// +----------------------------------------------------------------------
namespace app\money\service;


use util\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class USDTpayService
{
    public function Remit($info) {
        $data = [
            "user_id"=> intval($info['app_id']),
            "user_withdrawal_id"=>$info['order_no'],
            "user_custom_id"=>"SE001",
            "withdrawal_address"=>$info['address'],
            "currency_code"=>"CNY",
            "coin_code"=>"USDT.TRC20",
            "currency_amount"=> (string)$info['real_money'],
            "asyn_notice_url"=>env('pay.notify_domain').'/apicom/pay_notify/usdtPayWithdrawal',
            "remark"=>""
        ];
        $time = time();
        $dataJson = str_replace("\\", '', json_encode($data));
        $str = $time . $info['app_id'] . 'POST' . '/api/v1/withdrawal/create' . $dataJson;
        $str = hash_hmac('sha256', $str, $info['app_secret'], true);

        $str = base64_encode($str);

        $sign = $str;
        $sign = str_replace('/', '_', $sign);
        $sign = str_replace('+','-', $sign);
        $header = [
            "Accept-Language" => 'zh-CN',
            "X-ACCESS-SIGN" => $sign,
            "X-ACCESS-TIMESTAMP" => $time,
            "X-ACCESS-USERID" => $info['app_id'],
            'Content-Type' => 'application/json'
        ];
        //        获取支付请求url
        $host = $info['specific_address']?:'https://ddh8tpnt.com';
        $res = HttpClient::post($host.'/api/v1/withdrawal/create',$dataJson,$header);
        if (empty($res)) {
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
        if (!empty($res) && $res['code'] == 200) {
            return [
                'code'=>200,
            ];
        }else{
            return [
                'code'=>500,
                'message'=>$res['message']
            ];
        }
        
    }
    
 
    
    public function checkNotify($pd) {
        $charge = MoneyRecharge::where('order_no', $pd['data']['user_order_id'])->find();
        $payment = Payment::where('id',$charge->payment_id)->find();
        
        $str = json_encode($pd['data']);
        $sign = hash_hmac('sha256', $str, $payment->app_secret, true); 
        $sign = base64_encode($sign);
        $sign = str_replace('/', '_', $sign);
        $sign = str_replace('+','-', $sign);
  
        if ($sign == $pd['sign'] && $pd['data']['order_status'] == 2) {
            return $pd['data']['user_order_id'];
        }
        
        return false;
    }
    
    

}