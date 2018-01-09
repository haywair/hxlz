<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 17:42
 */

namespace app\api\controller;


use app\admin\model\Order_tab;
use app\admin\model\Price_differences_tab;
use app\admin\model\Project_tab;
use app\wx\model\Card_operat_tab;
use app\wx\model\Member_info_tab;

class Price extends Base
{
    //生成差价单使用微信支付差价
    /**
     *$get =[
     *    'DIFF_PRICE_AMT'=>差价金额
     *    'UPDATE_USER'   =>录入员编号
     *    'ORDER_CD'      =>订单ID
     *    'REASONE_INFO'  =>差价原因
     *    'DETAILS_INFO'  =>差价详情
     * ];
     * @return string
     */
    public function priceAdd(){
        $get = input();
        $data = [
            'DIFF_PRICE_AMT'=>$get['DIFF_PRICE_AMT'],
            'DIFF_PRICE_DATE_TIME'=>date("Y-m-d h:i:s",time()),
            'UPDATE_USER' =>$get['UPDATE_USER'],
            'UPDATE_DATE' =>date("Y-m-d h:i:s",time()),
        ];
        $order = new Order_tab();
        $odata = $order->getOrderInfoByID($get['ORDER_CD']);
        $res = $order->updateOrederById($data,$get['ORDER_CD']);
        //修改成功生成差价单
        if($res){
            $data = [
                'DIFF_PRICE_NO'=>"030".time(),
                'ORDER_CD'     =>$get['ORDER_CD'],
                'USER_ID'      =>$odata['USER_ID'],
                'STORE_CD'     =>$odata['STORE_CD'],
                'PAY_AMT'      =>$get['DIFF_PRICE_AMT'],
                'REASONE_INFO' =>$get['REASONE_INFO'],
                'DETAILS_INFO' =>$get['DETAILS_INFO'],
                'CREATE_USER'  =>$get['UPDATE_USER'],
                'CREATE_DATE'  =>date("Y-m-d h:i:s",time()),
                'PAY_TYPE'    =>1 //微信支付
            ];
            $price = new Price_differences_tab();
            $re = $price->priceDifAdd($data);
            if($re){
                //此处向用户推送一条付款链接。

                return '{"code":"200","Msg":"Price_differences creation successful!"}';
            }else{
                return '{"code":"400","Msg":"unexpected error"}';
            }
        }
    }


