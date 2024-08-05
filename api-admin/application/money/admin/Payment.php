<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------
namespace app\money\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\money\model\Payment as PaymentModel;
use util\HttpClient;

/**
 * 支付方式管理控制器
 * @package app\payment\admin
 */
class Payment extends Admin
{
    /**
     * 支付方式列表
     * @return mixed
     * @author 张继立 <404851763@qq.com>
     */
    public function index()
    {
//        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'sort asc ,id desc';
        // 数据列表
        $data_list = PaymentModel::where($map)->alias('a')->order($order)->paginate();
        foreach ($data_list as $v) {

            $v['money'] = "<p>" . $v['min_money'] . "</p><p>" . $v['max_money'] . "</p>";

            if ($v['support_type'] == '1') {
                $support_type = '充值';
            } elseif ($v['support_type'] == '2') {
                $support_type = '提现';
            } elseif ($v['support_type'] == '3') {
                $support_type = '提现+充值';
            }
//          echo  PaymentModel::$payTypeLogo[$v['type']];
//            $Logo=$v['logo']?$v['logo']:PaymentModel::$payTypeLogo[$v['type']];
            $v['name'] = "<p>" . $v['name'] . "</p><p>" . $support_type . "</p>";
//            $logourl   = $v['logo'] ? get_file_path($v['logo']) : PaymentModel::$payTypeLogo[$v['type']];
            $logourl = PaymentModel::translateLogo($v['type']);
            $v['logo_url2'] = '<img src="' . $logourl . '" style="width: 50px;height:50px;">';
        }

        $btn_getRecharge = ['title' => '充值记录', 'icon' => 'fa fa-fw fa-dollar', 'href' => url('recharge/index', ['payment_id' => '__id__'])];
        return ZBuilder::make('table')
            ->addColumns([ // 批量添加数据列
                           ['logo_url2', 'Logo'],
                           ['name', '名称/功能'],
                           //                     ['type', '支付方式', PaymentModel::$payType],

                           ['account_information', '账号信息'],
                           //                           ['address', '充值地址'],


                           ['payee', '收款人'],
                           //                           ['card_number', '收款账号'],
                           //                           ['bank_name', '所属银行'],

                           ['money', '充值最小/最大'],
                           ['exchange_rate', '汇率'],
                           ['sort', '排序', 'text.edit'],
//                           ['is_auto', '自动到账', 'switch'],
                           ['status', '状态', 'switch'],
                           //                           ['create_time', '时间', 'datetime'],
                           ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addRightButton('custom', $btn_getRecharge)
//            ->addOrder('id,name,type,address,qrcode,min_money,max_money,exchange_rate,status,create_time')
            ->setRowList($data_list)
            ->hideCheckbox()
            ->setColumnWidth('right_button', 50)
            ->setColumnWidth('money,name', 40)
            ->setColumnWidth('exchange_rate,logo_url2,sort,is_auto,status', 35)
            ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            // 验证
//            $result = $this->validate($data, 'language');
//            if(true !== $result) $this->error($result);
            if (in_array($data['type'], PaymentModel::$online)) {//是否是线上打款的
                $data['is_online'] = 1;
                if ($data['type'] == 'wdpay') {
                    $data['extra'] = [
                        ["max" => 200, "min" => 100, "title" => "微信", "value" => 1001, "multiple" => 100],
                        ["max" => 15000, "min" => 200, "title" => "支付宝", "value" => 2008, "multiple" => 1],
                    ];
                }
                if ($data['type'] == 'bfpay') {
                    $data['extra'] = [
                        ["max" => 15000, "min" => 1, "title" => "网银支付", "value" => "bank", "multiple" => 1],
                        ["max" => 15000, "min" => 1, "title" => "支付宝", "value" => "alipay", "multiple" => 1],
                    ];
                }
            } else {
                $data['is_online'] = 0;
            }

            if (in_array($data['type'], PaymentModel::$online)) {//是否是线上打款的
                $data['is_online'] = 1;
                if ($data['type'] == 'wdpay') {
                    $data['extra'] = [
                        ["max" => 200, "min" => 100, "title" => "微信", "value" => 1001, "multiple" => 100],
                        ["max" => 15000, "min" => 200, "title" => "支付宝", "value" => 2008, "multiple" => 1],
                    ];
                }
                if ($data['type'] == 'bfpay') {
                    $data['extra'] = [
                        ["max" => 15000, "min" => 1, "title" => "网银支付", "value" => "bank", "multiple" => 1],
                        ["max" => 15000, "min" => 1, "title" => "支付宝", "value" => "alipay", "multiple" => 1],
                    ];
                }
            } else {
                $data['is_online'] = 0;
            }
            if ($payment = PaymentModel::create($data)) {
                // 记录行为
                action_log('payment_add', 'cms_link', $payment['id'], UID, $data['name']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        //$pay_type_list = Db::name('pay_type')->column('name', 'type');
        $pay_type_list    = PaymentModel::$payType;
        $ip               = HttpClient::get(env('PAY.notify_domain') . '/apicom/pay_notify/getIp');//出网ip
        $support_type     = [
            '1' => '仅支持充值',
            '2' => '仅支持提现',
            '3' => '支持充值 + 提现',
        ];
        $withdrawal_param = [
            '1' => '账号/地址',
            '2' => '收款码',
            '3' => '账号/地址 + 收款码',
        ];
        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItem('select', 'type', '请选择支付方式', '请选择支付方式', $pay_type_list)
            ->setPageTips('添加此支付方式，如需用户将能够在提现和充值时选择使用。请确保该支持类型已正确配置', 'danger')
            ->addFormItems([
//            ['radio', 'type', '请选择支付方式', '请选择支付方式', $pay_type_list],
['text', 'name', '名称'],
['text', 'account_information', '账号信息'],
['text', 'address', '充值地址 (数字货币必填)'],
['image', 'qrcode', '二维码（银联支付无需上传，其他支付方式必填）'],
['image', 'logo', 'logo (数字货币必填)'],
['text', 'min_money', '最小充值金额'],
['text', 'max_money', '最大充值金额'],
['text', 'payee', '收款人（银联支付必填）'],
['text', 'card_number', '收款账号（银联支付必填）'],
['text', 'bank_name', '所属银行（银联支付必填）'],
['text', 'exchange_rate', '汇率 (数字货币必填)'],
['text', 'agreement', '协议 (数字货币必填)'],
['text', 'app_id', '商户号 (把这个IP-[' . $ip['data'] . '] 添加到支付商平台IP白名单)'],
['text', 'app_secret', '秘钥（或者Md5Key）'],
['text', 'app_public_key', '公钥'],
['text', 'specific_address', '商户网关API (非必填，需要特定网关才填写)','例：https://qse123jdsz.okpay777.com    ,去掉多余的路径信息'],
['select', 'support_type', '支持类型（用户充值和提现的时候提供选择）', '', $support_type, '1'],
['select', 'withdrawal_param', '提现参数（用户提现的时候需要填写的信息）', '', $withdrawal_param, '1'],
//['radio', 'is_auto', '是否自动到账', '', ['否', '是'], 1],
['radio', 'status', '状态', '', ['禁用', '启用'], 1],
['text', 'sort', '排序', '', 100],
            ])
            ->setTrigger('type', 'alipay,wechatpay,bank', 'qrcode,payee,card_number,bank_name')
            ->setTrigger('type', 'currency', 'address,logo,exchange_rate,agreement')
            ->setTrigger('type', implode(',', PaymentModel::$online), 'app_id,app_secret,app_public_key')
            ->setTrigger('support_type', '2,3', 'withdrawal_param')
            ->fetch();
    }

    /**
     * 编辑
     *
     * @param null $id 链接id
     *
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            // 验证
//            $result = $this->validate($data, 'language');
//            if(true !== $result) $this->error($result);
            if (in_array($data['type'], PaymentModel::$online)) {//是否是线上打款的
                $data['is_online'] = 1;
                if ($data['type'] == 'wdpay') {
                    $data['extra'] = [
                        ["max" => 200, "min" => 100, "title" => "微信", "value" => 1001, "multiple" => 100],
                        ["max" => 15000, "min" => 200, "title" => "支付宝", "value" => 2008, "multiple" => 1]
                    ];
                }
                if ($data['type'] == 'bfpay') {
                    $data['extra'] = [
                        ["max" => 15000, "min" => 1, "title" => "网银支付", "value" => "bank", "multiple" => 1],
                        ["max" => 15000, "min" => 1, "title" => "支付宝", "value" => "alipay", "multiple" => 1],
                    ];
                }
            } else {
                $data['is_online'] = 0;
            }
            if (PaymentModel::update($data)) {
                // 记录行为
                action_log('payment_edit', 'payment', $id, UID, $data['name']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        $info = PaymentModel::get($id);

        //$pay_type_list = Db::name('pay_type')->column('name', 'type');
        $support_type     = [
            '1' => '仅支持充值',
            '2' => '仅支持提现',
            '3' => '支持充值 + 提现',
        ];
        $withdrawal_param = [
            '1' => '账号/地址',
            '2' => '收款码',
            '3' => '账号/地址 + 收款码',
        ];
        $pay_type_list    = PaymentModel::$payType;
        $ip               = HttpClient::get(env('PAY.notify_domain') . '/apicom/pay_notify/getIp');//出网ip
        // 显示编辑页面
        return ZBuilder::make('form')
            ->addFormItem('select', 'type', '请选择支付方式', '请选择支付方式', $pay_type_list)
            ->setPageTips('添加此支付方式，如需用户将能够在提现和充值时选择使用。请确保该支持类型已正确配置', 'danger')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'name', '名称'],
                ['text', 'account_information', '账号信息'],
                ['text', 'address', '充值地址 (数字货币必填)'],
                ['image', 'qrcode', '二维码（银联支付无需上传，其他支付方式必填）'],
                ['image', 'logo', 'logo (数字货币必填)'],
                ['text', 'min_money', '最小充值金额'],
                ['text', 'max_money', '最大充值金额'],
                ['text', 'payee', '收款人（银联支付必填）'],
                ['text', 'card_number', '收款账号（银联支付必填）'],
                ['text', 'bank_name', '所属银行（银联支付必填）'],
                ['text', 'exchange_rate', '汇率 (数字货币必填)'],
                ['text', 'agreement', '协议 (数字货币必填)'],
                ['text', 'app_id', '商户号  (把这个IP-[' . $ip['data'] . '] 添加到支付商平台IP白名单)'],
                ['text', 'app_secret', '秘钥（或者Md5Key）'],
                ['text', 'app_public_key', '商户公钥'],
                ['text', 'specific_address', '商户网关API (非必填，需要特定商户网关才填写)','例：https://qse123jdsz.okpay777.com    ,去掉多余的路径信息'],
                ['text', 'unit', '货币', '如人民币，美元，USDT,欧元，ETH'],
                ['select', 'support_type', '支持类型（用户充值和提现的时候提供选择）', '', $support_type, '1'],
                ['select', 'withdrawal_param', '提现参数（用户提现的时候需要填写的信息）', '', $withdrawal_param, '1'],
//                ['radio', 'is_auto', '是否自动到账', '', ['否', '是'], 1],
                ['radio', 'status', '状态', '', ['禁用', '启用'], 1],
                ['text', 'sort', '排序', '', 100]
            ])
            ->setTrigger('type', 'alipay,wechatpay,bank', 'qrcode,payee,card_number,bank_name')
            ->setTrigger('type', 'currency', 'address,logo,exchange_rate,agreement')
            //->setTrigger('type', 'mpay', 'app_id,app_secret,app_public_key')
            ->setTrigger('type', implode(',', PaymentModel::$online), 'app_id,app_secret,app_public_key')
            ->setTrigger('support_type', '2,3', 'withdrawal_param')
            ->setFormData($info)
            ->fetch();
    }

    /**
     * 删除友情链接
     *
     * @param array $record 行为日志
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用友情链接
     *
     * @param array $record 行为日志
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用
     *
     * @param array $record 行为日志
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置友情链接状态：删除、禁用、启用
     *
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function setStatus($type = '', $record = [])
    {
        $ids          = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $payment_name = PaymentModel::where('id', 'in', $ids)->column('name');
        return parent::setStatus($type, ['payment_' . $type, 'payment', 0, UID, implode('、', $payment_name)]);
    }

    /**
     * 快速编辑
     *
     * @param array $record 行为日志
     *
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $link    = PaymentModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $link . ')，新值：(' . $value . ')';
        return parent::quickEdit(['Payment_edit', 'Payment', $id, UID, $details]);
    }
}