<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | @author 伊冰华 <2851336094@qq.com>
// +----------------------------------------------------------------------
namespace app\fund\home;

use app\cms\model\Advert as AdvertModel;
use app\common\controller\Common;
use app\fund\model\Fund as FundModel;
use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundIncome;
use app\fund\model\FundOrder;
use app\fund\model\FundOrderGs;
use app\fund\model\FundSalaryLog;
use app\fund\model\FundUserlevel;
use app\fund\model\FundUserlevel as FundUserlevelModel;
use app\fund\model\FundViptrade as FundViptradeModel;
use app\fund\model\Trader;
use app\fund\model\Trader as TraderModel;
use app\fund\model\TraderOrder;
use app\member\model\Member;
use app\money\model\Money;
use app\money\model\Money as MoneyModel;
use app\money\model\Recharge;
use app\money\model\Record;
use think\Db;
use think\db\Where;
use think\helper\Hash;
use think\facade\Lang;

class Fund extends Common
{
    protected $request;
    protected $status_txt = ['0' => '未确认(申请中)', '1' => '已确认(优投中)', '3' => '已结束', '4' => '已撤销', '5' => '已驳回', '6' => '持仓中'];
    protected $level_array = [];

    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function initialize()
    {// 系统开关
        if (!config('web_site_status')) {
            ajaxmsg('站点已经关闭，请稍后访问', 0, '', true, ['msgCode' => 'L0015']);
        }
        $req   = request();
        $token = input("token");
        defined('MID') or define('MID', isLogin($token));
        if (!MID) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);

