<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 11:55
 */

namespace app\admin\model;


class Project_unit_tab extends Base
{
    public function projectUnitAdd($data){
        return $this->data($data)->save();
    }
    public function getProjectUnitList(){
        return $this->paginate(10,false);
    }
    public function getProjectUnitFlg(){
        return $this->where(['AVAILABLE_FLG'=>1])->select();
    }
    public function getUnitInfoByUID($uid){
        return $this->where(['PROJECT_UNIT_CD'=>$uid])->find();
    }
    public function updateUnitInfoByUID($data,$uid){
        return $this->where(['PROJECT_UNIT_CD'=>$uid])->update($data);
    }
}