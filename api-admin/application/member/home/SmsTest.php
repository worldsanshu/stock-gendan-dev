<?php

namespace app\member\home;

use think\Controller;
use think\facade\Cache;

class SmsTest extends Controller
{
    function index()
    {
        return $this->fetch();
    }

    function getcode()
    {
        $R = ['status' => 0, "msg" => "", "data" => null];
        $mobile_txt = request()->param('mobile_txt');
        if (empty($mobile_txt)) {
            $R['msg'] = '号码不能为空';
            return $R;
        }
        $arr = explode('&', $mobile_txt);
        $data = array();
        foreach ($arr as $mobile) {
            $key = get_code_key($mobile);
            $code = Cache::get($key);
            $code = empty($code) ? '为空或已失效' : $code;
            $txt = '手机号码:' . $mobile . ';验证码:' . $code;
            $data[] = $txt;
        }
        $R['status'] = 1;
        $R['data'] = $data;
        return json($R);
    }

    function searchEmaiCode()
    {
        return $this->fetch();
    }

    function getEmailCode()
    {
        $R = ['status' => 0, "msg" => "", "data" => null];
        $email_txt = request()->param('email_txt');
        if (empty($email_txt)) {
            $R['msg'] = '号码不能为空';
            return $R;
        }
        $arr = explode('&', $email_txt);
        $data = array();
        foreach ($arr as $email) {
            $key = getEmailCodeCacheKey($email);
            $code = Cache::get($key);
            $code = empty($code) ? '为空或已失效' : $code;
            $txt = '邮箱地址:' . $email . ';验证码:' . $code;
            $data[] = $txt;
        }
        $R['status'] = 1;
        $R['data'] = $data;
        return json($R);
    }
}