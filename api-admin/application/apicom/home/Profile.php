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

use AlibabaCloud\Client\AlibabaCloud;
use app\member\model\Member;
use app\member\model\Member as MemberModel;
use app\member\model\MemberBlacklist as MemberBlacklistModel;
use app\member\model\MemberBlacklistLog as MemberBlacklistLogModel;
use PHPMailer\Email;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use think\helper\Hash;
use think\facade\Log;

class Profile extends Common
{
    public function construct(Request $request = null)
    {
        $token = input("token");
        defined('MID') or define('MID', isLogin($token));
        $mid = MID;
        if (!$mid) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
    }

    /*
     * 个人资料
     */
    public function index()
    {
        $mid = MID;
        if (empty($mid)) {
            $mid = $this->isLogin();
        }
        $datalist                       = member::getMemberInfoByID($mid);
        $fields                         = ['my_property', 'agent_user', 'wallet_management', 'promotion_commission'];
        $switch_data                    = Db::name('admin_config')->where('name', 'in', $fields)->column('value', 'name');
        $domain                         = request()->domain();
        $datalist['card_pic_back_text'] = $datalist['card_pic_back'] ? get_img_url($datalist['card_pic_back']) : '';
        $datalist['card_pic_hand_text'] = $datalist['card_pic_hand'] ? get_img_url($datalist['card_pic_hand']) : '';
        $datalist['passport_pic_text']  = $datalist['passport_pic'] ? get_img_url($datalist['passport_pic']) : '';
        $datalist['switch_data']        = $switch_data;
        ajaxmsg('个人资料信息', 1, $datalist, true, ['msgCode' => 'L0130']);
    }

    public function getAppVersion()
    {
        $version              = input('version');
        $data['close_status'] = 0;
        $config               = Db::name('admin_config')->where('name', 'app_version')->find();
        if ($version == $config['value']) {
            $data['close_status'] = 1;
        }
        ajaxmsg('app状态', 1, $data, true);
    }

    /*
     * 紧急联系人
     * @captcha 验证码
     * @name 紧急联系人名
     * @mobile 紧急联系人手机
     */
    public function urgent()
    {
        $mid = MID;
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        $post = input();
        if (!empty($post)) {
            $result = $this->validate($post, 'Member.urgent');
            if ($result) {
                $captcha = $post['captcha'];
                $name    = $post['name'];
                $phone   = $post['mobile'];
                if (check_sms_code($phone, $captcha)) {
                    $res = Db::name('member')->where(['id' => $mid])->update(['urgent_mobile' => $phone, 'urgent_name' => $name]);
                    if (!$res) {
                        ajaxmsg('修改失败', 0, '', true, ['msgCode' => 'L0127']);
                    }
                } else {
                    ajaxmsg('修改失败', 0, '', true, ['msgCode' => 'L0127']);
                }
            } else {
                ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
            }
        }
        $data = member::getMemberInfoByID($mid);
        ajaxmsg('设置成功', 1, $data, true, ['msgCode' => 'L0126']);
    }

