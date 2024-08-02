<?php
namespace app\member\model;

use app\fund\model\FundOrder;
use app\fund\model\FundUserlevel;
use think\Db;
use think\facade\Lang;
use think\helper\Hash;
use think\model;

class Member extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MEMBER__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 对密码进行加密
    public static function getlist($map, $order)
    {

        $list= self::view('member m', true)
          ->view('money n', 'account,freeze,operate_account,bond_account,activity_account,freeze_activity', 'm.id=n.mid', 'LEFT')
          ->view('member_login_record r', 'terminal,login_ip', 'm.id=r.mid', 'LEFT')
          ->where($map)
          ->order($order)
          ->group('m.id')
          ->paginate();
        return $list ?: [];
    }

    public static function getinvitelist($map, $order)
    {
        $field = '*,re_id re_name,re_id re_mobile,id re_num,id team_num,id buy_money,id sell_money';
        $list = self::where($map)->order($order)->field($field)->paginate();
        return $list ?: [];
    }

    //推荐人姓名
    public function getReNameAttr($value)
    {
        $name = self::where('id', $value)->value('name');
        return $name ?: '--';
    }

    //推荐人手机号
    public function getReMobileAttr($value)
    {
        $mobile = self::where('id', $value)->value('mobile');
        return $mobile ?: '--';
    }

    //直推人数
    public function getReNumAttr($value)
    {
        $count = self::where('re_id', $value)->where('is_buy', 1)->count();
        return $count;
    }

    //团队人数
    public function getTeamNumAttr($value)
    {
        $sql = 'SELECT id,level FROM lmq_member WHERE FIND_IN_SET(id,queryChildrenAreaInfo(' . $value . ')) and `is_buy` = 1  and `id` != ' . $value;
        $list = Db::query($sql);
        $count = count($list);
        return $count;
    }

    //买入金额
    public function getBuyMoneyAttr($value)
    {
        $map = [];
        $map['uid'] =$value;
        $map['type'] = 1;
        $total_money = FundOrder::where($map)->sum('money');
        return $total_money;
    }

    //买入金额
    public function getSellMoneyAttr($value)
    {
        $map = [];
        $map['uid'] = $value;
        $map['type'] = 2;
        $total_money = FundOrder::where($map)->sum('money');
        return $total_money;
    }

    /**
     * 根据会员ID获取会员基本信息
     * @param array $id 会员ID
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getMemberInfoByID($id = null)
    {
        $where['m.id'] = $id;//会员ID
        $where['m.status'] = 1;//会员状态
        $data = self::view('member m', true)
          ->view("money", 'account,freeze,operate_account,bond_account,activity_account,freeze_activity', 'money.mid=m.id', 'left')
          ->where($where)
          ->find();
        return $data;
    }

    // 获取注册ip

    /**
     * 根据会员手机号获取会员基本信息
     * @param array $id 会员ID
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getMemberInfoByMobile($mobile = null)
    {
        $where['m.mobile'] = $mobile;//会员手机号
        $where['m.status'] = 1;//会员状态
        $data = self::view('member m', true)
          ->view("money", 'account,freeze,operate_account,bond_account', 'money.mid=m.id', 'left')
          ->where($where)
          ->find();
        return $data;
    }

    /**
     * 保存注册数据
     * @param  [type] $data [description]
     * @return [type]       [description]
     * @author 张继立 <404851763@qq.com>
     */
    public static function saveData($data)
    {
//        $sdata['mobile'] = $data['mobile'];
        if (isset($data['email']) && $data['email'])
            $sdata['email'] = $data['email'];
        $sdata['passwd'] = $data['password'];
        if (isset($data['mobile']) && $data['mobile']) {
            $sdata['mobile'] = $data['mobile'];
        } else {
        }
//        if($data['mobile']){
//            $pay_pwd = substr($data['mobile'],-6);
//            $sdata['paywd'] = $pay_pwd;
//        }
//        $sdata['paywd'] = Hash::make((string)123456);
        $sdata['paywd'] = 123456;
//        $sdata['pid'] = 0;
        $sdata['agent_far'] = intval($data['agent_far']);
        $sdata['re_id'] = isset($data['re_id'])?intval($data['re_id']):'' ;
        $sdata['partner_parent_id'] = $sdata['re_id'];//合伙人-推荐关系
        $sdata['create_ip'] = get_client_ip(1);
        $sdata['create_time'] = time();
        $sdata['reg_code'] = $data['code'] ?? '86';
        $sdata['from_to'] = empty($data['from_to']) ? $_SERVER['SERVER_NAME'] : $data['from_to'];
        $lenth = 8;
        $invite_code = substr(uniqid() . time(), 0, $lenth);
        $sdata['invite_code'] = $invite_code;
        $agent_id = $data['agent_pid'] ?? 0;
        $sdata['id_auth'] = $data['id_auth'] ?? 0;
        //多级代理的逻辑代码
//        if($agent_id){
//
//            $agent_data = self::where('id',$agent_id) -> field('agent_id,name,mobile');//代理等级
//            $agent_level = $agent_data['agent_id'];
//            $sdata['pid'] = $agent_id;
//            $sdata['agent_name'] = $agent_data['name'];
//            $sdata['agent_mobile'] = $agent_data['mobile'];
//            $sdata['agent_id'] = $agent_level+1; //邀请人等级加1
//        }
        if (isset($data['role_name'])) {
            $sdata['role_name'] = $data['role_name'];
        }

        $result = self::create($sdata);


        if ($result->id) {
            $agent_id = $data['agent_pid'] ?? '';
            if ($agent_id) {
                self::appendInvitationUser($agent_id, $result->id, $data['invite_code']);
            }
            Db('money')->insert(['mid' => $result->id, 'for_user' => $sdata['agent_far']]);
            $mid = $sdata['id'] = $result->id;
            if (config('luck_register')) {
                #新用户注册成功可获得一次注册机会
                $count = explode("-", config('luck_register'))[1];
                //插入抽奖数据  $num抽奖次数   $mid 用户id  #抽奖次数获得/消耗明细
                insertoperate($count, $mid);
            } else {
                //插入抽奖数据  $num抽奖次数   $mid 用户id
                insertoperate(0, $mid);
            }
            //    getposition(get_client_ip(0, 1), 1, $mid);
            $_msg = Lang::get('L0171');
            return ['status' => 1, 'message' => $_msg, 'data' => $sdata];
        } else {
            Db::rollback();
            $_msg = Lang::get('L0172');
            return ['status' => 0, 'message' => $_msg];
        }
    }

    public function appendInvitationUser($agent_id = '', $invitation_mid = '', $invite_code = '')
    {
        $data['invitation_mid'] = $invitation_mid;
        $data['mid'] = $agent_id;
        $data['create_time'] = time();
        Db('member_invitation_relation')->insert($data);
        return;
    }

    public function setPasswdAttr($value)
    {
        return Hash::make((string)$value);
    }

    public function setPaywdAttr($value)
    {
        return Hash::make((string)$value);
    }

    public function setAddIpAttr()
    {
        return get_client_ip(1);
    }

    /**
     * 用户登录
     * @param string $mobile 手机号
     * @param string $password 密码
     * @param string $nopass 免密登录
     *
     * @return bool|mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function login($mobile = '', $password = '', $terminal = '', $nopass = '',$from_to='')
    {
        $mobile = trim($mobile);
        $password = trim($password);
        if (is_numeric($mobile)) {
            // 手机号登录
            $map['mobile'] = $mobile;
        } elseif (preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+$/', $mobile)) {
            $map['email'] = $mobile;
        } else {
            $this->error = Lang::get('L0164'); //'请用手机号码或邮箱登录';
            return false;
        }
        $map['status'] = 1;
        $map['is_del'] = 0;
        // 查找用户
        $member = $this::get($map);
        if (!$member) {
            $this->error = Lang::get('L0165'); //'用户不存在或被禁用！';
        } else {
            if (!Hash::check((string)$password, $member['passwd']) && empty($nopass)) {
                $this->error = Lang::get('L0166');// '用户名或密码错误！';
            } else {
                $mid = $member['id'];
                // 更新登录信息
                $member['terminal'] = $terminal;
                $member['last_login_time'] = request()->time();
                $member['last_login_ip'] = get_client_ip(1);
                if ($member->save()) {
                    $operateuser = Db::name('operate_user')->where(array("mid" => $mid))->select();
                    if (!$operateuser) {
                        //插入抽奖数据  $num抽奖次数   $mid 用户id  #抽奖次数获得/消耗明细
                        insertoperate(0, $mid);
                    }
                    //    updateoperate($num, $mid,$info,$type);
                    $prizecount = $operateuser[0]['count'];
                    $this->luck_user_plus($mid, $prizecount);
                    // getposition(get_client_ip(0, 1), 2, $mid);
                    #登陆记录
                    if (!$nopass) {
                        $this->login_record($mid, $member['mobile'], $member['email'], $member['name'], $member['terminal'],$from_to);
                    }
                    return $this->autoLogin($this::get($mid));
                } else {
                    // 更新登录信息失败
                    $this->error = Lang::get('L0167');//登录信息更新失败，请重新登录！';
                    return false;
                }
            }
        }
        return false;
    }

    public static function luck_user_plus($mid, $prizecount)
    {
        #普通用户送登录次数
        if (config('luck_user')) {
            #判断当日是否领取
            $sql = "SELECT count(*) as numcount FROM lmq_operate_record WHERE `mid` = " . intval($mid) . " AND (type=2 OR type=1) AND date_format(from_unixtime(addtime),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')";
            $luckycount = Db::query($sql);
            $luckycount = $luckycount[0]['numcount'];
            if ($luckycount < 1) {
                #新用户注册成功可获得一次注册机会
                $count = explode("-", config('luck_user'))[1];
                $newcount = $count + $prizecount;
                Db::name('operate_user')->where('mid', $mid)->update(['count' => $newcount]);
                #抽奖次数获得/消耗明细
                $insetrecorddata['num'] = $count;
                $insetrecorddata['mid'] = $mid;
                $insetrecorddata['type'] = 2;
                $insetrecorddata['info'] = "老用户登陆获得{$count}次抽奖机会";
                $insetrecorddata['addtime'] = time();
                Db::name('operate_record')->insert($insetrecorddata);
            }
        }
    }

    /**
     * 登陆纪录
     **/
    public static function login_record($uid, $mobile, $email, $name, $terminal = '',$from_to='')
    {

        $sdata['mid'] = $uid;
        $sdata['login_ip'] = get_client_ip(1);
        $sdata['login_time'] = time();
        $sdata['from_to'] = $from_to ?? $_SERVER['SERVER_NAME'];
        $sdata['mobile'] = $mobile;
        $sdata['terminal'] = $terminal;
        $sdata['email'] = $email;
        $sdata['username'] = $name;
        Db('member_login_record')->insert($sdata);
    }

