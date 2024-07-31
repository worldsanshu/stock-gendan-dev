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
use app\fund\model\Fund;
use app\fund\model\FundUserlevel as FundUserlevelModel;
use app\fund\model\FundViptrade as FundViptradeModel;
use app\fund\model\Trader as TraderModel;
use think\helper\Hash;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Trader extends Admin
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
        empty($order) && $order = 'id desc';
        $field = '*';
        // 数据列表
        $data_list = TraderModel::where($map)->field($field)->order($order)->paginate();
        foreach ($data_list as $key => $value){
            if($value['type'] == 2){
                $viptrade=FundViptradeModel::where('traderid',$value['id'])->select();
                // 初始化最大值和最小值
                $maxValue = PHP_INT_MIN;
                $minValue = PHP_INT_MAX;
                // 遍历数组找出最大值和最小值
                foreach ($viptrade as $item) {
                    if ($item['commission'] > $maxValue) {
                        $maxValue = $item['commission'];
                    }
                    if ($item['commission'] < $minValue) {
                        $minValue = $item['commission'];
                    }
                }
                if(count($viptrade)>1){
                    //                            最大最小值相等只显示一个
                    if($minValue == $maxValue){
                        $data_list[$key]['divide_into']       = formatNumber($minValue);
                    }else{
                        $data_list[$key]['divide_into']       = $minValue. "-" .$maxValue;
                    }
                }else{
                    $data_list[$key]['divide_into']       = formatNumber($minValue);
                }
            }

        }
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        //基金列表
        $fund_list = Fund::column('name', 'id');
//        $type_list = ['1' => '普通智投', '2' => '一键智投'];
        $type_list = $this->trader_documentary_array;
        return ZBuilder::make('table')