    //获取短信验证码
    public function getmobliecode()
    {
        $mid = MID;
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        $post  = input();
        $phone = $post['phone'];
        if (!check_sms_code($phone)) {
            ajaxmsg('请间隔60秒再获取验证码！', 0, '', true, ['msgCode' => 'L0059']);
        };
        $res = send_sms($phone, "sms_tp02");
        if ($res) {
            ajaxmsg('短信验证码已发送', 1, '', true, ['msgCode' => 'L0131']);
        } else {
            ajaxmsg('短信验证码发送失败', 0, '', true, ['msgCode' => 'L0132']);
        }
    }



//    修改手机号码
    public function updateMobile()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg(lang('L0097'), 0);
        }
        $post     = input();
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $reg_code = input('reg_code', '86'); //国家地区号码
        if (empty($mobile) || empty($code))
            ajaxmsg(lang('L0020'), 0);
        if (!is_numeric($mobile))
            ajaxmsg('手机号不正确', 0, '', true, ['msgCode' => 'V0006']);
        $where    = [
            'mobile'   => $mobile,
            'reg_code' => $reg_code,
            'status'   => 1
        ];
        $isExists = Db::name('member')->where($where)->where('is_del', 0)->count();
        if ($isExists)
            ajaxmsg('该手机号已经注册', 0, '', true, ['msgCode' => 'L0053']);
        //临时关闭验证短信
        $check_code = aliCheckSMSCode($mobile, $code);
        $check_code !== 1 && ajaxmsg('验证码错误', 0, '', true, ['msgCode' => 'L0054']);
        #  $pay_pwd = substr($mobile, -6, 6);
        $data  = [
            'mobile'   => $mobile,
            'reg_code' => $reg_code,
        ];
        $newid = Db::name('member')->where(["id" => $mid])->update($data);
        if ($newid) ajaxmsg('修改成功', 1, '', true, ['msgCode' => 'L0134']);
        else ajaxmsg(lang('L0127'), 0);
    }

    /**
     * 修改手机号码---旧流程
     * @return mixed
     * @captcha 验证码
     * @mobile 已注册手机号
     */
    public function telephone()
    {
        $mid        = MID;//用户ID
        $data       = member::getMemberInfoByID($mid);
        $old_mobile = $data['mobile'];//旧手机号
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0); //判断是否登录获取MID
        }
        $captcha1 = input('captcha'); //获取手机验证码
        $step     = input('step');    //获取新手机号
        if (empty($step)) {
            $step = 1;
        }
        $new_mobile = input('new_mobile'); //获取新手机号
        if ($captcha1 && $step == 1) {  //第一步
            session('step_key', 'lurenjiayi23101988');
            $result = $this->validate($captcha1, 'Member.captcha');//验证表单
            if ($result) {
                if (check_sms_code($old_mobile, $captcha1)) {  //验证手机验证码
                    ajaxmsg('验证通过,可进入第二部设置', 1, '', true, ['msgCode' => 'L0133']);
                } else {
                    ajaxmsg('验证码错误', 0, '', true, ['msgCode' => 'L0054']);
                }
            } else {
                ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
            }
        } elseif ($step == 2 && !empty($new_mobile)) {            //第二步
            $yz = ['mobile' => $new_mobile];
            // 验证码
            $result1 = $this->validate($yz, 'Member.editMoblie'); //验证表单
            if (!empty($result1) && $result1 != true) {
                ajaxmsg($result1, 0);
            } else {
                if (check_sms_code($new_mobile, $captcha1)) {
                    $res = Db::name('member')->where(['id' => $mid])->update(['mobile' => $new_mobile]);
                    if ($res) {
                        $auth           = session('member_auth');
                        $auth['mobile'] = $new_mobile;
                        session('member_auth', $auth);
                        session('member_auth_sign', data_auth_sign($auth));
                        ajaxmsg('修改成功', 1, '', true, ['msgCode' => 'L0134']);
                    } else {
                        ajaxmsg('修改失败', 0, '', true, ['msgCode' => 'L0127']);
                    }
                } else {
                    ajaxmsg('验证码错误', 0, '', true, ['msgCode' => 'L0054']);
                }
            }
        }
        ajaxmsg('修改成功', 1, '', true, ['msgCode' => 'L0134']);
    }

    public function sendsms()
    {
        $mobile = input('mobile');
        $code   = input('code');
        $type   = input('type', 'validate');
        if ($code == '86') {
            $tp  = 'code';
            $ret = member::getMemberInfoByMobile($mobile);
            if (!empty($ret)) ajaxmsg('该手机已经注册会员，请更换手机！', 0, '', true, ['msgCode' => 'L0058']);
            if (!check_sms_code($mobile)) {
                ajaxmsg('请间隔60秒再获取验证码!', 0, '', true, ['msgCode' => 'L0059']);
            }
            // $res = send_sms($mobile, $template);
            $content = \think\Config::get('sms_template')['register'];
            $content = str_replace(array("#var#"), array($mobile), $content);
            $res     = sendsms_mandao($mobile, $content, $tp);
            if ($res) {
                ajaxmsg('发送成功', 1, '', true, ['msgCode' => 'L0060']);
            } else {
                ajaxmsg('发送失败', 0, '', true, ['msgCode' => 'L0135']);
            }
        } else {
            AlibabaCloud::accessKeyClient('LTAI5tBsyVzkAvjxx3DWWb48', 'MjMhYYfEVjVVLS6tXUIW7s0czBMGwt')
                ->regionId('ap-southeast-1')
                ->asGlobalClient();
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                ->host('dysmsapi.ap-southeast-1.aliyuncs.com')
                ->version('2018-05-01')
                ->action('SendMessageToGlobe')
                ->method('POST')
                ->options([
                    'query' => [
                        "To"      => $code . $mobile,
                        "Message" => "Your phone  verification code is" . $code,
                    ],
                ])
                ->request();
            //file_put_contents('test.txt', '$result=' . var_export($result, true) . PHP_EOL, FILE_APPEND);
                Log::debug('test-'.var_export($result, true));
        }
    }

    function smsCode()
    {
        $mobile = input('mobile');
        $code   = input('code', '86');//国家地区电话代码
        $ip     = get_client_ip();
        $key    = $ip . '_' . $mobile;
        if (empty($mobile) || empty($code))
            ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
        if (!preg_match("/^1\d{10}$/", $mobile))
            ajaxmsg('手机号不正确', 0, '', true, ['msgCode' => 'V0006']);
        /* if (Cache::has($key))
             ajaxmsg('请间隔60秒再获取验证码!', 0, '', true, ['msgCode' => 'L0059']);
         */
        $mobile   = $code . $mobile;
        $valiCode = mt_rand(100000, 999999);
        $res      = false;
        if ($code == '86') {
        } else {
            $content = 'Your phone  verification code is ' . $valiCode;
            $res     = aliSendSMSGlobe($mobile, $content);
        }
        if ($res) {

            Cache::set($mobile, $valiCode, 600);
            ajaxmsg('发送成功', 1, '', true, ['msgCode' => 'L0060']);
        } else {
            ajaxmsg('发送失败', 0, '', true, ['msgCode' => 'L0135']);
        }
    }

    public function realname()
    {
        $mid = MID;
        if (empty($mid)) {
            $mid = $this->isLogin();
        }
        $datalist = member::getMemberInfoByID($mid);

        $post = input();
        if (!empty($post)) {
            if ($datalist['id_auth'] == 1) {
                ajaxmsg('您已经实名认证，不能更改', 0, '', true, ['msgCode' => 'L0064']);
            }

//            三要素认证
            if($post['examine_type'] == 1){
                $get_id_card = Db::name('member')->where(['id_card' => $post['id_card']])->find();
                if ($get_id_card) {
                    if($get_id_card['id'] != $mid){
                        ajaxmsg('该身份证号已被绑定', 0);
                    }
                }
                if ($datalist['name'] != null && $datalist['id_card'] != null && $datalist['id_auth'] == 0) {
                    //$this->assign("is_verified2", 1);
                    #  ajaxmsg('您已经提交实名认证申请，请耐心等待。', 0, '', true, ['msgCode' => 'L0065']);
//                $this->error('您已经提交实名认证申请，请耐心等待。');
                }
                $result    = $this->validate($post, 'Member.realname');
                $name      = $post['name'];
                $id_card   = $post['id_card'];
                $bank_card = $post['bank_card'];

                if (!$name) ajaxmsg('真实姓名不能为空', 0, '', true, ['msgCode' => 'L0066']);
                if (!$id_card) ajaxmsg('银行卡号不能为空', 0, '', true, ['msgCode' => 'L0067']);

                /****----**/
                if (MemberBlacklistModel::where('idcard', $id_card)->count()) {
                    MemberBlacklistLogModel::create(['idcard' => $id_card]);
                    ajaxmsg('该身份证号不能绑定', 0, '', true, ['msgCode' => 'L0154']);
                }
                //实名认证
                $params = array(
                    'accountNo'     => $bank_card,
                    'bankPreMobile' => $datalist['mobile'],
                    'idCardCode'    => $id_card,
                    'name'          => $name
                );

                printlog($params, '实名认证', 'realname');
                if (config('web_auto_realname_auth')) {
                    #API自动实名认证
                    $YHVerifyIdcard = autorealname($params);
                }
                Db::name('member')->where(['id' => $mid])->update([
                    'name' => $name,
                    'is_sub' => 1,
                    'auth_type' => $post['auth_type'],
                    'bank_card' => $bank_card,
                    'id_card' => $id_card,
                    'id_auth' => 0]);
            }else{
//                图片人工审核
                Db::name('member')->where(['id' => $mid])->update([
                    'name' => $post['name'],
                    'is_sub' => 1,
                    'auth_type' => $post['auth_type'],
                    'card_pic_hand' => $post['card_pic_hand'],
                    'card_pic_back' => $post['card_pic_back'],
                    'id_auth' => 0
                ]);
            }

            if (config('web_auto_realname_auth')&&$YHVerifyIdcard['code']) {
                $res = Db::name('member')->where(['id' => $mid])->update(['id_auth' => 1, 'auth_time' => time()]);
            } else {
                $res = Db::name('member')->where(['id' => $mid])->update(['id_auth' => 0, 'auth_time' => time()]);
            }

            if ($res) {
//                    if (config('admin_sms_mobile')) {
//                        send_sms(config('admin_sms_mobile'), 'idcard_sms_admin', 0);
//                    }
                ajaxmsg('实名认证已提交', 1, '', true, ['msgCode' => 'L0149']);
            } else {
                if (config('web_auto_realname_auth')) {
                    ajaxmsg($YHVerifyIdcard['msg'], 0);
                } else {
                    ajaxmsg('信息更新失败', 0);
                }

            }
        }
        ajaxmsg('提交资料不能为空', 0, '', true, ['msgCode' => 'L0137']);
    }

    /**
     * 实名认证接口
     */
    public
    function auth()
    {
        $mid = MID;
        if (empty($mid)) {
            $mid = $this->isLogin();
        }
        $datalist = member::getMemberInfoByID($mid);
        $post     = input();
        $file     = $this->request->file();
        $request  = input();
        if (!empty($post)) {
            if ($datalist['id_auth'] == 1) {
                ajaxmsg('您已经实名认证，不能更改', 1, '', true, ['msgCode' => 'L0064']);
            }
            if ($datalist['is_sub'] == 1 && $datalist['id_auth'] == 0) {
                ajaxmsg('您已经提交实名认证申请，请耐心等待。', 1, '', true, ['msgCode' => 'L0065']);
            }
//            file_put_contents('test.txt','request==>'.var_export($request,true).PHP_EOL,FILE_APPEND);
            $name      = $post['name'];
            $id_card   = $post['id_card'];
            $auth_type = intval($post['auth_type']);
            //P1
            $P1 = isset($post['card_pic_hand']) ? $post['card_pic_hand'] : '';
            $P2 = isset($post['card_pic_back']) ? $post['card_pic_back'] : '';
            $P3 = isset($post['passport_pic']) ? $post['passport_pic'] : '';
            if (MemberBlacklistModel::where('idcard', $id_card)->count()) {
                MemberBlacklistLogModel::create(['idcard' => $id_card]);
                ajaxmsg('该身份证号不能绑定', 0, '', true, ['msgCode' => 'L0154']);
            }
            if (!$name) ajaxmsg('真实姓名不能为空', 0, '', true, ['msgCode' => 'L0066']);
            if (!$id_card) ajaxmsg('身份证号不能为空', 0, '', true, ['msgCode' => 'L0143']);
            if (Db::name('member')->where('id_card', $id_card)->count()) {
                ajaxmsg('该身份证号已绑定', 0, '', true, ['msgCode' => 'L0177']);
            }
            if (!isset($auth_type) || !in_array($auth_type, ['0', '1'])) ajaxmsg('请求参数错误', 0, '', true, ['msgCode' => 'L0020']);
            if ($auth_type == 0 && !$P1) ajaxmsg('请上传身份证头像面图片', 0, '', true, ['msgCode' => 'L0144']);
            if ($auth_type == 0 && !$P2) ajaxmsg('请上传身份证国徽面图片', 0, '', true, ['msgCode' => 'L0145']);
            if ($auth_type == 1 && !$P3) ajaxmsg('请上传护照图片', 0, '', true, ['msgCode' => 'L0146']);
            $data = ['name' => $name, 'id_card' => $id_card, 'auth_time' => time(), 'auth_type' => $auth_type];
            if ($auth_type == '0') {
                $data['card_pic_hand'] = $post['card_pic_hand'];
                $data['card_pic_back'] = $post['card_pic_back'];
            }
            if ($auth_type == '1') {
                $data['passport_pic'] = $post['passport_pic'];
            }
            unset($data['token']);
            $data['is_sub']  = 1;
            $data['id_auth'] = 0;
//            file_put_contents('test.txt',var_export($data,true).PHP_EOL,FILE_APPEND);
            $res = Db::name('member')->where('id', $mid)->update($data);
            if ($res) {
                ajaxmsg('实名认证申请已提交', 1, '', true, ['msgCode' => 'L0149']);
            }
        }
        ajaxmsg('提交资料不能为空', 0, '', true, ['msgCode' => 'L0137']);
    }

