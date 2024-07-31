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
use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundOrder as FundOrderModel;
use app\fund\model\Trader as TraderModel;
use app\money\model\Money;
use app\money\model\Record;
use app\user\model\User as AdminUserModel;
use think\Db;
use think\facade\Cache;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Fundorder_bak extends Admin
{
  /**
   * 待确认
   * @return mixed
   */
  public function index()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map        = $this->getMap();
    $order      = $this->getOrder();
    $admin_user = AdminUserModel::where('id', UID)->find();
    if ($admin_user['role'] == 2) {
      $map[] = ['agent_id', '=', $admin_user['for_user']];
    }
    $page = input('page', 1);
    empty($order) && $order = 'id desc';
    // 数据列表
    $map[] = ['o.type', 'eq', 1];
    $map[] = ['o.status', 'eq', 0];

    $res       = FundOrderModel::getFollowList($page, $map, $order);
    $data_list = $res['list'] ?? [];
    foreach ($data_list as $key => $value) {
      $email                            = $value['email'] ?: '--';
      $mobile                           = $value['mobile'] ?: '--';
      $data_list[$key]['fund']          = "<p>" . $value['name'] . "</p><p>" . $value['code'] . "</p>";
      $str                              = "<p>" . $value['trader'] . "</p><p>" . $value['commission'] . "</p>";
      $data_list[$key]['username_text'] = "<p>" . $value['uid'] . "</p><p>" . $value['username'] . "</p>";
      $data_list[$key]['user_info']     = "<p>" . $email . "</p><p>" . $mobile . "</p>";
      $data_list[$key]['trader_info']   = $str;
      if ($value['order_type'] == 3) {
        $data_list[$key]['cycle_text'] = "<p>" . $value['cycle'] . '天' . "</p><p>" . $value['settlement_days'] . '天' . "</p>";
      } else {
        $data_list[$key]['cycle_text'] = "无";
      }
    }
    if (empty($map['create_time'][1][0])) {
      $beginday = date('Ymd', time() - 2592000);//30天前
    } else {
      $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
    }
    if (empty($map['create_time'][1][1])) {
      $endday = date('Ymd', time());
    } else {
      $endday = date('Ymd', strtotime($map['create_time'][1][1]));
    }
    // 分页数据
    $page = $data_list->render();
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    $btn_detail = ['title' => '收益明细', 'icon' => 'fa fa-fw fa-cny', 'href' => url('detail', ['order_id' => '__id__'])];
// 批量跟买按钮
    $btn_access = [
      'title' => '批量审核',
      'icon'  => 'fa fa-fw fa-key',
      'class' => 'btn btn-primary  js-get',
      'href'  => url('accessdata') . '?code=1'
    ];
    return ZBuilder::make('table')->setSearch(['o.name' => '名称', 'o.order_sn' => '订单号', 'o.mobile' => '手机号码', 'o.email' => '邮箱']) // 设置搜索框
    ->addColumns([ // 批量添加数据列
                   ['id', 'ID'],
                   ['fund', '基金'],
                   ['mobile', '用户ID/手机号码'],
                   ['code', '名称/代码'],
                   ['order_sn', '订单号'],
                   ['trader_info', '导师/导师佣金比例（%）'],
                   ['username_text', '用户ID/用户名称'],
                   ['user_info', '邮箱/手机号码'],
                   ['money', '购买金额'],
                   ['cycle_text', '周期/已结算天数'],
                   ['status', '状态', ['待确认', '已确认']],
                   ['balance', '持有金额'],
                   ['order_type', '订单类型', [1 => '普通订单', 2 => '每日优投', 3 => 'vip优投']],
                   ['settlement_time', '结算时间', 'datetime'],
                   ['create_time', '买入时间', 'datetime'],
                   ['right_button', '操作', 'btn']
    ])->setTableName('FundOrder')
      ->addTimeFilter('o.create_time', [$beginday, $endday]) // 添加时间段筛选
                                                             //            ->addRightButton('custom', $btn_detail) // 批量添加右侧按钮

      ->addRightButton('edit', ['href' => url('accessdata', ['ids' => '__id__', 'group' => 'index'])])
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
      ->addTopButton('custom', $btn_access) // 添加批量买入处理按钮
                                            //            ->addTopButton('custom', $dqr_access) // 添加批量驳回处理按钮
                                            //            ->addFilterMap('id', ['code' => '0']) // 只获取group等于cms的id字段信息
      ->addTimeFilter('o.create_time') // 添加时间段筛选
                                       //            ->addFilter('status', ['待确认', '已确认'])
                                       //            ->fixedLeft(2)
                                       //            ->fixedRight(1)
      ->setPages($page)                // 设置分页数据
      ->fetch(); // 渲染模板
  }

  /**
   * 买入
   * @return mixed
   */
  public function buy()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map   = $this->getMap();
    $order = $this->getOrder();
    $page  = input('page', 1);
    empty($order) && $order = 'id desc';
    // 数据列表
    $map[]     = ['o.type', 'eq', 1];
    $map[]     = ['o.status', 'eq', 1];
    $res       = FundOrderModel::getFollowList($page, $map, $order);
    $data_list = $res['list'] ?? [];
    foreach ($data_list as $key => $value) {
      $data_list[$key]['mobile'] = "<p>UID:" . $value['uid'] . "</p><p>" . $value['mobile'] . "</p>";
      $data_list[$key]['code']   = "<p>" . $value['name'] . "</p><p>" . $value['code'] . "</p>";
      if ($value['code'] == "0") {
        $data_list[$key]['codetype'] = "股市跟投订单";
      } else {
        $data_list[$key]['codetype'] = "新股订单";
      }
    }
    // 分页数据
    $page = $data_list->render();
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    $btn_detail          = ['title' => '收益明细', 'icon' => 'fa fa-fw fa-cny', 'href' => url('detail', ['order_id' => '__id__'])];
    $btn_SettlementOrder = ['title' => '结算订单', 'icon' => 'fa fa-fw fa-cny', 'href' => url('detail', ['order_id' => '__id__'])];

    return ZBuilder::make('table')->setSearch(['o.name' => '名称', 'o.order_sn' => '订单号', 'o.mobile' => '手机号码', 'o.email' => '邮箱']) // 设置搜索框
    ->addColumns([ // 批量添加数据列
                   ['mobile', '用户ID/手机号码'],
                   ['codetype', '订单类型'],
                   ['order_sn', '订单号'],

                   ['money', '合约金额'],
                   //        ['share_num', '总份额'],
                   ['balance', '持有金额'],
                   ['fund_contract', '合约天数'],
                   ['fundendtime', '结束时间'],
                   ['remaining_share', '剩余份额'],
                   ['hold_money', '持有金额'],
                   ['hold_income', '持有收益'],
                   ['earning_rate', '收益率'],
                   ['settlement_time', '结算时间', 'datetime'],
                   ['create_time', '买入时间', 'datetime'],
                   ['status', '状态', ['待确认', '已确认']],
                   ['right_button', '操作', 'btn']
    ])->setTableName('FundOrder')
      ->addRightButton('custom', $btn_detail) // 批量添加顶部按钮
      ->addRightButton('custom', $btn_SettlementOrder) // 批量添加顶部按钮

      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
                                                      //            ->addTopButton('custom', $btn_access) // 添加批量买入处理按钮
                                                      //            ->addTopButton('custom', $dqr_access) // 添加批量驳回处理按钮
                                                      //            ->addFilterMap('id', ['code' => '0']) // 只获取group等于cms的id字段信息
      ->addTimeFilter('o.create_time') // 添加时间段筛选
      ->addFilter('status', [0 => '待确认', 1 => '已确认', 3 => '已结束'])
                                       //            ->fixedLeft(2)
