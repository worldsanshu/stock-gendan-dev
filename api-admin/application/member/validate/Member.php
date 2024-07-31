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
namespace app\member\validate;

use think\Validate;

class Member extends Validate
{
    //定义验证规则
    protected $rule = [
      'mobile|用户名/手机号' => 'require|regex:^1\d{10}|unique:member',
      'name|姓名' => 'require|regex:/^[\x{4e00}-\x{9fa5}]{2,10}$/u',
      'id_card|身份证号码' => ['require', 'regex' => '/(^\d{15}$)|(^\d{17}([0-9]|X)$)/', 'unique' => 'member'],
        //'password|密码'  => 'require|length:6,32',
      'password|密码' => 'require|regex:/^(?![^a-zA-Z]+$)(?!\D+$)(^[a-zA-Z\d_]{6,16}$)/',
      'passwd|密码' => 'require|regex:/^(?![^a-zA-Z]+$)(?!\D+$)(^[a-zA-Z\d_]{6,16}$)/',
      'sms_code|手机验证码' => 'require',
      'captcha|验证码' => 'require|captcha',
      'mobiles' => 'require|regex:^1\d{10}',
      'email' => 'regex:\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*',
    ];
    //定义验证提示
    protected $message = [
      'name.require' => '请输入姓名',
      'name.regex' => '姓名格式错误',
      'id_card.require' => '请输入身份证号码',
      'id_card.unique' => '身份证已存在',
      'id_card.regex' => '身份证格式错误',
        //'password.require' => '密码不能为空',
        //'password.regex'  => '密码长度6-32位',
      'password.regex' => '密码格式有误，必须含有字母和数字,6-16个字符',
      'passwd.regex' => '密码格式有误，必须含有字母和数字,6-16个字符',
      'mobile.regex' => '手机号不正确',
      'mobile.unique' => '手机号码已注册',
      'mobile.require' => '请输入手机号',
      'mobiles.require' => '请输入手机号',
      'mobiles.regex' => '手机号不正确',
      'email.regex' => '邮箱格式不正确',
    ];
    //定义验证场景
    protected $scene = [
        //更新
      'update' => ['passwd', 'mobile'],
      'password' => ['password'],
      'realname' => ['name', 'id_card'],
      'urgent' => ['name', 'mobile', 'captcha'],
        //登录
      'signin' => ['mobile' => 'require', 'password', 'captcha'],
        //注册
      'reg' => ['mobile' => 'require', 'captcha'],
        // 身份证审核
      'update_card' => ['mobile', 'id_card'],
        //'create' => ['mobile', 'password', 'sms_code'],
      'create' => ['mobile', 'password'],
      'captcha' => ['captcha', 'mobile'],
      'captcha_mail' => ['captcha', 'email'],
      'editMoblie' => ['mobiles'],
      'register_sendsms' => ['mobile'],
        //新增
        'add' => ['mobile' => 'require', 'passwd' => 'require'],
    ];
}
