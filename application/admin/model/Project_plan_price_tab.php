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
    public static $priceattype_giftcard = 2;
    public static $priceattype_project = 1;
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
    /**
     * 查询充值卡的活动信息
     */
    public function getGiftcardPriceatInfo(){
        $data = $this->where('type',self::$priceattype_giftcard)->where('AVAILABLE_FLG',1)->order('PLAN_CD desc')->find();
        $day    = intval(date('m'));
        $date   = strtotime(date('Y-m-d'));
        $salePercent = 0.00;
        if($data){
            if($data['LEVEL_ONE'] && $data['LEVEL_ONE_SALE_PRICE'] > 0){
                $priceatInfo = getPriceatCategoryInfo($data['LEVEL_ONE']);
                $salePercent = (strtoime($priceatInfo['LEVEL_RULE']) == $date)?$data['LEVEL_ONE_SALE_PRICE']:null;
            }else if($data['LEVEL_TWO'] && $data['LEVEL_TWO_SALE_PRICE'] > 0){
                $priceatInfo = getPriceatCategoryInfo($data['LEVEL_ONE']);
                $days = explode(',',$priceatInfo['LEVEL_RULE']);
                $salePercent =  (in_array($day,$days))?$data['LEVEL_TWO_SALE_PRICE']:null;
            }else if($data['LEVEL_THREE'] && $data['LEVEL_THREE_SALE_PRICE'] > 0){
                $salePercent = $data['LEVEL_THREE_SALE_PRICE'];
            }
            $salePercent = ($salePercent < 1)?$salePercent:0;
        }
        return $salePercent;
    }
    /**
     * 充值卡优惠方案类型数量
     */
    public function getGiftCardPriceatNum(){
        $count = $this->where('type',self::$priceattype_giftcard)->where('AVAILABLE_FLG',1)->count();
        return $count;
    }

}