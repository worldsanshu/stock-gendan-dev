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


use app\cms\model\Advert as AdvertModel;
use app\cms\model\Column as ColumnModel;
use think\Db;
use think\facade\Lang;
use think\facade\Log;

/**
 * 前台首页控制器
 */
class Index extends Common
{

    public function index()
    {
        ajaxmsg("请求路径错误", 0, '', true, ['msgCode' => 'L0121']);
    }

    //app弹窗
    public function waptc()
    {
        $lang = Lang::range();
        $map['lang'] = $lang;
        $map['status'] = 1;
        $map['tagname'] = 'index_alert';
        $info = AdvertModel::where($map)->find();
        if ($info) {
            ajaxmsg("请求成功", 1, $info, true, ['msgCode' => 'L0011']);
        }
        ajaxmsg("无数据", 0, '', true, ['msgCode' => 'L0122']);
    }

    //app版本升级
    public function appupdata()
    {
        //file_put_contents(ROOT_PATH . '/public/log/appupdata-' . date("Y-m-d") . '.txt', var_export($_GET, true) . '\r\n', FILE_APPEND);
        Log::debug('appupdata-'.var_export($_GET, true));
        # $appdata=array("version"=>config("appversion"),"msg"=>config("appmsg"));
        $appdata = array("version" => "2012", "msg" => "版本更新内容：<br>1、优化banner点击跳转问题；<br>2、修复股价变动消息提醒；<br>3、优化UI<br>出现异常或出现两个版本，请先删除老版本。", "url" => "http://jd1.ghypz.com/appdown/");
        ajaxmsg("请求成功", 1, $appdata);
    }

    public function appindex()
    {
        $data = array();
        $data['cmslist'] = self::gglist();
        $data['banner'] = self::apigetSlider(1);  //预计2023年8月就不用该字段了  被ADlist取代
        $data['ADlist'] = array("ad1" => self::apigetSlider(1), "ad2" => self::apigetSlider(3, false));
        ajaxmsg("请求成功", 1, $data);
    }



    /**
     * 公告
     * @return void
     */
    public function partner()
    {
        $lang = Lang::range();
//
        $info = AdvertModel::where([['lang', '=', $lang], ['status', '=', 1], ['tagname', '=', 'partner_alert']])->find();
//        $tca = Db::query('select * from lmq_cms_column where id=?', [14]);
        if ($info) {
            ajaxmsg("请求成功", 1, $info, true, ['msgCode' => 'L0011']);
        } else {
            ajaxmsg("无数据", 0, '', true, ['msgCode' => 'L0122']);
        }
    }


