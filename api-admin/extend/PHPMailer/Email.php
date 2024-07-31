<?php

namespace PHPMailer;

use think\Exception;
use think\facade\Config;

class Email
{
    /**
     *Desc: 发送邮件
     * @param $email :邮件地址
     * @param $subject :邮件主题
     * @param $body :邮件内容
     * @return array
     *author:oszpac
     *date:2023-05-30
     */
    public function send($email = '', $subject = '', $body = '')
    {
        $result = ['success' => false, 'msg' => ''];
        if (empty($email) || empty($subject) || empty($body)) {
            $result['msg'] = '参数错误';
            return $result;
        }
        $_CFG = Config::get('Email');
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = $_CFG['host'];
            $mail->CharSet = $_CFG['charSet'];
            $mail->SMTPAuth = true;
            $mail->Username = $_CFG['userName'];
            $mail->Password = $_CFG['password'];
            $mail->SMTPSecure = 'ssl';          // 使用安全协议
            $mail->Port = 465;
            $mail->setFrom($_CFG['senderEmail'], $_CFG['senderNmae']);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            $result['success'] = true;
        } catch (Exception $E) {
            $result['msg'] = $mail->ErrorInfo;
        }
        return $result;
    }
}