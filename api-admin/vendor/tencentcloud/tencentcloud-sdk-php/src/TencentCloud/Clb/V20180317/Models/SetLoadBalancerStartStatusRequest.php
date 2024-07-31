<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Clb\V20180317\Models;
use TencentCloud\Common\AbstractModel;

/**
 * SetLoadBalancerStartStatus请求参数结构体
 *
 * @method string getOperationType() 获取操作类型。Start：启动实例，Stop：停止实例。
 * @method void setOperationType(string $OperationType) 设置操作类型。Start：启动实例，Stop：停止实例。
 * @method string getLoadBalancerId() 获取负载均衡实例ID。
 * @method void setLoadBalancerId(string $LoadBalancerId) 设置负载均衡实例ID。
 * @method array getListenerIds() 获取监听器ID。如果该字段为空，则表示操作负载均衡实例，如果不为空，则表示操作监听器。
 * @method void setListenerIds(array $ListenerIds) 设置监听器ID。如果该字段为空，则表示操作负载均衡实例，如果不为空，则表示操作监听器。
 */
class SetLoadBalancerStartStatusRequest extends AbstractModel
{
    /**
     * @var string 操作类型。Start：启动实例，Stop：停止实例。
     */
    public $OperationType;

    /**
     * @var string 负载均衡实例ID。
     */
    public $LoadBalancerId;

    /**
     * @var array 监听器ID。如果该字段为空，则表示操作负载均衡实例，如果不为空，则表示操作监听器。
     */
    public $ListenerIds;

    /**
     * @param string $OperationType 操作类型。Start：启动实例，Stop：停止实例。
     * @param string $LoadBalancerId 负载均衡实例ID。
     * @param array $ListenerIds 监听器ID。如果该字段为空，则表示操作负载均衡实例，如果不为空，则表示操作监听器。
     */
    function __construct()
    {

    }

    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("OperationType",$param) and $param["OperationType"] !== null) {
            $this->OperationType = $param["OperationType"];
        }

        if (array_key_exists("LoadBalancerId",$param) and $param["LoadBalancerId"] !== null) {
            $this->LoadBalancerId = $param["LoadBalancerId"];
        }

        if (array_key_exists("ListenerIds",$param) and $param["ListenerIds"] !== null) {
            $this->ListenerIds = $param["ListenerIds"];
        }
    }
}
