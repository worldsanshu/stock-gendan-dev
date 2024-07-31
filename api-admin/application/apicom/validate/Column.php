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
 * 栏目验证器
 * @package app\cms\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Column extends Validate
{
    // 定义验证规则
    protected $rule = [
      'pid|所属栏目' => 'require',
      'name|栏目名称' => 'require|unique:cms_column,name^pid',
      'model|内容模型' => 'require',
    ];
}
