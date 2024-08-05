<?php
// +----------------------------------------------------------------------
// | 对接文档： https://www.showdoc.com.cn/2024104669376989/9148573432044603
// +----------------------------------------------------------------------
namespace app\money\service;


use app\apicom\model\MoneyRecharge;
use app\money\model\Payment;
use util\HttpClient;

class BDTpayService
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
    public function Remit($info) {
        $bank = config('web_bank');
        $data = [
            'merchantId'=>$info['app_id'],
            'merchantUniqueOrderId'=>$info['order_no'],
            'currency'=>'CNY',
            'amount'=>(string)$info['real_money'],
            'bankCardNumber'=>$info['bank_number'],
//            'bankName'=>$info['bank_code'],
            'bankName'=>$bank[$info['bank_code']],
            'bankRealName'=>$info['bank_owner'],
            'notifyUrl'=>env('pay.notify_domain').'/apicom/pay_notify/bdPay'
        ];
        ksort($data);
        $signData = self::buildSignData($data);
//        $data['sign'] = md5($signData.'WNeEFUSmTLH5RLC2qVKmcrZlqzDCuFoe');
        $data['sign'] = md5($signData.$info['app_secret']);
//        获取支付请求url
        $host = $info['specific_address']?:'https://api.pay4.today';
        $url = $host . "/interface/payments/CreateWithdrawOrder";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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

        if (!empty($res) && $res['code'] == 200) {
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
    
    public function checkNotify($data) {
        
        
        return true;
    }
    
    

}