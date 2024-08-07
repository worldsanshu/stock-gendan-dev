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
 * 滚动图片验证器
 * @package app\cms\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Slider extends Validate
{
    // 定义验证规则
    protected $rule = [
      'title|标题' => 'require|length:1,30',
      'cover|图片' => 'require',
//        'url|链接'   => 'require|url',
    ];
    // 定义验证场景
    protected $scene = [
      'title' => ['title'],
//        'url'   => ['url'],
    ];
}
