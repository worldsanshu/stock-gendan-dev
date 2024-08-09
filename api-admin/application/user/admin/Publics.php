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
namespace app\user\admin;

use app\common\controller\Common;
use app\user\model\User as UserModel;
use think\facade\Cache;
use think\facade\Hook;
use util\Logs;
use think\Db;

/**
 * 用户公开控制器，不经过权限认证
 * @package app\user\admin
 */
class Publics extends Common
{


    /**
     * 刷新数据
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function ajaxtotal()
    {

        $auth             = Db::name('member')->where(['id_auth' => 0])->count('id');
        $apply            = Db::name('strategy_apply')->where(['apply_status' => 0])->count('id');
        $charge           = Db::name('money_recharge')->where(['status' => 0])->count('id');
        $withdraw         = Db::name('money_withdraw')->where(['status' => 0])->count('id');
        $data             = array();
        $data['status']   = 200;
        $data['auth']     = $auth;
        $data['apply']    = $apply;
        $data['charge']   = $charge;
        $data['withdraw'] = $withdraw;
        echo json_encode($data);
    }

    /**
     * 退出登录
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function signout()
    {
        $hook_result = Hook::listen('signout_sso');
        if (!empty($hook_result) && true !== $hook_result[0]) {
            if (isset($hook_result[0]['url'])) {
                $this->redirect($hook_result[0]['url']);
            }
            if (isset($hook_result[0]['error'])) {
                $this->error($hook_result[0]['error']);
            }
        }
        session(null);
        cookie('uid', null);
        cookie('signin_token', null);
        $this->redirect('/admin_user');
    }
}
