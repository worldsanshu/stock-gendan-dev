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

use app\member\model\Member as MemberModel;
use app\stock\model\Account as AccountModel;
use app\stock\model\Borrow as BorrowModel;
use app\stock\model\Subaccount as SubaccountModel;
use app\stock\model\SubaccountMoney as SubaccountMoneyModel;
use app\stock\model\SubaccountRisk as SubaccountRiskModel;
use think\Db;
use think\facade\Hook;
use think\facade\Log;

/**
 * 配资处理类
 */
class Handle extends Common //Home
{
    protected function initialize()
    {
        parent::initialize();
        $mid = MID;
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
    }

    /**
     * 申请配资提交
     * @param int $type 类型
     * @param int $multiple 倍数
     * @param int $deposit_money 保证金
     * @param int $borrow_duration 资金使用时间
     * @param int $trading_time 交易时间 0 今日 1 下个交易日
     * @return string
     * @author 张继立 <404851763@qq.com>
     */
    public function applySave()
    {
        $mid = MID;
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        $req = request();
        $type = intval(input('type'));
        $multiple = intval(input('multiple'));
        $deposit_money = intval(input('deposit_money'));
        if ($type == '4') {
            $deposit_money = 1;
        }
        $borrow_duration = intval(input('borrow_duration'));
        $trading_time = intval(input('trading_time'));
        $money_range = explode('|', config('money_range'));
        $orderinfo = Db::name('money')->where(['mid' => $mid])->find();
        if ($orderinfo['account'] / 100 < $deposit_money && $type != 4) {
            ajaxmsg('您账户余额不足请先充值', 0, '', true, ['msgCode' => 'L0080']);
        }
        $money_min = $money_range[0];//最低配资金额
        $money_max = $money_range[1]; //最高配资金额
        $money_step = $money_range[2]; //配资金额递增幅度
        if ($deposit_money % $money_step != 0 && $type != 4) {
            ajaxmsg('您要配资的金额必须是' . $money_step . '的整数倍', 0, '', true, ['msgCode' => 'L0081', 'params' => ["{$money_step}"]]);
        }
        if ($deposit_money < intval($money_min) && $type != 4) {
            ajaxmsg('配资金额不能低于' . $money_min . '元', 0, '', true, ['msgCode' => 'L0082', 'params' => ["{$money_min}"]]);
        }
        $db = new BorrowModel;
        if ($type != 4) {
            $onecheck = $db->onecheck($mid);
            if (!empty($onecheck)) {
                ajaxmsg('您已有配资申请，请耐心等候审核', 0, '', true, ['msgCode' => 'L0083']);
            }
        }
        //配资类型  1:按天配资 2:按周配资 3:按月配资 4:免费体验 5:免息配资
        switch ($type) {
            case 1:
                $config = day_config();
                $data['multiple'] = intval($multiple); // 倍率
                if (isset($config['day_rate'][$data['multiple']])) {//不在设置的倍率范围内
                    $data['rate'] = $config['day_rate'][$data['multiple']];
                } else {
                    ajaxmsg('参数出错，请联系管理员', 0, '', true, ['msgCode' => 'L0084']);
                }
                $data['total'] = 1;
                $data['loss_warn'] = $config['day_loss'][0]; // 预警线
                $data['loss_close'] = $config['day_loss'][1];// 止损线
                $mmax = count($config['day_position']);
                $data['position'] = isset($config['day_position'][$data['multiple']]) ? $config['day_position'][$data['multiple']] : $config['day_position'][$mmax];
                break;
            case 2:
                $config = week_config();
                $data['multiple'] = $multiple; // 倍率
                if (isset($config['week_rate'][$data['multiple']])) {//不在设置的倍率范围内
                    $data['rate'] = $config['week_rate'][$data['multiple']];
                } else {
                    ajaxmsg('参数出错，请联系管理员', 0, '', true, ['msgCode' => 'L0084']);
                }
                $data['total'] = 1;
                $data['loss_warn'] = $config['week_loss'][0]; // 预警线
                $data['loss_close'] = $config['week_loss'][1];// 止损线
                $mmax = count($config['week_position']);
                $data['position'] = isset($config['week_position'][$data['multiple']]) ? $config['week_position'][$data['multiple']] : $config['week_position'][$mmax];
                break;
            case 3:
                $config = month_config();
                $data['multiple'] = intval($multiple); // 倍率
                if (isset($config['month_rate'][$data['multiple']])) {//不在设置的倍率范围内
                    $data['rate'] = $config['month_rate'][$data['multiple']];
                } else {
                    ajaxmsg('参数出错，请联系管理员', 0, '', true, ['msgCode' => 'L0084']);
                }
                $data['total'] = intval($borrow_duration);
                $data['loss_warn'] = $config['month_loss'][0]; // 预警线
                $data['loss_close'] = $config['month_loss'][1];// 止损线
                $mmax = count($config['month_position']);
                $data['position'] = isset($config['month_position'][$data['multiple']]) ? $config['month_position'][$data['multiple']] : $config['month_position'][$mmax];
                break;
            case 4:
                $check = $db->trycheck($mid, 4);
                if (!empty($check)) {
                    ajaxmsg('您只能试用一次', 0, '', true, ['msgCode' => 'L0085']);
                }
                $config = free_config();
                $data['loss_warn'] = $config['free_loss'][0]; // 预警线 这里借用了免费配资的避免为空 其实无意义
                $data['loss_close'] = $config['free_loss'][1];// 止损线 这里借用了免费配资的避免为空 其实无意义
                $data['position'] = $config['day_position'][1];
                //$deposit_money=1;
                $deposit_money = 1;//by
                $borrow_duration = 2;
                $data['rate'] = 0;
                $data['multiple'] = 1; // 倍率
                $data['total'] = 1;
                //$data['borrow_money']=200000;
                $data['borrow_money'] = 1099900;//by
                break;
            case 5:
                $config = free_config();
                $multiple = $config['free_set'][0];
                $borrow_duration = $config['free_set'][1];
                $data['rate'] = 0;
                $data['multiple'] = intval($multiple); // 倍率
                $data['total'] = 1;
                $data['loss_warn'] = $config['free_loss'][0]; // 预警线
                $data['loss_close'] = $config['free_loss'][1];// 止损线
                $mmax = count($config['day_position']);
                $data['position'] = isset($config['day_position'][$data['multiple']]) ? $config['day_position'][$data['multiple']] : $config['day_position'][$mmax];
                break;
            default:
                ajaxmsg('类型出错，请重新输入！', 0, '', true, ['msgCode' => 'L0086']);
        }
        $data['deposit_money'] = $deposit_money;
        if ($type != 4) {
            $data['borrow_money'] = $data['deposit_money'] * $data['multiple']; // 配资金额
        }
        $data['init_money'] = $data['borrow_money'] + $data['deposit_money'];// 初始资金金额
        $money = bcdiv($data['init_money'], 100, 2);
        if ($money > intval($money_max)) {
            ajaxmsg('最多只能配资' . $money_max . '元', 0, '', true, ['msgCode' => 'L0087', 'params' => ["{$money_max}"]]);
        }
        $data['order_id'] = generate_rand_str(15, 3);
        $data['trading_time'] = intval($trading_time);
        $data['type'] = $type;
        $data['member_id'] = $mid;
        $data['borrow_duration'] = $borrow_duration; // 操盘期限
        $type == 3 && $data['total'] = $data['borrow_duration'];
        $data['sort_order'] = 1;
        //$result = $db->createStock($data);
        ajaxmsg('操作成功', 1, $data, true, ['msgCode' => 'L0119']);
    }

