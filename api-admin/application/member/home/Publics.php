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
use app\member\model\Invite as InviteModel;
use app\member\model\Member as MemberModel;
use app\statistics\model\DataReport as DataReportModel;
use think\Db;

class Publics extends home
{
    public function login()
    {
        if (!module_config("member.member_is_login")) {
            $this->error("系统关闭了登录", url('/'));
        }
        if ($this->request->isPost()) {
            // 获取post数据
            $data = input();
            // 验证数据
            $result = $this->validate($data, 'Member.signin');
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($result);
            }
            // 登录
            $MemberModel = new MemberModel;
            $mid = $MemberModel->login($data['mobile'], $data['password']);
            if ($mid) {
                // 记录行为
                $this->success('登录成功', url('member/index/index'));
            } else {
                $this->error($MemberModel->getError());
            }
        } else {
            if (is_member_signin()) {
                $this->redirect('member/index/index');
            } else {
                return $this->fetch();
            }
        }
    }

    public function registerss()
    {
        if ($this->request->isPost()) {
            $data = input();
            $check_code = check_sms_code($data['mobile'], $data['sms_code']);
            //临时关闭验证短信
            if (!$check_code) {
                return json(['status' => 0, 'message' => "验证码错误"]);
            }
            $user = Db::name('member')->where("mobile", $data['mobile'])->find();
            if ($user) {
                return json(['status' => 0, 'message' => "该账号已注册"]);
            }
            $data['agent_far'] = "";
            $res = MemberModel::saveData($data);
            if ($res['status'] === 1) {
                $auth = array(
                  'mid' => $res['data']['id'],
                  'mobile' => $res['data']['mobile'],
                  'last_login_time' => '',
                  'last_login_ip' => get_client_ip(1),
                );
                $auths = json_encode($auth);
                $sign = json_encode(data_auth_sign($auth));
                return json(['status' => 1, 'message' => "登录成功", 'url' => "http://www.xhpz99.com/member/index/index?member_auth=$auths&sign=$sign"]);
            } else {
                return json(['status' => 0, 'message' => "登录失败"]);
            }
        }
    }

    public function sendsmscode()
    {
        $mobile = input('mobile');
        $data['mobile'] = $mobile;
        $tp = 'code';
        if (!check_sms_code($mobile)) {
            return json(['message' => '请间隔60秒再获取验证码', 'status' => 0]);
        } else {
            //发送短信验证码
            $content = \think\Config::get('sms_template')['register'];
            $content = str_replace(array("#var#"), array($mobile), $content);
            $res = sendsms_mandao($mobile, $content, $tp);
            if ($res !== true) {
                return json(['message' => '短信发送失败', 'status' => 0]);
            } else {
                return json(['message' => '短信发送成功', 'status' => 1]);
            }
        }
    }

    /**
     * 退出登录
     * @author 张继立 <404851763@qq.com>
     */
    public function signout()
    {
        session(null);
        $this->redirect(URL('/login'));
    }

    /**
     * 注册
     * @return [type] [description]
     * @author 张继立 <404851763@qq.com>
     */
    public function register()
    {
        $host = request()->host();//获取当前域名
        if (!module_config("member.member_is_register")) {
            $this->error("暂不开放注册", url('/'));
        }
        $req = request();
        // $tid=substr(input('id'),2,-3);
        $tid = reductionranderode(input('id'));
        $pid = substr(input('pid'), 2, -3);
        if ($this->request->isPost()) {
            $data = input();
//                file_put_contents('register.txt',date('Y-m-d H:i:s')."\r\n",FILE_APPEND);
//                file_put_contents('register.txt', var_export($data, true) . "\r\n", FILE_APPEND);
//	    		$check_code = check_sms_code($data['mobile'], $data['sms_code']);
//	    		//临时关闭验证短信
//	    		true !== $check_code &&  $this->error("验证码错误");
//	    		$result = $this->validate($data, 'Member.create');
//	    		true !== $result  && $this->error($result);
            $check_code = aliCheckSMSCode($data['mobile'], $data['sms_code']);
//                file_put_contents('test.txt', '$check_code=' . var_export($check_code, true) . PHP_EOL, FILE_APPEND);
            if ($check_code == 0) {
                ajaxmsg('验证码错误', 0, '', true, ['msgCode' => 'L0054']);
            }
            if ($check_code == -1) {
                ajaxmsg('验证码已失效，请重新获取', 0, '', true, ['msgCode' => 'L0170']);
            }
            $invite_id = 0;
            if (!empty($data['recommend'])) {
                if ($data['recommend'] == $data['mobile']) {
                    $this->error("邀请人不能是自己");
                }
                $r_res = MemberModel::getMemberInfoByMobile($data['recommend']);
                if (empty($r_res)) {
                    $this->error("邀请人不存在");
                }
                $data['agent_far'] = $r_res['id'];//agent_far 生成模拟代理关系
//                    $data['invite_code'] = $data['invite_code'];//邀请码
                $data['invite_account'] = $r_res['mobile'];//邀请人账号
                $data['invite_name'] = $r_res['name'];//邀请姓名
                $invite_id = $r_res['id'];//邀请人id
            }
            if (!empty($data['invite_code'])) {
                $id = MemberModel::where('agent_code', $data['invite_code'])->value('id');
                if (empty($id)) {
                    $this->error("代理商不存在");
                }
//                    $data['agent_id'] = $id;
                $data['agent_far'] = $id;//agent_far 生成模拟代理关系
                $invite_id = $id;//邀请人id
            }
            $agent_user_id = MemberModel::where('agent_url', $host)->value('id');
            if ($agent_user_id) {
                $data['pid'] = $agent_user_id;
                $data['agent_far'] = $agent_user_id;//agent_far 生成模拟代理关系
                $invite_id = $agent_user_id;//邀请人id
            }
            $data['agent_far'] = isset($data['agent_far']) ? $data['agent_far'] : $data['agent_far'] = "";
            $res = MemberModel::saveData($data);
            if (!empty($data['recommend'])) {
                if (!empty($invite_id)) {
                    MemberModel::where('id', $invite_id)->setInc('invite_num', 1);
                }
                $m_res = MemberModel::getMemberInfoByMobile($data['mobile']);
                $data['id'] = $m_res['id'];//被邀请人id
                $data['mid'] = $r_res['id'];//邀请人id
                $Inv_res = InviteModel::saveData($data);//保存推荐关系数据
            }
            if ($res['status'] === 1) {
                $auth = array(
                  'mid' => $res['data']['id'],
                  'mobile' => $res['data']['mobile'],
                  'last_login_time' => '',
                  'last_login_ip' => get_client_ip(1),
                );
                //数据报表
                $data_report ['create_time'] = time();
                $data_report ['new_reg'] = 1;
                DataReportModel::appendDataReport($data_report);
                session('member_auth', $auth);
                session('member_auth_sign', data_auth_sign($auth));
            }
            1 === $res['status'] ? $this->success($res['message'], URL('/member/index/index')) : $this->error($res['message']);
        } else {
            $mobile = "";
            if ($tid !== false) {
                $tlist = MemberModel::getMemberInfoByID($tid);
                if (!empty($tlist['mobile'])) {
                    $mobile = $tlist['mobile'];
                }
            }
            $this->assign('mobile', $mobile);
            $this->assign('pid', $pid);
            return $this->fetch();
        }
    }

    public function register_username_ajax()
    {
        $mobile = input('username');
        if ($mobile) {
            return ['status' => 1, 'message' => '可以注册！'];
        }
    }

    /**
     * 忘记登录密码 重置
     */
    public function backPasswd()
    {
        session('sms_code', null);
        session('mobile', null);
        return $this->fetch();
    }

    /**
     * 忘记登录密码 重置--下一步
     */
    public function next()
    {
        if ($this->request->isPost()) {
            $data = input();
            $mobile = $data['mobile'];
            $sms_code = $data['sms_code'];
            $check_code = check_sms_code($mobile, $sms_code);
            //验证短信
            if (true !== $check_code) {
                $this->error("短信验证码错误或失效！");
            }
            //session('sms_code',$sms_code );
            $_SESSION['sms_code'] = sms_code;
            // session::set('mobile',$mobile);
            $_SESSION['mobile'] = $mobile;
            $this->success("", '/newpass');
        } else {
            return $this->fetch('backPasswd');
        }
    }

    /**
     * 设置新密码
     */
    public function newpass()
    {
        if ($this->request->isPost()) {
            $data = input();
            $result = $this->validate($data, 'Member.password');
            true !== $result && $this->error($result);
            $mobile = $_SESSION['mobile'];
            $sms_code = $_SESSION['sms_code'];
            $pwd = $data['password'];
            $MemberModel = new MemberModel;
            $password = $MemberModel->setPasswdAttr($pwd);
            $datas['passwd'] = $password;
            $where['mobile'] = $mobile;
            $result = Db::name('member')->where(['mobile' => $mobile])->update(['passwd' => $password]);
            unset($_SESSION['sms_code']);
            unset($_SESSION['mobile']);
            if ($result) {
                $this->success('密码重置成功！', '/login');
            } else {
                $this->error('密码重置失败！请重试', '/backPasswd');
            }
        } else {
            if ($_SESSION['mobile'] != null && $_SESSION['sms_code'] != null) {
                return $this->fetch();
            } else {
                $this->error("请先填写您的登录手机号！", '/backPasswd');
            }
        }
    }

    public function sendsms_old()
    {
        $mobile = input('mobile');
        $phonecode = input('phonecode');
        $data['mobile'] = $mobile;
        $data['captcha'] = $phonecode;
        $result = $this->validate($data, 'Member.reg');
        if (true !== $result) {
            // 验证失败 输出错误信息
            return ['status' => 0, 'message' => $result];
        }
        $tp = 'code';
        if (input('mobile')) {
            $captcha = ['mobile' => $mobile];
            // 验证码
            $result = $this->validate($captcha, 'Member.register_sendsms');
            if (true !== $result) {
                return ['status' => 0, 'message' => $result];
            };
        }
        if (!check_sms_code($mobile)) {
            return ['status' => 0, 'message' => "请间隔60秒再获取验证码！"];
        } else {
            //发送短信验证码
            $content = \think\Config::get('sms_template')['register'];
            $content = str_replace(array("#var#"), array($mobile), $content);
            $res = sendsms_mandao($mobile, $content, $tp);
            if ($res !== true) {
                return ['status' => 0, 'message' => '短信发送失败！'];
            } else {
                return ['status' => 1, 'message' => '短信发送成功！'];
            }
        }
    }

    /***
     ** 发送短信验证码
     ***/
    public function sendsms()
    {
        $mobile = input('mobile');
        $phonecode = input('phonecode');
        $code = input('code', '86');
        $data['mobile'] = $mobile;
        $data['captcha'] = $phonecode;
        $result = $this->validate($data, 'Member.reg');
        if (true !== $result) {
            // 验证失败 输出错误信息
            return ['status' => 0, 'message' => $result];
        }
        if ($mobile) {
            $captcha = ['mobile' => $mobile];
            // 验证码
            $result = $this->validate($captcha, 'Member.register_sendsms');
            if (true !== $result) {
                return ['status' => 0, 'message' => $result];
            };
        }
        if (!check_sms_code($mobile)) {
            return ['status' => 0, 'message' => "请间隔60秒再获取验证码！"];
        }
        $where['mobile'] = $mobile;//会员手机号
        $where['is_del'] = 0;
        $where['reg_code'] = $code;
        $ret = Db::name('member')->where($where)->value('id');
        if (!empty($ret)) ajaxmsg('该手机已经注册会员，请更换手机！', 0, '', true, ['msgCode' => 'L0058']);
        $res = SendSMSCode($mobile, $code);
        if ($res) {
            return ['status' => 1, 'message' => '短信发送成功！'];
        } else {
            return ['status' => 0, 'message' => '短信发送失败！'];
        }
    }

    public function getArea()
    {
        $reid = input('reid');
        /**
         * if(!cache('area_'.$reid)){
         * $area = (new Area())->where(['reid'=>$reid])->select();
         * }else{
         * $area = cache('area_'.$reid);
         * }**/
        $area = get_area($reid);
        return $area;
    }

    /**
     * 密码找回验证码发送
     * @return array
     */
    public function sendPassSms()
    {
        if ($this->request->isPost()) {
            $data = input();
            $mobile = $data['mobile'];
            $tp = $data['tp'];
            $captcha = $data['captcha'];
            //验证该手机号是否存在
            #$isExists = Db::name('member')->where('mobile',$mobile)->where($where)->value('id');
            $isExists = Db::name('member')->where('mobile', $mobile)->value('id');
            //如果找回密码所填写的手机号不存在
            if (is_null($isExists)) {
                return ['status' => 0, 'message' => '该手机号不存在'];
            }
            if ($captcha == '') {//如果验证码未填写
                return ['status' => 0, 'message' => '请输入图片验证码！'];
            }
            if (!captcha_check($captcha, '', config('captcha'))) {
                //验证失败
                return ['status' => 0, 'message' => '图片验证码错误或失效！'];
            };
            if (!check_sms_code($mobile)) {
                return ['status' => 0, 'message' => "请间隔60秒再获取验证码！"];
            } else {
                //发送短信验证码
                //$res = send_sms($mobile, $template);
                $content = \think\Config::get('sms_template')['register'];
                $content = str_replace(array("#var#"), array($mobile), $content);
                $res = sendsms_mandao($mobile, $content, $tp);
                if ($res !== true) {
                    return ['status' => 0, 'message' => '短信发送失败！'];
                } else {
                    return ['status' => 1, 'message' => '短信发送成功！'];
                }
            }
        }
    }
}

?>