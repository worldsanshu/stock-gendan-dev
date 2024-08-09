<?php

namespace app\index\controller;

use app\money\model\Money;
use think\Db;

/**
 * 通知处理控制器
 *
 * 完善getOrder, 获取订单信息, 注意必须数组必须包含out_trade_no与total_amount
 * 完善checkOrderStatus, 返回订单状态, 按要求返回布尔值
 * 完善handle, 进行业务处理, 按要求返回布尔值
 *
 * 请求地址为index, NotifyHandler会自动调用以上三个方法
 */
class Notify extends NotifyHandler
{
    protected $params; // 订单信息

    public function index()
    {
        parent::init();
    }

    public function alipay()
    {
        //$_POST = json_decode('{"gmt_create":"2019-02-11 15:32:54","charset":"UTF-8","seller_email":"prprpd@163.com","subject":"\u7b56\u7565\u652f\u4ed8","sign":"qIDvJLXUw66X6zuZ3SC7h4j966FfO+runL5D6aVpOGWdcFK0DVCoYQ0KCjiQLQPMqOEgFQCssf65aWn\/tXTd0UuqpKETSQ+8UFxh1CNUTK\/CGHrXfHqPKA1Yc9i2piK1XmU+PuUkqLS57NPPxqQMQI+ir3+WW81Rg68e31ZE\/jPQJ746wJk3cgCaCjKROTX\/hjY9pkcwHrvU3LRy62ewyCjdfAZOIwY4o\/AfsH4V94ILUmgDkOQ0FRS8vYaw9AYj6HtKnAF2eVeodlf2od1AA+VD3EquMBnIdqHmdGzIRx9vc+sTJTVzu0Hc3Tk9+7L1QogGV8WGF7\/I+PkqcvHNwA==","buyer_id":"2088202620486215","invoice_amount":"0.01","notify_id":"2019021100222153255086211040464155","fund_bill_list":"[{\"amount\":\"0.01\",\"fundChannel\":\"PCREDIT\"}]","notify_type":"trade_status_sync","trade_status":"TRADE_SUCCESS","receipt_amount":"0.01","buyer_pay_amount":"0.01","app_id":"2019012363120496","sign_type":"RSA2","seller_id":"2088431290360129","gmt_payment":"2019-02-11 15:32:55","notify_time":"2019-02-11 15:32:55","version":"1.0","out_trade_no":"cz4163974923","total_amount":"0.01","trade_no":"2019021122001486211015331718","auth_app_id":"2019012363120496","buyer_logon_id":"zha***@live.cn","point_amount":"0.00"}', true);
        $trade_no = $_POST['out_trade_no'];//input('out_trade_no');
        $trade_status = $_POST['trade_status'];//input('trade_status');
        $total_amount = $_POST['total_amount'];//input('total_amount');
        $order = Db::name('money_recharge')->where('order_no', $trade_no)->find();
        if ($trade_status == 'TRADE_SUCCESS' && $order['status'] == 0) {
            Db::startTrans();
            $up_charge['status'] = 1;
            $up_charge['id'] = $order['id'];
            $up_charge['remarks'] = json_encode($_POST);
            $contents = "充值成功";
            $account = Db::name('money')->where('mid', $order['mid'])->lock(true)->value('account');
            $up_money['account'] = bcadd($account, $order['money']);
            $res1 = Db::name('money')->where('mid', $order['mid'])->update($up_money);
            $info = '充值单号：' . $order['order_no'];
            $record = new \app\money\model\Record;
//            $res3 = $record->saveData($order['mid'],  $order['money'], $up_money['account'], 1, $info);
            $user_balance = Money::getMoney($order['mid']);
            $obj = ['affect' => $order['money'], 'account' => $up_money['account'], 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];//更新成功才充值,避免重复充值
            $res3 = $record->saveData($order['mid'], $order['money'], $up_money['account'], 1, $info, '', '', $obj);
            $res2 = Db::name('money_recharge')->update($up_charge);
            if ($res1 && $res2 && $res3) {
                $mobile = Db::name('member')->where('id', $order['mid'])->value('mobile');
                #送抽奖次数
                #判断当日是否领取
                $sql = 'SELECT count(*) as numcount FROM lmq_operate_record WHERE `mid` ="' . $order['mid'] . '" and type=4 AND date_format(from_unixtime(addtime),"%Y-%m-%d") = date_format(now(),"%Y-%m-%d")';
                $luckycount = Db::query($sql);
                $luckycount = $luckycount[0]['numcount'];
                $sql = 'SELECT sum(money) as account FROM lmq_money_recharge WHERE `mid` ="' . $order['mid'] . '"  AND date_format(from_unixtime(create_time),"%Y-%m-%d") = date_format(now(),"%Y-%m-%d")';
                $luckycount2 = Db::query($sql);
                $luckycount2 = $luckycount2[0]['account'];
                $frequency = floor($luckycount2 / 10000000);
                $Availability = $frequency - $luckycount;
                if ($Availability > 1) {
                    $prizecount = Db::name('operate_user')->where(array("mid" => $order['mid']))->find()['count'];
                    $count = explode("-", config('luck_recharge'))[1];
                    $newcount = $count * $Availability + $prizecount;
                    Db::name('operate_user')->where('mid', $order['mid'])->update(['count' => $newcount]);
                    #抽奖次数获得/消耗明细
                    $insetrecorddata['num'] = $count * $Availability;
                    $insetrecorddata['mid'] = $order['mid'];
                    $insetrecorddata['type'] = 4;
                    $insetrecorddata['info'] = "老用户登陆获得" . $count * $Availability . "次抽奖机会";
                    $insetrecorddata['addtime'] = time();
                    Db::name('operate_record')->insert($insetrecorddata);
                }
                Db::commit();
                //添加站内信信息
                $MemberMessageModel = new \app\member\model\MemberMessage;
                $MemberMessageModel->addInnerMsg($order['mid'], '充值通知', $contents, 2);//站内信
                send_sms($mobile, 'sms_recharge', 0, bcdiv($order['money'], 100, 2));
                return true;
            } else {
                Db::rollback();
                return false;
            }
        }
    }

    /**
     * 获取订单信息, 必须包含订单号和订单金额
     *
     * @return string $params['out_trade_no'] 商户订单
     * @return float  $params['total_amount'] 订单金额
     */
    public function getOrder()
    {
        // 以下仅示例
        $out_trade_no = $_POST['out_trade_no'];
        $order = Db::name('order')->where('out_trade_no', $out_trade_no)->find();
        $params = [
          'out_trade_no' => $order['out_trade_no'],
          'total_amount' => $order['total_amount'],
          'status' => $order['status'],
          'id' => $order['id'],
          'subject' => $order['subject']
        ];
        $this->params = $params;
    }

    /**
     * 检查订单状态
     *
     * @return Boolean true表示已经处理过 false表示未处理过
     */
    public function checkOrderStatus()
    {
        // 以下仅示例
        if ($this->params['status'] == 0) {
            // 表示未处理
            return false;
        } else {
            return true;
        }
    }

    /**
     * 业务处理
     * @return Boolean true表示业务处理成功 false表示处理失败
     */
    public function handle()
    {
        // 以下仅示例
        $result = Db::name('order')->where('id', $this->params['id'])->update(['status' => 1]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}