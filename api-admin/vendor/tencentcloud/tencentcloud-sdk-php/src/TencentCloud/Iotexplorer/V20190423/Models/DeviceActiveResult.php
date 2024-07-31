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
namespace TencentCloud\Iotexplorer\V20190423\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 设备激活结果数据
 *
 * @method string getModelId() 获取模版ID
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setModelId(string $ModelId) 设置模版ID
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getSn() 获取SN信息
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setSn(string $Sn) 设置SN信息
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getErrCode() 获取设备激活状态，0：激活成功；9800020：设备数超出限制；9800040：资源包类型和设备类型不匹配；9800039：资源包余额不足；9800037：激活码序号已使用；9800038：设备有效期超出限制；
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setErrCode(integer $ErrCode) 设置设备激活状态，0：激活成功；9800020：设备数超出限制；9800040：资源包类型和设备类型不匹配；9800039：资源包余额不足；9800037：激活码序号已使用；9800038：设备有效期超出限制；
注意：此字段可能返回 null，表示取不到有效值。
 */
class DeviceActiveResult extends AbstractModel
{
    /**
     * @var string 模版ID
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ModelId;

    /**
     * @var string SN信息
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Sn;

    /**
     * @var integer 设备激活状态，0：激活成功；9800020：设备数超出限制；9800040：资源包类型和设备类型不匹配；9800039：资源包余额不足；9800037：激活码序号已使用；9800038：设备有效期超出限制；
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ErrCode;

    /**
     * @param string $ModelId 模版ID
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Sn SN信息
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $ErrCode 设备激活状态，0：激活成功；9800020：设备数超出限制；9800040：资源包类型和设备类型不匹配；9800039：资源包余额不足；9800037：激活码序号已使用；9800038：设备有效期超出限制；
注意：此字段可能返回 null，表示取不到有效值。
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
        if (array_key_exists("ModelId",$param) and $param["ModelId"] !== null) {
            $this->ModelId = $param["ModelId"];
        }

        if (array_key_exists("Sn",$param) and $param["Sn"] !== null) {
            $this->Sn = $param["Sn"];
        }

        if (array_key_exists("ErrCode",$param) and $param["ErrCode"] !== null) {
            $this->ErrCode = $param["ErrCode"];
        }
    }
}
