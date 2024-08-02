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
use app\member\model\Bank as BankModel;
use app\member\model\Member as MemberModel;
use app\money\model\Money as Money;
use app\money\model\Withdraw as WithdrawModel;
use think\Db;
use think\helper\Hash;

/**
 * 前台首页控制器
 * @package app\money\home
 */
class Withdraw extends Common
{
    /**
     * 首页
     * @return [type] [description]
     */
    public function index()
    {
        $money = Money::getMoney(MID);
        $money['account'] = bcdiv($money['account'], 100, 2);
        if ($money['account'] < 0) {
            $money['account'] = 0;
        }
        $money['operate_account'] = bcdiv($money['operate_account'], 100, 2);
        $money['operate_account'] = bcdiv($money['operate_account'], 100, 2);
        $banks = BankModel::getBank(MID);
        //获取用户数字货币钱包
        $wallets = BankModel::getwallet(MID);
        if (empty($banks) && empty($wallets)) ajaxmsg('您未添加提现账户，请先添加提现账户', 0, '', true, ['msgCode' => 'L0160']);
        $list = [];
        foreach ($banks as $b) {
            $new_b = [];
            $new_b['type'] = 0;
            $new_b['id'] = $b['id'];
            $new_b['bank'] = $b['bank'];
            $new_b['card'] = $b['card'];
            $list[] = $new_b;
        }
        $wallets_list = [];
        foreach ($wallets as $w) {
            $new_w = [];
            $new_w['id'] = $w['id'];
            $new_w['bank'] = $w['name'];
            $new_w['card'] = $w['address'];
            $new_w['type'] = 1;
            $new_w['payment_code'] = $w['payment_code']?get_img_url($w['payment_code']):'';
            $wallets_list[] = $new_w;
        }
        $data['money'] = $money;
        $data['banks'] = $list;
        $data['wallets'] = $wallets_list;
        $data['default_bank'] = isset($list[0])?$list[0]:[];
//        $data['bankSetting'] = config("web_bank");
        ajaxmsg('线下提现信息', 1, $data, true, ['msgCode' => 'L0002']);
    }

    /*
     * 操作提现操作
     */
    public function doWithdraw()
    {
        $data = input();
        $data['mid'] = MID;
        $agent_far = MemberModel::where('id', MID)->value('agent_far');
        $data['for_user'] = $agent_far ?: 0;
        $result = $this->validate($data, "Withdraw.create");
        if (true !== $result) {
            ajaxmsg($result, 0);
        }
        $c = Db::name('member')->where(["id" => MID])->find();
        if (Hash::check((string)$data['paywd'], $c['paywd'])) {
//            $res = WithdrawModel::saveData($data);
        } else {
            ajaxmsg('支付密码错误', 0, '', true, ['msgCode' => 'L0008']);
        }
        if ($data['money'] <= 0) {
            ajaxmsg('提现金额错误！', 0, '', true, ['msgCode' => 'L0003']);
        }

        $result = Db::name('money_withdraw_config')->where("id", 1)->find();
        if($result){
            if ($data['money'] < $result['min_money']) {
                ajaxmsg('提现金额必须大于'.$result['min_money'].'元', 0, '', true);
            }
        }else{
            if ($data['money'] < 100) {
                ajaxmsg('提现金额必须大于100元', 0, '', true, ['msgCode' => 'L0004']);
            }
        }

        $money_res = Db::name('money')->where(['mid' => MID])->find();
        if (empty($money_res['account'])) {
            ajaxmsg('查询账户资金出错！', 0, '', true, ['msgCode' => 'L0005']);
        }
        if (isset($money_res['account']) && $money_res['account'] < $data['money']) {
            ajaxmsg('提现金额已经大于可用余额！', 0, '', true, ['msgCode' => 'L0006']);
        }
        $withdraw_info = Db::name('money_withdraw')
          ->where(['mid' => MID])
          ->where(['status' => 0])
          ->find();
        if (!empty($withdraw_info)) {
            ajaxmsg('您已有提现申请，请耐心等待审核。', 0, '', true, ['msgCode' => 'L0007']);
        }
        $res = WithdrawModel::saveData($data);
        if ($res['status']) {
            ajaxmsg('提现申请已提交，请耐心等待审核', 1, '', true, ['msgCode' => 'L0009']);
        } else {
            ajaxmsg('提现申请提交失败', 0, '', true, ['msgCode' => 'L0010']);
        }
    }

  /**
   * Desc : 提现记录
   * User : you name
   * Date : 2024-06-22 20:58
   */
  public function withdrawRecord()
  {
    $status = input('status', 0);

    $filter['r.mid'] =MID;
    if($status){
      if (!in_array($status, ['0', '1', '2', '3'])) {
        ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
      }
      $filter['r.status'] =$status;
    }


    $page = input("page");
    $page = $page ? intval($page) : 1;
    $ret = Db::name('money_withdraw')
      ->alias('r')
      ->where($filter)
      ->order('r.id DESC')
      ->paginate(10, false, ['page' => $page]);
    $data = [];
    $list = [];
    foreach ($ret as $v) {
      $v['money'] = bcdiv($v['money'], 100, 2);
      $v['real_money'] = bcdiv($v['real_money'], 100, 2);
      $v['fee'] = bcdiv($v['fee'], 100, 2);
      $v['bank_text'] =$v['bank'];
      $v['bank'] = explode('|',$v['bank']);

      $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
      $list[] = $v;
    }
    $data['list'] = $list;
    $data['more'] = $ret->currentPage() < $ret->lastPage() ? 1 : 0;
    ajaxmsg('获取成功', 1, $data, true, ['msgCode' => 'L0014']);
  }


    /**
     * Desc : 提现详情
     * User : you name
     * Date : 2024-06-22 20:58
     */
    public function withdrawRecordDetail()
    {

        $withdraw_id = input("withdraw_id");
        $filter['r.mid'] =MID;
        $filter['r.id'] =$withdraw_id;

        $ret = Db::name('money_withdraw')
            ->alias('r')
            ->where($filter)->find();
        $ret['money'] = bcdiv($ret['money'], 100, 2);
        $ret['real_money'] = bcdiv($ret['real_money'], 100, 2);
        $ret['fee'] = bcdiv($ret['fee'], 100, 2);
        $ret['bank_text'] =$ret['bank'];
        $ret['bank'] = explode('|',$ret['bank']);
        $ret['create_time'] = date('Y-m-d H:i:s', $ret['create_time']);
        ajaxmsg('获取成功', 1, $ret, true, ['msgCode' => 'L0014']);
    }

}