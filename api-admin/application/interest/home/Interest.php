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
namespace app\interest\home;

use app\apicom\model\JWT;
use app\common\controller\Common;
use app\member\model\Bank as BankModel;
use app\member\model\Member as MemberModel;
use app\interest\model\Interest as InterestModel;
use app\money\model\Money;
use think\Db;
use think\facade\Session;
use think\helper\Hash;

class Interest extends Common
{
    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function initialize()
    {
        // 系统开关
        if (!config('web_site_status')) {
            ajaxmsg('站点已经关闭，请稍后访问', 0, '', true, ['msgCode' => 'L0015']);
        }
        $req = request();
        $token = input("token");
        defined('MID') or define('MID', isLogin($token));
        if (!MID) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
    }

    //获取余额宝配置
    public function index()
    {
        $data['list'] = Db::name('interest_config')->field('id,name,min_account,max_account,buy_num,explain')->select();
        foreach ( $data['list'] as $k=>$v ) {
            $get_one = Db::name('interest_daily_float')
                ->where('interest_record_id',$v['id'])
                ->where('date',date('Y-m-d', strtotime('-1 day')))
                ->find();
            if($get_one){
                $data['list'][$k]['interest_rate'] = $get_one['interest_rate'];
            }else{
                $data['list'][$k]['interest_rate'] = 0;
            }
        }

        // 获取今天的开始时间和结束时间
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $todayEnd = strtotime(date('Y-m-d 23:59:59'));

// 今天购买的人数
        $buy_number = Db::name('interest')
            ->where('buy_time', 'BETWEEN', [$todayStart, $todayEnd])
            ->group('uid')
            ->count();
//        昨日收益
// 获取昨天的日期和今天（但实际上是昨天的23:59:59）的日期时间
        $yesterdayStart = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')));
        $yesterdayEnd = strtotime(date('Y-m-d 23:59:59', strtotime('-1 day')));

// 使用BETWEEN子句查询昨天的interest
        $data['yesterday_interest'] = Db::name('interest')
            ->where('uid', MID) // 假设MID是有效的变量或常量
            ->where('status', 2)
            ->where('rebate_time', 'BETWEEN', [$yesterdayStart, $yesterdayEnd])
            ->sum('interest');

//        累计收益
        $data['total_interest'] = Db::name('interest')
            ->where('uid',MID)
            ->where('status',2)
            ->sum('interest');
//        余额
        $freeze_balance_bao = Db::name('money')->where(['mid' => MID])->find();
        $data['freeze_balance_bao'] = $freeze_balance_bao['freeze_balance_bao'] / 100;
        $interest_basic = Db::name('interest_basic')->find();
        $data['banner'] = 'https://' . $_SERVER['HTTP_HOST'] . get_files_path($interest_basic['banner']);
        $data['interest_basic_name'] = $interest_basic['name'];

        $data['buy_number'] = $interest_basic['buy_number'] + $buy_number;
        ajaxmsg('获取成功', 1, $data, true, ['msgCode' => '配置信息']);
    }


    /*
 * 购买余额宝操作
 */
    public function doInterest()
    {
        $data = input();

        $data['mid'] = MID;
        $member_info = Db::name('member')->where(["id" => MID])->find();

        $interest_config = Db::name('interest_config')->where('id',$data['interest_record_id'])->field('min_account,max_account,buy_num')->find();
        $interest_basic = Db::name('interest_basic')->find();
        if($interest_basic['white_buy'] == 0){
            if($member_info['role_name'] != '普通会员'){
                ajaxmsg('余额宝额度已售罄', 0);
            }
        }
        if ($data['money'] < 0) {
            ajaxmsg('购买金额错误！', 0);
        }
        if ($data['money'] < $interest_config['min_account']) {
            ajaxmsg('购买金额必须大于'.$interest_config['min_account'].'元', 0);
        }
//        if ($data['money'] > $interest_config['max_account']) {
//            ajaxmsg('购买金额必须小于'.$interest_config['max_account'].'元', 0);
//        }

        $money_res = Db::name('money')->where(['mid' => MID])->find();
        if (empty($money_res['account'])) {
            ajaxmsg('查询账户资金出错！', 0);
        }
        if (isset($money_res['account']) && $money_res['account'] < ($data['money'] * 100)) {
            ajaxmsg('购买金额不能大于可用余额！', 0);
        }
        $todayStart = date('Y-m-d 00:00:00'); // 今天开始时间
        $todayEnd = date('Y-m-d 23:59:59'); // 今天结束时间

        $withdraw_info = Db::name('interest')
            ->where(['uid' => MID])
            ->whereTime('buy_time', 'between', [$todayStart, $todayEnd]) // 使用查询表达式
            ->count();

        if($interest_basic['buy_num'] > 0){
            if ($withdraw_info >= $interest_basic['buy_num']){
                ajaxmsg('今日购买次数已上限', 0);
            }
        }
        if (Hash::check((string)$data['paywd'], $member_info['paywd'])) {
            $res = InterestModel::saveData($data);
        } else {
            ajaxmsg('支付密码错误', 0);
        }
        if ($res['status']) {
            ajaxmsg('购买已提交', 1);
        } else {
            ajaxmsg('购买失败', 0);
        }
    }


    /*
* 获取统计
*/
    public function interestStatistics()
    {
        $data = input();
        $where[] = ['interest_record_id','=',$data['interest_record_id']];
        $currentDate = date('Y-m-d',strtotime('-1 day'));

// 计算七天前的日期
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));

// 构建查询条件
        $where[] = ['date', '>=', $sevenDaysAgo]; // 从七天前开始
        $where[] = ['date', '<=', $currentDate]; // 到今天为止

// 查询 interest_daily_float 表中的数据
        $list = Db::name('interest_daily_float')
            ->where($where) // 应用查询条件
            ->field('interest_rate,date') // 只选择 interest_rate 字段
            ->select();
        $result = [];
        foreach ($list as $key => $value) {
            $result[$key]['rate'] = $value['interest_rate'];
            $result[$key]['date'] = date('m-d', strtotime($value['date']));
        }
        ajaxmsg('获取成功', 1, $result, true, ['msgCode' => '收益统计']);
    }


    /*
* 收益明细
*/
    public function interestIncome()
    {
        // 获取查询条件
        $map = $this->getMap();
        $map['uid'] = MID;
        $map['status'] = 2;
        $order = 'id desc';
        $page = intval(input("page"));
        $page = $page ? $page : 1;
        $offset = $page;
        $result = [];
        // 数据列表
//        $data_list = Db::name('interest')
//            ->where($map)
//            ->where('status',2)
//            ->field('interest,rebate_time')
//            ->order($order)
//            ->page($offset, 10)
//            ->select();

        $data_list = InterestModel::getInterestList($offset, $map, $order);

        $result['list'] = $data_list;

        //        昨日收益
// 获取昨天的日期和今天（但实际上是昨天的23:59:59）的日期时间
        $yesterdayStart = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')));
        $yesterdayEnd = strtotime(date('Y-m-d 23:59:59', strtotime('-1 day')));

// 使用BETWEEN子句查询昨天的interest
        $result['yesterday_interest'] = Db::name('interest')
            ->where('uid', MID) // 假设MID是有效的变量或常量
            ->where('status', 2)
            ->where('rebate_time', 'BETWEEN', [$yesterdayStart, $yesterdayEnd])
            ->sum('interest');
//        累计收益
        $result['total_interest'] = Db::name('interest')
            ->where('uid',MID)
            ->where('status',2)
            ->sum('interest');
        ajaxmsg('数据列表', 1, $result, true, ['msgCode' => 'L0129']);
    }

}