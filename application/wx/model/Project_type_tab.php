<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 10:40
 */

namespace app\wx\model;


class Project_type_tab extends Base
{
    public function pTypeAdd($data){
        return $this->save($data);
    }
    public function getProjectTypeList(){
        return $this->paginate(10,false);
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