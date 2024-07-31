<?php
// +----------------------------------------------------------------------
// | 对接文档： https://www.showdoc.com.cn/2024104669376989/9148573432044603
// +----------------------------------------------------------------------
namespace app\apicom\service;


use util\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class CBpayService
{
    public function pay($info) {
        $data = [
            'userCode'=>$info['app_id'],
            'orderCode'=>$info['order_no'],
            'amount'=>$info['amount'],
            'payType'=>3,
            'callbackUrl'=>env('pay.notify_domain').'/apicom/pay_notify/cbPay'
        ];
        $str = $data['orderCode'].'&'.$data['amount'].'&'.$data['payType'].'&'.$data['userCode'].'&'.$info['app_secret'];
        $data['sign'] = md5($str);
        $data['sign'] = strtoupper($data['sign']);

        //        获取支付请求url
        //        获取支付请求url
        $host = $info['specific_address']?:'http://api.cbpayconnect.com';
        $res = HttpClient::post($host.'/system/api/pay',$data);
//        $res = HttpClient::post('http://api.cbpayconnect.com/system/api/pay',$data);
        if (empty($res)) {
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
        if (!empty($res) && $res['code'] == 200) {
            return [
                'code'=>200,
                'pay_url'=>$res['data']['url']
            ];
        }else{
            return [
                'code'=>500,
                'message'=>$res['message']
            ];
        }
        
    }
    
    public function checkNotify($pd) {
        $charge = MoneyRecharge::where('order_no', $pd['orderCode'])->find();
        $payment = Payment::where('id',$charge->payment_id)->find();
        $str = $pd['orderCode'].'&'.$pd['amount'].'&'.$pd['userCode'].'&'.$pd['status'].'&'.$payment->app_secret;
        $sign = md5($str);
        $sign = strtoupper($sign);
        if ($sign == $pd['sign']) {
            return $pd['orderCode'];
        }else{
            return false;
        }
    }
    
    

}