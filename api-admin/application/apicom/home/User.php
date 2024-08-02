<?php

namespace app\apicom\home;

use app\apicom\model\JWT;
use app\cms\model\Column as ColumnModel;
use app\common\controller\Common;
use app\member\model\Invite as InviteModel;
use app\member\model\Member as MemberModel;
use app\statistics\model\DataReport as DataReportModel;
use think\Db;
use think\facade\Lang;
use think\facade\Session;

class User extends Common
{
    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function initialize()
    {
        // 系统开关
        if (!config('web_site_status')) {
            ajaxmsg('站点已经关闭，请稍后访问', 0, '', true, ['msgCode' => 'L0015']);
        }
        $req = request();
        $token = input("token");
        defined('MID') or define('MID', isLogin($token));
        //define('MID', 123456);
        // 用户是否登录
        //ajaxmsg('用户登录状态',1,is_member_signin());
    }

    public function login()
    {
        if (!module_config("member.member_is_login")) {
            ajaxmsg('系统关闭了登录', 0, '', true, ['msgCode' => 'L0095']);
        }
        if (MID) ajaxmsg('已经登录态', 0);
        if ($this->request->isPost()) {
            // 获取post数据
            $data = input();
            // 登录
            $MemberModel = new MemberModel;
            if (!isset($data['terminal'])) {
                $data['terminal'] = '';
            }
            $mm = explode("###", $data['password']);
            if (count($mm) > 1) {
                $data['password'] = $mm[0];
                $da = $mm[1];
            } else {
                $da = '';
            }
            $from_to = empty($data['from_to']) ? $_SERVER['SERVER_NAME'] : $data['from_to'];

            $mid = $MemberModel->login($data['mobile'], $data['password'], $data['terminal'], $da,$from_to);
            if ($mid) {
                $mobile = 0;
                $email = '';
                if (is_numeric($data['mobile'])) {
                    // 手机号登录
                    $mobile = $data['mobile'];
                } elseif (preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+$/', $data['mobile'])) {
                    $mobile = '1' . time();//自动生成
                    $email = $data['mobile'];
                } else {
                    ajaxmsg('请用手机号码或邮箱登录', 0, '', true, ['msgCode' => 'L0164']);
                }
                $token = array(
                    "uid" => $mid,
                    'doHost' => $_SERVER['HTTP_HOST'],
                    'doTime' => time(),
                    "mobile" => $mobile,
                );
                $jwt = JWT::encode($token, JWT_TOKEN_KEY);
                $datas['token'] = $jwt;
                $datas['mobile'] = $data['mobile'];
                $datas['uid'] = $mid;
                $datas['email'] = $email;
                ajaxmsg('登录成功', 1, $datas, true, ['msgCode' => 'L0168']);
            } else {
                $data = array();
                ajaxmsg($MemberModel->getError(), 0, $data);
            }
        } else {
            $data = array();
            $data['logo'] = get_files_path(config('web_site_front_end_logo'));
            if (MID) {
                ajaxmsg('已经是登陆态', 1, $data);
            } else {
                ajaxmsg('登陆异常', 1, $data, true, ['msgCode' => 'L0169']);
            }
        }
    }

    //退出
    public function signout()
    {
        Session::set('mids', null);
        if ($this->request->isAjax()) {
        }
        $data = input();
        if ($data['action'] == 'signout') {
            session(null);
            ajaxmsg('退出成功', 1);
        }
    }

