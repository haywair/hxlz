<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11 0011
 * Time: 13:32
 */
namespace app\wx\controller;


use app\admin\model\Order_tab;
use app\admin\model\Project_tab;
use app\admin\model\Room_tab;
use app\admin\model\Store_tab;
use app\wx\model\Card_operat_tab;
use app\wx\model\Member_info_tab;
use app\wx\model\User_tab;
use app\admin\model\Room_order_time_tab;
use app\admin\model\Time_set_rule_tab;
use app\admin\model\Refunds_tab;
use app\admin\model\Project_plan_price_tab;
use app\admin\model\Price_plan_category_tab;
use Com\JsSdkPay;
use think\Request;

class Refund extends Base{
    public  function refundAdd(){
        $post = input();
        $order = new Order_tab();
        $rtime_model = new Room_order_time_tab();
        $card_model = new Member_info_tab();
        $user_model = new User_tab();
        //预约单信息
        $odata = $order->getOrderInfoByID($post['ORDER_CD']);
        if($odata['ORDER_STATUS'] < 1){
            $data['state'] = 'fail';
            $data['msg'] = '您尚未付款，不可申请退款！';
            echo json_encode($data);die();
        }
        if($odata['ORDER_STATUS'] == 8 || $odata['ORDER_STATUS'] == 9){
            $data['state'] = 'fail';
            $data['msg'] = '该预约单已申请退款或已退款，不可重复申请退款！';
            echo json_encode($data);die();
        }
        //是否为2人免单且已消费
        $rdata = $this->checkCarshTwo($odata);

        if(isset($rdata['state']) && $rdata['state'] == 3032 && $rdata['costNum'] > 0){
            $data['state'] = 'fail';
            $data['msg'] = '该订单为2人消费1人免单，其中1人已消费不可退款！';
            echo json_encode($data);die();
        }
        //优惠价
        if($odata['PAY_TYPE'] == 1 && $odata['PAY_TYPE3'] == 1){
            //微信和票券混合支付单价为订单总价除以预约总人数
            $sell_price = round(($odata['ORDER_AMT']/$odata['CUSTOMER_TOTAL_QTY']),2);
        }else if(isset($rdata['price'])){
            //采用优惠方案的优惠单价
            $sell_price = $rdata['price'];
        }else{
            //未采用优惠方案的单价
            $sell_price = $odata['SELL_PRICE'];
        }
        //已消费和未消费预约单信息
        $con_costed = [
            'WX_ORDER_NO'=>$odata['WX_ORDER_NO'],
            'ORDER_STATUS'=>array('egt',7)
        ];
        $con_noRefund = [
            'WX_ORDER_NO'=>$odata['WX_ORDER_NO'],
            'ORDER_STATUS'=>array('lt',7),
            'IS_REFUND' => 0
        ];
        //消费的订单数量
        $costedNum = $order->getOrdersNum($con_costed);
        //不可退款的数量
        $noRefundNum = $order->getOrdersNum($con_noRefund);
        $noCostedNum = $odata['CUSTOMER_TOTAL_QTY']-$costedNum-$noRefundNum;
        //已消费不可退款
        if($noCostedNum<=0){
            $data['state'] = 'fail';
            $data['msg'] = '该订单已消费，不可退款！';
            echo json_encode($data);die();
        }
        //退款金额
        $refund_amt = $noCostedNum*$sell_price;
        //订单支付金额
        $wx_pay = $odata['PAY_AMT']?$odata['PAY_AMT']:0;
        $card_pay = $odata['PAY_AMT2']?$odata['PAY_AMT2']:0;
        $order_pay = $odata['ORDER_AMT'];
        //预约单待退款
        $conditionRefund = [
            'WX_ORDER_NO'=>$odata['WX_ORDER_NO'],
            'ORDER_STATUS'=>1
        ];
        //未消费订单
        $unuseOrders = $order->orderListNpage($conditionRefund);
        $order_cds = [];
        foreach($unuseOrders as $ko=>$vo){
            $order_cds[] = $vo['ORDER_CD'];
        }
        //更改预约单状态
        $resOrder_refund = $order->updateOrderByCon(['ORDER_STATUS'=>8],$conditionRefund);
        //删除预约时段
        $rtime_cds = explode(',',$odata['RTIME_CD']);
        $dataRtime = [];
        foreach($rtime_cds as $v){
            //$resRtime_del = $rtime_model->where(['RTIME_CD'=>$v])->delete();
            $dataRtime[] = [
                'RTIME_CD'=>$v,
                'AVAILABLE_FLG'=>0
            ];
        }

        //生成退款单
        if($resOrder_refund){
            $resRtime_del = $rtime_model->saveAll($dataRtime);
            $dataRefund = [
                'REFUNDS_NO'=>"040".time(),
                'ORDER_CD' =>implode(',',$order_cds),
                'USER_ID' =>$odata['USER_ID'],
                'STORE_CD'=>$odata['STORE_CD'],
                'PAY_AMT' => $order_pay,
                'REFUNDS_AMT' => $refund_amt,
                'CREATE_USER'=>session('user_id'),
                'CREATE_DATE'=>date("Y-m-d h:i:s",time()),
                'UPDATE_USER'=>session('user_id'),
                'UPDATE_DATE'=>date("Y-m-d h:i:s",time()),
                'REFUNDS_STATUS' => 0
            ];
            $pefund = new Refunds_tab();
            $res = $pefund->refundsAdd($dataRefund);
            if(!empty($res)){
                $data['state'] = 'success';
                $data['msg'] = '您已申请了金额为￥'.$refund_amt.'元的退款';
            }else{
                $data['state'] = 'fail';
                $data['msg'] = '申请退款单失败';
            }
        }else{
            $data['state'] = 'fail';
            $data['msg'] = '预约单和时间预定修改失败';
        }
        echo json_encode($data);die();
    }
    /**
     * 两人免单是否已消费
     */
    public function checkCarshTwo($orderInfo){
        $order_model = new Order_tab();
        $priceat_model = new Project_plan_price_tab();
        $project_model = new Project_tab();
        $planCategory_model = new Price_plan_category_tab();
        //项目信息
        $projectInfo = $project_model->getStoreProjectOne($orderInfo['PROJECT_CD'],$orderInfo['STORE_CD']);
        $priceatInfo = $priceat_model->getProjectPriceatInfo($projectInfo['PROJECT_ID']);
        //项目优惠方案信息
        $rdata = [];
        if(!empty($priceatInfo['LEVEL_ONE'])){
            //项目优惠方式
            $planCategory_info = $planCategory_model->getPriceatCategoryInfo($priceatInfo['LEVEL_ONE']);
            //
            if(!empty($planCategory_info) && $planCategory_info['LEVEL_TYPE']==3 && $orderInfo['CUSTOMER_TOTAL_QTY']%2==0 ) {
                $condition = [];
                $condition['WX_ORDER_NO'] = $orderInfo['WX_ORDER_NO'];
                $condition['ORDER_STATUS'] = array('egt',7);
                $ordersNum = $order_model->getOrdersNum($condition);
                if($ordersNum > 0 ){
                    $rdata['state'] = 3032;
                    $rdata['costNum'] = $ordersNum;
                }else{
                    $rdata['state'] = 3032;
                    $rdata['costNum'] = 0;
                }

            }else if(!empty($planCategory_info)){
                $rdata['state'] = 301;
                if(!empty($priceatInfo['LEVEL_ONE_SALE_PRICE']) && $orderInfo['ORDER_DATE'] == $planCategory_info['LEVEL_RULE']){
                    $rdata['price'] = $projectInfo['PRICE'] - $priceatInfo['LEVEL_ONE_SALE_PRICE'];
                }else{
                    $rdata['price'] = $projectInfo['PRICE'];
                }
            }
        }else if(!empty($priceatInfo['LEVEL_TWO'])){
            $rdata['state'] = 302;
            //项目优惠方式
            $planCategory_info = $planCategory_model->getPriceatCategoryInfo($priceatInfo['LEVEL_TWO']);
            $order_date = intval(substr($orderInfo['ORDER_DATE'],8,2));
            $rulesTwo = explode(',',$planCategory_info['LEVEL_RULE']);
            if(!empty($priceatInfo['LEVEL_TWO_SALE_PRICE']) && in_array($order_date,$rulesTwo)){
                $rdata['price'] = $projectInfo['PRICE'] - $priceatInfo['LEVEL_TWO_SALE_PRICE'];
            }else{
                $rdata['price'] = $projectInfo['PRICE'];
            }
        }else if(!empty($priceatInfo['LEVEL_THREE'])) {
            $rdata['state'] = 303;
            if (!empty($priceatInfo['LEVEL_THREE_SALE_PRICE'])) {
                $rdata['price'] = $projectInfo['PRICE'] - $priceatInfo['LEVEL_THREE_SALE_PRICE'];
            } else {
                $rdata['price'] = $projectInfo['PRICE'];
            }
        }else if(!empty($priceatInfo['LEVEL_FOUR'])){
            $rdata['state'] = 304;
            //项目优惠方式
            $planCategory_info = $planCategory_model->getPriceatCategoryInfo($priceatInfo['LEVEL_TWO']);
            $rulesFour = explode(',',$planCategory_info['LEVEL_RULE']);
            if(!empty($priceatInfo['LEVEL_FOUR_SALE_PRICE']) && in_array($orderInfo['ROOM_CD'],$rulesFour)){
                $rdata['price'] = $projectInfo['PRICE'] - $priceatInfo['LEVEL_FOUR_SALE_PRICE'];
            }else{
                $rdata['price'] = $projectInfo['PRICE'];
            }
        }else {
            $rdata['state'] = 100;
            $rdata['price'] = $projectInfo['PRICE'];
        }
        return $rdata;
    }

}