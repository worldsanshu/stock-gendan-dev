<?php
namespace app\money\model;

use app\admin\model\Attachment as AttachmentModel;
use think\Model;

class Payment extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MONEY_PAYMENT__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    // 设置json类型字段
    protected $json = ['extra'];
 
    // 设置JSON数据返回数组
    protected $jsonAssoc = true;
    
    public static $payType = array(
        'alipay' => '支付宝',
        'wechatpay' => '微信支付', 
        'bank' => '银联支付', 
        'currency' => '数字货币',
        'kdpay' => 'kdpay',
        'cbpay' => 'CBpay',
        'jdpay' => 'JDpay',
        'mpay' => 'Mpay',
//        'bdtpay' => '八达通',
        'usdtpay' => 'AApay',
        'topay' => 'TOpay钱包',
        'okpay' => 'OKpay钱包',
        'wdpay'=>'豌豆支付',
        'bfpay'=>'百冯支付',
//        'hzpay'=>'华中支付',
        'sxypay'=>'守信易支付',
    );
    public static $payTypeLogo = array(
        'alipay' => '/static/payment/logo/zhifubao-pay.png',
        'wechatpay' => '/static/payment/logo/weixin-pay.png',
        'bank' => '/static/payment/logo/union-pay.png',
        'currency' => '/static/payment/logo/xn.png',
        'kdpay' => '/static/payment/logo/kd.png',
        'cbpay' => '/static/payment/logo/cb.png',
        'jdpay' => '/static/payment/logo/jd.png',
        'mpay' => '/static/payment/logo/mpay.png',
        'bdtpay' => '/static/payment/logo/aa.png',
        'usdtpay' => '/static/payment/logo/aa.png',
        'topay' => '/static/payment/logo/to.png',
        'okpay' => '/static/payment/logo/ok.png',
        'wdpay'=>'/static/payment/logo/wdpay.png',
        'bfpay'=>'/static/payment/logo/bfpay.png',
        'hzpay'=>'/static/payment/logo/hzpay.png',
        'sxypay'=>'/static/payment/logo/sxypay.png',
    );
    //线上支付的
    public static $online = array(
//        隐藏华中和八达通
//        'kdpay', 'cbpay', 'jdpay', 'mpay', 'bdtpay', 'usdtpay', 'topay', 'okpay','wdpay','bfpay','hzpay','sxypay'
        'kdpay', 'cbpay', 'jdpay', 'mpay', 'usdtpay', 'topay', 'okpay','wdpay','bfpay','sxypay'
    );
    //线上提现的
    public static $online_sithdrawal = array(
        //        隐藏华中和八达通
//        'kdpay', 'jdpay', 'topay', 'okpay','mpay','cbpay','bdtpay'
        'kdpay', 'jdpay', 'topay', 'okpay','mpay','cbpay'
    );
    /**
     * 转意
     * @param $cate
     * @return string
     */
    public static function translateLogo($type){
        $domain = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];
        return $domain.self::$payTypeLogo[$type]??'';
    }
    public static function translate($type){
        return self::$payType[$type]??'';
    }

    public function setCreateTimeAttr()
    {
        return time();
    }

    public static function getAll($map = [], $order = '')
    {
        $data_list = self::where($map)
          ->order($order)
          ->paginate();
        return $data_list;
    }

    public function getQrcodeUrlAttr($value)
    {
        $domain = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];
        $path = AttachmentModel::where('id', $value)->value('path');
        return $domain . '/' . $path;
    }

    public function getLogoUrlAttr($value)
    {
        $domain = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];
        $path = AttachmentModel::where('id', $value)->value('path');
        return $domain . '/' . $path;
    }
}

?>
