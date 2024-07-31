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
namespace app\apicom\validate;

use think\Validate;

class Recharge extends Validate
{
    //定义验证规则
    protected $rule = [
        //'money|充值金额' => 'require|unique:member',
      'remark|备注' => 'require|length:6,255',
      'status|充值状态' => 'require',
    ];
    //定义验证提示
    protected $message = [
      'money.require' => '请输入充值金额',
      'remark.require' => '备注不能为空6-255个字符',
      'status.require' => '请选择充值状态',
    ];
    //定义验证场景
    protected $scene = [
        //更新
      'update' => ['status', 'remark'],
    ];
}
