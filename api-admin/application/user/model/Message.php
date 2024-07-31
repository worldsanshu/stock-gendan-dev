<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\user\model;

use think\Db;
use think\Model;

/**
 * 角色模型
 * @package app\admin\model
 */
class Message extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_message';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取当前用户未读消息数量
     * @return int|string
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getMessageCount()
    {
        return self::where(['status' => 0, 'uid_receive' => UID])->count();
    }

    /**
     * 获取当前用户未处理金额数量
     * @return int|string
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getMoneyCount($type=1)
    {
        switch ($type) {
            case 1:
//        提现记录
                return Db::name('money_withdraw')->where('status', 0)->count();
            case 2:
//                充值记录
                return Db::name('money_recharge')->where('status', 0)->count();
            default:
                break;
        }
    }
}
