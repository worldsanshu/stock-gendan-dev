<?php
/**
 * 请求处理中间件
 * Author: weiyongqiang<hayixia606@163.com>
 * Date: 2018/9/27
 * Time: 11:02
 */

namespace app\common\middleware;

use think\Response;

class AllowCrossDomain
{
    public function handle($request, \Closure $next)
    {
        header("Content-type: text/html; charset=utf-8");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE');
        header('Access-Control-Max-Age: 1728000');
        if (strtoupper($request->method()) == "OPTIONS") {
            return json(array( 'data' => [], 'status' => 1, 'message' => 'OPTIONS' ));
        }
        return $next($request);
    }
}
