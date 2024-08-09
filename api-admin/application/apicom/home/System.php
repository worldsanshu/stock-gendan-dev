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
namespace app\apicom\home;


use app\admin\model\Custommenu as CustommenuModel;
use app\admin\model\Customproperty;
use app\admin\model\Domain as DomainModel;
use think\db;
use think\facade\Request;
use app\member\model\Member as MemberModel;
use app\common\service\SignService;
class System extends Common
{
    /**
     * 获取系统配置
     * @return [type] [description]
     */
    public function index()
    {
        $path                                = Db::name('admin_attachment')->where('id', config('web_site_front_end_logo'))->value('path');
        $domain                              = request()->domain();
        $data_list['logo']                   = $path ? $domain . '/' . $path : '';
        $data_list['web_site_title']         = config('web_site_title');
        $data_list['web_site_slogan']        = config('web_site_slogan');
        $data_list['web_site_description']   = config('web_site_description');
        $data_list['web_site_statistics']    = config('web_site_statistics');
        $data_list['web_operation_platform'] = config('web_operation_platform');
        $data_list['serviceUrl'] = config('kefuurl');
        $data_list['appdown'] = config('appdown');
        $data_list['vip_fund_text'] = config('vip_fund_text')?config('vip_fund_text'):'一键优投'; //标题
        $data_list['day_fund_text'] = config('day_fund_text')?config('day_fund_text'):'新手跟投'; //标题


//        每日福利区域
        $data_list['daily_welfare']['daily_welfare_title'] = config('daily_welfare_title')??'每日福利'; //标题
//        左边区域
        $data_list['daily_welfare']['activity']['novice_gift_pack'] = config('novice_gift_pack')??'1';
        $data_list['daily_welfare']['activity']['daily_attendance'] = config('daily_attendance')??'1';
        $data_list['daily_welfare']['activity']['friend_invitation'] = config('friend_invitation')??'1';
//        右边区域
        $data_list['daily_welfare']['daily_attendance'] = config('daily_attendance')??'1';


        
        //模块开关 //跟单、配资、基金、余额宝、多语言、使用帮助、导师战绩。  默认没有加配置项 显示
        $module = [
            'merchandiser'=>config('merchandiser')??1,
            'with_funding'=>config('with_funding')??1,
            'fund'=>config('fund')??1,
            'yu_e_bao'=>config('yu_e_bao')??1,
            'multi_language'=>config('multi_language')??1,
            'use_help'=>config('use_help')??1,
            'tutor_record'=>config('tutor_record')??1,
            'trader_record'=>config('trader_record')??1,
            'trader_detailed'=>config('trader_detailed')??1,
            'partner'=>config('partner')??1,
//            'novice_gift_pack'=>config('novice_gift_pack')??1,
//            'daily_attendance'=>config('daily_attendance')??1,
            'google_auth'=>config('google_auth')??1,
            'recharge_customer_service'=>config('recharge_customer_service')??1,
            'trader_list_type'=>config('trader_list_type')??1,
            //是否开启审核中列表
            'auto_gd_Audit'=>config('auto_gd_Audit')??1,
//            邮箱注册
            'email_register'=>config('email_register')??1,
            //        合伙人周工资
            'wage_display'=>config('wage_display')??1,
//            查看我的权益
            'my_rights'=>config('my_rights')??1,
        ];
        foreach ($module as &$value) {
            $value = intval($value);
        }
        unset($value);
        $data_list['module'] = $module;
        $data_list['F10_sign'] = (new SignService())->getF10DataSignStr();//这里是不限制登录能看
        ajaxmsg('数据列表', 1, $data_list);
    }

