<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
// +----------------------------------------------------------------------
namespace app\market\home;

use app\apicom\model\JWT;
use app\index\controller\Home;

/**
 * 前台公共控制器
 * @package app\market\home
 */
class Common extends Home
{
    /**
     * 初始化方法
     */
    protected function initialize()
    {
        parent::initialize();
        header('Content-Type: application/json;charset=UTF-8');
        defined('MID') or define('MID', $this->isLogin());
    }

    /**
     * 判断是否登录
     * @return boolean [description]
     */
    public function isLogin()
    {
        $req = request();
        $token = $req->param("token");
        if (((bool)$token)) {
            //return 0;
        }
        if ($token) {
            $decoded = JWT::decode($token, JWT_TOKEN_KEY, array('HS256'));
            $doHost = $_SERVER['HTTP_HOST'];
            if ($doHost == $decoded->doHost) {
                return $decoded->uid;
            } else {
                return 0;
            }
        } else {
            return is_member_signin();
        }
    }
}