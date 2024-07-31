<?php

namespace app\operate\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\operate\model\Operateuser as Operateuser;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\bi\admin
 */
class Index extends Admin
{
    /**
     * 签到记录
     * @return mixed
     * @author 一片天 <404230497@qq.com>
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        $map['role'] = 0;
        // 数据列表
        $data_list = Db::name('member_attendancelottery att')
          ->join('member m', "att.mid=m.id", 'left')
          ->field("att.id, m.mobile, m.name, att.addtime,  att.money as money")
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
            return $item;
        });
        // 分页数据
        $page = $data_list->render();
        !empty($_SERVER["QUERY_STRING"]) && $excel_url = URL("index_export") . '?' . $_SERVER["QUERY_STRING"];
        return ZBuilder::make('table')
//          ->setSearch(['m.name' => '姓名', 'm.mobile' => '手机号'])// 设置搜索框
        ->addColumns([ // 批量添加数据列
          ['id', 'ID'],
          ['mobile', '手机号', 'callback', function ($item) {
              //     $item2 = phone_decrypt($item);
              return $item;
          }],
          ['name', '姓名'],
          ['money', '获得奖励（分）'],
          ['addtime', '签到时间', 'datetime'],])
          ->setTableName('member')
          ->addOrder('addtime')
          ->setRowList($data_list)// 设置表格数据
          ->hideCheckbox(true)
          ->fetch(); // 渲染模板
    }

    /**
     * 抽奖记录
     * @param null $id 单页id
     *
     * @return mixed
     */
    public function luckrecord()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = Db::name('operate_luckydraw l')->join('lmq_member u', "u.id=l.mid", 'left')->join('lmq_operate_prize p', "p.id=l.pizeid", 'left')->field(" l.id,l.iswinning,l.addtime,l.status, u.mobile,p.`name` as pname")->where($map)->order($order)->paginate()->each(function ($item, $key) {
            return $item;
        });
        // 分页数据
        $page = $data_list->render();
        !empty($_SERVER["QUERY_STRING"]) && $excel_url = URL("index_export") . '?' . $_SERVER["QUERY_STRING"];
        return ZBuilder::make('table')->setSearch(['mobile' => '手机号'])// 设置搜索框
        ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['mobile', '手机号', 'callback', function ($item) {
                //    $item2 = phone_decrypt($item);
                return $item;
            }],
            ['iswinning', '是否中奖', ['0' => '未中奖', '1' => '中奖']],
            ['pname', '奖品名称', ['' => '--']],
            ['addtime', '抽奖时间', 'datetime']]
        )
          ->setTableName('operate_luckydraw')
          ->addOrder('addtime')
          ->setRowList($data_list)// 设置表格数据
          ->hideCheckbox(true)
          ->fetch(); // 渲染模板
    }

    /**
     * 抽奖记录发货操作详情
     * @param
     *
     * @return mixed
     */
    public function winningrecorddaitls()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $data = input();
        $map['l.id'] = $data['id'];
        // 数据列表
        $data_list = Db::name('operate_luckydraw l')
          ->join('lmq_operate_winningrecord_daitls d', "d.lid=l.id", 'left')
          ->join('lmq_operate_prize p', "p.id=l.pizeid", 'left')
          ->join('lmq_operate_exp e', "e.pid=p.id", 'left')
          ->field(" l.*,d.*")
          ->where($map)
          ->paginate()
          ->each(function ($item, $key) {
              return $item;
          });
        // 分页数据
        $page = $data_list->render();
        !empty($_SERVER["QUERY_STRING"]) && $excel_url = URL("index_export") . '?' . $_SERVER["QUERY_STRING"];
        return ZBuilder::make('table')->setSearch(['mobile' => '手机号'])// 设置搜索框
        ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['mobile', '用户手机号'],
            ['mobile', '手机号', 'callback', function ($item) {
                //    $item2 = phone_decrypt($item);
                return $item;
            }],
            ['iswinning', '是否中奖', ['0' => '未中奖', '1' => '中奖']],
            ['pname', '奖品名称', ['' => '--']],
            ['addtime', '抽奖时间', 'datetime']]
        )
          ->setTableName('operate_luckydraw')
          ->addOrder('addtime')
          ->setRowList($data_list)// 设置表格数据
          ->hideCheckbox(true)
          ->fetch(); // 渲染模板
    }

    /**
     * 发货记录
     *
     * @return mixed
     */
    public function lotterytreatment()
    {
        $data = input();
        $insetdata['aid'] = UID;
        $insetdata['lid'] = $data['cjid'];
        $insetdata['kname'] = $data['txt_kdname'];
        $insetdata['kdexp'] = $data['txt_kdcode'];
        $insetdata['addtime'] = time();
        #插入操作记录
        Db::name('operate_exp')->insert($insetdata);
        #更新状态
        $updata = Db::table('lmq_operate_luckydraw')->where('id', $data['cjid'])->setField('status', 1);
        if ($updata) {
            return ['status' => 200, 'message' => "更新成功"];
        } else {
            return ['status' => 500, 'message' => "更新失败"];
        }
    }

    /**
     * 中奖记录
     *
     * @return mixed
     */
    public function winningrecord()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // $luckydraw = Db::query("select u.mobile,p.`name` from lmq_operate_luckydraw l LEFT JOIN lmq_member u ON u.id=l.mid LEFT JOIN lmq_operate_prize p ON p.id=l.pizeid  where mid={$mid} and iswinning=1  LIMIT 0,20");
        // 数据列表
        $data_list = Db::name('operate_luckydraw l')
          ->join('lmq_member u', "u.id=l.mid", 'left')
          ->join('lmq_operate_prize p', "p.id=l.pizeid", 'left')
          ->join('operate_exp e', "e.pid=l.id", 'left')
          ->join('operate_winningrecord_daitls a', "a.lid=l.id", 'left')
          ->field(" l.id,l.iswinning,l.addtime,l.status,p.type,e.kname,e.kdexp,e.aid, u.mobile,p.`name` as pname ,a.phone as Aphone,a.name as Aname,a.address as Aaddress")
          ->where('l.iswinning', 1)
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item, $key) {
              return $item;
          });
        $btn_access = [
          'title' => '发货处理',
          'icon' => 'fa fa-fw fa-archive',
          'id' => 'exebtn'
        ];
        $js = <<<EOF
