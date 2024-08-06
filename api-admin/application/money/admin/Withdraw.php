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
use app\member\model\Member as MemberModel;
use app\money\model\Payment as PaymentModel;
use app\money\model\Recharge;
use app\money\model\Withdraw as WithdrawModel;
use app\statistics\model\DataReport as DataReportModel;
use app\statistics\model\RechargeWithdrawal as RechargeWithdrawalModel;
use app\statistics\model\UserStatistics as UserStatisticsModel;
use think\Db;
use app\common\service\UserService;

/**
 * 充值管理控制器
 * @package app\money\admin
 */
class Withdraw extends Admin
{
    /**
     * 充值列表
     * @return mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->agentPartnerSearchMap($map,'money_withdraw.mid');
        $map = (new UserService())->getAgentMap($map,'money_withdraw.mid');
        $noCheck = input('noCheck');
        if (isset($noCheck)) {
            $map[] = ['money_withdraw.status','=',$noCheck];
        }
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        if (empty($map['money_withdraw.create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['money_withdraw.create_time'][1][0]));
        }
        if (empty($map['money_withdraw.create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['money_withdraw.create_time'][1][1]));
        }
        $online_sithdrawal = PaymentModel::$online_sithdrawal;
        // 数据列表
        $data_list = WithdrawModel::view('money_withdraw', true)
        ->view('member', 'mobile, name, id_card,email,partner_parent_id,role_name,remarks', 'member.id=money_withdraw.mid', 'left')
        ->view('wallet', 'payment_id', 'wallet.id=money_withdraw.wallet_id', 'left')
        ->where($map)
        ->where('money_withdraw.status','=',0)
        ->order($order)
        ->paginate()
        ->each(function ($item, $key) {
            $item->money = money_convert($item->money);
            $item->fee = money_convert($item->fee);
            $item->real_money = money_convert($item->real_money);
            $item->mobile = privacy_info_switch('mobile',$item->mobile);
            $item->name = "<p>" . $item->name . "</p><p>" .$item->mobile. "</p>";;
//            $item->money = "<p>" . $item->money . "</p><p>" .$item->fee. "</p>";;
//            $item->total = $item->fee + $item->money;
            $inviteUser = MemberModel::where('id', $item['partner_parent_id'])->find();
            $item->partner_parent = '';
            $inviteUser['mobile'] = privacy_info_switch('mobile',$inviteUser['mobile']);
            if (!empty($inviteUser)) {
                $item->partner_parent = "<p>(ID:{$item['partner_parent_id']})".$inviteUser['mobile']."</p><p>" .$inviteUser['name'].'</p>';
            }
            $item->time = "<p>" . date('Y-m-d H:i',$item->create_time) . "</p>";

            $bank = explode('|',$item->bank);
            $item->withdraw_type = $bank[0];
            $item->address = $bank[1];
            if(count($bank) >3){
                $item->address = "<p>" . $bank[0] . "</p><p>" .$bank[1]. "</p>";;
            }
            if($item->wallet_id >0 && $item->wallet_type == 1){
                $item->payment_type = Db::name('money_payment')->where('id',$item->payment_id)->value('type');
            }else{
                $item->payment_type = 'not_payment_type';
            }
//            如果有汇率
            if($item->exchange_rate > 0){
                $item->exchange_rate_money = "<p>" . $item->exchange_rate . "</p><p>" .$item->exchange_rate_number. "</p>";;
            }


        });
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
              5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
              5) . "_export?" . $_SERVER["QUERY_STRING"].'&';
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
//          'href' => url($excel_url, '', '')
            'href' => url($excel_url.http_build_query([$map]), '', '')
        ];
        $btn_commission = [
            'title' => '提现配置',
            'icon' => 'fa fa-fw fa-gear',
//          'href' => url($excel_url, '', '')
            'href'  => url('commission'),
        ];
        $btn_getWithdraw = ['title' => '提现记录', 'icon' => 'fa fa-fw fa-paste', 'href' => url('getWithdraw', ['mid' => '__mid__'])];
        $btn_getRecharge = ['title' => '充值记录', 'icon' => 'fa fa-fw fa-dollar', 'href' => url('getRecharge', ['mid' => '__mid__'])];
        $btn_detail = ['title' => '审核详情', 'icon' => 'fa fa-fw fa-dollar', 'href' => url('detail', ['id' => '__id__'])];
        $btn_user = [
            'title' => '提现转账',
            'icon' => 'fa fa-times-circle-o',
            'class' => 'btn btn-xs btn-default ajax-get confirm',
            'data-title' => '确认提交转账信息吗？',
            'href' => url('edit_online',['id' => '__id__'])
        ];
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
//            ->setExtraHtmlFile('search', 'toolbar_top')
//          ->setSearch(['order_no' => '订单号', 'member.name' => '姓名', 'member.mobile' => '手机号', 'member.email' => '邮箱']) // 设置搜索框
            ->setSearchArea([
                ['text', 'order_no', '订单号', 'like'],
                ['text', 'member.name', '姓名', 'like'],
                ['text', 'member.mobile', '手机号', 'like'],
//                ['select', 'money_withdraw.status', '状态', '', '', [0 => '待处理',1 => '成功', 2 => '失败']],
//                ['text', 'member.email', '邮箱', 'like'],
                ['daterange', 'money_withdraw.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
                ['daterange', 'money_withdraw.create_time', '申请时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
          ->addColumns([ // 批量添加数据列
            ['order_no', '订单号'],
            ['name', '姓名/手机号'],
            ['remarks', '备注'],
              ['role_name', '白名单', $this->user_role_name],
              ['partner_parent', '邀请人账号/邀请人姓名'],
            ['money', '提现金额'],
            ['real_money', '实际到账'],
            ['fee', '手续费'],
            ['exchange_rate', '汇率'],
            ['exchange_rate_number', '到账数量'],
            ['withdraw_type', '提现方式'],
//            ['withdraw_type', '提现渠道'],
            ['address', '账号信息'],

//            ['bank', '账户信息'],
            ['status', '状态'],
            ['time', '申请时间'],
            ['right_button', '操作', 'btn']
          ])
          ->hideCheckbox()
          ->addTopButton('custem', $btn_excel)
          ->addTopButton('custem', $btn_commission) // 批量添加顶部按钮
//          ->addRightButton('edit_online', ['title' => '提现转账'], ['area' => ['800px', '90%'], 'title' => '提现转账']) // 批量添加右侧按钮

          ->addRightButton('edit', ['title' => '人工审核'], ['area' => ['800px', '90%'], 'title' => '提现审核']) // 批量添加右侧按钮
            ->addRightButton('btn_user', $btn_user)
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
//          ->addRightButton('custom1', $btn_getWithdraw)
//          ->addRightButton('custom2', $btn_getRecharge)
//          ->replaceRightButton(['status' => '成功'], '<button class="btn btn-success btn-xs" type="button" disabled>审核成功</button>')
//            ->replaceRightButton(['status' => '待处理'], '', ['custom1','custom2','btn_detail'])
            ->replaceRightButton(['payment_type' => ['not in', $online_sithdrawal]], '', 'btn_user')
//            ->replaceRightButton(['status' => '失败'], '', 'edit')
//          ->addTimeFilter('money_withdraw.create_time', [$beginday, $endday]) // 添加时间段筛选
          ->addOrder('id,create_time,status')
          ->setRowList($data_list)
            ->setColumnWidth('withdraw_type', 80)
            ->setColumnWidth('address', 150)
            ->setColumnWidth('time', 150)
//            ->setColumnWidth('order_no', 50)
//            ->setColumnWidth('right_button', 60)
//            ->setColumnWidth('status,money', 20)
//            ->setColumnWidth('create_time,name', 50)
          ->fetch(); // 渲染模板
    }

    /**
     * 充值列表(获取前三个月)
     * @return mixed
     * @author
     */
    public function getWithdraw($mid = '')
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
//            dump($map);exit;
        $map['mid'] = $mid;
        $map['money_withdraw.status'] = 1;
        //近三个月起止时间
        $start_time = mktime(0, 0, 0, date('m') - 3, 1, date('y'));
        $end_time = time();
        $where = [];
        $where['mid'] = ['eq', $mid];
        $where['status'] = ['eq', 1];
        $sum_money = WithdrawModel::where($where)
//                ->where('create_time','>=',$start_time)
//                ->where('create_time','<=',$end_time)
          ->sum('money');
        $sum_money = $sum_money / 100;
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = WithdrawModel::getAll2($map, $order, $start_time, $end_time);
        // 分页数据
        $page = $data_list->render();
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
        return ZBuilder::make('table')
          ->setSearch(['order_no' => '订单号', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
          ->setPageTips('提现金额总计：' . $sum_money, 'danger', 'bottom')
          ->addColumns([ // 批量添加数据列
            ['order_no', '订单号'],
            ['mobile', '手机号'],
            ['name', '姓名'],
            ['money', '金额'],
            ['bank', '银行信息'],
            ['status', '状态'],
            ['create_time', '申请时间', 'datetime'],
//                    ['right_button', '操作', 'btn']
          ])
          ->hideCheckbox()
//                ->addTopButton('custem',$btn_excel)
//                ->addRightButton('edit',[],['area' => ['800px', '90%'], 'title' => '提现审核']) // 批量添加右侧按钮
//                ->replaceRightButton(['status' => '成功'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
          ->addOrder('id,create_time,status,money,order_no')
          ->setRowList($data_list)
          ->fetch(); // 渲染模板
    }

    /**
     * 充值列表
     * @return mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function getRecharge($mid = '')
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map['mid'] =$mid;
        $map['money_recharge.status'] = 1;
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        //近三个月起止时间
        $start_time = mktime(0, 0, 0, date('m') - 3, 1, date('y'));
        $end_time = time();
        $where = [];
        $where['mid'] = ['eq', $mid];
        $where['status'] = ['eq', 1];
        $sum_money = Recharge::where($where)
//                ->where('create_time','>=',$start_time)
//                ->where('create_time','<=',$end_time)
          ->sum('money');
        // 数据列表
        $data_list = $this->getRechargeList($map, $order, $start_time, $end_time);
        // 分页数据
        $page = $data_list->render();
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
        $totalsum = 0;
        foreach ($data_list as $k => $v) {
            $totalsum = $totalsum + $v['money'];
        }
        $html = '';
        $html = <<<EOF
            <p>当前页面金额为：{$totalsum}元，\t可通过筛选条件快速快速筛选出对应数据。状态0为未处理，1为成功 2为失败</p>
EOF;
        return ZBuilder::make('table')
//            ->setSearch(['mid'=>'用户ID', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
          ->setPageTips('充值金额总计：' . $sum_money, 'danger', 'bottom')
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['order_no', '订单号'],
            ['mobile', '手机号'],
            ['name', '姓名'],
            ['money', '金额'],
            ['status', '状态'],
            ['type', '充值方式', ['transfer' => "线下转账", "1" => "支付宝线上转账", "2" => "微信线上转账"]],
            ['create_time', '充值时间', 'datetime'],
//                ['right_button', '操作', 'btn']
          ])
          ->setExtraHtml($html, 'table_bottom')
          ->addFilter('money_recharge.status') // 添加筛选
//            ->addTimeFilter('money_recharge.create_time', [date("Y-m-d"), date("Y-m-d")], ['开始时间', '结束时间'])
          ->hideCheckbox()
//            ->addTopButton('custem',$btn_excel)
          ->addRightButton('edit', [], ['area' => ['800px', '90%'], 'title' => '充值审核']) // 批量添加右侧按钮
          ->addOrder('id,create_time,status,money,order_no')
          ->setRowList($data_list)
          ->fetch(); // 渲染模板
    }

    public function getRechargeList($map = [], $order = '', $start_time = '', $end_time = '')
    {
        $data_list = Recharge::view('money_recharge', true)
          ->view('member', 'mobile, name, id_card', 'member.id=money_recharge.mid', 'left')
          ->where($map)
//            ->where('money_recharge.create_time','>=',$start_time)
//            ->where('money_recharge.create_time','<=',$end_time)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $item->money = money_convert($item->money);
              $item->fee = money_convert($item->fee);
          });
        return $data_list;
    }

    public function index_export()
    {
        $map = $this->getMap();
        $noCheck = input('noCheck');
        if (isset($noCheck)) {
            $map['money_withdraw.status'] = $noCheck;
        }
        $map[] = ['money_withdraw.status','=',0];
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData = WithdrawModel::getAll($map, $order);
        //$type_arr=['transfer'=>"线下转账","alipay"=>"支付宝"];
        foreach ($xlsData as $k => $v) {
            // $v['type']=$type_arr[$v['type']];
            // $v['status']=$status_arr[$v['status']];
            $xlsData[$k]['create_times'] = date('Y-m-d H:i:s', $v["create_time"]);
            $xlsData[$k]['mobile'] = privacy_info_switch('mobile',$xlsData[$k]['mobile']);
        }
        $title = "提现待审核记录";
        $arrHeader = array('订单号', '手机号', '姓名', '金额', '银行信息', '状态', '申请时间');
        $fields = array('order_no', 'mobile', 'name', 'money', 'bank', 'status', 'create_times');
        export($arrHeader, $fields, $xlsData, $title);
    }


    public function edit_online($id = null)
    {
        if ($id === null) $this->error('缺少参数', null, '_close_pop');
        $result_up = WithdrawModel::saveAuditOnline($id);
        if (true !== $result_up) {
            $this->error($result_up);
        } else {
            //提现成功
            $withdraw_data = WithdrawModel::where('id', $id)->field('*')->find();
            //添加到充值提现统计
            $post_data['withdrawal'] = $withdraw_data['money'] / 100;
            $post_data['create_time'] = $withdraw_data['create_time'];
            $post_data['withdrawal_user'] = 1;
            RechargeWithdrawalModel::appendRechargeWithdrawal($post_data);
            //用户统计
            UserStatisticsModel::appendUserStatistics(['recharge_withdrawal' => 1, 'create_time' => $withdraw_data['create_time']]);
            //数据报表
            $data_report['create_time'] = $withdraw_data['create_time'];
            $data_report['withdraw'] = $withdraw_data['money'] / 100;
            DataReportModel::appendDataReport($data_report);
            MemberModel::where('id', $withdraw_data['mid'])->setInc('withdrawal', $withdraw_data['money']);
            $this->success('审核处理成功', null, '_parent_reload');
        }
    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数', null, '_close_pop');
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            $result = $this->validate($data, 'Withdraw.update');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            $result_up = WithdrawModel::saveAudit();
            if (true !== $result_up) {
                $this->error($result_up);
            } else {
                if ($data['status'] == 1) {
                    //提现成功
                    $withdraw_data = WithdrawModel::where('id', $id)->field('*')->find();
                    //添加到充值提现统计
                    $post_data['withdrawal'] = $withdraw_data['money'] / 100;
                    $post_data['create_time'] = $withdraw_data['create_time'];
                    $post_data['withdrawal_user'] = 1;
                    RechargeWithdrawalModel::appendRechargeWithdrawal($post_data);
                    //用户统计
                    UserStatisticsModel::appendUserStatistics(['recharge_withdrawal' => 1, 'create_time' => $withdraw_data['create_time']]);
                    //数据报表
                    $data_report['create_time'] = $withdraw_data['create_time'];
                    $data_report['withdraw'] = $withdraw_data['money'] / 100;
                    DataReportModel::appendDataReport($data_report);
                    MemberModel::where('id', $withdraw_data['mid'])->setInc('withdrawal', $withdraw_data['money']);
                }
                $this->success('审核处理成功', null, '_parent_reload');
            }
        }
        $info = WithdrawModel::where('id', $id)->find();
        // dump($info);exit;
        $ishides = '';
        $info->money = money_convert($info->money);
        $info->fee = money_convert($info->fee);
        $info->real_money = money_convert($info->real_money);
        if ($info->getData('status') == 0) {
            $status_arr = [1 => '成功', 2 => '失败'];
        } elseif ($info->getData('status') == 1) {
            $status_arr = [3 => '退回'];
        } else {
            $status_arr = [2 => '失败'];
        }
        $staticContent = '';
        $staticLabel = '';
        if (!empty($info->payment_code)) {
            // 如果认证类型是0，显示身份证
            $staticContent = '<img src="'.get_img_url($info['payment_code']).'" alt="收款码" style="max-width: 100px; height: auto; margin-right: 10px;">';
            $staticLabel = '收款码'; // 这里你可以设置为两张照片的共同标签，或者为每个图片设置单独的标签
        }
        if ($info->getData('status') == 2) {
            return ZBuilder::make('form')
              ->setPageTitle('提现审核')// 设置页面标题
              ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static', 'order_no', '订单号'],
                ['static', 'money', '提现金额',],
                ['static', 'real_money', '实际到账',],
                ['static', 'fee', '手续费',],
                ['static', 'bank', '提现银行信息'],
                    ($staticContent ? ['static', 'auth_document', $staticLabel, $staticContent] : []),
//                ['static', 'auth_document', $staticLabel, $staticContent], // 根据 auth_type 显示身份证或护照
                ['static', 'status', '状态', '', $status_arr],
                ['textarea', 'remark', '备注', '']
              ])
              ->hideBtn('submit')
              ->setFormData($info)// 设置表单数据
              ->fetch();
        } else {
            return ZBuilder::make('form')
              ->setPageTitle('提现审核')// 设置页面标题
              ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static', 'order_no', '订单号'],
                ['static', 'money', '提现金额',],
                ['static', 'real_money', '实际到账',],
                ['static', 'fee', '手续费',],
                ['static', 'bank', '提现银行信息'],
                ['static', 'auth_document', $staticLabel, $staticContent], // 根据 auth_type 显示身份证或护照
                ['radio', 'status', '状态', '', $status_arr],
                ['textarea', 'remark', '备注', '']
              ])
              ->setFormData($info)// 设置表单数据
              ->fetch();
        }
    }


//    详情
    public function detail($id = null)
    {
        if ($id === null) $this->error('缺少参数', null, '_close_pop');

        $info = WithdrawModel::where('id', $id)->find();
        // dump($info);exit;
        $ishides = '';
        $info->money = money_convert($info->money);
        $info->fee = money_convert($info->fee);
        $info->real_money = money_convert($info->real_money);
        if ($info->getData('status') == 0) {
            $status_arr = [1 => '成功', 2 => '失败'];
        } elseif ($info->getData('status') == 1) {
            $status_arr = [3 => '退回'];
        } else {
            $status_arr = [2 => '失败'];
        }
        $staticContent = '';
        $staticLabel = '';
        if (!empty($info->payment_code)) {
            // 如果认证类型是0，显示身份证
            $staticContent = '<img src="'.get_img_url($info['payment_code']).'" alt="收款码" style="max-width: 100px; height: auto; margin-right: 10px;">';
            $staticLabel = '收款码'; // 这里你可以设置为两张照片的共同标签，或者为每个图片设置单独的标签
        }
            return ZBuilder::make('form')
                ->setPageTitle('提现审核')// 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['hidden', 'id'],
                    ['static', 'order_no', '订单号'],
                    ['static', 'money', '提现金额',],
                    ['static', 'real_money', '实际到账',],
                    ['static', 'fee', '手续费',],
                    ['static', 'bank', '提现银行信息'],
                    ($staticContent ? ['static', 'auth_document', $staticLabel, $staticContent] : []),
//                ['static', 'auth_document', $staticLabel, $staticContent], // 根据 auth_type 显示身份证或护照
                    ['static', 'status', '状态', '', $status_arr],
                    ['static', 'remark', '备注', '']
                ])
                ->hideBtn('submit')
                ->setFormData($info)// 设置表单数据
                ->fetch();

    }


//    手续费设置
    public function commission()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            $data['min_money'] = $data['min_money'] < 1 ? 1 : $data['min_money'];
            $result = Db::name('money_withdraw_config')->where("id", 1)->find();
            if ($result){
                Db::name('money_withdraw_config')->where("id", 1)->update($data);
            }else{
                $data['create_time'] = time();
                Db::name('money_withdraw_config')->insert($data);
            }
            $this->success('设置成功', cookie('__forward__'));
        }
        $info = Db::name('money_withdraw_config')->where("id", 1)->find();
        return ZBuilder::make('form')
            ->setPageTitle('提现设置') // 设置页面标题
            ->setPageTips('默认提现手续费比例，如果针对单个会员设置，请进入会员列表搜索该用户，编辑设置', 'danger')
            ->addFormItems([ // 批量添加表单项
                ['number', 'ratio', '手续费', '费率按 百分比%'],
                ['number', 'min_money', '最低提现金额', '数值最少为 1'],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }


//    审核完成
    public function complete()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->agentPartnerSearchMap($map,'money_withdraw.mid');
        $map = (new UserService())->getAgentMap($map,'money_withdraw.mid');
        $noCheck = input('noCheck');
        if (isset($noCheck)) {
            $map[] = ['money_withdraw.status','=',$noCheck];
        }
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        if (empty($map['money_withdraw.create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['money_withdraw.create_time'][1][0]));
        }
        if (empty($map['money_withdraw.create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['money_withdraw.create_time'][1][1]));
        }
        // 数据列表
        $data_list = WithdrawModel::view('money_withdraw', true)
            ->view('member', 'mobile, name, id_card,email,partner_parent_id,role_name,remarks', 'member.id=money_withdraw.mid', 'left')
            ->where($map)
            ->where('money_withdraw.status','>',0)
            ->order($order)
            ->paginate()
            ->each(function ($item, $key) {
                $item->money = money_convert($item->money);
                $item->fee = money_convert($item->fee);
                $item->real_money = money_convert($item->real_money);
                $item->mobile = privacy_info_switch('mobile',$item->mobile);

                $item->name = "<p>" . $item->name . "</p><p>" .$item->mobile. "</p>";;
//            $item->money = "<p>" . $item->money . "</p><p>" .$item->fee. "</p>";;
//                $item->total = $item->fee + $item->money;
                $inviteUser = MemberModel::where('id', $item['partner_parent_id'])->find();
                $item->partner_parent = '';
                if (!empty($inviteUser)) {
                    $inviteUser['mobile'] = privacy_info_switch('mobile',$inviteUser['mobile']);
                    $item->partner_parent = "<p>(ID:{$item['partner_parent_id']})".$inviteUser['mobile']."</p><p>" .$inviteUser['name'].'</p>';
                }
                $item->time = "<p>" . date('Y-m-d H:i',$item->create_time) . "</p><p>" .date('Y-m-d H:i',$item->examine_time). "</p>";

                $bank = explode('|',$item->bank);
                $item->withdraw_type = $bank[0];
                $item->address = $bank[1];
                if(count($bank) >3){
                    $item->address = "<p>" . $bank[0] . "</p><p>" .$bank[1]. "</p>";;
                }


            });
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"].'&';
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon' => 'fa fa-fw fa-download',
//          'href' => url($excel_url, '', '')
            'href' => url($excel_url.http_build_query([$map]), '', '')
        ];
        $btn_commission = [
            'title' => '提现配置',
            'icon' => 'fa fa-fw fa-gear',
//          'href' => url($excel_url, '', '')
            'href'  => url('commission'),
        ];
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        $btn_getWithdraw = ['title' => '提现记录', 'icon' => 'fa fa-fw fa-paste', 'href' => url('getWithdraw', ['mid' => '__mid__'])];
        $btn_getRecharge = ['title' => '充值记录', 'icon' => 'fa fa-fw fa-dollar', 'href' => url('getRecharge', ['mid' => '__mid__'])];
        $btn_detail = ['title' => '审核详情', 'icon' => 'fa fa-fw fa-dollar', 'href' => url('detail', ['id' => '__id__'])];
        return ZBuilder::make('table')
//            ->setExtraHtmlFile('search', 'toolbar_top')
//          ->setSearch(['order_no' => '订单号', 'member.name' => '姓名', 'member.mobile' => '手机号', 'member.email' => '邮箱']) // 设置搜索框
            ->setSearchArea([
                ['text', 'order_no', '订单号', 'like'],
                ['text', 'member.name', '姓名', 'like'],
                ['text', 'member.mobile', '手机号', 'like'],
                ['select', 'money_withdraw.status', '状态', '', '', [1 => '成功', 2 => '失败', 3 => '退回']],
//                ['text', 'member.email', '邮箱', 'like'],
                ['daterange', 'money_withdraw.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
                ['daterange', 'money_withdraw.create_time', '申请时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
            ->addColumns([ // 批量添加数据列
                ['order_no', '订单号'],
                ['name', '姓名/手机号'],
                ['remarks', '备注'],
                ['role_name', '白名单', $this->user_role_name],
                ['partner_parent', '邀请人账号/邀请人姓名'],
                ['money', '提现金额'],
                ['real_money', '实际到账'],
                ['fee', '手续费'],
                ['exchange_rate', '汇率'],
                ['exchange_rate_number', '到账数量'],
                ['withdraw_type', '提现方式'],
//            ['withdraw_type', '提现渠道'],
                ['address', '账号信息'],

//            ['bank', '账户信息'],
                ['status', '状态'],
                ['time', '申请时间/审核时间'],
                ['right_button', '操作', 'btn']
            ])
            ->hideCheckbox()
            ->addTopButton('custem', $btn_excel)
            ->addTopButton('custem', $btn_commission) // 批量添加顶部按钮
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
//            ->addRightButton('edit', ['title' => '编辑回退'], ['area' => ['800px', '90%'], 'title' => '提现审核']) // 批量添加右侧按钮
            ->addRightButton('btn_detail', $btn_detail,true)
//          ->addRightButton('custom1', $btn_getWithdraw)
//          ->addRightButton('custom2', $btn_getRecharge)
//          ->replaceRightButton(['status' => '成功'], '<button class="btn btn-success btn-xs" type="button" disabled>审核成功</button>')
//            ->replaceRightButton(['status' => '待处理'], '', ['custom1','custom2','btn_detail'])
//            ->replaceRightButton(['status' => '成功'], '', 'edit')
            ->replaceRightButton(['status' => '退回'], '', 'edit')
            ->replaceRightButton(['status' => '失败'], '', 'edit')
//          ->addTimeFilter('money_withdraw.create_time', [$beginday, $endday]) // 添加时间段筛选
            ->addOrder('id,create_time,status')
            ->setRowList($data_list)
            ->setColumnWidth('withdraw_type', 80)
            ->setColumnWidth('address', 150)
            ->setColumnWidth('partner_parent', 120)
//            ->setColumnWidth('order_no', 50)
//            ->setColumnWidth('right_button', 60)
//            ->setColumnWidth('status,money', 20)
            ->setColumnWidth('time', 150)
            ->fetch(); // 渲染模板
    }


    public function complete_export()
    {
        $map = $this->getMap();
        $noCheck = input('noCheck');
        if (isset($noCheck)) {
            $map['money_withdraw.status'] = $noCheck;
        }
        $map[] = ['money_withdraw.status','>',0];
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData = WithdrawModel::getAll($map, $order);
        //$type_arr=['transfer'=>"线下转账","alipay"=>"支付宝"];
        foreach ($xlsData as $k => $v) {
            // $v['type']=$type_arr[$v['type']];
            // $v['status']=$status_arr[$v['status']];
            $xlsData[$k]['create_times'] = date('Y-m-d H:i:s', $v["create_time"]);
            $xlsData[$k]['mobile'] = privacy_info_switch('mobile',$xlsData[$k]['mobile']);
        }
        $title = "提现审核完成记录";
        $arrHeader = array('订单号', '手机号', '姓名', '金额', '银行信息', '状态', '申请时间');
        $fields = array('order_no', 'mobile', 'name', 'money', 'bank', 'status', 'create_times');
        export($arrHeader, $fields, $xlsData, $title);
    }
}