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
use app\common\builder\table\Builder as ZBuilder;
use app\fund\model\Fund;
use app\fund\model\FundLine as FundLineModel;
use think\Db;
use think\facade\Cache;

//use app\common\builder\ZBuilder;
//use app\common\builder\table\ZBuilder;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Fundline extends Admin
{
  /**
   * 买入
   * @return mixed
   */
  public function list($fund_id)
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    $page  = input('page', 1);
    $order = 'fund_date desc';
    // 数据列表
    $data_list = Db('fund_line')->where('fund_id', $fund_id)->order($order)->paginate();
    $btn_add   = ['title' => '新增', 'icon' => 'fa fa-fw fa-plus', 'href' => url('add', ['fund_id' => $fund_id])];
    $btn_line  = ['title' => 'K线图', 'icon' => 'fa fa-fw fa-line-chart', 'href' => url('line', ['fund_id' => $fund_id])];
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
//        $line_data = $this -> line($fund_id);
//        dump($line_data);exit;
    return ZBuilder::make('table')
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['fund_date', '日期'],
                     ['price', '收益率：%'],
                     //        ['node', '节点',['否','是']],
                     ['right_button', '操作', 'btn']
      ])->setTableName('list')
      ->addTopButton('delete') // 批量添加顶部按钮
      ->addTopButton('custom', $btn_add) // 批量添加顶部按钮
      ->addTopButton('custom', $btn_line) // 批量添加顶部按钮
      ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮
                                            //        ->setExtraHtmlFile('line', 'block_top', $line_data)
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
      // 验证
      $fund_id = input('fund_id');
      if (!$fund_id) {
        $this->error('基金id不能为空');
      }
      if (!$data['price']) {
        $this->error('收益率不能为空');
      }
      //批量添加
      if (strpos($data['price'], ',')) {
        $new_fund_date = Db('fund_line')->where('fund_id', $fund_id)->order('fund_date desc')->value('fund_date');//最新一条的日期
        if (!$new_fund_date && !$data['fund_date']) {
          $this->error('日期不能为空');
        }
        $new_fund_date = $new_fund_date ?: $data['fund_date'];
        $price_array   = explode(',', $data['price']);//需要批量添加的收益率数组
        $res           = FundLineModel::addFundData($new_fund_date, $price_array, $fund_id);
        if ($res > 0) {
          $this->success('新增成功', cookie('__forward__'));
        } else {
          $this->error('新增失败');
        }
      } else {
        if (!$data['fund_date']) {
          $this->error('日期不能为空');
        }
      }
      $map   = [];
      $map[] = ['fund_id', 'eq', $fund_id];
      $map[] = ['fund_date', 'eq', $data['fund_date']];
      if (!Db::name('stock_trade_date')->where('date', $data['fund_date'])->count()) {
        $this->error('选择的时间不能是非交易日');
      }
      if (Db('fund_line')->where($map)->count()) {
        $this->error('该日期已存在');
      } else {
        $data['fund_id'] = $fund_id;
        if (Db('fund_line')->insert($data)) {
          $update_data              = [];
          $update_data['fund_date'] = $data['fund_date'];
          Db('fund')->where('id', $fund_id)->update($update_data);
          $this->success('新增成功', cookie('__forward__'));
        } else {
          $this->error('新增失败');
        }
      }
    }
    return ZBuilder::make('form')->setPageTitle('新增') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['text', 'price', '收益率：% (批量添加格式:3,6,9)，逗号要在英文状态下输入'],
                     ['date', 'fund_date', '日期 (批量添加不需要选择日期，日期以最新的一条日期往后新增，如果该K线图一条数据都没有则需要选择日期)'],
    ])->fetch();
  }

  /**
   * 新增
   * @return mixed
   * @author 蔡伟明 <314013107@qq.com>
   */
  public function add_old()
  {
    // 保存数据
    if ($this->request->isPost()) {
      // 表单数据
      $data = input();
      // 验证
      $fund_id = input('fund_id');
      if (!$fund_id) {
        $this->error('基金id不能为空');
      }
      $map   = [];
      $map[] = ['fund_id', 'eq', $fund_id];
      $map[] = ['fund_date', 'eq', $data['fund_date']];
      if ($data['price'] < 1) {
        $this->error('价格不能小于1');
      }
      if (!Db::name('stock_trade_date')->where('date', $data['fund_date'])->count()) {
        $this->error('选择的时间不能是非交易日');
      }
      if (Db('fund_line')->where($map)->count()) {
        $this->error('该日期已存在');
      } else {
        $data['fund_id'] = $fund_id;
        $data['node']    = 1;
        $node_data       = Db('fund_line')->where('fund_id', $fund_id)->where('node', 1)->order('fund_date desc')->find();//节点数据
        $node_price      = $node_data['price'];                                                                           //节点价格
        $node_date       = $node_data['fund_date'];                                                                       //节点日期
        if (Db('fund_line')->insert($data)) {
//                    $list = Db('fund_line')-> where('fund_id',$fund_id) -> order('id asc') -> select();
          $count         = FundLineModel::where('fund_id', $fund_id)->count();
          $current_price = $data['price'];     //当前价格
          $current_date  = $data['fund_date']; //当前日期
          FundLineModel::getDateList($count, $node_price, $node_date, $current_price, $current_date, $fund_id);
          $this->success('新增成功', cookie('__forward__'));
        } else {
          $this->error('新增失败');
        }
      }
    }
    return ZBuilder::make('form')->setPageTitle('新增') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['text', 'price', '收益率：%'],
                     ['date', 'fund_date', '日期'],
    ])->fetch();
  }

  public function edit($id = null)
  {
    if ($id === null) $this->error('缺少参数');
    // 保存数据
    if ($this->request->isPost()) {
      $update_data = input();
      // 验证
      $data = $update_data;
      $info = Db('fund_line')->where('id', $id)->find();
      if ($info['fund_date'] != $data['fund_date']) {
        $this->error('不能更改时间');
      }
//            if($info['price'] < 1){
//                $this->error('价格不能小于1');
//            }
      if (Db('fund_line')->update($data)) {
        $this->success('编辑成功', cookie('__forward__'));
      } else {
        $this->error('编辑失败');
      }
    }
    $info = Db('fund_line')->where('id', $id)->find();
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['text', 'price', '收益率：%'],
                     ['date', 'fund_date', '日期'],
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
    $map[]      = ['id', 'in', $ids];
    $result     = Db('fund_line')->where($map)->delete();
    if (false !== $result) {
      $this->success('操作成功', cookie('__forward__'));
    } else {
      $this->error('操作失败');
    }
  }

  public function delete_old($record = [])
  {
    $table_name = input('param.table');
    $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $ids        = (array)$ids;
    $field      = input('param.field', 'is_del');
    $map[]      = ['id', 'in', $ids];
    if (count($ids) == 1) {
      $node_data = Db('fund_line')->where('id', $ids[0])->find();
      $fund_id   = $node_data['fund_id'];
      $today     = date('Y-m-d');
      $fund_date = $node_data['fund_date'];//要删除的节点时间
      if ($fund_date <= $today) {
        $this->error('不能删掉当天及当天以前的数据');
      }
      if ($node_data ['node'] == 1) { //如果删除的是节点则删除节点以下的数据,但是不能删除当天以前的数据
        $back_node_date = Db('fund_line')->where('fund_id', $fund_id)->where('node', 1)->where('fund_date', '<', $fund_date)->order('fund_date desc')->value('fund_date');
        if ($back_node_date >= $today) { //如果上一个节点的时间大于当天时间就把两个节点直接的数据删除，反之删除大于当天的数据
          $result = Db('fund_line')->where('fund_id', $fund_id)->where("`fund_date`>'" . $back_node_date . "' and `fund_date`<='" . $fund_date . "'")->delete();
        } else {
          $result = Db('fund_line')->where('fund_id', $fund_id)->where("`fund_date`>'" . $today . "' and `fund_date`<='" . $fund_date . "'")->delete();
        }
      } else {
        $result = Db('fund_line')->where($map)->delete();
      }
    } else {
      $result = Db('fund_line')->where($map)->delete();
    }
    if (false !== $result) {
      Cache::clear();
      $data_id = Db('fund_line')->where('fund_id', $fund_id)->order('fund_date desc')->value('id');//最新一条数据的id;
      //把最新一条作为最新的节点数值
      $update_data['node'] = 1;
      Db('fund_line')->where('id', $data_id)->update($update_data);
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
    $member_status = Fund::where('id', 'in', $ids)->column('status');
    return parent::setStatus($type, ['member_' . $type, 'member', $member_id, UID, implode('、', $member_status)]);
  }

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

  public function line($fund_id = 0)
  {
//        $fund_id = input('fund_id');
    $day_date       = date('Y-m-d');
    $map            = [];
    $map['fund_id'] = $fund_id;
    $order          = 'fund_date asc';
    $list           = Db::name('fund_line')->order($order)->where($map)->select();
    $array          = [];
    foreach ($list as $key => $value) {
      $fund_time     = strtotime($value['fund_date']);
      $price         = $value['price'];
      $name          = date('Y/m/d', $fund_time);
      $data['name']  = $name;
      $data['value'] = [$name, $price];
      $array[]       = $data;
    }
    $line_data = $array;
    $type      = input('type');
    if ($type === "888") {
      return ZBuilder::make('table')
        ->assign(['line_data' => $line_data, 'type' => $line_data])
        ->setTemplate('line')
        ->fetch();
    } else {
      return ZBuilder::make('table')
        ->hideCheckbox()
        ->assign(['line_data' => $line_data, 'empty_tips' => ''])
        ->setExtraHtml('<iframe src="' . url('line', 'type=888&fund_id=' . $fund_id) . '" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
        ->fetch();
    }
  }
}