        $this->level_array = Funduserlevelarray();
    }

    //获取列表
    public function getListData()
    {
        $page             = input('page', 1);
        $cate_id          = input('cate_id', '');
        $sort             = input('sort', '');
        $map              = [];
        $map['status']    = 1;
        $map['fund_type'] = 1;
        if ($cate_id) {
            $map['cate_id'] = $cate_id;
        }
        $order        = 'id desc';
        $list         = FundModel::getList($page, $map, $order);
        $data         = [];
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '基金列表'
        ));
    }

    public function getDetail()
    {
        $id           = input('id');
        $field        = '*';
        $form         = FundModel::getDetailData($id);
        $uid          = MID;
        $map          = [];
        $map['uid']   = $uid;
        $fund_collect = Db('fund_collect')->where($map)->find();
        $data         = [];
        if (!$fund_collect) {
            $data['is_collect'] = 0;
        }
        $fund_ids_array = $fund_collect['fund_ids'] ? json_decode($fund_collect['fund_ids'], true) : [];
        if (in_array($id, $fund_ids_array)) {
            $data['is_collect'] = 1;
        } else {
            $data['is_collect'] = 0;
        }
        $data['form'] = $form ? $form : [];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '基金详情'
        ));
    }

    /**
     * 基金优投详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCollectDetail()
    {
        $id           = input('fund_id');
        $field        = '*';
        $form         = FundModel::getDetailData($id);
        $uid          = MID;
        $map          = [];
        $map['uid']   = ['eq', $uid];
        $fund_collect = Db('fund_collect')->where($map)->find();
        $data         = [];
        if (!$fund_collect) {
            $data['is_collect'] = 0;
        }
        $fund_ids_array = json_decode($fund_collect['fund_ids'], true) ?: [];
        if (in_array($id, $fund_ids_array)) {
            $data['is_collect'] = 1;
        } else {
            $data['is_collect'] = 0;
        }
        $data['form'] = isset($form['id']) ? $form->toArray() : [];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '基金详情'
        ));
    }

    /**
     * 股市优投详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCollectDetailGs()
    {
        $id   = input('id');
        $form = FundOrderGs::getDetailData($id);
        switch ($form['order_type']):
            case 1:
                $form['ordertypetxt'] = '普通订单';
                break;
            case 2:
                $form['ordertypetxt'] = '优投';
                break;
            case 3:
                $form['ordertypetxt'] = 'vip优投';
                break;
        endswitch;
        $uid          = MID;
        $map          = [];
        $map['uid']   = $uid;
        $fund_collect = Db('fund_collect')->where($map)->find();
        $data         = [];
        if (!$fund_collect) {
            $data['is_collect'] = 0;
        } else {
            $fund_ids_array = json_decode($fund_collect['fund_ids'], true);
            if (in_array($id, $fund_ids_array)) {
                $data['is_collect'] = 1;
            } else {
                $data['is_collect'] = 0;
            }
        }
        $data['form']               = isset($form['id']) ? $form->toArray() : [];
        $data['form']['trader']     = TraderModel::where('id', $form['trader_id'])->value('name');
        $data['form']['status_txt'] = $this->status_txt[$form['status']];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '基金详情'
        ));
    }

    public function getCateList()
    {
        $list         = Db::name('fund_cate')->select();
        $data         = [];
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '基金分类'
        ));
    }

    //收藏
    public function appendCollect()
    {
        $uid             = MID;
        $fund_id         = input('fund_id', '');
        $data['uid']     = $uid;
        $data['fund_id'] = $fund_id;
        $map             = [];
        $map['uid']      = $uid;
        if (!$fund_id) {
            ajaxmsg('没有选择基金', 0);
        }
        if (!FundModel::where('id', $fund_id)->count()) {
            ajaxmsg('基金不存在', 0);
        }
        if (!Db('fund_collect')->where($map)->count()) {
            $data['fund_ids'] = json_encode([$data['fund_id']]);
            Db('fund_collect')->insert($data);
            ajaxmsg('加入成功', 1);
        } else {
            $fund_collect   = Db('fund_collect')->where($map)->find();
            $fund_ids_array = json_decode($fund_collect['fund_ids'], true);
            if (!in_array($fund_id, $fund_ids_array)) {
                $fund_ids_array[]        = $fund_id;
                $update_data['fund_ids'] = json_encode($fund_ids_array);
                Db('fund_collect')->where($map)->update($update_data);
            }
            ajaxmsg('加入成功', 1);
        }
    }

    //取消收藏
    public function closeCollect()
    {
        $uid          = MID;
        $fund_id      = input('fund_id');//基金id
        $map          = [];
        $map['uid']   = $uid;
        $fund_collect = Db('fund_collect')->where($map)->find();
        if (!$fund_collect) {
            ajaxmsg('无收藏记录', 0);
        }
        $fund_ids_array = json_decode($fund_collect['fund_ids'], true);
        foreach ($fund_ids_array as $key => $value) {
            if ($value == $fund_id) {
                unset($fund_ids_array[$key]);
            }
        }
        $fund_ids_array          = array_merge($fund_ids_array);
        $fund_ids_json           = json_encode($fund_ids_array);
        $update_data['fund_ids'] = $fund_ids_json;
        if (Db('fund_collect')->where($map)->update($update_data)) {
            ajaxmsg('取消成功', 1);
        } else {
            ajaxmsg('取消失败', 0);
        }
    }

    //获取基金收藏列表
    public function getCollectList()
    {
        $uid            = MID;
        $map            = [];
        $map['uid']     = $uid;
        $fund_collect   = Db('fund_collect')->where($map)->find();
        $fund_ids_array = json_decode($fund_collect['fund_ids'], true);
        $list           = [];
        $page           = input('page', 1);
        $order          = 'id desc';
        $map            = [];
        if ($fund_ids_array) {
            $map[] = ['id', 'in', $fund_ids_array];
            $list  = FundModel::getList($page, $map, $order);
        }
        $data         = [];
        $data['list'] = $list;
        ajaxmsg('收藏列表', 1, $data);
    }

    //购买基金订单
    public function buy()
    {
        $uid          = MID;
        $user_balance = Money::getMoney($uid);
        $balance      = $user_balance['account'] / 100;//转为元
        $user         = Member::where('id', $uid)->find();
        $agent_id     = $user['agent_far'];
        $level        = $user['level'];
        $order_sn     = $uid . date('YmdHis');
        $fund_id      = input('fund_id');
        $money        = input('money');
        $order_type   = input('order_type', '1');//订单类型（1=普通订单，2=优投，3=vip优投）
        $trader_id    = input('trader_id', '');  //优投师id
        $cycle        = input('cycle', '');      //vip优投周期
        $divide_into  = 0;
        if (!$fund_id) {
            ajaxmsg('请选择要购买的基金', 0);
        }
        if ($money <= 0) {
            ajaxmsg('买入金额不能小于0', 0);
        }
        if (!preg_match("/^[1-9][0-9]*$/", $money)) {
            ajaxmsg('买入金额必须是正整数', 0);
        }
        $fund_type = config('fund_type');//1=分销，2=优投
        if ($order_type == 2 || $order_type == 3) {
            if (!$trader_id) {
                ajaxmsg('请选择优投师', 0);
            }
            $divide_into = Trader::where('id', $trader_id)->value('divide_into');//手续费
            if ($order_type == 3) {
                $divide_into = input('divide_into');
            }
            if ($money % 100) { //必须是100的整数倍
                ajaxmsg('优投金额必须是100的整数倍', 0);
            }
            $order = 'level asc';
            $field = '*';
            // 数据列表
            $user_level_list = FundUserlevelModel::field($field)->order($order)->select();
            if ($order_type == 2) { //普通优投
                foreach ($user_level_list as $v) {
                    if ($v['level'] == $level) {
                        if ($money < $v['min_money'] && $money > $v['max_money']) {
                            ajaxmsg('优投金额不能低于' . $v['min_money'] . '元且不能大于' . $v['max_money'] . '元', 0);
                        }
                    }
                }
//                if($level==0 && $money < 100 && $money>10000){
//                    ajaxmsg('优投金额不能低于100元且不能大于10000元', 0);
//                }elseif($level==1 && $money < 1000 && $money>20000){
//                    ajaxmsg('优投金额不能低于1000元且不能大于20000元', 0);
//                }elseif($level==2 && $money < 1000 && $money>40000){
//                    ajaxmsg('优投金额不能低于1000元且不能大于40000元', 0);
//                }elseif($level==3 && $money < 1000 && $money>70000){
//                    ajaxmsg('优投金额不能低于1000元且不能大于70000元', 0);
//                }elseif($level==4 && $money < 1000 && $money>100000){
//                    ajaxmsg('优投金额不能低于1000元且不能大于100000元', 0);
//                }elseif($level==5 && $money < 1000 && $money>150000){
//                    ajaxmsg('优投金额不能低于1000元且不能大于150000元', 0);
//                }
                $map               = [];
                $map['id']         = ['eq', $fund_id];
                $map['uid']        = ['eq', $uid];
                $map['is_sell']    = ['eq', 1];
                $map['order_type'] = ['eq', $order_type];
//                $count = FundOrder::where($map) -> count();
//                if($count){
//                    ajaxmsg('你还有一条每日优投未完成', 0);
//                }
            }
            if ($order_type == 3) { //vip优投
                $fund_viptrade_list = Db('fund_viptrade')->select();
                $is_cycle           = 0;
                foreach ($fund_viptrade_list as $key => $value) {
                    if (intval($cycle) == $value['cycle']) {
                        $is_cycle = 1;
                        if ($money < $value['min_money'] || $money > $value['max_money']) {
                            ajaxmsg('优投金额不能低于' . $value['min_money'] . '元且不能大于' . $value['max_money'] . '元', 0);
                        }
                    }
                }
                if ($is_cycle != 1) {
                    ajaxmsg('请选择周期', 0);
                }
            }
        }
        $fund_data        = FundModel::where('id', $fund_id)->find();
        $name             = $fund_data['name'];
        $code             = $fund_data['code'];
        $min_invest_money = $fund_data['min_invest_money'];
        if ($min_invest_money > $money && $order_type == 1) {
            ajaxmsg('该基金' . $min_invest_money . '元起投', 0);
        }
        if ($balance < $money) {
            ajaxmsg('余额不足', 0);
        }
        $confirm_time         = 24 * 3600 + strtotime(date('Y-m-d'));        //确认时间
        $income_time          = (24 + 15) * 3600 + strtotime(date('Y-m-d')); //日次15点后查看收益时间
        $data                 = [];
        $data['name']         = $name;
        $data['fund_id']      = $fund_id;
        $data['money']        = $money;
        $data['balance']      = $money;
        $data['order_sn']     = $order_sn;
        $data['code']         = $code;
        $data['confirm_time'] = $confirm_time;//确认时间
        $data['income_time']  = $income_time; //查看收益时间
        $data['order_type']   = $order_type;
        $data['trader_id']    = $trader_id;
        $data['email']        = $user['email'];
        $data['mobile']       = $user['mobile'];
        $data['type']         = 1;
        $data['uid']          = $uid;
//        $data['remaining_share'] = $data['share_num'];
        $data['commission']           = $divide_into;
        $data['cycle']                = $cycle;
        $data['agent_id']             = $agent_id;
        $day_start_time               = strtotime(date('Y-m-d'));//当天的开始时间戳
        $data['week_settlement_time'] = $day_start_time;         //结算时间先默认下单时的时间，方便结算周工资
        $res_data                     = FundOrder::create($data);
        $res                          = [];
        if ($res_data) {
            $map            = [];
            $map['uid']     = ['eq', $uid];
            $map['fund_id'] = ['eq', $fund_id];
            if (!FundIncome::where($map)->count()) {
                $add_fund_income['name']       = $name;
                $add_fund_income['fund_id']    = $fund_id;
                $add_fund_income['code']       = $code;
                $add_fund_income['uid']        = $uid;
                $add_fund_income['order_type'] = $order_type;
                FundIncome::create($add_fund_income); //添加一条收益
            }
            $res['id'] = $res_data['id'];
            Money::where('mid', $uid)->setDec('account', $money * 100);
            $user_balance = Money::getMoney($uid);
            $account      = $user_balance['account'];
            $info         = '买入基金：' . $name . ',买入金额：' . $money . '元';
//            Record::saveData($uid, $money*100, $account, 86, $info);
            $obj = ['affect' => $money * 100, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
            Record::saveData($uid, $money * 100, $account, 86, $info, '', '', $obj);
            Member::where('id', $uid)->update(['is_buy' => 1]);
            Member::updateUserLevel($uid); //升级
            if ($order_type == 2 || $order_type == 3) {
                FundOrder::recommendeDaward($uid, $name, $fund_id, $res_data['id'], $code, $order_type);//结算合伙人补贴
            }
            if ($fund_type == 1) { //分佣
                FundOrder::settlementBonus($uid, $money, $fund_id, $name, $code, $order_type, $res_data['id']);
            }
            ajaxmsg('买入成功', 1, $res);
        } else {
            ajaxmsg('买入失败', 0);
        }
    }

    //提交股市优投订单
    public function buygs()
    {
        $uid          = MID;
        $user_balance = Money::getMoney($uid);
        $balance      = $user_balance['account'] / 100;//转为元

        $user = Member::where('id', $uid)->find();

        $agent_id   = $user['agent_far'];
        $level      = $user['level'];
        $order_sn   = $uid . date('YmdHis');
        $money      = input('money');
        $order_type = input('order_type', 3);//订单类型（1=普通订单，2=优投，3=vip优投）
        $trader_id  = input('trader_id', '');//优投师id
        $cycle      = input('cycle', 1);     //vip优投周期
        if ($money <= 0) {
            ajaxmsg('买入金额不能小于0', 0);
        }
        if (!preg_match("/^[1-9][0-9]*$/", $money)) {
            ajaxmsg('买入金额必须是正整数', 0);
        }
        if (!$trader_id) {
            ajaxmsg('请选择优投师', 0);
        }
        $Trader      = Trader::where('id', $trader_id)->find();//手续费
        $divide_into = $Trader['divide_into'];
        if ($money % 100) { //必须是100的整数倍
            ajaxmsg('优投金额必须是100的整数倍', 0);
        }
        if ($balance < $money) {
            ajaxmsg('余额不足', 0);
        }
        $FundViptrade = FundViptradeModel::where('traderid', $trader_id)->where('cycle', $cycle)->find();

        $user_level = json_decode($FundViptrade['user_level'], true) ?: [];

        if (!empty($user_level) & !in_array($level, $user_level)) {
            ajaxmsg('当前等级不在优投师的等级列表内', 0);
        };
        $buy_where=[];
        $buy_where[]=['cycle','=',$cycle];
        $buy_where[]=['uid','=',$uid];
        $buy_where[]=['trader_id','=',$trader_id];
        $buy_where[]=['status','in','0,1,6,7'];
        if (FundOrderGs::where($buy_where)->find() ){
            ajaxmsg('已存在有效期内的合约，不能重复购买', 0);
        };


        // 数据列表
        if ($order_type == 2) { //普通优投
//            $min_invest_money = 100;
//            if ($min_invest_money > $money && $order_type == 1) {
//                ajaxmsg('该项目的最低投资金额是' . $min_invest_money, 0);
//            }
            if ($money < $Trader['min_money'] || $money > $Trader['max_money']) {
                ajaxmsg('优投金额不能低于' . $Trader['min_money'] . '元且不能大于' . $Trader['max_money'] . '元', 0);
            }
            //获取用户等级
//            $order = 'level asc';
//            $field = '*';
//            $user_level_list = FundUserlevelModel::field($field)->order($order)->select();
//            foreach ($user_level_list as $v) {
//                if ($v['level'] == $level) {
//                    if ($money < $v['min_money'] && $money > $v['max_money']) {
//                        ajaxmsg('优投金额不能低于' . $v['min_money'] . '元且不能大于' . $v['max_money'] . '元', 0);
//                    }
//                }
//            }
//            按后台定义 yfn
//            $limits = [
//              0 => ['min' => 100, 'max' => 10000, 'msg' => '优投金额不能低于100元且不能大于10000元'],
//              1 => ['min' => 1000, 'max' => 20000, 'msg' => '优投金额不能低于1000元且不能大于20000元'],
//              2 => ['min' => 1000, 'max' => 40000, 'msg' => '优投金额不能低于1000元且不能大于40000元'],
//              3 => ['min' => 1000, 'max' => 70000, 'msg' => '优投金额不能低于1000元且不能大于70000元'],
//              4 => ['min' => 1000, 'max' => 100000, 'msg' => '优投金额不能低于1000元且不能大于100000元'],
//              5 => ['min' => 1000, 'max' => 150000, 'msg' => '优投金额不能低于1000元且不能大于150000元'],
//            ];
//            if (isset($limits[$level])) {
//                $limit = $limits[$level];
//                if ($money < $limit['min'] || $money > $limit['max']) {
//                    ajaxmsg($limit['msg'], 0);
//                }
//            }
            $name = '普通跟投';
        }
        if ($order_type == 3) { //vip优投
            //获取讲师配置信息
            $fund_viptrade_list = Db('fund_viptrade')->where('cycle', $cycle)->where('traderid', $trader_id)->find();

            if (!$fund_viptrade_list) {
                ajaxmsg('请选择周期', 0);
            }
            if ($money < $fund_viptrade_list['min_money'] || $money > $fund_viptrade_list['max_money']) {
                ajaxmsg('优投金额不能低于' . $fund_viptrade_list['min_money'] . '元且不能大于' . $fund_viptrade_list['max_money'] . '元', 0);
            }
            $divide_into = $fund_viptrade_list['commission'];
            $name        = 'VIP跟投(' . $Trader['name'] . ')';
        }
        // 查询大于当前时间的前十条记录，并取第十条
        $record = Db::name('stock_trade_date')
            ->where('date', '>', date('Y-m-d'))
            ->order('date', 'asc')
            ->limit($cycle)
            ->select();
        if (isset($record[count($record) - 1]['date'])) {
            $fundendtime = $record[count($record) - 1]['date'] . ' 23:59:58';
            $fundendtime = strtotime($fundendtime);
        } else {
            $fundendtime = 0;
        }
        if(config('auto_gd_Audit')){
            $status=1;
        }else{
            $status=0;
        }
        //本处应该使用事务机制，但是执行到saveData这个静态方法时，导致事务执行失败
//        try {
//            Db::startTrans();
        $data                  = [];
        $data['money']         = $money;
        $data['uid']           = $uid;
        $data['order_sn']      = $order_sn;
        $data['fundendtime']   = $fundendtime;//合约结束时间
        $data['fund_contract'] = $cycle;      //合约周期
        $data['order_type']    = $order_type;
        $data['trader_id']     = $trader_id;
        $data['email']         = $user['email'];
        $data['mobile']        = $user['mobile'];
        $data['commission']    = $divide_into;
        $data['cycle']         = $cycle;
        $data['status']         = $status;
        $data['agent_id']      = $agent_id;
        $data['confirm_time']      =  time();

        $res_data              = FundOrderGs::create($data);
        if ($res_data) {
            //添加添加收益记录
            $add_fund_income['name']       = $name;
            $add_fund_income['order_id']   = $res_data['id'];
            $add_fund_income['uid']        = $uid;
            $add_fund_income['order_type'] = $order_type;
            $add_fund_income_insert        = FundIncome::create($add_fund_income);
            //结算合伙人补贴
            FundOrderGs::recommendeDaward($uid, $name, $res_data['id'], $order_type);
            $user_balance    = Money::getMoney($uid);
            $freeze_new      = $money * 100;

            //判断是否开启自动审核
            if(config('auto_gd_Audit')){
                Money::where('mid', $uid)->setDec('account', $money * 100);
                $info    = $order_sn . '跟投审核通过,扣除金额' . $money . '元';
                $account = $user_balance['account'];
                $obj = ['affect' => -$freeze_new, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => $order_sn];
                $Record_saveData = Record::saveData($uid, -$freeze_new, $account, 95, $info, '', '', $obj);

            }else{
                $up_money_freeze = bcadd($user_balance['freeze'], $money * 100);
                MoneyModel::money_freeze($uid, $up_money_freeze, $user_balance['account'] - $money * 100);
                $account         = $user_balance['account'];
                $info            = $name . ',跟投,冻结金额' . $money . '元';
                $obj             = ['affect' => -$freeze_new, 'account' => $user_balance['account'] - $money * 100, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
                $Record_saveData = Record::saveData($uid, -$freeze_new, $account, 97, $info, '', '', $obj);

            }





            Member::where('id', $uid)->update(['is_buy' => 1]);
            //Member::updateUserLevel($uid); //升级 合伙人统一升级

            if (
                !empty($add_fund_income_insert) &
                !empty($Record_saveData)
            ) {
//                    Db::commit();
                ajaxmsg('买入成功', 1, ['id' => $res_data['id']]);
            } else {
//                    Db::rollback();
                ajaxmsg('买入失败1', 0);
            }
        } else {
//                Db::rollback();
            ajaxmsg('买入失败2', 0);
        }
//        } catch (Exception $e) {
//            // 捕获异常并回滚事务
//            Db::rollback();
//            return false;
//        }
    }

    //卖出
    public function sell()
    {
        $uid              = MID;
        $fund_id          = input('fund_id');
        $money            = input('money');     //份额
        $order_type       = input('order_type');//1-普通订单，2-优投
        $user             = Member::where('id', $uid)->find();
        $agent_id         = $user['agent_far'];
        $map              = [];
        $map['uid']       = $uid;
        $map['fund_id']   = $fund_id;
        $fund_income_data = FundIncome::where($map)->lock(true)->find();
        if (!$fund_income_data) {
            ajaxmsg('订单不存在', 0);
        }
        if ($money <= 0) {
            ajaxmsg('卖出金额不能小于1', 0);
        }
        $total_money = $fund_income_data['total_money'];
        if ($money > $total_money) {
            ajaxmsg('金额不足', 0);
        }
        $account_time = (24 + 9.5) * 3600 + strtotime(date('Y-m-d'));//第二天9点半到账
        if ($order_type == 2) {
//            if($total_money != $fund_income_data['total_money']){
//                ajaxmsg('每日优投必须全部卖出', 0);
//            }
            $map               = [];
            $map['status']     = 1;
            $map['type']       = 1;
            $map['is_sell']    = 0;
            $map['uid']        = $uid;
            $map['fund_id']    = $fund_id;
            $map['order_type'] = 2; //只卖出每日优投的订单  1=普通订单，2=优投，3=vip优投
            $list              = FundOrder::where($map)->select();
            if (!$list) {
                return;
            }
            foreach ($list as $key => $value) {
                if ($value['cycle'] == $value['settlement_days']) { //结算周期到的订单卖出
                    $id         = $value['id'];
                    $uid        = $value['uid'];
                    $fund_id    = $value['fund_id'];
                    $profit     = $value['balance'] - $value['money'];//盈利部分
                    $name       = $value['name'];
                    $commission = $value['commission'] / 100;//手续费
                    if ($profit > 0) {
                        $profit = $profit * (1 - $commission); //实际收益
                        $bonus  = $value['money'] + $profit;
                        FundOrder::grantWeekBonus($uid, $profit, $value['name'], $value['code'], $fund_id, $value['id'], $value['order_type']);
                    } else {
                        $bonus = $value['balance'];
                    }
                    Db('money')->where('mid', $uid)->setInc('account', $bonus * 100);
                    $user                          = Member::where('id', $uid)->find();
                    $order_sn                      = $uid . date("YmdHis");
                    $order_data['name']            = $name;
                    $order_data['code']            = $value['code'];
                    $order_data['uid']             = $uid;
                    $order_data['type']            = 2;
                    $order_data['money']           = $bonus;
                    $order_data['email']           = $user['email'];
                    $order_data['mobile']          = $user['mobile'];
                    $order_data['fund_id']         = $fund_id;
                    $order_data['order_sn']        = $order_sn;
                    $order_data['status']          = 0;
                    $order_data['account_time']    = $account_time;
                    $order_data['create_time']     = time();
                    $order_data['cycle']           = $value['cycle'];
                    $order_data['settlement_days'] = $value['settlement_days'];
                    $order_data['order_type']      = $value['order_type'];
                    $order_data['commission']      = $value['commission'];
                    $order_data['agent_id']        = $agent_id;
                    $res_data                      = FundOrder::create($order_data);
                    $res['id']                     = $res_data['id'];
                    $update_data                   = [];
                    $update_data['balance']        = 0;
                    $update_data['is_sell']        = 1;
                    FundOrder::where('id', $id)->update($update_data);
                    FundIncome::where('uid', $uid)->where('fund_id', $fund_id)->setDec('total_money', $value['balance']);
                }
            }
            ajaxmsg('卖出成功', 1, $res);
        } else {
            $user                       = Member::where('id', $uid)->find();
            $order_sn                   = $uid . date("YmdHis");
            $order_data['name']         = $fund_income_data['name'];
            $order_data['code']         = $fund_income_data['code'];
            $order_data['uid']          = $uid;
            $order_data['type']         = 2;
            $order_data['money']        = $money;
            $order_data['email']        = $user['email'];
            $order_data['mobile']       = $user['mobile'];
            $order_data['fund_id']      = $fund_id;
            $order_data['order_sn']     = $order_sn;
            $order_data['status']       = 0;
            $order_data['account_time'] = $account_time;
            $order_data['create_time']  = time();
            $order_data['order_type']   = 1;
            $order_data['agent_id']     = $agent_id;
            unset($order_data['id']);
            if ($res_data = FundOrder::create($order_data)) {
                $res['id'] = $res_data['id'];
                FundIncome::where('uid', $uid)->where('fund_id', $fund_id)->setDec('total_money', $money);
                $user_balance = Money::getMoney($uid);
                $account      = $user_balance['account'];
                $info         = '卖出基金：' . $fund_income_data['name'] . ',卖出金额：' . $money . '元';
                Record::saveData($uid, $money * 100, $account, 87, $info);
                ajaxmsg('卖出成功', 1, $res);
            } else {
                ajaxmsg('卖出失败', 0);
            }
        }
    }

    //我的订单列表--基金
    public function getMyOrderList()
    {
        $uid                 = MID;
        $page                = input('page', 1);
        $fund_id             = input('fund_id', '');
        $map                 = [];
        $map['o.uid']        = $uid;
        $map['o.order_type'] = 1;
        if ($fund_id) {
            $map['o.fund_id'] = $fund_id;
        }
        $order              = 'o.id desc';
        $res                = FundOrder::getList2($page, $map, $order);
        $data['statistics'] = $res['statistics'] ?? [];
        $data['list']       = $res['list'] ?? [];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '我的持有'
        ));
    }

    //我的订单列表-股市
    public function getMyOrderListgs()
    {
        $uid                = MID;
        $page               = input('page', 1);
        $map                = [];
        $map['o.uid']       = $uid;
//        $map['o.status']    = 1;
        $map['o.status'] = [1, 6, 7];
        $order              = 'o.id desc';
        $res                = FundOrdergs::getList($page, $map, $order);
        $data['statistics'] = $res['statistics'] ?? [];
        $data['list']       = $res['list'] ?? [];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '我的持有'
        ));
    }

    //我的订单列表
    public function getTradeList()
    {
        $uid        = MID;
        $page       = input('page', 1);
        $type       = input('type', '');
        $order_id   = input('order_id', '');
        $fund_id    = input('fund_id', '');
        $order_type = input('order_type', '');
        $map        = [];
        $map[]      = ['uid', 'eq', $uid];
        if ($order_type) {
            if ($order_type != 1) { //不等于的1都是优投
                $map[] = ['order_type', 'neq', 1];
            } else {
                $map[] = ['order_type', 'eq', $order_type];
            }
        }
        if ($type) {
            $map[] = ['type', '=', $type];
        }
        if ($fund_id) {
            $map[] = ['fund_id', '=', $fund_id];
        }
        $order              = 'id desc';
        $res                = FundOrder::getList($page, $map, $order);
        $data['statistics'] = $res['statistics'] ?? [];
        $data['list']       = $res['list'] ?? [];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '交易记录'
        ));
    }

    //每日订单列表
    public function getTradeListGs()
    {
        $uid               = MID;
        $page              = input('page', 1);
        $order_id          = input('id', '');
        $map               = [];
        $map['o.order_id'] = $order_id;
        $map['o.uid']      = $uid;
        if (config('buy_show_order')) {
            $map['o.status'] = [1, 2, 3];
        } else {
            $map['o.status'] = [2, 3];
        }

        $order              = 'id desc';
        $res                = FundDaylineModel::getList($page, $map, $order);
        $data['statistics'] = $res['statistics'] ?? [];
        $data['list']       = $res['list'] ?? [];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '交易记录'
        ));
    }

    public function getOrderDetail()
    {
        $uid     = MID;
        $id      = input('id', '');
        $fund_id = input('fund_id', '');
        $order   = 'id desc';
        $form    = FundOrder::getDetailData($id);
        if (!isset($form['fund_id'])) {
//            兼容股票模式
            return json(array(
                'data'    => '',
                'status'  => 1,
                'message' => '订单详情'
            ));
        }
        $fund_id          = $fund_id ?: $form['fund_id'];
        $field            = '*,fund_id yesterday_income,fund_id no_confirm_money';
        $map              = [];
        $map['uid']       = $uid;
        $map['fund_id']   = $fund_id;
        $fund_income_data = FundIncome::where($map)->field($field)->find();
        $hold_money       = $fund_income_data['total_money'];
        $no_confirm_money = $fund_income_data['no_confirm_money'];
        $cost             = $fund_income_data['recharge_money'];
        $day_increase     = self::getDayRise($fund_id);
        $yesterday_income = $fund_income_data['yesterday_income'];
        $hold_income      = $fund_income_data['income_money'];
        if ($fund_income_data['recharge_money'] == "0.00" || $fund_income_data['recharge_money'] == "0" || empty($fund_income_data['recharge_money'])) {
            $hold_earning = 0; //收益率 = 收益金额/总买入金额
        } else {
            $hold_earning = ($hold_income / $fund_income_data['recharge_money']) * 100; //收益率 = 收益金额/总买入金额
        }
//        $hold_earning = $hold_income / $fund_income_data['recharge_money'] * 100; //收益率 = 收益金额/总买入金额
        $hold_earning                   = round($hold_earning, 2);
        $statistics['cost']             = $cost ?: 0;            //持仓成本
        $statistics['hold_money']       = $hold_money ?: 0;      //持有金额(总剩余金额)
        $statistics['no_confirm_money'] = $no_confirm_money ?: 0;//待确认金额
        $statistics['hold_income']      = $hold_income ?: 0;     //持有收益
        $statistics['hold_earning']     = $hold_earning ?: 0;    //持有收益率
        $statistics['total_money']      = $hold_money ?: 0;      //总的持有金额
        $statistics['day_increase']     = $day_increase ?: 0;    //日涨幅
        $statistics['yesterday_income'] = $yesterday_income ?: 0;//昨日收益
        $data['statistics']             = $statistics;
        $data['form']                   = $form ? $form->toArray() : [];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '订单详情'
        ));
    }

    /**
     * 股市优投订单详情
     * @return \think\response\Json
     */
    public function getOrderDetailGs()
    {
        $uid   = MID;
        $id    = input('id', '');
        $order = 'id desc';
        $form  = FundOrderGs::getDetailData($id);
        if (!$form) {
            return json(array(
                'data'    => '',
                'status'  => 0,
                'message' => '订单详情'
            ));
        }
        foreach ($form as $k => $value) {
            $value['commission'] = formatNumber($value['commission']);
        }
        $field            = '*,fund_id yesterday_income,fund_id no_confirm_money';
        $map              = [];
        $map['uid']       = $uid;
        $map['order_id']  = $id;
        $fund_income_data = FundIncome::where($map)->field($field)->find();
        $cost             = $fund_income_data['recharge_money'];
        $hold_income      = $fund_income_data['income_money'];
        if ($fund_income_data['recharge_money'] == "0.00" || empty($fund_income_data['recharge_money']) || $fund_income_data['recharge_money'] == 0) {
            $hold_earning = 0; //收益率 = 收益金额/总买入金额
        } else {
            $hold_earning = $hold_income / $fund_income_data['recharge_money'] * 100; //收益率 = 收益金额/总买入金额
        }
        $orderday = FundDaylineModel::where('order_id', $id)
            ->where('uid', $uid)
            ->where('status', '<', 3)
            ->select();
        $money    = 0;
        foreach ($orderday as $v) {
            $money += $v['buy_price'] * $v['num'];
        }
        #可投金额
        $dtje = round(orderbalance($id, 'FundOrderGs'), 2);

        $hold_earning       = round($hold_earning, 2);
        $statistics['cost'] = $cost ?: 0;        //持仓成本
        $statistics['dtje'] = $dtje ?: 0;        //持有金额(总剩余金额)
//    $statistics['hold_income']  = $hold_income ?: 0; //持有收益
//    $statistics['hold_earning'] = $hold_earning ?: 0;//持有收益率

        $statistics['hold_income']  = $form['balance'] ?: 0; //持有收益
        $earning_rate               = round((($form['money'] + $form['balance']) / $form['money'] - 1) * 100, 2);
        $statistics['hold_earning'] = $earning_rate ?: 0;//持有收益率

        $form['initial_money'] = $form['money'] - $form['add_money'];
        $data['statistics']    = $statistics;
        $data['form']          = $form ? $form->toArray() : [];
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '订单详情'
        ));
    }

    /**
     * 股市优投投资记录
     * @return \think\response\Json
     */
    public function Investmentrecords()
    {
        $uid                          = MID;
        $id                           = input('id', '');
        $orderday                     = FundDaylineModel::where('id', $id)
            ->where('uid', $uid)
            ->find();
        $orderday['create_time_text'] = date('Y-m-d', $orderday['create_time']);
        $orderday['buy_time_text']    = date('Y-m-d', strtotime($orderday['buy_time']));
        $orderday['sell_time_text']   = date('Y-m-d', strtotime($orderday['sell_time']));
        if ($orderday['status'] == '1') {

            $orderday['sell_cost']         = '--';
            $orderday['profit']            = '--';//订单利润
            $orderday['commission']        = '--';//给讲师的佣金成本
            $orderday['system_commission'] = '--';//平台佣金
        }
        return json(array(
            'data'    => $orderday,
            'status'  => 1,
            'message' => '订单详情'
        ));
    }

    //获取日涨幅
    public function getDayRise($fund_id)
    {
        $today_date     = date('Y-m-d'); //今天
        $map            = [];
        $map[]          = ['fund_id', '=', $fund_id];
        $day_start_time = strtotime(date('Y-m-d'));//0点之后
        $end_time       = $day_start_time + 15 * 3600;
        if (time() >= $end_time) { //超过下午3点才显示当天的收益率,否则显示昨天的收益率
            $map[]  = ['fund_date', '=', $today_date];
            $income = Db('fund_line')->where($map)->value('price');
        } else {
            $map[]  = ['fund_date', 'lt', $today_date];
            $income = Db('fund_line')->where($map)->order('fund_date desc')->value('price');
        }
        $income = round($income, 2);
        return $income ?: 0.00;
    }

    //获取K线图数据
    public function getFundLine()
    {
        $fund_id        = input('fund_id');
        $day_date       = date('Y-m-d');
        $map            = [];
        $map[]          = ['fund_id', '=', $fund_id];
        $day_start_time = strtotime(date('Y-m-d'));//0点之后
        $end_time       = $day_start_time + 15 * 3600;
        if (time() >= $end_time) {
            $map[] = ['fund_date', 'elt', $day_date];
        } else {
            $map[] = ['fund_date', 'lt', $day_date];
        }
        $order = 'fund_date asc';
        $list  = Db::name('fund_line')->order($order)->where($map)->select();
        $array = [];
        foreach ($list as $key => $value) {
            $fund_time     = strtotime($value['fund_date']);
            $name          = date('Y/m/d', $fund_time);
            $price         = $value['price'];
            $data['name']  = $name;
            $data['value'] = [$name, $price];
            $array[]       = $data;
        }
        $profit_loss                = Db::name('fund_line')->where($map)->sum('price');
        $profit_loss                = round($profit_loss, 2);
        $result_data                = [];
        $result_data['profit_loss'] = $profit_loss;
        $result_data['line_data']   = $array;
        return json(array(
            'data'    => $result_data,
            'status'  => 1,
            'message' => 'K线图数据'
        ));
    }

    //获取操盘师列表
    public function getTraderList()
    {
        $page = input('page', 1);
        $type = input('type', '');
        $keyword = input('keyword', '');
        $map  = [];
        $where  = [];
        if ($type) {
            $map['type'] = $type;
        }
//        0:默认选项卡列表 1:搜索页面
        $trader_list_type = config('trader_list_type');  //操盘师列表类型


        $map['status'] = 1;
        $order         = 'id desc';
        $page_size     = 20;
        $field         = '*,headimgurl headimgurl_text';
        $domain = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];

        if(isset($trader_list_type) && $trader_list_type == 1){
            unset( $map['type'] );
            $list = [];
            $list['total'] = 0;
            $list['per_page'] = 20;
            $list['current_page'] = 1;
            $list['last_page'] = 0;
            $list['data'] = [];
            if ($keyword) {
                $where[] = ['name', 'like', '%' . $keyword . '%'];
                $list          = TraderModel::where($map)->where($where)->order($order)->field($field)->paginate(['page' => $page, 'list_rows' => $page_size]);
                foreach ($list as $k => &$v) {
                    $v['max_money']         = convertToTenThousand($v['max_money']);
                    $v['min_money']         = convertToTenThousand($v['min_money']);
                    if($v['type']==2){
                        $viptrade=FundViptradeModel::where('traderid',$v['id'])->select();
                        // 初始化最大值和最小值
                        $maxValue = PHP_INT_MIN;
                        $minValue = PHP_INT_MAX;

                        // 遍历数组找出最大值和最小值
                        foreach ($viptrade as $item) {
//                            if (isset($item['divide_into'])) {
//                                if ($item['divide_into'] > $maxValue) {
//                                    $maxValue = $item['divide_into'];
//                                }
//                                if ($item['divide_into'] < $minValue) {
//                                    $minValue = $item['divide_into'];
//                                }
//                            }
                            if ($item['commission'] > $maxValue) {
                                $maxValue = $item['commission'];
                            }
                            if ($item['commission'] < $minValue) {
                                $minValue = $item['commission'];
                            }
                        }
//                        $v['divide_into']       = formatNumber($v['$minValue']) . "%-" .formatNumber($v['$maxValue']);
                        if(count($viptrade)>1){
//                            最大最小值相等只显示一个
                            if($minValue == $maxValue){
                                $v['divide_into']       = formatNumber($minValue) . "%";
                            }else{
                                $v['divide_into']       = $minValue. "%-" .$maxValue."%";
                            }
                        }else{
                            $v['divide_into']       = formatNumber($minValue)."%";
                        }
                    }else{
                        $v['divide_into']       = formatNumber($v['divide_into']) . "%";
                    }
                    $v['system_commission'] = config('system_commission') . "%";
                    $v['head_img'] = $v['headimgurl_text'] ?:$domain.'/uploads/default/user.jpg';

                }
            }
        }else{
            $list          = TraderModel::where($map)->where($where)->order($order)->field($field)->paginate(['page' => $page, 'list_rows' => $page_size]);
            foreach ($list as $k => &$v) {
                $v['max_money']         = convertToTenThousand($v['max_money']);
                $v['min_money']         = convertToTenThousand($v['min_money']);
                if($v['type']==2){
                    $viptrade=FundViptradeModel::where('traderid',$v['id'])->select();
                    // 初始化最大值和最小值
                    $maxValue = PHP_INT_MIN;
                    $minValue = PHP_INT_MAX;

                    // 遍历数组找出最大值和最小值
                    foreach ($viptrade as $item) {

                            if ($item['commission'] > $maxValue) {
                                $maxValue = $item['commission'];
                            }
                            if ($item['commission'] < $minValue) {
                                $minValue = $item['commission'];
                            }

                    }
                    if(count($viptrade)>1){
                        //                            最大最小值相等只显示一个
                        if($minValue == $maxValue){
                            $v['divide_into']       = formatNumber($minValue) . "%";
                        }else{
                            $v['divide_into']       = $minValue. "%-" .$maxValue."%";
                        }
                    }else{
                        $v['divide_into']       = formatNumber($minValue)."%";
                    }
                }else{
                    $v['divide_into']       = formatNumber($v['divide_into']) . "%";
                }

                $v['system_commission'] = config('system_commission') . "%";
                $v['head_img'] = $v['headimgurl_text'] ?:$domain.'/uploads/default/user.jpg';

            }
        }
        $data         = [];
        $data['list'] = $list;

        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '操盘师列表'
        ));
    }

    public function getTraderDetail()
    {
        $id           = input('id', '');
        $field        = '*,headimgurl headimgurl_text';
        $form         = TraderModel::where('id', $id)->field($field)->find();
        $form->max_money         = convertToTenThousand($form->max_money);
        $form->min_money         = convertToTenThousand($form->min_money);
        if($form->type==2){
            $viptrade=FundViptradeModel::where('traderid',$form->id)->select();
            // 初始化最大值和最小值
            $maxValue = PHP_INT_MIN;
            $minValue = PHP_INT_MAX;

            // 遍历数组找出最大值和最小值
            foreach ($viptrade as $item) {

                    if ($item['commission'] > $maxValue) {
                        $maxValue = $item['commission'];
                    }
                    if ($item['commission'] < $minValue) {
                        $minValue = $item['commission'];
                    }

            }
            $form->divide_into     =$minValue . "%-" .$maxValue."%";
        }else{
            $form->divide_into  = formatNumber( $form->divide_into) . "%";
        }
        $data         = [];
        $data['form'] = $form;

        $list = TraderOrder::alias('to')
            ->where([
                'to.trader_id' => $id,
            ])
            ->where('to.status','>','0')
            ->order('to.create_time desc')
            ->limit(5)
            ->select()->toArray();
        $domain = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];
        $form['head_img'] = $form['headimgurl_text'] ?:$domain.'/uploads/default/user.jpg';

        foreach ($list as $k => $v) {
            $list[$k]['buy_time'] = date('Y-m-d', strtotime($v['buy_time']));
            if(!empty($v['sell_time'])){
                $list[$k]['sell_time'] = date('Y-m-d', strtotime($v['sell_time']));
                if ($v['buy_price'] != 0 && $v['sell_price'] != 0) {
// 浮动收益 = 卖出价格 - 买入价格 / 买入价格 * 100%
                    $list[$k]['floating_ratio'] = number_format(100 * ($v['sell_price'] - $v['buy_price']) / $v['buy_price'], 2) . '%';
                } else {
                    $list[$k]['floating_ratio'] = '- -';
                }
            }else{
                $list[$k]['floating_ratio'] = '- -';
            }

        }

