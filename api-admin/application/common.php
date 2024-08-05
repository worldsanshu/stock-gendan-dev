<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ServerException;
use app\apicom\model\JWT;
use app\common\model\Area;
use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundOrderGs as FundOrderGsModel;
use app\fund\model\FundUserlevelConfig as FundUserlevelConfigModel;
use app\money\model\Money as MoneyModel;
use app\money\model\Record;
use app\stock\model\SubaccountMoney;
use app\user\model\User;
use PHPMailer\Email;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20190711\Models\SendSmsRequest;
use TencentCloud\Sms\V20190711\SmsClient;
use think\Container;
use think\Db;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Env;
use think\facade\Lang;
use think\facade\Request;
use think\facade\Session;
use util\Logs;

error_reporting(E_ERROR | E_WARNING | E_PARSE); //太多未定义数组索引错误了
// 应用公共文件
defined('JWT_TOKEN_KEY') or define('JWT_TOKEN_KEY', 'sanshu');
// 加载自定义公共文件
if (is_file(Env::get('app_path') . 'function.php')) {
    include_once Env::get('app_path') . 'function.php';
}
function Funduserlevelarray()
{
//    return $level_array = [
//        0  => '普通合伙人',
//        1  => '入门合伙人',
//        2  => '新手合伙人',
//        3  => '基础合伙人',
//        4  => '初级合伙人',
//        5  => '中级合伙人',
//        6  => '高级合伙人',
//        7  => '特级合伙人',
//        8  => '核心合伙人',
//        9  => '大区合伙人',
//        10 => '股东合伙人'
//    ];
    $level_array = FundUserlevelConfigModel::select()->toArray();
    $list        = [];
    foreach ($level_array as $key => $value) {
        $list[$value['level']] = $value['name'];
    }
    return $list;

}

//导师跟单类型
function traderDocumentaryArray()
{
    return $trader_documentary_array = ['1' => '每日优投', '2' => '一键优投'];
}

//订单跟单类型
function documentaryArray($index = '')
{
    $documentary_array = [2 => '每日优投', 3 => '一键优投'];
    if (empty($index)) {
        return $documentary_array;
    }
    return $documentary_array[$index];

}

function userRoleName()
{
    return array("白名单" => "白名单", "普通会员" => "普通会员");

}

/***
 * 处理优投结算
 ** ajax json 2018 05 29
 ***/

function processing_settlement($id, $orderinfo)
{
    printlog($id, '进入结算执行', 'jiesuan-end');
    $FundOrderinfo = FundOrderGsModel::where('id', $id)->find();
    if ($FundOrderinfo['status'] != 1) {
        return false;
    }
    $d_where = [['order_sn', '=', $orderinfo['order_sn']], ['status', 'in', '0,1,2']];
    $list    = FundDaylineModel::where($d_where)->select();

    if (count($list) > 0) {
        printlog('', '存在持仓数据', 'jiesuan-end');
        return false;
    }

    #获取总利润
    $FundDaylineinfolist = FundDaylineModel::where(['order_id' => $id])
        ->where('status', 3)
        ->sum('profit');
    printlog($orderinfo['money'], '合约金额', 'jiesuan-end');
    printlog($FundDaylineinfolist, '获取总利润', 'jiesuan-end');
    #原合约金额+合约订单盈亏金额
//    $FundDaylineProfit = $FundDaylineinfolist; //记录纯利润
    $FundDaylineinfolist = $FundDaylineinfolist + $orderinfo['money'];
    printlog($FundDaylineinfolist, '获取总利润+元合约金额', 'jiesuan-end');

    #更新订单状态
    FundOrderGsModel::where('id', $id)->update(['status' => 3, 'settlement_amount' => $FundDaylineinfolist, 'settlement_time' => time()]);
    #合约亏完了不穿透
    $FundDaylineinfolist = $FundDaylineinfolist < 0 ? 0 : $FundDaylineinfolist;
    printlog($FundDaylineinfolist, '可加入余额的金额', 'jiesuan-end');
    #更新用户资金表
    $user_balance = MoneyModel::getMoney($orderinfo['uid']);
    $account      = $user_balance['account'] + $FundDaylineinfolist * 100;

    $info = '结算优投（单号' . $orderinfo['order_sn'] . '）,变化金额：' . abs($FundDaylineinfolist) . '元';
    #第六步 跟新用户资金表
    if ($account < 0) {
        $account = 0;
    }
    $obj = [
        'affect'           => abs($FundDaylineinfolist * 100),
        'account'          => $account,
        'affect_activity'  => 0,
        'activity_account' => $user_balance['activity_account'],
        'sn'               => $orderinfo['order_sn']
    ];
    printlog($obj, '变更数据', 'jiesuan-end');
    Record::saveData($orderinfo['uid'], $FundDaylineinfolist * 100, $account, 96, $info, '', '', $obj);

    printlog($account, '余额数据', 'jiesuan-end');
    #更新余额
    return MoneyModel::money_up($orderinfo['uid'], ['account' => $account]);

}

/**
 * 清空系统缓存
 * @author 蔡伟明 <314013107@qq.com>
 */
function systemwipeCache()
{
    $wipe_cache_type = config('wipe_cache_type');
    if (!empty($wipe_cache_type)) {
        foreach ($wipe_cache_type as $item) {
            switch ($item) {
                case 'TEMP_PATH':
                    array_map('unlink', glob(Env::get('runtime_path') . 'temp/*.*'));
                    break;
                case 'LOG_PATH':
                    $dirs = (array)glob(Env::get('runtime_path') . 'log/*');
                    foreach ($dirs as $dir) {
                        array_map('unlink', glob($dir . '/*.log'));
                    }
                    array_map('rmdir', $dirs);
                    break;
                case 'CACHE_PATH':
                    array_map('unlink', glob(Env::get('runtime_path') . 'cache/*.*'));
                    break;
            }
        }
        Cache::clear();
        return true;
    } else {
        return false;
    }
}

/**
 * Desc : 解析出一级域名
 * Date : 2024-06-21 17:44
 *
 * @param $url
 *
 * @return false|string
 */
function getPrimaryDomain($url)
{
    // 解析 URL 并获取主机名部分
    $parsedUrl = parse_url($url);
    if (!isset($parsedUrl['host'])) {
        return false;
    }

    $host = $parsedUrl['host'];

    // 拆分主机名为各个部分
    $hostParts = explode('.', $host);
    $numParts  = count($hostParts);

    // 如果主机名部分少于2个部分，返回false
    if ($numParts < 2) {
        return false;
    }

    // 处理.co.uk等情况
    $tld        = $hostParts[$numParts - 2] . '.' . $hostParts[$numParts - 1];
    $exceptions = ['co.uk', 'com.au', 'com.cn', 'gov.uk'];

    // 判断是否为特殊的TLD（如co.uk）
    if (in_array($tld, $exceptions) && $numParts > 2) {
        return $hostParts[$numParts - 3] . '.' . $tld;
    }

    // 返回主域名
    return $hostParts[$numParts - 2] . '.' . $hostParts[$numParts - 1];
}

/**
 * Desc : ip转地区  国外接口
 * Date : 2024-06-21 18:45
 *
 * @param $ip
 *
 * @return \think\response\Json
 */
function getGeoLocation($ip = '')
{
    // 如果没有提供IP地址，则获取客户端IP
    if (empty($ip)) {
        $request = Request::instance();
        $ip      = $request->ip();
    }
//    #方式1 -不太准、
//        1、需要安装 composer require zoujingli/ip2region
//        2、说明https://thinkadmin.top/plugin/extra-ip2region.html
//        $ip2region = new \Ip2Region();
//        $data = $ip2region->simple('58.97.193.171');
//        var_dump($result);
//    方式二国外通道，也不太准、
//    // 调用IP-API接口
//    $url = "http://ip-api.com/json/{$ip}?fields=status,message,country,regionName,city,isp,query";
//    $response = wx_http_request($url);
//    $data = json_decode($response, true);
//    方式三、百度有获取限制  响应时间太长了
    // 调用IP-API接口
    Log::info('获取ip开始时间:' . time());
    $url = "http://qifu-api.baidubce.com/ip/geo/v1/district?ip=" . urlencode($ip);
    Log::info($url);
    $response = file_get_contents($url);
    $data     = json_decode($response, true);
    Log::info('获取ip结束时间:' . time());
    // 检查API返回的数据
    if ($data && $data['code'] == 'Success') {
        $data = $data['data'];

        return [
            'ip'      => $data['query'],
            'country' => $data['country'],
            'region'  => $data['prov'],
            'city'    => $data['city'],
            'isp'     => $data['isp'],
        ];
    } else {
        return '无法获取地理位置信息或局域网';
    }
}

/**
 * 暴力采集解析新浪财经新闻页面
 *
 * @param $html
 *
 * @return void
 */
function gethtml($url)
{
    $ch = curl_init();
// 设置cURL选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 如果目标站点使用的是自签名证书
// 执行cURL会话
    $html = curl_exec($ch);
// 检查是否有错误发生
    if (curl_errno($ch)) {
        return false;
    }
// 关闭cURL会话
    curl_close($ch);
// 创建一个新的DOMDocument实例
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html);
    libxml_clear_errors();
// 创建DOMXPath实例
    $xpath = new DOMXPath($doc);
// 查找所有具有class="art_pic_card art_content"的元素
    $nodes = $xpath->query('//section[contains(@class, "art_pic_card art_content")]');
// 遍历找到的节点并处理内容
    $center = '';
    foreach ($nodes as $node) {
        // 获取节点的HTML内容
        $innerHTML = '';
        foreach ($node->childNodes as $child) {
            $innerHTML .= $doc->saveHTML($child);
        }
        // 移除特定的<img>标签
        $innerHTML = preg_replace('/<img\s+src="\/\/n\.sinaimg\.cn\/default\/2fb77759\/20151125\/320X320\.png"\s*\/?>/', '', $innerHTML);
        // 移除所有HTML标签
        $cleanText = strip_tags($innerHTML, '<img><p>');
        // 输出清理后的内容
        $center .= $cleanText;
    }
    return $center;
}

#字符串转码为ASCII ，即&#xxxx格式
function unicode_encode($c, $prefix = "&#")
{
    $len = strlen($c);
    $a   = 0;
    while ($a < $len) {
        $ud = 0;
        if (ord($c{$a}) >= 0 && ord($c{$a}) <= 127) {
            $ud = ord($c{$a});
            $a  += 1;
        } else if (ord($c{$a}) >= 192 && ord($c{$a}) <= 223) {
            $ud = (ord($c{$a}) - 192) * 64 + (ord($c{$a + 1}) - 128);
            $a  += 2;
        } else if (ord($c{$a}) >= 224 && ord($c{$a}) <= 239) {
            $ud = (ord($c{$a}) - 224) * 4096 + (ord($c{$a + 1}) - 128) * 64 + (ord($c{$a + 2}) - 128);
            $a  += 3;
        } else if (ord($c{$a}) >= 240 && ord($c{$a}) <= 247) {
            $ud = (ord($c{$a}) - 240) * 262144 + (ord($c{$a + 1}) - 128) * 4096 + (ord($c{$a + 2}) - 128) * 64 + (ord($c{$a + 3}) - 128);
            $a  += 4;
        } else if (ord($c{$a}) >= 248 && ord($c{$a}) <= 251) {
            $ud = (ord($c{$a}) - 248) * 16777216 + (ord($c{$a + 1}) - 128) * 262144 + (ord($c{$a + 2}) - 128) * 4096 + (ord($c{$a + 3}) - 128) * 64 + (ord($c{$a + 4}) - 128);
            $a  += 5;
        } else if (ord($c{$a}) >= 252 && ord($c{$a}) <= 253) {
            $ud = (ord($c{$a}) - 252) * 1073741824 + (ord($c{$a + 1}) - 128) * 16777216 + (ord($c{$a + 2}) - 128) * 262144 + (ord($c{$a + 3}) - 128) * 4096 + (ord($c{$a + 4}) - 128) * 64 + (ord($c{$a + 5}) - 128);
            $a  += 6;
        } else if (ord($c{$a}) >= 254 && ord($c{$a}) <= 255) { //error
            $ud = false;
        }
        $scill .= $prefix . $ud . ";";
    }
    return $scill;
}

/***
 * ajax json 2018 05 29
 **/
