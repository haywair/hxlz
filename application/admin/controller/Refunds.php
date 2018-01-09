<?php
/**
 * 退款
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6 0006
 * Time: 8:33
 */
namespace app\admin\Controller;

use app\admin\model\Order_tab;
use app\admin\model\Refunds_tab;
use app\admin\model\Room_order_time_tab;
use app\wx\model\Card_operat_tab;
use app\wx\model\Member_info_tab;
use app\wx\model\User_tab;
use think\Request;

class Refunds extends Base{
    private $appId = 'wx185a4ed8311601a9';
    private $mch_id = '1484162892';
    private $nonce_str;
    private $key = '6bec1f6fc31092f968b8ca7577f8b74d';
    /**
     * 退款单列表
     */
    public function index(){		
        $refunds_model = new Refunds_tab();
        $order_cd = input('order_cd');
        $condition = array();
        if(!empty($order_cd)){
            $condition['ORDER_CD'] = array('LIKE','%'.$order_cd.'%');
            $this->assign('order_cd',$order_cd);
        }
        $list = $refunds_model->getRefundsList($condition);
        $page = $list->render();
        $refundsNum = $refunds_model->getRefundsNum($condition);
        $this->assign('page',$page);
        $this->assign('list',$list);
        $this->assign('refundsNum',$refundsNum);
        return $this->fetch();
    }
    /**
     *退款单详情
     */
    public function refundInfo(){
        $refunds_no = input('refunds_no');
        if(empty($refunds_no)){
            $this->error('未找到退款单');
        }
        $refunds_model = new Refunds_tab();
        $refundInfo = $refunds_model->getRefundsInfoById($refunds_no);
        if(!empty($refundInfo)){
            $this->assign('refundInfo',$refundInfo);
            return $this->fetch();
        }else{
            $this->error('无此退款单信息！');
        }
    }
    /**
     * 删除退款单
     */
    public function refundDel(){
        $refunds_no = input('refund_no');
        $flg = input('available_flg');
        if(!empty($refunds_no)){
            $refunds_model = new Refunds_tab();
            $available_flg = $flg?0:1;
            $result = $refunds_model->updaterRefundsById(array('AVAILABLE_FLG'=>$available_flg),$refunds_no);
            if($result){
                $this->success('设置成功！');
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/refunds/index'));
        }
    }
    //退款
    public function doRefund(){
        $order = new Order_tab();
        $card_model = new Member_info_tab();
        $user_model = new User_tab();
        $refund_model = new Refunds_tab();
        $rtime_model = new Room_order_time_tab();

        $refund_no = input('refund_no');
        $refundInfo = $refund_model->getRefundsInfoById($refund_no);
		if($refundInfo['REFUNDS_STATUS'] == 1){
			$this->error('请勿进行重复退款操作',url('admin/Refunds/index'));
		}
        //用户信息
        $userInfo = $user_model->idGetUserOne($refundInfo['USER_ID']);
        //电子卡信息
        $cardInfo = $card_model->getUserCard($refundInfo['USER_ID']);

        //订单支付金额
		//$refundInfo['REFUNDS_AMT'] = round($refundInfo['REFUNDS_AMT'],2);
        $order_pay = round($refundInfo['PAY_AMT'],2);
        //if($refundInfo['REFUNDS_AMT'] <= $order_pay){
            $data = [
                'UPDATE_USER' => session('user_id'),
                'UPDATE_DATE'=>date("Y-m-d h:i:s",time()),
            ];
            //包含的预约单
            $order_cds = explode(',',$refundInfo['ORDER_CD']);
            $odata = $order->getOrderInfoByID($order_cds[0]);
            //微信退款金额
            $wx_refund = '';
            //电子卡退款金额
            $card_refund = '';
            //退款渠道
            $pay_type = '';
            $dataCard_r = [];
            if($odata['PAY_TYPE']){
                $pay_type = 1;//微信
                $wx_refund = $refundInfo['REFUNDS_AMT'];
                $card_refund = 0;
                $refund_type = 1;
            }else if($odata['PAY_TYPE2']){
                $pay_type = 2;//电子卡
                $card_refund = $refundInfo['REFUNDS_AMT'];
                $wx_refund = 0;
                $refund_type = 2;
                //根据财务折扣率计算电子卡退款
                if($cardInfo['CARD_DISC_RATE_FINANCE']) {
                    // $dataCard_r['RECEIVE_AMT'] = $post['REFUND_AMT'] *   $cardInfo['CARD_DISC_RATE_FINANCE']+$cardInfo['RECEIVE_AMT'];
                    // $dataCard_r['GIVE_AMT'] = $post['REFUND_AMT'] * (1 - $cardInfo['CARD_DISC_RATE_FINANCE']) +$cardInfo['GIVE_AMT'];
                    $receive_amt =  $refundInfo['REFUNDS_AMT'] * $cardInfo['CARD_DISC_RATE_FINANCE'];
                    $give_amt =  $refundInfo['REFUNDS_AMT'] - $receive_amt;
                    $remainR_amt = $receive_amt+$cardInfo['RECEIVE_AMT'];
                    $remainG_amt = $give_amt+$cardInfo['GIVE_AMT'];
                }else{
                    $receive_amt = $refundInfo['REFUNDS_AMT'];
                    $remainR_amt = $receive_amt+$cardInfo['RECEIVE_AMT'];
                    $remainG_amt = $cardInfo['GIVE_AMT'];
                }
                //$dataCard_r['TOTAL_CONSUMP_AMT'] = $cardInfo['TOTAL_CONSUMP_AMT']-$post['REFUND_AMT'];
                $total_consump_amt = $cardInfo['TOTAL_CONSUMP_AMT']-$refundInfo['REFUNDS_AMT'];
            }else if($odata['PAY_TYPE4']){
                $pay_type = 4;//混合支付
                $refund_type = 4;
                //计算微信和电子卡的退款金额
                if(!$refundInfo['REFUND_AMT'] > $odata['PAY_AMT2']){
                    $wx_refund = 0;
                    $card_refund = $refundInfo['REFUND_AMT'];
                }else{
                    $wx_refund = $refundInfo['REFUND_AMT'] - $odata['PAY_AMT2'];
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

            //这里执行微信退款
            if(!empty($wx_refund)){
				$ref_amt = intval($refundInfo['REFUNDS_AMT']*100);
				$pay_amt = intval($odata['ORDER_AMT']*100);
                $resultWX = $this->createWxRefund($refundInfo['REFUNDS_NO'],$odata['WX_ORDER_NO'],$ref_amt,$pay_amt,session('user_id'));			
                if($resultWX['return_code']!='SUCCESS'){
					$this->error($resultWX['return_msg']);
				}else if($resultWX['result_code']!='SUCCESS'){
                    $this->error($resultWX['err_code_des']);
                }
            }
            //电子卡退款
            if(!empty($card_refund)){
                $card_operate_model = new Card_operat_tab();
                $dataCard_r = [
                    'RECEIVE_AMT'=> $remainR_amt,
                    'GIVE_AMT'=> $remainG_amt,
                    'TOTAL_CONSUMP_AMT'=> $total_consump_amt,
                    'UPDATE_USER' => session('user_id'),
                    'UPDATE_DATE' => date('Y-m-d H:i:s',time())
                ];
                $dataOperate_r = [
                    'CARD_NO' => $cardInfo['MEMBER_CARD_NO'],
                    'CARD_OPERAT_TYPE' => '退款',
                    'REMARKS'=>'退款操作',
                    'CARD_TYPE' => $cardInfo['CARD_TYPE'],
                    'USER_ID' => $refundInfo['USER_ID'],
                    'MEMBER_NAME' => $userInfo['NICK_NAME'],
                    'MEMBER_TEL' => $userInfo['TEL_NO'],
                    'MEMBER_SEX' => $userInfo['SEX'],
                    'CONSUMP_AMT' => $receive_amt,
                    'GIVE_AMT' => $give_amt,
                    'AFTER_CONSUMP_AMT' => $remainR_amt,
                    'AFTER_GIVE_AMT' => $remainG_amt,
                    'LEFT_AMT' => $remainR_amt+$remainG_amt,
                    'INVOICE_FLG' => 0,
                    'AVAILABLE_FLG' => 1,
					'STORE_CD'=>$refundInfo['STORE_CD'],
                    'CREATE_USER' => session('ADMIN_ID'),
                    'UPDATE_USER' => session('ADMIN_ID'),
                    'CREATE_DATE' => date('Y-m-d H:i:s',time()),
                    'UPDATE_DATE' => date('Y-m-d H:i:s',time())
                ];				
                $resultCard = $card_model->updataCard($dataCard_r,$cardInfo['MEMBER_CARD_NO']);
                $resultOperate = $card_operate_model->cardOperatAdd($dataOperate_r);
                if(!$resultCard){
                    $this->error('电子卡退款失败！');
                }
                if(!$resultOperate){
                    $this->error('电子卡记录表更新失败！');
                }
            }
            //更改预约单
            $dataOrder = [];
            foreach($order_cds as $vr){
				$orderUpInfo = $order->getOrderInfoByID($vr);
                $dataOrder[] = [
                    'ORDER_CD'=>$vr,
                    'ORDER_STATUS'=>9,
					'ORDER_FD'=>$orderUpInfo['ORDER_FD']
                ];
            }
            foreach($dataOrder as $v_up){
                $resOrder_up = $order->data($v_up,true)->isUpdate(true)->save();
            }
            //删除预定时段
            $resRtime_del = $rtime_model->where('RTIME_CD','IN',$odata['RTIME_CD'])->delete();
            $data = [
                'PAY_TYPE'=> $pay_type,
                'REFUNDS_TYPE'=> $refund_type,
                'UPDATE_USER'=>session('user_id'),
                'UPDATE_DATE'=>date("Y-m-d h:i:s",time()),
                'REFUNDS_STATUS' => 1
            ];
            $pefund = new Refunds_tab();
            $res = $pefund->updaterRefundsById($data,$refundInfo['REFUNDS_NO']);
            if(!$res){
                $this->error('修改退款单失败!');
            }
            $this->success('退款成功！');
        //}else{
        //    $this->error('退款金额不能大于订单支付的金额!');
        //}
    }
    /**
     * 生成微信退款
     */
    private function createWxRefund($out_refund_no,$out_trade_no,$refund_fee,$total_fee,$op_user_id){
        $this->nonce_str = $this->buildRandStr();
        $str = 'appid='.$this->appId;
        $str .= '&mch_id='.$this->mch_id;
        $str .= '&nonce_str='.$this->nonce_str;
        $str .= '&op_user_id='.$this->mch_id;
	    $str .= '&out_refund_no='.$out_refund_no;
		$str .= '&out_trade_no='.$out_trade_no;                
        $str .= '&refund_fee='.$refund_fee;
        $str .= '&total_fee='.$total_fee;
        $str .= '&key='.$this->key;			
		$str = md5($str);		
        $ref = strtoupper($str);	

        $refund = [
            'appid' => $this->appId,
            'mch_id' => $this->mch_id,
            'nonce_str' => $this->nonce_str,
            'op_user_id' => $this->mch_id,
            'out_trade_no' => $out_trade_no,
            'out_refund_no' => $out_refund_no,
            'refund_fee' => $refund_fee,
            'total_fee' => $total_fee,
            'sign' => $ref
        ];

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $xml = $this->arrayToXml($refund);	
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);//证书检查
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
        curl_setopt($ch,CURLOPT_SSLCERT,ROOT_PATH.'/extend/weixinPay/cert/apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
        curl_setopt($ch,CURLOPT_SSLKEY,ROOT_PATH.'/extend/weixinPay/cert/apiclient_key.pem');
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
        curl_setopt($ch,CURLOPT_CAINFO,ROOT_PATH.'/extend/weixinPay/cert/rootca.pem');
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);

        $data=curl_exec($ch);
        if($data){ //返回来的是xml格式需要转换成数组再提取值，用来做更新			
            curl_close($ch);		
            return $this->xmlToArray($data);			
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
                $xml.="<".$key.">".$this->arrayToXml($val)."</".$key.">";
            }else{
                $xml.="<".$key.">".$val."</".$key.">";
            }
        }
        $xml.="</root>";
        return $xml ;
    } 
    /**
     * xml转数组
     */
    function xmlToArray($xml){
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);
        return $val;
    }
    /**
     * 随机生成字符串
     */
    public function buildRandStr(){
        $code = '';
        $code2 = '';
        $str = '';
        for($i=1;$i<=4;$i++){
            $code .= chr(rand(97,122));
            $code2 .= chr(rand(65,90));
        }
        $str .= $code.rand(1000,9999).$code2.rand(1000,9999);
        return $str;
    }


}