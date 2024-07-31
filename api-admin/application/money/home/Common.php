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
namespace app\money\home;

use app\index\controller\Home;

/**
 * 前台公共控制器
 * @package app\member\admin
 */
class Common extends Home
{
    /**
     * 初始化方法
     * @author 张继立 <404851763@qq.com>
     */
    protected function initialize()
    {
        parent::initialize();
        header('Content-Type: application/json;charset=UTF-8');
    }
}