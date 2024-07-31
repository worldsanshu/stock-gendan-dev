<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
namespace form\dataTable;
/**
 * Class Builder
 * @package form\dataTable
 */
class Builder
{
    /**
     * 显示表格
     * @param string $name 表单项名
     * @param string $title 标题
     * @param string $tips 提示
     * @param array $fields 字段
     * @param array $data 数据
     * @return array
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function item($name = '', $title = '', $tips = '', $fields = [], $data = [])
    {

        foreach ($fields as $key => $field) {
            if (is_string($field)) {
                $fields[$key] = [
                  'type' => 'text',
                  'title' => $field,
                ];
            }
        }
        $data=[
          'name' => $name,
          'title' => $title,
          'tips' => $tips,
          'fields' => $fields,
          'data' => $data
        ];

        return $data;
    }

    /**
     * @var array 需要加载的js
     */
    public $js = [
      'datatable.js'
    ];
}