<?php
namespace app\member\model;

use think\model;

class MemberMessage extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__MEMBER_MESSAGE__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 新增站内信信息
     * @param int $mid 会员ID
     * @param string $tiyle 站内信标题
     * @param array $info 站内信内容
     * @param int $type （预留）
     * @param int $code 股票代码
     * @param array data 需要加入的其他数据
     * @return mixed
     * @author 蔡伟明 <314013107@qq.com>
     */
    public static function addInnerMsg($mid = null, $title, $info, $type = 0, $code = '', $datainfo = array())
    {
        $msgtype = self::msgtype($type);
        $data['mid'] = $mid;
        $data['title'] = $title;
        $data['info'] = $info;
        $data['type'] = $type;
        $data['status'] = 0;//站内信查看状态，默认为未查看0
        printlog($datainfo, 'datainfo', 'MemberMessage');
        if ($datainfo) {
            foreach ($datainfo as $k => $v) {
                printlog($k, '$k', 'MemberMessage');
                printlog($v, '$v', 'MemberMessage');
                $data[$k] = $v;
            }
        }
        printlog($data, '入库数据', 'MemberMessage');
        $result = self::create($data);
        $content = $title . ":\r\n" . $info;
//        vendor("jpush.push");
//        $jspush = new \push();
//        $jspush->puhs_single((string)$mid, $title, $info, $msgtype, $code);
        return $result;
    }

    public static function getAll($map = [], $order = '', $listRow)
    {
        $data_list = self::view('member_message mm', true)
          ->view('member m', 'mobile, name, id_card', 'm.id = mm.mid')
          ->where($map)
          ->order($order)
          ->paginate($listRow, false, [
            'query' => input('param.')
          ]);
        return $data_list;
    }

    public static function msgtype($type)
    {
        $msgtype = [1 => 'notice1', 2 => 'notice2', 3 => 'notice3', 4 => 'notice4', 5 => 'notice5', 6 => 'notice6', 7 => 'notice7', 8 => 'notice8', 9 => 'notice9', 10 => 'notice10', 20 => 'notice20'];
        $notice = isset($msgtype[$type]) ? $msgtype[$type] : '';
        return $notice;
    }
}

?>
