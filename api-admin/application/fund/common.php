<?php

use app\fund\model\FundDayline as FundDaylineModel;


/**
 * Desc : 投资中的金额
 * User : you name
 * Date : 2024-06-22 17:59
 *
 * @param $order_id
 */
function Investing($uid)
{

    $FundDaylinelist = FundDaylineModel::where([
        ['uid', '=', $uid],
        ['status', 'in', '1,2']
    ])->select();

    $Usedamount = 0;
    if ($FundDaylinelist) {
        foreach ($FundDaylinelist as $uv) {
            $Usedamount += $uv['num'] * $uv['buy_price'];
        }
    }
    return $Usedamount;
}


