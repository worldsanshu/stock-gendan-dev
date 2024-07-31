<?php
// 2024年 6月6日  方便找回老逻辑  把以前的代码找出来，这个是老代码   ——bak文件是新代码

namespace app\fund\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\fund\model\Fund as FundModel;
use app\fund\model\FundOrder as FundOrderModel;
use app\money\model\Money;
use app\money\model\Record;
use app\user\model\User as AdminUserModel;
use think\Db;
use think\Hook;
use think\Cache;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Fundorder extends Admin
{

  /**
   * 买入
   * @return mixed
   */
  public function index()
  {

    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map   = $this->getMap();
    $order = $this->getOrder();

    $admin_user = AdminUserModel::where('id', UID)->find();

    if ($admin_user['role'] == 2) {
      $map['agent_id'] = $admin_user['for_user'];
    }

    $input = input();
    if ($input) {
//            ['name' => '名称','order_sn'=>'订单号','mobile'=>'手机号码','email'=>'邮箱']) // 设置搜索框
      if (isset($input['name']) && $input['name']) {
        $map[] = ['name', 'like', "%" . $input['name'] . "%"];
      }
      if (isset($input['mobile']) && $input['mobile']) {
        $map[] = ['mobile', 'like', "%" . $input['mobile'] . "%"];
      }
      if (isset($input['email']) && $input['email']) {
        $map[] = ['email', 'like', "%" . $input['email'] . "%"];
      }
      if (isset($input['order_no']) && $input['order_no']) {
        $map[] = ['order_sn', 'like', "%" . $input['order_no'] . "%"];
      }

      // 确保日期字符串被正确转换为时间戳
      $startTimestamp = isset($input['_filter_time_from']) ? strtotime($input['_filter_time_from']) : '';
      $endTimestamp   = isset($input['_filter_time_to']) ? strtotime($input['_filter_time_to'] . ' 23:59:59') : ''; // 添加23:59:59确保包括全天
// 结合起始和结束时间进行筛选
      if ($startTimestamp && $endTimestamp) {
        // 当开始和结束时间都设置时，同时应用两个条件
        $map[] = ['create_time', 'between', [$startTimestamp, $endTimestamp]];
      } elseif ($startTimestamp) {
        // 仅设置了开始时间
        $map[] = ['create_time', '>=', $startTimestamp];
      } elseif ($endTimestamp) {
        // 仅设置了结束时间
        $map[] = ['create_time', '<=', $endTimestamp];
      }
    }

    $page = input('page', 1);
    empty($order) && $order = 'id desc';
    // 数据列表
//        $map['type'] = ['eq',1];
    $map[] = ['type', '=', 1];
    $res   = FundOrderModel::getList($page, $map, $order);

    $data_list = $res['list'] ?? [];

    foreach ($data_list as $key => $value) {

      $email  = $value['email'] ?: '--';
      $mobile = $value['mobile'] ?: '--';

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

    $btn_detail     = ['title' => '收益明细', 'icon' => 'fa fa-fw fa-cny', 'href' => url('detail', ['order_id' => '__id__'])];
    $btn_accessdata = ['title' => '收益明细', 'icon' => 'fa fa-fw fa-cny', 'href' => url('accessdata', ['order_id' => '__id__'])];
    return ZBuilder::make('table')
//            ->setSearch(['name' => '名称','order_sn'=>'订单号','mobile'=>'手机号码','email'=>'邮箱']) // 设置搜索框
      ->setExtraHtmlFile('search', 'toolbar_top')
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['fund', '基金'],
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
      ])->setTableName('member')
//        ->addTimeFilter('create_time', [$beginday, $endday]) // 添加时间段筛选
      ->addRightButton('custom', $btn_detail) // 批量添加顶部按钮
      ->addRightButtons(['edit']) // 批量添加右侧按钮
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
                                                      //        ->addTopButtons('confirm',$btn_accessdata) // 批量添加顶部按钮
      ->addOrder('status')
      ->fetch(); // 渲染模板

  }

  /**
   * 设置用户状态：禁用、启用
   *
   * @param string $type 类型：enable/disable
   * @param array $record
   *
   * @return mixed
   * @author 路人甲乙 <4853332099@qq.com>
   */
  public function confirm()
  {
    $table_name    = input('param.table');
    $ids           = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $map           = [];
    $map['id']     = ['in', $ids];
    $map['status'] = ['eq', 0];
    $list          = FundOrderModel::where($map)->select();
    FundOrderModel::autoUpdateBuyStatus($list, 2);

    $this->success('操作成功');
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

    $order = 'id desc';
    // 数据列表
    $data_list = Db('fund_income_log')->where('order_id', $order_id)->order($order)->paginate();
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

    $order = 'id desc';
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
      $map['agent_id'] = $admin_user['for_user'];
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
   * @author 路人甲乙 <4853332099@qq.com>
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
    $map       = [];
    $map['id'] = ['in', $ids];
    $list      = Db('fund_income_log')->where($map)->select();

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

      $obj = ['affect' => $bonus, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];

      Record::saveData($value['uid'], $bonus, $account, 90, $info, '', '', $obj);

      $new_ids[] = $value['id'];

    }
    if ($new_ids) {
      $map       = [];
      $map['id'] = ['in', $new_ids];
      Db('fund_income_log')->where($map)->update(['status' => 1]);
    }
    return;
  }

  /**
   * 买入
   * @return mixed
   */
  public function sell()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map   = $this->getMap();
    $order = $this->getOrder();
    $page  = input('page', 1);
    $input = input();
