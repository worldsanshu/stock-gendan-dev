<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace app\stock\model;

use think\Model as ThinkModel;

/**
 * 证券信息模型
 * @package app\stock\model
 */
class SubMoneyRecord extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBMONEY_RECORD__';
    protected $type = [
      '1' => '购买股票扣款',
      '2' => '卖出股票回款',
      '3' => '购买股票扣款',
      '4' => '卖出股票回款',
      '5' => '卖出成功解除冻结',
      '6' => '买入成功解除冻结',
      '7' => '扣费用',
      '8' => '买入撤单解除冻结',
      '9' => '卖出撤单解除冻结',
      '10' => '追加保证金',
      '11' => '扩大配资',
      '12' => '提取盈利',
      '13' => '股票分红',
      '14' => '股票除权',
      '15' => '代扣股票利息税',
      '21' => '管理员手动操作',
      '22' => '系统自动补子账户超额亏损部分',
    ];

    public function get_record($map, $order, $listRows)
    {
        $data_list = self::view("stock_submoney_record d")
          ->view("stock_subaccount s", 'sub_account', 's.id=d.sub_id', 'left')
          ->view("lmq_stock_borrow b", 'stock_subaccount_id,init_money', 'b.stock_subaccount_id=d.sub_id', 'left')
          ->where($map)
          ->order($order)
          ->paginate($listRows)
          ->each(function ($item, $key) {
              $item['affect'] = money_convert($item['affect']);
              $item['account'] = money_convert($item['account']);
              $item['type'] = $this->type[$item['type']];
              $item['init_money'] = money_convert($item['init_money']);
          });
        return $data_list;
    }

    public function get_record_strok($map, $order, $listRows)
    {
        $data_list = self::view("lmq_stock_submoney_record r")
          ->view("lmq_stock_subaccount s", 'sub_account', 's.id=r.sub_id', 'left')
          ->view("lmq_stock_borrow b", 'init_money', 'b.stock_subaccount_id=r.sub_id', 'left')
          ->view("lmq_stock_position p", 'gupiao_code,gupiao_name', 'p.sub_id=r.sub_id', 'left')
          ->where('r.type', 'IN', '1,2,3,4')
          ->where($map)->group("r.id")
          ->order($order)
          ->paginate($listRows)
          ->each(function ($item, $key) {
              $item['affect'] = money_convert($item['affect']);
              $item['account'] = money_convert($item['account']);
              $item['type'] = $this->type[$item['type']];
              $item['init_money'] = money_convert($item['init_money']);
              $item['create_time'] = date('Y-m-d H:i:s', $item['create_time']);
          });
        return $data_list;
    }

    public function get_record_strok2($map, $order, $listRows, $page = 1)
    {
        $data_list = self::view("lmq_stock_submoney_record r")
          ->view("lmq_stock_borrow b", 'init_money', 'b.stock_subaccount_id=r.sub_id', 'left')
          ->view("lmq_stock_position p", 'gupiao_code,gupiao_name', 'p.sub_id=r.sub_id', 'left')
          ->where('r.type', 'IN', '1,2,3,4,5,6,8,9,10,11,12,13,14')
          ->where($map)->group("r.id")
          ->order($order)
          ->page($page)
          ->paginate($listRows)
          ->each(function ($item, $key) {
              $item['affect'] = money_convert($item['affect']);
              $item['account'] = money_convert($item['account']);
              $item['type'] = $this->type[$item['type']];
              $item['init_money'] = money_convert($item['init_money']);
              $item['create_time'] = date('Y-m-d H:i:s', $item['create_time']);
          });

        return empty($data_list) ? [] : $data_list->toArray();

    }
}