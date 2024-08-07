<?php
// +----------------------------------------------------------------------
// | K豆钱包
// 对接文档：https://www.showdoc.com.cn/1836528338238130/8455236880773289
// +----------------------------------------------------------------------
namespace app\apicom\service;

use util\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class XSFpayService
{
    public function pay($info) {
        $data = [
            'merchNo'=>$info['account_information'],
            'appId'=>$info['app_id'],
            'payOrderId'=>$info['order_no'],
            'payAmt'=>$info['amount'],
            'reqTime'=>date('YmdHis',time()),
            'notifyUrl'=>env('pay.notify_domain').'/apicom/pay_notify/xsfPay'
        ];
        $arr = self::getSign($data,$info['app_secret']);
//        $str = $data['merchNo'].'&'.$data['appId'].'&'.$data['payOrderId'].'&'.$data['payAmt'].'&'.$data['reqTime'].'&'.$data['notifyUrl'].'&'.$info['app_secret'];
        $data['sign'] = strtoupper($arr);
        //        获取支付请求url

        $host = $info['specific_address']?:'http://api.hbzxwlkj.top';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $host.'/api/pay/createorder',
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
        $res = json_decode($res,true);
        if (empty($res)) {
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
        if (!empty($res) && $res['code'] == 0) {
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
        $signstr .= '&key='.$key;

        $sign = md5($signstr);
        return $sign;
    }
    
    public function checkNotify($pd) {
        printlog($pd['sign'], 'xsfPay签名', 'log');
        $charge = MoneyRecharge::where('order_no', $pd['payOrderId'])->find();
        $payment = Payment::where('id',$charge['payment_id'])->find();
        $data = [
            'payAmt'=>$pd['payAmt'],
            'payOrderId'=>$pd['payOrderId'],
            'reqTime'=>$pd['reqTime'],
            'appId'=>$pd['appId'],
            'currency'=>$pd['currency'],
            'state'=>$pd['state'],
            'mchNo'=>$pd['mchNo'],
        ];
        $sign = self::getSign($data,$payment->app_secret);
        $sign = strtoupper($sign);
        printlog($pd['sign'], 'xsfPay验签', 'log');
        if ($sign == $pd['sign'] && $pd['state'] == 2) {
            return $pd['payOrderId'];
        }else{
            return false;
        }
    }

    //响应结果
//array (
//'payOrderId' => 'CZ202408061738477342375903',
//'appId' => '66b06ec043fa229082e9ea6a',
//'sign' => '6810ED1971F23571DC2941B02A8E4FFE',
//'currency' => 'cny',
//'reqTime' => '1722937444402',
//'state' => '2',
//'payAmt' => '1.00',
//'mchNo' => 'M1722838720',
//);
}