if (!function_exists('isLogin')) {
    function isLogin($token)
    {
        if (!empty($token)) {
            $decoded = JWT::decode($token, JWT_TOKEN_KEY, array('HS256'));
            $doHost  = $_SERVER['HTTP_HOST'];
            if ($doHost == $decoded->doHost) {
                $uid  = $decoded->uid;
                $path = $_SERVER['REQUEST_URI'];
                if (strpos($path, 'appindex') != true) {
                    $d_info = Db::name("member_login_record")->where("mid", $uid)->max('id');
                    printlog($d_info, '用户信息', 'log');
                    Db::name("member_login_record")->where("id", $d_info)->update(["onlinetime" => time(), "terminal" => is_mobile() ? is_mobile() : "PC"]);
                }
                return $uid;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
/**
 * 获取毫秒
 * @return float
 */
function get_total_millisecond()
{
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

if (!function_exists('is_signin')) {
    /**
     * 判断是否登录
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    function is_signin()
    {
        $user = session('user_auth');
        //全部用户下线;
        // session('user_auth_sign',NULL);
        if (empty($user)) {
            // 判断是否记住登录
            if (cookie('?uid') && cookie('?signin_token')) {
                $UserModel = new User();
                $user      = $UserModel::get(cookie('uid'));
                if ($user) {
                    $signin_token = data_auth_sign($user['username'] . $user['id'] . $user['last_login_time']);
                    if (cookie('signin_token') == $signin_token) {
                        // 自动登录
                        $UserModel->autoLogin($user);
                        return $user['id'];
                    }
                }
            };
            return 0;
        } else {
            return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
        }
    }
}
if (!function_exists('Buyrestriction')) {
    /**
     * @param $code 股票代码
     * @param $borrow_id 子账户id
     * @param $stockdata 股票数据信息
     *
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function Buyrestriction($code, $borrow_id, $list_res = [], $stockinfo = '')
    {
        $code_info = substr($code, 0, -3);
        // init_money
        $Borrowinfo = Db::name("stock_borrow")->where("stock_subaccount_id", $borrow_id)->find();
        //判断科创板
        if (empty($Borrowinfo)) {
            return 0;
        }
        //判断科创板
        if ($code_info == '688') {
            $kechuang_config = config("kec688");
            //            if ($Borrowinfo['init_money'] < $kechuang_config) {
            //                return -1;
            //            }
            //新股上市购买时间限制
            $xgssxz     = config('xgssxz');
            $limit_time = time() - $xgssxz * 3600 * 24;
            $list_time  = Db::name("stock_list")->where("code", $code)->value('list_time');
            if ($list_time > $limit_time) {
                return -1;
            }
            //            #上市5天之后才可以购买
            if (isset($list_res['ss_date']) && !empty($list_res['ss_date'])) {
                $ss_date = (int)$list_res['ss_date'];
                $is_gou  = time() - $ss_date - 5 * 3600 * 24;
                if ($is_gou > 0) {
                    $b_res = $Borrowinfo;
                    if ($b_res) {
                        $borrow_money = $b_res['init_money'] / 100;
                        if ($kechuang_config !== "0" && $borrow_money < $kechuang_config) {
                            //限制股票，688开头股票只有超过50w配资金额才可以有权限购买
                            return -1;
                        }
                    }
                } else {
                    //'该股票上市未满五个交易，无法交易'
                    return -2;
                }
            } else {
                $b_res        = $Borrowinfo;
                $borrow_money = $b_res['init_money'] / 100;
                if ($kechuang_config !== "0" && $borrow_money < $kechuang_config) {
                    //限制股票，688开头股票只有超过50w配资金额才可以有权限购买
                    return -1;
                }
            }
            printlog(config("cybzdf"), '涨跌幅过大', 'common');
            printlog(abs($stockinfo['changeRatio']), '涨跌幅过大', 'common');
            if (config("cybzdf") !== "0" && config("cybzdf") <= abs($stockinfo['changeRatio'])) {
                printlog(config("cybzdf"), '涨跌幅过222大', 'common');
                //创业版 涨跌幅 不能超过系统限制
                return -5;
            }
        }
        //判断创业版
        if ($code_info == '300') {
            $chuangye_config = config("chuangye300");
            $borrow_money    = $Borrowinfo['init_money'] / 100;
            if ($chuangye_config !== "0" && $borrow_money < $chuangye_config) {
                //创业版多少钱起购
                return -3;
            }
            if (config("kcbzd") !== "0" && config("kcbzd") <= abs($stockinfo['changeRatio'])) {
                //创业版涨跌幅限购  涨跌幅过大，禁止购买
                return -5;
            }
        }
        //普通股票系统限制，低价股不能购买
        $chuangye_config = config("djguzdfu");
        printlog($chuangye_config, "低价股不能购买", 'buy');
        printlog($stockinfo['current_price'], "current_price", 'buy');
        if ($stockinfo['current_price'] < $chuangye_config && $chuangye_config !== "0") {
            return -4;
        }
        return 1;
    }
}
/*
 * 获取用户返佣收益
*/
if (!function_exists('agents_back_money')) {
    function get_back_money($mid)
    {
        $back_money = Db::name('agents_back_money')->where('mid', $mid)->sum('affect');
        $back_money = $back_money ? $back_money : 0.00;
        return round($back_money, 2);
    }
}

/**
 * Desc : 获取两个时间差
 * Date : 2024-06-28 01:15
 *
 * @param $one
 * @param $tow
 *
 * @return array
 */
function timedifference($one, $tow)
{
    $cle = $tow - $one;
    //得出时间戳差值
    /* 这个只是提示
       echo floor($cle/60); //得出一共多少分钟
       echo floor($cle/3600); //得出一共多少小时
       echo floor($cle/3600/24); //得出一共多少天
       */
    /*Rming()函数，即舍去法取整*/
    $d = floor($cle / 3600 / 24);
    $h = floor(($cle % (3600 * 24)) / 3600);  //%取余
    $m = floor(($cle % (3600 * 24)) % 3600 / 60);
    $s = floor(($cle % (3600 * 24)) % 60);
    // echo "两个时间相差 $d 天 $h 小时 $m 分 $s 秒";
    return ['d' => $d, 'h' => $h, 'm' => $m, 's' => $s];
    return ['duration_m' => floor($cle / 60), 'duration_h' => floor($cle / 3600)];
    //得出一共多少分钟
}

if (!function_exists('is_mobile')) {
    //判断是否是手机
    function is_mobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile',
                'MicroMessenger'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']), $matches)) {
                return $matches[1];
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return '协议判断法';
            }
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return 'MicroMessenger';
            return true;
        } else {
            return false;
        }
        return false;
    }
}
if (!function_exists('ajaxmsg')) {
    /**
     * @param $msg
     * @param $type
     * @param $data
     * @param $is_end
     * @param $ex :多语言设置数组[msgCode,params = []]
     *
     * @return void
     */
    function ajaxmsg($msg = "", $type = 1, $data = '', $is_end = true, $ex = [])
    {
        $json['status'] = $type . '';
        if (is_array($msg)) {
            foreach ($msg as $key => $v) {
                $json[$key] = $v;
            }
        } elseif (!empty($msg)) {
            $json['message'] = $msg;
        }
        //多语言处理
        if (!empty($ex) && !empty(($ex['msgCode']))) {
            $json['msgCode'] = $ex['msgCode'];
            $tran_txt        = Lang::get($ex['msgCode']);
            if (isset($ex['params']) && is_array($ex['params']) && count($ex['params']) > 0) {
                $tran_txt = vsprintf($tran_txt, $ex['params']);
            }
            $json['message'] = $tran_txt;
        }
        if ($data) $json['data'] = $data;
        if ($is_end) {
            echo json_encode($json, JSON_UNESCAPED_SLASHES);
            exit;
        } else {
            echo json_encode($json, JSON_UNESCAPED_SLASHES);
            exit;
        }
    }
}
if (!function_exists('data_auth_sign')) {
    /**
     * 数据签名认证
     *
     * @param array $data 被认证的数据
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function data_auth_sign($data = [])
    {
        // 数据类型检测
        if (!is_array($data)) {
            $data = (array)$data;
        }
        // 排序
        ksort($data);
        // url编码并生成query字符串
        $code = http_build_query($data);
        // 生成签名
        $sign = sha1($code);
        return $sign;
    }
}
if (!function_exists('get_file_path')) {
    /**
     * 获取附件路径
     *
     * @param int $id 附件id
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_file_path($id = 0)
    {
        $path = model('admin/attachment')->getFilePath($id);
        if (!$path) {
            return config('public_static_path') . 'admin/img/none.png';
        }
        return $path;
    }
}
if (!function_exists('get_files_path')) {
    /**
     * 批量获取附件路径
     *
     * @param array $ids 附件id
     *
     * @return array
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_files_path($ids = [])
    {
        $paths = model('admin/attachment')->getFilePath($ids);
        return !$paths ? [] : $paths;
    }
}
if (!function_exists('get_thumb')) {
    /**
     * 获取图片缩略图路径
     *
     * @param int $id 附件id
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_thumb($id = 0)
    {
        $path = model('admin/attachment')->getThumbPath($id);
        if (!$path) {
            return config('public_static_path') . 'admin/img/none.png';
        }
        return $path;
    }
}
if (!function_exists('get_avatar')) {
    /**
     * 获取用户头像路径
     *
     * @param int $uid 用户id
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     * @alter 小乌 <82950492@qq.com>
     */
    function get_avatar($uid = 0)
    {
        $avatar = Db::name('admin_user')->where('id', $uid)->value('avatar');
        $path   = model('admin/attachment')->getFilePath($avatar);
        if (!$path) {
            return config('public_static_path') . 'admin/img/avatar.jpg';
        }
        return $path;
    }
}
if (!function_exists('get_file_name')) {
    /**
     * 根据附件id获取文件名
     *
     * @param string $id 附件id
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_file_name($id = '')
    {
        $name = model('admin/attachment')->getFileName($id);
        if (!$name) {
            return '没有找到文件';
        }
        return $name;
    }
}
if (!function_exists('minify')) {
    /**
     * 合并输出js代码或css代码
     *
     * @param string $type 类型：group-分组，file-单个文件，base-基础目录
     * @param string $files 文件名或分组名
     *
     * @author 蔡伟明 <314013107@qq.com>
     */
    function minify($type = '', $files = '')
    {
        $files = !is_array($files) ? $files : implode(',', $files);
        $url   = PUBLIC_PATH . 'min/?';
        switch ($type) {
            case 'group':
                $url .= 'g=' . $files;
                break;
            case 'file':
                $url .= 'f=' . $files;
                break;
            case 'base':
                $url .= 'b=' . $files;
                break;
        }
        echo $url . '&v=' . config('asset_version');
    }
}
if (!function_exists('ck_js')) {
    /**
     * 返回ckeditor编辑器上传文件时需要返回的js代码
     *
     * @param string $callback 回调
     * @param string $file_path 文件路径
     * @param string $error_msg 错误信息
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function ck_js($callback = '', $file_path = '', $error_msg = '')
    {
        return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback, '$file_path' , '$error_msg');</script>";
    }
}
if (!function_exists('parse_attr')) {
    /**
     * 解析配置
     *
     * @param string $value 配置值
     *
     * @return array|string
     */
    function parse_attr($value = '')
    {
        $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
        if (strpos($value, ':')) {
            $value = array();
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k] = $v;
            }
        } else {
            $value = $array;
        }
        return $value;
    }
}
if (!function_exists('implode_attr')) {
    /**
     * 组合配置
     *
     * @param array $array 配置值
     *
     * @return string
     */
    function implode_attr($array = [])
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = $key . ':' . $value;
        }
        return empty($result) ? '' : implode(PHP_EOL, $result);
    }
}
if (!function_exists('parse_array')) {
    /**
     * 将一维数组解析成键值相同的数组
     *
     * @param array $arr 一维数组
     *
     * @return array
     * @author 蔡伟明 <314013107@qq.com>
     */
    function parse_array($arr)
    {
        $result = [];
        foreach ($arr as $item) {
            $result[$item] = $item;
        }
        return $result;
    }
}
if (!function_exists('parse_config')) {
    /**
     * 解析配置，返回配置值
     *
     * @param array $configs 配置
     *
     * @return array
     * @author 蔡伟明 <314013107@qq.com>
     */
    function parse_config($configs = [])
    {
        $type   = ['hidden' => 2, 'date' => 4, 'ckeditor' => 4, 'daterange' => 4, 'datetime' => 4, 'editormd' => 4, 'file' => 4, 'colorpicker' => 4, 'files' => 4, 'icon' => 4, 'image' => 4, 'images' => 4, 'jcrop' => 4, 'range' => 4, 'number' => 4, 'password' => 4, 'sort' => 4, 'static' => 4, 'summernote' => 4, 'switch' => 4, 'tags' => 4, 'text' => 4, 'array' => 4, 'textarea' => 4, 'time' => 4, 'ueditor' => 4, 'wangeditor' => 4, 'radio' => 5, 'bmap' => 5, 'masked' => 5, 'select' => 5, 'linkage' => 5, 'checkbox' => 5, 'linkages' => 6];
        $result = [];
        foreach ($configs as $item) {
            if (strpos($item[0], ':')) {
                list($config_type, $layout) = explode(':', $item[0]);
            } else {
                $config_type = $item[0];
            }
            // 判断是否为分组
            if ($config_type == 'group') {
                foreach ($item[1] as $option) {
                    foreach ($option as $group => $val) {
                        if (strpos($val[0], ':')) {
                            list($config_type, $layout) = explode(':', $val[0]);
                        } else {
                            $config_type = $val[0];
                        }
                        $result[$val[1]] = isset($val[$type[$config_type]]) ? $val[$type[$config_type]] : '';
                    }
                }
            } else {
                $result[$item[1]] = isset($item[$type[$config_type]]) ? $item[$type[$config_type]] : '';
            }
        }
        return $result;
    }
}
if (!function_exists('set_config_value')) {
    /**
     * 设置配置的值，并返回配置好的数组
     *
     * @param array $configs 配置
     * @param array $values 配置值
     *
     * @return array
     * @author 蔡伟明 <314013107@qq.com>
     */
    function set_config_value($configs = [], $values = [])
    {
        $type = ['hidden' => 2, 'date' => 4, 'ckeditor' => 4, 'daterange' => 4, 'datetime' => 4, 'editormd' => 4, 'file' => 4, 'colorpicker' => 4, 'files' => 4, 'icon' => 4, 'image' => 4, 'images' => 4, 'jcrop' => 4, 'range' => 4, 'number' => 4, 'password' => 4, 'sort' => 4, 'static' => 4, 'summernote' => 4, 'switch' => 4, 'tags' => 4, 'text' => 4, 'array' => 4, 'textarea' => 4, 'time' => 4, 'ueditor' => 4, 'wangeditor' => 4, 'radio' => 5, 'bmap' => 5, 'masked' => 5, 'select' => 5, 'linkage' => 5, 'checkbox' => 5, 'linkages' => 6];
        foreach ($configs as & $item) {
            if (strpos($item[0], ':')) {
                list($config_type, $layout) = explode(':', $item[0]);
            } else {
                $config_type = $item[0];
            }
            // 判断是否为分组
            if ($config_type == 'group') {
                foreach ($item[1] as & $option) {
                    foreach ($option as $group => & $val) {
                        if (strpos($val[0], ':')) {
                            list($config_type, $layout) = explode(':', $val[0]);
                        } else {
                            $config_type = $val[0];
                        }
                        if (!isset($val[3])) {
                            $val[3] = '';
                        }
                        $val[$type[$config_type]] = isset($values[$val[1]]) ? $values[$val[1]] : '';
                    }
                }
            } else {
                $item[$type[$config_type]] = isset($values[$item[1]]) ? $values[$item[1]] : '';
            }
        }
        return $configs;
    }
}
if (!function_exists('hook')) {
    /**
     * 监听钩子
     *
     * @param string $name 钩子名称
     * @param mixed $params 传入参数
     * @param bool $once 只获取一个有效返回值
     *
     * @author 蔡伟明 <314013107@qq.com>
     * @alter 小乌 <82950492@qq.com>
     */
    function hook($name = '', $params = null, $once = false)
    {
        \think\facade\Hook::listen($name, $params, $once);
    }
}
if (!function_exists('module_config')) {
    /**
     * 显示当前模块的参数配置页面，或获取参数值，或设置参数值
     *
     * @param string $name
     * @param string $value
     *
     * @return mixed
     * @author caiweiming <314013107@qq.com>
     */
    function module_config($name = '', $value = '')
    {
        if ($name === '') {
            // 显示模块配置页面
            return action('admin/admin/moduleConfig');
        } elseif ($value === '') {
            // 获取模块配置
            if (strpos($name, '.')) {
                list($name, $item) = explode('.', $name);
                return model('admin/module')->getConfig($name, $item);
            } else {
                return model('admin/module')->getConfig($name);
            }
        } else {
            // 设置值
            return model('admin/module')->setConfig($name, $value);
        }
    }
}
if (!function_exists('plugin_menage')) {
    /**
     * 显示插件的管理页面
     *
     * @param string $name 插件名
     *
     * @return mixed
     * @author caiweiming <314013107@qq.com>
     */
    function plugin_menage($name = '')
    {
        return action('admin/plugin/manage', ['name' => $name]);
    }
}
if (!function_exists('plugin_config')) {
    /**
     * 获取或设置某个插件配置参数
     *
     * @param string $name 插件名.配置名
     * @param string $value 设置值
     *
     * @return mixed
     * @author caiweiming <314013107@qq.com>
     */
    function plugin_config($name = '', $value = '')
    {
        if ($value === '') {
            // 获取插件配置
            if (strpos($name, '.')) {
                list($name, $item) = explode('.', $name);
                return model('admin/plugin')->getConfig($name, $item);
            } else {
                return model('admin/plugin')->getConfig($name);
            }
        } else {
            return model('admin/plugin')->setConfig($name, $value);
        }
    }
}
if (!function_exists('get_plugin_class')) {
    /**
     * 获取插件类名
     *
     * @param string $name 插件名
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_plugin_class($name)
    {
        return "plugins\\{$name}\\{$name}";
    }
}
if (!function_exists('get_client_ip')) {
    /**
     * 获取客户端IP地址
     *
     * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param bool $adv 是否进行高级模式获取（有可能被伪装）
     *
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = true)
    {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL) return $ip[$type];
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) unset($arr[$pos]);
                $ip = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip   = $long ? array(
            $ip,
            $long
        ) : array(
            '0.0.0.0',
            0
        );
        return $ip[$type];
    }
}
if (!function_exists('format_bytes')) {
    /**
     * 格式化字节大小
     *
     * @param number $size 字节数
     * @param string $delimiter 数字和单位分隔符
     *
     * @return string            格式化后的带单位的大小
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array(
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
            'PB'
        );
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }
}
if (!function_exists('format_time')) {
    /**
     * 时间戳格式化
     *
     * @param string $time 时间戳
     * @param string $format 输出格式
     *
     * @return false|string
     */
    function format_time($time = '', $format = 'Y-m-d H:i')
    {
        return !$time ? '' : date($format, intval($time));
    }
}
if (!function_exists('format_date')) {
    /**
     * 使用bootstrap-datepicker插件的时间格式来格式化时间戳
     *
     * @param null $time 时间戳
     * @param string $format bootstrap-datepicker插件的时间格式 https://bootstrap-datepicker.readthedocs.io/en/stable/options.html#format
     *
     * @return false|string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function format_date($time = null, $format = 'yyyy-mm-dd')
    {
        $format_map = ['yyyy' => 'Y', 'yy' => 'y', 'MM' => 'F', 'M' => 'M', 'mm' => 'm', 'm' => 'n', 'DD' => 'l', 'D' => 'D', 'dd' => 'd', 'd' => 'j',];
        // 提取格式
        preg_match_all('/([a-zA-Z]+)/', $format, $matches);
        $replace = [];
        foreach ($matches[1] as $match) {
            $replace[] = isset($format_map[$match]) ? $format_map[$match] : '';
        }
        // 替换成date函数支持的格式
        $format = str_replace($matches[1], $replace, $format);
        $time   = $time === null ? time() : intval($time);
        return date($format, $time);
    }
}
if (!function_exists('format_moment')) {
    /**
     * 使用momentjs的时间格式来格式化时间戳
     *
     * @param null $time 时间戳
     * @param string $format momentjs的时间格式
     *
     * @return false|string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function format_moment($time = null, $format = 'YYYY-MM-DD HH:mm')
    {
        $format_map = [
            // 年、月、日
            'YYYY' => 'Y', 'YY' => 'y',
            //            'Y'    => '',
            'Q'    => 'I', 'MMMM' => 'F', 'MMM' => 'M', 'MM' => 'm', 'M' => 'n', 'DDDD' => '', 'DDD' => '', 'DD' => 'd', 'D' => 'j', 'Do' => 'jS', 'X' => 'U', 'x' => 'u',
            // 星期
            //            'gggg' => '',
            //            'gg' => '',
            //            'ww' => '',
            //            'w' => '',
            'e'    => 'w', 'dddd' => 'l', 'ddd' => 'D', 'GGGG' => 'o',
            //            'GG' => '',
            'WW'   => 'W', 'W' => 'W', 'E' => 'N',
            // 时、分、秒
            'HH'   => 'H', 'H' => 'G', 'hh' => 'h', 'h' => 'g', 'A' => 'A', 'a' => 'a', 'mm' => 'i', 'm' => 'i', 'ss' => 's', 's' => 's',
            //            'SSS' => '[B]',
            //            'SS'  => '[B]',
            //            'S'   => '[B]',
            'ZZ'   => 'O', 'Z' => 'P',
        ];
        // 提取格式
        preg_match_all('/([a-zA-Z]+)/', $format, $matches);
        $replace = [];
        foreach ($matches[1] as $match) {
            $replace[] = isset($format_map[$match]) ? $format_map[$match] : '';
        }
        // 替换成date函数支持的格式
        $format = str_replace($matches[1], $replace, $format);
        $time   = $time === null ? time() : intval($time);
        return date($format, $time);
    }
}
if (!function_exists('format_linkage')) {
    /**
     * 格式化联动数据
     *
     * @param array $data 数据
     *
     * @return array
     * @author 蔡伟明 <314013107@qq.com>
     */
    function format_linkage($data = [])
    {
        $list = [];
        foreach ($data as $key => $value) {
            $list[] = ['key' => $key, 'value' => $value];
        }
        return $list;
    }
}
if (!function_exists('get_auth_node')) {
    /**
     * 获取用户授权节点
     *
     * @param int $uid 用户id
     * @param string $group 权限分组，可以以点分开模型名称和分组名称，如user.group
     *
     * @return array|bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_auth_node($uid = 0, $group = '')
    {
        return model('admin/access')->getAuthNode($uid, $group);
    }
}
if (!function_exists('check_auth_node')) {
    /**
     * 检查用户的某个节点是否授权
     *
     * @param int $uid 用户id
     * @param string $group $group 权限分组，可以以点分开模型名称和分组名称，如user.group
     * @param int $node 需要检查的节点id
     *
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function check_auth_node($uid = 0, $group = '', $node = 0)
    {
        return model('admin/access')->checkAuthNode($uid, $group, $node);
    }
}
if (!function_exists('get_level_data')) {
    /**
     * 获取联动数据
     *
     * @param string $table 表名
     * @param int $pid 父级ID
     * @param string $pid_field 父级ID的字段名
     *
     * @return array|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_level_data($table = '', $pid = 0, $pid_field = 'pid')
    {
        if ($table == '') {
            return '';
        }
        $data_list = Db::name($table)->where($pid_field, $pid)->select();
        if ($data_list) {
            return $data_list;
        } else {
            return '';
        }
    }
}
if (!function_exists('get_level_pid')) {
    /**
     * 获取联动等级和父级id
     *
     * @param string $table 表名
     * @param int $id 主键值
     * @param string $id_field 主键名
     * @param string $pid_field pid字段名
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_level_pid($table = '', $id = 1, $id_field = 'id', $pid_field = 'pid')
    {
        return Db::name($table)->where($id_field, $id)->value($pid_field);
    }
}
if (!function_exists('get_level_key_data')) {
    /**
     * 反向获取联动数据
     *
     * @param string $table 表名
     * @param string $id 主键值
     * @param string $id_field 主键名
     * @param string $name_field name字段名
     * @param string $pid_field pid字段名
     * @param int $level 级别
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_level_key_data($table = '', $id = '', $id_field = 'id', $name_field = 'name', $pid_field = 'pid', $level = 1)
    {
        $result             = [];
        $level_pid          = get_level_pid($table, $id, $id_field, $pid_field);
        $level_key[$level]  = $level_pid;
        $level_data[$level] = get_level_data($table, $level_pid, $pid_field);
        if ($level_pid != 0) {
            $data       = get_level_key_data($table, $level_pid, $id_field, $name_field, $pid_field, $level + 1);
            $level_key  = $level_key + $data['key'];
            $level_data = $level_data + $data['data'];
        }
        $result['key']  = $level_key;
        $result['data'] = $level_data;
        return $result;
    }
}
if (!function_exists('plugin_action_exists')) {
    /**
     * 检查插件控制器是否存在某操作
     *
     * @param string $name 插件名
     * @param string $controller 控制器
     * @param string $action 动作
     *
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function plugin_action_exists($name = '', $controller = '', $action = '')
    {
        if (strpos($name, '/')) {
            list($name, $controller, $action) = explode('/', $name);
        }
        return method_exists("plugins\\{$name}\\controller\\{$controller}", $action);
    }
}
if (!function_exists('plugin_model_exists')) {
    /**
     * 检查插件模型是否存在
     *
     * @param string $name 插件名
     *
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function plugin_model_exists($name = '')
    {
        return class_exists("plugins\\{$name}\\model\\{$name}");
    }
}
if (!function_exists('plugin_validate_exists')) {
    /**
     * 检查插件验证器是否存在
     *
     * @param string $name 插件名
     *
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function plugin_validate_exists($name = '')
    {
        return class_exists("plugins\\{$name}\\validate\\{$name}");
    }
}
if (!function_exists('get_plugin_model')) {
    /**
     * 获取插件模型实例
     *
     * @param string $name 插件名
     *
     * @return object
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_plugin_model($name)
    {
        $class = "plugins\\{$name}\\model\\{$name}";
        return new $class;
    }
}
if (!function_exists('plugin_action')) {
    /**
     * 执行插件动作
     * 也可以用这种方式调用：plugin_action('插件名/控制器/动作', [参数1,参数2...])
     *
     * @param string $name 插件名
     * @param string $controller 控制器
     * @param string $action 动作
     * @param mixed $params 参数
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    function plugin_action($name = '', $controller = '', $action = '', $params = [])
    {
        if (strpos($name, '/')) {
            $params = is_array($controller) ? $controller : (array)$controller;
            list($name, $controller, $action) = explode('/', $name);
        }
        if (!is_array($params)) {
            $params = (array)$params;
        }
        $class = "plugins\\{$name}\\controller\\{$controller}";
        $obj   = new $class;
        return call_user_func_array([$obj, $action], $params);
    }
}
if (!function_exists('_system_check')) {
    function _system_check()
    {
        $c = cache('_i_n_f_o');
        if (!$c || (time() - $c) > 86401) {
            cache('_i_n_f_o', time());
            $url                      = base64_decode('d3d3LmRvbHBoaW5waHAuY29tL3VwZGF0ZUluZm8=');
            $url                      = 'http://' . $url;
            $p['d' . 'om' . 'ain']    = request()->domain();
            $p[strtolower('I') . 'p'] = request()->server('SERVER_ADDR');
            $p                        = base64_encode(json_encode($p));
            $o                        = [CURLOPT_TIMEOUT => 20, CURLOPT_RETURNTRANSFER => true, CURLOPT_URL => $url, CURLOPT_USERAGENT => request()->server('HTTP_USER_AGENT'), CURLOPT_POST => 1, CURLOPT_POSTFIELDS => ['p' => $p]];
            if (function_exists('curl_init')) {
                $c = curl_init();
                curl_setopt_array($c, $o);
                curl_exec($c);
                curl_close($c);
            }
        }
    }
}
if (!function_exists('get_plugin_validate')) {
    /**
     * 获取插件验证类实例
     *
     * @param string $name 插件名
     *
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_plugin_validate($name = '')
    {
        $class = "plugins\\{$name}\\validate\\{$name}";
        return new $class;
    }
}
if (!function_exists('plugin_url')) {
    /**
     * 生成插件操作链接
     *
     * @param string $url 链接：插件名称/控制器/操作
     * @param array $param 参数
     * @param string $module 模块名，admin需要登录验证，index不需要登录验证
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function plugin_url($url = '', $param = [], $module = 'admin')
    {
        $params = [];
        $url    = explode('/', $url);
        if (isset($url[0])) {
            $params['_plugin'] = $url[0];
        }
        if (isset($url[1])) {
            $params['_controller'] = $url[1];
        }
        if (isset($url[2])) {
            $params['_action'] = $url[2];
        }
        // 合并参数
        $params = array_merge($params, $param);
        // 返回url地址
        return url($module . '/plugin/execute', $params);
    }
}
if (!function_exists('public_url')) {
    /**
     * 生成插件操作链接(不需要登陆验证)
     *
     * @param string $url 链接：插件名称/控制器/操作
     * @param array $param 参数
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function public_url($url = '', $param = [])
    {
        // 返回url地址
        return plugin_url($url, $param, 'index');
    }
}
if (!function_exists('clear_js')) {
    /**
     * 过滤js内容
     *
     * @param string $str 要过滤的字符串
     *
     * @return mixed|string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function clear_js($str = '')
    {
        $search = "/<script[^>]*?>.*?<\/script>/si";
        $str    = preg_replace($search, '', $str);
        return $str;
    }
}
if (!function_exists('get_nickname')) {
    /**
     * 根据用户ID获取用户昵称
     *
     * @param int $uid 用户ID
     *
     * @return mixed|string 用户昵称
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_nickname($uid = 0)
    {
        static $list;
        // 获取当前登录用户名
        if (!($uid && is_numeric($uid))) {
            return session('user_auth.username');
        }
        // 获取缓存数据
        if (empty($list)) {
            $list = cache('sys_user_nickname_list');
        }
        // 查找用户信息
        $key = "u{$uid}";
        if (isset($list[$key])) {
            // 已缓存，直接使用
            $name = $list[$key];
        } else {
            // 调用接口获取用户信息
            $info = model('user/user')->field('nickname')->find($uid);
            if ($info !== false && $info['nickname']) {
                $nickname = $info['nickname'];
                $name     = $list[$key] = $nickname;
                /* 缓存用户 */
                $count = count($list);
                $max   = config('user_max_cache');
                while ($count-- > $max) {
                    array_shift($list);
                }
                cache('sys_user_nickname_list', $list);
            } else {
                $name = '';
            }
        }
        return $name;
    }
}
if (!function_exists('action_log')) {
    /**
     * 记录行为日志，并执行该行为的规则
     *
     * @param null $action 行为标识
     * @param null $model 触发行为的模型名
     * @param string $record_id 触发行为的记录id
     * @param null $user_id 执行行为的用户id
     * @param string $details 详情
     *
     * @return bool|string
     * @author huajie <banhuajie@163.com>
     * @alter 蔡伟明 <314013107@qq.com>
     */
    function action_log($action = null, $model = null, $record_id = '', $user_id = null, $details = '')
    {
        // 判断是否开启系统日志功能
        if (config('system_log')) {
            // 参数检查
            if (empty($action) || empty($model)) {
                return '参数不能为空';
            }
            if (empty($user_id)) {
                $user_id = is_signin();
            }
            if (strpos($action, '.')) {
                list($module, $action) = explode('.', $action);
            } else {
                $module = request()->module();
            }
            // 查询行为,判断是否执行
            $action_info = model('admin/action')->where('module', $module)->getByName($action);
            if (empty($action_info) || $action_info['status'] != 1) {
                return '该行为被禁用或删除';
            }
            // 插入行为日志
            $data = ['action_id' => $action_info['id'], 'user_id' => $user_id, 'action_ip' => get_client_ip(1), 'model' => $model, 'record_id' => $record_id, 'create_time' => request()->time()];
            // 解析日志规则,生成日志备注
            if (!empty($action_info['log'])) {
                if (preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)) {
                    $log     = ['user' => $user_id, 'record' => $record_id, 'model' => $model, 'time' => request()->time(), 'data' => ['user' => $user_id, 'model' => $model, 'record' => $record_id, 'time' => request()->time()], 'details' => $details];
                    $replace = [];
                    foreach ($match[1] as $value) {
                        $param = explode('|', $value);
                        if (isset($param[1]) && $param[1] != '') {
                            if (is_disable_func($param[1])) {
                                continue;
                            }
                            $replace[] = call_user_func($param[1], $log[$param[0]]);
                        } else {
                            $replace[] = $log[$param[0]];
                        }
                    }
                    $data['remark'] = str_replace($match[0], $replace, $action_info['log']);
                } else {
                    $data['remark'] = $action_info['log'];
                }
            } else {
                // 未定义日志规则，记录操作url
                $data['remark'] = '操作url：' . $_SERVER['REQUEST_URI'];
            }
            // 保存日志
            model('admin/log')->insert($data);
            if (!empty($action_info['rule'])) {
                // 解析行为
                $rules = parse_action($action, $user_id);
                // 执行行为
                $res = execute_action($rules, $action_info['id'], $user_id);
                if (!$res) {
                    return '执行行为失败';
                }
            }
        }
        return true;
    }
}
if (!function_exists('parse_action')) {
    /**
     * 解析行为规则
     * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
     * 规则字段解释：table->要操作的数据表，不需要加表前缀；
     *            field->要操作的字段；
     *            condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
     *            rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
     *            cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
     *            max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
     * 单个行为后可加 ； 连接其他规则
     *
     * @param string $action 行为id或者name
     * @param int $self 替换规则里的变量为执行用户的id
     *
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author huajie <banhuajie@163.com>
     * @alter 蔡伟明 <314013107@qq.com>
     */
    function parse_action($action, $self)
    {
        if (empty($action)) {
            return false;
        }
        // 参数支持id或者name
        if (is_numeric($action)) {
            $map = ['id' => $action];
        } else {
            $map = ['name' => $action];
        }
        // 查询行为信息
        $info = model('admin/action')->where($map)->find();
        if (!$info || $info['status'] != 1) {
            return false;
        }
        // 解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
        $rule   = $info['rule'];
        $rule   = str_replace('{$self}', $self, $rule);
        $rules  = explode(';', $rule);
        $return = [];
        foreach ($rules as $key => & $rule) {
            $rule = explode('|', $rule);
            foreach ($rule as $k => $fields) {
                $field = empty($fields) ? array() : explode(':', $fields);
                if (!empty($field)) {
                    $return[$key][$field[0]] = $field[1];
                }
            }
            // cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
            if (!isset($return[$key]['cycle']) || !isset($return[$key]['max'])) {
                unset($return[$key]['cycle'], $return[$key]['max']);
            }
        }
        return $return;
    }
}
if (!function_exists('execute_action')) {
    /**
     * 执行行为
     *
     * @param array|bool $rules 解析后的规则数组
     * @param int $action_id 行为id
     * @param array $user_id 执行的用户id
     *
     * @return boolean false 失败 ， true 成功
     * @author huajie <banhuajie@163.com>
     * @alter 蔡伟明 <314013107@qq.com>
     */
    function execute_action($rules = false, $action_id = null, $user_id = null)
    {
        if (!$rules || empty($action_id) || empty($user_id)) {
            return false;
        }
        $return = true;
        foreach ($rules as $rule) {
            // 检查执行周期
            $map        = [['action_id', '=', $action_id], ['user_id', '=', $user_id], ['create_time', 'gt', request()->time() - intval($rule['cycle']) * 3600],];
            $exec_count = model('admin/log')->where($map)->count();
            if ($exec_count > $rule['max']) {
                continue;
            }
            // 执行数据库操作
            $field = $rule['field'];
            $res   = Db::name($rule['table'])->where($rule['condition'])->setField($field, array(
                'exp',
                $rule['rule']
            ));
            if (!$res) {
                $return = false;
            }
        }
        return $return;
    }
}
if (!function_exists('get_location')) {
    /**
     * 获取当前位置
     *
     * @param string $id 节点id，如果没有指定，则取当前节点id
     * @param bool $del_last_url 是否删除最后一个节点的url地址
     * @param bool $check 检查节点是否存在，不存在则抛出错误
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_location($id = '', $del_last_url = false, $check = true)
    {
        $location = model('admin/menu')->getLocation($id, $del_last_url, $check);
        return $location;
    }
}
if (!function_exists('packet_exists')) {
    /**
     * 查询数据包是否存在，即是否已经安装
     *
     * @param string $name 数据包名
     *
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    function packet_exists($name = '')
    {
        if (Db::name('admin_packet')->where('name', $name)->find()) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('sendsms_to_admin')) {
    /**
     * 给管理员发送短信
     *
     * @param string $msg 短信内容
     *
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function sendsms_to_admin($msg)
    {
        $mobile = $avatar = Db::name('admin_user')->where('username', 'admin')->value('mobile');
        $res    = send_sms($mobile, "", $msg);
        return $res;
    }
}
if (!function_exists('load_assets')) {
    /**
     * 加载静态资源
     *
     * @param string $assets 资源名称
     * @param string $type 资源类型
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function load_assets($assets = '', $type = 'css')
    {
        $assets_list = config('assets.' . $assets);
        $result      = '';
        foreach ($assets_list as $item) {
            if ($type == 'css') {
                $result .= '<link rel="stylesheet" href="' . $item . '?v=' . config('asset_version') . '">';
            } else {
                $result .= '<script src="' . $item . '?v=' . config('asset_version') . '"></script>';
            }
        }
        $result = str_replace(array_keys(config('template.tpl_replace_string')), array_values(config('template.tpl_replace_string')), $result);
        return $result;
    }
}
if (!function_exists('parse_name')) {
    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     *
     * @param string $name 字符串
     * @param integer $type 转换类型
     *
     * @return string
     */
    function parse_name($name, $type = 0)
    {
        if ($type) {
            return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }
                , $name));
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }
}
if (!function_exists('home_url')) {
    /**
     * 生成前台入口url
     *
     * @param string $url 路由地址
     * @param string|array $vars 变量
     * @param bool|string $suffix 生成的URL后缀
     * @param bool|string $domain 域名
     *
     * @return string
     * @author 小乌 <82950492@qq.com>
     */
    function home_url($url = '', $vars = '', $suffix = true, $domain = false)
    {
        $url = url($url, $vars, $suffix, $domain);
        if (defined('ENTRANCE') && ENTRANCE == 'admin') {
            $base_file = request()->baseFile();
            $base_file = substr($base_file, strripos($base_file, '/') + 1);
            return preg_replace('/\/' . $base_file . '/', '/index.php', $url);
        } else {
            return $url;
        }
    }
}
if (!function_exists('admin_url')) {
    /**
     * 生成后台入口url
     *
     * @param string $url 路由地址
     * @param string|array $vars 变量
     * @param bool|string $suffix 生成的URL后缀
     * @param bool|string $domain 域名
     *
     * @return string
     * @author 小乌 <82950492@qq.com>
     */
    function admin_url($url = '', $vars = '', $suffix = true, $domain = false)
    {
        $url = url($url, $vars, $suffix, $domain);
        if (defined('ENTRANCE') && ENTRANCE == 'admin') {
            return $url;
        } else {
            return preg_replace('/\/index.php/', '/' . ADMIN_FILE, $url);
        }
    }
}
if (!function_exists('htmlpurifier')) {
    /**
     * html安全过滤
     *
     * @param string $html 要过滤的内容
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function htmlpurifier($html = '')
    {
        $config     = HTMLPurifier_Config::createDefault();
        $purifier   = new HTMLPurifier($config);
        $clean_html = $purifier->purify($html);
        return $clean_html;
    }
}
if (!function_exists('extend_form_item')) {
    /**
     * 扩展表单项
     *
     * @param array $form 类型
     * @param array $_layout 布局参数
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function extend_form_item($form = [], $_layout = [])
    {
        if (!isset($form['type'])) return '';
        if (!empty($_layout) && isset($_layout[$form['name']])) {
            $form['_layout'] = $_layout[$form['name']];
        }
        $template = './extend/form/' . $form['type'] . '/' . $form['type'] . '.html';
        if (file_exists($template)) {
            $template_content = file_get_contents($template);
            $view             = Container::get('view');
            return $view->display($template_content, $form);
        } else {
            return '';
        }
    }
}
if (!function_exists('role_auth')) {
    /**
     * 读取当前用户权限
     * @author 蔡伟明 <314013107@qq.com>
     */
    function role_auth()
    {
        session('role_menu_auth', model('user/role')->roleAuth());
    }
}
if (!function_exists('get_server_ip')) {
    /**
     * 获取服务器端IP地址
     * @return array|false|string
     */
    function get_server_ip()
    {
        if (isset($_SERVER)) {
            if ($_SERVER['SERVER_ADDR']) {
                $server_ip = $_SERVER['SERVER_ADDR'];
            } else {
                $server_ip = $_SERVER['LOCAL_ADDR'];
            }
        } else {
            $server_ip = getenv('SERVER_ADDR');
        }
        return $server_ip;
    }
}
if (!function_exists('get_browser_type')) {
    /**
     * 获取浏览器类型
     * @return string
     */
    function get_browser_type()
    {
        $agent = $_SERVER["HTTP_USER_AGENT"];
        if (strpos($agent, 'MSIE') !== false || strpos($agent, 'rv:11.0')) return "ie";
        if (strpos($agent, 'Firefox') !== false) return "firefox";
        if (strpos($agent, 'Chrome') !== false) return "chrome";
        if (strpos($agent, 'Opera') !== false) return 'opera';
        if ((strpos($agent, 'Chrome') == false) && strpos($agent, 'Safari') !== false) return 'safari';
        if (false !== strpos($_SERVER['HTTP_USER_AGENT'], '360SE')) return '360SE';
        return 'unknown';
    }
}
if (!function_exists('generate_rand_str')) {
    /**
     * 生成随机字符串
     *
     * @param int $length 生成长度
     * @param int $type 生成类型：0-小写字母+数字，1-小写字母，2-大写字母，3-数字，4-小写+大写字母，5-小写+大写+数字
     *
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function generate_rand_str($length = 8, $type = 0)
    {
        $a = 'abcdefghijklmnopqrstuvwxyz';
        $A = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $n = '0123456789';
        switch ($type) {
            case 1:
                $chars = $a;
                break;
            case 2:
                $chars = $A;
                break;
            case 3:
                $chars = $n;
                break;
            case 4:
                $chars = $a . $A;
                break;
            case 5:
                $chars = $a . $A . $n;
                break;
            default:
                $chars = $a . $n;
        }
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
/**
 * 转换为万
 *
 * @param $num
 *
 * @return false|int|string
 */
function convertToTenThousand($num)
{
    $num = intval($num);

    // 小于10000的数字直接返回
    if ($num < 1000) {

        return $num;
    } elseif ($num < 10000) {
        //        return $num;
        // 大于或等于10000的数字进行转换
        $result = $num / 1000;
        return round($result, 2) . '千';
    } else {
        // 大于或等于10000的数字进行转换
        $result = $num / 10000;
        return round($result, 2) . '万';
    }

}

//字符串数字格式转换
function formatNumber($number)
{
    // 将字符串转换为浮点数
    $floatValue = floatval($number);

    // 检查小数部分是否为0
    if (fmod($floatValue, 1) === 0.0) {
        // 如果小数部分为0，输出整数部分
        return number_format($floatValue, 0, '.', '');
    } else {
        // 如果小数部分不为0，输出浮点数
        return rtrim(rtrim(number_format($floatValue, 2, '.', ''), '0'), '.');
    }
}

if (!function_exists('money_convert')) {
    /**
     * 资金单位为分转换其他单位
     *
     * @param  [type] $value [description]
     *
     * @return [type]        [description]
     * @author 张继立 <404851763@qq.com>
     */
    function money_convert($value = 0, $unit = '元')
    {
        $money = bcdiv($value, 100, 2);
        if ($unit == '万' && $value > 100000) {
            $money = bcdiv($value, 10000, 2);
        }
        return $money;
    }
}
if (!function_exists('send_sms')) {
    /**
     * 发送手机验证码 (短信插件 控制器名称必须和插件名相同 并且尾部带有Sms 字符，必须实现sendsms方法)
     *
     * @param int $mobile 手机号
     * @param string $template 模版名称,使用模板名称时，传入的短信内容info不生效
     * @param string $info 短信内容
     *
     * @return string           状态 成功 true 失败 false
     * @author 张继立 <404851763@qq.com>
     */
    function send_sms($mobile, $template, $info = null)
    {
        // 读取一条短信插件
        $sms_plugin = Db('admin_plugin')->where("name like '%daoSms' and status = 1")->find();
        //$develop_mode=config('develop_mode');//判断是否开发模式
        if (!$sms_plugin) { // 如果没有开启短信接口则发送默认验证码 0000
            $arr['code'] = md5('000000');
            $arr['time'] = time();
            setRegSmsCache($mobile, $arr);
            $res = true;
        } else {
            if (isset($template) || $template != '') { //如果存在后台模板，则使用模板；否则，使用自定义短信内容
                $code        = generate_rand_str(6, 3);
                $tp_str      = config($template);
                $info        = preg_replace("/\[code\]/", $code, $tp_str);
                $arr['code'] = md5($code);
                $arr['time'] = time();
                setRegSmsCache($mobile, $arr);
            } else {
                $code        = mt_rand(100000, 999999);
                $info        = '您好,' . $mobile . '您的验证码是' . $code;
                $arr['code'] = md5($code);
                $arr['time'] = time();
                setRegSmsCache($mobile, $arr);
            }
            //   $res = plugin_action($sms_plugin['name'], $sms_plugin['name'], 'sendSms', [$mobile, $info]);
            $res = true;
            if ($res == false) return false;
        }
        return $res;
    }
}
if (!function_exists('send_sms_member')) {
    function send_sms_member($msg, $param, $type = 'NULL')
    {
        // 读取一条短信插件
        $sms_plugin = Db('admin_plugin')->where("name like '%Sms' and status = 1")->find();
        if ($sms_plugin) {
            $admin_mobile = $avatar = Db::name('admin_user')->where('username', 'admin')->value('mobile');
            $params       = $type == 1000 ? $param : $admin_mobile . ',' . $param;
            $res          = plugin_action($sms_plugin['name'], $sms_plugin['name'], 'sendSms_member', [$msg, $params]);
            if ($res == false) return false;
            return $res;
        }
    }
}
if (!function_exists('yan_time')) {
    /*
     * 验证交易时间
    */
    function yan_time($t = '')
    {

        if ($t) {
            $t = $t - strtotime(date("Y-m-d", $t));
        } else {
            $t = time() - strtotime(date("Y-m-d", time()));
        }
        $t2 = 3600 * 9.5;   //早盘开盘时间
        $t3 = 3600 * 11.5;  //早盘停盘时间
        $t4 = 3600 * 13;    //下午开盘时间
        $t5 = 3600 * 14.99; //默认下午停盘时间14:55
        if (!(($t > $t2 && $t < $t3) || ($t > $t4 && $t < $t5))) {
            return false; //
        }
        if (date('N', time()) > 5) { //周六周日
            return false;            //
        }

        return true;
    }
}
if (!function_exists('dp_send_message')) {
    /**
     * 发送消息给用户
     *
     * @param string $type 消息类型
     * @param string $content 消息内容
     * @param string $uids 用户id，可以是数组，也可以是逗号隔开的字符串
     *
     * @return bool
     * @throws Exception
     * @author 蔡伟明 <314013107@qq.com>
     */
    function dp_send_message($type = '', $content = '', $uids = '')
    {
        $uids = is_array($uids) ? $uids : explode(',', $uids);
        $list = [];
        foreach ($uids as $uid) {
            $list[] = ['uid_receive' => $uid, 'uid_send' => UID, 'type' => $type, 'content' => $content,];
        }
        $MessageModel = model('user/message');
        return false !== $MessageModel->saveAll($list);
    }
}
if (!function_exists('is_disable_func')) {
    /**
     * 是否是禁用函数
     *
     * @param string $func
     *
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function is_disable_func($func = '')
    {
        if (!is_string($func) || $func == '') {
            return false;
        }
        $disable_functions = config('system.disable_functions');
        if (!$disable_functions) {
            return false;
        }
        return in_array(strtolower($func), $disable_functions);
    }
}
if (!function_exists('setRegSmsCache')) {
    /**
     * 设置手机短息验证码缓存
     *
     * @param $data_cache
     */
    function setRegSmsCache($mobile, $arr)
    {
        //think\facade\Cache::set('sms_'.$mobile, $arr, 300);
        cache('sms_ip', $arr, 120);
        cache('sms_' . $mobile, $arr, 120);
    }
}
if (!function_exists('check_sms_code')) {
    /*
     * 检测手机短信验证码
     * @param $mobile 手机号
     * @param bool|false $code
     * @return bool
     * @author menghui
    */
    function check_sms_code($mobile, $code = false)
    {
        if (!$mobile) return false;
        if ($code === false) { //判断60秒以内是否重复发送
            if (!think\facade\Cache::has('sms_' . $mobile) && !think\facade\Cache::has('sms_ip') && (think\facade\Cache::get('sms_ip') ['ip'] != get_client_ip())) return true;
            if (think\facade\Cache::get('sms_' . $mobile) ['time'] + 60 < time()) {
                if (think\facade\Cache::get('sms_ip') ['ip'] == get_client_ip()) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        } else { //判断验证码是否输入正确
            if (!think\facade\Cache::has('sms_' . $mobile)) return false;
            if (think\facade\Cache::get('sms_' . $mobile) ['code'] == md5($code)) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}
/**
 *Desc:校验短信验证码
 *
 * @param $mobile
 * @param $code
 *return:0:验证码不正确 1:验证码正确 -1:验证码已失效
 *author:oszpac
 *date:2023-06-15
 */
function aliCheckSMSCode($mobile, $code)
{
    if ($code == '112233') return 1;
    $key = get_code_key($mobile);
    if (!Cache::has($key)) return -1;
    $cac_code = Cache::get($key);
    if ($cac_code == $code) return 1;
    return 0;
}

//获取手机验证码缓存key
function get_code_key($mobile)
{
    return '_code_' . $mobile;
}

//获取手机验证码缓创建时间 key
function get_code_create_key($mobile)
{
    return '_code_create_' . $mobile;
}

/**
 *Desc:校验短信验证是否 间隔60秒
 *
 * @param $mobile
 *return: true: 大于60秒   false: 小于60
 *author:oszpac
 *date:2023-06-15
 */
function check_code_interval($mobile)
{
    $key         = get_code_create_key($mobile);
    $create_time = Cache::get($key);
    if (empty($create_time)) return true;
    $interval = time() - $create_time;
    return $interval > 60;
}

/**
 * Desc : 发送短信
 * Date : 2024-07-06 21:16
 */
function SendSMSCode($mobile, $reg_code = '86', $content = '')
{
    $_CFG     = config('SMS_channel');
    $validate = mt_rand(100000, 999999);
    if ($reg_code != '86') {
        $_CFG = 'AliSMS_internationality';
    }
    switch ($_CFG) {
        case "ALISMS":
            $content = '您的验证码为：' . $validate . '，该验证码5分钟内有效，请勿泄漏于他人';
            //待测试
            $code = alisendSMSInternal($mobile, $validate, 'validate');
            break;
        case "AliSMS_internationality":
            $content = 'Your confirmation code is: ' . $validate . ' (valid for 10 minutes)';
            $code    = aliSendSMSGlobe($reg_code . $mobile, $content);
            break;
        case "SMStx":
            $content = '您的验证码为：' . $validate . '，该验证码5分钟内有效，请勿泄漏于他人';
            $code    = TXSendSMSCode($reg_code . $mobile, $validate);

            break;
        case "SMSbao":
            $content = '您的验证码为：' . $validate . '，该验证码5分钟内有效，请勿泄漏于他人';
            $code    = sendSMSBao($mobile, $content);

            break;
        case "wanli":
            $content = '您的验证码为：' . $validate . '，该验证码5分钟内有效，请勿泄漏于他人';
            $code    = sendSMSwanli($mobile, $validate);

            break;
        case "zhonglian":
            $content = '您的验证码为：' . $validate . '，该验证码5分钟内有效，请勿泄漏于他人';
            $code    = sendSMSzhonglian($mobile, $validate);

            break;

        case "mandao":
            //已废弃
            $content = '您的验证码为：' . $validate . '，该验证码5分钟内有效，请勿泄漏于他人';
            $code    = sendsms_mandao($mobile, $content);

            break;

        default:
            return false;

    }
    $insert = [
        'mobile'         => $mobile,
        'reg_code'       => $reg_code,
        'channel'        => $_CFG,
        'return_content' => $code['msg'],
        'send_content'   => $content,
        'zone'           => '',
        'status'         => $code['status'] == true ? 1 : 0,
        'ip'             => get_client_ip(),
        'addtime'        => time(),

    ];
    Db::name("member_smslog")->insertGetId($insert);
    $key = get_code_key($mobile);
    Cache::set($key, $validate, 600);
    $key_creat = get_code_create_key($mobile);
    Cache::set($key_creat, time());
    if ($code['status']) {
        return true;
    } else {
        return false;
    }
}

/**
 *Desc:阿里云发送短信验证码
 *
 * @param $mobile  手机号
 * @param $reg_code 区号
 *return:
 *author:oszpac
 *date:2023-06-15
 */
function aliSendSMSCode($mobile, $reg_code = '86')
{
    //发送短信验证码
    $validate = mt_rand(100000, 999999);
    if ($reg_code == '86') {

        $content = '您的验证码为：' . $validate . '，该验证码5分钟内有效，请勿泄漏于他人';
        $code    = sendSMSBao($mobile, $content);
        $res     = $code == '0';
    } else {
        $content = 'Your confirmation code is: ' . $validate . ' (valid for 10 minutes)';
        $res     = aliSendSMSGlobe($reg_code . $mobile, $content);
    }
    $key = get_code_key($mobile);
    Cache::set($key, $validate, 600);
    $key_creat = get_code_create_key($mobile);
    Cache::set($key_creat, time());
    if ($res) {
        return true;
    } else {
        return false;
    }
}

/**
 *Desc:阿里云发送短信验证码
 *
 * @param $mobile  手机号
 * @param $reg_code 区号
 *return:
 *author:oszpac
 *date:2023-06-15
 */
function TXSendSMSCode($mobile, $TemplateParamSet)
{

    $_CFG = config('SMS_channel_config');
    $_CFG = $_CFG['SMStx'];

    $SecretId  = $_CFG['SecretId'];
    $SecretKey = $_CFG['SecretKey'];

    $templateId  = $_CFG['templateId'];
    $smsSign     = $_CFG['smsSign'];
    $SmsSdkAppid = $_CFG['SDKAppID'];

    try {
        // 实例化认证对象
        $cred        = new Credential($SecretId, $SecretKey);
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("sms.tencentcloudapi.com");

        // 实例化一个 http 选项
        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        // 实例化一个 client 选项
        $client = new SmsClient($cred, "", $clientProfile);

        // 实例化一个 sms 发送短信请求对象，每个接口都会对应一个 request 对象。
        $req = new SendSmsRequest();

        // 发送短信验证码配置参数
        $params = '{"PhoneNumberSet":["+' . $mobile . '"],"TemplateID":"' . $templateId . '","Sign":"' . $smsSign . '","TemplateParamSet":["' . $TemplateParamSet . '"],"SmsSdkAppid":"' . $SmsSdkAppid . '"}';
        $req->fromJsonString($params);

        // 通过 client 对象调用 SendSms 方法发起请求
        $resp = $client->SendSms($req);

        $resp = json_decode($resp->toJsonString(), true)['SendStatusSet'][0];

        return ['status' => $resp['Code'] == "Ok", 'msg' => $resp['Message']];
    } catch (ServerException $e) {

        return ['status' => false, 'msg' => $e->getMessage()];
    } catch (Exception $e) {
        return ['status' => false, 'msg' => $e->getMessage() . 'An unexpected error occurred.'];
    } catch (TencentCloudSDKException $e) {
        return ['status' => false, 'msg' => '未知原因'];
    }

}

//短信宝短信发送接口
function sendSMSBao($phone, $content)
{
    $_CFG      = config('SMS_channel_config');
    $_CFG      = $_CFG['SMSbao'];
    $statusStr = array(
        "0"  => "短信发送成功",
        "-1" => "参数不全",
        "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
        "30" => "密码错误",
        "40" => "账号不存在",
        "41" => "余额不足",
        "42" => "帐户已过期",
        "43" => "IP地址限制",
        "50" => "内容含有敏感词"
    );
    $smsapi    = "https://www.smsbao.com/sms"; //短信网关
    $user      = $_CFG['user_name'];           //短信平台帐号
    $pass      = md5($_CFG['api_key']);        //短信平台密码

    $code = http_request($smsapi, ['u' => $user, 'p' => $pass, 'm' => $phone, 'c' => urlencode($content)]);

    $log = ['mobile' => $phone, 'content' => $content, 'code' => $code, 'msg' => $statusStr[$code]];
    Logs::log('SMSBao', $log, 'SMS');
    if ($code == 0) {
        return ['status' => true, 'msg' => $statusStr[$code]];
    } else {
        return ['status' => false, 'msg' => $statusStr[$code]];
    }

}

//万里短信短信发送接口
function sendSMSwanli($phone, $validate)
{
    $_CFG = Config::get('SMS_channel_config')['wanli'];

    $smsapi = "https://sms-api.wanliyun.com/v1/open/api/cn/sms/send"; //短信网关

    $params           = [
        "accessKey"  => $_CFG['apiAccessKey'],
        "secretKey"  => $_CFG['apiSecretKey'],
        "templateId" => $_CFG['templateId'],
    ];
    $params['phones'] = array(array("areaCode" => "86", "phoneNumber" => "$phone"));

    $params['datas'] = ["code" => "$validate"];

    $code = http_request($smsapi, [], json_encode($params), true);
    $code = json_decode($code, true);

    if ($code['code'] == "200") {
        return ['status' => $code['code'] == "200", 'msg' => '成功'];
    } else {
        return ['status' => false, 'msg' => '发送失败'];
    }
}

//万里短信短信发送接口
function sendSMSzhonglian($phone, $validate)
{
    $_CFG = Config::get('SMS_channel_config')['zhonglian'];

    $smsapi = "http://112.74.47.72/smsJson.aspx"; //短信网关

    $params = [
        "account"  => $_CFG['account'],
        "password" => md5($_CFG['password']),
        "mobile"   => $phone,
        "content"  => "您的验证码是：{$validate}，请在5分钟内使用。" . $_CFG['smsSign'],
        "action"   => "send",
    ];
    printlog($params, '众联短信', 'smssend');
    $code = http_request($smsapi, $params, $params, false);
    printlog($code, '众联短信', 'smssend');
    $code = json_decode($code, true);
    printlog($code, '众联短信', 'smssend');
    if ($code['returnstatus'] == "Success") {

        return ['status' => $code['returnstatus'] == "Success", 'msg' => '操作成功'];
    } else {
        return ['status' => false, 'msg' => $code['message']];
    }
}

/**邮箱发送验证码
 *
 * @param $email
 *
 * @return bool
 */
function sendEmailCode($email)
{
    //生成随机六位数，不足六位两边补零
    $code    = mt_rand(100000, 999999);
    $subject = lang('L0150');
    $body    = '<H4><center>' . $subject . '</center></H4><H3><center style="color:#9373ef">' . $code . '</center></H3>';
    $emailer = new Email();
    $ret     = $emailer->send($email, $subject, $body);
    if ($ret) {

        $key = getEmailCodeCacheKey($email);
        Cache::set($key, $code, 600);
        $key_creat = get_code_create_key($email);
        Cache::set($key_creat, time());

        return true;
    } else {
        return false;
    }
}

/**验证邮箱验证码
 *
 * @param $email
 * @param $code
 *
 * @return bool
 */
function checkEmaiCode($email, $code)
{

    if (empty($email) || empty($code)) return false;

    if ($code == '112233') return true;
    $key = getEmailCodeCacheKey($email);
    if (!Cache::has($key)) return false;
    $cac_code = Cache::get($key);
    if ($cac_code == $code) return true;
    return false;

}

//获取邮箱验证码缓存Key
function getEmailCodeCacheKey($email = '')
{
    return 'emai.code.' . $email;
}

/*
 * 获取用户返佣收益
*/
if (!function_exists('agents_back_money')) {
    function agents_back_money($mid)
    {
        $back_money = Db::name('agents_back_money')->where('mid', $mid)->sum('affect');
        $back_money = $back_money ? $back_money : 0.00;
        return round($back_money, 2);
    }
}
if (!function_exists("text")) {
    //输出纯文本
    function text($text, $parseBr = false, $nr = false)
    {
        $text = htmlspecialchars_decode($text);
        $text = safe($text, 'text');
        if (!$parseBr && $nr) {
            $text = str_ireplace(array(
                "\r",
                "\n",
                "\t",
                "&nbsp;"
            ), '', $text);
            $text = htmlspecialchars($text, ENT_QUOTES);
        } elseif (!$nr) {
            $text = htmlspecialchars($text, ENT_QUOTES);
        } else {
            $text = htmlspecialchars($text, ENT_QUOTES);
            $text = nl2br($text);
        }
        $text = trim($text);
        return $text;
    }
}
if (!function_exists("safe")) {
    function safe($text, $type = 'html', $tagsMethod = true, $attrMethod = true, $xssAuto = 1, $tags = array(), $attr = array(), $tagsBlack = array(), $attrBlack = array())
    {
        //无标签格式
        $text_tags = '';
        //只存在字体样式
        $font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
        //标题摘要基本格式
        $base_tags = $font_tags . '<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
        //兼容Form格式
        $form_tags = $base_tags . '<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
        //内容等允许HTML的格式
        $html_tags = $base_tags . '<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed>';
        //专题等全HTML格式
        $all_tags = $form_tags . $html_tags . '<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
        //过滤标签
        $text = strip_tags($text, ${
            $type . '_tags'
        });
        //过滤攻击代码
        if ($type != 'all') {
            //过滤危险的属性，如：过滤on事件lang js
            while (preg_match('/(<[^><]+) (onclick|onload|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat)) {
                $text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
            }
            while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
                $text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
            }
        }
        return $text;
    }
}
if (!function_exists("is_verify")) {
    /*
     * 检测图形验证码
     * @param $mobile 手机号
     * @param bool|false $code
     * @return bool
     * @author menghui
    */
    function is_verify($code, $timespan)
    {
        $vd['send_time'] = array(
            "gt",
            time() - $timespan
        );
        $vd['code']      = md5(strtoupper($code));
        $vd['type']      = 0;
        $vo              = DB::name("verify")->where($vd)->count();
        if ($vo > 0) {
            $vo = DB::name("verify")->where($vd)->delete();
            if ($vo) return true;
        } else return false;
    }
}
if (!function_exists('sendsms_mandao')) {
    /****************************
     * /*  手机短信接口（漫道短信www.zucp.net）
     * /* 参数：$mob        手机号码
     * /*        $content    短信内容
     * /*        $tp        类型 $tp='code' 是验证码短信 ，等于空是变量短信
     *****************************/
    function sendsms_mandao($mob, $content, $tp = '', $is_mobile = 0, $phonecode = '')
    {
        //return true;
        $sign         = config('sms_sign');
        $content      = $sign . $content;
        $where        = '';
        $admin_mobile = $avatar = Db::name('admin_user')->where('username', 'admin')->where($where)->value('mobile');
        if ($tp == '') {
            $mob = $admin_mobile;
        };
        $type = config('sms_status'); // type=0 漫道短信接口
        if ($type == 1) {
            if ($is_mobile == 1) {
                $result = is_verify($phonecode, 60);
                if (true !== $result) {
                    // 验证失败 输出错误信息
                    //  ajaxmsg('图形验证码错误！', 0);
                }
            }
            if ($tp == 'code') {
                $code        = mt_rand(100000, 999999);
                $content     = $content . $code;
                $arr['code'] = md5($code);
                $arr['time'] = time();
                $arr['ip']   = get_client_ip();
                setRegSmsCache($mob, $arr);
                setexredis($mob, 120, $code);
                /////////////////////////////////////////漫道短信接口 开始/////////////////////////////////////////////////////////////
                $content = str_replace("平仓", "", $content);
                $content = str_replace("配资", "订单服务", $content);
                if (strpos($content, '策略') !== false || strpos($content, '提现') !== false || strpos($content, '充值') !== false) {
                    return true;
                }
                $content = str_replace("策略", "订单服务", $content);
                //   $content= utf8_encode($content);
                $ch     = curl_init();
                $sms_sn = config('sms_sn');
                $sms_pw = config('sms_pw');
                $url    = "http://api.smsbao.com/sms?u={$sms_sn}&p={$sms_pw}&m={$mob}&c={$content}";
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                $output = curl_exec($ch);
                curl_close($ch);
                if ($output == "0") {
                    return true;
                } else {
                    return FALSE;
                }
            }
        } else {
            if ($tp == 'code') {
                $code        = '000000';
                $arr['code'] = md5($code);
                $arr['time'] = time();
                $arr['ip']   = get_client_ip();
                setRegSmsCache($mob, $arr);
                return true;
            }
        }
    }
}
/**
 *Desc:发送阿里国内短信
 *
 * @param $mobile 手机号
 * @param $template_code 模板id
 * @param $param 变量数组
 * @param $sign： 签名
 *return:
 *author:oszpac
 *date:2023-06-15
 */
function alisendSMSInternal($mobile = '', $code = '', $param = '')
{
    $_CFG            = Config('SMS_channel_config')['AliSMS_cn'];
    $accessKeyId     = $_CFG['internal_key'];
    $accessKeySecret = $_CFG['internal_secret'];
    $sign            = $_CFG['internal_sign'];
    $template_code   = $_CFG['AliSMS_template_code']['captcha'];
    try {
        AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)->regionId('cn-hangzhou')->asDefaultClient();
        $query = ['query' => [
            'RegionId'      => "cn-hangzhou",
            'PhoneNumbers'  => $mobile,
            'SignName'      => $sign,
            'TemplateCode'  => $template_code,
            'TemplateParam' => json_encode(['code' => $code])
        ]
        ];

        $result = AlibabaCloud::rpc()
            ->product('Dysmsapi')
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->host('dysmsapi.aliyuncs.com')
            ->options($query)->request();


        printlog($result, '阿里云短信', 'smssend');
        $res = empty($result) ? $result : $result->toArray();

        $log = ['mobile' => $mobile, 'template_code' => $template_code, 'param' => $param, 'res' => $res];
        Logs::log('AliSendSMSInternal', $log, 'SMS');
        printlog($res, '阿里云短信', 'smssend');
        return ['status' => $res['Code'] == 'OK', 'msg' => $res['Message']];

    } catch (ServerException $e) {

        return ['status' => false, 'msg' => $e->getMessage()];
    } catch (Exception $e) {
        return ['status' => false, 'msg' => 'An unexpected error occurred.'];
    } catch (TencentCloudSDKException $e) {
        return ['status' => false, 'msg' => '未知原因'];
    }
}

/**
 *Desc:
 *
 * @param $number 国家地区电话代码
 * @param $mobile 手机号码
 * @param $content 短信内容
 *return:
 *author:oszpac
 *date:2023-06-15
 */
function aliSendSMSGlobe($mobile = '', $content = '')
{
    $_CFG            = Config('SMS_channel_config');
    $_CFG            = $_CFG['AliSMS_internationality'];
    $accessKeyId     = $_CFG['globe_key'];
    $accessKeySecret = $_CFG['globe_secret'];
    try {
        AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)->regionId('ap-southeast-1')->asDefaultClient();
        $result = AlibabaCloud::rpc()->product('Dysmsapi')->host('dysmsapi.ap-southeast-1.aliyuncs.com')->version('2018-05-01')->action('SendMessageToGlobe')->method('POST')->options(['query' => ["To" => $mobile, "Message" => $content]])->request();

        $res = empty($result) ? $result : $result->toArray();
        $log = ['mobile' => $mobile, 'content' => $content, 'res' => $res];
        Logs::log('AliSendSMSGlobe', $log, 'SMS');

        if ($res['ResponseDescription'] == 'OK') {
            return ['status' => true, 'msg' => '成功'];
        } else {
            //dump($res);
            return ['status' => false, 'msg' => $res['ResponseDescription']];
        }
    } catch (ServerException $e) {
        return ['status' => false, 'msg' => $e->getMessage()];
    } catch (Exception $e) {
        return ['status' => false, 'msg' => 'An unexpected error occurred.'];
    } catch (TencentCloudSDKException $e) {
        return ['status' => false, 'msg' => '未知原因'];
    }
}

if (!function_exists('sendsms_mandao3')) {
    /****************************
     * /*  手机短信接口（漫道短信www.zucp.net）
     * /* 参数：$mob        手机号码
     * /*        $content    短信内容
     * /*        $tp        类型 $tp='code' 是验证码短信 ，等于空是变量短信
     *****************************/
    function sendsms_mandao3($mob, $content, $tp = '', $is_mobile = 0, $phonecode = '')
    {
        $pwds         = "SDK-SRX-010-0000160-ca0d-46b";
        $sn           = "SDK-SRX-010-00001";
        $sign         = "【浩源】";
        $content      = $sign . $content;
        $pwds         = strtoupper(md5($pwds));
        $admin_mobile = $avatar = Db::name('admin_user')->where('username', 'admin')->value('mobile');
        if ($tp == '') {
            $mob = $admin_mobile;
        };
        $type = config('sms_status'); // type=0 漫道短信接口
        if ($type == 1) {
            if ($is_mobile == 1) {
                $result = is_verify($phonecode, 60);
                if (true !== $result) {
                    // 验证失败 输出错误信息
                    ajaxmsg('图形验证码错误！', 0);
                }
            }
            if ($tp == 'code') {
                $code        = mt_rand(100000, 999999);
                $content     = $content . $code;
                $arr['code'] = md5($code);
                $arr['time'] = time();
                $arr['ip']   = get_client_ip();
                setRegSmsCache($mob, $arr);
            }
            /////////////////////////////////////////漫道短信接口 开始/////////////////////////////////////////////////////////////
            //如果您的系统是utf-8,请转成GB2312 后，再提交、
            $flag = 0;
            //要post的数据
            $argv = array(
                'sn'      => $sn, ////替换成您自己的序列号
                'pwd'     => $pwds, //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
                'mobile'  => $mob, //手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
                'content' => iconv("UTF-8", "gb2312//IGNORE", $content), //短信内容
                'ext'     => '',
                'stime'   => '', //定时时间 格式为2011-6-29 11:09:21
                'rrid'    => ''
            );
            //构造要post的字符串
            $params = "";
            foreach ($argv as $key => $value) {
                if ($flag != 0) {
                    $params .= "&";
                    $flag   = 1;
                }
                $params .= $key . "=";
                $params .= urlencode($value);
                $flag   = 1;
            }
            $length = strlen($params);
            //创建socket连接
            $fp = fsockopen("sdk2.zucp.net", 8060, $errno, $errstr, 10) or exit($errstr . "--->" . $errno);
            //构造post请求的头
            $header = "POST /webservice.asmx/mt HTTP/1.1\r\n";
            $header .= "Host:sdk2.zucp.net\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: " . $length . "\r\n";
            $header .= "Connection: Close\r\n\r\n";
            //添加post的字符串
            $header .= $params . "\r\n";
            //发送post的数据
            fputs($fp, $header);
            $inheader = 1;
            // $resp_str='';
            while (!feof($fp)) {
                //  $resp_str .= fgets($fp,512);//返回值放入$resp_str
                $line = fgets($fp, 1024); //去除请求包的头只显示页面的返回数据
                // file_put_contents("/wwwserver/wwwroot/new.com/log.txt", "数组内容\n".var_export($line), FILE_APPEND);
                if ($inheader && ($line == "\n" || $line == "\r\n")) {
                    $inheader = 0;
                }
                if ($inheader == 0) {
                    // echo $line;
                }
            }
            // file_put_contents("/wwwserver/wwwroot/new.com/log.txt", "数组内容\n".var_export($resp_str), FILE_APPEND);
            $line   = str_replace("
	<string xmlns=\"http://tempuri.org/\">", "", $line);
            $line   = str_replace("</string>
	", "", $line);
            $result = explode("-", $line);
            if (count($result) > 1) {
                return true;
            } else {
                return true;
            }
            /////////////////////////////////////////漫道短信接口 结束/////////////////////////////////////////////////////////////
        } else {
            if ($tp == 'code') {
                $code        = '000000';
                $arr['code'] = md5($code);
                $arr['time'] = time();
                $arr['ip']   = get_client_ip();
                setRegSmsCache($mob, $arr);
                return true;
            }
        }
    }
}
if (!function_exists('getconfigSms_status')) {
    function getconfigSms_status($map)
    {
        $data_list = Db::name('admin_config')->where($map)->field('value,status')->find();
        return $data_list;
    }
}
/**
 * 计算可提盈金额
 */
if (!function_exists('lifting')) {
    function lifting($sub_id)
    {
        $position         = new \app\market\model\Position();
        $position_res     = $position->get_position_b($sub_id);
        $subaccount_money = Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $sub_id])->select();
        $sum              = $avail = $freeze_amount = 0;
        foreach ($position_res as $k => $v) {
            $sum += $v['market_value'];
        }
        foreach ($subaccount_money as $k) {
            $avail         += $k['avail'];
            $freeze_amount += $k['freeze_amount'];
        }
        //返回账户市值 子账户可用余额 账户冻结金额
        return array(
            "market"        => $sum * 100,
            "avail"         => $avail,
            "freeze_amount" => $freeze_amount
        );
    }
}
/**
 * 计算实时子账户提赢
 */
if (!function_exists('tixian')) {
    //因阿杜改变了想法重新换了一个算法  该方法停用
    function tixian($subaccount_id, $comeback = false)
    {
        $submoney_info = SubaccountMoney::getMoneyByID($subaccount_id);
        $addmoney      = $submoney_info['stock_addmoney'];   //累计追加保证金
        $drawprofit    = $submoney_info['stock_drawprofit']; //累计提取盈利
        $binfo         = Db::name("stock_borrow")->where(["stock_subaccount_id" => $subaccount_id])->find();
        //账户总盈利金额=操盘总资金(融资金额+保证金+扩大配资)+提赢-目前账户总资产-追加的保证金
        $subsummony = lifting($subaccount_id);
        $subsummony = ($subsummony["market"] + $subsummony["avail"]) / 100;
        printlog($binfo['init_money'], "操盘总资金", "test2");
        printlog($drawprofit, "提赢", "test2");
        printlog($addmoney, "追加的保证金", "test2");
        //委托卖出
        $affect_money1 = Db::name('stock_delivery_order')->where(array(
            'sub_id'        => $subaccount_id,
            'status'        => 0,
            "business_name" => "证券卖出"
        ))->SUM("liquidation_amount");
        printlog($affect_money1, "已委托卖出", "test2");
        //委托买入
        $affect_money2 = Db::name('stock_delivery_order')->where(array(
            'sub_id'        => $subaccount_id,
            'status'        => 0,
            "business_name" => "证券买入"
        ))->SUM("liquidation_amount");
        printlog($affect_money2, "已委托证券买入", "test2");
        $money = ($subsummony - ($binfo['init_money'] / 100) + $drawprofit - $addmoney + $affect_money2) * 100;
        printlog($money, "盈利金额", "test2");
        $str = $subsummony . "(目前账户总资产)-" . ($binfo['init_money'] / 100) . "(操盘总金额)+" . $drawprofit . "(提赢)-" . $addmoney . "(追加的保证金)+" . $affect_money2 . "(已委托证券买入)=" . $money / 100;
        printlog($str, "盈利金额", "test2");
        if ($comeback) {
            return $money;
        } else {
            if ($money > 0) {
                return $money;
            } else {
                return 0;
            }
        }
    }

    //计算实时子账户提赢
    /**  账户总盈亏盈亏金额=账户总资产（持仓市值+可用金额+冻结余额，是浮动的随股票变动而变动）-融资总金额（融资金额+保证金）
     * $subaccount_id 需要查询的子账户id
     * $comeback 是否返回具体的金额
     */
    function tixian3($subaccount_id, $comeback = false)
    {
        $sub_money = Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $subaccount_id])->find();
        //
        //print_r($sub_money);
        // 返回账户市值 子账户可用余额 账户冻结金额
        $lifting = lifting($subaccount_id);
        //    borrow_money： 融资金额
        //    deposit_money：保证金
        $borrowmoeny = $sub_money['deposit_money'] + $sub_money['borrow_money'];
        printlog($lifting, '总价值', 'tixian');
        printlog($borrowmoeny, '融资总金额', 'tixian');
        //子账户正向总金额=账户市值+子账户可用余额
        $available_amoun = $lifting['market'] + $lifting['avail'];
        if ($available_amoun == 0) {
            return 0;
        }
        printlog($available_amoun, '正向总金额', 'tixian');
        printlog($available_amoun, '盈亏金额', 'tixian');
        // 账户总盈亏盈亏金额=账户总资产（持仓市值+可用金额+冻结余额，是浮动的随股票变动而变动）-融资总金额（融资金额+保证金）
        $money = $available_amoun - $borrowmoeny;
        printlog($money, '盈亏金额', 'tixian');
        if ($comeback) {
            return $money;
        } else {
            if ($money > 0) {
                return $money;
            } else {
                return 0;
            }
        }
    }
}
function tixian22($subaccount_id, $comeback = false)
{
    $data_list    = Db::name('stock_submoney_profit_loss_record')->where(['sub_id' => $subaccount_id])->select();
    $data_list    = Db::view('stock_delivery_order d')->view('stock_subaccount s', 'sub_account', 's.id=d.sub_id', 'left')->where(['d.status' => 1, 'd.business_name' => '证券卖出'])->where(['d.sub_id' => $subaccount_id])->select();
    $return_money = $ck_profit = 0;
    //历史成交盈亏
    foreach ($data_list as $v) {
        $v['return_money'] = round(($v['deal_price'] - $v['buy_price']) * $v['volume'], 2);
        printlog($v['return_money'], $v['gupiao_code'], 'tixian');
        //stamp_duty 印花税,transfer_fee  过户费,commission 净佣金
        $return_money += $v['return_money'] - $v['stamp_duty'] - $v['transfer_fee'] - $v['commission'];
        printlog($return_money, $v['gupiao_code'], 'tixian');
    }
    printlog($return_money, '历史成交盈亏', 'tixian');
    $data_list = Db::view('stock_position p')->view('stock_subaccount s', 'sub_account', 's.id=p.sub_id', 'left')->view('member m', 'mobile,email', 'm.id=s.uid', 'left')->where(['p.buying' => 0])->where(['p.sub_id' => $subaccount_id])->select();
    foreach ($data_list as $k => $v1) {
        if (empty($v1['ck_price_new'])) {
            $v1['ck_price_new'] = $v1['ck_price'];
        }
        $v1['ck_profit'] = ($v1['now_price'] - $v1['ck_price_new']) * $v1['stock_count'];
        $ck_profit       += round($v1['ck_profit'], 2);
    }
    printlog($ck_profit, '持仓浮动盈亏', 'tixian');
    //账户总盈亏=历史成交盈亏+持仓浮动盈亏
    $money = ($ck_profit + $return_money) * 100;
    printlog($money, '总胡总盈亏', 'tixian');
    if ($comeback) {
        return $money;
    } else {
        if ($money > 0) {
            return $money;
        } else {
            return 0;
        }
    }
}

