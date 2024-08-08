<?php

use think\Db;
use think\facade\App;
use think\facade\Cache;
use util\Logs;
use think\facade\Log;

/**
 * Desc :  任意地方打印调试日志
 * User : you name
 * Date : 2024-07-16 17:54
 *
 * @param $data 数据可以是数组或者对象
 * @param $str
 * @param $filsename
 *
 * @return false|int
 */
function printlog($data, $str= '', $filsename = '')
{
    if (is_array($data)) {
        $data = var_export($data, true);
    }
    if (is_object($data)) {
        $data = (array)$data;
        $data = var_export($data, true);
    }
    $logpath  = App::getRuntimePath() . '/debug';
    $write    = "\r\n【" . date("Y-m-d H:i:s") . "】-{$str}:{$data}";
    $filepath = $logpath . '/printlog_' . $filsename . '_' . date("Y-m-d") . '.log';
    if (!file_exists($logpath)) {
        if (mkdir($logpath, 0777, true)) {
            return file_put_contents($filepath, $write, FILE_APPEND);
        } else {
            return false;
        }
    }
    return file_put_contents($filepath, $write, FILE_APPEND);
}

/**
 * Desc : 获取一天最后一秒时间戳
 * User : you name
 * Date : 2024-07-16 17:58
 *
 * @param $specificDate
 *
 * @return int
 */
function Lastsecond($specificDate){
    // 指定日期
//    $specificDate = '2024-07-16';

// 获取次日的时间戳
    $nextDayTimeStamp = strtotime($specificDate . ' +1 day');

// 将次日零点的时间戳减去 1 秒得到指定日期最后一毫秒的时间戳
    $endTimeStamp = $nextDayTimeStamp - 1;

    return $endTimeStamp;
}

