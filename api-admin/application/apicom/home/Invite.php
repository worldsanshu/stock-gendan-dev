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

use app\member\model\Member as MemberModel;
use think\db;

class Invite extends Common
{
    public function __construct()
    {
        $token = input("token");
        defined('MID') or define('MID', isLogin($token));
        $mid = MID;
        if (!$mid) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
    }

    /**
     * 邀请人记录
     * @return mixed
     */
    public function index()
    {
        $mid = MID;
        if (!$mid) {
            // $this->error('请登陆后进行申请', URL('/login'));
        }
        $order = intval(input("order"));
        if ($order) {
            $map = "r.create_time desc";
        } else {
            $map = "r.create_time asc";
        }
        $page = intval(input("page"));
        $page = $page ? $page : 1;
        $offset = $page;
        $data = Db::name('member_invitation_relation as r')
          ->join('member', 'member.id=r.invitation_mid', 'LEFT')
          ->field('r.id,r.mid,r.create_time,r.invitation_mid,member.name,member.mobile,member.agent_id,member.agent_rate,member.agent_far,member.agent_pro,member.id,member.agent_rate')
          ->where(['r.mid' => $mid])
          ->order($map)
          ->page($offset, 10)
          ->select();
//        ->paginate();

        foreach ($data as $key => $v) {
            $data[$key]['invitation_money'] = get_back_money($v['invitation_mid']);
            $data[$key]['agents_profit_money'] = agents_profit_money($v['invitation_mid']);
            $data[$key]['profit_member'] = get_users_m($v['invitation_mid']);
            $data[$key]['create_time'] = getTimeFormt($v['create_time'], 5);
            $data[$key]['create_time_m'] = getTimeFormt($v['create_time'], 6);
            $data[$key]['back_end'] = getEndBack($v['create_time']);
            $data[$key]['mange_rate'] = get_plus_rate($v['id']);
            $data[$key]['agent_des'] = $v['agent_id'] ? '代理商' : '普通用户';


        }

        ajaxmsg('邀请人奖励记录', 1, $data, true, ['msgCode' => 'L0123']);
    }

    /*
      * 需求参数
    */
    public function conf()
    {
        $mid = MID;
        $user = get_agents_info($mid);
        $agent_pro = $user['agent_pro'];
        $agent_rate = agents_back_rate($mid);
        $count_m = Db::name('member_invitation_relation')->where(['mid' => $mid])->count();//邀请用户人数
        $count = Db::name('agents_back_money')->where(['mid' => $mid])->sum('affect');//已赚取佣金
        $rande = randerode($mid);
       # $shareurl =   $this->http() . config('share_domain') . "/index/share/index?url=" . $rande;//邀请链接

        $shareurl = $this->http() .config('share_domain') . "/index/index/inviteuser?recom_id=" . $rande;



        $qrcode =  $this->http() . config('share_domain') . "/index/share/index?url=" . $shareurl;//二维码


        $data['qrcode'] = $qrcode;
        $data['rande'] = $rande;
        $data['url'] = $shareurl;
        $data['count_m'] = $count_m ? $count_m : 0;
        $data['count'] = $count ? round($count, 2) : 0;
        $data['agent_id'] = $user['agent_id'];
        $data['agent_rate'] = $agent_rate;
        $data['agent_pro'] = $agent_pro;
        $share_log = config('share_logo') ? config('share_logo') : config('web_site_front_end_logo');
        $data['share_logo'] = 'http://' . $_SERVER['HTTP_HOST'] . get_files_path($share_log);
        $data['share_title'] = config('share_title');
        $data['share_content'] = config('share_content');
        ajaxmsg('邀请需求参数', 1, $data, true, ['msgCode' => 'L0124']);
    }

