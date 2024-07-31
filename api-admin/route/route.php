<?php
//Route::get('think', function () {
//    return 'hello,ThinkPHP5!';
//});
//Route::get('hello/:name', 'index/hello');
use think\facade\Route;

Route::domain('m', function () {
    Route::rule(':str', 'wap/index/index');
});
Route::rule('ipset', 'ipres/pasw/checkPassword');
return [

    '__pattern__'     => [
        'name' => '\w+',
    ],


//    'admin' => 'admin/index/index',
    'admin_user'      => 'user/publics/signin',
    'apicom/free'     => 'apicom/stock/free',
    'stock/free'      => 'stock/index/free',
    'stock/day'       => 'stock/index/day',
    'stock/week'      => 'stock/index/week',
    'stock/month'     => 'stock/index/month',
    'login'           => 'member/publics/login',
    'register'        => 'member/publics/register',
    'backpasswd'      => 'member/publics/backpasswd',
    'newpass'         => 'member/publics/newpass',
    'signout'         => 'member/publics/signout',
    'wap'             => 'wap/index/index',
    'androiddownload' => 'cms/page/mobileDownload',
    '[handle]'        => [
        //'apply' =>['stock/index/applysave', ['method'=>'post']],
        'apply' => ['stock/handle/applysave', ['method' => 'get']],
    ],
];
