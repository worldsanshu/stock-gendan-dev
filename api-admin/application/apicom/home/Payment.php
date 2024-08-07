<?php
// +----------------------------------------------------------------------
// | 新支付整合
// +----------------------------------------------------------------------

namespace app\apicom\home;



use app\apicom\service\CBpayService;
use app\apicom\service\HZpayService;
use app\apicom\service\JDpayService;
use app\apicom\service\KdpayService;
use app\apicom\service\MpayService;
use app\apicom\service\OKpayService;
use app\apicom\service\RechargeService;
use app\apicom\service\TOpayService;
use app\apicom\service\USDTpayService;
use app\apicom\service\XSFpayService;
use app\member\model\Member;
use app\apicom\service\BDTpayService;
use app\apicom\service\WDpayService;
use app\apicom\service\BFpayService;
use app\apicom\service\SXYpayService;

class Payment extends Common
{
    //充值
    public function recharge() {
        $data = input();
        $money = $data['money'];
        if ($money <= 0) {
            ajaxmsg('充值金额必须大于0', 0, '', true, ['msgCode' => 'L0073']);
        }
        $paymentData = \app\money\model\Payment::where('id', $data['payment_id'])->find();
        if (empty($paymentData)) {
            ajaxmsg('payment not supported', 0);
        }
        if ($paymentData->min_money > $money) {
            ajaxmsg('充值金额最低为' . $paymentData->min_money, 0, '', true, ['msgCode' => 'L0162', 'params' => ["{$paymentData->min_money}"]]);
        }
        if ($paymentData['max_money'] < $money) {
            ajaxmsg('充值金额最高为' . $paymentData->max_money, 0, '', true, ['msgCode' => 'L0163', 'params' => ["{$paymentData->max_money}"]]);
        }
        //创建订单
        $data['mid'] = MID;
        $data['is_auto'] = $paymentData->is_auto;
        $data['num'] = $data['num'] ?? $money;
        $data['order_no'] = generate_order_no('CZ');
        $data['money'] = $money*100;
        $data['type'] = $paymentData->type;
        $orderId = (new RechargeService())->createOrder($data);
        
        $orderInfo = [
            'id' => $orderId,
            'order_no' =>$data['order_no'],
            'amount' => $money,
            'type'=>$paymentData->type,
            'app_id'=>$paymentData->app_id,
            'app_secret'=>$paymentData->app_secret,
            'app_public_key'=>$paymentData->app_public_key,
            'is_online'=>$paymentData->is_online,
            'extra_value'=>$data['extra_value']??'',
            'specific_address'=>$paymentData->specific_address,
            'account_information'=>$paymentData->account_information
        ];
        if ($paymentData->is_online != 1) { //线下转账
            $this->offline($orderInfo);
        }else{ //线上支付
            $this->online($orderInfo);
        }
    }
    //
    protected function offline($orderInfo) { //线下
        $var = Member::where('id', MID)->value('mobile');
        $contentarr = getconfigSms_status(['name' => 'stock_offline']);
        $content = str_replace(array("#var#", "#amount#"), array($var, $orderInfo['money']), $contentarr['value']);
        if ($contentarr['status'] == 1) {
            sendsms_mandao('', $content, '');
        }
        ajaxmsg('充值已提交，请耐心等待审核', 1, ['id' => $orderInfo['orderId'],'is_online'=>0], true, ['msgCode' => 'L0161']);
    }
     
    protected function online($orderInfo) { //线上
        $res = [];
        switch ($orderInfo['type']) {
            case 'kdpay':
                $res = (new KdpayService())->pay($orderInfo);
            break;
            case 'cbpay':
                $res = (new CBpayService())->pay($orderInfo);
                break;
            case 'jdpay':
                $res = (new JDpayService())->pay($orderInfo);
                break;
            case 'mpay':
                $res = (new MpayService())->pay($orderInfo);
                break;
            case 'bdtpay':
                $res = (new BDTpayService())->pay($orderInfo);
                break;
            case 'usdtpay':
                $res = (new USDTpayService())->pay($orderInfo);
                break;
            case 'topay':
                $res = (new TOpayService())->pay($orderInfo);
                break;
            case 'okpay':
                $res = (new OKpayService())->pay($orderInfo);
                break;
            case 'wdpay':
                $res = (new WDpayService())->pay($orderInfo);
                break;
            case 'bfpay':
                $res = (new BFpayService())->pay($orderInfo);
                break;
            case 'hzpay':
                $res = (new HZpayService())->pay($orderInfo);
                break;
            case 'sxypay':
                $res = (new SXYpayService())->pay($orderInfo);
                break;
            case 'xsfpay':
                $res = (new XSFpayService())->pay($orderInfo);
                break;
            default:;
            break;
        }
        if ($res['code'] == 200) {
            $result = [
                'is_online'=>1
                ,'pay_url'=>$res['pay_url']
                ,'order_no'=>$orderInfo['order_no']
            ];
            ajaxmsg('ok',1,$result);
        }else{
            ajaxmsg($res['message'],0);
        }
        
    }
}