//        $data['form']['week_ratio'] = FundDaylineModel::alias('fd')
//            ->where([
//                'fd.status'   => 3,
//                'f.trader_id' => $id,
//            ])->leftJoin('fund_order_gs f', 'fd.order_id = f.id')
//            ->whereTime('fd.sell_time', 'week')
//            ->sum('fd.commission_ratio');
//        $data['form']['week_ratio'] = 0;
//        $week_ratio = FundOrderGs::
//        where('trader_id',$id)
//            ->where('status',3)
//            ->whereTime('create_time', 'week')
//            ->select();
//        foreach ( $week_ratio as $key => $value) {
////            收益率 = 本金 + 盈利 / 本金 -1
//            $data['form']['week_ratio'] += round((($value['money'] + $value['balance']) / $value['money'] - 1) * 100, 2);
//        }
//        $data['form']['week_ratio'] = sprintf("%.2f", $data['form']['week_ratio']);

//        $data['form']['monthly_revenue'] = FundDaylineModel::alias('fd')
//            ->where([
//                'fd.status'   => 3,
//                'f.trader_id' => $id,
//            ])->leftJoin('fund_order_gs f', 'fd.order_id = f.id')
//            ->whereTime('fd.sell_time', 'month')
//            ->sum('fd.commission_ratio');
//        $data['form']['monthly_revenue'] = 0;
//        $monthly_revenue = FundOrderGs::
//            where('trader_id',$id)
//            ->where('status',3)
//            ->whereTime('create_time', 'month')
//            ->select();
//        foreach ( $monthly_revenue as $key => $value) {
////            收益率 = 本金 + 盈利 / 本金 -1
//            $data['form']['monthly_revenue'] += round((($value['money'] + $value['balance']) / $value['money'] - 1) * 100, 2);
//        }
//        $data['form']['monthly_revenue'] = sprintf("%.2f", $data['form']['monthly_revenue']);
        $data['list']                = $list;