    /**
     * 配资版本的首页新闻资讯
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function gglist()
    {
        $id = 2;
        if ($id === null) $this->error('缺少参数');
        $map = [
            'status' => 1,
            'id' => $id
        ];
        $column = Db::name('cms_column')->where($map)->find();
        if (!$column) $this->error('该栏目不存在');
        $model = Db::name('cms_model')->where('id', $column['model'])->find();
        print_r($model);
        $model = empty($model) ? [] : $model->toArray();
        if ($model['type'] == 2) {
            $cid_all = ColumnModel::getChildsId($id);
            $cid_all[] = (int)$id;
            $map = [
                [$model['table'] . '.trash', '=', 0],
                [$model['table'] . '.status', '=', 1],
                [$model['table'] . '.cid', 'in', $cid_all]
            ];
            $data_list = Db::view($model['table'], true)
                ->view('admin_user', 'username', $model['table'] . '.uid=admin_user.id', 'left')
                ->where($map)
                ->order('create_time desc')
                ->paginate(config('list_rows'));
            $this->assign('model', $column['model']);
        } else {
            $cid_all = ColumnModel::getChildsId($id);
            $cid_all[] = (int)$id;
            $lang = Lang::range();
            if (empty($lang) || $lang == 'undefined')
                $lang = 'zh-cn';
            $map = [
                ['cms_document.trash', '=', 0],
                ['cms_document.status', '=', 1],
                ['cms_document.cid', 'in', $cid_all],
                ['cms_document.language', '=', $lang]
            ];
            $data_list = Db::view('cms_document', true)
                ->view('admin_user', 'username', 'cms_document.uid=admin_user.id', 'left')
                ->view($model['table'], '*', 'cms_document.id=' . $model['table'] . '.aid', 'left')
                ->where($map)
                ->order('sort desc, create_time desc')
                ->paginate(config('list_rows'));
            $this->assign('model', '');
        }
        $data = [];
        if ($data_list) {
            $list = empty($data_list) ? $data_list : $data_list->toArray();
            foreach ($list['items'] as $key => $value) {
                $data[$key]['title'] = $value['title'];
                $data[$key]['id'] = $value['id'];
                $data[$key]['cid'] = $value['cid'];
                $data[$key]['model'] = $value['model'];
            }
        }
        return $data;
    }

    /**
     * 获取广告位列表
     * $ifwap 指定获取类型
     *  $ismore true 获取多条 false 获取单条
     * */
    public function apigetSlider($ifwap = 1, $ismore = true)
    {
        #pc：0   app：1  wap：2
        $map['ifwap'] = $ifwap;
        $map['lang'] = Lang::range();
        $map['status'] = 1;
        #print_r($map);
        if ($ismore) {
            $banner = Db::name('cms_slider')->where($map)->field("id,title,cover,sort,url,jumpid")->select();
            foreach ($banner as $key => $val) {
                $banner[$key]['img_url'] = 'https://' . $_SERVER['HTTP_HOST'] . get_files_path($val['cover']);
            }
        } else {
            $banner = Db::name('cms_slider')->where($map)->field("id,title,cover,sort,url,jumpid")->order('id desc')->find();
            $banner['img_url'] = 'https://' . $_SERVER['HTTP_HOST'] . get_files_path($banner['cover']);
        }
        return $banner;
    }


    /**
     * 优投新闻资讯列表
     * @return void
     * @throws \think\exception\DbException
     */
    public function newslist()
    {
        $data = input();
        $lang = Lang::range();
        $page = input('page', 1); // 默认页码为1
        $listRows = input('list_rows', config('paginate.list_rows')); // 每页显示条数

        if ($data['type'] == 2) {

            $data_list =
                Db::name('cms_kuaixun')
                    ->field(['id', 'rich_text', 'update_time', 'top_value', 'url'])
                    ->order('update_time desc')
                    ->paginate($listRows, false, ['page' => $page]);
        } else {


            $map = [
                ['trash', '=', 0],
                ['status', '=', 1],
                ['cid', '=', 11],
                ['cj_thumbs', 'neq', ''],
                ['language', 'in', "$lang,cn"]
            ];
            $data_list =
                Db::name('cms_document')
                    ->field(['id', 'cid', 'title', 'model', 'cj_thumbs','create_time'])
//                    ->field('id, cid, title, model, cj_thumbs,create_time')
                    ->whereNotNull('cj_thumbs')
                    ->where($map)
                    ->order('create_time desc')
                    ->paginate($listRows, false, ['page' => $page]);
            $list = $data_list->toArray();
//            print_r($list);die;
            foreach ($list['data'] as $key => &$val) {
                $val['create_time'] = date('Y-m-d H:i:s', $val['create_time']);
            }
            $data_list = $list;
        }


        ajaxmsg("请求成功", 1, $data_list, true, ['msgCode' => 'L0011']);

    }


    public function getSlider()
    {
        $equipment = input("equipment");
        if ($equipment == 2) {
            $map['ifwap'] = 1;
        } else if ($equipment == 1) {
            $map['ifwap'] = 1;
        }
        $map['status'] = 1;
        $banner = Db::name('cms_slider')->where($map)->select();
        foreach ($banner as $key => $val) {
            $banner[$key]['img_url'] = 'https://' . $_SERVER['HTTP_HOST'] . get_files_path($val['cover']);
        }
        ajaxmsg('获取成功', 1, $banner);
    }