//            ->fixedRight(1)
      ->fetch(); // 渲染模板
  }

  /**
   * 买入
   * @return mixed
   */
  public function detail()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $page     = input('page', 1);
    $order_id = input('order_id', '');
    $order    = 'id desc';
    // 数据列表
    $data_list = Db('fund_income_log')->where('order_id', $order_id)->where('status', 1)->where('order_type', 1)->order($order)->paginate();
    // 分页数据
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    return ZBuilder::make('table')
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['name', '基金名称'],
                     ['code', '基金代码'],
                     ['money', '收益金额'],
                     ['rebate', '收益率（%）'],
                     ['type', '类型', [1 => '交易日收益', 2 => '周工资', 3 => '合伙人补贴', 4 => '分销奖励']],
                     ['create_time', '时间', 'datetime'],
      ])->setTableName('fund_income_log')
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
      ->fetch(); // 渲染模板
  }

  /**
   * 分销返佣
   * @return mixed
   */
  public function detail2()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $page     = input('page', 1);
    $order_id = input('order_id', '');
    $order    = 'id desc';
    // 获取查询条件
    $map = $this->getMap();
    if (empty($map['create_time'][1][0])) {
      $beginday = date('Ymd', time() - 2592000);//30天前
    } else {
      $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
    }
    if (empty($map['create_time'][1][1])) {
      $endday = date('Ymd', time());
    } else {
      $endday = date('Ymd', strtotime($map['create_time'][1][1]));
    }
    $admin_user = AdminUserModel::where('id', UID)->find();
    if ($admin_user['role'] == 2) {
      $map[] = ['agent_id', '=', $admin_user['for_user']];
    }
    // 数据列表
    $data_list = Db('fund_income_log')->where($map)->where('type', 4)->order($order)->paginate();
    // 分页数据
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    return ZBuilder::make('table')
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['name', '基金名称'],
                     ['code', '基金代码'],
                     ['username', '姓名'],
                     ['mobile', '手机号'],
                     ['offline_account', '下线账号'],
                     ['money', '收益金额'],
                     ['rebate', '分佣比例（%）'],
                     ['level', '层级'],
                     ['type', '类型', [1 => '交易日收益', 2 => '周工资', 3 => '合伙人补贴', 4 => '分销奖励']],
                     ['create_time', '时间', 'datetime'],
      ])->setTableName('member')
      ->addTimeFilter('create_time', [$beginday, $endday]) // 添加时间段筛选
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
      ->fetch(); // 渲染模板
  }

  /**
   * 分销返佣
   * @return mixed
   */
  public function detail3()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map      = $this->getMap();
    $page     = input('page', 1);
    $order_id = input('order_id', '');
    if (empty($map['create_time'][1][0])) {
      $beginday = date('Ymd', time() - 2592000);//30天前
    } else {
      $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
    }
    if (empty($map['create_time'][1][1])) {
      $endday = date('Ymd', time());
    } else {
      $endday = date('Ymd', strtotime($map['create_time'][1][1]));
    }
    $admin_user = AdminUserModel::where('id', UID)->find();
    if ($admin_user['role'] == 2) {
      $map['agent_id'] = $admin_user['for_user'];
    }
    $order = 'id desc';
    // 数据列表
    $data_list = Db('fund_income_log')->where($map)->where('type', 3)->order($order)->paginate();
    // 分页数据
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    return ZBuilder::make('table')
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['username', '姓名'],
                     ['mobile', '手机号'],
                     ['money', '分佣金额'],
                     ['type', '类型', [1 => '交易日收益', 2 => '周工资', 3 => '合伙人补贴', 4 => '分销奖励']],
                     ['create_time', '时间', 'datetime'],
      ])->setTableName('member')
      ->addTimeFilter('create_time', [$beginday, $endday]) // 添加时间段筛选
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
      ->fetch(); // 渲染模板
  }

  /**
   * 买入
   * @return mixed
   */
  public function weekBonus()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map   = $this->getMap();
    $map[] = ['f.order_type', 'egt', 2];
    $order = 'id desc';
    if (empty($map['create_time'][1][0])) {
      $beginday = date('Ymd', time() - 2592000);//30天前
    } else {
      $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
    }
    if (empty($map['create_time'][1][1])) {
      $endday = date('Ymd', time());
    } else {
      $endday = date('Ymd', strtotime($map['create_time'][1][1]));
    }
    $admin_user = AdminUserModel::where('id', UID)->find();
    if ($admin_user['role'] == 2) {
      $map[] = ['agent_id', '=', $admin_user['for_user']];
    }

    // 数据列表
    $data_list = Db('fund_income_log')->view('fund_income_log f', true)
      ->view('member m', 'mobile,email', 'm.id=f.uid', 'LEFT')
      ->where($map)->order($order)->paginate();
    // 分页数据
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    $btn_edit = ['title' => '编辑', 'icon' => 'fa fa-fw fa-pencil', 'href' => url('editBonus', ['id' => '__id__'])];

    return ZBuilder::make('table')
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['uid', '用户ID'],
                     ['name', '基金名称'],
                     ['code', '基金代码'],
                     ['username', '姓名'],
                     ['mobile', '手机号'],
                     ['offline_account', '下线账号'],
                     ['money', '收益金额'],
                     ['rebate', '分佣比例（%）'],
                     ['level', '层级'],
                     ['order_type', '类型', [1 => '交易日收益', 2 => '周工资', 3 => '合伙人补贴', 4 => '分销奖励']],
                     ['status', '状态', ['未发放', '已发放']],
                     ['money', '金额'],
                     ['create_time', '时间', 'datetime'],
                     ['right_button', '操作', 'btn']
      ])->setTableName('fund_income_log')
