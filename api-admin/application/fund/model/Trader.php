<?php
namespace app\fund\model;

use think\Db;
use think\model;

class Trader extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;

  public function getHeadimgurlTextAttr($value)
  {
    $path   = Db::name('admin_attachment')->where('id', $value)->value('path');
    $domain = request()->domain();
    return $path ? $domain . '/' . $path : '';
  }
}

?>
