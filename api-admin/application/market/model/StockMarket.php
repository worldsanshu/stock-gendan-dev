<?php

namespace app\market\model;

use think\Model;
use think\Db;

class StockMarket extends Model
{
    // 设置当前模型对应的完整数据表名称stock_market_bat
    protected $table = '__STOCK_MARKET__';
    protected $pk = 'code';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /*
    * 单个存储和更新行情市场数据
    */
    public function acttion_market($code, $post)
    {
        //Logs::log('market_bat_insert',['data'=>$post],'Market_bat');
        $code_info = Db::name('stock_market')->where(['code' => $code])->find();
        if ($post) {
            if (!$code_info) {
                $this->saveAll($code_info, false);
            } elseif ($code_info) {
                $this->saveAll($code_info);
            }
            return $post;
        } elseif (!$post && $code_info) {
            return $code_info;
        }
        return false;
    }

    /*
     *
     */
    public function market_by_code($code)
    {
        if ($code) {
            $data = z_market($code);
            $info = $this->acttion_market($code, $data);
            if ($info) {
                return $info;
            }
        }
        return false;
    }
}