if (!function_exists('build_rid_no')) {
    function build_rid_no()
    {
        $numbers = mt_rand(100, 1000) + time();
        return $numbers;
    }
}
if (!function_exists('getTopHost')) {
    function getTopHost($url)
    {
        $url   = strtolower($url); // 首先转成小写
        $hosts = parse_url($url);
        $host  = $hosts['host'];
        // 查看是几级域名
        $data = explode('.', $host);
        $n    = count($data);
        // 判断是否是双后缀
        $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
        if (($n > 2) && preg_match($preg, $host)) {
            // 双后缀取后3位
            $host = $data[$n - 3] . '.' . $data[$n - 2] . '.' . $data[$n - 1];
        } else {
            // 非双后缀取后两位
            $host = $data[$n - 2] . '.' . $data[$n - 1];
        }
        return $host;
    }
}
if (!function_exists('gs')) {
    function gs($fun, $p = '')
    {
        return plugin_action('GreenSparrow/Sparrow/' . $fun, $p);
    }
}
if (!function_exists('month_config')) {
    function month_config()
    {
        $config['month_loss']     = explode('|', config('month_loss'));
        $config['month_rate']     = config('month_rate');
        $config['month_use_time'] = explode('|', config('month_use_time'));
        $config['month_position'] = config('month_position');
        return $config;
    }
}
if (!function_exists('week_config')) {
    function week_config()
    {
        $config['week_loss']     = explode('|', config('week_loss'));
        $config['week_rate']     = config('week_rate');
        $config['week_use_time'] = explode('|', config('week_use_time'));
        $config['week_position'] = config('week_position');
        return $config;
    }
}
if (!function_exists('free_config')) {
    function free_config()
    {
        $config['free_loss']    = explode('|', config('free_loss'));
        $config['free_set']     = explode('|', config('free_set'));
        $config['day_position'] = config('day_position');
        return $config;
    }
}
if (!function_exists('day_config')) {
    function day_config()
    {
        $config['day_loss']     = explode('|', config('day_loss'));
        $config['day_rate']     = config('day_rate');
        $config['day_position'] = config('day_position');
        $config['day_use_time'] = explode('|', config('day_use_time'));
        return $config;
    }
}
if (!function_exists('global_config')) {
    function global_config()
    {
        $config['money_range']  = explode('|', config('money_range'));
        $config['limit_fee']    = config('limit_fee');
        $config['commission']   = config('commission');
        $config['stamp_duty']   = config('stamp_duty');
        $config['transfer_fee'] = config('transfer_fee');
        $config['profit_share'] = config('profit_share');
        $config['stop_fee']     = config('stop_fee');
        $config['stock_count']  = config('stock_count');
        $config['rebate']       = config('rebate');
        return $config;
    }
}
if (!function_exists('getEndDay2')) {
    function getEndDay2($start = 'now', $exception = '')
    {
        $starttime = strtotime($start);
        $weekday   = date('N', $starttime);
        $tmpday    = date('md', $starttime);
        if (is_array($exception)) {
            $bfd = in_array($tmpday, $exception);
        } else {
            $bfd = $exception == $tmpday;
        }
        if ($weekday <= 5 && !$bfd) {
            $status = 1;
        } else {
            $status = 2;
        }
        return $status;
    }
}
if (!function_exists('festival')) {
    function festival()
    {
        $t0   = date('Y', time());
        $data = cache('festival' . $t0);
        if ($data) {
            return $data;
        } else {
            $hello = array();
            $d0    = config('legal_holiday');
            if (empty($d0)) {
                $url = 'http://tool.bitefu.net/jiari/?d=' . $t0;
                $d0  = file_get_contents($url);
                $d   = json_decode($d0);
                if (is_object($d->$t0)) {
                    $n = 0;
                    foreach ($d->$t0 as $key => $value) {
                        $hello[$n] = $key;
                        $n++;
                    }
                } else {
                    $hello = $d->$t0;
                }
            } else {
                $d     = json_decode($d0);
                $hello = $d->$t0;
            }
            cache('festival' . $t0, $hello);
            return $hello;
        }
    }
}
if (!function_exists('calculate_rate')) {
    /*
     * 计算手续费
     *
     */
    function calculate_rate($multiple, $rate, $type, $deposit_money, $endTime)
    {
        if ($type == 1 || $type == 2) {
            $duration     = 0;
            $durationTmp  = ceil(($endTime - mktime(0, 0)) / 3600 / 24);
            $durationDays = array();
            for ($i = 0; $i < $durationTmp; $i++) {
                $durationDays[$i] = date('Y-m-d', strtotime('+' . $i . ' day'));
            }
            if ($type == 1) {
                $hello = festival();
                foreach ($durationDays as $v) {
                    if (getEndDay2($v, $hello) == 1) {
                        $duration++;
                    }
                }
            }
            if ($type == 2) {
                $duration = round($durationTmp / 7, 2);
            }
        } else {
            $durationTmp = ceil(($endTime - mktime(0, 0)) / 3600 / 24);
            $duration    = round($durationTmp / 30, 2);
        }
        $fee = round($deposit_money * $multiple * $duration * $rate / 100, 2);
        return $fee;
    }
}
if (!function_exists('getEndDay')) {
    function getEndDay($start = 'now', $offset = 0, $exception = '')
    {
        $starttime = strtotime($start);
        $endtime   = $starttime + $offset * 24 * 3600;
        $end       = date('Y-m-d', $endtime);
        $weekday   = date('N', $starttime);
        $newoffset = 0;
        for ($i = 1; $i <= $offset; $i++) {
            $today = date('md', $starttime + $i * 24 * 3600);
            switch (($weekday + $i) % 7) {
                case 6:
                    $newoffset += 1;
                    break;
                case 0:
                    $newoffset += 1;
                    break;
                default:
                    if (is_array($exception)) {
                        if (in_array($today, $exception)) {
                            $newoffset += 1;
                        }
                    } else if ($today == $exception) {
                        $newoffset += 1;
                    }
                    break;
            }
        }
        if (0 < $newoffset) {
            return getEndDay($end, $newoffset, $exception);
        } else {
            return $end;
        }
    }
}
//f10实时行情
if (!function_exists('z_market')) {
    function z_market($code)
    {
        //调用F10的数据源
        $lang   = \think\facade\Lang::range();
        $url    = config('data_service_site') . '/stock/api/stock_detail?code=' . $code . '&lang=' . $lang;
        $url    = $url . '&t=' . time() . '&key=' . config('data_service_key');
        $sign   = md5($url);
        $kid    = "kid:" . config('data_service_kid');
        $sign   = "sign:" . $sign;
        $header = [
            $kid,
            $sign
        ];
        $res    = curl_post($url, [], $header);
        $res    = json_decode($res, true);
        //file_put_contents('f10api.txt', var_export($res, true) . "\r\n", FILE_APPEND);
        Log::debug('f10api---'.$res);
        $result_data = [];
        if ($res['status'] == '200')
            $result_data = $res['data'];
#	直接返回数据	
        return $result_data;

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
//ifind 数据请求统一方法
if (!function_exists('iApi_curl')) {
    function iApi_curl($act = '', $data = [])
    {
        $curl        = curl_init();
        $url         = config('f10_api_url') . $act;
        $requestdata = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($data)
        );
        $head_token  = '';
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
            $key = 'ifind_access_token';
            if ($res['errorcode'] == '-1302') {
                Cache::rm($key);//如果同花顺token过期，本地缓存的token没有过期那就清除缓存
            }
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
        $key   = 'ifind_access_token';
        $token = Cache::get($key);
        if (!$token) {
            $res = get_access_token('get_access_token');
            if ($res) {
                $token        = $res['data']['access_token'];
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
        $curl        = curl_init();
        $url         = config('f10_api_url') . $act;
        $requestdata = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($data)
        );
        $head_token  = '';
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
if (!function_exists('z_market_old')) {
    function z_market_old($code)
    {
        $res = \think\Db::name('admin_config')->where(array(
            'name' => 'market_data_in'
        ))->value('value');
        /**
         * *
         * 更新时间 2023-5-1
         * 1 腾讯 能用 免费
         * 4 天行 数据不全勉强能用 付费
         * 5 雪球 勉强能用 有访问限制 付费
         */
        $res = 1;
        switch ($res) {
            case 1:
                $res  = qq_market($code);
                $data = qq_to_api($res);
                break;
            case 2:
                $res  = sina_market($code);
                $data = sina_to_api($res);
                break;
            case 3:
                $res = gs('market', array(
                    $code
                ));
                if (empty($res['error'])) {
                    $data = $res[0];
                } else {
                    $data = $res['error'];
                }
                break;
            case 4:
                $d   = fenxi($code);
                $tmd = tianju_market($d, 0); // 天聚数据
                $arr = json_decode($tmd, true);
                if ($arr['code'] != '200') {
                    break;
                }
                $arr  = $arr['result']['list'];
                $data = tianju_to_api($arr, $d);
                break;
            case 5:
                // 雪球数据 仅供免费测试-限制同一IP访问次数
                $d   = strtoupper(fenxi($code));
                $tmd = xueqiu_market($d); // 雪球数据
                print_r($tmd);
                $tmd2 = xueqiu_to_bill_top($d); // 雪球数据
                $arr  = json_decode($tmd, true);
                $arr2 = json_decode($tmd2, true);
                if ($arr['error_code'] != 0) {
                    break;
                }
                if ($arr2['error_code'] != 0) {
                    break;
                }
                $arr  = $arr['quote'];
                $arr2 = $arr2['data'];
                unset($arr2['symbol'], $arr2['timestamp'], $arr2['current'], $arr2['bid1_order_list'], $arr2['ask1_order_list'], $arr2['buypct'], $arr2['sellpct'], $arr2['ratio'], $arr2['diff'], $arr2['level']);
                $data = xueqiu_to_api(array_merge($arr, $arr2));
                break;
            default:
                $res  = qq_market($code);
                $data = qq_to_api($res);
                break;
        }
        return $data;
    }
}
if (!function_exists('z_market_bat')) {
    function z_market_bat($code, $time = '')
    {
        $d    = $d1 = $d2 = '';
        $data = array();
        if ($code == "sh000001,399001,399006") {
            $res = 1;
        } else {
            $res = 6;
        }
        /**
         * *
         * 更新时间 2023-5-1
         * 1 腾讯 能用 免费
         * 4 天行 数据不全勉强能用 付费
         * 5 雪球 勉强能用 有访问限制 付费
         * 6.数据源接口
         */
        switch ($res) {
            case 1:
                $res   = explode(',', $code);
                $count = count($res);
                foreach ($res as $v) {
                    $d .= fenxi($v) . ',';
                }
                $d   = rtrim($d, ",");
                $tmd = qq_market_b($d);
                if (empty($tmd)) {
                    break;
                }
                foreach ($tmd as $i => $k) {
                    $data[$i] = qq_to_api($k);
                }
                break;
            case 2:
                // $res = explode(',', $code);
                // $count = count($res);
                // foreach ($res as $v) {
                // $d .= fenxi($v) . ',';
                // }
                // $tmd = sina_market_b($d);
                // if (empty($tmd)) {
                // break;
                // }
                // for ($i = 0; $i < $count; $i++) {
                // $k = $i * 32;
                // for ($n = 0; $n < 32; $n++) {
                // $num = $n + $k;
                // $data[$i][$n] = $tmd[$num];
                // }
                // if ($i == 0) {
                // list($tmp) = explode(';', $data[$i][0]);
                // $data[$i][0] = substr($tmp, 11, 8);
                // $data[$i][32] = substr($tmp, 21);
                // } else {
                // list(, $tmp) = explode(';', $data[$i][0]);
                // $data[$i][0] = substr($tmp, 12, 8);
                // $data[$i][32] = substr($tmp, 22);
                // }
                // $data[$i] = sina_to_api_b($data[$i]);
                // }
                // break;
            case 3:
                $res = gs('market_bat', array(
                    $code
                ));
                if (!empty($res['error'])) {
                    $data = $res['error'];
                    break;
                }
                if (is_array($res)) {
                    unset($res[0]);
                    $count = count($res);
                    for ($i = 0; $i < $count; $i++) {
                        $data[$i] = api_filter($res[$i + 1]);
                    }
                }
                break;
            case 4:
                $res   = explode(',', $code);
                $count = count($res);
                if ($count > 10) {
                    foreach ($res as $k => $v) {
                        if ($k > 9) {
                            $d1 .= fenxi($v) . ',';
                        } else {
                            $d2 .= fenxi($v) . ',';
                        }
                    }
                    $d1   = rtrim($d1, ",");
                    $d2   = rtrim($d2, ",");
                    $tmd1 = tianju_market($d1, 1); // 天聚数据
                    $tmd2 = tianju_market($d2, 1); // 天聚数据
                    $arr1 = json_decode($tmd1, true);
                    if ($arr1['code'] != '200') {
                        break;
                    }
                    $arr1 = $arr1['result'];
                    $arr2 = json_decode($tmd2, true);
                    if ($arr2['code'] != '200') {
                        break;
                    }
                    $arr2 = $arr2['result'];
                    $arr  = array_merge_recursive($arr2, $arr1);
                    foreach ($arr as $k => $v) {
                        $v      = explode(',', $v);
                        $data[] = tianju_to_api($v);
                    }
                } else {
                    foreach ($res as $v) {
                        $d .= fenxi($v) . ',';
                    }
                    $tmd = tianju_market($d, 1); // 天聚数据
                    $arr = json_decode($tmd, true);
                    if ($arr['code'] != '200') {
                        break;
                    }
                    $arr = $arr['result'];
                    foreach ($arr as $k => $v) {
                        $v      = explode(',', $v);
                        $data[] = tianju_to_api($v, $k);
                    }
                }
                break;
            case 5:
                // 雪球数据 仅供免费测试-限制同一IP访问次数
                $res = explode(',', $code);
                foreach ($res as $v) {
                    $d .= strtoupper(fenxi($v)) . ',';
                }
                $d   = rtrim($d, ",");
                $tmd = xueqiu_market_bat($d); // 雪球数据
                $arr = json_decode($tmd, true);
                if ($arr['error_code'] != 0) {
                    break;
                }
                $arr = $arr['data'];
                foreach ($arr as $k => $v) {
                    $v['bn1'] = '';
                    $v['bn2'] = '';
                    $v['bn3'] = '';
                    $v['bn4'] = '';
                    $v['bn5'] = '';
                    $v['bc1'] = '';
                    $v['bc2'] = '';
                    $v['bc3'] = '';
                    $v['bc4'] = '';
                    $v['bc5'] = '';
                    $v['sp1'] = '';
                    $v['sp2'] = '';
                    $v['sp3'] = '';
                    $v['sp4'] = '';
                    $v['sp5'] = '';
                    $v['sc1'] = '';
                    $v['sc2'] = '';
                    $v['sc3'] = '';
                    $v['sc4'] = '';
                    $v['sc5'] = '';
                    $data[$k] = xueqiu_to_api($v);
                }
                break;
            case 6:
                //调用F10的数据
                $lang        = \think\facade\Lang::range();
                $url         = config('data_service_site') . '/stock/api/stock_detail_bat?code=' . $code . '&lang=' . $lang . '&t=' . $time;
                $url         = $url . '&t=' . time() . '&key=' . config('data_service_key');
                $sign        = md5($url);
                $kid         = "kid:" . config('data_service_kid');
                $sign        = "sign:" . $sign;
                $header      = [
                    $kid,
                    $sign
                ];
                $res         = curl_post($url, [], $header);
                $res         = json_decode($res, true);
                $result_data = [];
                if ($res['status'] == '200')
                    $result_data = $res['data'];
                #直接停止  返回数据
                return $result_data;


            default:
                $res   = explode(',', $code);
                $count = count($res);
                foreach ($res as $v) {
                    $d .= fenxi($v) . ',';
                }
                $tmd = qq_market_b($d);
                for ($i = 0; $i < $count; $i++) {
                    $k = $i * 53;
                    for ($n = 1; $n <= 53; $n++) {
                        $num          = $n + $k;
                        $data[$i][$n] = $tmd[$num];
                    }
                    $data[$i][0] = '';
                    $data[$i]    = qq_to_api($data[$i]);
                }
                break;
        }
        return $data;
    }
}
/**
 * 指定时间行情
 */
if (!function_exists('z_market_time_bat')) {
    function z_market_time_bat($code, $time = '')
    {
        $d    = $d1 = $d2 = '';
        $data = array();
        $res  = 6;
        /**
         * *
         * 更新时间 2023-5-1
         * 1 腾讯 能用 免费
         * 4 天行 数据不全勉强能用 付费
         * 5 雪球 勉强能用 有访问限制 付费
         * 6.数据源接口
         */
        switch ($res) {
            case 1:
                $res   = explode(',', $code);
                $count = count($res);
                foreach ($res as $v) {
                    $d .= fenxi($v) . ',';
                }
                $d   = rtrim($d, ",");
                $tmd = qq_market_b($d);
                if (empty($tmd)) {
                    break;
                }
                foreach ($tmd as $i => $k) {
                    $data[$i] = qq_to_api($k);
                }
                break;
            case 2:
                // $res = explode(',', $code);
                // $count = count($res);
                // foreach ($res as $v) {
                // $d .= fenxi($v) . ',';
                // }
                // $tmd = sina_market_b($d);
                // if (empty($tmd)) {
                // break;
                // }
                // for ($i = 0; $i < $count; $i++) {
                // $k = $i * 32;
                // for ($n = 0; $n < 32; $n++) {
                // $num = $n + $k;
                // $data[$i][$n] = $tmd[$num];
                // }
                // if ($i == 0) {
                // list($tmp) = explode(';', $data[$i][0]);
                // $data[$i][0] = substr($tmp, 11, 8);
                // $data[$i][32] = substr($tmp, 21);
                // } else {
                // list(, $tmp) = explode(';', $data[$i][0]);
                // $data[$i][0] = substr($tmp, 12, 8);
                // $data[$i][32] = substr($tmp, 22);
                // }
                // $data[$i] = sina_to_api_b($data[$i]);
                // }
                // break;
            case 3:
                $res = gs('market_bat', array(
                    $code
                ));
                if (!empty($res['error'])) {
                    $data = $res['error'];
                    break;
                }
                if (is_array($res)) {
                    unset($res[0]);
                    $count = count($res);
                    for ($i = 0; $i < $count; $i++) {
                        $data[$i] = api_filter($res[$i + 1]);
                    }
                }
                break;
            case 4:
                $res   = explode(',', $code);
                $count = count($res);
                if ($count > 10) {
                    foreach ($res as $k => $v) {
                        if ($k > 9) {
                            $d1 .= fenxi($v) . ',';
                        } else {
                            $d2 .= fenxi($v) . ',';
                        }
                    }
                    $d1   = rtrim($d1, ",");
                    $d2   = rtrim($d2, ",");
                    $tmd1 = tianju_market($d1, 1); // 天聚数据
                    $tmd2 = tianju_market($d2, 1); // 天聚数据
                    $arr1 = json_decode($tmd1, true);
                    if ($arr1['code'] != '200') {
                        break;
                    }
                    $arr1 = $arr1['result'];
                    $arr2 = json_decode($tmd2, true);
                    if ($arr2['code'] != '200') {
                        break;
                    }
                    $arr2 = $arr2['result'];
                    $arr  = array_merge_recursive($arr2, $arr1);
                    foreach ($arr as $k => $v) {
                        $v      = explode(',', $v);
                        $data[] = tianju_to_api($v);
                    }
                } else {
                    foreach ($res as $v) {
                        $d .= fenxi($v) . ',';
                    }
                    $tmd = tianju_market($d, 1); // 天聚数据
                    $arr = json_decode($tmd, true);
                    if ($arr['code'] != '200') {
                        break;
                    }
                    $arr = $arr['result'];
                    foreach ($arr as $k => $v) {
                        $v      = explode(',', $v);
                        $data[] = tianju_to_api($v, $k);
                    }
                }
                break;
            case 5:
                // 雪球数据 仅供免费测试-限制同一IP访问次数
                $res = explode(',', $code);
                foreach ($res as $v) {
                    $d .= strtoupper(fenxi($v)) . ',';
                }
                $d   = rtrim($d, ",");
                $tmd = xueqiu_market_bat($d); // 雪球数据
                $arr = json_decode($tmd, true);
                if ($arr['error_code'] != 0) {
                    break;
                }
                $arr = $arr['data'];
                foreach ($arr as $k => $v) {
                    $v['bn1'] = '';
                    $v['bn2'] = '';
                    $v['bn3'] = '';
                    $v['bn4'] = '';
                    $v['bn5'] = '';
                    $v['bc1'] = '';
                    $v['bc2'] = '';
                    $v['bc3'] = '';
                    $v['bc4'] = '';
                    $v['bc5'] = '';
                    $v['sp1'] = '';
                    $v['sp2'] = '';
                    $v['sp3'] = '';
                    $v['sp4'] = '';
                    $v['sp5'] = '';
                    $v['sc1'] = '';
                    $v['sc2'] = '';
                    $v['sc3'] = '';
                    $v['sc4'] = '';
                    $v['sc5'] = '';
                    $data[$k] = xueqiu_to_api($v);
                }
                break;
            case 6:
                //调用F10的数据
                $lang        = \think\facade\Lang::range();
                $url         = config('data_service_site') . '/stock/api/stock_time_detail_bat?code=' . $code . '&lang=' . $lang . '&t=' . $time;
                $url         = $url . '&t=' . time() . '&key=' . config('data_service_key');
                $sign        = md5($url);
                $kid         = "kid:" . config('data_service_kid');
                $sign        = "sign:" . $sign;
                $header      = [
                    $kid,
                    $sign
                ];
                $res         = curl_post($url, [], $header);
                $res         = json_decode($res, true);
                $result_data = [];
                if ($res['status'] == '200')
                    $result_data = $res['data'];
                #直接停止  返回数据
                return $result_data;

            default:
                $res   = explode(',', $code);
                $count = count($res);
                foreach ($res as $v) {
                    $d .= fenxi($v) . ',';
                }
                $tmd = qq_market_b($d);
                for ($i = 0; $i < $count; $i++) {
                    $k = $i * 53;
                    for ($n = 1; $n <= 53; $n++) {
                        $num          = $n + $k;
                        $data[$i][$n] = $tmd[$num];
                    }
                    $data[$i][0] = '';
                    $data[$i]    = qq_to_api($data[$i]);
                }
                break;
        }
        return $data;
    }
}
if (!function_exists('tianju_to_api')) {
    /*
     * 天下实时行情转实盘API
     * $res 初始数据
     */
    function tianju_to_api($res, $code)
    {
        $data = array(
            "code"              => $code,
            "name"              => $res[0],
            "yesterday_price"   => $res[2],
            "open_price"        => $res[1],
            "national_debt"     => "0.00",
            "current_price"     => $res[3],
            "buy_one_price"     => $res[11],
            "buy_two_price"     => $res[13],
            "buy_three_price"   => $res[15],
            "buy_one_amount"    => $res[10],
            "buy_two_amount"    => $res[12],
            "buy_three_amount"  => $res[14],
            "buy_four_price"    => $res[17],
            "buy_five_price"    => $res[19],
            "buy_four_amount"   => $res[16],
            "buy_five_amount"   => $res[18],
            "sell_one_price"    => $res[21],
            "sell_two_price"    => $res[23],
            "sell_three_price"  => $res[25],
            "sell_one_amount"   => $res[24],
            "sell_two_amount"   => $res[20],
            "sell_three_amount" => $res[24],
            "sell_four_price"   => $res[27],
            "sell_five_price"   => $res[29],
            "sell_four_amount"  => $res[26],
            "sell_five_amount"  => $res[28],
            "exchange_code"     => 1,
            "mini_trans"        => 100,
            "buy_chang_price"   => "0.01",
            "sell_chang_price"  => "0.01",
            "type"              => 1,
            "currency"          => 0,
            "debt_sign"         => 255,
            "info"              => "",
            "highest"           => $res[4],
            "lowest"            => $res[5], // "contest_buy_price" =>$res[6],"contest_sell_price" =>$res[7],
            "volume"            => $res[8],
            "turnover"          => '--', # 换手率
            "drop_limit"        => '--', # 跌停
            "trading_limit"     => '--', # 涨停
            "prec_limit"        => $res[2], # 昨收价
            "time"              => $res[31]
        );
        //file_put_contents(ROOT_PATH . '/public/log/tianju_to_api-' . date("y-m-d") . '.txt', var_export($data, true) . "\r\n\r\n\r\n", FILE_APPEND);
        return $data;
    }
}
if (!function_exists('tianju_market')) {
    /*
     * https://www.tianapi.com/apiview/55#apiresult
     * 天行数据
     * $code 股票代码
     */
    function tianju_market($code, $list = 0)
    {
        $host    = "https://apis.tianapi.com/finance/index";
        $path    = "/query/comrms";
        $method  = "GET";
        $appcode = "b51a506d44f39cffbd1f9ee1f68d11f6";
        $headers = array();
        $querys  = "key=" . $appcode . "&code=" . $code . "&list=" . $list;
        $bodys   = "";
        $url     = $host . "?" . $querys;
        $curl    = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = $output2 = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($output, true);
        if ($data['code'] != '200' || empty($output2)) {
            //file_put_contents(ROOT_PATH . '/public/log/tianju_market-date' . date("y-m-d") . '.txt', '数据接口--' . var_export($data, true) . "\r\n", FILE_APPEND);
            if (config("is_pc") != "no") {
                setconfig(array(
                    'is_pc'
                ), array(
                    "no"
                ));
            }
        } else {
            if (config("is_pc") != "is") {
                setconfig(array(
                    'is_pc'
                ), array(
                    "is"
                ));
            }
        }
        return $output;
    }
}
// 雪球行情开始
if (!function_exists('xueqiu_to_api')) {
    /*
     * 雪球行情开始
     * $res 初始数据
     */
    function xueqiu_to_api($res)
    {
        $data = array(
            /**/
            "code"              => $res['code'],
            /**/
            "name"              => $res['name'],
            /**/
            "yesterday_price"   => $res['last_close'],
            /**/
            "open_price"        => $res['open'],
            "national_debt"     => "0.00",
            /**/
            "current_price"     => $res['current'],
            /**/
            "buy_one_price"     => $res['bn1'],
            /**/
            "buy_two_price"     => $res['bn2'],
            /**/
            "buy_three_price"   => $res['bn3'],
            /**/
            "buy_one_amount"    => $res['bc1'] / 100,
            /**/
            "buy_two_amount"    => $res['bc2'] / 100,
            /**/
            "buy_three_amount"  => $res['bc3'] / 100,
            /**/
            "sell_one_price"    => $res['sp1'],
            /**/
            "sell_two_price"    => $res['sp2'],
            /**/
            "sell_three_price"  => $res['sp3'],
            /**/
            "sell_one_amount"   => $res['sc1'] / 100,
            /**/
            "sell_two_amount"   => $res['sc2'] / 100,
            /**/
            "sell_three_amount" => $res['sc3'] / 100,
            /**/
            "buy_four_price"    => $res['bn4'],
            /**/
            "buy_five_price"    => $res['bn5'],
            /**/
            "buy_four_amount"   => $res['bc4'] / 100,
            /**/
            "buy_five_amount"   => $res['bc5'] / 100,
            /**/
            "sell_four_price"   => $res['sp4'],
            /**/
            "sell_five_price"   => $res['sp5'],
            /**/
            "sell_four_amount"  => $res['sc4'] / 100,
            /**/
            "sell_five_amount"  => $res['sc5'] / 100,
            /**/
            "exchange_code"     => 1,
            /**/
            "mini_trans"        => 100,
            /**/
            "buy_chang_price"   => "0.01",
            /**/
            "sell_chang_price"  => "0.01",
            "type"              => 1,
            "currency"          => 0,
            "debt_sign"         => 255,
            "info"              => "",
            /**/
            "highest"           => $res['high'],
            /**/
            "lowest"            => $res['low'], // "contest_buy_price" =>$res[6],"contest_sell_price" =>$res[7],
            /* */
            "volume"            => $res['volume'],
            /**/
            "turnover"          => $res['turnover_rate'],
            /**/
            "drop_limit"        => $res['limit_down'], # 跌停
            /* */
            "trading_limit"     => $res['limit_up'], # 涨停
            /* */
            "prec_limit"        => $res['last_close'], # 昨收价
            "time"              => date("h:i:s", $res['timestamp'] / 1000)
        );
        //file_put_contents(ROOT_PATH . '/public/log/tianju_to_api-' . date("y-m-d") . '.txt', var_export($data, true) . "\r\n\r\n\r\n", FILE_APPEND);
        return $data;
    }
}
if (!function_exists('xueqiu_to_bill_top')) {
    /*
     * 阿里云 实时行情转实盘API
     * $res 初始数据
     */
    function xueqiu_to_bill_top($code)
    {
        $url  = "https://stock.xueqiu.com/v5/stock/realtime/pankou.json?symbol={$code}";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $output = $output2 = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($output, true);
        if ($data['error_code'] != '0' || empty($output2)) {
            //file_put_contents(ROOT_PATH . '/public/log/xueqiu_to_market-date' . date("y-m-d") . '.txt', '数据接口--' . var_export($data, true) . "\r\n", FILE_APPEND);
            if (config("is_pc") != "no") {
                setconfig(array(
                    'is_pc'
                ), array(
                    "no"
                ));
            }
        } else {
            if (config("is_pc") != "is") {
                setconfig(array(
                    'is_pc'
                ), array(
                    "is"
                ));
            }
        }
        return $output;
    }
}
if (!function_exists('xueqiu_market_bat')) {
    /*
     * 雪球行情批量实时行情数据
     * $code 股票代码
     */
    function xueqiu_market_bat($code)
    {
        $url  = "https://stock.xueqiu.com/v5/stock/realtime/quotec.json?symbol={$code}";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $output = $output2 = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($output, true);
        if ($data['error_code'] != '0' || empty($output2)) {
            //file_put_contents(ROOT_PATH . '/public/log/xueqiu_to_market-date' . date("y-m-d") . '.txt', '数据接口--' . var_export($data, true) . "\r\n", FILE_APPEND);
            if (config("is_pc") != "no") {
                setconfig(array(
                    'is_pc'
                ), array(
                    "no"
                ));
            }
        } else {
            if (config("is_pc") != "is") {
                setconfig(array(
                    'is_pc'
                ), array(
                    "is"
                ));
            }
        }
        return $output;
    }
}
if (!function_exists('xueqiu_market')) {
    /*
     * 雪球行情批量实时行情数据
     * $code 股票代码
     */
    function xueqiu_market($code)
    {
        echo $url = "https://stock.xueqiu.com/v5/stock/quote.json?symbol={$code}&extend=detail";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36'
        ));
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = $output2 = curl_exec($curl);
        $data   = json_decode($output, true);
        if ($data['error_code'] != '0' || empty($output2)) {
            //file_put_contents(ROOT_PATH . '/public/log/xueqiu_to_market-date' . date("y-m-d") . '.txt', '数据接口--' . var_export($data, true) . "\r\n", FILE_APPEND);
            if (config("is_pc") != "no") {
                setconfig(array(
                    'is_pc'
                ), array(
                    "no"
                ));
            }
        } else {
            if (config("is_pc") != "is") {
                setconfig(array(
                    'is_pc'
                ), array(
                    "is"
                ));
            }
        }
        return $output;
    }
}
// 雪球行情结束
if (!function_exists('setconfig')) {
    /**
     * 修改config的函数
     *
     * @param $arr1 配置前缀
     * @param $arr2 数据变量
     *
     * @return bool 返回状态
     */
    function setconfig($pat, $rep)
    {
        /**
         * 原理就是 打开config配置文件 然后使用正则查找替换 然后在保存文件.
         * 传递的参数为2个数组 前面的为配置 后面的为数值. 正则的匹配为单引号 如果你的是分号 请自行修改为分号
         * $pat[0] = 参数前缀; 例: default_return_type
         * $rep[0] = 要替换的内容; 例: json
         */
        if (is_array($pat) and is_array($rep)) {
            for ($i = 0; $i < count($pat); $i++) {
                $pats[$i] = '/\'' . $pat[$i] . '\'(.*?),/';
                $reps[$i] = "'" . $pat[$i] . "'" . "=>" . "'" . $rep[$i] . "',";
            }
            $fileurl = APP_PATH . "config.php";
            $string  = file_get_contents($fileurl);         //加载配置文件
            $string  = preg_replace($pats, $reps, $string); // 正则查找然后替换
            file_put_contents($fileurl, $string);           // 写入配置文件
            return true;
        } else {
            return flase;
        }
    }
}
if (!function_exists('sohu_keyword')) {
    function sohu_keyword($key)
    {
        $e = preg_match('/^[\\x{4E00}-\\x{9FA5}]+$/u', $key);
        if ($e) {
            return '';
        }
        $ch  = curl_init();
        $url = 'http://q.stock.sohu.com/app1/stockSearch?method=search&callback=&type=cnwbj&sTypeId=15,16&keyword=' . $key . '&_=0.4941991283558309';
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // $output = curl_exec($ch);
        // curl_close($ch);
        $header = array(
            "Referer: http://sohu.com/"
        );
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 是否抓取跳转后的页面
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        ob_start();
        curl_exec($ch);
        $contents = ob_get_contents();
        ob_end_clean();
        curl_close($ch);
        $t2  = substr(mb_convert_encoding($contents, 'utf-8', 'gbk'), 42, -5);
        $t3  = explode('],["', $t2);
        $res = array();
        foreach ($t3 as $k => $v) {
            $tmd = explode(',', $v);
            if ($tmd[0] == '') {
                $res[$k] = '';
                continue;
            }
            if (strlen($key) === 6) {
                if ($tmd[6] != '15') {
                    $res[$k] = '';
                    continue;
                }
                $res[0]['code'] = substr($tmd[0], 3, -1);
                $res[0]['name'] = substr($tmd[2], 1, -1);
                $res[0]['pin']  = substr($tmd[3], 1, -1);
            } else {
                $res[$k]['code'] = substr($tmd[0], 3, -1);
                $res[$k]['name'] = substr($tmd[2], 1, -1);
                $res[$k]['pin']  = substr($tmd[3], 1, -1);
            }
        }
        ksort($res);
        return $res;
    }
}
if (!function_exists('sina_market_b')) {
    function sina_market_b($code)
    {
        $ch  = curl_init();
        $url = 'http://hq.sinajs.cn/list=' . $code;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 是否抓取跳转后的页面
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'referer: https://finance.sina.com.cn/'
        ));
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        $t2 = explode(',', mb_convert_encoding($output, 'utf-8', 'gbk'));
        return $t2;
    }
}
if (!function_exists('sina_market')) {
    function sina_market($code)
    {
        $d     = fenxi($code);
        $randm = time() . mt_rand(100, 999);
        $url   = 'http://hq.sinajs.cn/rn=' . $randm . '&list=' . $d . ',' . $d . '_i';
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 是否抓取跳转后的页面
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'referer: https://finance.sina.com.cn/'
        ));
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        $pos    = strpos($output, '=') + 2;
        $output = substr($output, $pos, -3);
        $t2     = explode(',', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $t2[32] = $t2[0];
        $t2[0]  = $d;
        return $t2;
    }
}
if (!function_exists('qq_market')) {
    function qq_market($code)
    {
        $d   = fenxi($code);
        $ch  = curl_init();
        $url = 'https://qt.gtimg.cn/q=' . $d;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 是否抓取跳转后的页面
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        $t2    = explode('~', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $t2[0] = substr($t2[0], 2, 8);
        return $t2;
    }
}
if (!function_exists('z_minute_k')) {
    function z_minute_k($code)
    {
        $res = \think\Db::name('admin_config')->where(array(
            'name' => 'market_data_in'
        ))->value('value');
        $res = 1;
        switch ($res) {
            case 1:
                $data = qq_minute_k($code);
                break;
            case 2:
                $data = wy_minute_k($code);
                break;
            case 3:
                $data = api_minute_k($code);
                break;
            default:
                $data = qq_minute_k($code);
                break;
        }
        return $data;
    }
}
if (!function_exists('z_day_k')) {
    function z_day_k($code)
    {
        $res = \think\Db::name('admin_config')->where(array(
            'name' => 'market_data_in'
        ))->value('value');
        $res = 1;
        switch ($res) {
            case 1:
                $data = qq_day_k($code);
                break;
            case 2:
                $data = sina_day_k($code);
                break;
            case 3:
                $data = api_k($code, 5);
                break;
            default:
                $data = qq_day_k($code);
                break;
        }
        return $data;
    }
}
if (!function_exists('z_week_k')) {
    function z_week_k($code)
    {
        $res = \think\Db::name('admin_config')->where(array(
            'name' => 'market_data_in'
        ))->value('value');
        switch ($res) {
            case 1:
                $data = qq_week_k($code);
                break;
            case 2:
                $data = sina_week_k($code);
                break;
            case 3:
                $data = api_k($code, 6);
                break;
            default:
                $data = qq_week_k($code);
                break;
        }
        return $data;
    }
}
if (!function_exists('z_month_k')) {
    function z_month_k($code)
    {
        $res = \think\Db::name('admin_config')->where(array(
            'name' => 'market_data_in'
        ))->value('value');
        switch ($res) {
            case 1:
                $data = qq_month_k($code);
                break;
            case 2:
                $data = sina_month_k($code);
                break;
            case 3:
                $data = api_k($code, 7);
                break;
            default:
                $data = qq_month_k($code);
                break;
        }
        return $data;
    }
}
if (!function_exists('api_minute_k')) {
    function api_minute_k($code)
    {
        if ($code === 'sh000001') {
            $code = '999999';
        }
        $tmd = gs('TimeData', $code);
        unset($tmd[0]);
        $time = strtotime(date('y-m-d 09:29:00', time()));
        $res  = array();
        foreach ($tmd as $k => $v) {
            if ($k == 122) {
                $time = $time + 5400;
            }
            $res[$k - 1]['time']   = date('Hi', $time + $k * 60);
            $res[$k - 1]['price']  = $v[0];
            $res[$k - 1]['volume'] = $v[1];
        }
        return $res;
    }
}
if (!function_exists('api_k')) {
    function api_k($code, $period)
    {
        $index = 0;
        if ($code === 'sh000001') {
            $code  = '999999';
            $index = 1;
        }
        if ($code === '399001' || $code === '399006') {
            $index = 1;
        }
        $tmd = gs('GetBar', array(
            $code,
            $period,
            $index
        ));
        unset($tmd[0]);
        $res = array();
        foreach ($tmd as $k => $v) {
            $res[$k - 1]['time']   = substr($v[0], 2);
            $res[$k - 1]['open']   = substr($v[1], 0, -4);
            $res[$k - 1]['close']  = substr($v[2], 0, -4);
            $res[$k - 1]['high']   = substr($v[3], 0, -4);
            $res[$k - 1]['low']    = substr($v[4], 0, -4);
            $res[$k - 1]['volume'] = substr($v[5], 0, -2);
        }
        return $res;
    }
}
if (!function_exists('qq_minute_k')) {
    function qq_minute_k($code)
    {
        $d   = fenxi($code);
        $url = 'http://data.gtimg.cn/flashdata/hushen/minute/' . $d . '.js';
        $ch  = $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = $output2 = curl_exec($curl);
        curl_close($ch);
        $t2    = explode('\\n\\', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $count = count($t2);
        unset($t2[0]);
        unset($t2[1]);
        unset($t2[$count - 1]);
        foreach ($t2 as $k => $v) {
            $t2[$k - 2] = explode(' ', trim($v));
        }
        unset($t2[$count - 2]);
        unset($t2[$count - 3]);
        ksort($t2);
        $res = array();
        foreach ($t2 as $k => $v) {
            $res[$k]['time']   = $v[0];
            $res[$k]['price']  = $v[1];
            $res[$k]['volume'] = $v[2];
        }
        $ret = array();
        foreach ($res as $k => $v) {
            $ret[$k]['time']  = $res[$k]['time'];
            $ret[$k]['price'] = $res[$k]['price'];
            if ($k == 0) {
                $ret[$k]['volume'] = $res[$k]['volume'];
                continue;
            }
            $ret[$k]['volume'] = (string)($res[$k]['volume'] - $res[$k - 1]['volume']);
        }
        return $ret;
    }
}
if (!function_exists('z_search_keyword')) {
    function z_search_keyword($key)
    {
        $res = \think\Db::name('admin_config')->where(array(
            'name' => 'market_data_in'
        ))->value('value');
        switch ($res) {
            case 1:
                $data = qq_keyword($key);
                break;
            case 2:
                $data = sina_keyword($key);
                break;
            case 3:
                $data = wy_keyword($key);
                break;
            default:
                $data = qq_keyword($key);
                break;
        }
        return $data;
    }
}
if (!function_exists('wy_keyword')) {
    function wy_keyword($key)
    {
        $url = 'http://quotes.money.163.com/stocksearch/json.do?type=&count=10&word=' . $key . '&t=0.599924786016345';
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        $t2  = json_decode(substr($output, 27, -1));
        $res = array();
        if (empty($t2)) {
            return $res;
        }
        foreach ($t2 as $k => $v) {
            $res[$k]['code'] = $v->symbol;
            $res[$k]['name'] = $v->name;
            $res[$k]['pin']  = $v->spell;
        }
        return $res;
    }
}
if (!function_exists('qq_keyword')) {
    function qq_keyword($key)
    {
        $url = 'http://smartbox.gtimg.cn/s3/?v=2&q=' . $key . '&t=all';
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        $output = preg_replace_callback('#\\\\u([0-9a-f]+)#i', function ($m) {
            return mb_convert_encoding(pack('H4', $m[1]), 'UTF-8', 'UCS-2');
        }, $output);
        $t2     = substr($output, 8, -1);
        if ($t2 == 'N"') {
            return '';
        }
        $t3  = explode('^', $t2);
        $res = array();
        foreach ($t3 as $k => $v) {
            $tmd             = explode('~', $v);
            $res[$k]['code'] = $tmd[1];
            $res[$k]['name'] = $tmd[2];
            $res[$k]['pin']  = $tmd[3];
        }
        return $res;
    }
}
if (!function_exists('hx_keyword')) {
    function hx_keyword($key)
    {
        $url = 'http://so.hexun.com/ajax.do?key=' . $key . '&type=stock?math=0.4235173752531409';
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        $t2  = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), 19);
        $t2  = json_decode($t2);
        $res = array();
        foreach ($t2 as $k => $v) {
            $res[$k]['code'] = $v->code;
            $res[$k]['name'] = $v->name;
            $res[$k]['pin']  = $v->pinyin;
        }
        return $res;
    }
}
if (!function_exists('jyj_keyword')) {
    function jyj_keyword($key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://code.jrjimg.cn/code?1=1&item=10&areapri=cn|hk|us|stb|ylbtg&typepri=s|w|i|f|b.hg&key=' . $key . '&d=131149');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $t2  = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), 26, -63);
        $t3  = explode('},{', $t2);
        $res = array();
        foreach ($t3 as $k => $v) {
            $tmd = explode('",', $v);
            list (, $res[$k]['code']) = explode(':"', $tmd[1]);
            list (, $res[$k]['name']) = explode(':"', $tmd[2]);
            list (, $res[$k]['pin']) = explode(':"', $tmd[3]);
        }
        return $res;
    }
}
if (!function_exists('api_keyword')) {
    function api_keyword($key)
    {
        $res = array();
        if (preg_match('/\\d/', $key)) {
            $res = gs('SearchByCode', array(
                $Name = $key
            ));
        }
        if (preg_match('/[A-Za-z]/', $key)) {
            $res = gs('searchByName', array(
                $Name = $key
            ));
        }
        if (0 < preg_match('/[\\x{4e00}-\\x{9fa5}]/u', $key)) {
            $res = gs('searchByName', array(
                $Name = $key
            ));
        }
        return $res;
    }
}
if (!function_exists('')) {
    function sina_keyword($key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://suggest3.sinajs.cn/suggest/type=&key=' . $key . '&name=suggestdata_' . time());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $t2  = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), 28, -3);
        $t3  = explode(';', $t2);
        $res = array();
        foreach ($t3 as $k => $v) {
            $tmd = explode(',', $v);
            if ($tmd[1] != '31') {
                $res[$k]['code'] = $tmd[2];
                $res[$k]['name'] = $tmd[4];
                $res[$k]['pin']  = strtoupper($tmd[5]);
            }
        }
        return $res;
    }
}
if (!function_exists('')) {
    function qq_month_k($code)
    {
        $d  = fenxi($code);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://data.gtimg.cn/flashdata/hushen/latest/monthly/' . $d . '.js');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $t2    = explode('\\n\\', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $count = count($t2);
        unset($t2[0]);
        unset($t2[1]);
        unset($t2[$count - 1]);
        foreach ($t2 as $k => $v) {
            $t2[$k - 2] = explode(' ', trim($v));
        }
        unset($t2[$count - 2]);
        unset($t2[$count - 3]);
        ksort($t2);
        $res = array();
        foreach ($t2 as $k => $v) {
            $res[$k]['time']   = $v[0];
            $res[$k]['open']   = $v[1];
            $res[$k]['close']  = $v[2];
            $res[$k]['high']   = $v[3];
            $res[$k]['low']    = $v[4];
            $res[$k]['volume'] = $v[5];
        }
        return $res;
    }
}
if (!function_exists('')) {
    function qq_week_k($code)
    {
        $d  = fenxi($code);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://data.gtimg.cn/flashdata/hushen/latest/weekly/' . $d . '.js');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $t2    = explode('\\n\\', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $count = count($t2);
        unset($t2[0]);
        unset($t2[1]);
        unset($t2[$count - 1]);
        foreach ($t2 as $k => $v) {
            $t2[$k - 2] = explode(' ', trim($v));
        }
        unset($t2[$count - 2]);
        unset($t2[$count - 3]);
        ksort($t2);
        $res = array();
        foreach ($t2 as $k => $v) {
            $res[$k]['time']   = $v[0];
            $res[$k]['open']   = $v[1];
            $res[$k]['close']  = $v[2];
            $res[$k]['high']   = $v[3];
            $res[$k]['low']    = $v[4];
            $res[$k]['volume'] = $v[5];
        }
        return $res;
    }
}
if (!function_exists('')) {
    function fenxi($code)
    {
        switch (substr($code, 0, 1)) {
            case '0':
                $d = 'sz' . $code;
                break;
            case '1':
                $d = 'sz' . $code;
                break;
            case '2':
                $d = 'sz' . $code;
                break;
            case '3':
                $d = 'sz' . $code;
                break;
            case '4':
                $d = 'bj' . $code;
            case '5':
                $d = 'sh' . $code;
                break;
            case '6':
                $d = 'sh' . $code;
                break;
            case '8':
                $d = 'bj' . $code;
                break;
            case '9':
                $d = 'sh' . $code;
                break;
            default:
                $d = $code;
                break;
        }
        return $d;
    }
}
if (!function_exists('object2array')) {
    function object2array($object)
    {
        $array = array();
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        } else {
            $array = $object;
        }
        return $array;
    }
}
if (!function_exists('wy_minute_k')) {
    function wy_minute_k($code)
    {
        switch (substr($code, 0, 1)) {
            case '0':
                $d = '1' . $code;
                break;
            case '3':
                $d = '1' . $code;
                break;
            case '6':
                $d = '0' . $code;
                break;
            case 's':
                $d = '0' . substr($code, 2);
                break;
            default:
                $d = $code;
                break;
        }
        $ch  = curl_init();
        $url = 'http://img1.money.126.net/data/hs/time/today/' . $d . '.json';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $tmd = json_decode($output)->data;
        $res = array();
        foreach ($tmd as $k => $v) {
            $res[$k]['time']   = $v[0];
            $res[$k]['price']  = $v[1];
            $res[$k]['volume'] = round($v[3] / 100, 0);
        }
        return $res;
    }
}
if (!function_exists('sina_day_k')) {
    function sina_day_k($code)
    {
        $d   = fenxi($code);
        $ch  = curl_init();
        $url = 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData?symbol=' . $d . '&scale=5&ma=no&datalen=100';
        // $url="http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData?symbol={$d}&scale=5&datalen=100";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = substr($output, 2);
        $output = substr($output, 0, -3);
        $t2     = explode('},{', $output);
        // $t2 = explode('},{', $t2);
        $res = array();
        foreach ($t2 as $k => $v) {
            $tmd = explode(',', $v);
            // $res[$k]['time2'] =substr($tmd[0], 7, -1);
            // $res[$k]['time3'] =$tmd[0];
            $res[$k]['time']   = date('ymd', strtotime(substr($tmd[0], 7, -1)));
            $res[$k]['open']   = substr($tmd[1], 8, -2);
            $res[$k]['high']   = substr($tmd[2], 8, -2);
            $res[$k]['low']    = substr($tmd[3], 7, -2);
            $res[$k]['close']  = substr($tmd[4], 9, -2);
            $res[$k]['volume'] = substr($tmd[5], 10, -1);
        }
        return $res;
    }
}
if (!function_exists('sina_week_k')) {
    function sina_week_k($code)
    {
        $d  = fenxi($code);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData?symbol=' . $d . '&scale=1200&ma=no&datalen=100');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = substr($output, 2);
        $output = substr($output, 0, -3);
        $t2     = explode('},{', $output);
        $res    = array();
        foreach ($t2 as $k => $v) {
            $tmd               = explode(',', $v);
            $res[$k]['time']   = date('ymd', strtotime(substr($tmd[0], 7, -1)));
            $res[$k]['open']   = substr($tmd[1], 8, -2);
            $res[$k]['high']   = substr($tmd[2], 8, -2);
            $res[$k]['low']    = substr($tmd[3], 7, -2);
            $res[$k]['close']  = substr($tmd[4], 9, -2);
            $res[$k]['volume'] = substr($tmd[5], 10, -1);
        }
        return $res;
    }
}
if (!function_exists('sina_month_k')) {
    function sina_month_k($code)
    {
        $d  = fenxi($code);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData?symbol=' . $d . '&scale=7200&ma=no&datalen=100');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = substr($output, 2);
        $output = substr($output, 0, -3);
        $t2     = explode('},{', $output);
        $res    = array();
        foreach ($t2 as $k => $v) {
            $tmd               = explode(',', $v);
            $res[$k]['time']   = date('ymd', strtotime(substr($tmd[0], 7, -1)));
            $res[$k]['open']   = substr($tmd[1], 8, -2);
            $res[$k]['high']   = substr($tmd[2], 8, -2);
            $res[$k]['low']    = substr($tmd[3], 7, -2);
            $res[$k]['close']  = substr($tmd[4], 9, -2);
            $res[$k]['volume'] = substr($tmd[5], 10, -1);
        }
        return $res;
    }
}
if (!function_exists('qq_day_k')) {
    function qq_day_k($code)
    {
        $d   = fenxi($code);
        $url = 'https://data.gtimg.cn/flashdata/hushen/latest/daily/' . $d . '.js?maxage=43201&visitDstTime=1';
        $ch  = $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $output = $output2 = curl_exec($curl);
        curl_close($ch);
        $t2    = explode('\\n\\', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $count = count($t2);
        unset($t2[0]);
        unset($t2[1]);
        unset($t2[$count - 1]);
        foreach ($t2 as $k => $v) {
            $t2[$k - 2] = explode(' ', trim($v));
        }
        unset($t2[$count - 2]);
        unset($t2[$count - 3]);
        ksort($t2);
        $res = array();
        foreach ($t2 as $k => $v) {
            $res[$k]['time']   = $v[0];
            $res[$k]['open']   = $v[1];
            $res[$k]['close']  = $v[2];
            $res[$k]['high']   = $v[3];
            $res[$k]['low']    = $v[4];
            $res[$k]['volume'] = $v[5];
        }
        return $res;
    }
}
if (!function_exists('qq_market_b')) {
    function qq_market_b($code)
    {
        $ch  = curl_init();
        $url = 'https://web.sqt.gtimg.cn/q=' . $code;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        $list = explode(';', $output);
        $data = [];
        foreach ($list as $k => $v) {
            $data[$k] = explode('~', mb_convert_encoding($v, 'utf-8', 'gbk'));

            $data[$k][0] = substr($v[0], 2, 8);

        }
        printlog($data, "行情数据", 'function');
        return $data;
    }
}
if (!function_exists('qq_to_api')) {
    function qq_to_api($res)
    {
        if (substr($res[0], 0, 2) === 'sh') {
            $res[40] = '1';
        } else {
            $res[40] = '0';
        }
        if (mktime(15, 0) < time()) {
            $time = date('H:i:s', mktime(15, 0, 0));
        } else {
            $time = date('H:i:s', time());
        }
        if (!isset($res[2])) {
            return '';
        }
        $res_data = array(
            'code'                     => $res[2],
            'name'                     => $res[1],
            'yesterday_price'          => $res[4],
            'open_price'               => $res[5],
            'national_debt'            => '0.00',
            'current_price'            => $res[3],
            'buy_one_price'            => $res[9],
            'buy_two_price'            => $res[11],
            'buy_three_price'          => $res[13],
            'buy_one_amount'           => $res[10],
            'buy_two_amount'           => $res[12],
            'buy_three_amount'         => $res[14],
            'sell_one_price'           => $res[19],
            'sell_two_price'           => $res[21],
            'sell_three_price'         => $res[23],
            'sell_one_amount'          => $res[20],
            'sell_two_amount'          => $res[22],
            'sell_three_amount'        => $res[24],
            'buy_four_price'           => $res[15],
            'buy_five_price'           => $res[17],
            'buy_four_amount'          => $res[16],
            'buy_five_amount'          => $res[18],
            'sell_four_price'          => $res[25],
            'sell_five_price'          => $res[27],
            'sell_four_amount'         => $res[26],
            'sell_five_amount'         => $res[28],
            'exchange_code'            => $res[40],
            'mini_trans'               => 100,
            'buy_chang_price'          => '0.01',
            'sell_chang_price'         => '0.01',
            'type'                     => 1,
            'currency'                 => 0,
            'debt_sign'                => 255,
            'info'                     => '',
            'highest'                  => $res[41],
            'lowest'                   => $res[42],
            'volume'                   => $res[36],
            'turnover'                 => $res[37],
            'time'                     => $time,
            'turnover_rate'            => $res[38],
            'pe_ratio'                 => $res[39],
            'circulation_market_value' => $res[44],
            'total_market_value'       => $res[45],
            'pb_ratio'                 => $res[46]
        );
        update_stock_real_time($res_data, $res[2], 2);
        return $res_data;
    }
}
if (!function_exists('sina_to_api')) {
    function sina_to_api($res)
    {
        if (substr($res[0], 0, 2) === 'sh') {
            $res[53] = '1';
        } else {
            $res[53] = '0';
        }
        if (empty($res[41]) || empty($res[34]) || empty($res[37])) {
            return false;
        }
        if ($res[10] == '0') {
            $res[10] = '000';
        }
        if ($res[12] == '0') {
            $res[12] = '000';
        }
        if ($res[14] == '0') {
            $res[14] = '000';
        }
        if ($res[16] == '0') {
            $res[16] = '000';
        }
        if ($res[18] == '0') {
            $res[18] = '000';
        }
        if ($res[20] == '0') {
            $res[20] = '000';
        }
        if ($res[22] == '0') {
            $res[22] = '000';
        }
        if ($res[24] == '0') {
            $res[24] = '000';
        }
        if ($res[26] == '0') {
            $res[26] = '000';
        }
        if ($res[28] == '0') {
            $res[28] = '000';
        }
        return array(
            'code'                     => substr($res[0], 2),
            'name'                     => $res[32],
            'yesterday_price'          => $res[2],
            'open_price'               => $res[1],
            'national_debt'            => '0.00',
            'current_price'            => $res[3],
            'buy_one_price'            => $res[11],
            'buy_two_price'            => $res[13],
            'buy_three_price'          => $res[15],
            'buy_one_amount'           => substr($res[10], 0, -2),
            'buy_two_amount'           => substr($res[12], 0, -2),
            'buy_three_amount'         => substr($res[14], 0, -2),
            'sell_one_price'           => $res[21],
            'sell_two_price'           => $res[23],
            'sell_three_price'         => $res[25],
            'sell_one_amount'          => substr($res[20], 0, -2),
            'sell_two_amount'          => substr($res[22], 0, -2),
            'sell_three_amount'        => substr($res[24], 0, -2),
            'buy_four_price'           => $res[17],
            'buy_five_price'           => $res[19],
            'buy_four_amount'          => substr($res[16], 0, -2),
            'buy_five_amount'          => substr($res[18], 0, -2),
            'sell_four_price'          => $res[27],
            'sell_five_price'          => $res[29],
            'sell_four_amount'         => substr($res[26], 0, -2),
            'sell_five_amount'         => substr($res[28], 0, -2),
            'exchange_code'            => $res[53],
            'mini_trans'               => 100,
            'buy_chang_price'          => '0.01',
            'sell_chang_price'         => '0.01',
            'type'                     => 1,
            'currency'                 => 0,
            'debt_sign'                => 255,
            'info'                     => '',
            'highest'                  => $res[4],
            'lowest'                   => $res[5],
            'volume'                   => (string)($res[8] / 100),
            'turnover'                 => (string)($res[9] / 10000),
            'time'                     => $res[31],
            'turnover_rate'            => (string)round($res[8] / $res[41] / 100, 2),
            'pe_ratio'                 => (string)round($res[3] / $res[34], 2),
            'circulation_market_value' => (string)round($res[3] * $res[41] / 10000, 2),
            'total_market_value'       => (string)round($res[3] * $res[39] / 10000, 2),
            'pb_ratio'                 => (string)round($res[3] / $res[37], 2)
        );
    }
}
if (!function_exists('sina_to_api_b')) {
    function sina_to_api_b($res)
    {
        if (substr($res[0], 0, 2) === 'sh') {
            $res[53] = '1';
        } else {
            $res[53] = '0';
        }
        return array(
            'code'              => substr($res[0], 2),
            'name'              => $res[32],
            'yesterday_price'   => $res[2],
            'open_price'        => $res[1],
            'national_debt'     => '0.00',
            'current_price'     => $res[3],
            'buy_one_price'     => $res[11],
            'buy_two_price'     => $res[13],
            'buy_three_price'   => $res[15],
            'buy_one_amount'    => substr($res[10], 0, -2),
            'buy_two_amount'    => substr($res[12], 0, -2),
            'buy_three_amount'  => substr($res[14], 0, -2),
            'sell_one_price'    => $res[21],
            'sell_two_price'    => $res[23],
            'sell_three_price'  => $res[25],
            'sell_one_amount'   => substr($res[20], 0, -2),
            'sell_two_amount'   => substr($res[22], 0, -2),
            'sell_three_amount' => substr($res[24], 0, -2),
            'buy_four_price'    => $res[17],
            'buy_five_price'    => $res[19],
            'buy_four_amount'   => substr($res[16], 0, -2),
            'buy_five_amount'   => substr($res[18], 0, -2),
            'sell_four_price'   => $res[27],
            'sell_five_price'   => $res[29],
            'sell_four_amount'  => substr($res[26], 0, -2),
            'sell_five_amount'  => substr($res[28], 0, -2),
            'exchange_code'     => $res[53],
            'mini_trans'        => 100,
            'buy_chang_price'   => '0.01',
            'sell_chang_price'  => '0.01',
            'type'              => 1,
            'currency'          => 0,
            'debt_sign'         => 255,
            'info'              => '',
            'highest'           => $res[4],
            'lowest'            => $res[5],
            'volume'            => (string)($res[8] / 100),
            'turnover'          => (string)($res[9] / 10000),
            'time'              => $res[31]
        );
    }
}
if (!function_exists('api_filter')) {
    function api_filter($res)
    {
        return array(
            'code'              => $res[0],
            'name'              => $res[1],
            'yesterday_price'   => $res[2],
            'open_price'        => $res[3],
            'national_debt'     => $res[4],
            'current_price'     => $res[5],
            'buy_one_price'     => $res[6],
            'buy_two_price'     => $res[7],
            'buy_three_price'   => $res[8],
            'buy_one_amount'    => $res[9],
            'buy_two_amount'    => $res[10],
            'buy_three_amount'  => $res[11],
            'sell_one_price'    => $res[12],
            'sell_two_price'    => $res[13],
            'sell_three_price'  => $res[14],
            'sell_one_amount'   => $res[15],
            'sell_two_amount'   => $res[16],
            'sell_three_amount' => $res[17],
            'buy_four_price'    => $res[18],
            'buy_five_price'    => $res[19],
            'buy_four_amount'   => $res[20],
            'buy_five_amount'   => $res[21],
            'sell_four_price'   => $res[22],
            'sell_five_price'   => $res[23],
            'sell_four_amount'  => $res[24],
            'sell_five_amount'  => $res[25],
            'exchange_code'     => $res[26],
            'mini_trans'        => $res[27],
            'buy_chang_price'   => $res[28],
            'sell_chang_price'  => $res[29],
            'type'              => $res[30],
            'currency'          => $res[31],
            'debt_sign'         => $res[32],
            'info'              => $res[33]
        );
    }
}
if (!function_exists('time_check')) {
    function time_check()
    {
        $tim = time() - strtotime(date('y-m-d 00:00:00', time()));
        if (54000 <= $tim || $tim <= 34200) {
            return false;
        }
        if (41400 <= $tim && $tim <= 46800) {
            return false;
        }
        return true;
    }
}
if (!function_exists('time_check')) {
    function microtime_float()
    {
        list ($usec, $sec) = explode(' ', microtime());
        return (double)$usec + (double)$sec;
    }
}
if (!function_exists('http')) {
    function http()
    {
        return empty($_SERVER['HTTP_X_CLIENT_PROTO']) ? 'http://' : $_SERVER['HTTP_X_CLIENT_PROTO'] . '://';
    }
}
if (!function_exists('sinahy')) {
    function sinahy()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://vip.stock.finance.sina.com.cn/q/view/newSinaHy.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '{"';
        $pos    = strpos($output, $findme) + 2;
        $output = substr($output, $pos, -2);
        $t2     = explode('","', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $res    = array();
        foreach ($t2 as $k => $v) {
            $res[$k] = explode(',', substr(explode(':', $v)[1], 1));
        }
        $lang = \think\facade\Lang::range();
        if ($lang != 'zh-cn') {
            $py = new Pinyin();
            foreach ($res as $k => $v) {
                $res[$k][1]  = industry_name_i18s($v[1], $lang);
                $res[$k][12] = $py->getpy($v[12]);
            }
        }
        return $res;
    }
}
if (!function_exists('qqtop10')) {
    function qqtop10()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/?q=bkqt_top10');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '="';
        $pos    = strpos($output, $findme) + 2;
        $output = substr($output, $pos, -1);
        $t2     = explode('^', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $res    = array();
        foreach ($t2 as $k => $v) {
            $res[$k] = explode('~', $v);
        }
        return $res;
    }
}
if (!function_exists('qqbot10')) {
    function qqbot10()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/?q=bkqt_bot10');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '="';
        $pos    = strpos($output, $findme) + 2;
        $output = substr($output, $pos, -1);
        $t2     = explode('^', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $res    = array();
        foreach ($t2 as $k => $v) {
            $res[$k] = explode('~', $v);
        }
        return $res;
    }
}
if (!function_exists('sinabot10')) {
    function sinabot10()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://hq.sinajs.cn/rn=1528781824076&format=text&list=sinaindustry_down');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '=[';
        $pos    = strpos($output, $findme) + 3;
        $output = substr($output, $pos, -3);
        $t2     = explode('\',\'', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $res    = array();
        foreach ($t2 as $k => $v) {
            $res[$k] = explode(',', $v);
        }
        return $res;
    }
}
if (!function_exists('sinatop10')) {
    function sinatop10()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://hq.sinajs.cn/rn=1528781848576&format=text&list=sinaindustry_up');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '=[';
        $pos    = strpos($output, $findme) + 3;
        $output = substr($output, $pos, -3);
        $t2     = explode('\',\'', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $res    = array();
        foreach ($t2 as $k => $v) {
            $res[$k] = explode(',', $v);
        }
        return $res;
    }
}
if (!function_exists('z_top10')) {
    function z_top10()
    {
        $ret  = 2;
        $data = array();
        switch ($ret) {
            case 1:
                $data = qqtop10();
                foreach ($data as $k => $v) {
                    unset($data[$k][8]);
                }
                $res = $data;
                break;
            case 2:
                $data = sinatop10();
                foreach ($data as $k => $v) {
                    $res[$k][0] = $v[0];
                    $res[$k][1] = $v[1];
                    $res[$k][2] = $v[2];
                    $res[$k][3] = $v[3];
                    $res[$k][4] = $v[5];
                    $res[$k][5] = $v[6];
                    $res[$k][6] = $v[7];
                    $res[$k][7] = $v[8];
                }
                break;
            case 3:
                $data = qqtop10();
                foreach ($data as $k => $v) {
                    unset($data[$k][8]);
                }
                $res = $data;
                break;
            default:
                $data = qqtop10();
                foreach ($data as $k => $v) {
                    unset($data[$k][8]);
                }
                $res = $data;
                break;
        }
        return $res;
    }
}
if (!function_exists('z_bot10')) {
    function z_bot10()
    {
        $ret  = 2;
        $data = array();
        switch ($ret) {
            case 1:
                $data = qqbot10();
                foreach ($data as $k => $v) {
                    unset($data[$k][8]);
                }
                $res = $data;
                break;
            case 2:
                $data = sinabot10();
                foreach ($data as $k => $v) {
                    $res[$k][0] = $v[0];
                    $res[$k][1] = $v[1];
                    $res[$k][2] = $v[2];
                    $res[$k][3] = $v[3];
                    $res[$k][4] = $v[5];
                    $res[$k][5] = $v[6];
                    $res[$k][6] = $v[7];
                    $res[$k][7] = $v[8];
                }
                break;
            case 3:
                $data = qqbot10();
                foreach ($data as $k => $v) {
                    unset($data[$k][8]);
                }
                $res = $data;
                break;
            default:
                $data = qqbot10();
                foreach ($data as $k => $v) {
                    unset($data[$k][8]);
                }
                $res = $data;
                break;
        }
        return $res;
    }
}
if (!function_exists('sina_stock_top10')) {
    function sina_stock_top10()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=1&num=10&sort=changepercent&asc=0&node=hs_a&symbol=&_s_r_a=setlen');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '[';
        $pos    = strpos($output, $findme) + 2;
        $output = substr($output, $pos, -2);
        $t2     = explode('},{', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $res    = array();
        $ret    = array();
        foreach ($t2 as $k => $v) {
            $findme  = '",buy:"';
            $pos     = strpos($v, $findme);
            $v       = substr($v, 0, $pos);
            $res[$k] = explode('",', $v);
            foreach ($res[$k] as $key => $value) {
                $res[$k][$key]              = explode(':"', $value);
                $ret[$k][$res[$k][$key][0]] = $res[$k][$key][1];
            }
            unset($ret[$k]['pricechange']);
            unset($ret[$k]['symbol']);
        }
        return $ret;
    }
}
if (!function_exists('qq_stock_top')) {
    function qq_stock_top($page = 1, $num = 20, $asc = 0)
    {
        $ch  = curl_init();
        $url = 'http://stock.gtimg.cn/data/view/rank.php?t=ranka/chr&p=' . $page . '&o=' . $asc . '&l=' . $num . '&v=list_data';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $num  = strpos($output, 'data:\'') + 6;
        $code = substr($output, $num, -3);
        $data = z_market_bat($code);
        $res  = array();
        foreach ($data as $k => $v) {
            $res[$k]['code']          = $v['code'];
            $res[$k]['name']          = $v['name'];
            $res[$k]['trade']         = $v['current_price'];
            $res[$k]['changepercent'] = (string)round(($v['current_price'] - $v['yesterday_price']) / $v['yesterday_price'] * 100, 1);
        }
        return $res;
    }
}
if (!function_exists('sina_stock_top')) {
    function sina_stock_top($page = 1, $num = 20, $asc = 0)
    {
        $ch  = curl_init();
        $url = 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=' . $page . '&num=' . $num . '&sort=changepercent&asc=' . $asc . '&node=hs_a&symbol=&_s_r_a=setlen';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        $data   = json_decode($output, true);
        return $data;
    }

    function sina_stock_top2($page = 1, $num = 20, $asc = 0)
    {
        $ch  = curl_init();
        $url = 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=' . $page . '&num=' . $num . '&sort=changepercent&asc=' . $asc . '&node=hs_a&symbol=&_s_r_a=setlen';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '[';
        $pos    = strpos($output, $findme) + 2;
        $output = substr($output, $pos, -2);
        $t2     = explode('},{', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $res    = array();
        $ret    = array();
        foreach ($t2 as $k => $v) {
            $findme  = '",buy:"';
            $pos     = strpos($v, $findme);
            $v       = substr($v, 0, $pos);
            $res[$k] = explode('",', $v);
            foreach ($res[$k] as $key => $value) {
                $res[$k][$key]              = explode(':"', $value);
                $ret[$k][$res[$k][$key][0]] = $res[$k][$key][1];
            }
            unset($ret[$k]['pricechange']);
            unset($ret[$k]['symbol']);
        }
        return $ret;
    }
}
if (!function_exists('sina_stock_bot10')) {
    function sina_stock_bot10()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=1&num=10&sort=changepercent&asc=1&node=hs_a&symbol=&_s_r_a=setlen');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '[';
        $pos    = strpos($output, $findme) + 2;
        $output = substr($output, $pos, -2);
        $t2     = explode('},{', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $res    = array();
        $ret    = array();
        foreach ($t2 as $k => $v) {
            $findme  = '",buy:"';
            $pos     = strpos($v, $findme);
            $v       = substr($v, 0, $pos);
            $res[$k] = explode('",', $v);
            foreach ($res[$k] as $key => $value) {
                $res[$k][$key]              = explode(':"', $value);
                $ret[$k][$res[$k][$key][0]] = $res[$k][$key][1];
            }
            unset($ret[$k]['pricechange']);
            unset($ret[$k]['symbol']);
        }
        return $ret;
    }
}
if (!function_exists('qq_stock_top10')) {
    function qq_stock_top10()
    {
        $time = time() . rand(100, 999);
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/?q=azdftop10&_=' . $time);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '="';
        $pos    = strpos($output, $findme) + 2;
        $output = substr($output, $pos, -4);
        $t2     = explode('^', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $ret    = array();
        foreach ($t2 as $k => $v) {
            $t2[$k]                   = explode('~', $v);
            $ret[$k]['code']          = $t2[$k][3];
            $ret[$k]['name']          = $t2[$k][0];
            $ret[$k]['trade']         = $t2[$k][1];
            $ret[$k]['changepercent'] = $t2[$k][2];
        }
        return $ret;
    }
}
if (!function_exists('qq_stock_bot10')) {
    function qq_stock_bot10()
    {
        $time = time() . rand(100, 999);
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/?q=azdfend10&_=' . $time);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $findme = '="';
        $pos    = strpos($output, $findme) + 2;
        $output = substr($output, $pos, -4);
        $t2     = explode('^', mb_convert_encoding($output, 'utf-8', 'gbk'));
        $ret    = array();
        foreach ($t2 as $k => $v) {
            $t2[$k]                   = explode('~', $v);
            $ret[$k]['code']          = $t2[$k][3];
            $ret[$k]['name']          = $t2[$k][0];
            $ret[$k]['trade']         = $t2[$k][1];
            $ret[$k]['changepercent'] = $t2[$k][2];
        }
        return $ret;
    }
}
if (!function_exists('z_stock_top10')) {
    function z_stock_top10()
    {
//        $ret = \think\Db::name('admin_config')->where(array(
//            'name' => 'market_data_in'
//        ))->value('value');
        $ret = 1;
        switch ($ret) {
            case 1:
                $data = qq_stock_top10();
                break;
            case 2:
                $data = sina_stock_top10();
                break;
            case 3:
                $data = qq_stock_top10();
                break;
            default:
                $data = qq_stock_top10();
                break;
        }
        return $data;
    }
}
if (!function_exists('z_stock_bot10')) {
    function z_stock_bot10()
    {
        $ret = \think\Db::name('admin_config')->where(array(
            'name' => 'market_data_in'
        ))->value('value');
        $ret = 1;
        switch ($ret) {
            case 1:
                $data = qq_stock_bot10();
                break;
            case 2:
                $data = sina_stock_bot10();
                break;
            case 3:
                $data = qq_stock_bot10();
                break;
            default:
                $data = qq_stock_bot10();
                break;
        }
        return $data;
    }
}
/*
 * market_bat批量获取数据
 * 监听某一接口错误后，自动转移到正常的其它接口
 *
 */
if (!function_exists('market_bat_error_transfer')) {
    function market_bat_error_transfer($code)
    {
        // Logs::log('z_market_bat',['code'=>$code],'market_bat_transfer');
        $market_data_in = [
            1,
            2,
            3,
            4,
            5
        ];
        $k              = count($market_data_in);
        $data           = array();
        for ($i = 1; $i <= $k; $i++) {
            $data = market_bat_transfer($code, $i);
            if ($data) {
                break;
            }
        }
        return $data;
    }
}
/*
 * market_bat批量获取数据
 * 监听某一接口错误后，自动转移到正常的其它接口
 *
 */
if (!function_exists('market_bat_transfer')) {
    function market_bat_transfer($code, $res)
    {
        $d = $d1 = $d2 = '';
        switch ($res) {
            case 1:
                $res   = explode(',', $code);
                $count = count($res);
                foreach ($res as $v) {
                    $d .= fenxi($v) . ',';
                }
                $tmd = qq_market_b($d);
                if (empty($tmd)) {
                    break;
                }
                for ($i = 0; $i < $count; $i++) {
                    $k = $i * 53;
                    for ($n = 1; $n <= 53; $n++) {
                        $num          = $n + $k;
                        $data[$i][$n] = $tmd[$num];
                    }
                    $data[$i][0] = '';
                    $data[$i]    = qq_to_api($data[$i]);
                }
                // Logs::log('z_market_bat',['code'=>$code,'data'=>$data],'market_bat_transfer');
                break;
            case 2:
                $res = explode(',', $code);
                Logs::log('z_market_bat_code', [
                    'code' => $code
                ], 'function');
                $count = count($res);
                foreach ($res as $v) {
                    $d .= fenxi($v) . ',';
                }
                $tmd = sina_market_b($d);
                Logs::log('sina_market_b', [
                    'sina_market_b' => $tmd
                ], 'function');
                if (empty($tmd)) {
                    break;
                }
                for ($i = 0; $i < $count; $i++) {
                    $k = $i * 32;
                    for ($n = 0; $n < 32; $n++) {
                        $num          = $n + $k;
                        $data[$i][$n] = $tmd[$num];
                    }
                    if ($i == 0) {
                        list ($tmp) = explode(';', $data[$i][0]);
                        $data[$i][0]  = substr($tmp, 11, 8);
                        $data[$i][32] = substr($tmp, 21);
                    } else {
                        list (, $tmp) = explode(';', $data[$i][0]);
                        $data[$i][0]  = substr($tmp, 12, 8);
                        $data[$i][32] = substr($tmp, 22);
                    }
                    $data[$i] = sina_to_api_b($data[$i]);
                }
                break;
            case 3:
                $res = gs('market_bat', array(
                    $code
                ));
                if (!empty($res['error'])) {
                    $data = $res['error'];
                    break;
                }
                if (is_array($res)) {
                    unset($res[0]);
                    $count = count($res);
                    for ($i = 0; $i < $count; $i++) {
                        $data[$i] = api_filter($res[$i + 1]);
                    }
                }
                break;
            case 4:
                $res   = explode(',', $code);
                $count = count($res);
                if ($count > 10) {
                    foreach ($res as $k => $v) {
                        if ($k > 9) {
                            $d1 .= fenxi($v) . ',';
                        } else {
                            $d2 .= fenxi($v) . ',';
                        }
                    }
                    $tmd1 = tianju_market($d1, 1); // 天聚数据
                    $tmd2 = tianju_market($d2, 1); // 天聚数据
                    $arr1 = json_decode($tmd1, true);
                    if ($arr1['Code'] == '-100') {
                        break;
                    }
                    $arr1 = $arr1['Obj'];
                    $arr2 = json_decode($tmd2, true);
                    if ($arr2['Code'] == '-100') {
                        break;
                    }
                    $arr2 = $arr2['Obj'];
                    $arr  = array_merge_recursive($arr2, $arr1);
                    foreach ($arr as $k => $v) {
                        $data[$k] = tianju_to_api($v);
                    }
                } else {
                    foreach ($res as $v) {
                        $d .= fenxi($v) . ',';
                    }
                    $tmd = tianju_market($d); // 天聚数据
                    $arr = json_decode($tmd, true);
                    if ($arr['Code'] == '-100') {
                        break;
                    }
                    $arr = $arr['Obj'];
                    foreach ($arr as $k => $v) {
                        $data[$k] = tianju_to_api($v);
                    }
                }
                break;
            case 5:
                $res   = explode(',', $code);
                $count = count($res);
                foreach ($res as $v) {
                    $d .= fenxi($v) . ',';
                }
                $tmd = qq_market_b($d);
                for ($i = 0; $i < $count; $i++) {
                    $k = $i * 53;
                    for ($n = 1; $n <= 53; $n++) {
                        $num          = $n + $k;
                        $data[$i][$n] = $tmd[$num];
                    }
                    $data[$i][0] = '';
                    $data[$i]    = qq_to_api($data[$i]);
                }
                break;
        }
        return $data;
    }
}
if (!function_exists('db_to_api')) {
    /*
     * 数据库实时行情转实盘API
     * $res 初始数据
     */
    function db_to_api($res)
    {
        return array(
            "code"              => $res['S'],
            "name"              => $res['N'],
            "yesterday_price"   => $res['YC'],
            "open_price"        => $res['O'],
            "national_debt"     => "0.00",
            "current_price"     => $res['P'],
            "buy_one_price"     => $res['B1'],
            "buy_two_price"     => $res['B2'],
            "buy_three_price"   => $res['B3'],
            "buy_one_amount"    => $res['B1V'],
            "buy_two_amount"    => $res['B2V'],
            "buy_three_amount"  => $res['B3V'],
            "sell_one_price"    => $res['S1'],
            "sell_two_price"    => $res['S2'],
            "sell_three_price"  => $res['S3'],
            "sell_one_amount"   => $res['S1V'],
            "sell_two_amount"   => $res['S2V'],
            "sell_three_amount" => $res['S3V'],
            "buy_four_price"    => $res['B4'],
            "buy_five_price"    => $res['B5'],
            "buy_four_amount"   => $res['B4V'],
            "buy_five_amount"   => $res['B5V'],
            "sell_four_price"   => $res['S4'],
            "sell_five_price"   => $res['S5'],
            "sell_four_amount"  => $res['S4V'],
            "sell_five_amount"  => $res['S5V'],
            "exchange_code"     => 1,
            "mini_trans"        => 100,
            "buy_chang_price"   => "0.01",
            "sell_chang_price"  => "0.01",
            "type"              => 1,
            "currency"          => 0,
            "debt_sign"         => 255,
            "info"              => "",
            "highest"           => $res['H'],
            "lowest"            => $res['L'], // "contest_buy_price" =>$res[6],"contest_sell_price" =>$res[7],
            "volume"            => $res['V'],
            "turnover"          => $res['A'],
            "time"              => date("h:i:s", $res['Tick'])
        );
    }
}
if (!function_exists('one_db_to_api')) {
    function one_db_to_api($res)
    {
        return array(
            'code'                     => $res['S'], // 0
            'name'                     => $res['N'], // 32
            'yesterday_price'          => $res['YC'], // 2
            'open_price'               => $res['O'], // 1
            'national_debt'            => '0.00',
            'current_price'            => $res['P'], // 3
            'buy_one_price'            => $res['B1'], // 11
            'buy_two_price'            => $res['B2'], // 13
            'buy_three_price'          => $res['B3'], // 15
            'buy_one_amount'           => $res['B1V'], // 10
            'buy_two_amount'           => $res['B2V'], // 12
            'buy_three_amount'         => $res['B3V'], // 14
            'sell_one_price'           => $res['S1'], // 21
            'sell_two_price'           => $res['S2'], // 23
            'sell_three_price'         => $res['S3'], // 25
            'sell_one_amount'          => $res['S1V'], // 20
            'sell_two_amount'          => $res['S2V'], // 22
            'sell_three_amount'        => $res['S3V'], // 24
            'buy_four_price'           => $res['B4'], // 17
            'buy_five_price'           => $res['B5'], // 19
            'buy_four_amount'          => $res['B4V'], // 16
            'buy_five_amount'          => $res['B5V'], // 18
            'sell_four_price'          => $res['S4'], // 27
            'sell_five_price'          => $res['S5'], // 29
            'sell_four_amount'         => $res['S4V'], // 26
            'sell_five_amount'         => $res['S5V'], // 28
            'exchange_code'            => 1, // 53
            'mini_trans'               => 100,
            'buy_chang_price'          => '0.01',
            'sell_chang_price'         => '0.01',
            'type'                     => 1,
            'currency'                 => 0,
            'debt_sign'                => 255,
            'info'                     => '',
            'highest'                  => $res['H'], // 4
            'lowest'                   => $res['L'], // 5
            'volume'                   => $res['V'], // 8
            'turnover'                 => (string)$res['A'], // 9
            'time'                     => date("h:i:s", $res['Tick']), // 31
            'turnover_rate'            => $res['HS'],
            'pe_ratio'                 => $res['SY'],
            'circulation_market_value' => $res['LS'],
            'total_market_value'       => $res['ZS'],
            'pb_ratio'                 => $res['SJ']
        ); // 175
    }
}
if (!function_exists('push')) {
    /*
     * $content推送内容
     * mid指定的用户mid
     */
    function push($content, $uid = 0)
    {
        return true;
        vendor("jpush.push");
        $jspush = new \push();
        if ($content) {
            if ($uid == 0) {
                $jspush->push_all($content);
            } elseif ($uid > 0) {
                $jspush->puhs_single($uid, $content);
            }
        }
    }
}
if (!function_exists('puhs_singles')) {
    /*
     * $data=[[uid=>$uid,gupiao_name=>$gupiao_name,gupiao_code=>$gupiao_code,'changepercent'=>$changepercent]]
     * $type = 'top'涨幅，bot跌幅
     * $content推送内容
     * mid指定的用户mid
     */
    function puhs_singles($data, $type = 'top')
    {
        return true;
        vendor("jpush.push");
        $jspush = new \push();
        if ($data && is_array($data)) {
            foreach ($data as $k => $v) {
                if ($type == 'top') {
                    $content = '您自选的股票-' . $v['gupiao_name'] . '(' . $v['gupiao_code'] . ')当前涨幅为' . $v['changepercent'];
                    $jspush->puhs_single($v['uid'], $content);
                } elseif ($type == 'bot') {
                    $content = '您自选的股票-' . $v['gupiao_name'] . '(' . $v['gupiao_code'] . ')当前跌幅幅为' . $v['changepercent'];
                    $jspush->puhs_single($v['uid'], $content);
                }
            }
        }
    }
}
if (!function_exists('setredis')) {
    function setredis($k, $v)
    {
        $redis = new Redis();
        $redis->connect(config('session.host'), config('session.port'));
        $redis->auth(config('session.password'));
        $redis->set($k, $v);
    }
}
if (!function_exists('getredis')) {
    function getredis($k)
    {
        $redis = new Redis();
        $redis->connect(config('session.host'), config('session.port'));
        $redis->auth(config('session.password'));
        return $redis->get($k);
    }
}
if (!function_exists('setexredis')) {
    function setexredis($key, int $expire, $value)
    {
//        $redis = new Redis();
        $redis = new \think\cache\driver\Redis();
        $redis->connect(config("session")['host'], 6379);
        echo config("session")['password'];
        $redis->auth(config("session")['password']);
        $redis->setex($key, $expire, $value);
    }
}
/**
 * 获取当前时间上一周的开始时间和结束时间；
 * 可以通过配置一周的开始时间，默认为星期一；
 * 使用方法，执行 extract(get_lastweek());
 * 之后，可以直接使用 $beginLastweek $endLastweek 两个变量；
 * 如果需要其他变量名称，可以修改参数 $begin 和 $end ，自行指定名称
 *
 * @param int $week_start_num
 *            一周的开始时间，默认为周一
 * @param int $now_time
 *            当前时间时间戳，这里做一个当前时间方便测试该方法的正确性
 * @param string $begin
 *            上一周开始时间的变量名称；
 * @param string $end
 *            上一周结束时间的变量名称；
 *
 * @return array()
 * @author leeyi <leeyisoft@qq.com>
 */
function get_lastweek($week_start_num = 1, $now_time = 0, $begin = 'beginLastweek', $end = 'endLastweek')
{
    $now_time       = $now_time > 0 ? $now_time : time();
    $now_weekday    = date('w', $now_time); // 获取当前是星期前 0-6 星期日-星期六
    $week_start_num = in_array($week_start_num, [
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6'
    ]) ? $week_start_num : 1; // 默认一周开始时间为周一
    $now_weekday    = $now_weekday < $week_start_num ? $now_weekday + 7 : $now_weekday;
    // php获取上周起始时间戳和结束时间戳
    $beginLastweek = $now_time - ($now_weekday + 7 - $week_start_num) * 86400;
    $endLastweek   = $beginLastweek + (6 * 86400);
    if (APP_DEBUG) { // for test
        // echo date('Y-m-d',$now_time).' : '.date('Y-m-d 00:00:00', $beginLastweek).' => '.date('Y-m-d 23:59:59', $endLastweek).'<br/>';
    }
    return array(
        $begin => strtotime(date('Y-m-d 00:00:00', $beginLastweek)),
        $end   => strtotime(date('Y-m-d 23:59:59', $endLastweek))
    );
}

function sina_test()
{
    $curl = curl_init();
    $url  = 'http://hq.sinajs.cn/rn=1685009250585&list=gb_dji,gb_ixic,gb_inx,hf_DJS,hf_NAS';
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 30,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
        CURLOPT_HTTPHEADER     => array(
            'Referer: https://finance.sina.com.cn/stock/usstock/'
        )
    ));
    if (1 == strpos("$" . $url, "https://")) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    $response = curl_exec($curl);
    curl_close($curl);
    $response = mb_convert_encoding($response, 'utf-8', 'gbk');
    dump($response);
    exit();
}

/**
 * $code_txt:gb_ixic,hkHSI,gb_inx,hf_DJS
 * @return void 获取美股港股数据,默认获取纳斯达克、恒生指数数据
 */
function sina_get_codes($code_txt = 'gb_ixic,hkHSI')
{
    $curl = curl_init();
    $url  = 'http://hq.sinajs.cn/rn=1685009250585&list=' . $code_txt;
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 30,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
        CURLOPT_HTTPHEADER     => array(
            'Referer: https://finance.sina.com.cn/stock/usstock/'
        )
    ));
    if (1 == strpos("$" . $url, "https://")) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    $response = curl_exec($curl);
    curl_close($curl);
    if ($response)
        $response = mb_convert_encoding($response, 'utf-8', 'gbk');
    return trim($response);
}

function format_sina($res)
{
    $result  = [];
    $res     = str_replace('"', '', $res);                      //去双引号
    $res     = str_replace(array("\r\n", "\r", "\n"), '', $res);//去换行号
    $res_arr = explode(';', $res);
    foreach ($res_arr as $k => $v) {
        if (strpos($v, 'hq_str_gb') !== false) {//全球
            $result[] = format_sina_gb($v);
        }
        if (strpos($v, 'hq_str_hk') !== false) {//
            $result[] = format_sina_hk($v);
        }
        if (strpos($v, 'hq_str_sh') !== false || strpos($v, 'hq_str_sz') !== false) {
            $result[] = format_sina_sh($v);
        }
    }
    return $result;
}

/**
 * 格式化新浪gb(全球)数据
 */
function format_sina_gb($txt)
{
    $ret         = [];
    $txt         = str_replace('var hq_str_', '', $txt);//去头
    $txt_arr     = explode('=', $txt);
    $ret['code'] = $txt_arr[0];
    if (!empty($txt_arr[1])) {
        $d                    = explode(',', trim($txt_arr[1]));
        $ret['name']          = $d[0];//名称
        $ret['current_price'] = $d[1];//当前价
        $ret['rate']          = $d[2];//涨跌幅
        $ret['diff']          = $d[4];//涨跌额
    }
    return $ret;
}

/**
 * 格式化新浪hk(香港)数据
 */
function format_sina_hk($txt)
{
    $ret         = [];
    $txt         = str_replace('var hq_str_', '', $txt);//去头
    $txt_arr     = explode('=', $txt);
    $ret['code'] = $txt_arr[0];
    if (!empty($txt_arr[1])) {
        $d                    = explode(',', trim($txt_arr[1]));
        $ret['name']          = $d[1];//名称
        $ret['current_price'] = $d[6];//当前价
        $ret['rate']          = $d[8];//涨跌幅
        $ret['diff']          = $d[7];//涨跌额
    }
    return $ret;
}

/**
 * 格式化新浪sh上证指数
 */
function format_sina_sh($txt)
{
    $ret         = [];
    $txt         = str_replace('var hq_str_', '', $txt);//去头
    $txt_arr     = explode('=', $txt);
    $ret['code'] = $txt_arr[0];
    if (!empty($txt_arr[1])) {
        $d                      = explode(',', trim($txt_arr[1]));
        $ret['name']            = $d[0];//名称
        $ret['current_price']   = $d[3];//当前价
        $ret['yesterday_price'] = $d[2];//昨收
        /* $ret['diff'] = $d[2];//涨跌额
         $ret['rate'] = $d[8];//涨跌幅*/
    }
    return $ret;
}

function reids_sh_KLineData()
{
    $data_key   = 'marketData.sh000001';
    $expire_key = 'marketData.expire.sh000001';//数据缓存时间： 2
    $expire     = Cache::get($expire_key);
    $data       = Cache::get($data_key);
    if ($expire) {
        return $data;
    } else {
        $txt  = web_sh_KLineData();
        $json = format_sh_KlineData($txt);
        if ($json) {
            Cache::set($data_key, $json);
            Cache::set($expire_key, time(), 120);
            return $json;
        }
        {//获取数据格式不正确
            return $data;
        }
    }
}

function format_sh_KlineData($txt)
{
    //正确返回txt包含字符
    $success_txt = "<script>location.href='//sina.com';</script>";
    if (strpos($txt, $success_txt) !== FALSE) {
        $txt_arr_one = explode('[', $txt);
        if (isset($txt_arr_one[1])) {
            $txt_arr_two = explode(']', $txt_arr_one[1]);
            $content     = $txt_arr_two[0] ?? '{}';
            $list        = [];
            $json        = '[' . $content . ']';
            $arr         = json_decode($json, true);
            foreach ($arr as $it) {
                $val          = [];
                $val['name']  = $it['day'];
                $val['value'] = [$it['day'], $it['high']];
                $list[]       = $val;
            }
            return json_encode($list);
        }
    }
    return '';
}

//上证指数分时信息
function web_sh_KLineData()
{
    // 数据间隔时间：scale=5
    // 数据条数：datalen=50
    $url = 'https://quotes.sina.cn/cn/api/jsonp_v2.php/var%20_sh000001_5_1685625197191=/CN_MarketDataService.getKLineData?';
    $url .= 'symbol=sh000001&scale=5&ma=no&datalen=50';
    $txt = file_get_contents($url);
    return $txt;
}

/**
 * http://quotes.sina.cn/hk/api/openapi.php/HK_MinlineService.getMinline?day=1&symbol=HSI
 *http://quotes.sina.cn/cn/api/jsonp_v2.php/test=/CN_MarketDataService.getKLineData?scale=5&ma=no&datalen=50&symbol=sh600000
 *https://stock.finance.sina.com.cn/hkstock/api/openapi.php/HK_StockService.getHKMinline?symbol=HSI&random=1686146351299
 *Desc:获取港股分时数据
 *
 * @param $cod：代码
 *
 * @return void
 *author:oszpac
 *date:2023-06-07
 */
function webHKKLine($code = '')
{
    $curl = curl_init();
    $URL  = "http://quotes.sina.cn/hk/api/openapi.php/HK_MinlineService.getMinline?day=1&symbol=" . $code;
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
        CURLOPT_HTTPHEADER     => array(
            'Referer:http://stock.finance.sina.com.cn/hkstock/quotes/HSI.html',
        ),
    ));
    $json     = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpCode == 200) {
        $response = json_decode($json, true);
        return $response;
    }
    return [];
}

