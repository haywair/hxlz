<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/9 0009
 * Time: 17:08
 */

namespace app\wx\base;
use app\wx\model\User_tab;
use app\wx\model\Member_info_tab;
use app\admin\model\Gift_card_tab;
use app\admin\model\Project_plan_price_tab;
use think\Exception;

class GiftcardCharge extends Controller
{
    public function dealGiftcardCharge($consump_amt,$gift_amt,$num,$user_id,$order_no,$sale_percent = 0){
        //创建数据
        $cardData = [];
        $total_amt = $consump_amt + $gift_amt;
        if(($sale_percent > 0) && $sale_percent < 1){
            $consump_amt = $consump_amt * $sale_percent;
        }
        $market_rate = round(($consump_amt/$total_amt),2);
        $finance_rate = round(($consump_amt/$total_amt),2);
        if($num > 1){
            for($i=1;$i<=$num;$i++){
                $cardData[] = $this->createGiftcardData($total_amt,$consump_amt,$gift_amt,$user_id,$order_no,
                    $market_rate,$finance_rate);
            }
        }else{
            $cardData = $this->createGiftcardData($total_amt,$consump_amt,$gift_amt,$user_id,$order_no,$market_rate,$finance_rate);
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
    private function createGiftcardData($total_amt,$consump_amt,$gift_amt,$user_id,$order_no,$market_rate = 0.00,
        $finance_rate=0.00){
        $cardData = [
            'CONSUMP_AMT'   =>  $consump_amt,
            'GIFT_AMT'      =>  $gift_amt,
            'CARD_NO'       =>  $this->createGiftcardNo(),
            'BUY_USER'      =>  $user_id,
            'TOTAL_AMT'     =>  $total_amt,
            'ORDER_NO'      =>  $order_no,
            'CREATE_TIME'   =>  time(),
            'UPDATE_TIME'   =>  time(),
            'CARD_DISC_RATE_MARKET'     => $market_rate,
            'CARD_DISC_RATE_FINANCE'    => $finance_rate
        ];
        return $cardData;
    }
    public function giftcardToMembercard($card_no,$card_id,$open_id,$type){
        $gift_model = new Gift_card_tab();
        $membercard_model = new Member_info_tab();
        $userInfo = (new User_tab())->openidGetUserOne($open_id);
        $memberInfo = $membercard_model-> getUserCard($userInfo['USER_ID']);
        $cardInfo = $gift_model->getGiftcardById($card_id);
        $error = new Error();
        if(!$userInfo){
            $error->setError('101', '无该用户信息', null);
        }
        if(!$memberInfo){
            $error->setError('101', '未找到用户匹配的电子卡', null);
        }
        if(!$cardInfo){
            $error->setError('101', '未找到匹配的充值卡', null);
        }
        if(($cardInfo['AVAILABLE_FLG'] == 0) && ($cardInfo['CONSUMP_TYPE'] > 0)){
            $error->setError('104','非常抱歉，该充值卡已被领取',null);
        }
        //电子卡合并
        $memberData = [
            'RECEIVE_AMT'               => $memberInfo['RECEIVE_AMT'] + $cardInfo['CONSUMP_AMT'],
            'GIVE_AMT'                  => $memberInfo['GIVE_AMT'] + $cardInfo['GIFT_AMT'],
            'TOTAL_PAY_AMT'             => $memberInfo['TOTAL_PAY_AMT'] + $cardInfo['CONSUMP_AMT'],
            'TOTAL_GIVE_AMT'            => $memberInfo['TOTAL_GIVE_AMT'] + $cardInfo['GIFT_AMT'],
            'UPDATE_DATE'               => date('Y-m-d H:i:s')
        ];
        //积分卡
        $giftcardData = [
            'CONSUMP_TYPE'  =>  $type,
            'TO_USER'       =>  $userInfo['USER_ID'],
            'AVAILABLE_FLG' =>  0,
            'UPDATE_TIME'   =>  time()
        ];
        try{
            $gift_model->startTrans();
            $res_gift = $gift_model->updateGiftCardByCardNo($giftcardData,$cardInfo['CARD_NO']);
            $res_member = $membercard_model->updataCard($memberData,$memberInfo['MEMBER_CARD_NO']);
            $gift_model->commit();
            $error->setOk();
        }catch(Exception $e){
            $gift_model->rollback();
            $error->setError('105','充值卡合并失败');
        }
        return $error;
    }


}