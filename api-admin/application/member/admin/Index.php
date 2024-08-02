<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------
namespace app\member\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\fund\model\FundIncomeLog;
use app\fund\model\FundSalaryLog;
use app\fund\model\FundUserlevel as FundUserlevelModel;
use app\market\model\Position;
use app\member\model\Member as MemberModel;
use app\member\model\Member;
use app\member\model\MemberBlacklist;
use app\member\model\MemberLoginRecord;
use app\member\model\UserReport;
use app\money\model\Recharge;
use app\statistics\model\DataReport as DataReportModel;
use app\stock\model\Borrow;
use app\stock\model\SubMoneyRecord;
use app\user\model\User as AdminUserModel;
use think\Db;
use think\facade\Cache;
use think\facade\Hook;
use think\helper\Hash;
use util\Tree;
use app\money\model\Record;
use app\money\model\Withdraw;
use app\fund\model\FundOrderGs;
use app\fund\model\FundOrder;
use app\common\service\UserService;
use app\stock\service\PartnerSettleService;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Index extends Admin
{
    /**
     * 首页
     * @return mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->agentPartnerSearchMap($map,'m.id');
        $order = $this->getOrder();
        $input = input();
//        $map = [];
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map[] = ['agent_far','=',$admin_user['for_user']];
        }

        empty($order) && $order = 'id desc';
//        $map['is_del'] = 0;
        // 数据列表
        $data_list = MemberModel::getlist($map, $order);
        $agent_list = MemberModel::where('agent_id', '>', 0)->where('agent_pro', 1)->column('name', 'id');
        $data = [];
        foreach ($data_list as $key => $value) {
            $data_list[$key]['agent_name'] = $agent_list[$value['agent_far']] ?? '';
            $email = $value['email'] ?? '--';
//            -*-----------去加密信息
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            $value['id_card'] = $value['id_card']?privacy_info_switch('id_card',$value['id_card']):'--';

            $data_list[$key]['user_us'] = "<p>" . $value['mobile'] . "</p><p>{$email}</p>";
            $data_list[$key]['user_info'] = "<p>" . $value['name'] . "</p><p>" . $value['id_card'] . "</p>";
            $data_list[$key]['invite'] = "<p>" . $value['invite_name'] . "</p><p>" . $value['invite_account'] . "</p>";
            $data_list[$key]['time'] = "<p>" . date("Y-m-d H:i:s", $value['create_time']) . "</p><p>" . date("Y-m-d H:i:s", empty($value['last_login_time']) ?$value['create_time']: $value['last_login_time']) . "</p>";
            $ip = long2ip($value['last_login_ip']);
            $data_list[$key]['login'] = "<p>" . $value['terminal'] . "</p><p>" . $ip . "</p>";
            $account = ($value['account'] / 100) > 1 ? "danger" : "";
            $activity_account = ($value['activity_account'] / 100) >= 1 ? "danger" : "";
            $freeze = ($value['freeze'] / 100) > 1 ? "danger" : "";
            $bond_account = ($value['bond_account'] / 100) >= 1 ? "danger" : "";
            $recharge_num = ($value['recharge_num'] / 100) >= 1 ? "danger" : "";
            $withdraw_num = ($value['withdraw_num'] / 100) >= 1 ? "danger" : "";
            $data_list[$key]['account'] = "<p class='{$account}'>" . ($value['account'] / 100) . "</p><p class='{$activity_account}'>" . ($value['activity_account'] / 100) . "</p>";
            $data_list[$key]['account2'] = "<p class='{$freeze}'>" . ($value['freeze'] / 100) . "</p><p class='{$bond_account}'>" . ($value['bond_account'] / 100) . "</p>";
            $data_list[$key]['frequency'] = "<p class='{$recharge_num}'>" . $value['recharge_num'] . "</p><p class='{$withdraw_num}'>" . $value['withdraw_num'] . "</p>";
            $agent_name = empty($value['agent_name']) ? '--' : $value['agent_name'];
            $data_list[$key]['agent'] = "<p>" . $agent_name . "</p><p>" . $value['invite_num'] . "</p>";
            
            $inviteUser = MemberModel::where('id', $value['partner_parent_id'])->find();
            $data_list[$key]['partner_parent'] = '';
            if (!empty($inviteUser)) {
                $data_list[$key]['partner_parent'] = "<p>(ID:{$value['partner_parent_id']})".$inviteUser['mobile']."</p><p>" .$inviteUser['name'].'</p>';
            }
            
            $data_list[$key]['partner_num'] = "<p>{$value['partner_directly_num']}</p><p>{$value['partner_team_num']}</p>";
        }
        $remarksconfig = config('remarksconfig');
        $remarksconfig = explode("\r\n", $remarksconfig);
        $remarksdata = [];
        foreach ($remarksconfig as $k => $v) {
            $remarksdata[$v] = $v;
        }
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"] . '&';
        }

        $excel_url = 'index_export';
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
           'href' => url($excel_url.http_build_query([$map]), '', '')
        ];
//        $btn_excel = ['title' => '导出EXCEL表', 'icon' => 'fa fa-fw fa-download', 'href' => url($excel_url, '', '')];
        $btn_report = ['title' => '统计', 'icon' => 'fa fa-fw fa-bar-chart', 'href' => url('statistics', ['id' => '__id__'])];
//        $btn_consult = ['title' => '合约', 'icon' => 'fa fa-fw fa-align-left', 'href' => url('consult', ['id' => '__id__']), 'target' => '_blank'];
        $btn_tree = ['title' => '团队', 'icon' => 'fa fa-fw fa-users', 'href' => url('tree', ['uid' => '__id__'])];

        $FundUserlevel_list = FundUserlevelModel::field('level')->select();

        $level_list=[];
        $this->level_array= Funduserlevelarray();
        foreach ($FundUserlevel_list as $v){
            $level_list[$v['level']]=$this->level_array[$v['level']];

        }

        $btn_access = [
            'title' => '批量添加',
            'icon'  => 'fa fa-plus-circle',
            'href'  => url('addbatch')
        ];
        
        $btn_update = [
            'title' => '更新会员级别',
            'icon'  => 'fa fa-fw fa-refresh',

            'href'  => url('net_update')
        ];

        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];

        
        $role_name = array("白名单" => "白名单", "普通会员" => "普通会员");
        return ZBuilder::make('table')
//            ->setExtraHtmlFile('listu', 'toolbar_top')
            ->setSearchArea([
                ['text', 'm.name', '姓名', 'like'],
                ['text', 'm.id_card', '身份证', 'like'],
                ['text', 'm.mobile', '手机号', 'like'],
                ['text', 'm.email', '邮箱', 'like'],
                ['text', 'partner_directly_num', '合伙人邀请人数', 'like'],
                ['daterange', 'm.create_time', '注册时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
                ['select', 'role_name', '白名单', '', '', $role_name],
                ['select', 'level', '会员级别', '', '', $level_list],
                ])
        ->hideCheckbox()
          ->setTableName('member') // 设置数据表名
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['user_us', '手机/邮箱'],
            ['user_info', '姓名/身份证号'],
            ['from_to', '来源'],
            ['level', '会员级别', 'select', $level_list],
//            ['remarks', '备注', 'select', $remarksdata], 暂时不知为什么是下拉框
            ['remarks', '备注', $remarksdata],
            ['role_name', '白名单', 'select', $role_name],
            ['account', '账户余额/活动金余额'],
            ['account2', '冻结金额/保证金金额'],
            ['frequency', '充值次数/提现次数'],
            ['agent_far', '所属代理', 'select', $agent_list],
            ['agent', '代理姓名/下线人数'],
            ['is_buy', '是否投资', ['未投资', '已投资']],
            //['invite', '邀请人姓名/邀请人账号'],
              ['partner_parent', '邀请人账号/邀请人姓名'],
              ['partner_num', '直属人数/团队人数'],
            ['login', '最后登录设备/最后登录IP'],
            ['time', '注册时间/最后登陆时间'],
            ['status', '登录状态', 'switch'],
            ['is_del', '账户状态', [0 => '正常', 1 => '注销/删除']],
            ['right_button', '操作', 'btn']])
          ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮  ,last_login_time
          ->addRightButton('custom', $btn_report)
          ->addRightButton('custom', $btn_tree)
          ->addTopButton('add') // 批量添加顶部按钮
            ->addTopButton('custom', $btn_access)
            ->addTopButton('custem', $btn_excel)
            ->addTopButton('custem', $btn_update)
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
//          ->addTopButton('add', [], ['area' => ['800px', '90%'], 'title' => '批量添加']) // 批量添加顶部按钮

          ->addOrder('id,id_auth,reg_time,status,is_del,level')
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->setColumnWidth('user_us,user_info', 200)
          ->setColumnWidth('time', 250)
          ->setColumnWidth('right_button,partner_parent,login', 160)
          ->setPageTips('注意：修改邀请人账户，如需重新计算会员级别 请点击【更新会员级别】按钮！', 'warning')
            ->fixedLeft(2)
            ->fixedRight(1)
          ->fetch(); // 渲染模板
    }

    public function consult($id = '')
    {
        $req = request();
        $map = input('map');
        $user_data = MemberModel::where('id', $id)->find();
        $user_data = empty($user_data) ? [] : $user_data->toArray();
        $balance = Db::name('money')->where('mid', $id)->where('status', 1)->sum('account');
        $user_data['balance'] = bcdiv($balance, 100, 2);
        //子账户id集合
        $sub_ids = Db('stock_subaccount')->where('uid', $id)->column('id');
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        $listRows = input('listRows', 10);
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'b.create_time desc';
        }
        $where = [['b.stock_subaccount_id','in', $sub_ids],['b.status','neq', 2]];

//        $where['member_id'] = 62;
        //合约列表
        $borrow_list = Borrow::getList2($where, $order);
        foreach ($borrow_list as $key => $value) {
            $borrow_list[$key]['status_text'] = $status_arr[$value['status']] ?? '';
        }
        $res_data = [];
        $res_data['borrow_list'] = $borrow_list;
        $res_data['code'] = 0;
        //持仓数据
        $position = new Position();
        // 读取数据
        $page = input('page', 1);
        $order = 'p.id desc';
        $position_data = $position->get_position($sub_ids, $page, $order, $listRows);
        foreach ($position_data as $v) {
            Db::name('stock_position')->where(['id' => $v['id']])->update($v);
        }
        $position_list = Db('stock_position')
	->where('sub_id', 'in', $sub_ids)
	->where(['buying' => 0])->order('id desc')->paginate($listRows);
        foreach ($position_list as $k => $v) {
            if (empty($v['ck_price_new'])) {
                $v['ck_price_new'] = $v['ck_price'];
            }
            $v['ck_profit'] = ($v['now_price'] - $v['ck_price_new']) * $v['stock_count'];
            $v['ck_profit'] = round($v['ck_profit'], 2);
            $position_list[$k] = $v;
        }
        $position_list = empty($position_list) ? [] : $position_list->toArray();
        $res_data['position_list'] = $position_list;
        // 合约资金明细
        $map2 = [['r.sub_id','in', $sub_ids]];
     
        $order = 'r.id desc ';
        $model = new SubMoneyRecord();
        $money_record_list = $model->get_record_strok($map2, $order, $listRows);
        $res_data['money_record_list'] = $money_record_list;
        // 用户资金明细
        $map3 = ['r.sub_id','in', $sub_ids];
    
        $order = 'r.id desc ';
        $listRows = 20;
        $model = new SubMoneyRecord();
        $money_record_list2 = $model->get_record_strok2($map3, $order, $listRows);
        $res_data['money_record_list2'] = $money_record_list2;
        // 获取排序
        $order = 'trust_date desc';
        $trust_list = Db('stock_trust t')->where('sub_id', 'in', $sub_ids)->order($order)->paginate($listRows);
        foreach ($trust_list as $key => $value) {
            $value['trust_date'] = date('Y-m-d', $value['trust_date']);
            $trust_list[$key] = $value;
        }
        $res_data['trust_list'] = $trust_list;
        if (input()) {
            echo json_encode($res_data);
            exit();
        }
        return ZBuilder::make('table')
          ->assign(['id' => $id, 'user_data' => $user_data, 'position_list' => $position_list, 'borrow_list' => $borrow_list, 'status_arr' => $status_arr, 'money_record_list' => $money_record_list, 'money_record_list2' => $money_record_list2, 'trust_list' => $trust_list])
          ->setTemplate('consult')
          ->assign('empty_tips', '')
          ->fetch();
    }
  //合约列表
    public function consult1()
    {
        if (input()) {
            $req = request();
            $map = $req::instance()->param('map');
            $id = input('id');
            $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
            //子账户id集合
            $sub_ids = Db('stock_subaccount')->where('uid', $id)->column('id');
            // 排序
            $order = $this->getOrder();
            if (empty($order)) {
                $order = 'create_time desc';
            }
            $where = [];
            $where['stock_subaccount_id'] = ['in', $sub_ids];
            $where['status'] = ['neq', 2];
            // 数据列表
            $borrow_list = Borrow::getList2($where, $order);
            foreach ($borrow_list as $key => $value) {
                $borrow_list[$key]['status_text'] = $status_arr[$value['status']] ?? '';
            }
            $res_data = [];
            $res_data['borrow_list'] = $borrow_list;
            $res_data['code'] = 0;
            echo json_encode($res_data);
            exit();
        }
    }
    //股票列表
    public function gupiaolist()
    {
        if (input()) {
            $req = request();
            $map = $req::instance()->param('map');
            $id = input('id');
            //子账户id集合
            $sub_ids = Db('stock_subaccount')->where('uid', $id)->column('id');
            // 排序
            $order = $this->getOrder();
            if (empty($order)) {
                $order = 'b.create_time desc';
            }
            //持仓数据
            $position = new Position();
            // 读取数据
            $page = input('page', 1);
            $listRows = input('listRows', 10);
            $order = 'p.id desc';
            $position_data = $position->get_position($sub_ids, $page, $order, $listRows);
            foreach ($position_data as $v) {
                Db::name('stock_position')->where(['id' => $v['id']])->update($v);
            }
            $position_list = Db('stock_position')->where('sub_id', 'in', $sub_ids)->where(['buying' => 0])->order('id desc')->paginate($listRows);
            foreach ($position_list as $k => $v) {
                if (empty($v['ck_price_new'])) {
                    $v['ck_price_new'] = $v['ck_price'];
                }
                $v['ck_profit'] = ($v['now_price'] - $v['ck_price_new']) * $v['stock_count'];
                $v['ck_profit'] = round($v['ck_profit'], 2);
                $position_list[$k] = $v;
            }
            $position_list = empty($position_list) ? [] : $position_list->toArray();
            $res_data = [];
            $res_data['position_list'] = $position_list;
            $res_data['code'] = 0;
            echo json_encode($res_data);
            exit();
        }
    }

    //合约资金明细
    public function heyuelist()
    {
        if (input()) {
            $req = request();
            $map = $req::instance()->param('map');
            $id = input('id');
            //子账户id集合
            $sub_ids = Db('stock_subaccount')->where('uid', $id)->column('id');
            // 读取数据
            $page = input('page', 1);
            $listRows = input('listRows', 10);
            // 合约资金明细
            $map[]=['r.sub_id','in', $sub_ids];
            $order = 'r.id desc ';
            $model = new SubMoneyRecord();
            $money_record_list = $model->get_record_strok($map, $order, $listRows);
            $res_data = [];
            $res_data['money_record_list'] = $money_record_list;
            $res_data['code'] = 0;
            echo json_encode($res_data);
            exit();
        }
    }

    //用户资金明细
    public function moneyrecordlist()
    {
        if (input()) {
            $req = request();
            $map = $req::instance()->param('map');
            $id = input('id');
            //子账户id集合
            $sub_ids = Db('stock_subaccount')->where('uid', $id)->column('id');
            // 读取数据
            $page = input('page', 1);
            $listRows = input('listRows', 10);
            // 用户资金明细
            $map[]=['r.sub_id','in', $sub_ids];
//            $map['r.sub_id'] = 77;
            $order = 'r.id desc';
            $model = new SubMoneyRecord();
            $money_record_list2 = $model->get_record_strok2($map, $order, $listRows, $page);
            $res_data = [];
            $res_data['money_record_list2'] = $money_record_list2;
            $res_data['code'] = 0;
            echo json_encode($res_data);
            exit();
        }
    }

    //委托列表
    public function trustlist()
    {
        if (input()) {
            $req = request();
            $map = $req::instance()->param('map');
            $id = input('id');
            $listRows = input('listRows', 10);
            $sub_ids = Db('stock_subaccount')->where('uid', $id)->column('id');
            // 获取排序
            $order = 'trust_date desc';
            $trust_list = Db('stock_trust t')->where('sub_id', 'in', $sub_ids)->order($order)->paginate($listRows);
            foreach ($trust_list as $key => $value) {
                $value['trust_date'] = date('Y-m-d', $value['trust_date']);
                $trust_list[$key] = $value;
            }
            $res_data = [];
            $res_data['trust_list'] = $trust_list;
            $res_data['code'] = 0;
            echo json_encode($res_data);
            exit();
        }
    }

    public function report($id = '')
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = ['m.agent_id' => 0];
        if ($webtype == 2) {
            $where['r.for_user'] = for_user();
        }
        $user_data = MemberModel::where('id', $id)->find();
        $user_data = empty($user_data) ? [] : $user_data->toArray();
        $balance = Db::name('money')->where('mid', $id)->where('status', 1)->sum('account');
        $user_data['balance'] = bcdiv($balance, 100, 2);
        $user_profit_loss = [];
        $returns = array();
        //会员盈亏
        $user_profit_loss['name'] = '会员盈亏';
        //总充值
        $user_recharge['name'] = '总充值';
        //总充值返现
        $cashback['name'] = '总充值返现';
        //合约实际盈亏
        $profit_loss['name'] = '合约实际盈亏';
        //累计利息
        $interest['name'] = '累计利息';
        //会员净资产
        $user_property['name'] = '会员净资产';
        //总提现
        $withdrawal['name'] = '总提现';
        //赚取佣金
        $commission['name'] = '赚取佣金';
        //累计抵扣金额
        $deduction_amount['name'] = '累计抵扣金额';
        //合约穿仓资金
        $contract_closing_funds['name'] = '合约穿仓资金';
        $returns = [];
        $j = 0;
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime('-' . $i . ' day'));
            $days[] = $date;
            $start_time = strtotime($date);
            $end_time = $start_time + 24 * 3600 - 1;
            $info = UserReport::where('create_time', 'between time', [$start_time, $end_time])->find();
            $info = empty($info) ? [] : $info->toArray();
            $user_profit_loss['data'][$j] = $info ? $info['user_profit_loss'] : 0;
            $user_recharge['data'][$j] = $info ? $info['user_recharge'] : 0;
            $cashback['data'][$j] = $info ? $info['cashback'] : 0;
            $profit_loss['data'][$j] = $info ? $info['profit_loss'] : 0;
            $interest['data'][$j] = $info ? $info['interest'] : 0;
            $user_property['data'][$j] = $info ? $info['user_property'] : 0;
            $withdrawal['data'][$j] = $info ? $info['withdrawal'] : 0;
            $commission['data'][$j] = $info ? $info['commission'] : 0;
            $deduction_amount['data'][$j] = $info ? $info['deduction_amount'] : 0;
            $contract_closing_funds['data'][$j] = $info ? $info['contract_closing_funds'] : 0;
            $j++;
        }
        for ($j = 0; $j < 10; $j++) {
            switch ($j) {
                case 0:
                    $returns[$j]['name'] = $user_profit_loss['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $user_profit_loss['data'];
                    break;
                case 1:
                    $returns[$j]['name'] = $user_recharge['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $user_recharge['data'];
                    break;
                case 2:
                    $returns[$j]['name'] = $cashback['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $cashback['data'];
                    break;
                case 3:
                    $returns[$j]['name'] = $profit_loss['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $profit_loss['data'];
                    break;
                case 4:
                    $returns[$j]['name'] = $interest['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $interest['data'];
                    break;
                case 5:
                    $returns[$j]['name'] = $user_property['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $user_property['data'];
                    break;
                case 6:
                    $returns[$j]['name'] = $withdrawal['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $withdrawal['data'];
                    break;
                case 7:
                    $returns[$j]['name'] = $commission['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $commission['data'];
                    break;
                case 8:
                    $returns[$j]['name'] = $deduction_amount['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $deduction_amount['data'];
                    break;
                case 9:
                    $returns[$j]['name'] = $contract_closing_funds['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $contract_closing_funds['data'];
                    break;
            }
        }
        $returns = json_encode($returns);
        $days = json_encode($days);
//        if ($map === "888") {
        return ZBuilder::make('table')
          ->assign(['user_data' => $user_data, 'returns' => $returns, 'days' => $days])
          ->setTemplate('report')
          ->assign('empty_tips', '')
          ->fetch();
//        } else {
//            return ZBuilder::make('table')
//                ->assign(['user_data' => $user_data, 'returns' => $returns, 'days' => $days])
//                ->setExtraHtml('<iframe src="' . url('report?id=' . $id, 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
//                ->fetch();
//        }
    }

    /**
     * Desc :  验证码发送记录

     * Date : 2024-07-07 18:32
     */
    public function smslog()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();


        $order = 'id desc';
        // 数据列表
        $data_list = Db::name('member_smslog')->where($map)->order($order)
            ->paginate()
            ->each(function ($v, $k) {
                $v['mobile'] = privacy_info_switch('mobile',$v['mobile']);
                return $v;
            });

        $btn_access = [
            'title' => '外部访问获取',
            'icon' => 'fa fa-fw fa-cc-visa',
            'href' => '/index.php/member/sms_test',
            'target' => '_blank'];
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
            ->setSearchArea([
                ['text', 'mobile', '手机号', 'like'],
                ['daterange', 'addtime', '创建时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
            ->hideCheckbox()
            ->setTableName('member_smslog') // 设置数据表名
            ->addColumns([ // 批量添加数据列
                           ['id', 'ID'],
                           ['reg_code', '区号'],
                           ['mobile', '手机号'],
                           ['channel', '发送渠道'],
                           ['zone', '地区'],
                           ['ip', 'IP'],
                           ['status', '是否成功', 'yesno'],
                           ['return_content', '返回内容提示'],

                           ['send_content', '请求内容'],
                           ['addtime', '操作时间', 'datetime']
            ])
            ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮  ,last_login_time
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
            ->addTopButton('custom', $btn_access)

            ->setRowList($data_list) // 设置表格数据

            ->setColumnWidth('send_content', 250)
            ->setColumnWidth('return_content', 250)

            ->fetch(); // 渲染模板
    }
    public function getLoginLog()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->getAgentMap($map,'mid');
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        if (empty($map['login_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['login_time'][1][0]));
        }
        if (empty($map['login_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['login_time'][1][1]));
        }
        // 数据列表
        $data_list = MemberLoginRecord::where($map)->order($order)->paginate();
        foreach ($data_list as $key => $value) {
            $data_list[$key]['login_ip'] = long2ip($value['login_ip']);
            $data_list[$key]['mobile'] = privacy_info_switch('mobile',$data_list[$key]['mobile']);
        }
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = ['title' => '导出EXCEL表', 'icon' => 'fa fa-fw fa-download', 'href' => url($excel_url, '', '')];
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
//            ->setSearch(['username' => '姓名', 'mobile' => '手机号']) // 设置搜索框
            ->setSearchArea([
                ['text', 'username', '姓名', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['text', 'email', '邮箱', 'like'],
                ['daterange', 'login_time', '登录时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']]
            ])
        ->addColumns([ // 批量添加数据列
          ['id', 'ID'],
          ['mobile', '手机号'],
          ['username', '姓名'],
          ['email', '邮箱'],
          ['from_to', '登录来源'],
          ['remarks', '备注'],
          ['login_area', '登录地区'],
          ['login_ip', '登录IP'],
          ['terminal', '终端'],
          ['onlinetime', '最后上线', 'datetime'],
          ['login_time', '登录时间', 'datetime'],
//            ['right_button', '操作', 'btn']
        ])->hideCheckbox()
          ->setTableName('member')
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
//            ->addTopButtons('enable,disable,delete') // 批量添加顶部按钮
//        ->addTopButton('custem', $btn_excel)->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮  ,last_login_time
//        ->addRightButton('aedit', ['href' => url('reset_payword', ['id' => '__id__']) ])
//        ->addRightButton('custom', ['title' => '详情报表'], ['href' => url('reset_payword', ['id' => '__id__'])])
          ->addOrder('id,id_auth,reg_time,status,is_del')->setRowList($data_list) // 设置表格数据
//          ->addTimeFilter('login_time', [$beginday, $endday]) // 添加时间段筛选
          ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function addBlack()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            // 验证
//            $result = $this->validate($data, 'language');
//            if(true !== $result) $this->error($result);
            if ($link = MemberBlacklist::create($data)) {
                // 记录行为
//                action_log('link_add', 'cms_link', $link['id'], UID, $data['title']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['text', 'name', '中文名称'],
            ['text', 'english', '英文名称'],
          ])
          ->setTrigger('type', 2, 'logo')
          ->fetch();
    }

    public function index_export()
    {
        $map = $this->getMap();
        $map = (new UserService())->agentPartnerSearchMap($map,'m.id');
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        $xlsData = MemberModel::getlist($map, $order);
//        $xlsData = MemberModel::where($map)->order($order)->paginate();
        $is_del_arr = [0 => '正常', 1 => '注销/删除'];
        foreach ($xlsData as $k => $v) {
            $v['is_del'] = $is_del_arr[$v['is_del']];
            $v['create_time'] = date('Y-m-d H:i', $v['create_time']);
            $v['mobile'] = privacy_info_switch('mobile',$v['mobile']);
        }
        $title = "会员列表";
        $arrHeader = array(
          'ID',
          '手机号',
          '姓名',
          '身份证号',
          '账户余额',
          '白名单',
          '注册时间',
        );
        $fields = array(
          'id',
          'mobile',
          'name',
          'id_card',
          'account',
          'role_name',
          'create_time',
        );
        export($arrHeader, $fields, $xlsData, $title);
    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $update_data = input();
            $update_data['agent_far'] = $update_data['pid'];
//            $data_r['id']= $update_data['id'];//被邀请人id
//            $data_r['mid']= $update_data['pid'];//邀请人id
//            InviteModel::where('invitation_mid',$update_data['id']) -> delete();
//            InviteModel::saveData($data_r);//保存推荐关系数据
            //$update_data['pid']=($update_data['pid']==="0")?null:$update_data['pid'];
            $update_data['id_card'] = ($update_data['id_card'] === "") ? null : $update_data['id_card'];
            $update_data['name'] = ($update_data['name'] === "") ? null : $update_data['name'];
            if ($update_data['id_auth']) {
                if (empty($update_data['id_card']) || empty($update_data['name'])) {
                    $this->error('身份证或姓名为空');
                }
            }
            // 验证
            $data = $update_data;
            unset($update_data['passwd'], $update_data['paywd']);
            $result = $this->validate($update_data, 'Member.update');
            $members_card = MemberModel::where(['id' => $data['id']])->value('id_card');
            if ($data['id_card'] != $members_card) {
                $memberhasid_card = MemberModel::where(['id_card' => $data['id_card']])->value('id_card');
                if ($memberhasid_card) {
                    $this->error('身份证已存在');
                }
                if (!preg_match("/^[1-9][0-9]{5}(19|20)[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|31)|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}([0-9]|x|X)$/", $data['id_card'])) {
                    $this->error('身份证格式不对');
                }
            }
//            手机号
            $members_mobile = MemberModel::where(['id' => $data['id']])->value('mobile');
            if ($data['mobile'] != $members_mobile) {
                $memberhasid_mobile = MemberModel::where(['mobile' => $data['mobile']])->value('mobile');
                if ($memberhasid_mobile) {
                    $this->error('该手机号已经被使用');
                }
            }
            // 验证失败 输出错误信息
//            if (true !== $result) $this->error($result);
            // 如果没有填写密码，则不更新密码
            if ($data['passwd'] == '') {
                unset($data['passwd']);
            }
            if (empty($data['paywd'])) {
                unset($data['paywd']);
            }
            /**
             * //设置代理 不设置rate 默认后台配置rate
             * if ($data['agent_rate'] == 0) {
             * $data['agent_rate'] = config('agent_back_rate');
             * }
             * //判断是否符合设置代理情况 已经是下级代理 则不能设置一级代理
             * $user = MemberModel::get($data['id']);
             * if(!$user['agent_id'] && !$user['agent_far']){ //需要修改注册位置 注册添加返佣期限限制 邀请用户填充上级代理
             * $this->error('该用户不能设置为搭理');
             * }
             * $data['agent_time'] = 0;//取消返佣期限限制
             *
             */


            if (MemberModel::update($data)) {
                $sync_ta  = [
                    'mobile'   => $data['mobile'],
                ];
                Member::syncUpdateMobile($sync_ta,$data['id'],2);
                $member = MemberModel::get($data['id']);

//                修改下线代理
                if(isset($data['agent_far']) && $data['agent_far'] > 0){
//                    A的代理是S，B是A邀请的，那么B的代理也是S
                    $us    = Member::where('id', $data['id'])->find();
                    $path  = $us->partner_parent_net . ',' . $us->id;
                    $list  = Member::where('1=1')
                        ->field('id,name')
                        ->where([['partner_parent_net', 'like', $path . '%']])
                        ->select();
                    foreach ($list as $key => $value){
                        Db::name('member')
                            ->where('id',$value['id'])->update(['agent_far'=>$data['agent_far']]);
                    }

                }


                Hook::listen('member_edit', $member);
                // 记录行为
                action_log('member_edit', 'member', $member['id'], UID, $data['mobile']);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info = MemberModel::where('id', $id)->find();
        /* $agent_res=Db::name("admin_user")->where(['status'=>1])->where('role','in',[2,3,4])->select();
        foreach ($agent_res as $k=>$v){
            $agent_list[$v['id']]=$v['username'];
        }*/
        switch ($info['agent_id']) {
            case 0:
                $agent_status = '普通用户';
                break;
            case 1:
                $agent_status = '一级代理商';
                break;
            case 2:
                $agent_status = '二级代理商';
                break;
            default:
                $agent_status = '三级代理商';
        }
        $info['agent_id'] = $agent_status;
        $agent_list = MemberModel::where('agent_id', '>', 0)->column('name', 'id');
        unset($info['passwd'], $info['paywd']);
//        开启才能看隐私信息
        $privacy = cookie('__privacy__');
        if($privacy == 'close'){
            $info['id_card'] = privacy_info_switch('id_card',$info['id_card']);
            $info['mobile'] = privacy_info_switch('mobile',$info['mobile']);
            $privacy_mobile = ['static', 'mobile', '用户名/手机号'];
            $privacy_id_card = ['static', 'id_card', '身份证号'];
        }else{
            $privacy_mobile = ['text', 'mobile', '用户名/手机号'];
            $privacy_id_card = ['text', 'id_card', '身份证号'];
        }
        return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
          ['hidden', 'id'],
//          ['text', 'mobile', '用户名/手机号', ''],
            $privacy_mobile,
          ['text', 'email', '邮箱', ''],
                         ['text', 'name', '姓名', ''],
//          ['text', 'id_card', '身份证号'],
            $privacy_id_card,
          ['text', 'bank_card', '银行卡', ''],
//        ['select', 'agent_far', '选择代理', '请选择代理',$agent_list],
          ['text', 'remarks', '备注'],
          ['password', 'passwd', '登录密码', '不修改请留空。修改时必填，6-20位'],
          ['text', 'paywd', '支付密码', '不修改请留空。修改时必填，6-20位'],
            ['number', 'withdrawal_commission', '手续费', '费率按 百分比%。 填写 0 表示按默认提现配置的手续费,否则以填写的费率计算。'],
          ['radio', 'status', '登录状态', '', ['禁用', '启用']],
          ['radio', 'is_del', '账户状态', '', ['正常', '注销/删除']],
          ['radio', 'id_auth', '认证状态', '', ['处理中', '通过']],
          ['static', 'agent_id', '代理商状态', ''],])
          ->addSelect('pid', '选择代理商', '请选择代理商', $agent_list)
          ->addSelectAjax('partner_parent_id', '邀请人', '上级推荐用户', [
              'table' => 'member',
              'id' => 'id',
              'name' => 'mobile',
              'search' => 'name|mobile'
          ])
          ->setFormData($info) // 设置表单数据
          ->fetch();
    }

    //重置支付密码
    public function reset_payword()
    {
        $id = input('param.id');
        if ($id === null) $this->error('缺少参数');
        $info = MemberModel::where('id', $id)->find();
        $mobile = substr($info['mobile'], -6);
        $newpwd = Hash::make((string)$mobile);
        $data['id'] = $id;
        $data['paywd'] = $mobile;
        if (MemberModel::update($data)) {
//            Hook::listen('member_edit', $member);
            // 记录行为
            action_log('member_edit', 'member', $info['id'], UID, $info['mobile'] . '-重置密码');
            $this->success('编辑成功', cookie('__forward__'));
        } else {
            $this->error('编辑失败');
        }
    }

    //重置支付密码  作废
    public function reset_payword2()
    {
        $id = input('param.id');
        if ($id === null) $this->error('缺少参数');
        $info = MemberModel::where('id', $id)->find();
        $mobile = substr($info['mobile'], -6);
        $newpwd = Hash::make((string)$mobile);
        $newid = Db::name('member')->where(["id" => $id])->update(['passwd' => $newpwd]);
        if ($newid) {
//            Hook::listen('member_edit', $member);
            // 记录行为
            action_log('member_edit', 'member', $info['id'], UID, $info['mobile'] . '-重置密码');
            $this->success('编辑成功', cookie('__forward__'));
        } else {
            $this->error('编辑失败');
        }
    }

    public function delete($record = [])
    {
        $table_name = input('param.table');
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids = (array)$ids;
        $field = input('param.field', 'is_del');
        $member_mobile = MemberModel::where('id', 'in', $ids)->column('mobile');
        $pk = Db('member')->getPk();
        $map[]=[$pk,'in', $ids];
        $result = Db('member')->where($map)->setField($field, 1);
        if (false !== $result) {
            Cache::clear();
            // 记录行为日志
            if (!empty($member_mobile)) {
                call_user_func_array('action_log', ['member_delete', 'member', $ids, UID, implode('、', $member_mobile)]);
            }
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 设置用户状态：禁用、启用
     * @param string $type 类型：enable/disable
     * @param array $record
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public function setStatus($type = '', $record = [])
    {
        $table_name = input('param.table');
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $member_id = is_array($ids) ? '' : $ids;
        $member_status = MemberModel::where('id', 'in', $ids)->column('status');
        return parent::setStatus($type, ['member_' . $type, 'member', $member_id, UID, implode('、', $member_status)]);
    }

    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    public function quickEdit($record = [])
    {
        $id = input('post.pk', '');
        $field = input('post.name', '');
        $value = input('post.value', '');
        $table = input('post.table', '');
        $status = MemberModel::where('id', $id)->value($field);

        if($field == 'agent_far' && $value > 0){
//                    A的代理是S，B是A邀请的，那么B的代理也是S
            //                    A的代理是S，B是A邀请的，那么B的代理也是S
            $us    = Member::where('id', $id)->find();
            $path  = $us->partner_parent_net . ',' . $us->id;
            $list  = Member::where('1=1')
                ->field('id,name')
                ->where([['partner_parent_net', 'like', $path . '%']])
                ->select();
            foreach ($list as $key => $v){
                Db::name('member')
                    ->where('id',$v['id'])->update(['agent_far'=>$value]);
            }

        }
        // $status = Db::name('member')->where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $status . ')，新值：(' . $value . ')';
        return parent::quickEdit(['member_edit', 'member', $id, UID, $details]);
    }

    //推荐路径
    public function treebak()
    {
        //return '待更新';
        // 获取节点数据
        $uid = input('uid');
        $sql = 'SELECT id,level,mobile,name,re_id FROM lmq_member WHERE FIND_IN_SET(id,queryChildrenAreaInfo(' . $uid . '))';
        $data_list = Db::query($sql);
        $level_array = ['普通投资者', '初级合伙人', '中级合伙人', '高级合伙人', '特级合伙人', '核心合伙人'];
        foreach ($data_list as $key => $value) {
            $value['level_text'] = $level_array[$value['level']] ?? '';
            $data_list[$key] = $value;
        }
        $max_level = input('max', 0);
        $menus = $this->getTree($data_list, $max_level, $uid);

        $this->assign('menus', $menus);
//        $this->assign('tab_nav', ['tab_list' => $tab_list]);
//        $this->assign('page_title', '节点管理');
        return $this->fetch();
    }

    /**
     * 获取嵌套式节点
     * @param array $lists 原始节点数组
     * @param int $pid 父级id
     * @param int $max_level 最多返回多少层，0为不限制
     * @param int $curr_level 当前层数
     * @return string
     * @author 路人甲乙
     */
    private function getTreebak($lists = [], $max_level = 0, $pid = 0, $curr_level = 1)
    {
        $result = '';
        foreach ($lists as $key => $value) {
            if ($value['re_id'] == $pid) {
//                $disable  = $value['status'] == 0 ? 'dd-disable' : '';
                // 组合节点
                $result .= '<li class="dd-item dd3-item " data-id="' . $value['id'] . '">';
                $result .= '<div class="dd-handle dd3-handle">拖拽</div><div class="dd3-content"><i class="' . $value['icon'] . '"></i> ' . $value['mobile'] . '（' . $value['level_text'] . '）';
                if ($value['url_value'] != '') {
                    $result .= '<span class="link"><i class="fa fa-link"></i> ' . $value['mobile'] . '（' . $value['level_text'] . '）</span>';
                }
                $result .= '<div class="action">';
//                $result .= '<a href="'.url('add', ['module' => $value['module'], 'pid' => $value['id']]).'" data-toggle="tooltip" data-original-title="新增子节点"><i class="list-icon fa fa-plus fa-fw"></i></a><a href="'.url('edit', ['id' => $value['id']]).'" data-toggle="tooltip" data-original-title="编辑"><i class="list-icon fa fa-pencil fa-fw"></i></a>';
//                if ($value['status'] == 0) {
//                    // 启用
//                    $result .= '<a href="javascript:void(0);" data-ids="'.$value['id'].'" class="enable" data-toggle="tooltip" data-original-title="启用"><i class="list-icon fa fa-check-circle-o fa-fw"></i></a>';
//                } else {
//                    // 禁用
//                    $result .= '<a href="javascript:void(0);" data-ids="'.$value['id'].'" class="disable" data-toggle="tooltip" data-original-title="禁用"><i class="list-icon fa fa-ban fa-fw"></i></a>';
//                }
                $result .= '</div>';
                $result .= '</div>';
                if ($max_level == 0 || $curr_level != $max_level) {
                    unset($lists[$key]);
                    // 下级节点
                    $children = $this->getTree($lists, $max_level, $value['id'], $curr_level + 1);
                    if ($children != '') {
                        $result .= '<ol class="dd-list">' . $children . '</ol>';
                    }
                }
                $result .= '</li>';
            }
        }
        return $result;
    }
    
    
    //推荐路径
    public function tree()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $uid = input('uid',0); 
        $level_array = Funduserlevelarray(); 
        
        $parentId = 0;
        if($uid == 0){
            $list = MemberModel::where('1=1')
            ->field('id,partner_parent_id as pid,name as username,mobile as name,mobile,is_buy,level')
            ->order('partner_parent_id asc')
            ->select()
            ->toArray();
            $agent = MemberModel::where('1=1')
            ->where((new UserService())->getAgentSql('id'))
            ->field('id,partner_parent_id as pid,name as username,mobile as name,mobile,is_buy,level')
            ->order('partner_parent_id asc')
            ->select()
            ->toArray();
            foreach ($list as &$value) {
                if (!in_array($value, $agent)) {
                    $value['not_in'] = 1;
                }else{
                    $value['not_in'] = 0;
                };
                $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            }
        }else{
            $us = MemberModel::where('id', $uid)->field('id,0 as pid,name as username,mobile as name,mobile,is_buy,level,partner_parent_net,partner_parent_level,partner_parent_id')->find();
            $parentId = $us->partner_parent_id;
            $path = $us->partner_parent_net . ',' . $us->id;
            $us->mobile = privacy_info_switch('mobile',$us->mobile);

            $list = MemberModel::where('1=1')
            ->field('id,partner_parent_id as pid,name as username,mobile as name,mobile,is_buy,level')
            ->where([['partner_parent_net', 'like', $path . '%']])
            ->order('partner_parent_id asc')
            ->select()
            ->toArray();
            
            $agent = MemberModel::where('1=1')
            ->field('id,partner_parent_id as pid,name as username,mobile as name,mobile,is_buy,level')
            ->where([['partner_parent_net', 'like', $path . '%']])
            ->where((new UserService())->getAgentSql('id'))
            ->order('partner_parent_id asc')
            ->select()
            ->toArray();
            foreach ($list as &$value) {
                if (!in_array($value, $agent)) {
                    $value['not_in'] = 1;
                }else{
                    $value['not_in'] = 0;
                };
                $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            }
            //加入自己当第一级
            $list[] = $us->toArray();
            
        }
        
        foreach ($list as &$value) {
            $isBuy = '否';
            if ($value['is_buy'] == 1) {
                $isBuy = '是';
            }
            if (empty($value['username'])) {
                $value['username'] = '未认证';
            }
            $value['title'] = "[用户id:{$value['id']}]-[账户:{$value['mobile']}]-[是否投资:{$isBuy}]-[合伙人级别:{$level_array[$value['level']]}]-[姓名:{$value['username']}]";
            if ($value['not_in'] == 1) {
                $value['title'] = 'XXX';
            }
        }
        unset($value);
        $menus = Tree::toLayer($list);
        $menus = $this->buildJsTree($menus); 
        $this->assign('menus', $menus);
        $this->assign('parents_url', url('member/index/tree', ['uid' => $parentId]));
        return $this->fetch();
    }
    
    private function buildJsTree($menus = [] )
    {
        $result = '';
        if (!empty($menus)) {
            $option = [
                'opened' => true,
                'selected' => false,
                'icon' => '',
            ];
            foreach ($menus as $menu) {
                $option['icon'] = $menu['icon'];
                if (isset($menu['child'])) {
                    $result .= '<li id="' . $menu['id'] . '" data-jstree=\'' . json_encode($option) . '\'>' . $menu['title'] . $this->buildJsTree($menu['child'] ) . '</li>';
                } else {
                    $result .= '<li id="' . $menu['id'] . '" data-jstree=\'' . json_encode($option) . '\'>' . $menu['title'] . '</li>';
                }
            }
        }
        return '<ul>' . $result . '</ul>';
    }
    
    public function statistics($id = '')
    {
        
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        if (!empty($id)) {
            $map[] = ['id','=',$id];
        }
        $data_list = MemberModel::where($map)
        ->field('*')
        ->order($order)
        ->paginate()->each(function($item){
            $uid = $item->id;
            //充值总额  活动金总额  提现总额   投资次数  盈亏总额  直属人数  合伙人总收益 
            $item->recharge = Recharge::where('mid',$item->id)->where('status',1)->sum('money');
            $item->recharge = round($item->recharge / 100 , 2);
            $item->activity = Record::where('mid',$uid)->whereIn('type',[36,37])->sum('affect_activity');//活动金总额
            $item->activity = round($item->activity / 100 , 2);
            $item->withdraw = Withdraw::where('mid',$item->id)->where('status',1)->sum('money');
            $item->withdraw = round($item->withdraw / 100 , 2);
            $item->invest_total = FundOrderGs::where('uid',$uid)->where('status', '>', 0)->count();//跟单数
            $item->invest_total += FundOrder::where('uid',$uid)->where('status', '>', 0)->count();//基金数
            $item->income_invest = FundIncomeLog::where('uid',$uid)->sum('money');//盈亏总额 
            $item->income_invest = round($item->income_invest , 2);
            $item->directly = Member::where('partner_parent_id',$uid)->where(['is_buy'=>1])->count();//直属人数
            $item->income_partner = FundSalaryLog::where('uid',$uid)->sum('price');//合伙人收益
            $item->mobile = privacy_info_switch('mobile',$item->mobile);
        });
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        
        return ZBuilder::make('table')
//         ->setSearchArea([
//             ['text', 'm.name', '用户名', 'like'],
//             ['text', 'm.mobile', '手机号', 'like'],
//             ['select', 'o.type', '工资类型', '', '', [1 => '团队分成', 2 => '周工资', 3 => '补贴']],
//             ['select', 'o.info_type', '来源', '', '', [1 => '跟单', 2 => '基金']],
//             ['daterange', 'o.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']]
//         ])
        //->addOrder('id') // 添加排序
        ->addColumns([ // 批量添加数据列
            ['id', '用户id'],
            ['mobile', '用户手机'],
            ['name', '用户名称'],
            ['recharge', '充值总额'],
            ['activity', '活动金总额'],
            ['withdraw', '提现总额'],
            ['invest_total', '投资次数'],
            ['income_invest', '投资盈亏'],
            ['directly', '直属人数'],
            ['income_partner', '合伙人收益'],
        ])->setTableName('fund')
        ->addOrder('id')->setRowList($data_list) // 设置表格数据
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
        //->setColumnWidth('settle_time,create_time', 140)
        ->fetch(); // 渲染模板
        
        
    }

//    新增用户
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            $result = $this->validate($data, 'Member.add');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            $isExists = Db::name('member')->where('mobile', $data['mobile'])->where('is_del', 0)->count();
            if ($isExists > 0) {
                $this->error('该手机号已经注册');
            }
            if(!$data['password']){
                $data['password'] = '123456';
            }
            if(!$data['paywd']){
                $data['paywd'] = '123456';
            }
            $result_up = Member::saveData($data);
            if ($result_up['status'] != 1) {
                $this->error('添加失败');
            } else {
                //数据报表
                if($data['role_name'] == '普通会员'){
                    $data_report ['create_time'] = time();
                    $data_report ['new_reg'] = 1;
                    DataReportModel::appendDataReport($data_report);
                }

                $this->success('添加成功', cookie('__forward__'));
            }
        }
        return ZBuilder::make('form')
            ->setPageTitle('新增用户') // 设置页面标题
            ->setPageTips('此操作添加真实用户，手机号不可更改，请填写真实号码！', 'danger')
            ->addFormItems([ // 批量添加表单项
                ['text', 'mobile', '手机号', ''],
                ['text', 'email', '邮箱', ''],
//                ['text', 'id_card', '身份证号'],
                ['text', 'name', '姓名', ''],
                ['text', 'remarks', '备注'],
                ['password', 'password', '登录密码', '不填默认为：123456'],
//                ['password', 'paywd', '支付密码', '不填默认为：123456'],
               ['number', 'withdrawal_commission', '手续费', '费率按 百分比%。 填写 0 表示按默认提现配置的手续费,否则以填写的费率计算'],
                ['radio', 'role_name', '白名单', '', ["白名单" => "白名单", "普通会员" => "普通会员"],'普通会员'],
//                ['radio', 'status', '登录状态', '', ['禁用', '启用'],'1'],
//                ['radio', 'is_del', '账户状态', '', ['正常', '注销/删除'],'0'],
                ['radio', 'id_auth', '认证状态', '', ['未认证', '已认证'],'1'],
            ])
            ->fetch();
    }

//    批量添加
    public function addbatch()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
//            $data['create_ip'] = get_client_ip(1);
//            $data['create_time'] = time();
            if(!$data['password']){
                $data['password'] = '123456';
            }
//            if(!$data['paywd']){
//                $data['paywd'] = '123456';
//            }
            $number = $data['number'];
            unset( $data['number']);
            $n = 0;
            $data['from_to'] = '批量添加';
            for ( $i = 0; $i < $number; $i++){
                $data['mobile'] = $this->generateRandomPhoneNumber();
                $result_up = Member::saveData($data);
                if($result_up['status'] == 1){
                    $n++;
                }
            }
            //数据报表
            if($data['role_name'] == '普通会员'){
                $data_report ['create_time'] = time();
                $data_report ['new_reg'] = $n;
                DataReportModel::appendDataReport($data_report);
            }
            $this->success('添加成功'.$n.'个账户', cookie('__forward__'));

        }

        return ZBuilder::make('form')
            ->setPageTitle('批量添加') // 设置页面标题
            ->setPageTips('批量添加仅作为测试用户使用！', 'danger')
            ->addNumber('number', '生成数量', '', '1', '1', '100')
            ->addFormItems([ // 批量添加表单项
                ['password', 'password', '登录密码', '不填默认为：123456'],
//                ['password', 'paywd', '支付密码', '不填默认为：123456'],
                ['radio', 'role_name', '白名单', '', ["白名单" => "白名单", "普通会员" => "普通会员"],'白名单'],
//                ['radio', 'status', '登录状态', '', ['禁用', '启用'],'1'],
//                ['radio', 'is_del', '账户状态', '', ['正常', '注销/删除'],'0'],
                ['radio', 'id_auth', '认证状态', '', ['未认证', '已认证'],'1'],
            ])
            ->fetch();
    }


//    随机手机号
    public function generateRandomPhoneNumber() {
        // 第一位固定为1
        $phoneNumber = '1';

        // 第二位是3-9之间的随机数字
        $secondDigit = rand(3, 9);
        $phoneNumber .= $secondDigit;

        // 剩余8位是随机数字
        for ($i = 0; $i < 9; $i++) {
            $phoneNumber .= rand(0, 9);
        }

        return $phoneNumber;
    }
    
    public function net_update(){
        if (Cache::get('stopAjax_update')) {
            $this->error('过于频繁，请稍后再试，更新进行中剩余时间'.(180-(time()-Cache::get('stopAjax_update'))).'秒');
        }
        Cache::set('stopAjax_update',time(),180);
       (new PartnerSettleService())->netUpdate();
       (new PartnerSettleService())->netWork();
       (new PartnerSettleService())->netUpdate();
       (new PartnerSettleService())->netWork();
       Cache::rm('stopAjax_update');
       $this->success('更新成功');
    }


    public function privacy()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            $privacy_pass = Db::name('admin_user')->where('id', UID)->find();
            if (!Hash::check((string)$data['password'], $privacy_pass['password'])) {
                $log['remark'] ='ID:'.UID.'.用户名：'.$privacy_pass['username'].'查看隐私信息失败';
                $log['status'] =2;
//                if(ce_privacy_log($log)){
//                }
                action_log('view_privacy', 'member', UID, UID,$log['remark']);
                    $this->error('密码错误');

            }else{
                $log['remark'] ='ID:'.UID.'.用户名：'.$privacy_pass['username'].'查看了隐私信息';
                $log['status'] =1;
//                if(ce_privacy_log($log)){
                cookie('__privacy__', 'open');
                action_log('view_privacy', 'member', UID, UID,$log['remark']);
                $this->success('验证成功', cookie('__forward__'),'_parent_reload');
//                }
//                $this->error('验证失败');
            }
        }
        $privacy = cookie('__privacy__');
        if($privacy == 'open'){
            $this->error('隐私信息已开启');
        }else{
            return ZBuilder::make('form')
                ->setPageTitle('查看隐私信息') // 设置页面标题
                ->setPageTips('请输入密码验证！', 'danger')
                ->addPassword('password', '登录密码')
                ->fetch();
        }


    }
}