/**
 *Desc:港股 A+H
 *return:
 *author:oszpac
 *date:2023-06-08
 */
function web_hk_AH()
{
    $curl           = curl_init();
    $code_str       = 'sz002594,hk01211,sh688235,hk06160,sz002821,hk06821,sh601888,hk01880,sh600600,hk00168,sh600941,hk00941,sh688185,hk06185,sh688366,hk06826,sh688339,hk02402,sz002466,hk09696,sz300347,hk03347,sh603259,hk02359,sz002460,hk01772,sh688331,hk09995,sh688981,hk00981,sh688385,hk01385,sh601318,hk02318,sz300759,hk03759,sh603127,hk06127,sh688187,hk03898,sh688180,hk01877,sh601995,hk03908,sz000513,hk01513,sh601628,hk02628,sh601336,hk01336,sz000063,hk00763,sh601869,hk06869,sh600332,hk00874,sh600036,hk03968,sh688236,hk01858,sh600660,hk03606,sh601865,hk06865,sh600196,hk02196,sh601088,hk01088,sh601601,hk02601,sh600188,hk01171,sh600685,hk00317,sz300748,hk06680,sh600585,hk00914,sz000921,hk00921,sh600547,hk01787,sh601066,hk06066,sh601633,hk02333,sh601607,hk02607,sz000756,hk00719,sh600690,hk06690,sz001289,hk00916,sh600030,hk06030,sh600362,hk00358,sh601811,hk00811,sh600938,hk00883,sh605198,hk02218,sh600875,hk01072,sz001236,hk03678,sh601788,hk06178,sh600876,hk01108,sz000776,hk01776,sh601211,hk02611,sz000002,hk02202,sh601808,hk02883,sh600999,hk06099,sh601688,hk06886,sh600801,hk06655,sh601717,hk00564,sz301039,hk01839,sh688428,hk09969,sz002399,hk09989,sz000338,hk02338,sh601881,hk06881,sh601899,hk02899,sh600012,hk00995,sh601038,hk00038,sh600026,hk01138,sh600860,hk00187,sz002202,hk02208,sh601919,hk01919,sh601800,hk01800,sh600775,hk00553,sh601238,hk02238,sh601186,hk01186,sh600377,hk00177,sh600958,hk03958,sh600837,hk06837,sh600011,hk00902,sh600548,hk00548,sh601456,hk01456,sh688505,hk01349,sh600956,hk00956,sh601963,hk01963,sh601898,hk01898,sh601111,hk00753,sh601390,hk00390,sh601857,hk00857,sz002703,hk01057,sh601298,hk06198,sh601330,hk01330,sz000039,hk02039,sh601998,hk00998,sh601766,hk01766,sh600027,hk01071,sh601939,hk00939,sh600028,hk00386,sz000157,hk01157,sh601319,hk01339,sh601728,hk00728,sh601328,hk03328,sh600874,hk01065,sh600029,hk01055,sh688009,hk03969,sz002672,hk00895,sh601600,hk02600,sh601068,hk02068,sh603993,hk03993,sh601658,hk01658,sh601398,hk01398,sh601828,hk01528,sz000488,hk01812,sh601598,hk00598,sh600115,hk00670,sh601727,hk02727,sz000166,hk06806,sh601107,hk00107,sz002490,hk00568,sh601988,hk03988,sh600016,hk01988,sh601077,hk03618,sh601618,hk01618,sh601333,hk00525,sh601375,hk01375,sh601288,hk01288,sh601326,hk03369,sh601991,hk00991,sz002948,hk03866,sh600635,hk01635,sh601818,hk06818,sh600688,hk00338,sz003816,hk01816,sz000898,hk00347,sh601916,hk02016,sh600808,hk00323,sh601866,hk02866,sh601868,hk03996,sz002936,hk06196,sh601992,hk02009,sh600871,hk01033,sh601588,hk00588,sh601880,hk02880,sh601005,hk01053';
    $web_url        = 'http://hq.sinajs.cn/rn=jqdqc&list=' . $code_str;
    $browserRequest = new BrowserRequest();
    $response       = $browserRequest->get($web_url);
    /*
     * 偶数行：A股
        0	    1	    2	    3	    4	    5	    6	    7	    8   	9	    10	11	12	13	14	15	16	17	18	19	20	21	22	23	24	25	26	27	28	29	30
        code	name	今开	昨收	现价	最高价	最低价	均价	最新价	成交量	成交额
    *奇数行 H股
        0	  1	    2	    3	    4	    5	    6	    7	    8	    9	    10	11	12	    13	14	15	16	17	18	19	20	21	22	23	24	25	26	27	28	29	30
        code		name	昨收	今开	最高价	最低价	现价	涨跌额	涨跌幅			成交额	成交量			52周最高	52周最低


   */
    if ($response) {
        $response = mb_convert_encoding($response, 'utf-8', 'gbk');
        $response = str_replace('var hq_str_', '', $response);
        $response = str_replace(array("\r\n", "\r", "\n"), '', $response);//去换行号
        $response = str_replace('"', '', $response);
        $response = str_replace('=', ',', $response);
        $response = rtrim($response, ';');
        $arr      = explode(';', $response);
        $list     = [];
        $i        = 0;
        foreach ($arr as $k => $v) {
            if ($v) {
                $item_arr = explode(',', $v);
                if ($k % 2 == 0) {                 //偶数
                    $list[$i]['t1'] = $item_arr[1];//名称
                    $list[$i]['t3'] = $item_arr[4];//a股价格
                    $rate           = bcdiv(($item_arr['4'] - $item_arr['3']), $item_arr['3'], 3);
                    $list[$i]['t4'] = $rate;//a股涨跌幅
                } else {                           //奇数
                    $list[$i]['t2'] = $item_arr[0];//代码
                    $list[$i]['t5'] = $item_arr[7];//H股现价
                    $list[$i]['t6'] = $item_arr[9];//H股涨跌幅
                    $i++;
                }
            }
        }
        $rate_arr = array_column($list, 't6');
        array_multisort($rate_arr, SORT_DESC, $list);
        return $list;
    }
    return [];
}

