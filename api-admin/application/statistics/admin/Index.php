<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
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
use app\market\model\Position;
use app\money\model\Money;
use app\money\model\Record;
use app\statistics\model\ContractStatistics as ContractStatisticsModel;
use app\statistics\model\ProfitLoss as ProfitLossModel;
use app\statistics\model\RechargeWithdrawal as RechargeWithdrawalModel;
use app\statistics\model\UserStatistics as UserStatisticsModel;
use app\user\model\User as AdminUserModel;
use think\Db;
use think\facade\Config;

class Index extends Admin
{
    //注册会员统计
    public function index()
    {
        $webtype = webType();
        $where = [];
        if ($webtype == 2) {
            //$data['for_user'] = for_user();
            $where['for_user'] = for_user();
        }
        $req = request();
        $map = input('map');
        if ($map === "888") {
            //注册会员总数
            $total = $day = $trade = 0;
            $total = Db::name('member')
              ->where(['is_del' => 0])
              ->where($where)
              ->count();
            $time = strtotime(date('Y-m-d', time()));
            //当日注册会员
            $day = Db::name('member')
              ->where(['is_del' => 0])
              ->where('create_time', '>', $time)
              ->where($where)
              ->count();
            //当日交易会员数
            $trade = Db::name('stock_trust')
              ->where('trust_date', '>=', $time)
              ->where($where)
              ->group('sub_id')
              ->count();
            //累计配资总数
            $where = '';
            if ($webtype == 2) {
                $for_user = for_user();
                $where = " and for_user={$for_user} ";
            }
            $borrow_count = Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where status=1 ' . $where . 'GROUP BY member_id ) aa');
            $borrow_count = $borrow_count[0]['nums'];
            // 代理商总数
            $where = [];
            if ($webtype == 2) {
                $where['for_user'] = for_user();
            }
            $agent_counts = Db::name('admin_user')->where('role', 'in', [2, 3, 4])->where($where)->count();
            //1级
            $agent_one_counts = Db::name('member')
                ->where('agent_id >= 1')
              ->where($where)
              ->count();
            //2级
//            $agent_two_counts = Db::name('admin_user')
//              ->where(['status' => 1, 'role' => 3])
//              ->where($where)
//              ->count();
            $agent_two_counts = 0;
            //3ji
//            $agent_tree_counts = Db::name('admin_user')
//              ->where(['status' => 1, 'role' => 4])
//              ->where($where)
//              ->count();
            $agent_tree_counts = 0;
            // 代理商总数
//            $agent_counts = Db::name('admin_user')->where('role', 'in', [2, 3, 4])->where($where)->count();
            $agent_counts = Db::name('member')
                ->where('agent_id >= 1')
                ->where($where)
                ->count();
            return ZBuilder::make('table')
              ->setTemplate('registered')
              ->assign(['total' => $total, 'day' => $day, 'trade' => $trade, 'borrow_count' => $borrow_count, 'agent_counts' => $agent_counts, 'agent_one_counts' => $agent_one_counts, 'agent_two_counts' => $agent_two_counts, 'agent_tree_counts' => $agent_tree_counts, 'agent_counts' => $agent_counts])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('index', 'map=888') . '" width="95%" height="550px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //资金变动记录
    public function capital()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        if (empty($_GET['list_rows'])) {
            $listRows = 10;
        } else {
            $listRows = $_GET['list_rows'];
        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['r.for_user'] = $admin_user['for_user'];
        }
        // 数据列表
        $data_list = Record::getAll($map, $order, $listRows);
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
              5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
              5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        // 分页数据
        return ZBuilder::make('table')
          ->setSearch(['r.mid' => '用户ID', 'm.name' => '姓名', 'm.mobile' => '手机号']) // 设置搜索框
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['mobile', '手机号'],
            ['name', '姓名'],
            ['type', '类型'],
            ['affect', '变动资金'],
            ['account', '账户余额'],
            ['create_time', '时间', 'datetime'],
            ['info', '信息'],
          ])
          ->hideCheckbox()
          ->setTableName('money')
          ->addTopButton('custem', $btn_excel)
          ->addOrder('id,account,affect,create_time')
          ->setRowList($data_list)
          ->fetch(); // 渲染模板
    }

    public function capital_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData = Record::getAll($map, $order);
        foreach ($xlsData as $k => $v) {
            $v['create_times'] = date('Y-m-d H:i', $v['create_time']);
        }
        $title = "资金变动记录";
        $arrHeader = array('ID', '手机号', '姓名', '类型', '变动资金', '账户余额', '时间', '信息');
        $fields = array('id', 'mobile', 'name', 'type', 'affect', 'account', 'create_times', 'info');
        export($arrHeader, $fields, $xlsData, $title);
    }

    //充值提现统计
    public function recharge()
    {
        $req = request();
        $map = input('map');//子账户id
        $webtype = webType();
        $where = [];
        if ($webtype == 2) {
            //$data['for_user'] = for_user();
            $where['for_user'] = for_user();
        }
        if ($map === "888") {
            $r_total = $r_day = $w_total = $w_day = $m_total = $a_total = 0;
            //累计充值总额
            $r_total = Db::name('money_recharge')
              ->where(['status' => 1])
              ->where($where)
              ->sum('money');
            $r_total = money_convert($r_total);
            $time = strtotime(date('Y-m-d', time()));
            //当日充值金额
            $r_day = Db::name('money_recharge')
              ->where(['status' => 1])
              ->where('create_time', '>', $time)
              ->where($where)
              ->sum('money');
            $r_day = money_convert($r_day);
            //累计提现总额
            $w_total = Db::name('money_withdraw')
              ->where(['status' => 1])
              ->where($where)
              ->sum('money');
            $w_total = money_convert($w_total);
            //当日提现金额
            $w_day = Db::name('money_withdraw')
              ->where(['status' => 1])
              ->where('create_time', '>', $time)
              ->where($where)
              ->sum('money');
            $w_day = money_convert($w_day);
            //会员提现总额
            $m_total = Db::name('money_withdraw')
              ->where('status=1')
              ->where($where)
              ->sum('money');
            $m_total = money_convert($m_total);
            //代理商提现总额
            $a_total = Db::name('agent_withdraw')
              ->where('status=1')
              ->where($where)
              ->sum('money');
            $a_total = money_convert($a_total);
            return ZBuilder::make('table')
              ->setTemplate('recharge')
              ->assign(['r_total' => $r_total, 'r_day' => $r_day, 'w_total' => $w_total, 'w_day' => $w_day, 'm_total' => $m_total, 'a_total' => $a_total])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('recharge', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //股票交易统计
    public function trade()
    {
        $req = request();
        $map = input('map');//子账户id
        $webtype = webType();
        $where = [];
        if ($webtype == 2) {
            //$data['for_user'] = for_user();
            $where['for_user'] = for_user();
        }
        if ($map === "888") {
            $p_total = $trust_day = $num_u_day = $trade_day = $num_a_day = $win = 0;
            //持仓总额
            $res = Db::name('stock_position')
              ->where('stock_count', '>', 0)
              ->where(['buying' => 0])
              ->where($where)
              ->select();
            foreach ($res as $k => $v) {
                $p_total += $v['stock_count'] * $v['now_price'];
            }
            $p_total = money_convert($p_total);
            $time = strtotime(date('Y-m-d', time()));
            //当日委托总额
            $trust_day = Db::name('stock_trust')
              ->where('trust_date', '>=', $time)
              ->where($where)
              ->sum('amount');
            $trust_day = money_convert($trust_day);
            //当日委托次数
            $num_u_day = Db::name('stock_trust')
              ->where('trust_date', '>=', $time)
              ->where($where)
              ->count();
            //当日成交总额
            $trade_day = Db::name('stock_deal_stock')
              ->where('deal_date', '>=', $time)
              ->where('status', '<>', '0')
              ->where($where)
              ->sum('amount');
            $trade_day = money_convert($trade_day);
            //当日成交次数
            $num_a_day = Db::name('stock_deal_stock')
              ->where('deal_date', '>=', $time)
              ->where('status', '<>', '0')
              ->where($where)
              ->count();
            //盈亏总额
            $win = Db::name('stock_position')
              ->where('stock_count', '>', 0)
              ->where(['buying' => 0])
              ->where($where)
              ->sum('ck_profit');
            $win = money_convert($win);
            return ZBuilder::make('table')
              ->setTemplate('trade')
              ->assign(['p_total' => $p_total, 'trust_day' => $trust_day, 'num_u_day' => $num_u_day, 'trade_day' => $trade_day, 'num_a_day' => $num_a_day, 'win' => $win])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('trade', 'map=888') . '" width="100%" height="800px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //网站费用统计
    public function cost()
    {
        $req = request();
        $map = input('map');//子账户id
        $webtype = webType();
        $where = [];
        if ($webtype == 2) {
            //$data['for_user'] = for_user();
            $where['for_user'] = for_user();
        }
        if ($map === "888") {
            $trade_fee = $defer = $defer_y_day = $defer_s_day = $agent_commission = $frozen_deposit = $add_deposit = 0;
            //已收交易综合费
            $trade_fee = Db::name('stock_borrow')
              ->where('status', '>=', 1)
              ->where($where)
              ->sum('borrow_interest');
            $trade_fee = money_convert($trade_fee);
            //已收递延费
            $defer = Db::name('stock_detail')->where(['status' => '1'])->where($where)->sum('receive_interest');
            //代理商返佣
            //    $agent_commission = 0;//暂时留空 后续处理
            if ($webtype == 2) {
                //$data['for_user'] = for_user();
                $where['a.for_user'] = for_user();
            }
            $agent_commission = Db::name('admin_user')
              ->alias('a')
              ->join('agent_money_record w', 'a.id = w.uid', 'RIGHT')
              ->where('w.type=1')
              ->where($where)
              ->sum('affect');
            $agent_commission = money_convert($agent_commission);
            //保证金总额
            if ($webtype == 2) {
                //$data['for_user'] = for_user();
                $where['for_user'] = for_user();
            }
            $frozen_deposit = Db::name('stock_borrow')
              ->where(['status' => 1])
              ->where($where)
              ->sum('deposit_money');
            $frozen_deposit = money_convert($frozen_deposit);
            //追加保证金总额
            $add_deposit = Db::name('stock_addmoney')
              ->where(['status' => 1])
              ->where($where)
              ->sum('money');
            $add_deposit = money_convert($add_deposit);
            $time = strtotime(date('Y-m-d', time()));
            //当日应收递延费
            $defer_y_arrs = Db::name('stock_borrow')->where(['type' => '3', 'status' => '1', 'end_time' => ['gt', time()]])->where($where)->select();
            $interest = 0;
            foreach ($defer_y_arrs as $k => $v) {
                $arr_int[$k] = Db::name('stock_detail')->where(['borrow_id' => $v['id'], 'sort_order' => $v['sort_order'], 'deadline' => ['gt', time()]])->where($where)->field('interest')->find();
                $interest += $arr_int[$k]['interest'];
            }
            $defer_y_day = $interest;
            //当日实收递延费
            //$defer_s_day = 0;//暂时留空 后续处理
            $startToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endToday = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
            $defer_s_day = Db::name('stock_detail')->where(['status' => '1', 'repayment_time' => [['egt', $startToday], ['elt', $endToday], 'and']])->where($where)->sum('interest');
            return ZBuilder::make('table')
              ->setTemplate('cost')
              ->assign([
                'trade_fee' => $trade_fee,
                'defer' => $defer,
                'defer_y_day' => $interest,
                'defer_s_day' => $defer_s_day,
                'agent_commission' => $agent_commission,
                'frozen_deposit' => $frozen_deposit,
                'add_deposit' => $add_deposit,
              ])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('cost', 'map=888') . '" width="100%" height="800px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //会员账户
    public function member()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
              5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
              5) . "_export?" . $_SERVER["QUERY_STRING"].'&';
        }

        // 获取查询条件
        $map = $this->getMap();
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon' => 'fa fa-fw fa-download',
//          'href' => url($excel_url, '', '')
            'href' => url($excel_url.http_build_query([$map]), '', '')
        ];
        $webtype = webType();
        $where = [];
        $wherem = [];
//            if($webtype == 2){
//              $where['for_user'] = for_user();
//                $wherem['m.for_user'] = for_user();
//            }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $wherem['m.agent_far'] = $admin_user['for_user'];
        }
        $order = $this->getOrder();
        empty($order) && $order = 'create_time,m.id desc';
        if (empty($_GET['list_rows'])) {
            $listRows = 10;
        } else {
            $listRows = $_GET['list_rows'];
        }
        $simple = false;
        $config = [];

        $config = array_merge(Config::get('paginate'), $config);
        $listRows = $listRows ?: $config['list_rows'];
        $class = false !== strpos($config['type'], '\\') ? $config['type'] : '\\think\\paginator\\driver\\' . ucwords($config['type']);
        $page = isset($config['page']) ? (int)$config['page'] : call_user_func([
          $class,
          'getCurrentPage',
        ], $config['var_page']);
        $page = $page < 1 ? 1 : $page;
        $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([$class, 'getCurrentPath']);
        $total = Db::table('lmq_member')
          ->alias('m')
          ->where($map)
          ->where($wherem)
          ->order($order)
          ->join('lmq_money o', 'm.id = o.mid')->count();
        $results = Db::table('lmq_member')
          ->alias('m')
          ->where($map)
          ->where($wherem)
          ->order($order)
          ->field('m.id as ids,m.*,o.*')
          ->join('lmq_money o', 'm.id = o.mid')->page($page, $listRows)->select();
        foreach ($results as $k => $v) {
            //处理中提现金额
            $w_money_1 = $data_list = Db::table('lmq_money_withdraw')->where($where)->where(['status' => 0, 'mid' => $v['ids']])->sum('money');
            //处理中提现手续费
            $w_fee_1 = $data_list = Db::table('lmq_money_withdraw')->where($where)->where(['status' => 0, 'mid' => $v['ids']])->sum('fee');
            //提现成功提现金额
            $w_money_2 = $data_list = Db::table('lmq_money_withdraw')->where($where)->where(['status' => 1, 'mid' => $v['ids']])->sum('money');
            //提现成功提现手续费
            $w_fee_2 = $data_list = Db::table('lmq_money_withdraw')->where($where)->where(['status' => 1, 'mid' => $v['ids']])->sum('fee');
            //处理中充值金额
            $r_money_1 = $data_list = Db::table('lmq_money_recharge')->where($where)->where(['status' => 0, 'mid' => $v['ids']])->sum('money');
            //充值成功充值金额
            $r_money_2 = $data_list = Db::table('lmq_money_recharge')->where($where)->where(['status' => 1, 'mid' => $v['ids']])->sum('money');
            $results[$k]['w_money_1'] = $w_money_1 / 100;
            $results[$k]['w_fee_1'] = $w_fee_1 / 100;
            $results[$k]['w_money_2'] = $w_money_2 / 100;
            $results[$k]['w_fee_2'] = $w_fee_2 / 100;
            $results[$k]['r_money_1'] = $r_money_1 / 100;
            $results[$k]['r_money_2'] = $r_money_2 / 100;
        }
        $data_list = $class::make($results, $listRows, $page, $total, $simple, $config);
        // 分页数据
        return ZBuilder::make('table')
          ->hideCheckbox()
//          ->setSearch(['name' => '姓名', 'mobile' => '手机号'], '', '', true) // 设置搜索框
            ->setSearchArea([
                ['text', 'name', '姓名', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
          ->addColumns([ // 批量添加数据列
            ['ids', 'ID'],
            ['mobile', '手机号'],
            ['name', '姓名'],
              ['role_name', '白名单', $this->user_role_name],
            ['account', '可用余额', 'callback', function ($value) {
                return $value / 100;
            }],
            ['freeze', '冻结资金', 'callback', function ($value) {
                return $value / 100;
            }],
            ['operate_account', '操盘资金总额', 'callback', function ($value) {
                return $value / 100;
            }],
            ['bond_account', '保证金总额', 'callback', function ($value) {
                return $value / 100;
            }],
            ['w_money_1', '处理中提现金额'],
            ['w_fee_1', '处理中提现手续费'],
            ['w_money_2', '累计提现金额'],
            ['w_fee_2', '累计提现手续费'],
            ['r_money_1', '处理中充值金额'],
            ['r_money_2', '累计充值金额'],
            ['is_del', '账户状态', [0 => '正常', 1 => '注销/删除']],
          ])
          ->setTableName('member')
          ->addTopButton('custem', $btn_excel)
          ->addOrder('id,is_del')
          ->setColumnWidth([
            'operate_account'  => 120,
            'w_money_1' => 140,
            'w_fee_1' => 160,
            'w_money_2' => 140,
            'w_fee_2' => 140,
            'r_money_1' => 140,
            'r_money_2' => 140,
          ])
          ->setRowList($data_list) // 设置表格数据
          ->fetch(); // 渲染模板
    }

    public function member_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        $webtype = webType();
        $where = [];
        $wherem = [];
        if ($webtype == 2) {

            $where['m.for_user'] = for_user();
            $wherem['m.for_user'] = for_user();
        }
        empty($order) && $order = 'create_time,m.id desc';
        if (empty($_GET['list_rows'])) {
            $listRows = 10;
        } else {
            $listRows = $_GET['list_rows'];
        }
        $simple = false;
        $config = [];
        $config = array_merge(Config::get('paginate'), $config);
        $listRows = $listRows ?: $config['list_rows'];
        $class = false !== strpos($config['type'], '\\') ? $config['type'] : '\\think\\paginator\\driver\\' . ucwords($config['type']);
        $page = isset($config['page']) ? (int)$config['page'] : call_user_func([
          $class,
          'getCurrentPage',
        ], $config['var_page']);
        $page = $page < 1 ? 1 : $page;
        $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([$class, 'getCurrentPath']);
        $total = Db::table('lmq_member')
          ->alias('m')
          ->where($map)
          ->where($where)
          ->order($order)
          ->join('lmq_money o', 'm.id = o.mid')
          ->count();
        $results = Db::table('lmq_member')
          ->alias('m')
          ->where($map)
          ->where($where)
          ->order($order)
          ->join('lmq_money o', 'm.id = o.mid')->page($page, $listRows)
          ->select();

        $is_del_arr = [0 => '正常', 1 => '注销/删除'];

        foreach ($results as $k => $v) {
            //处理中提现金额
            $w_money_1 = $data_list = Db::table('lmq_money_withdraw') ->alias('m')->where(['status' => 0, 'mid' => $v['id']])->where($where)->sum('money');
            //处理中提现手续费
            $w_fee_1 = $data_list = Db::table('lmq_money_withdraw') ->alias('m')->where(['status' => 0, 'mid' => $v['id']])->where($where)->sum('fee');
            //提现成功提现金额
            $w_money_2 = $data_list = Db::table('lmq_money_withdraw') ->alias('m')->where(['status' => 1, 'mid' => $v['id']])->where($where)->sum('money');
            //提现成功提现手续费
            $w_fee_2 = $data_list = Db::table('lmq_money_withdraw') ->alias('m')->where(['status' => 1, 'mid' => $v['id']])->where($where)->sum('fee');
            //处理中充值金额
            $r_money_1 = $data_list = Db::table('lmq_money_recharge') ->alias('m')->where(['status' => 0, 'mid' => $v['id']])->where($where)->sum('money');
            //充值成功充值金额
            $r_money_2 = $data_list = Db::table('lmq_money_recharge') ->alias('m')->where(['status' => 1, 'mid' => $v['id']])->where($where)->sum('money');
            $results[$k]['w_money_1'] = $w_money_1 / 100;
            $results[$k]['w_fee_1'] = $w_fee_1 / 100;
            $results[$k]['w_money_2'] = $w_money_2 / 100;
            $results[$k]['w_fee_2'] = $w_fee_2 / 100;
            $results[$k]['r_money_1'] = $r_money_1 / 100;
            $results[$k]['r_money_2'] = $r_money_2 / 100;
            $is_del_arr = [0 => '正常', 1 => '注销/删除'];
            $results[$k]['is_del_str'] = $is_del_arr[$v['is_del']];
        }
        $xlsData = $class::make($results, $listRows, $page, $total, $simple, $config);
        $title = "会员账户";
        $arrHeader = array('ID', '手机号', '姓名', '可用余额', '冻结资金', '操盘资金总额', '保证金总额', '处理中提现金额', '处理中提现手续费', '累计提现金额', '累计提现手续费', '处理中充值金额', '累计充值金额', '账户状态');
        $fields = array('id', 'mobile', 'name', 'account', 'freeze', 'operate_account', 'bond_account', 'w_money_1', 'w_fee_1', 'w_money_2', 'w_fee_2', 'r_money_1', 'r_money_2', 'is_del_str');
        export($arrHeader, $fields, $xlsData, $title);
    }

    //注册人数
    public function registercounts()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        if ($map === "888") {
            $startToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
            $startWeek = mktime(0, 0, 0, date('m'), date('d') - date('w'), date('Y'));
            $endWeek = strtotime(date('Y-m-d', strtotime("this week Sunday", time()))) + 24 * 3600 - 1;
            $startMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $endMonth = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'));
            //当日注册会员
            $where = ['is_del' => 0];
            if ($webtype == 2) {
                $where['for_user'] = for_user();
            }
            $day = Db::name('member')
              ->where($where)
              ->where('create_time', 'between', [$startToday, $endToday])
              ->count();
            //本周注册会员
            $weeks = Db::name('member')
              ->where($where)
              ->where('create_time', 'between', [$startWeek, $endWeek])
              ->count();
            //本月注册会员
            $months = Db::name('member')
              ->where($where)
              ->where('create_time', 'between', [$startMonth, $endMonth])
              ->count();
            return ZBuilder::make('table')
              ->setTemplate('registercounts')
              ->assign(['day' => $day, 'weeks' => $weeks, 'months' => $months])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('registercounts', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //会员分析
    public function useranalysis()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        if ($map === "888") {
            //会员总数
            $where = ['status' => 1];
            $sql_where = '';
            $sql_where2 = '';
            if ($webtype == 2) {
                $where['for_user'] = for_user();
                $for_user = for_user();
                $sql_where = " where for_user = {$for_user}";
                $sql_where2 = " and for_user = {$for_user}";
            }
            $member_count = Db::name('member')
              ->where($where)
              ->count();
            //配资用户
            $borrow_count = Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` ' . $sql_where . '  GROUP BY member_id ) aa');
            $borrow_count = $borrow_count[0]['nums'];
            //体验用户
            $free_count = Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where  type=4 ' . $sql_where2 . ' GROUP BY member_id ) aa');
            $free_count = $free_count[0]['nums'];
            //模拟用户
            $simulate_count = Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where  type=6 ' . $sql_where2 . ' GROUP BY member_id ) aa');
            $simulate_count = $simulate_count[0]['nums'];
            return ZBuilder::make('table')
              ->setTemplate('useranalysis')
              ->assign(['member_count' => $member_count, 'borrow_count' => $borrow_count, 'free_count' => $free_count, 'simulate_count' => $simulate_count])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('useranalysis', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //代理商分析
    public function agentanalyse()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = [];
//        if($webtype == 2){
//            $where['for_user'] = for_user();
//        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $where['agent_far'] = $admin_user['for_user'];
        }
        $where['role_name'] = array('neq', '白名单');
//        $admin_user = AdminUserModel::where('id',UID) -> find();
//
//        if($admin_user['role'] == 2){
//            $where['pid'] = $admin_user['for_user'];
//        }
        if ($map === "888") {
            //会员总数
            $member_count = Db::name('member')
              ->where(['status' => 1])
              ->where($where)
              ->count();
            $agent_one_counts = Db::name('member')
              ->where(['agent_id' => 1])
              ->where($where)
              ->count();
            //2级
            $agent_two_counts = Db::name('member')
              ->where(['agent_id' => 2])
              ->where($where)
              ->count();
            //3ji
            $agent_tree_counts = Db::name('member')
              ->where(['agent_id' => 3])
              ->where($where)
              ->count();
            //代理邀请的用户数
            $agentIdArr = Db::name('member')
              ->field('id')
              ->where('agent_id<>0')
              ->where($where)
              ->select();
            $agentId = [];
            foreach ($agentIdArr as $key => $val) {
                foreach ($val as $v) {
                    $agentId[] = $v;
                }
            }
            unset($where['pid']);
            unset($where['agent_far']);
            unset($where['role_name']);
            $agentId = implode(',', $agentId);
            $inviteUser = Db::name('member_invitation_relation')
              ->field('id')
              ->where('mid', 'in', [$agentId])
              ->where($where)
              ->count();
            //其他
            $other = $member_count - ($agent_one_counts + $agent_two_counts + $agent_tree_counts + $inviteUser);
            return ZBuilder::make('table')
              ->setTemplate('agentanalyse')
              ->assign(['agent_one_counts' => $agent_one_counts, 'agent_two_counts' => $agent_two_counts, 'agent_tree_counts' => $agent_tree_counts, 'member_count' => $member_count, 'inviteUser' => $inviteUser, 'other' => $other])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('agentanalyse', 'map=888') . '" width="100%" height="651px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //代理商佣金统计
    public function commissionstatistics()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = [];
        $wheres = [];
//         if($webtype == 2){
//             $where['bm.for_user'] = for_user();
//             $wheres['for_user'] = for_user();
//         }
//
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $where['bm.for_user'] = $admin_user['for_user'];
            $wheres['for_user'] = $admin_user['for_user'];
        }
        if ($map === "888") {
            //累计返佣
            $accumulateRemission = Db::name('agents_back_money')
              ->where($wheres)
              ->sum('affect');
            $accumulateRemission = sprintf("%.2f", $accumulateRemission);
            //1级代理返佣
            $oneRemission = Db::name('agents_back_money')
              ->alias('bm')
              ->join('member m', 'bm.mid=m.id')
              ->where(['m.agent_id' => 1])
              ->where($where)
              ->sum('bm.affect');
            $oneRemission = sprintf("%.2f", $oneRemission);
            //二级代理返佣
            $twoRemission = Db::name('agents_back_money')
              ->alias('bm')
              ->join('member m', 'bm.mid=m.id')
              ->where(['m.agent_id' => 2])
              ->where($where)
              ->sum('bm.affect');
            $twoRemission = sprintf("%.2f", $twoRemission);
            //三级代理返佣
            $threeRemission = Db::name('agents_back_money')
              ->alias('bm')
              ->join('member m', 'bm.mid=m.id')
              ->where(['m.agent_id' => 2])
              ->where($where)
              ->sum('bm.affect');
            $threeRemission = sprintf("%.2f", $threeRemission);
            return ZBuilder::make('table')
              ->setTemplate('commissionstatistics')
              ->assign(['accumulateRemission' => $accumulateRemission, 'oneRemission' => $oneRemission, 'twoRemission' => $twoRemission, 'threeRemission' => $threeRemission])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('commissionstatistics', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //操盘统计
    public function operatestatistics()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = [];
        if ($webtype == 2) {
            $where['for_user'] = for_user();
        }
        if ($map === "888") {
            $Position = new Position();
            $res = $Position->get_position();
            $market_value = '';  //累计持仓总额
            foreach ($res as $key => $val) {
                $market_value += $val['market_value'];
            }
            $tatal_operate_account = Db::name('money')->where('status=1')->where($where)->sum('operate_account');
            $tatal_bond_account = Db::name('money')->where('status=1')->where($where)->sum('bond_account');
            $tatal_operate_account = sprintf("%.2f", ($tatal_operate_account / 100));
            $tatal_bond_account = sprintf("%.2f", ($tatal_bond_account / 100));
            return ZBuilder::make('table')
              ->setTemplate('operatestatistics')
              ->assign(['tatal_operate_account' => $tatal_operate_account, 'tatal_bond_account' => $tatal_bond_account, 'market_value' => $market_value])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('operatestatistics', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //股票统计
    public function sharesstatistics()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = [];
        if ($webtype == 2) {
            $where['for_user'] = for_user();
        }
        if ($map === "888") {
            //$tatal_bond_account = sprintf("%.2f",($tatal_bond_account/100));
            $buy_flag = Db::name('stock_deal_stock')->where($where)->where(['flag2' => '证券买入'])->where('status', '<>', '0')->sum('amount');
            $sell_flag = Db::name('stock_deal_stock')->where($where)->where(['flag2' => '证券卖出'])->where('status', '<>', '0')->sum('amount');
            //累计成交金额
            $delivery_mount = Db::name('stock_deal_stock')->where($where)->where('status', '<>', '0')->sum('amount');
            //累计委托价格
            $trustPriceMount = Db::name('stock_trust')->where($where)->sum('trust_count*trust_price');
            return ZBuilder::make('table')
              ->setTemplate('sharesstatistics')
              ->assign(['buy_flag' => $buy_flag, 'sell_flag' => $sell_flag, 'delivery_mount' => $delivery_mount, 'trustPriceMount' => $trustPriceMount])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('sharesstatistics', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    public function amountstatistics()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = [];
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $where['for_user'] = $admin_user['for_user'];
        }
        if ($map === "888") {
            // 累计提盈
            $drawprofit = Db::name('stock_drawprofit ')->where($where)->where(['status' => 1])->sum('money');
            $drawprofit = money_convert($drawprofit);
            //累计充值
            //累计充值总额
            $r_total = Db::name('money_recharge')
              ->where(['status' => 1])
              ->where($where)
              ->sum('money');
            $r_total = money_convert($r_total);
            //累计提现总额
            $w_total = Db::name('money_withdraw')
              ->where(['status' => 1])
              ->where($where)
              ->sum('money');
            $w_total = money_convert($w_total);
            return ZBuilder::make('table')
              ->setTemplate('amountstatistics')
              ->assign(['r_total' => $r_total, 'w_total' => $w_total, 'drawprofit' => $drawprofit])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('amountstatistics', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    public function allocationstatistics()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = [];
//          if($webtype == 2){
//              $where['for_user'] = for_user();
//          }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $where['for_user'] = $admin_user['for_user'];
        }
        if ($map === "888") {
            // 累计操盘总资金
            $operate_account = Db::name('money')->where($where)->where('status=1')->sum('operate_account');
            $operate_account = money_convert($operate_account);
            //累计保证金
            $bond_account = Db::name('money')->where($where)->where('status=1')->sum('bond_account');
            $bond_account = money_convert($bond_account);
            //天周管理费合计
            $interest_day_week = Db::name('stock_borrow')->where($where)->where('type', 'in', [1, 2])->sum('borrow_interest');
            $month_interest = Db::name('stock_detail')->where($where)->where('status=1')->sum('sort_order*interest');
            $all_interest = money_convert($interest_day_week) + $month_interest;
            return ZBuilder::make('table')
              ->setTemplate('allocationstatistics')
              ->assign(['operate_account' => $operate_account, 'bond_account' => $bond_account, 'all_interest' => $all_interest])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('allocationstatistics', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    public function applynums()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = [];
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $where['for_user'] = $admin_user['for_user'];
        }
        if ($map === "888") {
            //总配资单数
            $allocation_all_nums = Db::name('stock_borrow')->where($where)->count();
            //免费体验
            $tryCount = Db::name('stock_borrow')->where($where)->where('type=4')->count();
            //按天配资
            $dayCount = Db::name('stock_borrow')->where($where)->where('type=1')->count();
            //按周配资
            $weekCount = Db::name('stock_borrow')->where($where)->where('type=2')->count();
            //按月配资
            $monthCount = Db::name('stock_borrow')->where($where)->where('type=3')->count();
            //免息配资
            $freeCount = Db::name('stock_borrow')->where($where)->where('type=5')->count();
            //模拟操盘
            $simulationCount = Db::name('stock_borrow')->where($where)->where('type=6')->count();
            return ZBuilder::make('table')
              ->setTemplate('applynums')
              ->assign(['allocation_all_nums' => $allocation_all_nums, 'tryCount' => $tryCount, 'dayCount' => $dayCount, 'weekCount' => $weekCount, 'monthCount' => $monthCount, 'freeCount' => $freeCount, 'simulationCount' => $simulationCount])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('applynums', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    public function invite()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
//          $where = ['m.agent_id'=>0];
//          if($webtype == 2){
//              $where['r.for_user'] = for_user();
//          }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $where['r.mid'] = $admin_user['for_user'];
        }
        if ($map === "888") {
            $returns = array();
            for ($i = 1; $i <= 12; $i++) {
                $year = date('Y');
                $last_day = date('t', mktime(0, 0, 0, $i, 1, $year));  //当月有几天
                $min = strtotime(date("Y") . '-' . $i . '-01 00:00:00');
                $max = strtotime(date("Y") . '-' . $i . '-' . $last_day . ' 23:59:59');
                $returns[$i] = Db::name('member_invitation_relation')
                  ->alias('r')
                  ->join('member m', 'r.mid = m.id', 'LEFT')
                  ->where($where)
                  ->where('r.create_time', ['>=', $min], ['<=', $max], 'and')
                  ->count();
            }
            $returns = json_encode($returns);
            return ZBuilder::make('table')
              ->setTemplate('invite')
              ->assign(['returns' => $returns])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('invite', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //累计邀请金额
    public function invite_amount()
    {
        $req = request();
        $map = input('map');
        $webtype = webType();
        $where = [];
        if ($webtype == 2) {
            $where['for_user'] = for_user();
        }
        if ($map === "888") {
            $returns = array();
            for ($i = 1; $i <= 12; $i++) {
                $year = date('Y');
                $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);  //当月有几天
                $min = strtotime(date("Y") . '-' . $i . '-01 00:00:00');
                $max = strtotime(date("Y") . '-' . $i . '-' . $last_day . ' 23:59:59');
                $returns[$i] = Db::name('member_invitation_record')
                  ->alias('r')
                  ->join('member m', 'r.mid = m.id', 'LEFT')
                  ->where('m.agent_id=0')
                  ->where('r.create_time', ['>=', $min], ['<=', $max], 'and')
                  ->where($where)
                  ->sum('money');
                $returns[$i] = $returns[$i];
            }
            $returns = json_encode($returns);
            return ZBuilder::make('table')
              ->setTemplate('invite_amount')
              ->assign(['returns' => $returns])
              ->fetch();
        } else {
            return ZBuilder::make('table')
              ->hideCheckbox()
              ->assign('empty_tips', '')
              ->setExtraHtml('<iframe src="' . url('invite_amount', 'map=888') . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
              ->fetch();
        }
    }

    //数据统计
    public function statistics()
    {
        $req = request();
        $num = 6;
        $start_time = strtotime(date('Y-m-d', strtotime('-' . $num . ' day')));
        $end_time = strtotime(date('Y-m-d'));
        $type = input('type');
        if (input()) {
            $start_time1 = strtotime(input('start_time'));
            $end_time1 = strtotime(input('end_time'));
            $type = input('type');
        }
        $map = input('map');
//        $system_profit_loss = Db::name('stock_submoney_profit_loss_record') -> sum('return_money');//平台累计盈亏
        $webtype = webType();
        $wherem = [];
        $system_profit_loss = Db::name('stock_position')
          ->where('stock_count', '>', 0)
          ->where($wherem)
          ->sum('ck_profit');
        $where = [];
        $where['status'] = 1;
        $user_assets = Money::where($where)->sum('account');//钱包余额
        $withdrawal_freeze = Money::where($where)->sum('freeze');//提现冻结金额
        $system_capital_pool = 0;//平台资金池
        $wallet_balance = Money::where($where)->sum('account');//会员净资产;
        $contract_assets = 0;//合约净资产
        $wherem2 = [];
        $rebate = Db::name('agent_distributionamount')->where($wherem2)->sum('amount');
        $data = [];
        $data['system_profit_loss'] = $system_profit_loss / 100;
        $data['user_assets'] = $user_assets / 100;
        $data['withdrawal_freeze'] = $withdrawal_freeze / 100;
        $data['system_capital_pool'] = $system_capital_pool / 100;
        $data['wallet_balance'] = $wallet_balance / 100;
        $data['contract_assets'] = $contract_assets / 100;
        $data['rebate'] = sprintf("%.2f", $rebate);;
        if ($type == 1) {
            $profit_loss_data = $this->getProfitLossData($start_time1, $end_time1);
        } else {
            $profit_loss_data = $this->getProfitLossData($start_time, $end_time);
        }
        if ($type == 2) {
            $user_statistics_data = $this->getUserData($start_time1, $end_time1);
        } else {
            $user_statistics_data = $this->getUserData($start_time, $end_time);
        }
        if ($type == 3) {
            $recharge_withdrawal_data = $this->getRechargeWithdrawalData($start_time1, $end_time1);
        } else {
            $recharge_withdrawal_data = $this->getRechargeWithdrawalData($start_time, $end_time);
        }
        if ($type == 4) {
            $contract_statistics_data = $this->getContractStatisticsData($start_time1, $end_time1);
        } else {
            $contract_statistics_data = $this->getContractStatisticsData($start_time, $end_time);
        }
        if (input()) {
            $profit_loss_data['days'] = json_decode($profit_loss_data['days'], true);
            $profit_loss_data['returns'] = json_decode($profit_loss_data['returns'], true);
            $user_statistics_data['days'] = json_decode($user_statistics_data['days'], true);
            $user_statistics_data['returns'] = json_decode($user_statistics_data['returns'], true);
            $recharge_withdrawal_data['days'] = json_decode($recharge_withdrawal_data['days'], true);
            $recharge_withdrawal_data['returns'] = json_decode($recharge_withdrawal_data['returns'], true);
            $contract_statistics_data['days'] = json_decode($contract_statistics_data['days'], true);
            $contract_statistics_data['returns'] = json_decode($contract_statistics_data['returns'], true);
            echo json_encode(['profit_loss_data' => $profit_loss_data, 'user_statistics_data' => $user_statistics_data, 'recharge_withdrawal_data' => $recharge_withdrawal_data, 'contract_statistics_data' => $contract_statistics_data]);
            exit;
        }
//        if($map==="888"){
        return ZBuilder::make('table')
          ->addTimeFilter('money_recharge.create_time', [date("Y-m-d"), date("Y-m-d")], ['开始时间', '结束时间'])
          ->assign(['data' => $data, 'profit_loss_data' => $profit_loss_data, 'user_statistics_data' => $user_statistics_data, 'recharge_withdrawal_data' => $recharge_withdrawal_data, 'contract_statistics_data' => $contract_statistics_data])
          ->setTemplate('statistics')
          ->fetch();
//        }else{
//            return ZBuilder::make('table')
//                ->hideCheckbox()
//                ->assign(['data'=>$data,'profit_loss_data' => $profit_loss_data,'user_statistics_data' => $user_statistics_data,'recharge_withdrawal_data' => $recharge_withdrawal_data,'contract_statistics_data' => $contract_statistics_data,'empty_tips'=>''])
//                ->setExtraHtml('<iframe src="'.url('statistics', 'map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
//                ->fetch();
//        }
    }

    //结算盈亏数据
    public function getProfitLossData($start_time, $end_time)
    {
        $profit_loss['name'] = '合约结算盈亏';
        $apply_interest['name'] = '申请利息';
        $expand_interest['name'] = '扩大利息';
        $renewal_interest['name'] = '续期利息';
        $days = create_date_range(date('Y-m-d', $start_time), date('Y-m-d', $end_time + 24 * 3600));
        $for_user = Db::name('admin_user')->where('id', UID)->value('for_user');
        $where = [];
        if ($for_user) {
            $where['for_user'] = $for_user;
        }
        //当天的数据
        $profit_loss_day = ProfitLossModel::where('create_time', '>', $end_time)->where($where)->find();
        $end_time = $end_time + 24 * 3600 - 1;
        //时间范围数据
        $profit_loss_datas = ProfitLossModel::whereTime('create_time', 'between', [$start_time, $end_time])
          ->where($where)
          ->field("SUM(`apply_interest`) as apply_interest,SUM(`profit_loss`) as profit_loss,SUM(`expand_interest`) as expand_interest,SUM(`renewal_interest`) as renewal_interest")
          ->find();
        $profit_loss_datas['apply_interest'] = $profit_loss_datas['apply_interest'] ?: '0.00';
        $profit_loss_datas['profit_loss'] = $profit_loss_datas['profit_loss'] ?: '0.00';
        $profit_loss_datas['expand_interest'] = $profit_loss_datas['expand_interest'] ?: '0.00';
        $profit_loss_datas['renewal_interest'] = $profit_loss_datas['renewal_interest'] ?: '0.00';
        if (empty($profit_loss_day)) {
            $profit_loss_day['apply_interest'] = '0.00';
            $profit_loss_day['profit_loss'] = '0.00';
            $profit_loss_day['expand_interest'] = '0.00';
            $profit_loss_day['renewal_interest'] = '0.00';
        }
        foreach ($days as $key => $value) {
            $start_time = strtotime($value);
            $end_time = $start_time + 24 * 3600 - 1;
            $info = ProfitLossModel::where('create_time', 'between time', [$start_time, $end_time])->where($where)->find();
            $info = empty($info) ? [] : $info->toArray();
            $profit_loss['data'][$key] = $info['profit_loss'] ?? '0.00';
            $expand_interest['data'][$key] = $info['expand_interest'] ?? '0.00';
            $apply_interest['data'][$key] = $info['apply_interest'] ?? '0.00';
            $renewal_interest['data'][$key] = $info['renewal_interest'] ?? '0.00';
        }
        for ($j = 0; $j < 4; $j++) {
            switch ($j) {
                case 0:
                    $returns[$j]['name'] = $profit_loss['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $profit_loss['data'];
                    break;
                case 1:
                    $returns[$j]['name'] = $expand_interest['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $expand_interest['data'];
                    break;
                case 2:
                    $returns[$j]['name'] = $apply_interest['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $apply_interest['data'];
                    break;
                case 3:
                    $returns[$j]['name'] = $renewal_interest['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $renewal_interest['data'];
                    break;
            }
        }
        $returns = json_encode($returns);
        $days = json_encode($days);
        $profit_loss_data['days'] = $days;
        $profit_loss_data['returns'] = $returns;
        $profit_loss_data['profit_loss_day'] = $profit_loss_day;
        $profit_loss_data['profit_loss'] = $profit_loss_datas;
        return $profit_loss_data;
    }

    //用户统计
    public function getUserData($start_time, $end_time)
    {
        $active_user['name'] = '活跃用户';
        $agiotage_user['name'] = '买卖股票用户';
        $new_reg_user['name'] = '新注册用户';
        $apply_contract_user['name'] = '申请合约用户';
        $recharge_withdrawal['name'] = '充值提现';
        $for_user = Db::name('admin_user')->where('id', UID)->value('for_user');
        $where = [];
        if ($for_user) {
            $where['for_user'] = $for_user;
        }
        $days = create_date_range(date('Y-m-d', $start_time), date('Y-m-d', $end_time + 24 * 3600));
        //当天的数据
        $user_data_day = UserStatisticsModel::where('create_time', '>', $end_time)->where($where)->find();
        $tody_cp_num = Db::query("SELECT DISTINCT
	                b.member_id
                   FROM
	               lmq_stock_submoney_record r
                   LEFT JOIN lmq_stock_borrow b ON b.stock_subaccount_id = r.sub_id
                   WHERE
	               r.type IN (1, 3) and r.create_time >= {$end_time}");
        $tody_cp_num = $tody_cp_num ? count($tody_cp_num) : 0;
        if (empty($user_data_day)) {
            $user_data_day['active_user'] = 0;
            $user_data_day['agiotage_user'] = 0;
            $user_data_day['new_reg_user'] = 0;
            $user_data_day['apply_contract_user'] = 0;
            $user_data_day['recharge_withdrawal'] = 0;
        }
        $user_data_day['active_user'] = $tody_cp_num;
        $end_time = $end_time + 24 * 3600 - 1;
        //时间范围数据
        $user_data = UserStatisticsModel::whereTime('create_time', 'between', [$start_time, $end_time])
          ->where($where)
          ->field("SUM(`active_user`) as active_user,SUM(`agiotage_user`) as agiotage_user,SUM(`new_reg_user`) as new_reg_user,SUM(`apply_contract_user`) as apply_contract_user,SUM(`recharge_withdrawal`) as recharge_withdrawal")
          ->find();
        $tody_total_num = Db::query("SELECT DISTINCT
	                b.member_id
                   FROM
	               lmq_stock_submoney_record r
                   LEFT JOIN lmq_stock_borrow b ON b.stock_subaccount_id = r.sub_id
                   WHERE
	               r.type IN (1, 3) and  r.create_time >= {$start_time} and  r.create_time <= {$end_time}");
        $user_data['active_user'] = $tody_total_num ? count($tody_total_num) : 0;
//        $user_data['active_user'] =  $user_data['active_user']?:0;
        $user_data['agiotage_user'] = $user_data['agiotage_user'] ?: 0;
        $user_data['new_reg_user'] = $user_data['new_reg_user'] ?: 0;
        $user_data['apply_contract_user'] = $user_data['apply_contract_user'] ?: 0;
        $user_data['recharge_withdrawal'] = $user_data['recharge_withdrawal'] ?: 0;
        foreach ($days as $key => $value) {
            $start_time = strtotime($value);
            $end_time = $start_time + 24 * 3600 - 1;
            $info = UserStatisticsModel::where('create_time', 'between time', [$start_time, $end_time])->where($where)->find();
            $info = empty($info) ? [] : $info->toArray();
            $active_user['data'][$key] = $info ? $info['active_user'] : 0;
            $agiotage_user['data'][$key] = $info ? $info['agiotage_user'] : 0;
            $new_reg_user['data'][$key] = $info ? $info['new_reg_user'] : 0;
            $apply_contract_user['data'][$key] = $info ? $info['apply_contract_user'] : 0;
            $recharge_withdrawal['data'][$key] = $info ? $info['recharge_withdrawal'] : 0;
        }
        for ($j = 0; $j < 5; $j++) {
            switch ($j) {
                case 0:
                    $returns[$j]['name'] = $active_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $active_user['data'];
                    break;
                case 1:
                    $returns[$j]['name'] = $agiotage_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $agiotage_user['data'];
                    break;
                case 2:
                    $returns[$j]['name'] = $new_reg_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $new_reg_user['data'];
                    break;
                case 3:
                    $returns[$j]['name'] = $apply_contract_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $apply_contract_user['data'];
                    break;
                case 4:
                    $returns[$j]['name'] = $recharge_withdrawal['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $recharge_withdrawal['data'];
                    break;
            }
        }
        $returns = json_encode($returns);
        $days = json_encode($days);
        $user_statistics_data['days'] = $days;
        $user_statistics_data['returns'] = $returns;
        $user_statistics_data['user_data_day'] = $user_data_day;
        $user_statistics_data['user_data'] = $user_data;
        return $user_statistics_data;
    }

    //结算盈亏数据
    public function getRechargeWithdrawalData($start_time, $end_time)
    {
        $recharge['name'] = '充值金额';
        $withdrawal['name'] = '提现金额';
        $cashback['name'] = '充值返现';
        $recharge_user['name'] = '充值用户';
        $withdrawal_user['name'] = '提现用户';
        $days = create_date_range(date('Y-m-d', $start_time), date('Y-m-d', $end_time + 24 * 3600));
        $for_user = Db::name('admin_user')->where('id', UID)->value('for_user');
        $where = [];
        if ($for_user) {
            $where['for_user'] = $for_user;
        }
        //当天的数据
        $user_statistics_day = RechargeWithdrawalModel::where('create_time', '>', $end_time)->where($where)->find();
        $end_time = $end_time + 24 * 3600 - 1;
        //时间范围数据
        $user_statistics = RechargeWithdrawalModel::whereTime('create_time', 'between', [$start_time, $end_time])
          ->where($where)
          ->field("SUM(`recharge`) as recharge,SUM(`withdrawal`) as withdrawal,SUM(`cashback`) as cashback,SUM(`recharge_user`) as recharge_user,SUM(`withdrawal_user`) as withdrawal_user")
          ->find();
        $user_statistics['recharge'] = $user_statistics['recharge'] ?: '0.00';
        $user_statistics['withdrawal'] = $user_statistics['withdrawal'] ?: '0.00';
        $user_statistics['cashback'] = $user_statistics['cashback'] ?: '0.00';
        $user_statistics['recharge_user'] = $user_statistics['recharge_user'] ?: 0;
        $user_statistics['withdrawal_user'] = $user_statistics['withdrawal_user'] ?: 0;
        if (empty($user_statistics_day)) {
            $user_statistics_day['recharge'] = '0.00';
            $user_statistics_day['withdrawal'] = '0.00';
            $user_statistics_day['cashback'] = '0.00';
            $user_statistics_day['recharge_user'] = 0;
            $user_statistics_day['withdrawal_user'] = 0;
        }
        foreach ($days as $key => $value) {
            $start_time = strtotime($value);
            $end_time = $start_time + 24 * 3600 - 1;
            $info = RechargeWithdrawalModel::where('create_time', 'between time', [$start_time, $end_time])->where($where)->find();
            $info = empty($info) ? [] : $info->toArray();
            $recharge['data'][$key] = $info ? $info['recharge'] : 0;
            $withdrawal['data'][$key] = $info ? $info['withdrawal'] : 0;
            $cashback['data'][$key] = $info ? $info['cashback'] : 0;
            $recharge_user['data'][$key] = $info ? $info['recharge_user'] : 0;
            $withdrawal_user['data'][$key] = $info ? $info['withdrawal_user'] : 0;
        }
        for ($j = 0; $j < 5; $j++) {
            switch ($j) {
                case 0:
                    $returns[$j]['name'] = $recharge['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $recharge['data'];
                    break;
                case 1:
                    $returns[$j]['name'] = $withdrawal['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $withdrawal['data'];
                    break;
                case 2:
                    $returns[$j]['name'] = $cashback['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $cashback['data'];
                    break;
                case 3:
                    $returns[$j]['name'] = $recharge_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $recharge_user['data'];
                    break;
                case 4:
                    $returns[$j]['name'] = $withdrawal_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $withdrawal_user['data'];
                    break;
            }
        }
        $returns = json_encode($returns);
        $days = json_encode($days);
        $recharge_withdrawal_data['days'] = $days;
        $recharge_withdrawal_data['returns'] = $returns;
        $recharge_withdrawal_data['user_statistics'] = $user_statistics;
        $recharge_withdrawal_data['user_statistics_day'] = $user_statistics_day;
        return $recharge_withdrawal_data;
    }

    //结算盈亏数据
    public function getContractStatisticsData($start_time, $end_time)
    {
        $new_contract['name'] = '新开合约';
        $qiangping_contract['name'] = '强平合约';
        $settlement_contract['name'] = '结算合约';
        $cross_warehouse_contract['name'] = '穿仓合约';
        $days = create_date_range(date('Y-m-d', $start_time), date('Y-m-d', $end_time + 24 * 3600));
        $for_user = Db::name('admin_user')->where('id', UID)->value('for_user');
        $where = [];
        if ($for_user) {
            $where['for_user'] = $for_user;
        }
        //当天的数据
        $contract_statistics_day = ContractStatisticsModel::where('create_time', '>', $end_time)->where($where)->find();
        $end_time = $end_time + 24 * 3600 - 1;
        //时间范围数据
        $contract_statistics_datas = ContractStatisticsModel::whereTime('create_time', 'between', [$start_time, $end_time])
          ->where($where)
          ->field("SUM(`new_contract`) as new_contract,SUM(`qiangping_contract`) as qiangping_contract,SUM(`settlement_contract`) as settlement_contract,SUM(`cross_warehouse_contract`) as cross_warehouse_contract,SUM(`new_num`) as new_num,SUM(`qiangping_num`) as qiangping_num,SUM(`settlement_num`) as settlement_num,SUM(`cross_warehouse_num`) as cross_warehouse_num")
          ->find();
        $contract_statistics_datas['new_contract'] = $contract_statistics_datas['new_contract'] ?: 0.00;
        $contract_statistics_datas['qiangping_contract'] = $contract_statistics_datas['qiangping_contract'] ?: 0.00;
        $contract_statistics_datas['settlement_contract'] = $contract_statistics_datas['settlement_contract'] ?: 0.00;
        $contract_statistics_datas['cross_warehouse_contract'] = $contract_statistics_datas['cross_warehouse_contract'] ?: 0.00;
        $contract_statistics_datas['new_num'] = $contract_statistics_datas['new_num'] ?: 0;
        $contract_statistics_datas['qiangping_num'] = $contract_statistics_datas['qiangping_num'] ?: 0;
        $contract_statistics_datas['settlement_num'] = $contract_statistics_datas['settlement_num'] ?: 0;
        $contract_statistics_datas['cross_warehouse_num'] = $contract_statistics_datas['cross_warehouse_num'] ?: 0;
        if (empty($user_data_day)) {
            $contract_statistics_day['new_contract'] = 0.00;
            $contract_statistics_day['qiangping_contract'] = 0.00;
            $contract_statistics_day['settlement_contract'] = 0.00;
            $contract_statistics_day['cross_warehouse_contract'] = 0.00;
            $contract_statistics_day['new_num'] = 0;
            $contract_statistics_day['qiangping_num'] = 0;
            $contract_statistics_day['settlement_num'] = 0;
            $contract_statistics_day['cross_warehouse_num'] = 0;
        }
        $j=0;
        foreach ($days as $key => $value) {
            $start_time = strtotime($value);
            $end_time = $start_time + 24 * 3600 - 1;
            $info = ContractStatisticsModel::where('create_time', 'between time', [$start_time, $end_time])->where($where)->find();
            $info = empty($info) ? [] : $info->toArray();
            $new_contract['data'][$key] = $info ? $info['new_contract'] : 0;
            $qiangping_contract['data'][$key] = $info ? $info['qiangping_contract'] : 0;
            $settlement_contract['data'][$key] = $info ? $info['settlement_contract'] : 0;
            $cross_warehouse_contract['data'][$key] = $info ? $info['cross_warehouse_contract'] : 0;
            $j++;
        }
        for ($j = 0; $j < 4; $j++) {
            switch ($j) {
                case 0:
                    $returns[$j]['name'] = $new_contract['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $new_contract['data'];
                    break;
                case 1:
                    $returns[$j]['name'] = $qiangping_contract['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $qiangping_contract['data'];
                    break;
                case 2:
                    $returns[$j]['name'] = $settlement_contract['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $settlement_contract['data'];
                    break;
                case 3:
                    $returns[$j]['name'] = $cross_warehouse_contract['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $cross_warehouse_contract['data'];
                    break;
            }
        }
        $returns = json_encode($returns);
        $days = json_encode($days);
        $contract_statistics['days'] = $days;
        $contract_statistics['returns'] = $returns;
        $contract_statistics['contract_statistics_day'] = $contract_statistics_day;
        $contract_statistics['contract_statistics_datas'] = $contract_statistics_datas;
        return $contract_statistics;
    }
}