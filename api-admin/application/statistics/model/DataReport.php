<?php
namespace app\statistics\model;

use app\member\model\Member;
use app\stock\model\Borrow;
use think\Db;
use think\model;

class DataReport extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__DATA_REPORT__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = false;

    //写入数据
    public static function appendDataReport($data = [], $type = 1)
    {
        //以充值的时间为准
        $start_time = strtotime(date('Y-m-d', $data['create_time']) . '00:00:00');
        $end_time = strtotime(date('Y-m-d', $data['create_time']) . '23:59:59');

        $info = self::where('create_time','between time', [$start_time, $end_time])->find();
        $data['create_time'] = $start_time;
        if (!$info) {
            $result = self::create($data);
            if ($result->id) {
                return ['status' => 1, 'message' => '写入成功', 'data' => []];
            } else {
                Db::rollback();
                return ['status' => 0, 'message' => '写入失败'];
            }
        } else {
            if ($data) {
                unset($data['create_time']);
                foreach ($data as $k => $v) {
                    if ($type == 1) { //type=1为自增字段,type=2更新
                        self::where('id', $info['id'])->setInc($k, $v);
                    } else {
                        self::where('id', $info['id'])->update($data);
                    }
                }
            }
            return ['status' => 1, 'message' => '写入成功', 'data' => []];
        }
    }

    //获取当天的报表数据
    public static function getDayData($type = 1)
    {
        $day_start_time = strtotime(date('Y-m-d'));
        $ids = Member::where('role_name', '白名单')->column('id');
        $where = 'status >=1 and status <=2';
        $data['effective_contract'] = Borrow::where($where)->where('create_time', '>=', $day_start_time)->where('member_id', 'not in', $ids)->count();
        $data['new_open_contract'] = Borrow::where('status', 1)->where('create_time', '>=', $day_start_time)->where('member_id', 'not in', $ids)->count();
        $data['settlement_contract'] = Borrow::where('status', 2)->where('create_time', '>=', $day_start_time)->where('member_id', 'not in', $ids)->count();
        $data['effective_user'] = Member::where('create_time', '>=', $day_start_time)->where('id', 'not in', $ids)->count();
        $month_time = time() - 30 * 24 * 3600; //一个月时间不登录的用户
        $data['user_run'] = Member::where('last_login_time', '<=', $month_time)->count();
        $data['oline'] = Member::where('last_login_time', '>=', $day_start_time)->count();
        $list = Db::name('stock_position')->where(['buying' => 0])->select();
        $stock_price = 0;
        foreach ($list as $key => $value) {
            $price = $value['stock_count'] * $value['now_price'];
            $stock_price += $price;
        }
        $data['stock_price'] = $stock_price;
        if ($type == 2) { //type等于2更新当天的数据到数据库
            $data['create_time'] = $day_start_time;
            self::appendDataReport($data, 2);
        }
        return $data;
    }

    //获取代理报表数据
    public function getAgentDayData($uids)
    {
        $day_start_time = strtotime(date('Y-m-d'));
        $map1['member_id'] = array('in', $uids);
        $map2['agent_far'] = array('in', $uids);
        $map3['mid'] = array('in', $uids);
        $map4['uid'] = array('in', $uids);
        $where = 'status >=1 and status <=2';
        $data['effective_contract'] = Borrow::where($where)->where('create_time', '>=', $day_start_time)->where($map1)->count();
        $data['new_open_contract'] = Borrow::where('status', 1)->where('create_time', '>=', $day_start_time)->where($map1)->count();
        $data['settlement_contract'] = Borrow::where('status', 2)->where('create_time', '>=', $day_start_time)->where($map1)->count();
        $borrow_money = Borrow::where($map1)->sum('borrow_money');
        $data['borrow_money'] = $borrow_money / 100;
        $data['effective_user'] = Member::where('create_time', '>=', $day_start_time)->where($map2)->count();
        $recharge_money = Db('money_recharge')->where($map3)->where('create_time', '>=', $day_start_time)->sum('money');
        $data['day_recharge'] = $recharge_money / 100;
        $withdraw_money = Db('money_withdraw')->where($map3)->where('create_time', '>=', $day_start_time)->sum('money');
        $data['day_withdraw'] = $withdraw_money / 100;
        $stock_subaccount_ids = Db('stock_subaccount')->where($map4)->column('id');
        $map5['sub_id'] = array('in', $stock_subaccount_ids);
        $data['stock_position_user_num'] = Db('stock_position')->where($map5)->count();
        $map6['stock_subaccount_id'] = array('in', $stock_subaccount_ids);
        $data['profit_loss'] = Db('stock_subaccount_money')->where($map6)->sum('return_money');
        $day_money_transfer = Db('money_transfer')->where($map3)->where('create_time', '>=', $day_start_time)->sum('money');
        $data['day_money_transfer'] = $day_money_transfer / 100;
        return $data;
    }
}

?>
