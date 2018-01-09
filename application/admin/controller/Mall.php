<?php
/**
 * 商城订单
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7 0007
 * Time: 16:45
 */
namespace app\admin\controller;

use app\admin\model\Finance_order_tab;
use app\admin\model\Store_tab;
class Mall extends Base{
    public function financeOrderList(){
        $order = new Finance_order_tab();
        $where = array();
        $finance_order_cd = input('finance_order_cd');
        $store_cd = input('store_cd');
        $tel_no = input('tel_no');
        $order_status = input('order_status');

        //门店信息
        $store_model = new Store_tab();
        $condition = [];
        $condition['STORE_STATE'] = 1;//门店已启用
        $condition['AVAILABLE_FLG'] = 1;//门店可用
        $store_model = new Store_tab();
        $stores = $store_model->storeListCondition($condition);
        if(!empty($finance_order_cd)){
            $where['a.FINANCE_ORDER_CD'] =  $finance_order_cd;
            $this->assign('finance_order_cd', $finance_order_cd);
        }
        if(!empty($store_cd)){
            $where['a.STORE_CD'] = $store_cd;
            $this->assign('store_cd',$store_cd);
        }
        if(!empty($tel_no)){
            $where['b.TEL_NO'] = array('LIKE','%'.$tel_no.'%');
            $this->assign('tel_no',$tel_no);
        }
        if(!empty($order_status)){
            $this->assign('order_status',$order_status);
            switch($order_status){
                case '1':
                    $where['a.ORDER_STATUS'] = 1;
                    break;
                case '10':
                    $where['a.AVAILABLE_FLG'] = 1;
                    break;
                case '11':
                    $where['a.AVAILABLE_FLG'] = 0;
                    break;
            }
        }
        $data = $order->financeOrderList($where);
        $page = $data->render();
        $this->assign('stores',$stores);
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }
    /**
     * 设置票券订单可用状态
     */
    public function orderSetFlg(){
        $finance_order_id = input('finance_order_id');
        if(!empty($finance_order_id)){
            $financeOrder_model = new Finance_order_tab();
            // $result = $order_model->where(['ORDER_CD'=>$order_cd])->delete();
            $orderInfo =  $financeOrder_model->getOrderInfoByID($finance_order_id);
            $flg = $orderInfo['AVAILABLE_FLG']?'0':1;
            $result = $financeOrder_model->updateFinanceOrderByCD(array('AVAILABLE_FLG'=>$flg),$finance_order_id);
            if($result){
                $this->redirect(url('admin/mall/financeOrderList'));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/mall/financeOrderList'));
        }
    }
}