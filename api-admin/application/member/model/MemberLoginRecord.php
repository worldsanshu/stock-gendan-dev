<?php
namespace app\member\model;

use think\model;

class MemberLoginRecord extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MEMBER_LOGIN_RECORD__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}

?>
