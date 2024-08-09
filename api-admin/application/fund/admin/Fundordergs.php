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
namespace app\fund\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\service\UserService;
use app\fund\model\Fund;
use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundInvestmentRecord as FundInvestmentRecordModel;
use app\fund\model\FundOrderGs as FundOrderGsModel;
use app\fund\model\Trader as TraderModel;
use app\fund\model\TraderOrder as TraderOrderModel;
use app\money\model\Money;
use app\money\model\Record;
use app\stock\model\StockList as StockListModel;
use app\user\model\User as AdminUserModel;
use think\Db;
use think\facade\Cache;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Fundordergs extends Admin
{
    /**
     * 待确认
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map        = $this->getMap();
        $order      = $this->getOrder();
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map[] = ['agent_id', '=', $admin_user['for_user']];
        }

        $page = input('page', 1);
        empty($order) && $order = 'id desc';
        // 数据列表
        $map[]     = ['o.status', 'eq', 0];
        $res       = FundOrderGsModel::getFollowList($page, $map, $order);
        $data_list = $res['list'] ?? [];
        foreach ($data_list as $key => $value) {
            $username = empty($value['username']) ? '--' : $value['username'];
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            $email                            = $value['email'] ?: '--';
            $mobile                           = $value['mobile'] ?: '--';
            $str                              = "<p>" . $value['trader_texta'] . "</p><p>" . $value['commission'] . "</p>";
            $data_list[$key]['username_text'] = "<p>{$username}</p><p>" . $value['mobile'] . "</p>";
            $data_list[$key]['user_info']     = "<p>" . $email . "</p><p>" . $mobile . "</p>";
            $data_list[$key]['trader_info']   = $str;
            if ($value['order_type'] == 3) {
                $data_list[$key]['cycle_text'] = "<p>" . $value['cycle'] . '天' . "</p><p>" . $value['settlement_days'] . '天' . "</p>";
            } else {
                $data_list[$key]['cycle_text'] = "无";
            }
        }
        if (empty($map['create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
        }
        if (empty($map['create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['create_time'][1][1]));
        }
        // 分页数据
        $page = $data_list->render();

        $btn_detail = ['title' => '收益明细', 'icon' => 'fa fa-fw fa-cny', 'href' => url('detail', ['order_id' => '__id__'])];
// 批量跟买按钮
        $btn_access = [
            'title' => '批量审核',
            'icon'  => 'fa fa-fw fa-key',
            'class' => 'btn btn-primary  js-get',
            'href'  => url('accessdata') . '?code=1'
        ];
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
//            ->setExtraHtmlFile('search', 'toolbar_top')
            ->setSearchArea([
                ['text', 'order_sn', '订单号', 'like'],
                ['text', 't.name', '操盘师', 'like'],
                ['text', 'm.name', '姓名', 'like'],
                ['text', 'm.mobile', '手机号', 'like'],
                //                ['text', 'o.email', '邮箱', 'like'],
                ['daterange', 'o.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
//            ->setSearch(['o.name' => '名称', 'o.order_sn' => '订单号', 'o.mobile' => '手机号码', 'o.email' => '邮箱']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                           ['id', 'ID'],
                           ['username_text', '名称/手机号'],
                            ['role_name', '白名单', $this->user_role_name],
                           ['order_sn', '订单号'],
                           ['trader_info', '操盘师/操盘师佣金比例（%）'],
                           ['money', '购买金额'],
                           ['cycle_text', '周期/已结算天数'],
                           ['settlement_time', '上次结算时间', 'datetime'],
                           ['create_time', '买入时间', 'datetime'],
                           ['status', '状态', ['待确认', '已确认']],
                           //          ['order_type', '订单类型', [1 => '普通订单', 2 => '每日优投', 3 => 'vip优投']],
                           ['order_type', '订单类型', $this->documentary_array],
                           ['right_button', '操作', 'btn']
            ])->setTableName('FundOrder')
//          ->addTimeFilter('o.create_time', [$beginday, $endday]) // 添加时间段筛选
//            ->addRightButton('custom', $btn_detail) // 批量添加右侧按钮
            ->addRightButton('edit', ['title' => "确认投资订单", 'href' => url('accessdata', ['ids' => '__id__', 'group' => 'index'])])
            ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
            ->addTopButton('custom', $btn_access) // 添加批量买入处理按钮
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])

                                                  //            ->addTopButton('custom', $dqr_access) // 添加批量驳回处理按钮
                                                  //            ->addFilterMap('id', ['code' => '0']) // 只获取group等于cms的id字段信息
                                                  //          ->addTimeFilter('o.create_time') // 添加时间段筛选
                                                  //            ->addFilter('status', ['待确认', '已确认'])
                                                  //            ->fixedLeft(2)
                                                  //            ->fixedRight(1)
            ->setPages($page)                     // 设置分页数据
            ->fetch(); // 渲染模板
    }

    /**
     * 持仓订单
     * @return mixed
     */
    public function orderlist()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map   = $this->getMap();
        $map   = (new UserService())->agentPartnerSearchMap($map, 'o.uid');
        $map   = (new UserService())->getAgentMap($map, 'o.uid');
        $order = $this->getOrder();
        $page  = input('page', 1);
        if (input('type') == 'remarks') {
            //有备注的
            $map[] = ['m.remarks', 'neq', ''];
        }
        empty($order) && $order = 'id desc';
        // 数据列表
        //$map[] = ['o.status', 'eq', 6];
        $map[]     = ['o.status', 'in', [1, 3, 4, 5, 6, 7]];
        $res       = FundOrderGsModel::getFollowList($page, $map, $order);
        $data_list = $res['list'] ?? [];
        foreach ($data_list as $key => $value) {
            $username                        = empty($value['username']) ? '--' : $value['username'];
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            $data_list[$key]['user_info']    = "<p>" . $username . "</p><p>" . $value['mobile'] . "</p>";
            $data_list[$key]['codetype']     = $this->documentary_array[$value['order_type']];
            $data_list[$key]['orderbalance'] = orderbalance($value['id'], 'FundOrderGs');
            $data_list[$key]['earning_rate'] = round((($value['money'] + $value['balance']) / $value['money'] - 1) * 100, 2);
            if ($data_list[$key]['earning_rate'] > 0) {
                $data_list[$key]['earning_rate'] = '<span style="color: #a92222">' . $data_list[$key]['earning_rate'] . "%</span>";
            } else {
                $data_list[$key]['earning_rate'] = '<span style="color:#46c37b">' . $data_list[$key]['earning_rate'] . "%</span>";
            }

        }
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_detail          = ['title' => '收益明细', 'icon' => 'fa fa-fw fa-cny', 'href' => url('detail', ['order_id' => '__id__'])];
        $btn_SettlementOrder = ['title' => '结算订单', 'icon' => 'fa fa-fw fa-cny', 'href' => url('settlementorder', ['ids' => '__id__'])];

        $btn_renewal       = ['title' => '合约续期', 'icon' => 'fa fa-fw fa-cny', 'href' => url('renewal', ['ids' => '__id__'])];
        $btn_addInvestment = ['title' => '追加投资', 'icon' => 'fa fa-fw fa-cny', 'href' => url('addInvestment', ['ids' => '__id__'])];

        $btn_edit = ['title' => '修改日期', 'icon' => 'fa fa-fw fa-key', 'href' => url('dataUpdate', ['ids' => '__id__'])];
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        $btn_remarks = [
            'title' => '查看已备注用户',
            'icon'  => 'fa si si-note',
            'class'  => 'btn btn-success',
            'href'  => url('/fund/fundordergs/orderlist') . '?type=remarks'
        ];
        $totalsum = FundOrderGsModel::gettotalsum($map);
        $html = <<<EOF
            <br><p>总金额为：{$totalsum}元</p>
EOF;
        return ZBuilder::make('table')
            ->setPageTitle('持仓优投订单（合约）')
            ->setSearchArea([
                ['text', 'order_sn', '订单号', 'like'],
                ['text', 't.name', '操盘师', 'like'],
                ['text', 'm.name', '姓名', 'like'],
                ['text', 'o.mobile', '手机号', 'like'],
                ['text', 'o.email', '邮箱', 'like'],
                ['select', 'order_type', '类型', '', '', $this->documentary_array],

                ['select', 'o.status', '状态', '', '', [1 => '已确认', 3 => '已结束', 6 => '持仓中', 7 => '待结算（有订单未卖出）']],

                ['daterange', 'o.confirm_time', '确认时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID', ''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID', ''],
                ['text', 'm.remarks', '备注', 'like'],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
            ->addColumns([ // 批量添加数据列
                           ['id', 'id'],
                           ['order_sn', '订单号'],
                           ['codetype', '订单类型'],
                           ['user_info', '姓名/手机号码'],
                ['remarks', '备注', 'text.edit'],
                ['role_name', '白名单', $this->user_role_name],
                           ['trader_texta', '操盘师'],
                           ['commission', '佣金比'],
                           ['create_time', '开始时间', 'datetime'],
                           ['fundendtime', '结束时间', 'datetime'],
                           ['confirm_time', '确认时间', 'datetime'],
                           ['money', '合约金额'],
                           ['orderbalance', '可用余额'],
                           ['fund_contract', '合约天数'],
                           ['balance', '持有收益'],
                           ['earning_rate', '收益率'],
                           ['settlement_last_time', '上次结算时间', 'datetime'],
                           ['settlement_time', '结算时间', 'datetime'],

                           ['settlement_amount', '结算收益'],
                           ['status', '状态', [1 => '已确认', 3 => '已结束', 4 => '已撤销', 5 => '已驳回', 6 => '持仓中', 7 => '待结算（有订单未卖出）']],

                           ['right_button', '操作', 'btn']
            ])->setTableName('FundOrder')
            ->addRightButton('custom', $btn_detail) // 批量添加顶部按钮
            ->addRightButton('SettlementOrder', $btn_SettlementOrder) // 批量添加顶部按钮
            ->addRightButton('renewal', $btn_renewal) // 批量表格按钮
            ->addTopButton('custem', $btn_remarks)
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
            ->addRightButton('addInvestment', $btn_addInvestment) // 批量表格按钮
            ->addRightButton('btn_edit', $btn_edit)
            ->addRightButton('edit', ['title' => '编辑订单'])
            ->addOrder('id,is_del')
            ->setRowList($data_list) // 设置表格数据
            ->setColumnWidth('user_info', 160)
            ->setColumnWidth('right_button', 180)
            ->replaceRightButton(['status' => '3'], '', 'renewal,addInvestment,SettlementOrder,btn_edit,edit')
            ->replaceRightButton(['status' => '5'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
            ->replaceRightButton(['order_type' => ['in', '1,2']], '', 'renewal')
//            ->replaceRightButton(['order_type' => ['in', '2']], '', 'addInvestment')
            ->replaceRightButton(['order_type' => 1], '', 'custom,renewal,SettlementOrder')

            ->fixedLeft(2)
            ->fixedRight(1)
            ->hideCheckbox()
            ->setExtraHtml($html, 'table_bottom')
            ->fetch(); // 渲染模板
    }

    /**
     * 收益详情
     * @return mixed
     */
    public function detail()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $page     = input('page', 1);
        $order_id = input('order_id', '');
        $order    = 'id desc';
        // 数据列表
        $data_list = Db::name('fund_income_log')->where('order_id', $order_id)->where('status', 1)->order($order)->paginate();

        // 分页数据

        return ZBuilder::make('table')
            ->addColumns([ // 批量添加数据列
                           ['id', 'ID'],
                           ['name', '基金名称'],
                           ['code', '基金代码'],
                           ['money', '收益金额'],
                           ['rebate', '收益率（%）'],
                           //            ['type', '类型', [1 => '交易日收益', 2 => '周工资', 3 => '合伙人补贴', 4 => '分销奖励']],
                           ['create_time', '时间', 'datetime'],
            ])->setTableName('fund_income_log')
            ->hideCheckbox()
            ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    /**
     * 周工资
     * @return mixed
     */
    public function weekBonus()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map   = $this->getMap();
        $map[] = ['f.order_type', 'egt', 2];
        $order = 'id desc';
        if (empty($map['create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
        }
        if (empty($map['create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['create_time'][1][1]));
        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map[] = ['agent_id', '=', $admin_user['for_user']];
        }
        // 数据列表
        $data_list = Db::name('fund_income_log')->view('fund_income_log f', true)
            ->view('member m', 'mobile,email', 'm.id=f.uid', 'LEFT')
            ->where($map)->order($order)->paginate();
        // 分页数据
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_edit = ['title' => '编辑', 'icon' => 'fa fa-fw fa-pencil', 'href' => url('editBonus', ['id' => '__id__'])];
        return ZBuilder::make('table')
            ->addColumns([ // 批量添加数据列
                           ['id', 'ID'],
                           ['uid', '用户ID'],
                           ['name', '基金名称'],
                           ['code', '基金代码'],
                           ['username', '姓名'],
                           ['mobile', '手机号'],
                           ['offline_account', '下线账号'],
                           ['money', '收益金额'],
                           ['rebate', '分佣比例（%）'],
                           ['level', '层级'],
                           ['order_type', '类型', [1 => '交易日收益', 2 => '周工资', 3 => '合伙人补贴', 4 => '分销奖励']],
                           ['status', '状态', ['未发放', '已发放']],
                           ['money', '金额'],
                           ['create_time', '时间', 'datetime'],
                           ['right_button', '操作', 'btn']
            ])->setTableName('fund_income_log')
//          ->addColumn('type', '状态', 'status', '', [1 => '交易日收益', 2 => '周工资', 3 => '合伙人补贴', 4 => '分销奖励'])
            ->addTimeFilter('f.create_time') // 添加时间段筛选
            ->addOrder('id,status')->setRowList($data_list) // 设置表格数据
            ->addRightButton('custom', $btn_edit)           // 批量添加右侧按钮
            ->fetch(); // 渲染模板
    }

    public function editBonus($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $update_data = input();
            // 验证
            $data = $update_data;
            if ($data['status'] == 1) {
                if ($id) {
                    $ids[] = $id;
                    $this->grantWeekBonus($ids);
                    $this->success('编辑成功', cookie('__forward__'));
                }
            } else {
                $this->error('编辑失败');
            }
        }
        $info = Db::name('fund_income_log')->where('id', $id)->find();
        return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['hidden', 'id'],
                         ['radio', 'status', '状态', '', ['未确认', '确认']],
        ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    public function status($record = [])
    {
        return $this->setStatus('status');
    }

    /**
     * 设置用户状态：禁用、启用
     *
     * @param string $type 类型：enable/disable
     * @param array $record
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public function setStatus($type = '', $record = [])
    {
        $table_name = input('param.table');
        $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        if ($ids) {
            $this->grantWeekBonus($ids);
        }
        $this->success('操作成功');
    }

    public function grantWeekBonus($ids = [])
    {
        $map     = [];
        $map[]   = ['id', 'in', $ids];
        $list    = Db::name('fund_income_log')->where($map)->select();
        $new_ids = [];
        foreach ($list as $key => $value) {
            if ($value['status'] == 1) {
                continue;
            }
            $user_balance = Money::getMoney($value['uid']);
            $bonus        = $value['money'];
            $info         = '周工资：收益金额：' . $bonus . '元';
            $bonus        = $bonus * 100;
            Money::where('mid', $value['uid'])->setInc('account', $bonus);
            $account = $user_balance['account'] + $bonus;
            $obj     = ['affect' => $bonus, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
            Record::saveData($value['uid'], $bonus, $account, 90, $info, '', '', $obj);
            $new_ids[] = $value['id'];
        }
        if ($new_ids) {

            $map[] = ['id', 'in', $new_ids];
            Db::name('fund_income_log')->where($map)->update(['status' => 1]);
        }
        return;
    }

    /***
     * 审核确认操作
     * access
     **/
    public function accessdata($ids = null)
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            if ($data['status'] == 0) {//取消操作
                $this->success('编辑成功', cookie('__forward__'));
            }
            $orderlist = explode(',', $data['orderlist']);
            foreach ($orderlist as $v => $K) {
                $Orderinfo = FundOrderGsModel::where('id', $K)->find();
                if ($Orderinfo['status'] != 0) {
                    $this->error('ID:' . $K . ':该订单已审核');
                }
                #把冻结金额返还或扣除冻结金额   ------旧
                $user_balance = Money::getMoney($Orderinfo['uid']);
//                Money::where('mid', $Orderinfo['uid'])->setDec('freeze', $Orderinfo['money'] * 100);



                $freeze=$user_balance['freeze']-$Orderinfo['money'] * 100<0 ?0:$user_balance['freeze']-$Orderinfo['money']* 100;


                Money::where('mid', $Orderinfo['uid'])->update(['freeze'=>$freeze]);
                // Money::where('mid', $Orderinfo['uid'])->setDec('freeze', $Orderinfo['money'] * 100);


                if ($data['status'] == 1) {
                    $info    = $Orderinfo['order_sn'] . '审核通过,扣除冻结金额' . $Orderinfo['money'] . '元';
                    $account = $user_balance['account'];
                    //                拆分，订单和追加的
                    $obj = ['affect' => $Orderinfo['money'] *100, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => $Orderinfo['order_sn']];
                    Record::saveData($Orderinfo['uid'], $Orderinfo['money'] * 100, $account, 99, $info, '', '', $obj);
                } else {
                    #审核不通过
//                    Money::where('mid', $Orderinfo['uid'])->setInc('account', $Orderinfo['money'] * 100);
                    Money::where('mid', $Orderinfo['uid'])->setInc('account', ($Orderinfo['money']-$Orderinfo['add_money']) * 100);
//                    $account = $user_balance['account'] + $Orderinfo['money'] * 100;
                    $account = $user_balance['account'] + ($Orderinfo['money']-$Orderinfo['add_money']) *100; //扣掉追加
                    $info    = $Orderinfo['order_sn'] . '跟投审核被拒,返还冻结金额' . $Orderinfo['money'] . '元';

                    //                拆分，订单和追加的
                    $obj = ['affect' => ($Orderinfo['money']-$Orderinfo['add_money']) *100, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => $Orderinfo['order_sn']];
                    Record::saveData($Orderinfo['uid'], $Orderinfo['money'] * 100, $account, 99, $info, '', '', $obj);
                    if($Orderinfo['add_money'] > 0){
                        //                拆分，订单和追加的
                        $user_balance1 = Money::getMoney($Orderinfo['uid']);
                        Money::where('mid', $Orderinfo['uid'])->setInc('account', $Orderinfo['add_money'] * 100);
                        $account1 = $user_balance1['account'] + $Orderinfo['add_money'] *100; //扣掉追加
                        $info1    = $Orderinfo['order_sn'] . '跟投审核被拒,返还追加冻结金额' . $Orderinfo['add_money'] . '元';
                        $obj1 = ['affect' => $Orderinfo['add_money'] *100, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => $Orderinfo['order_sn']];
                        Record::saveData($Orderinfo['uid'], $Orderinfo['add_money'] * 100, $account1, 113, $info1, '', '', $obj1);

                    }else{
                        $get_one = FundInvestmentRecordModel::where('order_id',$K)
                            ->where('uid',$Orderinfo['uid'])
                            ->find();
                        if($get_one){
                            //                审核驳回返回追加
                            $info2 = "追加金额退回：" . $Orderinfo['order_sn'];
                            $affect2 = $get_one['money']  * 100;
                            $type = 113;
                            $user_balance2 = Money::getMoney($Orderinfo['uid']);
                            $account2 = bcadd($user_balance2['account'], $affect2);

                            $freeze=$user_balance2['freeze']-$get_one['money'] * 100<0 ?0:$user_balance2['freeze']-$get_one['money']* 100;
                            Money::where('mid', $get_one['uid'])->update(['freeze'=>$freeze,'account'=>$account2]);
                            $obj = ['affect' => $affect2, 'account' => $account2, 'affect_activity' => 0, 'activity_account' => 0, 'sn' => $Orderinfo['order_sn']];
                            Record::saveData($get_one['uid'], $affect2, $account2, $type, $info2, '', '', $obj);
                        }
                    }
//                    退回待审核追加
                    FundInvestmentRecordModel::where('order_id',$K)
                        ->where('uid',$Orderinfo['uid'])
                        ->update(['status'=>2,'examine_time'=>time()]);

                }

//-------------------------------------------------
                #如果通过了冻结金额结算后在扣除，驳回直接返回   ------新
//                if ($data['status'] == 5) {
//                    #审核不通过
//                    Money::where('mid', $Orderinfo['uid'])->setDec('freeze', $Orderinfo['money'] * 100);
//                    Money::where('mid', $Orderinfo['uid'])->setInc('account', $Orderinfo['money'] * 100);
//                    $account = $user_balance['account'] + $Orderinfo['money'] * 100;
//                    $info    = $Orderinfo['order_sn'] . '跟投审核被拒,返还冻结金额' . $Orderinfo['money'] . '元';
//                    $obj = ['affect' => $Orderinfo['money'] * 100, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => $Orderinfo['order_sn']];
//                    Record::saveData($Orderinfo['uid'], $Orderinfo['money'] * 100, $account, 99, $info, '', '', $obj);
//                }
//                ----------------------------------

                FundOrderGsModel::where('id', $K)->update(['status' => $data['status'], 'confirm_time' => time()]);
            }
            $this->success('编辑成功', cookie('__forward__'));
        }
        $ids  = (array)$ids;
        $defu = 1;
        return ZBuilder::make('form')->setPageTitle('订单审核') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['tags', 'orderlist', '订单ID', '', implode(',', $ids)],
                         ['radio', 'status', '购买状态', '审核通过后将进入需要买入状态', [1 => '通过', 5 => '驳回'], $defu],
        ])
            ->setTrigger('status', '0', 'note')
//            ->setTrigger('status', '1', 'porductlist,buyprice,buytime')
            ->fetch();
    }

    /**
     * 每日下单操作
     * @return void
     */
    public function daybuy()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map   = $this->getMap();
        $map   = (new UserService())->getAgentMap($map, 'm.id');
        $order = $this->getOrder();
        $page  = input('page', 1);

        empty($order) && $order = 'id desc';
        $map[] = ['o.status', 'eq', 0];

        $res       = FundDaylineModel::getList($page, $map, $order);
        $data_list = $res['list'] ?? [];
        foreach ($data_list as &$v) {
            $username       = empty($v['username']) ? '--' : $v['username'];
            $v['mobile'] = privacy_info_switch('mobile',$v['mobile']);
            $v['user_info'] = "<p>" . $username . "</p><p>" . $v['mobile'] . "</p>";
        }
        // 分页数据
        $page             = $data_list->render();
        $btn_access       = [
            'title' => '下单',
            'icon'  => 'fa fa-fw fa-key',
            'class' => 'btn btn-primary  js-get',
            'href'  => url('access') . '?code=1'
        ];
        $btn_dayorder     = [
            'title' => '生成当日订单',
            'icon'  => 'fa fa-fw fa-plus-circle',
            'class' => 'btn ajax-get',

            'href' => '/index.php/stock/Crond/autobuildgsrecord'
            //          'href' => url('/stock/Crond/autobuildgsrecord')
        ];
        $btn_access_right = [
            'title' => '跟买',
            'icon'  => 'fa fa-fw fa-cny',
            'class' => 'btn btn-xs btn-danger',
            'href'  => url('access', ['ids' => '__id__'])
        ];
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];

        return ZBuilder::make('table')
            ->setSearchArea([
                ['text', 'o.order_sn', '订单号', 'like'],
                ['text', 't.name', '操盘师', 'like'],
                ['text', 'm.name', '姓名', 'like'],
                ['text', 'm.mobile', '手机号', 'like'],
                ['select', 'order_type', '类型', '', '', $this->documentary_array],
                ['daterange', 'o.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
            ->addColumns([ // 批量添加数据列
                           ['date', '股票交易日期'],

                           ['sn', '订单号'],
                           ['trader_texta', '操盘师'],
                           ['user_info', '姓名/手机号'],
                            ['role_name', '白名单', $this->user_role_name],
                           ['order_type', '订单类型', $this->documentary_array],

                           ['create_time', '添加时间', 'datetime'],
                           ['right_button', '操作', 'btn']
            ])
            ->setPrimaryKey('id')
            ->setPageTips('1、请选择同一操盘师下单<br>2、如果还有未购买的也无法生成新订单，需要下新单需把未购买删除或先购买后在点击“生成当日订单”', 'warning')
            ->addRightButton('delete', ['href' => url('deletedayline', ['ids' => '__id__'])])
            ->addRightButton('custom', $btn_access_right)
//            ->addRightButton('details', ['icon' => 'fa fa-eye', 'title' => '详情', 'href' => url('details', ['id' => '__id__'])])

            ->addOrder('id,is_del')
            ->setRowList($data_list) // 设置表格数据
            ->addTopButton('custom', $btn_access)   // 添加批量买入处理按钮
            ->addTopButton('custom', $btn_dayorder) // 添加批量买入处理按钮
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])

            ->fetch(); // 渲染模板
    }

    /**
     * 每日下单记录--结算记录
     * @return void
     */
    public function daybuyrecord()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->getAgentMap($map, 'fo.uid');
//        if($map[0][0]=='status'){
//            $map[] = ['o.status',$map[0][1], $map[0][2]];
//        }
        $fifilt = input();
        if (isset($fifilt['type']) && $fifilt['type'] == "sell") {
            $map[] = ['o.status', 'eq', 1];
        } elseif (isset($fifilt['type']) && $fifilt['type'] == "finish") {
            $map[] = ['o.status', 'eq', 2];
        } else {
          $map[] = ['o.status', 'in', '1,2,3'];
        }

        $order = $this->getOrder();
        $page  = input('page', 1);

        empty($order) && $order = 'id desc';
        $res       = FundDaylineModel::getList($page, $map, $order);
        $data_list = $res['list'] ?? [];
        foreach ($data_list as &$v) {
            $username       = empty($v['username']) ? '--' : $v['username'];
            $v['mobile'] = privacy_info_switch('mobile',$v['mobile']);
            $v['user_info'] = "<p>" . $username . "</p><p>" . $v['mobile'] . "</p>";
            if ($v['status'] == 2) {
                $v['new_status'] = '<span style="color: #00ff8c">已卖出</span>';
            } else if ($v['status'] == 3) {
                $v['new_status'] = '<span style="color: red">已结算</span>';
            } else {
                $v['new_status'] = '<span style="color:green">待卖出</span>';
            }
            $v['sum_buy_price']  = $v['buy_price'] * $v['num'] == 0 ? '' : $v['buy_price'] * $v['num'];
            $v['sum_sell_price'] = $v['sell_price'] * $v['num'] == 0 ? '' : $v['sell_price'] * $v['num'];
        }
        // 分页数据
        $page = $data_list->render();
        // 批量卖出按钮
        $btn_access = [
            'title' => '卖出',
            'icon'  => 'fa fa-fw fa-cny',
            'class' => 'btn btn-danger  js-get',
            'href'  => url('daybuyrecordsell') . '?code=1'
        ];

        $btn_recharge_sell   = [
            'title' => '只看待卖出',
            'icon'  => 'fa fa-fw fa-calendar',
            'href'  => url('daybuyrecord?type=sell')
        ];
        $btn_position        = [
            'title' => '特殊会员调整仓位',
            'icon'  => 'fa fa-fw fa-cny',
            'class' => 'btn btn-primary',
            'href'  => url('fundordergs/position')
        ];
        $btn_recharge_finish = [
            'title' => '只看待结算',
            'icon'  => 'fa fa-fw fa-calendar',
            'href'  => url('daybuyrecord?type=finish')
        ];
        $btn_access_right    = [
            'title' => '卖出',
            'icon'  => 'fa fa-fw fa-cny',
            'class' => 'btn btn-xs btn-danger',
            'href'  => url('daybuyrecordsell', ['ids' => '__id__'])
        ];
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
//          ->setTableName('fund_dayline') // 设置数据表名
//            ->setExtraHtmlFile('daybuyrecordSearch', 'toolbar_top')
//          ->setSearch(['m.mobile' => '手机号', 'fo.order_sn' => '订单号', 'o.stockcode' => '股票代码']) // 设置搜索框
            ->setSearchArea([
                ['text', 'fo.order_sn', '订单号', 'like'],
                ['text', 'm.name', '姓名', 'like'],
                ['text', 'm.mobile', '手机号', 'like'],
                ['text', 't.name', '操盘师', 'like'],
                ['text', 'o.stockname', '股票名称', 'like'],
                ['text', 'o.stockcode', '股票代码', 'like'],
                ['select', 'order_type', '类型', '', '', $this->documentary_array],
                ['select', 'o.status', '状态', '', '', [1 => '待卖出', 2 => '已卖出', 3 => '已结算']],
                ['daterange', 'o.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
            ->addColumns([ // 批量添加数据列
                           ['date', '股票交易日期'],
                           ['sn', '订单号'],
                           ['user_info', '姓名/手机号码'],
                ['role_name', '白名单', $this->user_role_name],
                           ['stockname', '股票名称'],
                           ['stockcode', '股票代码'],
                           ['trader_texta', '操盘师'],
                           ['commission_ratio', '讲师佣金比'],
                           ['sum_buy_price', '买入总价'],
                           ['num', '数量'],
                           ['buy_time', '买入时间'],
                           ['buy_price', '买入价格'],
                           ['position', '仓位（%）'],
                           ['sell_time', '卖出时间'],
                           ['sell_price', '卖出价格'],
                           ['sum_sell_price', '卖出总价'],
                           ['profit', '利润'],
                           ['buy_cost', '买入成本'],
                           ['sell_cost', '卖出成本'],
                           ['commission', '讲师分润'],
                           ['system_commission', '平台分润'],
                           ['order_type', '订单类型', $this->documentary_array],

                           ['traderorder_id_text', '智投方案'],
                           ['new_status', '状态'],
                           ['create_time', '生成时间', 'datetime'],
                           ['right_button', '操作', 'btn'],
                           //                ['new_status', '状态', [1 => '已下单', 3 => '已卖出']],
            ])
            ->setPrimaryKey('id')
//            ->addRightButtons(['edit']) // 批量添加右侧按钮
            ->addOrder('id,is_del')
            ->setRowList($data_list) // 设置表格数据
            ->addTopButton('custom', $btn_access) // 添加批量买入处理按钮
            ->addTopButton('custem', $btn_recharge_sell)
            ->addTopButton('custem', $btn_recharge_finish)
            ->addTopButton('custom', $btn_position)  // 特殊用户批量调整仓位
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
            ->addRightButton('custom', $btn_access_right)
            ->replaceRightButton(['status' => '3'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
            ->replaceRightButton(['status' => 2], '<a title="" icon="fa fa-fw fa-cny" class="btn btn-xs btn-warning" href="' . url('fund/fundordergs/DailyTrading', ['ids' => '__id__']) . '" _tag="custom" data-toggle="tooltip" data-original-title="结算"><i class="fa fa-fw fa-cny"></i> 结算 </a>')
//          ->addTopButton('custom', $dqr_access) // 添加批量结算处理按钮
//          ->addTimeFilter('o.buy_time', '', '买入时间') // 添加时间段筛选
//            ->addTopButton('custom', $dqr_access) // 添加批量驳回处理按钮
//            ->addFilter('status', [0 => '等待确认', 1 => '已下单', 2 => '已卖出', 3 => '已结算'])
//            ->addFilterMap('status', 1)
            ->setPageTips('1、请选择同一操盘师卖出<br>2、有匹配智投方案的，每天下午3点自动结算', 'warning')
            ->setColumnWidth('user_info', 160)
            ->fetch(); // 渲染模板
    }

    /**
     * Desc : 每日操盘结算
     * Date : 2024-07-02 03:03
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function DailyTrading($ids = '')
    {
        $ids  = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $info = FundDaylineModel::where(['id' => $ids])->find();
        if (!$info && $info['status'] != 2) {
            $this->error('数据不正确');
        }
        if ($info['num'] == 0 || $info['num'] == '') {

            //计算仓位
            #获取余额
            $orderbalance = orderbalance($ids);

            $balance = $orderbalance;
            printlog($balance, "可用余额：", 'jiesuan');
            if ($info['position']) {
                $position = $info['position'] / 100;
            } else {
                $position = 1;
            }

            printlog($position, "持仓比例：", 'jiesuan');

            $num = ($balance * $position) / $info['buy_price'];
            printlog($num, "能买的数量：", 'jiesuan');

            $num = floor($num);
            if ($num <= 0) {
                $this->error('订单可用余额不足，可操盘余额只有' . $balance);
            }
            FundDaylineModel::where(['id' => $ids])->update(['num' => $num]);
        }
        printlog('', "进入结算：", 'jiesuan');
        //计算收入
        $Trading = FundOrderGsModel::settlementFundgs($ids);
        if ($Trading) {
            $this->success('操作成功', cookie('__forward__'));
        }
        $this->error('结算失败');

    }

    /**
     * 批量卖出 价格-时间
     *
     * @param $id
     *
     * @return void
     */
    public function daybuyrecordsell($ids = null)
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            // 验证
            $result = $this->validate($data, 'Fund.daysell');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            if (!get_trading_time($data['selltime'])) {
                $this->error('卖出日期不是交易日期');
            }
            foreach (explode(',', $data['orderlist']) as $K) {
                $info = FundDaylineModel::where(['id' => $K])->find();
                if ($info['status'] != 1) {
                    continue;
                }
                $info = empty($info) ? $info : $info->toArray();

                if (strtotime($info['buy_time']) > strtotime($data['selltime'])) {
                    $this->error('ID为' . $info['id'] . '卖出时间不能早于买入时间');
                }
                if (date("Y-m-d", strtotime($info['buy_time'])) == date("Y-m-d", strtotime($data['selltime']))) {
                    $this->error('ID为' . $info['id'] . '买卖时间不能为同一天');
                }
                if ($info['status'] == 1) {

                    FundDaylineModel::where(['id' => $K])->update(['sell_time' => $data['selltime'], 'sell_price' => $data['sellprice'], 'status' => 2]);
                    //计算收入 结算
                    FundOrderGsModel::settlementFundgs($K);

                } else {
                    $this->error('ID为' . $info['id'] . '订单状态不对不能操作，在列表中筛选状态后再操作');
                }
            }
            $this->success('操作成功', cookie('__forward__'));
        }
        $ids = (array)$ids;
        //        判断是否不同操盘师
        $list      = Db::name('fund_dayline')
            ->alias('o')
            ->where('o.id', 'in', $ids[0])
            ->join('fund_order_gs fo', 'fo.id = o.order_id')
            ->field('fo.trader_id')
            ->select();
        $traderIds = array_column($list, 'trader_id');
        // 检查所有trader_id是否相同
        $firstTraderId = reset($traderIds);                     // 获取第一个trader_id作为基准
        $areAllIdsSame = count(array_unique($traderIds)) === 1; // 检查去重后的数组长度是否为1

        if (!$areAllIdsSame) {
            $this->error('请选择同一操盘师!');
        }

        $datainfo  = FundDaylineModel::where('id', $ids[0])->find();
        $stocklist = Db::name('stock_list')->where('status', 1)->select();
        $stock     = [];
        foreach ($stocklist as $k => $v) {
            $stock[$v['code']] = $v['title'];
        }

        return ZBuilder::make('form')->setPageTitle('批量设置卖出时间') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['tags', 'orderlist', '订单ID', '', implode(',', $ids)],
                         ['static', 'stockname', '股票名称', '', $datainfo->stockname],
                         ['static', 'buy_time', '买入时间', '', $datainfo->buy_time],
                         ['static', 'buy_price', '买入价格', '', $datainfo->buy_price],
                         ['static', 'num', '买入数量', '', $datainfo->num . "股"],
                         ['hidden', 'stockcode', $datainfo->stockcode],
                         ['date', 'selltime', '卖出时间', '操作卖出交易时间，便于跟市场行情对得上'],
                         ['number', 'sellprice', '卖出价格', '自动获取到的现价，价格仅供参考'],
        ])
                                                                        //            ->setExtraJs($js)
                                                                        ->fetch();
    }

    /**
     * Desc : 编辑每次跟投订单
     * Date : 2024-07-02 03:09
     *
     * @param $id
     *
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function daybuyrecordedit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $update_data = input();
            // 验证
            $data = $update_data;
            FundOrderGsModel::where('id', $id)->update(['cycle' => $data['cycle'], 'trader_id' => $data['trader_id']]);
            $this->success('编辑成功', cookie('__forward__'));
        }
        $info      = FundDaylineModel::where('id', $id)->find();
        $stocklist = Db::name('stock_list')->where('status', 1)->select();
        $stock     = [];
        foreach ($stocklist as $k => $v) {
            $stock[$v['code']] = $v['title'];
        }
        return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['hidden', 'id'],
                         ['select', 'porductlist', '股票', '', $stock],
                         ['number', 'buy_price', '买入价格'],
                         ['datetime', 'buy_time', '买入时间'],
        ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    /**
     * 结算优投订单
     *
     * @param $ids
     *
     * @return void
     */
    public function settlementorder($ids = null)
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }
        $settlementorders = FundOrderGsModel::settlementorders($ids,true);
        if ($settlementorders['code']) {
            $this->success($settlementorders['msg'], cookie('__forward__'));
        }

        $this->error($settlementorders['msg']);

    }

    /***
     * 每日操盘购买确认
     * access
     **/
    public function access($ids = null)
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            // 验证
            $result = $this->validate($data, 'Fund.daybuy');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            if (!get_trading_time($data['buytime'])) {
                $this->error('买入时间不在交易时间内');
            }

            $stockname = Db::name('stock_list')->where('code', $data['porductlist'])->value('title');
            foreach (explode(',', $data['orderlist']) as $v => $K) {
                //获取该用户等级的持仓比例  传入UID
                $FundDaylineinfo = TraderOrderModel::getcw('', $K);

                $FundOrderinfo = FundOrderGsModel::where('id', $FundDaylineinfo['order_id'])->find();
                $orderbalance  = orderbalance($K);
                $info          = $data;

                $balance = $orderbalance;
                printlog($balance, "可用余额：", 'buy');
                printlog($FundDaylineinfo['max_position_ratio'], "用户最大仓位比例：", 'buy');
                printlog($info['position'], "输入的：", 'buy');
                $position = !empty($info['position']) ? $info['position'] / 100 : $FundDaylineinfo['max_position_ratio'] / 100;
                if ($position <= 0) {
                    $this->error('仓位比例需要大于0');
                }
                printlog($position, "持仓比例：", 'buy');
                $num = ($balance * $position) / $info['buy_price'];
                printlog($num, "能买的数量：", 'buy');
                $num = floor($num);
                if ($num <= 0) {
                    continue;
                }
                $buyprice_sum = $data['buyprice'] * $num;
                #印花税
                $stamp_duty = config('stamp_duty') / 10000;
                #过户费
                $transfer_fee = config('transfer_fee') / 1000;
                #券商佣金
                $commission = config('commission') / 10000;
                #规费
                $stock_trading_fees = config('stock_trading_fees') / 1000;

                if (config('buy_cost')) {
                    #买入成本
                    $buy_cost = $buyprice_sum * $commission + $buyprice_sum * $transfer_fee + $buyprice_sum * $stock_trading_fees;
                } else {
                    $buy_cost = 0;
                }

                FundOrderGsModel::where('id', $FundDaylineinfo['order_id'])->update(['status' => 6, 'is_sell' => 0]);
                FundDaylineModel::where(['id' => $K])->update([
                    'stockname'        => $stockname,
                    'stockcode'        => $data['porductlist'],
                    'position'         => $position * 100,
                    'buy_cost'         => $buy_cost,
                    'num'              => $num,
                    'commission_ratio' => $FundOrderinfo['commission'],
                    'buy_time'         => $data['buytime'],
                    'buy_price'        => $data['buyprice'],
                    'status'           => 1
                ]);
            }
            $this->success('操作成功', cookie('__forward__'));
        }
        $ids = (array)$ids;

        #        判断是否不同操盘师
        $list      = Db::name('fund_dayline')
            ->alias('o')
            ->where('o.id', 'in', $ids[0])
            ->join('fund_order_gs fo', 'fo.id = o.order_id')
            ->field('fo.trader_id')
            ->select();
        $traderIds = array_column($list, 'trader_id');
        # 检查所有trader_id是否相同
        $firstTraderId = reset($traderIds);                     // 获取第一个trader_id作为基准
        $areAllIdsSame = count(array_unique($traderIds)) === 1; // 检查去重后的数组长度是否为1

        if (!$areAllIdsSame) {
            $this->error('请选择同一操盘师!');
        }

        $stocklist = Db::name('stock_list')->where('status', 1)->select();
        $stock     = [];
        foreach ($stocklist as $k => $v) {
            $stock[$v['code']] = $v['title'];
        }

        if (count(explode(',', $ids[0])) == 1) {

            $orderid   = explode(',', $ids[0])[0];
            $usermoney = orderbalance($orderid) . '元';

        } else {
            $usermoney = "多订单无法获取余额";
        }

        return ZBuilder::make('form')->setPageTitle('批量跟买') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['tags', 'orderlist', '订单ID', '', implode(',', $ids)],
                         ['static', 'porductlist', '可使用余额', '该订单可投资余额', $usermoney],
                         ['select', 'porductlist', '股票', '', $stock],
                         ['datetime', 'buytime', '买入时间'],
                         ['number', 'buyprice', '买入价格'],
                         ['number', 'position', '买入仓位比例', '如果为空或0，则自动根据等级获取等级最大的仓位比例；动态计算股数比例(%)，如果余额不足，则自动计算的则直接取最大值的整数', 0, 10, 100, 1],

        ])
                                                                //            ->setExtraJs($js)
                                                                ->fetch();
    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $update_data = input();
            // 验证
            $data = $update_data;

            $confirm_time = empty($data['confirm_time']) ? '' : strtotime($data['confirm_time']);

            FundOrderGsModel::where('id', $id)->update(['confirm_time' => $confirm_time, 'commission' => $data['commission'], 'balance' => $data['balance']]);
            $this->success('编辑成功', cookie('__forward__'));
        }
        $info       = FundOrderGsModel::where('id', $id)->find();
        $info['create_time'] = date('Y-m-d H:i', $info['create_time']);
        $Traderlist = [];
        $data_list  = TraderModel::where('status', 1)->select();
        foreach ($data_list as $v => $k) {
            $Traderlist[$k['id']] = $k['name'];
        }

        return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['hidden', 'id'],
                         ['radio', 'status', '订单类型', '', ['未确认(申请中)', '已确认(优投中)', '已结束', '已撤销', '已驳回', '持仓中', '待结束订单（过度状态）'], '', '', 'disabled'],
                         ['text', 'order_sn', '订单号', '系统生成不可更改', '', '', 'disabled', 'disabled'],
                         ['text', 'money', '合约金额', '只能通过追加等操作修改', '', '', 'disabled', 'disabled'],
                         ['text', 'create_time', '申请时间', '只查看不影响使用', '', '', 'disabled', '', 'disabled'],

                         ['datetime', 'fundendtime', '合约结束工作号日', '合约到期时间，整你通过续期调整', '', '', 'disabled'],
                         ['text', 'fund_contract', '合约天数', '合约天数，整你通过续期调整', '', '', 'disabled'],
                         ['text', 'cycle', '优投周期', '优投周期，整你通过续期调整', '', '', 'disabled'],
                         ['text', 'settlement_time', '订单结束结算时间', '只能通过结束订单才显示', '', '', 'disabled'],
                         ['text', 'settlement_days', '交易结算天数', '一共交易了多少天，只能通过结束订单才显示', '', '', 'disabled'],
                         ['text', 'settlement_last_time', '上一次结算时间', '仅供显示用无法调整', '', '', 'disabled'],
                         ['text', 'add_contract', '累计续期天数', '累计续期天数，只能通过续期调整', '', '', 'disabled'],
                         ['text', 'add_money', '累计追加金额', '累计追加金额，只能通过追加调整', '', '', 'disabled'],
                         ['text', 'settlement_amount', '结算金额', '这个只能查看无法调整', '', '', 'disabled'],
                         ['radio', 'order_type', '订单类型', '', [1 => '普通订单', 2 => '每日优投', 3 => '一键优投'], '', '', 'disabled'],
                         ['radio', 'is_sell', '是否卖出', '', [0 => '未卖出', 1 => '已卖出'], '', '', 'disabled'],
                         ['datetime', 'confirm_time', '确认时间', '会影响下单，如果低于14点后当日订单就无法下单操盘了'],
                         ['number', 'commission', '导师手续费', '每次导师操盘的手续费'],

                         ['number', 'balance', '订单盈亏情况', '购买金额-减去盈亏情况等于可用的'],

        ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    public function editsell($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $update_data = input();
            // 验证
            $data = $update_data;
            if ($data['status'] == 1) {
                $map           = [];
                $map['id']     = $id;
                $map['status'] = 0;
                $list          = FundOrderGsModel::where($map)->select();
                FundOrderGsModel::autoUpdateSellStatus($list, 2);
                $this->success('编辑成功', cookie('__forward__'));
//                    $money = $order_data['money']*100;
//                    $uid = $order_data['uid'];
//                    Db::name('money')->where(['mid' => $uid])->setInc('account',$money);
            } else {
                $this->error('编辑失败');
            }
        }
        $info = FundOrderGsModel::where('id', $id)->find();
        return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['hidden', 'id'],
                         ['radio', 'status', '状态', '', ['未确认', '确认']],
        ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    /**
     * Desc : 删除日优投
     * User : you name
     * Date : 2024-06-22 15:57
     *
     * @param $record
     *
     * @throws \Exception
     */
    public function deletedayline($record = [])
    {

        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids = (array)$ids;

        $map[]  = ['id', 'in', $ids];
        $result = FundDaylineModel::where($map)->delete();
        if (false !== $result) {
            Cache::clear();
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * Desc : 删除优投
     * User : you name
     * Date : 2024-06-22 15:56
     *
     * @param $record
     *
     * @throws \Exception
     */
    public function delete($record = [])
    {
        $table_name = input('param.table');
        $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids        = (array)$ids;
        $field      = input('param.field', 'is_del');
        $map[]      = ['id', 'in', $ids];
        $result     = FundOrderGsModel::where($map)->delete();
        if (false !== $result) {
            Cache::clear();
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }
    /**
     * 设置用户状态：禁用、启用
     *
     * @param string $type 类型：enable/disable
     * @param array $record
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
//    public function setStatus($type = '', $record = []) {
//        $table_name = input('param.table');
//        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
//        $member_id = is_array($ids) ? '' : $ids;
//        $member_status = Fund::where('id', 'in', $ids)->column('status');
//        return parent::setStatus($type, ['member_' . $type, 'member', $member_id, UID, implode('、', $member_status) ]);
//    }
    public function quickEdit($record = [])
    {
        $id     = input('post.pk', '');
        $field  = input('post.name', '');
        $value  = input('post.value', '');
        $table  = input('post.table', '');
        $status = Fund::where('id', $id)->value($field);
        // $status = Db::name('member')->where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $status . ')，新值：(' . $value . ')';
        return parent::quickEdit(['member_edit', 'member', $id, UID, $details]);
    }

    /***
     *  优投续期
     **/
    public function renewal($ids = null)
    {
        new \DateTime();
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            if (empty($data['renewal_day'])) {
                $this->error('缺少天数');
            }

            $renewal_ids = explode(',', $data['renewal_ids']);
            foreach ($renewal_ids as $value) {
                $row = FundOrderGsModel::where('id', $value)->find();
                // 初始日期时间对象
                $dateTime = new \DateTime();
                $dateTime->setTimestamp($row->fundendtime);

// 添加天数，但不包括周末
                $totalDaysToAdd = $data['renewal_day'];
                $weekdaysToAdd  = 0; // 工作日天数计数器

                while ($totalDaysToAdd > 0) {
                    $dateTime->modify('+1 day');
                    $dayOfWeek = $dateTime->format('w');

                    // 如果不是周末（0代表周日，6代表周六），则计入工作日天数
                    if ($dayOfWeek != 0 && $dayOfWeek != 6) {
                        $weekdaysToAdd++;
                        $totalDaysToAdd--;
                    }
                }
                $row->fundendtime = $dateTime->getTimestamp();
//        $row->fundendtime   += $data['renewal_day'] * 60 * 60 * 24;
                $row->fund_contract += $data['renewal_day'];
                $row->add_contract  += $data['renewal_day'];
                $row->save();
            }
            $this->success('操作成功', cookie('__forward__'));
        }
        $ids = (array)$ids;

        return ZBuilder::make('form')->setPageTitle('优投续期') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['tags', 'renewal_ids', '订单ID', '', implode(',', $ids)],
                         ['number', 'renewal_day', '续期天数'],
        ])->fetch();
    }

    //跟卖追加
    public function addInvestment($ids = null)
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            if (empty($data['money'])) {
                $this->error('缺少金额');
            }
            $renewal_ids = explode(',', $data['fund_ids']);
            $money       = $data['money'];
            foreach ($renewal_ids as $value) {
                if (empty($money) || $money < 0) {
                    $this->error('金额不能小于0');
                }
                if ($money % 100 != 0) {
                    $this->error('金额必须是100的倍数');
                }
                $row = FundOrderGsModel::where('id', $value)->find();
                if (empty($row)) {
                    $this->error('未找到优投');
                }
                if ($row->status != 1 && $row->status != 6) {
                    $this->error('优投状态错误');
                }

                $user_balance = Money::getMoney($row->uid);
                $balance      = $user_balance['account'] / 100;//转为元
                if ($balance < $money) {
                    $this->error('余额不足', 0);
                }

                $row->add_money += $money;
                $row->money     += $money;
                $row->save();
                $setDec_Money_insert = Money::where('mid', $row->uid)->setDec('account', $money * 100);//减少余额
                //记录日志
                $user_balance    = Money::getMoney($row->uid);
                $account         = $user_balance['account'];
                $info            = '优投追加投资金额：' . $money . '元-[优投号：' . $row->order_sn . ']';
                $obj             = ['affect' => $money * 100, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => $row->order_sn];
                $Record_saveData = Record::saveData($row->uid, $money * 100, $account, 94, $info, '', '', $obj);

            }
            $this->success('操作成功', cookie('__forward__'));
        }
        $ids = (array)$ids;
        $get_info = FundOrderGsModel::where('id', $ids[0])->find();
        $infos                     = Db::name('money')->where("mid", $get_info['uid'])->find();
        $infos['account']          = $infos['account'] / 100;
        return ZBuilder::make('form')->setPageTitle('优投追加') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['tags', 'fund_ids', '订单ID', '', implode(',', $ids)],
                         ['static', 'account', '用户余额/元'],
                         ['number', 'money', '追加金额'],
        ])
            ->setFormData($infos) // 设置表单数据
            ->fetch();
    }

    /**
     * 优投-追加记录
     * @return void
     */
    public function investmentRecord()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();

        $order = $this->getOrder();
        empty($order) && $order = 'r.id desc';
        $where['r.type'] = '94';
        // 数据列表
        $data_list = Record::alias('r')
            ->field('r.*,m.name,m.mobile,m.role_name')
            ->join('member m', 'm.id=r.mid', 'left')
            ->where($map)
            ->where($where)
            ->order($order)
            ->paginate()
            ->each(function ($item, $key) {
                $item->account          = money_convert($item->account);
                $item->affect           = money_convert($item->affect);
                $item->affect_activity  = money_convert($item->affect_activity);
                $item->activity_account = money_convert($item->activity_account);
                $item->mobile = privacy_info_switch('mobile',$item->mobile);
            });
        // 分页数据
        $page = $data_list->render();

        foreach ($data_list as $key => $value) {
            $affect_activity             = ($value['affect_activity']) >= 1 ? "danger" : "";
            $activity_account            = ($value['activity_account']) >= 1 ? "danger" : "";
            $affect                      = ($value['affect']) >= 1 ? "danger" : "";
            $account                     = ($value['account']) >= 1 ? "danger" : "";
            $data_list[$key]['activity'] = "<p class='{$affect_activity}'>" . ($value['affect_activity']) . "</p><p class='{$activity_account}'>" . ($value['activity_account']) . "</p>";
            $data_list[$key]['account']  = "<p class='{$affect}'>" . ($value['affect']) . "</p><p class='{$account}'>" . ($value['account']) . "</p>";
        }
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
            ->setSearchArea([
                ['text', 'sn', '订单号', 'like'],
                ['text', 'name', '姓名', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['daterange', 'r.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
            ->addColumns([ // 批量添加数据列
                           ['id', 'ID'],
                           ['sn', '订单号'],
                           ['name', '姓名'],
                            ['role_name', '白名单', $this->user_role_name],
                           ['mobile', '手机号'],
                           ['type', '类型'],
                           ['account', '变动资金/账户余额'],
                           ['create_time', '时间', 'datetime'],
                           ['info', '信息'],
            ])
            ->hideCheckbox()
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
            ->setExtraHtml('账户余额显示，把活动金也计算入内，可能有错误的情况。', 'toolbar_bottom')
            ->setTableName('money')
            ->addOrder('id,create_time')
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }

//    修改日期
    public function dataUpdate($ids = null)
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
//            订单信息
            $get_one = FundOrderGsModel::where('id', $ids)->find();
            if (!$get_one) {
                $this->error('数据异常');
            }
            $create_time = date('Y-m-d', $get_one['create_time']);  //买入时间
            if (strtotime($data['fundendtime']) <= strtotime($create_time)) {
                $this->error('结束日期不能小于买入日期');
            }
            // 查询交易日
            $record = Db::name('stock_trade_date')
                ->where('date', '=', $data['fundendtime'])
                ->find();
            if (!$record) {
                $this->error('结束时间必须在交易日内');
            }
//            获取交易天数
            $count = Db::name('stock_trade_date')
                ->where('date', '>', date('Y-m-d', $get_one['create_time']))
                ->where('date', '<=', $data['fundendtime'])
                ->count();

            $data['fundendtime']   = strtotime($data['fundendtime'] . ' 23:59:58');
            $data['fund_contract'] = $count;
            FundOrderGsModel::where('id', $ids)->update($data);
            $this->success('操作成功', cookie('__forward__'));
        }
        $get_one = FundOrderGsModel::where('id', $ids)->find();
        return ZBuilder::make('form')->setPageTitle('修改日期') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['date', 'fundendtime', '结束日期'],
        ])
            ->setFormData($get_one) // 设置表单数据
            ->fetch();
    }

    /**
     * Desc :特殊会员批量跟买
     * Date : 2024-07-18 23:45
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function special()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data   = input();
            $result = $this->validate($data, 'Fund.memberdaybuy');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            if (!get_trading_time($data['buytime'])) {
                $this->error('买入日期不是交易日');
            }
            if ($data['position'] > 100|| $data['position'] < 0) {
                $this->error('仓位比例不能为0或者大于100');
            }
            $data['date'] = date('Y-m-d', strtotime($data['date']));
//            ----------------旧
//            $mobile_list  = str_replace(',',PHP_EOL, $data['mobile_list']);
//            $mobile_list  = explode(PHP_EOL, $mobile_list);
//            $mobile_list  = array_unique($mobile_list);
//            // 使用 array_filter 过滤空数组
//            $mobile_list = array_filter($mobile_list, function($value) {
//                $value=trim($value);
//                return !empty($value);
//            });
            //            -------------旧
            //                --------------------------测试用
            // 首先，将逗号替换为换行符
            $mobile_list_str = str_replace(',', PHP_EOL, $data['mobile_list']);
// 然后，使用换行符分割字符串
            $mobile_list_array = explode(PHP_EOL, $mobile_list_str);
// 对每个元素进行 trim 操作，移除可能的前后空格
            $mobile_list_array = array_map('trim', $mobile_list_array);
// 过滤掉空字符串
            $mobile_list = array_filter($mobile_list_array);
            //               -------------- 测试用
            printlog($mobile_list, "用户列表", 'buy');
            $stockname = Db::name('stock_list')->where('code', $data['porductlist'])->value('title');
            $userlist  = Db::name('member')->whereIn('mobile', $mobile_list)->select();
            if (!$userlist) {
                $this->error('会员不存在');
            }
            #插入操盘记录内容
            $datainsert['position']    = $data['position'];
            $datainsert['buy_time']    = $data['buytime'];
            $datainsert['buy_price']   = $data['buyprice'];
            $datainsert['date']        = date('Y-m-d', strtotime($data['buytime']));
            $datainsert['trader_id']   = $data['trader'];
            $datainsert['trader_name'] = TraderModel::get( $data['trader'])['name'];
            $stockinfo                 = StockListModel::where('code', $data['porductlist'])->find();
            $datainsert['stock_name']  = $stockinfo['title'];
            $datainsert['stock_code']  = $data['porductlist'];
            $datainsert['create_time'] = time();
            $datainsert['status']      = 2;
            $datainsert['type']        = 2;

            $datainsert['note']        = implode(',', $mobile_list);
            $impact_quantity=0;
            $insertGetId = TraderOrderModel::insertGetId($datainsert);
            foreach ($userlist as $value) {
                printlog($value['mobile'], "mobile", 'buy');
                # 先生成每日订单=>成功后返回订单列表
                $autodate = ['uid' => $value['id'], 'buytime' => $data['buytime']];
                if (!empty($data['trader'])) {
                    $autodate['trader'] = $data['trader'];
                }
                $orderlist = FundOrderGsModel::autobuildgsrecord($autodate);

                if (count($orderlist)<1) {
                    printlog('生成数据为空', "", 'buy');
                   continue;
                }




                foreach ($orderlist as $d) {
                    //获取该用户这个等级的最大仓位；
                    $FundDaylineinfo = TraderOrderModel::getcw($value['id']);
                    $balance = orderbalance($d['id']);
                    printlog($balance, "可用余额：", 'buy');
                    $position = !empty($data['position']) ? $data['position'] / 100 : $FundDaylineinfo['max_position_ratio'] / 100;
                    printlog($position, "持仓比例：", 'buy');

                    printlog($balance * $position, "仓位比例：跟仓比例：", 'buy');
                    $num = ($balance * $position) / $data['buyprice'];
                    printlog($num, "能买的数量：", 'buy');

                    $num = floor($num);
                    printlog($num, "向下操作后的数量：", 'buy');
                    if ($num < 1) {
                        # 删除订单 可买数量不足  需要把之前生成的删除
                        FundDaylineModel::where([
                            ['id', '=', $d['id']],
                        ])->delete();
                        printlog('余额不足  可买数量小于0', "", 'buy');
                        continue;
                    }


                    if (config('buy_cost')) {
                        $buyprice_sum = $data['buyprice'] * $num;
                        #券商佣金
                        $commission = config('commission') / 10000;
                        #过户费
                        $transfer_fee = config('transfer_fee') / 1000;
                        #规费
                        $stock_trading_fees = config('stock_trading_fees') / 1000;
                        #买入成本
                        $buy_cost = $buyprice_sum * $commission + $buyprice_sum * $transfer_fee + $buyprice_sum * $stock_trading_fees;
                    } else {
                        $buy_cost = 0;
                    }

                    #修改订单内容
                    FundDaylineModel::where([
                        ['id', '=', $d['id']],
                    ])->update(
                        ['date'                => date('Y-m-d', strtotime($data['buytime'])),
                         'traderorder_id_text' => '特殊会员手动投注',
                         'traderorder_id'      => $insertGetId,
                         'stockname'           => $stockname,
                         'num'                 => $num,
                         'buy_cost'            => $buy_cost,
                         'position'            => $position * 100,
                         'commission_ratio'    => $d['commission_ratio'],
                         'stockcode'           => $data['porductlist'],
                         'buy_time'            => $data['buytime'],
                         'buy_price'           => $data['buyprice'],
                         'status'              => 1
                        ]
                    );
                    FundOrderGsModel::where([
                        ['order_sn', '=', $d['order_sn']]
                    ])->update(['status'=>6]);

                }
                $impact_quantity++;
            }
            TraderOrderModel::where('id',$insertGetId)->update(['impact_quantity'=>$impact_quantity]);
            if($impact_quantity==0){
                $this->error('这些用户在选择的买入时间段内没有可以操作的订单,请检查买入股票时间是否在订单确认时间之后');
            }
            $this->success('操作成功', cookie('__forward__'));
        }

        $stocklist = Db::name('stock_list')->where('status', 1)->select();
        $stock     = [];
        foreach ($stocklist as $v) {
            $stock[$v['code']] = $v['title'];
        }
        $Trader_list = TraderModel::where('status', 1)->select();
        $Traderlist  = [];
        foreach ($Trader_list as $v) {
            $Traderlist[$v['id']] = $v['name'] . "(" . traderDocumentaryArray()[$v['type']] . ")";
        }

        return ZBuilder::make('form')->setPageTitle('特殊会员批量上股') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['text', 'aa', '使用前提条件', '', '需要在导师批量上股前，针对特殊用户进行操作', '', 'disabled style="background: none;border: none;text-align: center;color: red;font-size: 26px;"'],
                         ['textarea', 'mobile_list', '手机号', '多个手机号请换行,或者用英文逗号隔开'],
                         ['select', 'trader', '讲师名称', '可为空，为空的话则该用户下所有导师及每日合约都会匹配操作', $Traderlist],
                         ['select', 'porductlist', '股票', '', $stock],
                         ['date', 'buytime', '买入时间'],
                         ['number', 'buyprice', '买入价格'],
                         ['number', 'position', '买入仓位比例', '如果为空或0，则自动根据等级获取等级最大的仓位比例；动态计算股数比例(%)，如果余额不足，则自动计算的则直接取最大值的整数', 0, 10, 100, 1],

        ])
            ->fetch();
    }

    /**
     * Desc :特殊会员批量修改仓位
     * Date : 2024-07-19 19:34
     */
    public function position()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();

            if (empty($data['position'])|| $data['position'] > 100) {
                $this->error('仓位比例不能为0或者操作100');
            }
            $data['date'] = date('Y-m-d', strtotime($data['date']));
