<?php
namespace app\fund\model;

use think\model;

class FundSalaryConfig extends Model
{
  // 自动写入时间戳
  protected $autoWriteTimestamp = true;
  
  
  protected $fundSalaryType = ['全部', '团队分成', '周工资', '补贴'];
  
  //初始化方法
  protected function initialize()
  {
      $this->fundSalaryType = lang('fundSalaryType');
  }
  
  public function getAllType(){
      return  $this->fundSalaryType;
  }
  

  
}

?>
