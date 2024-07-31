<?php

namespace app\admin\controller;

use app\admin\model\Domain as DomainModel;
use app\common\builder\ZBuilder;
use think\Db;

class Domain extends Admin
{
    /**
     * 域名管理首页
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        // 数据列表
        $data_list = DomainModel::where($map)->order('id desc')->paginate();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('域名管理') // 设置页面标题
            ->setSearch(['domain' => '域名']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                           ['id', 'ID'],
                           ['type', '域名类型', 'status', '', [1 => '主域名', 2 => '业务域名']],
                           ['domain', '域名'],
                           ['rootdomain', '一级域名'],
                           ['is_abnormal', '是否异常情况'],
                           ['status', '状态', 'switch'],
                           ['addtime', '创建时间', 'datetime'],
                           ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons('edit,delete') // 批量添加右侧按钮

            ->setRowList($data_list)         // 设置表格数据
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

            // 验证
            $result = $this->validate($data, 'Domain.add');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            $parsedUrl = parse_url($data['domain']);
            if (!$parsedUrl['scheme']) {
                $data['domain'] = 'https://' . $data['domain'];
            }

            $data['addtime']    = time();
            $data['rootdomain'] = getPrimaryDomain($data['domain']);
            if ($Domain = DomainModel::create($data)) {

                // 记录行为
                action_log('Domain_add', 'admin_system_domain', $Domain['id'], UID);
                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                             ['radio', 'type', '域名类型', '根域名是获取业务域名使用，建议用备案域名；业务域名是用于请求业务的，容易被封，任意域名都可以', [1 => '主域名', 2 => '业务域名'], 2],
                             ['text', 'domain', '域名', '填写域名'],
                             ['text', 'note', '备注', '方便识别记录'],

            ])
            ->fetch();
    }

    /**
     * 编辑
     *
     * @param null $id 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();

            // 验证
            $result = $this->validate($data, 'Domain.add');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            $data['rootdomain'] = getPrimaryDomain($data['domain']);
            if (DomainModel::update($data)) {

                // 记录行为
                action_log('Domain_edit', 'admin_system_domain', $data['id'], UID);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        // 获取数据
        $info = DomainModel::where('id', $id)->find();
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                             ['radio', 'type', '域名类型', '根域名是获取业务域名使用，建议用备案域名；业务域名是用于请求业务的，容易被封，任意域名都可以', [1 => '主域名', 2 => '业务域名'], 2],
                             ['text', 'domain', '域名', '填写一级域名就可以，比如baidu.com，不需要带www或者其他的前缀，如（www.baidu.com）是错误的会导致域名无法正常访问'],

                             ['text', 'note', '备注', '方便识别记录'],

                             ['radio', 'status', '状态', '', ['禁用', '启用']]
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    /**
     * 域名管理首页
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function log()
    {
        // 查询
        $map = $this->getMap();
        // 数据列表
        $data_list = Db::name('admin_domain_abnormallog')->where($map)->order('id desc')->paginate();

        $btn_access = [
            'title'      => '处理域名',
            'icon'       => 'fa fa-fw fa-key',
            'class'      => 'btn btn-xs btn-default ajax-get confirm',
            'href'       => url('access', ['id' => '__id__']),
            'data-title' => '处理问题域名',
            'data-tips'  => '确定域名处理了吗'
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('域名管理') // 设置页面标题
            ->setSearch(['domain' => '域名']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                           ['id', 'ID'],
                           ['domain', '域名'],
                           ['zone', '地区'],
                           ['username', '用户'],
                           ['ip', 'IP'],
                           ['UA', 'UA'],
                           ['status', '处理状态', 'yesno'],
                           ['addtime', '创建时间', 'datetime'],
                           ['right_button', '操作', 'btn']
            ])
            ->addRightButton('custom', $btn_access) // 添加授权按钮
            ->hideCheckbox()
            ->setColumnWidth([
                'id'     => 40,
                'domain' => 130,
                'zone'   => 100,
                'ip'     => 100, 'UA' => 300,
            ])
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    /**
     * Desc : 处理有问题的域名
     * Date : 2024-06-28 23:27
     */
    public function access($id)
    {

        Db::name('admin_domain_abnormallog')->where('id', $id)->update(['status' => 1]);
        $this->success('已处理', cookie('__forward__'));
    }

    /**
     * 删除用户
     *
     * @param array $ids 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function delete($ids = [])
    {

        return $this->setStatus('delete');
    }

    /**
     * 启用用户
     *
     * @param array $ids 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function enable($ids = [])
    {

        return $this->setStatus('enable');
    }

    /**
     * 禁用用户
     *
     * @param array $ids 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function disable($ids = [])
    {

        return $this->setStatus('disable');
    }

    /**
     * 设置用户状态：删除、禁用、启用
     *
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function setStatus($type = '', $record = [])
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $uid_delete = is_array($ids) ? '' : $ids;
        return parent::setStatus($type, ['domain_' . $type, 'admin_domain', $uid_delete, UID, '']);
    }

    /**
     * 快速编辑
     *
     * @param array $record 行为日志
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $config  = DomainModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $config . ')，新值：(' . $value . ')';
        return parent::quickEdit(['user_edit', 'admin_user', $id, UID, $details]);
    }

}