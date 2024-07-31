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
namespace app\stock\validate;

use think\Validate;

/**
 * 证券信息验证器
 * @package app\stock\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Borrow extends Validate
{
    //定义验证规则
    protected $rule = [
      'order_id|订单号' => 'require|unique:stock_borrow',
      'member_id|会员id' => 'require|number',
      'multiple|倍率' => 'require',
      'deposit_money|保证金' => 'require',
      'borrow_money|配资金额' => 'require',
      'init_money|初始资金金额' => 'require',
      'borrow_duration|资金使用时间' => 'require',
      'loss_warn|预警线' => 'require',
      'loss_close|止损线' => 'require',
      'rate|配资费率' => 'require',
      'total|配资总期数' => 'require',
      'type|配资类型' => 'require',
      'prohibit_open|是否禁止开仓' => 'require',
      'prohibit_close|是否禁止平仓' => 'require',
      'prohibit_back|是否禁止撤单' => 'require',
      'renewal_edit|是否开启自动续期' => 'require',
      'commission_scale|佣金比例' => 'require',
      'min_commission|最低佣金' => 'require',
      'rate_scale|配资管理费分成比例' => 'require',
      'profit_share_scale|盈利分成比例' => 'require',
      'stock_subaccount_id|子账户' => 'require',
    ];
    //定义验证提示
    protected $message = [
      'order_id.require' => '{%s0007}',// '订单号不存在',
      'order_id.unique' => '{%s0008}',// '订单号已存在',
      'member_id.require' => '{%s0009}',// '会员ID不能为空',
      'multiple.require' => '{%s0010}',// '配资倍率不能为空',
      'deposit_money.require' => '{%s0011}',// '保证金不能为空',
      'borrow_money.require' => '{%s0012}',// '配资金额不能为空',
      'init_money.require' => '{%s0013}',// '初始金额金额不能为空',
      'borrow_duration.require' => '{%s0014}',// '资金使用时间不能为空',
      'loss_warn.require' => '{%s0015}',// '预警线不能为空',
      'loss_close.require' => '{%s0016}',// '止损线不能为空',
      'rate.require' => '{%s0017}',// '配资费率不能为空',
      'total.require' => '{%s0018}',// '配资总期数不能为空',
      'type.require' => '{%s0019}',// '配资类型不能为空',
      'stock_subaccount_id.require' => '{%s0020}',// '子账户必选',
    ];
    //定义验证场景
    protected $scene = [
        //新增
      'create' => ['order_id', 'member_id', 'multiple', 'deposit_money', 'borrow_money', 'init_money', 'borrow_duration', 'rate', 'total'],
        //'audit' => ['prohibit_open', 'commission_scale', 'min_commission', 'rate_scale', 'profit_share_scale', 'stock_subaccount_id','loss_warn', 'loss_close', 'prohibit_close', 'prohibit_back', 'renewal'],
      //'audit' => ['prohibit_open', 'commission_scale', 'min_commission', 'rate_scale', 'profit_share_scale', 'stock_subaccount_id', 'loss_warn', 'loss_close', 'prohibit_close', 'renewal_edit'],
        'audit' => ['prohibit_open', 'commission_scale', 'min_commission', 'stock_subaccount_id', 'loss_warn', 'loss_close', 'prohibit_close', 'renewal_edit'],
    ];
}
