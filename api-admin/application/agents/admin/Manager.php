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
namespace app\agents\admin;

use app\admin\controller\Admin;
use app\admin\model\Attachment as AttachmentModel;
use app\agents\model\AgentCode as AgentCodeModel;
use app\common\builder\ZBuilder;
use app\member\model\Member as MemberModel;
use app\money\model\Money;
use app\money\model\Recharge;
use app\statistics\model\DataReport as DataReportModel;
use app\user\model\User as AdminUserModel;
use app\user\model\User as UserModel;
use think\Db;
use think\facade\Hook;
use app\common\service\UserService;

class Manager extends Admin
{
    /*
     * 代理商列表
     */
    public function agent_list()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map = (new UserService())->getAgentMap($map,'id');
        $order = $this->getOrder();
        $data_list = Db::name('member')
          ->where($map)
          ->where('agent_id >= 1')
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $count = Db::name('member')->where(["agent_far" => $item['id']])->count();
              $item['count_num'] = $count ? $count : 0;
              $item['invitation_money'] = agents_back_money($item['id']);
              $item['mobile'] = privacy_info_switch('mobile',$item['mobile']);
              return $item;
          });
        $domain = request()->domain();
        $apk_btn_report = ['title' => '下载安卓', 'icon' => 'fa fa-fw fa-arrow-down', 'href' => $domain];
        $ios_btn_report = ['title' => '下载IOS', 'icon' => 'fa fa-fw fa-arrow-down', 'href' => $domain ];
        // 分页数据
        $page = $data_list->render();
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
          ->setTableName('member')
//          ->setSearch(['name' => '姓名', 'mobile' => '手机号'], '', '', true) // 设置搜索框
            ->setSearchArea([
                ['text', 'name', '姓名', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
                ['select', 'agent_pro', '状态', '', '', [0 => '停止', 1 => '正常']],
                ['daterange', 'create_time', '注册时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
          ->addOrder('create_time,id desc') // 添加排序
          ->hideCheckbox()
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['mobile', '手机号'],
            ['name', '姓名'],
              ['role_name', '白名单', $this->user_role_name],
            ['agent_code', '邀请码'],
            ['agent_url', '代理域名'],
            ['agent_id', '代理等级', 'text', '', [0 => '普通会员', 1 => '一级代理', 2 => '二级代理', 3 => '三级代理']],
            ['agent_rate', '返佣比例', 'text.edit'],
            ['invitation_money', '返佣金额'],
//            ['count_num', '名下会员数', 'link', url('agentBelowUser', ['search_field' => 'invitation_user', 'keyword' => '__mobile__'])],
            ['count_num', '名下会员数', 'link', url('agentBelowUser', ['agent_id' => '__id__'])],
            ['agent_pro', '代理状态', [0 => '停止', 1 => '正常']],
            ['create_time', '注册时间', 'datetime'],
            ['right_button', '操作', 'btn']
          ])

          ->addTopButton('add') // 批量添加顶部按钮
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
          ->addRightButtons(['edit']) // 批量添加右侧按钮
//          ->addRightButton('custom', $apk_btn_report)
//          ->addRightButton('custom', $ios_btn_report)
          ->setRowList($data_list) // 设置表格数据
          ->fetch(); // 渲染模板
    }

    /*
    * 代理统计
    */
    public function agent_statistics()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        $admin_user = AdminUserModel::where('id', UID)->find();
        $search_field = input('search_field');
        if ($search_field == 'role_name') {

            $map[] = ['agent_far', 'eq', '白名单'];
        } else {

            $map[] = ['agent_far', 'neq', '白名单'];
        }
        if ($admin_user['role'] == 2) {

            $map[] = ['agent_far', '=', $admin_user['for_user']];
        }

        $map[] = ['agent_id', '=',1];
        $data_list = Db::name('member')
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $uids = Db::name('member')->where('agent_far', $item['id'])->column('id');//会员数
              $day_data_report = DataReportModel::getAgentDayData($uids);//当天的报表数据
              $item['user_num'] = count($uids) ?: 0;//下属会员数
              $item['day_reg'] = $day_data_report['effective_user'];//今日注册
              $item['day_recharge'] = $day_data_report['day_recharge'];//今日充值
              $item['day_withdraw'] = $day_data_report['day_withdraw'];//今日提现
              $item['effective_contract'] = $day_data_report['effective_contract'];//有效合约
              $item['new_open_contract'] = $day_data_report['new_open_contract']; //新开合约
              $item['invitation_money'] = $day_data_report['borrow_money']; //合约总数
              $item['stock_position_user_num'] = $day_data_report['stock_position_user_num']; //持仓用户数
              $item['profit_loss'] = $day_data_report['profit_loss']; //总盈亏
              $item['day_money_transfer'] = $day_data_report['day_money_transfer']; //今日赠送
              return $item;
          });
