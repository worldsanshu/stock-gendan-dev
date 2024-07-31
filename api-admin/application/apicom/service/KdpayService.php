<?php
// +----------------------------------------------------------------------
// | K豆钱包
// 对接文档：https://www.showdoc.com.cn/1836528338238130/8455236880773289
// +----------------------------------------------------------------------
namespace app\apicom\service;

use util\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class KdpayService
{
    public function pay($info) {
        $data = [
            'userCode'=>$info['app_id'],
            'orderCode'=>$info['order_no'],
            'amount'=>$info['amount'],
            'payType'=>3,
            'callbackUrl'=>env('pay.notify_domain').'/apicom/pay_notify/kdPay'
        ];
        $str = $data['orderCode'].'&'.$data['amount'].'&'.$data['payType'].'&'.$data['userCode'].'&'.$info['app_secret'];
        $data['sign'] = md5($str); 
        $data['sign'] = strtoupper($data['sign']);

        //        获取支付请求url

        $host = $info['specific_address']?:'http://api.kby.cc';

        $res = HttpClient::post($host.'/system/api/pay',$data);
//        $res = HttpClient::post('http://api.kby.cc/system/api/pay',$data);
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
        if ($sign == $pd['sign'] && $pd['status'] == 3) {
            return $pd['orderCode'];
        }else{
            return false;
        }
    }

    //响应结果
    //         array(3) {
    //             ["code"] => int(200)
    //             ["data"] => array(4) {
    //                 ["payAmount"] => string(12) "10000.000000"
    //                 ["orderNo"] => string(20) "20240715142030UKXQ5w"
    //                 ["rate"] => string(3) "1.0"
    //                 ["url"] => string(64) "https://kpa123.kd139xu.com/pay/#/?orderCode=20240715142030UKXQ5w"
    //             }
    //             ["message"] => string(7) "SUCCESS"
    //         }
}