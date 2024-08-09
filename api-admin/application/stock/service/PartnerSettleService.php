<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | @author 伊冰华 <2851336094@qq.com>
// +----------------------------------------------------------------------
namespace app\stock\service;

use app\fund\model\FundDayline;
use app\fund\model\FundIncomeLog;
use app\fund\model\FundSalaryConfig;
use app\fund\model\FundSalaryLog;
use app\fund\model\FundSalaryLogContent;
use app\fund\model\FundUserlevel;
use app\member\model\Member;
use app\money\model\Money;
use app\money\model\Record;
use think\Db;

class PartnerSettleService 
{

  public function crond()
  {
    $this->netWork();  
    //任务计算用户跟单团队收益和直属收益业绩
    $list = FundDayline::where(['partner_settle' => 2, 'status' => 3])->where([['profit', '>', 0]])->select();
    foreach ($list as $value) {
      //三层团队业绩
      $user       = Member::where('id', $value->uid)->find();
      $first_uid  = 0;
      $second_uid = 0;
      $third_uid  = 0;
      if (!empty($user->partner_parent_id)) {
        $first_uid = $user->partner_parent_id;//直属
        $info      = "团队用户[uid:{$value->uid}]-股票订单[订单id:{$value->id}]";
        self::fundSalaryLogContent($first_uid, $value->profit, 2, $value->id, 1, $info, json_encode($value));//记录直属业绩
        self::fundSalaryLogContent($first_uid, $value->profit, 1, $value->id, 1, $info, json_encode($value));//记录团队业绩
        $first = Member::where('id', $first_uid)->find();
        if (!empty($first->partner_parent_id)) {
          $second_uid = $first->partner_parent_id;                                                              //二级级
          self::fundSalaryLogContent($second_uid, $value->profit, 1, $value->id, 1, $info, json_encode($value));//记录团队业绩
          $second = Member::where('id', $second_uid)->find();
          if (!empty($second->partner_parent_id)) {
            $third_uid = $second->partner_parent_id;                                                             //三级
            self::fundSalaryLogContent($third_uid, $value->profit, 1, $value->id, 1, $info, json_encode($value));//记录团队业绩
          }
        }
      }
      $value->partner_settle      = 1;
      $value->partner_settle_time = date('Y-m-d H:i:s');
      $value->save();
    }
    var_dump(count($list));

    //任务计算用户基金收益业绩
    $list = FundIncomeLog::where(['partner_settle' => 2, 'order_type' => 1])->where([['money', '>', 0]])->select();
    foreach ($list as $value) {
      //三层团队业绩
      $user       = Member::where('id', $value->uid)->find();
      $first_uid  = 0;
      $second_uid = 0;
      $third_uid  = 0;
      if (!empty($user->partner_parent_id)) {
        $first_uid = $user->partner_parent_id;//直属
        $info      = "团队用户[uid:{$value->uid}]-基金订单[订单id:{$value->order_id}]";
        self::fundSalaryLogContent($first_uid, $value->money, 2, $value->id, 2, $info, json_encode($value));//记录直属业绩
        self::fundSalaryLogContent($first_uid, $value->money, 1, $value->id, 2, $info, json_encode($value));//记录团队业绩
        $first = Member::where('id', $first_uid)->find();
        if (!empty($first->partner_parent_id)) {
          $second_uid = $first->partner_parent_id;                                                             //二级级
          self::fundSalaryLogContent($second_uid, $value->money, 1, $value->id, 2, $info, json_encode($value));//记录团队业绩
          $second = Member::where('id', $second_uid)->find();
          if (!empty($second->partner_parent_id)) {
            $third_uid = $second->partner_parent_id;                                                            //三级
            self::fundSalaryLogContent($third_uid, $value->money, 1, $value->id, 2, $info, json_encode($value));//记录团队业绩
          }
        }
      }
      $value->partner_settle      = 1;
      $value->partner_settle_time = date('Y-m-d H:i:s');
      $value->save();
    }
    var_dump(count($list));
  }
  //更新网体
  public function netUpdate(){
      Member::where('1=1')->update(['partner_net_update'=>1, 'partner_count'=>1, 'partner_directly_num'=>0,'partner_team_num'=>0]);
  }
  //网体任务
  public function netWork(){
      //更新会员网体
      $list = Member::where(['partner_net_update' => 1])->order('id asc')->limit(3000)->select();
      foreach ($list as $value) {
          $parents   = Member::where(['id' => $value->partner_parent_id])->find();
          $parentStr = 0;
          $level     = 0;
          if (!empty($parents)) {
              if (empty($parents->partner_parent_net)) {
                  //continue;
              }
              $parentStr = $parents->partner_parent_net . ',' . $parents->id;
              $level     = $parents->partner_parent_level + 1;
          }
          $value->partner_parent_net   = $parentStr;
          $value->partner_parent_level = $level;
          $value->partner_net_update   = 2;
          $value->save();
      }
      //var_dump(count($list));
      //更新会员团队人数
      $list = Member::where(['partner_count' => 1, 'is_buy' => 1, 'partner_net_update'=>2])->limit(3000)->select();
      foreach ($list as $value) {
          $PathNet   = $value->partner_parent_net;
          $parentIds = explode(',', $PathNet);
          $parentIds = array_reverse($parentIds);                               //推荐关系反序
          Member::where('id', $parentIds[0])->setInc('partner_directly_num', 1);//直属加1  需不需要购买过的才计数 需要加上 过滤条件'is_buy'=>1
          
          $numElements = min(count($parentIds), env('partner.tier',3));
          $parentIds   = array_slice($parentIds, 0, $numElements);
          Member::where([['id', 'in', $parentIds]])->setInc('partner_team_num', 1);//团队大于3个取3个 团队每人加1
          
          $value->partner_count = 2;
          $value->save();
      }
      //var_dump(count($list));
      if (count($list) > 0) {
          self::upLevel();
      }
  }

