<?php

namespace app\index\controller;

use think\Db;

/**
 * 分享控制器
 * @package app\index\controller
 */
class Share extends Home
{  /**
 * 初始化方法
 * @author 路人甲乙
 */

    protected $device;
    protected function initialize()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        //分别进行判断
        if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
            $type = 'ios';
        }
        if (strpos($agent, 'android')) {
            $type = 'android';
        }
        $this->device=$type;
    }
    public function index()
    {


        $code = input('url');
        $url = config('share_domain')."/wap/invite/" .$code;
        $this->assign('shareurl', $url);
        if ($this->device == "ios") {

            return $this->fetch('ios');
        }
        return $this->fetch('android');

    }

}