    public function getconf()
    {
        $data['money'] = 2000;
        // $data['rate'] = 20;
        $data['DivideInto'] = explode('|', config('free_set'))[2] . '%';;
        $data['iftrade'] = get_trading_time();
        $data['kftime'] = config('web_site_service_time');
        $data['kfphone'] = config('web_site_telephone');
        $mid = MID;
        if ($mid) {
            $msg_num = Db('member_message')->where(['mid' => MID, 'status' => 0])->count();
            $data['msg_num'] = $msg_num;
        } else {
            $data['msg_num'] = 0;
        }
        ajaxmsg('获取成功', 1, $data);
    }


    /*
     * app升级操作
     */
    public function upgrade()
    {
        $app_open = config('app_open');
        $data['app_open'] = $app_open;
        $data['app_name'] = config('app_name');
        $data['app_down_android'] = config('app_down_android');
        $data['power_android'] = config('power_android');
        $data['version_androd_name'] = config('version_androd_name');
        $data['version_androd_code'] = config('version_androd_code');
        $data['description_android'] = config('description_android');
        $data['down_ios'] = config('down_ios');
        $data['power_ios'] = config('power_ios');
        $data['version_ios'] = config('version_ios');
        $data['description_ios'] = config('description_ios');
        if ($app_open) {
            ajaxmsg('app信息', 1, $data);
        } else {
            ajaxmsg('后台暂停APP升级', 0);
        }
    }

    /*
     * app wap 单页
     */
    public function getWapPage()
    {
        $data['regwap'] = http() . $_SERVER["HTTP_HOST"] . '/wap/agreement/register';
        $data['protocol'] = http() . $_SERVER["HTTP_HOST"] . '/wap/agreement/register';
        ajaxmsg('获取成功', 1, $data);
    }

    public function upload()
    {
        $file = request()->file('file');

        //查询图片上传限制
        $admin_config = Db::name('admin_config')
            ->where('name', 'upload_image_size')
            ->whereOr('name', 'upload_image_ext')
            ->column('value', 'name');
        $size = $admin_config['upload_image_size'] * 1000;
        $ext = $admin_config['upload_image_ext'];
        // 移动到框架应用根目录/public/uploads/ images目录下

        $info = $file->validate(['size' => $size, 'ext' => $ext])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'images');
        $_siez_m = round(floatval($admin_config['upload_image_size'] / 1024), 0);//转换成M
        if ($info) {
            $savename = $info->getSaveName();
            $file_path = images_url($savename);
            $data['file_path'] = $file_path;
            ajaxmsg('上传成功', 1, $data, true, ['msgCode' => 'L0139']);
        } else {
            $msg = $file->getError();
            if ($msg == '上传文件大小不符！') {
                ajaxmsg("图片大小不能超过'.$_siez_m.' M", 0, '', true, ['msgCode' => 'L0148', 'params' => ["{$_siez_m}"]]);
            } elseif ($msg == '上传文件后缀不允许') {
                ajaxmsg('请上传' . $ext . '格式的图片', 0, '', true, ['msgCode' => 'L0147', 'params' => ["{$admin_config['upload_image_ext']}"]]);
            } else {
                ajaxmsg($file->getError(), 0);
            }
        }
    }

    public function translate()
    {
        $data = input();
        $content = $data['content'];
        $from = $data['from'];
        $to = $data['to'];
        $res = baidu_translate($content, $from, $to);
        dump($res);
        exit;
    }

    //获取域名列表
    public function getDomainList()
    {
        $list = config('domain_name_array');
        $list = explode('|', $list);
        $data['list'] = $list;
        ajaxmsg('获取成功', 1, $data);
    }

}