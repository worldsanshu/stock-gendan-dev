<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
namespace app\market\model;

use think\Db;
use think\Model;
use util\Logs;

class StockMarketMonthK extends Model
{
    // 设置当前模型对应的完整数据表名称stock_market_bat
    protected $table = '__STOCK_MARKET_MONTH_K__';
    protected $pk = 'id';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /*
     * 批量存储和更新minute_k记录
     */
    public function action_market_month_k($code, $data)
    {
        Logs::log('action_market_month_k', ['data' => $data], 'StockMarketMonthK');
        $info = Db::name('stock_market_month_k')->where(['code' => $code])->find();
        if (!$info && $data) {
            foreach ($data as $k => $v) {
                $data[$k]['code'] = $code;
            }
            $this->saveAll($data, false);
            return $data;
        } elseif ($info && $data) {
            $list = Db::name('stock_market_month_k')->where(['code' => $code])->select();
            foreach ($data as $k => $v) {
                $data[$k]['id'] = $list[$k]['id'];
                $data[$k]['code'] = $code;
            }
            $this->saveAll($data);
            return $data;
        } elseif ($info && !$data) {
            $list = Db::name('stock_market_minute_k')->where(['code' => $code])->select();
            $data = array();
            if ($list) {
                foreach ($list as $k => $v) {
                    $data[$k]['time'] = $list[$k]['time'];
                    $data[$k]['open'] = $list[$k]['open'];
                    $data[$k]['high'] = $list[$k]['high'];
                    $data[$k]['low'] = $list[$k]['low'];
                    $data[$k]['close'] = $list[$k]['close'];
                    $data[$k]['volume'] = $list[$k]['volume'];
                }
                return $data;
            }
        }
        return false;
    }
}