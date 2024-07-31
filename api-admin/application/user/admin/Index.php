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
namespace app\user\admin;

use app\admin\controller\Admin;
use app\admin\model\Access as AccessModel;
use app\admin\model\Module as ModuleModel;
use app\common\builder\ZBuilder;
use app\user\model\Role as RoleModel;
use app\user\model\User as UserModel;
use think\Db;
use think\facade\Hook;
use util\Logs;
use util\Tree;

/**
 * 用户默认控制器
 * @package app\user\admin
 */
class Index extends Admin
{
    /**
     * 用户首页
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        // 数据列表
        $data_list = UserModel::where($map)->order('sort,id desc')->paginate();
        // 分页数据
        $page = $data_list->render();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('用户管理') // 设置页面标题
            ->setTableName('admin_user') // 设置数据表名
            ->setSearch(['id' => 'ID', 'username' => '用户名', 'email' => '邮箱']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                           ['id', 'ID'],
                           ['username', '用户名'],
                           ['nickname', '昵称'],
                           ['role', '角色', 'select', RoleModel::getTree(null, false)],
                           ['email', '邮箱'],
                           ['mobile', '手机号'],
                           ['google_authenticator', '谷歌验证器', 'yesno'],
                           ['create_time', '创建时间', 'datetime'],
                           ['status', '状态', 'switch'],
                           ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮

            ->addRightButton('edit', ['title' => '绑定谷歌登录器', 'href' => url('bindGoogle', ['id' => '__id__'])])
            ->addRightButtons('edit,delete') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page)        // 设置分页数据
            ->fetch(); // 渲染页面
    }

    /**
     * 绑定谷歌登录器
     */
    public function bindGoogle()
    {
        $id   = input('id');
        $user = UserModel::get($id);

        $app_name = config('web_operation_platform');
        $google   = new \GoogleAuthenticator();

        if ($this->request->isPost()) {
            $data   = input();
            $code   = $data['google_code'];
            $secret = $data['secret'];
            if ($data['google_authenticator'] == 1) {
                $checkResult = $google->verifyCode($secret, $code, 1);
                if (!$checkResult) {
                    $this->error('验证失败');
                }
            }
            UserModel::where('id', $id)->update(['google_authenticator' => $data['google_authenticator'], 'google_secret' => $secret]);
            $this->success('新增成功', url('index'));
        }
        $secret    = $google->createSecret();
        $qrCodeUrl = $google->getQRCodeGoogleUrl($user['username'], $secret, $app_name . '后台');

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增') // 设置页面标题

            ->addFormItems([ // 批量添加表单项
                             ['hidden', 'secret', $secret],
                             ['static', 'google_secret', '谷歌秘钥', '', $secret],
                             ['static', 'username', '用户名'],

                             ['img', 'google_authenticator', '谷歌验证器二维码', '已绑定的，扫码输入提交后可修改', $qrCodeUrl],
                             ['text', 'google_code', '验证码', ''],
                             ['radio', 'google_authenticator', '状态', '', ['禁用', '启用']]
            ])->setFormData($user) // 设置表单数据
            ->fetch();
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
            $data = input();
            // 验证
            $result = $this->validate($data, 'User');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            if ($user = UserModel::create($data)) {
                Hook::listen('user_add', $user);
                // 记录行为
                action_log('user_add', 'admin_user', $user['id'], UID);
                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                             ['text', 'username', '用户名', '必填，可由英文字母、数字组成'],
                             ['text', 'nickname', '昵称', '可以是中文'],
                             ['select', 'role', '角色', '', RoleModel::getTree(null, false)],
                             ['text', 'email', '邮箱', ''],
                             ['password', 'password', '密码', '必填，6-20位'],
                             ['text', 'mobile', '手机号'],
                             ['image', 'avatar', '头像'],
                             ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
            ])
            ->fetch();
    }

    /**
     * 编辑
     *
     * @param null $id 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
            // 禁止修改超级管理员的角色和状态
            if ($data['id'] == 1 && $data['role'] != 1) {
                $this->error('禁止修改超级管理员角色');
            }
            // 禁止修改超级管理员的状态
            if ($data['id'] == 1 && $data['status'] != 1) {
                $this->error('禁止修改超级管理员状态');
            }
            // 验证
            $result = $this->validate($data, 'User.update');
            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);
            // 如果没有填写密码，则不更新密码
            if ($data['password'] == '') {
                unset($data['password']);
            }
            if (UserModel::update($data)) {
                $user = UserModel::get($data['id']);
                Hook::listen('user_edit', $user);
                // 记录行为
                action_log('user_edit', 'admin_user', $user['id'], UID, get_nickname($user['id']));
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        // 获取数据
        $info = UserModel::where('id', $id)->field('password', true)->find();
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                             ['hidden', 'id'],
                             ['static', 'username', '用户名', '不可更改'],
                             ['text', 'nickname', '昵称', '可以是中文'],
                             ['select', 'role', '角色', '', RoleModel::getTree(null, false)],
                             ['text', 'email', '邮箱', ''],
                             ['password', 'password', '密码', '必填，6-20位'],
                             ['text', 'mobile', '手机号'],
                             ['image', 'avatar', '头像'],
                             ['radio', 'status', '状态', '', ['禁用', '启用']]
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    /**
     * 授权
     *
     * @param string $tab tab分组名
     * @param int $uid 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function access($tab = '', $uid = 0)
    {
        if ($uid === 0) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $post = input();
            list($module, $group) = explode('_', $post['tab_name']);
            // 先删除原有授权
            $map['module'] = $module;
            $map['group']  = $group;
            $map['uid']    = $post['uid'];
            if (false === AccessModel::where($map)->delete()) {
                $this->error('清除旧授权失败');
            }
            $data = [];
            // 授权节点
            $nids = [];
            if (isset($post['group_auth'])) {
                $nids = $post['group_auth'];
                foreach ($post['group_auth'] as $nid) {
                    $data[] = [
                        'nid'    => $nid,
                        'uid'    => $post['uid'],
                        'group'  => $group,
                        'module' => $module
                    ];
                }
                // 添加新的授权
                $AccessModel = new AccessModel;
                if (!$AccessModel->saveAll($data)) {
                    $this->error('操作失败');
                }
            }
            // 记录行为
            $nids    = !empty($nids) ? implode(',', $nids) : '无';
            $details = "模块($module)，分组($group)，授权节点ID($nids)";
            action_log('user_access', 'admin_user', $uid, UID, $details);
            $this->success('操作成功', 'index');
        }
        // 获取所有授权配置信息
        $list_access = ModuleModel::where('access', 'neq', '')->column('access', 'name');
        if ($list_access) {
            $curr_access = '';
            $group_table = '';
            $tab_list    = [];
            foreach ($list_access as $module => &$groups) {
                $groups = json_decode($groups, true);
                foreach ($groups as $group => $access) {
                    // 如果分组为空，则默认为第一个
                    if ($tab == '') {
                        // 当前分组名
                        $tab = $module . '_' . $group;
                        // 节点表名
                        $group_table = $access['table_name'];
                        // 当前权限配置信息
                        $curr_access = $access;
                    }
                    // 配置分组信息
                    $tab_list[$module . '_' . $group] = [
                        'title' => $access['tab_title'],
                        'url'   => url('access', [
                            'tab' => $module . '_' . $group,
                            'uid' => $uid
                        ])
                    ];
                }
            }
            list($module, $group) = explode('_', $tab);
            if ($curr_access == '') {
                $curr_access = $list_access[$module][$group];
                $group_table = $curr_access['table_name'];
            }
            // tab分组信息
            $tab_nav = [
                'tab_list' => $tab_list,
                'curr_tab' => $tab
            ];
            $this->assign('tab_nav', $tab_nav);
            // 获取授权数据
            $groups = '';
            if (isset($curr_access['model_name']) && $curr_access['model_name'] != '') {
                $class = "app\\{$module}\\model\\" . $curr_access['model_name'];
                $model = new $class;
                try {
                    $groups = $model->access();
                } catch (\Exception $e) {
                    $this->error('模型：' . $class . "缺少“access”方法");
                }
            } else {
                // 没有设置模型名，则按表名获取数据
                $fileds = [
                    $curr_access['primary_key'],
                    $curr_access['parent_id'],
                    $curr_access['node_name']
                ];
                $groups = Db::name($group_table)->order($curr_access['primary_key'])->field($fileds)->select();
            }
            if ($groups) {
                // 查询当前用户的权限
                $map['module'] = $module;
                $map['group']  = $group;
                $map['uid']    = $uid;
                $node_access   = AccessModel::where($map)->column('nid');
                $this->assign('node_access', $node_access);
                $this->assign('tab_name', $tab);
                $this->assign('field_access', $curr_access);
                $tree_config = [
                    'title' => $curr_access['node_name'],
                    'id'    => $curr_access['primary_key'],
                    'pid'   => $curr_access['parent_id']
                ];
                $this->assign('groups', Tree::config($tree_config)->toList($groups));
            }
        }
        $page_tips = isset($curr_access['page_tips']) ? $curr_access['page_tips'] : '';
        $tips_type = isset($curr_access['tips_type']) ? $curr_access['tips_type'] : 'info';
        $this->assign('page_tips', $page_tips);
        $this->assign('tips_type', $tips_type);
        $this->assign('page_title', '数据授权');
        return $this->fetch();
    }

    /**
     * 删除用户
     *
     * @param array $ids 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function delete($ids = [])
    {
        Hook::listen('user_delete', $ids);
        return $this->setStatus('delete');
    }

    /**
     * 启用用户
     *
     * @param array $ids 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function enable($ids = [])
    {
        Logs::log('index_enable1', ['ids' => $ids], 'user');
        Hook::listen('user_enable', $ids);
        return $this->setStatus('enable');
    }

    /**
     * 禁用用户
     *
     * @param array $ids 用户id
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function disable($ids = [])
    {
        Hook::listen('user_disable', $ids);
        return $this->setStatus('disable');
    }

    /**
     * 设置用户状态：删除、禁用、启用
     *
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function setStatus($type = '', $record = [])
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        if ((is_array($ids) && in_array(UID, $ids)) || $ids == UID) {
            $this->error('禁止操作当前账号');
        }
        $uid_delete = is_array($ids) ? '' : $ids;
        $ids        = array_map('get_nickname', (array)$ids);
        return parent::setStatus($type, ['user_' . $type, 'admin_user', $uid_delete, UID, implode('、', $ids)]);
    }

    /**
     * 快速编辑
     *
     * @param array $record 行为日志
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function quickEdit($record = [])
    {
        $id = input('post.pk', '');
        $id == UID && $this->error('禁止操作当前账号');
        $field = input('post.name', '');
        $value = input('post.value', '');
//        if($field=="google_authenticator" && $value==true){
//            $url=url("user/index/bindgoogle",["id"=>$id]);
//            $this->success('请先绑定谷歌验证码', $url);
//        }
        $config  = UserModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $config . ')，新值：(' . $value . ')';
        return parent::quickEdit(['user_edit', 'admin_user', $id, UID, $details]);
    }

    /**
     * Desc : 登录日志
     * Date : 2024-06-25 21:22
     * @return mixed
     * @throws \think\Exception
     */
    public function loginlog()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        // 数据列表
        $data_list = Db::name("admin_login_logs")->where($map)->order('id desc')->paginate();
        // 分页数据
        $page = $data_list->render();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('管理员登录日志') // 设置页面标题
            ->setTableName('admin_login_logs') // 设置数据表名
            ->setSearch(['username' => '用户名', 'create_ip' => 'IP']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                           ['id', 'ID'],
                           ['create_ip', 'IP'],
                           ['username', '用户名'],
                           ['zone', '区域'],
                           ['status', '是否登录成功', 'yesno'],
                           ['note', '备注'],

                           ['UA', 'UA'],
                           ['data', '请求数据'],
                           ['addtime', '登录时间', 'datetime']
            ])
          ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->setColumnWidth('UA', 150)
            ->setColumnWidth('data', 250)
            ->fetch(); // 渲染页面
    }

}
