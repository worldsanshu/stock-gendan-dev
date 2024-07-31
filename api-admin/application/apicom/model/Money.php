<?php
namespace app\apicom\model;

use think\Model;

class Money extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MONEY__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}

