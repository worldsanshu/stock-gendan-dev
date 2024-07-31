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
namespace app\interest\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;

use app\common\service\UserService;
use app\interest\model\Interest;
use app\member\model\MemberMessage as MemberMessageModel;
use app\money\model\Record;
use think\Db;
use think\facade\Request;

/**
 * 余额宝
 * @package app\member\admin
 */
class Index extends Admin
{
    /**
     * 首页
     * @return mixed
     */
    public function index($group = 'tab1')
    {
        $get_one = Db::name('interest_config')->select();
        if ($this->request->isPost()) {
            $data = input();
//            第一选项卡
            if($group == 'tab1'){
                if($get_one[0]){
                    $data['update_time'] = time();
                    $result = Db::name('interest_config')->where('id',1)->update($data);
                }else{
                    $data['create_time'] = time();
                    $result = Db::name('interest_config')->insert($data);
                }
            }elseif ( $group == 'tab2'){
//                第二选项卡
                if($get_one[1]){
                    $data['update_time'] = time();
                    $result = Db::name('interest_config')->where('id',2)->update($data);
                }else{
                    $data['create_time'] = time();
                    $result = Db::name('interest_config')->insert($data);
                }
            }

            if ($result) {
                $this->success('处理成功', null, 'index');
            } else {
                $this->error('处理失败');
            }
        }

        $list_tab = [
            'tab1' => ['title' => '七日年化收益率', 'url' => url('index', ['group' => 'tab1'])],
            'tab2' => ['title' => '万份收益', 'url' => url('index', ['group' => 'tab2'])],
        ];

        switch ($group) {
            case 'tab1':
                return ZBuilder::make('form')
//                    ->setPageTitle('七日年化收益率')// 设置页面标题
//                    ->setPageTips('计算方式： <br>七日年化收益 = 存入金额 * 利率 *7 /365。 <br>每日收益 = 七日年化收益 / 7。', 'danger')
                    ->setPageTips('计算方式： <br>七日年化收益 = 存入金额 * 利率%。', 'danger')
                    ->setTabNav($list_tab,  $group)
                    ->addFormItems([ // 批量添加表单项
                        ['text', 'name', '名称'],
                        ['text', 'min_account', '最低购买金额', '单次购买最低金额'],
//                        ['text', 'max_account', '最高购买金额', '单次购买最高金额'],
//                        ['text', 'buy_num', '每日购买次数', '0 为不限制购买'],
                        ['text', 'interest_rate', '固定利率', '利息比例 (单位 %)。填 0 默认按每日收益区间计算，否则优先按照固定利率，'],
//                        ['ueditor', 'explain', '说明', '']
                    ])
//                    ->addRange('daily_floating_range', '每日收益率区间', '收益 (单位 %)', '', ['double' => 'true','min' => 0, 'max' => 5,'input_values_separator' => ','])
//                    ->addUeditor('explain', '说明')
                    ->setFormData($get_one[0]) // 设置表格数据
                    ->fetch(); // 渲染模板
                break;
            case 'tab2':
                return ZBuilder::make('form')
//                    ->setPageTitle('万份收益')// 设置页面标题
//                    ->setPageTips('计算方式： <br>万份收益 = 存入金额 / 10000 * 利率', 'danger')
                    ->setPageTips('计算方式： <br>万份收益 = 存入金额 * 利率%。', 'danger')
                    ->setTabNav($list_tab,  $group)
                    ->addFormItems([ // 批量添加表单项
                        ['text', 'name', '名称'],
                        ['text', 'min_account', '最低购买金额', '单次购买最低金额'],
//                        ['text', 'max_account', '最高购买金额', '单次购买最高金额'],
//                        ['text', 'buy_num', '每日购买次数', '0 为不限制购买'],
//                        ['text', 'interest_rate', '固定利率', '利息比例 (单位 %)'],
                        ['text', 'interest_rate', '固定利率', '利息比例 (单位 %)。填 0 默认按每日收益区间计算，否则优先按照固定利率，'],
//                        ['ueditor', 'explain', '说明', '']
                    ])
//                    ->addRange('daily_floating_range', '每日收益区间', '收益 (单位 元)', '', ['double' => 'true','min' => 0, 'max' => 5,'input_values_separator' => ','])
//                    ->addUeditor('explain', '说明')
                    ->setFormData($get_one[1]) // 设置表格数据
                    ->fetch(); // 渲染模板
                break;
        }

    }



    /**
     * 基本信息
     * @return mixed
     */
    public function basic_Settings()
    {
        $get_one = Db::name('interest_basic')->find();
        if ($this->request->isPost()) {
            $data = input();
//            第一选项卡

                if($get_one){
                    $data['update_time'] = time();
                    $result = Db::name('interest_basic')->where('id',1)->update($data);
                }else {
                    $data['create_time'] = time();
                    $result = Db::name('interest_basic')->insert($data);
                }

            if ($result) {
                $this->success('处理成功', null, 'index');
            } else {
                $this->error('处理失败');
            }
        }

        return ZBuilder::make('form')
            ->addFormItems([ // 批量添加表单项
                ['text', 'name', '名称'],
                ['text', 'buy_num', '每日可购买次数', '0 为不限制购买'],
            ])
            ->addRadio('white_buy', '限制白名单购买', '', ['0' => '限制购买', '1' => '允许购买'])
            ->addImage('banner', '图片')
            ->setFormData($get_one) // 设置表格数据
            ->fetch(); // 渲染模板
    }






