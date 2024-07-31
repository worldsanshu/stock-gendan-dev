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
namespace TencentCloud\Lke\V20231130\Models;
use TencentCloud\Common\AbstractModel;

/**
 * ListModel请求参数结构体
 *
 * @method string getAppType() 获取应用类型；knowledge_qa-知识问答管理；summary-知识摘要；classifys-知识标签提取
 * @method void setAppType(string $AppType) 设置应用类型；knowledge_qa-知识问答管理；summary-知识摘要；classifys-知识标签提取
 * @method string getLoginUin() 获取登录用户主账号(集成商模式必填)	
 * @method void setLoginUin(string $LoginUin) 设置登录用户主账号(集成商模式必填)	
 * @method string getLoginSubAccountUin() 获取登录用户子账号(集成商模式必填)	
 * @method void setLoginSubAccountUin(string $LoginSubAccountUin) 设置登录用户子账号(集成商模式必填)	
 */
class ListModelRequest extends AbstractModel
{
    /**
     * @var string 应用类型；knowledge_qa-知识问答管理；summary-知识摘要；classifys-知识标签提取
     */
    public $AppType;

    /**
     * @var string 登录用户主账号(集成商模式必填)	
     */
    public $LoginUin;

    /**
     * @var string 登录用户子账号(集成商模式必填)	
     */
    public $LoginSubAccountUin;

    /**
     * @param string $AppType 应用类型；knowledge_qa-知识问答管理；summary-知识摘要；classifys-知识标签提取
     * @param string $LoginUin 登录用户主账号(集成商模式必填)	
     * @param string $LoginSubAccountUin 登录用户子账号(集成商模式必填)	
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
        if (array_key_exists("AppType",$param) and $param["AppType"] !== null) {
            $this->AppType = $param["AppType"];
        }

        if (array_key_exists("LoginUin",$param) and $param["LoginUin"] !== null) {
            $this->LoginUin = $param["LoginUin"];
        }

        if (array_key_exists("LoginSubAccountUin",$param) and $param["LoginSubAccountUin"] !== null) {
            $this->LoginSubAccountUin = $param["LoginSubAccountUin"];
        }
    }
}