    /**
     * Desc : 域名管理列表
     * Date : 2024-06-21 16:19
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function domain()
    {
        $data = array();
        // 定义正则表达式模式
        $pattern    = '/^[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$/';
        $rootdomain = DomainModel::where('type', 1)->Field('domain')->order('id desc')->select();


        foreach ($rootdomain as $k => $v) {

              $rootdomainlist[]['domain'] = "https://" . generate_rand_str(5) . '.' . getPrimaryDomain($v['domain']);
            // 使用preg_match函数进行验证
//            if (preg_match($pattern, $v['domain'])) {
//                $rootdomainlist[]['domain'] = "https://" . generate_rand_str(5) . '.' . $v['domain'];
//            } else {
//                $rootdomainlist[]['domain'] = $v['domain'];
//            }
        }

        $apidomain     = DomainModel::where('type', 2)->where('status', 1)->order('id desc')->select();
        $apidomainlist = [];
        foreach ($apidomain as $k => $v) {
            $apidomainlist[]['domain'] = "https://" . generate_rand_str(5) . '.' . getPrimaryDomain($v['domain']);
            // 使用preg_match函数进行验证
//            if (preg_match($pattern, $v['domain'])) {
//                for ($i = 0; $i < 5; $i++) {
//                    $apidomainlist[]['domain'] = "https://" . generate_rand_str(5) . '.' . $v['domain'];
//                }
//            } else {
//                $apidomainlist[]['domain'] = $v['domain'];
//            }
        }
        $data['apidomain']  = $apidomainlist;
        $data['rootdomain'] = $rootdomainlist;
        ajaxmsg("请求成功", 1, $data);
    }

    /**
     * Desc : 异常管理
     * Date : 2024-06-21 16:19
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function WrongDomain()
    {
        // 获取当前请求对象
        $request = Request::instance();
        $data    = input();

        $ip = $request->ip();
        if(MID){
            $username=MemberModel::get(MID)['mobile'];
        }


        $zone = getGeoLocation($ip);
        if (is_object($zone)||is_array($zone)) {
            $zone = sprintf(
                "%s-%s-%s-%s-%s",
                $zone['query'],
                $zone['country'],
                $zone['regionName'],
                $zone['city'],
                $zone['isp']
            );
        }
        if (!empty($data['domain'])) {
            if (!is_array($data['domain'])) {
                $domain       = $data['domain'];
                $d['domain']  = $domain;
                $d['UA']      = $request->header('user-agent');
                $d['ip']      = $request->ip();
                $d['zone']    = $zone;
                $d['username']    = isset($username) ?? '';
                $d['addtime'] = time();

                Db::name('admin_domain_abnormallog')->insert($d);

                $domain = getPrimaryDomain($domain);
                DomainModel::where('domain', $domain)->setInc('is_abnormal');
            } else {
                for ($i = 0; $i < count($data['domain']); $i++) {
                    $domain       = $data['domain'][$i];
                    $d['domain']  = $domain;
                    $d['UA']      = $request->header('user-agent');
                    $d['ip']      =$request->ip();
                    $d['zone']    = $zone;
                    $d['addtime'] = time();
                    Db::name('admin_domain_abnormallog')->insert($d);
                    $domain = getPrimaryDomain($domain);
                    DomainModel::where('domain', $domain)->setInc('is_abnormal');
                }
            }


        }
        ajaxmsg("请求成功", 1, '');
    }


    /**
     * 获取系统配置导航
     */
    public function config_navigation()
    {
        $navigation['home'] = CustommenuModel::where('position', 'home')
            ->where('status', 1)
            ->order('sort asc')
            ->field('name,url_type,url,img_url img_url_text,default_icon,url_level,sort')
            ->select();

        if(count($navigation['home']) > 0){
            foreach ($navigation['home'] as &$v){
                if(empty($v['img_url_text'])){
                    $v['img_url'] = $v['default_icon'];
                    unset( $v['default_icon'] );
                    unset( $v['img_url_text'] );
                }else{
                    $v['img_url'] = $v['img_url_text'];
                    unset( $v['default_icon'] );
                    unset( $v['img_url_text'] );
                }
            }
        }else{
//            因为默认没有菜单，返回了默认列表，如果手动把所有菜单关闭，返回空
            $home = CustommenuModel::where('position', 'home')->count();
            if($home > 0){
                $navigation['home'] = [];
            }else{
                $navigation['home'] = CustommenuModel::getMenuArr('home');
            }
        }

        $navigation['user'] = CustommenuModel::where('position', 'user')
            ->where('status', 1)
            ->order('sort asc')
            ->field('name,url_type,url,img_url img_url_text,default_icon,url_level,sort')
            ->select();
        if(count($navigation['user']) > 0){
            foreach ($navigation['user'] as &$v){
                if(empty($v['img_url_text'])){
                    $v['img_url'] = $v['default_icon'];
                    unset( $v['default_icon'] );
                    unset( $v['img_url_text'] );
                }else{
                    $v['img_url'] = $v['img_url_text'];
                    unset( $v['default_icon'] );
                    unset( $v['img_url_text'] );
                }
            }
        }else{
            //            因为默认没有菜单，返回了默认列表，如果手动把所有菜单关闭，返回空
            $home = CustommenuModel::where('position', 'user')->count();
            if($home > 0){
                $navigation['user'] = [];
            }else{
                $navigation['user'] = CustommenuModel::getMenuArr('user');
            }
        }

        $navigation['user_list'] = CustommenuModel::where('position', 'user_list')
            ->where('status', 1)
            ->order('sort asc')
            ->field('name,url_type,url,img_url img_url_text,default_icon,url_level,sort')
            ->select();
        if(count($navigation['user_list']) > 0){
            foreach ($navigation['user_list'] as &$v){
                if(empty($v['img_url_text'])){
                    $v['img_url'] = $v['default_icon'];
                    unset( $v['default_icon'] );
                    unset( $v['img_url_text'] );
                }else{
                    $v['img_url'] = $v['img_url_text'];
                    unset( $v['default_icon'] );
                    unset( $v['img_url_text'] );
                }
            }
        }else{
            //            因为默认没有菜单，返回了默认列表，如果手动把所有菜单关闭，返回空
            $home = CustommenuModel::where('position', 'user_list')->count();
            if($home > 0){
                $navigation['user_list'] = [];
            }else{
                $navigation['user_list'] = CustommenuModel::getMenuArr('user_list');
            }
        }

        ajaxmsg('数据列表', 1, $navigation);
    }


    /**
     * 获取系统配置资产
     */
    public function config_property()
    {
        $navigation = Customproperty::
            where('status', 1)
            ->order('sort asc')
            ->field('name,url_type,url,img_url img_url_text,default_icon,url_level,sort,money')
            ->select();

        if(count($navigation) > 0){
            foreach ($navigation as &$v){
                if(empty($v['img_url_text'])){
                    $v['img_url'] = $v['default_icon'];
                    unset( $v['default_icon'] );
                    unset( $v['img_url_text'] );
                }else{
                    $v['img_url'] = $v['img_url_text'];
                    unset( $v['default_icon'] );
                    unset( $v['img_url_text'] );
                }
            }
        }else{
//            因为默认没有菜单，返回了默认列表，如果手动把所有菜单关闭，返回空
            $home = Customproperty::count();
            if($home > 0){
                $navigation = [];
            }else{
                $navigation = Customproperty::getPropertyArr();
            }
        }

        ajaxmsg('数据列表', 1, $navigation);
    }


}