<?php

namespace app\member\home;

use app\money\model\Record as RecordModel;
use think\Db;
use think\facade\Lang;

/*
 * 前台首页控制器
 * @package app\member\wap
 */

class Attendancelottery extends Common
{
    /**
     * 首页
     * @return [type] [description]
     */
    public function index()
    {
        $req = request();
        $OA = input('OA');
        $mid = MID;
        $condition = $conditionlist = array();
        $arr = getdate();
        $num = $arr['wday'];
        $start = time() - ($num - 1) * 24 * 60 * 60;
        $end = time() + (7 - $num) * 24 * 60 * 60;
        $condition['mid'] = $mid;
        $condition['addtime'] = array(
          'between',
          array(
            $start,
            $end
          )
        );
        //  $dateinfo = Db::name('member_attendancelottery')->where($condition)->select();
        $sql = "select * from `lmq_member_attendancelottery` where mid={$mid} AND FROM_UNIXTIME(addtime,'%Y-%m')=date_format(now(),'%Y-%m')";
        $dateinfo = Db::query($sql);
        $jsondate = array();
        foreach ($dateinfo as $k => $v) {
            $jsondate[$k]['signDay'] = date("d", $v['addtime']);
        }
        $conditionlist['mid'] = $mid;
        $data = Db::name('member_attendancelottery')->where($conditionlist)->select();
        $datalist = array();
        $countday = '';
        foreach ($data as $k => $v) {
            $datalist[$k]['addtime'] = date("m月d日", $v['addtime']);
            $datalist[$k]['money'] = $v['money'] / 100;
            $datalist[$k]['count'] = count($data);
        }
        $countday = count($data);
        if ($OA == "APP") {
            $data = array();
            $data['jsondate'] = $jsondate;
            $data['countday'] = $countday;
            $data['datalist'] = $datalist;
            ajaxmsg('签到', 1, $data);
        } else {
            $jsondate = json_encode($jsondate, true);
            $this->assign('jsondate', $jsondate);
            $this->assign('datalist', $datalist);
            $this->assign('rooturl', $_SERVER['HTTP_HOST']);
            $this->display();
            if (is_mobile()) {
                return $this->fetch(); // 渲染模板
            } else {
                return $this->fetch('pc_index'); // 渲染模板
            }
        }
    }

    //执行签到操作
    public function ajaxattendance()
    {
        $mid = MID;
        $condition = array();
        $today = strtotime(date('Y-m-d 00:00:00'));
//        $condition['addtime'] = array('>=', $today);
        $condition[] = ['addtime','>=',$today];
        $condition[] = ['mid','=',$mid];
//        $condition['mid'] = $mid;
        //获取今天是否签到
        $data = Db::name('member_attendancelottery')->where($condition)->order('id', 'desc')->count();
        if ($data >= 1) {
            $msg = Lang::get('M0001');//当日已签到请明天再来
            ajaxmsg($msg, 500, '');
        } else {
            $money = Db::name('money')->where(array("mid" => $mid))->find();
            $affteractivity = bcadd($money['activity_account'], config('attendancelottery_money'));
            //更新资金
            $money_res = Db::name('money')->where('mid', $mid)->update(['activity_account' => $affteractivity]);
            // 更新资金日志表信息
            $record = new RecordModel;
            $info = "用户" . date("Y-m-d", time()) . "签到获得资金";
            $obj = ['affect' => 0, 'account' => $money['account'], 'affect_activity' => config('attendancelottery_money'), 'activity_account' => $affteractivity, 'sn' => ''];
            $record_res = $record->saveData($mid, 0, $money['account'], 37, $info, '', '', $obj);
//
//            $record['mid'] = $mid;
//            $record['create_time'] = time();
//            $record['affect_activity'] = config('attendancelottery_money');
//            $record['activity_account'] = $affteractivity;
//
//            $record['affect'] =0;
//            $record['account'] = $money['account'];
//
//
//
//
//            $record['type'] = 37;
//            $record['create_ip'] = get_client_ip(1);
//            $record['info'] =  "用户" . date("Y-m-d", time()) . "签到获得资金";
//            #插入抽奖机会消耗明细
//            $record_res = Db::name('money_record')->insert($record);
            if ($record_res && $money_res) {
                $inset = array("money" => config('attendancelottery_money'), "mid" => $mid, "addtime" => time());
                Db::table(config('database.prefix') . 'member_attendancelottery')->insert($inset);
                $msg = Lang::get('M0002');//签到成功，坚持才是胜利
                ajaxmsg($msg, 1, '');
            } else {
                $msg = Lang::get('M0003');//数据处理失败
                return ['status' => 0, 'message' => $msg . "（4009）"];
            }
        }
    }

