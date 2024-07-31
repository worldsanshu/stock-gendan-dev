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
use app\member\model\Member;
use app\money\model\Payment;
use app\money\model\Money as MoneyModel;
use app\money\model\Recharge as RechargeModel;
use app\statistics\model\DataReport as DataReportModel;
use app\statistics\model\RechargeWithdrawal as RechargeWithdrawalModel;
use app\statistics\model\UserStatistics as UserStatisticsModel;
use think\Db;
use app\common\service\UserService;

/**
 * 充值管理控制器
 * @package app\money\admin
 */
class Recharge extends Admin
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
        $map = (new UserService())->agentPartnerSearchMap($map,'money_recharge.mid');
        $map = (new UserService())->getAgentMap($map,'money_recharge.mid');
        $payment_id = input('payment_id');
        if ($payment_id) {
            $map[] = ['payment_id','=',$payment_id];
        }
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = RechargeModel::view('money_recharge', true)
        ->view('member', 'mobile, name, id_card,role_name', 'member.id=money_recharge.mid', 'left')
        ->view('money_payment', 'name as payment_name,unit', 'money_payment.id=money_recharge.payment_id', 'left')
        ->where($map)
        ->where('money_recharge.status',0)
        ->order($order)
        ->paginate()
        ->each(function ($item, $key) {
            $item->money = money_convert($item->money);
            $item->fee = money_convert($item->fee);
            if(empty($item->currency)){
                $item->currency = '人民币';
            }
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
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            //            'href' => url('/money/index/index_export?'.http_build_query([$map]),'','')
            'href'  => url($excel_url . http_build_query([$map]), '', '')
        ];
        $totalsum = 0;
        foreach ($data_list as $k => $v) {
            $totalsum = $totalsum + $v['money'];
            //兼容下之前的错误数据
            if (empty($v['type'])) {
                $data_list[$k]['type'] = Payment::where('id',$v['payment_id'])->value('type');
            }
        }
        $pay_type_list = Payment::$payType;
        $html = <<<EOF
            <p>当前页面金额为：{$totalsum}元，\t可通过筛选条件快速快速筛选出对应数据。状态0为未处理，1为成功 2为失败</p>
EOF;
        return ZBuilder::make('table')
//            ->setExtraHtmlFile('search', 'toolbar_top')
//          ->setSearch(['mid' => '用户ID', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
            ->setSearchArea([
                ['text', 'mid', '用户ID'],
                ['text', 'member.name', '姓名', 'like'],
                ['text', 'member.mobile', '手机号', 'like'],
//                ['text', 'member.email', '邮箱', 'like'],
//                ['select', 'money_recharge.status', '状态', '', '', [0 => '待处理',1 => '成功', 2 => '失败']],
                ['select', 'money_recharge.type', '充值方式', '', '', $pay_type_list],
//                ['text', 'account', '可用资金'],
//                ['text', 'freeze', '冻结金额'],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
                ['daterange', 'money_recharge.create_time', '充值时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['order_no', '订单号'],
            ['mobile', '手机号'],
            ['mid', '用户ID'],
            ['name', '姓名'],
              ['role_name', '白名单', $this->user_role_name],
            ['money', '金额'],

//            ['is_auto', '是否自动到账', ['0' => "否", "1" => "是"]],
            ['currency', '币种'],
//            ['type', '充值方式', ['alipay' => "支付宝", "wechatpay" => "微信支付", "bank" => "银联支付", "currency" => "数字货币", "transfer" => "线下支付"]],
            ['type', '充值方式',$pay_type_list],
            ['create_time', '充值时间', 'datetime'],
            ['status', '状态'],
            ['right_button', '操作', 'btn']
          ])
          ->setExtraHtml($html, 'table_bottom')
          ->addFilter('money_recharge.status') // 添加筛选
//          ->addTimeFilter('money_recharge.create_time', [date("Y-m-d"), date("Y-m-d")], ['开始时间', '结束时间'])
          ->hideCheckbox()
          ->addTopButton('custem', $btn_excel)
          ->addRightButton('edit', ['title' => '充值审核'], ['area' => ['800px', '90%'], 'title' => '充值审核']) // 批量添加右侧按钮
//          ->replaceRightButton(['status' => '成功'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
//          ->replaceRightButton(['status' => '失败'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
//          ->replaceRightButton(['type' => '1'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
//          ->replaceRightButton(['type' => '2'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
          ->addOrder('id,create_time,status,money,order_no')
          ->setRowList($data_list)
          ->fetch(); // 渲染模板
    }

    public function index_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        $map[] = ['money_recharge.status','=',0];
        // 数据列表
        $xlsData = RechargeModel::getAll($map, $order);
//        $type_arr = ['transfer' => "线下转账",'currency' => "货币", "1" => "支付宝线上转账", "2" => "微信线上转账", "bank" => "银联"];
        $type_arr = Db::name('pay_type')->column('name', 'type');
        foreach ($xlsData as $k => $v) {
            $v['type'] = $type_arr[$v['type']];
            // $v['status']=$status_arr[$v['status']];
            $xlsData[$k]['create_times'] = date('Y-m-d H:i:s', $v["create_time"]);
            if(empty($v["currency"])){
                $xlsData[$k]['currency'] = '人民币';
            }
        }
        $title = "充值待审核记录";
        $arrHeader = array('ID', '订单号', '手机号', '姓名', '金额', '币种','状态', '充值方式', '充值时间');
        $fields = array('id', 'order_no', 'mobile', 'name', 'money', 'currency','status', 'type', 'create_times');
        export($arrHeader, $fields, $xlsData, $title);
    }



    /**
     * 充值已审核列表
     * @return mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function complete()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->agentPartnerSearchMap($map,'money_recharge.mid');
        $map = (new UserService())->getAgentMap($map,'money_recharge.mid');
        $payment_id = input('payment_id');
        if ($payment_id) {
            $map[] = ['payment_id','=',$payment_id];
        }
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = RechargeModel::view('money_recharge', true)
            ->view('member', 'mobile, name, id_card,role_name', 'member.id=money_recharge.mid', 'left')
            ->view('money_payment', 'name as payment_name,unit', 'money_payment.id=money_recharge.payment_id', 'left')
            ->where($map)
            ->where('money_recharge.status','>',0)
            ->order($order)
            ->paginate()
            ->each(function ($item, $key) {
                $item->money = money_convert($item->money);
                $item->fee = money_convert($item->fee);
                if(empty($item->currency)){
                    $item->currency = '人民币';
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
        $totalsum = 0;
        foreach ($data_list as $k => $v) {
            $totalsum = $totalsum + $v['money'];
            //兼容下之前的错误数据
            if (empty($v['type'])) {
                $data_list[$k]['type'] = Payment::where('id',$v['payment_id'])->value('type');
            }
        }
        $pay_type_list = Payment::$payType;
        $html = <<<EOF
            <p>当前页面金额为：{$totalsum}元，\t可通过筛选条件快速快速筛选出对应数据。状态0为未处理，1为成功 2为失败</p>
EOF;
        $btn_detail = ['title' => '审核详情', 'icon' => 'fa fa-fw fa-dollar', 'href' => url('detail', ['id' => '__id__'])];
        return ZBuilder::make('table')
//            ->setExtraHtmlFile('search', 'toolbar_top')
//          ->setSearch(['mid' => '用户ID', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
            ->setSearchArea([
                ['text', 'mid', '用户ID'],
                ['text', 'member.name', '姓名', 'like'],
                ['text', 'member.mobile', '手机号', 'like'],
//                ['text', 'member.email', '邮箱', 'like'],
                ['select', 'money_recharge.status', '状态', '', '', [1 => '成功', 2 => '失败']],
                ['select', 'money_recharge.type', '充值方式', '', '', $pay_type_list],
//                ['text', 'account', '可用资金'],
//                ['text', 'freeze', '冻结金额'],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
                ['daterange', 'money_recharge.create_time', '充值时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['order_no', '订单号'],
                ['mobile', '手机号'],
                ['mid', '用户ID'],
                ['name', '姓名'],
                ['role_name', '白名单', $this->user_role_name],
                ['money', '金额'],

//                ['is_auto', '是否自动到账', ['0' => "否", "1" => "是"]],
                ['currency', '币种'],
//            ['type', '充值方式', ['alipay' => "支付宝", "wechatpay" => "微信支付", "bank" => "银联支付", "currency" => "数字货币", "transfer" => "线下支付"]],
                ['type', '充值方式',$pay_type_list],
                ['create_time', '充值时间', 'datetime'],
                ['status', '状态'],
                ['right_button', '操作', 'btn']
            ])
            ->setExtraHtml($html, 'table_bottom')
            ->addFilter('money_recharge.status') // 添加筛选
//          ->addTimeFilter('money_recharge.create_time', [date("Y-m-d"), date("Y-m-d")], ['开始时间', '结束时间'])
            ->hideCheckbox()
            ->addTopButton('custem', $btn_excel)
            ->addRightButton('btn_detail', $btn_detail,true)
//            ->addRightButton('detail', ['title' => '充值详情'], ['area' => ['800px', '90%'], 'title' => '充值详情']) // 批量添加右侧按钮
//            ->replaceRightButton(['status' => '成功'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
//            ->replaceRightButton(['status' => '失败'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
//            ->replaceRightButton(['type' => '1'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
//            ->replaceRightButton(['type' => '2'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
            ->addOrder('id,create_time,status,money,order_no')
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }



    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数', null, '_close_pop');
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            $result = $this->validate($data, 'Recharge.update');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            $result_up = RechargeModel::saveAudit();

            if (true !== $result_up['status']) {
                $this->error($result_up['msg']);
            } else {
                if ($data['status'] == 1) {
                    //充值成功
                    $recharge_data = RechargeModel::where('id', $id)->field('*')->find();
                    //更新充值时间
                    $update_data['recharge_time'] = $recharge_data['create_time'];
                    MoneyModel::where('mid', $recharge_data['mid'])->update($update_data);
                    $for_user = Member::where('id', $recharge_data['mid'])->value('agent_far');
                    //添加到充值提现统计
                    $post_data['recharge'] = $recharge_data['money'] / 100;
                    $post_data['create_time'] = $recharge_data['create_time'];
                    $post_data['recharge_user'] = 1;
                    $post_data['for_user'] = $for_user;
                    RechargeWithdrawalModel::appendRechargeWithdrawal($post_data);
                    //用户统计
                    UserStatisticsModel::appendUserStatistics(['recharge_withdrawal' => 1, 'create_time' => $recharge_data['create_time'], 'for_user' => $for_user]);
                    //数据报表
                    $data_report ['user_recharge'] = 1;
                    $data_report ['create_time'] = $recharge_data['create_time'];
                    $data_report ['day_recharge'] = $recharge_data['money'] / 100;
                    $mid = $recharge_data['mid'];
                    $map = [];
                    $map['mid'] = $mid;
                    $map['status'] = 1;
                    $count = RechargeModel::where($map)->count();
                    if ($count == 1) {
                        $create_time = Db::name('member')->where('id', $mid)->value('create_time');
                        if (date('Y-m-d', $create_time) == date('Y-m-d', $recharge_data['create_time'])) {
                            $data_report['effective_user'] = 1;
                        }
                        $data_report['user_first_recharge'] = 1;
                        RechargeModel::where('id', $id)->update(['is_first' => 1]);//标记为首充
                    }
                    DataReportModel::appendDataReport($data_report);
                }
                $this->success('编辑成功', null, '_parent_reload');
            }
        }
        $info = RechargeModel::where('id', $id)->find();
        $info['create_time_text'] = date('Y-m-d H:i:s', $info['create_time']);
        if ($info->getData('status') !== 0) {
            $this->error('此状态不允许编辑', null, '_close_pop');
        }
        $info->money = money_convert($info->money);
        $arr = ['alipay' => "支付宝", "wechatpay" => "微信支付", "bank" => "银联支付", "currency" => "数字货币", "transfer" => "线下支付"];
        if ($info->type !== 'transfer') {
            $info['line_bank'] = '线上支付';
        } else {
            if ($info['charge_type_id']) {
                $bank_info = Db::name("admin_bank")->where(['id' => $info['charge_type_id']])->find();
                if (!empty($bank_info)) {
                    $info['line_bank'] = '存入银行:' . $bank_info['bank_name'] . '; 收款卡号：' . $bank_info['card'];
                }
            }
        }
        $info->type = $arr[$info->type] ?? '线上支付';
        $addr = $_SERVER["DOCUMENT_ROOT"] . '/uploads/images/' . $info['receipt_img'];
        if (!empty($info["receipt_img"]) && file_exists($addr)) {
            $myurl = '<div id="WU_FILE_0" class="file-item js-gallery thumbnail"><a style="display: inline;" href="' . PUBLIC_PATH . 'uploads/images/' . $info['receipt_img'] . '"data-toggle="tooltip"title="点击查看大图"target="_blank"><img style="width:200px;" src="' . PUBLIC_PATH . 'uploads/images/' . $info['receipt_img'] . '"></a></div>';
        } else {
            $myurl = '<span></span>';
        }
        return ZBuilder::make('form')
          ->setPageTitle('审核') // 设置页面标题
          ->addFormItems([ // 批量添加表单项
            ['hidden', 'id'],
            ['static', 'order_no', '订单号'],
            ['static', 'money', '充值金额'],
            ['static', 'type', '充值方式'],
            ['static', 'create_time_text', '充值时间'],
            ['static', 'currency', '币'],
            ['static', 'num', '充值数量'],
            ['static', 'txid', '交易哈希'],
            ['static', 'address', '充值地址'],
            ['static', 'transfer_number', '转账卡号'],
            ['static', 'transfer_username', '转账用户名'],
            ['radio', 'status', '充值状态', '', [1 => '成功', 2 => '失败']],
            ['textarea', 'remark', '备注', '']
          ])
          ->setExtraHtml($myurl, 'form_top')
          ->setFormData($info) // 设置表单数据
          ->fetch();
    }


    public function detail($id = null)
    {
        if ($id === null) $this->error('缺少参数', null, '_close_pop');
        $info = RechargeModel::where('id', $id)->find();
        $info['create_time_text'] = date('Y-m-d H:i:s', $info['create_time']);

        $info->money = money_convert($info->money);
        $arr = ['alipay' => "支付宝", "wechatpay" => "微信支付", "bank" => "银联支付", "currency" => "数字货币", "transfer" => "线下支付"];
        if ($info->type !== 'transfer') {
            $info['line_bank'] = '线上支付';
        } else {
            if ($info['charge_type_id']) {
                $bank_info = Db::name("admin_bank")->where(['id' => $info['charge_type_id']])->find();
                if (!empty($bank_info)) {
                    $info['line_bank'] = '存入银行:' . $bank_info['bank_name'] . '; 收款卡号：' . $bank_info['card'];
                }
            }
        }
        if ($info->getData('status') == 0) {
            $status_arr = [1 => '成功'];
        }else {
            $status_arr = [2 => '失败'];
        }
//        $info->type = $arr[$info->type] ?? '线上支付';
        $pay_type_list = Payment::$payType;
        $info->type = $pay_type_list[$info['type']] ?? '线上支付';
        $addr = $_SERVER["DOCUMENT_ROOT"] . '/uploads/images/' . $info['receipt_img'];
        if (!empty($info["receipt_img"]) && file_exists($addr)) {
            $myurl = '<div id="WU_FILE_0" class="file-item js-gallery thumbnail"><a style="display: inline;" href="' . PUBLIC_PATH . 'uploads/images/' . $info['receipt_img'] . '"data-toggle="tooltip"title="点击查看大图"target="_blank"><img style="width:200px;" src="' . PUBLIC_PATH . 'uploads/images/' . $info['receipt_img'] . '"></a></div>';
        } else {
            $myurl = '<span></span>';
        }
        return ZBuilder::make('form')
            ->setPageTitle('审核') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static', 'order_no', '订单号'],
                ['static', 'money', '充值金额'],
                ['static', 'type', '充值方式'],
                ['static', 'create_time_text', '充值时间'],
                ['static', 'currency', '币'],
                ['static', 'num', '充值数量'],
                ['static', 'txid', '交易哈希'],
                ['static', 'address', '充值地址'],
                ['static', 'transfer_number', '转账卡号'],
                ['static', 'transfer_username', '转账用户名'],
//                ['static', 'status', '充值状态', '', [1 => '成功', 2 => '失败']],
                ['static', 'status', '状态', '', $status_arr],
                ['static', 'remark', '备注', '']
            ])
            ->setExtraHtml($myurl, 'form_top')
            ->hideBtn('submit')
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    public function complete_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        $map[] = ['money_recharge.status','>',0];
        // 数据列表
        $xlsData = RechargeModel::getAll($map, $order);
//        $type_arr = ['transfer' => "线下转账",'currency' => "货币", "1" => "支付宝线上转账", "2" => "微信线上转账", "bank" => "银联"];
        $type_arr = Db::name('pay_type')->column('name', 'type');
        foreach ($xlsData as $k => $v) {
            $v['type'] = $type_arr[$v['type']];
            // $v['status']=$status_arr[$v['status']];
            $xlsData[$k]['create_times'] = date('Y-m-d H:i:s', $v["create_time"]);
            if(empty($v["currency"])){
                $xlsData[$k]['currency'] = '人民币';
            }
        }
        $title = "充值审核完成记录";
        $arrHeader = array('ID', '订单号', '手机号', '姓名', '金额', '币种','状态', '充值方式', '充值时间');
        $fields = array('id', 'order_no', 'mobile', 'name', 'money', 'currency','status', 'type', 'create_times');
        export($arrHeader, $fields, $xlsData, $title);
    }

}

