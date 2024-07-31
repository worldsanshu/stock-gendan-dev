<?php

namespace app\money\home;

use think\Db;

class Jpay extends Common
{
    public function initialize()
    {
        parent::initialize();
    }

    public function doPay()
    {
        $payAmount = (float)trim(input("money"));
        if ($payAmount <= 0) {
            $this->error('充值金额不正确');
        }
        $mid = $_POST['mid'];
        if (empty($mid)) {
            $this->error("请先登录");
        }
        //商户用户唯一编号
        $user_id = $mid;
        //商户订单号
        $no_order = $user_id . get_total_millisecond() . rand(100, 999);
        //支付方式
        $pay_type = input("pay_type");  //1.支付宝  2.微信 3.银联
        $user = Db::name('member')->where('id', $mid)->find();
        //添加数据库记录
        $this->addMemberRecharge($user_id, $payAmount, $no_order, $pay_type);
        //支付类型
        $busi_partner = "101001"; //必填 虚拟商品：101001 实物商品：109001 账户充
        //商户网站订单系统中唯一订单号，必填
        //付款金额
        $money_order = $payAmount;
        //必填
        //商品名称
        $name_goods = "点买股票";
        //订单地址
        $url_order = $_POST['url_order'];
        //订单描述
        $info_order = "";
        //银行网银编码
        $bank_code = "";
        //卡号
        $card_no = "";
        //姓名
        $acct_name = "";
        //身份证号
        $id_no = "";
        //协议号
        $no_agree = "";
        //修改标记
        $flag_modify = 1;
        $mobile = $user['mobile'];
        $createTime = $user['createTime'];
        $createTime = date("YmdHis", strtotime($createTime));
        //风险控制参数
        $risk_item = '{"user_info_bind_phone":"' . $mobile . '","user_info_dt_register":"' . $createTime . '","risk_state":"1","frms_ware_category":"1009"}';
        //分账信息数据
        $shareing_data = "";
        //返回修改信息地址
        $back_url = "";
        //订单有效期
        $valid_order = "";
        /************************************************************/
        //构造要请求的参数数组，无需改动
        $parameter = array(
          "user_id" => $user_id,
          "timestamp" => date('YmdHis', time()),
          "busi_partner" => $busi_partner,
          "no_order" => $no_order,
          "dt_order" => date('YmdHis', time()),
          "name_goods" => $name_goods,
          "info_order" => $info_order,
          "money_order" => $money_order,
          "url_order" => $url_order,
          "bank_code" => $bank_code,
          "pay_type" => $pay_type,
          "no_agree" => $no_agree,
          "shareing_data" => $shareing_data,
          "risk_item" => $risk_item,
          "id_no" => $id_no,
          "acct_name" => $acct_name,
          "flag_modify" => $flag_modify,
          "card_no" => $card_no,
          "back_url" => $back_url
        );
        if ($pay_type == 1 || $pay_type == 2) { //支付宝 微信
            //建立简付支付请求
            require_once(dirname(__FILE__) . "/../../jpay/gateway.php");
        }
    }

    //简付回调
    public function notify_url()
    {
        //签名算法库
        require_once(dirname(__FILE__) . "/../../jpay/sign.php");
        //商户名称
        $account_name = $_POST['account_name'];
        //支付时间戳
        $pay_time = $_POST['pay_time'];
        //支付状态
        $status = $_POST['status'];
        //支付金额
        $amount = $_POST['amount'];
        //支付时提交的订单信息
        $out_trade_no = $_POST['out_trade_no'];
        //平台订单交易流水号
        $trade_no = $_POST['trade_no'];
        //该笔交易手续费用
        $fees = $_POST['fees'];
        //签名算法
        $sign = $_POST['sign'];
        //回调时间戳
        $callback_time = $_POST['callback_time'];
        //支付类型
        $type = $_POST['type'];
        //商户KEY（S_KEY）key值得传回不会有任何安全隐患
        $account_key = $_POST['account_key'];
        //第一步，检测商户KEY是否一致  587DA0A4149F40
        if ($account_key != '09999B5945E553') exit('error:key');
        //第二步，验证签名是否一致
        if (sign('09999B5945E553', ['amount' => $amount, 'out_trade_no' => $out_trade_no]) != $sign) exit('error:sign');
        if ($status == "success") { //验证成功
            $this->updatePayStatus($out_trade_no, 1);
            echo 'success';
        } else {
            die("{'ret_code':'9999','ret_msg':'验签失败'}");
        }
        //file_put_contents("666666.txt", json_encode($_POST));
    }

    //no_order 支付时传入的订单号；status: 1成功  2失败
    public function updatePayStatus($no_order, $status)
    {
        if (strlen($no_order) <= 0 || ($status != 1 && $status != 2)) {
            $this->error("更新失败");
        }
        $rech = Db::table("lmq_money_recharge")->where("order_no=$no_order")->find();
        if (!$rech) {
            $this->error("订单号不存在");
        }
        //用户id
        $memberId = $rech['mid'];
        //充值金额（分）
        $amount = $rech['money'];
        $amount1 = $amount / 100;
        if ($rech['type'] == 1) {
            $type = '支付宝';
        }
        if ($rech['type'] == 2) {
            $type = '微信';
        }
        $member = Db::table("lmq_member")->where("id=$memberId")->find();
        if (!$member) {
            $this->error("用户不存在");
        }
        //更新订单状态
        $ret = Db::table("lmq_money_recharge")->where("order_no=$no_order")->update(['status' => 1]);
        //更新用户总净额
        //if($status == 1){
        $money = Db::table("lmq_money")->where("mid=$memberId")->find();
        $moneys = $amount + $money['account'];
        Db::table("lmq_money")->where("mid=$memberId")->update(['account' => $moneys]);
        $money = Db::table("lmq_money")->where("mid=$memberId")->find();
        $data = array();
        $data['mid'] = $memberId;
        $data['affect'] = $amount;
        $data['account'] = $money['account'];
        $data['type'] = 1;
        $data['info'] = '订单号：' . $no_order . $type . '支付充值' . $amount1 . '成功';
        $data['create_time'] = time();
        $data['create_ip'] = 0;
        Db::table("lmq_money_record")->insertGetId($data);
        //}
        // Db::commit();
    }

    public function addMemberRecharge($memberId, $payAmount, $orderNO, $pay_type)
    {
        /*(if(!is_int($memberId) || $memberId <= 0 || !is_numeric($payAmount) || $payAmount <= 0 || strlen($orderNO) < 5){
            error("rechare 参数错误");
        }*/
        $data = array();
        $data['mid'] = $memberId;
        $data['money'] = $payAmount * 100;
        $data['status'] = 0;
        $data['order_no'] = $orderNO;
        $data['create_ip'] = 0;
        $data['type'] = $pay_type;
        $data['create_time'] = time();
        Db::table("lmq_money_recharge")->insertGetId($data);
    }
}