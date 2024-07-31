<?php

namespace app\market\model;

use think\Model;
use util\Logs;

class SocketData extends Model
{
    // 设置当前模型对应的完整数据表名称stock_market_bat
    protected $table = '__SOCKET_DATA__';
    protected $pk = 'id';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /*
    * 批量行情市场数据
    */
    public static function get_market_bat($code)
    {
        //Logs::log('market_bat_insert',['data'=>$post],'Market_bat');
        Logs::log('get_market_bat', ['code' => $code], 'SocketData');
        $data = self::where(['S' => ['in', $code]])->select();
        if (!$data) {
            $is_socket_data = getredis('is_socket_data');
            if ($is_socket_data != "no") {
                setredis('is_socket_data', 'no');
            }
            $is_pc = getredis('is_pc');
            if ($is_pc != "is") {
                setredis('is_pc', 'is');
            }
        } else {
            $is_socket_data = getredis('is_socket_data');
            if ($is_socket_data != "is") {
                setredis('is_socket_data', 'is');
            }
        }
        Logs::log('get_market_bat', ['data' => $data, 'code' => $code], 'SocketData');
        return $data;
    }

    /*
    * 获取单个行情市场数据
    */
    public function get_market($code)
    {
        $data = db('socket_data')->where(['S' => $code])->find();
//        if (!$data) {
//
//            if (config("is_socket_data") != "no") {
//                setconfig(array('is_socket_data'), array("no"));
//            }
//            if (config("is_sina_market") != "is") {
//                setconfig(array('is_sina_market'), array("is"));
//            }
//        } else {
//            if (config("is_socket_data") != "is") {
//                setconfig(array('is_socket_data'), array("is"));
//            }
//        }
        Logs::log('get_market', ['data' => $data, 'code' => $code], 'SocketData');
        return $data;
    }
}