//身份证图片上传
    public
    function upload()
    {
        $mid = MID;
        if (empty($mid)) {
            $mid = $this->isLogin();
        }
        $datalist = member::getMemberInfoByID($mid);
        if ($datalist['id_auth'] == 1) {
            ajaxmsg('您已经实名认证，不能更改', 0, '', true, ['msgCode' => 'L0064']);
        }
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ images目录下
        $info = $file->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'images');
        if ($info) {
            // 成功上传后 获取上传信息
            $data['card_pic'] = $info->getSaveName();
            $map['id']        = $mid;
            $res              = Db::name('member')->where($map)->update($data);
            if ($res) {
                $info->getSaveName();
                ajaxmsg('上传成功', 1, '', true, ['msgCode' => 'L0139']);
            } else {
                ajaxmsg('保存失败', 0, '', true, ['msgCode' => 'L0140']);
            }
        } else {
            ajaxmsg($file->getError(), 0);
        }
    }

    /**
     * 修改密码
     */
    public
    function password()
    {
        $mid = MID;
        if (empty($mid)) {
            $mid = $this->isLogin();
        }
        $post = input();
        if (!empty($post)) {
            #服务端验证密码
            $result = $this->validate($post['newpwd'], 'Member.password');
            if ($post['subpwd'] !== $post['newpwd']) {
                ajaxmsg('新密码和确认密码不一致', 0, '', true, ['msgCode' => 'L0068']);
            }
            $newpwd = Hash::make((string)$post['newpwd']);
            if (isset($post['oldpwd'])) {
                $c   = Db::name('member')->where(["id" => $mid])->find();
                $old = $post['oldpwd'];
                if ($post['oldpwd'] == $post['newpwd']) {
                    ajaxmsg('新密码与老密码不能一样', 0, '', true, ['msgCode' => 'L0069']);
                }
                if (empty($c['passwd'])) {
                    $newid = Db::name('member')->where(["id" => $mid])->update(['passwd' => $newpwd]);
                    if ($newid) {
                        ajaxmsg('设置成功', 1);
                    } else ajaxmsg('设置失败，请重试', 0);
                } else {
                    if (Hash::check((string)$old, $c['passwd'])) {
                        $newid = Db::name('member')->where(["id" => $mid])->update(['passwd' => $newpwd]);
                        if ($newid) {
                            ajaxmsg('设置成功', 1);
                        }
                        ajaxmsg('设置失败，请重试', 0);
                    } else {
                        ajaxmsg('原支付密码错误，请重试', 0, '', true, ['msgCode' => 'L0070']);
                    }
                }
            } else {
                $newid = Db::name('member')->where(["id" => $mid])->update(['passwd' => $newpwd]);
                if ($newid) {
                    ajaxmsg('设置成功', 1);
                } else ajaxmsg('设置失败，请重试', 0);
            }
        } else {
            ajaxmsg('设置失败，请重试', 0);
        }
    }

    /**
     * 支付密码
     */
    public
    function paypass()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        $post = input();
        if (!empty($post)) {
            #服务端验证支付密码
            $result = $this->validate($post['newpwd'], 'Member.password');
            if ($post['subpwd'] !== $post['newpwd']) {
                ajaxmsg('新密码和确认密码不一致', 0, '', true, ['msgCode' => 'L0068']);
            }
            $newpwd = Hash::make((string)$post['newpwd']);
            if (isset($post['oldpwd'])) {
                $c   = Db::name('member')->where(["id" => $mid])->find();
                $old = $post['oldpwd'];
                if (Hash::check((string)$old, $newpwd)) {
                    ajaxmsg('新密码与老密码不能一样', 0, '', true, ['msgCode' => 'L0069']);
                }
                if (empty($c['paywd'])) {
                    $newid = Db::name('member')->where(["id" => $mid])->update(['paywd' => $newpwd]);
                    if ($newid) {
                        ajaxmsg('设置成功', 1);
                    } else ajaxmsg('设置失败，请重试', 0);
                } else {
                    if (Hash::check((string)$old, $c['paywd'])) {
                        $newid = Db::name('member')->where(["id" => $mid])->update(['paywd' => $newpwd]);
                        if ($newid) {
                            ajaxmsg('设置成功', 1);
                        } else ajaxmsg('设置失败，请重试', 0);
                    } else {
                        ajaxmsg('原支付密码错误，请重试', 0, '', true, ['msgCode' => 'L0070']);
                    }
                }
            } else {
                $newid = Db::name('member')->where(["id" => $mid])->update(['paywd' => $newpwd]);
                if ($newid) {
                    ajaxmsg('设置成功', 1);
                } else ajaxmsg('设置失败，请重试', 0);
            }
        } else {
            ajaxmsg('设置失败，请重试', 0);
        }
    }

    /*
     * 头像
     */
