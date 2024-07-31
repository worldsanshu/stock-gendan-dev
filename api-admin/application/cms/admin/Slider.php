<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace app\cms\admin;

use app\admin\controller\Admin;
use app\cms\model\Slider as SliderModel;
use app\cms\model\Language as LanguageModel;
use app\common\builder\ZBuilder;

/**
 * 滚动图片控制器
 * @package app\cms\admin
 */
class Slider extends Admin
{
    /**
     * 滚动图片列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        // 数据列表
        $data_list = SliderModel::where($map)->order($order)->order('id desc')->paginate();
        $css = <<<EOF
           <style>
                .ss3 span{    color: #000 !important;}
           </style>
EOF;
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['title' => '标题']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                           ['id', 'ID'],
                           ['cover', '图片', 'picture'],
                           ['lang', '语言'],
                           ['ifwap', '类型', 'status', '', [1=>'首页首屏',  3=>'首页二屏',  4=>'首页中屏'], 'ss3', 'sd'],
                           //   ['ifwap', '位置', 'status', '', ['PC:0', 'app:1', 'wap:2', 'app:3'], 'ss3', 'sd'],
                           // ['title', '标题', 'text.edit'],
                           // ['url', '链接', 'text.edit'],
                           ['title', '标题'],
                           ['url', '链接'],
                           ['create_time', '创建时间', 'datetime'],
                           ['sort', '排序', 'text.edit'],
                           ['status', '状态', 'switch'],
                           ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,title,create_time')
            ->setRowList($data_list) // 设置表格数据
            ->addValidate('Slider', 'title,url')
            ->setExtraCss($css)
            ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     * @throws \think\Exception
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            $data['ifapp'] = $data['ifwap'];
            // 验证
            $result = $this->validate($data, 'Slider');
            if (true !== $result) $this->error($result);
            if($data['ifwap'] == 4){
                $set = SliderModel::where('ifwap', 4)->count();
                if($set > 0){
                    $this->error('首页中屏只能设置一个');
                }
            }
            if ($slider = SliderModel::create($data)) {
                // 记录行为
                action_log('slider_add', 'cms_slider', $slider['id'], UID, $data['title']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        $list_province = [
//            '0' => 'PC幻灯片',
//            '2' => 'WAP幻灯片',
            '1' => 'APP幻灯片-首页首屏',
            '3' => 'APP幻灯片-首页二屏',
            '4' => 'APP幻灯片-首页中屏',
        ];
        $trigger = [
            ['ifwap', '0', ''],
            ['ifwap', '1', 'jumpid'],
            ['ifwap', '2', 'ifshowApp'],
            ['ifwap', '3', 'jumpid'],
        ];
        $url_type = [
                '0' => '不跳转',
                '1' => '内部路径',
                '2' => '外部链接',

            ];
        $language_list = languageModel::column('name', 'english');
        return ZBuilder::make('form')
            ->addSelect('ifwap', '广告位置', '', $list_province)
            ->addSelect('lang', '选择语言', '', $language_list)
            ->addText('title', '标题')
            ->addImage('cover', '图片')
            ->addSelect('url_type', '选择跳转方式', '', $url_type, 1)
//            ->addSelect('jumpid', '选择跳转方式', '', [
//                '1' => '免费体验',
//                #    '2' => '投顾操盘',
//                '3' => '股市行情',
//                '4' => '推广赚钱',
//                #  '5'=>'系统单页',
//                '6' => '不做跳转',
//                '7' => 'WAP链接',
//                '8' => '新闻/公告详情',
//                '9' => '活动中心'
//            ], 6)
            ->addNumber('colomid', '目标id')
            ->addText('url', '跳转链接（如果需要跳到对应文章页面，格式为：/pages/article-detail/article-detail?id=63）,63为对应的文章 ID。')
//            ->addRadio('url_level', '路由等级', '', ['一级路由', '二级路由'], 0)
            ->addRadio('url_level', '路由等级', '', ['1' => '一级路由', '2' => '二级路由'], '1')
            ->addText('sort', '排序', '', 100)
            ->addRadio('status', '是否启用', '', ['否', '是'], 1)
//            ->setTrigger($trigger)
            ->setTrigger('url_type', '1,2', 'url_level,url',false)
            ->setTrigger('jumpid', '5', 'colomid')
            ->fetch();
    }

    /**
     * 编辑
     * @param null $id 滚动图片id
     * @return mixed
     * @throws \think\Exception
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
            $result = $this->validate($data, 'Slider');
            if (true !== $result) $this->error($result);
            if($data['ifwap'] == 4){
                $set = SliderModel::where('ifwap', 4)->find();
                if($set['id'] != $id){
                    $this->error('首页中屏只能设置一个');
                }
            }
            if($data['url_type'] == 0){
                $data['url'] = '';
            }
            if (SliderModel::update($data)) {
                // 记录行为
                action_log('slider_add', 'cms_slider', $id, UID, $data['title']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        $language_list = languageModel::column('name', 'english');
        $info = SliderModel::get($id);
        $list_province = [
            //   '0' => 'PC幻灯片',
            //   '2' => 'WAP幻灯片' , 
            '1' => 'APP幻灯片-首页首屏',
            '3' => 'APP幻灯片-首页二屏',
            '4' => 'APP幻灯片-首页中屏',
        ];
        $url_type = [
            '0' => '不跳转',
            '1' => '内部路径',
            '2' => '外部链接',

        ];
//        if (!$info['ifwap']) {
//            // 显示编辑页面
//            return ZBuilder::make('form')
//                ->addFormItems([
//                    ['hidden', 'id'],
//                    ['select', 'ifwap', '幻灯片位置', '', $list_province],
//                    ['text', 'title', '标题'],
//                    ['select', 'lang', '选择语言', '请选择语言', $language_list],
//                    ['image', 'cover', '图片'],
//                    ['text', 'url', '链接'],
//                    ['text', 'sort', '排序'],
//                ])
//                ->addSelect('url_type', '选择跳转方式', '', $url_type, 1)
//                ->addRadio('status', '立即启用', '', ['否', '是'], 0)
//                ->setFormData($info)
//                ->fetch();
//        } elseif ($info['ifwap'] && $info['ifapp']) {
            return ZBuilder::make('form')
                ->addFormItems([
                    ['hidden', 'id'],
                    ['select', 'ifwap', '广告位置', '', $list_province],
                    ['hidden', 'ifapp'],
                    ['text', 'title', '标题'],
                    ['select', 'lang', '选择语言', '请选择语言', $language_list],
                    ['image', 'cover', '图片'],
                    ['text', 'sort', '排序'],
//                    ['select', 'jumpid', '选择跳转方式', '', [
//                        '1' => '免费体验',
                        #    '2' => '投顾操盘',
//                        '3' => '股市行情',
//                        '4' => '推广赚钱',
                        #  '5'=>'系统单页',
//                        '6' => '不做跳转',
//                        '7' => 'WAP链接',
//                        '8' => '新闻/公告详情',
//                        '9' => '活动中心',
//                    ], 6],
//                    ['text', 'url', '链接'],
                    ['number', 'colomid', '目标id']
                ])
                ->addSelect('url_type', '选择跳转方式', '', $url_type, 1)
                ->addText('url', '跳转链接（如果需要跳到对应文章页面，格式为：/pages/article-detail/article-detail?id=63）,63为对应的文章 ID。')
                ->addRadio('url_level', '路由等级', '', ['1' => '一级路由', '2' => '二级路由'], '1')
                ->addRadio('status', '立即启用', '', ['否', '是'], 0)
                ->setTrigger('url_type', '1,2', 'url_level,url',false)
                ->setTrigger('jumpid', '5', 'colomid')
                ->setTrigger('jumpid', '7', 'url')
                ->setFormData($info)
                ->fetch();
//        } else {
//            return ZBuilder::make('form')
//                ->addFormItems([
//                    ['hidden', 'id'],
//                    ['hidden', 'ifwap'],
//                    ['hidden', 'ifapp'],
//                    ['text', 'title', '标题'],
//                    ['select', 'lang', '选择语言', '请选择语言', $language_list],
//                    ['image', 'cover', '图片'],
//                    ['text', 'url', '链接'],
//                    ['text', 'sort', '排序'],
//                ])
//                ->addSelect('url_type', '选择跳转方式', '', $url_type, 1)
//                ->addRadio('ifapp', '应用于APP', '', ['否', '是'], 0)
//                ->addRadio('status', '立即启用', '', ['否', '是'], 0)
//                ->setFormData($info)
//                ->fetch();
//        }
    }

    /**
     * 删除单页
     * @param array $record 行为日志
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用单页
     * @param array $record 行为日志
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用单页
     * @param array $record 行为日志
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置单页状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function setStatus($type = '', $record = [])
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $slider_title = SliderModel::where('id', 'in', $ids)->column('title');
        return parent::setStatus($type, ['slider_' . $type, 'cms_slider', 0, UID, implode('、', $slider_title)]);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function quickEdit($record = [])
    {
        $id = input('post.pk', '');
        $field = input('post.name', '');
        $value = input('post.value', '');
        $slider = SliderModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $slider . ')，新值：(' . $value . ')';
        return parent::quickEdit(['slider_edit', 'cms_slider', $id, UID, $details]);
    }
}
