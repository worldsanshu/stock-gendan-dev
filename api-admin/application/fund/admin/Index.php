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
use app\admin\model\Config as ConfigModel;
use app\common\builder\ZBuilder;
use app\fund\model\Fund;
use app\member\model\Member;
use app\user\model\Role as RoleModel;
use app\user\model\User as AdminUserModel;
use app\user\model\User as UserModel;
use think\Db;
use think\facade\Cache;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Index extends Admin
{
  /**
   * 用户首页
   * @return mixed
   */
  public function index()
  {

    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map   = $this->getMap();
    $order = $this->getOrder();
    empty($order) && $order = 'fund_date asc,use_num desc';
    $field = '*,type type_text,index_type index_type_text,cate_id cate_text,establish_date run_time_text,video_id video_url,content content_text,id income';
    // 数据列表
    //      $data_list = Fund::where($map)->field($field)->order($order)->paginate();//同样的方式不知道为什么获取失败
    $data_list = Db::name('fund')->where($map)->field($field)->order($order)->paginate();

    // 分页数据
    $page = $data_list->render();
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    //投资类型
    $invest_type_list = Db::name('invest_type')->column('name', 'id');
    //指数类型
    $index_type_list = Db::name('index_type')->column('name', 'id');
    //分类
    $cate_list = Db::name('fund_cate')->column('name', 'id');
    $btn_line  = ['title' => 'k线图', 'icon' => 'fa fa-fw fa-line-chart', 'href' => url('fund/fundline/list', ['fund_id' => '__id__'])];
    $type_list = ['1' => '普通基金', '2' => '操盘师基金'];

    return ZBuilder::make('table')
      ->setSearch(['name' => '名称']) // 设置搜索框
      ->addOrder('id,use_num,max_retreat,recharge,reality_recharge,sell_money') // 添加排序
      ->addOrder('is_del')
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['name', '基金名称'],
                     ['code', '基金代码'],
                     ['use_num', '使用人数'],
                     ['max_retreat', '卖出（赎回人数）'],
                     ['recharge', '买入总金额'],
                     ['reality_recharge', '实际参与收益金额'],
                     ['sell_money', '卖出金额'],
                     ['fund_date', 'K线最新时间点'],
                     ['type', '投资类型', 'select', $invest_type_list],
                     ['index_type', '投资类型', 'select', $index_type_list],
                     ['fund_type', '基金类型', 'select', $type_list],
                     ['cate_id', '所属分类', 'select', $cate_list],
                     ['create_time', '时间', 'datetime'],
                     ['status', '状态', 'switch'],
                     ['right_button', '操作', 'btn']
      ])
      ->setTableName('fund')
      ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
      ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮
      ->setRowList($data_list) // 设置表格数据
      ->setPages($page) // 设置分页数据
      ->addRightButton('custom', $btn_line)
        ->setColumnWidth('use_num,recharge', 120)
        ->setColumnWidth('max_retreat,reality_recharge,fund_date', 160)
      ->fetch(); // 渲染模板
  }