  public function upLevel()
  {//团队升级
    $levelConfig = FundUserlevel::where('1=1')->select();
    Member::where('1=1')->update(['level' => 0]);// 又升又降 去掉只升不降
    foreach ($levelConfig as $value) {
      $min   = $value->min_num;
      $level = $value->level;
      $count = Member::where([['partner_team_num', '>=', $min], ['level', '<', $level]])->update(['level' => $level]);//需不需要 过滤条件'is_buy'=>1
      //var_dump("等级[$level]-" . $count);
    }
  }

  //记录业绩
  protected function fundSalaryLogContent($uid, $profit, $type = 1, $info_id = 0, $info_type = 1, $desc = '', $custom_desc = '[]')
  {
    $addData = [
      'uid'         => $uid,
      'profit'      => $profit,
      'type'        => $type,
      'desc'        => $desc,
      'custom_desc' => $custom_desc,
      'info_id'     => $info_id,
      'info_type'   => $info_type
    ];
    (new FundSalaryLogContent())->save($addData);
  }

  //结算团队分成
  public function settleTeamSharing()
  {
    //跟单的分成
    $sql  = "SELECT
                	sum( profit ) AS sum_profit,
                	uid,
                	type 
                FROM
                	lmq_fund_salary_log_content 
                WHERE
                	is_settle = 2 
                	AND type =1
                    AND info_type = 1
                GROUP BY uid";
    $list = Db::query($sql);
    (new FundSalaryLogContent())->where(['is_settle' => 2, 'type' => 1, 'info_type' => 1])->update(['is_settle' => 1, 'settle_time' => time()]);
    foreach ($list as $value) {
      $user     = Member::where('id', $value['uid'])->find();
      $level    = $user->level;
      $levelSet = FundUserlevel::where('level', $level)->find();
      if (!empty($levelSet) && $user->is_buy == 1) {
        $price = $value['sum_profit'] * ($levelSet->ratio / 100);
        $info  = ":收益业绩[{$value['sum_profit']}]-比例[{$levelSet->ratio}]";
        self::fundSalaryLog($value['uid'], $price, 1, 0, 1, '团队分成' . $info);
      }
    }
    var_dump(count($list));
    //基金的分成
    $sql  = "SELECT
                	sum( profit ) AS sum_profit,
                	uid,
                	type
                FROM
                	lmq_fund_salary_log_content
                WHERE
                	is_settle = 2
                	AND type =1
                    AND info_type = 2
                GROUP BY uid";
    $list = Db::query($sql);
    (new FundSalaryLogContent())->where(['is_settle' => 2, 'type' => 1, 'info_type' => 2])->update(['is_settle' => 1, 'settle_time' => time()]);
    foreach ($list as $value) {
      $user     = Member::where('id', $value['uid'])->find();
      $level    = $user->level;
      $levelSet = FundUserlevel::where('level', $level)->find();
      if (!empty($levelSet) && $user->is_buy == 1) {
        $price = $value['sum_profit'] * ($levelSet->ratio / 100);
        $info  = ":收益业绩[{$value['sum_profit']}]-比例[{$levelSet->ratio}]";
        self::fundSalaryLog($value['uid'], $price, 1, 0, 2, '团队分成' . $info);
      }
    }
    var_dump(count($list));

  }

