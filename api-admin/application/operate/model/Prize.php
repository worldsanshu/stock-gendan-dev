<?php

namespace app\operate\model;

use think\Model as ThinkModel;

/**
 * 奖品设置模型
 * @package app\Prize\model
 */
class Prize extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__OPERATE_PRIZE__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取单页标题列表
     *
     * @return array|mixed
     */
    public static function getTitleList()
    {
        $result = cache('cms_page_title_list');
        if (!$result) {
            $result = self::where('status', 1)->column('id,title');
            // 非开发模式，缓存数据
            if (config('develop_mode') == 0) {
                cache('cms_page_title_list', $result);
            }
        }
        return $result;
    }

    public static function getPage($id)
    {
        $result = cache('cms_page_' . $id);
        if (!$result) {
            $result = self::where('id', $id)->find();
            cache('cms_page_' . $id, $result);
        }
        return $result;
    }
}