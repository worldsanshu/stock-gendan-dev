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
namespace app\fund\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundOrderGs as FundOrderGsModel;
use app\fund\model\Trader as TraderModel;
use app\fund\model\TraderOrder as TraderOrderModel;
use app\stock\model\StockList as StockListModel;
use think\Db;
use think\helper\Hash;

/**
 * 导师操盘管理控制器控制器
 * @package app\member\admin
 */
class Traderorder extends Admin
{

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'o.id desc';
        $field = 'o.*,t.type as teacher_type';
        // 数据列表
        $data_list = TraderOrderModel::alias('o')
            ->join('trader t', 'o.trader_id = t.id')
            ->where($map)->field($field)->order($order)->paginate();

        foreach ($data_list as $v) {

            switch ($v['status']) {
                case 0:
                    $status_text = '<span class="label label-default">待执行</span>';
                    break;
                case 1:
                    $status_text = '<span class="label label-danger">已执行</span>';
                    break;
                case 2:
                    $status_text     = '<span class="label label-warning">已买入</span>';
                    $impact_quantity = "<p style='font-size: 10px'>订单数:" . $v['impact_quantity'] . "</p>";
                    break;
                case 3:
                    $status_text     = '<span class="label label-success">已卖出</span>';
                    $impact_quantity = "<p style='font-size: 10px'>订单数:" . $v['impact_quantity'] . "</p>";
                    break;
                default:
                    // 如果没有匹配的值
            }

            $v['status_num'] = "<p>" . $status_text . "</p>" . $impact_quantity;
        }

        $btn_buy      = [
            'title'      => '上股',
            'icon'       => 'fa fa-fw fa-key',
            'class'      => 'btn btn-xs btn-warning ajax-get',
            'data-title' => '执行后，查找符合条件当日可以买入订单进行批量买入。',
            'href'       => url('access_buy', ['ids' => '__id__', 'status' => '',])
        ];
        $btn_sell     = [
            'title'      => '卖出',
            'icon'       => 'fa fa-fw fa-key',
            'class'      => 'btn btn-xs btn-success ajax-get',
            'data-title' => '执行后，查找符合条件当日可以卖出订单进行批量买入。',
            'href'       => url('access_sell', ['ids' => '__id__'])
        ];
        $btn_buy_sell = [
            'title'      => '自动买入卖出',
            'icon'       => 'fa fa-fw fa-location-arrow',
            'class'      => 'btn btn-xs btn-danger ajax-get confirm',
            'href'       => url('access', ['id' => '__id__']),
            'data-title' => '执行后，会根据卖出交易日匹配找到对应的优投订单去执行。'
        ];

        $buy_url      = url('access_buy', ['ids' => '__id__']);
        $btn_sell_url = url('access_sell', ['ids' => '__id__']);

        $btn_edit_url = url('edit', ['id' => '__id__']);

        //已买
        $status_2 = '<div class="btn-group">
<a title="" icon="fa fa-fw fa-key" class="btn btn-xs  ajax-get disabled-link" href="' . $buy_url . '"  data-toggle="tooltip" data-original-title="已执行买入操作"><i class="fa fa-fw fa-key"></i> 上股 </a>
    <a
    title="" icon="fa fa-fw fa-key" class="btn btn-xs btn-success ajax-get confirm" href="' . $btn_sell_url . '"  _data-toggle="tooltip" data-original-title="卖出"><i class="fa fa-fw fa-key"></i> 卖出</a>

        <a title="" icon="fa fa-pencil" class="btn btn-xs btn-default" href="' . $btn_edit_url . '" target="_self" _tag="edit" data-toggle="tooltip" style="    width: 102px;    display: block;    margin: 0 auto;"
        data-original-title="编辑"><i class="fa fa-pencil"></i> 编辑 </a>
      
</div>';

