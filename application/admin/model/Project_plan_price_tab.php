<?php
/**
 * 价格优惠方案model
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 17:02
 */
namespace app\admin\model;

class Project_plan_price_tab extends Base{
    /**
     *价格 优惠方案列表
     */
    public function getPriceatList(){
        return $this->paginate(15,false);
    }
    /**
     * 添加价格优惠方案
     */
    public function priceatAdd($data){
        return $this->data($data)->save();
    }
    /**
     * 更新优惠方案
     */
    public function priceatUpdate($data,$plan_cd){
        return $this->where(['PLAN_CD'=>$plan_cd])->update($data);
    }
    /**
     * 优惠方案信息
     */
    public function getPriceatInfo($plan_cd){
        return $this->where(['PLAN_CD'=>$plan_cd])->find();
    }
    /**
     * 查询项目的活动信息
     */
    public function getProjectPriceatInfo($project_id){
        $sql = "select * from PROJECT_PLAN_PRICE_TAB where charindex('".$project_id."',PROJECT_CD)>0 ";
		/*$sql = "select * from PROJECT_PLAN_PRICE_TAB where '".$project_id."' in (select * from dbo.f_split(PROJECT_CD,',
		')) ";*/
        $info = $this->query($sql);
        if(!empty($info)){
            $priceatInfo = $info[0];
            return $priceatInfo;
        }else{
            return '';
        }
    }

}