//    public function uploadImg(){
//
//        $mid=MID;
//        if(empty($mid)){
//            ajaxmsg('登陆后才能操作',0);
//        }
//
//        // 获取表单上传文件 例如上传了001.jpg
//        $file = request()->file('image');
//        // 移动到框架应用根目录/public/uploads/ images目录下
//        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'images');
//        if($info){
//            // 成功上传后 获取上传信息
//            $saveFile =$info->getSaveName();
//            $newPicPath1 = '/uploads/images';
//            $saveFile = $newPicPath1.'/'.$saveFile;
//            $newid = Db::name('member')->where(["id"=>$mid])->update(['head_img'=>$saveFile]);
//            $saveFile = str_replace("\\",'/',$saveFile);
//            $filePath = 'http://'.$_SERVER['HTTP_HOST'].'/'.$saveFile;
//            if($newid){
//                ajaxmsg('上传成功',1,$filePath);
//            }else{
//                ajaxmsg('保存失败',0);
//            }
//        }else{
//            ajaxmsg($file->getError(),0);
//        }
//    }
    public
    function uploadImg()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        $type = request()->param('type');
        // 移动到框架应用根目录/public/uploads/ images目录下
        $info = $file->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'images');
        if ($info) {
            // 成功上传后 获取上传信息
            $saveFile    = $info->getSaveName();
            $newPicPath1 = '/uploads/images';
            $saveFile    = $newPicPath1 . '/' . $saveFile;
            if (!$type) {
                $newid = Db::name('member')->where(["id" => $mid])->update(['head_img' => $saveFile]);
            }
            $saveFile = str_replace("\\", '/', $saveFile);
            $filePath = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $saveFile;
            if ($type) {
                if ($filePath) ajaxmsg('上传成功', 1, $filePath);
                else ajaxmsg('保存失败', 0);
            } else {
                if ($newid) {
                    ajaxmsg('上传成功', 1, $filePath);
                } else {
                    ajaxmsg('保存失败', 0);
                }
            }
        } else {
            ajaxmsg($file->getError(), 0);
        }
    }

    /**
     *Desc:邮箱发送验证码
     * @return void
     *author:oszpac
     *date:2023-05-30
     */
    public
    function email_send_code()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg(lang('L0097'), 0);
        }
        $post  = input();
        $email = $post['email'];
        if (empty($email))
            ajaxmsg(lang('L0020'), 0);
        $id = Db::name('member')
            ->where('email', 'eq', $email)
            ->where('is_del', 0)
            ->value('id');
        if ($id)
            ajaxmsg('该邮箱已经注册会员，请更换邮箱！', 0, '', true, ['msgCode' => 'L0159']);
        //生成随机六位数，不足六位两边补零
        $code    = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
        $subject = lang('L0150');
        $body    = '<H4><center>' . $subject . '</center></H4><H3><center style="color:#9373ef">' . $code . '</center></H3>';
        $emailer = new Email();
        $ret     = $emailer->send($email, $subject, $body);
        if ($ret) {
            $key    = 'user.email.code.' . $mid;
            $value  = md5($code);
            $expire = 300;
            setexredis($key, $expire, $value);
            $key    = 'user.email.' . $mid;
            $value  = $email;
            $expire = 300;
            setexredis($key, $expire, $value);
            ajaxmsg(lang('L0152'), 1);
        } else {
            ajaxmsg(lang('L0135'), 0);
        }
    }