    /**
     * 购买记录
     * @return void
     */
    public function buylist()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map   = $this->getMap();
        $map = (new UserService())->getAgentMap($map,'m.id');
        $order = $this->getOrder();
        $page  = input('page', 1);

        empty($order) && $order = 'id desc';

        $res       = Interest::getList($page, $map, $order);
        $data_list = $res['list'] ?? [];
        foreach ($data_list as &$v) {
            $username       = empty($v['username']) ? '--' : $v['username'];
            $v['user_info'] = "<p>" . $username . "</p><p>" . $v['mobile'] . "</p>";
            if ($v['status'] == 1) {
                $v['new_status'] = '<span style="color: red">买入</span>';
            } else {
                $v['new_status'] = '<span style="color:green">返利</span>';
            }
        }
        // 分页数据
        $page             = $data_list->render();

        $btn_access_right = [
            'title' => '测试返利',
            'icon'  => 'fa fa-fw fa-cny',
            'class' => 'btn btn-xs btn-success',
            'href'  => url('edit', ['id' => '__id__'])
        ];
        return ZBuilder::make('table')
            ->setSearchArea([
                ['text', 'order_number', '订单号', 'like'],
                ['text', 'm.name', '姓名', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['select', 'interest_record_id', '类型', '', '', ['1' => '七日年化收益率', '2' => '万份收益']],
                ['select', 'i.status', '状态', '', '', ['1' => '买入', '2' => '返利']],
                ['daterange', 'buy_time', '购买时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['daterange', 'rebate_time', '返利时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']]
            ])
            ->addColumns([ // 批量添加数据列
                ['order_number', '订单号'],
                ['cname', '类型'],
                ['money', '买入金额'],
                ['user_info', '姓名/手机号'],
                ['interest', '利息(元)'],
                ['new_status', '状态', ],
                ['buy_time', '购买时间', 'datetime'],
                ['rebate_time', '返利时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->setPrimaryKey('id')
//            ->addRightButton('custom', $btn_access_right, ['area' => ['800px', '90%'], 'title' => '测试返利']) // 批量添加右侧按钮
            ->addRightButton('edit', [], ['area' => ['800px', '90%'], 'title' => '测试返利']) // 批量添加右侧按钮
            ->addOrder('id,is_del')
            ->replaceRightButton(['status' => '2'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }





//    返利
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数', null, '_close_pop');
        $get_one = Interest::where('id', $id)->find();
        if (!$get_one) {
            $this->error('数据异常');
        }
//        计算利息
        $interest = Interest::setInterest($get_one['interest_record_id'],$get_one['money']);
        $get_one['interest'] =$interest['interest'];
        $get_one['interest_rate'] =$interest['interest_rate'];
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
//            Db::startTrans();
//            try {
                $record = new Record();
                $money_info = Db::name('money')->where('mid', $get_one['uid'])->find();
                $new_money = $get_one['money'] * 100; //钱包以分为单位
                $up_money['freeze_balance_bao'] = bcsub($money_info['freeze_balance_bao'], $new_money); //扣除冻结
                $new_interest = $interest['interest'] * 100;
                $up_money['account'] = bcadd($money_info['account'], $new_money); //加本金
                $up_money['account'] = bcadd($up_money['account'], $new_interest); //加返利

                Db('money')->where('mid', $get_one['uid'])->update($up_money);

                $info = "返还本金：" . $get_one['order_number'];
                $affect = $get_one['money']  * 100;
                $type = 106;
                $account = bcadd($money_info['account'], $affect);
                $obj = ['affect' => $affect, 'account' => $account, 'affect_activity' => 0, 'activity_account' => 0, 'sn' => $get_one['order_number']];
                $record->saveData($get_one['uid'], $affect, $account, $type, $info, '', '', $obj);

                $info1 = "返还利息：" . $get_one['order_number'];
                $affect1 = $interest['interest'] * 100;
                $type1 = 107;
                $account1 = bcadd($account, $affect1);
                $obj1 = ['affect' => $affect1, 'account' => $account1, 'affect_activity' => 0, 'activity_account' => 0, 'sn' => $get_one['order_number']];
                $record->saveData($get_one['uid'], $affect1, $account1, $type1, $info1, '', '', $obj1);

                $result = Interest::where('id', $id)->update(['status' => 2, 'rebate_time' => time(), 'interest' => $get_one['interest'], 'interest_rate' => $get_one['interest_rate']]);
                if ($result) {
//                    Db::commit();
                    $this->success('操作成功', null, '_parent_reload');

                } else {
//                    Db::rollback();
//                    return '数据更新失败';
                    $this->error('数据更新失败');
                }
//            } catch (\Exception $e) {
//                Db::rollback();
//                return $e->getMessage();
//            }
        }
        return ZBuilder::make('form')
            ->setPageTitle('返利')// 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static', 'order_number', '订单号'],
                ['static', 'money', '金额',],
                ['static', 'interest', '返利',],
                ['static', 'interest_rate', '利率%/收益',],
            ])
            ->setFormData($get_one)// 设置表单数据
            ->fetch();

    }


}

