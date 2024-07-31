<?php

use think\facade\Cache;
use think\Queue;
use util\Logs;




//dezend by http://www.yunlu99.com/

if (!function_exists('month_config')) {
    function month_config()
    {
        $config['month_loss'] = explode('|', config('month_loss'));
        $config['month_rate'] = config('month_rate');
        $config['month_use_time'] = explode('|', config('month_use_time'));
        $config['month_position'] = config('month_position');
        return $config;
    }
}
if (!function_exists('typetoc')) {
    function typetoc($num)
    {
        $type_arr = array(5 => '免费配资', 1 => '按天配资', 2 => '按周配资', 3 => '按月配资', 4 => '试用配资', 6 => '模拟操盘');
        return $type_arr[$num];
    }
}
if (!function_exists('df_today_bonus')) {
    function df_today_bonus()
    {
        $date = date('Y-m-d', time() + 86400);
        $time = time() . mt_rand(100, 999);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://datainterface.eastmoney.com/EM_DataCenter/JS.aspx?type=GSRL&sty=GSRL&stat=8&fd=' . $date . '&p=1&ps=100&js=({pages:(pc),data:[(x)]})&cb=callback&callback=callback&_=' . $time);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $num = strpos($output, 'data:') + 5;
        $tmd = substr($output, $num, -2);
        return $tmd;
    }
}
if (!function_exists('Handle')) {
    function Handle($v, $k, $rul)
    {
        $res[$k]['bonus'] = 0;#10股分红
        $res[$k]['zuan'] = 0;#10股转增
        $res[$k]['song'] = 0;#10股送股
        $res[$k]['msg'] = $v['sj'];
        #处理派送的问题
        preg_match('/派(\d+\.\d+|\d+)元/', $v['sj'], $matches);
        if (isset($matches[1])) {
            $distribution = $matches[1];
            $res[$k]['bonus'] = $distribution;
        }
        #处理转增的问题
        preg_match('/转增(\d+\.\d+|\d+)股/', $v['sj'], $matches);
        if (isset($matches[1])) {
            $distribution = $matches[1];
            $res[$k]['zuan'] = $distribution;
        }
        if (!$res[$k]['bonus'] && !$res[$k]['zuan'] && !$res[$k]['song']) {
            return false;
        }
        return $res;
    }
}
#老方法
if (!function_exists('Handle_bak')) {
    function Handle_bak($v, $k, $rul)
    {
        $yuan = $zuan = $gu = $pai = $song = 0;
        $sub = '#' . substr($v['sj'], 2);
        $tmd = preg_match_all($rul, $sub, $ret);
        if ($tmd && isset($ret[0])) {
            $count = count($ret[0]);
            $num = $ret[0];
        } else {
            return false;
        }
        $pai = strpos($sub, '派');
        $song = strpos($sub, '送');
        $zuan = strpos($sub, '转');
        $yuan = strpos($sub, '元');
        $gu = strpos($sub, '股');
        $res[$k]['bonus'] = 0;#10股分红
        $res[$k]['zuan'] = 0;#10股转
        $res[$k]['song'] = 0;#10股送股
        switch ($count) {
            case 1:
                if ($pai < $yuan && empty($song) && empty($zuan) && empty($gu)) {
                    $res[$k]['bonus'] = $num[0];
                }
                if ($song < $gu && empty($pai) && empty($zuan) && empty($yuan)) {
                    $res[$k]['song'] = $num[0];
                }
                if ($zuan < $gu && empty($pai) && empty($song) && empty($yuan)) {
                    $res[$k]['zuan'] = $num[0];
                }
                break;
            case 2:
                if ($gu < $yuan && empty($zuan) && !empty($yuan) && !empty($gu)) {
                    $res[$k]['song'] = $num[0];
                    $res[$k]['bonus'] = $num[1];
                }
                if ($gu < $yuan && empty($song) && !empty($yuan) && !empty($gu)) {
                    $res[$k]['zuan'] = $num[0];
                    $res[$k]['bonus'] = $num[1];
                }
                if ($yuan < $gu && empty($zuan) && !empty($yuan) && !empty($gu)) {
                    $res[$k]['song'] = $num[1];
                    $res[$k]['bonus'] = $num[0];
                }
                if ($yuan < $gu && empty($song) && !empty($yuan) && !empty($gu)) {
                    $res[$k]['zuan'] = $num[1];
                    $res[$k]['bonus'] = $num[0];
                }
                break;
            case 3:
                if ($song < $zuan && $zuan < $pai) {
                    $res[$k]['song'] = $num[0];
                    $res[$k]['zuan'] = $num[1];
                    $res[$k]['bonus'] = $num[2];
                }
                if ($song < $pai && $pai < $zuan) {
                    $res[$k]['song'] = $num[0];
                    $res[$k]['bonus'] = $num[1];
                    $res[$k]['zuan'] = $num[2];
                }
                if ($zuan < $song && $song < $pai) {
                    $res[$k]['zuan'] = $num[0];
                    $res[$k]['song'] = $num[1];
                    $res[$k]['bonus'] = $num[2];
                }
                if ($zuan < $pai && $pai < $song) {
                    $res[$k]['zuan'] = $num[0];
                    $res[$k]['bonus'] = $num[1];
                    $res[$k]['song'] = $num[2];
                }
                if ($pai < $song && $song < $zuan) {
                    $res[$k]['bonus'] = $num[0];
                    $res[$k]['song'] = $num[1];
                    $res[$k]['zuan'] = $num[2];
                }
                if ($pai < $zuan && $zuan < $song) {
                    $res[$k]['bonus'] = $num[0];
                    $res[$k]['zuan'] = $num[1];
                    $res[$k]['song'] = $num[2];
                }
                break;
            default:
                $res = false;
                break;
        }
        return $res;
    }
}
if (!function_exists('hexun_bonus')) {
    function hexun_bonus($code = '')
    {
        if (empty($code)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://stockdata.stock.hexun.com/gszl/data/jsondata/fhrzfhTable.ashx?no=' . $code);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $num = strpos($output, 'list:') + 5;
        $tmd = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), $num, -2);
        $tmd = str_replace('{', '{"', $tmd);
        $tmd = str_replace(':', '":', $tmd);
        $tmd = str_replace(',', ',"', $tmd);
        $tmd = str_replace('},"{', '},{', $tmd);
        $tmd = str_replace('\'', '"', $tmd);
        $tmd = str_replace('\'{', '\'[{', $tmd);
        $tmd = str_replace('}\'', '}]\'', $tmd);
        $tmd = str_replace('target="_blank"', '', $tmd);
        return $tmd;
    }
}
if (!function_exists('qq_bonus')) {
    function qq_bonus()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://web.ifzq.gtimg.cn/stock/notice/tzbw/search?_var=v_tzbw_index');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
if (!function_exists('HttpGet')) {
    function HttpGet($url, $status = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1000);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36');
        if ($status) {
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept:application', 'X-Request:JSON', 'X-Requested-With:XMLHttpRequest'));
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
}
if (!function_exists('Http_Spider')) {
    function Http_Spider($url)
    {
        $ch = curl_init();
        $ip = '115.239.211.112';
        $timeout = 15;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip . '', 'CLIENT-IP:' . $ip . ''));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.baidu.com/');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $content = curl_exec($ch);
        return $content;
    }
}
//ifind 数据请求统一方法
if (!function_exists('iApi_curl')) {
    function iApi_curl($act = '', $data = [])
    {
        $curl = curl_init();
        $url = config('f10_api_url') . $act;
        $requestdata = array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_ENCODING => 'gzip',
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($data)
        );
        $head_token = '';
        if ($act == 'get_access_token') {//获取token
            $head_token = 'refresh_token:' . config('f10_refresh_token');
        } else {
            $head_token = 'access_token:' . iApi_get_access_token();
        }
        $requestdata[CURLOPT_HTTPHEADER] = [
          "Content-Type:application/json",
          $head_token
        ];
        curl_setopt_array($curl, $requestdata);
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response, true);
        if ($res['errorcode'] != '0') {//请求失败，日志
            $logs = compact('act', 'data', 'res');
            Logs::e_log('iApi_error', $logs, 'iApi');
            return false;
        }
        return $res;
    }
}
if (!function_exists('iApi_get_access_token')) {
    function iApi_get_access_token()
    {
        $key = 'ifind_access_token';
        $token = Cache::get($key);
        if (!$token) {
            $res = get_access_token('get_access_token');
            if ($res) {
                $token = $res['data']['access_token'];
                $expired_time = strtotime($res['data']['expired_time']) - time();
                Cache::set($key, $token, $expired_time);
            }
        }
        return $token;
    }
}
//ifind 数据请求统一方法
if (!function_exists('get_access_token')) {
    function get_access_token($act = '', $data = [])
    {
        $curl = curl_init();
        $url = config('f10_api_url') . $act;
        $requestdata = array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_ENCODING => 'gzip',
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($data)
        );
        $head_token = '';
        if ($act == 'get_access_token') {//获取token
            $head_token = 'refresh_token:' . config('f10_refresh_token');
        } else {
            $head_token = 'access_token:' . iApi_get_access_token();
        }
        $requestdata[CURLOPT_HTTPHEADER] = [
          "Content-Type:application/json",
          $head_token
        ];
        curl_setopt_array($curl, $requestdata);
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response, true);
        if ($res['errorcode'] != '0') {//请求失败，日志
            $logs = compact('act', 'data', 'res');
            Logs::e_log('iApi_error', $logs, 'iApi');
            return false;
        }
        return $res;
    }
}
if (!function_exists('code_suffix')) {
    //生成同花顺股票代码
    function code_suffix($code)
    {
        $arr = array(
          '00' => '.SZ',
          '30' => '.SZ',
          '43' => '.BJ',
          '60' => '.SH',
          '68' => '.SH',
          '83' => '.BJ',
          '87' => '.BJ',
          '90' => '.SH',
          '20' => '.SZ',
          '36' => '.SH',
          '14' => '.SZ',
        );
        $key = substr($code, 0, 2);
        if (isset($arr[$key]))
            $code .= $arr[$key];
        return $code;
    }
}
?>
