<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\common\model;

use think\Model;

/**
 * 插件公共模型
 * @package app\admin\model
 */
class Area extends Model
{
    protected $table = 'lmq_area';
    /**
     * 初始化方法
     */
    protected function initialize()
    {
        //$this->table = 'lmq_area_vn';
    }
    
}