//            ->setSearch(['name' => '名称']) // 设置搜索框
            ->setSearchArea([
                ['text', 'product_name', '产品名称', 'like'],
                ['text', 'name', '导师名称', 'like'],
                ['text', 'level_name', '级别', 'like'],
                ['daterange', 'create_time', '创建时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'type', '优投类型', '', '', $type_list],
            ])
        ->addOrder('id') // 添加排序
        ->addColumns([ // 批量添加数据列
                       ['id', 'ID'],
                       ['product_name', '产品名称'],
                       ['name', '导师名称'],
                       ['level_name', '级别'],
                       ['experience', '操盘经验'],
                       ['min_money', '最低投资金额'],
                       ['max_money', '最高投资金额'],
                       ['divide_into', '佣金比例'],
                       //                   ['fund_id', '基金', 'select', $fund_list],
                       ['type', '优投类型', 'select', $type_list],
                       ['create_time', '时间', 'datetime'],
                       ['status', '状态', 'switch'],
                       ['right_button', '操作', 'btn']
        ])->setTableName('fund')->addTopButtons('add,delete') // 批量添加顶部按钮
        ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮
        ->addOrder('id,is_del')->setRowList($data_list) // 设置表格数据
        ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            if (TraderModel::where('name', $data['name'])->where("type",$data['type'])->count()) {
                $this->error('名称已重复');
            }
            if (empty($data['type'])) {
                $this->error('请选择优投类型');
            }
            if ($data['type'] == 2) {
                if (isset($data['project']) && !empty($data['project'])) {
                    // 有新增行，需要循环处理数据
                    $prodctlist = [];
                    foreach ($data['project']['cycle'] as $key => $SFDM) {
                        $prodctdata               = [];
                        $prodctdata['cycle']      = intval($SFDM);
                        $prodctdata['cycle_text'] = $SFDM . '个交易日';
                        $prodctdata['commission'] = $data['project']['commission'][$key];
                        $Userlevel = [];
                        if ($data['project']['user_level']) {
                            if (isset($data['project']['user_level'][$key + 1])) {
                                $Userlevellist = $data['project']['user_level'][$key + 1];
                                foreach ($Userlevellist as $uk => $uv) {
                                    $Userlevel[] = $uk;
                                }
                            }
                        }
                        $prodctdata['user_level']  = json_encode($Userlevel);
                        $prodctdata['create_time'] = time();
                        $prodctdata['min_money']   = $data['project']['min_money'][$key];
                        $prodctdata['max_money']   = $data['project']['max_money'][$key];
                        if ($data['project']['max_money'][$key] < $data['project']['min_money'][$key]) {
                            $this->error('最大金额不能最小金额大');
                        }
                        $prodctlist[] = $prodctdata;
                    }
                } else {
                    // 没有新增任何行
                    $this->error('需要设置产品');
                }
                // 按照age键值升序排序
                usort($prodctlist, function ($a, $b) {
                    return $a['min_money'] - $b['min_money'];
                });
                $data['min_money'] = $prodctlist[0]['min_money'];
                $data['max_money'] = $prodctlist[count($prodctlist) - 1]['max_money'];
            }
            $min_invest_money = $data['min_money'];
            if ($min_invest_money < 100) {
                $this->error('最低投资金额不能小于100');
            }
            if ($data['min_money'] >= $data['max_money']) {
                $this->error('最高投资金额必须大于最低投资金额');
            }
            if ($tid = TraderModel::create($data)) {
                $newField = 'traderid';
                $value    = $tid['id'];
                if ($data['type'] == 2) {
                    if (!FundViptradeModel::where('traderid', $value)->find()) {
                        foreach ($prodctlist as &$item) {
                            $item[$newField] = $value;
                        }
                        FundViptradeModel::insertAll($prodctlist);
                        FundViptradeModel::getlastsql();
                    }
                }
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        $Userlevel     = [];
        $FundUserlevel = FundUserlevelModel::select();
        foreach ($FundUserlevel as $k => $v) {
            $Userlevel[$v['id']] = $this->level_array[$v['level']];
        }
        //基金列表
        $fund_list = Fund::where('fund_type', 2)->column('name', 'id');
//        $type_list = ['1' => '每日优投', '2' => 'VIP优投'];
        $type_list = $this->trader_documentary_array;
        return ZBuilder::make('form')->setPageTitle('新增') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['image', 'headimgurl', '头像','最有尺寸：200px*200px'],
                         ['text', 'product_name', '产品名称'],
                         ['text', 'name', '讲师名称','5个字以内,超出会导致错位'],
                         ['text', 'level_name', '级别名称（如：操盘师）','5个字以内,超出会导致错位'],
                         ['text', 'experience', '操盘经验（如：三年投资操盘经验）','10个字以内,超出会导致错位'],
                         ['text', 'explain', '个人名言（如：不盲目跟风，坚守个人投资风格）'],
                         ['text', 'divide_into', '佣金比例', '以该比例为主，下方的比例暂未启用'],
                         ['select', 'type', '请选择优投类型', '', $type_list],
//                         ['select', 'fund_id', '请选择基金', '', $fund_list,],
                         ['text', 'min_money', '最低优投金额'],
                         ['text', 'max_money', '最高优投金额'],
                         ['dataTable', 'project', '跟买产品', '选择并设置项目所需的硬软件数量',
                          [
//                           'cycle'      => ['type' => 'select', 'title' => '交易日', 'options' => $list_sf],
                            'cycle'  => '交易日(只能输入数字)',
                           'user_level' => ['type' => 'checkbox', 'title' => '层级', 'options' => $Userlevel],
                           'min_money'  => '最小投入金额', 'max_money' => '最大投入金额', 'commission' => '佣金比例'
                          ]
                         ],
                         ['text', 'total_revenue', '总收益率'],
                         ['text', 'monthly_revenue', '月收益率'],
                         ['text', 'position', '仓位率'],
                         ['text', 'maximum_rollback', '最大回撤率'],
                         ['text', 'win_obbs', '胜率'],
        ])
            ->setTrigger('type', '2', 'project')
            ->setTrigger('type', '1', 'min_money,max_money,fund_id')
            ->fetch();
    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
