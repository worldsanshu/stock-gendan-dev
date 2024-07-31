<?php

namespace app\operate\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\operate\model\Prize as PrizeModel;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\bi\admin
 */
class Prize extends Admin
{
    /**
     * 奖品列表
     * @return mixed
     * @author 一片天 <404230497@qq.com>
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = Db::name('operate_prize')
          ->field("id,icon, name, type, winningrate,  winningratetype,  status,  status,isprize")
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              return $item;
          });
        // 分页数据
        $page = $data_list->render();
        !empty($_SERVER["QUERY_STRING"]) && $excel_url = URL("index_export") . '?' . $_SERVER["QUERY_STRING"];
        return ZBuilder::make('table')
          ->setSearch(['name' => '奖品名称'])// 设置搜索框
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['icon', '图片', 'picture'],
            ['name', '奖品名称'],
            ['winningrate', '中奖率'],
            ['type', '奖品类型', ['0' => '虚拟产品', '1' => '实物产品']],
            ['isprize', '中奖标识', ['0' => '不是奖品', '1' => '奖品']],
            ['winningratetype', '新用户是否100%中奖', ['0' => '--', '1' => '新用户必中']],
            ['status', '状态', 'switch'],
            ['right_button', '操作', 'btn']
          ])
          ->setTableName('operate_prize')
          ->addOrder('addtime')
          ->setRowList($data_list)// 设置表格数据
          ->addTopButtons('add,enable,disable,delete')// 批量添加顶部按钮
          ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']])// 批量添加右侧按钮
          ->fetch(); // 渲染模板
    }

    /**
     * 新增奖品设置
     *
     * @return mixed
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            // 验证
            $result = $this->validate($data, 'Prize');
            if (true !== $result) $this->error($result);
            if ($page = PrizeModel::create($data)) {
                // 记录行为
                action_log('page_add', 'cms_page', $page['id'], UID, $data);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['text', 'name', '奖品名称'],
            ['image', 'icon', '图标'],
            ['radio', 'isprize', '中奖标识', '', ['不是奖品', '是奖品']],
            ['radio', 'type', '奖品类型', '', ['虚拟奖品', '实物将奖品'], 1],
            ['number', 'winningrate', '中奖率', '单位为%，10%即输入10，1%输入1即可'],
            ['radio', 'winningratetype', '新用户必中', '注册时间在3天内容且没消费的用户视为新用户', ['否', '是'], 0],
            ['radio', 'status', '立即启用', '', ['停用', '启用'], 1]
          ])
          ->addHidden('addtime', time())
          ->fetch();
    }

    /**
     * 编辑
     * @param null $id 单页id
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            // 验证
            $result = $this->validate($data, 'Prize');
            if (true !== $result) $this->error($result);
            if (PrizeModel::update($data)) {
                // 记录行为
                action_log('Prize_edit', 'operate_prize', $id, UID, $data['name']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        $info = PrizeModel::get($id);
        // 显示编辑页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['hidden', 'id'],
            ['text', 'name', '奖品名称'],
            ['image', 'icon', '图标'],
            ['radio', 'isprize', '中奖标识', '', ['不是奖品', '是奖品']],
            ['radio', 'type', '奖品类型', '', ['虚拟奖品', '实物将奖品'], 1],
            ['number', 'winningrate', '中奖率', '单位为%，10%即输入10，1%输入1即可'],
            ['radio', 'winningratetype', '新用户必中', '注册时间在3天内容且没消费的用户视为新用户', ['否', '是'], 0],
            ['radio', 'status', '立即启用', '', ['停用', '启用'], 1]
          ])
          ->setFormdata($info)
          ->fetch();
    }
}