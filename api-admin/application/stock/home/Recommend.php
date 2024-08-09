<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------
namespace app\stock\home;

use app\index\controller\Home;
use app\money\model\Record as RecordModel;
use think\Db;

/**
 * 免费体验控制器
 * @package app\stock\home
 */
class Recommend extends Home
{
    public function index()
    {
        $this->assign('title', '今日推荐');
        $req = request();
        $OA = input('OA');
        $mid = is_member_signin();
        if (!$mid) {
            $mid = null;
            //$this->error('请登陆后进行申请', URL('/login'));
        }
        $data_list = Db::name('stock_recommend')->limit(40)->order('id desc')->select();
        $list = array();
        if (!empty($data_list)) {
            for ($i = 0; $i < count($data_list); $i++) {
                $gpCode = $data_list[$i]['gupiao_code'];
                $date = $data_list[$i]['date_str'];
                $s1 = Db::name('stock_deal_stock')->where(['gupiao_code' => $gpCode])->order('deal_date desc')->find();
                $data_list[$i]['price'] = empty($s1) ? '-' : intval($s1['deal_price']);
                $data_list[$i]['volume'] = empty($s1) ? '-' : intval($s1['volume']);
                $data_list[$i]['amount'] = empty($s1) ? '-' : intval($s1['amount']);
                $data_list[$i]['state'] = empty($s1) ? '-' : '完成';
                if (array_key_exists($data_list[$i]['date_str'], $list)) {
                    array_push($list[$data_list[$i]['date_str']], $data_list[$i]);
                } else {
                    $list[$data_list[$i]['date_str']][0] = $data_list[$i];
                }
            }
        }
//        print_r($list);
//        array_multisort(array_column($list, 'id'), SORT_DESC, $list);
        if ($OA == "APP") {
            ajaxmsg('今日推荐', 1, $list);
        } else {
            $this->assign('list', $list);
            return $this->fetch();
        }
    }