//          ->addColumn('type', '状态', 'status', '', [1 => '交易日收益', 2 => '周工资', 3 => '合伙人补贴', 4 => '分销奖励'])
      ->addTimeFilter('f.create_time') // 添加时间段筛选
      ->addOrder('id,status')->setRowList($data_list) // 设置表格数据

      ->addRightButton('custom', $btn_edit)           // 批量添加右侧按钮
      ->fetch(); // 渲染模板
  }

  public function editBonus($id = null)
  {
    if ($id === null) $this->error('缺少参数');
    // 保存数据
    if ($this->request->isPost()) {
      $update_data = input();
      // 验证
      $data = $update_data;
      if ($data['status'] == 1) {
        if ($id) {
          $ids[] = $id;
          $this->grantWeekBonus($ids);
          $this->success('编辑成功', cookie('__forward__'));
        }
      } else {
        $this->error('编辑失败');
      }
    }
    $info = Db('fund_income_log')->where('id', $id)->find();
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['radio', 'status', '状态', '', ['未确认', '确认']],
    ])
      ->setFormData($info) // 设置表单数据
      ->fetch();
  }

  public function status($record = [])
  {
    return $this->setStatus('status');
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
    $table_name = input('param.table');
    $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    if ($ids) {
      $this->grantWeekBonus($ids);
    }
    $this->success('操作成功');
  }

  public function grantWeekBonus($ids = [])
  {
    $map = [];

    $map[] = ['id', 'in', $ids];
    $list  = Db('fund_income_log')->where($map)->select();
    foreach ($list as $key => $value) {
      if ($value['status'] == 1) {
        continue;
      }
      $user_balance = Money::getMoney($value['uid']);
      $bonus        = $value['money'];
      $info         = '周工资：收益金额：' . $bonus . '元';
      $bonus        = $bonus * 100;
      Money::where('mid', $value['uid'])->setInc('account', $bonus);
      $account = $user_balance['account'] + $bonus;
      $obj     = ['affect' => $bonus, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
      Record::saveData($value['uid'], $bonus, $account, 90, $info, '', '', $obj);
      $new_ids[] = $value['id'];
    }
    if ($new_ids) {
      $map = [];

      $map[] = ['id', 'in', $new_ids];
      Db('fund_income_log')->where($map)->update(['status' => 1]);
    }
    return;
  }

  /**
   * 卖出
   * @return mixed
   */
  public function sell()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map   = $this->getMap();
    $order = $this->getOrder();
    $page  = input('page', 1);
    empty($order) && $order = 'id desc';
    // 数据列表
    $map[]      = ['type', '=', 2];
    $admin_user = AdminUserModel::where('id', UID)->find();
    if ($admin_user['role'] == 2) {
      $map[] = ['agent_id', '=', $admin_user['for_user']];
    }
    if (empty($map['create_time'][1][0])) {
      $beginday = date('Ymd', time() - 2592000);//30天前
    } else {
      $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
    }
    if (empty($map['create_time'][1][1])) {
      $endday = date('Ymd', time());
    } else {
      $endday = date('Ymd', strtotime($map['create_time'][1][1]));
    }

    $res       = FundOrderModel::getList($page, $map, $order);
    $data_list = $res['list'] ?? [];
    foreach ($data_list as $key => $value) {
      $email                            = $value['email'] ?: '--';
      $mobile                           = $value['mobile'] ?: '--';
      $data_list[$key]['fund']          = "<p>" . $value['name'] . "</p><p>" . $value['code'] . "</p>";
      $str                              = "<p>" . $value['trader'] . "</p><p>" . $value['commission'] . "</p>";
      $data_list[$key]['username_text'] = "<p>" . $value['uid'] . "</p><p>" . $value['username'] . "</p>";
      $data_list[$key]['user_info']     = "<p>" . $email . "</p><p>" . $mobile . "</p>";
      $data_list[$key]['trader_info']   = $str;
      if ($value['order_type'] == 3) {
        $data_list[$key]['cycle_text'] = "<p>" . $value['cycle'] . '天' . "</p><p>" . $value['settlement_days'] . '天' . "</p>";
      } else {
        $data_list[$key]['cycle_text'] = "无";
      }
    }
    // 分页数据
    $page = $data_list->render();
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    $btn_edit = ['title' => '编辑', 'icon' => 'fa fa-fw fa-pencil', 'href' => url('editsell', ['id' => '__id__'])];
    return ZBuilder::make('table')->setSearch(['name' => '名称', 'order_sn' => '订单号', 'mobile' => '手机号码', 'email' => '邮箱'])
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['name', '名称'],
                     ['code', '代码'],
                     ['order_sn', '订单号'],
                     ['trader_info', '导师/导师佣金比例（%）'],
                     ['username_text', '用户ID/用户名称'],
                     ['user_info', '邮箱/手机号码'],
                     ['cycle_text', '周期/已结算天数'],
                     ['money', '卖出金额'],
                     ['order_type', '订单类型', [1 => '普通订单', 2 => '每日优投', 3 => 'vip优投']],
                     //            ['remaining_share', '剩余份额'],
                     ['status', '状态', ['待确认', '已确认']],
                     //            ['fund_contract', '合约天数'],
                     //            ['earning_rate', '收益率'],
                     ['create_time', '卖出时间', 'datetime'],
                     ['account_time', '预计到账时间', 'datetime'],
                     ['right_button', '操作', 'btn']
      ])->setTableName('member')