<script type="text/javascript">
 //注册新增按钮的事件
$("#exebtn").click(function() {
    $('#myModal').modal();
    var textid = $(this).closest('tr').find(".text-id").text().trim();
    console.log(textid);
    $("#cjid").val(textid);
});

function openwi(e, id) {
    var textid = $(e).closest('tr').find(".text-id").text().trim();
    $("#cjid").val(textid);
    $('#myModal').modal();
}
$("#btn_submit").click(function() {
    txt_kdname = $("#txt_kdname").val();
    txt_kdcode = $("#txt_kdcode").val();
    cjid = $("#cjid").val();
    $.post("/FB.php/operate/index/lotterytreatment", {
        txt_kdname: txt_kdname,
        txt_kdcode: txt_kdcode,
        cjid: cjid
    }, function(result) {
        console.log(result.status);
        if (result.status == '200') {
            location.reload()
        }
    });
});

function popalert(e) {
    var textid = $(e).closest('tr').find(".text-id").text().trim();
    $.get("/FB.php/operate/index/winningrecordinfo?id=" + textid, function(result) {
        if (result['status'] == 0) {
            var statusstr = "未处理";
        } else {
            var statusstr = "已处理";
        }
        if (result['type'] == 0) {
            $(".Aname").hide();
            $(".Aphone").hide();
            $(".Aaddress").hide();
            $(".kname").hide();
            $(".kdexp").hide();
        } else {
            $("#Aname").show();
            $("#Aphone").show();
            $("#Aaddress").show();
            $("#kname").show();
            $("#kdexp").show();
            $("#Aname").val(result['Aname']);
            $("#Aphone").val(result['Aphone']);
            $("#Aaddress").val(result['Aaddress']);
            $("#kname").val(result['kname']);
            $("#kdexp").val(result['kdexp']);
        }
        if (result['aid'] == '0') {
            var aidstr = "系统自助处理";
        } else {
            var aidstr = "系统管理员";
        }
        $("#aid").val(aidstr);
        $("#pname").val(result['pname']);
        $("#status").val(statusstr);
        $('#myModal2').modal();
    });
}
</script>
EOF;
        $html = <<<EOF
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">奖品发货操作</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="txt_departmentname">快递公司</label>
                    <input type="text" name="txt_departmentname" class="form-control" id="txt_kdname" placeholder="输入快递公司名称">
                </div>
                <div class="form-group">
                    <label for="txt_parentdepartment">快递单号</label>
                    <input type="text" name="txt_parentdepartment" class="form-control" id="txt_kdcode" placeholder="输入快单单号">
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="cjid">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>取消</button>
                <button type="button" id="btn_submit" class="btn btn-primary" data-dismiss="modal"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>确定</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">抽奖详情</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="txt_departmentname">中将奖品</label>
                    <input type="text" name="pname" class="form-control" disabled id="pname">
                </div>
                <div class="form-group">
                    <label for="txt_parentdepartment">处理状态</label>
                    <input type="text" name="status" class="form-control" disabled id="status">
                </div>
                <div class="form-group Aname">
                    <label for="txt_departmentname">收货人</label>
                    <input type="text" name="Aname" class="form-control" disabled id="Aname">
                </div>
                <div class="form-group Aphone">
                    <label for="txt_parentdepartment">联系电话</label>
                    <input type="text" name="Aphone" class="form-control" disabled id="Aphone">
                </div>
                <div class="form-group Aaddress">
                    <label for="txt_parentdepartment">收获地址</label>
                    <input type="text" name="Aaddress" class="form-control" disabled id="Aaddress">
                </div>
                <div class="form-group kname">
                    <label for="txt_parentdepartment">快递名称</label>
                    <input type="text" name="kname" class="form-control" disabled id="kname">
                </div>
                <div class="form-group kdexp">
                    <label for="txt_parentdepartment">快递单号</label>
                    <input type="text" name="kdexp" class="form-control" disabled id="kdexp">
                </div>
                <div class="form-group aid">
                    <label for="txt_parentdepartment">处理人</label>
                    <input type="text" name="aid" class="form-control" disabled id="aid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>关闭</button>
            </div>
        </div>
    </div>