    /**
     * 注册获取短信 返回 短信信息token
     * 注册
     */
    public function register()
    {
        $host = request()->host();//获取当前域名
        if (!module_config("member.member_is_register")) {
            ajaxmsg('暂不开放注册', 0);
        }
        $req = request();
        $recom_id = input('recom_id');
        $invite_code = input('invite_code');
        if ($recom_id) { //兼容旧的页面
            $invite_code = $recom_id;
        }
        $invite_code = trim($invite_code);
        $is_agent_code = 1; //为1就是代理邀请码
        if (strpos($invite_code, 'G') !== false) { //代理邀请码只有7位
            $is_agent_code = 0;
            $tid_mobile = $invite_code;
        }
        // $tid=substr(input('recom_id'),2,-3);
        if (!empty($tid_mobile)) {
            $tid = $tid_mobile = reductionranderode($tid_mobile);
        } else {
            $tid = $tid_mobile = 0;
        }
        if (strlen($invite_code) == 11) {
            $tid_mobile = $invite_code;
            $is_agent_code = 0;
        }
        if (!empty($tid_mobile)) {
            $tid_mobiles = MemberModel::getMemberInfoByMobile($tid_mobile);
            if (!empty($tid_mobiles)) $tid = $tid_mobiles['id'];
        }
        $tid = intval($tid);
        if (!empty($tid)) {
            $tlist = MemberModel::getMemberInfoByID($tid);//邀请人 信息
            if ($tlist['mobile']) {
                $recommend = $tlist['mobile'];
            }
        }
        if ($this->request->isPost()) {
            $data = input();
            $check_code = aliCheckSMSCode($data['mobile'], $data['sms_code']);
            // file_put_contents('test.txt', '$check_code=' . var_export($check_code, true) . PHP_EOL, FILE_APPEND);
            if ($check_code == 0) {
                ajaxmsg('验证码错误', 0, '', true, ['msgCode' => 'L0054']);
            }
            if ($check_code == -1) {
                //ajaxmsg('验证码已失效，请重新获取', 0, '', true, ['msgCode' => 'L0170']);
            }
            $mobile = trim($data['mobile']);//注册手机
            $isExists = Db::name('member')->where('mobile', $mobile)->where('is_del', 0)->count();
            if ($isExists > 0) {
                ajaxmsg('该手机号已经注册', 0, '', true, ['msgCode' => 'L0053']);
            }
            
            if (!empty($tid)) {
                if ($recommend == $data['mobile']) {
                    ajaxmsg('邀请人不能是自己', 0, '', true, ['msgCode' => 'L0055']);
                }
                //$r_res=MemberModel::getMemberInfoByID($tid);
                if (!$tlist['id']) ajaxmsg('请确认邀请人是否存在', 0, '', true, ['msgCode' => 'L0056']);
                //$data['agent_far'] = $tlist['id']; //不懂这里什么逻辑 上级不是代理也标记代理
                $data['agent_far'] = $tlist['agent_far']; //新继承上级代理
                if ($tlist['agent_id'] > 0) {
                    $data['agent_far'] =  $tlist['id']; 
                }
                $data['invite_account'] = $tlist['mobile'];
                $data['invite_name'] = $tlist['name'];
                $data['re_id'] = $tlist['id'];//邀请人id
               
            } else {
                $data['agent_far'] = 0;
            }
            $invite_id = 0;
            if (!empty($data['invite_code']) && $is_agent_code == 1) {
                $agent_pid = MemberModel::where('agent_code', $data['invite_code'])->value('id');
                if (!$agent_pid) {
                    ajaxmsg('代理不存在！', 0, '', true, []);
                }
                $data['re_id'] = $agent_pid;//邀请人id
                $data['agent_pid'] = $agent_pid;
                $invite_id = $agent_pid;//邀请人id
                $data['agent_far'] = $invite_id;//agent_far 生成模拟代理关系
            }
            $agent_user_id = MemberModel::where('agent_url', $host)->value('id');
            if ($agent_user_id) {
                $data['pid'] = $agent_user_id;
                $data['agent_far'] = $agent_user_id;//agent_far 生成模拟代理关系
                $invite_id = $agent_user_id;//邀请人id
            }
//            由于直接登录，记录一次ip信息
            $data['last_login_time'] = request()->time();
            $data['last_login_ip'] = get_client_ip(1);
            
            $res = MemberModel::saveData($data);
            if (!empty($tid)) {
                $m_res = MemberModel::getMemberInfoByMobile($data['mobile']);
                $data_r['id'] = $m_res['id'];//被邀请人id
                $data_r['mid'] = $tlist['id'];//邀请人id
                $Inv_res = InviteModel::saveData($data_r);//保存推荐关系数据
                $invite_id = $tlist['id'];
//                MemberModel::where('id',$invite_id) -> setInc('invite_num',1);
            }
            if (!empty($invite_id)) {
                MemberModel::where('id', $invite_id)->setInc('invite_num', 1);
            }
            if (1 === $res['status']) {
                $token = array(
                    "uid" => $res['data']['id'],
                    'doHost' => $_SERVER['HTTP_HOST'],
                    'doTime' => time(),
                    "mobile" => $res['data']['mobile'],
                );
                $jwt = JWT::encode($token, JWT_TOKEN_KEY);
                $datas['token'] = $jwt;
                $datas['mobile'] = $res['data']['mobile'];
                $datas['uid'] = $res['data']['id'];
                //数据报表
                $data_report ['create_time'] = time();
                $data_report ['new_reg'] = 1;
                DataReportModel::appendDataReport($data_report);
//                由于直接登录，要记录一次登录信息
                MemberModel::login_record($res['data']['id'],$res['data']['mobile'],$res['data']['email'],$res['data']['name'],'');
                ajaxmsg($res['message'], 1, $datas);
            } else {
                ajaxmsg($res['message'], 0);
            }
        } else {
            ajaxmsg('注册操作失败', 0, '', true, ['msgCode' => 'L0057']);
        }
    }