//绑定邮箱
    public function bindMobile()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg(lang('L0097'), 0);
        }
        $post     = input();
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $reg_code = input('reg_code', '86'); //国家地区号码
        if (empty($mobile) || empty($code))
            ajaxmsg(lang('L0020'), 0);
        if (!is_numeric($mobile))
            ajaxmsg('手机号不正确', 0, '', true, ['msgCode' => 'V0006']);
        $where    = [
            'mobile'   => $mobile,
            'reg_code' => $reg_code,
            'status'   => 1
        ];
        $isExists = Db::name('member')->where($where)->where('is_del', 0)->count();
        if ($isExists)
            ajaxmsg('该手机号已经注册', 0, '', true, ['msgCode' => 'L0053']);
        //临时关闭验证短信
        $check_code = aliCheckSMSCode($mobile, $code);
        $check_code !== 1 && ajaxmsg('验证码错误', 0, '', true, ['msgCode' => 'L0054']);
        #  $pay_pwd = substr($mobile, -6, 6);
        $data  = [
            'mobile'   => $mobile,
            'reg_code' => $reg_code,
            'paywd'    => Hash::make((string)123456)
        ];
        $newid = Db::name('member')->where(["id" => $mid])->update($data);
        if ($newid) ajaxmsg(lang('L0126'), 1);
        else ajaxmsg(lang('L0127'), 0);
    }

    public
    function bindEmail()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg(lang('L0097'), 0);
        }
        $post  = input();
        $code  = $post['code'];
        $email = $post['email'];
        if (empty($email) || empty($code))
            ajaxmsg(lang('L0020'), 0);
        //验证邮箱是否前后一致