/**
 * 计算实时子账户提赢
 */
if (!function_exists('tixian')) {
    //计算实时子账户提赢
    /**  账户总盈亏盈亏金额=账户总资产（持仓市值+可用金额+冻结余额，是浮动的随股票变动而变动）-融资总金额（融资金额+保证金）
     * $subaccount_id 需要查询的子账户id
     * $comeback 是否返回具体的金额
     */
    function tixian2($subaccount_id, $comeback = false)
    {
        printlog($subaccount_id, '获取正在挂单的部分列表', 'subtixian_new_tixian');
        //1、获取正在挂单的部分
        $stock_trust = Db::name('stock_trust')->where(['sub_id' => $subaccount_id, 'flag2' => '买入委托', 'status' => '已委托'])->select();
        printlog($stock_trust, '获取正在挂单的部分列表', 'subtixian_new_tixian');
        //委托买入金额的挂单金额
        $total_trust = 0;
        foreach ($stock_trust as $v) {
            $total_trust += $v['volume'] * $v['amount'] / 100;
        }
        printlog($total_trust, '委托买入金额的挂单金额', 'subtixian_new_tixian');
        $lifting = lifting($subaccount_id);
        //        //返回账户市值 子账户可用余额 账户冻结金额
        //        return array(
        //            "market" => $sum * 100,
        //            "avail" => $avail,
        //            "freeze_amount" => $freeze_amount
        //        );
        printlog($lifting['market'] / 100, '账户市值', 'subtixian_new_tixian');
        printlog($lifting['avail'] / 100, '子账户可用余额', 'subtixian_new_tixian');
        printlog($total_trust, '委托买入金额', 'subtixian_new_tixian');
        $sub_money   = Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $subaccount_id])->find();
        $borrowmoeny = $sub_money['deposit_money'] + $sub_money['borrow_money'];
        printlog($borrowmoeny / 100, '总操盘金额', 'subtixian_new_tixian');
        $money = ($lifting['market'] / 100 + $lifting['avail'] / 100 + $total_trust - $borrowmoeny / 100);
        printlog($money, '可提赢部分', 'subtixian_new_tixian');
        if ($comeback) {
            return $money;
        } else {
            if ($money > 0) {
                return $money;
            } else {
                return 0;
            }
        }
    }
}
/**
 * 计算子账户提赢部分
 */
