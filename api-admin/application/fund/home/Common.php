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
namespace app\fund\home;

use app\apicom\model\JWT;
use app\index\controller\Home;
use think\Db;

/**
 * 前台公共控制器
 * @package
 */
class Common extends Home
{
  protected $level_array = [];

  /**
   * 初始化方法
   * @author 张继立 <404851763@qq.com>
   */
  protected function initialize()
  {
    parent::initialize();
    $this->level_array = Funduserlevelarray();
//        $not_check_array = ['getlistdata','getdetail','getcatelist','getfundline'];//不校验的方法
    $not_check_array = [];//不校验的方法
    $module          = request()->module();
    $controller      = request()->controller();
    $action          = request()->action();
    if (!in_array($action, $not_check_array)) {
      defined('MID') or define('MID', $this->isLogin());
    }
  }

  /**
   * 获取导航
   * @author 蔡伟明 <314013107@qq.com>
   */
  /**
   * 判断是否登录
   * @return boolean [description]
   */
  protected function isLogin()
  {
    $req   = request();
    $token = input("token");
    if ($token) {
      $decoded = JWT::decode($token, JWT_TOKEN_KEY, array('HS256'));
      $doHost  = $_SERVER['HTTP_HOST'];
      if ($doHost == $decoded->doHost) {
        $uid  = $decoded->uid;
        $path = $_SERVER['REQUEST_URI'];
        if (strpos($path, 'appindex') != true) {
          $d_info = Db::name("member_login_record")->where("mid", $uid)->max('id');
          printlog($d_info, '用户信息', 'log');
          Db::name("member_login_record")->where("id", $d_info)->update(["onlinetime" => time(), "terminal" => is_mobile() ? is_mobile() : "PC"]);
        }
        return $uid;
      } else {
        ajaxmsg('请先登录', 0);
      }
    } else {
      ajaxmsg('请先登录', 0);
    }
  }
}