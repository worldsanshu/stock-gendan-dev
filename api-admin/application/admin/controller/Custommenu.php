<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Customproperty;
use app\common\builder\ZBuilder;
use app\admin\model\Custommenu as CustommenuModel;
use app\member\model\Member;
use app\member\model\Member as MemberModel;
use app\statistics\model\DataReport as DataReportModel;
use think\facade\Cache;
use think\Db;
use think\facade\Hook;

/**
 * 用于移动端自定义菜单管理
 * @package app\admin\controller
 */
class Custommenu extends Admin
{
    /**
     * 首页
     * @return mixed
     */
    public function index($group = 'home')
    {
        $data_count = CustommenuModel::where('position',$group)->count();
        if($data_count < 1){
            $this->restore($group,true);
        }
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 查询
        $map = $this->getMap();
        $map[] = ['position', '=', $group];
        // 排序
        $order = $this->getOrder('sort');
        // 数据列表
        $data_list = CustommenuModel::getAll($map, $order);
        foreach ( $data_list as &$value){
            if($value['img_url']){
                $value['image'] = CustommenuModel::getImgurlTextAttr($value['img_url']);
            }else{
                $value['image'] = $value['default_icon'];
            }
        }
        // 分页数据
        $page = $data_list->render();

        $list_tab = [
            'home' => ['title' => '首页导航', 'url' => url('index', ['group' => 'home'])],
            'user' => ['title' => '用户中心图标导航', 'url' => url('index', ['group' => 'user'])],
            'user_list' => ['title' => '用户中心列表导航', 'url' => url('index', ['group' => 'user_list'])],
        ];
        $role_name = array("home" => "首页", "user" => "用户中心图标", "user_list" => "用户中心列表");
        $btn_add = ['title' => '新增', 'icon' => 'fa fa-fw fa-users', 'href' => url('add', ['position' => $group])];
        $btn_menu = ['title' => '查看内置路径', 'icon' => 'fa fa-fw fa-users', 'href' => url('menu',['group' => $group])];

        $title = '';
        $text = '';
        if($group == 'home'){
            $title = '还原首页导航';
            $text = '确认要还原首页导航吗？';
        }else if ( $group == 'user'){
            $title = '还原个人中心图标导航';
            $text = '确认要还原个人中心图标导航吗？';
        }else{
            $title = '还原个人中心列表导航';
            $text = '确认要还原个人中心列表导航吗？';
        }
        $btn_home = [
            'title' => $title,
            'icon' => 'fa fa-times-circle-o',
            'class' => 'btn btn-primary ajax-get confirm',
            'data-title' => $text,
            'href' => url('restore',['group' => $group])
        ];

        $url_name = [
//            '0' => '不跳转',
'1' => '内部路径',
'2' => '外部链接',

        ];
        switch ($group) {
            case 'home':
                return ZBuilder::make('table')
                    ->setTabNav($list_tab,  $group)
                    ->setPageTips('首页导航显示', 'danger')
                    ->hideCheckbox()
                    ->addTopButton('custom', $btn_home)
                    ->addTopButton('custom', $btn_add)
                    ->addTopButton('custom', $btn_menu,true)
                    ->addColumns([ // 批量添加数据列
                                   ['position', '位置', $role_name],
                                   ['name', '名称', 'text.edit'],
                                   ['image', '图标', 'img_url'],
                                   ['url', '路径'],
                                   ['url_level', '路由等级'],
                                   ['url_type', '路径类型',$url_name],
                                   ['is_default', '是否默认菜单',[ '0' => '否','1' => '是',]],
                                   ['sort', '排序','text.edit'],
                                   //                        ['create_time', '创建时间', 'datetime', '', 'Y-m-d H:i:s'],
                                   //                        ['create_time', '修改时间', 'datetime', '', 'Y-m-d H:i:s'],
                    ])
                    ->setColumnWidth('url', 200)
                    ->addColumn('status', '状态', 'switch')
                    ->addColumn('right_button', '操作', 'btn')
                    ->addRightButtons('edit') // 批量添加右侧按钮  ,last_login_time
                    ->addRightButtons('delete') // 批量添加右侧按钮  ,last_login_time
                    ->replaceRightButton(['is_default' => 1], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
                    ->setRowList($data_list) // 设置表格数据
                    ->setPages($page) // 设置分页数据
                    ->fetch(); // 渲染模板
                break;
            case 'user':
                return ZBuilder::make('table')
                    ->setTabNav($list_tab,  $group)
                    ->setPageTips('用户中心图标导航显示', 'danger')
                    ->hideCheckbox()
                    ->addTopButton('custom', $btn_home)
                    ->addTopButton('custom', $btn_add)
                    ->addTopButton('custom', $btn_menu,true)
                    ->addColumns([ // 批量添加数据列
                                   ['position', '位置', $role_name],
                                   ['name', '名称', 'text.edit'],
                                   ['image', '图标', 'img_url'],
                                   ['url', '路径'],
                                   ['url_level', '路由等级'],
                                   ['url_type', '路径类型',$url_name],
                                   ['is_default', '是否默认菜单',[ '0' => '否','1' => '是',]],
                                   ['sort', '排序','text.edit'],
                                   //                        ['create_time', '创建时间', 'datetime', '', 'Y-m-d H:i:s'],
                                   //                        ['create_time', '修改时间', 'datetime', '', 'Y-m-d H:i:s'],
                    ])
                    ->setColumnWidth('url', 200)
                    ->addColumn('status', '状态', 'switch')
                    ->addColumn('right_button', '操作', 'btn')
                    ->addRightButtons(['edit']) // 批量添加右侧按钮  ,last_login_time
                    ->addRightButtons('delete') // 批量添加右侧按钮  ,last_login_time
                    ->replaceRightButton(['is_default' => 1], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
                    ->setRowList($data_list) // 设置表格数据
                    ->setPages($page) // 设置分页数据
                    ->fetch(); // 渲染模板
                break;
            case 'user_list':
                return ZBuilder::make('table')
                    ->setTabNav($list_tab,  $group)
                    ->setPageTips('用户中心列表导航显示', 'danger')
                    ->hideCheckbox()
                    ->addTopButton('custom', $btn_home)
                    ->addTopButton('custom', $btn_add)
                    ->addTopButton('custom', $btn_menu,true)
                    ->addColumns([ // 批量添加数据列
                                   ['position', '位置', $role_name],
                                   ['name', '名称', 'text.edit'],
                                   ['image', '图标', 'img_url'],
                                   ['url', '路径'],
                                   ['url_level', '路由等级'],
                                   ['url_type', '路径类型',$url_name],
                                   ['is_default', '是否默认菜单',[ '0' => '否','1' => '是',]],
                                   ['sort', '排序','text.edit'],
                                   //                        ['create_time', '创建时间', 'datetime', '', 'Y-m-d H:i:s'],
                                   //                        ['create_time', '修改时间', 'datetime', '', 'Y-m-d H:i:s'],
                    ])
                    ->setColumnWidth('url', 200)
                    ->addColumn('status', '状态', 'switch')
                    ->addColumn('right_button', '操作', 'btn')
                    ->addRightButtons(['edit']) // 批量添加右侧按钮  ,last_login_time
                    ->addRightButtons('delete') // 批量添加右侧按钮  ,last_login_time
                    ->replaceRightButton(['is_default' => 1], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
                    ->setRowList($data_list) // 设置表格数据
                    ->setPages($page) // 设置分页数据
                    ->fetch(); // 渲染模板
                break;
        }

    }


    public function add()
    {
        $position = input('position',0);
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();

            if ($data['status']) {
                $data['status'] = 1;
            }
            if($data['url_type'] == 2) {
                if (!preg_match('/^https?:\/\//', $data['url'])) {
                    $this->error('请填写正确的链接:需要包含http:// 或者 https://');
                }
            }
            // 验证失败 输出错误信息
            $isExists = Db::name('custom_menu')->where('name', $data['name'])->where('position', $data['name'])->count();
            if ($isExists > 0) {
                $this->error('名称已存在');
            }
            $data['create_time'] = time();
            $data['is_default'] = 0;
            $result_up = CustommenuModel::create($data);
            if ($result_up['status'] != 1) {
                $this->error('添加失败');
            } else {

                $this->success('添加成功', cookie('__forward__'));
            }
        }
//        $url_name = array("url" => "内置路径", "custom" => "第三方路径");
        $url_name = [
//            '0' => '不跳转',
'1' => '内部路径',
'2' => '外部链接',

        ];
        return ZBuilder::make('form')
            ->setPageTitle('新增') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                             ['text', 'name', '名称', '导航的显示名称'],
                             ['select', 'url_type', '路径类型', '(内置路径：从列表页点击查看内置路径)',$url_name,'1'],
                             ['text', 'url', '路径', '导航跳转的页面路径'],
                             ['radio','url_level', '路由等级', '(一级路由：多为底部导航，二级路由：通常为部分页面里的子页面)', ['1' => '一级路由', '2' => '二级路由'], '1'],
                             ['number', 'sort', '排序'],
                             ['radio', 'status', '显示状态', '', ['禁用', '启用'],'1'],
            ])
//            ->addSwitch('status', '显示状态', '', '1')
            ->addImage('img_url', '图标')
            ->setTrigger('url_type', '1', 'url_level')
            ->fetch();
    }


    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            // 验证失败 输出错误信息
            $isExists = Db::name('custom_menu')->where('name', $data['name'])->where('position', $data['name'])->count();
            if($isExists){
                if ($isExists['id'] != $data['id']) {
                    $this->error('名称已存在');
                }
            }
            if($data['url_type'] == 2) {
                if (!preg_match('/^https?:\/\//', $data['url'])) {
                    $this->error('请填写正确的链接:需要包含http:// 或者 https://');
                }
            }
            $data['update_time'] = time();
            $edit = CustommenuModel::update($data);
            if ($edit) {
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info = CustommenuModel::where('id', $id)->find();
        $new_img = '';
//        if($info['img_url']){
//            $info['img_url'] = CustommenuModel::getImgurlTextAttr($info['img_url']);
//        }else{
        $info['img_url'] = $info['default_icon'];
//        }
//        $url_name = array("url" => "内置路径", "custom" => "第三方路径");
        $url_name = [
//            '0' => '不跳转',
'1' => '内部路径',
'2' => '外部链接',

        ];

        if($info['is_default'] == 1){
            return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                             ['static', 'name', '名称','导航的显示名称'],
                             ['static', 'url_type', '路径类型', '(内置路径：从列表页点击查看内置路径)',$url_name],
                             ['static', 'url', '路径','导航跳转的页面路径'],
                             ['static','url_level', '路由等级', '(一级路由：多为底部导航，二级路由：通常为部分页面里的子页面)', ['1' => '一级路由', '2' => '二级路由'], '1'],
                             ['number', 'sort', '排序'],
                             ['radio', 'status', '显示状态', '', ['禁用', '启用']],
            ])

