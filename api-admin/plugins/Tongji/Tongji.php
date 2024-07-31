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
namespace plugins\Tongji;

use app\common\controller\Plugin;

/**
 * 系统环境信息插件
 * @package plugins\DevTeam
 * @author 蔡伟明 <314013107@qq.com>
 */
class Tongji extends Plugin
{
    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
      'name' => 'Tongji',
        // 插件标题[必填]
      'title' => '数据统计',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
      'identifier' => 'Tongji.ming.plugin',
        // 插件图标[选填]
      'icon' => 'fa fa-fw fa-users',
        // 插件描述[选填]
      'description' => '在后台首页营收数据统计',
        // 插件作者[必填]
      'author' => '蔡伟明 <314013107@qq.com>',
        // 作者主页[选填]
      'author_url' => 'http://www.lurenjiayi.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
      'version' => '1.0.0',
        // 是否有后台管理功能[选填]
      'admin' => '0',
    ];
    /**
     * @var array 插件钩子
     */
    public $hooks = [
      'admin_index'
    ];

    /**
     * 后台首页钩子
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function adminIndex()
    {
        $config = $this->getConfigValue();
        if ($config['display']) {
            $this->assign('transfer_nodo', $transfer_nodo);
            //$this->fetch('tongji', $config);
        }
    }

    /**
     * 安装方法
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function install()
    {
        return true;
    }

    /**
     * 卸载方法必
     * @return bool
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function uninstall()
    {
        return true;
    }
}