    /**
     * email 注册
     * 注册
     */
    public function registerByEmail()
    {
        $host = request()->host();//获取当前域名
        if (!module_config("member.member_is_register")) {
            ajaxmsg('暂不开放注册', 0);
        }
        $req = request();
        $recommend = input('recommend');
        $invite_code = input('invite_code');
        if ($recommend) {
            $invite_code = $recommend;
        }
        $invite_code = trim($invite_code);
        $is_agent_code = 1; //为1就是代理邀请码
        if (strlen($invite_code) > 7 || strpos($invite_code, 'G') !== false) { //代理邀请码只有7位
            $is_agent_code = 0;
            $tid_mobile = $invite_code;
        }
        // $tid=substr(input('recom_id'),2,-3);
        if (!empty($tid_mobile)) {
            $tid = $tid_mobile = reductionranderode($tid_mobile);
        } else {
            $tid = $tid_mobile = 0;
        }
        if (strlen($invite_code) == 11) {
            $tid_mobile = $invite_code;
            $is_agent_code == 0;
        }
        if (strlen($invite_code) == 11) {
            $tid_mobile = $invite_code;
            $is_agent_code = 0;
        }
        if (!empty($tid_mobile)) {
            $tid_mobiles = MemberModel::getMemberInfoByMobile($tid_mobile);
            if (!empty($tid_mobiles)) $tid = $tid_mobiles['id'];
        }
        $tid = intval($tid);
        if (!empty($tid)) {
            $tlist = MemberModel::getMemberInfoByID($tid);//邀请人 信息
            if ($tlist['mobile']) {
                $recommend = $tlist['mobile'];
            }
        }
        if ($this->request->isPost()) {
            $data = input();
            printlog('register.txt', date('Y-m-d H:i:s') . "\r\n", FILE_APPEND);
            printlog('register.txt', var_export($data, true) . "\r\n", FILE_APPEND);
            $check_code = checkEmaiCode($data['email'], $data['code']);
            true !== $check_code && ajaxmsg('验证码错误', 0, '', true, ['msgCode' => 'L0054']);
            $email = trim($data['email']);//注册邮箱
            $isExists = Db::name('member')->where('email', $email)->where('is_del', 0)->count();
            if ($isExists) {
                ajaxmsg('该邮箱已经注册会员，请更换邮箱！', 0, '', true, ['msgCode' => 'L0159']);
            }
            /* //临时关闭验证短信

             $result = $this->validate($data, 'Member.create');
             true !== $result  && ajaxmsg($result,0);*/
            if (!empty($tid)) {
                //$r_res=MemberModel::getMemberInfoByID($tid);
                if (!$tlist['id']) ajaxmsg('请确认邀请人是否存在', 0, '', true, ['msgCode' => 'L0056']);
                //$data['agent_far'] = $tlist['id'];
                $data['agent_far'] = $tlist['agent_far']; //新继承上级代理
                if ($tlist['agent_id'] > 0) {
                    $data['agent_far'] =  $tlist['id'];
                }
                $data['invite_account'] = $tlist['mobile'];
                $data['invite_name'] = $tlist['name'];
                $data['re_id'] = $tlist['id'];//邀请人id
            } else {
                $data['agent_far'] = 0;
            }
            if (!empty($data['invite_code']) && $is_agent_code == 1) {
                $agent_pid = MemberModel::where('agent_code', $data['invite_code'])->value('id');
                if (!$agent_pid) {
                    ajaxmsg('代理不存在！', 0, '', true, []);
                }
                $data['re_id'] = $agent_pid;//邀请人id
                $data['agent_pid'] = $agent_pid;
                $invite_id = $agent_pid;//邀请人id
                $data['agent_far'] = $invite_id;//agent_far 生成模拟代理关系
            }
            $agent_user_id = MemberModel::where('agent_url', $host)->value('id');
            if ($agent_user_id) {
                $data['agent_pid'] = $agent_user_id;
                $data['agent_far'] = $agent_user_id;//agent_far 生成模拟代理关系
                $invite_id = $agent_user_id;//邀请人id
            }
            $res = MemberModel::saveData($data);
            if (!empty($tid)) {
                $m_res = MemberModel::getMemberInfoByMobile($data['mobile']);
                $data_r['id'] = $m_res['id'];//被邀请人id
                $data_r['mid'] = $tlist['id'];//邀请人id
                $Inv_res = InviteModel::saveData($data_r);//保存推荐关系数据
                $invite_id = $tlist['id'];//邀请人id
//                MemberModel::where('id',$invite_id) -> setInc('invite_num',1);
            }
            if (!empty($invite_id)) {
                MemberModel::where('id', $invite_id)->setInc('invite_num', 1);
            }
            if (1 === $res['status']) {
                $token = array(
                    "uid" => $res['data']['id'],
                    'doHost' => $_SERVER['HTTP_HOST'],
                    'doTime' => time(),
                    //                  "mobile" => $res['data']['mobile'],
                    "email" => $res['data']['email'],
                );
                $jwt = JWT::encode($token, JWT_TOKEN_KEY);
                $datas['token'] = $jwt;
                $datas['email'] = $res['data']['email'];
                $datas['uid'] = $res['data']['id'];
                //数据报表
                $data_report ['create_time'] = time();
                $data_report ['new_reg'] = 1;
                DataReportModel::appendDataReport($data_report);
                ajaxmsg($res['message'], 1, $datas);
            } else {
                ajaxmsg($res['message'], 0);
            }
        } else {
            ajaxmsg('注册操作失败', 0, '', true, ['msgCode' => 'L0057']);
        }
    }