//        $key = 'user.email.' . $mid;
//        $cache_email = getredis($key);
//        if ($cache_email != $email)
//            ajaxmsg(lang('L0156'), 0);
//        $key = 'user.email.code.' . $mid;
//        $md5_code = getredis($key);
//        if (md5($code) != $md5_code)
//            ajaxmsg(lang('L0153'), 0);
        $check_code = checkEmaiCode($email, $code);
        true !== $check_code && ajaxmsg(lang('L0153'), 0);
        $id = Db::name('member')
            ->where('email', 'eq', $email)
            ->where('is_del', 0)
            ->value('id');
        if ($id)
            ajaxmsg('该邮箱已经注册会员，请更换邮箱！', 0, '', true, ['msgCode' => 'L0159']);
        $newid = Db::name('member')->where(["id" => $mid])->update(['email' => $email]);
        if ($newid) {
            ajaxmsg(lang('L0126'), 1);
        } else ajaxmsg(lang('L0127'), 0);
    }

    public
    function email_reset_psw()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg(lang('L0097'), 0);
        }
        $post           = input();
        $code           = $post['code'];
        $password       = $post['password'];
        $check_password = $post['check_password'];
        if (empty($code) || empty($password) || empty($check_password))
            ajaxmsg(lang('L0020'), 0);
        if ($password !== $check_password)
            ajaxmsg(lang('L0068'), 0);
        $key      = 'user.email.code.' . $mid;
        $md5_code = getredis($key);
        if (md5($code) != $md5_code)
            ajaxmsg(lang('L0153'), 0);
        $newpwd = Hash::make((string)$password);
        $newid  = Db::name('member')->where(["id" => $mid])->update(['passwd' => $newpwd]);
        if ($newid) {
            ajaxmsg(lang('L0126'), 1);
        } else ajaxmsg(lang('L0127'), 0);
    }

    function showGoogleAuth()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg(lang('L0097'), 0);
        }
        $data    = [];
        $db_data = Db::name('member')
            ->where(['id' => $mid])
            ->field('mobile,email,google_auth,google_qrcod_url')
            ->find();