  //结算周工资
  public function settleWeekSalary()
  {
    $sql  = "SELECT
                	sum( profit ) AS sum_profit,
                	uid,
                	type
                FROM
                	lmq_fund_salary_log_content
                WHERE
                	is_settle = 2
                	AND type = 2
                    AND info_type = 1
                GROUP BY uid";
    $list = Db::query($sql);
    (new FundSalaryLogContent())->where(['is_settle' => 2, 'type' => 2, 'info_type' => 1])->update(['is_settle' => 1, 'settle_time' => time()]);

    foreach ($list as $value) {
      $SalaryConfig = FundSalaryConfig::where([['min_money', '<', $value['sum_profit']]])->where([['max_money', '>', $value['sum_profit']]])->find();
      $user = Member::where('id', $value['uid'])->find();
      if (!empty($SalaryConfig) && $user->is_buy == 1) {
        $price = $value['sum_profit'] * ($SalaryConfig->ratio / 100);
        $info  = ":收益业绩[{$value['sum_profit']}]-比例[{$SalaryConfig->ratio}]";
        self::fundSalaryLog($value['uid'], $price, 2, 0, 1, '周工资' . $info);
      }
    }
    var_dump(count($list));

    $sql  = "SELECT
                	sum( profit ) AS sum_profit,
                	uid,
                	type
                FROM
                	lmq_fund_salary_log_content
                WHERE
                	is_settle = 2
                	AND type = 2
                    AND info_type = 2
                GROUP BY uid";
    $list = Db::query($sql);
    (new FundSalaryLogContent())->where(['is_settle' => 2, 'type' => 2, 'info_type' => 2])->update(['is_settle' => 1, 'settle_time' => time()]);

    foreach ($list as $value) {
      $SalaryConfig = FundSalaryConfig::where([['min_money', '<', $value['sum_profit']]])->where([['max_money', '>', $value['sum_profit']]])->find();
      $user = Member::where('id', $value['uid'])->find();
      if (!empty($SalaryConfig) && $user->is_buy == 1) {
        $price = $value['sum_profit'] * ($SalaryConfig->ratio / 100);
        $info  = ":收益业绩[{$value['sum_profit']}]-比例[{$SalaryConfig->ratio}]";
        self::fundSalaryLog($value['uid'], $price, 2, 0, 2, '周工资' . $info);
      }
    }
    var_dump(count($list));

  }

  //记录奖金+添加余额
  protected function fundSalaryLog($uid, $price, $type, $info_id = 0, $info_type = 1, $desc = '', $custom_desc = '[]')
  {
    if ($price <= 0) {
      return;
    }
    $addData = [
      'uid'         => $uid,
      'price'       => $price,
      'type'        => $type,
      'desc'        => $desc,
      'custom_desc' => $custom_desc,
      'info_id'     => $info_id,
      'info_type'   => $info_type,
      'is_settle'   => 1,
      'settle_time' => time()
    ];
    (new FundSalaryLog())->save($addData);

    //添加奖金
    $mType = 93;
    if ($type == 2) {
      $mType = 92;
    }
    $user_balance = Money::getMoney($uid);
    $info         = $desc;
    $money        = $price * 100;
    Money::where('mid', $uid)->setInc('account', $money);
    $account = $user_balance['account'] + $money;
    $obj     = ['affect' => $money, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $user_balance['activity_account'], 'sn' => ''];
    Record::saveData($uid, $money, $account, $mType, $info, '', '', $obj);

  }

}