<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundOrderGs;
use app\money\model\Money;
use app\money\model\Record as RecordModel;
use app\statistics\model\ContractStatistics as ContractStatisticsModel;
use app\statistics\model\DataReport as DataReportModel;
use app\statistics\model\ProfitLoss as ProfitLossModel;
use app\statistics\model\RechargeWithdrawal as RechargeWithdrawalModel;
use app\statistics\model\UserStatistics as UserStatisticsModel;
use think\facade\Cache;
use think\facade\Env;
use think\helper\Hash;
use think\Db;
use app\common\builder\ZBuilder;
use app\user\model\User as UserModel;
use app\common\service\UserService;

/**
 * 后台默认控制器
 * @package app\admin\controller
 */
class Index extends Admin
{
    /**
     * 后台首页
     * @return string
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function index()
    {
        $admin_pass = Db::name('admin_user')->where('id', 1)->value('password');
        if (UID == 1 && $admin_pass && Hash::check('admin', $admin_pass)) {
            $this->assign('default_pass', 1);
        }
        return $this->fetch();
    }
    public function indexdata()
    {
        #平台总余额  扣除白名单
        $memberIds = Db::name('member')->where('role_name', '普通会员')->where((new UserService())->getAgentSql('id'))->column('id');

        $sum_account =  Db::name('money')->whereIn("mid",$memberIds)
            ->field("SUM(`account`) as sum_money")->find();

//        $this->assign('sum_account', convertToTenThousand($sum_account['sum_money']));
        if($sum_account['sum_money'] > 0){
            $sum_account['sum_money'] = sprintf("%.2f", $sum_account['sum_money']/100);// 保留两位小数
        }else{
            $sum_account['sum_money'] = 0;
        }
        $this->assign('sum_account', $sum_account['sum_money']);

        #工资累计发放
        $sum_price =  Db::name('fund_salary_log')->field("SUM(`price`) as sum_money")->where((new UserService())->getAgentSql('uid'))->find();
//        $this->assign('sum_money', convertToTenThousand($sum_price['sum_money']));
        $this->assign('sum_money', $sum_price['sum_money']);



        #----------处理图表----------------
        #本月日历
        $generateCurrentMonthCalendar=generateCurrentMonthCalendar();
        $this->assign('generateCurrentMonthCalendar',json_encode($generateCurrentMonthCalendar));


        $days = getCurrentMonthDates();


        #查询注册用户当月数据
        $userData1 = getUserDataForCurrentMonth();
        foreach ($userData1 as $data) {
            $days[$data['date']] = $data['count'];
        }
        $month_member_1=[];
        foreach ($days as $v) {
            $month_member_1[] = $v;
        }
        $this->assign('month_member_1',json_encode($month_member_1));


        #查询注册用户上月月数据
        $days2 = getAllDatesOfLastMonth();
        $userData2 =getUserDataForCurrentMonth(date('Y'),date('m') - 1);
        foreach ($userData2 as $data2) {
            $days2[$data2['date']] = $data2['count'];
        }
        $month_member_2=[];
        foreach ($days2 as $v) {
            $month_member_2[] = $v;
        }
        $this->assign('month_member_2',json_encode($month_member_2));




        #查询--用户跟单---本月数据
        $gd_days1 = getCurrentMonthDates();
        $gd_1 = getGDMonth(date('Y'),date('m'));
        foreach ($gd_1 as $gd_1_v) {
            $gd_days1[$gd_1_v['date']] = $gd_1_v['count'];
        }
        $month_gd_1=[];
        foreach ($gd_days1 as $v_gd) {
            $month_gd_1[] = $v_gd;
        }
        $this->assign('month_gd_1',json_encode($month_gd_1));

        #查询--用户跟单---上月数据
        $gd_days2 = getAllDatesOfLastMonth();
        $gd_2 = getGDMonth(date('Y'),date('m')-1);
        foreach ($gd_2 as $gd_2_v) {
            $gd_days2[$gd_2_v['date']] = $gd_2_v['count'];
        }
        $month_gd_2=[];
        foreach ($gd_days2 as $v_gd) {
            $month_gd_2[] = $v_gd;
        }
        $this->assign('month_gd_2',json_encode($month_gd_2));



        #查询--活跃用户---本月数据
        $hy_days1 = getCurrentMonthDates();
        $hy_1 = getHYMonth(date('Y'),date('m'));
        foreach ($hy_1 as $hy_1_v) {
            $hy_days1[$hy_1_v['date']] = $hy_1_v['count'];
        }
        $month_hy_1=[];
        foreach ($hy_days1 as $v_hy) {
            $month_hy_1[] = $v_hy;
        }
        $this->assign('month_hy_1',json_encode($month_hy_1));

        #查询--活跃用户---上月数据
        $hy_days2 = getAllDatesOfLastMonth();
        $hy_2 = getHYMonth(date('Y'),date('m')-1);
        foreach ($hy_2 as $hy_2_v) {
            $hy_days2[$hy_2_v['date']] = $hy_2_v['count'];
        }
        $month_hy_2=[];
        foreach ($hy_days2 as $v_hy) {
            $month_hy_2[] = $v_hy;
        }
        
        $this->assign('month_hy_2',json_encode($month_hy_2));




        #本月度充值查询
        $recharge_days1 = getCurrentMonthDates();
        $recharge_1 = getrechargeMonth(date('Y'),date('m'));
        foreach ($recharge_1 as $recharge_1_v) {
            $recharge_days1[$recharge_1_v['date']] = $recharge_1_v['count'];
        }
        $recharge_2=[];
        foreach ($recharge_days1 as $recharge_hy) {
            $recharge_2[] = $recharge_hy/100;
        }
        $this->assign('recharge_1',json_encode($recharge_2));


        #本月度提现查询
        $withdraw_days1 = getCurrentMonthDates();
        $withdraw_1 = getwithdrawMonth(date('Y'),date('m'));
        foreach ($withdraw_1 as $withdraw_1_v) {
            $withdraw_days1[$withdraw_1_v['date']] = $withdraw_1_v['count'];
        }
        $withdraw_2=[];
        foreach ($withdraw_days1 as $withdraw_hy) {
            $withdraw_2[] = $withdraw_hy/100;
        }
        $this->assign('withdraw_1',json_encode($withdraw_2));

        #本月度充值累计
        $recharge_sum = getrechargeMonthSUM(date('Y'),date('m')) /100;
//        $this->assign('recharge_sum',convertToTenThousand($recharge_sum));
//        增加资金操作类型：系统自动转入

        $Record_recharge = getRecordMonthSUM(date('Y'),date('m'),18)/100;

        $recharge_sum = sprintf("%.2f", $recharge_sum + $Record_recharge);// 保留两位小数
        $this->assign('recharge_sum',$recharge_sum.'元');

        #本月读提现总额
        $withdraw_sum = getwithdrawMonthSUM(date('Y'),date('m')) /100;
        //        增加资金操作类型：管理员操作
        $Record_withdraw = getRecordMonthSUM(date('Y'),date('m'),19)/100;
        $Record_withdraw = abs($Record_withdraw);
        $withdraw_sum = sprintf("%.2f", $withdraw_sum + $Record_withdraw);// 保留两位小数
//        $this->assign('withdraw_sum',convertToTenThousand($withdraw_sum));
        $this->assign('withdraw_sum',$withdraw_sum.'元');
        #充提差
//        $this->assign('recharge_withdraw_sum',convertToTenThousand($recharge_sum-$withdraw_sum));
        $Difference = $recharge_sum - $withdraw_sum;
        $Deposit_Withdrawal_Difference = 0;
        if ($Difference > 0) {
//            $Difference = sprintf("%.2f", $Difference/100);// 保留两位小数
            $Deposit_Withdrawal_Difference = $Difference . '元';
        }
        $this->assign('recharge_withdraw_sum',$Deposit_Withdrawal_Difference);

        #跟单待审核
        $gd_wait = Db::name('fund_order_gs')
            ->alias('o')
            ->field('o.*,o.*,t.name as trader_name,t.headimgurl')
            ->leftJoin('trader t','o.trader_id = t.id')
            ->where('o.status',0)->where((new UserService())->getAgentSql('uid'))->select();
        foreach ($gd_wait as $k=> $item){
            $gd_wait[$k]['trader_img'] = get_thumb($item['headimgurl']);
            $gd_wait[$k]['addtime'] = date("Y-m-d H:i:s",$item['create_time']);
        }


        $this->assign('gd_wait',$gd_wait);
        $this->assign('gd_wait_count',count($gd_wait));
        #充值待审核
        $recharge_wait = Db::name('money_recharge')
            ->alias('r')
            ->field('r.*,m.name as user_name,m.mobile')
            ->leftJoin('member m','r.mid = m.id')
            ->where((new UserService())->getAgentSql('mid'))
            ->where('r.status',0)->select();
        foreach ($recharge_wait as $k=> $item){
            $gd_wait[$k]['create_time'] = date("Y-m-d H:i:s",$item['create_time']);
        }
        $this->assign('recharge_wait',$recharge_wait);
        $this->assign('recharge_wait_count',count($recharge_wait));
        #提现待审核
        $withdraw_wait = Db::name('money_withdraw')
            ->alias('w')
            ->field('w.*,m.name as user_name,m.mobile')
            ->leftJoin('member m','w.mid = m.id')
            ->where((new UserService())->getAgentSql('mid'))
            ->where('w.status',0)->select();
        $this->assign('withdraw_wait',$withdraw_wait);
        $this->assign('withdraw_wait_count',count($withdraw_wait));
        #域名异常
        $domain_error = Db::name('admin_domain_abnormallog')->where("status",0)->group('domain')->select();

        $this->assign('domain_error',$domain_error);
        $this->assign('domain_error_count',count($domain_error));


        #每日操盘审核审核
        $map   = $this->getMap();
        $map[] = ['o.status','=', 0];
        $map = (new UserService())->getAgentMap($map,'fo.uid');
        $res = FundDaylineModel::getList(1, $map, 'o.id desc');
        $res=$res['list'];
        foreach ($res as $k=> $item){
            $username       = empty($item['username']) ? '--' : $item['username'];

            $res[$k]['user_info'] = $username . "/" . $item['mobile'] ;
            $res[$k]['addtime'] = date("Y-m-d H:i:s",$item['create_time']);
        }


        $this->assign('gd_DailyTrading',$res);
        $this->assign('gd_DailyTrading_count',count($res));

        return $this->fetch();
    }




    /**
     * 清空系统缓存
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function wipeCache()
    {
      if(systemwipeCache()){
        $this->success('清空成功');
      }else{
        $this->error('请在系统设置中选择需要清除的缓存类型');
      }

    }

    /**
     * 个人设置
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function profile()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            $data['nickname'] == '' && $this->error('昵称不能为空');
            $data['id'] = UID;
            // 如果没有填写密码，则不更新密码
            if ($data['password'] == '') {
                unset($data['password']);
            }
            $UserModel = new UserModel();
            if ($user = $UserModel->allowField(['nickname', 'email', 'password', 'mobile', 'avatar'])->update($data)) {
                // 记录行为
                action_log('user_edit', 'admin_user', UID, UID, get_nickname(UID));
                $this->success('编辑成功');
            } else {
                $this->error('编辑失败');
            }
        }
        // 获取数据
        $info = UserModel::where('id', UID)->field('password', true)->find();
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->addFormItems([ // 批量添加表单项
                ['static', 'username', '用户名', '不可更改'],
                ['text', 'nickname', '昵称', '可以是中文'],
                ['text', 'email', '邮箱', ''],
                ['password', 'password', '密码', '必填，6-20位'],
                ['text', 'mobile', '手机号'],
                ['image', 'avatar', '头像']
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    /**
     * 检查版本更新
     * @return \think\response\Json
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function checkUpdate()
    {
        $params = config('dolphin.');
        $params['domain'] = request()->domain();
        $params['website'] = config('web_site_title');
        $params['ip'] = $_SERVER['SERVER_ADDR'];
        $params['php_os'] = PHP_OS;
        $params['php_version'] = PHP_VERSION;
        $params['mysql_version'] = db()->query('select version() as version')[0]['version'];
        $params['server_software'] = $_SERVER['SERVER_SOFTWARE'];
        $params = http_build_query($params);
        $opts = [
            CURLOPT_TIMEOUT => 20,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => config('dolphin.product_update'),
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $params
        ];
        // 初始化并执行curl请求
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);
        if ($result['code'] == 1) {
            return json([
                'update' => '<a class="badge badge-primary" href="http://www.dolphinphp.com/download" target="_blank">有新版本：' . $result["version"] . '</a>',
                'auth' => $result['auth']
            ]);
        } else {
            return json([
                'update' => '',
                'auth' => $result['auth']
            ]);
        }
    }


//数据统计
    public function statistics()
    {
        $num = 6;
        $start_time = strtotime(date('Y-m-d', strtotime('-' . $num . ' day')));
        $end_time = strtotime(date('Y-m-d'));

        if (input()) {
            $start_time1 = strtotime(input('start_time'));
            $end_time1 = strtotime(input('end_time'));
            $type = input('type');
        }

        $wherem = [];
        $system_profit_loss = Db::name('stock_position')
            ->where('stock_count', '>', 0)
            ->where($wherem)
            ->sum('ck_profit');
        $where = [];
        $where['status'] = 1;
        $withdrawal_freeze = Money::where($where)->sum('freeze');//提现冻结金额
        $wallet_balance = Money::where($where)->sum('account');//会员净资产;
        $wherem2 = [];
        $data = [];
        $data['system_profit_loss'] = $system_profit_loss / 100;  //平台累计盈亏
        $data['withdrawal_freeze'] = $withdrawal_freeze / 100;  //提现冻结金额
        $data['wallet_balance'] = $wallet_balance / 100;  //钱包余额
        //充值提现去除测试的
        $memberIds = Db::name('member')->where('role_name', '普通会员')->column('id');
        $user_statistics = RechargeWithdrawalModel::whereIn("id",$memberIds)->
            field("SUM(`recharge`) as recharge,SUM(`withdrawal`) as withdrawal")
            ->find();

        $data['recharge_total_amount'] = $user_statistics['recharge'] ?: '0.00';  //充值总金额
        $data['withdrawal_total_amount'] = $user_statistics['withdrawal'] ?: '0.00'; //累计提现
        if ($type == 2) {
            $user_statistics_data = $this->getUserData($start_time1, $end_time1);
        } else {
            $user_statistics_data = $this->getUserData($start_time, $end_time);
        }

        if ($type == 3) {
            $recharge_withdrawal_data = $this->getRechargeWithdrawalData($start_time1, $end_time1);
        } else {
            $recharge_withdrawal_data = $this->getRechargeWithdrawalData($start_time, $end_time);
        }

        if (input()) {
            $user_statistics_data['days'] = json_decode($user_statistics_data['days'], true);
            $user_statistics_data['returns'] = json_decode($user_statistics_data['returns'], true);
//            $recharge_withdrawal_data['days'] = json_decode($recharge_withdrawal_data['days'], true);
//            $recharge_withdrawal_data['days'] = json_decode($recharge_withdrawal_data['days'], true);
//            $recharge_withdrawal_data['returns'] = json_decode($recharge_withdrawal_data['returns'], true);
            echo json_encode(['user_statistics_data' => $user_statistics_data, 'recharge_withdrawal_data' => $recharge_withdrawal_data]);
            exit;
        }
        return ZBuilder::make('table')
            ->addTimeFilter('money_recharge.create_time', [date("Y-m-d"), date("Y-m-d")], ['开始时间', '结束时间'])
            ->assign(['data' => $data,'user_statistics_data' => $user_statistics_data, 'recharge_withdrawal_data' => $recharge_withdrawal_data])
            ->setTemplate('statistics')
            ->fetch();

    }


    //用户统计
    public function getUserData($start_time, $end_time)
    {
        $new_reg['name'] = '新注册用户';
        $recharge_user['name'] = '充值用户';
        $purchase_tracking['name'] = '购买优投';
        $withdrawal_user['name'] = '提现用户';
        $active_user['name'] = '活跃用户';

        $for_user = Db::name('admin_user')->where('id', UID)->value('for_user');
        $where = [];
        if ($for_user) {
            $where['for_user'] = $for_user;
        }
        $days = create_date_range(date('Y-m-d', $start_time), date('Y-m-d', $end_time + 24 * 3600));

        //当天的数据-----------------------------------------------
//        用户统计
        $user_data_day = DataReportModel::where('create_time', '>', $end_time)->where($where)->find();
        if (empty($user_data_day)) {
            $user_data_day['new_reg'] = 0;
        }
//        跟单
        $user_data_day['purchase_tracking'] = FundOrderGs::where('create_time', '>', $end_time)
            ->where('status','>',0)
            ->count();

//        充值提现统计
        $user_statistics_day = RechargeWithdrawalModel::where('create_time', '>', $end_time)->where($where)->find();
        if (empty($user_statistics_day)) {
            $user_data_day['recharge_user'] = 0;
            $user_data_day['withdrawal_user'] = 0;
        }else{
            $user_data_day['recharge_user'] = $user_statistics_day['recharge_user'];
            $user_data_day['withdrawal_user'] = $user_statistics_day['withdrawal_user'];
        }
//        活跃用户
        $tody_cp_num = 0;
        $user_data_day['active_user'] = $tody_cp_num;
        //当天的数据-----------------------------------------------


        $end_time = $end_time + 24 * 3600 - 1;
        //时间范围数据------------------------
//        用户统计
        $user_data = DataReportModel::whereTime('create_time', 'between', [$start_time, $end_time])
            ->where($where)
            ->field("SUM(`new_reg`) as new_reg")
            ->find();
//        充值提现统计
        $user_statistics = RechargeWithdrawalModel::whereTime('create_time', 'between', [$start_time, $end_time])
            ->where($where)
            ->field("SUM(`recharge_user`) as recharge_user,SUM(`withdrawal_user`) as withdrawal_user")
            ->find();

        $purchase_data = FundOrderGs::
            where('status','>',0)
            ->count();

        $user_data['active_user'] = 0;
        $user_data['new_reg'] = $user_data['new_reg'] ?: 0;

        $user_data['recharge_user'] = $user_statistics['recharge_user'] ?: 0;
        $user_data['withdrawal_user'] = $user_statistics['withdrawal_user'] ?: 0;

        $user_data['purchase_tracking'] = $purchase_data ?: 0;

        foreach ($days as $key => $value) {
            $start_time = strtotime($value);
            $end_time = $start_time + 24 * 3600 - 1;
//            用户统计
            $info = DataReportModel::where('create_time', 'between time', [$start_time, $end_time])->where($where)->find();
            $info = empty($info) ? [] : $info->toArray();
            //            充值提现
            $infos = RechargeWithdrawalModel::where('create_time', 'between time', [$start_time, $end_time])->where($where)->find();
            $infos = empty($infos) ? [] : $infos->toArray();
            $infosa = FundOrderGs::where('create_time', 'between time', [$start_time, $end_time])->where('status','>',0)->count();

            $purchase_tracking['data'][$key] = $infosa;
//            $active_user['data'][$key] = $info ? $info['active_user'] : 0;
//            $infosa = FundOrderGs::where('create_time', 'between time', [$start_time, $end_time])->find();
//            $infosa = empty($infos) ? [] : $infos->toArray();

            $new_reg['data'][$key] = $info ? $info['new_reg'] : 0;

            $recharge_user['data'][$key] = $infos ? $infos['recharge_user'] : 0;
            $withdrawal_user['data'][$key] = $infos ? $infos['withdrawal_user'] : 0;
        }
        for ($j = 0; $j < 5; $j++) {
            switch ($j) {
                case 0:
                    $returns[$j]['name'] = $new_reg['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $new_reg['data'];
                    break;
                case 1:
                    $returns[$j]['name'] = $recharge_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $recharge_user['data'];
                    break;
                case 2:
                    $returns[$j]['name'] = $purchase_tracking['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $purchase_tracking['data'];
                    break;
                case 3:
                    $returns[$j]['name'] = $withdrawal_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = $withdrawal_user['data'];
                    break;
                case 4:    //活跃用户
                    $returns[$j]['name'] = $active_user['name'];
                    $returns[$j]['type'] = 'line';
                    $returns[$j]['data'] = [];
                    break;
            }
        }
        $returns = json_encode($returns);
        $days = json_encode($days);
        $user_statistics_data['days'] = $days;
        $user_statistics_data['returns'] = $returns;
        $user_statistics_data['user_data_day'] = $user_data_day;
        $user_statistics_data['user_data'] = $user_data;
        return $user_statistics_data;
    }

    //跟单购买
    public function getRechargeWithdrawalData($start_time, $end_time)
    {
//        $purchase_tracking['tracking_total_number'] = '跟单总购买数';
//        $purchase_tracking['tracking_day_number'] = '今日跟单购买数';
//        $purchase_tracking['balance_total_number'] = '总结算跟单数';
//        $purchase_tracking['balance_day_number'] = '今日结算跟单数';

        //当天的数据-----------------------------------------------

        $purchase_tracking['tracking_day_number'] = FundOrderGs::where('create_time', '>', $end_time)
            ->where('status','>',0)
            ->count();
        $purchase_tracking['balance_day_number'] = FundOrderGs::where('create_time', '>', $end_time)
            ->where('status','=',3)
            ->count();

        $purchase_tracking['tracking_total_number'] = FundOrderGs::where('status','>',0)
            ->count();
        $purchase_tracking['balance_total_number'] = FundOrderGs::where('status','=',3)
            ->count();
        return $purchase_tracking;
    }

}