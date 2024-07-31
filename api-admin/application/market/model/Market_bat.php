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

class Market_bat extends Model
{
    // 设置当前模型对应的完整数据表名称stock_market_bat
    protected $table = '__STOCK_MARKET_BAT__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /*
    * 存储行情市场数据
    */
    public function add_market_bat($post)
    {
        //Logs::log('market_bat_insert',['data'=>$post],'Market_bat');
        if ($post) {
            $row = array();
            foreach ($post as $k => $v) {
                $code_info = db('stock_market_bat')->field('code')->where(['code' => $v['code']])->find();
                if (!$code_info) {
                    $row[$k] = $v;
                }
            }
            if ($row) {
                Db::name('stock_market_bat')->insertall($row, true);
            }
        }
        return true;
    }

    /*
     * 存储minute_k记录
     */
    public function add_market_minute_k($code, $data)
    {
        $info = Db::name('stock_market_minute_k')->where(['code' => $code])->find();
        if (!$info) {
            $row = array();
            foreach ($data as $k => $v) {
                $row[$k]['code'] = $code;
                $row[$k]['price'] = $v['price'];
                $row[$k]['price_equal'] = $v['price_equal'];
                $row[$k]['volume'] = $v['volume'];
                $row[$k]['time'] = $v['time'];
                $row[$k]['ctime'] = time();
            }
            Db::name('stock_market_minute_k')->insertall($row, true);
        }
        return true;
    }
}