//    if ($db_data['google_auth'] && $db_data['google_qrcod_url']) {
//      $data['secret']    = $db_data['google_auth'];
//      $data['qrcod_url'] = $db_data['google_qrcod_url'];
//    } else {
//    }
        $google   = new \GoogleAuthenticator();
        $name     = empty($db_data['mobile']) ? $db_data['email'] : $db_data['mobile'];
        $app_name = config('web_operation_platform');
        // 生成谷歌key
        $secret = $google->createSecret();
        // 将谷歌加密key信息保存，并生成二维码链接
        $qrcod_url         = $google->getQRCodeGoogleUrl($name, $secret, $app_name);
        $data['secret']    = $secret;
        $data['qrcod_url'] = $qrcod_url;

        if ($data) {
            ajaxmsg('获取成功', 1, $data, true, ['msgCode' => 'L0014']);
        } else {
            ajaxmsg('申请失败，请联系客服', 0, $data, true, ['msgCode' => 'L0029']);
        }
    }

    public
    function setGoogleAtuh()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg(lang('L0097'), 0);
        }
        $secret      = input('secret', '');
        $code        = input('code', '');
        $pwd         = input('password', '');
        $MemberModel = new MemberModel;
        $db_data     = $MemberModel->where(['id' => $mid])
            ->field('mobile,passwd,email,google_auth,google_qrcod_url')
            ->find();

        if (!empty($db_data['google_auth'])) {

            if (!Hash::check((string)$pwd, $db_data['passwd'])) {

                ajaxmsg('登录密码错误，请重新输入', 0, '', true, ['msgCode' => 'L0178']);
            }
        }

        if (!empty($secret) && !empty($code)) {
            $google      = new \GoogleAuthenticator();
            $checkResult = $google->verifyCode($secret, $code, 1);

            if ($checkResult) {

                Db::name('member')
                    ->where('id', $mid)
                    ->update(['google_auth' => $secret, 'google_qrcod_url' => $code]);

                ajaxmsg('设置成功', 1, '', true, ['msgCode' => 'L0126']);
            } else {
                ajaxmsg('谷歌验证码不正确', 0, '', true, ['msgCode' => 'L0179']);
            }
        } else {
            ajaxmsg('参数不正确', 0, '', true, ['msgCode' => 'L0180']);
        }
    }

