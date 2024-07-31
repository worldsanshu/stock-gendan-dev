<?php
header("Content-type: text/html; charset=utf-8");
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', TRUE);
// 定义应用目录
define('APP_PATH', '../application/');
define('EXTEND_PATH', '../extend/');
//定义
define('RUNTIME_PATH', '../runtime/');
// 引入ThinkPHP入口文件
require '../thinkphp/start.php';
?>