//            $mobile_list  = explode(PHP_EOL, $data['mobile_list']);
            //                --------------------------测试用
            // 首先，将逗号替换为换行符
            $mobile_list_str = str_replace(',', PHP_EOL, $data['mobile_list']);
// 然后，使用换行符分割字符串
            $mobile_list_array = explode(PHP_EOL, $mobile_list_str);
// 对每个元素进行 trim 操作，移除可能的前后空格
            $mobile_list_array = array_map('trim', $mobile_list_array);
// 过滤掉空字符串
            $mobile_list = array_filter($mobile_list_array);
            //               -------------- 测试用

            $userlist     = Db::name('member')->whereIn('mobile', $mobile_list)->select();
            $faillist     = $sucesslist = '';
            foreach ($userlist as $value) {
                # 先生成每日订单=>成功后返回订单列表
                $orderlist = FundDaylineModel::where([
                    ['trader_id', '=', $data['trader']],
                    ['uid', '=', $value['id']],
                    ['buy_price', '>', 0],
                    ['status', '=', 1],
                    ['date', '=', $data['date']
                    ],
                ])->select();
                if (!$orderlist) {
                    continue;
                }
                foreach ($orderlist as $d) {

                    $balance = orderbalance($d['id']) + $d['num'] * $d['buy_price'];
                    printlog($balance, "可用余额：", 'buy');
                    $position = $data['position'] / 100;
                    printlog($position, "持仓比例：", 'buy');
                    printlog($balance * $position, "仓位金额", 'buy');
                    $num = ($balance * $position) / $d['buy_price'];
                    printlog($num, "能买的数量：", 'buy');

                    $num = floor($num);
                    printlog($num, "向下操作后的数量：", 'buy');
                    if ($num <= 0) {

                        $faillist .= $value['mobile'];
                        continue;
                    }

                    if (config('buy_cost')) {

                        $buyprice_sum = $d['buy_price'] * $num;

                        #券商佣金
                        $commission = config('commission') / 10000;

                        #过户费
                        $transfer_fee = config('transfer_fee') / 1000;

                        #规费
                        $stock_trading_fees = config('stock_trading_fees') / 1000;

                        #买入成本
                        $buy_cost = $buyprice_sum * $commission + $buyprice_sum * $transfer_fee + $buyprice_sum * $stock_trading_fees;

                    } else {
                        $buy_cost = 0;
                    }

                    $update = [
                        'traderorder_id_text' => '手动调仓位',
                        'buy_cost'            => $buy_cost,
                        'position'            => $position * 100,
                        'num'                 => $num,
                    ];

                    #修改订单内容
                    $FundDaylineModel=    FundDaylineModel::where([
                        ['id', '=', $d['id']],
                    ])->update(
                        $update
                    );

                }

            }

            printlog($faillist, "'faillist'", 'buy');
            if (!empty($faillist)) {
                $this->success('部分操作失败：' . $faillist . '按照该仓位比例可购买数量为0', cookie('__forward__'));
            }
            $this->success('操作成功', cookie('__forward__'));
        }

        $Trader_list = TraderModel::where('status', 1)->select();
        $Traderlist  = [];
        foreach ($Trader_list as $v) {

            $Traderlist[$v['id']] = $v['name'] . "(" . traderDocumentaryArray()[$v['type']] . ")";
        }

        return ZBuilder::make('form')->setPageTitle('特殊会员批量调整仓位') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['text', 'aa', '使用前提条件', '', '只能设定未卖出的操盘订单的仓位', '', 'disabled style="background: none;border: none;text-align: center;color: red;font-size: 26px;"'],
                         ['textarea', 'mobile_list', '手机号', '多个手机号请换行,或者用英文逗号隔开'],
                         ['select', 'trader', '讲师名称', '', $Traderlist],
                         ['date', 'date', '日期', '默认为今天', date('Y-m-d')],

                         ['number', 'position', '买入仓位比例', '动态计算股数比例(%)，如果余额不足，则自动计算的则直接取最大值的整数', 0, 10, 100, 1],

        ])
            ->fetch();
    }

}