/**
 *Desc:热门港股数据
 *return: 数组
 *author:oszpac
 *date:2023-06-08
 */
/**
 *Desc:
 *
 * @param $sort 排序数据
 * @param $sort_type 0：降序  1:升序
 * @param $node
 *return:
 *author:oszpac
 *date:2023-06-08
 */
function web_hk_hot($sort = 'symbol', $sort_type = 1, $node = 'hot_hk')
{
    $curl    = curl_init();
    $web_url = "http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHKStockData?page=1&num=40&{$sort}=symbol&asc={$sort_type}&node={$node}&_s_r_a=init";
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $web_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET'
    ));
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpCode == 200) {
        $response = mb_convert_encoding($response, 'utf-8', 'gbk');
        //返回数据格式:json
        $arr  = json_decode($response, true);
        $list = [];
        foreach ($arr as $val) {
            $it['t1'] = $val['name'];       //名称
            $it['t2'] = $val['symbol'];     //代码
            $it['t3'] = $val['lasttrade'];  //最新价
            $it['t4'] = $val['pricechange'];//涨跌幅
            $it['t5'] = $val['prevclose'];  //昨收
            $it['t6'] = $val['open'];       //今开
            $list[]   = $it;
        }
        return $list;
    }
    return [];
}

/**
 *Desc:热门港股数据
 *return: 数组
 *author:oszpac
 *date:2023-06-08
 */
