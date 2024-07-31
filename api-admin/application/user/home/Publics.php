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
namespace app\user\home;

use app\common\controller\Common;
use app\user\model\User as UserModel;
use think\facade\Cache;
use think\facade\Hook;
use util\Logs;

/**
 * 用户公开控制器，不经过权限认证
 * @package app\user\admin
 */
class Publics extends Common
{
    protected function initialize()
    {
        parent::initialize();
        // 调用admin模块下的admin控制器中的方法
        #验证可访问后端域名
        AllowedDomain();

        #验证IP白名单
        ip_restriction();
    }
    /**
     * 用户登录
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function signin()
    {
        if ($this->request->isPost()) {
            Cache::set('login_ip', $this->request->ip());//方便记录登录日志
            // 获取post数据
            $data = input();
            //验证验证码token
            $rememberme = isset($data['remember-me']) ? true : false;
            // 登录钩子
            $hook_result = Hook::listen('signin', $data);
            if (!empty($hook_result) && true !== $hook_result[0]) {
                $this->error($hook_result[0]);
            }
            // 验证数据
            $result = $this->validate($data, 'User.signin');
            if (true !== $result) {
                // 验证失败 输出错误信息
                 $this->error($result);
            }
            // 验证码
            if (config('captcha_signin')) {
                $captcha = input('captcha', '');
                $captcha == '' && $this->error('请输入验证码');
                if (!captcha_check($captcha, '')) {
                    //验证失败
                    $this->error('验证码错误或失效');
                };
            }
            // 登录
            $UserModel = new UserModel;
            $uid       = $UserModel->login($data['username'], $data['password'], $rememberme);

            if ($uid) {
                // 记录行为
                action_log('user_signin', 'admin_user', $uid, $uid);
                $this->jumpUrl($uid);
            } else {
                $this->error($UserModel->getError());
            }
        } else {
            $hook_result = Hook::listen('signin_sso');
            if (!empty($hook_result) && true !== $hook_result[0]) {
                if (isset($hook_result[0]['url'])) {
                    $this->redirect($hook_result[0]['url']);
                }
                if (isset($hook_result[0]['error'])) {
                    $this->error($hook_result[0]['error']);
                }
            }
            if (is_signin()) {
                $this->jumpUrl();
            } else {
                $token = md5(config('do_strategy_token') . time());
                session('sms_code_token', $token);
                $this->assign('token', $token);
                return $this->fetch();
            }
        }
    }

    public function inspect()
    {
        $data        = input();
        $user        = UserModel::get(Cache::get('admin_uid'));
        $google      = new \GoogleAuthenticator();
        $checkResult = $google->verifyCode($user['google_secret'], $data['code'], 1);

        if(true !== $checkResult){
            UserModel::loginlogs(array_merge(array('username'=>$user['username']), input()),0,'谷歌验证码错误');
            $this->error("谷歌验证码错误");
        }
        $uid  = Cache::get('admin_uid');
        $umd5 = Cache::get('admin_uid_md5');

        Logs::log('inspect', ['uid' => $uid, 'umd5' => $umd5, 'data' => $data], 'pulbics');

        action_log('user_signin', 'admin_user', $uid, $uid);
        $UserModel = new UserModel;
        $uid       = $UserModel->Secondlogin($user);
        Logs::log('uid', ['uid' => $uid], 'pulbics');

        if ($uid) {
            UserModel::loginlogs(array_merge(array('username'=>$user['username']), input()),1,'登录验证成功，谷歌验证码登录');
            #前清除缓存
            systemwipeCache();
            return [
                'code' => 1,
                'msg'  => '验证成功',
                'data' => '',
                'url'  => url('admin/index/index'),
                'wait' => 3,
            ];
        } else {
            UserModel::loginlogs(array_merge(array('username'=>$user['username']), input()),0,'第二次认证时登录失败-未知错误1');
            return ['code' => 0, 'message' => '登录失败1'];
        }
        UserModel::loginlogs(array_merge(array('username'=>$user['username']), input()),0,'第二次认证时登录失败-未知错误2');
        return ['code' => 0, 'message' => '登录失败2'];

    }

    public function sendsms()
    {
        $data   = input();
        $mobile = $data['phone'];
        $tp     = 'code';
        $msg    = check_sms_code($mobile);
        if (true !== $msg) {
            return ['status' => 0, 'message' => $msg];
        } else {
            //发送短信验证码
            //      $res = send_sms($mobile, 'sms_register');
            //发送短信验证码
            $content = config('sms_template')['register'];
            $content = str_replace(array("#var#"), array($mobile), $content);
            $res     = sendsms_mandao($mobile, $content, $tp);
            if ($res !== true) {
                return ['status' => 0, 'message' => '短信发送失败！'];
            } else {
                return ['status' => 1, 'message' => '短信发送成功！'];
            }

        }
    }

    /**
     * 跳转到第一个有权限访问的url
     * @return mixed|string
     * @author 蔡伟明 <314013107@qq.com>
     */
    private function jumpUrl($data = '')
    {
        $user = UserModel::get(Cache::get('admin_uid'));


        if (!empty($user['google_authenticator']) && Cache::get('admin_uid')) {
            UserModel::loginlogs(array_merge(array('username'=>$user['username']), input()),1,'登录验证成功，等待谷歌验证码');
            $this->success('验证成功', '', Cache::get('admin_uid_md5') . '-' . $data);
        }
        UserModel::loginlogs(array_merge(array('username'=>$user['username']), input()),1,'登录成功，没谷歌认证');
        #前清除缓存
        systemwipeCache();
        UserModel::Secondlogin( $user);
        $this->success('登录成功~~', url('admin/index/index'), 1);
    }

    /**
     * Desc : 清除缓存节点
     * Date : 2024-07-05 01:43
     * @return array|array[]
     */
    public function delLocationsArr()
    {
        $data = input();
        
        // 验证输入
        if (!isset($data['index'])) {
            // 返回错误，例如：
            $this->error('操作失败');
        }
        if ($data['index'] < 0) {
            
            cache('_history'.UID, []);
            return [
                'locationsArray' => [],
            ];
        }
        //        如果清掉的是当前页面
        $locationsArray = cache('_history'.UID);
        // 如果缓存不存在，可能需要初始化它
        if (!$locationsArray) {
            $locationsArray = [];
        }
        
        // 移除指定索引的元素
        if (isset($locationsArray[$data['index']])) {
            unset($locationsArray[$data['index']]);
        }
        
        // 重新索引数组
        $locationsArray = array_values($locationsArray);
        
        // 保存修改后的数组回缓存
        cache('_history'.UID, $locationsArray);
        
        // 返回操作结果，而不是整个缓存数组
        return [
            'locationsArray' => $locationsArray,
        ];
    }

}
