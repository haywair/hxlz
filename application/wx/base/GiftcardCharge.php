<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/9 0009
 * Time: 17:08
 */

namespace app\wx\base;

use app\admin\model\Gift_card_tab;
class GiftcardCharge extends Controller
{
    public function dealGiftcardCharge($consump_amt,$gift_amt,$num,$user_id,$order_no){
        //创建数据
        $cardData = [];
        if($num > 1){
            for($i=1;$i<=$num;$i++){
                $cardData[] = $this->createGiftcardData($consump_amt,$gift_amt,$user_id,$order_no);
            }
        }else{
            $cardData = $this->createGiftcardData($consump_amt,$gift_amt,$user_id,$order_no);
        }
        $res = (new Gift_card_tab())->saveAll($cardData);
        return $res;
    }

    /**
     * 生成卡号
     * @return string
     */
    private function createGiftcardNo(){
        $cardNo = 'GF'.time().rand(10000,9999);
        return $cardNo;
    }

    /**
     * 生成礼品卡数据
     * @param $consump_amt
     * @param $gift_amt
     * @param $user_id
     * @return array
     */
    private function createGiftcardData($consump_amt,$gift_amt,$user_id,$order_no){
        $total_amt = $consump_amt + $gift_amt;
        $cardData = [
            'CONSUMP_AMT'   =>  $consump_amt,
            'GIFT_AMT'      =>  $gift_amt,
            'CARD_NO'       =>  $this->createGiftcardNo(),
            'BUY_USER'      =>  $user_id,
            'TOTAL_AMT'     =>  $total_amt,
            'ORDER_NO'      =>  $order_no,
            'CREATE_TIME'   =>  time(),
            'UPDATE_TIME'   =>  time()
        ];
        return $cardData;
    }

}