</div>
EOF;
        // 分页数据
        $page = $data_list->render();
        !empty($_SERVER["QUERY_STRING"]) && $excel_url = URL("index_export") . '?' . $_SERVER["QUERY_STRING"];
        return ZBuilder::make('table')->setSearch(['mobile' => '手机号'])// 设置搜索框
        ->addColumns([ // 批量添加数据列
          ['id', 'ID', '', '', '', 'text-id'],
          ['mobile', '手机号', 'callback', function ($item) {
              //  $item2 = phone_decrypt($item);
              return $item;
          }],
          ['pname', '奖品名称', ['' => '--']],
          ['addtime', '抽奖时间', 'datetime'],
          ['status', '处理状态', ['0' => '未处理', '1' => '已处理']],
          ['kname', '快递名称', ['' => '--']],
          ['kdexp', '快递单号', ['' => '--']],
          ['aid', '处理人', ['1' => '系统管理员', '0' => '系统自助处理', '' => '--']],
          ['right_button', '操作', 'callback', function ($item) {
              $item = "<div class='unline_span' title='订单详情'>" . $item . " <button class=\"btn btn-warning btn-xs\" type=\"button\"  onclick=\"popalert(this)\">订单收货信息</button></div>";
              return $item;
          }]])
          ->setTableName('operate_luckydraw')
          ->addOrder('addtime')
          ->addOrder('status')
          ->setRowList($data_list)// 设置表格数据
          ->setExtraHtml($html, 'toolbar_bottom')
          ->hideCheckbox(true)
          ->addRightButton('glyphicon', $btn_access)// 批量添加右侧按钮
          ->replaceRightButton(['status' => 0], '<button class="btn btn-danger btn-xs" type="button" onclick="openwi(this,id)">发货</button>')
          ->replaceRightButton(['status' => 1], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
          ->setExtraJs($js)
          ->fetch(); // 渲染模板
    }

    /**
     * 查看中奖详情
     *
     * @return mixed
     */
    public function winningrecordinfo()
    {
        $data = input();
        $map['id'] = $data['id'];
        // 获取查询条件
        $map = $this->getMap();
        // 数据列表
        $data_list = Db::name('operate_luckydraw l')
          ->join('lmq_member u', "u.id=l.mid", 'left')
          ->join('lmq_operate_prize p', "p.id=l.pizeid", 'left')
          ->join('operate_exp e', "e.pid=l.id", 'left')
          ->join('operate_winningrecord_daitls a', "a.lid=l.id", 'left')
          ->field(" l.id,l.iswinning,l.addtime,l.status,p.type,e.kname,e.kdexp,e.aid, u.mobile,p.`name` as pname ,a.phone as Aphone,a.name as Aname,a.address as Aaddress")
          ->where('l.id', $data['id'])
          ->find();
        return $data_list;
    }

    /**
     * 可用抽奖次数
     * @return mixed
     */
    public function operateuser()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = Db::name('operate_user u')
          ->join('lmq_member m', "m.id=u.mid", 'left')
          ->field(" u.*, m.mobile,m.`name` as pname")
          ->where($map)
          ->order($order)
          ->paginate()
          ->each(function ($item) {
              return $item;
          });
        $page = $data_list->render();
        $account = 0;
        foreach ($data_list as $val) {
            $account = bcadd($account, $val['count'], 0);
        }
        $html = <<<EOF
            <table class='table'>
            <tbody>
            <tr>
                <td>可用抽奖次数:{$account}</td>

            </tr>
            </tbody>
            </table>
