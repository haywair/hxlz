<?php
/**
 * 价格优惠类型model
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 17:06
 */
namespace app\admin\model;

class Price_plan_category_tab extends Base{
    /**
     * 优惠类型列表(分页)
     */
    public function getCategoryList($condition){
        return $this->where($condition)->paginate(15,false);
    }
    /**
     *
     */
    public function getCategoryListCon($condition){
        return $this->where($condition)->select();
    }
    /**
     * 添加优惠类型
     */
    public function priceCategoryAdd($data){
        return $this->data($data)->save();
    }
    /**
     * 更新优惠类别
     */
    public function priceatCategoryUpdate($data,$cl_id){
        return $this->where(['CL_ID'=>$cl_id])->update($data);
    }
    /**
     * 获取优惠类别信息
     */
    public function getPriceatCategoryInfo($cl_id){
        return $this->where(['CL_ID'=>$cl_id])->find();
    }

}
