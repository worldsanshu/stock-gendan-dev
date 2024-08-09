<?php
namespace app\fund\model;

use app\member\model\Bank as BankModel;
use app\money\model\Record;
use think\Db;
use think\model;
use app\common\service\UserService;

class FundInvestmentRecord extends Model
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


}

?>
