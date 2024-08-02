<?php
// +----------------------------------------------------------------------
// | 对接文档： http://mch-api.wd-pay.com/api/anon/apiDoc
// +----------------------------------------------------------------------
namespace app\money\service;

class OKpayService
{
    public function Remit($info)
    {

        $data         = [
            'sendid'  => $info['app_id'],
            'orderid' => $info['order_no'],
            'amount'  => $info['real_money'],
            'address' => $info['address'],
        ];
        $str          = $data['sendid'] . $data['orderid'] . $data['amount'] . $info['app_secret'];
        $data['sign'] = md5($str);
        $host         = $info['specific_address'] ?: 'https://qse123jdsz.okpay777.com/createwd';

        $curl = curl_init();
        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'https://qse123jdsz.okpay777.com/createwd',
            CURLOPT_URL            => $host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json'
            ),
        ));
        $res = curl_exec($curl);
        curl_close($curl);

        if (empty($res)) {
            return [
                'code'    => 500,
                'message' => 'network error'
            ];
        }
        $res = json_decode($res, true);
        if (!empty($res) && $res['code'] == 1 && !empty($res['data'])) {
            return [
                'code' => 200,
            ];
        } else {
            return [
                'code'    => 500,
                'message' => $res['msg']
            ];
        }
    }
}