function web_hk_adr()
{
    $curl    = curl_init();
    $web_url = 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getADRData?page=1&num=20&sort=symbol&asc=1&node=adr_hk&_s_r_a=init';
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $web_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET'
    ));
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpCode == 200) {
        $response = mb_convert_encoding($response, 'utf-8', 'gbk');
        //返回数据格式:json
        $arr  = json_decode($response, true);
        $list = [];
        foreach ($arr as $val) {
            $it['t1'] = $val['chname'];   //名称
            $it['t2'] = $val['symbol'];   //代码
            $it['t3'] = $val['last'];     //最新价
            $it['t4'] = $val['pchg'];     //涨跌幅
            $it['t5'] = $val['prevclose'];//昨收
            $it['t6'] = $val['open'];     //今开
            $list[]   = $it;
        }
        return $list;
    }
    return [];
}

/**
 *Desc: 获取 美股指数数据
 *
 * @param $codes :代码，多个用,隔开
 *return:
 *author:oszpac
 *date:2023-06-08
 */
function web_gb_index($codes = 'gb_ixic')
{
    $curl    = curl_init();
    $web_url = 'http://hq.sinajs.cn/rn=' . time() . '&list=' . $codes;
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $web_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
        CURLOPT_HTTPHEADER     => array(
            'Referer: https://stock.finance.sina.com.cn/usstock/quotes/CAT.html'
        ),
    ));
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpCode == 200) {
        //需过滤字符串
        $earch     = [
            'var hq_str_',
            "\r\n",
            "\r",
            "\n",
            '"',
            'gb_'
        ];
        $response  = mb_convert_encoding($response, 'utf-8', 'gbk');
        $response  = str_replace($earch, '', $response);
        $response  = str_replace('=', ',', $response);
        $response  = rtrim($response, ';');//去尾
        $txt_array = explode(';', $response);
        $result    = [];
        foreach ($txt_array as $txt) {
//			echo $txt . PHP_EOL;
            if ($txt) {
                $it               = explode(',', $txt);
                $val['code']      = $it[0];
                $val['name']      = $it[1];
                $val['lastprice'] = $it[2];
                $val['rate']      = $it[3];
                $val['diff']      = round($it[11] / 100000000, 2);
                $result[]         = $val;
            }
        }
        return $result;
    }
    return [];
}