//        $btn_report = ['title' => '下属会员数', 'icon' => 'fa fa-fw fa-arrow-down', 'href' => $domain.__ios_download_link__ ];
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
          ->setTableName('member')
          ->setSearch(['name' => '姓名', 'mobile' => '手机号', 'role_name' => '白名单'], '', '', true) // 设置搜索框
          ->addOrder('create_time,id desc') // 添加排序
          ->hideCheckbox()
          ->addColumns([ // 批量添加数据列
            ['mobile', '手机号'],
            ['name', '姓名'],
            ['user_num', '下属会员数'],
            ['day_reg', '今日注册'],
            ['day_recharge', '今日充值'],
            ['day_withdraw', '今日提现'],
            ['day_money_transfer', '今日赠送'],
            ['effective_contract', '有效合约'],
            ['new_open_contract', '新开合约'],
            ['invitation_money', '合约总数(单位：元)'],
            ['stock_position_user_num', '持仓用户数'],
            ['profit_loss', '客户总盈亏'],
          ])
//            ->addTopButton('add') // 批量添加顶部按钮
//            ->addRightButtons(['edit']) // 批量添加右侧按钮
//            ->addRightButton('custom', $btn_report)
          ->setRowList($data_list) // 设置表格数据
          ->fetch(); // 渲染模板
    }

    /*
     * 新增代理商
     */
    public function add()
    {
        // 保存数据
        $admin_user = AdminUserModel::where('id', UID)->find();
        $user_id = $admin_user['for_user'];
        $agent_level = MemberModel::where('id', $user_id)->value('agent_id');//代理级别
        if ($admin_user['role'] != 2) {
            $agent_level = 0;
        }
        $agent_level = 0;
        if ($this->request->isPost()) {
            $data = input();
            $agent_code = AgentCodeModel::where('code', $data['agent_code'])->find();
            $apk_download_link = AttachmentModel::getFilePath($agent_code['apk']);
            $ios_download_link = AttachmentModel::getFilePath($agent_code['ios']);
            $data['ios_download_link'] = $ios_download_link;
            $data['apk_download_link'] = $apk_download_link;
            //设置代理 不设置rate 默认后台配置rate
            if ($data['agent_rate'] == 0) {
                $data['agent_rate'] = config('agent_back_rate');
            }
            if ($data['agent_rate'] > 100) {
                $this->error('返佣比例过高，请重新设置');
            }
            if ($data['agent_rate'] < 0) {
                $this->error('返佣比例过低，请重新设置');
            }
            $codeinfo = AgentCodeModel::where('code', $data['agent_code'])->find();
            if ($codeinfo['status'] !== 0) {
                $this->error('邀请码已被使用');
            }
            $result = $this->validate($data, 'agents.create');
            true !== $result && $this->error($result);
            //判断是否符合设置代理情况 已经是下级代理 则不能设置一级代理
            $user = MemberModel::getMemberInfoByMobile($data['mobile']);
            //几种不能设置一级代理商的情况 不存在 已经是代理商 上级代理是代理商
            if (empty($user)) $this->error('该会员信息未找到');
            if ($user['agent_id']) $this->error('该会员已经是代理商，不能重复添加');
            if (!$user['id_auth']) $this->error('未实名不能进行设置代理商操作');
//			if($user['agent_far']) {
//				$agent_in = MemberModel::getMemberInfoByID($user['agent_far']);
//				if($agent_in['agent_id']){
//					$this->error('该用户不允许添加为一级代理商');
//				}
//			}
            if ($admin_user['role'] == 2) {
//                $data['agent_id'] = $agent_level+1;
                $data['agent_id'] = 1;//先按照需求只可添加一级代理
                $data['pid'] = $user_id;
                $data['agent_far'] = $user_id;
            }
            $data['id'] = $user['id'];
            $data['agent_time'] = 0;//取消返佣期限限制
            if (MemberModel::update($data)) {
                AgentCodeModel::where('code', $data['agent_code'])->update(['status' => 1]);
                //添加代理商的同时也要添加一个后台账号
                $add_data['username'] = $data['mobile'];
                $add_data['mobile'] = $data['mobile'];
                $add_data['password'] = $user['passwd'];
                $add_data['role'] = 2;
                $add_data['for_user'] = $user['id'];
                $add_data['status'] = 1;
                UserModel::insert($add_data);
                $member = MemberModel::get($data['id']);
                Hook::listen('member_edit', $member);
                // 记录行为
                action_log('member_edit', 'member', $member['id'], UID, $data['mobile']);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $agent_rate = config('agent_back_rate');
        $agent_list[0] = "一级代理商";
        $html = "<div class='form-group dowmList' id='dowmList'></div>";
        $code_list = AgentCodeModel::where('status', 0)->column('name', 'code');
        $agent_array = ['一级代理', '二级代理', '三级代理'];
        $agent_name = $agent_array[$agent_level] ?? '';
        return ZBuilder::make('form')
          ->setPageTitle('添加代理商') // 设置页面标题
          ->addFormItems([ // 批量添加表单项
            ['text:5', 'mobile', '用户名/手机号', ''],
            ['select', 'agent_code', '选择邀请码', '请选择邀请码', $code_list],
            ['text', 'agent_url', '代理域名(格式：www.baidu.com)'],
            ['text:5', 'agent_rate', '返佣比例', '', $agent_rate],
            ['radio', 'agent_pro', '代理状态', '', [0 => '禁用', 1 => '启用'], '1'],
            ['hidden', 'agent_id', '1'],
          ])
          ->js('downlist')
          ->css('downlist')
          ->setExtraHtml($html, 'form_top')
          ->addStatic('name', '代理商级别', '', $agent_name)
          ->fetch();
    }

    public function getLikeMobile($mobile)
    {
        $map = " agent_id = 0 and mobile like '%{$mobile}%'";
        $member = Db::name('member')->field('id,mobile')->where($map)->limit(10)->select();
        return $member;
    }

    /*
    * 代理商编辑
    */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data = input();
//            $data['download_link'] = AgentCodeModel::where('code', $data['invite_code'])->value('download_link');
            $data['pid'] = 0;
            // 验证
            //设置代理 不设置rate 默认后台配置rate
            if ($data['agent_rate'] == 0) {
                $data['agent_rate'] = config('agent_back_rate');
            }
            if ($data['agent_rate'] > 100) {
                $this->error('返佣比例过高，请重新设置');
            }
            if ($data['agent_rate'] < 0) {
                $this->error('返佣比例过低，请重新设置');
            }
//            邀请码不给修改
            if (strlen($data['agent_code']) >= 10 || strlen($data['agent_code']) <= 6) {
                $this->error('邀请码必须6-10位');
            }
             $codeifo=MemberModel::where('agent_code',$data['agent_code'])->find();
            if($codeifo){
                if($codeifo['id'] != $data['id']){
                    $this->error('邀请码已被使用');
                }
            }

            $result = $this->validate($data, 'agents.create');
            true !== $result && $this->error($result);
            if (MemberModel::update($data)) {
                AgentCodeModel::where('code', $data['agent_code'])->update(['status' => 1]);
                //   echo AgentCodeModel::getlastsql();
                $member = MemberModel::get($data['id']);
                Hook::listen('member_edit', $member);
                // 记录行为
                action_log('member_edit', 'member', $member['id'], UID, $data['agent_code']);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info = MemberModel::where(['id' => $id])->find();
        $arr = [0 => '普通会员', 1 => '一级代理', 2 => '二级代理', 3 => '三级代理'];
        if (!empty($info)) {
            $info['class'] = $arr[$info['agent_id']];
        }
        $privacy = cookie('__privacy__');
        if($privacy == 'close'){
            $info['mobile'] = privacy_info_switch('mobile',$info['mobile']);
        }
        $code_list = AgentCodeModel::where('status', 0)->column('name', 'code');
        if ($info['agent_id'] == 1) {
            return ZBuilder::make('form')
              ->setPageTitle('编辑代理商') // 设置页面标题
              ->addFormItems([ // 批量添加表单项
                ['static:12', 'mobile', '用户名/手机号', ''],
                ['static:12', 'name', '姓名', ''],
//                ['hidden', 'agent_code'],
                ['text', 'agent_url', '代理域名(格式：www.baidu.com)', '',],
                ['static:12', 'class', '代理等级', '',],
                ['text:12', 'agent_rate', '返佣比例', ''],
                ['number', 'agent_code', '邀请码', ''],
                ['radio:6', 'agent_pro', '代理状态', '', ['禁用', '启用']],
                ['hidden', 'agent_id', '1'],
                ['hidden', 'id', ''],
              ])
              ->setFormData($info) // 设置表单数据
              ->fetch();
        } else {
            return ZBuilder::make('form')
              ->setPageTitle('编辑代理商') // 设置页面标题
              ->addFormItems([ // 批量添加表单项
                ['static:6', 'mobile', '用户名/手机号', ''],
                ['static:6', 'name', '姓名', ''],
                ['static:6', 'class', '代理等级', '',],
                ['text:6', 'agent_rate', '返佣比例', ''],
                ['number', 'invite_code', '邀请码', ''],
                ['radio:6', 'agent_pro', '代理状态', '', ['禁用', '启用']],
                ['hidden', 'agent_id', '1'],
                ['hidden', 'id', ''],
              ])
              ->setFormData($info) // 设置表单数据
              ->fetch();
        }
    }

    /*
     * 盈利分成明细
     */
    public function agent_share()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {

            $map[] = ['r.for_user', '=',$admin_user['for_user']];
        }
        if (empty($map['r.create_time'][1][0])) {
            $beginday = date('Ymd', time() - 2592000);//30天前
        } else {
            $beginday = date('Ymd', strtotime($map['r.create_time'][1][0]));
        }
        if (empty($map['r.create_time'][1][1])) {
            $endday = date('Ymd', time());
        } else {
            $endday = date('Ymd', strtotime($map['r.create_time'][1][1]));
        }
        $info = Db::view('member u', "mobile")
          ->view('agents_back_money r', '*', 'r.mid=u.id', 'right')
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              // $item['money_a'] = $item['money_a']/100;
              return $item;
          });
        $page = $info->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export.html?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        return ZBuilder::make('table')
          ->hideCheckbox()
          ->addColumns([ // 批量添加列
            ['mobile', '代理手机号'],
            ['affect_mobile', '奖励来源'],
            ['money_a', '资金管理费'],
            ['rate', '分成比例'],
            ['affect', '佣金金额'],
            ['info', '信息'],
            ['create_time', '发生时间', 'datetime'],
          ])
