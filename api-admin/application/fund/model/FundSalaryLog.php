<?php
namespace app\fund\model;

use think\model;

class FundSalaryLog extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;
  
  public function getTypeNameAttr($value,$data)
  {
      $typeArr = (new FundSalaryConfig)->getAllType();
      return $typeArr[$data['type']];
  }
  
  
}




?>