//          ->addTimeFilter('create_time') // 添加时间段筛选
      ->addRightButton('custom', $btn_edit)
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
      ->fetch(); // 渲染模板
  }

  /***
   * 审核确认操作
   * access
   **/
  public function accessdata($ids = null)
  {
    // 保存数据
    if ($this->request->isPost()) {
      $data      = input();
      $orderlist = explode(',', $data['orderlist']);
      foreach ($orderlist as $v => $K) {
        FundOrderModel::where('id', $K)->update(['status' => $data['status']]);
      }
      $this->success('编辑成功', cookie('__forward__'));
    }
    $ids  = (array)$ids;
    $defu = 1;
    return ZBuilder::make('form')->setPageTitle('订单审核') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['tags', 'orderlist', '订单ID', '', implode(',', $ids)],
                     ['radio', 'status', '购买状态', '审核通过后将进入需要买入状态', ['未确认', '确认'], $defu],
    ])
      ->setTrigger('status', '0', 'note')
//            ->setTrigger('status', '1', 'porductlist,buyprice,buytime')
      ->fetch();
  }

  /**
   * 每日下单操作
   * @return void
   */
  public function daybuy()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map   = $this->getMap();
    $order = $this->getOrder();
    $page  = input('page', 1);
    empty($order) && $order = 'id desc';
    $map[]     = ['o.status', 'eq', 0];
    $res       = FundDaylineModel::getList($page, $map, $order);
    $data_list = $res['list'] ?? [];
    // 分页数据
    $page       = $data_list->render();
    $btn_access = [
      'title' => '批量下单',
      'icon'  => 'fa fa-fw fa-key',
      'class' => 'btn btn-primary  js-get',
      'href'  => url('access') . '?code=1'
    ];
    return ZBuilder::make('table')
      ->setSearch(['m.mobile' => '手机号', 'fo.order_sn' => '订单号']) // 设置搜索框
      ->addColumns([ // 批量添加数据列
                     ['uid', '用户ID'],
                     ['mobile', '手机号码'],
                     ['sn', '订单号'],
                     ['order_id', '订单ID'],
                     ['date', '股票交易日期'],
                     ['create_time', '添加时间', 'datetime'],
                     ['right_button', '操作', 'btn']
      ])
      ->setPrimaryKey('id')
      ->addRightButton('edit', ['href' => url('access', ['ids' => '__id__'])])
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
      ->addTopButton('custom', $btn_access)           // 添加批量买入处理按钮
      ->fetch(); // 渲染模板
  }

  /**
   * 每日下单记录--结算记录
   * @return void
   */
  public function daybuyrecord()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map = $this->getMap();
