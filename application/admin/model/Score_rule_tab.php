<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20 0020
 * Time: 17:34
 */

namespace app\admin\model;


class Score_rule_tab extends Base
{
    /**
     * 积分类型列表
     */
    public function getScoreCategoryList($condition){
        return $this->where($condition)->paginate(15,false);
    }
    /**
     * 积分类型列表(无分页)
     */
    public function getScoreCategoryListNP($condition){
        return $this->where($condition)->select();
    }
    /**
     * 积分类型数量
     */
    public function getScoreCategoryNum($condition){
        return $this->where($condition)->count();
    }
    /**
     * 添加积分类型
     */
    public function scoreCategoryAdd($data){
        return $this->data($data)->save();
    }
    /**
     * 获取积分类型信息
     */
    public function getScoreCategoryById($category_id){
        return $this->where(['CATEGORY_ID'=>$category_id])->find();
    }
    /**
     * 更新积分信息
     */
    public function updateRechargeInfoById($data,$category_id){
        return $this->where(['CATEGORY_ID'=>$category_id])->update($data);
    }
}