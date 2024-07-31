<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +------------------------------------------------------------------
namespace app\agents\admin;

use app\admin\controller\Admin;
use app\agents\model\AgentCode as AgentCodeModel;
use app\common\builder\ZBuilder;
use think\facade\Cache;

class Agentcode extends Admin
{
    public function index()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('update_time desc');
        // 数据列表
        $data_list = AgentCodeModel::where($map)->order($order)->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['code', '邀请码'],
            ['download_link', '下载链接'],
            ['status', '状态', [0 => '未使用', 1 => '已使用']],
            ['right_button', '操作', 'btn']
          ])
          ->addTopButtons('add,delete') // 批量添加顶部按钮
          ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
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
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $num = input('num');
            $add_data = [];
            for ($i = $num - 1; $i >= 0; $i--) {
                $lenth = 8 - strlen($num);
                $str = substr(md5(uniqid()), 0, $lenth);
                $invite_code = $str . $i;
                $add_data[$i]['code'] = $invite_code;
                $add_data[$i]['name'] = $invite_code;
            }
            // 验证
            if ($link = AgentCodeModel::insertAll($add_data)) {
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['text', 'num', '数量'],
          ])
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
            // 验证
//            $result = $this->validate($data, 'language');
//            if(true !== $result) $this->error($result);
            if (AgentCodeModel::update($data)) {
                // 记录行为
//                action_log('link_edit', 'cms_link', $id, UID, $data['title']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        $info = AgentCodeModel::get($id);
        // 显示编辑页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['hidden', 'id'],
            ['text', 'download_link', '下载链接'],
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
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids = (array)$ids;

        $map[] = ['id', 'in', $ids];
        empty($ids) && $this->error('缺少主键');
        $result = AgentCodeModel::where($map)->delete();
        if (false !== $result) {
            Cache::clear();
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
}