//        if($map[0][0]=='status'){
//            $map[] = ['o.status',$map[0][1], $map[0][2]];
//        }
    $order = $this->getOrder();
    $page  = input('page', 1);
    empty($order) && $order = 'id desc';
    $map[]     = ['o.status', 'in', '1,2,3'];
    $res       = FundDaylineModel::getList($page, $map, $order);
    $data_list = $res['list'] ?? [];
    // 分页数据
    $page = $data_list->render();
    // 批量卖出按钮
    $btn_access = [
      'title' => '批量卖出',
      'icon'  => 'fa fa-fw fa-key',
      'class' => 'btn btn-primary  js-get',
      'href'  => url('daybuyrecordsell') . '?code=1'
    ];
    $dqr_access = [
      'title' => '批量结算',
      'icon'  => 'fa fa-fw fa-key',
      'class' => 'btn btn-primary  js-get',
      'href'  => url('accessdata') . '?code=1'
    ];
    $dqr_access = [
      'title' => '批量结算',
      'icon'  => 'fa fa-fw fa-key',
      'class' => 'btn btn-primary ajax-post confirm',
      'href'  => url('settlementorder')
    ];
    return ZBuilder::make('table')
//          ->setTableName('fund_dayline') // 设置数据表名
      ->setSearch(['m.mobile' => '手机号', 'fo.order_sn' => '订单号', 'o.stockcode' => '股票代码']) // 设置搜索框
      ->addColumns([ // 批量添加数据列
                     //                ['uid', '用户ID'],
                     ['mobile', '手机号码'],
                     ['stockname', '股票名称'],
                     ['stockcode', '股票代码'],
                     ['num', '数量'],
                     ['buy_time', '买入时间'],
                     ['buy_price', '买入价格'],
                     ['sell_time', '卖出时间'],
                     ['sell_price', '卖出价格'],
                     ['profit', '利润'],
                     ['buy_cost', '买入成本'],
                     ['sell_cost', '卖出成本'],
                     ['commission', '讲师分润'],
                     ['sn', '订单号'],
                     ['date', '股票交易日期'],
                     ['create_time', '生成时间', 'datetime'],
                     ['status', '状态', [1 => '已下单', 2 => '已卖出', 3 => '已结算']],
                     //                ['right_button', '操作', 'btn']
      ])
      ->setPrimaryKey('id')
      ->addRightButtons(['edit']) // 批量添加右侧按钮
      ->addOrder('id,is_del')
      ->setRowList($data_list) // 设置表格数据
      ->addTopButton('custom', $btn_access) // 添加批量买入处理按钮
      ->addTopButton('custom', $dqr_access) // 添加批量驳回处理按钮
      ->addTimeFilter('o.buy_time', '', '买入时间') // 添加时间段筛选
                                                    //            ->addTopButton('custom', $dqr_access) // 添加批量驳回处理按钮
      ->addFilter('status', [0 => '等待确认', 1 => '已下单', 2 => '已卖出', 3 => '已结算'])
      ->addFilterMap('status', 1)
      ->fetch(); // 渲染模板
  }

  /**
   * 批量卖出时间
   *
   * @param $id
   *
   * @return void
   */
  public function daybuyrecordsell($ids = null)
  {
    // 保存数据
    if ($this->request->isPost()) {
      $data = input();
      // 验证
      $result = $this->validate($data, 'Fund.daysell');
      // 验证失败 输出错误信息
      if (true !== $result) $this->error($result);

      if (!get_trading_time($data['selltime'])) {
        $this->error('卖出时间不在交易日');
      }
      foreach (explode(',', $data['orderlist']) as $v => $K) {
        $info = FundDaylineModel::where(['id' => $K])->find();
        $info = empty($info) ? $info : $info->toArray();
        if ($info['status'] == 1) {
          if (date("Y-m-d", strtotime($info['buy_time'])) == date("Y-m-d", strtotime($data['selltime']))) {
            $this->error('ID为' . $info['id'] . '买卖时间不能为同一天');
          }
          FundDaylineModel::where(['id' => $K])->update(['sell_time' => $data['selltime'], 'sell_price' => $data['sellprice'], 'status' => 2]);
        } else {
          $this->error('ID为' . $info['id'] . '订单状态不对不能操作，在列表中筛选状态后再操作');
        }
      }
      $this->success('操作成功', cookie('__forward__'));
    }
    $ids       = (array)$ids;
    $datainfo  = FundDaylineModel::where('id', $ids[0])->find();
    $stocklist = Db::name('stock_list')->where('status', 1)->select();
    $stock     = [];
    foreach ($stocklist as $k => $v) {
      $stock[$v['code']] = $v['title'];
    }
    $js = <<<EOF
            <script type="text/javascript">
                $('input[name="buytime"]')
                $('#selltime').on('dp.change', function(e){ 
                    var newDate = e.date; // 获取新选择的日期
                 
                    console.log(Date.parse(newDate._d));
                  
                       // 使用封装的函数构建URL，并发起AJAX请求
                       var url="/index/index/getstock?time="+Date.parse(newDate._d)/1000+'&stockcode='+$('#stockcode').val();
                        $.getJSON(url, function(result) {
                            // 使用更安全的方法处理结果，例如在这里可以对结果进行一些处理后再展示
                            // 为了遵守要求，这里不做结果展示的修改，仅假设对result进行了安全的处理
                            console.log(result);
                               console.log(result.data);
                                 $('input[name="sellprice"]').val(result.data)
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            // 添加失败处理机制，例如向用户显示一个错误消息
                            console.error(`Request failed:`+textStatus+errorThrown);
                        });
                });


            </script>
EOF;
    return ZBuilder::make('form')->setPageTitle('批量设置卖出时间') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['tags', 'orderlist', '订单ID', '', implode(',', $ids)],
                     ['static', 'stockname', '股票名称', '', $datainfo->stockname],
                     ['static', 'buy_time', '买入时间', '', $datainfo->buy_time],
                     ['static', 'buy_price', '买入价格', '', $datainfo->buy_price],
                     ['hidden', 'stockcode', $datainfo->stockcode],
                     ['datetime', 'selltime', '卖出时间', '操作卖出交易时间，便于跟市场行情对得上'],
                     ['number', 'sellprice', '卖出价格', '自动获取到的现价'],
    ])->setExtraJs($js)
      ->fetch();
  }

  public function daybuyrecordedit($id = null)
  {
    if ($id === null) $this->error('缺少参数');
    // 保存数据
    if ($this->request->isPost()) {
      $update_data = input();
      // 验证
      $data = $update_data;
      FundOrderModel::where('id', $id)->update(['cycle' => $data['cycle'], 'trader_id' => $data['trader_id']]);
      $this->success('编辑成功', cookie('__forward__'));
    }
    $info      = FundDaylineModel::where('id', $id)->find();
    $stocklist = Db::name('stock_list')->where('status', 1)->select();
    $stock     = [];
    foreach ($stocklist as $k => $v) {
      $stock[$v['code']] = $v['title'];
    }
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['select', 'porductlist', '股票', '', $stock],
                     ['number', 'buy_price', '买入价格'],
                     ['datetime', 'buy_time', '买入时间'],
    ])
      ->setFormData($info) // 设置表单数据
      ->fetch();
  }

  /**
   * 批量结算订单
   *
   * @param $ids
   *
   * @return void
   */
  public function settlementorder($ids = null)
  {
    $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    if (empty($ids)) {
      $this->error('请选择要操作的数据');
    }
    FundOrderModel::settlementFundgs($ids);
    $this->success('操作已提交', cookie('__forward__'));
  }

  /***
   * 每日购买确认
   * access
   **/
  public function access($ids = null)
  {
    // 保存数据
    if ($this->request->isPost()) {
      $data = input();
      // 验证
      $result = $this->validate($data, 'Fund.daybuy');
      // 验证失败 输出错误信息
      if (true !== $result) $this->error($result);
      if (!get_trading_time($data['buytime'])) {
        $this->error('买入时间不在交易时间内');
      }
      $stockname = Db::name('stock_list')->where('code', $data['porductlist'])->value('title');
      foreach (explode(',', $data['orderlist']) as $v => $K) {
        //获取该用户这个订单的信息余额；
        $FundDaylineinfo = FundDaylineModel::where(['id' => $K])->find();
        $FundOrderinfo   = FundOrderModel::where('id', $FundDaylineinfo['order_id'])->find();
        $FundDaylinelist = FundDaylineModel::where([
          ['uid', '=', $FundDaylineinfo['uid']],
          ['order_id', '=', $FundDaylineinfo['order_id']],
          ['status', 'in', '1,2']
        ])->select();
        $Usedamount      = 0;
        if ($FundDaylinelist) {
          foreach ($FundDaylinelist as $uv) {
            $Usedamount += $uv['num'] * $uv['buy_price'];
          }
        }
        if ($FundOrderinfo['money'] <= $Usedamount) {
          // 余额不足
          continue;
        }
        printlog($FundOrderinfo['money'], "可用总余额：", 'jiesuan');
        printlog($Usedamount, "已使用：", 'jiesuan');
        $balance = $FundOrderinfo['money'] - $Usedamount;
        printlog($balance, "可用余额：", 'jiesuan');
        $position = $data['position'] / 100;
        printlog($position, "持仓比例：", 'jiesuan');
        printlog(intval($FundOrderinfo['money'] * $position), "--：", 'jiesuan');
        printlog(intval($balance), "--：", 'jiesuan');
        if (intval($FundOrderinfo['money'] * $position) < intval($balance)) {
          printlog($FundOrderinfo['money'] * $position, "不够仓位比例：全仓：", 'jiesuan');
          // 如果仓位比例小于可以使用余额则满仓
          $num = $balance / $data['buyprice'];
          printlog($balance, "余额：", 'jiesuan');
          printlog($data['buyprice'], "市场价：", 'jiesuan');
          printlog($num, "能买的数量：", 'jiesuan');
          //这类订单可以理解是合买订单，所以不需要整手，不管是多少都可以购买
//                    $num = floor($num / 100) * 100;
          $num = floor($num);
          printlog($num, "向下操作后的数量：", 'jiesuan');
        } else {
          printlog($balance * $position, "仓位比例：跟仓比例：", 'jiesuan');
          $num = ($balance * $position) / $data['buyprice'];
          printlog($num, "能买的数量：", 'jiesuan');
          //这类订单可以理解是合买订单，所以不需要整手，不管是多少都可以购买
//                    $num = floor($num / 100) * 100;
          $num = floor($num);
          printlog($num, "向下操作后的数量：", 'jiesuan');
        }
        if ($num == 0) {
          $this->error($K . '订单可用余额不足，可操盘余额只有' . $balance);
        }
        FundDaylineModel::where(['id' => $K])->update([
          'stockname'        => $stockname,
          'stockcode'        => $data['porductlist'],
          'position'         => $data['position'],
          'num'              => $num,
          'commission_ratio' => $FundOrderinfo['commission'],
          'buy_time'         => $data['buytime'],
          'buy_price'        => $data['buyprice'],
          'status'           => 1
        ]);
      }
      $this->success('操作成功', cookie('__forward__'));
    }
    $ids       = (array)$ids;
    $stocklist = Db::name('stock_list')->where('status', 1)->select();
    $stock     = [];
    foreach ($stocklist as $k => $v) {
      $stock[$v['code']] = $v['title'];
    }
    $js = <<<EOF
            <script type="text/javascript">
           
                $('#buytime').on('dp.change', function(e){ 
                    var newDate = e.date; // 获取新选择的日期
                 
                    console.log(Date.parse(newDate._d));
                  
                       // 使用封装的函数构建URL，并发起AJAX请求
                       var url="/index/index/getstock?time="+Date.parse(newDate._d)/1000+'&stockcode='+$('#porductlist').val();
    $.getJSON(url, function(result) {
        // 使用更安全的方法处理结果，例如在这里可以对结果进行一些处理后再展示
        // 为了遵守要求，这里不做结果展示的修改，仅假设对result进行了安全的处理
        console.log(result);
           console.log(result.data);
             $('input[name="buyprice"]').val(result.data)
    }).fail(function(jqXHR, textStatus, errorThrown) {
        // 添加失败处理机制，例如向用户显示一个错误消息
        console.error(`Request failed:`+textStatus+errorThrown);
    });
                });

            // http://www.a1.com/index/index/getstock?stockcode=00006&time=2
            </script>
EOF;
    return ZBuilder::make('form')->setPageTitle('批量跟买') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['tags', 'orderlist', '订单ID', '', implode(',', $ids)],
                     ['select', 'porductlist', '股票', '', $stock],
                     ['datetime', 'buytime', '买入时间'],
                     ['number', 'buyprice', '买入价格'],
                     ['number', 'position', '买入仓位比例', '动态计算股数比例(%)，如果余额不足，则自动计算的则直接取最大值的整数', '100'],
    ])->setExtraJs($js)
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
      FundOrderModel::where('id', $id)->update(['cycle' => $data['cycle'], 'trader_id' => $data['trader_id']]);
      $this->success('编辑成功', cookie('__forward__'));
    }
    $info       = FundOrderModel::where('id', $id)->find();
    $Traderlist = [];
    $data_list  = TraderModel::where('status', 1)->select();
    foreach ($data_list as $v => $k) {
      $Traderlist[$k['id']] = $k['name'];
    }
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['number', 'cycle', '合约天数', '选择的10天，20天等合约天数'],
                     ['select', 'trader_id', '优投老师', '', $Traderlist],
                     //            ['number', 'settlement_days', '结算天数', ''],
    ])
      ->setFormData($info) // 设置表单数据
      ->fetch();
  }

  public function editsell($id = null)
  {
    if ($id === null) $this->error('缺少参数');
    // 保存数据
    if ($this->request->isPost()) {
      $update_data = input();
      // 验证
      $data = $update_data;
      if ($data['status'] == 1) {
        $map           = [];
        $map['id']     = $id;
        $map['status'] = 0;
        $list          = FundOrderModel::where($map)->select();
        FundOrderModel::autoUpdateSellStatus($list, 2);
        $this->success('编辑成功', cookie('__forward__'));
//                    $money = $order_data['money']*100;
//                    $uid = $order_data['uid'];
//                    Db('money')->where(['mid' => $uid])->setInc('account',$money);
      } else {
        $this->error('编辑失败');
      }
    }
    $info = FundOrderModel::where('id', $id)->find();
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['radio', 'status', '状态', '', ['未确认', '确认']],
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
    $result = FundorderModel::where($map)->delete();
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
//    public function setStatus($type = '', $record = []) {
//        $table_name = input('param.table');
//        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
//        $member_id = is_array($ids) ? '' : $ids;
//        $member_status = Fund::where('id', 'in', $ids)->column('status');
//        return parent::setStatus($type, ['member_' . $type, 'member', $member_id, UID, implode('、', $member_status) ]);
//    }
  public function quickEdit($record = [])
  {
    $id     = input('post.pk', '');
    $field  = input('post.name', '');
    $value  = input('post.value', '');
    $table  = input('post.table', '');
    $status = Fund::where('id', $id)->value($field);
    // $status = Db::name('member')->where('id', $id)->value($field);
    $details = '字段(' . $field . ')，原值(' . $status . ')，新值：(' . $value . ')';
    return parent::quickEdit(['member_edit', 'member', $id, UID, $details]);
  }
}

