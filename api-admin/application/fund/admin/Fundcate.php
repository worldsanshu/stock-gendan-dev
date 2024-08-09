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
use think\facade\Cache;
use think\Db;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Fundcate extends Admin
{
  /**
   * 首页
   * @return mixed
   */
  public function index()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map   = $this->getMap();
    $order = $this->getOrder();
    empty($order) && $order = 'id desc';
    // 数据列表
    $data_list = Db::name('fund_cate')->where($map)->order($order)->paginate();
    // 分页数据
    $page = $data_list->render();
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    return ZBuilder::make('table')->setSearch(['name' => '名称']) // 设置搜索框
    ->addColumns([ // 批量添加数据列
                   ['id', 'ID'],
                   ['name', '名称'],
                   ['right_button', '操作', 'btn']
    ])->setTableName('member')->addTopButtons('add', 'enable,disable,delete') // 批量添加顶部按钮
    ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮
    ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
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
      // 表单数据
      $data = input();
        $info = Db::name('fund_cate')->where('name', $data['name'])->find();
        if($info){
            $this->error('名称已存在');
        }
      if (Db::name('fund_cate')->insert($data)) {
        $this->success('新增成功', 'index');
      } else {
        $this->error('新增失败');
      }
    }
    return ZBuilder::make('form')->setPageTitle('新增') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['text', 'name', '名称', '']
    ])
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
        $info_type = Db::name('fund_cate')->where('name', $data['name'])->find();
        if ($info_type) {
            if ($info_type['id'] != $data['id']) {
                $this->error('名称已存在');
            }
        }
      if (Db::name('fund_cate')->update($data)) {
        $this->success('编辑成功', cookie('__forward__'));
      } else {
        $this->error('编辑失败');
      }
    }
    $info = Db::name('fund_cate')->where('id', $id)->find();
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['text', 'name', '名称', ''],
    ])
      ->setFormData($info) // 设置表单数据
      ->fetch();
  }

  public function delete($record = [])
  {
    $table_name = input('param.table');
    $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $ids        = (array)$ids;
    $field      = input('param.field', 'is_del');

    $map[]  = ['id', 'in', $ids];
    $result = Db::name('fund_cate')->where($map)->delete();
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
  public function setStatus($type = '', $record = [])
  {
    $table_name    = input('param.table');
    $ids           = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $member_id     = is_array($ids) ? '' : $ids;
    $member_status = Db::name('fund_cate')->where('id', 'in', $ids)->column('status');
    return parent::setStatus($type, ['member_' . $type, 'member', $member_id, UID, implode('、', $member_status)]);
  }

  public function quickEdit($record = [])
  {
    $id     = input('post.pk', '');
    $field  = input('post.name', '');
    $value  = input('post.value', '');
    $table  = input('post.table', '');
    $status = Db::name('fund_cate')->where('id', $id)->value($field);
    // $status = Db::name('member')->where('id', $id)->value($field);
    $details = '字段(' . $field . ')，原值(' . $status . ')，新值：(' . $value . ')';
    return parent::quickEdit(['member_edit', 'member', $id, UID, $details]);
  }
}

