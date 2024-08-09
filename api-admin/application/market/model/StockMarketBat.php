<?php

namespace app\market\model;

use think\Db;
use think\Model;
use util\Logs;

class StockMarketBat extends Model
{
    // 设置当前模型对应的完整数据表名称stock_market_bat
    protected $table = '__STOCK_MARKET_BAT__';
    protected $pk = 'code';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /*
    * 批量存储和更新行情市场数据
     * @codes string 多个code
    */
    public function action_market_bat($codes, $post)
    {
        Logs::log('action_market_bat', ['data' => $post], 'StockMarketBat');
        if ($post) {
            $save_row = array();
            $update_row = array();
            foreach ($post as $k => $v) {
                $code_info = Db::name('stock_market_bat')->where(['code' => $v['code']])->find();
                if (!$code_info) {
                    $save_row[$k] = $v;
                } elseif ($code_info) {
                    $update_row[$k] = $v;
                }
            }
            if ($save_row) {
                $this->saveAll($save_row, false);
            }
            if ($update_row) {
                $this->saveAll($update_row);
            }
            return $post;
        } elseif (!$post && $codes) {
            $codes_arr = explode(",", $codes);
            $field = 'code,name,yesterday_price,open_price,national_debt,current_price,buy_one_price,buy_chang_price,buy_two_price,buy_three_price,buy_one_amount,buy_two_amount,buy_three_amount,buy_four_price,buy_four_amount,time,turnover,volume,lowest,highest,info,debt_sign,currency,type,mini_trans,exchange_code,sell_chang_price,sell_five_amount,sell_four_amount,sell_five_price,sell_three_amount,sell_two_amount,sell_one_amount,buy_five_price,buy_five_amount,sell_four_price,sell_one_price,sell_two_price,sell_three_price';
            $data = Db::name('stock_market_bat')->field($field)->where(['code' => ['in', $codes_arr]])->select();
            Logs::log('action_market_bat_for_table', ['data' => $data, 'sql' => Db::table('stock_market_bat')->getLastSql()], 'StockMarketBat');
            return $data;
        }
        return false;
    }

    /*
     * 从stock_list表中获取code批量下载相关信息到本地
     */
    public function get_data_from_code()
    {
        $page_size = 50;
        for ($page = 1; $page <= 64; $page++) {
            $lists = Db::name('stock_list')
              ->field('code')
              ->limit($page, $page_size)
              ->select();
            $code = array();
            foreach ($lists as $k => $v) {
                $code[] = $v['code'];
                $data = z_market_bat($v['code']);
                $this->action_market_bat($v['code'], $data);
            }
        }
    }
}