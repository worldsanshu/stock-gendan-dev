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
use app\money\model\Transfer as TransferModel;
use app\common\service\UserService;

/**
 * 转账管理控制器
 * @package app\money\admin
 */
class Transfer extends Admin
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
        $map = (new UserService())->getAgentMap($map,'money_transfer.user_id');
        $order = $this->getOrder();

        empty($order) && $order = 'id desc';
        if (empty($map['money_transfer.create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['money_transfer.create_time'][1][0]));
        }
        if (empty($map['money_transfer.create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['money_transfer.create_time'][1][1]));
        }
        // 数据列表
        $data_list = TransferModel::getAll($map, $order);
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
        return ZBuilder::make('table')
//            ->setExtraHtmlFile('search', 'toolbar_top')
//          ->setSearch(['order_no' => '订单号', 'admin_user.username' => '执行者', 'member.name' => '姓名', 'member.mobile' => '手机号', 'member.email' => '邮箱']) // 设置搜索框
            ->setSearchArea([
                ['text', 'order_no', '订单号', 'like'],
                ['text', 'admin_user.username', '执行者', 'like'],
                ['text', 'member.name', '姓名', 'like'],
                ['text', 'member.mobile', '手机号', 'like'],
                ['text', 'member.email', '邮箱', 'like'],
                ['daterange', 'money_transfer.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
          ->addColumns([ // 批量添加数据列
            ['order_no', '订单号'],
            ['mobile', '手机号'],
            ['email', '邮箱'],
            ['name', '姓名'],
            ['role_name', '白名单', $this->user_role_name],
            ['money', '金额'],
            ['username', '执行者'],
            ['info', '详情'],
            ['create_time', '时间', 'datetime'],
            ['create_ip', 'IP地址', 'callback', 'long2ip'],
          ])
          ->hideCheckbox()
          ->addTopButton('add', [], ['area' => ['800px', '90%'], 'title' => '发起转账']) // 批量添加顶部按钮
          ->addTopButton('custem', $btn_excel)
          ->addOrder('id,create_time,user_id,money,order_no')
          ->setRowList($data_list)
//          ->addTimeFilter('money_transfer.create_time', [$beginday, $endday]) // 添加时间段筛选
          ->fetch(); // 渲染模板
    }

    public function index_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData = TransferModel::getAll($map, $order);
        foreach ($xlsData as $k => $v) {
            $v['create_times'] = date('Y-m-d h:i', $v['create_time']);
        }
        $title = "转账管理";
        $arrHeader = array('订单号', '手机号', '姓名', '金额', '执行者', '详情', '时间', 'IP地址');
        $fields = array('order_no', 'mobile', 'name', 'money', 'username', 'info', 'create_times', 'create_ip');
        export($arrHeader, $fields, $xlsData, $title);
    }

    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            $result = $this->validate($data, 'Transfer.save');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            $result_up = TransferModel::saveData();
            if (true !== $result_up) {
                $this->error($result_up);
            } else {
                $this->success('转账处理成功', null, '_parent_reload');
            }
        }
        return ZBuilder::make('form')
          ->setPageTitle('发起转账') // 设置页面标题
          ->addFormItems([ // 批量添加表单项
            ['text', 'mobile', '手机号', '会员用于登陆手机号码'],
            ['text', 'money', '转账金额', '需要转给会员的金额（元）'],
            ['textarea', 'info', '备注', '转账详情6-255个字符']
          ])
          ->fetch();
    }
}