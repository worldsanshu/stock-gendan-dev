<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Customproperty as CustompropertyModel;
use app\common\builder\ZBuilder;
use app\admin\model\Custommenu as CustommenuModel;
use app\member\model\Member;
use app\member\model\Member as MemberModel;
use app\statistics\model\DataReport as DataReportModel;
use think\Db;
use think\facade\Hook;

/**
 * 用于移动端自定义菜单管理
 * @package app\admin\controller
 */
class Customproperty extends Admin
{

    public function quickEdit($record = [])
    {
        $id = input('post.pk', '');
        $field = input('post.name', '');
        $value = input('post.value', '');
        $table = input('post.table', '');
        $type = input('post.type', '');
        if($field == 'status'){
            $value = $value == 'true' ? 1 : 0;
        }
        $status = CustompropertyModel::where('id', $id)->update([$field=>$value,'update_time'=>time()]);
        if ($status){
            $this->success('操作成功');
        }$this->error('操作失败');
    }






    /**
     * 首页
     * @return mixed
     */
    public function index_property()
    {
        $data_count = CustompropertyModel::count();
        if($data_count < 1){
            $this->restoreperty();
        }
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('sort');
        // 数据列表
        $data_list = CustompropertyModel::getAll($map, $order);
        foreach ( $data_list as &$value){
            if($value['img_url']){
                $value['image'] = CustompropertyModel::getImgurlTextAttr($value['img_url']);
            }else{
                $value['image'] = $value['default_icon'];
            }
        }
        // 分页数据
        $page = $data_list->render();


        $url_name = [
//            '0' => '不跳转',
            '1' => '内部路径',
            '2' => '外部链接',

        ];
                return ZBuilder::make('table')
                    ->hideCheckbox()
                    ->addColumns([ // 批量添加数据列
                        ['name', '名称','text.edit'],
                        ['image', '图标', 'img_url'],
                        ['sort', '排序','text.edit'],
                    ])
                    ->setColumnWidth('url', 200)
                    ->addColumn('status', '状态', 'switch')
//                    ->addColumn('right_button', '操作', 'btn')
//                    ->addRightButtons('edit') // 批量添加右侧按钮  ,last_login_time
                    ->setRowList($data_list) // 设置表格数据
                    ->setPages($page) // 设置分页数据
                    ->fetch(); // 渲染模板

    }



    public function restoreperty()
    {
        CustompropertyModel::where('id','>',0)->delete();
            $list = CustompropertyModel::getPropertyArr();
            foreach ( $list as $k => $v ) {
                $data = [
                    'name'      => $v['name'],
                    'default_icon'     => $v['img_url'],
                    'url'     => $v['url'],
                    'url_type'  => $v['url_type'],
                    'url_level' => $v['url_level'],
                    'money' => $v['money'],
                ];
                CustompropertyModel::create($data);
            }
            action_log('recover_perty', 'Customproperty', 0, UID, '还原了资产列表显示');

    }
}