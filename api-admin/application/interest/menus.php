<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 蔡伟明 <314013107@qq.com>科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
/**
 * 菜单信息
 */
return [
  [
    'title' => '余额宝',
    'icon' => 'fa fa-fw fa-user',
    'url_type' => 'module_admin',
    'url_value' => 'interest/index/index',
    'url_target' => '_self',
    'online_hide' => 0,
    'sort' => 100,
    'status' => 1,
    'child' => [
      [
        'title' => '利率配置',
        'icon' => 'fa fa-fw fa-folder-open-o',
        'url_type' => 'module_admin',
        'url_value' => 'interest/index/index',
        'url_target' => '_self',
        'online_hide' => 0,
        'sort' => 100,
        'status' => 1,
      ]

    ],
  ],
];
