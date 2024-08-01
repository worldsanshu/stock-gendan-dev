<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Env;

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------
return [
    'app_debug'              => Env::get('APP_DEBUG', false),
    // +----------------------------------------------------------------------
    // | 开版配置
    // +----------------------------------------------------------------------
    #平台上架开始时间
    'Added_time'             => Env::get('ADDED_TIME', date('Y-m-d 00:00:00')),
    #运行访问后台的域名
    #@1总站 @2代理站
//     'web_type'             => [
//         'www.a1.com'  => 1,  //本地正式
//         'gendan.topic.run'  => 1,//测试  正式的
//         'gentou1.0p0.cn'  => 1,//测试  正式的
//         'gentouagent.0p0.cn'  => 2,//测试 代理的
//         'gentou2.0p0.cn'  => 2,//测试  代理的
//     ],
    
    'root_domain' => explode(',', Env::get('domain.root_domain', '')),// #1总站
    'agent_domain' => explode(',', Env::get('domain.agent_domain','')), //#2代理站

    //分享页域名
    'share_domain'           => Env::get('domain.share_domain', ''),

    #更新股票数据url
    'f10_api_url'=> Env::get('F10.f10_api_url', 'https://quantapi.51ifind.com/api/v1/'),
    #股票行情数据
    'f10_refresh_token'=>'eyJzaWduX3RpbWUiOiIyMDIzLTA3LTI3IDE4OjQxOjQyIn0=.eyJ1aWQiOiI2ODIyMDc0NTEifQ==.A274176FD692173975337CC95F02297103DE07A4710113D8BADEEA96C212F771',
    #数据源域名
    'data_service_site'=>Env::get('F10.data_service_site', 'http://data.001ghsp.com'),

    #配置短信通道  AliSMS_cn、AliSMS_internationality、SMSbao、SMStx、wanli、zonglian、mandao
    'SMS_channel'=>Env::get('SMS.SMS_channel', 'zhonglian'),
    'SMS_channel_config'=>[
        //阿里云 国内短信配置
        'AliSMS_cn'               => [
            'internal_sign'   => Env::get('ALISMS.internal_sign', '阿里云短信测试'),//国内 签名
            'internal_key'    => Env::get('ALISMS.internal_key', 'LTAI5tEj3iqz2TPYoYBu7GzV'),//国内 key
            'internal_secret' => Env::get('ALISMS.internal_secret', 'hAkD0eHCe1YPxM2lbYTvRFZseTeRLT'),//国内 secret

            //阿里云短信模板
            'AliSMS_template_code' => [
                'captcha' => Env::get('AliSMS.captcha', 'SMS_154950909'),//验证码
            ],
        ],
        //阿里云 国际短信配置
        'AliSMS_internationality'               => [
            'globe_key'       => Env::get('ALISMSINT.globe_key', 'LTAI5tBsyVzkAvjxx3DWWb48'),//国际 key
            'globe_secret'    => Env::get('ALISMSINT.globe_secret', 'MjMhYYfEVjVVLS6tXUIW7s0czBMGwt'),//国际 secret
        ],
        //短信宝配置
        'SMSbao'               => [
            'user_name' => Env::get('SMSBAO.user_name', 'htht008'),
            'api_key'   => Env::get('SMSBAO.api_key', '4d608057acd14e62bdfa323bce48aeb6'),
        ],
        //腾讯云短信
        'SMStx'                => [
            'templateId' => Env::get('SMSTX.templateId', '2207265'),
            'smsSign'    => Env::get('SMSTX.smsSign', "佛山珲宇奇甄选"),
            'appid'      => Env::get('SMSTX.appid', 1327759085),
            'SecretId'     => Env::get('SMSTX.SecretId', 'AKID97qQvrZ2GwTlpeEZfIrFWhhW6RWP4Wkk'),
            'SecretKey'     => Env::get('SMSTX.SecretKey', 'pOiYDgw0tXw65rdN1RedNtHSQFqEOL5c'),
            'SDKAppID'     => Env::get('SMSTX.SDKAppID', '1400922744'),
        ],
        #万里短信
        'wanli'                => [
            //后台网址：https://cn-admin.wanliyun.com/
                //后台账号：zhongfa
            //后台密码：zhongfa
            'templateId' => Env::get('wanli.templateId', 180066),
            'apiAccessKey'    => Env::get('wanli.apiAccessKey', "a2987b6495a24e3587c131025f4164dc"),
            'apiSecretKey'     => Env::get('wanli.apiSecretKey', '892D3D077CEAC5E6BD6C1781F5AD2593')
        ],
        #众联短信
        'zhonglian'                => [
            //登录地址：http://112.74.47.72:66/
            // 账号：jsjs
            //  密码：12345678
            'account' => Env::get('zhonglian.account', 'jsjs'),
            'password'    => Env::get('zhonglian.password', "12345678"),
            'smsSign'     => Env::get('zhonglian.smsSign', '【君盛】')
        ],

        #漫道
        'mandao'                => [
            'templateId' => Env::get('mandao.templateId', 1938698),
            'smsSign'    => Env::get('mandao.smsSign', "琨易峰管理"),
            'appid'      => Env::get('mandao.appid', 1400857739),
            'appkey'     => Env::get('mandao.appkey', 'ddd1fee1bdc2cb4b03d0295f8ab1447c')
        ],
    ],

    #四像素实名认证  txcloud、alicloud
    'verified_channel'=>Env::get('verified.channel', 'alicloud'),
    'Verified_channel_config'=>[
        'txcloud'=>[
            #https://market.cloud.tencent.com/products/17675
            #腾讯云云市场分配的密钥Id
            'txcloud_Certification_secretId' => Env::get('txcloud.secretId', 'AKID3tbgSm1qX43MdGkZRqLB9bbWEV1JD4cBzL2w'),
            #腾讯云云市场分配的密钥Key
            'txcloud_Certification_secretKey' => Env::get('txcloud.secretKey', '5l1PIfS9eBJZf0A84BiQdecNBwnE9DJYzpq3a0s'),
        ],
        'alicloud'=>[
            #https://market.aliyun.com/apimarket/detail/cmapi028807?spm=5176.2020520132.101.6.780d7218bXUBgx#sku=yuncode22807000021
            #阿里云四像素配置
            'alicloud_Certification_AppKey' => Env::get('alicloud.AppKey', '204656986'),
            #阿里云云市场分配的密钥Key
            'alicloud_Certification_AppSecret' => Env::get('alicloud.AppSecret', 'xJj02DYlgi8cROQ9q1mciNI4GdpRLFLD'),
            #阿里云云市场分配的密钥AppCode
            'alicloud_Certification_AppCode' => Env::get('alicloud.AppCode', 'cb8b74b16af64d54b4ca3638d06530e0'),

        ],
    ],






    // +----------------------------------------------------------------------
    // | 系统相关设置
    // +----------------------------------------------------------------------
    // 应用调试模式

    'admin_base_layout'      => Env::get('app_path') . 'admin/view/layout.html',
    // 插件目录路径
    'plugin_path'            => Env::get('root_path') . 'plugins/',
    // 数据包目录路径
    'packet_path'            => Env::get('root_path') . 'packet/',
    // 文件上传路径
    'upload_path'            => Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads',
    // 文件上传临时目录
    'upload_temp_path'       => Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'temp/',
    // +----------------------------------------------------------------------
    // | 用户相关设置
    // +----------------------------------------------------------------------
    // 最大缓存用户数
    'user_max_cache'         => 1000,
    // 管理员用户ID
    'user_admin'             => 1,
    // 应用名称
    'app_name'               => '',
    // 应用地址
    'app_host'               => '',
    // 应用Trace
    'app_trace'              => false,
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => ['plugins' => Env::get('root_path') . 'plugins/'],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'Asia/Shanghai',
    // 是否开启多语言
    'lang_switch_on'         => true,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,
    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------
    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空模块名
    'empty_module'           => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法前缀
    'use_action_prefix'      => false,
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,
    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------
    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // HTTPS代理标识
    'https_agent_name'       => '',
    // IP代理获取标识
    'http_agent_ip'          => 'X-REAL-IP',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由延迟解析
    'url_lazy_route'         => false,
    // 是否强制使用路由
//    'url_route_must'         => true,
   'url_route_on' => true,
    // 合并路由规则
    'route_rule_merge'       => false,
    // 路由是否完全匹配
    'route_complete_match'   => false,
    // 使用注解路由
    'route_annotation'       => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],
    // 是否开启路由缓存
    'route_check_cache'      => false,
    // 路由缓存的Key自定义设置（闭包），默认为当前URL和请求类型的md5
    'route_check_cache_key'  => '',
    // 路由缓存类型及参数
    'route_cache_option'     => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => Env::get('app_path') . 'admin/view/dispatch_jump.tpl',
    'dispatch_error_tmpl'    => Env::get('app_path') . 'admin/view/dispatch_jump.tpl',
    // 异常页面的模板文件
    'exception_tmpl'         => Env::get('think_path') . 'tpl/think_exception.tpl',
    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle

    'exception_handle'     => '\\app\\common\\exception\\Http',
    //分页配置
    'paginate'             => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    /*每分钟*/
    /*每小时 某分*/
    /*每天 某时:某分*/
    /*每周-某天 某时:某分  0=周日*/
    /*每月-某天 某时:某分*/
    /*某月-某日 某时-某分*/
    /*某年-某月-某日 某时-某分*/
    'sys_crond_timer'      => array('*', '*:i', 'H:i', '@-w H:i', '*-d H:i', 'm-d H:i', 'Y-m-d H:i'),
    //短信模板
    'sms_template'         => [
        'stock_expend'                        => '用户#var#申请了扩大配资',
        'stock_addmoney'                      => '会员#var#申请了追加保证金,追加金额#amount#元',
        'stock_drawprofit'                    => '会员#var#申请了提取收益',
        'stock_renewal'                       => '会员#var#申请了配资延期',
        'stock_stop'                          => '会员#var#申请了终止配资',
        'stock_borrow_endedit'                => '会员#var#配资使用期限结束',
        'stock_handle_applySave'              => '会员#var#申请了配资',
        'stock_realname'                      => '会员#var#,申请了实名认证.',
        'stock_withdraw'                      => '会员#var#,申请了提现，金额#amount#元.',
        'stock_offline'                       => '会员#var#,申请了线下充值,金额#amount#元.',
        'register'                            => '#var#你好，你正在平台系统申请手机验证，验证码为',
        'stock_auditing'                      => '尊敬的会员#var#,您的订单号#order_id#的配资审核通过',
        'stock_realname_pass'                 => '尊敬的会员#var#,您的实名认证审核通过',
        'stock_realname_fail'                 => '尊敬的会员#var#,您的实名认证审核失败,请仔细填写',
        'stock_auditing_fail'                 => '尊敬的会员#var#,您的订单号#order_id#的配资审核失败',
        'stock_addfinancing_saveAddfinancing' => '尊敬的会员#var#,您的订单号#order_id#追加配资审核不通过,释放冻结资金',
        'stock_addfinancing_success'          => '尊敬的会员#var#,您的订单号#order_id#追加配资审核通过',
        'stock_addmoney_saveAddmoney'         => '尊敬的会员#var#,您的订单号#order_id#,追加保证金审核不通过，退回冻结金额',
        'stock_drawprofit_saveDrawprofit'     => '尊敬的会员#var#,您的订单号#order_id#申请提取盈利审核未通过',
        'stock_renewal_saveRenewal'           => '尊敬的会员#var#,您的订单号#order_id#扩大续期审核未通过，返回冻结服务费',
        'stock_offline_auditing_success'      => '尊敬的会员#var#,线下充值金额#amount#元,审核通过.',
        'stock_offline_auditing_fail'         => '尊敬的会员#var#,线下充值金额#amount#元,审核失败.',
        'stock_withdraw_auditing_success'     => '尊敬的会员#var#,提现金额#amount#元,审核通过.',
        'stock_withdraw_auditing_fail'        => '尊敬的会员#var#,提现金额#amount#元,审核失败.',
        'stock_loss_warn'                     => '尊敬的会员#var#,您的订单号#order_id#的配资的操盘资金低于预警线',
        'stock_loss_close'                    => '尊敬的会员#var#,您的订单号#order_id#的配资的操盘资金低于平仓线，已自动平仓',
    ],
    //支付
    'alipay'               => [
        //应用ID,您的APPID。
        'app_id'               => "2019041663951167",
        //商户私钥, 请把生成的私钥文件中字符串拷贝在此
        'merchant_private_key' => "MIIEowIBAAKCAQEAoKN6QppJXZj1ZSAwES0l3sRU0zLxFa9ocv/gpFpdws/HenpclUHrLBjpjk3T+9QGZ9KoY9wH0y66BYLTLRoGeoe9mPljz69XXtDeIvKHY56QA8nOfhfy18qynpIJhSaFGqNLTXsy0vYnVBNanb4jYTcdbS04PwnZfOBTWKuZug//FwZ2ckvS1NZ6iElpB/NJRUHdWCuyK8zyFQS7XzeDiHdhYaxxtzzs9hPWF1orcQzQQ3UamvJ4Saq8ssTW2xFttK17LVYdHyAVvCXsdVMKq8JY1SbwJ01S8GOA/mcuqEJyMkRtrDNk25vCcWvNHBkbc28DhlBy0rfv7+vqavp3tQIDAQABAoIBAQCNBHYDmeXl4K0sBUGtEnE4Sioc7Y9udfm/WpkeLqY4qqZ3AovcC24j3ArL/c6mN88XvXpNj47e+RocVGiGI16kmhz6+ZOBcm/EsJ8RFs97yGqsqLA5SVTJ0wtS4OiylsoqREeK0Sggwi5sREE8UPpGEVgny5ag4Eios1T3dk0RZC022x/R0NoY81kZOtANlclSLW6WwmSYCAdUgIuNg/dykcuzeQq8YNVJ1ZIlHFmrVVKzvylT/C+B9UQPKYdTDlxFc3IlV3NZU3eH7jutU/GOmKYjwu7UD+pnXhGATN2dOwPnHMOK11az1TxiS5S8OJs+8XO7QIrmuAVhiXj3dZ2BAoGBANNMglfZGAuTN+W8Dxo44P2GiNCTumjbmgcGrvv/Rv9O6HOmiZDLgGNL22OcnsCnOVS9z1NahNMIlkMFeHSUStFpGuCQX7Qyu6k5vsuiLk6xyNJkSL6GZYen1D5U7xd0lV6tW+Ac11pKZN4mbdLSmoMTd7eP8ROk3X6pBfJ45YHZAoGBAMKfUSHDL8YlEFL+OZcWhoJQ9pg84fATwF42OPwGHHUaNYhzQuyk2IcgKQ0jk/+NVR6txoqGp9T/s5r1cpY6gUT5pWa55XHNMj7Jdy852B2OUVvh1QQV/dFozxU/KgjPpOI+HqG1wapeU/TLZInje9IrZy+R9+XZGanRF91+E189AoGAbivXTmywX+Cr5YovqbF8hlQuY+qS6HeQERfIBzasUxRWmW59DdU9pQh5ZK/WCodmmiu5vTFWTh6NEnb4vnhPpm6SCALfE7rcJDkqpUxzrq5+Ek0drNGwgJHeVX29iFJOS6ZCYFFJbBecDkSlQKZ0z+FHq5tOzLpKju8cXbfxmskCgYAuKEi+oCMZdDrd5AtWiPIXwAnr6yyOmrCZ53Q2WWCwaSnfFa7+ns33pin2QsTb6/IPF5mpyuvM+WPoUWP+uxtjd23B4j7HcaoD+BX7LlXSFKm5B+NUV77dxmSunk9izZRGUANFyOyIclypBv/uL/Ry0PZk8tJ/QbDzdHKbhkYj5QKBgCNFJIiQPadg449y5Cz5wEZDp/pvnEqa3kQkpz8jQLUa//fz0t4b6QmN2TzL9ynrUvymWDD4Vwkqq7VlKixq+BtMlFPspNDe5YQLFbhAe4EfGhbG8xQVDMMEvpd7PPWbqDyUR8PK1pxZMkg6lXsbq6A7swk5LwziJoOHlcgbKK7e",
        //异步通知地址
        'notify_url'           => "http://" . $_SERVER['HTTP_HOST'] . "/index/notify/alipay.html",
        //同步跳转
        'return_url'           => "http://" . $_SERVER['HTTP_HOST'] . "/wap/member/index",
        //编码格式
        'charset'              => "UTF-8",
        //签名方式
        'sign_type'            => "RSA2",
        //支付宝网关
        'gatewayUrl'           => "https://openapi.alipay.com/gateway.do",
        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key'    => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoKN6QppJXZj1ZSAwES0l3sRU0zLxFa9ocv/gpFpdws/HenpclUHrLBjpjk3T+9QGZ9KoY9wH0y66BYLTLRoGeoe9mPljz69XXtDeIvKHY56QA8nOfhfy18qynpIJhSaFGqNLTXsy0vYnVBNanb4jYTcdbS04PwnZfOBTWKuZug//FwZ2ckvS1NZ6iElpB/NJRUHdWCuyK8zyFQS7XzeDiHdhYaxxtzzs9hPWF1orcQzQQ3UamvJ4Saq8ssTW2xFttK17LVYdHyAVvCXsdVMKq8JY1SbwJ01S8GOA/mcuqEJyMkRtrDNk25vCcWvNHBkbc28DhlBy0rfv7+vqavp3tQIDAQAB",
    ],
    'Email'                => [
        'host'        => Env::get('Email.host','smtp.gmail.com'),
        'charSet'     => 'UTF-8',
        'userName'    => Env::get('Email.senderEmail','wordsanshu@gmail.com'),
        'password'    => Env::get('Email.password','ujqigyietcgqexvd'),
        'port'        => Env::get('Email.host','465'),
        'senderNmae'  => Env::get('Email.senderNmae','中金'),//发送人名称
        'senderEmail' => Env::get('Email.senderEmail','wordsanshu@gmail.com'),''//发送人邮箱
    ],

    //精确获取调试记录
    'Logs_status'          => 1,//0关闭，1开启
    'Logs_e_log_status'    => 1,//0关闭，1开启




];
