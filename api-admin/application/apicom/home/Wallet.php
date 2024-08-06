<?php

namespace app\apicom\home;

use app\member\home\Common;
use app\money\model\Payment as PaymentModel;
use think\Db;

/**
 * 前端 钱包管理
 *
 */
class Wallet extends Common
{
    /**
     *Desc: 获取用户银行卡、钱包列表
     * @return
     *author:oszpac
     *date:2023-05-31
     */
    public function index()
    {
        $bank_list = Db::name('member_bank')
          ->where(['mid' => MID, 'is_delete' => 0])
          ->field('id,card,bank')
          ->select();
        $bank = config('web_bank');

        foreach ($bank_list as $k => $v) {
            $bank_list[$k]['card'] = '****' . substr($v['card'], -4);
            $bank_list[$k]['bank_name'] = $bank[$v['bank']];
        }
        $wallet_list = Db::name('wallet')
          ->alias('w')
          ->join('money_payment p ', 'w.payment_id = p.id', 'LEFT')
          ->join('admin_attachment tt', 'p.logo = tt.id', 'LEFT')
          ->field('w.id,p.name,w.alias,w.address,p.agreement,tt.path as logo,p.type,w.bank_code,w.bank_number,w.bank_owner,p.exchange_rate')
          ->where(['w.mid' => MID, 'w.is_delete' => 0, 'p.status' => 1])
          ->select();
        foreach ($wallet_list as $k => $v) {
//            $wallet_list[$k]['logo'] = 'http://' . request()->host() . '/' . $v['logo'];
            $wallet_list[$k]['logo'] = PaymentModel::translateLogo($v['type']);
        }
        $data = [
          'bank_list' => $bank_list,
          'wallet_list' => $wallet_list
        ];
        ajaxmsg('获取成功', 1, $data, true, ['msgCode' => 'L0014']);
    }

    public function getBankList()
    {
        $list = [];
        $bank_list = Db::name('member_bank')
          ->where(['mid' => MID, 'is_delete' => 0])
          ->field('id,card,bank')
          ->select();
        foreach ($bank_list as $k => $v) {
            $v['card'] = '****' . substr($v['card'], -4);
            $v['type'] = 0;
            $list[] = $v;
        }
        $wallet_list = Db::name('wallet')
          ->alias('w')
          ->join('money_payment p ', 'w.payment_id = p.id')
          ->field('w.id,p.name,w.alias,w.address,p.agreement')
          ->where(['w.mid' => MID, 'w.is_delete' => 0])
          ->select();
        foreach ($wallet_list as $v) {
            $v['type'] = 1;
            $list[] = $v;
        }
        ajaxmsg('获取成功', 1, $list, true, ['msgCode' => 'L0014']);
    }

    /**
     *Desc: 获取币种列表
     * @return void
     *author:oszpac
     *date:2023-05-31
     */
    public function get_currency_list()
    {
        $map = [
          'type' => 'currency',
          'status' => 1
        ];
        $list = Db::name('money_payment')
          ->where($map)
          ->field('id ,name')
          ->select();
        ajaxmsg('', 1, $list);
    }

    /**
     *Desc: 保存用户钱包
     * @return void
     *author:oszpac
     *date:2023-05-31
     */
    public function save()
    {
        $post = input();
        $payment_id = $post['id'];
        $address = $post['address'];
        $alias = $post['alias'];
        $payment_code = $post['payment_code'];
        $bank_code = $post['bank_code'];
        $bank_number = $post['bank_number'];
        $bank_owner = $post['bank_owner'];
        if (empty($payment_id)) {
            ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
        }
        $id_auth = Db('member')->where(['id' => MID])->value('id_auth');
        if ($id_auth !== 1) {
            ajaxmsg('您还没有实名认证', 0, '', true, ['msgCode' => 'L0074']);
        }

        //校验是否重复
        $map = [
          'mid' => MID,
          'payment_id' => $payment_id,
          'address' => $address,
        ];
        $wallet_id = Db::name('wallet')
          ->where($map)
          ->value('id');
        if ($wallet_id)
            ajaxmsg('当前提现地址已添加，请不要重复操作', 0, '', true, ['msgCode' => 'L0157']);
        $data = [];
        $data['mid'] = MID;
        $data['payment_id'] = $payment_id;
        $data['alias'] = $alias;
        $data['address'] = $address;
        $data['payment_code'] = $payment_code;
        $data['bank_code'] = $bank_code;
        $data['bank_number'] = $bank_number;
        $data['bank_owner'] = $bank_owner;
        $data['create_time'] = time();
        $ret = Db::name('wallet')->insert($data);
        if ($ret) {
            ajaxmsg('添加成功', 1, '', true, ['msgCode' => 'L0100']);
        } else {
            ajaxmsg('添加失败', 0, '', true, ['msgCode' => 'L0101']);
        }
    }

    /**
     *Desc: 删除银行卡 、钱包
     * @return void
     *author:oszpac
     *date:2023-06-01
     */
    public function delete()
    {
        $id = input('id', '0');
        $type = input('type', '0');
        if (empty($id)) {
            ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
        }
        if ($type == '0') {
            $map = [
              'id' => $id,
              'mid' => MID
            ];
//            $ret = Db::name('member_bank')
//              ->where($map)
//              ->update(['is_delete' => 1]);
            $ret = Db::name('member_bank')
                ->where($map)
                ->delete();
            if ($ret)
                ajaxmsg('删除成功', 1, '', true, ['msgCode' => 'L0103']);
            else
                ajaxmsg('删除成功', 0, '', true, ['msgCode' => 'L0102']);
        }
        if ($type == '1') {
            $map = [
              'id' => $id,
              'mid' => MID
            ];
//            $ret = Db::name('wallet')
//              ->where($map)
//              ->update(['is_delete' => 1]);
            $ret = Db::name('wallet')
                ->where($map)
                ->delete();
            if ($ret)
                ajaxmsg('删除成功', 1, '', true, ['msgCode' => 'L0103']);
            else
                ajaxmsg('删除成功', 0, '', true, ['msgCode' => 'L0102']);
        }
    }
}