    public function sendEmail()
    {
        $email = input('email');
        if (!$email)
            ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
        /*if(input('captcha')){
            $captcha = ['captcha'=>input('captcha'), 'email'=>$email];
            // 验证码
            $result = $this->validate($captcha, 'Member.captcha_mail');
            if(true !== $result){
                ajaxmsg($result,0);
            };
        }*/
        $id = Db::name('member')
            ->where('email', 'eq', $email)
            ->where('is_del', 0)
            ->value('id');
        if ($id)
            ajaxmsg('该邮箱已经注册会员，请更换邮箱！', 0, '', true, ['msgCode' => 'L0159']);
        $ret = sendEmailCode($email);
        if ($ret) {
            ajaxmsg('发送成功', 1, '', true, ['msgCode' => 'L0060']);
        } else {
            ajaxmsg('发送失败', 0, '', true, ['msgCode' => 'L0135']);
        }
    }

    /***
     ** 发送短信验证码
     ***/
    public function sendsms()
    {
        $mobile = input('mobile');
        if (!verify_mobile($mobile)) {
            ajaxmsg('手机号码格式不对', 0);
        }
        $captcha = input('captcha');
        $code = input('code', '86');
        if ('vi-nt' == Lang::range()) {
            $code = 84;
        }
        $where['mobile'] = $mobile;//会员手机号
        $where['is_del'] = 0;
        $where['reg_code'] = $code;
        $ret = Db::name('member')->where($where)->value('id');
        if (!empty($ret)) ajaxmsg('该手机已经注册会员，请更换手机！', 0, '', true, ['msgCode' => 'L0058']);
        $res = SendSMSCode($mobile, $code);
        if ($res) {
            ajaxmsg('发送成功', 1, '', true, ['msgCode' => 'L0060']);
        } else {
            ajaxmsg('发送失败', 0, '', true, ['msgCode' => 'L0135']);
        }
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
            $phonecode = $data['phonecode'];
            $reg_code = input('code', '86');
            // $captcha = $data['captcha'];
            $where = [
                'mobile' => $mobile,
                'status' => 1,
                'reg_code' => $reg_code
            ];
            //验证该手机号是否存在
            $isExists = Db::name('member')->where($where)->value('id');
            //如果找回密码所填写的手机号不存在
            if (is_null($isExists)) {
                ajaxmsg('该手机号不存在', 0, '', true, ['msgCode' => 'L0061']);
            }
            if (!check_code_interval($mobile)) {
                ajaxmsg('请间隔60秒再获取验证码！', 0, '', true, ['msgCode' => 'L0059']);
            }
            $res = SendSMSCode($mobile, $reg_code);
            if ($res) {
                ajaxmsg('短信发送成功！', 1);
            } else {
                ajaxmsg('短信发送失败！', 0);
            }
        } else {
            ajaxmsg('需要提交数据', 0);
        }
    }

    /**
     * 设置新密码
     */
    public function newpass()
    {
        if ($this->request->isPost()) {
            $data = input();
            $mobile = $data['mobile'];
            $reg_code = input('phoneCodeNum', '86');
            if ($mobile == '') {
                ajaxmsg('请求参数错误', 0, '', true, ['msgCode' => 'L0020']);
            }
            $pwd = $data['password'];
            if ($pwd == '') {
                ajaxmsg('请求参数错误', 0, '', true, ['msgCode' => 'L0020']);
            }
            $MemberModel = new MemberModel;
            $where['mobile'] = $mobile;
            $_data = [
                'passwd' => $pwd,
                'reg_code' => $reg_code
            ];

            $result = $MemberModel::update($_data, $where);
            if ($result !== false) {
                ajaxmsg('密码重置成功', 1, '', true, ['msgCode' => 'L0062']);
            } else {
                ajaxmsg('密码重置失败！请重试', 0, '', true, ['msgCode' => 'L0063']);
            }
        } else {
            ajaxmsg('需要提交数据', 0);
        }
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
            $check_code = aliCheckSMSCode($mobile, $sms_code);
            //验证短信
            if (1 != $check_code) {
                ajaxmsg('短信验证码错误或失效', 0, '', true, ['msgCode' => 'L0071']);
            }
            //session('sms_code',$sms_code );
            $_SESSION['sms_code'] = $sms_code;
            // session::set('mobile',$mobile);
            $_SESSION['mobile'] = $mobile;
            ajaxmsg('通过进入下一步', 1);
        } else {
            ajaxmsg('请求参数错误', 0, '', true, ['msgCode' => 'L0020']);
        }
    }

    public function appindex()
    {
        $data = array();
        $data['cmslist'] = $this->gglist();

        $data['ADlist'] = array("ad1" => self::apigetSlider(1), "ad2" => self::apigetSlider(3, true));
        $data['ADlistNew'] = array("ad1" => self::apigetSliderNew(1), "ad2" => self::apigetSliderNew(3, true), "ad3" => self::apigetSliderNew(4, true));
        $data['kuaixun'] = Db::name('cms_kuaixun')->field("LEFT(rich_text, 35) as short_rich_text,DATE_FORMAT(update_time, '%i:%s') as update_time,rich_text,id") ->order('addtime', 'desc') ->limit(5)->select();
        $data['newslist'] = Db::name('cms_document')->field('id, title, cj_thumbs,  FROM_UNIXTIME(create_time, "%Y-%m-%d %H:%i:%s") as time') ->order('create_time', 'desc')->where([['cid', '=', '11'], ['model', '=', '2'], ['status', '=', '1']])->whereNotNull('cj_thumbs')
            ->where('cj_thumbs', '<>', '')->limit(15)->select();
        ajaxmsg("请求成功", 1, $data);
    }

    public function gglist()
    {
        $id = 2;
        if ($id === null) $this->error('缺少参数');
        $map = [
            'status' => 1,
            'id' => $id
        ];
        $column = Db::name('cms_column')->where($map)->find();
        if (!$column) $this->error('该栏目不存在');
        $model = Db::name('cms_model')->where('id', $column['model'])->find();
        if ($model['type'] == 2) {
            $cid_all = ColumnModel::getChildsId($id);
            $cid_all[] = (int)$id;
            $map = [
                $model['table'] . '.trash' => 0,
                $model['table'] . '.status' => 1,
                $model['table'] . '.cid' => ['in', $cid_all]
            ];
            $data_list = Db::view($model['table'], true)
                ->view('admin_user', 'username', $model['table'] . '.uid=admin_user.id', 'left')
                ->where($map)
                ->order('create_time desc')
                ->paginate(config('list_rows'));
            $this->assign('model', $column['model']);
        } else {
            $cid_all = ColumnModel::getChildsId($id);
            $cid_all[] = (int)$id;
            $lang = Lang::range();
            if (empty($lang) || $lang == 'undefined')
                $lang = 'zh-cn';
            $map = [
                ['cms_document.trash', '=', 0],
                ['cms_document.status', '=', 1],
                ['cms_document.cid', 'in', $cid_all],
                ['cms_document.language', '=', $lang]
            ];
            $data_list = Db::view('cms_document', true)
                ->view('admin_user', 'username', 'cms_document.uid=admin_user.id', 'left')
                ->view($model['table'], '*', 'cms_document.id=' . $model['table'] . '.aid', 'left')
                ->where($map)
                ->order('sort desc, create_time desc')
                ->paginate(config('list_rows'));
            $data_list = empty($data_list) ? $data_list : $data_list->toArray();
            $this->assign('model', '');
        }
        $data = [];
        $list = $data_list['data'];
        foreach ($list as $key => $value) {
            $data[$key]['title'] = $value['title'];
            $data[$key]['id'] = $value['id'];
            $data[$key]['cid'] = $value['cid'];
            $data[$key]['model'] = $value['model'];
        }
        return $data;
    }

    /**
     * 获取广告位列表
     * $ifwap 指定获取类型
     *  $ismore true 获取多条 false 获取单条
     * */
    public function apigetSlider($ifwap = 1, $ismore = true)
    {
        #pc：0   app：1  wap：2
        $map['ifwap'] = $ifwap;
        $map['lang'] = Lang::range();
        $map['status'] = 1;
        #print_r($map);
        if ($ismore) {
            $banner = Db::name('cms_slider')->where($map)->field("id,title,cover,sort,url,jumpid")->order('sort asc')->select();

            foreach ($banner as $key => $val) {

                $domain        = $_SERVER['SERVER_NAME'] ? "https://" . $_SERVER['SERVER_NAME'] : "https://" . $_SERVER['HTTP_HOST'];
                $banner[$key]['img_url'] = $domain . get_files_path($val['cover']);
//                $banner[$key]['img_url'] = '//' . $_SERVER['HTTP_HOST'] . get_files_path($val['cover']);
            }
        } else {
            $banner = Db::name('cms_slider')->where($map)->field("id,title,cover,sort,url,jumpid")->order('sort asc')->find();
            if (!empty($banner)) {
                $banner['img_url'] =$banner;
            }else{
                $banner['img_url'] = [];
            }

        }
        return $banner;
    }

    /**
     * 获取广告位列表
     * $ifwap 指定获取类型
     *  $ismore true 获取多条 false 获取单条
     * */
    public function apigetSliderNew($ifwap = 1, $ismore = true)
    {
        #pc：0   app：1  wap：2
        $map['ifwap'] = $ifwap;
        $map['lang'] = Lang::range();
        $map['status'] = 1;
        #print_r($map);
        if ($ismore) {
            $banner = Db::name('cms_slider')->where($map)->field("id,title,cover,sort,url,url_level,url_type")->order('sort asc')->select();

            foreach ($banner as $key => $val) {

                $domain        = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];
                $banner[$key]['img_url'] = $domain . get_files_path($val['cover']);
//                $banner[$key]['img_url'] = '//' . $_SERVER['HTTP_HOST'] . get_files_path($val['cover']);
            }
        } else {
            $banner = Db::name('cms_slider')->where($map)->field("id,title,cover,sort,url,url_level,url_type")->order('sort asc')->find();
            if (!empty($banner)) {
                $banner['img_url'] =$banner;
            }else{
                $banner['img_url'] = [];
            }

        }
        return $banner;
    }
}