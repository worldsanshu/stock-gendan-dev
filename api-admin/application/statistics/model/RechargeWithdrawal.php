<?php
namespace app\statistics\model;

use think\Db;
use think\model;

class RechargeWithdrawal extends Model
{
    // 设置当前模型对应的完整数据表名称
//    protected $table = '';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //写入充值提现统计
    public static function appendRechargeWithdrawal($data = [])
    {
        //以充值的时间为准
        $start_time = strtotime(date('Y-m-d', $data['create_time']) . '00:00:00');
        $end_time = strtotime(date('Y-m-d', $data['create_time']) . '23:59:59');

//        $start_time = strtotime(date('Y-m-d').'00:00:00');
//        $end_time = strtotime(date('Y-m-d').'23:59:59');
        $info = self::where('create_time','between time', [$start_time, $end_time])->find();
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
                    self::where('id', $info['id'])->setInc($k, $v);
                }
            }
            return ['status' => 1, 'message' => '写入成功', 'data' => []];
        }
    }



}

?>