//判断当然是否已赠送抽奖次数

    /**
     * 自动登录
     * @param object $member 用户对象
     * @return bool|int
     * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
     */
    public function autoLogin($member)
    {
        // 记录登录SESSION和COOKIES
        $auth = array(
          'mid' => $member->id,
          'mobile' => $member->mobile,
          'last_login_time' => $member->last_login_time,
          'last_login_ip' => get_client_ip(1),
        );
        session('member_auth', $auth);
        session('member_auth_sign', data_auth_sign($auth));
        return $member->id;
    }

    public function getCreateTimeTextAttr($value)
    {
        return $value ? date('Y-m-d H:i', $value) : '';
    }

    public function getLevelTextAttr($value)
    {
        $level_array = Funduserlevelarray();
        return $level_array[$value] ?? '';
    }

    //更新用户级别
    public static function updateUserLevel($id)
    {
        #找到上级ID
        $re_id = self::where('id', $id)->value('re_id');
        #查询上级的等级
        $user_level = self::where('id', $re_id)->value('level');
        #获取上级的直推人数
        $count = self::where('re_id', $re_id)->count();
        $level = 0;

        $list = FundUserlevel::order('id asc')->select();
        foreach ($list as $key => $value) {
            if ($count >= $value['min_num'] && $count <= $value['max_num']) {
                $level = $value['level'];
            }
        }
        if ($user_level < $level) {
            #如果原先在后台设置的级别大于或者等于将要升级的级别那就不处理
            self::where('id', $re_id)->update(['level' => $level]);
        }
        return true;
    }


    //获取顶级
