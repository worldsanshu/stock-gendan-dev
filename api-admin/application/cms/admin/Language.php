<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace app\cms\admin;

use app\admin\controller\Admin;
use app\cms\model\Language as languageModel;
use app\common\builder\ZBuilder;

/**
 * 语言控制器
 * @package app\cms\admin
 */
class language extends Admin
{
    /**
     *
     * @return mixed
     * @author 路人甲乙
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('update_time desc');
        // 数据列表
        $data_list = languageModel::where($map)->order($order)->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['name', '中文名称'],
            ['english', '英文名称'],
            ['right_button', '操作', 'btn']
          ])
          ->addTopButtons('add,delete') // 批量添加顶部按钮
          ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
          ->addOrder('id,title,type,create_time,update_time')
          ->setRowList($data_list) // 设置表格数据
          ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     * @author 路人甲乙
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            // 验证
//            $result = $this->validate($data, 'language');
//            if(true !== $result) $this->error($result);
            $c_name = languageModel::where('name',$data['name'])->find();
            $e_name = languageModel::where('english',$data['english'])->find();
            if($c_name || $e_name){
                $this->error('中文名称或英文名称重复');
            }
            if ($link = languageModel::create($data)) {
                // 记录行为
//                action_log('link_add', 'cms_link', $link['id'], UID, $data['title']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['text', 'name', '中文名称'],
            ['text', 'english', '英文名称'],
          ])
          ->setTrigger('type', 2, 'logo')
          ->fetch();
    }

    /**
     * 编辑
     * @param null $id 链接id
     * @author 路人甲乙
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            // 验证
//            $result = $this->validate($data, 'language');
//            if(true !== $result) $this->error($result);
            $c_name = languageModel::where('name',$data['name'])->find();
            $e_name = languageModel::where('english',$data['english'])->find();
            if($c_name){
                if($c_name['id'] != $data['id'])
                $this->error('中文名称重复');
            }
            if($e_name){
                if($e_name['id'] != $data['id'])
                    $this->error('英文名称重复');
            }

            if (languageModel::update($data)) {
                // 记录行为
//                action_log('link_edit', 'cms_link', $id, UID, $data['title']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        $info = languageModel::get($id);
        // 显示编辑页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['hidden', 'id'],
            ['text', 'name', '中文名称'],
            ['text', 'english', '英文名称'],
          ])
          ->setTrigger('type', 2, 'logo')
          ->setFormData($info)
          ->fetch();
    }

    /**
     * 删除友情链接
     * @param array $record 行为日志
     * @return mixed
     * @author 路人甲乙
     */
    public function delete($record = [])
    {
//        return $this->setStatus('delete');

        $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids        = (array)$ids;
        $map[]      = ['id', 'in', $ids];
        $result     = languageModel::where($map)->delete();
        if (false !== $result) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 启用友情链接
     * @param array $record 行为日志
     * @return mixed
     * @author 路人甲乙
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用
     * @param array $record 行为日志
     * @return mixed
     * @author 路人甲乙
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
     * @author 路人甲乙
     */
    public function setStatus($type = '', $record = [])
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $link_title = languageModel::where('id', 'in', $ids)->column('title');
        return parent::setStatus($type, ['link_' . $type, 'cms_link', 0, UID, implode('、', $link_title)]);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志
     * @return mixed
     * @author 路人甲乙
     */
    public function quickEdit($record = [])
    {
        $id = input('post.pk', '');
        $field = input('post.name', '');
        $value = input('post.value', '');
        $link = languageModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $link . ')，新值：(' . $value . ')';
        return parent::quickEdit(['link_edit', 'cms_link', $id, UID, $details]);
    }
}