<?php
/**
 * 设置model
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 10:44
 */
namespace app\admin\model;

class Setting_tab extends Base{
    public function getSettingInfo($name){
        return $this->where(['NAME'=>$name])->find();
    }
    public function updateSetting($value,$name){
        return $this->where(['NAME'=>$name])->update(['VALUE'=>$value]);
    }
}