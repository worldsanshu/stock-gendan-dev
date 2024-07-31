<?php

namespace app\stock\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\stock\model\StockList;
use think\Db;
use think\facade\Session;

class Index extends Admin
{
    public function index()
    {
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        // 读取数据
        $data_list = Db::name('stock_list')->where($map)->order($order)->paginate($listRows = 50);
        // 分页数据
        $page = $data_list->render();
        // 自定义按钮
        $btn_sh_all = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '更新上海主板股票',
          'href' => url('sh_all', ["last" => "0"], true)
        ];
        $btn_sz_all = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '更新深圳主板股票',
          'href' => url('sz_all', ["last" => "1"], true)
        ];
        $btn_szcy_all = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '更新深圳创业板股票',
          'href' => url('szcy_all', ["last" => "1"], true)
        ];
        $btn_kc_alls = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '更新科创板块',
          'href' => url('kecuan_all', ["last" => "1"], true)
        ];
        $btn_hs_alls = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '更新沪深A股',
          'href' => url('hushen', ["last" => "1"], true)
        ];
        $btn_all_low = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '设全部股票为可买',
          'href' => url('all_low')
        ];
        $btn_risk_alls = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '设ST*ST为禁买',
          'href' => url('risk_alls')
        ];
        $btn_cy_alls = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '设创业板为禁买',
          'href' => url('cy_alls')
        ];
        $btn_kc_alls = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '设科创板为禁买',
          'href' => url('kc_alls')
        ];
        $btn_jijin_alls = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '设基金债券为禁买',
          'href' => url('jijin_alls')
        ];
        $domain = request()->domain();
        $btn_update_alls = [
          'class' => 'btn btn-success',
          'icon' => 'fa fa-fw fa-key',
          'title' => '添加/更新股票',
          'href' => 'http://jdf10.0p0.cn/stock/crond/autoScript'
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
          ->setPageTitle('股票管理') // 设置页面标题
          ->setTableName('stock_list') // 设置数据表名
          ->addOrder('title,code,pinyin') // 添加排序
          ->addFilter('code') // 添加筛选
          ->setSearch(['code' => '代码', 'title' => '股票名', 'pinyin' => '简拼']) // 设置搜索参数
//                ->addTopButton('custom', $btn_sh_all,true) // 添加顶部按钮
//                ->addTopButton('custom', $btn_sz_all,true) // 添加顶部按钮
//                ->addTopButton('custom', $btn_szcy_all,true) // 添加顶部按钮
//                ->addTopButton('custom', $btn_kc_alls,true) // 添加顶部按钮
//                ->addTopButton('custom', $btn_hs_alls,true) // 添加顶部按钮
          ->addTopButton('custom', $btn_update_alls) // 添加顶部按钮
          ->addTopButton('custom', $btn_all_low) // 添加顶部按钮
          ->addTopButton('custom', $btn_risk_alls) // 添加顶部按钮
          ->addTopButton('custom', $btn_cy_alls) // 添加顶部按钮
          ->addTopButton('custom', $btn_jijin_alls) // 添加顶部按钮
          ->addTopButton('custom', $btn_kc_alls) // 添加顶部按钮
          ->addRightButton('enable', ['table' => 'stock_list']) // 启用
          ->addRightButton('disable', ['table' => 'stock_list']) // 禁用
          ->addRightButton('edit') // 添加编辑按钮
          ->addRightButton('delete') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['title', '股票名'],
            ['code', '股票代码'],
            ['pinyin', '股票缩写'],
            ['add_time', '添加时间', 'datetime'],
            ['quota', '购买总限额(元)'],
            ['status', '状态', 'status', '', ['禁用:danger', '启用:success']],
            ['right_button', '操作', 'btn'],
          ])
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 获取数据
        $info = Db::name('stock_list')->where('id', $id)->field('*', false)->find();
        if ($this->request->isPost()) {
            $data = input();
            $data['quota'] = abs($data['quota']);
            if (StockList::update($data)) {
                $this->success('编辑成功', "index");
            } else {
                $this->error('编辑失败');
            }
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
          ->setPageTitle('编辑') // 设置页面标题
          ->addFormItems([ // 批量添加表单项
            ['hidden', 'id'],
            ['static:6', 'title', '股票名', '股票名'],
            ['static:6', 'code', '代码', '股票代码'],
            ['static:6', 'pinyin', '拼音', '股票名简拼'],
            ['text:3', 'quota', '限额', '平台单支股票购买限额，0为不限制，其余数字为限制额'],
            ['radio', 'status', '状态', '', ['禁用', '启用']]
          ])
          ->setFormData($info) // 设置表单数据
          ->fetch();
    }

    public function delete($record = [])
    {
        return parent::delete($record); // TODO: Change the autogenerated stub
    }

    //更新沪深A股数据
    public function hushen_all()
    {
        $post_data = [];
        $post_data['searchstring'] = '沪深A股';
        $post_data['searchtype'] = 'stock';
        $post_data['act'] = 'smart_stock_picking';
        $url = 'https://jdf10.0p0.cn/stock/api/stockApi';
        $data['data'] = json_encode($post_data);
        $res = curl_post($url, $data);
        $res = json_decode($res, true);
        dump($res);
        exit;
//        $this->view->assign('arr',$arr);
//        $this->view->assign('last',$last);
        $this->view->assign('local', http() . $_SERVER["SERVER_NAME"]);
        return $this->view->fetch();
    }

    //更新深圳主板数据
    public function sz_all()
    {
        $req = request();
        $star = input('last');
//        $url = 'https://jdf10.0p0.cn/stock/api/stockApi';
//        $data['url'] = '1222';
//        $res = curl_post($url, $data);
//        $res = json_decode($res,true);
        $last = $star + 8;
        $model = Db::name('stock_list');
        $arr = array();
        for ($i = $star; $i <= $last; $i++) { //更新深圳主板股票
            $t = substr("00000" . $i, -6, 6);
            $tmd = $model->where("code=$t")->find();
            $dat = sohu_keyword($t);
            if (count($dat[0]) >= 3) {
                $data = $this->each($dat[0]);
                if (empty($tmd)) {
                    $model->data($data)->insert();
                } else {
                    $model->where(['code' => $data['code']])->data($data)->update();
                }
            }
            if (!empty($dat[0])) {
                $arr[$i] = $dat[0];
            };
        }
        $this->view->assign('arr', $arr);
        $this->view->assign('last', $last);
        $this->view->assign('local', http() . $_SERVER["SERVER_NAME"]);
        return $this->view->fetch();
    }

    /*
     * 设置所有st *st 为禁买
     */
    public function risk_alls()
    {
        $stock_list = Db::name('stock_list');
        $class_res = $stock_list->select();
        if (!empty($class_res)) {
            foreach ($class_res as $key => $v) {
                if ($v['status'] == 0) {
                    continue;
                }
                if (substr($v['title'], 0, 1) == 'S' || substr($v['title'], 0, 1) == '*' || substr($v['title'], 0, 1) == 's') {
//                    $map="id=".$v['id'];
                    $ids[] = $v['id'];
                    $data['status'] = 0;
//                    $stock_list->where($map)->update($data);
                }
            }
            $stock_list->where('id', 'in', $ids)->update($data);
        }
        $this->success('设置成功', "index", '', '1');
    }

    /*
     * 设置所有科创板为禁买
     */
    public function kc_alls()
    {
        $stock_list = Db::name('stock_list');
        $class_res = $stock_list->select();
        if (!empty($class_res)) {
            foreach ($class_res as $key => $v) {
                if ($v['status'] == 0) {
                    continue;
                }
                if (substr($v['code'], 0, 2) == '68') {
//                    $map="id=".$v['id'];
                    $ids[] = $v['id'];
                    $data['status'] = 0;
                }
            }
            $stock_list->where('id', 'in', $ids)->update($data);
        }
        $this->success('设置成功', "index", '', '1');
    }

    /*
    * 设置创业板为禁买
    */
    public function cy_alls()
    {
        $stock_list = Db::name('stock_list');
        $class_res = $stock_list->select();
        if (!empty($class_res)) {
            foreach ($class_res as $key => $v) {
                if ($v['status'] == 0) {
                    continue;
                }
                if (substr($v['code'], 0, 1) == '3') {
//                    $map="id=".$v['id'];
                    $ids[] = $v['id'];
                    $data['status'] = 0;
//                    $stock_list->where($map)->update($data);
                }
            }
            $stock_list->where('id', 'in', $ids)->update($data);
        }
        $this->success('设置成功', "index", '', '1');
    }

    /*
     * 设置所有基金债券为禁买
     */
    public function jijin_alls()
    {
        $stock_list = Db::name('stock_list');
        $class_res = $stock_list->select();
        if (!empty($class_res)) {
            foreach ($class_res as $key => $v) {
                if ($v['status'] == 0) {
                    continue;
                }
                if (strlen($v['pinyin']) > 4) {
//                    $map="id=".$v['id'];
                    $ids[] = $v['id'];
                    $data['status'] = 0;
//                    $stock_list->where($map)->update($data);
                }
            }
            $stock_list->where('id', 'in', $ids)->update($data);
        }
        $this->success('设置成功', "index", '', '1');
    }

    /*
     *设置所有st *st 为可买
     */
    public function riskall_low()
    {
        $stock_list = Db::name('stock_list');
        $class_res = $stock_list->select();
        if (!empty($class_res)) {
            foreach ($class_res as $key => $v) {
                if ($v['status'] == 1) {
                    continue;
                }
                if (substr($v['title'], 0, 1) == 'S' || substr($v['title'], 0, 1) == '*' || substr($v['title'], 0, 1) == 's') {
                    $map = "id=" . $v['id'];
                    $data['status'] = 1;
                    $stock_list->where($map)->update($data);
                }
            }
        }
        $this->success('设置成功', "index", '', '1');
    }

    /*
     * 设置所有股票为可买
     */
    public function all_low()
    {
        $stock_list = Db::name('stock_list');
        $class_res = $stock_list->select();
        if (!empty($class_res)) {
            foreach ($class_res as $key => $v) {
                if ($v['status'] == 1) {
                    continue;
                }
//                    $map="id=".$v['id'];
                $ids[] = $v['id'];
                $data['status'] = 1;
//                    $stock_list->where($map)->update($data);
            }
            $stock_list->where('id', 'in', $ids)->update($data);
        }
        $this->success('设置成功', "index", '', '1');
    }

    //更新上海主板数据
    public function sh_all()
    {
        $req = request();
        $star = input('last');
        $last = $star + 8;
        $model = Db::name('stock_list');
        $arr = array();
        for ($j = $star; $j <= $last; $j++) { //更新上海主板股票
            $t = 600000 + $j;
            $tmd = $model->where("code=$t")->find();
            $dat = sohu_keyword($t);
            if (count($dat[0]) >= 3) {
                $data = $this->each($dat[0]);
                if (empty($tmd)) {
                    $model->data($data)->insert();
                } else {
                    $model->where(['code' => $data['code']])->data($data)->update();
                }
            }
            if (!empty($dat[0])) {
                $arr[$j] = $dat[0];
            };
        }
        $this->view->assign('arr', $arr);
        $this->view->assign('last', $last);
        $this->view->assign('local', http() . $_SERVER["SERVER_NAME"]);
        return $this->view->fetch();
    }

    //更新深圳创业板数据
    public function szcy_all()
    {
        $req = request();
        $star = input('last');
        $last = $star + 8;
        $model = Db::name('stock_list');
        $arr = array();
        for ($j = $star; $j <= $last; $j++) { //更新深圳创业板股票
            $t = 300000 + $j;
            $tmd = $model->where("code=$t")->find();
            $dat = sohu_keyword($t);
            if (count($dat['result'][0]) >= 3) {
                $data = $this->each($dat[0]);
                if (empty($tmd)) {
                    $model->data($data)->insert();
                } else {
                    $model->where(['code' => $data['code']])->data($data)->update();
                }
            }
            if (!empty($dat[0])) {
                $arr[$j] = $dat[0];
            };
        }
        $this->view->assign('arr', $arr);
        $this->view->assign('last', $last);
        $this->view->assign('local', http() . $_SERVER["SERVER_NAME"]);
        return $this->view->fetch();
    }

    //更新科创板块数据
    public function kecuan_all()
    {
        $req = request();
        $star = input('last');
        $last = $star + 8;
        $model = Db::name('stock_list');
        $arr = array();
        for ($j = $star; $j <= $last; $j++) {
            $t = 688000 + $j;
            $tmd = $model->where("code=$t")->find();
            $dat = sohu_keyword($t);
            if (count($dat[0]) >= 3) {
                $data = $this->each($dat[0]);
                if (empty($tmd)) {
                    $model->data($data)->insert();
                } else {
                    $model->where(['code' => $data['code']])->data($data)->update();
                }
            }
            if (!empty($dat[0])) {
                $arr[$j] = $dat[0];
            };
        }
        $this->view->assign('arr', $arr);
        $this->view->assign('last', $last);
        $this->view->assign('local', http() . $_SERVER["SERVER_NAME"]);
        return $this->view->fetch();
    }

    //接收数据
    public function each($arr)
    {
        $data['title'] = $arr['name'];
        $data['code'] = $arr['code'];
        $data['status'] = 1;
        $data['pinyin'] = $arr['pin'];
        $data['add_time'] = time();
        $data['target_uid'] = UID;
        $data['target_name'] = Session::get("user_auth")['username'];
        return $data;
    }
}