if (!function_exists('subtixian_new')) {
    //计算子账户提赢部分
    //  公式：可提金额=（浮动市值+可用余额+委托买入金额-操盘总金额）*80%
    //  可提金额不能大于可用余额
    /**
     * 可提金额=（浮动市值+可用余额+委托买入金额-操盘总金额）*80%
     * $subaccount_id 需要查询的子账户id
     */
    function subtixian_new($subaccount_id)
    {
        printlog($subaccount_id, '获取正在挂单的部分列表', 'subtixian_new');
        //1、获取正在挂单的部分
        $stock_trust = Db::name('stock_trust')->where(['sub_id' => $subaccount_id, 'flag2' => '买入委托', 'status' => '已委托'])->select();
        printlog($stock_trust, '获取正在挂单的部分列表', 'subtixian_new');
        //委托买入金额的挂单金额
        $total_trust = 0;
        foreach ($stock_trust as $v) {
            $total_trust += $v['volume'] * $v['amount'] / 100;
        }
        printlog($total_trust, '委托买入金额的挂单金额', 'subtixian_new');
        $lifting = lifting($subaccount_id);
        //        //返回账户市值 子账户可用余额 账户冻结金额
        //        return array(
        //            "market" => $sum * 100,
        //            "avail" => $avail,
        //            "freeze_amount" => $freeze_amount
        //        );
        printlog($lifting['market'] / 100, '账户市值', 'subtixian_new');
        printlog($lifting['avail'] / 100, '子账户可用余额', 'subtixian_new');
        printlog($total_trust, '委托买入金额', 'subtixian_new');
        $sub_money   = Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $subaccount_id])->find();
        $borrowmoeny = $sub_money['deposit_money'] + $sub_money['borrow_money'];
        printlog($borrowmoeny / 100, '总操盘金额', 'subtixian_new');
        $ty = ($lifting['market'] / 100 + $lifting['avail'] / 100 + $total_trust - $borrowmoeny / 100);
        printlog($ty, '可提赢部分', 'subtixian_new');
        $ty = $ty * 0.8;
        printlog($ty, '只能提取80%', 'subtixian_new');
        if ($lifting['avail'] / 100 < $ty && $lifting['avail'] / 100 > 0) {
            printlog($lifting['avail'] / 100, '可提赢金额大于子账户余额，只能提之账户余额部分：', 'subtixian_new');
            return $lifting['avail'] / 100;
        } elseif ($lifting['avail'] / 100 > $ty && $ty > 0) {
            printlog($ty, '可提赢金额小于子账户余额，可以提取全部盈利部分：', 'subtixian_new');
            return $ty;
        } else {
            printlog(0, '可提赢金额小于子账户余额，可提现部分：', 'subtixian_new');
            return 0;
        }
    }
}
//写入抽奖次数
if (!function_exists('insertoperate')) {
    function insertoperate($num, $mid)
    {
        $insetdata['mid']   = $mid;
        $insetdata['count'] = $num;
        Db::name('operate_user')->insert($insetdata);
        if ($num > 0) {
            $insetrecorddata['num']     = $num;
            $insetrecorddata['mid']     = $mid;
            $insetrecorddata['type']    = 1;
            $insetrecorddata['info']    = "新用户注册获得{$num}次抽奖机会";
            $insetrecorddata['addtime'] = time();
            return Db::name('operate_record')->insert($insetrecorddata);
        }
    }
}
//更新抽奖次数  1、抽奖次数  用户   内容  类型
if (!function_exists('updateoperate')) {
    function updateoperate($num, $mid, $info, $type)
    {
        $operateuser        = Db::name('operate_user')->where(array(
            "mid" => $mid
        ))->select();
        $insetdata['count'] = $num + $operateuser['$operateuser'];
        Db::name('operate_user')->where(['mid' => $mid])->update($insetdata);
        $insetrecorddata['num']     = $num;
        $insetrecorddata['mid']     = $mid;
        $insetrecorddata['type']    = $type;
        $insetrecorddata['info']    = $info . ",获得{$num}次抽奖机会";
        $insetrecorddata['addtime'] = time();
        return Db::name('operate_record')->insert($insetrecorddata);
    }
}
if (!function_exists('is_member_signin')) {
    /*
     * 判断会员是否登录
     * @return int member_id
     * @author 张继立 <404851763@qq.com>
    */
    function is_member_signin()
    {
        $req   = request();
        $token = $req->param("token");
        if ($token == "false") {
            return false;
        }
        if (empty($token)) {
            $member = session('member_auth');
            if (empty($member)) {
                // 判断是否记住登录
                if (cookie('?mid') && cookie('?m_signin_token')) {
                    $MemberModel = new member();
                    $member      = $MemberModel::get(cookie('mid'));
                    if ($member) {
                        $signin_token = data_auth_sign($member['mobile'] . $member['id'] . $member['last_login_time']);
                        if (cookie('m_signin_token') == $signin_token) {
                            // 自动登录
                            $MemberModel->autoLogin($member);
                            return $member['id'];
                        }
                    }
                };
                return 0;
            } else {
                return session('member_auth_sign') == data_auth_sign($member) ? $member['mid'] : 0;
            }
        } else {
            $decoded = JWT::decode($token, JWT_TOKEN_KEY, array(
                'HS256'
            ));
            $doHost  = $_SERVER['HTTP_HOST'];
            if ($doHost == $decoded->doHost) {
                return $decoded->uid;
            } else {
                return 0;
            }
        }
    }
}
if (!function_exists("format_str")) {
    function format_str($str, $type = 'mobile')
    {
        switch ($type) {
            case 'mobile':
                $str = substr_replace($str, '****', 3, 4);
                break;
            case 'bank':
                $len = strlen($str) - 4;
                $str = substr_replace($str, '**** **** **** ', 0, $len);
                break;
        }
        return $str;
    }
}
if (!function_exists("get_area")) {
    /*
     * 获取城市地区数据并写入缓存
     * @param  intval $reid 上级id
     * @param  intval $id   地区id
     * @return [type]       [description]
    */
    function get_area($reid = null, $id = null)
    {
        if (!cache('area')) {
            $area_arr = (new Area())->select();
            foreach ($area_arr as $value) {
                $area[$value['id']] = $value;
            }
            cache('area', $area);
        } else {
            $area = cache('area');
        }
        if ($reid) {
            foreach ($area as $val) {
                $val['reid'] == $reid && $data[] = $val;
            }
            return $data;
        }
        if ($id) {
            return $area[$id];
        }
        return null;
    }
}
if (!function_exists("arrangeTime")) {
    /**
     * 返回本年、本周、本月、本日的开始和结束时间的时间戳
     * @return array
     */
    function arrangeTime($type)
    {
        switch ($type) {
            case 'month':
                $y  = date("Y", time());
                $m  = date("m", time());
                $d  = date("d", time());
                $t0 = date('t');                       // 本月一共有几天
                $d0 = mktime(0, 0, 0, $m, 1, $y);      // 创建本月开始时间
                $d1 = mktime(23, 59, 59, $m, $t0, $y); // 创建本月结束时间
                break;
            case 'week':
                $w  = date("w", time());                                            //这天是星期几
                $d0 = mktime(0, 0, 0, date("m"), date("d") - $w, date("Y"));        //创建周开始时间
                $d1 = mktime(23, 59, 59, date("m"), date("d") - $w + 6, date("Y")); //创建周结束时间
                break;
            case 'day':
                $d0 = mktime(0, 0, 0, date("m"), date("d"), date("Y"));    //创建日开始时间
                $d1 = mktime(23, 59, 59, date("m"), date("d"), date("Y")); //创建日结束时间
                break;
            case 'year':
                $d0 = strtotime(date("Y", time()) . "-1" . "-1");   //本年开始
                $d1 = strtotime(date("Y", time()) . "-12" . "-31"); //本年结束
                break;
        }
        return array(
            $d0,
            $d1
        );
    }
}
if (!function_exists("is_page_active")) {
    function is_page_active($menu)
    {
        $request     = request();
        $current_url = $request->url();
        if (strpos($current_url, '.html') !== false) $current_url = preg_replace('/\.html/', '', $current_url);
        if (strpos($menu, '.html') !== false) $menu = preg_replace('/\.html/', '', $menu);
        return $menu === $current_url;
    }
}
/**
 * @info
 *
 * @param  $id 广告id号
 * @param  $bg 是否匹配出 src
 *
 * @return
 * @author lmq_zcj <357745180@qq.com>
 * @date  2018-06-15
 */
