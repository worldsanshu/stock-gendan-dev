<?php

namespace app\operate\validate;

use think\Validate;

/**
 * 奖品设置验证器
 * @package app\cms\validate
 *
 */
class Prize extends Validate
{
    // 定义验证规则
    protected $rule = [
      'name|奖品名称' => 'require|length:1,30',
      'isprize|中奖标识' => 'require'
    ];
    // 定义验证场景
    protected $scene = [
      'name' => ['name']
    ];
}