//修改用户信息
    public
    function updateUserData()
    {
        $mid = MID;
        if (empty($mid)) {
            ajaxmsg(lang('L0097'), 0);
        }
        $email       = input('email', '');
        $nickname    = input('nickname', '');
        $code        = input('code', '');
        $update_data = [];
        if ($nickname) {
            $nickname = userTextEncode($nickname);//处理特殊字符
            if (Db::name('member')->where('nickname', $nickname)->where('is_del', 0)->count())
                ajaxmsg('该昵称已被占用，请更换昵称！', 0, '', true, ['msgCode' => 'L0173']);
            $update_data['nickname'] = $nickname;
        }
        if ($email) {
            if (empty($code))
                ajaxmsg(lang('L0020'), 0);
            //验证邮箱是否前后一致
            $key         = 'user.email.' . $mid;
            $cache_email = getredis($key);
            if ($cache_email != $email)
                ajaxmsg(lang('L0156'), 0);
            $key      = 'user.email.code.' . $mid;
            $md5_code = getredis($key);
            if (md5($code) != $md5_code)
                ajaxmsg(lang('L0153'), 0);
            if (Db::name('member')->where('email', $email)->where('is_del', 0)->where('id', $mid)->count())
                ajaxmsg('该邮箱已被占用，请更换邮箱！', 0, '', true, ['msgCode' => 'L0159']);
            $update_data['email'] = $email;
        }
        $res = Db::name('member')->where(["id" => $mid])->update($update_data);
        if ($res) {
            ajaxmsg(lang('L0126'), 1);
        } else ajaxmsg(lang('L0127'), 0);
    }
    /*
     * END
     */
}