//          ->addTimeFilter('r.create_time', [$beginday, $endday]) // 添加时间段筛选
          ->addTopButton('custom', $btn_excel)
          ->setSearch(['mobile' => '代理手机号'], '', '', true) // 设置搜索参数
          ->setRowList($info) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    public function agent_share_export()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {

            $map[] = ['r.for_user', '=',$admin_user['for_user']];
        }
        // 数据列表
        $xlsData = $info = Db::name('member')
          ->alias('u')
          ->join('agents_back_money r', 'r.mid=u.id', 'RIGHT')
          ->field('u.mobile,r.*')
          ->where($map)
          ->order($order)
          ->paginate();
        $test = [];
        foreach ($xlsData as $k => $v) {
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            $test[$k]['create'] = $v['create_time'];
            $test[$k] = $v;
        }
        $title = "佣金分成明细";
        $arrHeader = array('代理手机号', '奖励来源', '资金管理费', '分成比例', '佣金金额', '信息', '发生时间');
        $fields = array('mobile', 'affect_mobile', 'money_a', 'rate', 'affect', 'info', 'create_time');
        export($arrHeader, $fields, $test, $title);
    }

    /*
     * 代理提现记录
     */
    public function application_record()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {

            $map[] = ['r.for_user', '=',$admin_user['for_user']];
        }
