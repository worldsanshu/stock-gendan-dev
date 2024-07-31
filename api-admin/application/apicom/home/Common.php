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
namespace app\apicom\home;

use app\apicom\model\JWT;
use app\index\controller\Home;
use think\Db;

/**
 * 前台公共控制器
 * @package app\apicom\admin
 */
class Common extends Home
{
    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function initialize()
    {
        parent::initialize();
        defined('MID') or define('MID', $this->isLogin());
//        if (!MID) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
    }


    /**
     * 判断是否登录
     * @return boolean [description]
     */
    protected function isLogin()
    {   // 判断是否登录
      if ($mid = is_member_signin()) {
        // 已登录
        return $mid;
      } else {
        return 0;
      }
    }
}