    //生成差价单使用电子卡支付差价
    /**
     *$get =[
     *    'DIFF_PRICE_AMT'=>差价金额
     *    'UPDATE_USER'   =>录入员编号
     *    'ORDER_CD'      =>订单ID
     *    'REASONE_INFO'  =>差价原因
     *    'DETAILS_INFO'  =>差价详情
     * ];
     * @return string
     */
    public function priceAddByCard(){
        $get = input();
        $card = new Member_info_tab();
        $order = new Order_tab();
        $odata = $order->getOrderInfoByID($get['ORDER_CD']);
        $cdata = $card->getUserCard($odata['USER_ID']);
        if($cdata['RECEIVE_AMT']<$get['DIFF_PRICE_AMT']){
            return '{"code":"401","Msg":"电子卡余额不足！"}';
        }else{
            $ccdata = [
                'RECEIVE_AMT'=>$cdata['RECEIVE_AMT']-$get['DIFF_PRICE_AMT'],
                'TOTAL_CONSUMP_AMT'=>$cdata['TOTAL_CONSUMP_AMT']+$get['DIFF_PRICE_AMT'],
                'TOTAL_CONSUMP_TIMES'=>$cdata['TOTAL_CONSUMP_TIMES']+1,
            ];
            $r = $card->updataCard($ccdata,$cdata['MEMBER_CARD_NO']);
            if($r){
                $copdata = [
                    'CARD_NO'=>$cdata['MEMBER_CARD_NO'],
                    'CARD_TYPE'=>$cdata['CARD_TYPE'],
                    'CARD_OPERAT_TYPE'=>"补差价",
                    'USER_ID'=>$odata['USER_ID'],
                    'MEMBER_NAME'=>$cdata['MEMBER_NAME'],
                    'MEMBER_TEL'=>$cdata['TEL_NO'],
                    'MEMBER_SEX'=>$cdata['SEX'],
                    'STORE_CD'=>$odata['STORE_CD'],
                    'CONSUMP_AMT'=>$get['DIFF_PRICE_AMT'],
                    'AFTER_CONSUMP_AMT'=>$cdata['RECEIVE_AMT']-$get['DIFF_PRICE_AMT'],
                    'LEFT_AMT'=>$cdata['RECEIVE_AMT']-$get['DIFF_PRICE_AMT'],
                    'REMARKS'=>"补差价操作",
                ];
                $cardOP = new Card_operat_tab();
                $rr = $cardOP->cardOperatAdd($copdata);
                if($rr){
                    $data = [
                        'DIFF_PRICE_AMT'=>$get['DIFF_PRICE_AMT'],
                        'DIFF_PRICE_DATE_TIME'=>date("Y-m-d h:i:s",time()),
                        'UPDATE_USER' =>$get['UPDATE_USER'],
                        'UPDATE_DATE' =>date("Y-m-d h:i:s",time()),
                    ];

                    $res = $order->updateOrederById($data,$get['ORDER_CD']);
                    //修改成功生成差价单
                    if($res){
                        $data = [
                            'DIFF_PRICE_NO'=>"030".time(),
                            'ORDER_CD'     =>$get['ORDER_CD'],
                            'USER_ID'      =>$odata['USER_ID'],
                            'STORE_CD'     =>$odata['STORE_CD'],
                            'PAY_AMT'      =>$get['DIFF_PRICE_AMT'],
                            'REASONE_INFO' =>$get['REASONE_INFO'],
                            'DETAILS_INFO' =>$get['DETAILS_INFO'],
                            'CREATE_USER'  =>$get['UPDATE_USER'],
                            'CREATE_DATE'  =>date("Y-m-d h:i:s",time()),
                            'PAY_TYPE'    =>2,  //电子卡支付
                            'PAY_STATE'   =>1,
                        ];
                        $price = new Price_differences_tab();
                        $re = $price->priceDifAdd($data);
                        if($re){
                            return '{"code":"200","Msg":"Price_differences creation successful!"}';
                        }else{
                            return '{"code":"400","Msg":"unexpected error"}';
                        }
                    }
                }
            }
        }
    }



    /**
     * 微信支付差价，成功后差价单支付状态改为1
     *
     * */
    public function priceSucceed(){

    }
    /**
     * $get = [
     *      'STORE_CD'=>门店编号
     * ];
     *获取指定门店的差价单列表
     */
    public function getPriceList(){
        $get = input();
        $price = new Price_differences_tab();
        $data = $price->getPriceListByStore($get['STORE_CD']);
        if($data){
            return '{"code":"200","data":'.json_encode($data).'}';
        }else{
            return '{"code":"400","Msg":"unexpected error"}';
        }
    }
    /**
     * $get = [
     *      'DIFF_PRICE_NO'=>差价单ID
     *      'UPDATE_USER'=>变更员编号
     * ];
     * 删除差价单
     */
    public function delPrice(){
        $get = input();
        $price = new Price_differences_tab();
        $data = [
            'UPDATE_USER'=>$get['UPDATE_USER']
        ];
        $re = $price->priceDiffUpdate($data,$get['DIFF_PRICE_NO']);
        if($re){
            $res = $price->delPriceOne($get['DIFF_PRICE_NO']);
            if($res){
                return '{"code":"200","Msg":"Price_differences deleted successfully!"}';
            }else{
                return '{"code":"400","Msg":"unexpected error"}';
            }
        }
    }
}