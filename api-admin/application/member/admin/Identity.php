<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------
namespace app\member\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\member\model\Bank as BankModel;
use app\member\model\Member as MemberModel;
use app\user\model\User as AdminUserModel;
use think\facade\Hook;
use app\common\model\Area;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Identity extends Admin
{
    /**
     *
     * @return mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        if (empty($map)) {
            $map['is_sub'] = 1;
        }
        $order = $this->getOrder();
        empty($order) && $order = 'auth_time desc';
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $map['agent_far'] = $admin_user['for_user'];
        }
        if (empty($map['auth_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['auth_time'][1][0]));
        }
        if (empty($map['auth_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['auth_time'][1][1]));
        }
        // 数据列表
        $data_list = MemberModel::where($map)->order($order)->paginate();
        foreach ($data_list as &$value){
            $value['mobile'] = privacy_info_switch('mobile',$value['mobile']);
            $value['id_card'] = privacy_info_switch('id_card',$value['id_card']);
        }

        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        $btn_report = ['title' => '查看银行卡', 'icon' => 'fa fa-fw fa-cc-visa', 'href' => url('getBankList', ['uid' => '__id__']), 'target' => '_blank'];
        return ZBuilder::make('table')
//          ->setSearch(['name' => '姓名', 'id_card' => '身份证']) // 设置搜索框
            ->setSearchArea([
                ['text', 'name', '姓名', 'like'],
                ['text', 'id_card', '身份证', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['daterange', 'auth_time', '申请时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']]
            ])
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['mobile', '手机号/邮箱'],

                ['name', '姓名'],
                ['id_card', '证件号码'],
                ['bank_card', '银行卡号'],
                ['id_auth', '账户状态', [0 => '处理中', 1 => '通过', 2 => '未通过']],
                ['auth_type', '认证类型', [0 => '身份证', 1 => '护照']],
                ['auth_time', '实名申请时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->hideCheckbox()
            ->setTableName('member')
            ->setPageTips('系统已开启自动认证实名，当前列表出现处理中的账户是自动认证未通过的', 'warning')
            ->addRightButtons(['edit' => ['title' => '审核']]) // 批量添加右侧按钮
            ->addOrder('id,id_auth,reg_time')
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
//          ->addTimeFilter('auth_time', [$beginday, $endday]) // 添加时间段筛选
            ->replaceRightButton(['id_auth' => ['in', '2,1']], '<button class="btn btn-danger btn-xs" type="button" disabled>已审核</button>')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    public function index_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        if (empty($map)) {
            $map[]=['id_auth','=', 0];
            $map[]=['id_card','<>', ''];
            $map[]=['name','<>', ''];
        }
        $order = $this->getOrder();
        empty($order) && $order = 'auth_time desc';

        $xlsData = MemberModel::where($map)->order($order)->paginate();
        $id_auth_arr = ['0' => '处理中', '1' => '通过', '2' => '错误'];
        foreach ($xlsData as $k => $v) {
            $v['id_auth'] = $id_auth_arr[$v['id_auth']];
            $v['auth_time'] = date('Y-m-d H:i', $v['auth_time']);
        }
        $title = "实名认证";
        $arrHeader = array('ID', '手机号', '姓名', '身份证号', '账户状态', '实名申请时间');
        $fields = array('id', 'mobile', 'name', 'id_card', 'id_auth', 'auth_time');
        export($arrHeader, $fields, $xlsData, $title);
    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
//            // 验证
//            $result = $this->validate($data, 'Member.update_card');
//             //验证失败 输出错误信息
//            if(true !== $result) $this->error($result);
            if ($data["id_auth"] == 2) {
                $data["name"] = null;
                $data["id_card"] = null;
                $data["card_pic"] = null;
                $data["card_pic_back"] = null;
                $data["card_pic_hand"] = null;
                $data["passport_pic"] = null;
            }
            if (MemberModel::update($data)) {
                $member = MemberModel::get($data['id']);
                Hook::listen('member_id_auth', $member);
                // 记录行为
                action_log('member_id_auth', 'member', $member['id'], UID, $member['mobile']);
                //身份认证通过-短信通知
                if ($data["id_auth"] == 1) {
                    //发送短信验证码
                    /*                  $content = \think\Config::get('sms_template')['stock_realname_pass'];
                                        $content = str_replace(array("#var#"),array($mobile), $content);
                                        sendsms_mandao($mobile,$content,'user');*/
                    $contentarr = getconfigSms_status(['name' => 'stock_realname_pass']);
                    $content = str_replace(array("#var#"), array($data['mobile']), $contentarr['value']);
                    if ($contentarr['status'] == 1) {
                        sendsms_mandao($data['mobile'], $content, 'user');
                    }
                } else {
                    $contentarr = getconfigSms_status(['name' => 'stock_realname_fail']);
                    $content = str_replace(array("#var#"), array($data['mobile']), $contentarr['value']);
                    if ($contentarr['status'] == 1) {
                        sendsms_mandao($data['mobile'], $content, 'user');
                    }
                }
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info = MemberModel::where('id', $id)->field('*')->find();
        $array = ["身份证", "护照"];
        $info->auth_type_text = $array[$info->auth_type] ?? '身份证';

