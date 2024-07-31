<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
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
use app\fund\model\FundUserlevel as FundUserlevelModel;
use app\fund\model\FundUserlevelConfig as FundUserlevelConfigModel;

use think\Cache;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Funduserlevel extends Admin
{

  /**
   * 首页
   * @return mixed
   */
  public function index()
  {

    // 获取查询条件
    $map   = $this->getMap();
    $order = $this->getOrder();
    empty($order) && $order = 'level asc';
    $field = '*';
    // 数据列表
    $data_list = FundUserlevelModel::where($map)->field($field)->order($order)->paginate();

    return ZBuilder::make('table')
      ->addOrder('id') // 添加排序
      ->addColumns([ // 批量添加数据列
                                 ['name', '名称'],
                     ['level', '级别', $this->level_array],
                     ['min_num', '至少人数'],
                     ['max_num', '最高人数'],
                     ['ratio', '返佣比例'],
                     ['min_money', '最低投资金额'],
                     ['max_money', '最高投资金额'],
                     ['min_position_ratio', '最低仓位比例'],
                     ['max_position_ratio', '最高仓位比例'],
                     ['create_time', '时间', 'datetime'],
                     ['right_button', '操作', 'btn']
      ])

      ->setTableName('fund')->addTopButtons('add,delete') // 批量添加顶部按钮
      ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
      ->fetch(); // 渲染模板
  }

  /**
   * 新增
   * @return mixed
   * @author 路人甲乙
   */
  public function add()
  {
    // 保存数据
    if ($this->request->isPost()) {
      // 表单数据
      $data = input();
      if (FundUserlevelModel::create($data)) {
        $this->success('新增成功', 'index');
      } else {
        $this->error('新增失败');
      }
    }
    //基金列表

    return ZBuilder::make('form')->setPageTitle('新增') // 设置页面标题
    ->addSelect('level', '请选择级别', '请选择级别', $this->level_array)
      ->addFormItems([ // 批量添加表单项
                       //            ['text','name', '名称'],
                       ['text', 'min_num', '至少人数'],
                       ['text', 'max_num', '最高人数'],
                       ['text', 'num_work', '人数显示文案', '在页面会替代人数的显示'],
                       ['text', 'ratio', '返佣比例'],
                       ['number', 'min_position_ratio', '最低仓位比例','该等级老师投资的最小仓位比例，整10倍',10,10],
                       ['number', 'max_position_ratio', '最高仓位比例','该等级老师投资的最大仓位比例，整10倍',100,10],
                       ['text', 'min_money', '最低投资金额'],
                       ['text', 'max_money', '最高投资金额'],
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
      if (FundUserlevelModel::update($data)) {
        $this->success('编辑成功', url('/fund/funduserlevel/index'));
      } else {
        $this->error('编辑失败');
      }
    }
    $info = FundUserlevelModel::where('id', $id)->find();
    //基金列表
    $level_array = $this->level_array;
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['text', 'name', '名称'],
                     ['text', 'min_num', '至少人数'],
                     ['text', 'max_num', '最高人数'],
                     ['text', 'num_work', '人数显示文案', '在页面会替代人数的显示'],
                     ['text', 'ratio', '返佣比例'],
                     ['number', 'min_position_ratio', '最低仓位比例','该等级老师投资的最小仓位比例，整10倍',10,10],
                     ['number', 'max_position_ratio', '最高仓位比例','该等级老师投资的最大仓位比例，整10倍',100,10],
                                ['text', 'min_money', '最低投资金额'],
                                ['text', 'max_money', '最高投资金额'],
    ])
      ->addSelect('level', '请选择级别', '请选择级别', $level_array)
      ->setFormData($info) // 设置表单数据
      ->fetch();
  }

  public function delete($record = [])
  {
    $table_name = input('param.table');
    $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $ids        = (array)$ids;
    $field      = input('param.field', 'is_del');
    $map[]      = ['id', 'in', $ids];
    $result     = FundUserlevelModel::where($map)->delete();
    if (false !== $result) {

        $this->success('操作成功', url('/fund/funduserlevel/index'));
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
}