EOF;
        return ZBuilder::make('table')
          ->setSearch(['name' => '姓名', 'mobile' => '手机号'])// 设置搜索框
          ->addColumns([ // 批量添加数据列
            ['id', 'ID'],
            ['mobile', '手机号', 'callback', function ($item) {
                //   $item2 = phone_decrypt($item);
                return $item;
            }],
            ['pname', '姓名'],
            ['count', '可用抽奖次数'],
            ['right_button', '操作', 'btn']
          ])
          ->hideCheckbox()
          ->setTableName('operate_user')
          ->addRightButtons(['edit'])// 批量添加右侧按钮
          ->setExtraHtml($html, 'toolbar_bottom')
          ->setRowList($data_list)
          ->fetch(); // 渲染模板
    }

    /**
     * 编辑
     * @param null $id 单页id
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = input();
            // 验证
            $result = $this->validate($data, 'Ouser');
            if (true !== $result) $this->error($result);
            $data2 = array();
            $data2['id'] = $data['id'];
            $data2['count'] = $data['count'];
            if (Operateuser::update($data2)) {
                // 记录行为
                action_log('Prize_edit', 'operate_prize', $id, UID, $data);
                $this->success('编辑成功', 'operateuser');
            } else {
                $this->error('编辑失败');
            }
        }
        $info = Db::name('operate_user u')
          ->join('lmq_member m', "m.id=u.mid", 'left')
          ->field(" u.*, m.mobile,m.`name` as pname")
          ->where('u.id', $id)
          ->find();
        // 显示编辑页面
        return ZBuilder::make('form')
          ->addFormItems([
            ['hidden', 'id'],
            ['hidden', 'mid'],
            ['text', 'pname', '姓名'],
            ['text', 'mobile', '手机号'],
            ['text', 'count', '可用抽奖次数']
          ])
          ->setFormdata($info)
          ->fetch();
    }
}
