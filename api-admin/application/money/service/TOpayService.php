<?php
// +----------------------------------------------------------------------
// | 对接文档： 
// +----------------------------------------------------------------------
namespace app\money\service;



use Symfony\Component\HttpClient\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class TOpayService
{
    public function Remit($info) {

        $data = [
            'sendid'=>$info['app_id'],
            'orderid'=>$info['order_no'],
            'amount'=>$info['real_money'],
            'address'=>$info['address'],
        ];
        $str = $data['sendid'].$data['orderid'].$data['amount'].$info['app_secret'];
        $data['sign'] = md5($str);
        //        获取支付请求url
        //        获取支付请求url
        $host = $info['specific_address']?:'https://toshcshf01.topay365.com';

        //搞不懂标准的请求不行 每个支付都有多样性
        $curl = curl_init();
        curl_setopt_array($curl, array(
            URLOPT_URL => $host.'/createwd',
//            CURLOPT_URL => 'https://toshcshf01.topay365.com/createwd',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST=>0,
            CURLOPT_SSL_VERIFYPEER=>0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        if (empty($res)) {
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
        $res = json_decode($res,true);
        //dump($res);
        if (!empty($res) && $res['code'] == 1 && !empty($res['data'])) {
            return [
                'code'=>200
            ];
        }else{
            return [
                'code'=>500,
                'message'=>$res['msg']
            ];
        }

    }
    

}