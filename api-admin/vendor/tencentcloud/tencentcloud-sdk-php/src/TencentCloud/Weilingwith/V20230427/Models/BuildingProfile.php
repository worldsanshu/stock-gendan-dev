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
namespace TencentCloud\Weilingwith\V20230427\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 建筑概要信息
 *
 * @method string getBuildingId() 获取建筑id
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setBuildingId(string $BuildingId) 设置建筑id
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getBuildingName() 获取建筑名称
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setBuildingName(string $BuildingName) 设置建筑名称
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getSpaceCode() 获取空间编码
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setSpaceCode(string $SpaceCode) 设置空间编码
注意：此字段可能返回 null，表示取不到有效值。
 * @method float getLongitude() 获取经度
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setLongitude(float $Longitude) 设置经度
注意：此字段可能返回 null，表示取不到有效值。
 * @method float getLatitude() 获取纬度
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setLatitude(float $Latitude) 设置纬度
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getAddress() 获取地址
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setAddress(string $Address) 设置地址
注意：此字段可能返回 null，表示取不到有效值。
 */
class BuildingProfile extends AbstractModel
{
    /**
     * @var string 建筑id
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $BuildingId;

    /**
     * @var string 建筑名称
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $BuildingName;

    /**
     * @var string 空间编码
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $SpaceCode;

    /**
     * @var float 经度
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Longitude;

    /**
     * @var float 纬度
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Latitude;

    /**
     * @var string 地址
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Address;

    /**
     * @param string $BuildingId 建筑id
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $BuildingName 建筑名称
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $SpaceCode 空间编码
注意：此字段可能返回 null，表示取不到有效值。
     * @param float $Longitude 经度
注意：此字段可能返回 null，表示取不到有效值。
     * @param float $Latitude 纬度
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Address 地址
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
        if (array_key_exists("BuildingId",$param) and $param["BuildingId"] !== null) {
            $this->BuildingId = $param["BuildingId"];
        }

        if (array_key_exists("BuildingName",$param) and $param["BuildingName"] !== null) {
            $this->BuildingName = $param["BuildingName"];
        }

        if (array_key_exists("SpaceCode",$param) and $param["SpaceCode"] !== null) {
            $this->SpaceCode = $param["SpaceCode"];
        }

        if (array_key_exists("Longitude",$param) and $param["Longitude"] !== null) {
            $this->Longitude = $param["Longitude"];
        }

        if (array_key_exists("Latitude",$param) and $param["Latitude"] !== null) {
            $this->Latitude = $param["Latitude"];
        }

        if (array_key_exists("Address",$param) and $param["Address"] !== null) {
            $this->Address = $param["Address"];
        }
    }
}