    /**
     * 抽奖首页
     * @return
     */
    public function lottery()
    {
        $mid = MID;
        $condition = $conditionlist = array();
        $v = rand(100, 2000);
        $rows = 6;
        $limit = "0," . $rows;
        $list = $prizelist = Db::name('operate_prize')->where(array("status" => 1))->limit($limit)->select();
        $winning = $prizelistarray = array();
        //商品js列表
        foreach ($prizelist as $k => $s) {
            $prizelistarray[$k] = $s['name'];
        }
        //中奖率列表
        foreach ($prizelist as $k => $s) {
            $winning[$k] = $s['winningrate'] / 10;
        }
        //页面商品列表
        foreach ($prizelist as $k => $s) {
            $list[$k]['icon'] = get_file_path($s['icon']);
            switch ($k) {
                case 0:
                    $list[$k]['sort'] = 0;
                    break;
                case 1:
                    $list[$k]['sort'] = 4;
                    break;
                case 2:
                    $list[$k]['sort'] = 1;
                    break;
                case 3:
                    $list[$k]['sort'] = 2;
                    break;
                case 4:
                    $list[$k]['sort'] = 3;
                    break;
                case 5:
                    $list[$k]['sort'] = 5;
                    break;
            }
        }
        $last_names = array_column($list, 'sort');
        array_multisort($last_names, SORT_ASC, $list);
        //中奖用户列表
        $luckydraw = Db::query("select u.mobile,p.`name` from lmq_operate_luckydraw l LEFT JOIN lmq_member u ON u.id=l.mid LEFT JOIN lmq_operate_prize p ON p.id=l.pizeid  where iswinning=1  LIMIT 0,20");
        foreach ($luckydraw as $k => $s) {
            $tel = $s['mobile'];
            $luckydraw[$k]['mobile'] = substr($tel, 0, 3) . '****' . substr($tel, 7);
        }
        //中奖奖品
        $luckydrawlist = Db::query("select l.addtime,l.status,p.`name` from lmq_operate_luckydraw l LEFT JOIN lmq_operate_prize p ON p.id=l.pizeid  where l.mid={$mid} AND iswinning=1  LIMIT 0,50");
        foreach ($luckydrawlist as $k => $s) {
            $statusstr = $s['status'];
            if ($statusstr == '0') {
                $statusstr = "待处理";
            } else {
                $statusstr = "已处理";
            }
            $luckydrawlist[$k]['status'] = $statusstr;
            $luckydrawlist[$k]['addtime'] = date("Y-m-d h:s", $s['addtime']);
        }
        // $winning[count($prizelist)]="20";
        $prizelisjson = json_encode($prizelistarray);
        $winningratejson = json_encode($winning);
        //获取可抽奖次数
        $prizecount = Db::name('operate_user')->where(array("mid" => $mid))->find()['count'];
        #普通用户送登录次数
        if (config('luck_user')) {
            #判断当日是否领取
            $sql = 'SELECT count(*) as numcount FROM lmq_operate_record WHERE `mid` ="' . $mid . '" AND (type=2 OR type=1) AND date_format(from_unixtime(addtime),"%Y-%m-%d") = date_format(now(),"%Y-%m-%d")';
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
            //   $prizecount = $prizecount + 1;
        }
#处理获得抽奖机会规则
        if ($prizecount == '0') {
//1.新会员只能抽奖一次特定奖品我这边设置。 老会员1次
//2.有持仓的会员一天抽奖2次。
//3.当天充值满10000可以增加一次以1万一个单元增加一次，10万10次。
        }
        $this->assign('prizecount', $prizecount == '' ? 0 : $prizecount);
        $this->assign('winningratejson', $winningratejson);
        $this->assign('shopname', $prizelisjson);
        $this->assign('rows', $rows);
        $this->assign('v', $v);
        $this->assign('list', $list);
        $this->assign('luckydraw', $luckydraw);
        $this->assign('luckydrawlist', $luckydrawlist);
        $req = request();
        $OA = input('OA');
        if ($OA == "APP") {
            $data = array();
            $data['list'] = $list;
            $data['luckydraw'] = $luckydraw;
            $data['luckydrawlist'] = $luckydrawlist;
            $data['rows'] = $rows;
            $data['shopname'] = $prizelisjson;
            $data['winningratejson'] = $winningratejson;
            $data['web_cjgz'] = config('web_cjgz');
            ajaxmsg('抽奖信息', 1, $data);
        } else {
            if (is_mobile()) {
                return $this->fetch(); // 渲染模板
            } else {
                return $this->fetch('pc_lottery'); // 渲染模板
            }
        }
    }

