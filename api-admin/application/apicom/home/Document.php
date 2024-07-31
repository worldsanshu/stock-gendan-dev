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

use app\apicom\model\Document as DocumentModel;
use think\Db;
use think\facade\Lang;

/**
 * 文档控制器
 * @package app\apicom\home
 */
class Document extends Common
{
    /**
     * 文档详情页
     * @param null $id 文档id
     * @param string $model 独立模型id
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function detail($id = null, $model = '')
    {
        $req = request();
        $id = intval(input("id"));
        $type = intval(input("type"));
        //  $model = input("model");
        //  if($model =='') {     }
        //         // $model = 2;
        $model = Db::name("cms_document")->where("id", $id)->value('model');
        if ($id === null) ajaxmsg('缺少参数', 0, '', true, ['msgCode' => 'L0012']);
        $lang = Lang::range();
        if ($lang != 'zh-cn') {
            //办公环境
            if ($id == '7')
                $id = $lang == 'en-us' ? 522 : 523;
            if ($id == '6')
                $id = $lang == 'en-us' ? 525 : 524;
            if ($id == '39')
                $id = $lang == 'en-us' ? 524 : 525;
            if ($id == '5')
                $id = $lang == 'en-us' ? 531 : 530;
            if ($id == '43')//注册协议
                $id = $lang == 'en-us' ? 532 : 533;
        }
        if ($model != '') {
            $table = get_model_table($model);
            $map = [
                //'n.status' => 1,
                //'n.trash'  => 0
            ];
            $info = DocumentModel::getOne($id, $model, $map);
            $info['create_time'] = getTimeFormt($info['create_time'], 1);
            $host = 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/';
            $info['content'] = str_replace('/uploads/', $host, $info['content']);
            if (isset($info['tags'])) {
                $info['tags'] = explode(',', $info['tags']);
            }
        } else {
            if($type == 2){
                $kuaixun = Db::name("cms_kuaixun")->where("id", $id)->find();
                $info['create_time'] = date('Y-m-d H:i:s', $kuaixun['addtime']);
                $info['content'] = $kuaixun['rich_text'];

            }else{
                $map = [
                    'cms_document.status' => 1,
                    'cms_document.trash' => 0,
                ];
                $info = DocumentModel::getOne($id, $model, $map);
                $info['create_time'] = getTimeFormt($info['create_time'], 1);
                $host = 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/';
                $info['content'] = str_replace('/uploads/', $host, $info['content']);
                if (isset($info['tags'])) {
                    $info['tags'] = explode(',', $info['tags']);
                }
            }

        }

        ajaxmsg('获取成功', 1, $info, true, ['msgCode' => 'L0014']);
    }

    /**
     * 快讯详情页
     * @param null $id 文档id
     * @param string $model 独立模型id
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function detailKuai($id = null)
    {
        $id = intval(input("id"));

        $kuaixun = Db::name("cms_kuaixun")->where("id", $id)->find();
        $info['create_time'] = date('Y-m-d H:i:s', $kuaixun['addtime']);
        $info['content'] = $kuaixun['rich_text'];

        ajaxmsg('获取成功', 1, $info, true, ['msgCode' => 'L0014']);
    }
}