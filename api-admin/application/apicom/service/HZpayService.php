<?php
// +----------------------------------------------------------------------
// | 对接文档： http://mch-api.wd-pay.com/api/anon/apiDoc
// +----------------------------------------------------------------------
namespace app\apicom\service;


use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;
use Exception;
use phpseclib3\Crypt\RSA;
use util\HttpClient;

class HZpayService
{
    private function buildSignData($params) {

        $signStr = "";
        foreach ($params as $x => $x_value) {
            if ($x_value == "0" || !empty($x_value)) {
                if ($signStr == "") {
                    $signStr = $signStr . $x . '=' . $x_value;
                } else {
                    $signStr = $signStr . '&' . $x . '=' . $x_value;
                }
            }
        }
        return $signStr;
    }

    private function rsaSign($plainText, $path){
        try {
            $resource = openssl_pkey_get_private($path);
            $result = openssl_sign($plainText, $sign, $resource);
            openssl_free_key($resource);

            if (!$result) {
                return;
            }
            return base64_encode($sign);
        } catch (Exception $e) {
            return;
        }
    }


    private function getSign($str, $md5Key)
    {
        return md5($str . '&key=' . $md5Key);
    }

    public function pay($info) {

        $data = [
            'mchid'=>$info['app_id'],
            'out_trade_no'=>$info['order_no'],
            'amount'=>$info['amount'],
            'bank_code'=>'ICBC',
            'bank_number'=>'123456789123456789',
            'bank_owner'=>'张三',
            'body'=>'3',
            'notify_url'=>env('pay.notify_domain').'/apicom/pay_notify/hzPay',
        ];

        $signData = self::buildSignData($data);
        $rsaSign = self::getSign($signData, 'CxeIEx6iZNb5Et8hmqSuf0O1uffi8H6dRMuxRz16');
        $data['sign'] = $rsaSign;
        //        获取支付请求url
        $host = $info['specific_address']?:'https://gateway.zhbaopay.com';
        $url = $host . "/api/remitOrder/v2/create";
        //搞不懂标准的请求不行 每个支付都有多样性
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'x-forwarded-for: 18.167.86.140'));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
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


    public function payRsa($info) {
        
        $data = [
            'merchantId'=>$info['app_id'],
            'version'=>'1.0.0',
            'merchantOrderNo'=>$info['order_no'],
            'amount'=>$info['amount'],
            'bankCode'=>'ICBC',
            'bankcardAccountNo'=>'123456789123456789',
            'bankcardAccountName'=>'张三',
            'notifyUrl'=>env('pay.notify_domain').'/apicom/pay_notify/hzPay',
        ];
        
        $signData = self::buildSignData($data);

        $rsaSign = self::rsaSign($signData,$info['app_public_key']);

        $data['sign'] = $rsaSign;
        $reqData = json_encode($data);
        //        获取支付请求url

        $host = $info['specific_address']?:'https://gateway.zhbaopay.com';
        $url = $host . "/api/payOrder/create";
        //搞不懂标准的请求不行 每个支付都有多样性
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $reqData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        print_r($res);die;
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