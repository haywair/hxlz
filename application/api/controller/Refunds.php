<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 13:52
 */

namespace app\api\controller;


use app\admin\model\Order_tab;
use app\admin\model\Refunds_tab;
use app\wx\model\Card_operat_tab;
use app\wx\model\Member_info_tab;
use app\wx\model\User_tab;

class Refunds extends Base
{
    /**
     * 生成退款单
     * $post = [
     *      'ORDER_CD'=>预约单号
     *      'REFUND_AMT'=>退款金额
     *      'STORE_CD'=>门店编号
     *      'REASONE_INFO'=>退款原因
     *      'DETAILS_INFO'=>退款详情
     *      'CREATE_USER'=>录入员编号
     * ]
     * */
    public  function refundsAdd(){
        $post = input();
        $order = new Order_tab();
        $card_model = new Member_info_tab();
        $user_model = new User_tab();
        //预约单信息
        $odata = $order->getOrderInfoByID($post['ORDER_CD']);
        //用户信息
        $userInfo = $user_model->idGetUserOne($odata['USER_ID']);
        //电子卡信息
        $cardInfo = $card_model->getUserCard($odata['USER_ID']);
        //订单支付金额
        $wx_pay = $odata['PAY_AMT']?$odata['PAY_AMT']:0;
        $card_pay = $odata['PAY_AMT2']?$odata['PAY_AMT2']:0;
        $order_pay = $wx_pay + $card_pay;

        if(!$post['REFUND_AMT'] > $order_pay){
            $data = [
                'REFUND_AMT'=>$post['REFUND_AMT'],
                'CANCEL_DATE_TIME'=>date("Y-m-d h:i:s",time()),
                'CANCEL_REASON' => $post['REASONE_INFO'],
                'UPDATE_USER' => $post['CREATE_USER'],
                'UPDATE_DATE'=>date("Y-m-d h:i:s",time()),
            ];
            if( $post['REFUND_AMT']== $order_pay){
                $data['ORDE_STATUS'] = 8;
                $res = $order->updateOrederById($data,$post['ORDER_CD']);
            }

            $wx_refund = '';
            $card_refund = '';
            $pay_type = '';
            $dataCard_r = [];
            if($odata['PAY_TYPE1']){
                $pay_type = 1;//微信
                $wx_refund = $post['REFUND_AMT'];
                $card_refund = 0;
                $refund_type = 1;
            }else if($odata['PAY_TYPE2']){
                $pay_type = 2;//电子卡
                $card_refund = $post['REFUND_AMT'];
                $wx_refund = 0;
                $refund_type = 2;
                //根据财务折扣率计算电子卡退款
                if($cardInfo['CARD_DISC_RATE_FINANCE']) {
                   // $dataCard_r['RECEIVE_AMT'] = $post['REFUND_AMT'] *   $cardInfo['CARD_DISC_RATE_FINANCE']+$cardInfo['RECEIVE_AMT'];
                   // $dataCard_r['GIVE_AMT'] = $post['REFUND_AMT'] * (1 - $cardInfo['CARD_DISC_RATE_FINANCE']) +$cardInfo['GIVE_AMT'];
                   $receive_amt =  $post['REFUND_AMT'] *   $cardInfo['CARD_DISC_RATE_FINANCE'];
                   $give_amt =  $post['REFUND_AMT'] * (1 - $cardInfo['CARD_DISC_RATE_FINANCE']);
                   $remainR_amt = $receive_amt+$cardInfo['RECEIVE_AMT'];
                   $remainG_amt = $give_amt+$cardInfo['GIVE_AMT'];
                }else{
                    $receive_amt = $post['REFUND_AMT'];
                    $remainR_amt = $receive_amt+$cardInfo['RECEIVE_AMT'];
                    $remainG_amt = $cardInfo['GIVE_AMT'];
                }
                //$dataCard_r['TOTAL_CONSUMP_AMT'] = $cardInfo['TOTAL_CONSUMP_AMT']-$post['REFUND_AMT'];
                $total_consump_amt = $cardInfo['TOTAL_CONSUMP_AMT']-$post['REFUND_AMT'];
            }else if($odata['PAY_TYPE4']){
                $pay_type = 4;//混合支付
                $refund_type = 4;
                //计算微信和电子卡的退款金额
                if(!$post['REFUND_AMT'] > $odata['PAY_AMT2']){
                   $wx_refund = 0;
                   $card_refund = $post['REFUND_AMT'];
                }else{
                   $wx_refund = $post['REFUND_AMT'] - $odata['PAY_AMT2'];
                   $card_refund = $odata['PAY_AMT2'];
                }
                //根据财务折扣率计算电子卡退款
                if($cardInfo['CARD_DISC_RATE_FINANCE']) {
                    //$dataCard_r['RECEIVE_AMT'] =  $card_refund*$cardInfo['CARD_DISC_RATE_FINANCE']+$cardInfo['RECEIVE_AMT'];
                    //$dataCard_r['GIVE_AMT'] = $card_refund * (1 - $cardInfo['CARD_DISC_RATE_FINANCE'])+$cardInfo['GIVE_AMT'];
                    $receive_amt =  $card_refund*$cardInfo['CARD_DISC_RATE_FINANCE'];
                    $give_amt =  $card_refund * (1 - $cardInfo['CARD_DISC_RATE_FINANCE']);
                    $remainR_amt = $receive_amt+$cardInfo['RECEIVE_AMT'];
                    $remainG_amt = $give_amt+$cardInfo['GIVE_AMT'];
                }else{
                    //$dataCard_r['RECEIVE_AMT'] = $card_refund+$cardInfo['RECEIVE_AMT'];
                    $receive_amt =  $card_refund;
                    $remainR_amt = $receive_amt+$cardInfo['RECEIVE_AMT'];
                    $remainG_amt = $cardInfo['GIVE_AMT'];
                }
                //$dataCard_r['TOTAL_CONSUMP_AMT'] = $cardInfo['TOTAL_CONSUMP_AMT']-$card_refund;
                $total_consump_amt = $cardInfo['TOTAL_CONSUMP_AMT']-$card_refund;
            }

           // if($res){
                //生成退款单
                $data = [
                    'REFUNDS_NO'=>"040".time(),
                    'ORDER_CD' =>$post['ORDER_CD'],
                    'USER_ID' =>$odata['USER_ID'],
                    'STORE_CD'=>$post['STORE_CD'],
                    'PAY_AMT' =>  $order_pay,
                    'PAY_TYPE'=> $pay_type,
                    'REFUNDS_AMT' => $post['REFUND_AMT'],
                    'REFUNDS_TYPE'=> $refund_type,
                    'REASONE_INFO'=>$post['REASONE_INFO'],
                    'DETAILS_INFO'=>$post['DETAILS_INFO'],
                    'CREATE_USER'=>$post['CREATE_USER'],
                    'CREATE_DATE'=>date("Y-m-d h:i:s",time()),
                ];
                //这里执行微信退款
                if(!empty($wx_refund)){
                    $resultWX = $this->createWxRefund($data['REFUNDS_NO'],$odata['WX_ORDER_NO'],$post['REFUND_AMT'],$odata['ORDER_AMT'], $post['CREATE_USER']);
                }
                if(!empty($card_refund) && $dataCard_r){
                    $card_operate_model = new Card_operat_tab();
                    $dataCard_r = [
                        'RECEIVE_AMT'=> $remainR_amt,
                        'GIVE_AMT'=> $remainG_amt,
                        'TOTAL_CONSUMP_AMT'=> $total_consump_amt,
                        'UPDATE_USER' => $post['CREATE_USER'],
                        'UPDATE_DATE' => date('Y-m-d H:i:s',time())
                    ];
                    $dataOperate_r = [
                        'CARD_NO' => $cardInfo['MEMBER_CARD_NO'],
                        'USER_ID' => session('user_id'),
                        'CARD_OPERAT_TYPE' => '退款',
                        'CARD_TYPE' => $cardInfo['CARD_TYPE'],
                        'MEMBER_NAME' => $userInfo['USER_NAME'],
                        'MEMBER_TEL' => $userInfo['TEL_NO'],
                        'MEMBER_SEX' => $userInfo['SEX'],
                        'CONSUMP_AMT' => $receive_amt,
                        'GIVE_AMT' => $give_amt,
                        'AFTER_CONSUMP_AMT' => $remainR_amt,
                        'AFTER_GIVE_AMT' => $remainG_amt,
                        'LEFT_AMT' => $remainR_amt+$remainG_amt,
                        'INVOICE_FLG' => 0,
                        'AVAILABLE_FLG' => 1,
                        'CREATE_USER' => session('user_id'),
                        'UPDATE_USER' => session('user_id'),
                        'CREATE_DATE' => date('Y-m-d H:i:s',time()),
                        'UPDATE_DATE' => date('Y-m-d H:i:s',time())
                    ];
                    $resultCard = $card_model->updataCard($dataCard_r,$cardInfo['MEMBER_CARD_NO']);
                    $resultOperate = $card_operate_model->cardOperatAdd($dataOperate_r);
                }
                $pefund = new Refunds_tab();
                $res = $pefund->refundsAdd($data);
                if($res){
                    return '{"code":"200","Msg":"Refund creation successful!"}';
                }else{
                    return '{"code":"400","Msg":"unexpected error"}';
                }
            //}
        }else{
            return '{"code":"400","Msg":"退款金额不能大于订单支付的金额!"}';
        }

    }
    /**
     * 获取指定门店的退款单列表
     *
     * $post = [
     *      'STORE_CD'=>门店编号
     * ]
     * */
    public function getRefundsList(){
        $post = input();
        $refunds = new Refunds_tab();
        $data = $refunds->getRefundsListByStore($post['STORE_CD']);
        if($data){
            return '{"code":"200","data":'.json_encode($data).'}';
        }else{
            return '{"code":"400","Msg":"unexpected error"}';
        }
    }
    /**
     * 删除一个退款单
     * $post=[
     *      'REFUNDS_NO'=>退款单号
     *      'UPDATE_USER'=>变更员编号
     * ]
     * */
    public function delRefundsOne(){
        $post = input();
        if($post['UPDATE_USER']){
            $data = [
                'UPDATE_USER'=>$post['UPDATE_USER'],
                'UPDATE_DATE'=>date("Y-m-d h:i:s",time()),
                'AVAILABLE_FLG'=>0
            ];
            $refunds = new Refunds_tab();
            $res = $refunds->delRefundsOne($data,$post['REFUNDS_NO']);
            if($res){
                return '{"code":"200","Msg":"Refunds deleted successfully!"}';
            }else{
                return '{"code":"400","Msg":"unexpected error"}';
            }
        }else{
            return '{"code":"400","Msg":"必须输入变更员编号"}';
        }
    }
    /**
     * 生成微信退款
     */
    private function createWxRefund($out_refund_no,$out_trade_no,$refund_fee,$total_fee,$op_user_id){
        /*ini_set('date.timezone','Asia/Shanghai');
        //error_reporting(E_ERROR);
        include_once '/extend/weixinPay/lib/WxPay.Api.php';
        include_once '/extend/weixinPay/pay/log.php';

        $logHandler= new CLogFileHandler(EXTEND_PATH."weixinPay/logs/".date('Y-m-d').'.log');
        $log = Log::Init($logHandler, 15);

        if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
            $out_trade_no = $_REQUEST["out_trade_no"];
            $total_fee = $_REQUEST["total_fee"];
            $refund_fee = $_REQUEST["refund_fee"];
            $input = new \WxPayRefund();
            $input->SetOut_trade_no($out_trade_no);
            $input->SetTotal_fee($total_fee);
            $input->SetRefund_fee($refund_fee);
            $input->SetOut_refund_no(\WxPayConfig::MCHID.date("YmdHis"));
            $input->SetOp_user_id(\WxPayConfig::MCHID);
            printf_info(\WxPayApi::refund($input));
            exit();
        }*/
        $str = "appid=".$appId;
        $str .= '&mch_id='.$mch_id;
        $str .= '&nonce_str='.$nonce_str;
        $str .= '&op_user_id='.$op_user_id;
        $str .= '&out_refund_no='.$out_refund_no;
        $str .= '&out_trade_no='.$out_trade_no;
        $str .= '&refund_fee='.$refund_fee;
        $str .= '&total_fee='.$total_fee;
        $str .= '$key='.$key;
        $ref = strtoupper(md5($str));

        $refund = [
            'appid' => $appId,
            'mch_id' => $mch_id,
            'nonce_str' => $nonce_str,
            'op_user_id' => $op_user_id,
            'out_trade_no' => $out_trade_no,
            'out_refund_no' => $out_refund_no,
            'refund_fee' => $refund_fee,
            'total_fee' => $total_fee,
            'sign' => $ref
        ];

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $xml = arrayToXml($refund);

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);//证书检查
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
        curl_setopt($ch,CURLOPT_SSLCERT,dirname(__FILE__).'/cert/apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
        curl_setopt($ch,CURLOPT_SSLKEY,dirname(__FILE__).'/cert/apiclient_key.pem');
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
        curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).'/cert/rootca.pem');
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);

        $data=curl_exec($ch);
        if($data){ //返回来的是xml格式需要转换成数组再提取值，用来做更新
            curl_close($ch);
            var_dump($data);
        }else{
            $error=curl_errno($ch);
            echo "curl出错，错误代码：$error"."<br/>";
            curl_close($ch);
            echo false;
        }
    }
    /**
     * 数组转xml
     */
    function arrayToXml($arr){
        $xml = "<root>";
        foreach ($arr as $key=>$val){
            if(is_array($val)){
                $xml.="<".$key.">".arrayToXml($val)."</".$key.">";
            }else{
                $xml.="<".$key.">".$val."</".$key.">";
            }
        }
        $xml.="</root>";
        return $xml ;
    }
}