<?php
// +----------------------------------------------------------------------
// | 对接文档： http://mch-api.wd-pay.com/api/anon/apiDoc
// +----------------------------------------------------------------------
namespace app\apicom\service;


use util\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class WDpayService
{
    public function pay($info) {
 
        if (empty($info['extra_value'])) {
            return [
                'code'=>500,
                'message'=>'extra_value error'
            ];
        }
        $data = [
            'mchNo'=>$info['app_id'],
            'mchOrderNo'=>$info['order_no'],
            'amount'=>$info['amount']*100,
            'clientIp'=>request()->ip(),
            'productId'=>intval($info['extra_value']),
            'notifyUrl'=>env('pay.notify_domain').'/apicom/pay_notify/wdPay',
            'reqTime'=>time()
        ];
        $str = $this->generateUrlParams($data);
        $str .= "&key={$info['app_secret']}";
        $data['sign'] = md5($str);
        $data['sign'] = strtoupper($data['sign']);

        //        获取支付请求url
//        $url = 'http://pay-api.wd-pay.com/api/pay/unifiedOrder';
//        $payment = Payment::where('id',$info['payment_id'])->find();
//        if ($payment && !empty($payment->specific_address)) {
//            $url = $payment->specific_address;
//        }
        //        获取支付请求url
        $host = $info['specific_address']?:'http://pay-api.wd-pay.com';

        $res = HttpClient::post($host.'/api/pay/unifiedOrder',$data);
//        $res = HttpClient::post('http://pay-api.wd-pay.com/api/pay/unifiedOrder',$data);

        if (empty($res)) {
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
        if (!empty($res) && $res['code'] == 0) {
            return [
                'code'=>200,
                'pay_url'=>$res['data']['payData']
            ];
        }else{
            return [
                'code'=>500,
                'message'=>$res['msg']
            ];
        }
        
    }
    
    protected function generateUrlParams($data) {
        $params = array();
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $params[$key] = $value;
            }
        }
        ksort($params);// 按参数名进行排序
        
        $query = array();
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $query[] = $key . '=' . ($value);
            }
        }
        
        $urlParams = implode('&', $query); // 格式化参数为URL键值对的格式
        
        return $urlParams;
    }
 
    
    public function checkNotify($pd) {
        if ($pd['state'] == 2) {
            $charge = MoneyRecharge::where('order_no', $pd['mchOrderNo'])->find();
            $payment = Payment::where('id',$charge->payment_id)->find();
            $pdSign = $pd['sign'];
            unset($pd['sign']);
            $str = $this->generateUrlParams($pd);
            $str .= "&key={$payment->app_secret}";
            $sign = md5($str);
            $sign = strtoupper($sign);
            if ($sign == $pdSign) {
                return $pd['mchOrderNo'];
            }
        }
        return false;
    }
    
//实例
//     array(4) {
//         ["code"] => int(0)
//         ["data"] => array(5) {
//             ["mchOrderNo"] => string(26) "CZ202407151806262756053715"
//                 ["orderState"] => int(1)
//                 ["payData"] => string(54) "http://api.mkkapup.xyz/pay/2024071518ojXKDMzrKOAY.html"
//                     ["payDataType"] => string(6) "payUrl"
//                         ["payOrderId"] => string(20) "P1812790815966179329"
//         }
//         ["msg"] => string(7) "SUCCESS"
//             ["sign"] => string(32) "3C7602C5CB24A6C1051325D097FD8C38"
//     }

}