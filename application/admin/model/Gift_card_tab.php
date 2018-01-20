<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/9 0009
 * Time: 17:30
 */

namespace app\admin\model;

use think\Model;
class Gift_card_tab extends Base
{
    public static $giftundone_type = 0;//未使用状态
    public static $giftjoin_type = 1;//合并充值卡
    public static $giftshare_type = 2;//转赠他人
    /**
     * 获取用户购买的积分卡
     * @param $buyerId
     */
    public function getBuyerGiftCardsByUid($buyerId){
        $data = $this->where('BUYER_ID',$buyerId)->where('AVAILABLE_FLG',1)->where('CONSUMP_TYPE',self::$giftundone_type)
            ->select();
        return $data?$data:null;
    }

    /**
     * @param $cardId
     */
    public function getGiftcardById($cardId){
       if(!$cardId){
           return null;
       }
       $data = $this->where('CARD_ID',$cardId)->find();
       return $data?$data:null;
    }
    /**
     * @param $cardNo
     */
    public function getGiftcardByNo($cardNo){
        if(!$cardNo){
            return null;
        }
        $data = $this->where('CARD_NO',$cardNo)->find();
        return $data?$data:null;
    }

    /**
     * 更新数据
     * @param $data
     * @param $cardNo
     * @return $this
     */
    public function updateGiftCardByCardNo($data,$cardNo){
        return $this->where('CARD_NO',$cardNo)->update($data);
    }
    public function getGiftcardDisableByOrderNo($order_no){
        return $this->where('ORDER_NO',$order_no)->where('AVAILABLE_FLG',0)->select();
    }
}