        //已卖
        $status_3     = '<div class="btn-group">
<a title="" icon="fa fa-fw fa-key" class="btn btn-xs btn-warning ajax-get disabled-link" href="' . $buy_url . '" data-original-title="买入"><i class="fa fa-fw fa-key"></i> 上股 </a>
    <a 
    title="" icon="fa fa-fw fa-key" class="btn btn-xs  ajax-get  disabled-link" href="' . $btn_sell_url . '"  data-original-title="已执行卖出操作"><i class="fa fa-fw fa-key"></i> 卖出</a>

        <a title="" icon="fa fa-pencil" class="btn btn-xs btn-default" href="' . $btn_edit_url . '" target="_self" _tag="edit" data-toggle="tooltip" style="    width: 102px;    display: block;    margin: 0 auto;"
        data-original-title="编辑"><i class="fa fa-pencil"></i> 编辑 </a>
      
</div>';
        $css_disabled = <<<EOF
           <style>
              .disabled-link {
    pointer-events: none;
    cursor: default;
      color: lightgrey;
}
           </style>
EOF;
        $btn_special  = [
            'title' => '特殊会员独立上股',
            'icon'  => 'fa fa-fw fa-cny',
            'class' => 'btn btn-success',
            'href'  => url('fundordergs/special')
        ];

        return ZBuilder::make('table')
            ->addColumns([ // 批量添加数据列
                           ['date', '交易日'],
                           ['trader_name', '导师名称'],
                           ['teacher_type', '产品类型', 'status', '', [1 => '每日优投', 2 => '一键优投']],
                           ['stock_name', '股票名称'],
                           ['stock_code', '股票代码'],
                           ['buy_time', '买入时间'],
                           ['buy_price', '买入价格'],
                           ['sell_time', '卖出时间'],
                           ['sell_price', '卖出价格'],
                           ['position', '仓位'],
                           ['type', '操盘类型', 'status', '', [1 => '批量操盘', 2 => '特殊会员操盘']],
                           ['status_num', '执行状态/数量'],
                           ['create_time', '生成时间', 'datetime'],
                           ['right_button', '操作', 'btn']
            ])
            ->setTableName('trader_order')
            ->addTopButtons('add')
            ->addRightButton('custom', $btn_buy)
            ->addRightButton('custom', $btn_sell)
//            ->addRightButton('custom', $btn_buy_sell)
//            ->replaceRightButton(['status' => 3], $status_3)
            ->replaceRightButton(['status' => 2], $status_2)
            ->replaceRightButton(['status' => 3], '<button class="btn btn-danger btn-xs" type="button" disabled>无法操作</button>')
            ->replaceRightButton(['status' => 1], '<button class="btn btn-danger btn-xs" type="button" disabled>无法操作</button>')
            ->addRightButtons(['edit', 'delete'])
            ->hideCheckbox()
            ->setColumnWidth('right_button', 130)
            ->setSearchArea([

                ['text', 'trader_name', '操盘师', 'like'],
                ['daterange', 'buy_time', '买入时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['daterange', 'date', '卖出日期', '', '', ['format' => 'YYYY-MM-DD HH:mm']],

            ])
            ->addTopButton('custom', $btn_special)  // 添加批量买入处理按钮
            ->setExtraCss($css_disabled)
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    /***
     * 每日购买确认
     * access
     **/
    public function add($ids = null)
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data       = input();
            $datainsert = [];
            // 验证
            $result = $this->validate($data, 'Fund.traderorder');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            if ($data['position'] > 100 || $data['position'] < 0) {
                $this->error('仓位比例不能小于0或者操作100');
            }
            if (isset($data['buytime']) && !empty($data['buytime'])) {

                $data['date']       = date("Y-m-d", strtotime($data['buytime']));
                $datainsert['date'] = $data['data'];
                $transaction_date   = Db::name('stock_trade_date')->where("date", $data['date'])->find();
                if (!$transaction_date) {
                    $this->error('选定的日期不是交易日');
                }
            }

            if (isset($data['selltime']) && isset($data['buytime']) && $data['selltime'] !== '') {

                if (strtotime($data['buytime']) > strtotime($data['selltime'])) {
                    $this->error('卖出时间不能比买入的时间早');
                }
                $transaction_date = Db::name('stock_trade_date')->where("date", date("Y-m-d", strtotime($data['selltime'])))->find();
                if (!$transaction_date) {
                    $this->error('选定的日期不是交易日');
                }
            }
            if (
                isset($data['selltime']) && isset($data['buytime']) && $data['selltime'] !== '' && $data['buytime'] !== '' &&

                date("Y-m-d", strtotime($data['buytime'])) == date("Y-m-d", strtotime($data['selltime']))) {
                $this->error('买卖时间不能为同一天');
            }

            $TraderOrderlist = TraderOrderModel::where("date", $data['date'])->where('trader_id', $data['trader'])->where('type', 1)->find();

            if ($TraderOrderlist) {
                $this->error('该导师当天交易操作记录已生成无法重新生成，但可编辑');
            }

            $datainsert['position'] = $data['position'];

            $datainsert['buy_time']  = $data['buytime'];
            $datainsert['buy_price'] = $data['buyprice'];
            if (!empty($data['selltime'])) {
                $datainsert['sell_time'] = $data['selltime'];
            }

            $datainsert['sell_price']  = $data['sellprice'];
            $datainsert['date']        = $data['date'];
            $datainsert['trader_id']   = $data['trader'];
            $datainsert['trader_name'] = TraderModel::get($data['trader'])['name'];
            $stockinfo                 = StockListModel::where('code', $data['stockcode'])->find();
            $datainsert['stock_name']  = $stockinfo['title'];
            $datainsert['stock_code']  = $data['stockcode'];
            $datainsert['create_time'] = time();
            TraderOrderModel::insert($datainsert);

            $this->success('操作成功', cookie('__forward__'));
        }