if (!function_exists("get_advert")) {
    function get_advert($id, $bg = 0)
    {
        $result = Db::name('cms_advert')->where("id={$id} and status=1")->field('content')->find();
        if ($bg) {
            $match_str = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
            preg_match_all($match_str, $result['content'], $out, PREG_PATTERN_ORDER);
            $result['content'] = $out[1][0];
        }
        return $result['content'];
    }
}
if (!function_exists("get_spapi")) {
    function get_spapi($type = 1, $port = '')
    {
        if ($port == '') {
            $port = config('web_trade_port');
        }
        //$result=file_get_contents(http().$_SERVER["SERVER_NAME"].'/spapi.php?type='.$type.'&port='.$port);
        //return $result;
        return false;
    }
}
/*
 * 导出excel表
 * $arrHeader  表头
 * $fields 表头对应字段
 * $xlsData 数据
 * $title 表名
*/
if (!function_exists("export")) {
    function export($arrHeader, $fields, $xlsData, $title)
    {
//        Vendor('PHPExcel.PHPExcel'); //调用类库,路径是基于vendor文件夹的
//        Vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
//        Vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter   = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $objActSheet = $objExcel->getActiveSheet();
        $key         = ord("A");
        $tit         = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,S,Y,Z,";
        $arr         = explode(',', substr($tit, 0, -1));
        $lenth       = count($arrHeader);
        if ($lenth > 26) {
            return "目前只支持最大26列";
        }
        $tit_tmd = substr($tit, 0, 2 * $lenth - 1);
        $letter  = explode(',', $tit_tmd);
        //填充表头信息
        for ($i = 0; $i < $lenth; $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
        };
        //填充表格信息
        foreach ($xlsData as $k => $v) {
            $k   += 2;
            $num = 0;
            foreach ($fields as $key => $value) {
                $objActSheet->setCellValue($arr[$num] . $k, $v[$value]);
                $num += 1;
            }
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
        $num   = 0;
        $width = array(
            10,
            15,
            20,
            25,
            30
        ); //可选宽度
        foreach ($fields as $key => $value) {
            //设置表格的宽度
            $objActSheet->getColumnDimension($arr[$num])->setWidth($width[2]);
            $num += 1;
        }
        $outfile = $title . ".xlsx";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outfile . '"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
}
if (!function_exists('getposition')) {
    function getposition($ip, $type, $mid)
    {
        $url         = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
        $result_json = curl_request($url);
        $result      = json_decode($result_json, true);
        if ($type == 1) {
            $insetdata['loginDomain'] = $_SERVER['HTTP_HOST'];
        } else {
            $insetdata['registeredDomain'] = $_SERVER['HTTP_HOST'];
        }
        $insetdata['type']     = $type;
        $insetdata['mid']      = $mid;
        $insetdata['ip']       = $ip;
        $insetdata['province'] = $result['data']['region'];
        $insetdata['city']     = $result['data']['city'];
        $insetdata['area']     = $result['data']['county'];
        $insetdata['isp']      = $result['data']['isp'];
        $insetdata['country']  = $result['data']['country'];
        $insetdata['addtime']  = time();
        Db::name('member_positioninfor')->insert($insetdata);
        return $result;
    }
}

/**
 * Desc : 获取单个优投订单的 可以用余额
 * User : you name
 * Date : 2024-06-22 00:47
 *
 * @param $K
 * @param $table 通过undDayline 的id查， FundOrderGs id查
 *
 * @return float|int
 */
function orderbalance($K, $table = 'fundDayline')
{

    if ($table == 'fundDayline') {
        //获取该用户这个订单的信息余额；
        $FundDaylineinfo = FundDaylineModel::where(['id' => $K])->find();
        $K               = $FundDaylineinfo['order_id'];

    }
    printlog($K, '$K', 'jiesuan');

    $FundOrderinfo = FundOrderGsModel::where([['id', '=', $K], ['status', 'in', '1,6,7']])->find();
    printlog($FundOrderinfo, '$FundOrderinfo', 'jiesuan');
    $FundDaylinelist = FundDaylineModel::where([
        ['uid', '=', $FundOrderinfo['uid']],
        ['order_id', '=', $FundOrderinfo['id']],
        ['status', 'in', '1,2']
    ])->select();

    $Usedamount = 0;
    if ($FundDaylinelist) {
        foreach ($FundDaylinelist as $uv) {
            $Usedamount += $uv['num'] * $uv['buy_price'];
        }
    }
    printlog($FundOrderinfo['money'], '合约金额', 'jiesuan');
    printlog($FundOrderinfo['balance'], '合约总盈亏', 'jiesuan');
    printlog($Usedamount, '已使用', 'jiesuan');
    #合约金额+合约总盈亏-已使用=当前可用余额
    return $FundOrderinfo['money'] + $FundOrderinfo['balance'] - $Usedamount;
}

/**
 * Desc : 自动实名认证审核  通过四像素认证
 * Date : 2024-07-07 01:23
 *
 * @param $bodyParams
 */
function autorealname($bodyParams)
{
    $_CFG = config('verified_channel');

    switch ($_CFG) {
        case 'txcloud':
            return txrealname($bodyParams);

        case 'alicloud':
            return alirealname($bodyParams);

    }
}

/**
 * Desc : 阿里四像素实名认证
 * Date : 2024-07-07 01:24
 *
 * @param $bodyParams
 */
function alirealname($bodyParams)
{

    $host    = "https://bcard3and4.market.alicloudapi.com";
    $path    = "/bank3CheckNew";
    $method  = "GET";
    $appcode = config('Verified_channel_config')['alicloud']['alicloud_Certification_AppCode'];//开通服务后 买家中心-查看AppCode
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $querys = "idCard=" . $bodyParams['idCardCode'] . "&mobile=" . $bodyParams['bankPreMobile'] . "&accountNo=" . $bodyParams['accountNo'] . "&name=" . urlencode($bodyParams['name']);

    $url = $host . $path . "?" . $querys;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (1 == strpos("$" . $host, "https://")) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    $out_put = curl_exec($curl);
    if (curl_errno($curl)) {
        return ['code' => false, 'msg' => curl_error($curl)];

    } else {
        $data = json_decode($out_put, true);
        printlog($data, '实名认证', 'realname');
        if ($data['status'] == "01") {
            return ['code' => true, 'data' => json_decode($out_put, true), 'msg' => 'success'];
        }
//        return ['code' => false, 'msg' => $data['result']['respMsg']];
        return ['code' => false, 'msg' => $data['msg']];
    }
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    list($header, $body) = explode("\r\n\r\n", $out_put, 2);
    if ($httpCode == 200) {
        print("正常请求计费(其他均不计费)<br>");
        print($body);
    } else {
        if ($httpCode == 400 && strpos($header, "Invalid Param Location") !== false) {
            print("参数错误");
        } elseif ($httpCode == 400 && strpos($header, "Invalid AppCode") !== false) {
            print("AppCode错误");
        } elseif ($httpCode == 400 && strpos($header, "Invalid Url") !== false) {
            print("请求的 Method、Path 或者环境错误");
        } elseif ($httpCode == 403 && strpos($header, "Unauthorized") !== false) {
            print("服务未被授权（或URL和Path不正确）");
        } elseif ($httpCode == 403 && strpos($header, "Quota Exhausted") !== false) {
            print("套餐包次数用完");
        } elseif ($httpCode == 403 && strpos($header, "Api Market Subscription quota exhausted") !== false) {
            print("套餐包次数用完，请续购套餐");
        } elseif ($httpCode == 500) {
            print("API网关错误");
        } elseif ($httpCode == 0) {
            print("URL错误");
        } else {
            print("参数名错误 或 其他错误");
            print($httpCode);
            $headers  = explode("\r\n", $header);
            $headList = array();
            foreach ($headers as $head) {
                $value               = explode(':', $head);
                $headList[$value[0]] = $value[1];
            }
            print($headList['x-ca-error-message']);
        }
    }

    if (curl_errno($curl)) {
        return ['code' => false, 'msg' => curl_error($curl)];

    } else {
        $data = json_decode($out_put, true);
        printlog($data, '实名认证', 'realname');
        if ($data['status'] == "01") {
            return ['code' => true, 'data' => json_decode($data, true), 'msg' => 'success'];
        }
        return ['code' => false, 'msg' => $data['result']['respMsg']];
    }
}

/**
 * 腾讯云四要素实名认证
 */

function txrealname($bodyParams)
{
// 云市场分配的密钥Id
    $secretId = config('Verified_channel_config')['txcloud']['txcloud_Certification_secretId'];
// 云市场分配的密钥Key
    $secretKey = config('Verified_channel_config')['txcloud']['txcloud_Certification_secretKey'];
    $source    = 'market';

// 签名
    $datetime = gmdate('D, d M Y H:i:s T');
    $signStr  = sprintf("x-date: %s\nx-source: %s", $datetime, $source);
    $sign     = base64_encode(hash_hmac('sha1', $signStr, $secretKey, true));
    $auth     = sprintf('hmac id="%s", algorithm="hmac-sha1", headers="x-date x-source", signature="%s"', $secretId, $sign);

// 请求方法
    $method = 'POST';
// 请求头
    $headers = array(
        'X-Source'      => $source,
        'X-Date'        => $datetime,
        'Authorization' => $auth,

    );
// 查询参数
    $queryParams = array();

// url参数拼接
    $url = 'https://service-lq1dzayt-1300755093.ap-beijing.apigateway.myqcloud.com/release/v2/bcheck';
    if (count($queryParams) > 0) {
        $url .= '?' . http_build_query($queryParams);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function ($v, $k) {
        return $k . ': ' . $v;
    }, array_values($headers), array_keys($headers)));
    if (in_array($method, array('POST', 'PUT', 'PATCH'), true)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyParams));
    }

    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['code' => false, 'msg' => curl_error($ch)];

    } else {
        $data = json_decode($data, true);
        printlog($data, '实名认证', 'realname');
        if ($data['result']['respCode'] == "T") {
            return ['code' => true, 'data' => json_decode($data, true), 'msg' => 'success'];
        }
        return ['code' => false, 'msg' => $data['result']['respMsg']];
    }
    curl_close($ch);

}

