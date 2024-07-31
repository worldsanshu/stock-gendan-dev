<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

/**
 * 节点验证器
 * @package app\admin\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Domain extends Validate
{
    //定义验证规则
    protected $rule = [
        'type|域名类型' => 'require|in:1,2',
        'domain|域名' => 'require'
    ];
    //定义验证提示
    protected $message = [
        'type.require' => '请选择域名类型',
        'type.in' => '域名类型不正确',
        'domain.require' => '请输入一级域名',
        'domain.regex' => '请输入正确的域名格式，如xxx.com'
    ];
    protected $scene = [
      'add'   =>  ['type','domain'],

    ];
}
