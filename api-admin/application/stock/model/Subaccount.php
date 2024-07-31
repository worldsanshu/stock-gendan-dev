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
namespace app\stock\model;

use app\market\model\Grid;
use app\stock\model\Account as AccountModel;
use app\stock\model\Borrow as BorrowModel;
use think\Db;
use think\helper\Hash;
use think\Model as ThinkModel;

/**
 * 证券信息模型
 * @package app\stock\model
 */
class Subaccount extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBACCOUNT__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }

    //账户模式获取器
    public function getRelationTypeAttr($value)
    {
        $status = ['0' => '模拟账户', '1' => '实盘账户'];
        return $status[$value];
    }
    //账户模式设置器
    /*public function setRelationTypeAttr()
    {
        return self::getData('relation_type');
    }*/
    //关联子账户资金表定义
    public function subaccountMoney()
    {
        return $this->hasOne('SubaccountMoney', 'stock_subaccount_id');
    }

    //关联子账户资金表定义
    public function subaccountRisk()
    {
        return $this->hasOne('SubaccountRisk', 'stock_subaccount_id');
    }

    /**
     * 获取证券账户列表
     * @param array $map 筛选条件
     * @param array $order 排序
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getList($map = [], $order = [])
    {
        if (empty($order)) {
            $order = 'create_time desc';
        };
        $data_list = self::view('stock_subaccount', true)
          ->view("stock_borrow b", 'status st', 'stock_subaccount.id=b.stock_subaccount_id', 'left')
          ->where($map)
          ->where("b.status <>2 || stock_subaccount.status = ''")
          ->order($order)
          ->paginate();
#echo self::view('stock_subaccount', true)->getLastSql();
        //dump($data_list);exit;
        return $data_list;
    }

    /*
     * 根据证券账户ID获取证券账户列表
     * @param  $id 证券账户ID
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public static function getSubaccountById($id = '')
    {
        $data_list = self::where('id', $id)->find();
        $data_list['account_id'] = AccountModel::getBrokerNameByID($data_list['account_id']);
        return $data_list;
    }

    /**
     * 获取实盘证券账户类型列表(下拉选择时使用)
     * @param
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getAccountList()
    {
        $list = [];
        $where['status'] = 1;
        //$where['broker'] = ['>',0];//排除模拟账户
        $data_list = Db::name('stock_account')->where($where)->column(true, 'id');
        if (!is_null($data_list)) {
            foreach ($data_list as $v) {
                $list[$v['id']] = $v['stockjobber'];
            }
        } else {
            $list = [];
        }
        return $list;
    }

    /**
     * 根据代理商ID获取代理商信息
     * @param int $map 代理商ID
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function getAgentName($id)
    {
        $where['id'] = $id;
        $data_list = Db::name('stock_agent')->field('name')->where($where)->find();
        if (isset($data_list['user_name'])) {
            return $data_list['user_name'];
        } else {
            return false;
        }
    }

    // 获取角色列表
    public static function getRole()
    {
        $result = [];
        // 获取角色下存在对应会员的列表
        $roles = self::view('admin_role r', 'id,pid,name')
          ->view("admin_user u", 'username', 'u.role = r.id', 'right')
          ->where('r.status', 1)
          ->select();
        if (!is_null($roles)) {
            foreach ($roles as $role) {
                $result[$role['id']] = $role['name'];
            }
        } else {
            $result = [];
        }
        return $result;
    }

    /*
     * 查看子账户下的自选股票记录
     * @param $id  子账户ID
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public static function getSelfByID($map = [], $order = [], $id = '')
    {
        $data_list = self::view('stock_subaccount_self', true)
          ->view("stock_subaccount", 'sub_account', 'stock_subaccount.id=stock_subaccount_self.sub_id', 'left')
          ->where('sub_id', $id)
          ->where($map)
          ->order($order)
          ->paginate();
        return $data_list;
    }

    /*
     * 获取模拟证券账户列表(下拉选择时使用)
     * @param $type 0:模拟盘类型 1：实盘类型
     * @param $init_money 股票初始资金
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public static function getSelaccountList($type, $init_money)
    {
        $listType = [];
        $where['status'] = 1;
        $num = get_spapi();
        if ($num != '1') {
            $where['type'] = $type;//账户类型 $type=0
        }
        $idlist = Db::name('stock_account')->field('id')->where($where)->select();
        if (!empty($idlist)) {
            $list = [];
            foreach ($idlist as $k => $v) {
                $list[$v['id']] = $v['id'];
            }
            $param[] = ['account_id','in', $list];
            $param[] = ['status','=',0];
            $data_list = Db::name('stock_subaccount')->field(true)->where($param)->select();
            /////////////////////////////////////筛选可用的实盘账户开始///////////
            if (!empty($data_list)) {
                foreach ($data_list as $key => $value) {
                    if ($value['relation_type'] === 1) {
                        //获得对应实盘账户已用的配资总金额
                        $total = BorrowModel::getRealBorrowmoney($value['account_id']);
                        //获取实盘账户资金信息 （可用余额，总资产，总市值等）
                        $account = [];
                        if (config('web_real_api') == 1) {
                            $account = gs('queryTradeData', [$value['account_id'], 1]);//更新查询资金信息到数据表
                        }
                        if (config('web_real_api') == 2) {
                            $res = json_decode(Grid::grid_funds($value['account_id']), true);
                            $account['1']['1'] = null;
                            $account['1']['2'] = $res["TotalAvailableAmount"];
                            $account['1']['3'] = null;
                            $account['1']['4'] = $res["TotalAvailableAmount"];
                            $account['1']['5'] = $res["TotalMarketValue"];
                            $account['1']['6'] = $res["TotalAssets"];
                            $account['1']['7'] = null;
                            $account['1']['8'] = null;
                        }
                        //判断当前配资额是否大于实盘账户可用余额，如果大于则此子账户不可用，排除此子账户
                        if ($init_money > $account[1][2]) {
                            //lack为资金不足标志
                            $data_list[$key]["lack"] = $data_list[$key]["sub_account"];
                            //unset($data_list[$key]);
                        } elseif (($total / 100 + $init_money) > $account[1][6]) {
                            //判断当前配资额+实盘账户对应uid配资金额是否大于实盘账户总资产，如果大于则此子账户不可用，排除此子账户
                            $data_list[$key]["lack"] = $data_list[$key]["sub_account"];
                        }
                    }
                }
            }
            /////////////////////////////////////筛选可用的实盘账户结束///////////
            if (!is_null($data_list)) {
                $listType['lack'] = '';
                foreach ($data_list as $key => $value) {
                    if (isset($value['lack'])) {
                        $listType['lack'] = $listType['lack'] . $value['lack'] . '/';
                    } else {
                        $listType[$value['id']] = $value['sub_account'];
                    }
                }
                if ($listType['lack'] != '') {
                    $listType['lack'] = '注意:' . $listType['lack'] . '对应券商账户资金不足未列入选择';
                }
            }
        }
        return $listType;
    }

    //返回单个子账号资金
    public static function getSubaccountMoneyONE($map)
    {
        $webtype = webType();
        $where = [];
        if ($webtype == 2) {
            $where['t.for_user'] = for_user();
        }
        $data_list = self::view('stock_subaccount s', true)
          ->view('stock_subaccount_money m', '*', 'm.stock_subaccount_id=s.id')
          ->where($map)
          ->paginate(1)
          ->each(function ($item) {
              $item['deposit_money'] = money_convert($item['deposit_money']);
              $item['borrow_money'] = money_convert($item['borrow_money']);
              $item['avail'] = money_convert($item['avail']);
              $item['return_money'] = money_convert($item['return_money']);
              #   $item['available_amount'] = round(subtixian_new($item['id']), 2);
              $item['available_amount'] = round($item['available_amount'], 2);
              #money_convert($item['available_amount']);
              $item['freeze_amount'] = money_convert($item['freeze_amount']);
              $item['market'] = money_convert($item['market']);
              #  $item['market'] = money_convert(lifting($item['stock_subaccount_id'])['market']);
              $item['stock_addfinancing'] = money_convert($item['stock_addfinancing']);
              $item['stock_addmoney'] = money_convert($item['stock_addmoney']);
              $item['stock_drawprofit'] = money_convert($item['stock_drawprofit']);
          });
        return $data_list;
    }

    //返回子账号资金
    public static function getSubaccountMoney($map, $order, $listRows)
    {
        $a = Db::name("stock_subaccount_money")->select();
        $data_list = self::view('stock_subaccount s', true)
          ->view('member m1', 'mobile,email', 'm1.id=s.uid')
          ->view('stock_subaccount_money m', '*', 'm.stock_subaccount_id=s.id')
          ->where($map)
          ->order($order)
          ->paginate($listRows)
          ->each(function ($item) {
              $item['deposit_money'] = money_convert($item['deposit_money']);
              $item['borrow_money'] = money_convert($item['borrow_money']);
              $item['avail'] = money_convert($item['avail']);
//                $item['return_money'] = money_convert($item['return_money']);
              //新算法  2023-7-14 启用
//                $uaa=subtixian_new($item['id']);
//                $item['return_money'] = round(tixian($item['id'],true)/100, 2);
//                $item['available_amount'] = round($uaa, 2);
              # $item['available_amount'] = round( $item['available_amount'], 2);
              #money_convert($item['available_amount']);
              #  $item['market'] = money_convert(lifting($item['stock_subaccount_id'])['market']);
              $item['freeze_amount'] = money_convert($item['freeze_amount']);
              $item['return_money'] = money_convert($item['return_money']);
              $item['market'] = money_convert($item['market']);
              $item['available_amount'] = money_convert($item['available_amount']);
              $item['stock_addfinancing'] = money_convert($item['stock_addfinancing']);
              $item['stock_addmoney'] = money_convert($item['stock_addmoney']);
              $item['stock_drawprofit'] = money_convert($item['stock_drawprofit']);
          });
        return $data_list;
    }

    //返回子账号资金报表明细
    public static function getSubaccountMoneyLog()
    {
        $day_start_time = strtotime(date('Y-m-d'));//当天的起始时间
        $subaccount_id_array = Db('stock_subaccount_money_log')->where('create_time', $day_start_time)->column('sub_account_id');//子账户id集合
        $data_list = self::view('stock_subaccount s', true)
          ->view('member m1', 'mobile,email,agent_far,name', 'm1.id=s.uid')
          ->view('stock_subaccount_money m', '*', 'm.stock_subaccount_id=s.id')->select();
        $sql = "REPLACE INTO `lmq_stock_subaccount_money_log` (`uid`,`sub_account_id`,`sub_account`,`mobile`,`deposit_money`,`operate_money`,`profit_loss`,`create_time`,`email`,`name`,`contract_num`,`agent_name`,`position`,`withdrawal`,`recharge`,`activity_money`) values ";
        if (!$data_list) {
            return;
        }
        foreach ($data_list as $value) {
            $profit_loss = tixian($value['id']); //盈亏金额
            if (!in_array($value['id'], $subaccount_id_array)) {
                $contract_num = Db('stock_borrow')->where('stock_subaccount_id', $value['id'])->count();
                $agent_name = Db('member')->where('id', $value['agent_far'])->count();
                $position = Db('stock_position')->where('sub_id', $value['id'])->count();
                $withdrawal_money = Db('money_withdraw')->where('mid', $value['uid'])->where('status', 1)->sum('money');
                $withdrawal = money_convert($withdrawal_money);
                $recharge = Db('money_recharge')->where('mid', $value['uid'])->where('status', 1)->sum('money');
                $recharge = money_convert($recharge);
                $activity_money = Db('money')->where('mid', $value['uid'])->sum('activity_account');
                $activity_money = money_convert($activity_money);
                $is_add = 1;
                $sql .= "('" . $value['uid'] . "','" . $value['id'] . "','" . $value['sub_account'] . "','" . $value['mobile'] . "','" . $value['deposit_money'] . "','" . $value['borrow_money'] . "','" . $profit_loss . "','" . $day_start_time . "','" . $value['email'] . "','" . $value['name'] . "','" . $contract_num . "','" . $agent_name . "','" . $position . "','" . $withdrawal . "','" . $recharge . "','" . $activity_money . "'),";
            }
        }
        if ($is_add == 1) {
            $sql = substr($sql, 0, strlen($sql) - 1);
            Db::query($sql);
        }
        echo 'success';
        exit;
    }

    //返回子账号盈亏情况
    public static function getSubaccountprofitLossRecord($map, $order, $listRows)
    {
        $data_list = self::view('stock_submoney_profit_loss_record r', true)
          ->view("lmq_stock_subaccount s", 'sub_account', 's.id=r.sub_id', 'left')
          ->where($map)
          ->order($order)
          ->paginate($listRows)
          ->each(function ($item) {
              $item['return_money'] = money_convert($item['return_money']);
              $item['affect'] = money_convert($item['affect']);
          });
        return $data_list;
    }
}