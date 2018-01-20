<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/26
 * Time: 14:38
 */

namespace app\wx\model;


class Member_info_tab extends Base
{
    /**
     * 生成电子卡基本信息
     * */
    public function addElectronic_card($data){
        return $this->data($data)->save();
    }
    /**
     * 设置电子卡支付密码
     * */
    public function setCardPwd($user_id,$pwd){
        return $this->where(['USER_ID'=>$user_id])->update(['MEMBER_PASS'=>$pwd]);
    }

    /**
     * 根据用户ID查询电子卡
     * */
    public function getUserCard($userid){
        return $this->where(['USER_ID'=>$userid])->find();
    }

    /**
     * 根据卡号查询电子卡
     * @param $card_no
     */
    public function getUserCardByCardNo($card_no){
        return $this->where('MEMBER_CARD_NO',$card_no)->find();
    }
    public function updataCard($data,$cid){
        return $this->where(['MEMBER_CARD_NO'=>$cid])->update($data);
    }

}