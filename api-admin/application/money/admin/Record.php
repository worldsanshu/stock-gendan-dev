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
use app\money\model\Record as RecordModel;
use app\common\service\UserService;

/**
 * 资金记录控制器
 * @package app\money\admin
 */
class Record extends Admin
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
        $map = (new UserService())->agentPartnerSearchMap($map,'r.mid');
        $map = (new UserService())->getAgentMap($map,'r.mid');
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
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
        // 数据列表
        $data_list = RecordModel::view('money_record r', true)
        ->view('member m', 'mobile, name,id_card,email,create_time as m_time,role_name', 'm.id=r.mid', 'left')
        ->where($map)
        ->order($order)
        ->paginate()
        ->each(function ($item, $key) {
            $item->account = money_convert($item->account);
            $item->affect = money_convert($item->affect);
            $item->affect_activity = money_convert($item->affect_activity);
            $item->activity_account = money_convert($item->activity_account);
            $item->type_str = $item->type;
            $item->mobile = privacy_info_switch('mobile',$item->mobile);
        });
        // 实例化 MyClass
        $myClassInstance = new RecordModel();

        // 通过 getter 方法获取 $type 属性的值
        $typeArray = $myClassInstance->getTypeAll();

        // 现在 $typeArray 包含了 $type 属性的值，你可以在控制器中使用它
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
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        foreach ($data_list as $key => $value) {
            $affect_activity = ($value['affect_activity']) >= 1 ? "danger" : "";
            $activity_account = ($value['activity_account']) >= 1 ? "danger" : "";
            $affect = ($value['affect']) >= 1 ? "danger" : "";
            $account = ($value['account']) >= 1 ? "danger" : "";
            $data_list[$key]['activity'] = "<p class='{$affect_activity}'>" . ($value['affect_activity']) . "</p><p class='{$activity_account}'>" . ($value['activity_account']) . "</p>";
            $data_list[$key]['account'] = "<p class='{$affect}'>" . ($value['affect']) . "</p><p class='{$account}'>" . ($value['account']) . "</p>";
            $email = empty($value['email']) ? '--' : $value['email'];
            $data_list[$key]['user'] = "<p>" . $value['mobile'] . "</p><p>" . $email . "</p>";
        }
        return ZBuilder::make('table')
//          ->setSearch('mobile,name') // 设置搜索框
//            ->setExtraHtmlFile('search', 'toolbar_top')
            ->setSearchArea([
                ['text', 'name', '姓名', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['select', 'type', '类型', '', '', $typeArray],
                ['daterange', 'r.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
                ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['user', '手机号/邮箱'],
            ['name', '姓名'],
            ['role_name', '白名单', $this->user_role_name],
            ['type_str', '类型'],
            ['activity', '变动活动金/活动金余额'],
            ['account', '变动资金/账户余额'],
//                ['affect_activity', '变动活动金'],
//                ['activity_account', '活动金余额'],
//                ['affect', '变动资金'],
//                ['account', '账户余额'],
            ['create_time', '时间', 'datetime'],
            ['info', '信息'],
          ])
          ->hideCheckbox()
          ->setExtraHtml('账户余额显示，把活动金也计算入内，可能有错误的情况。', 'toolbar_bottom')
          ->addTopButton('custem', $btn_excel)
          ->setTableName('money')
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
//          ->addTimeFilter('r.create_time', [$beginday, $endday]) // 添加时间段筛选
          ->addOrder('id,create_time')
          ->setRowList($data_list)
          ->fetch(); // 渲染模板
    }

    public function index_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData = RecordModel::getAll($map, $order);
        foreach ($xlsData as &$v) {
            $v['create_time'] = date('Y-m-d h:i', $v['create_time']);
            $v['mobile'] = privacy_info_switch('mobile',$v['mobile']);
        }
        $title = "清算明细";
        $arrHeader = array('ID', '手机号', '姓名', '类型', '变动资金','变动活动金', '账户余额', '时间', '信息');
        $fields = array('id', 'mobile', 'name', 'type', 'affect','affect_activity', 'account', 'create_time', 'info');
        export($arrHeader, $fields, $xlsData, $title);
    }
}