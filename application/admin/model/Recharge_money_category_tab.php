<?php
/**
 * 充值金额类型model
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21 0021
 * Time: 17:40
 */
namespace app\admin\model;

class Recharge_money_category_tab extends Base{
    /**
     * 充值金额列表
     */
    public $recharge_member_type = 1;
    public $recharge_gift_type = 2;
    public function getRechargeList($condition){
        return $this->where($condition)->paginate(15,false);
    }
    /**
     * 电子卡充值金额列表(无分页)
     */
    public function getRechargeListNP($condition){
        $condition['RECHARGE_TYPE'] = $this->recharge_member_type;
        return $this->where($condition)->select();
    }
    /**
     * 转赠卡充值金额列表(无分页)
     */
    public function getGiftRechargeListNP($condition){
        $condition['RECHARGE_TYPE'] = $this->recharge_gift_type;
        return $this->where($condition)->select();
    }
    /**
     * 充值类型数量
     */
    public function getRechargeCateNum($condition){
        return $this->where($condition)->count();
    }
    /**
     * 添加充值金额的
     */
    public function rechargeAdd($data){
        return $this->data($data)->save();
    }
    /**
     * 获取充值金额信息
     */
    public function getRechargeInfoById($rechargeID){
        return $this->where(['RECHARGE_ID'=>$rechargeID])->find();
    }
    /**
     * 更新充值金额信息
     */
    public function updateRechargeInfoById($data,$rechargeId){
        return $this->where(['RECHARGE_ID'=>$rechargeId])->update($data);
    }
}

