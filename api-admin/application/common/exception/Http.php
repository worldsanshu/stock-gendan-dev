<?php

namespace app\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\facade\Log;

class Http extends Handle
{
    public function render(Exception $e)
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            return json($e->getError(), 422);
        }
        // 请求异常
        if ($e instanceof HttpException && request()->isAjax()) {
            return response($e->getMessage(), $e->getStatusCode());
        }
        
        //额外记录错误
        if (!empty($e->getMessage())) {
            Log::notice('#url:'.request()->url()." \n #param:".json_encode(request()->param(),JSON_UNESCAPED_UNICODE)." \n #header:".json_encode(request()->header(),JSON_UNESCAPED_UNICODE)." \n #err_msg:".'['.$e->getMessage()."] ".$e->getFile().'('.$e->getLine().')');
            Log::notice('err:'.$e->getTraceAsString());
        }
        
        if (!config("app_debug")) {
            die('{"status":0,"msg":"无数据/系统错误"}');
        }
        // 其他错误交给系统处理
        return parent::render($e);
    }
}