    public function view()
    {
        $mid = MID;
        $rande = randerode($mid);
        $url = $this->http() . $_SERVER["SERVER_NAME"] . "/wap/invite/" . $rande;
        plugin_action('Qrcode/Qrcode/generate', [$url, APP_PATH . 'test.png']);
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

    /*
    *查看邀请用户
    */
    public function lookup()
    {
        $mid = MID;
        $look_uid = intval(input("look_uid"));
        $page = intval(input("page"));
        $page = $page ? $page : 1;
        $offset = $page;
        $data = $res = Db::name('member_invitation_relation as r')
          ->join('member', 'member.id=r.invitation_mid', 'LEFT')
          ->field('r.id,r.mid,r.create_time,member.name,member.mobile,member.agent_id,member.agent_far,member.agent_pro,member.agent_rate')
          ->where(['mid' => $look_uid])
          ->page($offset, 10)
          ->select();
        foreach ($data as $key => $v) {
            $data[$key]['create_time'] = getTimeFormt($v['create_time'], 5);
            $data[$key]['create_time_m'] = getTimeFormt($v['create_time'], 6);
            $data[$key]['back_end'] = getEndBack($v['create_time']);
        }
        ajaxmsg('查看邀请用户', 1, $data, true, ['msgCode' => 'L0125']);
    }

    /*
    *修改用户返佣比例
    */
    public function changRate()
    {
        $req = request();
        $rate = intval(input("rate"));
        $chang_uid = intval(input("chang_uid"));
        $mid = MID;
        $agent_id = get_agents_level($mid);
        if (!$mid) {
            ajaxmsg('登陆后才能进行操作', 0, '', true, ['msgCode' => 'L0077']);
        }
        if (!$agent_id) {
            ajaxmsg('请先升级代理后才能设置邀请用户为代理', 0, '', true, ['msgCode' => 'L0078']);
        }
        if (!$chang_uid) {
            ajaxmsg('请求参数错误', 0, '', true, ['msgCode' => 'L0020']);
        }
        $user = get_agents_info($mid);
        if (!$user['agent_pro']) ajaxmsg('代理已经停用,不能设置其他代理状态', 0, '', true, ['msgCode' => 'L0079']);
        $rate_init = get_plus_rate($chang_uid);
        $rate_c = round(($rate_init * $rate), 2);
        $data['agent_rate'] = $rate;
        $data['agent_far'] = $mid;
        $data['agent_pro'] = 1;
        $data['agent_time'] = 0;
        $data['agent_id'] = $agent_id + 1;
        $member = new MemberModel();
        $res = $member->where(['id' => $chang_uid])->update($data);
        if ($res) {

            $minfo = Db::name('member m', true)->where('id',$chang_uid)->find();
            $minfo['invitation_money'] = get_back_money($minfo['invitation_mid']);
            $minfo['profit_member'] = get_users_m($minfo['invitation_mid']);
            $minfo['create_time'] = getTimeFormt($minfo['create_time'], 5);
            $minfo['create_time_m'] = getTimeFormt($minfo['create_time'], 6);
            $minfo['back_end'] = getEndBack($minfo['create_time']);
            $minfo['mange_rate'] = get_plus_rate($minfo['id']);
            $minfo['agent_des'] = $minfo['agent_id'] ? '代理商' : '普通用户';
            ajaxmsg('设置成功', 1, $minfo, true, ['msgCode' => 'L0126']);
        } else {
            ajaxmsg('修改失败', 0, '', true, ['msgCode' => 'L0127']);
        }
    }

    /*
    * 单个代理详情
    */
    public function agentdetail()
    {
        $mid = MID;
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        $look_uid = intval(input("chang_uid"));
        $data = $res = Db::name('member_invitation_relation as r')
          ->join('member', 'member.id=r.invitation_mid', 'LEFT')
          ->field('r.id,r.mid,r.create_time,r.invitation_mid,member.name,member.mobile,member.agent_id,member.agent_rate,member.agent_far,member.agent_pro,member.id,member.agent_rate')
          ->where(['r.mid' => $look_uid])
          ->find();
        $data['invitation_money'] = get_back_money($data['invitation_mid']);
        $data['agents_profit_money'] = agents_profit_money($data['invitation_mid']);
        $data['profit_member'] = get_users_m($data['invitation_mid']);
        $data['create_time'] = getTimeFormt($data['create_time'], 5);
        $data['create_time_m'] = getTimeFormt($data['create_time'], 6);
        $data['back_end'] = getEndBack($data['create_time']);
        $data['mange_rate'] = get_plus_rate($data['id']);
        $data['agent_des'] = $data['agent_id'] ? '代理商' : '普通用户';
        ajaxmsg('邀请人奖励记录', 1, $data, true, ['msgCode' => 'L0123']);
    }

    /*
    *修改代理状态
    */
    public function changStop()
    {
        $req = request();
        $chang_uid = intval(input("chang_uid"));
        $agent_pro = intval(input("agent_pro"));
        $mid = MID;
        $user = get_agents_info($mid);
        if (!$user['agent_pro']) ajaxmsg('代理已经停用,不能设置其他代理状态', 0, '', true, ['msgCode' => 'L0079']);
        $agent_id = $user['agent_id'];
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        if (!$agent_id) {
            ajaxmsg('请先升级代理后才能设置邀请用户为代理', 0, '', true, ['msgCode' => 'L0078']);
        }
        if (!$chang_uid) {
            ajaxmsg('请求参数错误', 0, '', true, ['msgCode' => 'L0020']);
        }
        $member = new MemberModel();
        $data['agent_pro'] = !$agent_pro;
        $res = $member->where(['id' => $chang_uid])->update($data);
        if ($res) {
            ajaxmsg('设置成功', 1, '', true, ['msgCode' => 'L0126']);
        } else {
            ajaxmsg('修改失败', 0, '', true, ['msgCode' => 'L0127']);
        }
    }

    /**
     * 邀请奖励记录
     * @return mixed
     */
    public function award()
    {
        $mid = is_member_signin();
        if (!$mid) {
            ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        }
        $req = request();
        $order = intval(input("order"));
        if ($order == 1) {
            $map = "create_time desc";
        } elseif ($order == 2) {
            $map = "affect desc";
        } elseif ($order == 3) {
            $map = "affect asc";
        } else {
            $map = "create_time asc";
        }
        $page = intval(input("page"));
        $page = $page ? $page : 1;
        $offset = $page;
        $data = $res = db::name('agents_back_money')
          ->where(['mid' => $mid])
          ->order($map)
          ->page($offset, 10)
          ->select();
        foreach ($data as $key => $v) {
            $data[$key]['create_time'] = getTimeFormt($v['create_time'], 5);
            $data[$key]['create_time_m'] = getTimeFormt($v['create_time'], 6);
        }
        ajaxmsg('设置成功', 1, $data, true, ['msgCode' => 'L0126']);
    }
}