//        战绩显示开关
        $data['seeting']['trader_record']   = config('trader_record');
        $data['seeting']['trader_detailed'] = config('trader_detailed');
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '操盘师详情'
        ));
    }

//    持仓明细
    //获取操盘师列表
    public function getTraderOrderList()
    {
        $page      = input('page', 1);
        $trader_id = input('trader_id', '');

        $order     = 'to.sell_time desc';
        $page_size = 20;
        $days      = 10;
//    $list      = FundDaylineModel::alias('fd')
//      ->where([
//        'fd.status'   => 3,
//        'f.trader_id' => $trader_id,
//        //                'fd.sell_time', '>=', date('Y-m-d H:i:s', strtotime("-{$days} days")), // 添加最近10天的条件
//      ])->whereTime('fd.sell_time', 'between', [strtotime("-{$days} days"), date('Y-m-d H:i:s')])
//      ->leftJoin('fund_order_gs f', 'fd.order_id = f.id')
//      ->field('fd.stockname,fd.buy_cost,fd.sell_cost,fd.sell_price,fd.buy_price,fd.buy_time,fd.sell_time,fd.order_sn,f.trader_id')
//      ->order($order)
//      ->paginate(['page' => $page, 'list_rows' => $page_size]);

        $list = TraderOrder::alias('to')
            ->where([
//                'to.status'    => 1,
                'to.trader_id' => $trader_id,
            ])
            ->where('to.status','>','0')
            ->order('to.create_time desc')
            ->order($order)
            ->paginate(['page' => $page, 'list_rows' => $page_size]);
        foreach ($list as $k => $v) {
            $list[$k]['buy_time'] = date('Y-m-d', strtotime($v['buy_time']));
            if(!empty($v['sell_time'])){
                $list[$k]['sell_time'] = date('Y-m-d', strtotime($v['sell_time']));
                if ($v['buy_price'] != 0 && $v['sell_price'] != 0) {
// 浮动收益 = 卖出价格 - 买入价格 / 买入价格 * 100%
                    $list[$k]['floating_ratio'] = number_format(100 * ($v['sell_price'] - $v['buy_price']) / $v['buy_price'], 2) . '%';
                } else {
                    $list[$k]['floating_ratio'] = '- -';
                }
            }else{
                $list[$k]['floating_ratio'] = '- -';
            }

        }

        $data         = [];
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '操盘列表'
        ));
    }

    //我的购买基金列表
    public function getFollowList()
    {
        $uid    = MID;
        $page   = input('page', 1);
        $status = input('status', 0);
        $map    = [['o.uid', 'eq', $uid], ['o.order_type', 'egt', 2], ['o.type', 'eq', 1]];
        if ($status !== '') {
            $map[] = ['o.status', 'eq', $status];
        }
        $order        = 'id desc';
        $list         = FundOrder::getFollowList($page, $map, $order);
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '优投记录'
        ));
    }

    //我的优投列表
    public function getFollowListgs()
    {
        $uid    = MID;
        $page   = input('page', 1);
        $status = input('status', 0);
        $map    = [['o.uid', 'eq', $uid]];
        if ($status !== '') {
//            针对前端，跟卖持仓中包含在优投中
            if ($status == 1) {
                $map[] = ['o.status', 'in', [1, 6]];
            } else {
                $map[] = ['o.status', 'eq', $status];
            }
        }
        $order        = 'id desc';
        $list         = FundOrderGs::getFollowList($page, $map, $order);
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '优投记录'
        ));
    }

    //数据统计
    public function getDataStatistics()
    {
        $is_distribution = config('fund_type');//1=分销，2=优投
        $map             = [];
        $uid             = MID;
        $map[]           = ['uid', '=', $uid];
        $user_data       = Member::where('id', $uid)->find();
        //$level_array = $this->level_array;
        //$level_text = $level_array[$user_data['level']] ?? '';
        $level_text = FundUserlevel::where('level', $user_data['level'])->value('name') ?: '';
//        $sql = 'SELECT id,level FROM lmq_member WHERE FIND_IN_SET(id,queryChildrenAreaInfo(' . $uid . ')) and `is_buy`=1 and `id` != ' . $uid;
//        $list = Db::query($sql);
        $list           = Db::name('member')
            ->where('agent_far', $uid)
            ->where('is_buy', 1)
            ->select();
        $ordinary_level = 0;
        $primary_level  = 0;
        if ($list) {
            foreach ($list as $value) {
                if ($value['level'] == 0) {
                    $ordinary_level += 1;
                }
                if ($value['level'] == 1) {
                    $primary_level += 1;
                }
                $ids[] = $value['id'];
            }
            //伞下人数
            $count = count($list);
        } else {
            $ids = [];
            //伞下人数
            $count = 0;
        }
        $team_num = $count;
        if ($is_distribution == 1) {
            //$map['order_type'] = ['eq', 1];
            $map[] = ['order_type', '=', 1];
//            $invest_total = FundOrder::where($map)->where('type', 1)->count();//总投资数
            $invest_total = FundOrderGs::where($map)->where('status', '>', 0)->count();                                 //总优投数
//            $invest_day = FundOrder::where($map)->whereTime('create_time', 'today')->where('type', 1)->count();//今日投资数
            $invest_day = FundOrderGs::where($map)->whereTime('create_time', 'today')->where('status', '>', 0)->count();//今日优投数
        } else {
            //$map['order_type'] = ['<>', 1];//不等于1时为优投收益  tp5.1不行了
            $map[] = ['order_type', '<>', 1];
//            $invest_total = FundOrder::where($map)->where('type', 1)->count();//总优投数
            $invest_total = FundOrderGs::where($map)->where('status', '>', 0)->count();                                 //总优投数
//            $invest_day = FundOrder::where($map)->whereTime('create_time', 'today')->where('type', 1)->count();//今日优投数
            $invest_day = FundOrderGs::where($map)->whereTime('create_time', 'today')->where('status', '>', 0)->count();//今日优投数
        }
        //总收益
        //$total_income = Db('fund_income_log')->where($map)->sum('money');
        $total_income = FundSalaryLog::where('uid', $uid)->sum('price');
        //今日收益
        //$day_income = Db('fund_income_log')->whereTime('create_time', 'today')->where($map)->sum('money');
        $day_income = FundSalaryLog::where('uid', $uid)->whereTime('create_time', 'today')->sum('price');
        //伞下人数
//        $sql='';
//        $sql='SELECT id FROM lmq_member WHERE FIND_IN_SET(id,queryChildrenAreaInfo('.$uid.')) and `is_buy`=1 and `id` != '.$uid;
//        $list = Db::query($sql);
//
//        foreach ($list as $value){
//            $ids[] = $value['id'];
//        }
        //     $zhitui_num = Member::where('re_id', $uid)->where('is_buy', 1)->count();
//         $three_time = time() - 3 * 24 * 3600;//三天内的
//         $map = [['create_time', 'egt', $three_time], ['uid', 'in', $ids], ['type', 'eq', 1]];
//         $order_list = FundOrder::where($map)->select();
//         $order_list = empty($order_list) ? $order_list : $order_list->toArray();
//         $not_invest_num = 0;
//         $id_array = [];
//         if ($order_list) {
//             foreach ($ids as $v) {
//                 foreach ($order_list as $v1) {
//                     if ($v != $v1['uid']) {
//                         $id_array[] = $v;
//                     }
//                 }
//             }
//         } else {
//             $id_array = $ids;
//         }
//         $id_array = array_unique($id_array);
//         $not_invest_num = count($id_array);
        $map                    = [['mid', 'in', $ids], ['is_first', 'eq', 1]];
        $day_recharge_num       = Recharge::where($map)->whereTime('create_time', 'today')->group('mid')->count();
        $data['level_text']     = $level_text;                       //级别
        $data['ordinary_level'] = $ordinary_level;                   //普通投资者人数
        $data['primary_level']  = $primary_level;                    //初级投资者人数
        $data['total_income']   = $total_income;                     //总收益
        $data['day_income']     = $day_income;                       //今日收益
        $data['invest_total']   = $invest_total;                     //投资总数/优投总数
        $data['invest_day']     = $invest_day;                       //今日投资数/今日优投数
        $data['team_num']       = $user_data['partner_team_num'];    //团队人数  //新版
        $data['zhitui_num']     = $user_data['partner_directly_num'];//直属人数  //新版

        $three_time               = time() - 3 * 24 * 3600;//三天内的
        $us                       = Member::where('id', $uid)->find();
        $path                     = $us->partner_parent_net . ',' . $us->id;
        $level                    = $us->partner_parent_level + 3; //计算三层团队
        $not_invest_num           = Member::where('1=1')
            ->where([['partner_parent_net', 'like', $path . '%']])
            ->where([['partner_parent_level', '<=', $level]])
            ->where([['create_time', '<=', $three_time]])
            ->where('is_buy', 0)->count();
        $data['not_invest_num']   = $not_invest_num;  //三天内未投资/三天内未优投 //新版
        $data['day_recharge_num'] = $day_recharge_num;//今日首充
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '数据统计'
        ));
    }

    public function getVipTradeList()
    {
        $traderid    = input('traderid');
        $list        = Db('fund_viptrade')->where('traderid', $traderid)->select();
        $level_array = $this->level_array;
        foreach ($list as $key => $v) {
//            $v['max_money_text'] = ($v['max_money'] / 10000) . '万';
//            $v['min_money_text'] = ($v['min_money'] / 10000) . '万';
            $v['max_money_text'] = convertToTenThousand($v['max_money']);
            $v['min_money_text'] = convertToTenThousand($v['min_money']);
            // 使用 array_map 将 $a["user_level"] 数组中的每个键转换为对应的文本

            $v["user_level"] = json_decode($v["user_level"], true);

            $arr = [];
            if (!empty($v["user_level"])) {

                # 初始化一个空字符串来保存结果
                $user_levels_text = '';

                #遍历 $v["user_level"] 数组
//                foreach ($v["user_level"] as $level) {
//                    // 检查 $level 是否在 $level_array 中有对应的文本
//
//                    // 如果有，添加到 $user_levels_text 字符串中，并用 <br> 分隔
//                    $user_levels_text       .= $level_array[$level] . '|';
//                    $v["user_level_list"][] = $level_array[$level];
//                }
//                只保留第一层
                    $user_levels_text       = $level_array[$v["user_level"][0]] .'(及以上)';
                    $v["user_level_list"][] = $level_array[$v["user_level"][0]] .'(及以上)';
//        print_r($arr);die;
                #去掉最后一个 <br>（如果需要的话）
                if (!empty($user_levels_text)) {
                    $user_levels_text = rtrim($user_levels_text, '|');
                }

                # 将结果赋值给 $a["user_level"]（虽然这可能会覆盖原来的数组）
                $v["user_level"]         = $user_levels_text;
                $v["user_level_require"] = 1;

            } else {
                $v['user_level']         = '无门槛';
                $v["user_level_require"] = 0;
            }

            $list[$key] = $v;
        }
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => 'vip优投交易日列表'
        ));
    }

    //获取合伙人规则
    public function getRuleContent()
    {
        $lang = Lang::range();
        $map['lang']     = $lang;
        $map['status']   = 1;
        $map['tagname']  = 'rule_content';
        $info            = AdvertModel::where($map)->find();
//        $content         = $info['content'];
//        $data['content'] = $content;
//        $data['content'] = '1、完成首充并且本周有优投记录的才算有效人数。
//                            2、本周无优投记录的人不计入团队有效人数。
//                            3、本周团队优投总收益乘以相应的工资比例核算周工资。
//                            4、团队工资比例结合直属下级合伙人工资比例核算周工资。
//                            5、合伙人补贴以及周工资一周结算一次。';

        $data['partner_notice'] = config('partner_notice'); //标题
        $data['partner_notice_content'] = config('partner_notice_content'); //内容
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '合伙人规则'
        ));
    }

    //总收益列表
    public function getIncomeList()
    {
        $is_distribution = config('fund_type');//1=分销，2=优投
        $page            = input('page', 1);
        $map             = [];
        $uid             = MID;
        $map['uid']      = $uid;
        if ($is_distribution == 1) {
            $map['order_type'] = 1;
        } else {
            $map[] = ['order_type', 'neq', 1];
        }
        //总收益
        $field     = '*';
        $order     = 'id desc';
        $page_size = 15;
        $list      = Db('fund_income_log')->where($map)->field($field)->order($order)->paginate(['page' => $page, 'list_rows' => $page_size]);
        foreach ($list as $key => $value) {
            $value['create_time_text'] = date('Y-m-d H:i', $value['create_time']);
            $list[$key]                = $value;
        }
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '总收益列表'
        ));
    }

    //总收益列表
    public function getDayIncomeList()
    {
        $is_distribution = config('fund_type');//1=分销，2=优投
        $page            = input('page', 1);
        $map             = [];
        $uid             = MID;
        $map['uid']      = $uid;
        if ($is_distribution == 1) {
            $map['order_type'] = 1;
        } else {
            $map[] = ['order_type', 'neq', 1];
        }
        //总收益
        $field     = '*';
        $order     = 'id desc';
        $page_size = 15;
        $list      = Db('fund_income_log')->where($map)->whereTime('create_time', 'today')->field($field)->order($order)->paginate(['page' => $page, 'list_rows' => $page_size]);
        foreach ($list as $key => $value) {
            $value['create_time_text'] = date('Y-m-d H:i', $value['create_time']);
            $list[$key]                = $value;
        }
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '总收益列表'
        ));
    }

    //总投资数
    public function getInvestList()
    {
        $is_distribution = config('fund_type');//1=分销，2=优投
        $page            = input('page', 1);
        $map             = [];
        $uid             = MID;
        $map[]           = ['uid', '=', $uid];
        if ($is_distribution == 1) {
            $map[] = ['order_type', '=', 1];
        } else {
            $map[] = ['order_type', 'neq', 1];
        }

        $today = input('is_today', 0);
        //总收益
        $field        = '*,create_time create_time_text';
        $order        = 'id desc';
        $page_size    = 15;
        $list         = FundOrderGs::where($map)
            ->field($field)
            ->where(function ($query) use ($today) {
                if (!empty($today)) {
                    $query->whereTime('create_time', 'today');
                }
            })
            ->where('status', '>', 0)
            ->order($order)
            ->paginate(['page' => $page, 'list_rows' => $page_size]);
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '优投列表'
        ));
    }

    //今日投资数
    public function getDayInvestList()
    {
        $is_distribution = config('fund_type');//1=分销，2=优投
        $page            = input('page', 1);
        $map             = [];
        $uid             = MID;
        $map[]           = ['uid', '=', $uid];
        if ($is_distribution == 1) {
            $map[] = ['order_type', '=', 1];
        } else {
            $map[] = ['order_type', 'neq', 1];
        }
        //总收益
        $field        = '*,create_time create_time_text';
        $order        = 'id desc';
        $page_size    = 15;
        $list         = FundOrderGs::where($map)->whereTime('create_time', 'today')->field($field)->order($order)->paginate(['page' => $page, 'list_rows' => $page_size]);
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '今日投资/今日优投'
        ));
    }

    //团队列表
    public function getTeamList()
    {
        $uid = MID;
//         $sql = 'SELECT id,level,name,mobile,create_time FROM lmq_member WHERE FIND_IN_SET(id,queryChildrenAreaInfo(' . $uid . ')) and `is_buy`=1 and `id` != ' . $uid;
//         $list = Db::query($sql);
//         foreach ($list as $key => $value) {
//             $value['create_time_text'] = date('Y-m-d H:i', $value['create_time']);
//             $list[$key] = $value;
//         }
//         $data['list'] = $list;

        //新写三级团队
        $us    = Member::where('id', $uid)->find();
        $path  = $us->partner_parent_net . ',' . $us->id;
        $level = $us->partner_parent_level + 3; //计算三层团队
        $list  = Member::where('1=1')
            ->field('id,level,name,mobile,create_time,create_time as create_time_text')
            ->where([['partner_parent_net', 'like', $path . '%']])
            ->where([['partner_parent_level', '<=', $level]])
            ->where('is_buy', 1)
            ->order('id desc')
            ->paginate()
            ->each(function ($item) {

            });

        $data['list'] = $list->items();
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '团队列表'
        ));
    }

    //直推列表
    public function getRecommendList()
    {
        $page         = input('page', 1);
        $uid          = MID;
        $field        = 'id,name,mobile,create_time create_time_text';
        $order        = 'id desc';
        $page_size    = 15;
        $list         = Member::where('partner_parent_id', $uid)->where('is_buy', 1)->field($field)->order($order)->paginate(['page' => $page, 'list_rows' => $page_size]);
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '直推列表'
        ));
    }

    //三日未优投人数/三日未投资
    public function getThreeInvestList()
    {
        $uid = MID;
//         $sql = 'SELECT id,level FROM lmq_member WHERE FIND_IN_SET(id,queryChildrenAreaInfo(' . $uid . ')) and `is_buy`=1 and `id` != ' . $uid;
//         $list = Db::query($sql);
//         $ids = [];
//         foreach ($list as $value) {
//             $ids[] = $value['id'];
//         }
//         $three_time = time() - 3 * 24 * 3600;//三天内的
//         $map = [['create_time', 'egt', $three_time], ['uid', 'in', $ids], ['type', 'eq', 1]];
//         $field = '*';
//         $order = 'id desc';
//         $order_list = FundOrder::where($map)->field($field)->order($order)->select();
//         $id_array = [];
//         if ($order_list) {
//             foreach ($ids as $v) {
//                 foreach ($order_list as $v1) {
//                     if ($v != $v1['uid']) {
//                         $id_array[] = $v;
//                     }
//                 }
//             }
//         } else {
//             $id_array = $ids;
//         }
//         $field1 = 'id,name,mobile,level level_text,create_time create_time_text';
//         $list = [];
//         if ($id_array) {
//             $list = Member::where(['id', 'in', $id_array])->field($field1)->order($order)->select();
//         }
//         $data['list'] = $list;

        //新版三层团队
        $three_time   = time() - 3 * 24 * 3600;//三天内的
        $us           = Member::where('id', $uid)->find();
        $path         = $us->partner_parent_net . ',' . $us->id;
        $level        = $us->partner_parent_level + 3; //计算三层团队
        $list         = Member::where('1=1')
            ->where([['partner_parent_net', 'like', $path . '%']])
            ->where([['partner_parent_level', '<=', $level]])
            ->where([['create_time', '<=', $three_time]])
            ->where('is_buy', 0)
            ->order('id desc')
            ->paginate()
            ->each(function ($item) {
                $item->create_time_text = date('Y-m-d H:i', $item->create_time);
            });
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '三日未优投人数/三日未投资'
        ));
    }

    //今日充值
    public function getDayRechargeListbak()
    {
        $uid = MID;
        $sql = 'SELECT id,level FROM lmq_member WHERE FIND_IN_SET(id,queryChildrenAreaInfo(' . $uid . ')) and `is_buy`=1 and `id` != ' . $uid;
//        $list = Db::query($sql);
        $list = Db::name('member')
            ->where('agent_far', $uid)
            ->where('is_buy', 1)
            ->select();
        $ids  = [];
        foreach ($list as $value) {
            $ids[] = $value['id'];
        }
        $map   = [];
        $map[] = ['a.mid', 'in', $ids];
        $map[] = ['a.is_first', '=', 1];
        $field = 'a.*,m.name,mobile';
        $list  = [];
        if ($ids) {
            $list = Db('money_recharge')->alias('a')->where($map)->field($field)->join('member m', 'm.id = a.mid')->whereTime('a.create_time', 'today')->select();
        }
        $data['list'] = $list;
        return json(array(
            'data'    => $data,
            'status'  => 1,
            'message' => '今日充值'
        ));
    }

    //今日充值
    public function getDayRechargeList()
    {
        $uid     = MID;
        $user    = Member::where('id', $uid)->find();
        $path    = $user->partner_parent_net . ',' . $user->id;
        $level   = $user->partner_parent_level + 3; //计算三层团队
        $teamIds = Member::where('1=1')
            ->where([['partner_parent_net', 'like', $path . '%']])
            ->where([['partner_parent_level', '<=', $level]])
            ->where('is_buy', 1)
            ->column('id');

        $map   = [];
        $map[] = ['a.mid', 'in', $teamIds];
        $map[] = ['a.is_first', '=', 1];
        $field = 'a.*,m.name,mobile';
        $list  = Db('money_recharge')
            ->alias('a')
            ->where($map)
            ->field($field)
            ->join('member m', 'm.id = a.mid')
            ->whereTime('a.create_time', 'today')
            ->paginate();
        return json(array(
            'data'    => $list,
            'status'  => 1,
            'message' => '今日充值'
        ));
    }

    //优投追加投资金额
    public function addInvestment()
    {
        $uid   = MID;
        $id    = input('id');
        $money = input('money');
        $paywd = input('paywd');
        if (!isset($paywd) || !$paywd) {
            ajaxmsg('请输入支付密码', 0);
        }
        if (empty($money) || $money < 0) {
            ajaxmsg('金额不能小于0', 0);
        }
        if ($money % 100 != 0) {
            ajaxmsg('金额必须是100的倍数', 0);
        }
        $member = Db::name('member')->where(["id" => $uid])->find();
        if (!Hash::check((string)$paywd, $member['paywd'])) {
            ajaxmsg('支付密码不正确', 0);// '用户名或密码错误！';
        }
        $row = FundOrderGs::where('id', $id)->where('uid', $uid)->find();

        if (empty($row)) {
            ajaxmsg('未找到优投', 0);
        }
        if ($row->status != 1 && $row->status != 6) {
            ajaxmsg('状态错误', 0);
        }

        $user_balance = Money::getMoney($uid);
        $balance      = $user_balance['account'] / 100;//转为元

        if ($balance < $money) {
            ajaxmsg('余额不足', 0);
        }

        $row->add_money += $money;
        $row->money     += $money;
        $row->save();

        $setDec_Money_insert = Money::where('mid', $uid)->setDec('account', $money * 100);//减少余额
        if (!$setDec_Money_insert) {
            //ajaxmsg('追加失败', 0);
        }
        //记录日志
        $user_balance    = Money::getMoney($uid);
        $account         = $user_balance['account'];
        $info            = '优投追加投资金额：' . $money . '元-[优投号：' . $row->order_sn . ']';
        $obj             = ['affect' => $money * 100, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => $row->order_sn];
        $Record_saveData = Record::saveData($uid, $money * 100, $account, 94, $info, '', '', $obj);
        if (!$Record_saveData) {
            //ajaxmsg('失败', 0);
        }
        ajaxmsg('追加成功');
    }

    public function getAgreement()
    {
//        $get_one = Db::name('fund_config')->find();
        $get_one['explain'] = config('fund_explain');
        return json(array(
            'data'    => $get_one,
            'status'  => 1,
            'message' => '获取协议'
        ));
    }
}