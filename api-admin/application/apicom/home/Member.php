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

use app\apicom\model\JWT;
use app\common\controller\Common;
use app\fund\model\FundOrderGs;
use app\member\model\Bank as BankModel;
use app\member\model\Member as MemberModel;
use app\money\model\Money;
use think\Db;
use think\facade\Session;
use app\common\service\SignService;

class Member extends Common
{
    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function initialize()
    {
        // 系统开关
        if (!config('web_site_status')) {
            ajaxmsg('站点已经关闭，请稍后访问', 0, '', true, ['msgCode' => 'L0015']);
        }
        $req = request();
        $token = input("token");
        defined('MID') or define('MID', isLogin($token));
        if (!MID) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
    }

    //用户中心首页数据接口 登录后获取的用户信息 未登录初始化 返回 data = 0
    public function index()
    {
        $money = Money::getMoney(MID);
        $money['total_assets'] = bcdiv($money['account'], 100, 2) +bcdiv($money['activity_account'], 100, 2);
        $money['activity_account']=bcdiv($money['activity_account'], 100, 2);
        $money['account'] = bcdiv($money['account'], 100, 2);
        $money['freeze'] = bcdiv(bcadd($money['freeze'], $money['freeze_activity']), 100, 2);
        $money['operate_account'] = bcdiv($money['operate_account'], 100, 2);
        $money['bond_account'] = bcdiv($money['bond_account'], 100, 2);


        #合约资产
        $order_money= FundOrdergs::where('uid',MID)->whereIn('status', [1, 6, 7])->sum('money');
        $balance_money= FundOrdergs::where('uid',MID)->whereIn('status', [1, 6, 7])->sum('balance');

        //        持仓资产保留两位小数
        $money['contract_gd'] = $order_money+$balance_money;
        $money['contract_gd'] = sprintf("%.2f", $money['contract_gd']);
//        总资产保留两位小数
        $money['total'] =$money['total']/100+$money['contract_gd'];
        $money['total'] = sprintf("%.2f", $money['total']);


        $data['money'] = $money;
        $msg_num = Db('member_message')->where(['mid' => MID, 'status' => 0])->count();
        $mobile = Db('member')->alias('m')->field('id,mobile,agent_id,head_img,id_auth,id_card,invite_num')->where(['id' => MID, 'status' => 1])->find();
        $agent_num = Db('member')->where(['agent_far' => MID, 'status' => 1])->count();
        $other['msg_num'] = $msg_num;
        $other['mobile'] = $mobile['mobile'];
        $other['agent_id'] = $mobile['agent_id'];
        $other['link_m'] = $agent_num;
        $other['id_auth'] = $mobile['id_auth'];
        $other['id_card'] = $mobile['id_card'];
        $other['invite_num'] = $mobile['invite_num'];
        if ($mobile['head_img']) {
            $saveFile = str_replace("'\'", '/', $mobile['head_img']);
            $other['head_img'] = 'http://' . $_SERVER['HTTP_HOST'] . $saveFile;
        } else {
            $other['head_img'] = '';
        }
        $token = array(
          "uid" => $mobile['id'],
          'doHost' => $_SERVER['HTTP_HOST'],
          'doTime' => time(),
          "mobile" => $mobile['mobile'],
        );
        $jwt = JWT::encode($token, JWT_TOKEN_KEY);
        $other['token'] = $jwt;
        $data['info'] = $other;
        
        $data['F10_sign'] = (new SignService())->getF10DataSignStr();//这里是限制登录才能看
        Session::set('mids', MID);
        ajaxmsg('登陆信息', 1, $data, true, ['msgCode' => '登陆信息']);
    }

    /**
     * 用户信息-即将删除
     * @return void
     */
    public function userInfo()
    {
        $mid = MID;
        $minfo = MemberModel::getMemberInfoByID($mid);
        $fund_type = config('fund_type');
        $minfo['fund_type'] = $fund_type;
        unset($minfo['passwd']);
        unset($minfo['paywd']);
        if ($minfo['id_card'] == null) $minfo['id_card'] = '';
        if ($minfo['name'] == null) $minfo['name'] = '';
        $minfo['account'] = bcdiv($minfo['account'], 100, 2);
        $minfo['freeze'] = bcdiv($minfo['freeze'], 100, 2);
        $minfo['operate_account'] = bcdiv($minfo['operate_account'], 100, 2);
        $minfo['bond_account'] = bcdiv($minfo['bond_account'], 100, 2);
        if ($minfo['head_img']) {
            $saveFile = str_replace("\\", '/', $minfo['head_img']);
            $minfo['head_img'] = 'http://' . $_SERVER['HTTP_HOST'] . $saveFile;
        } else {
            $minfo['head_img'] = '';
        }
        $token = array(
          "uid" => $minfo['id'],
          'doHost' => $_SERVER['HTTP_HOST'],
          'doTime' => time(),
          "mobile" => $minfo['mobile'],
        );
        $jwt = JWT::encode($token, JWT_TOKEN_KEY);
        $minfo['token'] = $jwt;
        ajaxmsg('用户信息', 1, $minfo, true, ['msgCode' => 'L0099']);
    }

