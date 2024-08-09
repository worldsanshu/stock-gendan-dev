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
// 共用接口模块公共函数库
use app\apicom\model\JWT;
use think\Db;
use think\facade\Lang;
use app\money\model\Record;

/***
 ** ajax json 2018 05 29
 ***/
if (!function_exists('isLogin')) {
    function isLogin($token)
    {
        if (!empty($token)) {
            $decoded = JWT::decode($token, JWT_TOKEN_KEY, array('HS256'));
            $doHost = $_SERVER['HTTP_HOST'];
            if ($doHost == $decoded->doHost) {
                $uid = $decoded->uid;
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
/*
* 返回代理商信息
*/
if (!function_exists('get_agents_info')) {
    function get_agents_info($mid)
    {
        $user = Db::name('member')->field('id,agent_id,agent_pro,agent_far,agent_rate')->where('id', $mid)->find();
        return $user;
    }
}
/***
 ** ajax json 2018 05 29
 ***/
if (!function_exists('ajaxmsg')) {
    function ajaxmsg($msg = "", $type = 1, $data = '', $is_end = true)
    {
        $json['status'] = $type . '';
        if (is_array($msg)) {
            foreach ($msg as $key => $v) {
                $json[$key] = $v;
            }
        } elseif (!empty($msg)) {
            $json['message'] = $msg;
        }
        if ($data) $json['data'] = $data;
        if ($is_end) {
            echo json_encode($json, JSON_UNESCAPED_SLASHES);
            exit;
        } else {
            echo json_encode($json, JSON_UNESCAPED_SLASHESn);
            exit;
        }
    }
}
if (!function_exists('sina_sssj_a')) {
    //获得实时行情
    function sina_sssj_a($gp)
    {
        $d = fenxi($gp);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://hq.sinajs.cn/list=" . $d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $t2 = explode(',', mb_convert_encoding($output, "utf-8", "gbk"));
        $t2['32'] = substr($t2['0'], 21);
        $t2['0'] = substr($t2['0'], 11, 8);
        return $t2;
    }
}
if (!function_exists('get_column_name')) {
    /**
     * 获取栏目名称
     * @param int $cid 栏目id
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_column_name($cid = 0)
    {
        $column_list = model('cms/column')->getList();
        return isset($column_list[$cid]) ? $column_list[$cid]['name'] : '';
    }
}
if (!function_exists('get_model_name')) {
    /**
     * 获取内容模型名称
     * @param string $id 内容模型id
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_model_name($id = '')
    {
        $model_list = model('cms/model')->getList();
        return isset($model_list[$id]) ? $model_list[$id]['name'] : '';
    }
}
if (!function_exists('get_model_title')) {
    /**
     * 获取内容模型标题
     * @param string $id 内容模型标题
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_model_title($id = '')
    {
        $model_list = model('cms/model')->getList();
        return isset($model_list[$id]) ? $model_list[$id]['title'] : '';
    }
}
if (!function_exists('get_model_type')) {
    /**
     * 获取内容模型类别：0-系统，1-普通，2-独立
     * @param int $id 模型id
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_model_type($id = 0)
    {
        $model_list = model('cms/model')->getList();
        return isset($model_list[$id]) ? $model_list[$id]['type'] : '';
    }
}
if (!function_exists('get_model_table')) {
    /**
     * 获取内容模型附加表名
     * @param int $id 模型id
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function get_model_table($id = 0)
    {
        $model_list = model('cms/model')->getList();
        return isset($model_list[$id]) ? $model_list[$id]['table'] : '';
    }
}
if (!function_exists('is_default_field')) {
    /**
     * 检查是否为系统默认字段
     * @param string $field 字段名称
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    function is_default_field($field = '')
    {
        $system_fields = cache('cms_system_fields');
        if (!$system_fields) {
            $system_fields = Db::name('cms_field')->where('model', 0)->column('name');
            cache('cms_system_fields', $system_fields);
        }
        return in_array($field, $system_fields, true);
    }
}
if (!function_exists('table_exist')) {
    /**
     * 检查附加表是否存在
     * @param string $table_name 附加表名
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function table_exist($table_name = '')
    {
        return true == Db::query("SHOW TABLES LIKE '{$table_name}'");
    }
}
if (!function_exists('time_tran')) {
    /**
     * 转换时间
     * @param int $timer 时间戳
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    function time_tran($timer)
    {
        $diff = $_SERVER['REQUEST_TIME'] - $timer;
        $day = floor($diff / 86400);
        $free = $diff % 86400;
        if ($day > 0) {
            return $day . " 天前";
        } else {
            if ($free > 0) {
                $hour = floor($free / 3600);
                $free = $free % 3600;
                if ($hour > 0) {
                    return $hour . " 小时前";
                } else {
                    if ($free > 0) {
                        $min = floor($free / 60);
                        $free = $free % 60;
                        if ($min > 0) {
                            return $min . " 分钟前";
                        } else {
                            if ($free > 0) {
                                return $free . " 秒前";
                            } else {
                                return '刚刚';
                            }
                        }
                    } else {
                        return '刚刚';
                    }
                }
            } else {
                return '刚刚';
            }
        }
    }
}
/**
 * 时间格式转换
 */
if (!function_exists('getTimeFormt')) {
    function getTimeFormt($time, $type = 0)
    {
        if ($type == 0) $f = "m-d H:i";
        else if ($type == 1) $f = "Y-m-d H:i";
        else if ($type == 3) $f = "m-d H:i";
        else if ($type == 4) $f = "H:i:s";
        else if ($type == 5) $f = "Y-m-d";
        else if ($type == 6) $f = "H:i:s";
        else if ($type == 7) $f = "Y-m-d H:i:s";
        return date($f, intval($time));
    }
}
/**
 * 根据type 获取资金明细的交易类型
 */
if (!function_exists('getTypeNameForMoney')) {
    function getTypeNameForMoney($type_id = 33)
    {
        $type = [
          '1' => lang('money_type_1'),// '充值成功',
          '2' => lang('money_type_2'),// '提现冻结',
          '3' => lang('money_type_3'),// '提现成功',
          '4' => lang('money_type_4'),// '提现失败',
          '5' => lang('money_type_5'),// '撤销提现',
          '6' => lang('money_type_6'),// '提现退回',
          '7' => lang('money_type_7'),// '追加保证金',
          '8' => lang('money_type_8'),// '冻结保证金',
          '9' => lang('money_type_9'),// '返还保证金',
          '10' => lang('money_type_10'),// '邀请人推广返佣',
          '11' => lang('money_type_11'),// '代理商佣金入账',
          '12' => lang('money_type_12'),// '代理商分成入账',
          '13' => lang('money_type_13'),// '提取佣金',
          '14' => lang('money_type_14'),// '提取分成',
          '15' => lang('money_type_15'),// '终止配资',
          '16' => lang('money_type_16'),// '扣配资管理费',
          '17' => lang('money_type_17'),// '扣递延费',
          '18' => lang('money_type_18'),// '后台转账',
          '19' => lang('money_type_19'),// '管理员操作',
          '20' => lang('money_type_20'),// '配资结算',
          '21' => lang('money_type_21'),// '配资审核不通过',
          '22' => lang('money_type_22'),// '配资审核通过',
          '23' => lang('money_type_23'),// '申请配资续期',
          '24' => lang('money_type_24'),// '扩大配资审核通过',
          '25' => lang('money_type_25'),// '扩大配资审核未通过',
          '26' => lang('money_type_26'),// '追加保证金审核通过',
          '27' => lang('money_type_27'),// '追加保证金审核未通过',
          '28' => lang('money_type_28'),// '配资续期审核通过',
          '29' => lang('money_type_29'),// '配资续期审核未通过',
          '30' => lang('money_type_30'),// '配资提前终止审核通过',
          '31' => lang('money_type_31'),// '配资提前终止审核未通过',
          '32' => lang('money_type_32'),// '按月配资手续费自动扣款',
          '33' => lang('money_type_33'),// '冻结金额',
          '34' => lang('money_type_34'),// '扣除金额',
          '35' => lang('money_type_35'),// '新手任务',
          '36' => lang('money_type_36'),// '分享成功奖励',
          '37' => lang('money_type_37'),// '签到奖励',
          '85' => lang('money_type_85'),// '提取盈利',
          '86' => lang('money_type_86'),//'基金买入',
          '87' => lang('money_type_87'),//'基金卖出',
          '88' => lang('money_type_88'),//'基金收益',
          '89' => lang('money_type_89'),//'卖出基金到账'
          '90' => lang('money_type_90'),//'分销佣金',
//           '91' => lang('money_type_91'),//'基金收益',
//           '92' => lang('money_type_92'),//'合伙人周工资'
//           '94' => lang('money_type_94'),//'合伙人周工资'
          
            '91' => lang('money_type_91'),//'合伙人补贴',
            '92' => lang('money_type_92'),//'合伙人周工资',
            '93' => lang('money_type_93'),//'合伙人团队分成',
            '94' => lang('money_type_94'),//'优投追加投资金额',
            '95' => lang('money_type_95'),//'优投投资',
            '96' => lang('money_type_96'),//'结算优投'
          '97' => lang('money_type_97'),//'优投冻结/解冻'
          '98' => lang('money_type_98'),//'活动金抵扣佣金'
          '99' => lang('money_type_99'),//'优投解冻'
          '104' => lang('money_type_104'),//'余额宝买入'
          '105' => lang('money_type_105'),//'余额宝冻结金'
          '106' => lang('money_type_106'),//'余额宝返还本金'
          '107' => lang('money_type_107'),//'余额宝利息'
          '108' => lang('money_type_108'),//'结算优投本金'
          '109' => lang('money_type_109'),//'提取盈利'
          '110' => lang('money_type_110'),//'彩金'
          '111' => lang('money_type_111'),//系统存入活动金
          '112' => lang('money_type_112'),//任务领取
          '113' => lang('money_type_113'),//追加金额退回
          '114' => lang('money_type_114'),//优投追加金额冻结
          '115' => lang('money_type_115'),//优投追加金额解冻
        ];
        return $type[$type_id];
    }
}
/*
* 邀请用户个数统计
*/
if (!function_exists('get_users_m')) {
    function get_users_m($mid)
    {
        $count_m = Db::name('member_invitation_relation')->where('mid', $mid)->count();
        $count_m = $count_m ? $count_m : 0;
        return $count_m;
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
/*
*获取用户盈利
*/
if (!function_exists('agents_profit_money')) {
    function agents_profit_money($mid)
    {
        $far_user = get_agents_info($mid);
        $arr = getSonAgentArr($mid);
        $arr_str = implode(',', $arr);
        if ($arr_str != '') $map = "affect_mid in ({$arr_str}) and mid = {$far_user['agent_far']}";
        else return 0;
        $back_money = Db::name('agents_back_money')->where($map)->sum('affect');
        $back_money = $back_money ? $back_money : 0.00;
        return round($back_money, 2);
    }
}
/*
* 返回当前用户返佣比例
*/
if (!function_exists('agents_back_rate')) {
    function agents_back_rate($mid)
    {
        $user = get_agents_info($mid);
        if ($user['agent_rate'] && (!$user['agent_id'] || $user['agent_id'] == 1)) {
            $rate = $user['agent_rate'];
        } else {
            if ($user['agent_id'] == 2) {
                $agent_1 = get_agents_info($user['agent_far']);
                $rate = bcmul($agent_1['agent_rate'], $user['agent_rate'], 2);
                $rate = bcdiv($rate, 100, 2);
            } elseif ($user['agent_id'] == 3) {
                $agent_1 = get_agents_info($user['agent_far']);
                $agent_2 = get_agents_info($agent_1['agent_far']);
                $agent_rate = $agent_1['agent_rate'] * $agent_2['agent_rate'];
                $rate = bcmul($agent_rate, $user['agent_rate'], 2);
                $rate = bcdiv($rate, 10000, 2);
            } else $rate = config('member_back_rate');
        }
        return $rate;
    }
}
/*
 * 获取当前用户返佣比例
 */
if (!function_exists('get_plus_rate')) {
    function get_plus_rate($mid)
    {
        $user = get_agents_info($mid);
        if ($user['agent_id'] == 1) {
            $rate = agents_back_rate($mid);
        } elseif ($user['agent_id'] == 2) {
            $agent_1 = get_agents_info($user['agent_far']);
            $agent_1_rate = $agent_1['agent_rate'] ? $agent_1['agent_rate'] : config('agent_back_rate');
            $rate = $user['agent_rate'] * $agent_1_rate / 100;
        } elseif ($user['agent_id'] == 3) {
            $agent_1 = get_agents_info($user['agent_far']);
            $agent_1_rate = $agent_1['agent_rate'] ? $agent_1['agent_rate'] : config('agent_back_rate');
            $agent_2 = get_agents_info($agent_1['agent_far']);
            $rate = $user['agent_rate'] * $agent_2['agent_rate'] * $agent_1_rate / 10000;
        } else {
            $rate = config('member_back_rate');
        }
        return $rate;
    }
}
/*
* 获得当前用户返佣来源
*/
if (!function_exists('agents_back_come')) {
    function agents_back_come($mid)
    {
        $user = Db::name('member')->field('id,mobile')->where('id', $mid)->find();
        $note = '用户' . $user['mobile'] . '名下';
        return $note;
    }
}
/*
* 获取当前用户代理类型 代理级别
*/
if (!function_exists('get_agents_level')) {
    function get_agents_level($mid)
    {
        $user = Db::name('member')->field('id,agent_id')->where('id', $mid)->find();
        return $user['agent_id'];
    }
}
/*
* 返回代理商信息
*/
if (!function_exists('get_agents_info')) {
    function get_agents_info($mid)
    {
        $user = Db::name('member')->field('id,agent_id,agent_pro,agent_far,agent_rate')->where('id', $mid)->find();
        return $user;
    }
}
/*
* 返回返佣截止时间
*/
if (!function_exists('getEndBack')) {
    function getEndBack($time)
    {
        $time_n = time();
        $rate_time = config('member_back_time') ? config('member_back_time') : 0;
        $agent_time = $time + ($rate_time * 86400 * 30);
        if ($agent_time > $time_n) {
            $end_time = getTimeFormt($agent_time, 5);
        } else {
            $end_time = '已到期';
        }
        return $end_time;
    }
}
//调取所有下级树 20 1 2 3
if (!function_exists('getSonAgentArr')) {
    function getSonAgentArr($mid)
    {
        $user = Db::name('member')->field('id,agent_id')->where('agent_far', $mid)->select();
        $arr = array();
        if (!empty($user)) {
            foreach ($user as $k => $v) {
                if ($v['id']) array_push($arr, $v['id']);
                $user_info = getSonAgentArr2($v['id']);
                foreach ($user_info as $key => $val) {
                    if ($val['id']) array_push($arr, $val['id']);
                }
            }
        }
        array_push($arr, $mid);
        return $arr;
    }
}
//调取所有下级树 20 1 2 3
if (!function_exists('getSonAgentArr2')) {
    function getSonAgentArr2($mid)
    {
        $arr = array();
        $user = Db::name('member')->field('id,agent_id')->where('agent_far', $mid)->select();
        return $user;
    }
}
if (!function_exists('yan_time')) {
    /*
     * 验证交易时间
     */
    function yan_time($last_time = 14.95)
    {
        $t = time() - strtotime(date("Y-m-d", time()));
        $t2 = 3600 * 9.5 + 300;//早盘开盘时间
        $t3 = 3600 * 11.5;//早盘停盘时间
        $t4 = 3600 * 13;//下午开盘时间
        $t5 = 3600 * $last_time;//默认下午停盘时间14:55
        if (!(($t > $t2 && $t < $t3) || ($t > $t4 && $t < $t5))) {
            return false;//
        }
        if (date('N', time()) > 5) { //周六周日
            return false;//
        }
        $array = festival();//返回节假日
        if (in_array(date("md", time()), $array)) {//如果是节假日
            return false;
        }
        return true;
    }
}
/**
 *Desc:字符串对应多语言对照码
 * @param $title
 * @return mixed|string
 *author:oszpac
 *date:2023-06-07
 */
function message_i18n($txt)
{
    $pattern = '/\d+(\.\d+)?/';//匹配数字规则
    //与 zh-cn.php 对应
    $_i18n = [
      'messege_1' => '充值审核通知',
      'messege_2' => '充值通知',
      'messege_3' => '分享奖励到账',
      'messege_4' => '扩大配资审核通知',
      'messege_5' => '提前终止配资审核通知',
      'messege_6' => '提取盈利审核通知',
      'messege_7' => '提现审核通知',
      'messege_8' => '续期审核通知',
      'messege_9' => '自动续期成功通知',
      'messege_10' => '追加保证金审核通知',
      'messege_11' => '配资审核通知',
      'messege_12' => '充值审核通过',
      'messege_13' => '充值审核未通过',
      'messege_14' => '充值成功',
      'messege_15' => '把好的赚钱方式分享给身边的朋友',
      'messege_16' => '追加配资审核不通过,释放冻结资金',
      'messege_17' => '恭喜您申请追加配资成功,总操盘资金为%s元',
      'messege_18' => '申请终止配资审核未通过',
      'messege_19' => '申请终止配资审核通过',
      'messege_20' => '申请提取盈利审核未通过',
      'messege_21' => '申请提取盈利审核通过，添加金额%s元',
      'messege_22' => '提现审核未通过',
      'messege_23' => '提现审核通过',
      'messege_24' => '审核未通过，返回冻结服务费',
      'messege_25' => '审核通过，扣除冻结利息费%s元',
      'messege_26' => '配资%s自动续期，扣除续期利息%s元，活动资金扣除，%s元',
      'messege_27' => '配资%s自动续期，扣除续期利息%s元，余额扣除，活动资金扣除：%s元,系统余额扣除：%s元',
      'messege_28' => '审核不通过，退回冻结金额',
      'messege_29' => '审核通过，扣除冻结金额，增加保证金金额',
      'messege_30' => '您的股票交易账号为：%s 密码：%s',
      'messege_31' => '系统自动审核  您的股票交易账号为：%s 密码：%s',
      'messege_32' => '通知',
    ];
    // txt= '恭喜您申请追加配资成功,总操盘资金增加为119900元'
    if (preg_match_all($pattern, $txt, $match)) {//内容包含有数字
        //将数字替换成换字符
        $re_txt = preg_replace($pattern, "%s", $txt);
        // ret_txt = '恭喜您申请追加配资成功,总操盘资金增加为%s元'
        $key = array_search($re_txt, $_i18n);
        if ($key === FALSE)
            return $txt;
        $langs = Lang::get($key);
        return vsprintf(Lang::get($key), $match[0]);
    }
    $key = array_search($txt, $_i18n);
    if ($key === FALSE)
        return $txt;
    return Lang::get($key);
}