<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author gs101
namespace app\market\model;

use think\Db;
use think\Model;

class StockSubAccountRisk extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBACCOUNT_RISK__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /*
     * 返回子账号风控信息
     * $sub_id 子账号
     */
    public function get_risk($sub_id)
    {
        $res = Db::name('stock_subaccount_risk')->where(['stock_subaccount_id' => $sub_id])->find();
        return $res;
    }
}