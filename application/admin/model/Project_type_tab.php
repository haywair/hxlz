<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 10:15
 */
namespace app\admin\model;
class Project_type_tab extends Base
{
    public function projectTypeAdd($data){
        return $this->data($data)->save();
    }
    public function getProjectTypeFlg(){
        return $this->where(['AVAILABLE_FLG'=>1])->select();
    }
    public function getTypeInfoById($id){
       return $this->where(['TYPE_ID'=>$id])->find();
    }
    public function updateTypeInfoById($data,$id){
        return $this->where(['TYPE_ID'=>$id])->update($data);
    }
}