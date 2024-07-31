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

use app\member\model\MemberMessage as Msg;
use think\Db;
use think\facade\Lang;

class Message extends Common
{
    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function initialize()
    {
        // 系统开关
        if (!config('web_site_status')) {
            ajaxmsg('站点已经关闭，请稍后访问', 0, '', true, ['msgCode' => 'L0015']);
        }
        $req = request();
        $token = input("token");
        defined('MID') or define('MID', isLogin($token));
        if (!MID) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
    }

    /**
     * 消息首页
     */
    public function index()
    {
        $status = input('t');
        $map = [];
        if (isset($status)) $map['mm.status'] = $status;
        $map['mm.mid'] = MID;
        $order = $this->getOrder();
        empty($order) && $order = 'mm.id desc';
        // 数据列表
        $data = Msg::getAll($map, $order, 12);
        $data->each(function ($it, $key) {
            $it->parameter = '';
            printlog($it->openpath, '入库数据2', 'MemberMessage2');
            if (!empty($it->openpath)) {
                $parameter = explode("|", $it->openpath);
                printlog($parameter, '入库数据2', 'MemberMessage2');
                if (isset($parameter[1]) && !empty($parameter[1])) {
                    $it->openpath = $parameter[0];
                    $it->parameter = $parameter[1];
                }
            }
            $it->create_time = getTimeFormt($it->create_time, 7);
            if (request()->get('lang') == 'zh-cn')
                return;
            $it->title = message_i18n($it->title);
            $it->info = message_i18n($it->info);
//
        });
        ajaxmsg('获取成功', 1, $data, true, ['msgCode' => 'L0014']);
    }

    /**
     * 阅读消息
     */
    public function read()
    {
        $map = [];
        $message_id = input("id");
        $map['id'] = $message_id;
        $map['mid'] = MID;
        $messageModel = new Msg;
        $result = $messageModel
          ->where($map)
          ->update(['status' => 1]);
        if ($result == 1) {
            ajaxmsg('设置为阅读', 1, '', true, ['msgCode' => 'L0128']);
        } else {
            ajaxmsg('参数错误', 0, '', true, ['msgCode' => 'L0020']);
        }
    }

    /**
     * 批量阅读未读消息
     */
    public function readall()
    {
        $map = [];
        $mid = MID;
        if (!$mid) ajaxmsg('登陆后才能操作', 0, '', true, ['msgCode' => 'L0097']);
        $map['mid'] = $mid;
        $messageModel = new Msg;
        $result = $messageModel
          ->where($map)
          ->update(['status' => 1]);
        ajaxmsg('设置为阅读', 1, '', true, ['msgCode' => 'L0128']);
    }

    /**
     * 站内信页面
     * 显示：最新网站公告
     * 显示：最新系统通知
     */
    public function messageList()
    {
        $gao = Db::name('cms_document')
          ->alias('c')
          ->join('cms_document_news d', 'c.id = d.aid')
          ->where('c.cid', 'eq', 2)
          ->where('c.language', 'eq', Lang::range())
          ->order('c.id desc')
          ->find();
        $messgae = Db::name('member_message')
          ->alias('m')
          ->where('m.mid', 'eq', MID)
          ->order('m.id desc')
          ->find();
        //判空
        $data = [];
        if (!empty($gao)) {
            $gao['create_time'] = getTimeFormt($gao['create_time'], 1);
            $data['ggao'] = $gao;
        } else {
            $data['ggao'] = [];
        }
        if (!empty($messgae)) {
            $messgae['create_time'] = getTimeFormt($messgae['create_time'], 1);
            $messgae['title'] = message_i18n($messgae['title']);
            $data['messgae'] = $messgae;
        } else {
            $data['messgae'] = [];
        }
        $msg_num = Db('member_message')->where(['mid' => MID, 'status' => 0])->count();
        // $data['ggao'] = $gao;
        $data['messgae']['msg_num'] = $msg_num;
        ajaxmsg('获取成功', 1, $data, true, ['msgCode' => 'L0014']);
    }
}