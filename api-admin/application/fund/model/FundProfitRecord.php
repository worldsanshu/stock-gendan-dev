<?php
namespace app\fund\model;

use app\member\model\Bank as BankModel;
use app\money\model\Record;
use think\Db;
use think\model;
use app\common\service\UserService;

class FundProfitRecord extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public static function getList($page = 1, $map = [], $order = 'id desc')
    {
        $field = 'i.*,m.mobile,m.name as username,o.order_sn,t.name as trader_name,m.role_name';
        $list  = self::where($map)
            ->field($field)
            ->alias('i')
            ->join('member m', 'm.id = i.uid')
            ->join('fund_order_gs o', 'o.id = i.order_id')
            ->join('trader t', 't.id = o.trader_id')
            ->order($order)
            ->paginate();

        $res['list'] = $list;
        return $res ?: [];
    }



//  金额操作
    public static function saveData($parameter)
    {

        $data['uid'] = $parameter['mid'];
        $data['order_id'] = $parameter['order_id'];
        $data['money'] = $parameter['money'];  //金额
        $data['create_time'] = time();

        Db::startTrans();

        try {
//      记录订单
            $res1 = self::create($data);
//            减盈利
            FundOrderGs::where('id', $data['order_id'])->setDec('balance', $parameter['money']);
            if ($res1) {
                Db::commit();
                return ['status' => 1, 'message' => '提交成功'];
            } else {
                Db::rollback();
                return ['status' => 0, 'message' => '提交失败'];
            }
        } catch (\Exception $e) {
            Db::rollback();
            return ['status' => 0, 'message' => '数据异常'];
        }
    }


}

?>