    public function task()
    {

        $OA = input('OA');
        $mid = is_member_signin();

        if (!$mid) {
            $mid = null;
            //$this->error('请登陆后进行领取', URL('/wap/login'));
        }
        $state = [];
        //任务1 完成注册 task_id = 0 $state 0 前往  1领取  2已领取
        if (!empty($mid)) {
            $r = Db::name('stock_task_record')->where(['task_id' => 0, 'mid' => $mid])->find();
            $state[0]['name'] = '完成注册';
            $state[0]['note'] = '新用户注册享体验金';
            $state[0]['money'] = '+188佣金抵扣券';
            $state[0]['id'] = '0';
            $state[0]['is_show'] =1;
            if (!empty($r)) {
                $state[0]['status'] = 2;
            } else {
                $state[0]['status'] = 1;
            }
        } else {
            $state[0] = 0;
        }
        //任务2 实名认证
        if (!empty($mid)) {
            $r2 = Db::name('member')->where(['id_auth' => 1, 'id' => $mid])->find();
            $r = Db::name('stock_task_record')->where(['task_id' => 1, 'mid' => $mid])->find();
            $state[1]['name'] = '实名认证';
            $state[1]['note'] = '真实身份实名信息认证';
            $state[1]['money'] = '+3000体验金';
            $state[1]['id'] = 1;
            $state[1]['is_show'] =1;
            if (!empty($r)) {
                $state[1]['status'] = 2;
            } else {
                if (!empty($r2)) {
                    $state[1]['status'] = 1;
                } else {
                    $state[1]['status'] = 0;
                }
            }
        } else {
            $state[1]['status'] = 0;
        }
        //任务3 首次充值
        if (!empty($mid)) {
            $r2 = Db::name('money_recharge')->where(['status' => 1, 'mid' => $mid])->find();
            $r = Db::name('stock_task_record')->where(['task_id' => 2, 'mid' => $mid])->find();
            $state[2]['name'] = '首次充值';
            $state[2]['note'] = '首次充值入金';
            $state[2]['money'] = '+200佣金抵扣券';
            $state[2]['id'] = 2;
            $state[2]['is_show'] =1;
            if (!empty($r)) {
                $state[2]['status'] = 2;
            } else {
                if (!empty($r2)) {
                    $state[2]['status'] = 1;
                } else {
                    $state[2]['status'] = 0;
                }
            }
        } else {
            $state[2]['status'] = 0;
        }
        //任务4 首次配资
        if (!empty($mid)) {
//            $r2 = Db::name('stock_borrow')->where(['status' => 1, 'member_id' => $mid])->find();
            $r2 = Db::name('fund_order_gs')
                ->where('uid', $mid)
                ->whereIn('status', [1, 3, 6])
                ->find();
            $r = Db::name('stock_task_record')->where(['task_id' => 3, 'mid' => $mid])->find();
            $state[3]['name'] = '首次优投';
            $state[3]['note'] = '首次投资优投';
            $state[3]['money'] = '+300佣金抵扣券';
            $state[3]['id'] = 3;
            $state[3]['is_show'] =1;
            if (!empty($r)) {
                $state[3]['status'] = 2;
            } else {
                if (!empty($r2)) {
                    $state[3]['status'] = 1;
                } else {
                    $state[3]['status'] = 0;
                }
            }
        } else {
            $state[3]['status'] = 0;
        }
        //任务5 追加操盘金
        if (!empty($mid)) {
            $r2 = Db::name('stock_addfinancing')->where(['status' => 1, 'uid' => $mid])->find();
            $r = Db::name('stock_task_record')->where(['task_id' => 4, 'mid' => $mid])->find();
            $state[4]['name'] = '配资追加操盘金';

            $state[4]['note'] = '首次体验配资功能';
            $state[4]['money'] = '+300佣金抵扣券';
            $state[4]['id'] = 4;
            $state[4]['is_show'] =0;
            if (!empty($r)) {
                $state[4]['status'] = 2;
            } else {
                if (!empty($r2)) {
                    $state[4]['status'] = 1;
                } else {
                    $state[4]['status'] = 0;
                }
            }
        } else {
            $state[4]['status'] = 0;
        }
        //任务6 追加保证金
        if (!empty($mid)) {
            $r2 = Db::name('stock_addmoney')->where(['status' => 1, 'uid' => $mid])->find();
            $r = Db::name('stock_task_record')->where(['task_id' => 5, 'mid' => $mid])->find();
            $state[5]['name'] = '配资追加保证金';
            $state[5]['note'] = '首次追加保证金';
            $state[5]['money'] = '+300佣金抵扣券';
            $state[5]['id'] = 5;
            $state[5]['is_show'] =0;
            if (!empty($r)) {
                $state[5]['status'] = 2;
            } else {
                if (!empty($r2)) {
                    $state[5]['status'] = 1;
                } else {
                    $state[5]['status'] = 0;
                }
            }
        } else {
            $state[5]['status'] = 0;
        }

        //任务7 追加跟单
        if (!empty($mid)) {
            $r2 = Db::name('fund_order_gs')->where([['add_money' ,'>',0],[ 'uid' ,'=', $mid]])->find();
            $r = Db::name('stock_task_record')->where(['task_id' => 6, 'mid' => $mid])->find();
            $state[6]['name'] = '首次追加优投';
            $state[6]['note'] = '投资后完成追加优投';
            $state[6]['money'] = '+300佣金抵扣券';
            $state[6]['id'] = 6;
            $state[6]['is_show'] =1;
            if (!empty($r)) {
                $state[6]['status'] = 2;
            } else {
                if (!empty($r2)) {
                    $state[6]['status'] = 1;
                } else {
                    $state[6]['status'] = 0;
                }
            }
        } else {
            $state[6]['status'] = 0;
        }

        //任务8 首次续期
        if (!empty($mid)) {
            $r2 = Db::name('fund_order_gs')->where([['add_contract' ,'>',0],[  'uid' ,'=', $mid]])->find();
            $r = Db::name('stock_task_record')->where(['task_id' => 7, 'mid' => $mid])->find();
            $state[7]['name'] = '首次续期';
            $state[7]['note'] = '投资后完成优投续期';
            $state[7]['money'] = '+300佣金抵扣券';
            $state[7]['id'] = 7;
            $state[7]['is_show'] =1;
            if (!empty($r)) {
                $state[7]['status'] = 2;
            } else {
                if (!empty($r2)) {
                    $state[7]['status'] = 1;
                } else {
                    $state[7]['status'] = 0;
                }
            }
        } else {
            $state[7]['status'] = 0;
        }


        $data['note']='完成注册 task_id = 0 $state 0 前往  1领取  2已领取';
        $data['task']=$state;
        if ($OA == "APP") {
            ajaxmsg('任务', 1, $data);
        } else {
            $this->assign('state', $data);
            return $this->fetch();
        }
    }

