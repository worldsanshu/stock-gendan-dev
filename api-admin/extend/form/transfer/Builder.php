<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace form\transfer;
/**
 * Class Builder
 * @package form\transfer
 */
class Builder
{
    /**
     * 穿梭框
     * @param string $name 表单项名
     * @param string $title1 标题1
     * @param string $title2 标题2
     * @param string $tips 提示
     * @param array $fields 可选字段
     * @param array $data 选中值
     * @return array
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function item($name = '', $title1 = '', $title2 = '', $tips = '', $fields = [], $data = [])
    {
        if (!empty($data)) {
            $data = array_flip($data);
            foreach ($data as $key => $value) {
                if (isset($fields[$key])) {
                    $data[$key] = $fields[$key];
                    unset($fields[$key]);
                }
            }
        }
        return [
          'name' => $name,
          'title1' => $title1,
          'title2' => $title2,
          'tips' => $tips,
          'fields' => $fields,
          'data' => $data
        ];
    }

    /**
     * @var array 需要加载的js
     */
    public $js = [
      'transfer.js'
    ];
}