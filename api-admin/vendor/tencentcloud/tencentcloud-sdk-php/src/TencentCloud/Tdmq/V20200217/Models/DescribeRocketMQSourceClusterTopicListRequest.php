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
namespace TencentCloud\Tdmq\V20200217\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeRocketMQSourceClusterTopicList请求参数结构体
 *
 * @method integer getLimit() 获取分页大小
 * @method void setLimit(integer $Limit) 设置分页大小
 * @method integer getOffset() 获取偏移量
 * @method void setOffset(integer $Offset) 设置偏移量
 * @method string getTaskId() 获取迁移任务名
 * @method void setTaskId(string $TaskId) 设置迁移任务名
 * @method array getFilters() 获取查询过滤器，支持字段如下
TopicName,
Type，Imported
 * @method void setFilters(array $Filters) 设置查询过滤器，支持字段如下
TopicName,
Type，Imported
 */
class DescribeRocketMQSourceClusterTopicListRequest extends AbstractModel
{
    /**
     * @var integer 分页大小
     */
    public $Limit;

    /**
     * @var integer 偏移量
     */
    public $Offset;

    /**
     * @var string 迁移任务名
     */
    public $TaskId;

    /**
     * @var array 查询过滤器，支持字段如下
TopicName,
Type，Imported
     */
    public $Filters;

    /**
     * @param integer $Limit 分页大小
     * @param integer $Offset 偏移量
     * @param string $TaskId 迁移任务名
     * @param array $Filters 查询过滤器，支持字段如下
TopicName,
Type，Imported
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
        if (array_key_exists("Limit",$param) and $param["Limit"] !== null) {
            $this->Limit = $param["Limit"];
        }

        if (array_key_exists("Offset",$param) and $param["Offset"] !== null) {
            $this->Offset = $param["Offset"];
        }

        if (array_key_exists("TaskId",$param) and $param["TaskId"] !== null) {
            $this->TaskId = $param["TaskId"];
        }

        if (array_key_exists("Filters",$param) and $param["Filters"] !== null) {
            $this->Filters = [];
            foreach ($param["Filters"] as $key => $value){
                $obj = new Filter();
                $obj->deserialize($value);
                array_push($this->Filters, $obj);
            }
        }
    }
}