    /**
     * Desc : 完成任务
     * Date : 2024-06-19 17:18
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function taskGift()
    {
        $req = request();
        $id = intval(input('id'));
        $mid = is_member_signin();
        $r = Db::name('stock_task_record')->where(['task_id' => $id, 'mid' => $mid])->find();
        if (!empty($r)) {
            ajaxmsg('已领取过了', 0, '', true, ['msgCode' => 'L0100']);
        }
        switch ($id) {
            case 0:
                $this->rec_money($mid, 18800, '完成注册奖励');
                break;
            case 1:
                $r2 = Db::name('member')->where(['id_auth' => 1, 'id' => $mid])->find();
                if (!empty($r2)) {
                    $this->rec_money_balance($mid, 300000, '完成实名认证奖励');
                } else {
                    ajaxmsg('条件不满足', 0, '', true, ['msgCode' => 'L0101']);
                }
                break;
            case 2:
                $r2 = Db::name('money_recharge')->where(['status' => 1, 'mid' => $mid])->find();
                if (!empty($r2)) {
                    $this->rec_money($mid, 20000, '完成首次充值奖励');
                } else {
                    ajaxmsg('条件不满足', 0, '', true, ['msgCode' => 'L0101']);
                }
                break;
            case 3:
//                $r2 = Db::name('stock_borrow')->where(['status' => 1, 'member_id' => $mid])->find();
                $r2 = Db::name('fund_order_gs')
                    ->where('uid', $mid)
                    ->whereIn('status', [1, 3, 6])
                    ->find();
                if (!empty($r2)) {
//                    $this->rec_money($mid, 30000, '完成首次配资奖励');
                    $this->rec_money($mid, 30000, '完成首次优投奖励');
                } else {
                    ajaxmsg('条件不满足', 0, '', true, ['msgCode' => 'L0101']);
                }
                break;
            case 4:
                $r2 = Db::name('stock_addfinancing')->where(['status' => 1, 'uid' => $mid])->find();
                if (!empty($r2)) {
                    $this->rec_money($mid, 30000, '完成追加操盘金奖励');
                } else {
                    ajaxmsg('条件不满足', 0, '', true, ['msgCode' => 'L0101']);
                }
                break;
            case 5:
                $r2 = Db::name('stock_addmoney')->where(['status' => 1, 'uid' => $mid])->find();
                if (!empty($r2)) {
                    $this->rec_money($mid, 30000, '完成追加保证金');
                } else {
                    ajaxmsg('条件不满足', 0, '', true, ['msgCode' => 'L0101']);
                }
                break;
            case 6:

                $r2 = Db::name('fund_order_gs')->where([['add_money' ,'>',0],[ 'uid' ,'=', $mid]])->find();
                if (!empty($r2)) {
                    $this->rec_money($mid, 30000, '首次追加优投');
                } else {
                    ajaxmsg('条件不满足', 0, '', true, ['msgCode' => 'L0101']);
                }
                break;
            case 7:
                $r2 = Db::name('fund_order_gs')->where([['add_contract' ,'>',0],[ 'uid' ,'=', $mid]])->find();
                if (!empty($r2)) {
                    $this->rec_money($mid, 30000, '首次续期');
                } else {
                    ajaxmsg('条件不满足', 0, '', true, ['msgCode' => 'L0101']);
                }
                break;
            default:
                break;
        }
        $d['mid'] = $mid;
        $d['task_id'] = $id;
        $d['add_time'] = time();
        Db::name('stock_task_record')->insert($d);
        ajaxmsg('领取成功', 200, '', true, ['msgCode' => 'L0102']);
    }

    protected function rec_money($mid, $fee, $info)
    {
        $money = Db::name('money')->where(array("mid" => $mid))->find();
        $afftermoney = bcadd($money['account'], $fee);
        $affteractivity = bcadd($money['activity_account'], $fee);
        //更新资金
        $money_res = Db::table(config('database.prefix') . 'money')->where('mid', $mid)->update(['activity_account' => $affteractivity]);
//
//        $record['mid'] = $mid;
//        $record['create_time'] = time();
//        $record['affect_activity'] =$fee /100;
//        $record['account'] = $money['account']/100;
//
////        $record['affect_activity'] = $afftermoney;
//         $record['activity_account'] = $affteractivity/100;
//
//
//        $record['type'] = 36;
//        $record['create_ip'] = get_client_ip(1);
//        $record['info'] = $info;
//        $record['type_name'] = '新手任务奖励';
//       Db::name('money_record')->insert($record);
//
        // 更新资金日志表信息
        $record = new RecordModel;
        $info = "新手任务奖励";
        $obj = ['affect' => 0, 'account' => $money['account'], 'affect_activity' => $fee, 'activity_account' => $affteractivity, 'sn' => ''];
        $record->saveData($mid, 0, $money['account'], 35, $info, '', '', $obj);
    }

    protected function rec_money_balance($mid, $fee, $info)
    {
        $money = Db::name('money')->where(array("mid" => $mid))->find();
        $balancemoney = bcadd($money['account'], $fee);

        //更新资金
        $money_res = Db::table(config('database.prefix') . 'money')->where('mid', $mid)->update(['account' => $balancemoney]);


        // 更新资金日志表信息
        $record = new RecordModel;
        $info = "新手任务奖励";
        $obj = ['affect' => $fee, 'account' =>$balancemoney, 'affect_activity' => 0, 'activity_account' => 0, 'sn' => ''];
        $record->saveData($mid, 0, $balancemoney, 35, $info, '', '', $obj);
    }


    public function active()
    {
        return $this->fetch();
    }
}

?>
