<?php


// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// [ 应用入口文件 ]
namespace think;
// [ PHP版本检查 ]
//header("Content-type: text/html; charset=utf-8");
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: *');
// header('Access-Control-Allow-Headers: *');
// 定义后台入口文件
define('ADMIN_FILE', 'admin.php');
// 定义应用目录
define('APP_PATH', '../application/');
define('EXTEND_PATH', '../extend/');
define('UPLOADS_PATH', './uploads/');
// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';
// 支持事先使用静态方法设置Request对象和Config对象
//定义
define('ROOT_PATH', '../');
define('RUNTIME_PATH', '../runtime/');
define('DS', DIRECTORY_SEPARATOR);
// 执行应用并响应
Container::get('app')->run()->send();
