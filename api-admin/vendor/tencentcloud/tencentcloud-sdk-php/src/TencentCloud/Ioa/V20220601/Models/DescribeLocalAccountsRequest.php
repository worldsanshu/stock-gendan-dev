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
namespace TencentCloud\Ioa\V20220601\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeLocalAccounts请求参数结构体
 *
 * @method Condition getCondition() 获取滤条件、分页参数
<li>UserName - String - 是否必填：否 - 操作符: eq,like  - 排序支持：否- 按账号UserName过滤。</li>
<li>UserId - string - 是否必填：否 - 操作符: eq,like  - 排序支持：否 - 按账号UserNd过滤。</li>
<li>Phone - string - 是否必填：否 - 操作符: eq,like - 排序支持：否 - 按手机号过滤。</li>
 * @method void setCondition(Condition $Condition) 设置滤条件、分页参数
<li>UserName - String - 是否必填：否 - 操作符: eq,like  - 排序支持：否- 按账号UserName过滤。</li>
<li>UserId - string - 是否必填：否 - 操作符: eq,like  - 排序支持：否 - 按账号UserNd过滤。</li>
<li>Phone - string - 是否必填：否 - 操作符: eq,like - 排序支持：否 - 按手机号过滤。</li>
 * @method integer getAccountGroupId() 获取获取账号的分组Id，不传默认获取全部(只支持32位)
 * @method void setAccountGroupId(integer $AccountGroupId) 设置获取账号的分组Id，不传默认获取全部(只支持32位)
 * @method integer getShowFlag() 获取是否仅展示当前目录下用户 1： 递归显示 2：仅显示当前目录下用户(只支持32位)
 * @method void setShowFlag(integer $ShowFlag) 设置是否仅展示当前目录下用户 1： 递归显示 2：仅显示当前目录下用户(只支持32位)
 */
class DescribeLocalAccountsRequest extends AbstractModel
{
    /**
     * @var Condition 滤条件、分页参数
<li>UserName - String - 是否必填：否 - 操作符: eq,like  - 排序支持：否- 按账号UserName过滤。</li>
<li>UserId - string - 是否必填：否 - 操作符: eq,like  - 排序支持：否 - 按账号UserNd过滤。</li>
<li>Phone - string - 是否必填：否 - 操作符: eq,like - 排序支持：否 - 按手机号过滤。</li>
     */
    public $Condition;

    /**
     * @var integer 获取账号的分组Id，不传默认获取全部(只支持32位)
     */
    public $AccountGroupId;

    /**
     * @var integer 是否仅展示当前目录下用户 1： 递归显示 2：仅显示当前目录下用户(只支持32位)
     */
    public $ShowFlag;

    /**
     * @param Condition $Condition 滤条件、分页参数
<li>UserName - String - 是否必填：否 - 操作符: eq,like  - 排序支持：否- 按账号UserName过滤。</li>
<li>UserId - string - 是否必填：否 - 操作符: eq,like  - 排序支持：否 - 按账号UserNd过滤。</li>
<li>Phone - string - 是否必填：否 - 操作符: eq,like - 排序支持：否 - 按手机号过滤。</li>
     * @param integer $AccountGroupId 获取账号的分组Id，不传默认获取全部(只支持32位)
     * @param integer $ShowFlag 是否仅展示当前目录下用户 1： 递归显示 2：仅显示当前目录下用户(只支持32位)
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
        if (array_key_exists("Condition",$param) and $param["Condition"] !== null) {
            $this->Condition = new Condition();
            $this->Condition->deserialize($param["Condition"]);
        }

        if (array_key_exists("AccountGroupId",$param) and $param["AccountGroupId"] !== null) {
            $this->AccountGroupId = $param["AccountGroupId"];
        }

        if (array_key_exists("ShowFlag",$param) and $param["ShowFlag"] !== null) {
            $this->ShowFlag = $param["ShowFlag"];
        }
    }
}