//        $type_list = ['1' => '每日优投', '2' => 'VIP优投'];
        $type_list = $this->trader_documentary_array;
        $info      = TraderModel::where('id', $id)->find();

        // 保存数据
        if ($this->request->isPost()) {
            $update_data = input();
            // 验证
            $data = $update_data;
            if ($data['name'] != $info['name']) {
                $id2 = TraderModel::where('name', $data['name'])->find();
//            $result = $this->validate($data, 'Fund');
//            if(true !== $result) $this->error($result);
                if ($id2) {
                   // $this->error('名称已重复');
                }
            }
            if (empty($data['type'])) {
                $this->error('请选择优投类型');
            }
            if ($data['type'] == 2) {
                if (isset($data['project']) && !empty($data['project'])) {
                    // 有新增行，需要循环处理数据
                    $prodctlist = [];
                    foreach ($data['project']['cycle'] as $key => $SFDM) {
                        $prodctdata               = [];
                        $prodctdata['cycle']      =intval($SFDM);
                        $prodctdata['cycle_text'] = $SFDM . '个交易日';
                        $prodctdata['commission'] = $data['project']['commission'][$key];
                        $Userlevel = [];
                        if ($data['project']['user_level']) {

                            if (isset($data['project']['user_level'][$key + 1])) {
                                $Userlevellist = $data['project']['user_level'][$key + 1];

                                foreach ($Userlevellist as $uk => $uv) {
                                    $Userlevel[] = $uk;
                                }
                            }
                        }

                        $prodctdata['user_level']  = json_encode($Userlevel);
                        $prodctdata['create_time'] = time();
                        $prodctdata['min_money']   = $data['project']['min_money'][$key];
                        $prodctdata['max_money']   = $data['project']['max_money'][$key];
                        if ($data['project']['max_money'][$key] < $data['project']['min_money'][$key]) {
                            $this->error('最大金额不能最小金额大');
                        }
                        $prodctlist[] = $prodctdata;
                    }
                } else {
                    // 没有新增任何行
                    $this->error('需要设置产品');
                }

                // 按照age键值升序排序
                usort($prodctlist, function ($a, $b) {
                    return $a['min_money'] - $b['min_money'];
                });
                $data['min_money'] = $prodctlist[0]['min_money'];
                $data['max_money'] = $prodctlist[count($prodctlist) - 1]['max_money'];
            }
            $min_invest_money = $data['min_money'];
            if ($min_invest_money < 100) {
                $this->error('最低投资金额不能小于100');
            }
            if ($data['min_money'] >= $data['max_money']) {
                $this->error('最高投资金额必须大于最低投资金额');
            }

            if (TraderModel::update($data)) {
                $newField = 'traderid';
                $value    = $id;
                if ($data['type'] == 2) {
                    foreach ($prodctlist as &$item) {
                        $item[$newField] = $value;
                    }
                    FundViptradeModel::where('traderid', $value)->delete();
                    FundViptradeModel::insertAll($prodctlist);
                }
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        //基金列表
        $fund_list = Fund::column('name', 'id');
        $list_sf   = [
            1 => '1个交易日',
            5 => '5个交易日',
            10 => '10个交易日',
            15 => '15个交易日',
            20 => '20个交易日',
            30 => '30个交易日',
            50 => '50个交易日',
            60 => '60个交易日',
            80 => '80个交易日',
            90 => '90个交易日',
            180 => '180个交易日'
        ];

        $Userlevel     = [];
        $FundUserlevel = FundUserlevelModel::select();
        foreach ($FundUserlevel as $k => $v) {
            $Userlevel[$v['level']] = $this->level_array[$v['level']];
        }

        $dataTabledata = FundViptradeModel::where('traderid', $id)->select();

        foreach ($dataTabledata as $k => $v) {
            $v['user_level'] = json_decode($v['user_level'], true);;
        }
        $dataTabledata = $dataTabledata->toArray();
        // 按照cycle键值升序排序
        usort($dataTabledata, function ($a, $b) {
            return $a['cycle'] - $b['cycle'];
        });

        return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
        ->addFormItems([ // 批量添加表单项
                         ['hidden', 'id'],
                         ['image', 'headimgurl', '头像'],
                         ['text', 'product_name', '产品名称'],
                         ['text', 'name', '讲师名称','5个字以内,超出会导致错位'],
                         ['text', 'level_name', '级别名称（如：操盘师）','5个字以内,超出会导致错位'],
                         ['text', 'experience', '操盘经验（如：三年投资操盘经验）','10个字以内,超出会导致错位'],
                         ['text', 'explain', '个人名言（如：不盲目跟风，坚守个人投资风格）'],
                         ['number', 'min_money', '最低优投金额', '只能填写数字'],
                         ['number', 'max_money', '最高优投金额', '只能填写数字'],
                         ['number', 'divide_into', '佣金比例', '只能填写数字，10表示10%'],
                         ['dataTable', 'project', '跟买产品', '选择并设置项目所需的硬软件数量', [
//                             'cycle'      => ['type' => 'select', 'title' => '交易日', 'options' => $list_sf],
                             'cycle'  => '交易日(只能输入数字)',
//                            'cycle'      => ['type' => 'number', 'title' => '交易日'],
                             'user_level' => ['type' => 'checkbox', 'title' => '层级', 'options' => $Userlevel],
                             'min_money'  => '最小投入金额', 'max_money' => '最大投入金额', 'commission' => '佣金比例'
                         ], $dataTabledata
                         ],
                         ['select', 'type', '请选择优投类型', '', $type_list],
//                         ['select', 'fund_id', '请选择基金', '', $fund_list],
                         ['text', 'total_revenue', '总收益率', '仅在在前端显示使用，看起来好看'],
                         ['text', 'monthly_revenue', '月收益率', '仅在在前端显示使用，看起来好看'],
                         ['text', 'position', '仓位率', '仅在在前端显示使用，看起来好看'],
                         ['text', 'maximum_rollback', '最大回撤率', '仅在在前端显示使用，看起来好看'],
                         ['text', 'win_obbs', '胜率', '仅在在前端显示使用，看起来好看'],
                         ['radio', 'status', '状态', '', ['禁用', '启用']]
        ])
            ->setTrigger('type', '2', 'project')

            ->setTrigger('type', '1', 'min_money,max_money,fund_id,divide_into')
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    public function delete($record = [])
    {
        $ids    = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids    = (array)$ids;
        $result = TraderModel::where('id', 'in', $ids)->update(['status' => 0]);
        if (false !== $result) {
            Db('fund_line')->where('fund_id', 'in', $ids)->delete();
//      Db('fund_viptrade')->where('traderid', 'in', $ids)->delete();
            $this->success('操作成功', cookie('__forward__'));
        } else {
            $this->error('操作失败');
        }
    }

    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置用户状态：禁用、启用
     *
     * @param string $type 类型：enable/disable
     * @param array $record
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public function setStatus($type = '', $record = [])
    {
        $table_name    = input('param.table');
        $ids           = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $member_id     = is_array($ids) ? '' : $ids;
        $member_status = TraderModel::where('id', 'in', $ids)->column('status');
        return parent::setStatus($type, ['member_' . $type, 'member', $member_id, UID, implode('、', $member_status)]);
    }

    public function quickEdit($record = [])
    {
        $id     = input('post.pk', '');
        $field  = input('post.name', '');
        $value  = input('post.value', '');
        $table  = input('post.table', '');
        $type   = input('post.type', '');
        $status = TraderModel::where('id', $id)->value($field);
        // $status = Db::name('member')->where('id', $id)->value($field);
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
        $result = TraderModel::where('id', $id)->setField($field, $value);
        return $this->success('操作成功');
//        return parent::quickEdit(['trader', 'trader', $id, UID, $details]);
    }
}