//        if (empty($map['r.create_time'][1][0])) {
//            $beginday = date('Ymd', time() - 2592000);//30天前
//        } else {
//            $beginday = date('Ymd', strtotime($map['r.create_time'][1][0]));
//        }
//        if (empty($map['r.create_time'][1][1])) {
//            $endday = date('Ymd', time());
//        } else {
//            $endday = date('Ymd', strtotime($map['r.create_time'][1][1]));
//        }
        $info = Db::view('member u', "id,mobile,agent_id,name,role_name")
          ->view('money_withdraw r', '*', 'r.mid=u.id', 'right')
          ->where($map)
          ->where(['r.status' => 1])
//            ->where('u.agent_id','<>',0)
          ->order($order)
          ->paginate()
        ->each(function ($item, $key) {
                $item['mobile'] = privacy_info_switch('mobile',$item['mobile']);
                return $item;
            });

        $page = $info->render();
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
          ->hideCheckbox()
            ->setSearchArea([
                ['text', 'name', '姓名', 'like'],
                ['text', 'mobile', '手机号', 'like'],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
//                ['daterange', 'r.create_time', '申请时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
          ->addColumns([ // 批量添加列
            ['name', '姓名'],
            ['mobile', '代理手机号'],
              ['role_name', '白名单', $this->user_role_name],
            ['money', '提现金额', "callback", "money_convert"],
            ['fee', '手续费', "callback", "money_convert"],
            ['status', '状态', [0 => '待处理', 1 => '成功', 2 => '失败', 3 => '退回']],
            ['create_time', '申请时间', 'datetime'],
            ['agent_id', '代理等级', 'text', '', [0 => '普通会员', 1 => '一级代理', 2 => '二级代理', 3 => '三级代理']],
          ])
//          ->addTimeFilter('r.create_time', [$beginday, $endday]) // 添加时间段筛选
//          ->setSearch(['mobile' => '代理手机号'], '', '', true) // 设置搜索参数
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
          ->setRowList($info) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    /*
    * 代理下会员的充值记录
    */
    public function application_recharge()
    {
        $map = $this->getMap();
        $map = (new UserService())->getAgentMap($map,'money_recharge.mid');
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $order = $this->getOrder();
        empty($order) && $order = 'money_recharge.id desc';
        // 数据列表
        $data_list = Recharge::getAll($map, $order);
        // 分页数据
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
              5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
              5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
          'title' => '导出EXCEL表',
          'icon' => 'fa fa-fw fa-download',
          'href' => url($excel_url, '', '')
        ];
        $totalsum = 0;
        foreach ($data_list as $k => $v) {
            $totalsum = $totalsum + $v['money'];
        }
        $html = <<<EOF
            <p>当前页面金额为：{$totalsum}元，\t可通过筛选条件快速快速筛选出对应数据。状态0为未处理，1为成功 2为失败</p>
EOF;
        return ZBuilder::make('table')
          ->setSearch(['mid' => '用户ID', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['order_no', '订单号'],
            ['mobile', '手机号'],
            ['name', '姓名'],
            ['money', '金额'],
            ['status', '状态'],
//            ['is_auto', '是否自动到账', ['0' => "否", "1" => "是"]],
            ['currency', '币'],
            ['type', '充值方式', ['alipay' => "支付宝", "wechatpay" => "微信支付", "bank" => "银联支付", "currency" => "数字货币", "transfer" => "线下支付"]],
            ['create_time', '充值时间', 'datetime'],
          ])
          ->setExtraHtml($html, 'table_bottom')
          ->addFilter('money_recharge.status') // 添加筛选
          ->addTimeFilter('money_recharge.create_time', [date("Y-m-d"), date("Y-m-d")], ['开始时间', '结束时间'])
          ->hideCheckbox()
          ->addOrder('id,create_time,status,money,order_no')
          ->setRowList($data_list)
          ->fetch(); // 渲染模板
    }

    /*
     * 邀请用户
     */
    public function application_invite()
    {
        $map = $this->getMap();

        $order = $this->getOrder();
        $order = 'r.id desc';
        $search_field = input('param.search_field/s', '');
        $getMobile = input('param.keyword/s', '');
        $where = [];
        if($search_field && $getMobile){
            if($search_field == 'invitation_user'){
                unset($map['invitation_user']);
                $info = Db::name('member')->field('id,mobile,name,agent_id,create_time,email')->where('mobile', $getMobile)->find();
                $where['agent_far'] = $info['id'];
            }elseif ($search_field == 'mobile'){
//                $where['mobile'] = ['mobile', 'like', "%{$getMobile}%"];
                $where['mobile'] = $getMobile;
            }
        }
        if (count($map) > 0){
            foreach ($map as $key => $value) {
                if ($value[0] == 'invitation_user'){
                    $where_new[] = ['mobile', 'like', "%{$value[2]}%"];
                    $info_new = Db::name('member')->field('id,mobile,name,agent_id,create_time,email')
                        ->where($where_new)
                        ->find();
                    if ($info_new){
//                        $map[] = ['agent_far', '=',$info_new['id']];
                        $where['agent_far'] = $info_new['id'];
                    }
                    unset($map[$key]);

                }
            }
        }
//        if ($getMobile && $search_field == 'invitation_user') {
//            $agent_far = Db::name('member')->where('mobile', $getMobile)->value('id');
//
//            $map[] = ['agent_far', '=',$agent_far];
//        } else {
//            if ($getMobile) {
//
////                $map[] = ['mobile', '=',$getMobile];
//                $map[] =  ['mobile', 'like', "%{$getMobile}%"];;
//            }
//        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2 && empty($getMobile)) {

//            $map[] = ['mid', '=',$admin_user['for_user']];
            $where['agent_far'] = $admin_user['for_user'];
        }
        $list = MemberModel::
        where($map)
            ->where($where)
            ->field('*')->paginate();
        foreach ($list as $key => $value) {
            $list[$key]['invitation_user'] = Db::name('member')->where('id', $value['agent_far'])->value('mobile');
            $user_balance = Money::getMoney($value['id']);
            $balance = $user_balance['account'] / 100;//转为元
            $list[$key]['balance2'] = $balance;
            $list[$key]['invitation_money'] = get_back_money($value['id']);
            $list[$key]['invitation_user'] = privacy_info_switch('mobile',$list[$key]['invitation_user']);
            $list[$key]['mobile'] = privacy_info_switch('mobile',$list[$key]['mobile']);
        }
        $page = $list->render();
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
          ->hideCheckbox()
            ->setSearchArea([
                ['text', 'name', '姓名', 'like'],
                ['text', 'mobile', '被邀请人', 'like'],
                ['text', 'invitation_user', '邀请人', 'like'],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
                ['daterange', 'create_time', '邀请时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
          ->addColumns([ // 批量添加列
            ['invitation_user', '邀请人'],
            ['mobile', '被邀请人'],
            ['name', '姓名'],
              ['role_name', '白名单', $this->user_role_name],
            ['email', '邮箱'],
            ['balance2', '下线账户余额'],
            ['invitation_money', '下线返佣金额'],
            ['create_time', '邀请时间', 'datetime'],
            ['agent_id', '代理等级', 'text', '', ['普通会员', '一级代理', '二级代理', '三级代理', '四级代理', '五级代理']],
          ])
//          ->setSearch(['mobile' => '被邀请人','invitation_user' => '邀请人'], '', '', true) // 设置搜索参数
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
          ->setRowList($list) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

    /*
     * 代理下面的用户
     */
    public function agentBelowUser()
    {
        $map = $this->getMap();
        $order = 'id desc';
        $agent_id = input('param.agent_id/s', '');
        $where = [];
//        邀请人
        $where['agent_far'] = $agent_id;

        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $where['agent_far'] = $admin_user['for_user'];
        }
        $info = Db::name('member')
          ->where($map)
          ->where($where)
          ->order($order)
          ->field('id,mobile,name,agent_id,create_time,email,agent_far,role_name')
          ->paginate()
          ->each(function ($item, $key) {
              $user_balance = Money::getMoney($item['id']);
              $balance = $user_balance['account'] / 100;//转为元
              $item['balance'] = $balance;
              $item['invitation_mid'] = $item['mobile'];
              $item['invitation_user'] = Db::name('member')->where('id', $item['agent_far'])->value('mobile');
              $item['invitation_money'] = get_back_money($item['id']);
              $item['invitation_user'] = privacy_info_switch('mobile',$item['invitation_user']);
              $item['mobile'] = privacy_info_switch('mobile',$item['mobile']);
              return $item;
          });

        $page = $info->render();
        $btn_privacy = [
            'title' => '查看隐私信息',
            'icon'  => 'fa fa-fw fa-refresh',
            'class'  => 'btn btn-info',
            'href'  => url('member/index/privacy'),
        ];
        return ZBuilder::make('table')
          ->hideCheckbox()
          ->setPageTitle('代理名下用户列表') // 设置页面标题
            ->setSearchArea([
                ['text', 'name', '下线名字', 'like'],
                ['text', 'mobile', '下线手机号', 'like'],
                ['select', 'role_name', '白名单', '', '', $this->user_role_name],
                ['daterange', 'create_time', '邀请时间', '', '', ['format' => 'YYYY-MM-DD HH:mm']],
            ])
            ->addColumns([ // 批量添加列
            ['invitation_user', '代理商手机号'],
            ['mobile', '下线手机号'],
            ['name', '姓名'],
                ['role_name', '白名单', $this->user_role_name],
            ['email', '邮箱'],
            ['balance', '下线账户余额'],
            ['invitation_money', '下线返佣金额'],
            ['create_time', '邀请时间', 'datetime'],
            ['agent_id', '代理等级', 'text', '', ['普通会员', '一级代理', '二级代理', '三级代理', '四级代理', '五级代理']],
          ])
            ->addTopButton('custem', $btn_privacy,['area' => ['500px', '40%']])
          ->setRowList($info) // 设置表格数据
          ->setPages($page) // 设置分页数据
          ->fetch();
    }

//    代理名下会员-旧的接口
    public function agentBelowUserOld()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        $order = 'id desc';
        $search_field = input('param.search_field/s', '');
        $getMobile = input('param.keyword/s', '');
        $where = [];
//        邀请人
        if($search_field && $getMobile){
            if($search_field == 'invitation_user'){
                $info = Db::name('member')->field('id,mobile,name,agent_id,create_time,email')->where('mobile', $getMobile)->find();
                $where['agent_far'] = $info['id'];
            }elseif ($search_field == 'mobile'){
                $where['mobile'] = $getMobile;
            }
        }

//        if ($getMobile) {
//            $info = Db::name('member')->field('id,mobile,name,agent_id,create_time,email')->where('mobile', $getMobile)->find();
//            $where['agent_far'] = $info['id'];
//        }
        $admin_user = AdminUserModel::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $where['agent_far'] = $admin_user['for_user'];
        }

        $info = Db::name('member')
            ->where($where)
//          ->where($map)
            ->order($order)
            ->field('id,mobile,name,agent_id,create_time,email,agent_far')
            ->paginate()
            ->each(function ($item, $key) {
                $user_balance = Money::getMoney($item['id']);
                $balance = $user_balance['account'] / 100;//转为元
                $item['balance'] = $balance;
                $item['invitation_mid'] = $item['mobile'];
                $item['invitation_user'] = Db::name('member')->where('id', $item['agent_far'])->value('mobile');
                $item['invitation_money'] = get_back_money($item['id']);
                return $item;
            });
//        echo Db::name('member')->getLastSql();die;
        $page = $info->render();
        return ZBuilder::make('table')
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['invitation_user', '代理商'],
                ['mobile', '被邀请人'],
                ['name', '姓名'],
                ['email', '邮箱'],
                ['balance', '下线账户余额'],
                ['invitation_money', '下线返佣金额'],
                ['create_time', '邀请时间', 'datetime'],
                ['agent_id', '代理等级', 'text', '', ['普通会员', '一级代理', '二级代理', '三级代理', '四级代理', '五级代理']],
            ])
            ->setSearch(['invitation_user' => '代理商', 'mobile' => '被邀请人'], '', '', true) // 设置搜索参数
            ->setRowList($info) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }

    /*
     * 产生id混淆数
     */
    protected function rande($mid)
    {
        return mt_rand(10, 99) . $mid . mt_rand(100, 999);
    }

    //返回网址前缀
    protected function http()
    {
        return empty($_SERVER['HTTP_X_CLIENT_PROTO']) ? 'http://' : $_SERVER['HTTP_X_CLIENT_PROTO'] . '://';
    }
}