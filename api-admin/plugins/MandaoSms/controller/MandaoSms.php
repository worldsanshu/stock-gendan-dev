<?php

namespace plugins\MandaoSms\controller;

use app\common\controller\Common;

class MandaoSms extends Common
{
    public function sendSms($mobile, $msg, $ifuser = '')
    {
        $Config = plugin_config('MandaoSms');
        $pwds = $Config['username'] . $Config['password'];
        $sn = $Config['username'];
        $sign = $Config['tip'];
        $content = $sign . $msg;
        $pwds = strtoupper(md5($pwds));
        //$content = str_replace(array("#var#"),array($mobile), $msg);
        $res = self::mandaoSend($mobile, $sn, $content, $pwds);
        var_dump($res);
        return $res;
    }

    public function sms_status()
    {
        $db_config = plugin_config('MandaoSms');
        $pwds = $db_config['username'] . $db_config['password'];
        $sn = $db_config['username'];
        $pwds = strtoupper(md5($pwds));
        $d = @file_get_contents("http://sdk2.zucp.net:8060/webservice.asmx/balance?sn={$sn}&pwd={$pwds}", false);
        preg_match('/<string.*?>(.*?)<\/string>/', $d, $matches);
        if ($matches[1] < 0) {
            switch ($matches[1]) {
                case -2:
                    $d = "帐号/密码不正确或者序列号未注册";
                    break;
                case -4:
                    $d = "余额不足";
                    break;
                case -6:
                    $d = "参数有误";
                    break;
                case -7:
                    $d = "权限受限,该序列号是否已经开通了调用该方法的权限";
                    break;
                case -12:
                    $d = "序列号状态错误，请确认序列号是否被禁用";
                    break;
                default:
                    $d = "用户名或密码错误";
                    break;
            }
        } else {
            $d = $matches[1] . '条';
        }
        return $d;
    }

    public static function mandaoSend($mobile, $sn, $content, $pwds)
    {
        $flag = 0;
        $argv = array(
          'sn' => $sn,
          'pwd' => $pwds, //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
          'mobile' => $mobile,
          'content' => iconv("UTF-8", "gb2312//IGNORE", $content),
          'ext' => '',
          'stime' => '',
          'rrid' => ''
        );
        //构造要post的字符串
        $params = "";
        foreach ($argv as $key => $value) {
            if ($flag != 0) {
                $params .= "&";
                $flag = 1;
            }
            $params .= $key . "=";
            $params .= urlencode($value);
            $flag = 1;
        }
        $length = strlen($params);
        //创建socket连接
        $fp = fsockopen("sdk2.zucp.net", 8060, $errno, $errstr, 10) or exit($errstr . "--->" . $errno);
        //构造post请求的头
        $header = "POST /webservice.asmx/mt HTTP/1.1\r\n";
        $header .= "Host:sdk2.zucp.net\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . $length . "\r\n";
        $header .= "Connection: Close\r\n\r\n";
        //添加post的字符串
        $header .= $params . "\r\n";
        //发送post的数据
        fputs($fp, $header);
        $inheader = 1;
        file_put_contents("/wwwserver/wwwroot/new.com/log.txt", "数组内容\n" . var_export(feof($fp)), FILE_APPEND);
        file_put_contents("/wwwserver/wwwroot/new.com/log.txt", "数组内容\n" . var_export($fp), FILE_APPEND);
        while (!feof($fp)) {
            $line = fgets($fp, 1024); //去除请求包的头只显示页面的返回数据
            if ($inheader && ($line == "\n" || $line == "\r\n")) {
                $inheader = 0;
            }
            if ($inheader == 0) {
                // echo $line;
            }
        }
        $line = str_replace("<string xmlns=\"http://tempuri.org/\">", "", $line);
        $line = str_replace("</string>", "", $line);
        $result = explode("-", $line);
        if (count($result) > 1) {
            return true;
        } else {
            return true;
        }
    }
}

?>