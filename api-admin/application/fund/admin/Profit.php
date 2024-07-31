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

use app\common\service\UserService;
use app\fund\model\FundProfitRecord;
use app\fund\model\FundOrderGs;
use app\money\model\Record;
use think\Db;

/**
 * 提取盈利
 * @package app\member\admin
 */
class Profit extends Admin
{
    /**
     * 提盈配置
     * @return mixed
     */
    public function index()
    {
        $get_one = Db::name('fund_profit_config')->find();
        if ($this->request->isPost()) {
            $data = input();
                if($get_one){
                    $data['update_time'] = time();
                    $result = Db::name('fund_profit_config')->where('id',1)->update($data);
                }else{
                    $data['create_time'] = time();
                    $result = Db::name('fund_profit_config')->insert($data);
                }


            if ($result) {
                $this->success('处理成功', null, 'index');
            } else {
                $this->error('处理失败');
            }
        }

            return ZBuilder::make('form')
                ->addFormItems([ // 批量添加表单项
                    ['text', 'min_account', '最低提取金额', '单次提取最低金额'],
                    ['text', 'buy_num', '每日提取次数', '-1 为不限制'],
                    ['time', 'profit_start_time', '提取时间-开始'],
                    ['time', 'profit_end_time', '提取时间-结束'],
                ])
                ->setFormData($get_one) // 设置表格数据
                ->fetch(); // 渲染模板



    }




    /**
     * 提盈审核
     * @return mixed
     */
    public function examine()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map   = $this->getMap();
        $map = (new UserService())->getAgentMap($map,'m.id');
        $order = $this->getOrder();
        $page  = input('page', 1);

        empty($order) && $order = 'id desc';

        $res       = FundProfitRecord::getList($page, $map, $order);
        $data_list = $res['list'] ?? [];
        foreach ($data_list as &$v) {
            $username       = empty($v['username']) ? '--' : $v['username'];
            $v['user_info'] = "<p>" . $username . "</p><p>" . $v['mobile'] . "</p>";
            if ($v['status'] == 1) {
                $v['new_status'] = '<span style="color: green">审核通过</span>';
            } elseif ($v['status'] == 2) {
                $v['new_status'] = '<span style="color:red">驳回</span>';
            }else {
                $v['new_status'] = '<span style="">待审核</span>';
            }
        }
        // 分页数据
        $page             = $data_list->render();

        return ZBuilder::make('table')
            ->setSearchArea([
                ['text', 'order_sn', '订单号', 'like'],
                ['text', 'm.name', '姓名', 'like'],
                ['text', 't.name', '导师姓名', 'like'],
                ['text', 'm.mobile', '手机号', 'like'],
                ['select', 'i.status', '状态', '', '', ['0' => '待审核','1' => '审核通过', '2' => '驳回']],
                ['daterange', 'i.create_time', '提取时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['daterange', 'examine_time', '审核时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
            ->addColumns([ // 批量添加数据列
                ['order_sn', '订单号'],
                ['money', '提取金额'],
                ['user_info', '姓名/手机号'],
                ['role_name', '白名单', $this->user_role_name],
                ['trader_name', '导师姓名'],
                ['new_status', '状态', ],
                ['create_time', '提取时间', 'datetime'],
                ['examine_time', '审核时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->setPrimaryKey('id')
//            ->addRightButton('custom', $btn_access_right, ['area' => ['800px', '90%'], 'title' => '测试返利']) // 批量添加右侧按钮
            ->addRightButton('edit', [], ['area' => ['800px', '90%'], 'title' => '审核']) // 批量添加右侧按钮
            ->addOrder('id,is_del')
            ->replaceRightButton(['status' => ['in', '1,2']], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }


//    审核
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数', null, '_close_pop');
        $get_one = FundProfitRecord::where('i.id', $id)
            ->alias('i')
            ->join('fund_order_gs g','g.id = i.order_id')
            ->field('i.*,g.order_sn')
            ->find();
        if (!$get_one) {
            $this->error('数据异常');
        }
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
//            Db::startTrans();
//            try {
//            审核通过增加余额
            if($data['status'] == 1){
                $record = new Record();
                $money_info = Db::name('money')->where('mid', $get_one['uid'])->find();
                $new_money = $get_one['money'] * 100; //钱包以分为单位
                $up_money['account'] = bcadd($money_info['account'], $new_money); //加本金

                Db('money')->where('mid', $get_one['uid'])->update($up_money);

                $info = "提取盈利：" . $get_one['order_sn'];
                $affect = $get_one['money']  * 100;
                $type = 109;
                $account = bcadd($money_info['account'], $affect);
                $obj = ['affect' => $affect, 'account' => $account, 'affect_activity' => 0, 'activity_account' => 0, 'sn' => $get_one['order_sn']];
                $record->saveData($get_one['uid'], $affect, $account, $type, $info, '', '', $obj);
            }else{
//                审核驳回返回提盈
                FundOrderGs::where('id', $get_one['order_id'])->setInc('balance', $get_one['money']);
            }



            $result = FundProfitRecord::where('id', $id)->update(['status' => $data['status'], 'examine_time' => time()]);
            if ($result) {
                $this->success('操作成功', null, '_parent_reload');
            } else {
                $this->error('数据更新失败');
            }

        }
        return ZBuilder::make('form')
            ->setPageTitle('提盈审核')// 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static', 'order_sn', '单号'],
                ['static', 'money', '金额'],
                ['radio', 'status', '审核状态','',['1' => '通过', '2' => '驳回'],'1'],
            ])
//            ->addRadio('status', '审核状态', '', ['1' => '通过', '2' => '驳回'],'1')
            ->setFormData($get_one)// 设置表单数据
            ->fetch();

    }






}