    /**省市调用**/
    public function getArea()
    {
        $reid = input("reid");
        $area = get_area($reid);
        ajaxmsg('银行卡信息', 1, $area, true, ['msgCode' => 'L0096']);
    }

    /**
     * 用户银行卡操作
     */
    public function bankInfo()
    {
        $bank = config('web_bank');
        $rate_val = array();
        foreach ($bank as $k => $v) {
            $arr = array(
              'id' => $k,
              'name' => $v,
            );
            array_push($rate_val, $arr);
        }
        $data['bank'] = $rate_val;
        $data['banks'] = Db('member_bank')->where(['mid' => MID])->select();
        ajaxmsg('银行卡信息', 1, $data, true, ['msgCode' => 'L0096']);
    }

    /***
     *添加银行卡
     **/
    public function addBank()
    {
        $id_auth = Db('member')->where(['id' => MID])->value('id_auth');
        if ($id_auth !== 1) {
            ajaxmsg('您还没有实名认证', 0, '', true, ['msgCode' => 'L0074']);
        }
        $data = input();
        $map = [
          'mid' => MID,
          'bank' => $data['bank'],
          'card' => $data['card'],
          'is_delete' => 0
        ];
        $bnk_id = Db::name('member_bank')
          ->where($map)
          ->value('id');
        if ($bnk_id)
            ajaxmsg('银行卡号已存在', 0, '', true, ['msgCode' => 'V0022']);
        $data['mid'] = MID;
        $res = BankModel::saveData($data);
        if ($res['status'] == 1) {
            ajaxmsg('添加成功', 1, true, ['msgCode' => 'L0100']);
        } else {
            ajaxmsg($res['message'], 0, '', true, ['msgCode' => 'L0101']);
        }
    }

    /*
     * 删除银行卡
     * id 要删除的银行卡id
     * mid 用户id
     */
    public function delBank()
    {
        $id = input("id");
        $mid = input("mid");
        if (MID) {
            $res = BankModel::del_bank($id);
            if (!$res) {
                ajaxmsg('删除失败', 0, '', true, ['msgCode' => 'L0102']);
            }
        } else {
            ajaxmsg('您要删除的银行卡不在您名下', 0, '', true, ['msgCode' => 'L0075']);
        }
        ajaxmsg('删除成功', 1, '', true, ['msgCode' => 'L0103']);
    }

    /*
     * 编辑银行卡
     * id 要编辑的银行卡id
     * mid 用户id
     */
    public function editBank()
    {
        $id = input("id");
        $res = [];
        $res = BankModel::bankInfo($id);
        if (!$res) {
            ajaxmsg('编辑失败', 0, '', true, ['msgCode' => 'L0104']);
        } elseif ($res['mid'] !== MID) {
            ajaxmsg('您要编辑的银行卡不在您名下', 0, '', true, ['msgCode' => 'L0076']);
        }
        $name = Db('member')->where(['id' => MID])->value('name');
        $data['name'] = $name;
        $data['bank_id'] = $id;
        $data['web_bank'] = config('web_bank');
        $data['bankinfo'] = $res;
        ajaxmsg('编辑信息', 1, $data, true, ['msgCode' => 'L0105']);
    }

    /*
     * 保存编辑数据
     */
    public function doEdit()
    {
        $data = input();
        $mid = MID;
        $data['mid'] = $mid;
        $res = BankModel::upEdit($data);
        if ($res['status'] == 1) {
            ajaxmsg($res['message'], 1, '', true, ['msgCode' => 'L0134']);
        } else {
            ajaxmsg($res['message'], 0, '', true, ['msgCode' => 'L0135']);
        }
    }

    /**
     * 支付方式
     */
    public function paymentmethod()
    {
        $data = array(
            //银行卡支付
          array("title" => lang('L0107'), "state" => 1, "thumb" => "__STATIC__/payment/recharge03.png", "type" => "offline", "name" => "xxxxx", "name1" => "中国建设银行", "name2" => "中国建设银行xxx支行", "card" => "1000", "img" => "")
            //支付宝扫码
        , array("title" => lang('L0108'), "state" => 1, "thumb" => "__STATIC__/payment/recharge02.png", "type" => "offline", "name" => "xxxxx", "name1" => "", "name2" => "", "card" => "", "img" => "//111.aa.com")
            //   ,array("title"=>"微信扫码","state"=>1,"thumb"=>"__STATIC__/payment/wxqr.png","type"=>"offline","name"=>"xxxxx","name1"=>"","name2"=>"","card"=>"","img"=>"//111.aa.com")
            //个人银行卡
        , array("title" => lang('L0109'), "state" => 1, "thumb" => "__STATIC__/payment/gryhk.png", "type" => "offline", "name" => "xxxxx", "name1" => "", "name2" => "", "card" => "", "img" => "//111.aa.com")
            //,array("title"=>"线上支付","state"=>0,"thumb"=>"__STATIC__/payment/recharge01.png","type"=>"online","name"=>"","name1"=>"","name2"=>"","card"=>"","img"=>"")
        );
        ajaxmsg('支付方式', 1, $data, true, ['msgCode' => 'L0106']);
    }
}