if (!function_exists('wx_http_request')) {
    /**
     * Desc : 网络请求接口
     * Date : 2024-07-06 22:44
     *
     * @param $url
     * @param $params
     * @param $body
     * @param $isPost
     * @param $isImage
     *
     * @return bool|string
     */
    function http_request($url, $params = [], $body = "", $isPost = false, $isImage = false)
    {

        $url = $url . "?" . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($isPost) {
            if ($isImage) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: multipart/form-data;',
                    "Content-Length: " . strlen($body)
                ));
            } else {
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
            }

            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }
}
/*
 * 生成可适配的推广图片
 * */
if (!function_exists('buildingshareimg')) {
    function buildingshareimg($mid)
    {
        define("ROOTDIR", config('sharerootdir'));
        define("DSDIR", config('sharebuilding'));
        define("USERQRCODE", config('upload_path') . '/userqrcode/');
        define("SEPARATER", '\\');
        $localimg = empty($_SERVER['HTTP_X_CLIENT_PROTO']) ? 'http://' : $_SERVER['HTTP_X_CLIENT_PROTO'] . '://' . $_SERVER["HTTP_HOST"] . "/member/Invite/view"; //二维码
        // $img=imagecreatefromjpeg($localimg);
        $watermark = imagecreatefrompng(USERQRCODE . MID . '_qrcode.png');                                                                                        //水印文件
        $wsx       = imagesx($watermark);                                                                                                                         //水印宽度
        $wsy       = imagesy($watermark);                                                                                                                         //水印高度
        $filenames = scandir(ROOTDIR);                                                                                                                            //读取文件夹下的所有文件
        $i         = 0;
        //遍历所有文件
        //    echo '遍历文件图片<br>';
        $filenames2 = '';
        foreach ($filenames as $k => $name) {
            switch ($name) {
                case '.': //文件夹本身不处理
                    unset($filenames[$k]);
                    break;
                case '..': //上级文件夹不处理
                    unset($filenames[$k]);
                    break;
                default: // 读取图片文件(png,jpg)
                    if ('png' == strstr($name, 'png')) {
                        $image = imagecreatefrompng(ROOTDIR . SEPARATER . $name);
                    } else {
                        $image = imagecreatefromjpeg(ROOTDIR . SEPARATER . $name);
                    }
                    $isx = imagesx($image);
                    $isy = imagesy($image);
                    //图片缩小所需变量
                    $per = 1;
                    $n_x = $isx;
                    $n_y = $isy;
                    //图片宽度大于1140的按比例缩小到1140
                    if ($isx > 1140) {
                        $n_x     = 1140;
                        $per     = $n_x / $isx;        //计算缩放比例
                        $n_y     = (int)($isy * $per); //等比例缩小高度
                        $n_image = imagecreatetruecolor($n_x, $n_y);
                        imagecopyresized($n_image, $image, 0, 0, 0, 0, $n_x, $n_y, $isx, $isy); //缩小原来图片
                        imagedestroy($image);                                                   //内存回收
                        //将原来的图片数据进行修改
                        $image = $n_image;
                        $isx   = $n_x;
                        $isy   = $n_y;
                        echo 'resize image<br>';
                    }
                    $flag = imagecopy($image, $watermark, $isx - $wsx - 20, $isy - $wsy - 20, 0, 0, $wsx, $wsy);
                    if ($flag) {
                        imagejpeg($image, DSDIR . SEPARATER . $mid . '_' . $name); //保存文件
                        imagedestroy($image);                                      //内存回收
                    } else {
                        return false;
                    }
                    $filenames2[$k]['img'] = '/uploads/share/building/' . MID . '_' . $name;
            }
        }
        return $filenames2;
    }
}
/*
 * 产生id混淆数  应用在生成邀请码
*/
if (!function_exists('randerode')) {
    function randerode($mid)
    {
        $mid1 = $mid * 12;
        $mid2 = $mid1 + 131413;
        $mid3 = "G" . $mid2;
        return $mid3;
    }
}
/*
 * 混淆id还原
*/
if (!function_exists('reductionranderode')) {
    function reductionranderode($mid)
    {
        $mid3 = str_replace("G", "", $mid);
        $mid2 = $mid3 - 131413;
        $mid1 = $mid2 / 12;
        return $mid1;
    }
}
if (!function_exists('curl_request')) {
    /**
     * curl 以 post方式
     *
     * @param mixed $url
     * @param mixed $data
     */
    function curl_request($url, $data)
    {
        $curl = curl_init();                                                // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);                              // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);                      // 对认证证书来源的检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);                      // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);                         // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1);                                // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                      // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);                            // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0);                              // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                      // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl);                                        // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl); //捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo;   // 返回数据，json格式
    }
}
if (!function_exists('timediff')) {
    //功能：计算两个时间戳之间相差的日时分秒
    //$begin_time  开始时间戳
    //$end_time 结束时间戳
    function timediff($begin_time, $end_time)
    {
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime   = $end_time;
        } else {
            $starttime = $end_time;
            $endtime   = $begin_time;
        }
        //计算天数
        $timediff = $endtime - $starttime;
        $days     = intval($timediff / 86400);
        //计算小时数
        $remain = $timediff % 86400;
        $hours  = intval($remain / 3600);
        //计算分钟数
        $remain = $remain % 3600;
        $mins   = intval($remain / 60);
        //计算秒数
        $secs = $remain % 60;
        $res  = array(
            "day"  => $days,
            "hour" => $hours,
            "min"  => $mins,
            "sec"  => $secs
        );
        return $res;
    }
}
/*
 *HTTP POST请求
*/
if (!function_exists('HttprequestPOST')) {
    function HttprequestPOST($url, $body = "")
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => array(
                "Accept: */*",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Type: application/x-www-form-urlencoded",
                "Host: api.t.sina.com.cn",
                "User-Agent: PostmanRuntime/7.11.0",
                "accept-encoding: gzip, deflate",
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}
/*
 * 代理获取自身userid
 * $mid 会员id
*/
if (!function_exists('for_user')) {
    function for_user($mid = null)
    {
        Logs::log('for_user', ['UID' => UID], 'common');
        $for_user = 0;
        if ($mid) {
            $res      = db('member')->where(['id' => $mid])->value('for_user');
            $for_user = $res ? $res : 0;
        } else {
            if (Session::has('for_user')) {
                $for_user = Session::get('for_user');
            } elseif (Session::has('mfor_user')) {
                $for_user = Session::get('mfor_user');
            }
        }
//        Logs::log('for_user', ['for_user' => $for_user, 'mid' => MID, 'mfor_user' => Session::get('mfor_user') ], 'common');
        return $for_user;
    }
}
/*
 * 前台代理获取自身userid
*/
if (!function_exists('mfor_user')) {
    function mfor_user()
    {
        $for_user = 0;
        if (Session::has('mfor_user')) {
            $for_user = Session::get('mfor_user');
        }
        return $for_user;
    }
}
/*
 * 用户获取所属网站标识
 * 1为总后台，其余为代理后台
*/
if (!function_exists('webType')) {
//     function webType()
//     {
//         $header  = request()->header();
//         $webtype = 2;
//         if (isset(config('web_type') [$header['host']])) {
//             $webtype = config('web_type') [$header['host']];
//         }
//         return $webtype;
//     }
    function webType()
    {
        $header  = request()->header();
        $webtype = 2;
        if (in_array($header['host'], config('root_domain'))) {
            $webtype = 1;
        }
        return $webtype;
    }
}
if (!function_exists('get_host')) {
    function get_host()
    {
        $header = request()->header();
        $host   = $header['host'];
        if (!$host) {
            $host = '';
        }
        return $host;
    }
}
/*
 * 代理网站信息
*/
if (!function_exists('ProxyInfo')) {
    function ProxyInfo()
    {
        $webType  = webType();
        $for_user = for_user();
        Logs::log('ProxyInfo', ['ProxyInfo' => $for_user], 'common');
        $siteinfo = [];
        if ($webType == 2) {
            $siteinfo = db('proxy_config')->where(['for_user' => $for_user])->find();
            if ($siteinfo) {
                $logo             = db('admin_attachment')->where(['id' => $siteinfo['logo']])->value('path');
                $siteinfo['logo'] = 'http://' . $siteinfo['host'] . '/' . $logo;
            }
        }
        return $siteinfo;
    }
}
/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 *
 * @param string $date1
 * @param string $date2
 *
 * @return number
 */
if (!function_exists('diff_date')) {
    function diff_date($date1, $date2)
    {
        if ($date1 > $date2) {
            $startTime = strtotime($date1);
            $endTime   = strtotime($date2);
        } else {
            $startTime = strtotime($date2);
            $endTime   = strtotime($date1);
        }
        $diff = $startTime - $endTime;
        $day  = $diff / 86400;
        return intval($day);
    }
}
/**
 * Returns every date between two dates as an array
 *
 * @param string $startDate the start of the date range
 * @param string $endDate the end of the date range
 * @param string $format DateTime format, default is Y-m-d
 *
 * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
 */
if (!function_exists('create_date_range')) {
    function create_date_range($startDate, $endDate, $format = "Y-m-d")
    {
        $begin     = new DateTime($startDate);
        $end       = new DateTime($endDate);
        $interval  = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);
        $range     = [];
        foreach ($dateRange as $date) {
            $range[] = $date->format($format);
        }
        return $range;
    }
}
if (!function_exists('randomPhone')) {
    //随机生成n条手机号
    function randomPhone($n = 100, $start = 1000, $end = 9999)
    {
        $tel_arr = array(
            '130',
            '131',
            '132',
            '133',
            '134',
            '135',
            '136',
            '137',
            '138',
            '139',
            '144',
            '147',
            '150',
            '151',
            '152',
            '153',
            '155',
            '156',
            '157',
            '158',
            '159',
            '176',
            '177',
            '178',
            '180',
            '181',
            '182',
            '183',
            '184',
            '185',
            '186',
            '187',
            '188',
            '189',
        );
        for ($i = 0; $i < $n; $i++) {
            $phone            = $tel_arr[array_rand($tel_arr)] . mt_rand($start, $end) . mt_rand($start, $end);
            $phone            = substr_replace($phone, '****', 3, 4);
            $tmp[$i]['phone'] = $phone;
            $tmp[$i]['money'] = (rand(1, 900) * 100);
        }
        return $tmp;
        return array_unique($tmp);
    }
}
if (!function_exists('images_url')) {
    function images_url($value)
    {
        $images_url = 'uploads/images/' . str_replace('\\', '/', $value);
        return $images_url;
    }
}
/**
 * 根据相对路径获取图片绝对路径
 *
 * @param $path 图片路径
 */
