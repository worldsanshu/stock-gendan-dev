<?php

class BrowserRequest
{
    private $curl;
    private $query = '';
    private $user_agent = 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Mobile Safari/537.36';

    public function __construct()
    {
        $this->curl = curl_init(); // 初始化
    }

    public function request($url, $data = [], $type = 'GET')
    {
        $ret = '';
        switch ($type) {
            case 'GET';
                $ret = $this->get($url, $data);
                break;
            case 'POST';
                $ret = $this->post($url, $data);
                break;
        }
        return $ret;
    }

    // 模拟浏览器get请求
    public function get($url, $data = '')
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->query .= $key . '=' . $value;
            }
            $url .= '?' . $this->query;
        }
//        curl_setopt($this->curl, CURLOPT_PROXY, $GLOBALS ['proxy']);//代理服务器地址
        curl_setopt($this->curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent); // 模拟用户使用的浏览器
        @curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($this->curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
//        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $GLOBALS ['cookie_file']); // 读取上面所储存的Cookie信息
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 120); // 设置超时限制防止死循环
        curl_setopt($this->curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, ['Referer: https://vip.stock.finance.sina.com.cn']); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($this->curl); // 执行操作
        $httpCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        return $httpCode == 200 ? $tmpInfo : '';
    }

    // 模拟浏览器post请求
    public function post($url, $data = [])
    {
        //代理服务器地址
        curl_setopt($this->curl, CURLOPT_PROXY, $GLOBALS ['proxy']);
        curl_setopt($this->curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent); // 模拟用户使用的浏览器
        @curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($this->curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $GLOBALS ['cookie_file']); // 读取上面所储存的Cookie信息
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 120); // 设置超时限制防止死循环
        curl_setopt($this->curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($this->curl); // 执行操作
        return $tmpInfo;
    }

    public function __destruct()
    {
        //关闭URL请求
        curl_close($this->curl);
    }
}