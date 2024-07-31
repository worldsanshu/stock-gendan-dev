<?php
// +----------------------------------------------------------------------
// | 用户服务层
// +----------------------------------------------------------------------
namespace app\common\service;


class SignService
{
    //获得F10 接口sign 签名
    public function getF10DataSignStr()
    {
        $appID = env('F10DATA.app_id');
        $appSecret = env('F10DATA.app_secret');
        $time = time();
        $md = md5($time.$appID.$appSecret);
        return $time.'&'.$appID.'&'.$md;
    }
 
}