    /**
     * 申请配资提交
     * @param int $type 类型
     * @param int $multiple 倍数
     * @param int $deposit_money 保证金
     * @param int $borrow_duration 资金使用时间
     * @param int $trading_time 交易时间 0 今日 1 下个交易日
     * @return string
     * @author 张继立 <404851763@qq.com>
     */
    public function applySaveSub()
    {
        $mid = MID;
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        $req = request();
        $type = intval(input('type'));
        $multiple = intval(input('multiple'));
        $deposit_money = intval(input('deposit_money'));
        if ($type == '4') {
            $deposit_money = 1;
        }
        $borrow_duration = intval(input('borrow_duration'));
        $trading_time = intval(input('trading_time'));
        $money_range = explode('|', config('money_range'));
        $orderinfo = Db::name('money')->where(['mid' => $mid])->find();
        if ($orderinfo['account'] / 100 < $deposit_money && $type != 4) {
            ajaxmsg('您账户余额不足请先充值', 0, '', true, ['msgCode' => 'L0080']);
        }
        $money_min = $money_range[0];//最低配资金额
        $money_max = $money_range[1]; //最高配资金额
        $money_step = $money_range[2]; //配资金额递增幅度
        if ($deposit_money % $money_step != 0 && $type != 4) {
            ajaxmsg('您要配资的金额必须是' . $money_step . '的整数倍', 0);
        }
        if ($deposit_money < intval($money_min) && $type != 4) {
            if ($type != 4) ajaxmsg('配资金额不能低于' . $money_min . '元', 0, '', true, ['msgCode' => 'L0082', 'params' => ["{$money_min}"]]);
        }
        $db = new BorrowModel;
        if ($type != 4) {
            $onecheck = $db->onecheck($mid);
            if (!empty($onecheck)) {
                if (!env('APP_DEBUG')) {
                    ajaxmsg('您已有配资申请，请耐心等候审核', 0, '', true, ['msgCode' => 'L0083']);
                }
            }
        }
        //配资类型  1:按天配资 2:按周配资 3:按月配资 4:免费体验 5:免息配资
        switch ($type) {
            case 1:
                $config = day_config();
                $data['multiple'] = intval($multiple); // 倍率
                if (isset($config['day_rate'][$data['multiple']])) {//不在设置的倍率范围内
                    $data['rate'] = $config['day_rate'][$data['multiple']];
                } else {
                    ajaxmsg('参数出错，请联系管理员', 0, '', true, ['msgCode' => 'L0084']);
                }
                $data['total'] = 1;
                $data['loss_warn'] = $config['day_loss'][0]; // 预警线
                $data['loss_close'] = $config['day_loss'][1];// 止损线
                $mmax = count($config['day_position']);
                $data['position'] = isset($config['day_position'][$data['multiple']]) ? $config['day_position'][$data['multiple']] : $config['day_position'][$mmax];
                break;
            case 2:
                $config = week_config();
                $data['multiple'] = $multiple; // 倍率
                if (isset($config['week_rate'][$data['multiple']])) {//不在设置的倍率范围内
                    $data['rate'] = $config['week_rate'][$data['multiple']];
                } else {
                    ajaxmsg('参数出错，请联系管理员', 0, '', true, ['msgCode' => 'L0084']);
                }
                $data['total'] = 1;
                $data['loss_warn'] = $config['week_loss'][0]; // 预警线
                $data['loss_close'] = $config['week_loss'][1];// 止损线
                $mmax = count($config['week_position']);
                $data['position'] = isset($config['week_position'][$data['multiple']]) ? $config['week_position'][$data['multiple']] : $config['week_position'][$mmax];
                break;
            case 3:
                $config = month_config();
                $data['multiple'] = intval($multiple); // 倍率
                if (isset($config['month_rate'][$data['multiple']])) {//不在设置的倍率范围内
                    $data['rate'] = $config['month_rate'][$data['multiple']];
                } else {
                    ajaxmsg('参数出错，请联系管理员', 0, '', true, ['msgCode' => 'L0084']);
                }
                $data['total'] = intval($borrow_duration);
                $data['loss_warn'] = $config['month_loss'][0]; // 预警线
                $data['loss_close'] = $config['month_loss'][1];// 止损线
                $mmax = count($config['month_position']);
                $data['position'] = isset($config['month_position'][$data['multiple']]) ? $config['month_position'][$data['multiple']] : $config['month_position'][$mmax];
                break;
            case 4:
                $check = $db->trycheck($mid, 4);
                if (!empty($check)) {
                    ajaxmsg('您只能试用一次', 0, '', true, ['msgCode' => 'L0085']);
                }
                $config = free_config();
                $data['loss_warn'] = $config['free_loss'][0]; // 预警线 这里借用了免费配资的避免为空 其实无意义
                $data['loss_close'] = $config['free_loss'][1];// 止损线 这里借用了免费配资的避免为空 其实无意义
                $data['position'] = $config['day_position'][1];
                //$deposit_money=1;
                $deposit_money = 1;//by
                $borrow_duration = 3;
                $data['rate'] = 0;
                $data['multiple'] = 1; // 倍率
                $data['total'] = 1;
                //$data['borrow_money']=200000;
                $data['borrow_money'] = 1099900;//by
                break;
            case 5:
                $config = free_config();
                $multiple = $config['free_set'][0];
                $borrow_duration = $config['free_set'][1];
                $data['rate'] = 0;
                $data['multiple'] = intval($multiple); // 倍率
                $data['total'] = 1;
                $data['loss_warn'] = $config['free_loss'][0]; // 预警线
                $data['loss_close'] = $config['free_loss'][1];// 止损线
                $mmax = count($config['day_position']);
                $data['position'] = isset($config['day_position'][$data['multiple']]) ? $config['day_position'][$data['multiple']] : $config['day_position'][$mmax];
                break;
            default:
                ajaxmsg('类型出错，请重新输入！', 0, '', true, ['msgCode' => 'L0086']);
        }
        $data['deposit_money'] = bcmul($deposit_money, 100, 2);
        if ($type != 4) {
            $data['borrow_money'] = $data['deposit_money'] * $data['multiple']; // 配资金额
        }
        $data['init_money'] = $data['borrow_money'] + $data['deposit_money'];// 初始资金金额
        $money = bcdiv($data['init_money'], 100, 2);
        if ($money > intval($money_max)) {
            ajaxmsg('最多只能配资' . $money_max . '元', 0, '', true, ['msgCode' => 'L0087', 'params' => ["{$money_max}"]]);
        }
        $data['order_id'] = $sn = generate_rand_str(15, 3);
        $data['trading_time'] = intval($trading_time);
        $data['type'] = $type;
        $data['member_id'] = $mid;
        $data['borrow_duration'] = $borrow_duration; // 操盘期限
        $type == 3 && $data['total'] = $data['borrow_duration'];
        $data['sort_order'] = 1;
        $result = $db->createStock($data);
        $orderinfo = Db::name('stock_borrow')->where(['order_id' => $sn])->find();
        $id = $orderinfo['id'];
        if ($result['status'] != '0') {
            $memberInfo = MemberModel::getMemberInfoByID($data['member_id']);
            /*  $adminmsg='用户{$var}申请了配资';
              $params  = $memberInfo['mobile'];
              send_sms_member($adminmsg,$params);*/
            $var = $memberInfo['mobile'];
            //$content = \think\Config::get('sms_template')['stock_handle_applySave'];
            $contentarr = getconfigSms_status(['name' => 'stock_handle_applySave']);
            $content = str_replace(array("#var#"), array($var), $contentarr['value']);
            //自动审核
            $automatic = $this->automatic($id);
            # $automatic = 1;
            //        print_r($automatic);
            if ($contentarr['status'] == 1 && $automatic) {
                sendsms_mandao('', $content, '');
            }
            ajaxmsg($result['msg'], 1, '', true, ['msgCode' => 'L0120']);
        } else {
            //自动审核
            # $automatic = $this->automatic($id);
            ajaxmsg($result['msg'], 0);
        }
    }