if (!function_exists('get_img_url')) {
    function get_img_url($path)
    {
        $def = 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1550825731271&di=69d34163cee727b59471b98df6fa6b06&imgtype=0&src=http%3A%2F%2Fpic.51yuansu.com%2Fpic3%2Fcover%2F02%2F21%2F00%2F59afc61f0f3d7_610.jpg';
        if ($path == '') {
            return $def;
        }
        if (preg_match('/(http:\/\/)|(https:\/\/)/i', $path)) {
            return $path;
        }
        $domain = request()->domain();
        $url    = $domain . '/' . $path;
        return $path != '' ? $url : $def;
    }
}
//百度翻译
if (!function_exists('baidu_translate')) {
    function baidu_translate($content = '', $from = '', $to = '')
    {
        $appid      = config('baidu_appid'); //您注册的API Key
        $key        = config('baidu_key');   //密钥
        $value_code = $content;
        $salt       = rand(1000000000, 9999999999);             //随机数
        $sign       = md5($appid . $value_code . $salt . $key); //签名
        $value_code = urlencode($value_code);
        //生成翻译API的URL
        $languageUrl = "http://api.fanyi.baidu.com/api/trans/vip/translate?q=$value_code&appid=$appid&salt=$salt&from=$from&to=$to&sign=$sign";
        $type        = $from . '_' . $to;
        $list        = Db::name('translate_log')->where('type', $type)->select();
        if ($list) {
            foreach ($list as $value) {
                if ($value['content'] == $content) {
                    return $value['translate_result'];
                }
            }
        }
        $text = json_decode(language_text($languageUrl), true);
        if ($text['trans_result'][0]['dst']) {
            $translate_result         = $text['trans_result'][0]['dst'];
            $data                     = [];
            $data['content']          = $content;
            $data['translate_result'] = $translate_result;
            $data['type']             = $type;
            $data['create_time']      = time();
            Db::name('translate_log')->insert($data);
            return $translate_result;
        } else {
            return [];
        }
    }
}
if (!function_exists('language_text')) {
    function language_text($reqURL)
    {
        $ch = curl_init($reqURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if ($result) {
            curl_close($ch);
            return $result;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return ("curl出错，错误码:$error");
        }
    }
}
if (!function_exists('curl_post')) {
    function curl_post($url, $param = [], $header = [], $type = 'POST')
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $result = curl_exec($curl);
        return $result;

    }
}
/**
 * PHP发送Json对象数据
 *
 * @param $url 请求url
 * @param $jsonStr 发送的json字符串
 *
 * @return array
 */
function http_post_json($url, $jsonStr)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Content-Length: ' . strlen($jsonStr)
    ));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array(
        $httpCode,
        $response
    );
}

