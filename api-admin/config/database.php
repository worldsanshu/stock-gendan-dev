<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
return [
    // 数据库类型
    'type' => env('DB_TYPE', 'mysql'),
    // 服务器地址
    'database' => env('DB_NAME', 'gentou1_0p0_cn'),
    
    'hostname' => env('DB_HOST', '127.0.0.1'),
    'username' => env('DB_USER', 'root'),
    'password' => env('DB_PASS', ''),
 
    // 端口
    'hostport' => env('DB_PORT', '3306'),
    // 连接dsn
    'dsn' => '',
    // 数据库连接参数
    'params' => [],
    // 数据库编码默认采用utf8
    'charset' => env('DB_CHARSET', 'utf8'),
    // 数据库表前缀
    'prefix' => env('DB_PREFIX', ''),
    // 数据库调试模式
    'debug' => true,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy' => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate' => false,
    // 读写分离后 主服务器数量
    'master_num' => 1,
    // 指定从服务器序号
    'slave_no' => '',
    // 是否严格检查字段是否存在
    'fields_strict' => false,
    // 数据集返回类型
    'resultset_type' => 'array',
    // 自动写入时间戳字段
    'auto_timestamp' => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => false,
    // 是否需要进行SQL性能分析
    'sql_explain' => false,
];
