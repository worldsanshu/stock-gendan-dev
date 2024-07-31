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
namespace app\cms\model;

use think\Model as ThinkModel;

/**
 * 滚动图片模型
 * @package app\cms\model
 */
class Slider extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__CMS_SLIDER__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}