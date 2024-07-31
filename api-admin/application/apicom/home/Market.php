<?php

namespace app\apicom\home;

use app\common\controller\Common;
use think\facade\Cache;
use think\facade\Lang;

class Market extends Common
{
    function index()
    {
        return '';
    }

    //美股指数
    function gbIndex()
    {
        $json = '[{"code":"dji","name":"道琼斯","lastprice":"33833.6094","rate":"0.50","diff":2.73},{"code":"ixic","name":"纳斯达克","lastprice":"13238.5239","rate":"1.02","diff":38.97},{"code":"inx","name":"标普500","lastprice":"4293.9302","rate":"0.62","diff":22.24}]';
        $result = json_decode($json, true);
        ajaxmsg('获取成功', 1, $result, true, ['msgCode' => 'L0014']);
        /////////////////////////////////////////////////////////////////
        $result = [];
        $data_key = 'gb_index';
        $expire_key = 'expire_gb_index';
        $data = Cache::get($data_key);
        $expire = Cache::get($expire_key);
        if ($expire) {
            $result = $data;
        } else {
            $web_data = web_gb_index('gb_dji,gb_ixic,gb_inx');
            if ($web_data) {
                $result = $web_data;
                Cache::set($data_key, $web_data);
                Cache::set($expire_key, '1', 60);
            } else {
                $result = $data;
            }
        }
        ajaxmsg('获取成功', 1, $result, true, ['msgCode' => 'L0014']);
    }

    //美股分时数据
    function usMinute()
    {
        $codes = [
          'us.IXIC' => '纳斯达克',
          'us.DJI' => '道琼斯',
          'us.INX' => '标普500'
        ];
        $result = [];
        foreach ($codes as $key => $name) {
            $name = translateStockName($name);
            $data_key = 'us_minute_' . $key;
            $expire_key = 'expire_us_minute_' . $key;
            $list = Cache::get($data_key);
            $expire = Cache::get($expire_key);
            if ($expire) {
                $result[] = ['name' => $name, 'list' => $list];
            } else {
                $web_data = web_us_minute($key);
                if ($web_data) {
                    $format_data = [];
                    foreach ($web_data as $txt) {
                        //txt => 1415 13194.54 2612119025
                        $arr = explode(' ', trim($txt));
                        $format_data[] = [
                          'time' => substr($arr[0], 0, 2) . ':' . substr($arr[0], 2),
                          'price' => $arr[1],
                          'volume' => $arr[2]
                        ];
                    }
                    $result[] = ['name' => $name, 'list' => $format_data];
                    Cache::set($data_key, $format_data);
                    Cache::set($expire_key, '1', 60);
                } else {
                    $result[] = ['name' => $name, 'list' => []];
                }
            }
        }
        ajaxmsg('获取成功', 1, $result, true, ['msgCode' => 'L0014']);
    }

    function usCategory()
    {
        $cat = request()->param('cat', 'FIN');
        $result = [];
        $data_key = 'us_type_' . $cat;
        $expire_key = 'expire_us_type_' . $cat;
        $data = Cache::get($data_key);
        $expire = Cache::get($expire_key);
        if ($expire) {
            $result = $data;
        } else {
            if ($cat == 'AEE') {
                $web_data = web_us_AAE();
            } else {
                $web_data = web_us_type($cat);
            }
            if ($web_data) {
                $result = $web_data;
                Cache::set($data_key, $web_data);
                Cache::set($expire_key, '1', 60);
            } else {
                $result = $data;
            }
        }
        ajaxmsg('获取成功', 1, $result, true, ['msgCode' => 'L0014']);
    }

    /*获取QQ股票指数
     * sh000001:上证指数
     * sz399001:深证成指
     * sz399006:创业板指
     * r_hkHSI:恒生指数
     * usIXIC:纳斯达克
     *
     *
     *
     */
    function stockIndex()
    {
        $result = [];
        $codes = [
          'home' => 'sh000001,sz399001,sz399006,r_hkHSI,usIXIC',//首页
          'gbIndex' => 'usDJI,usIXIC,usINX',//行情-美股
          'hkIndex' => 'r_hkHSI,r_hkHSCEI,r_hkHSCCI',//行情-美股
        ];
        $from = input('from', 'home');
        $code = $codes[$from];
        if ($code) {
            $code_list = explode(',', $code);
            $need_web_data = [];//缓存过期需重新爬取
            $data_prefix = 'stockIndex_';
            $expire_prefix = 'expire_stockIndex_';
            foreach ($code_list as $sub_code) {
                $data_key = $data_prefix . $sub_code;
                $expire_key = $expire_prefix . $sub_code;
                // **1  从缓存获取
                $data = Cache::get($data_key);
                $expire = Cache::get($expire_key);// 用于判断短期缓存是否过期
                if ($expire)
                    $result[] = $data;
                else
                    $need_web_data[] = $sub_code;
            }
            if ($need_web_data) {
                $code_str = implode(',', $need_web_data);
                $web_data = qqStockIndex($code_str);
                foreach ($web_data as $stock) {
                    $data_key = $data_prefix . $stock['code'];
                    $expire_key = $expire_prefix . $stock['code'];
                    Cache::set($data_key, $stock);
                    Cache::set($data_key, 1, 10);
                    $result[] = $stock;
                }
            }
        }
        if (Lang::range() != 'zh-cn') {
            foreach ($result as $k => $v) {
                $result[$k]['name'] = translateStockName($v['name']);
            }
        }
        ajaxmsg('获取成功', 1, $result, true, ['msgCode' => 'L0014']);
    }

    /**股票分时数据---腾讯
     *Desc:
     * @param $type
     * @param $code
     *return:
     *author:oszpac
     *date:2023-06-13
     */
    function stockMinute($type, $code)
    {
        $result = [];
        $code = input('code');
        $type = input('type');
        $data_key = 'stockMinute_' . $code;
        $expire_key = 'expire_stockMinute_' . $code;
        if ($code) {
            $data = Cache::get($data_key);
            $expire = Cache::get($expire_key);// 用于判断短期缓存是否过期
            if ($expire)
                $result = $data;
            else {
                $web_data = qqStockMinute($type, $code);
                if ($web_data) {
                    Cache::set($data_key, $web_data);
                    Cache::set($expire_key, 1, 60);
                    $result = $web_data;
                } else
                    $result = $data;
            }
        }
        ajaxmsg('获取成功', 1, $result, true, ['msgCode' => 'L0014']);
    }
}