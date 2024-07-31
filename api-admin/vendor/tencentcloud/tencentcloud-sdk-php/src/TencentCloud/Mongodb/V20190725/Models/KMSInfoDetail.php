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
namespace TencentCloud\Mongodb\V20190725\Models;
use TencentCloud\Common\AbstractModel;

/**
 * KMS密钥信息
 *
 * @method string getKeyId() 获取主密钥 ID。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setKeyId(string $KeyId) 设置主密钥 ID。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getKeyName() 获取主密钥名称。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setKeyName(string $KeyName) 设置主密钥名称。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getCreateTime() 获取实例与密钥绑定时间。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateTime(string $CreateTime) 设置实例与密钥绑定时间。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getStatus() 获取密钥状态。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setStatus(string $Status) 设置密钥状态。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getKeyUsage() 获取密钥用途。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setKeyUsage(string $KeyUsage) 设置密钥用途。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getKeyOrigin() 获取密钥来源。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setKeyOrigin(string $KeyOrigin) 设置密钥来源。
注意：此字段可能返回 null，表示取不到有效值。
 */
class KMSInfoDetail extends AbstractModel
{
    /**
     * @var string 主密钥 ID。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $KeyId;

    /**
     * @var string 主密钥名称。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $KeyName;

    /**
     * @var string 实例与密钥绑定时间。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateTime;

    /**
     * @var string 密钥状态。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Status;

    /**
     * @var string 密钥用途。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $KeyUsage;

    /**
     * @var string 密钥来源。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $KeyOrigin;

    /**
     * @param string $KeyId 主密钥 ID。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $KeyName 主密钥名称。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $CreateTime 实例与密钥绑定时间。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Status 密钥状态。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $KeyUsage 密钥用途。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $KeyOrigin 密钥来源。
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
        if (array_key_exists("KeyId",$param) and $param["KeyId"] !== null) {
            $this->KeyId = $param["KeyId"];
        }

        if (array_key_exists("KeyName",$param) and $param["KeyName"] !== null) {
            $this->KeyName = $param["KeyName"];
        }

        if (array_key_exists("CreateTime",$param) and $param["CreateTime"] !== null) {
            $this->CreateTime = $param["CreateTime"];
        }

        if (array_key_exists("Status",$param) and $param["Status"] !== null) {
            $this->Status = $param["Status"];
        }

        if (array_key_exists("KeyUsage",$param) and $param["KeyUsage"] !== null) {
            $this->KeyUsage = $param["KeyUsage"];
        }

        if (array_key_exists("KeyOrigin",$param) and $param["KeyOrigin"] !== null) {
            $this->KeyOrigin = $param["KeyOrigin"];
        }
    }
}
