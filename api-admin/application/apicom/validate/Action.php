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
 * 行为验证器
 * @package app\cms\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Action extends Validate
{
    //定义验证规则
    protected $rule = [
      'module|所属模块' => 'require',
      'name|行为标识' => 'require|regex:^[a-zA-Z]\w{0,39}$|unique:admin_action',
      'title|行为名称' => 'require|length:1,80',
      'remark|行为描述' => 'require|length:1,128'
    ];
    //定义验证提示
    protected $message = [
      'name.regex' => '{%V0009}',//'行为标识由字母和下划线组成',
    ];
}
