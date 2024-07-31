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
 * DescribeQA返回参数结构体
 *
 * @method string getQaBizId() 获取QA业务ID

 * @method void setQaBizId(string $QaBizId) 设置QA业务ID

 * @method string getQuestion() 获取问题

 * @method void setQuestion(string $Question) 设置问题

 * @method string getAnswer() 获取答案

 * @method void setAnswer(string $Answer) 设置答案

 * @method string getCustomParam() 获取自定义参数
 * @method void setCustomParam(string $CustomParam) 设置自定义参数
 * @method integer getSource() 获取来源

 * @method void setSource(integer $Source) 设置来源

 * @method string getSourceDesc() 获取来源描述

 * @method void setSourceDesc(string $SourceDesc) 设置来源描述

 * @method string getUpdateTime() 获取更新时间

 * @method void setUpdateTime(string $UpdateTime) 设置更新时间

 * @method integer getStatus() 获取状态

 * @method void setStatus(integer $Status) 设置状态

 * @method string getStatusDesc() 获取状态描述

 * @method void setStatusDesc(string $StatusDesc) 设置状态描述

 * @method string getCateBizId() 获取分类ID

 * @method void setCateBizId(string $CateBizId) 设置分类ID

 * @method boolean getIsAllowAccept() 获取是否允许校验

 * @method void setIsAllowAccept(boolean $IsAllowAccept) 设置是否允许校验

 * @method boolean getIsAllowDelete() 获取是否允许删除

 * @method void setIsAllowDelete(boolean $IsAllowDelete) 设置是否允许删除

 * @method boolean getIsAllowEdit() 获取是否允许编辑

 * @method void setIsAllowEdit(boolean $IsAllowEdit) 设置是否允许编辑

 * @method string getDocBizId() 获取文档id

 * @method void setDocBizId(string $DocBizId) 设置文档id

 * @method string getFileName() 获取文档名称

 * @method void setFileName(string $FileName) 设置文档名称

 * @method string getFileType() 获取文档类型

 * @method void setFileType(string $FileType) 设置文档类型

 * @method string getSegmentBizId() 获取分片ID

 * @method void setSegmentBizId(string $SegmentBizId) 设置分片ID

 * @method string getPageContent() 获取分片内容
 * @method void setPageContent(string $PageContent) 设置分片内容
 * @method array getHighlights() 获取分片高亮内容
 * @method void setHighlights(array $Highlights) 设置分片高亮内容
 * @method string getOrgData() 获取分片内容

 * @method void setOrgData(string $OrgData) 设置分片内容

 * @method integer getAttrRange() 获取属性标签适用范围

 * @method void setAttrRange(integer $AttrRange) 设置属性标签适用范围

 * @method array getAttrLabels() 获取属性标签
 * @method void setAttrLabels(array $AttrLabels) 设置属性标签
 * @method string getExpireStart() 获取有效开始时间，unix时间戳
 * @method void setExpireStart(string $ExpireStart) 设置有效开始时间，unix时间戳
 * @method string getExpireEnd() 获取有效结束时间，unix时间戳，0代表永久有效
 * @method void setExpireEnd(string $ExpireEnd) 设置有效结束时间，unix时间戳，0代表永久有效
 * @method string getRequestId() 获取唯一请求 ID，由服务端生成，每次请求都会返回（若请求因其他原因未能抵达服务端，则该次请求不会获得 RequestId）。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，由服务端生成，每次请求都会返回（若请求因其他原因未能抵达服务端，则该次请求不会获得 RequestId）。定位问题时需要提供该次请求的 RequestId。
 */
class DescribeQAResponse extends AbstractModel
{
    /**
     * @var string QA业务ID

     */
    public $QaBizId;

    /**
     * @var string 问题

     */
    public $Question;

    /**
     * @var string 答案

     */
    public $Answer;

    /**
     * @var string 自定义参数
     */
    public $CustomParam;

    /**
     * @var integer 来源

     */
    public $Source;

    /**
     * @var string 来源描述

     */
    public $SourceDesc;

    /**
     * @var string 更新时间

     */
    public $UpdateTime;

    /**
     * @var integer 状态

     */
    public $Status;

    /**
     * @var string 状态描述

     */
    public $StatusDesc;

    /**
     * @var string 分类ID

     */
    public $CateBizId;

    /**
     * @var boolean 是否允许校验

     */
    public $IsAllowAccept;

    /**
     * @var boolean 是否允许删除

     */
    public $IsAllowDelete;

    /**
     * @var boolean 是否允许编辑

     */
    public $IsAllowEdit;

    /**
     * @var string 文档id

     */
    public $DocBizId;

    /**
     * @var string 文档名称

     */
    public $FileName;

    /**
     * @var string 文档类型

     */
    public $FileType;

    /**
     * @var string 分片ID

     */
    public $SegmentBizId;

    /**
     * @var string 分片内容
     */
    public $PageContent;

    /**
     * @var array 分片高亮内容
     */
    public $Highlights;

    /**
     * @var string 分片内容

     */
    public $OrgData;

    /**
     * @var integer 属性标签适用范围

     */
    public $AttrRange;

    /**
     * @var array 属性标签
     */
    public $AttrLabels;

    /**
     * @var string 有效开始时间，unix时间戳
     */
    public $ExpireStart;

    /**
     * @var string 有效结束时间，unix时间戳，0代表永久有效
     */
    public $ExpireEnd;

