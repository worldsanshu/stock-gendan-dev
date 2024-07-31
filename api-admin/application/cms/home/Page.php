<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\cms\home;

use app\cms\model\Page as PageModel;

/**
 * 前台单页控制器
 * @package app\cms\admin
 */
class Page extends Common
{
    /**
     * 单页详情
     * @param null $id 单页id
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function detail($id = null)
    {
        /**
         * know my page id
         * get nav link id from cms_menu throw page id
         */
        $current_nav = db("cms_nav")->where(['tag' => 'cms_nav'])->find();
        $activeMenu = db("cms_menu")->where(["page" => $id, "nid" => $current_nav['id']])->find();
        $info = PageModel::where('status', 1)->find($id);
        $info['url'] = url('cms/page/detail', ['id' => $info['id']]);
        $info['tags'] = explode(',', $info['keywords']);
        // 更新阅读量
        PageModel::where('id', $id)->setInc('view');
        $android_download_page = url('android_qrcode');
        $this->assign('page_info', $info);
        $this->assign('android_download_page', $android_download_page);
        $this->assign('activeMenuId', $activeMenu['id']);
        return $this->fetch($info['template']); // 渲染模板
    }

    public function mobileDownload()
    {
        $this->assign("inWechat", $this->isWechat());
        return $this->fetch('download/mobile');
    }

    protected function isWechat()
    {
        if (!preg_match('/MicroMessenger/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return 0;
        }
        return 1;
    }

    public function android_qrcode()
    {
        $url = url('/androiddownload');
        plugin_action('Qrcode/Qrcode/generate', [$url, APP_PATH . 'qrdownload.png']);
    }
}
