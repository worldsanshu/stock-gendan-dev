<?php
namespace app\apicom\service;

use app\money\model\Recharge;

class RechargeService
{

    public function __construct()
    {
        // 当前服务模型实例化
        $this->model = new Recharge();
    }

    public function createOrder($data)
    {
        $data['create_ip'] = get_client_ip();
        $result = $this->model->save($data);
        if (!$result) {
            ajaxmsg('create fail', 0);
        }

        return $this->model->id;
    }
}

?>
