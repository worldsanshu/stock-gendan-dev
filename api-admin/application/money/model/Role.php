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
namespace app\money\model;

use think\Model;
use util\Tree;

/**
 * 角色模型
 * @package app\admin\model
 */
class Role extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__ADMIN_ROLE__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 写入时，将菜单id转成json格式
    public function setMenuAuthAttr($value)
    {
        return json_encode($value);
    }

    // 读取时，将菜单id转为数组
    public function getMenuAuthAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 获取树形角色
     * @param null $id 需要隐藏的角色id
     * @param string $default 默认第一个菜单项，默认为“顶级角色”，如果为false则不显示，也可传入其他名称
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public static function getTree($id = null, $default = '')
    {
        $result[0] = '顶级角色';
        $where['status'] = ['egt', 0];
        // 排除指定菜单及其子菜单
        if ($id !== null) {
            $hide_ids = array_merge([$id], self::getChildsId($id));
            $where['id'] = ['notin', $hide_ids];
        }
        // 获取菜单
        $roles = Tree::config(['title' => 'name'])->toList(self::where($where)->column('id,pid,name'));
        foreach ($roles as $role) {
            $result[$role['id']] = $role['title_display'];
        }
        // 设置默认菜单项标题
        if ($default != '') {
            $result[0] = $default;
        }
        // 隐藏默认菜单项
        if ($default === false) {
            unset($result[0]);
        }
        return $result;
    }

    /**
     * 获取所有子角色id
     * @param string $pid 父级id
     * @return array
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public static function getChildsId($pid = '')
    {
        $ids = self::where('pid', $pid)->column('id');
        foreach ($ids as $value) {
            $ids = array_merge($ids, self::getChildsId($value));
        }
        return $ids;
    }

    /**
     * 检查访问权限
     * @param int $id 需要检查的节点ID，默认检查当前操作节点
     * @param bool $url 是否为节点url，默认为节点id
     * @return bool
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public static function checkAuth($id = 0, $url = false)
    {
        // 当前用户的角色
        $role = session('user_auth.role');
        // id为1的是超级管理员，或者角色为1的，拥有最高权限
        if (session('user_auth.uid') == '1' || $role == '1') {
            return true;
        }
        // 获取当前用户的权限
        $menu_auth = session('role_menu_auth');
        // 检查权限
        if ($menu_auth) {
            if ($id !== 0) {
                return $url === false ? isset($menu_auth[$id]) : in_array($id, $menu_auth);
            }
            // 获取当前操作的id
            $location = MenuModel::getLocation();
            $action = end($location);
            return $url === false ? isset($menu_auth[$action['id']]) : in_array($action['url_value'], $menu_auth);
        }
        // 其他情况一律没有权限
        return false;
    }

    /**
     * 读取当前角色权限
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public function roleAuth()
    {
        $menu_auth = cache('role_menu_auth_' . session('user_auth.role'));
        if (!$menu_auth) {
            $menu_auth = self::where('id', session('user_auth.role'))->value('menu_auth');
            $menu_auth = json_decode($menu_auth, true);
            $menu_auth = MenuModel::where('id', 'in', $menu_auth)->column('id,url_value');
        }
        // 非开发模式，缓存数据
        if (config('develop_mode') == 0) {
            cache('role_menu_auth_' . session('user_auth.role'), $menu_auth);
        }
        return $menu_auth;
    }

    /**
     * 根据节点id获取所有角色id和权限
     * @param string $menu_id 节点id
     * @param bool $menu_auth 是否返回所有节点权限
     * @return array
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public static function getRoleWithMenu($menu_id = '', $menu_auth = false)
    {
        if ($menu_auth) {
            return self::where('menu_auth', 'like', '%"' . $menu_id . '"%')->column('id,menu_auth');
        } else {
            return self::where('menu_auth', 'like', '%"' . $menu_id . '"%')->column('id');
        }
    }

    /**
     * 根据角色id获取权限
     * @param array $role 角色id
     * @return array
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public static function getAuthWithRole($role = [])
    {
        return self::where('id', 'in', $role)->column('id,menu_auth');
    }
}