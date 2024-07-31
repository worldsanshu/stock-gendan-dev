<?php
// +----------------------------------------------------------------------
// | K豆钱包
// 对接文档：https://www.showdoc.com.cn/1836528338238130/8455236880773289
// +----------------------------------------------------------------------
namespace app\money\service;

use util\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class KdpayService
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
//        $res = HttpClient::post('http://api.kby.cc/system/api/remit',$data);
        $host = $info['specific_address']?:'http://api.kby.cc';

        $res = HttpClient::post($host.'/system/api/remit',$data);
        if (empty($res)) {
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
        if (!empty($res) && $res['code'] == 200) {
            return [
                'code'=>200
            ];
        }else{
            return [
                'code'=>500,
                'message'=>$res['message']
            ];
        }
       
    }


}