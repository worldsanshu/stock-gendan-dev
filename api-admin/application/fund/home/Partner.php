<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | @author 伊冰华 <2851336094@qq.com>
// +----------------------------------------------------------------------
namespace app\fund\home;

use app\cms\model\Advert as AdvertModel;
use app\fund\model\Fund as FundModel;
use app\fund\model\FundDayline as FundDaylineModel;
use app\fund\model\FundIncome;
use app\fund\model\FundOrder;
use app\fund\model\FundOrderGs;
use app\fund\model\FundUserlevel;
use app\fund\model\FundUserlevel as FundUserlevelModel;
use app\fund\model\FundViptrade as FundViptradeModel;
use app\fund\model\Trader;
use app\fund\model\Trader as TraderModel;
use app\member\model\Member;
use app\money\model\Money;
use app\money\model\Recharge;
use app\money\model\Record;
use think\Db;
use app\fund\model\FundSalaryConfig;
use app\fund\model\FundUserlevelAward;
use app\fund\model\FundSalaryLog;
use app\fund\model\FundIncomeLog;

class Partner extends Common
{
  protected $request;

  /**
   * 初始化方法
   * @author 蔡伟明 <314013107@qq.com>
   */
  protected function initialize()
  {
    $token = input("token");
    defined('MID') or define('MID', isLogin($token));
  }

  //获取列表
  public function getConfig()
  {
    $data                  = [];
    $data['user_level']    = FundUserlevelModel::where('1=1')->select()->each(function ($data){
      $data['position_ratio_txt'] = $data['min_position_ratio']/10 . '-'. $data['max_position_ratio']/10 .'层仓';
    });
    $data['subsidy']       = FundUserlevelAward::where('1=1')->select();
    $data['salary_config'] = FundSalaryConfig::where('1=1')->select();
    $data['salary_type']   = ['全部', '团队分成', '周工资', '补贴'];
    $data['fund_rule'] = config('fund_rule');
    $data['fund_rule_img'] = config('fund_rule_img');
    $data['notes_partner_system'] = config('notes_partner_system'); //备注
    if(!$data['fund_rule_img']){
        $data['fund_rule_img'] = $_SERVER['HTTP_HOST'].'/uploads/default/documentaryRule.jpeg';
    }else{
        $data['fund_rule_img'] = $_SERVER['HTTP_HOST'] . get_files_path($data['fund_rule_img']);
    }
    return json(array(
      'data'    => $data,
      'status'  => 1,
      'message' => '合伙人配置'
    ));
  }

  //账单明细
  public function getBillLog()
  {
    $uid       = MID;
    $type      = input('type');
    $rangeTime = input('range_time', '');

    $typeArr = ['', '团队分成', '周工资', '补贴'];
    $list    = FundSalaryLog::where('uid', $uid)
      ->where(function ($query) use ($rangeTime, $type) {
        if (!empty($rangeTime)) {
          $rangeTime[0] = strtotime($rangeTime[0]);
          $rangeTime[1] = strtotime('+1 d', strtotime($rangeTime[1]));
          $query->where('create_time', '>', $rangeTime[0])
            ->where('create_time', '<', $rangeTime[1]);
        }
        if (!empty($type)) {
          $query->where('type', $type);
        }
      })
      ->order('id desc')
      ->paginate()
      ->each(function ($item) use ($typeArr) {
        $item->type_name = $typeArr[$item->type];
      });

    return json(array(
      'data'    => $list,
      'status'  => 1,
      'message' => '账单明显'
    ));
  }
  
