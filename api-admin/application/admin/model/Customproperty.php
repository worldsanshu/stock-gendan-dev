<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Db;
use think\Model;

/**
 * 自定义资产显示模型
 * @package app\admin\model
 */
class Customproperty extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'custom_property';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有日志
     * @param array $map 条件
     * @param string $order 排序
     * @return \think\Paginator
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getAll($map = [], $order = '')
    {
        $data_list = self::view('custom_property', '*,img_url img_url_text')
            ->where($map)
            ->order($order)
            ->paginate();
        return $data_list;
    }

    public function getImgurlTextAttr($value)
    {
        $path   = Db::name('admin_attachment')->where('id', $value)->value('path');
        $domain = request()->domain();
        return $path ? $domain . '/' . $path : '';
    }

    public function getPropertyArr()
    {
        $domain = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];


        $list = [
            [
                'name' => '账户余额',
                'url' => '',
                'img_url' => $domain.'/uploads/default/account-balance.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '保证金',
                'url' => '',
                'img_url' => $domain.'/uploads/default/bond.png',
                'url_level' => '1',
                'url_type' => '1',
            ],
            [
                'name' => '活动金',
                'url' => '',
                'img_url' => $domain.'/uploads/default/activity-gold.png',
                'url_level' => '1',
                'url_type' => '1',
            ],
            [
                'name' => '冻结资产',
                'url' => '',
                'img_url' => $domain.'/uploads/default/freeze.png',
                'url_level' => '1',
                'url_type' => '1',
            ],
            [
                'name' => '持仓资产',
                'url' => '',
                'img_url' => $domain.'/uploads/default/fund.png',
                'url_level' => '1',
                'url_type' => '1',
            ],
        ];

        return $list;
    }


}