//  旧的
  public function set_old()
  {
    $data_list['GDmodel'] = config('GDmodel');
    $data_list['trader_record'] = config('trader_record');
    $data_list['trader_detailed'] = config('trader_detailed');
    if ($this->request->isPost()) {
      $data = input();
//            ->update(['value' => $data['GDmodel']])
//      $z = Db::name('admin_config')->where('name', 'GDmodel')->update(['value' => $data['GDmodel']]);
        $updates = [
            ['name' => 'GDmodel', 'value' => $data['GDmodel']],
            ['name' => 'trader_record', 'value' => $data['trader_record']],
            ['name' => 'trader_detailed', 'value' => $data['trader_detailed']],
        ];

        foreach ($updates as $update) {
            Db::name('admin_config')->where('name', $update['name'])->update(['value' => $update['value']]);
        }
//      if ($z) {
        cache('system_config', null);
        $this->success('处理成功', null, 'index');
//      } else {
//        $this->error('处理失败');
//      }
    }
    return ZBuilder::make('form')
      ->setPageTitle('优投模式设置')// 设置页面标题
      ->addFormItems([ // 批量添加表单项
                       ['radio', 'GDmodel', '优投模式', '新股模式：自己设置股票名称和涨跌；股市跟买：基于已有的股票市场的股票做操作', ['xg' => '新股模式', 'gs' => '股市跟买']],
                       ['radio', 'trader_record', '导师战绩', '隐藏后在优投页面将不再显示导师战绩信息', ['1' => '显示', '0' => '隐藏']],
                       ['radio', 'trader_detailed', '导师持仓明细', '隐藏后在优投页面将不再显示导师持仓明细', ['1' => '显示', '0' => '隐藏']],
      ])
      ->setFormData($data_list) // 设置表格数据
      ->fetch(); // 渲染模板
  }

    /**
     * 优投配置
     * @return mixed
     */
    public function set($group = 'tab1')
    {

        if ($this->request->isPost()) {
            $data = input();
            switch ($group) {
                case 'tab1':
                    if($data['buy_cost']=='on'){
                        $data['buy_cost']=1;
                    }else{
                        $data['buy_cost']=0;
                    }
                    if($data['sell_cost']=='on'){
                        $data['sell_cost']=1;
                    }else{
                        $data['sell_cost']=0;
                    }

                    if($data['auto_gd_Audit']=='on'){
                        $data['auto_gd_Audit']=1;
                    }else{
                        $data['auto_gd_Audit']=0;
                    }
                    if($data['buy_show_order']=='on'){
                        $data['buy_show_order']=1;
                    }else{
                        $data['buy_show_order']=0;
                    }

                    $updates = [
                        ['name' => 'system_commission', 'value' => $data['system_commission']],
                        ['name' => 'buy_cost', 'value' => $data['buy_cost']],
                        ['name' => 'sell_cost', 'value' => $data['sell_cost']],
                        ['name' => 'buy_show_order', 'value' => $data['buy_show_order']],
                        ['name' => 'auto_gd_Audit', 'value' => $data['auto_gd_Audit']],

                    ];
                    break;
                case 'tab2':
                    $updates = [
                        ['name' => 'trader_record', 'value' => $data['trader_record']],
                        ['name' => 'trader_detailed', 'value' => $data['trader_detailed']],
                    ];
                    break;
                case 'tab3':
                    if($data['proflt_buy_num'] < 0){
                        $data['proflt_buy_num'] = 0;
                    }
                    $updates = [
                        ['name' => 'proflt_min_account', 'value' => $data['proflt_min_account']],
                        ['name' => 'proflt_buy_num', 'value' => $data['proflt_buy_num']],
                        ['name' => 'profit_start_time', 'value' => strtotime($data['profit_start_time'])],
                        ['name' => 'profit_end_time', 'value' => strtotime($data['profit_end_time'])],
                    ];
                    break;
                case 'tab4':
                    $updates = [
                        ['name' => 'fund_explain', 'value' => $data['fund_explain']],
                    ];
                    break;
                case 'tab5':
                    $updates = [
                        ['name' => 'fund_rule', 'value' => $data['fund_rule']],
                        ['name' => 'fund_rule_img', 'value' => $data['fund_rule_img']],
                    ];
                    break;
            }

            foreach ($updates as $update) {
                Db::name('admin_config')->where('name', $update['name'])->update(['value' => $update['value']]);
            }
            systemwipeCache();
            $this->success('处理成功', null, 'index');
        }
        $list_tab = [
            'tab1' => ['title' => '优投设置', 'url' => url('set', ['group' => 'tab1'])],
            'tab2' => ['title' => '导师配置', 'url' => url('set', ['group' => 'tab2'])],
            'tab3' => ['title' => '提盈配置', 'url' => url('set', ['group' => 'tab3'])],
            'tab4' => ['title' => '服务协议', 'url' => url('set', ['group' => 'tab4'])],
            'tab5' => ['title' => '优投规则', 'url' => url('set', ['group' => 'tab5'])],
        ];
        $form_data = [];
        $data_list = [];
        switch ($group) {
            case 'tab1':
                $data_list['system_commission'] = config('system_commission');
                $data_list['buy_cost'] = config('buy_cost');
                $data_list['sell_cost'] = config('sell_cost');
                $data_list['buy_show_order'] = config('buy_show_order');
                $data_list['auto_gd_Audit'] = config('auto_gd_Audit');
                $form_data = [

                       ['number', 'system_commission', '平台分成', '用户智投操盘后系统分成比例'],
                       ['switch', 'buy_cost', '是否开启购买成本', '开启后结算会扣除购买成本'],
                       ['switch', 'sell_cost', '是否开启卖出成本', '开启后结算会扣除卖出成本'],
                       ['switch', 'buy_show_order', '是否买入就显示订单', '买入就显示订单，前端用户就可以看到买入操作了在交易记录中显示出来'],
                       ['switch', 'auto_gd_Audit', '优投自动审核', '开启后，在用户端提交 管理后台无需审核自动通过'],
                ];
                break;
            case 'tab2':
                $data_list['trader_record'] = config('trader_record');
                $data_list['trader_detailed'] = config('trader_detailed');
                $form_data = [
                    ['radio', 'trader_record', '导师战绩', '隐藏后在优投页面将不再显示导师战绩信息', ['1' => '显示', '0' => '隐藏']],
                    ['radio', 'trader_detailed', '导师持仓明细', '隐藏后在优投页面将不再显示导师持仓明细', ['1' => '显示', '0' => '隐藏']],
                ];
                break;
            case 'tab3':
                $data_list['proflt_min_account'] = config('proflt_min_account');
                $data_list['proflt_buy_num'] = config('proflt_buy_num');
                $data_list['profit_start_time'] = config('profit_start_time');
                $data_list['profit_end_time'] = config('profit_end_time');
                $form_data = [
                    ['text', 'proflt_min_account', '最低提取金额', '单次提取最低金额'],
                    ['number', 'proflt_buy_num', '每日提取次数(0不限制次数)', '','','0'],
                    ['time', 'profit_start_time', '提取时间-开始'],
                    ['time', 'profit_end_time', '提取时间-结束'],
                ];
                break;
            case 'tab4':
                $data_list['fund_explain'] = config('fund_explain');
                $form_data = [
                    ['ueditor', 'fund_explain', '服务协议'],
                ];
                break;
            case 'tab5':
                $data_list['fund_rule'] = config('fund_rule');
                $data_list['fund_rule_img'] = config('fund_rule_img');

                $form_data = [
                    ['ueditor', 'fund_rule', '优投规则'],
                    ['image', 'fund_rule_img', '优投规则背景图'],
                ];
                break;
        }

        return ZBuilder::make('form')
            ->setTabNav($list_tab,  $group)
            ->addFormItems($form_data)
            ->setFormData($data_list) // 设置表格数据
            ->fetch(); // 渲染模板

    }

  /**
   * 新增
   * @return mixed
   * @author 蔡伟明 <314013107@qq.com>
   */
  public function add()
  {
    // 保存数据
    if ($this->request->isPost()) {
      // 表单数据
      $data = input();
      if (Fund::where('name', $data['name'])->count()) {
        $this->error('基金名称已重复');
      }
      $content_str  = $data['content'];
      $content_arry = explode(',', $content_str);

      foreach ($content_arry as $k => $v) {
        $array                        = explode('/', $v);
        $content[$k]['small_title']   = isset($array[0]) ? $array[0] : '';
        $content[$k]['small_content'] = isset($array[1]) ? $array[1] : '';
      }
      $data['content'] = json_encode($content);
      $code            = Fund::order('id desc')->value('code');
      $new_code        = $code + 1;
      $lenght          = 6;
      $num             = $lenght - strlen($new_code);
      $str             = 0;
      for ($i = 0; $i < $num - 1; $i++) {
        $str .= 0;
      }
      // 获取表单项
      $cache_name   = $this->request->module() . '/' . parse_name($this->request->controller()) . '/add';
      $cache_name   = strtolower($cache_name);
      $form         = Cache::get($cache_name, []);
      $data['code'] = $str . $new_code;
      // 验证
      $result = $this->validate($data, 'Fund.add');
      if (true !== $result) $this->error($result);
//            $start_date = $data['establish_date']; //成立时间
//            $current_date = $data['node_date']; //节点时间
      $min_invest_money = $data['min_invest_money'];
      if ($min_invest_money < 1) {
        $this->error('最低投资金额不能小于1');
      }
      unset($data['node_date'], $data['node_price']);
      if ($res = Fund::create($data)) {
        $this->success('新增成功', 'index');
      } else {
        $this->error('新增失败');
      }
    }
    //投资类型
    $invest_type_list = Db::name('invest_type')->column('name', 'id');
    //指数类型
    $index_type_list = Db::name('index_type')->column('name', 'id');
    $type_list       = ['1' => '普通基金', '2' => '操盘师基金'];
    //分类
    $cate_list = Db::name('fund_cate')->column('name', 'id');
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['text', 'name', '基金名称', ''],
                     ['text', 'how', '做多/做少'],
                     ['number', 'num', '数值 （格式：20.00X）', ''],
                     ['text', 'fund_summarize', '基金概括'],
                     ['date', 'establish_date', '成立日期'],
                     ['text', 'scale', '基金规模(格式：1000万元)'],
                     ['text', 'share', '基金份额(格式：1000万份)'],
                     ['text', 'manage', '基金经理（经理姓名）'],
                     ['text', 'administrator', '基金管理（管理者姓名）'],
                     ['text', 'title', '视频标题'],
                     ['file', 'video_id', '视频'],
                     //           ['text', 'content', '内容'],
                     ['text', 'follow_funds', '跟随资金(格式：1000.00)'],
                     ['text', 'year_income', '年化收益(格式：10%)'],
                     ['text', 'year_max_retreat', '年均最大回撤(格式：10%)'],
                     ['text', 'hold_time', '建议持有时间(格式：2年)'],
                     ['number', 'min_invest_money', '最低投资金额(格式：2000)'],
                     ['text', 'trace_index', '跟踪指数(格式：+10%)'],
                     ['text', 'trace_title', '指数标题(如：食品饮料)'],

                     //            ['text', 'explain', '说明']
    ])
      ->addTextarea('content', '基金简介（格式 标题/内容,标题/内容）')
      ->addSelect('cate_id', '请选择基金分类', '请选择基金分类', $cate_list)
      ->addSelect('type', '请选择投资类型', '请选择投资类型', $invest_type_list)
      ->addSelect('index_type', '选择指数类型', '选择指数类型', $index_type_list)
      ->addSelect('fund_type', '请选择基金类型', '请选择基金类型', $type_list)
      ->fetch();
  }

  public function edit($id = null)
  {
    if ($id === null) $this->error('缺少参数');
    // 保存数据
    if ($this->request->isPost()) {
      $update_data = input();
      // 验证
      $data = $update_data;
      $id2  = Fund::where('name', $data['name'])->value('id');

//            $result = $this->validate($data, 'Fund');
//            if (true !== $result) $this->error($result);
      if ($id2 != $id) {
        $this->error('基金名称已重复');
      }
      $content_str   = $data['content'];
      $content_array = explode(',', $content_str);
      foreach ($content_array as $k => $v) {
        $array                        = explode('/', $v);
        $content[$k]['small_title']   = $array[0];
        $content[$k]['small_content'] = $array[1];
      }
      $data['content'] = json_encode($content);
      if (Fund::update($data)) {
        $this->success('编辑成功', cookie('__forward__'));
      } else {
        $this->error('编辑失败');
      }
    }
    $info          = Fund::where('id', $id)->find();
    $content_array = $info['content'] ? json_decode($info['content'], true) : '';
    $str           = '';
    if ($content_array) {
      foreach ($content_array as $key => $value) {
        $str .= $value['small_title'] . '/' . $value['small_content'] . ',';
      }
      $str             = substr($str, 0, strlen($str) - 1);
      $info['content'] = $str;
    }
    //投资类型
    $invest_type_list = Db::name('invest_type')->column('name', 'id');
    //指数类型
    $index_type_list = Db::name('index_type')->column('name', 'id');
    $type_list       = ['1' => '普通基金', '2' => '操盘师基金'];
    //分类
    $cate_list = Db::name('fund_cate')->column('name', 'id');
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['text', 'name', '基金名称', ''],
                     ['text', 'how', '做多/做少'],
                     ['number', 'num', '数值 （格式：20.00X）', ''],
                     ['text', 'fund_summarize', '基金概括'],
                     ['date', 'establish_date', '成立日期'],
                     ['text', 'scale', '基金规模(格式：1000万元)'],
                     ['text', 'share', '基金份额(格式：1000万份)'],
                     ['text', 'manage', '基金经理（经理姓名）'],
                     ['text', 'administrator', '基金管理（管理者姓名）'],
                     ['text', 'title', '视频标题'],
                     ['file', 'video_id', '视频'],
                     //            ['text', 'content', '内容'],
                     ['text', 'follow_funds', '跟随资金(格式：1000.00)'],
                     ['text', 'year_income', '年化收益(格式：10%)'],
                     ['text', 'year_max_retreat', '年均最大回撤(格式：10%)'],
                     ['text', 'hold_time', '建议持有时间(格式：2年)'],
                     ['number', 'min_invest_money', '最低投资金额(格式：2000)'],
                     ['text', 'trace_index', '跟踪指数(格式：+10%)'],
                     ['text', 'trace_title', '指数标题(如：食品饮料)'],
                     //            ['text', 'explain', '说明']
    ])
