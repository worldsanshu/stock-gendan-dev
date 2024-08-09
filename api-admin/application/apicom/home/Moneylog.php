<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace app\apicom\home;

use app\money\model\Money;
use think\db;

class Moneylog extends Common
{
    /**
     * 资金明细
     * @return [type] [description]
     */
    public function index()
    {
        // $post=input();
//        if (!MID) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        // 获取查询条件
        $map = $this->getMap();
        if (isset($map['r.create_time'][1])) {
            $map['r.create_time'][1][0] = strtotime($map['r.create_time'][1][0]);
            $map['r.create_time'][1][1] = strtotime($map['r.create_time'][1][1]);
        }
        $map['mid'] = MID;
        $where = [];
        $keyword = input('keyword');

//        if (!empty(input('keyword'))) {
//            if(input('keyword') == 435){
//                $map['r.type'] = ['in','35','36','37','98'];
////                $map['r.affect_activity'] = ['>','0'];
//                $where[] = ['r.affect_activity','>','0'];
//            }else if(input('keyword') == 456){
//
//            $map['r.type'] = ['in', '2','8','33','97'];
//          }else{
//
//                $map['r.type'] = input('keyword');
//            }
//        }
        switch ($keyword) {
            case 1:
//                充值
                $map['r.type'] = $keyword;
                break;
            case 3:
                // 提现
                $map['r.type'] = ['in', '2','3','4'];
                break;
            case 456:
                // 冻结
                $map['r.type'] = ['in', '2','8','33','97'];
                break;
            case 20:
                // 结算
                $map['r.type'] = ['in', '96'];
                break;
            case 10:
                // 佣金
                $map['r.type'] = ['in', '91','92','93'];
                break;
            case 435:
                // 活动金
                $map['r.type'] = ['in','35','36','37','98','111'];
                break;
            case 457:
                // 解冻金
                $map['r.type'] = ['in','99'];
                break;
            // ...
            default:
                // 全部，不包含活动金,提现成功,优投解冻
                $where[] = ['r.type','not in', ['35','36','37','98','111']];
                break;
        }
        $order = 'r.id desc';
        $page = intval(input("page"));
        $page = $page ? $page : 1;
        $offset = $page;
        // 数据列表
        $data_list = Db::name('money_record')
          ->alias('r')
          ->where($map)
          ->where($where)
          ->field('r.*')
          ->order($order)
          ->page($offset, 10)
          ->select();
        foreach ($data_list as $k => $v) {
            $data_list[$k]['happend_time'] = getTimeFormt($v['create_time'], 4);
            $data_list[$k]['happend_date'] = getTimeFormt($v['create_time'], 5);
            $data_list[$k]['type_name'] = getTypeNameForMoney($v['type']);
         $data_list[$k]['activity_account'] = bcdiv($v['activity_account'], 100, 2);
            $data_list[$k]['affect'] = bcdiv($v['affect'], 100, 2);
            $data_list[$k]['account'] = bcdiv($v['account'], 100, 2);
            $data_list[$k]['affect_activity'] = bcdiv($v['affect_activity'], 100, 2);
            $data_list[$k]['fee'] = bcdiv($v['fee'], 100, 2);
        }
        ajaxmsg('数据列表', 1, $data_list, true, ['msgCode' => 'L0129']);
    }


    //获取明细
    public function interest_record()
    {
        // 获取查询条件
        $map = $this->getMap();
        $map['mid'] = MID;
        $where = [];
        $map['r.type'] = ['in','104','105','106','107'];
        $order = 'r.id desc';
        $page = intval(input("page"));
        $page = $page ? $page : 1;
        $offset = $page;
        // 数据列表
        $list = Db::name('money_record')
            ->alias('r')
            ->where($map)
            ->where($where)
            ->field('r.*')
            ->order($order)
//            ->page($offset, 10)
//            ->select();
            ->paginate();
        $data_list = $list->toArray();
        $basic = Db::name('interest_basic')->find();
        foreach ($data_list['data'] as $k => &$v) {
            $v['happend_time'] = getTimeFormt($v['create_time'], 4);
            $v['happend_date'] = getTimeFormt($v['create_time'], 5);
            $v['type_name'] = $basic['name'].getTypeNameForMoney($v['type']);
            $v['activity_account'] = bcdiv($v['activity_account'], 100, 2);
            $v['affect'] = bcdiv($v['affect'], 100, 2);
            $v['account'] = bcdiv($v['account'], 100, 2);
            $v['affect_activity'] = bcdiv($v['affect_activity'], 100, 2);
            $v['fee'] = bcdiv($v['fee'], 100, 2);
        }
        ajaxmsg('数据列表', 1, $data_list, true, ['msgCode' => 'L0129']);
    }
}