//        if($info['card_pic']){
//        $info['card_pic_hidden'] = '/uploads/images/'.$info['card_pic'];
//        }else{
//            $info['card_pic_hidden']='';
//        }
        $domain = request()->domain();
        $info['card_pic_hidden'] = $info['card_pic_hand'] ? get_img_url($info['card_pic_hand']) : '';
        $info['card_pic_back_hidden'] = $info['card_pic_back'] ? get_img_url($info['card_pic_back']) : '';
        $info['passport_pic_text'] = $info['passport_pic'] ? get_img_url($info['passport_pic']) : '';
        $staticContent = ''; // 初始化$staticContent为空字符串
        $staticLabel = '';
        if ($info->auth_type == 0) {
            // 如果认证类型是0，显示身份证
            $staticContent .= '<img src="'.$info['card_pic_hidden'].'" alt="身份证1" style="max-width: 100px; height: auto; margin-right: 10px;">'; // 假设你有card_pic_hidden_1和card_pic_hidden_2两个字段
            $staticContent .= '<img src="'.$info['card_pic_back_hidden'].'" alt="身份证2" style="max-width: 100px; height: auto;">';
            $staticLabel = '身份证照'; // 这里你可以设置为两张照片的共同标签，或者为每个图片设置单独的标签
        } elseif ($info->auth_type == 1) {
            // 如果认证类型是1，显示护照
            $staticContent = '<img src="'.$info['passport_pic_text'].'" alt="护照" style="max-width: 100px; height: auto;">';
            $staticLabel = '护照';
        }else{
            $staticContent = '';
        }
//        $html = <<<EOF
//            <input type="hidden" name="card_pic_hidden" value="{$info['card_pic_hidden']}"/>
//            <input type="hidden" name="card_pic_back_hidden" value="{$info['card_pic_back_hidden']}"/>
//             <input type="hidden" name="passport_pic_text" value="{$info['passport_pic_text']}"/>
//EOF;

        //        开启才能看隐私信息
        $privacy = cookie('__privacy__');
        if($privacy == 'close'){
            $info['id_card'] = privacy_info_switch('id_card',$info['id_card']);
            $info['mobile'] = privacy_info_switch('mobile',$info['mobile']);
        }
        return ZBuilder::make('form')
            ->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['hidden', 'mobile', '用户名/手机号'],
                ['static', 'email', '邮箱'],
                ['static', 'id_card', '身份证号'],
                ['static', 'auth_type_text', '认证类型'],
                ['hidden', 'auth_type', '认证类型'],
                ['static', 'name', '姓名'],
                ['static', 'bank_card', '银行卡号'],
                ['static', 'auth_document', $staticLabel, $staticContent], // 根据 auth_type 显示身份证或护照

//                ['image','card_pic_hand_text','手持身份证照'],
//                ['image','card_pic_back_text','身份证背面照'],
//                ['image','passport_pic_text','护照'],
                ['radio', 'id_auth', '账户状态', '', ['2' => '拒绝', '1' => '通过']]
            ])
//          ->setExtraHtml($html)
            ->setFormData($info) // 设置表单数据
//
//          ->js('idcard')
            ->fetch();
    }

    public function getBankList()
    {
        $map = [];
        $order = 'id desc';
        $list = BankModel::where($map)->order($order)->paginate();
        foreach ($list as $k => $v) {
            $province_name = (new Area())->where('id', $v['province'])->value('name');
            $city_name = (new Area())->where('id', $v['city'])->value('name');
            $bank_address = $province_name . $city_name . $v['branch'];
            $v['bank_address'] = $bank_address;
            $v['add_time'] = date('Y-m-d H:i:s', $v['create_time']);
            $user_data = MemberModel::where('id', $v['mid'])->field('*')->find();
            $v['uid'] = $v['mid'];
            $v['user_mobile'] = $user_data['mobile'];
            $v['email'] = $user_data['email'];
            $v['name'] = $user_data['name'];
            $v['id_card'] = $user_data['id_card'];
            $v['auth_time'] = date('Y-m-d H:i:s', $user_data['auth_time']);
        }
        $bank = config('web_bank');
        foreach ($list as $key => $value) {
            foreach ($bank as $k => $v) {
                if ($k == $value['bank']) {
                    $value['bank_name'] = $v;
                }
            }
            $list[$key] = $value;
        }
        return ZBuilder::make('table')
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['uid', '用户ID'],
                ['user_mobile', '手机号码'],
                ['email', '邮箱'],
                ['name', '姓名'],
                ['id_card', '身份证号码'],
                ['auth_time', '实名申请时间'],
                ['card', '银行卡号'],
                ['bank_name', '银行'],
                ['bank_address', '开户行'],
                ['add_time', '绑定时间'],
            ])
            ->hideCheckbox()
            ->addRightButtons('edit', [], true) // 批量添加右侧按钮
            ->addOrder('id')
            ->setRowList($list) // 设置表格数据
            ->fetch(); // 渲染模板
    }
}