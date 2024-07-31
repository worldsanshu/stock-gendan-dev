<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------
namespace app\apicom\validate;

use think\Validate;

class Bank extends Validate
{
    //定义验证规则
    protected $rule = [
      'card|银行卡号' => 'require|regex:^\d{15,19}$|unique:member_bank',
      'bank|银行' => 'require',
      'branch|分行信息' => 'require',
      'province|省份' => 'require',
      'city|市区' => 'require',
    ];
    //定义验证提示
    protected $message = [
      'card.regex' => '{%V0018}',//'请输入15-19位卡号',
      'password.regex' => '{%V0019}',// '密码格式有误(a-zA-Z0-9_) 6-16个字符',
      'mobile.regex' => '{%V0020}',// '手机号不正确',
      'card.require' => '{%V0021}',// '银行卡号不能为空',
      'card.unique' => '{%V0022}',// '银行卡号已存在',
      'bank.unique' => '{%V0023}',// '所属银行不能为空',
      'province.unique' => '{%V0024}',// '省份不能为空',
      'city.unique' => '{%V0025}',// '城市不能为空',
      'branch.unique' => '{%V0026}',// '分行不能为',
    ];
    //定义验证场景
    protected $scene = [
        //更新
      'create' => ['card', 'bank', 'province', 'city', 'branch'],
    ];
}
