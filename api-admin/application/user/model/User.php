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
namespace app\user\model;

use app\user\model\Role as RoleModel;
use app\user\model\User as UserModel;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use think\helper\Hash;
use think\Model;
use util\Logs;

/**
 * 后台用户模型
 * @package app\admin\model
 */
class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__ADMIN_USER__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }

    // 获取注册ip
    public function setSignupIpAttr()
    {
        return get_client_ip(1);
    }

    /**
     * 用户登录
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @param bool $rememberme 记住登录
     *
     * @return bool|mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function login($username = '', $password = '', $rememberme = false)
    {
        Cache::set('rememberme', $rememberme);
        $username = trim($username);
        $password = trim($password);
        // 匹配登录方式
        if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $username)) {
            // 邮箱登录
            $map['email'] = $username;
        } elseif (preg_match("/^1\d{10}$/", $username)) {
            // 手机号登录
            $map['mobile'] = $username;
        } else {
            // 用户名登录
            $map['username'] = $username;
        }
        $map['status'] = 1;
        // 查找用户
        $user = $this::get($map);
        if (!$user) {

            $this->loginlogs(array_merge(array('username' => $username), input()), 0, '用户不存在或被禁用');
            $this->error = '用户不存在或被禁用！';
        } else {

            if ($user['lockstatus'] == '1') {

                if ($user['lockstatusEndTime'] > time()) {
                    $unlocktime = $user['lockstatusEndTime'] - time();
                    $m          = floor((($unlocktime % (3600 * 24)) % 3600) / 60);
                    if ($m == 0) {
                        $m = $m + 1;
                    }
                    $this->error = '已连续错误5次，账户已被锁定，' . $m . '分钟后解除';
                    return false;
                } else {
                    $user['lockstatus']        = 0;
                    $user['lockstatusEndTime'] = null;
                    $user->save();
                    $data = array("uid" => $user['id']);
                    $res  = Db('admin_loginfails')->where($data)->delete();

                }
            }
            $failscount = $this->loginfailscounbt($user['id']);
            if (count($failscount) >= 5) {
                $this->loginlogs(array_merge(array('username' => $username), input()), 0, '已连续错误5次，账户已被锁定');
                $this->error = '已连续错误5次，账户已被锁定，30分钟后解除';
                // 更新登录信息
                if ($user['lockstatus'] != '1') {
                    $user['lockstatus']        = 1;
                    $user['lockstatusEndTime'] = time() + 1 * 30 * 60;
                    $user->save();
                }
                return false;
            }
            // 检查是否分配用户组
            if ($user['role'] == 0) {
                $this->error = '禁止访问，原因：未分配角色！';
                return false;
            }
            // 检查是可登录后台
            if (!RoleModel::where(['id' => $user['role'], 'status' => 1])->value('access')) {
                $this->loginlogs(array_merge(array('username' => $username), input()), 0, '用户所在角色未启用或禁止访问后台');
                $this->error = '禁止访问，用户所在角色未启用或禁止访问后台！';
                return false;
            }
            if (!Hash::check((string)$password, $user['password'])) {
                $this->loginlogs(array_merge(array('username' => $username), input()), 0, '账号或者密码错误');
                $this->error = '账号或者密码错误1！';
                $this->loginfails($user);
            } else {

                #检测登录域名是否是可以更等对应的域名  代理域名只能登录代理账户  总后台代理域名不能登录
                $webType = webType();
                if ($user['role'] == 2 && $webType !== 2) {
                    $this->error = '该账号登录入口错误';
                    return false;
                }
                $uid  = $user['id'];
                $uid2 = self::rande($uid);
                $umd5 = md5($uid2);
                Cache::set('admin_uid', $uid);
                Cache::set('admin_uid_md5', $umd5);
                if (empty($user['mobile'])) {
                    $this->error = '该账户未设置手机号，请设置手机号后登录';
                    return false;
                }
                return $this->success = $user['mobile'];

            }
        }
        return false;
    }

    /**
     * 用户二次登录
     *
     * @param string $uid 用户名
     * @param bool $rememberme 记住登录
     *
     * @return bool|mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function secondlogin($user)
    {
        $mobile = $user['mobile'];
        $uid    = $user['id'];
        Logs::log('secondlogin', ['uid' => $uid, 'mobile' => $mobile], 'pulbics');

        // 更新登录信息
        $user['last_login_time'] = request()->time();
        $user['last_login_ip']   = get_client_ip(1);

        if (UserModel::where('id', $uid)->update(['last_login_time' => request()->time(), 'last_login_ip' => get_client_ip(1)])) {
            Logs::log('secondlogin5', ['uid' => $uid, 'mobile' => $mobile, 'autoLogin' => $user], 'publics');
            $data = array("uid" => $user['id']);
            Db('admin_loginfails')->where($data)->delete();
            // 自动登录
            return self::autoLogin($user, true);
        } else {
            Logs::log('secondlogin3', ['uid' => $uid, 'mobile' => $mobile], 'pulbics');
            self::loginlogs(array_merge(array('username' => $user['username']), input()), 0, '登录信息更新失败，请重新登录');
            // 更新登录信息失败
            return false;
//            $this->error = '登录信息更新失败，请重新登录！';

        }
    }

    /*
     * 产生id混淆数
     */
    protected function rande($mid)
    {
        return mt_rand(10, 99) . $mid . mt_rand(100, 999);
    }

    /**
     * 记录登录错误次数
     *
     * @param  [type] $data [description]
     *
     * @author 一片天 <404230497@qq.com>
     */
    public static function loginfails($data)
    {

        $sdata['uid']         = $data['id'];
        $sdata['username']    = $data['username'];
        $sdata['mobile']      = $data['mobile'];
        $sdata['create_ip']   = get_client_ip(1);
        $sdata['create_time'] = time();
        # $result = self::create($sdata);
        $res = Db('admin_loginfails')->insert($sdata, false, true);

        return $res;

    }
    /**
     * 记录登录错误次数
     *
     * @param  [type] $data [description]
     *
     * @author 一片天 <404230497@qq.com>
     */
    /**
     * Desc : 管理员登录日志
     * Date : 2024-06-25 18:08
     *
     * @param $data
     * @param $status
     *
     * @return int|string
     */
    public static function loginlogs($data, $status, $note)
    {

        if (isset($data['password']) && strlen($data['password']) > 3) {
            $visiblePart = substr($data['password'], 0, -3);
            // 生成掩码
            $data['password'] = $visiblePart . '***';

        }
        // 获取当前请求对象
        $request = Request::instance();

        $ip = $request->ip();
        if ($ip == Cache::get('zone_login_ip')) {
            $zone = Cache::get('zone_addree');
        } else {
            $zone = getGeoLocation($ip);
            Cache::set('zone_addree', $zone);
            Cache::set('zone_login_ip', $request->ip());
        }

        if (is_object($zone) || is_array($zone)) {
            $zone = sprintf(
                "%s-%s-%s-%s-%s",
                $zone['query'],
                $zone['country'],
                $zone['region'],
                $zone['city'],
                $zone['isp']
            );
        }

        $sdata['username']  = $data['username'];
        $sdata['zone']      = $zone;
        $sdata['create_ip'] = $ip;
        $sdata['UA']        = $request->header('user-agent');
        $sdata['addtime']   = time();
        $sdata['status']    = $status;
        $sdata['note']      = $note;
        $sdata['data']      = var_export($data, true);

        $res = Db('admin_login_logs')->insert($sdata);

        return $res;

    }

    /**
     * 查询错误次数
     *
     * @param  [type] $data [description]
     *
     * @author 一片天 <404230497@qq.com>
     */
    public static function loginfailscounbt($uid)
    {

        # $result = self::create($sdata);
        $data = array("uid" => $uid);
        $res  = Db('admin_loginfails')->where($data)->select();
        return $res;

    }

    /**
     * 自动登录
     *
     * @param object $user 用户对象
     * @param bool $rememberme 是否记住登录，默认7天
     *
     * @return bool|int
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function autoLogin($user, $rememberme = false)
    {
        // 记录登录SESSION和COOKIES
        $auth = array(
            'uid'             => $user->id,
            'group'           => $user->group,
            'role'            => $user->role,
            'role_name'       => Db::name('admin_role')->where('id', $user->role)->value('name'),
            'avatar'          => $user->avatar,
            'username'        => $user->username,
            'nickname'        => $user->nickname,
            'last_login_time' => $user->last_login_time,
            'last_login_ip'   => get_client_ip(1),
        );
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
        // 保存用户节点权限
        if ($user->role != 1) {
            $menu_auth = Db::name('admin_role')->where('id', session('user_auth.role'))->value('menu_auth');
            Logs::log('menu_auth', ['menu_auth' => $menu_auth], 'user');
            $menu_auth = json_decode($menu_auth, true);
            Logs::log('menu_auth2', ['menu_auth' => $menu_auth], 'user');
            if (!$menu_auth) {
                session('user_auth', null);
                session('user_auth_sign', null);
//                $this->error = '登陆已超时，请重新登陆！';
                return false;
            }
        }
        // 记住登录
        if ($rememberme) {
            $signin_token = $user->username . $user->id . $user->last_login_time;
            cookie('uid', $user->id, 24 * 3600 * 7);
            cookie('signin_token', data_auth_sign($signin_token), 24 * 3600 * 7);
        }
        return $user->id;
    }
}
