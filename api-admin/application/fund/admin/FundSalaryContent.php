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
use app\fund\model\FundSalaryLog;
use app\member\model\Member;
use app\fund\model\FundSalaryLogContent;
use app\common\service\UserService;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class FundSalaryContent extends Admin
{

  /**
   * 首页
   * @return mixed
   */
  public function index()
  {
    $salary_id = input('salary_id', 0);
    // 获取查询条件
    $map = $this->getMap();
    $map = (new UserService())->agentPartnerSearchMap($map,'o.uid');
    $map = (new UserService())->getAgentMap($map,'o.uid');
    if (!empty($salary_id)) {
      $salary = FundSalaryLog::where('id', $salary_id)->find();
      $map[]  = ['o.uid', '=', $salary->uid];
      $map[]  = ['o.settle_time', '>=', $salary->create_time - 20];
      $map[]  = ['o.settle_time', '<=', $salary->create_time + 20];
      $map[]  = ['o.type', '=', $salary->type];
      $map[]  = ['o.info_type', '=', $salary->info_type];
    }
    $order = $this->getOrder();
    empty($order) && $order = 'o.id desc';
    $field = '*';
    // 数据列表
    $data_list = FundSalaryLogContent::where($map)
      ->field('o.*,m.name,m.mobile')
      ->alias('o')
      ->join('member m', 'm.id = o.uid', 'left')
      ->order($order)
      ->paginate();
    return ZBuilder::make('table')
      ->setSearchArea([
        ['text:2', 'm.name', '用户名', 'like'],
        ['text:2', 'm.mobile', '手机号', 'like'],
        ['select:2', 'o.type', '业绩类型', '', '', [1 => '团队', 2 => '直属']],
        ['select:2', 'o.info_type', '来源', '', '', [1 => '优投', 2 => '基金']],
        ['daterange', 'o.create_time', '业绩时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
        ['daterange', 'o.settle_time', '结算时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
          ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
          ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
      ])
      ->addOrder('o.id') // 添加排序
      ->addColumns([ // 批量添加数据列
                     ['id', 'id'],
                     ['uid', '用户id'],
                     ['mobile', '用户手机'],
                     ['name', '用户名称'],
                     ['type', '业绩类型', ['', '团队', '直属']],
                     ['info_type', '来源', ['', '优投', '基金']],
                     ['profit', '收益业绩'],
                     ['create_time', '时间', 'datetime'],
                     ['is_settle', '是否结算', ['', '已结算', '未结算']],
                     ['settle_time', '结算时间', 'datetime'],
                     ['desc', '描述'],
                     /* ['right_button', '操作', 'btn'] */
      ])->setTableName('fund')
      //->addTopButtons('add,delete') // 批量添加顶部按钮
      //->addTopButton('js',['title'=>'结算分成'])
      ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮
      ->addOrder('id')->setRowList($data_list) // 设置表格数据
      ->setColumnWidth('desc', 160)
      ->setColumnWidth('settle_time,create_time', 120)
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
      $data                = input();
      $data['is_settle']   = 1;
      $data['settle_time'] = time();
      if (FundSalaryLog::create($data)) {
        $this->success('新增成功', 'index');
      } else {
        $this->error('新增失败');
      }
    }
    return ZBuilder::make('form')->setPageTitle('新增') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['text', 'uid', '用户id'],
                     ['text', 'price', '金额'],
                     ['text', 'desc', '备注']
    ])
      ->addSelect('type', '工资类型', '请选择类型', ['', '团队分成', '周工资', '补贴'])
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
      if (FundSalaryLog::update($data)) {
        $this->success('编辑成功', 'index');
      } else {
        $this->error('编辑失败');
      }
    }
    $info = FundSalaryLog::where('id', $id)->find();

    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['text', 'uid', '用户id'],
                     ['text', 'price', '金额'],
                     ['text', 'desc', '备注']
    ])
      ->addSelect('type', '工资类型', '请选择类型', ['', '团队分成', '周工资', '补贴'])
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
    $result     = FundSalaryLog::where($map)->delete();
    if (false !== $result) {

      $this->success('操作成功', cookie('__forward__'));
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

