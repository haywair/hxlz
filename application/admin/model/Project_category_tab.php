<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 11:39
 */

namespace app\admin\model;


class Project_category_tab extends Base
{
    public function projectCateAdd($data){
        return $this->data($data)->save();
    }
    public function getProjectCateList(){
        return $this->paginate(10,false);
    }
    public function getProjectCateFlg(){
        return $this->where(['AVAILABLE_FLG'=>1])->select();
    }
    public function updateCategoryByCID($data,$cid){
        return $this->where(['CATEGORY_CD'=>$cid])->update($data);
    }
    public function getCategoryByCID($cid){
        return $this->where(['CATEGORY_CD'=>$cid])->find();
    }
}