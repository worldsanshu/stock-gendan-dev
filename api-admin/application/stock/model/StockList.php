<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
namespace app\stock\model;

use think\Db;
use think\facade\Cache;
use think\model;

//use think\facade\Cache;
class StockList extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_LIST__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //更新股票数据
    public function updateStockData($stock_list = [])
    {
        if ($stock_list) {
            $sql = "REPLACE INTO `lmq_stock_list` (`title`,`code`,`pinyin`,`add_time`,`target_uid`,`target_name`,`thscode`) values ";
            $is_add = 0;
            $list_code = Db::name('stock_list')->column('code');
            foreach ($stock_list as $key => $value) {
                $thscode = $value['thscode'];
                $title = $value['title'];
                $code = $value['code'];
                $pinyin = $value['pinyin'];
                if (!in_array($code, $list_code) && $code) {
                    $is_add = 1;
                    $sql .= "('" . $title . "','" . $code . "','" . $pinyin . "','" . time() . "',1,'admin','" . $thscode . "'),";
                }
            }
            if ($is_add == 1) {
                $sql = substr($sql, 0, strlen($sql) - 1);
                Db::query($sql);
            }
        }
        //每次更新都将缓存清除
        $key = 'jd_stock_lists';
        Cache::rm($key);
        return 1;
    }

    //更新股票上市时间
    public static function updateListTime($list)
    {
        foreach ($list as $key => $value) {
            $thscode = $key;
            $list_time = $value;
            $update_data = [];
            $update_data['list_time'] = $list_time;
            self::where('thscode', $thscode)->update($update_data);
        }
        return true;
    }

    //更新股票数据
    public static function updateStockData2($searchstring = '', $searchtype = '', $act = '')
    {
        ini_set('max_execution_time', 0);
        $post_data = [];
        $post_data['searchstring'] = $searchstring;
        $post_data['searchtype'] = $searchtype;
        $res = iApi_curl($act, $post_data);
        $stock_list = $res['tables'][0]['table']['股票代码'] ?? [];
        $stock_list_name = $res['tables'][0]['table']['股票简称'] ?? [];
        $list = Db::name('stock_list2')->column('code');
        dump($list);
        exit;
        //批量更新金额
        $sq2 = '';
        $sql2 = "UPDATE `lmq_stock_list2` SET `title` = CASE `code`";
        $sql3 = '';
        $sql3 .= "END,`own_fee` = CASE `number`";
        $sql_str2 = "";
        if ($stock_list) {
            $sql = "insert  into `lmq_stock_list2` (`title`,`code`,`pinyin`,`add_time`,`target_uid`,`target_name`,`thscode`) values ";
            $is_add = 0;
            foreach ($stock_list as $key => $value) {
                $title = $stock_list_name[$key] ?? '';
                $code = explode('.', $value)[0];
                $pinyin = '';
                $pinyin = pinyin_long($title);
                $array[$key]['thscode'] = $value;
                $array[$key]['title'] = $title;
                $array[$key]['code'] = $code;
                $array[$key]['pinyin'] = $pinyin;
                $sql2 .= " WHEN '$k1' THEN " . $v1['fee'] . "\n";
                $sql3 .= "WHEN '$k1' THEN " . $v1['own_fee'] . "\n";
                $sql_str2 .= "'$k1',";
                if (!Db::table('lmq_stock_list')->where('code', $code)->count()) {
                    $is_add = 1;
                    $sql .= "('" . $title . "','" . $code . "','" . $pinyin . "','" . time() . "',1,'admin','" . $value . "'),";
                }
            }
            $sql_str1 = substr($sql_str2, 0, strlen($sql_str2) - 1);
            $sql2 .= 'END WHERE `number` IN (' . $sql_str2 . ')';
            $sql_str = $sql1 . $sql2;
            Db::query($sql_str);
            if ($is_add == 1) {
                $sql = substr($sql, 0, strlen($sql) - 1);
                Db::query($sql);
            }
            $data['stock_list'] = $array;
            $data['stock_list_name'] = $stock_list_name;
            $json_data = json_encode($data);
            $url = "http://8.134.176.49/stock/crond/autoScript";
            http_post_json($url, $json_data);
            $url = "http://www.6789ht.com/stock/crond/autoScript";
            http_post_json($url, $json_data);
        }
        return 1;
    }
}