//获取美股分时数据
function web_us_minute($code)
{
    $curl    = curl_init();
    $web_url = 'https://web.ifzq.gtimg.cn/appstock/app/dayus/query?_var=&code=' . $code . '&r=0.' . mt_rand(1, 99999);
    $res     = file_get_contents($web_url);
    $res     = json_decode($res, true);
    if (is_array($res) && !empty($res['data'][$code]['data'][0]['data'])) {
        return $res['data'][$code]['data'][0]['data'];
    }
    return [];
}

/*
 * 获取美股分类数据,按涨跌幅降序排序
 * type FIN:金融类
 *      TEC：科技类
 *      MAR：制造零售类
 *      AAE：汽车能源类
 */
function web_us_type($type = 'FIN')
{
    $url      = 'https://stock.gtimg.cn/data/index.php?appn=usRank&t=' . $type . '/code&p=1&o=0&l=40&v=list&_du_r_t=0.' . mt_rand(1, 999999);
    $bR       = new BrowserRequest();
    $web_data = $bR->get($url);
    if ($web_data) {
        //需过滤字符串
        $earch = ['list=', "\r\n", "\r", "\n"];
        $json  = str_replace($earch, '', $web_data);
        $json  = mb_convert_encoding($json, 'utf-8', 'gbk');
        $list  = json_decode($json, true);
        if (is_array($list) && isset($list['data']['result'])) {
            $ret  = [];
            $data = $list['data']['result'];
            foreach ($data as $val) {
                $ret[] = [
                    't1' => $val[0],
                    't2' => $val[2],
                    't3' => $val[3],
                    't4' => $val[4],
                    't5' => $val[10],
                    't6' => $val[11],
                ];
            }
            return $ret;
        }
    }
    return [];
}

