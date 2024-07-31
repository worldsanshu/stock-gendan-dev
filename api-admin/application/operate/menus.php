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
    'title' => '运营中心',
    'icon' => 'fa fa-fw fa-newspaper-o',
    'url_type' => 'module_admin',
    'url_value' => 'operate/index/index.',
    'url_target' => '_self',
    'online_hide' => 0,
    'sort' => 100,
    'child' => [
      [
        'title' => '签到',
        'icon' => 'fa fa-fw fa-tachometer',
        'url_type' => 'module_admin',
        'url_value' => 'operate/index/index',
        'url_target' => '_self',
        'online_hide' => 0,
        'sort' => 100,
        'child' => [
          [
            'title' => '签到记录',
            'icon' => 'fa fa-fw fa-tachometer',
            'url_type' => 'module_admin',
            'url_value' => 'operate/index/index',
            'url_target' => '_self',
            'online_hide' => 1,
            'sort' => 100,
            'child' => []
          ]
        ],
      ],
      [
        'title' => '抽奖中心',
        'icon' => 'fa fa-fw fa-tachometer',
        'url_type' => 'module_admin',
        'url_value' => 'agents/manager/application_invite',
        'url_target' => '_self',
        'online_hide' => 0,
        'sort' => 100,
        'child' => [[
          'title' => ' 抽奖记录',
          'icon' => 'fa fa-fw fa-tachometer',
          'url_type' => 'module_admin',
          'url_value' => 'operate/index/luckrecord',
          'url_target' => '_self',
          'online_hide' => 1,
          'sort' => 100,
          'child' => []
        ], [
          'title' => '中奖记录',
          'icon' => 'fa fa-fw fa-tachometer',
          'url_type' => 'module_admin',
          'url_value' => 'operate/index/winningrecord',
          'url_target' => '_self',
          'online_hide' => 1,
          'sort' => 100,
          'child' => []
        ], [
          'title' => '奖品设置',
          'icon' => 'fa fa-fw fa-tachometer',
          'url_type' => 'module_admin',
          'url_value' => 'operate/prize/index',
          'url_target' => '_self',
          'online_hide' => 1,
          'sort' => 100,
          'child' => []
        ], [
          'title' => '可用抽奖次数',
          'icon' => 'fa fa-fw fa-tachometer',
          'url_type' => 'module_admin',
          'url_value' => 'operate/index/operateuser',
          'url_target' => '_self',
          'online_hide' => 1,
          'sort' => 100,
          'child' => []
        ]],
      ],
      [
        'title' => '提现记录',
        'icon' => 'fa fa-fw fa-tachometer',
        'url_type' => 'module_admin',
        'url_value' => 'agents/manager/application_record',
        'url_target' => '_self',
        'online_hide' => 0,
        'sort' => 100,
        'child' => [],
      ],
      [
        'title' => '佣金分成明细',
        'icon' => 'fa fa-fw fa-tachometer',
        'url_type' => 'module_admin',
        'url_value' => 'agents/manager/agent_share',
        'url_target' => '_self',
        'online_hide' => 0,
        'sort' => 100,
        'child' => [],
      ],
    ],
  ],
];
