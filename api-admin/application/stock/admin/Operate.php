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
namespace app\stock\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\market\model\Grid;
use app\market\model\Position;
use app\market\model\StockSubAccount;
use app\stock\model\Borrow;
use app\stock\model\Subaccount;
use app\stock\model\SubMoneyRecord;
use app\user\model\User as AdminUserModel;
use think\Db;

class Operate extends Admin
{
    /*
     * 持仓查询
     */
    public function index()
    {
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'p.id desc';
        }
        $position = new Position();
        // 读取数据
        $page = input('page');
        $data = $position->get_position('', $page, $order, $listRows);
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['p.for_user'] = $admin_user['for_user'];
        }
        foreach ($data as $v) {
            Db::name('stock_position')->where(['id' => $v['id']])->update($v);
        }
        //$data_list = Db::name('stock_position')->where($map)->where(['buying'=>0])->order($order)->paginate($listRows = 20);
        $data_list = Db::view('stock_position p')
          ->view('stock_subaccount s', 'sub_account', 's.id=p.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where($map)
          ->where(['p.buying' => 0])
          ->order($order)
          ->paginate($listRows);
        foreach ($data_list as $k => $v) {
            //临时处理  处理好了可以注释掉
            #  $v['now_price']=  z_market($v['gupiao_code'])['current_price'];
            if (empty($v['ck_price_new'])) {
                $v['ck_price_new'] = $v['ck_price'];
            }
            $v['ck_profit'] = ($v['now_price'] - $v['ck_price_new']) * $v['stock_count'];
            $v['ck_profit'] = round($v['ck_profit'], 2);
            $email = $v['email'] ?: '--';
            $v['user_info'] = "<p>" . $v['mobile'] . "</p><p>" . $email . "</p>";
            $v['user_name'] = "<p>" . $v['sub_id'] . "</p><p>" . $v['sub_account'] . "</p>";
            $v['gupiao'] = "<p>" . $v['gupiao_name'] . "</p><p>" . $v['gupiao_code'] . "</p>";
            $data_list[$k] = $v;
        }
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        // 分页数据
        $page = $data_list->render();
        //自定义按钮（风控设置）
        $btn_risk = [
          'title' => '风控设置',
          'icon' => 'fa fa-fw fa-balance-scale',
          'href' => url('/stock/subaccount/risk', ['id' => '__sub_id__'])
        ];
        $btn_close = [
          'title' => '强制卖出',
          'icon' => 'fa fa-fw fa-trash-o',
          'class' => 'btn btn-xs btn-default ajax-get confirm',
          'data-title' => '真的要卖出平仓吗？',
          'data-tips' => '请确认此股票已到了平仓线！',
          'href' => url('close', ['id' => '__sub_id__', 'key_id' => '__id__', 'code' => '__gupiao_code__', 'count' => '__canbuy_count__'])
        ];
        return ZBuilder::make('table')
          ->setPageTitle('股票持仓') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_position') // 设置数据表名
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code') // 添加筛选
          ->setSearch(['sub_account' => '子账户', 'mobile' => '手机号',]) // 设置搜索参数
          ->addRightButton('risk', $btn_risk) // 添加子账户风控设置按钮
          ->addRightButton('close', $btn_close) // 强制卖出
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['user_info', '手机号/邮箱'],
//                ['sub_account', '子账户'],
              //            ['lid', '交易账户名'],
//                ['soruce', '证券来源'],
//                ['login_name', '证券账户'],
            ['gupiao', '证券名称/证券代码'],
              //['count', '证券数量'],
              //['stock_count', '实际持仓数量'],
            ['canbuy_count', '可卖数量'],
            ['stock_count', '持仓数量'],
            ['ck_price_new', '成本价'],
            ['now_price', '当前价'],
            ['market_value', '最新市值'],
            ['ck_profit', '参考浮动盈亏'],
            ['profit_rate', '盈亏比例'],
            ['jiyisuo', '交易所'],
            ['right_button', '操作', 'btn'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function index_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'p.id desc';
        }
        $xlsData = Db::view('stock_position p')
          ->view('stock_subaccount s', 'sub_account', 's.id=p.sub_id', 'left')
          ->view('member m', 'mobile', 'm.id=s.uid', 'left')
          ->where(['p.buying' => 0])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        // $status_arr=['-1'=>'待审核','0'=>'未通过','1'=>'操盘中','2'=>'已结束'];
        /*   foreach ($xlsData as $k=>$v){
               //$v['type']=$type_arr[$v['type']];
               $v['status']=$status_arr[$v['status']];
               $v['verify_time']=date('Y-m-d h:i',$v['verify_time']);
               $v['end_time']=date('Y-m-d h:i',$v['end_time']);

           }*/
        $title = "持仓查询";
        $arrHeader = array('子账户ID', '手机号', '邮箱', '子账户', '交易账户名', '证券代码', '证券名称', '持仓数量', '买入均价', '当前价', '最新市值', '参考浮动盈亏', '盈亏比例', '交易所');
        $fields = array('sub_id', 'mobile', 'email', 'sub_account', 'lid', 'gupiao_code', 'gupiao_name', 'stock_count', 'buy_average_price', 'now_price', 'market_value', 'ck_profit', 'profit_rate', 'jiyisuo');
        // export($arrHeader,$fields,$xlsData,$title);
    }

    /*
    * 卖出管理
    */
    public function out()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 10;
        if (empty($map['deal_date'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['deal_date'][1][0]));
        }
        if (empty($map['deal_date'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['deal_date'][1][1]));
        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['d.for_user'] = $admin_user['for_user'];
        }
        $data_list = Db::view('stock_delivery_order d')
          ->view('stock_subaccount s', 'sub_account', 's.id=d.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where(['d.status' => 1, 'd.business_name' => '证券卖出'])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        $list = array();
        foreach ($data_list as &$v) {
            #交易盈亏=（成交价-买入价）*成交数量
            $v['return_money'] = round(($v['deal_price'] - $v['buy_price']) * $v['volume'], 2);
            $trade_cost = round($v['buy_price'] * $v['volume'], 2);//交易成本
            $email = $v['email'] ?: '--';
            $v['user_info'] = "<p>" . $v['mobile'] . "</p><p>" . $email . "</p>";
            $v['user_name'] = "<p>" . $v['sub_id'] . "</p><p>" . $v['sub_account'] . "</p>";
            $v['number'] = "<p>" . $v['trust_no'] . "</p><p>" . $v['deal_no'] . "</p>";
            $v['trade_money'] = "<p>" . $v['residual_quantity'] . "</p><p>" . $trade_cost . "</p>";
            $v['gupiao'] = "<p>" . $v['gupiao_name'] . "</p><p>" . $v['gupiao_code'] . "</p>";
            $total_commission = $v['stamp_duty'] + $v['transfer_fee'] + $v['commission'] + $v['fees'];
            $total_commission = round($total_commission, 2);
            $v['commission2'] = $total_commission . '(净佣金(' . $v['commission'] . ') + 印花税(' . $v['stamp_duty'] . ')+ 过户费(' . $v['transfer_fee'] . ')+ 规费(' . $v['fees'] . '))';
            $list[] = $v;
        }
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
          ->setPageTitle('卖出管理') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_delivery_order') // 设置数据表名
          ->addTimeFilter('deal_date', [$beginday, $endday]) // 添加时间段筛选
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code,sub_id') // 添加筛选
          ->setSearch(['sub_id' => '子账户ID', 'sub_account' => '子账户', 'gupiao_code' => '证券代码', 'mobile' => '手机号']) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['user_info', '手机号/邮箱'],
              // ['lid', '交易账户名'],
            ['sys', '卖出类型', 'callback', function ($value) {
                if ($value == '1') {
                    $str = '<span style="color:red">系统卖出</span>';
                    return $str;
                } elseif ($value == '0') {
                    return '用户卖出';
                }
            }],
            ['gupiao', '证券名称/证券代码'],
              // ['business_name', '业务名称'],
            ['trade_money', '成交金额/交易成本'],
            ['buy_price', '买入均价（买入价格）'],
            ['deal_price', '卖出均价'],
            ['volume', '成交数量'],
            ['type', '交易方式', 'status', '', [2 => '市价', 1 => '限价']],
//                ['stamp_duty', '印花税'],
//                ['transfer_fee', '过户费'],
//                ['commission', '净佣金'],
            ['commission2', '手续费'],
            ['return_money', '交易盈亏'],
            ['deal_date', '成交日期', 'datetime'],
            ['number', '委托编号/成交编号'],
          ])
          ->hideCheckbox()
          ->setRowList($list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function out_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $xlsData = Db::view('stock_delivery_order d')
          ->view('stock_subaccount s', 'sub_account', 's.id=d.sub_id', 'left')
          ->view('member m', 'mobile', 'm.id=s.uid', 'left')
          ->where(['d.status' => 1, 'd.business_name' => '证券卖出'])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['return_money'] = round(($value['deal_price'] - $value['buy_price']) * $value['volume'], 2);
            $xlsData->items->items[$key]['deal_date'] = date('Y-m-d', $value['deal_date']);
        };
        $title = "卖出管理";
        $arrHeader = array('子账户ID', '手机号', '邮箱', '子账户', '交易账户名', '证券代码', '证券名称', '成交日期', '业务名称', '买入均价', '卖出均价', '成交数量', '印花税', '过户费', '净佣金', '交易盈亏', '委托编号', '成交编号');
        $fields = array('sub_id', 'mobile', 'email', 'sub_account', 'lid', 'gupiao_code', 'gupiao_name', 'deal_date', 'business_name', 'buy_price', 'deal_price', 'volume', 'stamp_duty', 'transfer_fee', 'return_money', 'commission', 'trust_no', 'deal_no');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 资金流水
     */
    public function money_record()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.create_time desc';
            #     $order = 'd.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['d.for_user'] = $admin_user['for_user'];
        }
        $model = new SubMoneyRecord();
        if (empty($map['d.create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['d.create_time'][1][0]));
        }
        if (empty($map['d.create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['d.create_time'][1][1]));
        }
        $data_list = $model->get_record($map, $order, $listRows);
        foreach ($data_list as $key => $value) {
            $data_list[$key]['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
        }
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        return ZBuilder::make('table')
          ->setPageTitle('资金流水') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_submoney_record') // 设置数据表名
          ->addFilter('sub_id') // 添加筛选
          ->setSearch(['sub_id' => '子账户ID', 'sub_account' => '子账户'])
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账号'],
            ['init_money', '配资金额'],
            ['affect', '影响金额(元)'],
            ['account', '账户余额(元)'],
            ['type', '资金类型'],
            ['info', '详情'],
            ['create_time', '变动日期', 'datetime'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->addTimeFilter('d.create_time', [$beginday, $endday]) // 添加时间段筛选
          ->fetch();
    }

    /*
    * 子账号好下股票交易情况
    */
    public function money_record_strok()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'r.id desc ';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $model = new SubMoneyRecord();
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['r.for_user'] = $admin_user['for_user'];
        }
        if (empty($map['r.create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['r.create_time'][1][0]));
        }
        if (empty($map['r.create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['r.create_time'][1][1]));
        }
        $data_list = $model->get_record_strok($map, $order, $listRows);
        foreach ($data_list as $key => $value) {
            $data_list[$key]['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
        }
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        return ZBuilder::make('table')
          ->setPageTitle('子账号好下股票交易情况') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_submoney_record') // 设置数据表名
          ->addFilter('sub_id') // 添加筛选
          ->setSearch(['r.sub_id' => '子账户ID', 's.sub_account' => '子账户', 'r.code' => '股票code']) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['code', '股票代码'],
            ['init_money', '配资金额'],
            ['affect', '影响金额(元)'],
            ['account', '账户余额(元)'],
            ['type', '资金类型'],
            ['info', '详情'],
            ['create_time', '变动日期'],
          ])
          ->hideCheckbox()
          ->addTimeFilter('r.create_time', [$beginday, $endday]) // 添加时间段筛选
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    /*
     * 资金流水导出excel表
     *
     */
    public function money_record_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $model = new SubMoneyRecord();
        $xlsData = $model->get_record($map, $order, $listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['create_time'] = date('Y-m-d h:i', $value['create_time']);
        };
        $title = "资金流水";
        $arrHeader = array('子账户ID', '子账户', '影响金额', '账户余额', '资金类型', '详情', '变动日期');
        $fields = array('sub_id', 'sub_account', 'affect', 'account', 'type', 'info', 'create_time');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 强制平仓.
     */
    public function close()
    {
        if (!get_trading_time('','time')) {
            $this->error('非交易时间');
        };
        $day_time = strtotime(date('Y-m-d'));//当天开始时间
        $req = request();
        $id = input('id');//子账户id
        $key_id = input('key_id');//子账户id
        $code = input('code');
        $count = input('count');
        if ($count == 0) {
            $this->error("可卖数量为零，委托失败");
        }
        $trust_date = Db::name("stock_trust")->where(['id' => $key_id])->value('trust_date');
        if ($trust_date >= $day_time) {
            $this->error("当天股票不能卖出平仓");
        }
        $trust_info = Db::name("stock_trust")
          ->where(['sub_id' => $id])
          ->where(['gupiao_code' => $code])
          ->where('add_time', '>=', mktime(9, 30, 0))
          ->find();
        if (!empty($trust_info)) {
            $this->error("用户已委托卖出此股票，不能重复委托");
        }
        $borrow = new Borrow();
        $count = intval($count);
        $res = $borrow->savesell($id, $code, $count, $sys = 1);
        if (isset($res['status']) && isset($res['status']) === 1) {
            $this->success($res['message']);
        } else {
            $this->error($res['message']);
        }
    }

    /*
     * 历史成交
     */
    public function history_deal()
    {
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.deal_date desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        if (empty($map['deal_date'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['deal_date'][1][0]));
        }
        if (empty($map['deal_date'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['deal_date'][1][1]));
        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['d.for_user'] = $admin_user['for_user'];
        }
        $data_list = Db::view('stock_deal_stock d')
          ->view('stock_subaccount s', 'sub_account', 's.id=d.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where('d.status', '<>', '0')
          ->where($map)
          ->order($order)->paginate($listRows);
        foreach ($data_list as $key => $value) {
            $email = $value['email'] ?: '--';
            $value['user_info'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
            $value['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
            $value['gupiao'] = "<p>" . $value['gupiao_name'] . "</p><p>" . $value['gupiao_code'] . "</p>";
            $value['number'] = "<p>" . $value['trust_no'] . "</p><p>" . $value['deal_no'] . "</p>";
            $value['trust_text'] = "<p>" . $value['trust_price'] . "</p><p>" . $value['trust_count'] . "</p>";
            $value['deal_text'] = "<p>" . $value['deal_price'] . "</p><p>" . $value['volume'] . "</p>";
            $data_list[$key] = $value;
        }
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
          ->setPageTitle('历史成交') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_deal_stock') // 设置数据表名
          ->addTimeFilter('deal_date', [$beginday, $endday]) // 添加时间段筛选
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code') // 添加筛选
          ->setSearch(['sub_id' => '子账户ID', 'sub_account' => '子账户', 'mobile' => '手机号',]) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['user_info', '手机号/邮箱'],
              // ['lid', '交易账户名'],
              //['soruce', '证券来源'],
              //['login_name', '证券账户'],
            ['gupiao', '证券名称/证券代码'],
//                ['gupiao_name', '证券名称'],
            ['deal_date', '成交日期', 'date'],
            ['deal_time', '成交时间'],
            ['number', '委托编号/成交编号'],
            ['trust_text', '委托价格/委托数量'],
//                ['trust_price', '委托价格'],
//                ['trust_count', '委托数量'],
//                ['deal_no', '成交编号'],
//                ['deal_price', '成交价格'],
//                ['volume', '成交数量'],
            ['deal_text', '成交价格/成交数量'],
            ['amount', '成交金额'],
            ['flag2', '买卖标志'],
            ['info', '买入类型'],
            ['status', '状态说明'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function history_deal_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $xlsData = Db::view('stock_deal_stock d')
          ->view('stock_subaccount s', 'sub_account', 's.id=d.sub_id', 'left')
          ->view('member m', 'mobile', 'm.id=s.uid', 'left')
          ->where('d.status', '<>', '0')
          ->where($map)
          ->order($order)->paginate($listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['deal_date'] = date('Y-m-d', $value['deal_date']);
        };
        $title = "历史成交";
        $arrHeader = array('子账户ID', '手机号', '子账户', '交易账户名', '证券代码', '证券名称', '成交日期', '成交时间', '委托编号', '委托价格', '委托数量', '成交编号', '成交价格', '成交数量', '成交金额', '买卖标志', '状态说明');
        $fields = array('sub_id', 'mobile', 'sub_account', 'lid', 'gupiao_code', 'gupiao_name', 'deal_date', 'deal_time', 'trust_no', 'trust_price', 'trust_count', 'deal_no', 'deal_price', 'volume', 'amount', 'flag2', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 历史委托
     */
    public function history_trust()
    {
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 't.trust_date desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        if (empty($map['trust_date'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['trust_date'][1][0]));
        }
        if (empty($map['trust_date'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['trust_date'][1][1]));
        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['t.for_user'] = $admin_user['for_user'];
        }
        $data_list = Db::view('stock_trust t')
          ->view('stock_subaccount s', 'sub_account', 's.id=t.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        #  Db::view('stock_trust t')->getLastSql();
        foreach ($data_list as $key => &$value) {
            $email = $value['email'] ?: '--';
            $value['user_info'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
            $value['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
            $value['gupiao'] = "<p>" . $value['gupiao_name'] . "</p><p>" . $value['gupiao_code'] . "</p>";
            $data_list[$key] = $value;
        }
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
          ->setPageTitle('历史委托') // 设置页面标题
          ->setTableName('stock_trust') // 设置数据表名
          ->addTimeFilter('trust_date', [$beginday, $endday]) // 添加时间段筛选
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code') // 添加筛选
          ->setSearch(['sub_id' => '子账户ID', 'sub_account' => '子账户', 'mobile' => '手机号']) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['user_info', '手机号/邮箱'],
              //['soruce', '证券来源'],
              //['login_name', '证券账户'],
            ['gupiao', '证券名称/证券代码'],
//                ['gupiao_name', '证券名称'],
            ['trust_date', '委托日期', 'date'],
            ['trust_time', '委托时间'],
            ['trust_no', '委托编号'],
            ['trust_price', '委托价格'],
            ['trust_count', '委托数量'],
            ['flag2', '买卖标志'],
            ['volume', '成交数量'],
            ['amount', '成交金额'],
            ['cancel_order_count', '撤单数量'],
            ['status', '状态说明'],
          ])
          ->hideCheckbox()
          ->addTopButton('custem', $btn_excel)
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function history_trust_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 't.id desc';
        }
        $xlsData = Db::view('stock_trust t')
          ->view('stock_subaccount s', 'sub_account', 's.id=t.sub_id', 'left')
          ->view('member m', 'mobile', 'm.id=s.uid', 'left')
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['trust_date'] = date('Y-m-d', $value['trust_date']);
        };
        $title = "历史委托";
        $arrHeader = array('子账户ID', '手机号', '子账户', '交易账户名', '证券代码', '证券名称', '委托日期', '委托时间', '委托编号', '委托价格', '委托数量
', '买卖标志', '成交数量', '成交金额', '撤单数量', '状态说明');
        $fields = array
        ('sub_id', 'mobile', 'sub_account', 'lid', 'gupiao_code', 'gupiao_name', 'trust_date', 'trust_time', 'trust_no', 'trust_price', 'trust_count', 'flag2', 'vol
ume', 'amount', 'cancel_order_count', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 当日委托
     */
    public function trust()
    {
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 't.id desc';
        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['t.for_user'] = $admin_user['for_user'];
        }
        // 读取数据
        $time = strtotime(date('y-m-d', time()));
        $data_list = Db::view('stock_trust t')
          ->view('stock_subaccount s', 'sub_account', 's.id=t.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where(['trust_date' => $time])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        foreach ($data_list as $key => $value) {
            $email = $value['email'] ?: '--';
            $value['user_info'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
            $value['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
            $value['gupiao'] = "<p>" . $value['gupiao_name'] . "</p><p>" . $value['gupiao_code'] . "</p>";
            $data_list[$key] = $value;
        }
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
          ->setPageTitle('当日委托') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_trust') // 设置数据表名
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code') // 添加筛选
          ->setSearch(['sub_id' => '子账户ID', 'sub_account' => '子账户', 'mobile' => '手机号']) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['user_info', '手机号/邮箱'],
              // ['lid', '交易账户名'],
              //['soruce', '证券来源'],
              //['login_name', '证券账户'],
            ['gupiao', '证券名称/证券代码'],
            ['trust_date', '委托日期', 'date'],
            ['trust_time', '委托时间'],
            ['trust_no', '委托编号'],
            ['trust_price', '委托价格'],
            ['trust_count', '委托数量'],
            ['flag2', '买卖标志'],
            ['volume', '成交数量'],
            ['amount', '成交金额'],
            ['cancel_order_count', '撤单数量'],
            ['status', '状态说明'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function trust_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 't.id desc';
        }
        $time = strtotime(date('y-m-d', time()));
        $xlsData = Db::view('stock_trust t')
          ->view('stock_subaccount s', 'sub_account', 's.id=t.sub_id', 'left')
          ->view('member m', 'mobile', 'm.id=s.uid', 'left')
          ->where(['trust_date' => $time])
          ->where($map)
          ->order($order)->paginate($listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['trust_date'] = date('Y-m-d', $value['trust_date']);
        };
        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        // $status_arr=['-1'=>'待审核','0'=>'未通过','1'=>'操盘中','2'=>'已结束'];
        /*   foreach ($xlsData as $k=>$v){
               //$v['type']=$type_arr[$v['type']];
               $v['status']=$status_arr[$v['status']];
               $v['verify_time']=date('Y-m-d h:i',$v['verify_time']);
               $v['end_time']=date('Y-m-d h:i',$v['end_time']);

           }*/
        $title = "当日委托";
        $arrHeader = array('子账户ID', '手机号', '子账户', '交易账户名', '证券来源', '证券账户', '证券代码', '证券名称', '委托日期', '委托时间', '委托编号', '委托价格', '委托数量', '买卖标志', '成交数量', '成交金额', '撤单数量', '状态说明');
        $fields = array('sub_id', 'mobile', 'sub_account', 'lid', 'soruce', 'login_name', 'gupiao_code', 'gupiao_name', 'trust_date', 'trust_time', 'trust_no', 'trust_price', 'trust_count', 'flag2', 'volume', 'amount', 'cancel_order_count', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 实盘暂时未成交委托
     */
    public function temp()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 't.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $data_list = Db::view('stock_temp t')
          ->view('stock_subaccount s', 'sub_account', 's.id=t.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where("trust_date", ">=", time() - 36000)
          ->where(["deal_no" => null])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        $btn_demp_edit = [
          'title' => '',
          'icon' => 'fa fa-fw fa-hand-o-right',
          'class' => 'btn btn-xs btn-default ajax-get confirm',
          'href' => url('temp_edit', ['id' => '__id__']),
          'data-title' => '再次提交',
          'data-tips' => '您确定需要再次提交实盘交易吗？'
        ];
        return ZBuilder::make('table')
          ->setPageTitle('当日暂时未成交委托') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_temp') // 设置数据表名
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code') // 添加筛选
          ->setSearch(['sub_account' => '子账户']) // 设置搜索参数
          ->addColumns([ // 批量添加列
            ['sub_id', '子账户ID'],
            ['mobile', '手机号'],
            ['email', '邮箱'],
            ['sub_account', '子账户'],
            ['lid', '交易账户名'],
              //['soruce', '证券来源'],
              //['login_name', '证券账户'],
            ['gupiao_code', '证券代码'],
            ['gupiao_name', '证券名称'],
            ['trust_date', '委托日期', 'date'],
            ['trust_time', '委托时间'],
            ['trust_no', '委托编号'],
            ['trust_price', '委托价格'],
            ['trust_count', '委托数量'],
            ['volume', '成交数量'],
            ['amount', '成交金额'],
            ['cancel_order_count', '撤单数量'],
            ['status', '状态说明'],
            ['right_button', '操作', 'btn']
          ])
          ->hideCheckbox()
          ->addRightButtons('delete') // 批量添加右侧按钮
          ->addRightButton('custom', $btn_demp_edit) // 添加再提交按钮
          ->setRowList($data_list) // 设置表格数据
          ->setPageTips('如果委托一直未成交请检查确认并手动交易', 'danger')
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function temp_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 't.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $xlsData = Db::view('stock_temp t')
          ->view('stock_subaccount s', 'sub_account', 's.id=t.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where("trust_date", ">=", time() - 36000)
          ->where(["deal_no" => null])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['trust_date'] = date('Y-m-d', $value['trust_date']);
        };
        $title = "当日暂时未成交委托";
        $arrHeader = array('子账户ID', '手机号', '邮箱', '子账户', '交易账户名', '证券代码', '证券名称', '委托日期', '委托时间', '委托编号', '委托价格', '委托数量', '成交数量', '成交金额', '撤单数量', '状态说明');
        $fields = array
        ('sub_id', 'mobile', 'email', 'sub_account', 'lid', 'gupiao_code', 'gupiao_name', 'trust_date', 'trust_time', 'trust_no', 'trust_price', 'trust_count', 'volume', 'amount', 'cancel_order_count', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }

    //将委托再次提交实盘交易
    public function temp_edit()
    {
        $res = request();
        $id = intval($res->param('id'));
        if (empty($id)) {
            $this->error('参数错误', url('temp'));
        }
        $ret = StockSubAccount::checkGreenSparrow();
        if ($ret === false) {
            echo "系统出错";
            exit;
        }
        if ($ret['status'] == 0) {
            echo $ret['message'];
            exit;
        }
        $t_res = Db::name('stock_temp')
          ->where(['id' => $id])
          ->find();
        $time = strtotime(date('Y-m-d', time()));
        if ($t_res['add_time'] < $time) {
            $this->error('此委托已过再次委托时限', url('temp'));
        }
        if (time() >= mktime()) {
        }
        $etype = Borrow::market_type($t_res['gupiao_code']);
        if ($etype == 5) {
            $this->error('请检查卖出的股票代码是否错误', url('temp'));
        }
        $otype = 3;
        //flag1==1 卖出
        if ($t_res['flag1'] === 1) {
            $otype = 2;//1买入 2卖出
        } elseif ($t_res['flag1'] === 0) {
            $otype = 1;//1买入 2卖出
        } else {
            $this->error('委托类型出错', url('temp'));
        }
        if ($otype === 1) {
            $money = $t_res['trust_count'] * $t_res['trust_price'];
            $m_res = [];
            if (config('web_real_api') == 1) {
                $m_res = gs('queryTradeData', [$t_res['broker_id'], 1]);
            }
            if (config('web_real_api') == 2) {
                $res = json_decode(Grid::grid_funds($t_res['broker_id']), true);
                $m_res['1']['1'] = null;
                $m_res['1']['2'] = $res["TotalAvailableAmount"];
                $m_res['1']['3'] = null;
                $m_res['1']['4'] = $res["TotalAvailableAmount"];
                $m_res['1']['5'] = $res["TotalMarketValue"];
                $m_res['1']['6'] = $res["TotalAssets"];
                $m_res['1']['7'] = null;
            }
            if ($m_res[1][2] < $money) {
                $this->error('可用余额不足', url('temp'));
            }
        }
        $trade_id = $t_res['broker_id'];
        $data = [];
        if (config('web_real_api') == 1) {
            $ptype = 5;//5市价委托(上海五档即成剩撤/ 深圳五档即成剩撤)
            $data = gs('commitOrder', [$trade_id, $t_res['gupiao_code'], $t_res['trust_count'], $etype, $otype, $ptype, $t_res['trust_price']]);
        }
        if (config('web_real_api') == 2) {
            if ($otype == 2) {
                $otype = 1;//0->买入 1->卖出 2->融资买入 3->融券卖出 4->买券还券 5->卖券还款 6->现券还券 7->担保品买入 8->担保品卖出
            } else {
                $otype = 0;
            }
            $ptype = 4;//4市价委托(上海五档即成剩撤/ 深圳五档即成剩撤)
            $data = Grid::grid_order($otype, $ptype, $t_res['gupiao_code'], $t_res['trust_price'], $t_res['trust_count'], $trade_id);
        }
        if ($data) {
            $this->success('提交成功!', url('temp'));
        } else {
            $this->error('提交失败!', url('temp'));
        }
    }

    /*
     * 当日成交
     */
    public function deal()
    {
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'st.id desc';
        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['st.for_user'] = $admin_user['for_user'];
        }
        // 读取数据
        $time = strtotime(date('y-m-d', time()));
        $data_list = Db::view('stock_deal_stock st')
          ->view('stock_subaccount s', 'sub_account', 's.id=st.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where('st.status', '<>', '0')
          ->where(['deal_date' => $time])
          ->where($map)
          ->order($order)->paginate($listRows = 20);
        foreach ($data_list as $key => $value) {
            $email = $value['email'] ?: '--';
            $value['user_info'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
            $value['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
            $value['gupiao'] = "<p>" . $value['gupiao_name'] . "</p><p>" . $value['gupiao_code'] . "</p>";
            $data_list[$key] = $value;
        }
        // 分页数据
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        $page = $data_list->render();
        return ZBuilder::make('table')
          ->setPageTitle('当日成交') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_deal_stock') // 设置数据表名
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code') // 添加筛选
          ->setSearch(['sub_id' => '子账户ID', 'sub_account' => '子账户', 'mobile' => '手机号']) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['user_info', '手机号/邮箱'],
              // ['lid', '交易账户名'],
              //['soruce', '证券来源'],
              //['login_name', '证券账户'],
            ['gupiao', '证券名称/证券代码'],
//                ['gupiao_name', '证券名称'],
            ['deal_date', '成交日期', 'date'],
            ['deal_time', '成交时间'],
            ['trust_no', '委托编号'],
            ['trust_price', '委托价格'],
            ['trust_count', '委托数量'],
            ['deal_no', '成交编号'],
            ['deal_price', '成交价格'],
            ['volume', '成交数量'],
            ['amount', '成交金额'],
            ['flag2', '买卖标志'],
            ['status', '状态说明'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function deal_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'st.id desc';
        }
        $time = strtotime(date('y-m-d', time()));
        $xlsData = Db::view('stock_deal_stock st')
          ->view('stock_subaccount s', 'sub_account', 's.id=st.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where('st.status', '<>', '0')
          ->where(['deal_date' => $time])
          ->where($map)
          ->order($order)->paginate($listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['deal_date'] = date('Y-m-d', $value['deal_date']);
        };
        $title = "当日成交";
        $arrHeader = array('子账户ID', '手机号', '邮箱', '子账户', '交易账户名', '证券代码', '证券名称', '成交日期', '成交时间', '委托编号', '委托价格', '委托数量', '成交编号', '成交价格', '成交数量', '成交金额', '买卖标志', '状态说明');
        $fields = array('sub_id', 'mobile', 'sub_account', 'lid', 'gupiao_code', 'gupiao_name', 'deal_date', 'deal_time', 'trust_no', 'trust_price', 'trust_count', 'deal_no', 'deal_price', 'volume', 'amount', 'flag2', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 撤单查询
     */
    public function cancel_order()
    {
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 't.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['t.for_user'] = $admin_user['for_user'];
        }
        if (empty($map['t.trust_date'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['t.trust_date'][1][0]));
        }
        if (empty($map['t.trust_date'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['t.trust_date'][1][1]));
        }
        $data_list = Db::view('stock_trust t')
          ->view('stock_subaccount s', 'sub_account', 's.id=t.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where($map)
          ->where('t.status', '=', '已撤')
          ->order($order)->paginate($listRows);
        foreach ($data_list as $key => $value) {
            $email = $value['email'] ?: '--';
            $value['user_info'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
            $value['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
            $value['gupiao'] = "<p>" . $value['gupiao_name'] . "</p><p>" . $value['gupiao_code'] . "</p>";
            $data_list[$key] = $value;
        }
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        return ZBuilder::make('table')
          ->setPageTitle('撤单查询') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_trust') // 设置数据表名
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code') // 添加筛选
          ->setSearch(['sub_account' => '子账户', 'mobile' => '手机号']) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['user_info', '手机号/邮箱'],
            ['lid', '交易账户名'],
            ['gupiao', '证券名称/证券代码'],
              //['soruce', '证券来源'],
              //['login_name', '证券账户'],
//                ['gupiao_code', '证券代码'],
//                ['gupiao_name', '证券名称'],
            ['trust_date', '委托日期', 'date'],
            ['trust_time', '委托时间'],
            ['trust_no', '委托编号'],
            ['trust_price', '委托价格'],
            ['trust_count', '委托数量'],
            ['volume', '成交数量'],
            ['amount', '成交金额'],
            ['cancel_order_count', '撤单数量'],
            ['status', '状态说明'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->addTimeFilter('t.trust_date', [$beginday, $endday]) // 添加时间段筛选
          ->addTimeFilter('t.trust_date', [$beginday, $endday]) // 添加时间段筛选
          ->fetch();
    }

    public function cancel_order_export()
    {
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 't.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $xlsData = Db::view('stock_trust t')
          ->view('stock_subaccount s', 'sub_account', 's.id=t.sub_id', 'left')
          ->view('member m', 'mobile', 'm.id=s.uid', 'left')
          ->where($map)
          ->where('t.status', '=', '已撤')
          ->order($order)->paginate($listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['trust_date'] = date('Y-m-d', $value['trust_date']);
        };
        $title = "撤单查询";
        $arrHeader = array('子账户ID', '手机号', '子账户', '交易账户名', '证券代码', '证券名称', '委托日期', '委托时间', '委托编号', '委托价格', '委托数量', '成交数量', '成交金额', '撤单数量', '状态说明');
        $fields = array
        ('sub_id', 'mobile', 'sub_account', 'lid', 'gupiao_code', 'gupiao_name', 'trust_date', 'trust_time', 'trust_no', 'trust_price', 'trust_count', 'volume', 'amount', 'cancel_order_count', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 交割单
     */
    public function delivery()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.deal_date desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        if (empty($map['deal_date'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['deal_date'][1][0]));
        }
        if (empty($map['deal_date'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['deal_date'][1][1]));
        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['d.for_user'] = $admin_user['for_user'];
        }
        $data_list = Db::view('stock_delivery_order d')
          ->view('stock_subaccount s', 'sub_account', 's.id=d.sub_id', 'left')
          ->view('member m', 'mobile,email', 'm.id=s.uid', 'left')
          ->where(['d.status' => 1])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        foreach ($data_list as $key => $value) {
            $email = $value['email'] ?: '--';
            $value['user_info'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
            $value['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
            $total_commission = $value['stamp_duty'] + $value['transfer_fee'] + $value['commission'];
            $total_commission = round($total_commission, 2);
            $value['commission2'] = $total_commission . '(净佣金(' . $value['commission'] . ') + 印花税(' . $value['stamp_duty'] . ')+ 过户费(' . $value['transfer_fee'] . ')+ 规费(' . $value['fees'] . '))';
            $value['gupiao'] = "<p>" . $value['gupiao_name'] . "</p><p>" . $value['gupiao_code'] . "</p>";
            $value['number'] = "<p>" . $value['trust_no'] . "</p><p>" . $value['deal_no'] . "</p>";
            $data_list[$key] = $value;
        }
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
          ->setPageTitle('交割单') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_delivery_order') // 设置数据表名
          ->addTimeFilter('deal_date', [$beginday, $endday]) // 添加时间段筛选
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code,sub_id,business_name') // 添加筛选
          ->setSearch(['sub_account' => '子账户', 'mobile' => '手机号', 'email' => '邮箱', 'gupiao_code' => '证券代码', 'gupiao_name' => '证券名称']) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/子账户'],
            ['user_info', '手机号/邮箱'],
              // ['lid', '交易账户名'],
//                ['soruce', '证券来源'],
//                ['login_name', '证券账户'],
            ['gupiao', '证券名称/证券代码'],
            ['deal_date', '成交日期', 'date'],
            ['business_name', '业务名称'],
            ['deal_price', '成交价格'],
            ['volume', '成交数量'],
            ['amount', '剩余数量'],
            ['residual_quantity', '成交金额'],
            ['liquidation_amount', '清算金额'],
            ['residual_amount', '剩余金额'],
            ['commission2', '手续费'],
//                ['stamp_duty', '印花税'],
//                ['transfer_fee', '过户费'],
//                ['commission', '净佣金'],
            ['type', '买入类型', 'status', '', [2 => '市价', 1 => '限价']],
            ['number', '委托编号/成交编号'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function delivery_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $xlsData = Db::view('stock_delivery_order d')
          ->view('stock_subaccount s', 'sub_account', 's.id=d.sub_id', 'left')
          ->view('member m', 'mobile', 'm.id=s.uid', 'left')
          ->where(['d.status' => 1])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        foreach ($xlsData as $key => $value) {
            $xlsData->items->items[$key]['deal_date'] = date('Y-m-d', $value['deal_date']);
        };
        $title = "交割单";
        $arrHeader = array('子账户ID', '手机号', '子账户', '交易账户名', '证券代码', '证券名称', '成交日期', '业务名称', '成交价格', '成交数量', '剩余数量', '成交金额', '清算金额', '剩余金额', '印花税', '过户费', '净佣金', '委托编号', '成交编号');
        $fields = array('sub_id', 'mobile', 'sub_account', 'lid', 'gupiao_code', 'gupiao_name', 'deal_date', 'business_name', 'buy_price', 'volume', 'amount', 'residual_quantity', 'liquidation_amount', 'residual_amount', 'stamp_duty', 'transfer_fee', 'commission', 'trust_no', 'deal_no');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 资金明细
     */
    public function capital_details()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 's.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['s.for_user'] = $admin_user['for_user'];
        }
        $data_list = Subaccount::getSubaccountMoney($map, $order, $listRows);
        foreach ($data_list as $key => $value) {
            $email = $value['email'] ?: '--';
            $data_list[$key]['user_info'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
            $data_list[$key]['stock_subaccount'] = "<p>" . $value['stock_subaccount_id'] . "</p><p>" . $value['sub_account'] . "</p>";
        }
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        return ZBuilder::make('table')
          ->setPageTitle('资金列表') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_subaccount_money') // 设置数据表名
          ->setSearch(['sub_account' => '子账户', 'mobile' => '手机号']) // 设置搜索参数
          ->addOrder('available_amount,return_money') // 添加排序
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['stock_subaccount', '账户ID/交易子账户名'],
            ['user_info', '手机号/邮箱'],
//                ['sub_account', '交易子账户名'],
            ['avail', '可用余额', 'text.edit'],
            ['market', '当前市值'],
            ['available_amount', '可提盈金额'],
            ['return_money', '盈亏金额', 'text.edit'],
            ['freeze_amount', '冻结资金', 'text.edit'],
            ['deposit_money', '保证金'],
            ['borrow_money', '配资金额'],
            ['stock_addfinancing', '扩大配资累计金额'],
            ['stock_addmoney', '追加保证金累计金额'],
            ['stock_drawprofit', '提盈累计金额'],
          ])
          ->addFilter('stock_subaccount_id,sub_account') // 添加筛选
          ->hideCheckbox()
          ->addFilter('stock_subaccount_id') // 添加筛选
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    /*
            * 导出资金流水
            */
    public function capital_details_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 's.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $xlsData = Subaccount::getSubaccountMoney($map, $order, $listRows);
        $title = "资金明细";
        $arrHeader = array('账户ID', '交易子账户名', '可用余额', '可提现金额', '冻结资金', '保证金', '配资金额');
        $fields = array('id', 'sub_account', 'avail', 'available_amount', 'freeze_amount', 'deposit_money', 'borrow_money');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
         * 盈亏记录
         */
    public function stocksubmoneyprofitlossrecord()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'addtime desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        if (empty($map['r.addtime'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['r.addtime'][1][0]));
        }
        if (empty($map['r.addtime'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['r.addtime'][1][1]));
        }
        $data_list = Subaccount::getSubaccountprofitLossRecord($map, $order, $listRows);
        foreach ($data_list as $key => $value) {
            $data_list[$key]['user_name'] = "<p>" . $value['sub_id'] . "</p><p>" . $value['sub_account'] . "</p>";
        }
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        return ZBuilder::make('table')
          ->setPageTitle('盈亏记录(仅对2020年2月2日后数据有效)') // 设置页面标题
          ->addTopButton('custem', $btn_excel)
          ->setTableName('stock_list') // 设置数据表名
          ->setSearch(['sub_id' => '子账户id', 'sub_account' => '交易子账户名', 'code' => '股票代码']) // 设置搜索参数
          ->addRightButton('edit') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['user_name', '子账户ID/交易子账户名'],
            ['affect', '盈亏金额变化'],
            ['return_money', '用户盈亏金额'],
            ['code', '股票代码'],
            ['stock_count', '卖出数量'],
            ['addtime', '交易时间', 'datetime'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->addTimeFilter('r.addtime', [$beginday, $endday]) // 添加时间段筛选
          ->fetch();
    }

    /*
            * 导出盈亏
            */
    public function stocksubmoneyprofitlossrecord_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 20;
        $data_list = Subaccount::getSubaccountprofitLossRecord($map, $order, $listRows);
        $title = "资金明细";
        $arrHeader = array('账户ID', '交易子账户名', '可用余额', '可提现金额', '冻结资金', '保证金', '配资金额');
        $fields = array('id', 'sub_account', 'avail', 'available_amount', 'freeze_amount', 'deposit_money', 'borrow_money');
        export($arrHeader, $fields, $data_list, $title);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志内容
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function quickEdit($record = [])
    {
        $field = input('post.name', '');
        $value = input('post.value', '') * 100;
        $id = input('post.pk', '');
        $validate = input('post.validate', '');
        $validate_fields = input('post.validate_fields', '');
        $field == '' && $this->error('缺少字段名');
        $id == '' && $this->error('缺少主键值');
        $request = request();
        $module_name = $request->module();//当前操作的模型名
        $controller_name = $request->controller();//当前操作的控制器名
        switch ($controller_name) {
            case "Operate":
                #资金明细
                $dbname = "stock_subaccount_money";
                break;
        }
        $datainfo = Db::name($dbname)->where(['id' => $id])->find();
        $res_up = Db::name($dbname)->where(['id' => $id])->update([$field => $value]);
        $record = array();
        switch ($field) {
            case "avail":
                $oldvule = $datainfo['avail'];
                $name = "可用余额";
                break;
            case "return_money":
                #资金明细
                $oldvule = $datainfo['return_money'];
                $name = "盈亏金额";
                break;
            case "freeze_amount":
                #资金明细
                $oldvule = $datainfo['freeze_amount'];
                $name = "冻结资金";
                break;
        }
        if ($res_up) {
            $info = "管理员ID:" . UID . ",手动调整{$name}，原数值为{$oldvule},调整后为{$value}，<br>注:该处单位为分.因有多种类型故影响金额用0表示，可用忽略";
            self::insetsubmoney_record($id, '', $datainfo['avail'], 21, $info);
            ajaxmsg('操作成功');
        } else {
            ajaxmsg('操作失败');
        }
    }

    /*
     * 插入额外子账户调整纪录
     * $sub_id子账户id   $affect变化金额  $account变化后余额  $typ类型  $info备注说明
     * */
    public function insetsubmoney_record($sub_id, $affect = '', $account, $type, $info)
    {
        $record['sub_id'] = $sub_id;
        $record['affect'] = $affect;
        $record['account'] = $account;
        $record['type'] = $type;
        $record['info'] = $info;
        $record['create_time'] = time();
        $record['create_ip'] = get_client_ip(1);
        call_user_func_array('action_log', $record);
        return $inset = Db::name('stock_submoney_record')->insert($record);
    }
}
