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
namespace app\fund\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;

use think\Db;

/**
 * 余额宝
 * @package app\member\admin
 */
class Agreement extends Admin
{
    /**
     * 协议
     * @return mixed
     */
    public function index()
    {
        $get_one = Db::name('fund_config')->find();
        if ($this->request->isPost()) {
            $data = input();
                if($get_one){
                    $data['update_time'] = time();
                    $result = Db::name('fund_config')->where('id',1)->update($data);
                }else{
                    $data['create_time'] = time();
                    $result = Db::name('fund_config')->insert($data);
                }


            if ($result) {
                $this->success('处理成功', null, 'index');
            } else {
                $this->error('处理失败');
            }
        }

            return ZBuilder::make('form')
                ->addUeditor('explain', '说明')
                ->setFormData($get_one) // 设置表格数据
                ->fetch(); // 渲染模板



    }






}