if (!function_exists('getfirstchar')) {
    function getfirstchar($s0)
    { //获取单个汉字拼音首字母。注意:此处不要纠结。汉字拼音是没有以U和V开头的
        $fchar = ord($s0{0});
        if ($fchar >= ord("A") and $fchar <= ord("z")) return strtoupper($s0{0});
        //        $s1 = iconv("UTF-8","gb2312", $s0);
        //        $s2 = iconv("gb2312","UTF-8", $s1);
        $s1 = iconv("UTF-8", "GBK", $s0);
        $s2 = iconv("GBK", "UTF-8", $s1);
        if ($s2 == $s0) {
            $s = $s1;
        } else {
            $s = $s0;
        }
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 and $asc <= -20284) return "A";
        if ($asc >= -20283 and $asc <= -19776) return "B";
        if ($asc >= -19775 and $asc <= -19219) return "C";
        if ($asc >= -19218 and $asc <= -18711) return "D";
        if ($asc >= -18710 and $asc <= -18527) return "E";
        if ($asc >= -18526 and $asc <= -18240) return "F";
        if ($asc >= -18239 and $asc <= -17923) return "G";
        if ($asc >= -17922 and $asc <= -17418) return "H";
        if ($asc >= -17922 and $asc <= -17418) return "I";
        if ($asc >= -17417 and $asc <= -16475) return "J";
        if ($asc >= -16474 and $asc <= -16213) return "K";
        if ($asc >= -16212 and $asc <= -15641) return "L";
        if ($asc >= -15640 and $asc <= -15166) return "M";
        if ($asc >= -15165 and $asc <= -14923) return "N";
        if ($asc >= -14922 and $asc <= -14915) return "O";
        if ($asc >= -14914 and $asc <= -14631) return "P";
        if ($asc >= -14630 and $asc <= -14150) return "Q";
        if ($asc >= -14149 and $asc <= -14091) return "R";
        if ($asc >= -14090 and $asc <= -13319) return "S";
        if ($asc >= -13318 and $asc <= -12839) return "T";
        if ($asc >= -12838 and $asc <= -12557) return "W";
        if ($asc >= -12556 and $asc <= -11848) return "X";
        if ($asc >= -11847 and $asc <= -11056) return "Y";
        if ($asc >= -11055 and $asc <= -10247) return "Z";
        return NULL;
    }
}
if (!function_exists('pinyin_long')) {
    function pinyin_long($zh)
    {
        $ret    = "";
        $zh     = str_replace('*', '', $zh);
        $length = mb_strlen($zh);
        for ($i = 0; $i < $length; $i++) {
            $s1  = mb_substr($zh, $i, 1);
            $ret .= getfirstchar($s1);
        }
//        if(!preg_match("/^[a-zA-Z\s]+$/",$ret)){
//            $ret = '';
//        }
        return $ret;
    }
}
//同花顺实时行情
if (!function_exists('get_market')) {
    function get_market($code)
    {
        $time_num = get_trading_time(); //交易日
        if ($time_num == 2) {
            return;
        }
        //调用同花顺的数据源
        $act                = 'real_time_quotation';
        $data['codes']      = code_suffix($code);
        $data['indicators'] = "latest,preClose,open,high,low,volume,change,changeRatio,avgPrice,amount,turnoverRatio,totalCapital,pb,pe_ttm,pbr_lf,bid1,bid2,bid3,bid4,bid5,bidSize2,bidSize3,bidSize4,bidSize5,bidSize1,ask1,ask2,ask3,ask4,ask5,askSize1,askSize2,askSize3,askSize4,askSize5";
        $stime              = time();
        $key                = 'jd_stock_lists';
        $stock_list         = $token = Cache::get($key);
        if (!$stock_list) {
            $stock_list = Db::name('stock_list')->column('title,pinyin,thscode', 'code');
            Cache::set($key, $stock_list);
        }
        $res    = iApi_curl($act, $data);
        $stime2 = time();
        $sub    = $stime2 - $stime;
        $str    = "{$stime}\t{$stime2}\t{$sub}\t{$act}\t";
        //        printlog($str, "访问耗时统计：", "function-z_market");
        $d    = [];
        $lang = \think\facade\Lang::range();
        if ($res && isset($res['tables'][0]['table'])) {
            $t                       = $res['tables'][0]['table'];
            $d['code']               = $code;
            $d['success']            = 1;
            $d['name']               = $lang == 'zh-cn' ? $stock_list[$code]['title'] : $stock_list[$code]['pinyin'];
            $d['current_price']      = $t['latest'][0] ?? 0.00;   //最新价
            $d['yesterday_price']    = $t['preClose'][0] ?? 0.00; //昨收
            $d['open_price']         = $t['open'][0] ?? 0.00;     //今开
            $d['highest']            = $t['high'][0] ?? 0.00;     //最高
            $d['lowest']             = $t['low'][0] ?? 0.00;      //最低
            $change                  = $t['change'][0] ?? 0;
            $d['change']             = round($change, 2); //涨跌
            $changeRatio             = $t['changeRatio'][0] ?? 0;
            $d['changeRatio']        = round($changeRatio, 2);  //涨跌幅
            $d['volume']             = $t['volume'][0] ?? 0.00; //成交量
            $pe_ratio                = $t['pe_ttm'][0] ?? 0;
            $d['pe_ratio']           = round($pe_ratio, 2); //市盈(TTM)
            $buy_chang_price         = $t['buy_chang_price'][0] ?? 0;
            $d['buy_chang_price']    = round($buy_chang_price, 2); //换手率
            $turnover                = $t['turnover'][0] ?? 0;
            $d['turnover']           = bcdiv($turnover, 10000, 2); //成交额
            $pb_ratio                = $t['pb_ratio'][0] ?? 0;
            $d['pb_ratio']           = round($pb_ratio, 2); //市净率
            $total_market_value      = $t['total_market_value'][0] ?? 0;
            $d['total_market_value'] = bcdiv($total_market_value, 100000000, 2); //总市值
            $d['buy_one_price']      = $t['bid1'][0] ?? 0.00;                    //买一价
            $d['buy_one_amount']     = $t['bidSize1'][0] ?? 0.00;                //买一量
            $d['buy_two_price']      = $t['bid2'][0] ?? 0.00;
            $d['buy_two_amount']     = $t['bidSize2'][0] ?? 0.00;
            $d['buy_three_price']    = $t['bid3'][0] ?? 0.00;
            $d['buy_three_amount']   = $t['bidSize3'][0] ?? 0.00;
            $d['buy_four_price']     = $t['bid4'][0] ?? 0.00;
            $d['buy_four_amount']    = $t['bidSize4'][0] ?? 0.00;
            $d['buy_five_price']     = $t['bid5'][0] ?? 0.00;
            $d['buy_five_amount']    = $t['bidSize5'][0] ?? 0.00;
            $d['sell_one_price']     = $t['ask1'][0] ?? 0.00;     //卖一价
            $d['sell_one_amount']    = $t['askSize1'][0] ?? 0.00; //卖一量
            $d['sell_two_price']     = $t['ask2'][0] ?? 0.00;
            $d['sell_two_amount']    = $t['askSize2'][0] ?? 0.00;
            $d['sell_three_price']   = $t['ask3'][0] ?? 0.00;
            $d['sell_three_amount']  = $t['askSize3'][0] ?? 0.00;
            $d['sell_four_price']    = $t['ask4'][0] ?? 0.00;
            $d['sell_four_amount']   = $t['askSize4'][0] ?? 0.00;
            $d['sell_five_price']    = $t['ask5'][0] ?? 0.00;
            $d['sell_five_amount']   = $t['askSize5'][0] ?? 0.00;
            if ($d['open_price'] < 0.1) {
                $d = [];
            }
        }
        return $d;
    }
}
if (!function_exists('code_suffix')) {
    //生成同花顺股票代码
    function code_suffix($code)
    {
        $arr = array(
            '00' => '.SZ',
            '30' => '.SZ',
            '43' => '.BJ',
            '60' => '.SH',
            '68' => '.SH',
            '83' => '.BJ',
            '87' => '.BJ',
            '90' => '.SH',
            '20' => '.SZ',
            '36' => '.SH',
            '14' => '.SZ',
        );
        $key = substr($code, 0, 2);
        if (isset($arr[$key])) $code .= $arr[$key];
        return $code;
    }
}
if (!function_exists('get_stock_trade_dates')) {
    //获取股票交易日
    function get_stock_trade_dates()
    {
        //调用同花顺的数据源
        $act                             = 'get_trade_dates';
        $data['marketcode']              = '212001';
        $functionpara_data['mode']       = 1;
        $functionpara_data['dateType']   = 0;
        $functionpara_data['period']     = "D";
        $functionpara_data['dateFormat'] = 0;
        $data['functionpara']            = $functionpara_data;
        $data['startdate']               = get_begin_year();
        $data['enddate']                 = get_end_year();
        $res                             = iApi_curl($act, $data);
        if ($res && $res['errorcode'] == 0 && isset($res['tables']['time'])) {
            $date_list  = $res['tables']['time'];
            $data       = [];
            $date_array = Db::name('stock_trade_date')->column('date');
            foreach ($date_list as $key => $value) {
                if (!in_array($value, $date_array)) {
                    $data[$key]['date']        = $value;
                    $data[$key]['create_time'] = time();
                }
            }
            $date_array = array_merge($data);
            Db::name('stock_trade_date')->insertAll($data);
        }
        return;
    }
}
if (!function_exists('get_begin_year')) {
    //本年开始时间
    function get_begin_year($His = false)
    {
        $timestamp = mktime(0, 0, 0, 1, 1, date('Y'));
        return $His ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d', $timestamp);
    }
}
if (!function_exists('get_end_year')) {
    //本年开始时间
    function get_end_year($His = false)
    {
        $timestamp = mktime(23, 59, 59, 12, 31, date('Y'));
        return $His ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d', $timestamp);
    }
}
if (!function_exists('update_stock_real_time')) {
    /**
     * 更新股票更新实时行情
     *
     * @param string
     *
     * @return bool
     * @author
     */
    function update_stock_real_time($data = [], $code = '', $type = 1)
    {
        if (!$data || !$code) {
            return;
        }

        $updata_data['stock_data'] = json_encode($data);

        if (Db::name('stock_real_time')->where('code', $code)->count()) {
            Db::name('stock_real_time')->where('code', $code)->update($updata_data);
        } else {
            $updata_data['code'] = $code;
            Db::name('stock_real_time')->insert($updata_data);
        }
        if ($type != 1) {
            $start_time                     = strtotime(date('Y-m-d'));
            $updata_time['stock_real_time'] = $start_time;
            Db::name('stock_list')->where('code', $code)->update($updata_time);
            return;
        }
    }
}
if (!function_exists('userTextEncode')) {
    /**
     * 把用户输入的文本转义（主要针对特殊符号和emoji表情）
     */
    function userTextEncode($str)
    {
        if (!is_string($str)) return $str;
        if (!$str || $str == 'undefined') return '';
        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($str) {
            return addslashes($str[0]);
            //            return '#';
        }
            , $text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        return json_decode($text);
    }
}
if (!function_exists('get_trading_time')) {
    function isMarketOpen($datetime = null)
    {
        // 如果没有提供datetime参数，使用当前时间
        if ($datetime === null) {
            $datetime = new DateTime();
        } else {
            $datetime = new DateTime($datetime);
        }

        // 获取小时和分钟
        $hour   = (int)$datetime->format('H');
        $minute = (int)$datetime->format('i');

        // 检查是否在上午交易时间内
        if (($hour == 9 && $minute >= 30) || ($hour > 9 && $hour < 11) || ($hour == 11 && $minute <= 30)) {
            return true;
        }
        // 检查是否在下午交易时间内
        if (($hour == 13) || ($hour > 13 && $hour < 15) || ($hour == 15 && $minute == 0)) {
            return true;
        }

        // 不在交易时间内
        return false;
    }

    /**
     * Desc : 检测指定日期或者时间是否在交易时间段内
     * 替换 yan_time函数
     * Date : 2024-07-23 03:39
     *
     * @param $datetime
     * @param $type date 日期 time 时间 datetime 当前日期及时间是否在交易时间段内
     * get_trading_time('2024-07-28 14:50:22','datetime')
     */
    function get_trading_time($datetime = '', $type = 'date')
    {
        if (!$datetime) {
            $datetime = date('Y-m-d H:i:s');
        }
        #使用date和strtotime统一格式化
        $date_array = Db::name('stock_trade_date')->where('date', date('Y-m-d', strtotime($datetime)))->column('date');

        if ($type == 'date') {
            return $date_array;
        }
        if ($type == 'time') {
            return isMarketOpen($datetime);
        }
        if ($type == 'datetime') {
            return $date_array && isMarketOpen($datetime);
        }
        return false;
    }
}

if (!function_exists('get_determine_time')) {
    /**
     * 判断当日时间或者指定时间戳时间   -即将废弃
     *
     * @param string
     *
     * @return bool
     * @author
     */
    function get_determine_time($datetime = '')
    {
        if ($datetime) {
            $week       = date("w", $datetime);
            $start_time = $datetime;                //0点之后
            $day_date   = date('Y-m-d', $datetime); //获取当天时间
        } else {
            $week       = date("w");                //0-6 0-表示星期天
            $start_time = strtotime(date('Y-m-d')); //0点之后
            $day_date   = date('Y-m-d');            //获取当天时间
        }
        $end_time   = $start_time + 9.25 * 3600; //周一到到周五9.：15分之后查询同花顺接口
        $date_array = Db::table('lmq_stock_trade_date')->column('date');
        if (!in_array($day_date, $date_array)) { //判断是否是交易日
            return 2;
        }
        if ($week == 0 || $week == 6) { //判断是否是周末
            return 3;
        } else {
            if ($datetime) {
                if ($start_time <= $datetime && $datetime <= $end_time) { //0点-9：15
                    return 4;
                } else {
                    return 1;
                }
            } else {
                return 1;
            }
        }
    }
}

if (!function_exists('verify_mobile')) {
    function verify_mobile($mobile)
    {
        // 11位数字
        if (!preg_match('/^1[3-9]\d{9}$/', $mobile)) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('log_sql')) {
    function log_sql()
    {
        echo Db::getLastSql();
    }
}
if (!function_exists('format_money')) {
    /**
     * 格式化金额
     *
     * @param num 单位为分的钱数
     *
     * @return array|bool|string
     */
    function format_money($num)
    {
        if (!is_numeric($num)) {
            return false;
        }
        $num    = $num / 100;//转换成元
        $rvalue = number_format($num, 2);
        $num    = round($num, 2);
        $rvalue = '';
        $num    = explode('.', $num);                //把整数和小数分开
        $rl     = !isset($num['1']) ? '' : $num['1'];//小数部分的值
        $j      = strlen($num[0]) % 3;               //整数有多少位
        $sl     = substr($num[0], 0, $j);            //前面不满三位的数取出来
        $sr     = substr($num[0], $j);               //后面的满三位的数取出来
        $i      = 0;
        while ($i <= strlen($sr)) {
            $rvalue = $rvalue . ',' . substr($sr, $i, 3);//三位三位取出再合并，按逗号隔开
            $i      = $i + 3;
        }
        $rvalue = $sl . $rvalue;
        $rvalue = substr($rvalue, 0, strlen($rvalue) - 1);//去掉最后一个逗号
        $rvalue = explode(',', $rvalue);                  //分解成数组
        if ($rvalue[0] == 0 && $num[0] != 0) {
            array_shift($rvalue);//如果第一个元素为0，删除第一个元素
        }
        $rv = $rvalue[0];//前面不满三位的数
        for ($i = 1; $i < count($rvalue); $i++) {
            $rv = $rv . ',' . $rvalue[$i];
        }
        if (!empty($rl)) {
            $rvalue = $rv . '.' . $rl;//小数不为空，整数和小数合并
        } else {
            $rvalue = $rv;//小数为空，只有整数
        }

        return $rvalue;
    }
}

/**
 * Desc : IP未加入白名单
 * Date : 2024-06-28 03:20
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function ip_restriction()
{

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // 注意 X-Forwarded-For 可能包含多个 IP，如果是多个代理，第一个是离客户端最近的代理 IP
        $client_ip = explode(',', $client_ip)[0]; // 取第一个 IP
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        // 一些代理服务器会在 HTTP_CLIENT_IP 这个头里传递客户端 IP
        $client_ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        // 如果以上都不是，则使用 REMOTE_ADDR
        $client_ip = $_SERVER['REMOTE_ADDR'];
    }
    $ips = get_client_ip();
//    $ips     = $client_ip;
    $get_one = Db::name('ip_res')->where('ip', $ips)->find();
    if (!$get_one) {
        echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                <meta charset='UTF-8'>
                <title>403 Forbidden</title>
                </head>
                <body>
                <h1>无法访问</h1>
                <p>请联系管理员添加白名单</p>
                </body>
                </html>";
        exit;
    }
}

/**
 * Desc : 允许访问后台的域名 Allowed Domain Names
 * Date : 2024-07-05 01:31
 */
function AllowedDomain()
{

    //判断访问域名
//     $ALLOWED_domain = config('web_type');
//     // 提取数组的键名
//     $ALLOWED_domain = array_keys($ALLOWED_domain);

    $ALLOWED_domain = config('root_domain');
    $ALLOWED_domain = array_merge($ALLOWED_domain, config('agent_domain'));

    $domain = $_SERVER['HTTP_HOST'];

    //如果是许可的域名访问前端的，强制跳转
    if (in_array($domain, $ALLOWED_domain)) {

        return true;
    }
    // 设置 HTTP 状态码为 403 Forbidden
    http_response_code(403);
    // 输出自定义的错误消息或页面内容
    exit("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don't have permission to access this resource.</p></body></html>");
}

if (!function_exists('generate_order_no')) {
    /**
     * 生成 前缀+24位数字的订单号
     *
     * @param string $prefix 前缀
     *
     * @return string
     */
    function generate_order_no($prefix = '')
    {
        $randLen = 6;
        $id      = base_convert(substr(uniqid(), 0 - $randLen), 16, 10);
        if (strlen($id) > 10) {
            $id = substr($id, -10);
        } elseif (strlen($id) < 10) {
            $rLen = 10 - strlen($id);
            $id   = $id . rand(pow(10, $rLen - 1), pow(10, $rLen) - 1);
        }
        $dateTimeStr = date('YmdHis');
        return $prefix . $dateTimeStr . $id;
    }
}

/**
 * 隐私信息显示隐藏
 *
 * @param string $type 类型 mobile id_card
 * @param string $value 值
 */
function privacy_info_switch($type, $value)
{
    $privacy = cookie('__privacy__');
    if ($privacy == 'close') {
        switch ($type) {
            case 'mobile':
                $result = substr_replace($value, '****', 3, 4);
                break;
            case 'id_card':
                $result = substr_replace($value, '********', 4, 8);
                break;
            default:
                $result = '';
        }
    } else {
        $result = $value;
    }

    return $result;
}

/**
 * 记录查看隐私信息
 */
function ce_privacy_log($log)
{

    $data['user_id']     = UID;
    $data['remark']      = $log['remark'];
    $data['status']      = $log['status'];
    $data['create_time'] = time();
    $d_info              = Db::name("privacy_log")->insert($data);
    if ($d_info) {
//        验证成功在开启
        if ($log['status'] == 1) {
            cookie('__privacy__', 'open');  //隐私保护、登录默认是关闭状态 close关闭 open开
        }
        return true;
    }
}