    /**
     * @var string 唯一请求 ID，由服务端生成，每次请求都会返回（若请求因其他原因未能抵达服务端，则该次请求不会获得 RequestId）。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;

    /**
     * @param string $QaBizId QA业务ID

     * @param string $Question 问题

     * @param string $Answer 答案

     * @param string $CustomParam 自定义参数
     * @param integer $Source 来源

     * @param string $SourceDesc 来源描述

     * @param string $UpdateTime 更新时间

     * @param integer $Status 状态

     * @param string $StatusDesc 状态描述

     * @param string $CateBizId 分类ID

     * @param boolean $IsAllowAccept 是否允许校验

     * @param boolean $IsAllowDelete 是否允许删除

     * @param boolean $IsAllowEdit 是否允许编辑

     * @param string $DocBizId 文档id

     * @param string $FileName 文档名称

     * @param string $FileType 文档类型

     * @param string $SegmentBizId 分片ID

     * @param string $PageContent 分片内容
     * @param array $Highlights 分片高亮内容
     * @param string $OrgData 分片内容

     * @param integer $AttrRange 属性标签适用范围

     * @param array $AttrLabels 属性标签
     * @param string $ExpireStart 有效开始时间，unix时间戳
     * @param string $ExpireEnd 有效结束时间，unix时间戳，0代表永久有效
     * @param string $RequestId 唯一请求 ID，由服务端生成，每次请求都会返回（若请求因其他原因未能抵达服务端，则该次请求不会获得 RequestId）。定位问题时需要提供该次请求的 RequestId。
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
        if (array_key_exists("QaBizId",$param) and $param["QaBizId"] !== null) {
            $this->QaBizId = $param["QaBizId"];
        }

        if (array_key_exists("Question",$param) and $param["Question"] !== null) {
            $this->Question = $param["Question"];
        }

        if (array_key_exists("Answer",$param) and $param["Answer"] !== null) {
            $this->Answer = $param["Answer"];
        }

        if (array_key_exists("CustomParam",$param) and $param["CustomParam"] !== null) {
            $this->CustomParam = $param["CustomParam"];
        }

        if (array_key_exists("Source",$param) and $param["Source"] !== null) {
            $this->Source = $param["Source"];
        }

        if (array_key_exists("SourceDesc",$param) and $param["SourceDesc"] !== null) {
            $this->SourceDesc = $param["SourceDesc"];
        }

        if (array_key_exists("UpdateTime",$param) and $param["UpdateTime"] !== null) {
            $this->UpdateTime = $param["UpdateTime"];
        }

        if (array_key_exists("Status",$param) and $param["Status"] !== null) {
            $this->Status = $param["Status"];
        }

        if (array_key_exists("StatusDesc",$param) and $param["StatusDesc"] !== null) {
            $this->StatusDesc = $param["StatusDesc"];
        }

        if (array_key_exists("CateBizId",$param) and $param["CateBizId"] !== null) {
            $this->CateBizId = $param["CateBizId"];
        }

        if (array_key_exists("IsAllowAccept",$param) and $param["IsAllowAccept"] !== null) {
            $this->IsAllowAccept = $param["IsAllowAccept"];
        }

        if (array_key_exists("IsAllowDelete",$param) and $param["IsAllowDelete"] !== null) {
            $this->IsAllowDelete = $param["IsAllowDelete"];
        }

        if (array_key_exists("IsAllowEdit",$param) and $param["IsAllowEdit"] !== null) {
            $this->IsAllowEdit = $param["IsAllowEdit"];
        }

        if (array_key_exists("DocBizId",$param) and $param["DocBizId"] !== null) {
            $this->DocBizId = $param["DocBizId"];
        }

        if (array_key_exists("FileName",$param) and $param["FileName"] !== null) {
            $this->FileName = $param["FileName"];
        }

        if (array_key_exists("FileType",$param) and $param["FileType"] !== null) {
            $this->FileType = $param["FileType"];
        }

        if (array_key_exists("SegmentBizId",$param) and $param["SegmentBizId"] !== null) {
            $this->SegmentBizId = $param["SegmentBizId"];
        }

        if (array_key_exists("PageContent",$param) and $param["PageContent"] !== null) {
            $this->PageContent = $param["PageContent"];
        }

        if (array_key_exists("Highlights",$param) and $param["Highlights"] !== null) {
            $this->Highlights = [];
            foreach ($param["Highlights"] as $key => $value){
                $obj = new Highlight();
                $obj->deserialize($value);
                array_push($this->Highlights, $obj);
            }
        }

        if (array_key_exists("OrgData",$param) and $param["OrgData"] !== null) {
            $this->OrgData = $param["OrgData"];
        }

        if (array_key_exists("AttrRange",$param) and $param["AttrRange"] !== null) {
            $this->AttrRange = $param["AttrRange"];
        }

        if (array_key_exists("AttrLabels",$param) and $param["AttrLabels"] !== null) {
            $this->AttrLabels = [];
            foreach ($param["AttrLabels"] as $key => $value){
                $obj = new AttrLabel();
                $obj->deserialize($value);
                array_push($this->AttrLabels, $obj);
            }
        }

        if (array_key_exists("ExpireStart",$param) and $param["ExpireStart"] !== null) {
            $this->ExpireStart = $param["ExpireStart"];
        }

        if (array_key_exists("ExpireEnd",$param) and $param["ExpireEnd"] !== null) {
            $this->ExpireEnd = $param["ExpireEnd"];
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
