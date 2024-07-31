<?php

namespace app\operate\validate;

use think\Validate;

/**
 * 奖品设置验证器
 * @package app\cms\validate
 *
 */
class Ouser extends Validate
{
    // 定义验证规则
    protected $rule = [
      'count|可用抽奖次数' => 'require|length:1,30',
    ];
    // 定义验证场景
    protected $scene = [
      'count' => ['count']
    ];
}
