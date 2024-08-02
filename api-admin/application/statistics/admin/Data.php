<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
// +----------------------------------------------------------------------
namespace app\statistics\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\fund\model\FundOrderGs;
use app\fund\model\FundUserlevel as FundUserlevelModel;

use app\interest\model\Interest;
use app\member\model\Member;
use app\member\model\MemberLoginRecord;
use app\money\model\Money;
use app\money\model\Recharge as RechargeModel;
use app\statistics\model\DataReport as DataReportModel;
use app\stock\model\Borrow;
use think\Db;
use app\common\service\UserService;

class Data extends Admin
{
//    主站点
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $for_user = Db::name('admin_user')->where('id', UID)->value('for_user');
        $where = [];
        if ($for_user) {
            $where['for_user'] = $for_user;
        }
        $day_start_time = strtotime(date('Y-m-d'));
        $month_time = time() - 30 * 24 * 3600; //一个月时间不登录的用户
        //每天添加一条数据
        DataReportModel::appendDataReport(['create_time' => $day_start_time]);
        $order = 'create_time desc';
        $data_list = DataReportModel::where($map)
          ->where($where)
          ->order($order)
          ->paginate();
        $day_data_report = DataReportModel::getDayData();//当天的报表数据
        foreach ($data_list as $key => $value) {
            if ($value['create_time'] == $day_start_time) { //当天的数据读取实时的
//                $data_list[$key]['effective_contract'] = $day_data_report['effective_contract'];
//                $data_list[$key]['new_open_contract'] = $day_data_report['new_open_contract'];
//                $data_list[$key]['settlement_contract'] = $day_data_report['settlement_contract'];
                $data_list[$key]['effective_user'] = $day_data_report['effective_user'];
                $data_list[$key]['user_run'] = $day_data_report['user_run'];
                $data_list[$key]['oline'] = $day_data_report['oline'];
                $data_list[$key]['stock_price'] = $day_data_report['stock_price'];
            }else{
                $oneMonthAgo = strtotime('-1 month', $value['create_time']);
                $user_run = Member::where('last_login_time', '<=', $oneMonthAgo)->count();
                $data_list[$key]['user_run'] = $user_run;

                $startOfDay = strtotime(date('Y-m-d', $value['create_time']).' 00:00:00'); // 当天开始的时间戳
                $endOfDay = strtotime(date('Y-m-d', $value['create_time']).' 23:59:59'); // 当天结束的时间戳

                $user_oline = MemberLoginRecord::where('login_time', '>=', $startOfDay)
                    ->where('login_time', '<=', $endOfDay)
                    ->group('mid')
                    ->count(); // 直接计算满足条件的记录数
                $data_list[$key]['oline'] = $user_oline;
            }

//            $class_effective_contract = $value['effective_contract'] > 1 ? 'danger' : '';
//            $class_new_open_contract = $value['new_open_contract'] > 1 ? 'danger' : '';
//            $class_settlement_contract = $value['settlement_contract'] > 1 ? 'danger' : '';
            $class_effective_user = $value['effective_user'] > 1 ? 'danger' : '';
            $class_user_run = $value['user_run'] > 1 ? 'danger' : '';
            $class_oline = $value['oline'] > 1 ? 'danger' : '';
            $class_stock_price = $value['stock_price'] > 1 ? 'danger' : '';
            $class_new_reg = $value['new_reg'] > 1 ? 'danger' : '';
            $class_day_recharge = $value['day_recharge'] > 1 ? 'danger' : '';
            $class_recharge_cashback = $value['recharge_cashback'] > 1 ? 'danger' : '';
            $class_withdraw = $value['withdraw'] > 1 ? 'danger' : '';
            $class_user_first_recharge = $value['user_first_recharge'] > 1 ? 'danger' : '';
//            $data_list[$key]['effective_contract'] = "<p class='{$class_effective_contract}'>" . $value['effective_contract'] . "</p>";
//            $data_list[$key]['new_open_contract'] = "<p class='{$class_new_open_contract}'>" . $value['new_open_contract'] . "</p>";
//            $data_list[$key]['settlement_contract'] = "<p class='{$class_settlement_contract}'>" . $value['settlement_contract'] . "</p>";
            $data_list[$key]['effective_user'] = "<p class='{$class_effective_user}'>" . $value['effective_user'] . "</p>";
            $data_list[$key]['user_run'] = "<p class='{$class_user_run}'>" . $value['user_run'] . "</p>";
            $data_list[$key]['oline'] = "<p class='{$class_oline}'>" . $value['oline'] . "</p>";
            $data_list[$key]['stock_price'] = "<p class='{$class_stock_price}'>" . $value['stock_price'] . "</p>";
            $data_list[$key]['new_reg'] = "<p class='{$class_new_reg}'>" . $value['new_reg'] . "</p>";
            $data_list[$key]['day_recharge'] = "<p class='{$class_day_recharge}'>" . $value['day_recharge'] . "</p>";
            $data_list[$key]['recharge_cashback'] = "<p class='{$class_recharge_cashback}'>" . $value['recharge_cashback'] . "</p>";
            $data_list[$key]['withdraw'] = "<p class='{$class_withdraw}'>" . $value['withdraw'] . "</p>";
            $data_list[$key]['user_first_recharge'] = "<p class='{$class_user_first_recharge}'>" . $value['user_first_recharge'] . "</p>";
            $data_list[$key]['date_strtotime'] = $value['create_time'];
            $data_list[$key]['month_strtotime'] = $value['create_time'] - 30 * 24 * 3600; //一个月时间不登录的用户

//            跟单
            $data_list[$key]['purchase_tracking_total'] = FundOrderGs::where('create_time', '>', $value['create_time'])
                ->where('status','>',0)
                ->count();
            $data_list[$key]['month_strtotime_balance'] = FundOrderGs::where('create_time', '>', $value['create_time'])
                ->where('status','=',3)
                ->count();
            $start_time = strtotime(date('Y-m-d', $value['create_time']) . '00:00:00');
            $end_time = strtotime(date('Y-m-d', $value['create_time']) . '23:59:59');
            $data_list[$key]['interest_tracking_total'] = Db::name('interest')
                ->where('create_time', '>=', $start_time)
                ->where('create_time', '<=', $end_time)
                ->count();

        }
        // 分页数据
        $page = $data_list->render();
//        $month_time = time()  - 30*24*3600; //一个月时间不登录的用户
        $day_start_time = strtotime(date('Y-m-d'));
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
          ->setTableName('data_report')
//          ->setSearch(['create_time' => '时间'], '', '', true) // 设置搜索框
          ->addOrder('create_time,id desc') // 添加排序
          ->hideCheckbox()
          ->addColumns([ // 批量添加数据列
//                ['id', 'ID'],
            ['create_time', '日期', 'date'],
            ['purchase_tracking_total', '购买优投'],
            ['month_strtotime_balance', '结算优投'],
//            ['interest_tracking_total', '购买小金库', 'link', url('userInterest', ['create_time' => '__date_strtotime__']), '', ''],
//            ['effective_contract', '有效合约(个)'],
//            ['new_open_contract', '新开合约(个)', 'link', url('contract', ['status' => 1, 'create_time' => '__date_strtotime__']), '_blank', 'pop'],
//            ['settlement_contract', '结算合约(个)', 'link', url('contract', ['status' => 2, 'create_time' => '__date_strtotime__']), '_blank', 'pop'],
            ['effective_user', '有效用户(个)'],
            ['user_recharge', '用户充值(个)', 'link', url('userRecharge', ['create_time' => '__date_strtotime__']), '', ''],
            ['user_first_recharge', '当日首充(个)'],
            ['user_run', '用户跑(个)', 'link', url('userlist', ['last_login_time' => '__month_strtotime__']), '', '', '用信户息'],
            ['new_reg', '新注册用户数(个)', 'link', url('userlist', ['create_time' => '__date_strtotime__']), '', '', '用户信息'],
            ['oline', '当日在线(个)', 'link', url('userlistOnline', ['online_time' => '__date_strtotime__']), '', '', '用户信息'],
            ['day_recharge', '今日充值(元)'],
            ['recharge_cashback', '充值返现(元)'],
            ['withdraw', '提现(元)'],
            ['stock_price', '股市价值(元)'],
          ])
//            ->addTimeFilter('create_time') // 添加时间段筛选
            ->setSearchArea([
                ['daterange', 'create_time', '时间', '', '', ['format' => 'YYYY-MM-DD']],
            ])
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
          ->setRowList($data_list) // 设置表格数据
          ->fetch(); // 渲染模板
    }

    /**
     * 充值列表
     * @return mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function userRecharge()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $day_start_time = input('create_time');
        $end_time = $day_start_time + 24 * 3600;
        $map[]=['money_recharge.create_time','>=', $day_start_time];
        $map[]=['money_recharge.create_time','<', $end_time];
        $map[]=['money_recharge.status','eq', 1];
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';

        foreach ($map as $key => $value) {
            if($value[0] == 'create_time'){
                unset( $map[$key] );
            }
        }
        // 数据列表
        $data_list = RechargeModel::getAll($map, $order);
        foreach ($data_list as &$value){
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
        }
        return ZBuilder::make('table')
          ->setSearch(['mid' => '用户ID', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['order_no', '订单号'],
            ['mobile', '手机号'],
            ['name', '姓名'],
            ['money', '金额'],
            ['status', '状态'],
//            ['is_auto', '是否自动到账', ['0' => "否", "1" => "是"]],
            ['unit', '币'],
            ['type', '充值方式', ['alipay' => "支付宝", "wechatpay" => "微信支付", "bank" => "银联支付", "currency" => "数字货币", "transfer" => "线下支付"]],
            ['create_time', '充值时间', 'datetime'],
            ['right_button', '操作', 'btn']
          ])
          ->addFilter('money_recharge.status') // 添加筛选
          ->hideCheckbox()
          ->replaceRightButton(['status' => '成功'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
          ->setRowList($data_list)
          ->fetch(); // 渲染模板
    }

//    购买小金库
    public function userInterest()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $day_start_time = input('create_time');
        $end_time = $day_start_time + 24 * 3600;
        $map[]=['i.create_time','>=', $day_start_time];
        $map[]=['i.create_time','<', $end_time];
        $order = $this->getOrder();
        foreach ($map as $key => $value) {
            if($value[0] == 'create_time'){
                unset( $map[$key] );
            }
        }
        // 数据列表
        $data_list = Interest::getAll($map, $order);
        foreach ($data_list as &$value){
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
        }
        return ZBuilder::make('table')
            ->setSearch(['mid' => '用户ID', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['order_number', '订单号'],
                ['mobile', '手机号'],
                ['username', '姓名'],
                ['money', '金额'],
                ['create_time', '时间', 'datetime'],
            ])
            ->hideCheckbox()
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }

    //合约相关数据
    public function contract()
    {
        $status = input('status', '');
        $day_start_time = input('create_time');
        $end_time = $day_start_time + 24 * 3600;
        // 获取查询条件

        $map[]=['b.status','=' ,$status];

        $map[] = ['b.create_time', '>=', $day_start_time];
        $map[] = ['b.create_time', '<', $end_time];

        // 排序
        if (empty($order)) {
            $order = 'b.create_time desc';
        }
        // 数据列表
        $data_list = Borrow::getList($map, $order);
        foreach ($data_list as $key => $value) {
            $email = $value['email'] ?: '--';
            $data_list[$key]['user_info'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
            $data_list[$key]['total_money'] = "<p>" . $value['borrow_money'] . "</p><p>" . $value['init_money'] . "</p>";
            $data_list[$key]['time'] = "<p>" . date('Y-m-d H:i:s', $value['verify_time']) . "</p><p>" . date('Y-m-d H:i:s', $value['end_time']) . "</p>";
        }
        // 分页数据
        $page = $data_list->render();
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        $status_str = $status_arr[input('status')];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -14) . "_export?status=2";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -14) . "_export?" . $_SERVER["QUERY_STRING"];
        }
//        $btn_excel = ['title' => '导出EXCEL表', 'icon' => 'fa fa-fw fa-download', 'href' => url($excel_url, '', '') ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')->setPageTitle($status_str . '结算合约') // 设置页面标题
        ->setTableName('stock_borrow') // 设置数据表名
        ->setSearch(['order_id' => '订单号', 'mobile' => '手机号', 'type' => '配资类型'], '', '', true) // 设置搜索参数
        ->addOrder('order_id,name,create_time') // 添加排序
        ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
        ->hideCheckbox()->addColumns([ // 批量添加列
          ['sub_account', '子账号'], ['order_id', '订单号'], ['user_info', '手机号/邮箱'], ['name', '配资人'], ['type', '配资类型'], ['deposit_money', '保证金'], ['total_money', '配资金额/总操盘金额'], ['borrow_duration', '操盘期限'], ['status', '状态', 'status', '', ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束']], ['create_time', '申请时间', 'datetime'], ['time', '开始时间/终止时间'],
        ])
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch(); // 渲染页面
    }

    public function userlist()
    {
        $last_login_time = input('last_login_time', '');
        $online_time = input('online_time', '');;//当天开始时间
        $create_time = input('create_time', '');
        if ($last_login_time) {
            $map[]=['last_login_time','elt', $last_login_time];
        }
        if ($create_time) {
            $end_time = $create_time + 24 * 3600;
            $map[]=['create_time','>=', $create_time];
            $map[]=['create_time','<', $end_time];
        }
        if ($online_time) {
            $map[]=['last_login_time','egt', $online_time];
        }
        empty($order) && $order = 'id desc';
        $map[]=['is_del','=',0];
        // 数据列表
        $data_list = Member::getlist($map, $order);
        $agent_list = Member::where('agent_id', '>', 0)->column('name', 'id');
        $data = [];
        foreach ($data_list as $key => $value) {
            $data_list[$key]['agent_name'] = $agent_list[$value['pid']] ?? '';
            $email = $value['email'] ?? '--';
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            $value['id_card'] = privacy_info_switch('id_card',$value['id_card']);
            $data_list[$key]['user_us'] = "<p>" . $value['mobile'] . "</p><p>{$email}</p>";
            $data_list[$key]['user_info'] = "<p>" . $value['name'] . "</p><p>" . $value['id_card'] . "</p>";
            $data_list[$key]['invite'] = "<p>" . $value['invite_name'] . "</p><p>" . $value['invite_account'] . "</p>";
            $data_list[$key]['time'] = "<p>" . date("m-d H:i:s", $value['create_time']) . "</p><p>" . date("m-d H:i:s", $value['last_login_time']) . "</p>";
            $ip = long2ip($value['login_ip']);
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
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = ['title' => '导出EXCEL表', 'icon' => 'fa fa-fw fa-download', 'href' => url($excel_url, '', '')];
        $btn_report = ['title' => '报表', 'icon' => 'fa fa-fw fa-bar-chart', 'href' => url('report', ['id' => '__id__']), 'target' => '_blank'];
        $btn_consult = ['title' => '合约', 'icon' => 'fa fa-fw fa-align-left', 'href' => url('consult', ['id' => '__id__']), 'target' => '_blank'];
        return ZBuilder::make('table')->setSearch(['m.name' => '姓名', 'm.id_card' => '身份证', 'm.mobile' => '手机号']) // 设置搜索框
        ->hideCheckbox()
          ->setPageTitle('用户列表') // 设置页面标题
          ->setTableName('member') // 设置数据表名
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['user_us', '手机/邮箱'],
            ['user_info', '姓名/身份证号'],
            ['from_to', '来源'],
            ['remarks', '备注', $remarksdata],
            ['role_name', '角色'],
            ['account', '账户余额/活动金余额'],
            ['account2', '冻结金额/保证金金额'],
            ['frequency', '充值次数/提现次数'],
            ['agent', '代理姓名/下线人数'],
            ['invite', '邀请人姓名/邀请人账号'],
            ['login', '登录设备/登录IP'],
            ['time', '注册时间/最后登陆时间'],
//                ['status', '登录状态', 'switch'],
            ['is_del', '账户状态',
              [0 => '正常', 1 => '注销/删除']],
//                ['right_button', '操作', 'btn']
          ])->setTableName('member') // 批量添加顶部按钮
//            ->addTopButton('custem', $btn_excel)
//            ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮  ,last_login_time
//        ->addRightButton('aedit', ['href' => url('reset_payword', ['id' => '__id__']) ])
//            ->addRightButton('custom', $btn_report)
//            ->addRightButton('custom', $btn_consult)
          ->addOrder('id,id_auth,reg_time,status,is_del')->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch(); // 渲染模板
    }

//    当日在线
    public function userlistOnline()
    {
        $online_time = input('online_time', '');;//当天开始时间


        empty($order) && $order = 'ml.id desc';
        $map[]=['is_del','=',0];
        // 数据列表
        $startOfDay = strtotime(date('Y-m-d', $online_time).' 00:00:00'); // 当天开始的时间戳
        $endOfDay = strtotime(date('Y-m-d', $online_time).' 23:59:59'); // 当天结束的时间戳

        $data_list= MemberLoginRecord::alias('ml')
            ->join('member m','m.id=ml.mid', 'LEFT')
            ->join('money n','m.id=n.mid', 'LEFT')
            ->join('member_login_record r','m.id=r.mid', 'LEFT')
            ->where('ml.login_time', '>=', $startOfDay)
                ->where('ml.login_time', '<=', $endOfDay)
                ->group('ml.mid')
            ->order($order)
            ->paginate();
        $agent_list = Member::where('agent_id', '>', 0)->column('name', 'id');
        $data = [];
        foreach ($data_list as $key => $value) {
            $data_list[$key]['agent_name'] = $agent_list[$value['pid']] ?? '';
            $email = $value['email'] ?? '--';
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            $value['id_card'] = $value['id_card']?privacy_info_switch('id_card',$value['id_card']):'--';
            $data_list[$key]['user_us'] = "<p>" . $value['mobile'] . "</p><p>{$email}</p>";
            $data_list[$key]['user_info'] = "<p>" . $value['name'] . "</p><p>" . $value['id_card'] . "</p>";
            $data_list[$key]['invite'] = "<p>" . $value['invite_name'] . "</p><p>" . $value['invite_account'] . "</p>";
            $data_list[$key]['time'] = "<p>" . date("m-d H:i:s", $value['create_time']) . "</p><p>" . date("m-d H:i:s", $value['last_login_time']) . "</p>";
            $ip = long2ip($value['login_ip']);
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
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = ['title' => '导出EXCEL表', 'icon' => 'fa fa-fw fa-download', 'href' => url($excel_url, '', '')];
        $btn_report = ['title' => '报表', 'icon' => 'fa fa-fw fa-bar-chart', 'href' => url('report', ['id' => '__id__']), 'target' => '_blank'];
        $btn_consult = ['title' => '合约', 'icon' => 'fa fa-fw fa-align-left', 'href' => url('consult', ['id' => '__id__']), 'target' => '_blank'];
        return ZBuilder::make('table')->setSearch(['m.name' => '姓名', 'm.id_card' => '身份证', 'm.mobile' => '手机号']) // 设置搜索框
        ->hideCheckbox()
            ->setPageTitle('用户列表') // 设置页面标题
            ->setTableName('member') // 设置数据表名
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['user_us', '手机/邮箱'],
                ['user_info', '姓名/身份证号'],
                ['from_to', '来源'],
                ['remarks', '备注', $remarksdata],
                ['role_name', '角色'],
                ['account', '账户余额/活动金余额'],
                ['account2', '冻结金额/保证金金额'],
                ['frequency', '充值次数/提现次数'],
                ['agent', '代理姓名/下线人数'],
                ['invite', '邀请人姓名/邀请人账号'],
                ['login', '登录设备/登录IP'],
                ['time', '注册时间/最后登陆时间'],
//                ['status', '登录状态', 'switch'],
                ['is_del', '账户状态',
                    [0 => '正常', 1 => '注销/删除']],
//                ['right_button', '操作', 'btn']
            ])->setTableName('member') // 批量添加顶部按钮
//            ->addTopButton('custem', $btn_excel)
//            ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮  ,last_login_time
//        ->addRightButton('aedit', ['href' => url('reset_payword', ['id' => '__id__']) ])
//            ->addRightButton('custom', $btn_report)
//            ->addRightButton('custom', $btn_consult)
            ->addOrder('id,id_auth,reg_time,status,is_del')->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染模板
    }


//    首充报表
    public function firstChargeList()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->agentPartnerSearchMap($map,'money_recharge.mid');
        $map = (new UserService())->getAgentMap($map,'money_recharge.mid');
        $map[]=['money_recharge.status','eq', 1];
        $map[]=['money_recharge.is_first','eq', 1];
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = RechargeModel::getAll($map, $order);
        $totalsum = 0;
        foreach ($data_list as $key => $value) {
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            $value['id_card'] = $value['id_card']?privacy_info_switch('id_card',$value['id_card']):'--';
            $email = $value['email'] ?? '--';
            $data_list[$key]['user_us'] = "<p>" . $value['mobile'] . "</p><p>{$email}</p>";
            $data_list[$key]['user_info'] = "<p>" . $value['name'] . "</p><p>" . $value['id_card'] . "</p>";
            $data_list[$key]['total_money'] = RechargeModel::where('mid', $value['mid'])->where('status', 1)->sum('money');
            $money = Money::getMoney($value['mid']);
            $data_list[$key]['new_account'] = bcdiv($money['account'], 100, 2);
            $data_list[$key]['new_total'] = bcdiv($money['total'], 100, 2);
            $data_list[$key]['total_money'] = bcdiv($data_list[$key]['total_money'], 100, 2);
            $documentary = FundOrderGs::where('uid', $value['mid'])->where('status','>', 0)->count();
            $balance = FundOrderGs::where('uid', $value['mid'])->where('status','=', 3)->count();
            $data_list[$key]['documentary_balance'] = "<p>" . $documentary . "</p><p>".$balance."</p>";
            $data_list[$key]['top_name'] = Member::getTopLevel($value['mid'],$value['mid']);
            $totalsum = $totalsum + $value['money'];
        }
        $totalsum = RechargeModel::
                alias('money_recharge')
            ->where('money_recharge.status',1)
            ->where('money_recharge.is_first',1)
            ->sum('money');
        $html = <<<EOF
            <br><p>总金额为：{$totalsum}元</p>
EOF;

        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];

        return ZBuilder::make('table')
//            ->setSearch(['member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
            ->setSearchArea([
                ['text', 'member.name', '姓名', 'like'],
                ['text', 'member.mobile', '手机号', 'like'],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
                ['daterange', 'money_recharge.create_time', '充值时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['user_us', '手机/邮箱'],
                ['user_info', '姓名/身份证号'],
                ['role_name', '白名单', $this->user_role_name],
                ['new_total', '账户余额'],
                ['new_account', '可提余额'],
                ['money', '首充金额'],
                ['money', '实际到账'],
                ['total_money', '总充值金额'],
                ['type', '充值方式', ['alipay' => "支付宝", "wechatpay" => "微信支付", "bank" => "银联支付", "currency" => "数字货币", "transfer" => "线下支付"]],
                ['documentary_balance', '购买优投/结算优投'],
                ['create_time', '充值时间', 'datetime'],
            ])
            ->addFilter('money_recharge.status') // 添加筛选
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
            ->hideCheckbox()
            ->replaceRightButton(['status' => '成功'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
            ->setRowList($data_list)
            ->setColumnWidth('user_info', 200)
            ->setColumnWidth('user_us', 200)
            ->setExtraHtml($html, 'table_bottom')
            ->fetch(); // 渲染模板
    }
}