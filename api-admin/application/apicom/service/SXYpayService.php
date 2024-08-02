<?php
// +----------------------------------------------------------------------
// | 守信易支付通道 对接文档： https://pay.yoyopays.com/doc.html
// +----------------------------------------------------------------------
namespace app\apicom\service;



use Symfony\Component\HttpClient\HttpClient;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class SXYpayService
{
    private $pid;
    private $key;
    public function pay($info) {
        $this->key = $info['app_secret'];
        $data = [
            'mchId'=>(int)$info['app_id'],
            'appId'=>$info['app_public_key'],
            'productId'=>8038,
            'mchOrderNo'=>$info['order_no'],
            'currency'=>'cny',
            'amount'=>(int)$info['amount']*100,
            'clientIp'=>$_SERVER['SERVER_ADDR'],
            'notifyUrl'=>env('pay.notify_domain').'/apicom/pay_notify/sxyPay',
            'subject'=>'平台充值',
            'body'=>'平台充值'
        ];
        $host = $info['specific_address']?:'http://qerpay.pengusk.com';
        $param = $this->buildRequestParam($data);
        $client = HttpClient::create();
        $response = $client->request('POST', $host.'/api/payAPI/create', [
            'body' => $param
        ]);
        $res = $response->toArray();
        if (empty($res)) {
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
        if (!empty($res) && $res['retCode'] == 'SUCCESS') {
            return [
                'code'=>200,
                'pay_url'=>$res['payParams']['payUrl']
            ];
        }else{
            return [
                'code'=>500,
                'message'=>$res['errDes']
            ];
        }
        
    }
    public function checkNotify($pd) {
        $charge = MoneyRecharge::where('order_no', $pd['mchOrderNo'])->find();
        $payment = Payment::where('id',$charge->payment_id)->find();
        $pdsign = $pd['sign'];
        unset($pd['sign']);
        $this->key = $payment->app_secret;
        $mysign = $this->getSign($pd);
        if ($mysign == $pdsign &&  $pd['status'] == 2) {
            return $pd['mchOrderNo'];
        }
        return false;
    }
    
    private function buildRequestParam($param){
        $mysign = $this->getSign($param);
        $param['sign'] = $mysign;
        return $param;
    }
    private function getSign($param){
        ksort($param);
        reset($param);
        $signstr = '';
        foreach($param as $k => $v){
            if($k != "sign" && $k != "sign_type" && $v!=''){
                $signstr .= $k.'='.$v.'&';
            }
        }
        $signstr = substr($signstr,0,-1);
        $signstr .= '&keySign='.$this->key.'Apm';
        $sign = md5($signstr);
        return $sign;
    }
 
}