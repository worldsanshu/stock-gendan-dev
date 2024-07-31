<?php
namespace app\member\model;

use app\member\model\Member as MemberModel;
use think\Db;
use think\model;

class UserReport extends Model
{
    // 设置当前模型对应的完整数据表名称
//    protected $table = '__USERREPORT__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //写入会员报表数据
    public function appendUserReport($uid = '', $data = [])
    {
        $start_time = strtotime(date('Y-m-d', $data['create_time']) . '00:00:00');
        $end_time = strtotime(date('Y-m-d', $data['create_time']) . '23:59:59');
        $map[]=['create_time','between time', [$start_time, $end_time]];
//        $start_time = strtotime(date('Y-m-d').'00:00:00');
//        $end_time = strtotime(date('Y-m-d').'23:59:59');
        $info = self::where('uid', $uid)->where($map)->find();
        if ($data) {
            foreach ($data as $key => $value) {
                MemberModel::where('id', $uid)->setInc($key, $value);
            }
        }
        if (!$info) {
            $data['uid'] = $uid;
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