    /**
     * 抽奖
     * @return [type] [description]
     */
    public function lucky()
    {
        $mid = MID;
        $rows = 16;
        $limit = "0," . $rows;
        $prizelist = Db::name('operate_prize')->where(array("status" => 1))->limit($limit)->select();
        $prizecount = Db::name('operate_user')->where(array("mid" => $mid))->find()['count'];
        if ($prizecount < 1) {
            return ['status' => 500, 'message' => '抽奖次数不足'];
        } else {
            $prize_arr = Db::name('operate_prize')->where(array("status" => 1))->select();
            foreach ($prize_arr as $key => $val) {
                $arr[$val['id']] = $val['winningrate'];
            }
            $luchhos = Db::name('operate_luckydraw')->where(array("mid" => $mid))->select();
            //用户没抽过奖 视为新用户
            if (count($luchhos) < 1) {
                $chars = [];
                foreach ($prizelist as $k => $v) {
                    if ($v['winningratetype'] == 1) {
                        $chars[] = $v['id'];
                    }
                }
                shuffle($chars); //打乱数组顺序
                $charsLen = count(shuffle) - 1;
                $str = '';
                for ($i = 0; $i < count(shuffle); $i++) {
                    $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
                }
                $lid = $str;
            } else {
                $lid = self::get_rand($arr);
            }
            $prizeid = $lid;
            #index  查询出来的位置顺序
            $k = array_search($lid, array_column($prizelist, 'id'));
            //产品类型  虚拟的还是实物
            $prizetype = $prizelist[$k]['type'];
            //是否中奖
            $iswinning = $prizelist[$k]['isprize'];
            if ($iswinning == '1') {
                if ($prizetype == '0') {
                    $money = Db::name('money')->where(array("mid" => $mid))->find();
                    $sumaccount = self::findNum($prizelist[$k]['name']);
                    $afftermoney = bcadd($money['account'], $sumaccount * 100);
                    $affteractivity = bcadd($money['activity_account'], $sumaccount * 100);
                    $money_res = Db::table(config('database.prefix') . 'money')->where('mid', $mid)->update(['activity_account' => $affteractivity]);
                    $record['mid'] = $mid;
                    $record['create_time'] = time();
                    $record['affect'] = $sumaccount * 100;
                    $record['account'] = $afftermoney;
                    $record['type'] = 36;
                    $record['create_ip'] = get_client_ip(1);
                    $record['info'] = "用户" . date("Y-m-d", time()) . "抽奖获得资金,奖品为：" . $prizelist[$k]['name'];
                    #插入抽奖机会消耗明细
                    Db::name('money_record')->insert($record);
                    $prizestatus = '1';
                } else {
                    #中奖订单处理情况  虚拟的自动处理了
                    $Handledata['aid'] = 0;
                    $Handledata['pid'] = $lid;
                    $Handledata['kname'] = 0;
                    $Handledata['kdexp'] = 0;
                    $Handledata['addtime'] = time();
                    Db::name('operate_exp')->insert($Handledata);
                }
                $msg = '抽奖成功，奖品为' . $prizelist[$k]['name'];
                $type = $prizetype;
                #插入中奖记录
                $insetdata['mid'] = $mid;
                $insetdata['addtime'] = time();
                $insetdata['iswinning'] = $iswinning;
                $insetdata['pizeid'] = $prizeid;
                $insetdata['status'] = $prizestatus == '0' ? 0 : $prizestatus;
                Db::name('operate_luckydraw')->insert($insetdata);
                #获取中奖记录id
                $hid = Db::name('operate_luckydraw')->getLastInsID();
            }
            #插入抽奖机会消耗明细
            $record['mid'] = $mid;
            $record['addtime'] = time();
            $record['num'] = "-1";
            $record['type'] = "-1";
            $record['info'] = "抽奖消耗1个抽奖机会";
            #插入抽奖机会消耗明细
            Db::name('operate_record')->insert($record);
            #更新抽奖次
            $prizecount = $prizecount - 1;
            Db::table('lmq_operate_user')->where('mid', $mid)->setField('count', $prizecount);
            if ($iswinning == '0') {
                return ['status' => 500, 'message' => "再接再厉", 'lid' => $lid, 'index' => $k];
            } else {
                #状态，提示文案，中奖物品类型（实物，虚拟），奖品id，中将记录id  #index 查询处理的位置顺序
                return ['status' => 200, 'message' => $msg, 'type' => $type, 'pid' => $lid, 'hid' => $hid, 'index' => $k];
            }
        }
    }

