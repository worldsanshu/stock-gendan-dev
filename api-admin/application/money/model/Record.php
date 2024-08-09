<?php
namespace app\money\model;

use app\statistics\model\ProfitLoss as ProfitLossModel;
use think\model;
use think\Db;

class Record extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MONEY_RECORD__';

    protected function setCreateIpAttr($value)
    {
        return get_client_ip(1);
    }

    protected $type = [
      '1' => '充值成功',
      '2' => '提现处理中',
      '3' => '提现成功',
      '4' => '提现失败',
      '5' => '撤销提现',
      '6' => '提现退回',
      '7' => '追加保证金',
      '8' => '冻结保证金',
      '9' => '返还保证金',
      '10' => '邀请人推广返佣',
      '11' => '代理商佣金入账',
      '12' => '代理商分成入账',
      '13' => '提取佣金',
      '14' => '提取分成',
      '15' => '终止配资',
      '16' => '扣配资管理费',
      '17' => '扣递延费',
      '18' => '系统自动转入',
      '19' => '管理员操作',
      '20' => '配资结算',
      '21' => '配资审核不通过',
      '22' => '配资审核通过',
      '23' => '申请配资续期',
      '24' => '扩大配资审核通过',
      '25' => '扩大配资审核未通过',
      '26' => '追加保证金审核通过',
      '27' => '追加保证金审核未通过',
      '28' => '配资续期审核通过',
      '29' => '配资续期审核未通过',
      '30' => '配资提前终止审核通过',
      '31' => '配资提前终止审核未通过',
      '32' => '按月配资手续费自动扣款',
      '33' => '配资-冻结金额',
      '34' => '自动续期-扣除金额',
      '35' => '系统存入',
      '36' => '新手任务',
      '37' => '手动签到',
      '85' => '提取盈利',
      '86' => '基金买入',
      '87' => '基金卖出',
      '88' => '基金收益',
      '89' => '卖出基金到账',
      '90' => '分销佣金',
      '91' => '合伙人补贴',
      '92' => '合伙人周工资',
      '93' => '合伙人团队分成',
      '94' => '优投追加投资金额' ,
      '95' => '优投投资',
      '96' => '结算优投',
      '97' => '优投冻结',
      '98' => '活动金抵扣佣金',
      '99' => '优投解冻',
      '104' => '余额宝买入',
      '105' => '余额宝冻结金',
      '106' => '余额宝返还本金',
      '107' => '余额宝利息',
      '108' => '结算跟投本金',
      '109' => '提取优投盈利',
      '110' => '彩金',
      '111' => '系统存入活动金',
      '112' => '任务领取',
      '113' => '优投追加金额退回',
      '114' => '优投追加金额冻结',
      '115' => '优投追加金额解冻',
    ];

    public function getTypeAttr($value)
    {
        return $this->type[$value];
    }

    public function getTypeAll()
    {
        return $this->type;
    }

    /**
     * 添加资金记录
     * @param  $mid [description] 会员id
     * @param  $affect [description] 影响金额
     * @param  $type [description] 资金类型
     * @param  $account [description] 剩余可用资金
     * @param  $info [description] 文字描述
     * @param  $remarks [description] 评论  识别
     * @param  $interest [description] 扩大利息
     * @param  $obj [description] 数据内容  affect变化的账户余额  account剩余的账户余额   affect_activity变化的活动金  剩余的活动金  sn订单号
     */
    public static function saveData($mid, $affect, $account, $type, $info = '', $remarks = '', $interest = 0, $obj = [])
    {
        $for_user = Db::name('member')->where('id', $mid)->value('agent_far');
        if ($obj) {
            #后续开发的功能  后续慢慢取消 $affect和$account参数
            $record['sn'] = $obj['sn'];
            $record['affect'] = $obj['affect'];
            $record['account'] = $obj['account'];
            $record['affect_activity'] = $obj['affect_activity'];
            $record['activity_account'] = $obj['activity_account'];
//            如果设有手续费
            if(isset($obj['fee'])){
                $record['fee'] = $obj['fee'];
            }

        } else {
            $record['affect'] = $affect;
            $record['account'] = $account ?: 0;
        }



        $record['mid'] = $mid;
        $record['type'] = $type;
        $record['info'] = $info;
        $record['for_user'] = $for_user;
        $record['create_time'] = time();
        $record['create_ip'] = get_client_ip(1);
        $res = self::create($record); // 资金记录
        //资金操作记录表
        if ($type == 34) {
            $data = [];
            $data['create_time'] = time();
            $data['renewal_interest'] = $affect / 100;
            ProfitLossModel::appendProfitLoss($data);
        }
        //扩大利息
        if ($remarks == 'kdlx' && $interest > 0) {
            $data = [];
            $data['create_time'] = time();
            $data['expand_interest'] = $interest;
            ProfitLossModel::appendProfitLoss($data);
        }
        //申请利息
        if ($remarks == 'sqlx' && $interest > 0) {
            $data = [];
            $data['create_time'] = time();
            $data['apply_interest'] = $interest;
            ProfitLossModel::appendProfitLoss($data);
        }

        return true;
    }

    public static function getAll($map = [], $order = '')
    {
        $data_list = self::view('money_record r', true)
          ->view('member m', 'mobile, name,id_card,email,create_time as m_time', 'm.id=r.mid', 'left')
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              $item->account = money_convert($item->account);
              $item->affect = money_convert($item->affect);
              $item->affect_activity = money_convert($item->affect_activity);
              $item->activity_account = money_convert($item->activity_account);
              $item->type = $item->type;
          });
        return $data_list;
    }
}

?>
