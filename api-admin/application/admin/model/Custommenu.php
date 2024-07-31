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
 * 自定义菜单模型
 * @package app\admin\model
 */
class Custommenu extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'custom_menu';
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
        $data_list = self::view('custom_menu', '*,img_url img_url_text')
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

    public function getMenuArr($key='')
    {
        $domain = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];
        $list['home'] = [
            [
                'name' => '股票',
                'url' => '/pages/market-v2/market-v2',
                'img_url' => $domain.'/uploads/default/gp.png',
                'url_level' => '1',
                'url_type' => '1',
            ],
            [
                'name' => '基金',
                'url' => '/pages/copy-trading/copy-trading',
                'img_url' => $domain.'/uploads/default/card-jijin.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '合伙人中心',
                'url' => '/pages/agent-center/agent-center',
                'img_url' => $domain.'/uploads/default/card-5.png',
                'url_level' => '2',
                'url_type' => '1',
            ],

            [
                'name' => '合伙人制度',
                'url' => '/pages/index-partner/index-partner',
                'img_url' => $domain.'/uploads/default/card-5.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '在线充值',
                'url' => '/pages/user-center-recharge/user-center-recharge',
                'img_url' => $domain.'/uploads/default/card-4.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '优投规则',
                'url' => '/pages/index-trading-rules/index-trading-rules',
                'img_url' => $domain.'/uploads/default/card-6.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '在线客服',
                'url' => '/pages/customer-service/customer-service',
                'img_url' => $domain.'/uploads/default/card-8.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '公司介绍',
                'url' => '/pages/article-detail/article-detail?id=6',
                'img_url' => $domain.'/uploads/default/card-7.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '小金库',
                'url' => '/pages/zero-money-pass/zero-money-pass',
                'img_url' => $domain.'/uploads/default/xjk.png',
                'url_level' => '2',
                'url_type' => '1',
            ],

        ];

        $list['user'] = [
//            [
//                'name' => '充值',
//                'url' => '/pages/user-center-recharge/user-center-recharge',
//                'img_url' => $domain.'/uploads/default/recharge.png',
//                'url_level' => '2',
//                'url_type' => '1',
//            ],
             [
                'name' => '资金管理',
                'url' => '/pages/user-center-recharge/user-center-recharge',
                'img_url' => $domain.'/uploads/default/recharge.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '小金库',
                'url' => '/pages/zero-money-pass/zero-money-pass',
                'img_url' => $domain.'/uploads/default/xjk.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
//            [
//                'name' => '提现',
//                'url' => '/pages/user-center-withdraw/user-center-withdraw',
//                'img_url' => $domain.'/uploads/default/withdraw.png',
//                'url_level' => '2',
//                'url_type' => '1',
//            ],
            [
                'name' => '资金明细',
                'url' => '/pages/user-center-fund-details/user-center-fund-details',
                'img_url' => $domain.'/uploads/default/fund-details.png',
                'url_level' => '2',
                'url_type' => '1',
            ]
        ];
        $list['user_list'] = [
            [
                'name' => '自选列表',
                'url' => '/pages/user-center-stock-list/user-center-stock-list',
                'img_url' => $domain.'/uploads/default/stock-list.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '优投列表',
                'url' => '/pages/trade/trade',
                'img_url' => $domain.'/uploads/default/fund-list.png',
                'url_level' => '1',
                'url_type' => '1',
            ],
            [
                'name' => '合伙人中心',
                'url' => '/pages/agent-center/agent-center',
                'img_url' => $domain.'/uploads/default/agent-center.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
//            [
//                'name' => '钱包管理',
//                'url' => '/pages/user-center-wallet-management/user-center-wallet-management',
//                'img_url' => $domain.'/uploads/default/fund-details.png',
//                'url_level' => '2',
//                'url_type' => '1',
//            ],
            [
                'name' => '用户资料',
                'url' => '/pages/user-center-profile/user-center-profile',
                'img_url' => $domain.'/uploads/default/center-profile.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '推广提成',
                'url' => '/pages/recommended-friends/recommended-friends',
                'img_url' => $domain.'/uploads/default/recommended-friends.png',
                'url_level' => '2',
                'url_type' => '1',
            ],
            [
                'name' => '关于我们',
                'url' => '/pages/user-center-about/user-center-about',
                'img_url' => $domain.'/uploads/default/center-about.png',
                'url_level' => '2',
                'url_type' => '1',
            ]
        ];
        if($key){
            return $list[$key];
        }
        return $list;
    }

    public function getPropertyArr()
    {

    }

}
