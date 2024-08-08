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
namespace app\money\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\service\UserService;
use app\money\model\Money as MoneyModel;
use app\money\model\Record as RecordModel;
use think\Db;
use think\facade\Cache;
use think\helper\Hash;

/**
 * 资金管理控制器
 * @package app\money\admin
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

        $map = (new UserService())->agentPartnerSearchMap($map, 'money.mid');
        $map = (new UserService())->getAgentMap($map, 'money.mid');
//        print_r(input('type'));die;
//        if (input('type') == 'month.html') {
        if (input('type') == 'month') {
            //近三个月起止时间
            $start_time = mktime(0, 0, 0, date('m') - 3, 1, date('y'));
            $end_time   = time();
            $map[]      = ['recharge_time', 'between time', [$start_time, $end_time]];
        }
//        if (input('type') == 'balance.html') {
        if (input('type') == 'balance') {
            //余额大于0的用户
            $map[] = ['account', 'gt', 0];
        }

        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = MoneyModel::view('money', true)
            ->view('member', 'mobile, name, id_card,email,role_name', 'member.id=money.mid', 'left')
            ->where($map)
            ->order($order)
            ->paginate()
            ->each(function ($item, $key) {
                $item->account          = money_convert($item->account);
                $item->freeze           = money_convert($item->freeze);
                $item->activity_account = money_convert($item->activity_account);
                $item->operate_account  = money_convert($item->operate_account);
                $item->bond_account     = money_convert($item->bond_account);
                $item->freeze_activity  = money_convert($item->freeze_activity);
                $item->mobile = privacy_info_switch('mobile',$item->mobile);
            });
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"] . '&';
        }

        $btn_risk  = [
            'title' => '资金操作',
            'icon'  => 'fa fa-fw fa-balance-scale',
            'href'  => url('/money/index/money', ['id' => '__mid__'])
        ];
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            //            'href' => url('/money/index/index_export?'.http_build_query([$map]),'','')
            'href'  => url($excel_url . http_build_query([$map]), '', '')
        ];
//        $btn_recharge = [
//          'title' => '近三个月充值',
//          'icon' => 'fa fa-fw fa-calendar',
//          'href' => url('/money/index?type=month')
//        ];
        $btn_recharge = [
            'title' => '近三个月充值',
            'icon'  => 'fa fa-fw fa-calendar',
            // 使用路由别名
            'href'  => url('/money/index/index') . '?type=month'
        ];
        $btn_all      = [
            'title' => '全部',
            'icon'  => 'fa fa-fw fa-users',
            'href'  => url('/money/index')
        ];
        $btn_balance  = [
            'title' => '余额大于0的用户',
            'icon'  => 'fa fa-fw fa-qq',
            //          'href' => url('/money/index?type=balance')
            'href'  => url('/money/index/index') . '?type=balance'
        ];
        $btn_money    = ['title' => '客户客损', 'icon' => 'fa fa-fw fa-th', 'href' => url('detail', ['uid' => '__mid__'], '')];
        $btn_edit       = ['title' => '资金批量操作', 'icon' => 'fa fa-fw fa-balance-scale', 'href' => url('moneyUpdate')];
        $btn_wallet       = ['title' => '用户钱包', 'icon' => 'si si-wallet', 'href' => url('wallet', ['uid' => '__mid__'])];
        $statisticsTable   = [];
        $statisticsTable[] = ['可用资金', '冻结金额', '活动资金', '冻结活动金额'];
        $statistics        = [];
        $temp              = MoneyModel::view('money', true)
            ->view('member', 'mobile, name, id_card,email', 'member.id=money.mid', 'left')
            ->where($map)
            ->field('sum(account) as sum_account, sum(freeze) as sum_freeze, sum(activity_account) as sum_activity_account, sum(freeze_activity) as sum_freeze_activity')
            ->find();
        $statistics[]      = format_money($temp['sum_account']);
        $statistics[]      = format_money($temp['sum_freeze']);
        $statistics[]      = format_money($temp['sum_activity_account']);
        $statistics[]      = format_money($temp['sum_freeze_activity']);
        $statisticsTable[] = $statistics;
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
//          ->setSearch(['mid' => '用户ID', 'member.name' => '姓名', 'member.email' => '邮箱', 'member.mobile' => '手机号', 'account' => '余额']) // 设置搜索框
//            ->setExtraHtmlFile('indexSearch', 'toolbar_top')
            ->setSearchArea([
                ['text:2', 'mid', '用户ID'],
                ['text:2', 'member.name', '姓名', 'like'],
                ['text:2', 'member.mobile', '手机号', 'like'],
                ['text:2', 'member.email', '邮箱', 'like'],
                ['text:2', 'account', '可用资金', 'like'],
                ['text:2', 'freeze', '冻结金额', 'like'],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID', ''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID', ''],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
            ->addRightButton('risk', $btn_risk) // 添加子账户风控设置按钮
//            ->addRightButton('custem', $btn_money) // 客户客损
                                                   //            ->addTimeFilter('create_time', [date("Y-m-d"), date("Y-m-d")], ['开始时间', '结束时间'])
            ->addColumns([ // 批量添加数据列
                           ['mid', '用户id'],
                           ['mobile', '手机号'],

                           ['email', '邮箱'],
                           ['name', '姓名'],
                           ['role_name', '白名单', $this->user_role_name],
                            ['account', '可用资金'],
                           ['freeze', '冻结金额'],
                           ['activity_account', '活动资金'],
                           ['freeze_activity', '冻结活动金额'],
//                           ['account', '可用资金', 'text.edit'],
//                           ['freeze', '冻结金额', 'text.edit'],
//                           ['activity_account', '活动资金', 'text.edit'],
//                           ['freeze_activity', '冻结活动金额', 'text.edit'],
                           //            ['operate_account', '操盘总额', 'text.edit'],
                           //            ['bond_account', '保证金总额', 'text.edit'],
                           //            ['bond_account', '保证金总额', 'text.edit'],
//                           ['status', '状态', 'switch'],
                           ['right_button', '操作', 'btn'],
            ])
            ->hideCheckbox()
            ->setTableName('money')
            ->addTopButton('custem', $btn_excel)
            ->addTopButton('custem', $btn_all)
            ->addTopButton('custem', $btn_recharge)
            ->addTopButton('custem', $btn_balance)
            ->addTopButton('custem', $btn_edit)
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
            ->addRightButton('custem', $btn_wallet)
            ->setExtraHtml($this->tableHtml($statisticsTable), 'toolbar_bottom')
            //->addTopButtons('enable,disable') // 批量添加顶部按钮
            // ->addRightButtons(['edit']) // 批量添加右侧按钮
            ->addOrder('id,account,freeze,status,operate_account,bond_account')
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }

    public function wallet($group = 'wallet')
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map        = $this->getMap();
        $uid        = input('uid', '');
        $map['mid'] = $uid;
        $order      = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $table = $group;

        $list_tab = [
            'wallet' => ['title' => '用户钱包地址', 'url' => url('wallet', ['uid' => $uid,'group' => 'wallet'])],
            'member_bank' => ['title' => '用户银行卡包', 'url' => url('wallet', ['uid' => $uid,'group' => 'member_bank'])],
        ];
        $btn_risk  = [
            'title' => '编辑钱包',
            'icon'  => 'fa fa-fw fa-balance-scale',
            'href'  => url('wallet_edit', ['id' => '__id__','group' => $group])
        ];
        switch ($group) {
            case 'wallet':
                $data_list = Db($table)->order('create_time desc')
                    ->where($map)
                    ->where('is_delete',0)
                    ->paginate()
                    ->each(function ($item,$key) {
                       $money_payment = Db::name('money_payment')->where('id',$item['payment_id'])->find();
                       $item['pay_name'] = $money_payment['name'];
                       return $item;
                    });
                // 分页数据
                $page = $data_list->render();

                return ZBuilder::make('table')
                    ->setTabNav($list_tab,  $group)
                    ->setPageTips('首页导航显示', 'danger')
                    ->hideCheckbox()
                    ->addColumns([ // 批量添加数据列
                        ['pay_name', '支付类型'],
                        ['alias', '别名'],
                        ['address', '钱包地址'],
                        ['create_time', '创建时间', 'datetime', '', 'Y-m-d H:i:s'],
                    ])
                    ->addColumn('right_button', '操作', 'btn')
//                    ->addRightButtons('edit') // 批量添加右侧按钮  ,last_login_time
                    ->addRightButton('risk', $btn_risk) // 添加子账户风控设置按钮
                    ->setRowList($data_list) // 设置表格数据
                    ->setPages($page) // 设置分页数据
                    ->fetch(); // 渲染模板
                break;
            case 'member_bank':
                $data_list = Db($table)->order('create_time desc')
                    ->where($map)
                    ->where('is_delete',0)
                    ->paginate()
                    ->each(function ($item,$key) {
                        $item['province'] = Db::name('area')->where('id',$item['province'])->value('name');
                        $item['city'] = Db::name('area')->where('id',$item['city'])->value('name');
                        return $item;
                    });
                // 分页数据
                $page = $data_list->render();
                return ZBuilder::make('table')
                    ->setTabNav($list_tab,  $group)
                    ->setPageTips('用户中心图标导航显示', 'danger')
                    ->hideCheckbox()
                    ->addColumns([ // 批量添加数据列
                        ['bank', '所属银行'],
                        ['branch', '支行'],
                        ['card', '卡号'],
                        ['province', '省'],
                        ['city', '市'],
                        ['create_time', '创建时间', 'datetime', '', 'Y-m-d H:i:s'],
                    ])
                    ->setColumnWidth('url', 200)
                    ->addColumn('right_button', '操作', 'btn')
                    ->addRightButton('risk', $btn_risk) // 添加子账户风控设置按钮
//                    ->addRightButtons(['edit']) // 批量添加右侧按钮  ,last_login_time
                    ->setRowList($data_list) // 设置表格数据
                    ->fetch(); // 渲染模板
                break;
        }
    }



    //    钱包修改
    public function wallet_edit($id = null, $group = null)
    {
        if ($id === null) $this->error('缺少参数');
        if ($group === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data             = input();
            switch ($group) {
                case 'wallet':
                    $edit = [
                        'address' => $data['address'],
                        'alias' => $data['alias'],
                    ];
                    Db::name('wallet')->where('id',$id)->update($edit);
                    // 记录行为 后续需要在添加行为
//                    action_log('member_edit', 'member', UID, UID,'修改钱包地址:'.$data['address']);
                    break;
                case 'member_bank':
                    $edit = [
                        'card' => $data['card'],
                        'bank' => $data['bank'],
                        'branch' => $data['branch'],
                    ];
                    Db::name('member_bank')->where('id',$id)->update($edit);
                    // 记录行为 后续需要在添加行为
//                    action_log('member_edit', 'member', UID, UID,'修改银行信息:'.$data['bank'].'/n/n'.$data['card']);
                    break;
            }
            $this->success('编辑成功', cookie('__forward__'));

        }
        switch ($group) {
            case 'wallet':
                $info = Db::name('wallet')
                    ->alias('w')
                    ->join('money_payment p ', 'w.payment_id = p.id')
                    ->field('w.id,p.name as pay_name,w.alias,w.address,p.agreement')
                    ->where(['w.id' => $id, 'w.is_delete' => 0])
                    ->find();
                return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['hidden', 'id'],
                    ['static', 'pay_name', '支付类型', ''],
                    ['text', 'alias', '别名', ''],
                    ['text', 'address', '钱包地址', ''],])
                    ->setFormData($info) // 设置表单数据
                    ->fetch();

                break;
            case 'member_bank':
                $info = Db::name('member_bank')
                    ->where(['id' => $id, 'is_delete' => 0])
                    ->field('id,card,bank,branch')
                    ->find();
                $bank = config('web_bank');

                return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['hidden', 'id'],
                    ['text', 'card', '卡号', ''],
                    ['select', 'bank', '所属银行', '',$bank],
                    ['text', 'branch', '分行', ''],
//                    ['text', 'bank', '所属银行', ''],
                    ])
//                    ->addSelect('bank', '所属银行', '请选择', $bank)
                    ->setFormData($info) // 设置表单数据
                    ->fetch();

                break;
        }
    }

    public function detail()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map        = $this->getMap();
        $uid        = input('uid', '');
        $map['uid'] = $uid;
        $order      = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = Db('stock_subaccount_money_log')->order('create_time desc')->where($map)->paginate();
        foreach ($data_list as $key => &$value) {
            $value['deposit_money'] = money_convert($value['deposit_money']);
            $value['operate_money'] = money_convert($value['operate_money']);
            $value['profit_loss']   = money_convert($value['profit_loss']);
            $data_list[$key]        = $value;
        }
        return ZBuilder::make('table')
            ->setSearch(['sub_account' => '子账户']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                           ['create_time', '日期', 'date'],
                           ['sub_account', '子账户'],
                           ['name', '会员名'],
                           ['mobile', '手机号'],
                           ['email', '邮箱'],
                           ['contract_num', '合约数'],
                           ['position', '持仓数'],
                           ['contract_num', '配资总金额'],
                           ['withdrawal', '提现总金额'],
                           ['recharge', '充值总金额'],
                           ['activity_money', '获得活动金总金额'],
                           ['deposit_money', '保证金总额'],
                           ['operate_money', '操盘总额'],
                           ['profit_loss', '总盈亏总额'],
            ])
            ->hideCheckbox()
            ->setTableName('stock_subaccount_money_log')
            ->addOrder('id,profit_loss')
            ->setColumnWidth('activity_money,user_info', 150)
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }


//    资金批量操作
    public function moneyUpdate()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data             = input();
            $moneyUpdate = MoneyModel::moneyUpdate($data);
            if($moneyUpdate['code'] == 0){
                $this->success($moneyUpdate['message'], cookie('__forward__'));
            }else{
                $this->error($moneyUpdate['message']);
            }
        }
        $money_type = ['1' => '余额', '2' => '活动金', '3' => '彩金'];
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('金额操作') // 设置页面标题
//            ->addSelect('money_type', '选择操作资金类型', '', $money_type,'1')
            ->addFormItems([ // 批量添加表单项
                ['select:7', 'money_type', '选择操作资金类型', '',$money_type,'1'],
                ['textarea:7', 'mobile_list', '扣款手机号', '多个手机号请换行'],
//                ['number:6', 'new_account', '需增加金额/元', '负数为扣除，正数为增加'],
                ['number:7', 'new_account', '余额操作金额/元（负数为扣款，正数为增加余额）', '负数为扣款，正数为增加余额'],
                ['number:7', 'new_activity_account', '活动金操作金额/元（负数为扣款，正数为增加金额）', '负数为扣款，正数为增加金额'],
                ['number:7', 'winnings', '彩金操作金额/元（只允许正数）', '只允许正数'],
                ['textarea', 'remark', '转账说明'],
            ])
            ->setTrigger('money_type', '3', 'winnings')
            ->setTrigger('money_type', '2', 'new_activity_account')
            ->setTrigger('money_type', '1', 'new_account')
            ->fetch();
    }

    public function money($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data             = input();
            $data['new_account'] = $data['new_account']?: 0;
            $data['new_activity_account'] = $data['new_activity_account'] ?: 0;
            $info             = Db::name('money')->where("mid", $id)->find();
            $account          = $data['new_account'] * 100;  //允许负数
            $activity_account = $data['new_activity_account'] * 100;   //允许负数

            $money1           = ($account + $info['account']);//允许负数
            $money2           = $activity_account + $info['activity_account'];//允许负数
            if ($money1 < 0 || $money2 < 0) {
                $this->error('金额不能为负数');
            }

            $type = 18; //默认系统转入

            if($account <0 || $activity_account <0){
                $type = 19; //管理员操作
            }

            if($data['new_activity_account'] > 0){
                $type = 111;
            }
            if(isset($data['winnings'])){
                if($data['winnings'] < 0){
                    $this->error('彩金不能为负数');
                }
                if ($data['winnings'] > 0){
                    $money1 += $data['winnings'] * 100;
                    $account += $data['winnings'] * 100;
                    $type = 110;
                }
            }
            $infos  = Db::name('money')->where("mid", $data['mid'])->update(['account' => $money1 ?? 0, 'activity_account' => $money2 ?? 0]);
            $remark = $data['remark'];
            if ($infos) {
                if ($info['account'] != $money1) {
                    $straccount = "余额的变化金额：" . ($info['account'] / 100) . "=>" . ($money1 / 100) . "元";
                } else {
                    $straccount = "余额未变化";
                }
                if ($info['activity_account'] != $money2) {
                    $activity_account_text = "活动资金的变化金额：" . ($info['activity_account'] / 100) . "=>" . ($money2 / 100) . "元";
                } else {
                    $activity_account_text = "活动金余额未变化";
                }

                $obj     = ['affect' => $account, 'account' => $money1, 'affect_activity' => $activity_account, 'activity_account' => $money2, 'sn' => ''];
                $details = "(管理员：" . UID . " ，向uid为：" . $id . " 的资金账户做操作，{$straccount}，{$activity_account_text}, 备注：" . $remark . ')';
                RecordModel::saveData($data['mid'], '', '', $type, $details, '', 0, $obj);
                action_log('transfer_add', 'money_transfer', $data['mid'], UID, $details);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info                     = Db::name('money')->where("mid", $id)->find();
        $info['account']          = $info['account'] / 100;
        $info['activity_account'] = $info['activity_account'] / 100;
        $money_type = ['1' => '余额', '2' => '活动金', '3' => '彩金'];
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('增加金额') // 设置页面标题
            ->addSelect('money_type', '选择操作资金类型', '', $money_type,'1')
            ->addFormItems([ // 批量添加表单项
                             ['hidden', 'mid'],
                             ['static', 'account', '用户余额/元'],
                             ['static', 'activity_account', '活动余额/元'],
                             ['number', 'new_account', '余额操作金额/元（负数为扣款，正数为增加余额）', '负数为扣款，正数为增加余额'],
                             ['number', 'new_activity_account', '活动金操作金额/元（负数为扣款，正数为增加金额）', '负数为扣款，正数为增加金额'],
                             ['number', 'winnings', '彩金操作金额/元（只允许正数）', '只允许正数'],
                             ['textarea', 'remark', '说明'],
            ])
            ->setTrigger('money_type', '3', 'winnings')
            ->setTrigger('money_type', '2', 'new_activity_account')
            ->setTrigger('money_type', '1', 'new_account')
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }


//    旧资金操作
    public function money_old($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data             = input();
//            $data['new_account'] = $data['new_account'] < 0 ? 0 : $data['new_account'];
//            $data['new_activity_account'] = $data['new_activity_account'] < 0 ? 0 : $data['new_activity_account'];
            $info             = Db::name('money')->where("mid", $id)->find();
            $account          = $data['new_account'] * 100;  //允许负数
            $activity_account = $data['new_activity_account'] * 100;   //允许负数

            $money1           = ($account + $info['account']);//允许负数
//            $money1           = $money1 < 0 ? 0 : $money1;
            $money2           = $activity_account + $info['activity_account'];//允许负数
//            $money2           = $money2 < 0 ? 0 : $money2;

            if ($money1 < 0 || $money2 < 0) {
                $this->error('金额不能为负数');
            }

            $type = 18; //系统转入

            if($account <0 || $activity_account <0){
                $type = 19; //管理员操作
            }

            if($data['new_activity_account'] > 0){
                $type = 111;
            }
            if(isset($data['winnings'])){
                if($data['winnings'] < 0){
                    $this->error('彩金不能为负数');
                }
                if ($data['winnings'] > 0){
                    $money1 += $data['winnings'] * 100;
                    $account += $data['winnings'] * 100;
                }
                $type = 110;
            }
            $infos  = Db::name('money')->where("mid", $data['mid'])->update(['account' => $money1 ?? 0, 'activity_account' => $money2 ?? 0]);
            $remark = $data['remark'];
            if ($infos) {
                if ($info['account'] != $money1) {
                    $straccount = "余额的变化金额：" . ($info['account'] / 100) . "=>" . ($money1 / 100) . "元";
                } else {
                    $straccount = "余额未变化";
                }
                if ($info['activity_account'] != $money2) {
                    $activity_account_text = "活动资金的变化金额：" . ($info['activity_account'] / 100) . "=>" . ($money2 / 100) . "元";
                } else {
                    $activity_account_text = "活动金余额未变化";
                }

                $obj     = ['affect' => $account, 'account' => $money1, 'affect_activity' => $activity_account, 'activity_account' => $money2, 'sn' => ''];
                $details = "(管理员：" . UID . " ，向uid为：" . $id . " 的资金账户做操作，{$straccount}，{$activity_account_text}, 备注：" . $remark . ')';
                RecordModel::saveData($data['mid'], '', '', $type, $details, '', 0, $obj);
                action_log('transfer_add', 'money_transfer', $data['mid'], UID, $details);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info                     = Db::name('money')->where("mid", $id)->find();
        $info['account']          = $info['account'] / 100;
        $info['activity_account'] = $info['activity_account'] / 100;
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('增加金额') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'mid'],
                ['static', 'account', '用户余额/元'],
                ['number:4', 'new_account', '余额操作金额/元（负数为扣款，正数为增加余额）', '负数为扣款，正数为增加余额'],
//                             ['number:4', 'new_account', '余额需增加金额/元', '负数自动为0 不做操作'],
                ['static', 'activity_account', '活动余额/元'],
                ['number:4', 'new_activity_account', '活动金操作金额/元（负数为扣款，正数为增加金额）', '负数为扣款，正数为增加金额'],
//                             ['number:4', 'new_activity_account', '活动金需增加金额/元', '负数自动为0 不做操作'],
                ['number:4', 'winnings', '彩金操作金额/元（只允许正数）', '只允许正数'],
                ['textarea', 'remark', '说明'],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    public function index_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        if (input('type') == 'month') {
            //近三个月起止时间
            $start_time = mktime(0, 0, 0, date('m') - 3, 1, date('y'));
            $end_time   = time();
            $map[]      = ['recharge_time', 'between time', [$start_time, $end_time]];
        }
//        if (input('type') == 'balance.html') {
        if (input('type') == 'balance') {
            //余额大于0的用户
            $map[] = ['account', 'gt', 0];
        }
        // 数据列表
        $xlsData   = MoneyModel::getAll($map, $order);
        foreach ($xlsData as &$v){
            $v['mobile'] = privacy_info_switch('mobile',$v['mobile']);
        }
        $title     = "资金列表";
        $arrHeader = array('ID', '用户id', '手机号', '邮箱', '姓名', '可用资金', '冻结金额', '活动资金', '冻结活动金额', '操盘总额', '保证金总额');
        $fields    = array('id', 'mid', 'mobile', 'email', 'name', 'account', 'freeze', 'activity_account', 'freeze_activity', 'operate_account', 'bond_account');
        export($arrHeader, $fields, $xlsData, $title);

//        ['id', 'ID'],
//            ['mobile', '手机号'],
//            ['mid', '用户id'],
//            ['email', '邮箱'],
//            ['name', '姓名'],
//            ['account', '可用资金', 'text.edit'],
//            ['freeze', '冻结金额', 'text.edit'],
//            ['activity_account', '活动资金', 'text.edit'],
//            ['freeze_activity', '冻结活动金额', 'text.edit'],
//            ['operate_account', '操盘总额', 'text.edit'],
//            ['bond_account', '保证金总额', 'text.edit'],
//            ['right_button', '操作', 'btn'],
    }

    public function quickEdit($record = [])
    {
        $id    = input('post.pk', '');
        $field = input('post.name', '');
        $value = input('post.value', '');
        $table = input('post.table', '');
        $type  = input('post.type', '');
        if (in_array($field, array('account', 'freeze', 'operate_account', 'bond_account', 'activity_account', 'freeze_activity'))) {
            $value    *= 100;
            $str_info = ",以元为单位";
        }

        $mid       = MoneyModel::where('id', $id)->value('mid');
        $old_value = MoneyModel::where('id', $id)->value($field);
        $value1    = $value - $old_value;
        $mobile    = Db('member')->where('id', $mid)->value('mobile');
        $details   = $mobile . ' 字段(' . $field . ')，原值：(' . $old_value . ')新值：(' . $value . ')';

        switch ($type) {
            // 日期时间需要转为时间戳
            case 'combodate':
                $value = strtotime($value);
                break;
            // 开关
            case 'switch':
                $value = $value == 'true' ? 1 : 0;
                break;
            // 开关
            case 'password':
                $value = Hash::make((string)$value);
                break;
        }
        $pk               = Db('money')->getPk();
        $result           = Db('money')->where($pk, $id)->setField($field, $value);
        $result_data      = Db('money')->where('id', $id)->find();
        $affect           = $affect_activity = 0;
        $account          = $result_data['account'];
        $activity_account = $result_data['activity_account'];
        $Variety          = $value - $old_value;
        if (false !== $result) {
            switch ($field) {
                case 'activity_account':
                    $str              = "活动资金-" . $field;
                    $activity_account = $value;
                    break;
                case 'account':
                    $str    = "余额" . $field;
                    $affect = $account = $value;
                    break;
                case 'freeze_activity':
                    $str             = "冻结活动资金-" . $field;
                    $affect_activity = $value;
                    break;
                case 'freeze':
                    $str    = "冻结金额-" . $field;
                    $affect = $value;
                    break;
                case 'operate_account':
                    $str = "操盘总资金总额-" . $field;
                    break;
                case 'bond_account':
                    $str = "保证金总额-" . $field;
                    break;
                default:
                    // code...
                    break;
            }
            $obj       = ['affect' => $affect, 'account' => $account, 'affect_activity' => $affect_activity, 'activity_account' => $activity_account, 'sn' => ''];
            $old_value = $old_value / 100;
            $value     = $value / 100;
            $info      = "(管理员ID:" . UID . ",手动调整({$str})，原数值为{$old_value},调整后为{$value}{$str_info})";
            RecordModel::saveData($result_data['mid'], $value1, $account, 19, $info, '', 0, $obj);
            action_log('transfer_add', 'money_transfer', $id, UID, $info);
            Cache::clear();
            // 记录行为日志
            call_user_func_array('action_log', ['money_edit', 'money', $id, UID, $details]);
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }


}