                                     //                ->addImage('img_url', '图标')
                ->setFormData($info) // 设置表单数据
                ->fetch();
        }else{
            return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                             ['text', 'name', '名称'],
                             ['text', 'url', '路径'],
                             ['select', 'url_type', '路径类型', '(内置路径：从列表页点击查看内置路径)',$url_name],
                             ['radio','url_level', '路由等级', '(一级路由：多为底部导航，二级路由：通常为部分页面里的子页面)', ['1' => '一级路由', '2' => '二级路由'], '1'],
                             ['number', 'sort', '排序'],
                             ['radio', 'status', '显示状态', '', ['禁用', '启用']],
            ])
//            ->addSwitch('status', '显示状态')
                ->addImage('img_url', '图标')
                ->setFormData($info) // 设置表单数据
                ->fetch();
        }

    }


    public function menu($group = '')
    {
        $url_name = [
//            '0' => '不跳转',
'1' => '内部路径',
'2' => '外部链接',

        ];

        return ZBuilder::make('table')
            ->hideCheckbox()
            ->addColumns([ // 批量添加数据列
                           ['name', '名称'],
                           ['url_level', '路由等级'],
                           ['url_type', '路径类型',$url_name],
                           ['url', '路径'],
            ])
            ->setRowList(CustommenuModel::getMenuArr($group)) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    public function quickEdit($record = [])
    {
        $id = input('post.pk', '');
        $field = input('post.name', '');
        $value = input('post.value', '');
        $table = input('post.table', '');
        $type = input('post.type', '');
        if($field == 'status'){
            $value = $value == 'true' ? 1 : 0;
        }
        $status = CustommenuModel::where('id', $id)->update([$field=>$value,'update_time'=>time()]);
        if ($status){
            $this->success('操作成功');
        }$this->error('操作失败');
    }


    // 还原首页
    public function restore($group = '',$type='')
    {
        if (!empty($group)) {
            CustommenuModel::where('position', $group)->delete();
            $list = CustommenuModel::getMenuArr($group);
            foreach ( $list as $k => $v ) {
                $data = [
                    'position'      => $group,
                    'name'      => $v['name'],
                    'url'     => $v['url'],
                    'url_type'  => $v['url_type'],
                    'url_level' => $v['url_level'],
                    'default_icon' => $v['img_url'],
                    'is_default' => 1,
                ];
                CustommenuModel::create($data);
            }
            if(!$type){
                action_log('recover_home_menu', 'custommenu', 0, UID, '还原了'.$group.'导航');
                $this->success('还原成功');
            }
        } else {
            $this->error('还原失败');
        }
    }



    public function delete($record = [])
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids = (array)$ids;
        $result = Db::name('custom_menu')->where('id', 'in', $ids)->delete();
        if (false !== $result) {

            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }





}