//获取美股汽车能源数据
function web_us_AAE()
{
    $result   = [];
    $url      = 'https://qt.gtimg.cn/q=usXOM,usWMB,usSO,usSLB,usHAL,usGM,usF,usEXC,usETR,usEP,usDUK,usCVX,usCOP,usCAT,usAES,usAEP&r=' . mt_rand(1, 999999);
    $bR       = new BrowserRequest();
    $web_data = $bR->get($url);
    if ($web_data) {
        //需过滤字符串
        $earch = ["\r\n", "\r", "\n", '"', 'v_us'];
        $str   = str_replace($earch, '', $web_data);
        $str   = str_replace('=', '~', $str);
        $str   = mb_convert_encoding($str, 'utf-8', 'gbk');
        $str   = rtrim($str, ';');
        $list  = explode(';', $str);
        foreach ($list as $txt) {
            $arr      = explode('~', $txt);
            $result[] = [
                't1' => $arr[0],
                't2' => $arr[2],
                't3' => $arr[4],
                't4' => $arr[33],
                't5' => $arr[6],
                't6' => $arr[5],
            ];
        }
    }
    return $result;
}

/**爬取qq指数数据
 *
 * @param $code ： 多个用, 分隔
 *
 * @return void
 */
function qqStockIndex($code)
{
    $ur             = 'https://qt.gtimg.cn/?q=' . $code . '&r=0.' . mt_rand(123456, 999999);
    $browserRequest = new BrowserRequest();
    $res            = $browserRequest->get($ur);
    if ($res) {
        $result = [];
        //需过滤字符串
        $earch = ["\r\n", "\r", "\n", '"', 'v_'];
        $res   = str_replace($earch, '', $res);
        $res   = str_replace('=', '~', $res);
        $res   = mb_convert_encoding($res, 'utf-8', 'gbk');
        $res   = rtrim($res, ';');
        $list  = explode(';', $res);
        foreach ($list as $txt) {// 3 6 31
            $d        = explode('~', $txt);
            $result[] = [
                'code'          => $d[0],
                'name'          => $d[2],
                'current_price' => $d[4],
                'diff'          => $d[32],
                'rate'          => $d[33],
            ];
        }
        return $result;
    }
    return [];
}

/**爬取qq分时数据
 *
 * @param $type： ['sh','us','hk'] [A股，美股，港股]
 * @param $code
 *
 * @return void
 */
function qqStockMinute($type, $code)
{
    $url = 'https://web.ifzq.gtimg.cn/appstock/app/minute/query?_var=&code=' . $code;
    if ($type == 'hk')
        $url = 'https://web.ifzq.gtimg.cn/appstock/app/hkMinute/query?_var=&code=' . $code;
    if ($type == 'us')
        $url = 'https://web.ifzq.gtimg.cn/appstock/app/UsMinute/query?_var=&code=' . $code;
    $url            .= '&r=0.897' . mt_rand(123456, 999999);
    $browserRequest = new BrowserRequest();
    $res            = $browserRequest->get($url);
    if ($res) {
        //file_put_contents('test.txt', '$res=' . var_export($res, true) . PHP_EOL, FILE_APPEND);
    }
    return [];
}

function translateStockName($name)
{
    $cn   = [
        'sh000001'  => '上证指数',
        'sz399001'  => '深证成指',
        'sz399006'  => '创业板指',
        'r_hkHSI'   => '恒生指数',
        'usIXIC'    => '纳斯达克',
        'r_hkHSCEI' => '国企指数',
        'r_hkHSCCI' => '红筹指数',
        'usDJI'     => '道琼斯',
        'usINX'     => '标普500',
        'dqhy'      => '电器行业',
        'hanyu'     => '汉宇集团',
        'jdly'      => '酒店旅游',
        'zhzm'      => '中国中免',
        'fdsb'      => '发电设备',
        'swgf'      => '尚纬股份',
        'slzp'      => '塑料制品',
        'sxxc'      => '双星新材',
        'yqyb'      => '仪器仪表',
        'xsd'       => '新时达',
        'jxhy'      => '机械行业',
        'blx'       => '保力新',
        'cbzz'      => '船舶制造',
        'zsgx'      => '江苏国信',
        'cmyl'      => '传媒娱乐',
        'dlhy'      => '电力行业',
        'flhye'     => '涪陵电力',
        'dzqj'      => '电子器件',
        'ywln'      => '亿纬锂能',
    ];
    $en   = [
        'sh000001'  => 'SSE',
        'sz399001'  => 'SIASA',
        'sz399006'  => 'ChiNext ',
        'r_hkHSI'   => 'GEM',
        'usIXIC'    => 'Nasdaq',
        'r_hkHSCEI' => 'HSCEI',
        'r_hkHSCCI' => 'HSCCI',
        'usDJI'     => 'Dow Jones',
        'usINX'     => 'S&P 500',
        'dqhy'      => 'Electrical Equipment',
        'hanyu'     => 'Hanyu Group',
        'jdly'      => 'Hotel tourism',
        'zhzm'      => 'China Zhongmian',
        'fdsb'      => 'Power generation equipment',
        'swgf'      => 'Shangwei Shares',
        'slzp'      => 'Plastic products',
        'sxxc'      => 'Double Star New Materials',
        'yqyb'      => 'Instruments and meters',
        'xsd'       => 'Xinshida',
        'jxhy'      => 'Machinery industry',
        'blx'       => 'Poly New',
        'cbzz'      => 'Ship manufacturing',
        'zsgx'      => 'Jiangsu Guoxin',
        'cmyl'      => 'Media and entertainment',
        'dlhy '     => 'Power industry ',
        'flhye'     => 'Fuling Power',
        'dzqj '     => 'Electronic devices',
        'ywln'      => 'Yiwei Lithium Energy',
    ];
    $vi   = [
        'sh000001'  => 'Chỉ số Shanghai',
        'sz399001'  => 'Bằng chứng sâu sắc',
        'sz399006'  => 'Bảng lập nghiệp chỉ',
        'r_hkHSI'   => 'Chỉ số Hang Seng',
        'usIXIC'    => 'Viet Nam',
        'r_hkHSCEI' => 'doanh nghiệp nhà',
        'r_hkHSCCI' => 'Chỉ số Red Funding',
        'usDJI'     => 'Việt',
        'usINX'     => 'Tiêu chuẩn',
        'dqhy'      => 'Ngành công nghiệp điện',
        'hanyu'     => 'Tập đoàn Hanyu',
        'jdly'      => ' du lịch khách sạn ',
        'ZHZM '     => ' trung quốc miễn phí ',
        'FDSB '     => ' thiết bị phát điện ',
        'SWGF '     => ' cân bằng cổ phần ',
        'SLZP '     => ' sản phẩm nhựa ',
        'SXXC '     => ' song tinh mới ',
        'yqyb'      => ' dụng cụ đo ',
        'XSD '      => ' xinshida ',
        'jxhy'      => ' ngành công nghiệp cơ khí ',
        'BLX '      => ' power mới ',
        'CBZZ'      => 'chế tạo tàu ',
        'ZSGX'      => 'jiangsu guoxin ',
        'cmyl'      => 'truyền thông giải trí ',
        'dlhy'      => 'ngành công nghiệp điện ',
        'flhye'     => 'fuling power ',
        'DZQJ'      => 'điện tử ',
        'ywln'      => 'vĩ tuyến lithium ',
    ];
    $lang = \think\facade\Lang::range();
    $key  = array_search($name, $cn);
    if ($key === FALSE)
        return $name;
    if ($lang == 'en-us')
        $name = $en[$key];
    if ($lang == 'vi-nt')
        $name = $vi[$key];
    return $name;
}

