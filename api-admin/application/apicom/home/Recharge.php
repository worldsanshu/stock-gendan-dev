<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace app\apicom\home;

use app\member\home\Common;
use app\money\model\Payment as PaymentModel;
use app\money\model\Recharge as ChargeModel;
use think\Db;
use app\money\model\Payment;

/**
 * 前台首页控制器
 * @package app\money\home
 */
class Recharge extends Common
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 首页
     * @return [type] [description]
     */
    public function index()
    {
        $money = \app\money\model\Money::getMoney(MID);
        $money['account'] = bcdiv($money['account'], 100, 2);
        $money['operate_account'] = bcdiv($money['operate_account'], 100, 2);
        $money['operate_account'] = bcdiv($money['operate_account'], 100, 2);
        $data['money'] = $money;
        $data['account'] = config('web_site_account');
        ajaxmsg('用户账户信息', 1, $data);
    }

    //获取支付方式
    public function getPayType()
    {
        $list = Db::name('pay_type')->order('id asc')->field('name,type')->select();
        $data['list'] = $list;
        ajaxmsg('获取支付方式', 1, $data);
    }

    //获取钱包列表
    public function getPaymentList()
    {
        $type = input('type');
        $filed = "*,qrcode qrcode_url,logo logo_url,support_type,withdrawal_param";
        if(empty($type)){
//            充值列表
            $map = [
                ['status', 'eq', 1],
                ['support_type','in',[1, 3]] //仅充值
            ];
            $list = PaymentModel::where($map)->field($filed)->order('sort asc ,id desc')->select();
            $list = $list->toArray();
            foreach ($list as &$value) {
                if (in_array($value['type'], Payment::$online)) {
                    $value['logo_url'] = PaymentModel::translateLogo($value['type']);
                }
            }
            unset($value);
        }else{
            if($type=='wallet'){  //添加钱包的时候
                $map = [
                    ['status', 'eq', 1],
                    ['support_type','in',[2, 3]] //仅提现
                    ];
                $list = PaymentModel::where($map)->field($filed)->order('sort asc ,id desc')->select();
            }elseif ($type=='currency'){
                $map = [['status', 'eq', 1],['type','eq', $type]];
                $list = PaymentModel::where($map)->field($filed)->order('sort asc ,id desc')->select();
            }else{
                $map = [['status', 'eq', 1],['type','eq', $type]];
                $list = PaymentModel::where($map)->field($filed)->order('sort asc ,id desc')->find();
            }
        }

        $data['list'] = $list;
        ajaxmsg('获取钱包列表', 1, $data);
    }

    //获取钱包列表
    public function getPaymentList2()
    {
        $type = input('type');
        $map = [['status', 'eq', 1], ['type', 'eq', $type]];
        $filed = "*,qrcode qrcode_url,logo logo_url";
        $list = PaymentModel::where($map)->field($filed)->order('id desc')->select();
        $count = count($list) - 1;
        $key = rand(0, $count);
        if ($type != 'currency') {
            $list = $list[$key];
        }
        $data['is_close'] = 0;
        if (!$list) {
            $data['is_close'] = 1;
        }
        $data['list'] = $list;
        ajaxmsg('获取钱包列表', 1, $data);
    }

    public function editCharge()
    {
        $money = \app\money\model\Money::getMoney(MID);
        $account = Db::name("admin_bank")->where(['status' => 1])->select();
        foreach ($account as $k => $v) {
            if (!empty($v['image'])) {
                $account[$k]['image'] = get_file_path($v['image']);
            }
        }
        $data['money'] = $money;
        $data['offline'] = config('web_site_account');
        $data['account'] = $account;
        $data['kftime'] = config('web_site_service_time');
        $data['kfphone'] = config('web_site_telephone');
        ajaxmsg('用户账户信息', 1, $data);
    }

    public function doCharge()
    {
        $data = input();
        $line_bank = '';
        $receipt_img = '';
        $charge_type_id = 0;
        $form_name = $data['form_name'];
        $money = $data['money'];
        if ($money <= 0) {
            ajaxmsg('充值金额必须大于0', 0, '', true, ['msgCode' => 'L0073']);
        }
        $type = $data['transfer']; // transfer 线下转账支付
        if ($type == 'transfer') {
            $card_id = $data['cardno'];
            $line_bank = '卡号：' . $card_id . '；时间：' . date('Y-m-d', time());
        }
        $order_no = ChargeModel::saveData($money, MID, $type, $line_bank, $receipt_img, $charge_type_id, $form_name);
        if ($type == 'transfer') {
            $var = Db::name('member')->where('id', MID)->value('mobile');
            $contentarr = getconfigSms_status(['name' => 'stock_offline']);
            $content = str_replace(array("#var#", "#amount#"), array($var, $money), $contentarr['value']);
            if ($contentarr['status'] == 1) {
                sendsms_mandao('', $content, '');
            }
            ajaxmsg('充值已提交，请耐心等待审核', 1, '', true, ['msgCode' => 'L0161']);
        } else {
            ajaxmsg('走接口才能完成', 0);
        }
    }

    /*
     * 充值操作
     */
    public function doChargeCurrency()
    {
        $data = input();
        $line_bank = '';
        $receipt_img = '';
        $charge_type_id = 0;
//        $form_name = $data['form_name'];
        $money = $data['money'];
        if ($money <= 0) {
            ajaxmsg('充值金额必须大于0', 0, '', true, ['msgCode' => 'L0073']);
        }
        $payment_data = PaymentModel::where('type', $data['type'])->field('is_auto,min_money,max_money')->find();
        if ($payment_data['min_money'] > $money) {
            ajaxmsg('充值金额最低为' . $payment_data['min_money'], 0, '', true, ['msgCode' => 'L0162', 'params' => ["{$payment_data['min_money']}"]]);
        }
        if ($payment_data['max_money'] < $money) {
            ajaxmsg('充值金额最高为' . $payment_data['max_money'], 0, '', true, ['msgCode' => 'L0163', 'params' => ["{$payment_data['max_money']}"]]);
        }
        $transfer = $data['transfer']; // transfer 线下转账支付
//    		if($transfer == 'transfer'){
//    		    $card_id=$data['cardno'];
//    			$line_bank = '卡号：'.$card_id.'；时间：'.date('Y-m-d',time());
//    		}
        $type = $data['type'];
        $payment_id = $data['payment_id'];
        $num = isset($data['num']) ?$data['num']: $money;
        $txid = isset($data['txid']) ?$data['txid']: '';
        $address = isset($data['address']) ?$data['address']: '';
        $currency = isset($data['currency']) ?$data['currency']: '';
        $is_auto = $payment_data['is_auto'];
        $transfer_username = isset($data['transfer_username']) ?$data['transfer_username']: '';
        $transfer_number = isset($data['transfer_number']) ?$data['transfer_number']:'';
        //
        $id = ChargeModel::saveDataCurrency($money * 100, MID, $type, $payment_id, $num, $txid, $address, $currency, $is_auto, $transfer_username, $transfer_number);
        if ($transfer == 'transfer') {
            $var = Db::name('member')->where('id', MID)->value('mobile');
            $contentarr = getconfigSms_status(['name' => 'stock_offline']);
            $content = str_replace(array("#var#", "#amount#"), array($var, $money), $contentarr['value']);
            if ($contentarr['status'] == 1) {
                sendsms_mandao('', $content, '');
            }
            ajaxmsg('充值已提交，请耐心等待审核', 1, ['id' => $id], true, ['msgCode' => 'L0161']);
        } else {
            ajaxmsg('走接口才能完成', 0);
        }
    }

    public function info()
    {
        $post = input();
        $id = $post['id'];
        if (empty($id) || !is_numeric($id)) {
            ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
        }
        $map = [
          'r.id' => $id,
          'r.mid' => MID
        ];
        $field = 'r.num,r.transfer_number,p.type as payment_type,p.name as payment_name,p.account_information,p.payee,p.bank_name,p.card_number,r.money,p.exchange_rate,r.status,r.fee,r.create_time,r.txid,r.address,r.remark';
        $R = Db::name('money_recharge')->alias('r')
          ->join('money_payment p', 'r.payment_id = p.id', 'LEFT')
          ->where($map)
          ->field($field)
          ->find();
        if ($R) {
            $R['money'] = bcdiv($R['money'], 100, 2);
            $R['fee'] = $R['fee'] > 0 ? bcdiv($R['fee'], 100, 2) : 0;
            $R['create_time'] = date('Y-m-d H:i:s', $R['create_time']);
        }
        ajaxmsg('获取成功', 1, $R, true, ['msgCode' => 'L0014']);
    }

    /**
     *Desc: 充值记录
     * @return
     *author:oszpac
     *date:2023-06-01
     */
    public function rechargeRecord()
    {
        $status = input('status', 0);
        if (!in_array($status, ['0', '1', '2'])) {
            ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
        }
        $filter = [
          'r.mid' => MID,
          'r.status' => $status
        ];
        $page = input("page");
        $page = $page ? intval($page) : 1;
        $ret = Db::name('money_recharge')
          ->alias('r')
          ->join('money_payment p', 'r.payment_id = p.id', 'LEFT')
          ->field('r.id,p.name,p.type,r.money,r.create_time,r.status')
          ->where($filter)
          ->order('r.id DESC')
          ->paginate(10, false, ['page' => $page]);
        $data = [];
        $list = [];
        foreach ($ret as $v) {
            $v['money'] = bcdiv($v['money'], 100, 2);
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            $list[] = $v;
        }
        $data['list'] = $list;
        $data['more'] = $ret->currentPage() < $ret->lastPage() ? 1 : 0;
        ajaxmsg('获取成功', 1, $data, true, ['msgCode' => 'L0014']);
    }
}