        $stocklist = StockListModel::where('status', 1)->select();
        $stock     = [];
        foreach ($stocklist as $v) {
            $stock[$v['code']] = $v['title'];
        }

        $Trader_list = TraderModel::where('status', 1)->select();
        $Traderlist  = [];
        foreach ($Trader_list as $v) {

            $Traderlist[$v['id']] = $v['name'] . "(" . traderDocumentaryArray()[$v['type']] . ")";
        }

        return ZBuilder::make('form')->setPageTitle('每日操作记录') // 设置页面标题
        ->addFormItems([ // 批量添加表单项

                         ['select', 'trader', '导师名称', '', $Traderlist],
                         ['select', 'stockcode', '股票', '', $stock],
                         ['date', 'buytime', '买入时间'],
                         ['number', 'buyprice', '买入价格'],
                         ['number', 'position', '买入仓位比例(%)', '如果为空或0，则自动根据等级获取等级最大的仓位比例；动态计算股数比例(%)，如果余额不足，则自动计算的则直接取最大值的整数', 0, 10, 100, 1],
                         ['date', 'selltime', '卖出时间'],
                         ['number', 'sellprice', '卖出价格'],

        ])
            ->fetch();
    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        $info = TraderOrderModel::where('id', $id)->find();

        // 保存数据
        if ($this->request->isPost()) {
            $datainsert = [];
            $data       = input();
            // 验证
            $result = $this->validate($data, 'Fund.traderorder');
            if ($info['status'] == 0) {
                // 验证失败 输出错误信息
                if (true !== $result) $this->error($result);
            }
            if ($data['position'] > 100 || $data['position'] < 0) {
                $this->error('仓位比例不能为0或者操作100');
            }
            if (isset($data['buytime']) && !empty($data['buytime'])) {

                $data['date']     = date("Y-m-d", strtotime($data['buytime']));
                $transaction_date = Db::name('stock_trade_date')->where("date", $data['date'])->find();
                if (!$transaction_date) {
                    $this->error('选定的日期不是交易日');
                }
            }

            if (isset($data['selltime']) && isset($data['buytime']) && $data['selltime'] !== '') {

                if (strtotime($data['buytime']) > strtotime($data['selltime'])) {
                    $this->error('卖出时间不能比买入的时间早');
                }
                $transaction_date = Db::name('stock_trade_date')->where("date", date("Y-m-d", strtotime($data['selltime'])))->find();
                if (!$transaction_date) {
                    $this->error('选定的日期不是交易日');
                }
            }
            if (
                isset($data['selltime']) && isset($data['buytime']) && $data['selltime'] !== '' && $data['buytime'] !== '' &&

                date("Y-m-d", strtotime($data['buytime'])) == date("Y-m-d", strtotime($data['selltime']))) {
                $this->error('买卖时间不能为同一天');
            }
            if ($info['status'] == 2) {
                if (date("Y-m-d", strtotime($info['buy_time'])) == date("Y-m-d", strtotime($data['selltime']))) {
                    $this->error('买卖时间不能为同一天');
                }

                if (!empty($data['selltime']) && strtotime($info['buy_time']) > strtotime($data['selltime'])) {
                    $this->error('卖出时间不能比买入的时间早2');
                }
            }
            if (isset($data['buytime']) && !empty($data['buytime'])) {
                $datainsert['buy_time'] = $data['buytime'];
            }
            if (isset($data['selltime']) && !empty($data['selltime'])) {
                $datainsert['sell_time'] = $data['selltime'];
            }
            if (isset($data['buyprice'])) {
                $datainsert['buy_price'] = $data['buyprice'];
            }
            if (isset($data['sellprice'])) {
                $datainsert['sell_price'] = $data['sellprice'];
            }
            if (isset($data['position'])) {
                $datainsert['position'] = $data['position'];
            }
            if (isset($data['trader'])) {
                $datainsert['trader_id']   = $data['trader'];
                $datainsert['trader_name'] = TraderModel::get($data['trader'])['name'];
            }
            if (isset($data['stockcode'])) {
                $stockinfo                = StockListModel::where('code', $data['stockcode'])->find();
                $datainsert['stock_name'] = $stockinfo['title'];
                $datainsert['stock_code'] = $data['stockcode'];
            }

            if (TraderOrderModel::where('id', $data['id'])->update($datainsert)) {
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }

        $stocklist = StockListModel::where('status', 1)->select();
        $stock     = [];
        foreach ($stocklist as $v) {
            $stock[$v['code']] = $v['title'];
        }

        $Trader_list = TraderModel::where('status', 1)->select();
        $Traderlist  = [];
        foreach ($Trader_list as $v) {
            $Traderlist[$v['id']] = $v['name'] . "(" . traderDocumentaryArray()[$v['type']] . ")";
        }

        $status_Disable = $status_Disable_1 = $status_Disable_2 = '';
        if ($info->status == 2) {
            $status_Disable   = 'disabled';
            $status_Disable_1 = 'disabled';
        } elseif ($info->status == 3) {
            $this->error('该状态不能编辑');
        }

        return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['hidden', 'id'],
                         //                         ['date', 'date', '交易日期', '操作的交易日期', '', '', $status_Disable],
                         ['select', 'trader', '导师名称', '', $Traderlist, $info['trader_id'], $status_Disable],
                         ['select', 'stockcode', '股票', '', $stock, $info['stock_code'], $status_Disable],
                         ['date', 'buytime', '买入时间', '', $info['buy_time'], '', $status_Disable_1],
                         ['number', 'buyprice', '买入价格', '', $info['buy_price'], '', '', '', $status_Disable_1],
                         ['number', 'position', '买入仓位比例', '如果为空或0，则自动根据等级获取等级最大的仓位比例；动态计算股数比例(%)，如果余额不足，则自动计算的则直接取最大值的整数', '', '', '', '', $status_Disable_1],
                         ['date', 'selltime', '卖出时间', '', $info['sell_time'], '', $status_Disable_2],
                         ['number', 'sellprice', '卖出价格', '', $info['sell_price'], '', '', '', $status_Disable_2],
                         ['text', 'note', '手机号', '批量操作的用户', $info['note'], '', 'disabled'],
        ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    public function access_buy($ids = null)
    {
        if ($ids === null) $this->error('缺少参数');
        $info = TraderOrderModel::where('id', $ids)->find();
        if (isset($info['status']) && $info['status'] != 0) {
            $this->error('当前状态不能操作');
        }
        $list = FundOrderGsModel::where([
            ['status', 'in', "1,6"],

            ['trader_id', '=', $info['trader_id']],
            ['confirm_time', '<', strtotime(date($info['buy_time'] . " 14:00:00"))],
            ['fundendtime', '>', strtotime(date($info['buy_time'] . " H:i:s"))]
        ])->select();

        $n    = 0;
        $date = $info['date'];
        #先找到这个讲师下所有可跟买订单   并给他生成跟买记录
        foreach ($list as $v) {

            //排查可能用户通过其他方式生成的情况
            $where    = [
                ['uid', '=', $v['uid']],
                ['order_sn', '=', $v['order_sn']],
                ['trader_id', '=', $v['trader_id']],
                ["date", '=', $date],

            ];
            $infodata = FundDaylineModel::where($where)->find();
            if ($infodata) {
                continue;
            }

            #如果是每日跟单整你生成一次  且不时间只能是规定时间
            if ($v['order_type'] == 1 || $v['order_type'] == 2) {
                $where    = [
                    ['uid', '=', $v['uid']],
                    ['order_sn', '=', $v['order_sn']]
                ];
                $infodata = FundDaylineModel::where($where)->find();
                if ($infodata) {
                    continue;
                }
            }

            printlog(date("Y-m-d H:i:s", $v['confirm_time']), "订单确认时间", 'buy');
            printlog(date("Y-m-d H:i:s", strtotime($info['buy_time'])), "买入时间", 'buy');
            #如果日期是跟单以前的日期不会生成跟买记录
            if ($v['confirm_time'] > strtotime($info['buy_time'] . " 23:59:59")) {
                printlog('如果买入时间是跟单确认前的时间不会生成跟买记录', "跳过", 'buy');
                continue;
            }
            $enddate           = date("Y-m-d 23:59:59");                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  // 日期字符串
            $enddate_timestamp = strtotime($enddate);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 // 将日期字符串转换为时间戳
            #如果买入日期在结束日期之后
            if ($info['buy_time'] > $enddate_timestamp) {
                printlog('如果买入日期在结束日期之后', "跳过", 'buy');
                continue;
            }
            printlog($enddate, "订单结束时间", 'buy');
            #如果是在14点之后就跳过
            $afternoon_time           = date("Y-m-d 14:00:00", strtotime($info['buy_time']));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   // 日期字符串
            $afternoon_time_timestamp = strtotime($afternoon_time);    // 将日期字符串转换为时间戳
            printlog($afternoon_time, "下午14点时间", 'buy');
            #如果确认日期大于14点
            if ($v['confirm_time'] > $afternoon_time_timestamp) {
                printlog('如果确认日期大于14点', "跳过", 'buy');
                continue;
            }

            $data = [
                'order_id'            => $v['id'],
                'commission_ratio'    => $v['commission'],
                'trader_id'           => $v['trader_id'],
                'order_sn'            => $v['order_sn'],
                'date'                => $date,
                'create_time'         => time(),
                'uid'                 => $v['uid'],
                'buy_price'           => $info['buy_price'],
                'buy_time'            => $info['buy_time'],
                'stockname'           => $info['stock_name'],
                'stockcode'           => $info['stock_code'],
                'traderorder_id'      => $info['id'],
                'status'              => 1,
                'traderorder_id_text' => $info['date'] . '-' . $info['trader_name'] . '方案',

            ];
            $n    += FundDaylineModel::insert($data);
        }
        if ($n > 0) {
            #更新跟买的仓位比例
            $FundDaylinelist = FundDaylineModel::where('traderorder_id', $info['id'])->select();
            printlog(count($FundDaylinelist), "需要操作数据数量：", 'buy');
            foreach ($FundDaylinelist as $vv) {
                //获取该用户等级仓位；
                $FundDaylineinfo = TraderOrderModel::getcw($vv['uid']);
                printlog($vv['uid'], "用户id：", 'buy');
                //计算仓位
                #获取余额
                $orderbalance = orderbalance($vv['id']);

                $balance = $orderbalance;
                printlog($balance, "可用余额：", 'buy');
                printlog($FundDaylineinfo['max_position_ratio'], "用户最大仓位比例：", 'buy');
                printlog($info['position'], "输入的：", 'buy');
                $position = !empty($info['position']) ? $info['position'] / 100 : $FundDaylineinfo['max_position_ratio'] / 100;
                if ($position <= 0) {
                    $this->error('仓位比例需要大于0');
                }
                printlog($position, "持仓比例：", 'buy');
                $num = ($balance * $position) / $info['buy_price'];
                printlog($num, "能买的数量：", 'buy');
                $num = floor($num);
                if ($num <= 0) {
                    FundDaylineModel::where('id', $vv['id'])->delete();
                    continue;
                }
                if (config('buy_cost')) {

                    $buyprice_sum = $vv['buy_price'] * $num;
                    #券商佣金
                    $commission = config('commission') / 10000;
                    #过户费
                    $transfer_fee = config('transfer_fee') / 1000;
                    #规费
                    $stock_trading_fees = config('stock_trading_fees') / 1000;
                    #买入成本
                    $buy_cost = $buyprice_sum * $commission + $buyprice_sum * $transfer_fee + $buyprice_sum * $stock_trading_fees;
                } else {
                    $buy_cost = 0;
                }

                FundDaylineModel::where('id', $vv['id'])->update([
                    'num'      => $num,
                    'buy_cost' => $buy_cost,
                    'position' => $position * 100
                ]);
                FundOrderGsModel::where([
                    ['order_sn', '=', $vv['order_sn']]
                ])->update(['status' => 6]);

            }

        }
        TraderOrderModel::where('id', $ids)->update(['status' => 2, 'impact_quantity' => $n]);
        $this->success('操作成功', cookie('__forward__'));
    }

    /**
     * Desc :执行卖出操作
     * Date : 2024-07-16 02:35
     *
     * @param $ids
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function access_sell($ids = null)
    {
        if ($ids === null) $this->error('缺少参数');

        $info = TraderOrderModel::where('id', $ids)->find();
        if (empty($info['sell_time']) || empty($info['sell_price']) || $info['sell_price'] == '0.00') {
            $this->error('卖出信息未设置或不正确');
        }

        if (isset($info['status']) && $info['status'] != 2) {
            $this->error('当前状态不能操作，如果未买入需要先买入后再卖出');
        }
        if (strtotime($info['sell_time']) > strtotime(date("Y-m-d H:i:s"))) {
            $this->error('未到卖出时间不能提前卖出');
        }

        $where = [['traderorder_id', '=', $info['id']], ["status", '=', 1]];
        $res   = FundDaylineModel::where($where)
            ->update([
                'sell_price' => $info['sell_price'],
                'sell_time'  => $info['sell_time'],
                'status'     => 2
            ]);
        if ($res) {
            //结算
            FundOrderGsModel::settlementFundgs();

        }
        TraderOrderModel::where('id', $ids)->update(['status' => 3]);
        $this->success('操作成功', cookie('__forward__'));
    }

    public function delete($record = [])
    {
        $ids    = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids    = (array)$ids;
        $result = TraderOrderModel::where('id', 'in', $ids)->delete();
        if (false !== $result) {
            Db('fund_line')->where('fund_id', 'in', $ids)->delete();
            Db('fund_viptrade')->where('traderid', 'in', $ids)->delete();
            $this->success('操作成功', cookie('__forward__'));
        } else {
            $this->error('操作失败');
        }
    }

    public function quickEdit($record = [])
    {
        $id     = input('post.pk', '');
        $field  = input('post.name', '');
        $value  = input('post.value', '');
        $table  = input('post.table', '');
        $type   = input('post.type', '');
        $status = TraderOrderModel::where('id', $id)->value($field);

        $details = '字段(' . $field . ')，原值(' . $status . ')，新值：(' . $value . ')';

        switch ($type) {
            // 日期时间需要转为时间戳
            case 'combodate':
                $value = strtotime($value);
                break;
            // 开关
            case 'switch':
                $value = $value == 'true' ? 1 : 0;
                break;
            // 开关
            case 'password':
                $value = Hash::make((string)$value);
                break;
        }
        $result = TraderOrderModel::where('id', $id)->setField($field, $value);
        return $this->success('操作成功');
//        return parent::quickEdit(['trader', 'trader', $id, UID, $details]);
    }

    /**
     * Desc : 批量执行优投操作
     * Date : 2024-07-01 23:1
     *
     * @param $id
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function access($id = '')
    {
        if ($id === null) $this->error('缺少参数');

        $info = TraderOrderModel::where('id', $id)->find();
        #获取上一个交易日
        $previousData = Db::name('stock_trade_date')
            ->where('date', '<', $info['date']) // 获取比当前日期小的数据
            ->order('date', 'desc') // 按日期降序排列
            ->limit(1)              // 只取一条记录
            ->find();

        $where = [['f.trader_id', '=', $info['trader_id']], ["f.date", '=', $previousData['date']], ["f.status", '=', 0]];

        $re = FundDaylineModel::where($where)->alias('f')
            ->join('member m', 'f.uid = m.id')
            ->join('fund_userlevel l', 'l.level = m.level')
            ->field('f.id,l.max_position_ratio,l.min_position_ratio')
            ->select();

        $n = 0;

        foreach ($re as $v) {
            //计算仓位
            #获取余额
            $orderbalance = orderbalance($v['id']);

            $balance = $orderbalance;
            printlog($balance, "可用余额：", 'jiesuan');
            $position = !empty($info['position']) ? $info['position'] / 100 : $v['max_position_ratio'] / 100;
            printlog($position, "持仓比例：", 'jiesuan');
            $num = ($balance * $position) / $info['buy_price'];
            printlog($num, "能买的数量：", 'jiesuan');
            if ($position <= 0) {
                $this->error('仓位比例需要大于0');
            }
            $num = floor($num);
            if ($num <= 0) {
                continue;
            }

            FundDaylineModel::where($where)->alias('f')
                ->update([
                    'num'                 => $num,
                    'buy_price'           => $info['buy_price'],
                    'sell_price'          => $info['sell_price'],
                    'buy_time'            => $info['buy_time'],
                    'sell_time'           => $info['sell_time'],
                    'stockname'           => $info['stock_name'],
                    'stockcode'           => $info['stock_code'],
                    'position'            => $position * 100,
                    'traderorder_id'      => $id,
                    'status'              => 2,
                    'traderorder_id_text' => $info['date'] . '-' . $info['trader_name'] . '方案',
                ]);
            //计算收入 结算

            $n++;
        }

        if ($n > 0) {
            //结算
            FundOrderGsModel::settlementFundgs();
            TraderOrderModel::where('id', $id)->update(['status' => 1]);
            $this->success('操作成功', cookie('__forward__'));
        }
        $this->error('无记录可操作');
    }
}

