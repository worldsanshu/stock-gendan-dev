<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace app\index\controller;

use app\cms\model\Column as ColumnModel;
use app\money\model\Record;
use think\Db;

/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Indexapi extends Home
{
    /**
     * 初始化方法
     * @author 路人甲乙
     */
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        if (self::ismobile()) {
            $http = empty($_SERVER['HTTP_X_CLIENT_PROTO']) ? 'http://' : $_SERVER['HTTP_X_CLIENT_PROTO'] . '://';
            header('Location:' . $http . $_SERVER["HTTP_HOST"] . '/wap/');
            exit;
        }
        // 默认跳转模块
        if (config('home_default_module') != 'index') {
            $this->redirect(config('home_default_module') . '/index/index');
        }
        // 临时赋值 稍后删除
        $sl = array('url' => "", 'title' => "");
        $this->assign('sl', $sl);
        // 按天配资最低倍率
        $dayRateSetting = config("day_rate");
        asort($dayRateSetting);
        $this->assign("min_day_rate", array_shift($dayRateSetting));
        // 按月配资最大杠杆
        $monthRateSetting = config("month_rate");
        ksort($monthRateSetting);
        $settingKeys = array_keys($monthRateSetting);
        $this->assign("max_month_rate", array_pop($settingKeys));
        // 按天配资最大杠杆
        $dayMaxRateSetting = config("day_rate");
        ksort($dayMaxRateSetting);
        $dayMaxKeys = array_keys($dayMaxRateSetting);
        $tca = Db::query('select * from lmq_cms_column where id=?', [15]);
        $this->assign('tca', $tca[0]["status"]);
        $this->assign('tcon', $tca[0]["content"]);
        $this->assign('boxhide', $tca[0]["hide"]);
        $this->assign("max_day_rate", array_pop($dayMaxKeys));
        $data = array();
        $data['status'] = 200;
        $data['msg'] = '获取成功';
        $data['msg'] = '获取成功';
        return $this->fetch();
    }

    /**
     * 获取滚动图片
     * @author 路人甲乙
     */
    private function getSlider()
    {
        return Db::name('cms_slider')->where('status = 1 and ifwap = 0')->order('sort asc')->select();
    }

    //APP下载模板
    public function appdown()
    {
        return $this->fetch();
    }

    /**
     * 获取统计数据
     * @author 路人甲乙
     */
    private function getStatistics()
    {
        $result = [];
        $result['member'] = Db::name('member')->count('id');//获取累计配资人数
        $result['borrow'] = Db::name('stock_borrow')->where("type", '<>', 6)->sum('init_money');//获取累计实盘资金
        $return_money = Db::name('stock_subaccount_money')->sum('return_money');//获取累计赚取收益
        $result['return_money'] = $return_money < 0 ? 0 : $return_money / 100;
        return $result;
    }

    protected static function ismobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;
        //此条摘自TPM智能切换模板引擎，适合TPM开发
        if (isset ($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
            return true;
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
        //判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
              'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
            );
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取友情链接
     * @author 路人甲乙
     */
    private function getLink()
    {
        return Db::name('cms_link')->where('status', 1)->order('sort')->select();
    }

    /**
     * 获取合作伙伴链接
     * @author 路人甲乙
     */
    private function getCooperate()
    {
        return Db::name('cms_cooperate')->where('status', 1)->order('sort')->select();
    }

    /**
     * 获取文章列表
     * @author 路人甲乙
     */
    public function getArticleList($id = null, $limit = 12, $ar_type = "")
    {
        if ($id === null) $this->error('缺少参数');
        $map = [
          'status' => 1,
          'id' => $id
        ];
        $column = Db::name('cms_column')->where($map)->find();
        if (!$column) $this->error("该栏目不存在(id={$id})");
        $model = Db::name('cms_model')->where('id', $column['model'])->find();
        if ($model['type'] == 2) {
            $cid_all = ColumnModel::getChildsId($id);
            $cid_all[] = (int)$id;
            $map = [
              $model['table'] . '.trash' => 0,
              $model['table'] . '.status' => 1,
              $model['table'] . '.cid' => ['in', $cid_all]
            ];
            $data_list = Db::view($model['table'], true)
              ->view('admin_user', 'username', $model['table'] . '.uid=admin_user.id', 'left')
              ->where($map)
              ->order('create_time desc')
              ->limit($limit)
              ->select();
            $this->assign('model', $column['model']);
        } else {
            $cid_all = ColumnModel::getChildsId($id);
            $cid_all[] = (int)$id;
            $map = [
              'cms_document.trash' => 0,
              'cms_document.status' => 1,
              'cms_document.cid' => ['in', $cid_all]
            ];
            $data_list = Db::view('cms_document', true)
              ->view('admin_user', 'username', 'cms_document.uid=admin_user.id', 'left')
              ->view($model['table'], '*', 'cms_document.id=' . $model['table'] . '.aid', 'left')
              ->where($map)
              ->order('sort desc, create_time desc')
              ->limit($limit)
              ->select();
            $this->assign('model', '');
        }
//        foreach ($data_list as $key=>$val){
//            $data_list[$key]['title_img'] =  Db::name('admin_attachment')->where('id',$val['title_img'])->value('path');
//
//        }
        $this->assign("ar_List{$ar_type}", $data_list);
    }

    //异步
    public function payDebaoNotice()
    {
        $flag = $this->debao_verify($_POST);
        $trade_no = $_POST['trade_no'];//多得宝支付交易号
        $nid = $_POST['order_no'];//获取订单号
        if ($flag) {//判断提交来的数组是否为空
            $done = $this->payDone(1, $nid, $trade_no);
        } else {
            $done = $this->payDone(3, $nid, $trade_no);
        }
        if ($done === true) {
            echo "success";
        } else {
            echo "Verification Error";
        }
    }

    //接收数据验证
    private function debao_verify($arr)
    {
        $merchant_code = $arr["merchant_code"];
        $interface_version = $arr["interface_version"];
        $sign_type = $arr["sign_type"];
        $dinpaySign = base64_decode($arr["sign"]);
        $notify_type = $arr["notify_type"];
        $notify_id = $arr["notify_id"];
        $order_no = $arr["order_no"];
        $order_time = $arr["order_time"];
        $order_amount = $arr["order_amount"];
        $trade_status = $arr["trade_status"];
        $trade_time = $arr["trade_time"];
        $trade_no = $arr["trade_no"];
        $bank_seq_no = $arr["bank_seq_no"];
        $extra_return_param = $arr["extra_return_param"];
        $public_key = config('public_key');
        //$orginal_money = $arr["orginal_money"];
        /////////////////////////////   参数组装  /////////////////////////////////
        /**
         * 除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
         */
        $signStr = "";
        if ($bank_seq_no != "") {
            $signStr = $signStr . "bank_seq_no=" . $bank_seq_no . "&";
        }
        if ($extra_return_param != "") {
            $signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
        }
        $signStr = $signStr . "interface_version=" . $interface_version . "&";
        $signStr = $signStr . "merchant_code=" . $merchant_code . "&";
        $signStr = $signStr . "notify_id=" . $notify_id . "&";
        $signStr = $signStr . "notify_type=" . $notify_type . "&";
        $signStr = $signStr . "order_amount=" . $order_amount . "&";
        $signStr = $signStr . "order_no=" . $order_no . "&";
        $signStr = $signStr . "order_time=" . $order_time . "&";
        //$signStr = $signStr."orginal_money=".$orginal_money."&";
        $signStr = $signStr . "trade_no=" . $trade_no . "&";
        $signStr = $signStr . "trade_status=" . $trade_status . "&";
        $signStr = $signStr . "trade_time=" . $trade_time;
        //echo $signStr;
        /////////////////////////////   RSA-S验证  /////////////////////////////////
        $dinpay_public_key = openssl_get_publickey($public_key);
        $flag = openssl_verify($signStr, $dinpaySign, $dinpay_public_key, OPENSSL_ALGO_MD5);
        return $flag;
    }

    private function payDone($status, $nid, $oid)
    {
        $done = false;
        if ($this->locked) return false;
        $this->locked = true;
        $vo = Db::name('money_recharge')->field('mid,money,fee,status')->where("order_no='{$nid}'")->find();
        $vo1 = Db::name('money')->field('account')->where("mid='{$vo['mid']}'")->find();
        switch ($status) {
            case 1:
                $updata['status'] = $status;
                //$updata['tran_id'] = text($oid);
                if ($vo['status'] != 0 || !is_array($vo)) return;
                $xid = Db::name('money_recharge')->where("mid={$vo['mid']} AND order_no='{$nid}'")->update($updata);
                $tmoney = floatval($vo['money'] - $vo['fee']);
                $account = $vo1['account'] + $tmoney;
                $updata1['account'] = $account;
                $nn = false;
                if ($xid) $nn = Db::name('money')->where("mid={$vo['mid']}")->update($updata1);
                $record = new Record();
                if ($xid && $nn) {
                    $obj = ['affect' => $tmoney, 'account' => $account, 'affect_activity' => 0, 'activity_account' => $vo1['activity_account'], 'sn' => ''];//更新成功才充值,避免重复充值
                    $newid = $record->saveData($vo['mid'], $tmoney, $account, 1, '充值单号：' . $nid, '', '', $obj);
//                    $newid = $record->saveData($vo['mid'], $tmoney,$account,1,'充值单号：'.$nid);//更新成功才充值,避免重复充值
                }
                break;
            case 2:
                $updata['status'] = $status;
                //$updata['tran_id'] = text($oid);
                $xid = Db::name('money_recharge')->where("mid={$vo['mid']} AND order_no='{$nid}'")->update($updata);
                break;
            case 3:
                $updata['status'] = $status;
                $xid = Db::name('money_recharge')->where("mid={$vo['mid']} AND order_no='{$nid}'")->update($updata);
                break;
        }
        if ($status > 0) {
            if ($xid) $done = true;
        }
        $this->locked = false;
        return $done;
    }
}
