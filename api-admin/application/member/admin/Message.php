<?php

namespace app\member\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\member\model\Member as MemberModel;
use app\member\model\MemberMessage as MemberMessageModel;
use think\Db;
use think\facade\Cache;

class Message extends Admin
{
    public function index()
    {
        // 获取查询条件
        $map = $this->getMap();
        if (empty($map['create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
        }
        if (empty($map['create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['create_time'][1][1]));
        }
        // 数据列表
        $data_list = MemberMessageModel::
            alias('ms')
            ->where($map)
            ->join('member m', 'm.id = ms.mid')
            ->field('ms.*,m.name,m.mobile,m.role_name')
        ->order('id desc')->paginate();
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
//            ->setSearch(['mid' => '用户ID', 'title' => '消息标题', 'for_user' => '代理商id']) // 设置搜索框
            ->setSearchArea([
                ['text', 'mid', '用户ID'],
                ['text', 'title', '消息标题', 'like'],
                ['text', 'name', '姓名', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['daterange', 'create_time', '时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
            ])
        ->addColumns([ // 批量添加数据列
          ['id', 'ID'],
          ['mid', '用户ID'],
          ['name', '姓名'],
          ['mobile', '手机号'],
            ['role_name', '白名单', $this->user_role_name],
          ['title', '消息标题'],
//          ['type', '消息类型'],
          ['for_user', '用户所属代理商'],
          ['create_time', '发布时间', 'datetime'],
          ['status', '查看状态', [0 => '未查看', 1 => '已查看']],
          ['right_button', '操作', 'btn']])
          ->setTableName('member')
            ->addRightButtons(['delete'])
            ->addTopButtons('add,delete') // 批量添加顶部按钮
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
//          ->addTimeFilter('create_time', [$beginday, $endday]) // 添加时间段筛选
          ->fetch(); // 渲染模板
    }

    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            if ($data['dx'] == "1") {
                $mobile_list  = explode(PHP_EOL, $data['mobile_list']);
                $mobile_list = array_filter($mobile_list);
                $userlist     = Db::name('member')->whereIn('mobile', $mobile_list)->select();
                foreach ($userlist as $v) {
                        MemberMessageModel::addInnerMsg(intval($v['id']), $data['title'], $data['info'], 20, '', array("opentype" => $data['opentype'], "openpath" => $data['openpath']));//站内信
                }
//                $ids = explode(",", $data['mid']);
//                // 使用array_filter()函数删除空数组元素
//                $ids = array_filter($ids);
//                if ($ids) {
//                    foreach ($ids as $v) {
//                        MemberMessageModel::addInnerMsg(intval($v), $data['title'], $data['info'], 20, '', array("opentype" => $data['opentype'], "openpath" => $data['openpath']));//站内信
//                    }
//                }
                $this->success('新增成功', url('index'));
            } else {
                $map['is_del'] = 0;
                // 数据列表
                $ids = MemberModel::where($map)->select();
                foreach ($ids as $v) {
                    MemberMessageModel::addInnerMsg($v['id'], $data['title'], $data['info'], 20, '', array("opentype" => $data['opentype'], "openpath" => $data['openpath']));//站内信
                }
                $this->success('新增成功', url('index'));
            }
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
          ->setPageTitle('新增') // 设置页面标题
          ->addFormItems([ // 批量添加数据列
            ['hidden', 'type', 50],
            ['radio', 'dx', '发送对象', '', ['0' => '全部', '1' => '指定用户'], 1],
            ['radio', 'opentype', '打开方式', '', ['0' => '不跳转', '1' => '打开内部页面', '2' => '打开网页'], 0],
            ['text', 'openpath', '打开页面路径', '内部页面路径如‘page-trial’或‘userInfo/nickname-setting|3’，有参数的话|分隔开，跳内部页面，需要技术协作，网页需要以https://或http://开通'],
//            ['tags', 'mid', '用户id', '可以输入多个id,只能为数字'],
            ['textarea', 'mobile_list', '手机号', '多个手机号请换行'],
            ['text', 'title', '消息标题', '最长只能30个字'],
            ['ueditor', 'info', '消息内容', '内容不宜太长']])
//          ->setTrigger('dx', '1', 'mid')
          ->setTrigger('dx', '1', 'mobile_list')
          ->setTrigger('opentype', '1,2', 'openpath')
          ->fetch();
    }
    public function delete($record = [])
    {
        $table_name = input('param.table');
        $ids = input('param.ids');
        if (is_array($ids)) {
            $result = MemberMessageModel::where('id', 'IN', $ids)->delete();
        } else {
            $result = MemberMessageModel::where(['id' => $ids])->delete();
        }
        if ($result) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    public function delete2()
    {
        $data = input();
        $id = $data['ids'];
        if (is_array($id)) {
            $d = MemberMessageModel::where('id', 'IN', $id)->delete();
        } else {
            $d = MemberMessageModel::where(['id' => $id])->delete();
        }
        if ($d) {
            $this->success('操作成功', 'index');
        } else {
            $this->error('删除错误');
        }
    }
}