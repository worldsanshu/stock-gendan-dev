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
namespace app\ipres\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;

use app\ipres\model\IpRes as IpResModel;
use think\facade\Request;

/**
 * IP白名单控制器
 * @package app\member\admin
 */
class Index extends Admin
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取当前请求对象
        $request = Request::instance();

// 获取域名
        $domain = $request->domain();


        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        $field = '*';
        // 数据列表
        $data_list =IpResModel::where($map)->field($field)->order($order)->paginate();

        // 分页数据
        $page = $data_list->render();

        $qurl=$domain.'/ipset?password=123456';
        return ZBuilder::make('table')
            ->setSearch(['ip' => 'IP']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['ip', 'IP'],
                ['create_time', '时间', 'datetime'],   ['right_button', '操作', 'btn']
                ])
            ->setTableName('ip_res')
            ->addTopButton('add')
                ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
                ->addRightButtons('delete')
            ->setPageTips('可以在后台添加，可以复制链接给需要打开的用户快捷录入IP，密码为:123456,链接为：'.$qurl, 'warning')
            ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();


            if ($IpResModel = IpResModel::create($data)) {

                // 记录行为
                action_log('IpResModel_add', 'IpResModel', $IpResModel['id'], UID);
                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                             ['text', 'ip', 'IP', '必填，可由英文字母、数字组成'],

            ])
            ->fetch();
    }


}

