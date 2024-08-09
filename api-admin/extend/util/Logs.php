<?php

namespace util;

use think\Db;

class Logs
{
    /*
     * 程序调试数据记录器
     * */
    public static function log($str = '', $data = '', $dir = '', $start = 1)
    {
        $status = config('Logs_status');
        if ($status && $start) {
            $dir = trim($dir);
            if ($dir) {
                $dir = RUNTIME_PATH . "logs/" . $dir . '';
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $filename = $dir . '/' . date("Ymd") . $str . ".txt";
            } else {
                $filename = $dir . "/" . date("Ymd") . $str . ".txt";
            }
            $logs = array('logs-time' => date('Y-m-d H:i:s', time()), 'data' => $data);
            file_put_contents($filename, "\r\n" . var_export($logs, true) . "\r\n", FILE_APPEND);
        }
    }

    /*
     * 错误信息记录器
     */
    public static function e_log($str = '', $data = '', $dir = '')
    {
        $Logs_e_log_status = config('Logs_e_log_status');
        if ($Logs_e_log_status == 1) {
            $dir = trim($dir);
            if ($dir) {
                $dir = RUNTIME_PATH . "logs/" . $dir . '';
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $filename = $dir . '/' . date("Ymd") . $str . ".txt";
            } else {
                $filename = $dir . "/" . date("Ymd") . $str . ".txt";
            }
            $logs = array('logs-time' => date('Y-m-d H:i:s', time()), 'data' => $data);
            file_put_contents($filename, "\r\n" . var_export($logs, true) . "\r\n", FILE_APPEND);
        }
    }

    /*
     * 记录到数据表stock_error_log
     *
     */
    public static function add_error($post_data)
    {
        //监听并记录错误
        if ($post_data && is_array($post_data)) {
            $post_data['ctime'] = time();
            Db::name('stock_error_log')->insert($post_data);
            //转到其它接口
        }
    }
}
