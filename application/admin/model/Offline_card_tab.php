<?php
/**
 * Created by PhpStorm.
 * 实体卡model
 * User: Administrator
 * Date: 2017/8/2 0002
 * Time: 10:06
 */
namespace app\admin\model;

class Offline_card_tab extends Base{
    //添加新卡
    public function cardAdd($data){
        return $this->data($data)->save();
    }
    public function getCardsFlg($condition=[]){
        $field = 'a.*,b.TEL_NO,b.USER_NAME';
        $join = [
            ['USER_TAB b','a.USER_ID = b.USER_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->select();
    }
    public function getCardInfoById($id){
        $field = 'a.*,b.USER_NAME,b.TEL_NO';
        $join = [
            ['USER_TAB b','a.USER_ID = b.USER_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where(['OFFLINE_CARD_ID'=>$id])->find();
    }
    public function updateCardInfoById($data,$id){
        return $this->where(['OFFLINE_CARD_ID'=>$id])->update($data);
    }
    public function getCardInfoByCardNo($memberCardNo){
        if(!$memberCardNo){
            return null;
        }
        return $this->where(['MEMBER_CARD_NO'=>$memberCardNo])->find();
    }
    //获取实体卡数量
    public function getCardNum($condition=[]){
        return $this->where($condition)->count();
    }
}
