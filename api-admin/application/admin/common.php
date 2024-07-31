<?php

use think\Db;
use app\common\service\UserService;

function getCurrentMonthDates() {
    // 获取当前年份和月份
    $year = date('Y');
    $month = date('m');

    // 获取本月的第一天
    $startDate = new DateTime("$year-$month-01");

    // 获取下个月的第一天，然后减去一天，得到本月的最后一天
    $endDate = (clone $startDate)->modify('first day of next month')->modify('-1 day');

    // 生成包含所有日期的数组
    $days = [];
    for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
        $days[$date->format('Y-m-d')] = 0; // 初始化为0
    }

    return $days;
}



/**
 * Desc : 输出本月日历
 * Date : 2024-06-28 18:12
 * @return array
 */
function generateCurrentMonthCalendar() {
    // 获取当前年份和月份
    $year = date('Y');
    $month = date('m');

    // 获取本月的第一天
    $startDate = new DateTime("$year-$month-01");

    // 获取下个月的第一天，然后减去一天，得到本月的最后一天
    $endDate = (clone $startDate)->modify('first day of next month')->modify('-1 day');

    // 生成包含所有日期的数组
    $days = [];
    for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
        $days[] = (int)$date->format('d');
    }

    return $days;
}

/**
 * Desc : 查询当前月份或其他月份的数据  用户总数
 * Date : 2024-06-28 18:17
 * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
  function getUserDataForCurrentMonth($year='',$month='') {
    if(empty($year)){
        $year = date('Y');
    }
    if(empty($month)){
        $month = date('m');
    }
    $month=$month<1?12:$month;

    $startDate = strtotime("$year-$month-01 00:00:00");
    $endDate = strtotime(date("Y-m-t 23:59:59", $startDate));

    // 查询当前月份的用户数据
    $results = Db::name('member')
        ->field("FROM_UNIXTIME(create_time, '%Y-%m-%d') as date, COUNT(*) as count")
        ->where('create_time', '>=', $startDate)
        ->where('create_time', '<=', $endDate)
        ->where('role_name', '=', '普通会员')
        ->where((new UserService())->getAgentSql('id'))
        ->group('date')
        ->select();


    return $results;
}



/**
 * Desc : 查询当前月份或其他月份的数据 跟单总数
 * Date : 2024-06-28 18:17
 * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
 function getGDMonth($year='',$month='') {
    if(empty($year)){
        $year = date('Y');
    }
    if(empty($month)){
        $month = date('m');
    }

    $month=$month<1?12:$month;

    $startDate = strtotime("$year-$month-01 00:00:00");
    $endDate = strtotime(date("Y-m-t 23:59:59", $startDate));

    // 查询当前月份的用户数据
    $results = Db::name('fund_order_gs')
        ->field("FROM_UNIXTIME(create_time, '%Y-%m-%d') as date, COUNT(*) as count")
        ->where('create_time', '>=', $startDate)
        ->where('create_time', '<=', $endDate)
        ->where((new UserService())->getAgentSql('uid'))
        ->group('date')
        ->select();


    return $results;
}




/**
 * Desc : 查询当前月份或其他月份的数据 跟单总数
 * Date : 2024-06-28 18:17
 * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getHYMonth($year='',$month='') {
    if(empty($year)){
        $year = date('Y');
    }
    if(empty($month)){
        $month = date('m');
    }

    $month=$month<1?12:$month;

    $startDate = strtotime("$year-$month-01 00:00:00");
    $endDate = strtotime(date("Y-m-t 23:59:59", $startDate));

    // 查询当前月份的用户数据
    $results = Db::name('member')
        ->field("FROM_UNIXTIME(last_login_time, '%Y-%m-%d') as date, COUNT(*) as count")
        ->where('last_login_time', '>=', $startDate)
        ->where('last_login_time', '<=', $endDate)
        ->where((new UserService())->getAgentSql('id'))
        ->whereNotNull('last_login_time')
        ->group('date')
        ->select();


    return $results;
}



/**
 * Desc : 查询当前月份或其他月份的数据 充值数据
 * Date : 2024-06-28 18:17
 * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getrechargeMonth($year='',$month='') {
    if(empty($year)){
        $year = date('Y');
    }
    if(empty($month)){
        $month = date('m');
    }

    $month=$month<1?12:$month;
    $memberIds = Db::name('member')->where('role_name', '普通会员')->where((new UserService())->getAgentSql('id'))->column('id');
    $startDate = strtotime("$year-$month-01 00:00:00");
    $endDate = strtotime(date("Y-m-t 23:59:59", $startDate));

    // 查询当前月份的用户数据
    $results = Db::name('money_recharge')
        ->field("FROM_UNIXTIME(create_time, '%Y-%m-%d') as date, SUM(money) as count")
        ->whereIn('mid',$memberIds)
        ->where('create_time', '>=', $startDate)
        ->where('create_time', '<=', $endDate)
        ->where('status','=',1)
        ->group('date')
        ->select();


    return $results;
}


/**
 * Desc : 查询当前月份或其他月份的数据 提现数据
 * Date : 2024-06-28 18:17
 * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getwithdrawMonth($year='',$month='') {
    if(empty($year)){
        $year = date('Y');
    }
    if(empty($month)){
        $month = date('m');
    }

    $month=$month<1?12:$month;
    $memberIds = Db::name('member')->where('role_name', '普通会员')->where((new UserService())->getAgentSql('id'))->column('id');
    $startDate = strtotime("$year-$month-01 00:00:00");
    $endDate = strtotime(date("Y-m-t 23:59:59", $startDate));

    // 查询当前月份的用户数据
    $results = Db::name('money_withdraw')
        ->field("FROM_UNIXTIME(create_time, '%Y-%m-%d') as date, SUM(money) as count")
        ->whereIn('mid',$memberIds)
        ->where('create_time', '>=', $startDate)
        ->where('create_time', '<=', $endDate)
        ->where('status','=',1)
        ->group('date')
        ->select();


    return $results;
}

/**
 * Desc : 查询当前月份或其他月份的数据 充值总数据
 * Date : 2024-06-28 18:17
 * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getrechargeMonthSUM($year='',$month='') {
    if(empty($year)){
        $year = date('Y');
    }
    if(empty($month)){
        $month = date('m');
    }

    $month=$month<1?12:$month;
    $memberIds = Db::name('member')->where('role_name', '普通会员')->where((new UserService())->getAgentSql('id'))->column('id');
    $startDate = strtotime("$year-$month-01 00:00:00");
    $endDate = strtotime(date("Y-m-t 23:59:59", $startDate));

    // 查询当前月份的用户数据
    $results = Db::name('money_recharge')
        ->field("FROM_UNIXTIME(create_time, '%Y-%m-%d') as date, SUM(money) as count")
        ->whereIn('mid',$memberIds)
        ->where('create_time', '>=', $startDate)
        ->where('create_time', '<=', $endDate)
        ->where('status','=',1)
        ->SUM('money');


    return $results;
}

/**
 * Desc : 查询当前月份或其他月份的数据 提现数据
 * Date : 2024-06-28 18:17
 * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getwithdrawMonthSUM($year='',$month='') {
    if(empty($year)){
        $year = date('Y');
    }
    if(empty($month)){
        $month = date('m');
    }

    $month=$month<1?12:$month;
    $memberIds = Db::name('member')->where('role_name', '普通会员')->where((new UserService())->getAgentSql('id'))->column('id');
    $startDate = strtotime("$year-$month-01 00:00:00");
    $endDate = strtotime(date("Y-m-t 23:59:59", $startDate));

    // 查询当前月份的用户数据
    $results = Db::name('money_withdraw')
        ->whereIn('mid',$memberIds)
        ->where('create_time', '>=', $startDate)
        ->where('create_time', '<=', $endDate)
        ->where('status','=',1)
        ->SUM('money');


    return $results;
}


/**
 * Desc : 查询当前月份或其他月份的数据 充值总数据
 * Date : 2024-06-28 18:17
 * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getRecordMonthSUM($year='',$month='',$type='') {
    if(empty($year)){
        $year = date('Y');
    }
    if(empty($month)){
        $month = date('m');
    }
    $month=$month<1?12:$month;
    $memberIds = Db::name('member')->where('role_name', '普通会员')->where((new UserService())->getAgentSql('id'))->column('id');
    $startDate = strtotime("$year-$month-01 00:00:00");
    $endDate = strtotime(date("Y-m-t 23:59:59", $startDate));

    $where = [];
//    由于旧数据有正数,如不清数据，只能固定查询 负数
    if($type == 19){
        $where[] = ['affect','<',0];
    }
    // 查询当前月份的用户数据
    $results = Db::name('money_record')
        ->whereIn('mid',$memberIds)
        ->where($where)
        ->where('type', '=', $type)
        ->where('create_time', '>=', $startDate)
        ->where('create_time', '<=', $endDate)
        ->SUM('affect');

    return $results;
}
//获得上个月日期
if (!function_exists('getAllDatesOfLastMonth')) {
    function getAllDatesOfLastMonth() {
        $start = new DateTime('first day of last month');
        $end = new DateTime('first day of this month');
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);
        
        $dates = array();
        foreach ($period as $date) {
            $dates[$date->format('Y-m-d')] = 0;
        }
        return $dates;
    };
}