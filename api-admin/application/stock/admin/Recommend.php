<?php

namespace app\stock\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use think\Db;

class Recommend extends Admin
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
        $btn_recommend = [
          'title' => '推荐',
          'icon' => 'fa fa-fw fa-level-up',
          'href' => url('to_recommend', ['id' => '__id__'])
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
          ->setPageTitle('推荐股票') // 设置页面标题
          ->setTableName('stock_list') // 设置数据表名
          ->addOrder('title,code,pinyin') // 添加排序
          ->addFilter('code') // 添加筛选
          ->setSearch(['code' => '代码', 'title' => '股票名', 'pinyin' => '简拼']) // 设置搜索参数
          ->addRightButton('custom', $btn_recommend) // 推荐
          ->addColumns([ // 批量添加列
            ['title', '股票名'],
            ['code', '股票代码'],
            ['pinyin', '股票缩写'],
            ['add_time', '添加时间', 'datetime'],
            ['quota', '购买总限额(元)'],
            ['status', '状态', 'status', '', ['禁用:danger', '启用:success']],
            ['right_button', '操作', 'btn'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function to_recommend($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 获取数据
        $info = Db::name('stock_list')->where('id', $id)->field('*', false)->find();
        if ($this->request->isPost()) {
            $data = input();
            if (empty($data['recommend_time'])) {
                $this->error('推荐失败,请选择推荐日期');
            }
            $map['date_str'] = $data['recommend_time'];
            $map['gupiao_code'] = $data['code'];
            $f = Db::name('stock_recommend')->where($map)->find();
            if (!empty($f)) {
                $this->error('当日已推荐该股票');
            }
            $add['recommend_date'] = strtotime($data['recommend_time']);
            $add['date_str'] = $data['recommend_time'];
            $add['gupiao_code'] = $data['code'];
            $add['gupiao_name'] = $data['title'];
            $add['s_id'] = $data['id'];
            $add['c_date'] = time();
            $r = Db::name('stock_recommend')->insert($add);
            if ($r) {
                $this->success('推荐成功', "recommend_list");
            } else {
                $this->error('推荐失败');
            }
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
          ->setPageTitle('今日推荐') // 设置页面标题
          ->addFormItems([ // 批量添加表单项
            ['hidden', 'id'],
            ['hidden', 'title'],
            ['hidden', 'code'],
            ['static:6', 'title', '股票名', '股票名'],
            ['static:6', 'code', '代码', '股票代码'],
            ['static:6', 'pinyin', '拼音', '股票名简拼'],
            ['date', 'recommend_time', '推荐日期'],
              //['text:3', 'quota', '限额','平台单支股票购买限额，0为不限制，其余数字为限制额'],
              //['radio', 'status', '状态', '', ['禁用', '启用']]
          ])
          ->setFormData($info) // 设置表单数据
          ->fetch();
    }

    public function recommend_list()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'd.recommend_date desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1) ? $list_rows1 : 10;
        if (empty($map['recommend_date'][1][0])) {
            $beginday = date('Ymd', time() - 3600 * 24);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['recommend_date'][1][0]));
        }
        if (empty($map['recommend_date'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['recommend_date'][1][1]));
        }
        $data_list = Db::view('stock_recommend d')
          //->view('stock_subaccount s','sub_account','s.id=d.sub_id','left')
          //->view('member m','mobile','m.id=s.uid','left')
          //->where(['d.status'=>1,'d.business_name'=>'证券卖出'])
          ->where($map)
          ->order($order)
          ->paginate($listRows);
        $list = array();
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
          ->setPageTitle('今日推荐') // 设置页面标题
          ->setTableName('stock_recommend') // 设置数据表名
          ->addTimeFilter('recommend_date', [$beginday, $endday]) // 添加时间段筛选
          ->addOrder('sub_id,gupiao_name') // 添加排序
          ->addFilter('gupiao_code') // 添加筛选
          //->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
          ->addRightButton('delete') // 添加编辑按钮
          ->addColumns([ // 批量添加列
            ['gupiao_code', '证券代码'],
            ['gupiao_name', '证券名称'],
            ['recommend_date', '推荐日期', 'date'],
            ['right_button', '操作', 'btn'],
          ])
          ->hideCheckbox()
          ->setRowList($data_list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function delete($record = [])
    {
        return parent::delete($record); // TODO: Change the autogenerated stub
    }
}