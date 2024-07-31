<?php
namespace app\common\service;

use think\Model;
use think\Db;

class AreaService extends BaseService
{
    public function importArea (){
        $province = file_get_contents(app()->getRootPath() . 'public' . '/json/cities.json');
        $province = json_decode($province, true);
        //dump($province);
        foreach ($province as $value) {
            Db::name('area_vn')->insert($value);
        }
        
        $city = file_get_contents(app()->getRootPath() . 'public' . '/json/districts.json');
        $city = json_decode($city, true);
        //dump($city);
        foreach ($city as $value) {
            $find = Db::name('area_vn')->where('code', $value['parent_code'])->find();
            $value['reid'] = $find['id'];
            Db::name('area_vn')->insert($value);
        }
        
        $area = file_get_contents(app()->getRootPath() . 'public' . '/json/wards.json');
        $area = json_decode($area, true);
        //dump($area);
        foreach ($area as $value) {
            $find = Db::name('area_vn')->where('code', $value['parent_code'])->find();
            $value['reid'] = $find['id'];
            Db::name('area_vn')->insert($value);
        }
    }


}