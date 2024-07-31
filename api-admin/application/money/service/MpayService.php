<?php
// +----------------------------------------------------------------------
// | 对接文档： https://document.mp-api.com/
// +----------------------------------------------------------------------
namespace app\money\service;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use phpseclib3\Crypt\PublicKeyLoader;
use think\Exception;
use think\facade\Log;
use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;

class MpayService
{
    protected $config;
    protected $clientMap = [];
 
    const USERAGENT = "MPAY_MERCH_CLIENT_v1_0";
    public function Remit($info) {
        try {
            $bodyData = [
                'merchUserId'=>$info['app_id'],
                'merchOrderId'=>$info['order_no'],
                'userWalletAddress'=>$info['address'],
                'amount'=>intval($info['amount']),
                'title'=>'平台提现'
            ];
            //        获取支付请求url
//            $url = 'https://1b763c18-3ca4-11ef-a2eb-864d3c14f554.mp-api.app';
//            $payment = Payment::where('id',$info['payment_id'])->find();
//            if ($payment && !empty($payment->specific_address)) {
//                $url = $payment->specific_address;
//            }
            //        获取支付请求url
            $host = $info['specific_address']?:'https://1b763c18-3ca4-11ef-a2eb-864d3c14f554.mp-api.app';
            $curl = $this->makeCurl($host);

//            $curl = $this->makeCurl('https://1b763c18-3ca4-11ef-a2eb-864d3c14f554.mp-api.app');
            $parmars = $this->makeRequestData($bodyData,$info['app_public_key'],$info['app_id'],$info['app_secret']);
            $response = $curl->request("GET", "/api/v2/merch/user/down?" . $parmars);

            $res = $response->toArray();
            if (empty($res)) {
                return [
                    'code'=>500,
                    'message'=>'network error'
                ];
            }
            if (!empty($res) && $res['code'] == 0) {
                return [
                    'code'=>200,
                ];
            }else{
                return [
                    'code'=>500,
                    'message'=>$res['message']
                ];
            }
        } catch (Exception $e) {
            Log::error('mPay失败----'.json_encode($e));
            return [
                'code'=>500,
                'message'=>'network error'
            ];
        }
        
    }
    
    public function checkNotify($pd) {
        if ($pd['status'] == true || $pd['status'] == 'true') {
            $charge = MoneyRecharge::where('order_no', $pd['merchOrderId'])->find();
            $payment = Payment::where('id',$charge->payment_id)->find(); 
            if ($this->parseUpAskRequest($pd,$payment->app_secret)) {
                return $pd['merchOrderId'];
            }
        }
        return false;
    }
    
    
    /**
     * @throws 
     */
    private function makeRequestData($bodyData,$publicRsaKey,$merchUserId,$md5Key)
    {
        $dt = new \DateTime("now", new \DateTimeZone('America/New_York'));
        $jsonBody = json_encode($bodyData);
        $key = PublicKeyLoader::loadPublicKey(base64_decode($publicRsaKey));
        $partLength = ($key->getLength() - 2 * 256 - 16) >> 3;
        $parts = str_split($jsonBody, $partLength);
        //var_dump('$parts::',$parts);
        $encrypted = "";
        foreach ($parts as $part) {
            $encryptedPart = $key->encrypt($part);
            $encrypted = $encrypted . $encryptedPart;
        }
        $dataBase64Str = $this->base64url_encode($encrypted);
        $timestamp = $dt->getTimestamp();
        $key = md5($merchUserId . $dataBase64Str . $timestamp . $md5Key);
        return "merchId=" . $merchUserId . "&body=" . $dataBase64Str . "&t=" . $timestamp . "&key=" . $key;
    }
    
    private function base64url_encode($data, $pad = null)
    {
        $data = str_replace(array('+', '/'), array('-', '_'), base64_encode($data));
        if (!$pad) {
            $data = rtrim($data, '=');
        }
        return $data;
    }
    
    protected function makeCurl(string $host): HttpClientInterface
    {
        if (array_key_exists($host, $this->clientMap)) {
            return $this->clientMap[$host];
        } else {
            $_client = HttpClient::create();
            $client = ScopingHttpClient::forBaseUri($_client, $host, [
                'headers' => [
                    'Accept' => 'application/json'
                ],
            ]);
            $this->clientMap[$host] = $client;
            return $client;
        }
    }
    private function toQueryString($arr)
    {
        $query_array = array();
        foreach ($arr as $key => $key_value) {
            $query_array[] = $key . '=' . $key_value;
        }
        return implode('&', $query_array);
    }
    /**解码回调数据
     */
    protected function parseUpAskRequest($form,$md5Key)
    {
        $signStr = $form["sign"];
        unset($form["sign"]);
        ksort($form);
        $querySource = $this->toQueryString($form) . $md5Key;
        $sign = md5($querySource);
        if (strtoupper($sign) == strtoupper($signStr)) {
            return true;
        } else {
            return false;
        }
    }
}