    /**
     * 收货人信息
     * @return
     */
    public function addressinfo()
    {
        $mid = MID;
        $data = input();
        $luckydraw = Db::name('operate_luckydraw')->where(array("mid" => $mid, 'id' => $data['lid']))->find();
        if ($luckydraw) {
            $insetdata['mid'] = $mid;
            $insetdata['lid'] = $data['lid'];
            $insetdata['sname'] = $data['sname'];
            $insetdata['sphone'] = $data['sphone'];
            $insetdata['saddress'] = $data['saddress'];
            $insetdata['addtime'] = time();
            #插入操作记录
            $insetinfo = Db::name('operate_winningrecord_daitls')->insert($insetdata);
            if ($insetinfo) {
                return ['status' => 200, 'message' => "成功"];
            }
            return ['status' => 500, 'message' => "参数错误-1"];
        } else {
            return ['status' => 500, 'message' => "参数错误-2"];
        }
    }

    /**
     * 提取字符串中的数字
     */
    private function findNum($str = '')
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }
        $temp = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $result = '';
        for ($i = 0; $i < strlen($str); $i++) {
            if (in_array($str[$i], $temp)) {
                $result .= $str[$i];
            }
        }
        return $result;
    }

    /*
    假设：有一个二维数组，记录了所有本次抽奖的奖项信息：
    $test_arr =array('a'=>20,'b'=>30,'c'=>50);
    a奖概率20%，b奖概率30%，c奖概率50%
    模拟函数执行过程：
    总概率精度为20+30+50=100
    第一次数组循环，$procur=20
    假设抽取的随机数rand(1,100)，假设抽到$randNum=55
    如果$randNum<=20,则result=a
    否则进入下一循环，总概率精度变为100-20=80
    第二次数组循环，$procur=30
    假设抽取的随机数rand(1,80)，假设抽到$randNum=33
    如果$randNum<=30,则result=b
    否则进入下一循环，总概率精度变为80-30=50
    第三次数组循环，$prosur=50;
    假设抽取的随机数rand(1,50)，不管怎么抽，随机数都会<或=50，
    那么得出result=c;
    因为样本没有改变，虽然可能抽取的随机数不止一个，但是概率是不变的。
    */
    private function get_rand($proArr)
    {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
}