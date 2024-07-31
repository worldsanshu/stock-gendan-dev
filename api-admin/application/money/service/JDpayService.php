<?php
// +----------------------------------------------------------------------
// | 对接文档： https://www.showdoc.com.cn/2024104669376989/9148573432044603
// +----------------------------------------------------------------------
namespace app\money\service;


use util\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class JDpayService
{
    public function Remit($info) {
        $data = [
            'userCode'=>$info['app_id'],
            'orderCode'=>$info['order_no'],
            'amount'=>$info['real_money'],
            'address'=>$info['address'],
        ];
        $str = $data['orderCode'].'&'.$data['amount'].'&'.$data['address'].'&'.$data['userCode'].'&'.$info['app_secret'];
        $data['sign'] = md5($str);
        $data['sign'] = strtoupper($data['sign']);
        //        获取支付请求url

        $host = $info['specific_address']?:'https://openapi.jdpayapi.com';

        $res = HttpClient::post($host.'/jdpayOpen/api/remit',$data);

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
    

    
    

}