//        if ($input){
////            ['name' => '名称','order_sn'=>'订单号','mobile'=>'手机号码','email'=>'邮箱']
//            if (isset($input['mobile']) && $input['mobile']) {
//                $map[] = ['mobile', 'like', "%".$input['mobile']."%"];
//            }
//            if (isset($input['email']) && $input['email']) {
//                $map[] = ['email', 'like', "%".$input['email']."%"];
//            }
//            if (isset($input['order_no']) && $input['order_no']) {
//                $map[] = ['order_sn', 'like', "%".$input['order_no']."%"];
//            }
//            if (isset($input['name']) && $input['name']) {
//                $map[] = ['name', 'like', "%".$input['name']."%"];
//            }
//            // 确保日期字符串被正确转换为时间戳
//            $startTimestamp = isset($input['_filter_time_from']) ? strtotime($input['_filter_time_from']) : '';
//            $endTimestamp = isset($input['_filter_time_to']) ? strtotime($input['_filter_time_to'] . ' 23:59:59') : ''; // 添加23:59:59确保包括全天
//// 结合起始和结束时间进行筛选
//            if ($startTimestamp && $endTimestamp) {
//                // 当开始和结束时间都设置时，同时应用两个条件
//                $map[] = ['create_time', 'between', [$startTimestamp, $endTimestamp]];
//            } elseif ($startTimestamp) {
//                // 仅设置了开始时间
//                $map[] = ['create_time', '>=', $startTimestamp];
//            } elseif ($endTimestamp) {
//                // 仅设置了结束时间
//                $map[] = ['create_time', '<=', $endTimestamp];
//            }
//        }
    empty($order) && $order = 'id desc';
    // 数据列表
//        $map['type'] = ['eq',2];
    $map[] = ['type', '=', 2];

    $admin_user = AdminUserModel::where('id', UID)->find();

    if ($admin_user['role'] == 2) {
      $map['agent_id'] = $admin_user['for_user'];
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

      $email  = $value['email'] ?: '--';
      $mobile = $value['mobile'] ?: '--';

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
    return ZBuilder::make('table')
//            ->setSearch(['name' => '名称','order_sn'=>'订单号','mobile'=>'手机号码','email'=>'邮箱'])
      ->setExtraHtmlFile('sellSearch', 'toolbar_top')
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['fund', '基金'],
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
//            ->addTimeFilter('create_time', [$beginday, $endday]) // 添加时间段筛选
      ->addRightButton('custom', $btn_edit)
      ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
//            ->addTopButtons('confirmSell') // 批量添加顶部按钮
      ->fetch(); // 渲染模板

  }

  /**
   * 设置用户状态：禁用、启用
   *
   * @param string $type 类型：enable/disable
   * @param array $record
   *
   * @return mixed
   * @author 路人甲乙 <4853332099@qq.com>
   */
  public function confirmSell()
  {
    $table_name    = input('param.table');
    $ids           = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $map           = [];
    $map['id']     = ['in', $ids];
    $map['status'] = ['eq', 0];
    $list          = FundOrderModel::where($map)->select();
    FundOrderModel::autoUpdateSellStatus($list, 2);

    $this->success('操作成功');
  }

  public function edit($id = null)
  {
    if ($id === null) $this->error('缺少参数');
    // 保存数据
    if ($this->request->isPost()) {
      $update_data = input();
      // 验证
      $data = $update_data;
      if ($data['status'] == 1) {
        $map           = [];
        $map['id']     = ['eq', $id];
        $map['status'] = ['eq', 0];
        $list          = FundOrderModel::where($map)->select();
        FundOrderModel::autoUpdateBuyStatus($list, 2);
        $this->success('编辑成功', cookie('__forward__'));
      } else {
        $this->error('编辑失败');
      }
    }
    $info    = FundOrderModel::where('id', $id)->find();
    $money   = $info['money'];
    $fund_id = $info['fund_id'];
//        FundModel::where('id',$fund_id) -> setInc('use_num');
//        FundModel::where('id',$fund_id) -> setInc('follow_funds',$money);

    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['radio', 'status', '状态', '', ['未确认', '确认']],
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
        $map['id']     = ['eq', $id];
        $map['status'] = ['eq', 0];
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
    $map['id']  = ['in', $ids];
    $result     = FundorderModel::where($map)->delete();
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
   * @author 路人甲乙 <4853332099@qq.com>
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

