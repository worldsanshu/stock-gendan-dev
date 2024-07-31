<?php

namespace app\ipres\home;

use think\Db;

class Pasw
{
    public function checkPassword()
    {

//        / 设置密码
        $PASSWORD = "123456"; // 请替换成你的密码
        $data     = input();
        if (isset($data['password'])) {
            // 获取提交的密码
            $submittedPassword = $data['password'];
            // 验证密码
            if ($submittedPassword === $PASSWORD) {

                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    // 注意 X-Forwarded-For 可能包含多个 IP，如果是多个代理，第一个是离客户端最近的代理 IP
                    $client_ip = explode(',', $client_ip)[0]; // 取第一个 IP
                } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                    // 一些代理服务器会在 HTTP_CLIENT_IP 这个头里传递客户端 IP
                    $client_ip = $_SERVER['HTTP_CLIENT_IP'];
                } else {
                    // 如果以上都不是，则使用 REMOTE_ADDR
                    $client_ip = $_SERVER['REMOTE_ADDR'];
                }
                $ips     = $client_ip;
                $get_one = Db::table('lmq_ip_res')->where('ip', $ips)->find();
                if (!$get_one) {
                    $data = [
                        'ip'          => $ips,
                        'create_time' => time(),
                    ];
                    $res  = Db::table('lmq_ip_res')->insert($data);
                    if ($res) {
                        echo "白名单添加成功！";
                        echo "<script>setTimeout(function(){window.location.href='/admin.php';},2000)</script>";
                        die;
                    }
                } else {
                    echo "已添加过白名单！";
                    echo "<script>setTimeout(function(){window.location.href='/admin.php';},2000)</script>";
                    die;
                }
            } else {
                // 密码错误，显示错误消息
                echo "密码错误！";
            }
        } else {
            // 没有提交密码，显示密码输入表单
            echo "<form method='post' action=''>";
            echo "<input type='password' name='password' placeholder='请输入密码'>";
            echo "<input type='submit' value='提交'>";
            echo "</form>";
        }

    }

}




