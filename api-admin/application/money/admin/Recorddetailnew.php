<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------
namespace app\money\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\service\UserService;
use think\Db;

/**
 * 利息控制器
 * @package app\money\admin
 */
class Recorddetailnew extends Admin
{
    /**
     * 首页
     * @return mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->getAgentMap($map,'mid');
        $order = $this->getOrder();
        $input = input();
        if ($input){
//            ['order_no' => '订单号', 'member.name' => '姓名', 'member.mobile' => '手机号', 'member.email' => '邮箱']) // 设置搜索框
            if (isset($input['user_id']) && $input['user_id']) {
                $map[] = ['mid', '=', $input['user_id']];
            }
            if (isset($input['borrow_id']) && $input['borrow_id']) {
                $map[] = ['borrow_id', '=', $input['borrow_id']];
            }

            // 确保日期字符串被正确转换为时间戳
            $startTimestamp = isset($input['_filter_time_from']) ? strtotime($input['_filter_time_from']) : '';
            $endTimestamp = isset($input['_filter_time_to']) ? strtotime($input['_filter_time_to'] . ' 23:59:59') : ''; // 添加23:59:59确保包括全天
// 结合起始和结束时间进行筛选
            if ($startTimestamp && $endTimestamp) {
                // 当开始和结束时间都设置时，同时应用两个条件
                $map[] = ['addtime', 'between', [$startTimestamp, $endTimestamp]];
            } elseif ($startTimestamp) {
                // 仅设置了开始时间
                $map[] = ['addtime', '>=', $startTimestamp];
            } elseif ($endTimestamp) {
                // 仅设置了结束时间
                $map[] = ['addtime', '<=', $endTimestamp];
            }
        }
        empty($order) && $order = 'id desc';
        // 数据列表
//        $data_list = Db::name('stock_detail_new')->where($map)->order($order)->select();
        if (empty($map['addtime'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['addtime'][1][0]));
        }
        if (empty($map['addtime'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['addtime'][1][1]));
        }
        $data_list = Db::name("stock_detail_new")
          ->where($map)
          ->order($order)
          ->paginate();
        // 获取当前页的数据集合，并转换为数组进行修改
        $collection = $data_list->getCollection()->toArray(); // 或者 $collection = $data_list->items(); 根据实际版本而定
        foreach ($collection as &$item) {
            // 修改数据集合中的每个元素的 interest 字段
            $item['interest'] = $item['interest'] / 100;
        }
        unset($item); // 取消引用

// 将修改后的数据集合重新赋值给分页对象
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setSearchArea([
                ['text', 'borrow_id', '子账号id'],
                ['text', 'mid', '用户id'],
                ['daterange', 'addtime', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']]
            ])
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['borrow_id', '子账号id'],
            ['mid', '用户id'],
            ['interest', '平台收入（手续费）元'],
            ['info', '内容'],
            ['addtime', '发生时间', 'datetime'],
          ])
          ->hideCheckbox()
          ->setTableName('stock_detail_new')
          ->setRowList($collection)
            ->setPages($page) // 设置分页数据
          ->fetch(); // 渲染模板
    }
}