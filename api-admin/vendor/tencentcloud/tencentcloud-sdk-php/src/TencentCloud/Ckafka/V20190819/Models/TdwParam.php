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
namespace TencentCloud\Ckafka\V20190819\Models;
use TencentCloud\Common\AbstractModel;

/**
 * Tdw类型入参
 *
 * @method string getBid() 获取Tdw的bid
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setBid(string $Bid) 设置Tdw的bid
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getTid() 获取Tdw的tid
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTid(string $Tid) 设置Tdw的tid
注意：此字段可能返回 null，表示取不到有效值。
 * @method boolean getIsDomestic() 获取默认true
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setIsDomestic(boolean $IsDomestic) 设置默认true
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getTdwHost() 获取TDW地址，默认tl-tdbank-tdmanager.tencent-distribute.com
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTdwHost(string $TdwHost) 设置TDW地址，默认tl-tdbank-tdmanager.tencent-distribute.com
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getTdwPort() 获取TDW端口，默认8099
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTdwPort(integer $TdwPort) 设置TDW端口，默认8099
注意：此字段可能返回 null，表示取不到有效值。
 */
class TdwParam extends AbstractModel
{
    /**
     * @var string Tdw的bid
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Bid;

    /**
     * @var string Tdw的tid
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Tid;

    /**
     * @var boolean 默认true
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $IsDomestic;

    /**
     * @var string TDW地址，默认tl-tdbank-tdmanager.tencent-distribute.com
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $TdwHost;

    /**
     * @var integer TDW端口，默认8099
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $TdwPort;

    /**
     * @param string $Bid Tdw的bid
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Tid Tdw的tid
注意：此字段可能返回 null，表示取不到有效值。
     * @param boolean $IsDomestic 默认true
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $TdwHost TDW地址，默认tl-tdbank-tdmanager.tencent-distribute.com
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $TdwPort TDW端口，默认8099
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
        if (array_key_exists("Bid",$param) and $param["Bid"] !== null) {
            $this->Bid = $param["Bid"];
        }

        if (array_key_exists("Tid",$param) and $param["Tid"] !== null) {
            $this->Tid = $param["Tid"];
        }

        if (array_key_exists("IsDomestic",$param) and $param["IsDomestic"] !== null) {
            $this->IsDomestic = $param["IsDomestic"];
        }

        if (array_key_exists("TdwHost",$param) and $param["TdwHost"] !== null) {
            $this->TdwHost = $param["TdwHost"];
        }

        if (array_key_exists("TdwPort",$param) and $param["TdwPort"] !== null) {
            $this->TdwPort = $param["TdwPort"];
        }
    }
}
