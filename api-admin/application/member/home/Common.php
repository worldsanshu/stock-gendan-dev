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
namespace app\member\home;

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
    $post = input();
    if (isset($post['member_auth']) && $post['member_auth']) {
      $auth = json_decode($post['member_auth'], true);
      $sign = json_decode($post['sign'], true);
      session('member_auth', $auth);
      session('member_auth_sign', $sign);
    }
    //$sign = data_auth_sign($sign);
    defined('MID') or define('MID', $this->isLogin());
    if (!MID) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
    $user     = get_agents_info(MID);
    $agent_id = $user['agent_id'];
    $this->assign('isAgent', $agent_id);
    $this->assign('member_auth', session('member_auth'));
    $this->assign('mockset', config('web_mock_set'));
//        $this->assign('path_info', $_SERVER['PATH_INFO']);
  }

  /**
   * 判断是否登录
   * @return boolean [description]
   */
  protected function isLogin()
  {
    // 判断是否登录
    if ($mid = is_member_signin()) {
      // 已登录
      return $mid;
    } else {
      return 0;
    }
  }
}