//            ->setExtraHtml($html,'form_top')
      ->addTextarea('content', '基金简介（格式 标题/内容,标题/内容）')
      ->addSelect('cate_id', '请选择分类', '请选择分类', $cate_list)
      ->addSelect('type', '请选择投资类型', '请选择投资类型', $invest_type_list)
      ->addSelect('index_type', '选择指数类型', '选择指数类型', $index_type_list)
      ->addSelect('fund_type', '请选择基金类型', '请选择基金类型', $type_list)
      ->js('fund')
      ->css('fund')
      ->setFormData($info) // 设置表单数据
      ->fetch();
  }

  //投资类型
  public function investList()
  {
    // 数据列表
    $order     = 'id desc';
    $map       = [];
    $data_list = Db::name('invest_type')->where($map)->order($order)->paginate();
    // 分页数据
    $page       = $data_list->render();
    $btn_add    = ['title' => '新增', 'icon' => 'fa fa-fw fa-plus', 'href' => url('addinvest')];
    $btn_edit   = ['title' => '编辑', 'icon' => 'fa fa-fw fa-pencil', 'href' => url('editinvest', ['id' => '__id__'])];
    $btn_delete = ['title' => '删除', 'icon' => 'fa fa-fw fa-times', 'href' => url('deleteinvest', ['ids' => '__id__'])];
    return ZBuilder::make('table')
//          ->setSearch(['name' => '名称']) // 设置搜索框
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['name', '名称'],
                     ['create_time', '时间', 'datetime'],
                     ['right_button', '操作', 'btn']
      ])->setTableName('member')
      ->addTopButton('custom', $btn_add) // 批量添加顶部按钮
      ->addRightButton('custom', $btn_edit)
      ->addRightButton('custom', $btn_delete)
      ->setRowList($data_list) // 设置表格数据
      ->fetch(); // 渲染模板
  }

  public function editinvest($id = null)
  {
    if ($id === null) $this->error('缺少参数');
    // 保存数据
    if ($this->request->isPost()) {
      $update_data = input();
      // 验证
      $data = $update_data;
        $info_type = Db::name('invest_type')->where('name', $data['name'])->find();
        if ($info_type) {
            if ($info_type['id'] != $data['id']) {
                $this->error('名称已存在');
            }
        }
      if (Db::name('invest_type')->update($data)) {
        $this->success('编辑成功', 'investlist');
      } else {
        $this->error('编辑失败');
      }
    }
    $info = Db::name('invest_type')->where('id', $id)->find();
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['text', 'name', '名称', ''],
    ])
      ->setFormData($info) // 设置表单数据
      ->fetch();
  }

  public function deleteinvest($record = [])
  {
    $ids    = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $ids    = (array)$ids;
    $map[]  = ['id', 'in', $ids];
    $result = Db::name('invest_type')->where($map)->delete();
    if (false !== $result) {
      Cache::clear();
      // 记录行为日志
      $this->success('操作成功', cookie('__forward__'));
    } else {
      $this->error('操作失败');
    }
  }

  /**
   * 新增
   * @return mixed
   * @author 蔡伟明 <314013107@qq.com>
   */
  public function addinvest()
  {
    // 保存数据
    if ($this->request->isPost()) {
      // 表单数据
      $data = input();
      $info = Db::name('invest_type')->where('name', $data['name'])->find();
      if($info){
          $this->error('类型名称已存在');
      }
      if (Db::name('invest_type')->insert($data)) {
        $this->success('新增成功', 'investlist');
      } else {
        $this->error('新增失败');
      }
    }
    return ZBuilder::make('form')->setPageTitle('新增') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['text', 'name', '名称', ''],
    ])->fetch();
  }

  //指数类型
  public function indexList()
  {
    // 数据列表
    $order   = 'id desc';
    $map     = [];
    $keyword = input('param.keyword/s', '');
    if ($keyword) {
      $map[] = ['name', 'like', "%{$keyword}%"];;
    }

    $data_list = Db::name('index_type')->where($map)->order($order)->paginate();
    // 分页数据
    $page       = $data_list->render();
    $btn_add    = ['title' => '新增', 'icon' => 'fa fa-fw fa-plus', 'href' => url('addindex')];
    $btn_edit   = ['title' => '编辑', 'icon' => 'fa fa-fw fa-pencil', 'href' => url('editindex', ['id' => '__id__'])];
    $btn_delete = ['title' => '删除', 'icon' => 'fa fa-fw fa-times', 'href' => url('deleteindex', ['ids' => '__id__'])];
    return ZBuilder::make('table')->setSearch(['name' => '名称']) // 设置搜索框
    ->addColumns([ // 批量添加数据列
                   ['id', 'ID'],
                   ['name', '名称'],
                   ['create_time', '时间', 'datetime'],
                   ['right_button', '操作', 'btn']
    ])->setTableName('member')
      ->addTopButton('custom', $btn_add) // 批量添加顶部按钮
      ->addRightButton('custom', $btn_edit)
      ->addRightButton('custom', $btn_delete)
      ->setRowList($data_list) // 设置表格数据
      ->fetch(); // 渲染模板
  }

  public function editindex($id = null)
  {
    if ($id === null) $this->error('缺少参数');
    // 保存数据
    if ($this->request->isPost()) {
      $update_data = input();
      // 验证
      $data = $update_data;
        $info_type = Db::name('index_type')->where('name', $data['name'])->find();
        if ($info_type) {
            if ($info_type['id'] != $data['id']) {
                $this->error('名称已存在');
            }
        }
      if (Db::name('index_type')->update($data)) {
        $this->success('编辑成功', 'indexlist');
      } else {
        $this->error('编辑失败');
      }
    }
    $info = Db::name('index_type')->where('id', $id)->find();
    return ZBuilder::make('form')->setPageTitle('编辑') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['hidden', 'id'],
                     ['text', 'name', '名称', ''],
    ])
      ->setFormData($info) // 设置表单数据
      ->fetch();
  }

  public function deleteindex($record = [])
  {
    $ids    = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $ids    = (array)$ids;
    $map[]  = ['id', 'in', $ids];
    $result = Db::name('index_type')->where($map)->delete();
    if (false !== $result) {
      Cache::clear();
      // 记录行为日志
      $this->success('操作成功');
    } else {
      $this->error('操作失败');
    }
  }

  /**
   * 新增
   * @return mixed
   * @author 蔡伟明 <314013107@qq.com>
   */
  public function addindex()
  {
    // 保存数据
    if ($this->request->isPost()) {
      // 表单数据
      $data = input();
        $info = Db::name('index_type')->where('name', $data['name'])->find();
        if ($info) {
          $this->error('该名称已存在');
        }
      if (Db::name('index_type')->insert($data)) {
        $this->success('新增成功', 'indexlist');
      } else {
        $this->error('新增失败');
      }
    }
    return ZBuilder::make('form')->setPageTitle('新增') // 设置页面标题
    ->addFormItems([ // 批量添加表单项
                     ['text', 'name', '名称', ''],
    ])->fetch();
  }

  public function delete($record = [])
  {
    $table_name = input('param.table');
    $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $ids        = (array)$ids;
    $field      = input('param.field', 'is_del');
    $map[]      = ['id', 'in', $ids];
    $result     = Fund::where($map)->delete();
    $map2[]     = ['fund_id', 'in', $ids];
    if (false !== $result) {
      Cache::clear();
      Db::name('fund_line')->where($map2)->delete();
      $this->success('操作成功', cookie('__forward__'));
    } else {
      $this->error('操作失败');
    }
  }

  public function enable($record = [])
  {
    return $this->setStatus('enable');
  }

  public function disable($record = [])
  {
    return $this->setStatus('disable');
  }

  /**
   * 设置用户状态：禁用、启用
   *
   * @param string $type 类型：enable/disable
   * @param array $record
   *
   * @return mixed
   * @author 蔡伟明 <314013107@qq.com> <4853332099@qq.com>
   */
  public function setStatus($type = '', $record = [])
  {
    $table_name    = input('param.table');
    $ids           = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
    $member_id     = is_array($ids) ? '' : $ids;
    $member_status = Fund::where('id', 'in', $ids)->column('status');
    return parent::setStatus($type, ['member_' . $type, 'member', $member_id, UID, implode('、', $member_status)]);
  }

  public function quickEdit($record = [])
  {
    $id      = input('post.pk', '');
    $field   = input('post.name', '');
    $value   = input('post.value', '');
    $table   = input('post.table', '');
    $status  = Fund::where('id', $id)->value($field);
    $details = '字段(' . $field . ')，原值(' . $status . ')，新值：(' . $value . ')';

    return parent::quickEdit(['member_edit', 'member', $id, UID, $details]);

  }

  public function invite()
  {
    cookie('__forward__', $_SERVER['REQUEST_URI']);
    // 获取查询条件
    $map        = $this->getMap();
    $order      = $this->getOrder();
    $admin_user = AdminUserModel::where('id', UID)->find();
    if ($admin_user['role'] == 2) {
      $map[] = ['agent_far', '=', $admin_user['for_user']];
    }
    empty($order) && $order = 'id desc';
    $map[] = ['is_del', '=', 0];
    // 数据列表
    $data_list = Member::getinvitelist($map, $order);
    if (empty($map['create_time'][1][0])) {
      $beginday = date('Ymd', time() - 2592000);//30天前
    } else {
      $beginday = date('Ymd', strtotime($map['create_time'][1][0]));
    }
    if (empty($map['create_time'][1][1])) {
      $endday = date('Ymd', time());
    } else {
      $endday = date('Ymd', strtotime($map['create_time'][1][1]));
    }
    foreach ($data_list as $key => $value) {
      $email                          = $value['email'] ?: '--';
      $name                           = $value['name'] ?: '--';
      $mobile                         = $value['mobile'] ?: '--';
      $data_list[$key]['email']       = "<p>" . $email . "</p>";
      $data_list[$key]['user_info']   = "<p>" . $name . "</p><p>" . $mobile . "</p>";
      $data_list[$key]['invite_info'] = "<p>" . $value['re_name'] . "</p><p>" . $value['re_mobile'] . "</p>";
      $data_list[$key]['time']        = "<p>" . date("m-d H:i:s", $value['create_time']) . "</p><p>" . date("m-d H:i:s", $value['last_login_time']) . "</p>";
    }
    // 分页数据
    $page = $data_list->render();
    if (empty($_SERVER["QUERY_STRING"])) {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
    } else {
      $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
    }
    $user_list   = Member::where('status', 1)->column('mobile', 'id');
    $level_array = ['普通投资者', '初级合伙人', '中级合伙人', '高级合伙人', '特级合伙人', '核心合伙人'];
    return ZBuilder::make('table')->setSearch(['name' => '姓名', 'mobile' => '手机号']) // 设置搜索框
    ->hideCheckbox()
      ->setTableName('member') // 设置数据表名
      ->addColumns([ // 批量添加数据列
                     ['id', 'ID'],
                     ['email', '被邀请人邮箱'],
                     ['user_info', '被邀请人姓名/被邀请人手机号'],
                     ['invite_info', '邀请人姓名/邀请人手机号'],
                     ['is_buy', '是否投资', ['未投资', '已投资']],
                     ['level', '会员级别', $level_array],
                     ['re_num', '直推人数'],
                     ['re_id', '推荐人', 'select', $user_list],
                     ['team_num', '团队人数'],
                     ['buy_money', '买入总额（元）'],
                     ['sell_money', '卖出总额（元）'],
                     ['time', '注册时间/最后登陆时间']
                     //               ['right_button', '操作', 'btn']
      ])
      ->setTableName('member')
      ->addTimeFilter('create_time', [$beginday, $endday]) // 添加时间段筛选
      ->addOrder('id,re_num,team_num')->setRowList($data_list) // 设置表格数据
                                                               //            ->fixedRight(1)
      ->setPages($page)                                        // 设置分页数据
//            ->setColumnWidth('login,time,right_button', 80)
      ->fetch(); // 渲染模板
  }
}