//$old_uid  原uid
//$uid     递归变换uid
    public static function getTopLevel($old_uid,$uid, $level=3, $currentLevel = 1)
    {
        if ($currentLevel > $level) {
            // 已超过最大查询层级，返回null或空结果
            $get_one = self::where('id', $uid)->field('id,re_id,name')->find();
            return $get_one['name'];
        }
        $get_one = self::where('id', $uid)->field('id,re_id,name')->find();
        if (!$get_one) {
            // 当前UID对应的记录不存在，也返回null或空结果
            return '--';
        }

        if ($get_one['re_id'] == 0) {
//            如果是当前用户
            if($old_uid == $uid){
                return '--';
            }
            // 找到顶级节点，直接返回
            return $get_one['name'];
        } else {
            // 继续查询上一级，递增层级计数
            return self::getTopLevel($old_uid,$get_one['re_id'], $level, $currentLevel + 1);
        }
    }



    /**
     * 同步修改其他关联的手机号
     * @param  $data
     * @param  $uid
     * @param  $type  1用户修改  2管理员操作
     */
    public static function syncUpdateMobile($data,$uid,$type)
    {
        Db::startTrans();
        try {
            if($type == 1){
                $data  = [
                    'mobile'   => $data['mobile'],
                    'reg_code' => $data['reg_code'],
                ];
                $res = Db::name('member')->where(["id" => $uid])->update($data);

            }else{
                $res =true;
            }
            if($res){
                $fund['mobile'] = $data['mobile'];
                Db::name('fund_order_gs')->where(["uid" => $uid])->update($fund);
            }

            Db::commit();

        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
        return true;
    }
}

?>
