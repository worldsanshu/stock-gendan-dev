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
namespace app\cms\validate;

use think\Validate;

/**
 * 客服验证器
 * @package app\cms\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Support extends Validate
{
    // 定义验证规则
    protected $rule = [
      'name|客服名称' => 'require',
      'qq|QQ号码' => 'number',
      'msn|MSN' => 'email',
    ];
    //定义验证提示
    protected $message = [
      'name.require' => '{%V0048}',// '请输入客服名称',
      'qq.number' => '{%V0049}',// 'QQ号码格式不正确',
      'msn.email' => '{%V0044}',// 'MSN格式不正确'
    ];
    // 定义验证场景
    protected $scene = [
      'name' => ['name']
    ];
}
