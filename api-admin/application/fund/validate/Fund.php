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
namespace app\fund\validate;

use think\Validate;

class Fund extends Validate
{
  //定义验证规则
  protected $rule = [
    'name|基金名称'                 => 'require',
    'how|做多/做少'                 => 'require',
    'num|数值'                      => 'require',
    'fund_summarize|基金概括'       => 'require',
    'establish_date|成立日期'       => 'require',
    'scale|基金规模'                => 'require',
    'share|基金份额'                => 'require',
    'manage|基金经理'               => 'require',
    'administrator|基金管理'        => 'require',
    'title|视频标题'                => 'require',
    'video_id|视频'                 => 'require',
    'follow_funds|跟随资金'         => 'require',
    'year_income|年化收益'          => 'require',
    'year_max_retreat|年均最大回撤' => 'require',
    'hold_time|建议持有时间'        => 'require',
    'min_invest_money|最低投资金额' => 'require',
    'trace_index|跟踪指数'          => 'require',
    'trace_title|指数标题'          => 'require',
    'content|基金简介'              => 'require',
    'cate_id|请选择基金分类'        => 'require',
    'type|请选择投资类型'           => 'require',
    'index_type|选择指数类型'       => 'require',
    'orderlist|订单id不能为空'      => 'require',
    'porductlist|请选择股票信息'    => 'require',
    'buyprice|请指定买入价格'       => 'require',
    'buyprice|买入价格不能为0'      => 'gt:0',
    'buytime|请指定买入时间'        => 'require',
    'stockcode|股票代码'            => 'require',
    'selltime|卖出时间'             => 'require',
    'sellprice|卖出价格'            => 'require',
    'sellprice|卖出价格不能为0'     => 'gt:0',
//    'position|持仓比例需要大于0'    => 'gt:0',
    'position|持仓比例'             => 'require',
    'trader|导师'            => 'require',
    'date|交易日期'            => 'require',
  ];
  //定义验证提示
  protected $message = [
    'card.regex'     => '请输入15-19位卡号',
    'password.regex' => '密码格式有误(a-zA-Z0-9_) 6-16个字符',
    'mobile.regex'   => '手机号不正确',
    'mobile.require' => '手机号不能为空',
  ];
  //定义验证场景
  protected $scene = [
    //更新
    'create'  => ['card', 'bank', 'province', 'city', 'branch', 'sms_code'],
    'add'     => ['name', 'how', 'num', 'fund_summarize', 'establish_date', 'scale', 'share', 'manage', 'administrator', 'title', 'video_id', 'follow_funds',
                  'trace_index', 'year_income', 'year_max_retreat', 'hold_time', 'min_invest_money'
                  , 'trace_title', 'content', 'cate_id', 'type'

    ],
    'sms'     => ['mobile'],
    'daybuy'  => ['porductlist', 'orderlist', 'buyprice', 'buytime'],
    'memberdaybuy'  => ['porductlist', 'buyprice', 'buytime'],
    'daysell' => ['stockcode', 'selltime', 'orderlist', 'sellprice'],
    'traderorder' => ['trader','stockcode', 'buyprice', 'buytime' ],
  ];
}
