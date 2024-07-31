<?php

namespace plugins\MandaoSms;

use app\common\controller\Plugin;

class MandaoSms extends Plugin
{
    public $info = [
      'name' => 'MandaoSms',
      'title' => '漫道短信',
      'identifier' => 'mandaosms.mandao.plugin',
      'icon' => 'fa fa-fw fa-envelope',
      'author' => 'lvxique',
      'author_url' => 'http://www.XXX.com',
      'version' => '1.0.0',
      'description' => '漫道短信接口。',
    ];

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }
}

?>