  //账单明细
  public function statistics()
  {
      $uid = MID;
      $map[]= ['uid', '=', $uid];
      $data = [];
      $user = Member::where('id', $uid)->find();
      $data['my_level_text'] = FundUserlevel::where('level', $user['level'])->value('name') ?: '普通合伙人';
      //总收益
      $totalIncome = 0;
      $totalIncome +
      $teamIncome = FundSalaryLog::where($map)->sum('price');//合伙人收益
      $totalIncome += $teamIncome;
      $totalIncome += FundIncomeLog::where($map)->sum('money');//投资收益
      $totalIncome = round($totalIncome , 2);
      $data['my_income_total'] = $totalIncome;
      //今日收益
      $todayIncome = 0;
      $todayIncome += FundSalaryLog::where($map)->whereTime('create_time', 'today')->sum('price');//合伙人收益
      $todayIncome += FundIncomeLog::where($map)->whereTime('create_time', 'today')->sum('money');//投资收益
      $todayIncome = round($todayIncome  , 2);
      $data['my_income_today'] = $todayIncome;
      
      $data['my_directly_ordinary'] = Member::where('partner_parent_id',$uid)->where(['is_buy'=>1,'level'=>0])->count(); //普通合伙人数
      $data['my_directly_first']  = Member::where('partner_parent_id',$uid)->where(['is_buy'=>1,'level'=>1])->count(); //一级合伙人数
      
      $data['my_invest_total']  = FundOrderGs::where($map)->where('status', '>', 0)->count();//总优投数
      $data['my_invest_today']  = FundOrderGs::where($map)->where('status', '>', 0)->whereTime('create_time', 'today')->count();//今日优投

      //团队用户
      $path = $user->partner_parent_net . ',' . $user->id;
      $level = $user->partner_parent_level + 3; //计算三层团队
      $teamIds = Member::where('1=1')
      ->where([['partner_parent_net', 'like', $path . '%']])
      ->where([['partner_parent_level', '<=', $level]])
      ->where('is_buy', 1)
      ->column('id');
      $data['team_recharge_today'] = Recharge::whereIn('mid',$teamIds)->whereTime('create_time', 'today')->where('is_first',1)->group('mid')->count();
      $data['team_invest_today']  = FundOrderGs::whereIn('uid',$teamIds)->where('status', '>', 0)->whereTime('create_time', 'today')->count();//今日优投数
      $data['team_invest_week']  = FundOrderGs::whereIn('uid',$teamIds)->where('status', '>', 0)->whereTime('create_time', 'week')->group('uid')->count();//一周优投人数
      
      $teamIncome = round($teamIncome , 2);
      $data['team_income'] = $teamIncome;//团队收益
      $data['team_num_total'] = count($teamIds);     //团队人数
      $data['team_num_directly'] = Member::where('partner_parent_id',$uid)->where(['is_buy'=>1])->count();//直属人数

      $max_level= FundUserlevel::order('level', 'desc')->limit(1)->find();
      $next_level= FundUserlevel::where('level','>', $user['level']) ->order('id', 'asc')->limit(1)->find();
      //下一个等级名称
      $data['max_level_text'] = $max_level->name ?: '';
      $data['next_level_text'] = $next_level->name ?: '';

      //距离下一个等级还差多少人
      $data['next_level_people'] =$next_level->min_num-$data['team_num_total'] ?: '0';

//      当前人数百分比
      $data['current_level_people'] = $next_level->min_num ?: '';


      if (!is_numeric($next_level->min_num) || $next_level->min_num <= 0 || !is_numeric($data['team_num_directly'])) {
          //        如果是顶级
          if($user['level'] == $max_level['level']){
              if($max_level->min_num <= $data['team_num_directly']){
                  $data['current_level_people_radio'] = 100;
              }
          }else{
              // 存储百分比
              $data['current_level_people_radio'] = 0;
          }
      }else{

          // 计算百分比
          $percentage = ($data['team_num_directly'] / $next_level->min_num) * 100;
          if($percentage >100){
//              超出人数上限的话
              $percentage = 100;
          }
          // 格式化百分比（可选，根据需要调整）
          $formatted_percentage = round($percentage);

          // 存储百分比
          $data['current_level_people_radio'] = $formatted_percentage;
          //        如果是顶级
          if($user['level'] == $max_level['level']){
              if($max_level->min_num <= $data['team_num_directly']){
                  $data['current_level_people_radio'] = 100;
              }
          }
      }



      //$data['uid'] = $uid;
      //$data['teamIds'] = $teamIds;
      ajaxmsg('合伙人统计', 1, $data);
  }

  //一周内优投人数
  public function investWeekList()
  {
      $uid = MID;
      //团队用户
      $user = Member::where('id', $uid)->find();
      $path = $user->partner_parent_net . ',' . $user->id;
      $level = $user->partner_parent_level + 3; //计算三层团队
      $teamIds = Member::where('1=1')
      ->where([['partner_parent_net', 'like', $path . '%']])
      ->where([['partner_parent_level', '<=', $level]])
      ->where('is_buy', 1)
      ->column('id');
      $list = FundOrderGs::whereIn('uid',$teamIds)
      ->where('status', '>', 0)
      ->whereTime('create_time', 'week')
      ->group('uid')
      ->order('id desc')
      ->paginate()->each(function($iteam){
          $iteam->user_info = Member::where('id', $iteam->uid)->find();
      });
      ajaxmsg('一周内优投人数', 1, $list);
  }
  //今日优投数
  public function investTodayList()
  {
      $uid = MID;
      //团队用户
      $user = Member::where('id', $uid)->find();
      $path = $user->partner_parent_net . ',' . $user->id;
      $level = $user->partner_parent_level + 3; //计算三层团队
      $teamIds = Member::where('1=1')
      ->where([['partner_parent_net', 'like', $path . '%']])
      ->where([['partner_parent_level', '<=', $level]])
      ->where('is_buy', 1)
      ->column('id');
      $list = FundOrderGs::whereIn('uid',$teamIds)
      ->where('status', '>', 0)
      ->whereTime('create_time', 'today')
      //->group('uid')
      ->order('id desc')
      ->paginate()->each(function($iteam){
          $iteam->user_info = Member::where('id', $iteam->uid)->find();
      });
      ajaxmsg('今日优投数', 1, $list);
  }
  
  //团队列表
  public function getTeamList()
  {
      $uid = MID;
      //新写三级团队
      $us    = Member::where('id', $uid)->find();
      $path  = $us->partner_parent_net . ',' . $us->id;
      $level = $us->partner_parent_level + 3; //计算三层团队
      $list  = Member::where('1=1')
      ->field('id,level,name,mobile,create_time,create_time as create_time_text')
      ->where([['partner_parent_net', 'like', $path . '%']])
      ->where([['partner_parent_level', '<=', $level]])
      ->where('is_buy', 1)
      ->order('id desc')
      ->paginate();
      ajaxmsg('团队列表', 1, $list);
 
  }

}