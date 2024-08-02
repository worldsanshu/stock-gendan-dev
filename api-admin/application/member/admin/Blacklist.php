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
namespace app\member\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\member\model\MemberBlacklist as MemberBlacklistModel;
use app\member\model\MemberBlacklistLog;

/**
 * 语言控制器
 * @package app\member\admin
 */
class Blacklist extends Admin
{
    /**
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('update_time desc');
        // 数据列表
        $data_list = MemberBlacklistModel::where($map)->order($order)->paginate();
        foreach ($data_list as &$value){
            $value['idcard'] = privacy_info_switch('id_card',$value['idcard']);
        }
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        $btn_getlog = ['title' => '使用详情', 'icon' => 'fa fa-fw fa-paste', 'href' => url('getlog', ['idcard' => '__idcard__'])];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
          ->setSearch(['idcard' => '身份证']) // 设置搜索框
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['idcard', '身份证号码'],
            ['remarks', '备注'],
            ['operator', '操作管理员'],
            ['create_time', '时间', 'datetime'],
            ['right_button', '操作', 'btn']
          ])
          ->addTopButtons('add,delete') // 批量添加顶部按钮
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
          ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
          ->addRightButton('custom', $btn_getlog)
          ->addOrder('id,title,type,create_time,update_time')
          ->setRowList($data_list) // 设置表格数据
          ->fetch(); // 渲染模板
    }

    /**
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function getlog($idcard = '')
    {
        // 查询
        // 排序
        $order = $this->getOrder('id desc');
        // 数据列表
        $data_list = MemberBlacklistLog::where('idcard', $idcard)->order($order)->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['idcard', '身份证号码'],
            ['create_time', '时间', 'datetime'],
          ])
          ->addOrder('id,idcard,create_time,update_time')
          ->setRowList($data_list) // 设置表格数据
          ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function add()
    {
        $user = session('user_auth');
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            $data['operator'] = $user['username'];
            // 验证
            $row = MemberBlacklistModel::where('idcard',$data['idcard'])->find();
            if (!empty($row)) {
                $this->error('已存在相同数据');
            }
            if ($link = MemberBlacklistModel::create($data)) {
                // 记录行为
                action_log('link_add', 'cms_link', $link['id'], UID, $data);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['text', 'idcard', '身份证号码'],
            ['text', 'remarks', '备注'],
          ])
          ->setTrigger('type', 2, 'logo')
          ->fetch();
    }

    /**
     * 编辑
     * @param null $id 链接id
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            $user = session('user_auth');
            $data['operator'] = $user['username'];
            // 验证
//            $result = $this->validate($data, 'language');
//            if(true !== $result) $this->error($result);
            if (MemberBlacklistModel::update($data)) {
                // 记录行为
//                action_log('link_edit', 'cms_link', $id, UID, $data['title']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        $info = MemberBlacklistModel::get($id);
        //        开启才能看隐私信息
        $privacy = cookie('__privacy__');
        if($privacy == 'close'){
            $info['idcard'] = privacy_info_switch('id_card',$info['idcard']);
            $privacy_id_card = ['static', 'idcard', '身份证号码'];
        }else{
            $privacy_id_card = ['text', 'idcard', '身份证号'];
        }
        // 显示编辑页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['hidden', 'id'],
//            ['text', 'idcard', '身份证号码'],
              $privacy_id_card,
            ['text', 'remarks', '备注'],
          ])
          ->setTrigger('type', 2, 'logo')
          ->setFormData($info)
          ->fetch();
    }

    /**
     * 删除友情链接
     * @param array $record 行为日志
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用友情链接
     * @param array $record 行为日志
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用
     * @param array $record 行为日志
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置友情链接状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function setStatus($type = '', $record = [])
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $link_title = MemberBlacklistModel::where('id', 'in', $ids)->column('idcard');
        return parent::setStatus($type, ['link_' . $type, 'cms_link', 0, UID, implode('、', $link_title)]);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function quickEdit($record = [])
    {
        $id = input('post.pk', '');
        $field = input('post.name', '');
        $value = input('post.value', '');
        $link = MemberBlacklistModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $link . ')，新值：(' . $value . ')';
        return parent::quickEdit(['link_edit', 'cms_link', $id, UID, $details]);
    }
}