<?php
// +----------------------------------------------------------------------
// | 对接文档： 
// +----------------------------------------------------------------------
namespace app\apicom\service;



use Symfony\Component\HttpClient\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class TOpayService
{
    public function pay($info) {
        
        $data = [
            'recvid'=>$info['app_id'],
            'orderid'=>$info['order_no'],
            'amount'=>$info['amount'],
            'notifyurl'=>env('pay.notify_domain').'/apicom/pay_notify/toPay'
        ];
 
        $str = $data['recvid'].$data['orderid'].$data['amount'].$info['app_secret'];
        $data['sign'] = md5($str);

        //        获取支付请求url
//        $url = 'https://toshcshf01.topay365.com/createpay';
//        $payment = Payment::where('id',$info['payment_id'])->find();
//        if ($payment && !empty($payment->specific_address)) {
//            $url = $payment->specific_address;
//        }
        //        获取支付请求url
        $host = $info['specific_address']?:'https://toshcshf01.topay365.com';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $host.'/createpay',
//            CURLOPT_URL => 'https://toshcshf01.topay365.com/createpay',
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
            $data = json_decode($res['data'],true);
            return [
                'code'=>200,
                'pay_url'=>$data['navurl']
            ];
        }else{
            return [
                'code'=>500,
                'message'=>$res['msg']
            ];
        }
        
    }
    
    public function checkNotify($pd) {
        $charge = MoneyRecharge::where('order_no', $pd['orderid'])->find();
        $payment = Payment::where('id',$charge->payment_id)->find();
        $str = $pd['sign'].$payment->app_secret;
        $sign = md5($str);
        if ($sign == $pd['retsign'] && $pd['state'] == 4) {
            return $pd['orderid'];
        }
        return false;
    }

    

}