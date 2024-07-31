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
namespace TencentCloud\Cls\V20201016\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 文件路径信息
 *
 * @method string getPath() 获取文件路径
 * @method void setPath(string $Path) 设置文件路径
 * @method string getFile() 获取文件名称
 * @method void setFile(string $File) 设置文件名称
 */
class FilePathInfo extends AbstractModel
{
    /**
     * @var string 文件路径
     */
    public $Path;

    /**
     * @var string 文件名称
     */
    public $File;

    /**
     * @param string $Path 文件路径
     * @param string $File 文件名称
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
        if (array_key_exists("Path",$param) and $param["Path"] !== null) {
            $this->Path = $param["Path"];
        }

        if (array_key_exists("File",$param) and $param["File"] !== null) {
            $this->File = $param["File"];
        }
    }
}
