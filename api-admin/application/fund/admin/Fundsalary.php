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
use app\money\model\Money;
use app\money\model\Record;
use app\common\service\UserService;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Fundsalary extends Admin
{

  /**
   * 首页
   * @return mixed
   */
  public function index()
  {

    // 获取查询条件
    $map   = $this->getMap();
    $map = (new UserService())->agentPartnerSearchMap($map,'o.uid');
    $map = (new UserService())->getAgentMap($map,'o.uid');
    $order = $this->getOrder();
    empty($order) && $order = 'id desc';
    $field = '*';
    // 数据列表
    $data_list = FundSalaryLog::where($map)
      ->field('o.*,m.name,m.mobile')
      ->alias('o')
      ->join('member m', 'm.id = o.uid', 'left')
      ->order($order)
      ->paginate();
      foreach ($data_list as &$v){
          $v['mobile'] = privacy_info_switch('mobile',$v['mobile']);
      }
    $infoBtn   = ['title' => '详情', 'icon' => 'fa fa-fw fa-th-list', 'href' => url('/fund/fund_salary_content/index', ['salary_id' => '__id__'])];
      $btn_privacy = [
          'title' => '查看隐私信息',
          'icon'  => 'fa fa-fw fa-refresh',
          'class'  => 'btn btn-info',
          'href'  => url('member/index/privacy'),
      ];
    return ZBuilder::make('table')
      ->setSearchArea([
        ['text:2', 'm.name', '用户名', 'like'],
        ['text:2', 'm.mobile', '手机号', 'like'],
        ['select:2', 'o.type', '工资类型', '', '', [1 => '团队分成', 2 => '周工资', 3 => '补贴']],
        ['select:2', 'o.info_type', '来源', '', '', [1 => '优投', 2 => '基金']],
        ['daterange', 'o.create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
          ['text:4', 'agent_search', '代理姓名/手机号/用户ID',''],
          ['text:4', 'partner_search', '合伙人姓名/手机号/用户ID',''],
      ])
      ->addOrder('id') // 添加排序
      ->addColumns([ // 批量添加数据列
                     ['id', 'id'],
                     ['uid', '用户id'],
                     ['mobile', '用户手机'],
                     ['name', '用户名称'],
                     ['type', '工资类型', ['', '团队分成', '周工资', '补贴']],
                     ['info_type', '来源', ['', '优投', '基金']],
                     ['price', '金额'],

                     ['create_time', '时间', 'datetime'],
                     ['desc', '描述'],
                     //['is_settle', '是否发放',['','已发放','未发放']],
                     //['settle_time', '发放时间', 'datetime'],
                     ['right_button', '操作', 'btn']
      ])->setTableName('fund')
      ->addTopButtons('add,delete') // 批量添加顶部按钮
        ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
                                    //->addTopButton('js',['title'=>'结算分成'])
      ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮
      ->addRightButton('custom', $infoBtn)
      ->addOrder('id')->setRowList($data_list) // 设置表格数据
      ->setColumnWidth('desc', 300)
      ->setColumnWidth('right_button', 160)
      ->setColumnWidth('settle_time,create_time', 140)
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
      $data['is_admin'] = 1;
      if (FundSalaryLog::create($data)) {
          
          //添加奖金
          $uid = $data['uid'];
          $mType = 93;
          $desc = "后台添加-[{$data['desc']}]";
          if ($data['type'] == 2) {
              $mType = 92;
          }
          if ($data['type'] == 3) {
              $mType = 91;
          }
          $user_balance = Money::getMoney($uid);
          $info         = $desc;
          $money        = $data['price'] * 100;
          Money::where('mid', $uid)->setInc('account', $money);
          $account = $user_balance['account'] + $money;
          $obj     = ['affect' => $money, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
          Record::saveData($uid, $money, $account, $mType, $info, '', '', $obj);
          
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