    /**
     * 新增子账户
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function addsubaccounts()
    {
        // 保存数据
        $count = Db::name('stock_subaccount')->count();
        $subname = 60753101 + $count;
        $data = array();
        $data['sub_pwd'] = 123456;
        $data['sub_account'] = $subname;
        $data['role_id'] = 1;
        $data['agent_id'] = 1;
        $data['info'] = "系统自动生成";
        $data['account_type'] = 2;  //暂时是写死的应该是需要通过后台获取
        $data['account_id'] = 2;//暂时是写死的应该是需要通过后台获取
        $uid = MID;
        $for_user = Db::name('member')->where('id', $uid)->value('agent_far');
        $data['for_user'] = $for_user;
        //获取证券账户信息
        $commission = AccountModel::getAccountByID($data['account_id']);
        if ($commission['broker'] == -1) {//如果选择的券商类型为-1
            $data['relation_type'] = 0;//选择的账户模式为模拟账户
        } else {
            $data['relation_type'] = 1;//选择的账户模式为实盘账户
        }
        // 验证
        $result = $this->validate($data, 'Subaccount.insert');
        // 验证失败 输出错误信息
        if (true !== $result) $this->error($result);
        //业务逻辑处理
        $subaccount = SubaccountModel::create($data);
        Db::startTrans();
        $resu = $subaccount->subaccountMoney()->save($data);//关联更新子账户资金表
        /////   更新子账户资金表 ///////////
        $Id = Db::name('stock_subaccount_money')->getLastInsID();
        $money = SubaccountMoneyModel::get($Id);
        $money['commission_scale'] = $commission['commission_scale'];//券商的佣金比例
        $money['profit_share_scale'] = 0;// 平台盈利分成
        $money['rate_scale'] = 0;//管理费分成比例
        $money['min_commission'] = $commission['min_commission'];//最低佣金 config('limit_fee');
        $res = $money->save($money);
        $result2 = $subaccount->subaccountRisk()->save($data);//关联更新子账户风控表
        $res2 = false;
        if ($result2) {
            $RiskId = Db::name('stock_subaccount_risk')->getLastInsID();
            $Risk = SubaccountRiskModel::get($RiskId);
            $Risk['loss_warn'] = 50;
            $Risk['loss_close'] = 20;
            $Risk['position'] = 100;
            $Risk['prohibit_open'] = 1;
            $Risk['prohibit_close'] = 1;
            $Risk['prohibit_back'] = 1;
            $Risk['renewal'] = 1;
            $Risk['autoclose'] = 1;
            $res2 = $Risk->save($Risk);
        }
        ////////////
        Hook::listen('subaccount_add', $subaccount);
        // 记录行为
        $details = '新增子账户,子帐户名为：' . $subaccount['sub_account'];
        $uid = 1;
        action_log('subaccount_add', 'stock_subaccount', $subaccount['id'], $uid, $details);
        if ($resu && $res && $res2) {
            Db::commit();
            return array($subaccount['sub_account'], $RiskId);
        } else {
            Db::rollback();
            return false;
        }
    }

    /**
     * 自动审核
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function automatic($id)
    {
        $addsubaccounts = $this->addsubaccounts();
        $info = BorrowModel::getEditBorrow($id);//获取配资信息
        $risk = Db::name('stock_subaccount_risk')->where(['stock_subaccount_id' => $addsubaccounts[1]])->find();
        //$account=Db::name('stock_account')->where([''=>])->find();
        $money = Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $addsubaccounts[1]])->find();
        if (!empty($money)) {
            $money['min_commission'] = money_convert($money['min_commission']);
        }
        $sub_arr = array_merge($risk, $money);
        $data = array();
        $data['profit_share_scale'] = $sub_arr['profit_share_scale'];
        $data['rate_scale'] = $sub_arr['rate_scale'];
        $data['min_commission'] = $sub_arr['min_commission'];
        $data['commission_scale'] = $sub_arr['commission_scale'];
        $data['autoclose'] = $sub_arr['autoclose'];
        $data['renewal'] = $sub_arr['renewal'];
        $data['prohibit_close'] = $sub_arr['prohibit_close'];
        $data['prohibit_open'] = $sub_arr['prohibit_open'];
        $data['position'] = $sub_arr['position'];
        $data['loss_close'] = $sub_arr['loss_close'];
        $data['loss_warn'] = $sub_arr['loss_warn'];
        $data['borrow_money'] = $info['borrow_money'];
        $data['borrow_interest'] = $info['borrow_interest'];
        $data['deposit_money'] = $info['deposit_money'];
        $data['init_money'] = $info['init_money'];
        $data['contents'] = "系统自动审核";
        $data['stock_subaccount_id_r'] = $addsubaccounts[0];
        $data['stock_subaccount_id'] = $addsubaccounts[1];
        $data['v_status'] = 1;
        $data['type'] = $info['type'];
        $data['borrow_duration'] = $info['borrow_duration'];
        $data['member_id'] = MID;
        $data['id'] = $id;
        if ($data['type'] == "模拟操盘" && $data['trade_type'] == 1) {
            $this->error("模拟操盘配资不能选择实盘子账户！");
        }
        $data['status'] = $data['v_status'];
        if ($data['status'] == 1) {
            //$data['stock_subaccount_id'] = !$data['trade_type'] ? $data['stock_subaccount_id_v'] :$data['stock_subaccount_id_r'];
            //  $data['stock_subaccount_id'] = $data['stock_subaccount_id_r'];
            $result = $this->validate($data, 'Borrow.audit');
            if (true !== $result) {
                return false;
            }
        }
        $BorrowModel = new BorrowModel();
        //临时测试 审核不通过也分配佣金
        $result = $BorrowModel->saveBorrow($data);
        //file_put_contents(ROOT_PATH . '/public/log/zidongshenhe-' . date("y-m-d") . '.txt', var_export($result, true), FILE_APPEND);
        Log::debug('zidongshenhe-'.var_export($result, true));
        $member = MemberModel::getMemberInfoByID($data['member_id']);
        $mobile = $member['mobile'];
        $infos = BorrowModel::get($data['id']); //短信用到的信息
        if ($result['status'] == 1) {
            //根据佣金比例分配佣金 用户id 配资id 配资管理费
            if ($data['borrow_interest']) {
                $res_agent = $BorrowModel->agentToRateMoney($data['member_id'], $data['id'], $data['borrow_interest'] / 100);
            }
            //申请配资审核通过-短信通知
            $browid = $data['id'];
            $info = BorrowModel::getEditBorrow($browid);//获取配资信息
            self::borrow_audite_sms($mobile, 'stock_auditing', $infos);
            return true;
        } elseif ($result['status'] == 2) {
            return false;
        } else {
            self::borrow_audite_sms($mobile, 'stock_auditing_fail', $infos);
            return false;
        }
        // 记录行为
        action_log('borrow_edit', 'borrow', $id, UID, $data['id']);
    }

    public static function borrow_audite_sms($mobile, $type, $info)
    {
        $contentarr = getconfigSms_status(['name' => $type]);
        $content = str_replace(array("#var#", "#order_id#"), array($mobile, $info['order_id']), $contentarr['value']);
        if ($contentarr['status'] == 1) {
            sendsms_mandao($mobile, $content, 'user');
        }
    }
}

?>