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
namespace app\apicom\home;

use think\Db;

/**
 * 前台搜索控制器
 * @package app\apicom\admin
 */
class Search extends Common
{
    /**
     * 搜索列表
     * @param string $keyword 关键词
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function index($keyword = '')
    {
        $post = input();
        $keyword = $post['keyword'];
        if ($keyword == '') ajaxmsg('请输入关键字', 0, '', true, ['msgCode' => 'L0072']);
        $map = [
          'cms_document.trash' => 0,
          'cms_document.status' => 1,
          'cms_document.title' => ['like', "%$keyword%"]
        ];
        $data_list = Db::view('cms_document', true)
          ->view('admin_user', 'username', 'cms_document.uid=admin_user.id', 'left')
          ->where($map)
          ->order('create_time desc')
          ->paginate(config('list_rows'));
        ajaxmsg('搜索列表', 1, $data_list, true, ['msgCode' => 'L0141']);
    }
}