//调用数据源接口函数
if (!function_exists('getDataServer')) {
    function getDataServer($rout_url, $data)
    {
        $url                             = $rout_url . '&t=' . time() . '&key=' . config('data_service_key');
        $sign                            = md5($url);
        $url                             = config('data_service_site') . $url;
        $curl                            = curl_init();
        $requestdata                     = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $data
        );
        $kid                             = "kid:" . config('data_service_kid');
        $sign                            = "sign:" . $sign;
        $requestdata[CURLOPT_HTTPHEADER] = [
            $kid,
            $sign
        ];
        curl_setopt_array($curl, $requestdata);
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response, true);
        if ($res && $res['status'] != '200') {//请求失败，日志
            $logs = compact('rout_url', 'data', 'response', 'res');
            Logs::e_log('iApi_error', $logs, 'iApi');
            return false;
        }
        return $res;
    }
}
// 判断文件夹是否存在方法函数
function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
}

if (!function_exists('industry_name_i18s')) {
    /**
     *Desc: 行业名称翻译
     *
     * @param $cn_name ：中文行业名称
     * @param $cn_name ：需要翻译语种
     *return:
     *author:oszpac
     *date:2023-07-02
     */
    function industry_name_i18s($cn_name, $target_lang)
    {
        if ($target_lang == 'zh-cn')
            return $cn_name;
        $cn  = [
            'A1'  => '玻璃行业',
            'A2'  => '船舶制造',
            'A3'  => '传媒娱乐',
            'A4'  => '电力行业',
            'A5'  => '电器行业',
            'A6'  => '电子器件',
            'A7'  => '电子信息',
            'A8'  => '房地产',
            'A9'  => '发电设备',
            'A10' => '飞机制造',
            'A11' => '纺织行业',
            'A12' => '纺织机械',
            'A13' => '服装鞋类',
            'A14' => '公路桥梁',
            'A15' => '供水供气',
            'A16' => '钢铁行业',
            'A17' => '环保行业',
            'A18' => '化工行业',
            'A19' => '化纤行业',
            'A20' => '家电行业',
            'A21' => '酒店旅游',
            'A22' => '家具行业',
            'A23' => '金融行业',
            'A24' => '交通运输',
            'A25' => '机械行业',
            'A26' => '建筑建材',
            'A27' => '开发区',
            'A28' => '酿酒行业',
            'A29' => '摩托车',
            'A30' => '煤炭行业',
            'A31' => '农林牧渔',
            'A32' => '农药化肥',
            'A33' => '汽车制造',
            'A34' => '其它行业',
            'A35' => '塑料制品',
            'A36' => '水泥行业',
            'A37' => '食品行业',
            'A38' => '次新股',
            'A39' => '生物制药',
            'A40' => '商业百货',
            'A41' => '石油行业',
            'A42' => '陶瓷行业',
            'A43' => '物资外贸',
            'A44' => '医疗器械',
            'A45' => '仪器仪表',
            'A46' => '印刷包装',
            'A47' => '有色金属',
            'A48' => '综合行业',
            'A49' => '造纸行业',
            'A50' => '玻璃行业',
            'A51' => '船舶制造',
            'A52' => '传媒娱乐',
            'A53' => '电力行业',
            'A54' => '电器行业',
            'A55' => '电子器件',
            'A56' => '电子信息',
            'A57' => '房地产',
            'A58' => '发电设备',
            'A59' => '飞机制造',
            'A60' => '纺织行业',
            'A61' => '纺织机械',
            'A62' => '服装鞋类',
            'A63' => '公路桥梁',
            'A64' => '供水供气',
            'A65' => '钢铁行业',
            'A66' => '环保行业',
            'A67' => '化工行业',
            'A68' => '化纤行业',
            'A69' => '家电行业',
            'A70' => '酒店旅游',
            'A71' => '家具行业',
            'A72' => '金融行业',
            'A73' => '交通运输',
            'A74' => '机械行业',
            'A75' => '建筑建材',
            'A76' => '开发区',
            'A77' => '酿酒行业',
            'A78' => '摩托车',
            'A79' => '煤炭行业',
            'A80' => '农林牧渔',
            'A81' => '农药化肥',
            'A82' => '汽车制造',
            'A83' => '其它行业',
            'A84' => '塑料制品',
            'A85' => '水泥行业',
            'A86' => '食品行业',
            'A87' => '次新股',
            'A88' => '生物制药',
            'A89' => '商业百货',
            'A90' => '石油行业',
            'A91' => '陶瓷行业',
            'A92' => '物资外贸',
            'A93' => '医疗器械',
            'A94' => '仪器仪表',
            'A95' => '印刷包装',
            'A96' => '有色金属',
            'A97' => '综合行业',
            'A98' => '造纸行业',
        ];
        $en  = [
            'A1'  => 'Glass industry',
            'A2'  => 'Shipbuilding',
            'A3'  => 'Media and entertainment',
            'A4'  => 'Power industry',
            'A5'  => 'Electrical industry',
            'A6'  => 'Electronic devices',
            'A7'  => 'Electronic Information',
            'A8'  => 'real estate',
            'A9'  => 'Power generation equipment',
            'A10' => 'Aircraft manufacturing',
            'A11' => 'Textile industry',
            'A12' => 'textile machinery',
            'A13' => 'Clothing and footwear',
            'A14' => 'Highway bridges',
            'A15' => 'Water supply and gas supply',
            'A16' => 'Steel industry',
            'A17' => 'Environmental protection industry',
            'A18' => 'Chemical industry',
            'A19' => 'Chemical fiber industry',
            'A20' => 'Home appliance industry',
            'A21' => 'Hotel tourism',
            'A22' => 'Furniture industry',
            'A23' => 'Financial industry',
            'A24' => 'Transportation',
            'A25' => 'Machinery industry',
            'A26' => 'Building materials',
            'A27' => 'development zones',
            'A28' => 'Brewing industry',
            'A29' => 'motorcycle',
            'A30' => 'Coal industry',
            'A31' => 'Agriculture, forestry, animal husbandry, and fishing',
            'A32' => 'Pesticides and fertilizers',
            'A33' => 'Automobile manufacturing',
            'A34' => 'Other industries',
            'A35' => 'Plastic products',
            'A36' => 'Cement industry',
            'A37' => 'Food industry',
            'A38' => 'Secondary New Shares',
            'A39' => 'Biopharmaceuticals',
            'A40' => 'Commercial department stores',
            'A41' => 'Petroleum industry',
            'A42' => 'Ceramic industry',
            'A43' => 'Foreign trade of materials',
            'A44' => 'Medical devices',
            'A45' => 'Instrumentation',
            'A46' => 'Printing and packaging',
            'A47' => 'Non ferrous metals',
            'A48' => 'Comprehensive industry',
            'A49' => 'Paper industry',
            'A50' => 'Glass industry',
            'A51' => 'Shipbuilding',
            'A52' => 'Media and entertainment',
            'A53' => 'Power industry',
            'A54' => 'Electrical industry',
            'A55' => 'Electronic devices',
            'A56' => 'Electronic Information',
            'A57' => 'real estate',
            'A58' => 'Power generation equipment',
            'A59' => 'Aircraft manufacturing',
            'A60' => 'Textile industry',
            'A61' => 'textile machinery',
            'A62' => 'Clothing and footwear',
            'A63' => 'Highway bridges',
            'A64' => 'Water supply and gas supply',
            'A65' => 'Steel industry',
            'A66' => 'Environmental protection industry',
            'A67' => 'Chemical industry',
            'A68' => 'Chemical fiber industry',
            'A69' => 'Home appliance industry',
            'A70' => 'Hotel tourism',
            'A71' => 'Furniture industry',
            'A72' => 'Financial industry',
            'A73' => 'Transportation',
            'A74' => 'Machinery industry',
            'A75' => 'Building materials',
            'A76' => 'development zones',
            'A77' => 'Brewing industry',
            'A78' => 'motorcycle',
            'A79' => 'Coal industry',
            'A80' => 'Agriculture, forestry, animal husbandry, and fishing',
            'A81' => 'Pesticides and fertilizers',
            'A82' => 'Automobile manufacturing',
            'A83' => 'Other industries',
            'A84' => 'Plastic products',
            'A85' => 'Cement industry',
            'A86' => 'Food industry',
            'A87' => 'Secondary New Shares',
            'A88' => 'Biopharmaceuticals',
            'A89' => 'Commercial department stores',
            'A90' => 'Petroleum industry',
            'A91' => 'Ceramic industry',
            'A92' => 'Foreign trade of materials',
            'A93' => 'Medical devices',
            'A94' => 'Instrumentation',
            'A95' => 'Printing and packaging',
            'A96' => 'Non ferrous metals',
            'A97' => 'Comprehensive industry',
            'A98' => 'Paper industry',
        ];
        $vi  = [
            'A1'  => 'Ngành công nghiệp thủy tinh',
            'A2'  => 'Tàu sản xuất',
            'A3'  => 'Truyền thông giải trí',
            'A4'  => 'Ngành công nghiệp điện',
            'A5'  => 'Ngành công nghiệp thiết bị điện',
            'A6'  => 'Các thiết bị điện tử',
            'A7'  => 'Thông tin điện tử',
            'A8'  => 'Bất động sản',
            'A9'  => 'Thiết bị phát điện',
            'A10' => 'Sản xuất máy bay',
            'A11' => 'Ngành công nghiệp dệt may',
            'A12' => 'Máy móc dệt may',
            'A13' => 'Trang phục điều đây',
            'A14' => 'Cầu cao tốc',
            'A15' => 'Cung cấp nước và khí',
            'A16' => 'Ngành công nghiệp thép',
            'A17' => 'Ngành công nghiệp môi trường',
            'A18' => 'HuaGong ngành công nghiệp',
            'A19' => 'Bộ ngành công nghiệp vải polyester với vài',
            'A20' => 'Ngành công nghiệp ở nhà',
            'A21' => 'Du lịch khách sạn',
            'A22' => 'Ngành công nghiệp đồ nội thất',
            'A23' => 'Ngành công nghiệp tài chính',
            'A24' => 'Giao thông vận chuyển',
            'A25' => 'Ngành công nghiệp máy móc',
            'A26' => 'Kiến trúc những khối gạch xây dựng',
            'A27' => 'KaiFa District',
            'A28' => 'Ánh trăng ngành công nghiệp',
            'A29' => 'Xe máy',
            'A30' => 'Ngành công nghiệp than đá',
            'A31' => 'NongLin MuYu',
            'A32' => 'Phải là thuốc trừ sâu phân bón',
            'A33' => 'Sản xuất xe hơi',
            'A34' => 'Các ngành công nghiệp khác',
            'A35' => 'nhựa',
            'A36' => 'Ngành công nghiệp xi măng',
            'A37' => 'Ngành công nghiệp thực phẩm',
            'A38' => 'Lần XinGu',
            'A39' => 'Dược phẩm sinh học',
            'A40' => 'Cửa hàng kinh doanh',
            'A41' => 'Ngành công nghiệp dầu mỏ',
            'A42' => 'Ngành công nghiệp làm gốm',
            'A43' => '2kg WaiMao',
            'A44' => 'Dụng cụ y tế',
            'A45' => 'Dụng cụ đo',
            'A46' => 'In đóng gói',
            'A47' => 'Người da màu Kim loại',
            'A48' => 'Ngành công nghiệp tổng hợp',
            'A49' => 'ZaoZhi ngành công nghiệp',
            'A50' => 'Ngành công nghiệp thủy tinh',
            'A51' => 'Con tàu tạo ra',
            'A52' => 'Truyền thông giải trí',
            'A53' => 'Ngành công nghiệp điện',
            'A54' => 'Ngành công nghiệp điện',
            'A55' => 'Lục tìm mấy thứ đồ điện tử',
            'A56' => 'Thông tin điện tử',
            'A57' => 'Bất động sản',
            'A58' => 'Thiết bị năng lượng',
            'A59' => 'Tạo ra máy bay',
            'A60' => 'Ngành công nghiệp may mặc',
            'A61' => 'Những sợi cơ khí',
            'A62' => 'Trang phục điều đây',
            'A63' => 'Đường cao tốc chiếc cầu nối',
            'A64' => 'Nước nghe',
            'A65' => 'Ngành công nghiệp thép',
            'A66' => 'Ngành công nghiệp môi trường',
            'A67' => 'HuaGong ngành công nghiệp',
            'A68' => 'Bộ ngành công nghiệp vải polyester với vài',
            'A69' => 'Ngành công nghiệp ở nhà',
            'A70' => 'Du lịch khách sạn',
            'A71' => 'Ngành công nghiệp đồ nội thất',
            'A72' => 'Ngành công nghiệp tài chính',
            'A73' => 'Giao thông vận chuyển',
            'A74' => 'Ngành công nghiệp máy móc',
            'A75' => 'Kiến trúc những khối gạch xây dựng',
            'A76' => 'KaiFa District',
            'A77' => 'Ánh trăng ngành công nghiệp',
            'A78' => 'Xe máy',
            'A79' => 'Ngành công nghiệp than đá',
            'A80' => 'NongLin MuYu',
            'A81' => 'Phải là thuốc trừ sâu phân bón',
            'A82' => 'Sản xuất xe hơi',
            'A83' => 'Các ngành công nghiệp khác',
            'A84' => 'nhựa',
            'A85' => 'Ngành công nghiệp xi măng',
            'A86' => 'Ngành công nghiệp thực phẩm',
            'A87' => 'Lần XinGu',
            'A88' => 'Dược phẩm sinh học',
            'A89' => 'Cửa hàng kinh doanh',
            'A90' => 'Ngành công nghiệp dầu mỏ',
            'A91' => 'Ngành công nghiệp làm gốm',
            'A92' => '2kg WaiMao',
            'A93' => 'Thiết bị y tế',
            'A94' => 'Dụng cụ đo',
            'A95' => 'In bao bì',
            'A96' => 'Kim loại non ferrous',
            'A97' => 'Tổng hợp ngành công nghiệp',
            'A98' => 'Ngành công nghiệp giấy',
        ];
        $key = array_search($cn_name, $cn);
        if ($key === false) {
            return $cn_name;
        } else {
            if ($target_lang == 'en-us') {
                return $en[$key];
            }
            if ($target_lang == 'vi-nt') {
                return $vi[$key];
            }
        }
    }
}