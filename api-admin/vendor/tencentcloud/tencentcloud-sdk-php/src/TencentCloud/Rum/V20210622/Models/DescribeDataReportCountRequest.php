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
namespace TencentCloud\Rum\V20210622\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeDataReportCount请求参数结构体
 *
 * @method integer getStartTime() 获取开始时间
 * @method void setStartTime(integer $StartTime) 设置开始时间
 * @method integer getEndTime() 获取结束时间
 * @method void setEndTime(integer $EndTime) 设置结束时间
 * @method integer getID() 获取项目ID
 * @method void setID(integer $ID) 设置项目ID
 * @method string getReportType() 获取上报类型（custom，event，log，miniProgramData，performance，pv，speed，webvitals）
 * @method void setReportType(string $ReportType) 设置上报类型（custom，event，log，miniProgramData，performance，pv，speed，webvitals）
 * @method string getInstanceID() 获取实例ID
 * @method void setInstanceID(string $InstanceID) 设置实例ID
 */
class DescribeDataReportCountRequest extends AbstractModel
{
    /**
     * @var integer 开始时间
     */
    public $StartTime;

    /**
     * @var integer 结束时间
     */
    public $EndTime;

    /**
     * @var integer 项目ID
     */
    public $ID;

    /**
     * @var string 上报类型（custom，event，log，miniProgramData，performance，pv，speed，webvitals）
     */
    public $ReportType;

    /**
     * @var string 实例ID
     */
    public $InstanceID;

    /**
     * @param integer $StartTime 开始时间
     * @param integer $EndTime 结束时间
     * @param integer $ID 项目ID
     * @param string $ReportType 上报类型（custom，event，log，miniProgramData，performance，pv，speed，webvitals）
     * @param string $InstanceID 实例ID
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
        if (array_key_exists("StartTime",$param) and $param["StartTime"] !== null) {
            $this->StartTime = $param["StartTime"];
        }

        if (array_key_exists("EndTime",$param) and $param["EndTime"] !== null) {
            $this->EndTime = $param["EndTime"];
        }

        if (array_key_exists("ID",$param) and $param["ID"] !== null) {
            $this->ID = $param["ID"];
        }

        if (array_key_exists("ReportType",$param) and $param["ReportType"] !== null) {
            $this->ReportType = $param["ReportType"];
        }

        if (array_key_exists("InstanceID",$param) and $param["InstanceID"] !== null) {
            $this->InstanceID = $param["InstanceID"];
        }
    }
}