<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6 0006
 * Time: 8:37
 */
namespace app\admin\model;

class Refunds_tab extends Base{
    /**
     * 退款单列表
     */
    public function getRefundsList($condition=[]){
        return $this->alias('a')
                    -> join('STORE_TAB b','a.STORE_CD = b.STORE_CD','LEFT')
                    -> join('USER_TAB c','a.USER_ID = c.USER_ID','LEFT')
                    -> field('a.*,c.NICK_NAME as USER_NAME,b.STORE_NAME')
                    -> where($condition)
                    -> order('CREATE_DATE DESC')
                    -> paginate(15,false,['query' => request()->param()]);
    }
    /**
     * 退款单数量
     */
    public function getRefundsNum($condition){
        return $this->where($condition)->count();
    }
    public function refundsAdd($data){
        return $this->data($data)->save();
    }
    public function getRefundsListByStore($sid){
        return $this->where(['STORE_CD'=>$sid])->select();
    }
    public function getRefundsInfoById($id){
        return $this->alias('a')
                    -> join('USER_TAB b','a.USER_ID = b.USER_ID','LEFT')
                    -> join('STORE_TAB c','c.STORE_CD = A.STORE_CD')
                    -> field('a.*,b.USER_NAME,c.STORE_NAME')
                    ->where(['REFUNDS_NO'=>$id])
                    ->find();
    }
    public function delRefundsOne($data,$rid){
        return $this->where(['REFUNDS_NO'=>$rid])->update($data);
    }
    public function updaterRefundsById($data,$rid){
        return $this->where(['REFUNDS_NO'=>$rid])->update($data);
    }
}