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
namespace app\stock\model;

use app\market\model\Deal_stock;
use app\market\model\Delivery;
use app\market\model\Grid;
use app\market\model\Position;
use app\market\model\StockSubAccount;
use app\market\model\StockSubAccountRisk;
use app\market\model\SubAccountMoney as SubAccountMoneyModel;
use app\market\model\Trust;
use app\member\model\Member as MemberModel;
use app\member\model\MemberMessage as MemberMessageModel;
use app\money\model\Money;
use app\money\model\Record as RecordModel;
use app\money\model\RecordAgent as RecordAgentModel;
use app\stock\model\Addfinancing as AddfinancingModel;
use app\stock\model\Addmoney as AddmoneyModel;
use app\stock\model\Renewal as RenewalModel;
use app\stock\model\Subaccount as SubaccountModel;
use think\Db;
use think\Exception;
use think\facade\Lang;
use think\Model as ThinkModel;
use util\Logs;

/**
 * 配资管理模型
 * @package app\stock\model
 */
class Borrow extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_BORROW__';
//    static $status_str = [];
    static $status_str = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束', '3' => '已逾期'];

    /**多语言化 配资状态
     * @param $status
     * @return mixed
     */
    public static function formatStatusTxt($status)
    {
        $str = [
          '-1' => Lang::get('B0001'),
          '0' => Lang::get('B0002'),
          '1' => Lang::get('B0003'),
          '2' => Lang::get('B0004'),
          '3' => Lang::get('B0005')
        ];
        return $str[$status];
    }

    protected function setAddIpAttr($value)
    {
        return get_client_ip(1);
    }

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //配资类型获取器
    public function getTypeAttr($value)
    {
//        $status = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘'];
        $status = [
          '5' => Lang::get('B0006'),// '免息配资',
          '1' => Lang::get('B0007'),// '按天配资',
          '2' => Lang::get('B0008'),// '按周配资',
          '3' => Lang::get('B0009'),// '按月配资',
          '4' => Lang::get('B0010'),// '免费体验',
          '6' => Lang::get('B0011'),// '模拟操盘'
        ];
        return $status[$value];
    }

    //配资类型设置器
    /**
     * public function setTypeAttr()
     * {
     * return self::getData('type');
     * }**/
    //关联子账户资金表定义
    public function member()
    {
        return $this->belongsTo('member');
    }

    /*
     * 获取证券类型列表
     * @param array $map 筛选条件
     * @param array $order 排序
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public static function getList($map = [], $order = [], $condition = [])
    {
        $where = [];
        $where['b.status'] = input('status');//待审核
        $where['b.status'] == '' && $where['b.status'] = -1;
        $array = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘'];
        $data_list = self::view('stock_borrow b', true)
          ->view("member", 'mobile,name,email', 'member.id=b.member_id', 'left')
          ->view("stock_subaccount s", 'sub_account', 's.id=b.stock_subaccount_id', 'left')
          ->where($condition)
          ->where($map)
          ->where($where)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $item->deposit_money = money_convert($item->deposit_money);
              $item->init_money = money_convert($item->init_money);
              $item->borrow_money = money_convert($item->borrow_money);
              $item->borrow_interest = money_convert($item->borrow_interest);
              $item->stock_money = money_convert($item->stock_money);
              if ($item->getData('type') === 3) {
                  $unit = '个月';
              } elseif ($item->getData('type') === 2) {
                  $unit = '周';
              } else {
                  $unit = '天';
              }
              $item->borrow_duration .= $unit;
          });
        Logs::log('sql', ['sql' => Db::getLastSql(), 'data_list' => $data_list], 'borrow');
        return $data_list;
    }

    /*
     * 获取证券类型列表
     * @param array $map 筛选条件
     * @param array $order 排序
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public static function getList2($map = [], $order = [], $condition = [])
    {
        $where = [];
        $data_list = self::where($condition)
          ->where($map)
          ->where($where)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $item->deposit_money = money_convert($item->deposit_money);
              $item->init_money = money_convert($item->init_money);
              $item->borrow_money = money_convert($item->borrow_money);
              $item->borrow_interest = money_convert($item->borrow_interest);
              $item->stock_money = money_convert($item->stock_money);
              if ($item->getData('type') === 3) {
                  $unit = '个月';
              } elseif ($item->getData('type') === 2) {
                  $unit = '周';
              } else {
                  $unit = '天';
              }
              $item->borrow_duration .= $unit;
          });
        Logs::log('sql', ['sql' => Db::getLastSql(), 'data_list' => $data_list], 'borrow');
        return $data_list;
    }

    /*
     * 获取到期配资列表
     * @param array $map 筛选条件
     * @param array $order 排序
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public static function getEnd($map = [], $order = [], $condition = [])
    {
        $data_list = self::view('stock_borrow b', true)
          ->view("member", 'mobile,name,email', 'member.id=b.member_id', 'left')
          ->view('stock_subaccount_risk r', 'renewal', 'r.stock_subaccount_id=b.stock_subaccount_id', 'left')
          ->view("stock_subaccount s", 'sub_account', 's.id=b.stock_subaccount_id', 'left')
          ->where($map)
          ->where($condition)
          ->where('b.status', 'in', [1, 3])
          ->where("b.end_time", "<=", time() + 32400)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $item->deposit_money = money_convert($item->deposit_money);
              $item->init_money = money_convert($item->init_money);
              $item->borrow_money = money_convert($item->borrow_money);
              $item->borrow_interest = money_convert($item->borrow_interest);
              $item->stock_money = money_convert($item->stock_money);
              if ($item->getData('type') === 3) {
                  $unit = '个月';
              } elseif ($item->getData('type') === 2) {
                  $unit = '周';
              } else {
                  $unit = '天';
              }
              $item->borrow_duration .= $unit;
          });
        return $data_list;
    }

    /**
     * @param $sub_id 子账户id
     * @param $uid 用户id
     * @param $id 配资id
     * @return bool
     */
    public static function actionEnd($sub_id, $uid, $id)
    {
        $submoney_info = SubaccountMoney::getMoneyByID($sub_id);
        $surplus_money = $submoney_info['avail'] * 100; //返回的可用余额
        $addmoney = $submoney_info['stock_addmoney'] * 100; //累计追加保证金
        $drawprofit = $submoney_info['stock_drawprofit'] * 100; //累计提取盈利
        $renewal = new RenewalModel();
        $ret = $renewal->settlementFinancing($surplus_money, $addmoney, $drawprofit, $id);
        if ($ret) {
            $u_info = MemberModel::getMemberInfoByID($uid);
            if ($u_info['mobile']) {
                $var = $u_info['mobile'];;
                $contentarr = getconfigSms_status(['name' => 'stock_borrow_endedit']);
                $content = str_replace(array(
                  "#var#"
                ), array(
                  $var
                ), $contentarr['value']);
                if ($contentarr['status'] == 1) {
                    sendsms_mandao('', $content, '');
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /*
    * 获取即将到期配资列表
    * @param array $map 筛选条件
    * @param array $order 排序
    * @author 蔡伟明 <314013107@qq.com>
    * @return mixed
    */
    public static function getSoonExpire($map = [], $order = [], $condition = [])
    {
        $tis = 3 * 86400 + time();
        $data_list = self::view('stock_borrow b', true)
          ->view("member", 'mobile,name,email', 'member.id=b.member_id', 'left')
          ->view("stock_subaccount s", 'sub_account', 's.id=b.stock_subaccount_id', 'left')
          ->where($map)
          ->where($condition)
          ->where(['b.status' => 1])
          ->where('b.end_time', '<=', $tis)
          ->order('b.id desc')
          ->paginate()
          ->each(function ($item, $key) {
              $item->deposit_money = money_convert($item->deposit_money);
              $item->init_money = money_convert($item->init_money);
              $item->borrow_money = money_convert($item->borrow_money);
              $item->borrow_interest = money_convert($item->borrow_interest);
              $item->stock_money = money_convert($item->stock_money);
              if ($item->getData('type') === 3) {
                  $unit = '个月';
              } elseif ($item->getData('type') === 2) {
                  $unit = '周';
              } else {
                  $unit = '天';
              }
              $item->borrow_duration .= $unit;
          });
        return $data_list;
    }

    /**
     * 获取待审核的配资记录信息
     * @param array $id 配资ID
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getEditBorrow($id = null)
    {
        $where['b.id'] = intval($id);
        $where['b.status'] = -1;//待审核
        $data_list = self::view('stock_borrow b', true)
          ->view("member m", 'mobile,name,pid', 'm.id=b.member_id', 'left')
          //->view("stock_subaccount sub", true, 'sub.id=b.stock_subaccount_id', 'left')
          ->where($where)
          ->find();
        if (!is_null($data_list)) {
            if (isset($data_list['pid']) && $data_list['pid'] > 1) {
                $res = Db::view("admin_user a")
                  ->view('admin_role r', 'description', 'r.id=a.role')
                  ->where(["a.id" => $data_list['pid']])
                  ->find();
                if ($res) {
                    $data_list['agent'] = $res['description'] . " : " . $res['username'];
                } else {
                    $data_list['agent'] = "";
                }
            }
            $data_list['deposit_money'] = money_convert($data_list['deposit_money']);
            $data_list['init_money'] = money_convert($data_list['init_money']);
            $data_list['borrow_money'] = money_convert($data_list['borrow_money']);
            $data_list['borrow_interest'] = money_convert($data_list['borrow_interest']);
            $data_list['stock_money'] = money_convert($data_list['stock_money']);
        }
        return $data_list;
    }

    /**
     * 保存审核的配资记录信息
     * @param array $data 配资信息
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function saveBorrow($data)
    {
        $data['renewal'] = $data['renewal_edit'];
        $contents = trim($data['contents']);//审核信息
        //获取会员信息及会员资金信息
        $minfo_m = Db::name('member')->where(['id' => $data['member_id']])->where(['status' => 1])->find();
        //获取股票信息
        $stock_borrow = Db::name('stock_borrow')->where(['id' => $data['id']])->find();
        if ($stock_borrow['borrow_interest_activity'] == 0) {
            //全部从余额扣除
            $mmoney['k'] = "全部从余额扣除";
            #总冻结资金:用于从用户原有的保证金总减出来
            $freeze = $stock_borrow['deposit_money'] + $stock_borrow['borrow_interest'];
            #余额支付那部分保证金和利息：用于加回余额
            $sumMoney = $stock_borrow['deposit_money'] + $stock_borrow['borrow_interest'];
            $borrow_interest_activity = 0;
        } else if ($stock_borrow['borrow_interest_activity'] == $stock_borrow['borrow_interest']) {
            //全部从活动金总扣除
            #总冻结资金:用于从用户原有的保证金总减出来
            $freeze = $stock_borrow['deposit_money'];
            #余额支付那部分保证金：用于加回余额
            $sumMoney = $stock_borrow['deposit_money'];
            #用的那部分活动金
            $borrow_interest_activity = $stock_borrow['borrow_interest'];
            $mmoney['k'] = "全部从活动金总扣除";
        } else {
            //部分从余额扣除  部分从保证金中扣除
            //当前配资冻结余额=保证金+从余额扣除部分（总利息减去从活动金总扣除部分）
            #余额支付总金额：用于加回余额
            $sumMoney = $stock_borrow['deposit_money'] + $stock_borrow['borrow_interest'] - $stock_borrow['borrow_interest_activity'];
            #总冻结资金:用于从用户原有的保证金总减出来
            $freeze = $stock_borrow['deposit_money'] + $stock_borrow['borrow_interest'];
            #用的那部分活动金
            $borrow_interest_activity = $stock_borrow['borrow_interest_activity'];
            $mmoney['k'] = "部分从余额扣除  部分从保证金中扣除";
        }
        $record = new RecordModel;
        if ($data['type'] == "模拟操盘") {
            if ($data['v_status'] == 0) {//审核未通过
                $res = $this->where(['id' => $data['id']])->update(['status' => 0]);
                if ($res) {
                    return ['status' => 1, 'msg' => '审核未通过，处理成功'];
                } else {
                    return ['status' => 0, 'msg' => '审核未通过，处理失败'];
                }
            } else {
                $subInfo = SubaccountModel::get($data['stock_subaccount_id']);
                if (empty($subInfo['sub_account']) || empty($subInfo['sub_pwd'])) {
                    return ['status' => 0, 'msg' => '子账户帐号或密码不能为空！'];
                }
                $contents .= "  您的模拟操盘交易账号为：" . $subInfo['sub_account'];
                $borrow = self::get($data['id']);
                $dataInfo['status'] = $data['v_status'];
                $dataInfo['stock_subaccount_id'] = $data['stock_subaccount_id'];
                if (getEndDay2('now', festival()) === 1 && time() < mktime(14, 45)) {
                    $dataInfo['verify_time'] = time();
                } else {
                    $dataInfo['verify_time'] = strtotime(getEndDay('now', 1, festival()) . " 09:00:00");
                }
                if (time() < mktime(14, 45)) {
                    $data['borrow_duration'] = $data['borrow_duration'] - 1;
                }
                $start = date('Y-m-d', $dataInfo['verify_time']);
                $end_time = getEndDay($start, $data['borrow_duration'], festival());
                $dataInfo['end_time'] = strtotime($end_time . " 14:45:00");
                $dataInfo['account_id'] = $subInfo['account_id'];
                $ret = $borrow->save($dataInfo);
                $subAccount = SubaccountModel::get($data['stock_subaccount_id']);
                $pram['uid'] = $data['member_id'];
                $pram['status'] = 1;
                $pram['user_type'] = 3;
                $mmRet = $subAccount->save($pram);
                // 子账户风控
                $risk['loss_warn'] = $data['loss_warn'];
                $risk['loss_close'] = $data['loss_close'];
                $risk['position'] = $data['position'];
                $risk['prohibit_open'] = $data['prohibit_open'];
                $risk['prohibit_close'] = $data['prohibit_close'];
                //$risk['prohibit_back'] = $data['prohibit_back'];
                $risk['renewal'] = $data['renewal'];
                $risk['autoclose'] = $data['autoclose'];
                $risk['update_time'] = time();
                $risk_res = Db('stock_subaccount_risk')
                  ->where(['stock_subaccount_id' => $data['stock_subaccount_id']])
                  ->update($risk);
                $subaccount_money['commission_scale'] = $data['commission_scale'];
                $subaccount_money['min_commission'] = $data['min_commission'] * 100;
                $subaccount_money['rate_scale'] = $data['rate_scale'];
                $subaccount_money['profit_share_scale'] = $data['profit_share_scale'];
                $subaccount_money['deposit_money'] = $data['deposit_money'] * 100;
                $subaccount_money['borrow_money'] = $data['borrow_money'] * 100;
                $subaccount_money['avail'] = $data['init_money'] * 100;
                $subaccount_money['update_time'] = time();
                $subaccount_money_res = Db('stock_subaccount_money')
                  ->where(['stock_subaccount_id' => $data['stock_subaccount_id']])
                  ->update($subaccount_money);
            }
            if ($mmRet !== false && $ret !== false && $risk_res && $subaccount_money_res) {
                //给用户发送短信和站内信(短信发送不可用,待处理)
                if ($minfo_m['mobile']) {
                    //$sms = send_sms($minfo_m['mobile'], '',$contents);//发送自定义短信
                }
                //添加站内信信息
                $MemberMessageModel = new MemberMessageModel();
                $MemberMessageModel->addInnerMsg($data['member_id'], '模拟操盘审核通知', $contents, 10);//站内信
                return ['status' => 1, 'msg' => '审核通过，处理成功'];
            } else {
                return ['status' => 0, 'msg' => '审核通过，处理失败'];
            }
        }
        if ($data['v_status'] == 0) {//审核未通过
            // 审核不通过，资金解冻、返还利息
            //更新资金表
            Db::startTrans();
            $minfo = Db::name("money")->where(['mid' => $data['member_id']])->lock(true)->find();
            $mmoney['freeze'] = $minfo['freeze'] - $freeze;//资金解冻，减掉冻结中的资金
            $mmoney['account'] = $minfo['account'] + $sumMoney;//资金解冻，增加会员资金账户余额
            $mmoney['activity_account'] = $minfo['activity_account'] + $borrow_interest_activity;//资金解冻，增加会员资金活动金
            $mmoney['freeze_activity'] = $minfo['freeze_activity'] - $borrow_interest_activity;//资金解冻，增加会员资金活动金
            $money_res = Db('money')->where(['mid' => $data['member_id']])->update($mmoney);
            $hs_sumMoney = $sumMoney / 100;
            $obj = ['affect' => $sumMoney, 'account' => $mmoney['account'], 'affect_activity' => $borrow_interest_activity, 'activity_account' => $mmoney['activity_account'], 'sn' => ''];
            $msg = '配资审核未通过，解冻保证金到余额，余额：' . $hs_sumMoney . '元，活动金：' . $borrow_interest_activity . '元';
            $record_res = $record->saveData($data['member_id'], $sumMoney, $mmoney['account'], 21, $msg, '', '', $obj);
            $res = $this->where(['id' => $data['id']])->update(['status' => 0]);
            if ($res && $money_res && $record_res) {
                Db::commit();
                return ['status' => 2, 'msg' => '审核未通过，处理成功'];
            } else {
                Db::rollback();
                return ['status' => 0, 'msg' => '审核未通过，处理失败'];
            }
        } else {
            //审核通过
            //根据子账户ID获取子账户信息
            $subInfo = SubaccountModel::get($data['stock_subaccount_id']);
            if (empty($subInfo['sub_account']) || empty($subInfo['sub_pwd'])) {
                return ['status' => 0, 'msg' => '子账户帐号或密码不能为空！'];
            }
            $contents .= "  您的股票交易账号为：" . $subInfo['sub_account'] . " 密码：" . $subInfo['sub_pwd'];
            Db::startTrans();
            $minfo = Db::name("money")->where(['mid' => $data['member_id']])->find();
            //更新资金表
            $mmoney['freeze'] = $minfo['freeze'] - $freeze;//资金解冻，减掉冻结中的资金
            $mmoney['operate_account'] = $minfo['operate_account'] + $data['init_money'] * 100;//配资成功，增加该配资的初始操盘资金
            $mmoney['bond_account'] = $minfo['bond_account'] + $data['deposit_money'] * 100;
            $mmoney['freeze_activity'] = $minfo['freeze_activity'] - $borrow_interest_activity;//资金活动资金
            //额外插入一个管理费记录
            $inset_borrow_interest = $stock_borrow['borrow_interest'] / 100;
            $infostr = "新增配资审核通过，子账号id：" . $data['stock_subaccount_id'] . "（" . $subInfo['sub_account'] . "），系统收入" . $inset_borrow_interest . "元";
            $stock_detail_new_data = array("borrow_id" => $stock_borrow['id'], "mid" => $data['member_id'], "interest" => $stock_borrow['borrow_interest'], "info" => $infostr, "addtime" => time());
            Db::name('stock_detail_new')->insert($stock_detail_new_data);
            // 启动事务
            //还息明细表
            $stockDetail = Db::name('stock_detail');
            $insert_res = true;
            if ($data['type'] == "按月配资" && $data['borrow_duration'] > 1) {//按月并且大于一个月
                $data['total'] = $data['borrow_duration'];
                //插入还息明细表
                for ($i = 1; $i <= $data['borrow_duration']; $i++) {
                    $detailData['status'] = 0;
                    $detailData['borrow_id'] = $data['id'];
                    $detailData['mid'] = $data['member_id'];
                    $detailData['interest'] = $data['borrow_interest'];
                    $detailData['receive_interest'] = 0;
                    $detailData['sort_order'] = $i;
                    $detailData['total'] = $data['borrow_duration'];
                    $detailData['deadline'] = $data['borrow_duration'] == 1 ? time() : strtotime("+{$i} Month");
                    $detailData['repayment_time'] = 0;
                    $insert_res = Db::name('stock_detail')->insert($detailData);
                    if (!$insert_res) {
                        break;
                    }
                }
                //更新明细表第一个月的信息
                $detailInfo['status'] = 1;
                $detailInfo['receive_interest'] = $data['borrow_interest'];
                $detailInfo['sort_order'] = 1;
                $detailInfo['repayment_time'] = time();
                //where条件
                $whereDetail['borrow_id'] = $data['id'];
                $whereDetail['mid'] = $data['member_id'];
                $whereDetail['sort_order'] = 1;
                $stock_res = $stockDetail->where($whereDetail)->update($detailInfo);
            } else {
                $stock_res = true;
            }
            $rebate = true;
            if ($data['type'] == "按月配资" || $data['type'] == "按天配资") {
                //推荐人返利 只有按天、按月配资推荐人才会返利
//                $investor = $data['member_id'];
//                $recommendor = Db::name('member_invitation_relation')->where("invitation_mid = {$investor}")->value('mid');
//                if (!is_null($recommendor)) {//如果存在推荐人，则进行推荐人返佣计算
//                        $rebate = $this->sendRebate($recommendor, $data['id'], $memberInfo);
//                }
            }
            $subResult = Db('money')->where(['mid' => $data['member_id']])->update($mmoney);
            // 更新资金日志表信息
            $obj = ['affect' => 0, 'account' => $minfo['account'], 'affect_activity' => 0, 'activity_account' => $minfo['activity_account'], 'sn' => ''];
            $msg = '配资审核通过，解冻保证金到配资账户,保证金金额：' . $data['deposit_money'] . "元";
            $mmLogRet = $record->saveData($data['member_id'], $sumMoney, $minfo['account'], 22, $msg, '', '', $obj);
            //更新配资表信息
            $borrow = self::get($data['id']);
            $dataInfo['status'] = $data['v_status'];
            $dataInfo['stock_subaccount_id'] = $data['stock_subaccount_id'];
            switch ($data['type']) {
                case "按月配资":
                    $dataInfo['verify_time'] = time();
                    $duration = $data['borrow_duration'] . " month";
                    if (time() < mktime(14, 45)) {
                        $end_time = date('Y-m-d', strtotime("+{$duration}") - 86400);
                    } else {
                        $end_time = date('Y-m-d', strtotime("+{$duration}"));
                    }
                    break;
                case "按周配资":
                    $dataInfo['verify_time'] = time();
                    $duration = $data['borrow_duration'] . " week";
                    if (time() < mktime(14, 45)) {
                        $end_time = date('Y-m-d', strtotime("+{$duration}") - 86400);
                    } else {
                        $end_time = date('Y-m-d', strtotime("+{$duration}"));
                    }
                    break;
                case "按天配资":
                    if (getEndDay2('now', festival()) === 1 && time() < mktime(14, 45)) {
                        $dataInfo['verify_time'] = time();
                    } else {
                        $dataInfo['verify_time'] = strtotime(getEndDay('now', 1, festival()) . " 09:00:00");
                    }
                    $start = date('Y-m-d', $dataInfo['verify_time']);
                    $data['borrow_duration'] = $data['borrow_duration'] - 1;
                    $end_time = getEndDay($start, $data['borrow_duration'], festival());
                    break;
                default:
                    if (getEndDay2('now', festival()) === 1 && time() < mktime(14, 45)) {
                        $dataInfo['verify_time'] = time();
                    } else {
                        $dataInfo['verify_time'] = strtotime(getEndDay('now', 1, festival()) . " 09:00:00");
                    }
                    $start = date('Y-m-d', $dataInfo['verify_time']);
                    $data['borrow_duration'] = $data['borrow_duration'] - 1;
                    $end_time = getEndDay($start, $data['borrow_duration'], festival());
                    break;
            }
            $dataInfo['end_time'] = strtotime($end_time . " 14:45:00");
            //$account_info=account::getBroker($subInfo['account_id']);
            $dataInfo['account_id'] = $subInfo['account_id'];
            $ret = $borrow->save($dataInfo);
            $subAccount = SubaccountModel::get($data['stock_subaccount_id']);
            $pram['uid'] = $data['member_id'];
            $pram['status'] = 1;
            $mmRet = $subAccount->save($pram);
            // 子账户风控
            $risk['loss_warn'] = $data['loss_warn'];
            $risk['loss_close'] = $data['loss_close'];
            $risk['position'] = $data['position'];
            $risk['prohibit_open'] = $data['prohibit_open'];
            $risk['prohibit_close'] = $data['prohibit_close'];
            //$risk['prohibit_back'] = $data['prohibit_back'];
            $risk['renewal'] = $data['renewal'];
            $risk['autoclose'] = $data['autoclose'];
            $risk['update_time'] = time();
            $risk_res = Db('stock_subaccount_risk')
              ->where(['stock_subaccount_id' => $data['stock_subaccount_id']])
              ->update($risk);
            $subaccount_money['commission_scale'] = $data['commission_scale'];
            $subaccount_money['min_commission'] = $data['min_commission'] * 100;
            $subaccount_money['rate_scale'] = $data['rate_scale'];
            $subaccount_money['profit_share_scale'] = $data['profit_share_scale'];
            $subaccount_money['deposit_money'] = $data['deposit_money'] * 100;
            $subaccount_money['borrow_money'] = $data['borrow_money'] * 100;
            $subaccount_money['avail'] = $data['init_money'] * 100;
            $subaccount_money['update_time'] = time();
            $subaccount_money_res = Db('stock_subaccount_money')
              ->where(['stock_subaccount_id' => $data['stock_subaccount_id']])
              ->update($subaccount_money);
            if ($mmRet && $mmLogRet !== false && $ret && $subResult && $risk_res && $subaccount_money_res && $stock_res && $rebate && $insert_res) {
                //添加站内信信息
                $MemberMessageModel = new MemberMessageModel();
                $MemberMessageModel->addInnerMsg($data['member_id'], '配资审核通知', $contents, 10);//站内信
                Db::commit();
                return ['status' => 1, 'msg' => '审核通过，处理成功'];
            } else {
                Db::rollback();
                return ['status' => 0, 'msg' => '审核通过，处理失败'];
            }
        }
    }

    /*
     * 发送返利给推荐人
     * 返利金额
     * @param $recommender 推荐人id
     * @param $investor 配资人id
     * @param $borrowId 配资ID
     * @param $memberInfo 会员信息及资金信息数据集
     * @return true 无论是否成功，都返回true，不影响后续操作
     */
    public function sendRebate($recommender, $borrowId, $memberInfo)
    {
        $result = false;
        $borrowInfo = self::get($borrowId);
        $money = 0;
        if ($borrowInfo['type'] == "按月配资") {//如果是按月配资
            $money = $borrowInfo['borrow_interest'] * $borrowInfo['borrow_duration']; //利息
        } elseif ($borrowInfo['type'] == "按天配资") {//如果是按天配资
            $money = $borrowInfo['borrow_interest'];
        }
        $rate = config('rebate');//获取全局参数推荐人返佣比例
        $amount = $money * $rate / 100;//返佣
        //备注说明信息
        $info = "您推荐的用户" . $memberInfo['mobile'] . "(" . $memberInfo['name'] . ")配资成功，配资管理费：" . ($money / 100) . "元，您获得返利： " . ($amount / 100) . "元.";
        ////将返佣插入推荐人返佣资金记录表
        $dataRecord['mid'] = $recommender;//$investor;
        $dataRecord['money'] = $amount;
        $dataRecord['remark'] = $info;
        $dataRecord['create_time'] = time();
        $record = Db::name('member_invitation_record')->insert($dataRecord);
        $money_info = Money::getMoney($recommender);
        if (isset($money_info['account'])) {
            $mmoney['account'] = $money_info['account'] + $amount;
            $money_res = Money::money_up($recommender, $mmoney);
        } else {
            $mmoney['account'] = $money_info['account'];
            $money_res = false;
        }
        //将返佣插入资金记录
        $RecordModel = new RecordModel();
//        $moneyRecord = $RecordModel->saveData($recommender, $amount, $mmoney['account'], 10, $info);
        $obj = ['affect' => $amount, 'account' => $mmoney['account'], 'affect_activity' => 0, 'activity_account' => $money_info['activity_account'], 'sn' => ''];
        $moneyRecord = $record->saveData($recommender, $amount, $mmoney['account'], 10, $info, '', '', $obj);
        if ($record && $moneyRecord && $money_res) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /*
     * 查询正在操盘中的配资总金额
     *
     * @param $accountID 证券id
     * @return 返回配资总金额
     */
//    public static function getBorrowmoney($accountID){
//        $total=Db::query("select sum(b.init_money) as borrowMoney from lmq_stock_borrow b where b.member_id in(select u.uid from lmq_stock_subaccount u where u.account_id =".$accountID."  GROUP BY u.uid) and status =1");
//        return intval($total[0]['borrowMoney']);
//    }
    /*
     * 查询正在操盘中的实盘配资总金额
     * @param $accountID 证券id
     * @return 返回配资总金额
     */
    public static function getRealBorrowmoney($accountID)
    {
        //$res=Db::name("stock_subaccount")->field("uid")->where(["account_id"=>$accountID])->group("uid")->select();
        $total = Db::name("stock_borrow")
          //->where("member_id","in",$res)
          ->where(["status" => 1])
          ->where(["account_id" => $accountID])
          ->sum("init_money");
        return intval($total[0]['borrowMoney']);
    }

    /*
     * 根据ID返回配资记录
     * @id 配资记录ID
     */
    public static function getBorrowById($id, $status = null)
    {
        if ($status == null) {
            return $binfo = Db::name("stock_borrow")->where(["id" => $id])->find();
        } else {
            return $binfo = Db::name("stock_borrow")->where(["id" => $id, 'status' => $status])->find();
        }
    }

    /*
     * 是否已有试用、免费、模拟操盘校验
     */
    public function trycheck($mid, $type)
    {
        return Db::name('stock_borrow')
          ->where(['member_id' => $mid])
          ->where(['type' => $type])
          ->where('status', '<>', '0')
          ->find();
    }

    /*
    * 使用有充值记录  mid 用户id   money 金额
    */
    public function trymoney($mid, $type)
    {
        return Db::name('money_recharge')
          ->where(['member_id' => $mid])
          ->where(['type' => $type])
          ->where('status', '<>', '0')
          ->find();
    }

    /*
     * 已有申请验证
     */
    public function onecheck($mid)
    {
        return Db::name('stock_borrow')->where(['member_id' => $mid])->where(['status' => "-1"])->find();
    }

    /*
  * 已有申请验证
  */
    public function onechecktype($mid)
    {
        $map = "status !=0";
        return Db::name('stock_borrow')->where(['member_id' => $mid])->where($map)->find();
    }

    /*
     * 会员发起配资申请存库
     * @return [type] [description]
     * @author 张继立 <404851763@qq.com>
     */
    public function createStock($data = [])
    {
        $record = new RecordModel;
        $interest = self::interest($data['type'], $data['rate'], $data['borrow_money'], $data['borrow_duration']);
        $data['borrow_interest'] = $interest;
        $need_money = bcadd($interest, $data['deposit_money']);
        Db::startTrans();
        $member_money = Db('money')->where(['mid' => $data['member_id']])->lock(true)->find();
        #  INSERT INTO `test-1`.`lmq_money` (`id`, `mid`, `account`, `freeze`, `operate_account`, `bond_account`, `status`, `activity_account`) VALUES ('61', '61', '870720', '10020', '1210000', '110000', '1', '40000');
        if ($member_money['activity_account'] >= $interest) {
            if ($member_money['account'] < $data['deposit_money']) {
                return ['status' => 0, 'msg' => '资金不足请充值'];
            }
            //活动资金-利息
            $activity_account = $member_money['activity_account'] - $interest;
            //余额等于用户余额-保证金
            $updatamone = $member_money['account'] - $data['deposit_money'];
            $lx = $interest / 100;
            $deposit_money = $data['deposit_money'] / 100;
            $str = '申请配资:' . $data['order_id'] . "，扣除保证金：{$deposit_money}元，总利息{$lx}(从活动金扣除{$lx}元,余额扣除0元)";
            #   $interest = $lx;
            #这里冻结资金可能有问题，不知道解冻后会怎么样
            $freeze = $member_money['freeze'] + $data['deposit_money'];
            $freeze_activity = $member_money['freeze_activity'] + $interest;
            $data['borrow_interest_activity'] = $interest;
            $obj = ['affect' => -abs($deposit_money * 100), 'account' => $updatamone, 'affect_activity' => -abs($lx * 100), 'activity_account' => $activity_account, 'sn' => $data['order_id']];
        } else {
            if ($member_money['account'] < $need_money - $member_money['activity_account']) {
                return ['status' => 0, 'msg' => '资金不足请充值'];
            }
            $activity_account = $member_money['activity_account'];
            //换算单位后
            $activity_account2 = $activity_account / 100;
            //先扣掉活动资金  得到扣除活动金后的利息
            $interest3 = $interest - $member_money['activity_account'];
            //换算单位后
            $interest3_conversion = $interest3 / 100;
            $interest_conversion = $interest / 100;
            $deposit_money = $data['deposit_money'] / 100;
            //在用余额补上  =用户余额-扣除活动金后的利息-保证金
            $updatamone = $member_money['account'] - $interest3 - $data['deposit_money'];
            $str = '申请配资:' . $data['order_id'] . "，扣除保证金：" . $deposit_money . "元，总利息{$interest_conversion}(从活动金扣除{$activity_account2}元,余额扣除{$interest3_conversion}元)";
            #     $interest = $interest_conversion;
            #这里冻结资金可能有问题，不知道解冻后会怎么样
            $freeze = $member_money['freeze'] + $data['deposit_money'] + $interest;
            $freeze_activity = $member_money['freeze_activity'] + $activity_account;
            $data['borrow_interest_activity'] = $activity_account;
            //活动金在这里已全部扣除 设为0
            $activity_account = 0;
            $obj = ['affect' => -abs(($interest_conversion * 100 + $deposit_money * 100)), 'account' => $updatamone, 'affect_activity' => -abs($activity_account2 * 100), 'activity_account' => $activity_account, 'sn' => $data['order_id']];
        }
//
//        echo "freeze:".$freeze."\r\n";
//        echo "freeze_activity:".$freeze_activity."\r\n";
//        echo "activity_account:".$activity_account."\r\n";
//        echo "account:".$updatamone."\r\n";
//        die();
        try {
            $money_res = Db('money')->where(['mid' => $data['member_id']])->update([
              'account' => $updatamone,
              'freeze' => $freeze,
              'freeze_activity' => $freeze_activity,
              'activity_account' => $activity_account]);
            $record_res = $record->saveData($data['member_id'], -abs($need_money), $updatamone, 33, $str, 'sqlx', $interest, $obj);
            $data['for_user'] = Db('member')->where('id', $data['member_id'])->value('agent_far');
            // 验证
            //$result = $this->validate('Borrow.create')->save($data);tp5.1不再支持
            $validate = new \app\apicom\validate\Borrow();
            $result = $validate->scene('create')->check($data);
            if ($result === false) {
                Db::rollback();
                return ['status' => '0', 'msg' => $this->getError()];
            }
            $this->save($data);
            if ($money_res && $result && $record_res) {
                Db::commit();
                return ['status' => '1', 'msg' => '提交成功，已通知管理员审核'];
            } else {
                Db::rollback();
                return ['status' => '0', 'msg' => '系统异常1'];
            }
        } catch (\Exception $e) {
            Db::rollback();
            print_r($e->getMessage());
            exit;
            //return ['status'=>'0', 'msg'=>'系统异常2'];
        }
    }

    /*
     * 计算利息
     * @param  intval $type         配资类型
     * @param  float $rate         手续费费率
     * @param  intval $borrow_money 配资金额
     * @param  intval $duration     融资期数
     * @return int   利息
     * @author 张继立 <404851763@qq.com>
     */
    public static function interest($type, $rate, $borrow_money, $duration)
    {
        $interest = 0;
        $rate = floatval($rate / 100);
        switch ($type) {
            case '1': // 按天配资
                $interest = bcmul($borrow_money, $rate) * $duration;
                break;
            case '2': //按周配资
                $interest = bcmul($borrow_money, $rate) * $duration;
                break;
            case '3': // 按月配资
                $interest = bcmul($borrow_money, $rate) * 1;
                break;
            case '4': //免费体验
                break;
            case '5': //免息配资
                break;
            case '6': //模拟操盘
                break;
            default:
                break;
        }
        return intval($interest);
    }

    public static function getBorrow($mid, $limit = '', $map = [], $order = 'id desc')
    {
        $map['member_id'] = $mid;
        $data_list = self::view('stock_borrow sb', true)
          ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'sb.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
          ->view('stock_subaccount_money ssm', 'avail,return_money, return_rate', 'sb.stock_subaccount_id=ssm.stock_subaccount_id', 'left')
          ->where($map)
          ->order($order)
          //->limit($limit)
          ->paginate($limit)
          ->each(function ($item, $key) {
              $item->loss_warn_money = $item->borrow_money + $item->loss_warn * $item->deposit_money / 100;
              $item->loss_close_money = $item->borrow_money + $item->loss_close * $item->deposit_money / 100;
              $item->status_text = self::$status_str[$item->status];
          });
        return $data_list;
    }

    /*
     * 返回带收益的borrow表信息
     */
    public static function getBorrowinfo($mid, $limit = '', $map = [], $order = 'id desc')
    {
        $data_list = self::view('stock_borrow sb', "type as types,*")
          ->view("stock_subaccount subaccount", "sub_account", "sb.stock_subaccount_id=subaccount.id", "left")
          ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'sb.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
          ->view('stock_subaccount_money ssm', 'avail,return_money, return_rate', 'sb.stock_subaccount_id=ssm.stock_subaccount_id', 'left')
          ->where($map)
          ->where(['member_id' => $mid])
          ->where("sb.type", "<>", 6)
          ->order($order)
          ->paginate($limit, false, ['query' => request()->param(),])
          ->each(function ($item, $key) {
              $item->loss_warn_money = $item->borrow_money + $item->loss_warn * $item->deposit_money / 100;
              $item->loss_close_money = $item->borrow_money + $item->loss_close * $item->deposit_money / 100;
              $item->return_money = round($item->return_money, 2);
              $item->status_text = self::$status_str[$item->status] ?? '';
              if ($item->end_time <= time() && $item->status != 2 && $item->end_time > 0) {
                  $item->status = 3;
              }
              if ($item->types == 3) {
                  if (($item->verify_time + 2592000) > time()) {
                      $item->management = '首月管理费';
                  } else {
                      $item->management = '配资管理费(月)';
                  }
                  $item->imgsType = 'yuepeizi.png';
              } elseif ($item->types == 5) {
                  $item->management = '配资管理费(免)';
                  $item->imgsType = 'mianxipeizi.png';
              } elseif ($item->types == 1) {
                  $item->management = '配资管理费(天)';
                  $item->imgsType = 'tianpeizi.png';
              } elseif ($item->types == 2) {
                  $item->management = '配资管理费(周)';
                  $item->imgsType = 'zhoupeizi.png';
              } elseif ($item->types == 4) {
                  $item->management = '配资管理费(体)';
                  $item->imgsType = 'tiyan.png';
              }
              $res = Db::name('stock_position')
                ->where(['sub_id' => $item->stock_subaccount_id])
                ->where(['buying' => 0])
                ->select();
              $sum = 0;
              $item->btn = "去交易";
              if (!empty($res)) {
                  foreach ($res as $k => $v) {
                      $lis = z_market($v['gupiao_code']);
                      if (!isset($lis['current_price'])) {
                          $lis['current_price'] = 0;
                      }
                      $sum += $lis['current_price'] * $v['stock_count'];
                  }
                  $item->btn = "查看实盘交易";
              }
              $item->sum_money = $sum;
          });
        return $data_list;
    }

    /*
     * 返回结束的模拟操盘信息
     */
    public static function get_end_mock($map, $order)
    {
        $data_list = self::view('stock_borrow sb', true)
          ->view("member m", 'mobile,name', 'm.id=sb.member_id')
          ->view("stock_subaccount subaccount", "sub_account", "sb.stock_subaccount_id=subaccount.id", "left")
          ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'sb.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
          ->view('stock_subaccount_money ssm', 'avail,return_money, return_rate', 'sb.stock_subaccount_id=ssm.stock_subaccount_id', 'left')
          ->where($map)
          ->order($order)
          ->where("sb.type", "=", 6)
          ->paginate()
          ->each(function ($item, $key) {
              $item->loss_warn_money = $item->borrow_money + $item->loss_warn * $item->deposit_money / 100;
              $item->loss_close_money = $item->borrow_money + $item->loss_close * $item->deposit_money / 100;
              $item->return_money = $item->return_money / 100;
              $item->status_text = self::$status_str[$item->status];
          });
        return $data_list;
    }

    /*
     * 返回带收益的模拟操盘信息
     */
    public static function getmock($mid)
    {
        $map['member_id'] = $mid;
        $data_list = self::view('stock_borrow sb', true)
          ->view("stock_subaccount subaccount", "sub_account", "sb.stock_subaccount_id=subaccount.id", "left")
          ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'sb.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
          ->view('stock_subaccount_money ssm', 'avail,return_money, return_rate', 'sb.stock_subaccount_id=ssm.stock_subaccount_id', 'left')
          ->where($map)
          ->where("type", "=", 6)
          ->paginate()
          ->each(function ($item, $key) {
              $item->loss_warn_money = $item->borrow_money + $item->loss_warn * $item->deposit_money / 100;
              $item->loss_close_money = $item->borrow_money + $item->loss_close * $item->deposit_money / 100;
              $item->return_money = $item->return_money / 100;
              if ($item->end_time <= time() && $item->status != 2 && $item->end_time > 0) {
                  $item->status = 3;
              };
              $item->status_text = self::$status_str[$item->status];
              $res = Db::name('stock_position')
                ->where(['sub_id' => $item->stock_subaccount_id])
                ->where(['buying' => 0])
                ->select();
              $sum = 0;
              $item->btn = "去交易";
              if (!empty($res)) {
                  foreach ($res as $k => $v) {
                      $lis = z_market($v['gupiao_code']);
                      if (!isset($lis['current_price'])) {
                          $lis['current_price'] = 0;
                      }
                      $sum += $lis['current_price'] * $v['stock_count'];
                  }
                  $item->btn = "查看实盘交易";
              }
              $item->sum_money = $sum;
          });
        return $data_list;
    }

    /*
     * 判断是否为A股股票
     */
    public static function market_type($code)
    {
        switch (substr($code, 0, 1)) {
            case '0':
                return 1;
                break;
            case '3':
                return 1;
                break;
            case '6':
                return 2;
                break;
            case '8':
                return 1;
            default:
                return 5;
                break;//5、出错
        }
    }

    /**
     * 根据配资ID获取配资记录详情信息
     * @param array $id 配资ID
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getBorrowDetail($id = null)
    {
        $where['b.id'] = intval($id);
        $data_list = self::view('stock_borrow b', true)
          ->view("member m", 'mobile,name', 'm.id=b.member_id', 'left')
          ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
          ->view('stock_subaccount_money ssm', 'avail,return_money,available_amount,return_rate', 'b.stock_subaccount_id=ssm.stock_subaccount_id', 'left')
          ->view('stock_subaccount ss', 'sub_account,sub_pwd,agent_id', 'ss.id=b.stock_subaccount_id', 'left')
          ->where($where)
          ->find();
        if ($data_list['type'] == Lang::get('B0008')) { //按周配资
            $data_list['units'] = '周';
            $data_list['type_text'] = 'week';
            $data_list['units_text'] = 'week';
        } elseif ($data_list['type'] == Lang::get('B0007')) { //按天配资
            $data_list['units'] = '天';
            $data_list['type_text'] = 'day';
            $data_list['units_text'] = 'day';
        } elseif ($data_list['type'] == Lang::get('B0009')) { //按月配资
            $data_list['units'] = '月';
            $data_list['type_text'] = 'month';
            $data_list['units_text'] = 'month';
        } elseif ($data_list['type'] == Lang::get('B0010')) { //免费体验
            $data_list['units'] = '体';
            $data_list['type_text'] = 'free';
            $data_list['units_text'] = 'free';
        } elseif ($data_list['type'] == Lang::get('B0006')) { //免息配资
            $data_list['units'] = '免';
            $data_list['type_text'] = 'interest_free';
            $data_list['units_text'] = 'interest_free';
        }
        if (!is_null($data_list)) {
            if ($data_list['loss_warn'] === null) {
                $list = self::view('stock_borrow b', true)->where($where)->find();
                $data_list['loss_warn'] = $list['loss_warn'];
                $data_list['loss_close'] = $list['loss_close'];
            }
            $data_list['loss_warn_money'] = $data_list['borrow_money'] + $data_list['loss_warn'] * $data_list['deposit_money'] / 100;
            $data_list['loss_close_money'] = $data_list['borrow_money'] + $data_list['loss_close'] * $data_list['deposit_money'] / 100;
            $data_list['return_money'] = money_convert($data_list['return_money']);
            if ($data_list['end_time'] <= time() && $data_list['status'] == 1 && $data_list['end_time'] > 0) {
                $data_list['status'] = 3;
            }
            $data_list['status_text'] = $data_list['status'];
            $data_list['status'] = self::$status_str[$data_list['status']];
        }
        return $data_list;
    }

    /*
     * 判断用户是否有未处理的申请
     */
    public static function checkApply($borrow_id, $uid, $sub_id)
    {
        $addmoney = Db::name("stock_addmoney");
        $moneyres = $addmoney->where("status=0 and borrow_id={$borrow_id} and uid={$uid}")->find();
        if (!empty($moneyres)) {
            return ['status' => 0, 'msg' => '该用户还有未审核的增加保证金申请,请处理！'];
        }
        $renewal = Db::name("stock_renewal");
        $renewalres = $renewal->where("type=1 and status=0 and borrow_id={$borrow_id} and uid={$uid}")->find();
        if (!empty($renewalres)) {
            return ['status' => 0, 'msg' => '该用户有未审核的延期申请，请处理!'];
        }
        $addfinancing = Db::name("stock_addfinancing");
        $addfinancingres = $addfinancing->where("status=0 and borrow_id={$borrow_id} and uid={$uid}")->find();
        if (!empty($addfinancingres)) {
            return ['status' => 0, 'msg' => '该用户有未审核的扩大配资申请，请处理!'];
        }
        $drawprofit = Db::name("stock_drawprofit");
        $drawprofits = $drawprofit->where("status=0 and borrow_id={$borrow_id} and uid={$uid}")->find();
        if (!empty($drawprofits)) {
            return ['status' => 0, 'msg' => '该用户有未审核的提取盈利申请，请处理'];
        }
        $stockposition = Db::name("stock_position");
        $stock = $stockposition->where("sub_id={$sub_id}")->where(['buying' => 0])->find();
        if (!empty($stock)) {
            return ['status' => 0, 'msg' => '该用户持有未平仓的股票，请处理'];
        }
        return ['status' => 1, 'msg' => '检查完毕可以清算'];
    }

    /*
     * 卖出
     * $id 子账户id
     * $code 股票代码
     * $count 卖出数量
     * $sys 是否系统卖出标志 0、非系统卖出 1、系统卖出
     * $model交易模式 1 限价 2市价
     */
    public function savesell($id, $code, $count, $sys = 0, $price = null, $model = 2)
    {
        $stockinfo = z_market($code);
        if (empty($stockinfo)) {
            return ['status' => 0,
              'message' => '行情数据异常'
            ];
        }
        printlog($stockinfo, "卖-委托股票行情", "buy-sell");
        // 加了一个容错方式
        // 判断 实时价格 的上下幅度是否超过 委托价格 的15%
        $a_current_price = $stockinfo['current_price']; // 实时价格
        printlog($a_current_price, "卖-实时价格", "buy-sell");
        $b_trust_price = $price;  // 委托价格
        printlog($b_trust_price, "卖-委托价格", "buy-sell");
        // 计算 $b_trust_price 的15%的范围
        $threshold = $b_trust_price * 0.2;
        printlog($threshold, "计算 b_trust_price 的20%的范围", "buy-sell");
        if ($a_current_price < $b_trust_price - $threshold || $a_current_price > $b_trust_price + $threshold) {
            // 超过了15%的范围
            printlog('超过了20%的范围', "判断", "buy-sell");
            // return  [
            //     'status' => 0,
            //     'message' => '价格震荡期建议稍后再试' //'系统错误，请稍后再试！'
            // ];
        }
        if (intval($stockinfo["yesterday_price"]) == 0) {
            return ['status' => 0, 'message' => '请检查该股是否退市'];
        }
        if (intval($stockinfo["open_price"]) == 0) {
            return ['status' => 0,
              'message' => '该股今日还没开盘'
            ];
        }
        // 当股票跌停时买一至买五价格为空
        if (intval($stockinfo["buy_one_price"]) <= 0) {
            return [
              'status' => 0,
              'message' => '当前买盘不足，无法即时成交！'
            ];
        }
        $maxprice = intval($stockinfo["open_price"]) + intval($stockinfo["open_price"]) * 1.2;
        if ($maxprice < $price) {
            return [
              'status' => 0,
              'message' => '委托价不能超过涨停价！'
            ];
        }
        $maxprice2 = intval($stockinfo["open_price"]) - intval($stockinfo["open_price"]) * 1.2;
        if ($maxprice2 > $price) {
            # return ['status' => 0, 'message' => '委托价不能低于跌停价！'];
        }
        $trade_money = 0;
        // if(!empty($price)&&$model==1&&$price<$stockinfo['buy_one_price']){
        // $model=2;
        // }
        # model 为1并且价格不为空 是限价，model为2并价格为空是市价
        if ($price <= $stockinfo['buy_one_price']) {
            $model = 2;
        }
        if (!empty($price) && $model == 1) {
            $trade_money = $count * $price;
        } elseif ($model == 2) {
            $price = 0;
            $arr[1] = $stockinfo["buy_one_amount"] * 100;
            $arr[2] = $stockinfo["buy_two_amount"] * 100;
            $arr[3] = $stockinfo["buy_three_amount"] * 100;
            $arr[4] = $stockinfo["buy_four_amount"] * 100;
            $arr[5] = $stockinfo["buy_five_amount"] * 100;
            $p_arr[1] = $stockinfo["buy_one_price"];
            $p_arr[2] = $stockinfo["buy_two_price"];
            $p_arr[3] = $stockinfo["buy_three_price"];
            $p_arr[4] = $stockinfo["buy_four_price"];
            $p_arr[5] = $stockinfo["buy_five_price"];
            $tmd = 0;
            foreach ($arr as $key => $v) {
                $tmd = $tmd + $v;
                if ($tmd >= $count) {
                    $sum_money = 0;
                    $sum_count = $count;
                    for ($i = 1; $i < $key; $i++) {
                        $sum_money += $arr[$i] * $p_arr[$i];
                        $sum_count -= $arr[$i];
                    }
                    $trade_money = $sum_money + $sum_count * $p_arr[$key];
                    $price = round($trade_money / $count, 2);
                    $trade_money = $price * $count;
                    break;
                }
                if ($key == 5)
                    return [
                      'status' => 0,
                      'message' => '买量不足，无法成交'
                    ];
            }
        } else {
            return [
              'status' => 0,
              'message' => '价格已变动, 请刷新后重试'//'参数错误'
            ];
        }
        // if ($model==1&&abs($stockinfo["yesterday_price"] - $price) / $stockinfo["yesterday_price"] >= 0.08) {
        // return ['status' => 0, 'message' => '委托价格超过风控限制，禁止购买！'];
        // }
        $etype = $this->market_type($code);
        if ($etype == 5) {
            return [
              'status' => 0,
              'message' => '请检查卖出的股票代码是否错误'
            ];
        }
        $submodel = new StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (isset($res['id'])) {
            $sub_id = $res['id'];
        } else {
            return [
              'status' => 0,
              'message' => '不存在的子账号'
            ];
        }
        $bs_res = Db::name('stock_borrow')->where([
          'stock_subaccount_id' => $sub_id
        ])->find();
        if (empty($bs_res)) {
            return [
              'status' => 0,
              'message' => '没有对应的配资'
            ];
        }
        // if($bs_res['end_time']<=time()){
        // return ['status'=>0, 'msg'=>'配资已经到期，不允许卖出。'];
        // }
        $moneymodel = new SubAccountMoneyModel();
        $moneyinfo = $moneymodel->get_account_money($id);
        $broker = $submodel->get_broker($res['account_id']);
        $commission = round($trade_money * $moneyinfo['commission_scale'] / 10000, 2); // 佣金
        if ($commission < $moneyinfo['min_commission'] / 100) {
            $commission = $moneyinfo['min_commission'] / 100;
        }
        $stamp = round($trade_money * self::config('stamp_duty') / 1000, 2); // 印花税
//        if ($etype == 2) { //判断股票类型(这里区分股票类型来扣除过户费，暂时不做限制)
//            $Transfer = round($trade_money / 50000, 2); // 过户费,只有上海交易所收
//            if ($Transfer < self::config('transfer_fee')) {
//                $Transfer = self::config('transfer_fee');
//            }
//        } else {
//            $Transfer = 0;
//        }
        $Transfer = round($trade_money / 50000, 2); // 过户费,只有上海交易所收
        if ($Transfer < self::config('transfer_fee')) {
            $Transfer = self::config('transfer_fee');
        }
        //交易金额+过户费+佣金+规费
        $fees = round($trade_money * config('fees'), 2);//规费
        $position = new Position();
        $position_res = $position->get_code_position($sub_id, $code);
        if (empty($position_res)) {
            return [
              'status' => 0,
              'message' => '不存在的股票持仓'
            ];
        }
        if (empty($position_res['buy_average_price'])) {
//            file_put_contents(ROOT_PATH . '/public/log/sell-' . date("y-m-d") . '.txt', "价格异常" . "\r\n", FILE_APPEND);
            return [
              'status' => 0,
              'message' => '股票价格异常'
            ];
        }
        if ($position_res['canbuy_count'] < $count) {
            return [
              'status' => 0,
              'message' => '可卖股票数量不足!!'
            ];
        }
        if ($count % 100 > 0) {
            if ($position_res['canbuy_count'] != $count) {
                # return ['status' => 0, 'message' => '目前不允许拆零出售'];
            } else { // 为将来扩展处理分红配股预留
                # return ['status' => 0, 'message' => '目前只允许按手数出售'];
            }
        }
        // 买入价格
        $buy_average_price = $position_res['buy_average_price'];
        $risk = new StockSubAccountRisk();
        $risk_res = $risk->get_risk($sub_id);
        if ($risk_res['prohibit_close'] == 0 && $sys == 0) {
            return [
              'status' => 0,
              'message' => '您的子账户被禁止平仓，请联系管理员咨询原因。'
            ];
        }
        $Delivery = new Delivery();
        $dv_res = $Delivery->get_code_delivery_order($sub_id, $code);
        if (!empty($dv_res)) {
            $dv_num = 0;
            $deal_date = mktime(0, 0);
            foreach ($dv_res as $kk => $vv) {
                if ($vv['business_name'] == '证券买入') {
                    if ($vv['deal_date'] > $deal_date) {
                        continue;
                    }
                    $dv_num += $vv['volume'];
                }
                if ($vv['business_name'] == '证券卖出') {
                    $dv_num -= $vv['volume'];
                }
            }
            if ($dv_num < 100 && $code < $count) {
                //$dv_num = 100; //真不知道是啥  股票代码000001  可以一直卖出
            } else if ($dv_num > 100) {
                $dv_num = $dv_num / 100;
                $dv_num = ceil($dv_num) * 100;
            }
            if ($dv_num < $count) {
                return [
                  'status' => 0,
                  'message' => '可卖股票数量不足!!'
                ];
            } else {
                $amount = $dv_num - $count;
            }
        } else {
            return [
              'status' => 0,
              'message' => '可卖股票数量不足!'
            ];
        }
        if ($broker['broker'] === -1) {
            $type = 2; // 模拟账户回款
        } else {
            $type = 4; // 实盘账户
        }
        // 总价-过户费-印花税-佣金-规费
        $effect_money = $trade_money - $Transfer - $stamp - $commission - $fees;
        Db::startTrans();
        $Cost = $Transfer + $stamp + $commission + $fees;
        $Cost = $count . "，账面未体现金额{$Cost}，已被当成交易费用从余额扣除";
        printlog($model, "配资类型  ", 'Borrow');
        if ($model == 1) {
            $type = 4; // 如果是限价交易走实盘交易模式-先冻结卖出资金等交易确认后解冻
            $ret = $moneymodel->up_moneylog($sub_id, $effect_money * 100, $type, '', '', $code, $count, $Cost);
        } else {
            # 盈利金额=总价-参考成本价*数量-过户费-印花税-佣金
            # $return_money = $trade_money - $position_res['ck_price'] * $count - $Transfer - $stamp - $commission;
            //该处为2023年7月7日之前的算法
            $return_money = $trade_money - $buy_average_price * $count - $Transfer - $stamp - $commission;
            //这里是优化之前的算法  2023年7月7日之后的算法
            //       $return_money = $trade_money - $buy_average_price * $count ;
            //       printlog($type,"账户类型：实盘/虚拟盘   type = 2; // 模拟账户回款   type = 4; // 实盘账户",'Borrow');
            $ret = $moneymodel->up_moneylog($sub_id, $effect_money * 100, $type, $return_money * 100, '', $code, $count, $Cost);
        }
        if (!$ret) {
            Db::rollback();
            return [
              'status' => 0,
              'message' => "交易失败"
            ];
        }
        $lid = $broker['lid'];
        $user = $broker['user'];
        $soure = $broker['stockjobber'];
        $Trust_no = mt_rand(101010, 999999) . substr(time(), 1);
        # 添加卖出模拟持仓记录
        $pos_res = $position->sell_m_position($stockinfo, $count, $price, $sub_id, $model, $Trust_no, $commission, $Transfer, $stamp);
        if (!$pos_res) {
            Db::rollback();
            return [
              'status' => 0,
              'message' => "交易失败"
            ];
        }
        # 存储模拟卖出委托记录
        $Trust = new Trust();
        $Trust_res = $Trust->sell_m_trust($stockinfo, $count, $price, $sub_id, $lid, $user, $soure, $Trust_no, $broker, $model);
        if (!$Trust_res) {
            Db::rollback();
            return [
              'status' => 0, 'message' => "交易失败"];
        }
        #存储模拟卖出成交记录
        $deal_stack = new Deal_stock();
        $deal_res = $deal_stack->sell_m_deal_stock($stockinfo, $count, $price, $sub_id, $lid, $user, $soure, $Trust_no, $model);
        if (!$deal_res) {
            Db::rollback();
            return ['status' => 0, 'message' => "交易失败"];
        }
        $avail = $moneyinfo["avail"] / 100 + $effect_money;
        #添加卖出模拟交割单记录
        $pos_res = $Delivery->sell_m_delivery_order($stockinfo, $count, $price, $buy_average_price, $sub_id, $lid, $user, $soure, $commission, $Transfer, $Trust_no, $stamp, $avail, $amount, $model, $sys, $fees);
        if (!$pos_res) {
            Db::rollback();
            return ['status' => 0, 'message' => "交易失败"];
        }
        if ($broker['broker'] != -1) {
            $p_data = [];
            if (self::config('web_real_api') == 1) {
                $p_data = gs('queryTradeData', [$broker['id'], 2]);
            }
            if (self::config('web_real_api') == 2) {
                $p_data = Grid::grid_category_stock($broker['id']);
            }
            if (!$p_data) {
                Db::rollback();
                return ['status' => 0, 'message' => '交易接口错误，无法成交'];
            }
            unset($p_data[0]);
            $p_res = null;
            foreach ($p_data as $k => $v) {
                if ($v[0] == $code) {
                    $p_res = $p_data[$k];
                    break;
                }
            }
            if (empty($p_res)) {
                Db::rollback();
                return ['status' => 0, 'message' => '不存在的股票持仓!'];
            }
            if ($p_res[4] < $count) {
                Db::rollback();
                return ['status' => 0, 'message' => '可卖股票数量不足!'];
            }
            $trade_id = $broker['id'];
            if (self::config('web_real_api') == 1) {
                $otype = 2;//1买入 2卖出
                if ($model == 1) {
                    $ptype = 1;//1、限价委托
                } else {
                    $ptype = 5;//5市价委托(上海五档即成剩撤/ 深圳五档即成剩撤)
                }
                $data = gs('commitOrder', [$trade_id, $code, $count, $etype, $otype, $ptype, $price]);
            }
            if (self::config('web_real_api') == 2) {
                $otype = 1;//0->买入 1->卖出 2->融资买入 3->融券卖出 4->买券还券 5->卖券还款 6->现券还券 7->担保品买入 8->担保品卖出
                if ($model == 1) {
                    $ptype = 0;//0、限价委托
                } else {
                    $ptype = 4;//0->上海限价委托 深圳限价委托1->市价委托（深圳对方最优价格）2->市价委托（深圳本方最优价格）3->市价委托（深圳即时成交剩余撤销）
                    //4->市价委托（上海五档即成剩撤 深圳五档即成剩撤）5->市价委托（深圳全额成交或撤销）6->市价委托（上海五档即成转限价）
                }
                $data = Grid::grid_order($otype, $ptype, $code, $price, $count, $trade_id);
            }
            if (isset($data['error'])) {
                Db::rollback();
                return ['status' => 0, 'message' => $data['error']];
            }
            if (isset($data['ErrInfo'])) {
                Db::rollback();
                return ['status' => 0, 'message' => $data['ErrInfo']];
            }
        }
        Db::commit();
        return ['status' => 1, 'message' => '卖出委托已提交'];
    }

    //命令行下返回参数
    public static function config($parm)
    {
        $name = Db::name("admin_config")->where(["name" => $parm])->value("value");
        if (is_numeric($name)) {
            return $name;
        }
        $name = strtolower($name);
        $name = str_replace(array("\r\n", "\r", "\n"), ",", $name);
        $name = explode(',', $name);
        $res = [];
        foreach ($name as $k => $v) {
            $tmd = explode(':', $v);
            $res[$k + 1] = $tmd[1];
        }
        return $res;
    }

    /***分配佣金**/
    public function agentToRateMoney($mid, $borrowId, $money, $type_d = 1)
    {
        $memberInfo = MemberModel::getMemberInfoByID($mid);//配资用户信息
        //$arr = self::getFarAgentArr($mid);//上级代理列表 进行查询数据进行分配佣金
        $rate_c = config('agent_back_rate');
        $rate_u = config('member_back_rate');
        if ($memberInfo['agent_far']) {//如果有邀请用户则执行分佣
            $info = MemberModel::getMemberInfoByID($memberInfo['agent_far']);//上级用户
            $rate = $info['agent_rate'];
            $agent_level = $info['agent_id'];
            if ($agent_level == 1) {//上级代理情况分析
                //一级搭理分配钱数 to_money_1
                $to_money_1 = round($money * $rate / 100, 4);
                self::sendRebate_toAgent($info['id'], $borrowId, $memberInfo, $to_money_1, $rate, $money, $type_d);
            } elseif ($agent_level == 2) {
                $info_f = MemberModel::getMemberInfoByID($info['agent_far']);//一级代理信息
                $rate_1 = $info_f['agent_rate'];//一级代理信息
                $to_money_1 = round($money * $rate_1 / 100, 4);//一级代理应返金额
                $to_money_2 = round($money * $rate_1 * $rate / 10000, 4);
                $rate_w = $rate_1 * $rate / 100;
                $to_money_1_a = round(($to_money_1 - $to_money_2), 4);
                self::sendRebate_toAgent($info['id'], $borrowId, $memberInfo, $to_money_2, $rate_w, $money, $type_d);//二级代理返佣
                self::sendRebate_toAgent($info_f['id'], $borrowId, $info, $to_money_1_a, $rate_1, $money, $type_d);//一级代理返佣
            } elseif ($agent_level == 3) {
                $info_f = MemberModel::getMemberInfoByID($info['agent_far']);//二级代理信息
                $info_f_1 = MemberModel::getMemberInfoByID($info_f['agent_far']);//一级代理信息
                $rate_2 = $info_f['agent_rate'];//二级返佣比例
                $rate_1 = $info_f_1['agent_rate'];//一级返佣比例
                $to_money_1 = round($money * $rate_1 / 100, 4);//一级代理应返金额
                $to_money_2 = round($money * $rate_2 * $rate_1 / 10000, 4);//二级理应返金额
                $to_money_3 = round($money * $rate_2 * $rate_1 * $rate / 1000000, 4);//三级理应返金额
                $rate_w = $rate_1 * $rate_2 / 100;//2
                $rate_w_3 = $rate_1 * $rate_2 * $rate / 10000;//2
                $to_money_1_a = round(($to_money_1 - $to_money_2), 4);
                $to_money_2_a = round(($to_money_2 - $to_money_3), 4);
                self::sendRebate_toAgent($info['id'], $borrowId, $memberInfo, $to_money_3, $rate_w_3, $money, $type_d);//三级代理返佣
                self::sendRebate_toAgent($info_f['id'], $borrowId, $info, $to_money_2_a, $rate_w, $money, $type_d);//二级代理返佣
                self::sendRebate_toAgent($info_f_1['id'], $borrowId, $info_f, $to_money_1_a, $rate_1, $money, $type_d);//一级代理返佣
            } else {//上级是普通用户
                $time = time();
                $rate = $rate_u;
                $rate_time = config('member_back_time') ? config('member_back_time') : 0;
                $agent_time = $memberInfo['create_time'] + ($rate_time * 86400 * 30);
                if ($agent_time > $time) {
                    if (!$rate) $rate = $rate ? $rate : $rate_u;//理想状态下 只有一级代理可能不填比例值
                    $to_money_u = round($money * $rate / 100, 4);
                    self::sendRebate_toAgent($info['id'], $borrowId, $memberInfo, $to_money_u, $rate, $money, $type_d);
                }
            }
        }
        /*
         * end
         */
//		if(is_array($arr)){
//
//			$info_1= MemberModel::getMemberInfoByID($arr[0]);//代理用户信息 15
//            $rate_1 = $info_1['agent_rate'];
//            if(!$info_1['agent_id']) $rate_1 = $rate_u;
//
//			if(!$rate_1) $rate_1 = $rate_1 ? $rate_1 : $rate_c;//理想状态下 只有一级代理可能不填比例值
//            //1 2 3
//            foreach($arr as $k => $v){
//				if(!$v) continue;
//				if($k > 2) break;
//
//				$info= MemberModel::getMemberInfoByID($v);//代理用户信息
//				$rate = $info['agent_rate'];
//				$agent_level = $info['agent_id'];
//				$to_money_1 = round($money * $rate_1 / 100,4);//一级代理分配钱数
//
//                if($agent_level ==1){
//					//一级搭理分配钱数 to_money_1
//                    self::sendRebate_toAgent($v,$borrowId,$memberInfo,$to_money_1,$rate_1,$money,$type_d);
//				}elseif($agent_level ==2){
//					//二级代理分配钱数 20 * 80
//					$to_money_2 =  round($money * $rate_1 * $rate / 10000,4);
//					$rate_w = $rate_1 * $rate / 100;
//					self::sendRebate_toAgent($v,$borrowId,$memberInfo,$to_money_2,$rate_w,$money,$type_d);
//				}elseif($agent_level ==3){
//					$agent_2_rate = self::getAgent_Rate($v);
//					$to_money_3 = round($money * $rate_1 * $agent_2_rate * $rate/1000000,4);
//					$rate_w = $rate_1 * $agent_2_rate * $rate /10000;
//					self::sendRebate_toAgent($v,$borrowId,$memberInfo,$to_money_3,$rate_w,$money,$type_d);
//				}else{
//					$time = time();
//					$rate = $info['agent_rate'];
//					$rate_time = config('member_back_time') ? config('member_back_time') : 0;
//					$agent_time = $info['create_time'] + ($rate_time * 604800 * 4);
//					if($agent_time > $time){
//						if(!$rate) $rate = $rate ? $rate : $rate_u;//理想状态下 只有一级代理可能不填比例值
//						$to_money_u = round($money * $rate / 100,4);
//						self::sendRebate_toAgent($v,$borrowId,$memberInfo,$to_money_u,$rate,$money,$type_d);
//					}
//				}
//			}
//		}
        //return true;
    }

    //返回代理数组
    protected function getFarAgentArr($mid)
    {
        $arr = array();
        $agent3 = $this->getFarAgent($mid);
        $agent2 = false;
        $agent1 = false;
        if ($agent3) {
            array_push($arr, $agent3);
            $info = $this->getFarAgentInfo($agent3);
            if ($info['agent_id']) $agent2 = $this->getFarAgent($agent3);
        }
        if ($agent2) {
            array_push($arr, $agent2);
            $info_1 = $this->getFarAgentInfo($agent3);
            if ($info_1['agent_id']) $agent1 = $this->getFarAgent($agent2);
        }
        if ($agent1) {
            array_push($arr, $agent1);
        }
        $aff = array_reverse($arr);
        return $aff;
    }

    //返回代理数组
    protected function getFarAgentArrRevers($mid)
    {
        $arr = array();
        $agent3 = $this->getFarAgent($mid);
        $agent2 = false;
        $agent1 = false;
        if ($agent3) {
            array_push($arr, $agent3);
            $info = $this->getFarAgentInfo($agent3);
            if ($info['agent_id']) $agent2 = $this->getFarAgent($agent3);
        }
        if ($agent2) {
            array_push($arr, $agent2);
            $info_1 = $this->getFarAgentInfo($agent3);
            if ($info_1['agent_id']) $agent1 = $this->getFarAgent($agent2);
        }
        if ($agent1) {
            array_push($arr, $agent1);
        }
        //$aff = array_reverse($arr);
        return $arr;
    }

    //返回一级代理rate
    protected function getAgent_Rate($mid)
    {
        $info = MemberModel::getMemberInfoByID($mid);//代理用户信息
        $user = MemberModel::getMemberInfoByID($info['agent_far']);//代理用户信息
        return $user['agent_rate'];
    }

    //上级代理id
    protected function getFarAgent($mid)
    {
        //遍历下表查询用户代理情况 或邀请用户情况 放进数组
        $user = Db::name("member")->field('id,agent_far')->where(["id" => $mid])->find();
        $agentFar = $user['agent_far'];//上级代理id
        return $agentFar;
    }

    //上级代理信息
    protected function getFarAgentInfo($mid)
    {
        $user = Db::name("member")->field('id,agent_far,agent_rate,agent_id')->where(["id" => $mid])->find();
        return $user;
    }

    /*
     * 发送返利给推荐人
     * 返利金额
     * @param $recommender 推荐人id
     * @param $borrowId 配资ID
     * @param $memberInfo 会员信息及资金信息数据集
     * @param $amount 配资金额
     * @return true 无论是否成功，都返回true，不影响后续操作
     */
    public function sendRebate_toAgent($recommender, $borrowId, $memberInfo, $amount, $rate, $money_a, $type_d = 1)
    {
        $result = false;
        if ($type_d == 2) {
            $borrowInfo = Db::name('stock_addfinancing')->where(['id' => $borrowId])->find();
            $type_info = '扩大配资成功，配资管理费';
            $borrowInfo_money = $borrowInfo['borrow_interest'] / 100;
            $borrowInfo_member_id = $borrowInfo['uid'];
            $amount = $amount / 100;
        } elseif ($type_d == 3) {
            $type_info = '申请续期成功，配资管理费';
            $borrowInfo = Db::name('stock_renewal')->where(['id' => $borrowId])->find();
            $borrowInfo_money = $borrowInfo['borrow_fee'] / 100;
            $borrowInfo_member_id = $borrowInfo['uid'];
            $money_a = $money_a / 100;
            $amount = $amount / 100;
        } elseif ($type_d == 4) {
            $type_info = '自动续期成功，配资管理费';
            $borrowInfo = Db::name('stock_borrow')->where(['id' => $borrowId])->find();
            $borrowInfo_money = $money_a;
            $borrowInfo_member_id = $borrowInfo['member_id'];
            $money_a = $money_a / 100;
            $amount = $amount / 100;
        } else {
            $borrowInfo = self::get($borrowId);
            $type_info = '配资成功，配资管理费';
            $borrowInfo_money = $borrowInfo['borrow_interest'] / 100;
            $borrowInfo_member_id = $borrowInfo['member_id'];
        }
        //备注说明信息
        $info = "您推荐的用户" . $memberInfo['mobile'] . "(" . $memberInfo['name'] . ")" . $type_info . "：" . ($borrowInfo_money) . "元，您获得返利： " . ($amount * 100) . "元.";
        // 启动事务
        Db::startTrans();
        try {
            ////将返佣插入推荐人返佣资金记录表
            $dataRecord['mid'] = $recommender;//$investor;
            $dataRecord['money'] = $amount * 100;
            $dataRecord['remark'] = $info;
            $dataRecord['create_time'] = time();
            $record = Db::name('member_invitation_record')->insert($dataRecord);
            $money_info = Money::getMoney($recommender);
            if (isset($money_info['account'])) {
                $mmoney['account'] = $money_info['account'] + $amount * 10000;
                $money_res = Money::money_up($recommender, $mmoney);
            } else {
                $mmoney['account'] = $money_info['account'];
                $money_res = false;
            }
            //资金记录
            $RecordModel = new RecordModel();
//            $moneyRecord = $RecordModel->saveData($recommender, $amount * 10000, $mmoney['account'], 10, $info);
            $obj = ['affect' => $amount * 10000, 'account' => $mmoney['account'], 'affect_activity' => 0, 'activity_account' => $money_info['activity_account'], 'sn' => ''];
            $moneyRecord = $RecordModel->saveData($recommender, $amount, $mmoney['account'], 10, $info, '', '', $obj);
            # file_put_contents('agent_far.txt', date('Y-m-d H:i:s') . "\r\n", FILE_APPEND);
            //将返佣插入资金记录	($mid,$affect_mid,$affect, $info='')
            $infos = "您推荐的用户" . $memberInfo['mobile'] . "(" . $memberInfo['name'] . ")" . $type_info . "：" . ($borrowInfo_money) . "元，您获得返利：" . ($amount * 100) . "元.";
            $RecordAgentModel = new RecordAgentModel();
            $moneyRecordAgent = $RecordAgentModel->saveData($recommender, $borrowInfo_member_id, $amount * 100, $infos, $memberInfo['mobile'], $rate, $money_a * 100);
            if ($record && $moneyRecord && $money_res && $moneyRecordAgent) {
                $result = true;
                // 提交事务
                Db::commit();
            }
        } catch (\Exception $e) {
            $result = false;
            // 回滚事务
            Db::rollback();
        }
        return $result;
    }


    /**
     * 自动审核配资
     *
     * @return string
     */
    /***
     * 自动审核配资
     * @param $id 添加扩大配资的ID
     * @return string
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public static function addfinancing($id)
    {
        $status = 1;
        $Addf_Model = new AddfinancingModel();
        $info = AddfinancingModel::getAddfinancingById($id);
        $ret = $Addf_Model->saveAddfinancing($id, $status, $info);
        if ($ret['status'] == '1' && $status == "1") {
            //配资类型  1:按天配资 2:按周配资 3:按月配资 4:试用配资 5:免息配资
            $data = [];
            switch ($info['type']) {
                case 1:
                    $config = day_config();
                    $data['multiple'] = $info['multiple']; // 倍率
                    $data['deposit_money'] = $info['money'] + $info['deposit_money']; //扩大配资后的保证金
                    $loss_warn = $config['day_loss'][0]; // 预警线
                    $loss_close = $config['day_loss'][1]; // 止损线
                    $data['borrow_money'] = $info['money'] * $info['multiple'] + $info['borrow_money']; //扩大后的总配资金额
                    $data['loss_warn'] = ($data['deposit_money'] + $data['borrow_money']) * ($loss_warn / 100); //扩大后预警线
                    $data['loss_close'] = ($data['deposit_money'] + $data['borrow_money']) * ($loss_close / 100); //扩大后平仓线
                    $data['rate'] = $config['day_rate'][$data['multiple']]; //系统天利率
                    $data['borrow_interest'] = self::interest($info['type'], $data['rate'], $data['borrow_money'], $info['borrow_duration']); //扩大后的管理费
                    break;
                case 2:
                    $config = week_config();
                    $data['multiple'] = $info['multiple']; // 倍率
                    $data['deposit_money'] = $info['money'] + $info['deposit_money']; //扩大配资后的保证金
                    $loss_warn = $config['week_loss'][0]; // 预警线
                    $loss_close = $config['week_loss'][1]; // 止损线
                    $data['borrow_money'] = $info['money'] * $info['multiple'] + $info['borrow_money']; //扩大后的总配资金额
                    $data['loss_warn'] = ($data['deposit_money'] + $data['borrow_money']) * ($loss_warn / 100); //扩大后预警线
                    $data['loss_close'] = ($data['deposit_money'] + $data['borrow_money']) * ($loss_close / 100); //扩大后平仓线
                    $data['rate'] = $config['week_rate'][$data['multiple']]; //系统天利率
                    $data['borrow_interest'] = self::interest($info['type'], $data['rate'], $data['borrow_money'], $info['borrow_duration']); //扩大后的管理费
                    break;
                case 3:
                    $config = month_config();
                    $data['multiple'] = $info['multiple']; // 倍率
                    $data['deposit_money'] = $info['money'] + $info['deposit_money']; //扩大配资后的保证金
                    $loss_warn = $config['month_loss'][0]; // 预警线
                    $loss_close = $config['month_loss'][1]; // 止损线
                    $data['borrow_money'] = $info['money'] * $info['multiple'] + $info['borrow_money']; //扩大后的总配资金额
                    $data['loss_warn'] = ($data['deposit_money'] + $data['borrow_money']) * ($loss_warn / 100); //扩大后预警线
                    $data['loss_close'] = ($data['deposit_money'] + $data['borrow_money']) * ($loss_close / 100); //扩大后平仓线
                    $data['rate'] = $config['month_rate'][$data['multiple']]; //系统天利率
                    $data['borrow_interest'] = self::interest($info['type'], $data['rate'], $data['borrow_money'], $info['borrow_duration']); //扩大后的管理费
                    break;
                default:
                    return false;
            }
            $result = Db::name('stock_borrow')->where(['id' => $info['borrow_id']])->update($data);
            //根据佣金比例分配佣金 用户id 配资id 配资管理费
            if ($result) {
                if ($status) {
                    if ($data['borrow_interest']) $res_agent = self::agentToRateMoney($info['uid'], $info['id'], $info['borrow_interest'] / 100, 2);
                    return true;
                }
            }
        } elseif ($ret['status'] == '0') {
            return false;
        } else {
            return true;
        }
    }

    /***
     * 自动追加保证金
     * @param $id 添加追加保证金记录的ID
     * @return string
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public static function addmoney($id)
    {
        $status = 1;
        $info = AddmoneyModel::getAddmoneyById($id);
        $ret = AddmoneyModel::saveAddmoney($id, $status, $info);
        if ($ret['status'] === '1') {
            return true;
        } elseif ($ret['status'] === '0') {
            return false;
        }
    }

    /**
     *
     * 自动审核合约续期
     * @param $id
     * @return bool|void
     */
    public static function renewal($id)
    {
        $status = 1;
        $info = RenewalModel::getRenewalById($id);
        $ret = RenewalModel::saveRenewal($id, $status, $info);
        if ($ret['status'] === '1') {
            //根据佣金比例分配佣金 用户id 配资id 配资管理费
            if ($info['borrow_fee']) {
                self::agentToRateMoney($info['uid'], $info['id'], $info['borrow_fee'] / 100, 3);
            }
            return true;
        } elseif ($ret['status'] === '0') {
            return false;
        }
    }
}

?>