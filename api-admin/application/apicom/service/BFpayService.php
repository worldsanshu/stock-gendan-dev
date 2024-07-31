<?php
// +----------------------------------------------------------------------
// | 对接文档： http://mch-api.wd-pay.com/api/anon/apiDoc
// +----------------------------------------------------------------------
namespace app\apicom\service;


use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;
use util\HttpClient;

class BFpayService
{

    private function getSign($param,$key){
        ksort($param);
        reset($param);
        $signstr = '';

        foreach($param as $k => $v){
            if($k != "sign" && $k != "sign_type" && $v!=''){
                $signstr .= $k.'='.$v.'&';
            }
        }
        $signstr = substr($signstr,0,-1);
        $signstr .= $key;
        $sign = md5($signstr);
        return $sign;
    }

    public function pay($info) {
        if (empty($info['extra_value'])) {
            return [
                'code'=>500,
                'message'=>'extra_value error'
            ];
        }
        $data = [
            'pid'=>$info['app_id'],
            'type'=>$info['extra_value'],
            'out_trade_no'=>$info['order_no'],
            'money'=>$info['amount'],
            'name'=>$info['amount'],
            'clientip'=>request()->ip(),
            'notify_url'=>env('pay.notify_domain').'/apicom/pay_notify/bfPay'
        ];

        $arr = self::getSign($data,$info['app_secret']);
        $data['sign'] = $arr;
        $data['sign_type'] = 'MD5';

        //        获取支付请求url
        $host = $info['specific_address']?:'https://pay.yoyopays.com';
        $res = HttpClient::post($host.'/mapi.php',$data);
//        $res = HttpClient::post('https://pay.yoyopays.com/mapi.php',$data);
        if (empty($res)) {
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
//        $res = json_decode($res,true);
        if (!empty($res) && $res['code'] == 1 ) {
//            $data = json_decode($res['data'],true);
            return [
                'code'=>200,
                'pay_url'=>$res['payurl']
            ];
        }else{
            return [
                'code'=>500,
                'message'=>$res['msg']
            ];
        }
        
    }
    
    public function checkNotify($pd) {
        $charge = MoneyRecharge::where('order_no', $pd['out_trade_no'])->find();
        $payment = Payment::where('id',$charge->payment_id)->find();

        $sign = self::getSign($pd,$payment->app_secret);

        if ($sign == $pd['sign'] && $pd['trade_status'] == 'TRADE_SUCCESS') {
            return $pd['out_trade_no'];
        }
        return false;
    }
    
    

}