<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1 0001
 * Time: 15:01
 */
namespace app\admin\controller;



use app\admin\model\Order_tab;
use app\admin\model\Store_tab;
use app\admin\model\Project_tab;
use app\admin\model\Room_tab;
use app\admin\model\Price_differences_tab;
use think\Request;

class Order extends Base
{
    /**
     * 订单列表
     * */
    public function orderList()
    {
        $order = new Order_tab();
        $where = array();
        $order_no = input('order_no');
        $store_cd = input('store_cd');
        if(!empty($order_no)){
            $where['ORDER_NO'] = $order_no;
            $this->assign('order_no',$order_no);
        }
        if(!empty($store_cd)){
            $where['store_cd'] = $store_cd;
            $this->assign('store_cd',$store_cd);
        }
        $data = $order->getOrderListByCon($where);
        $page = $data->render();
        $orderListNum = $order->getOrdersNum($where);
        //门店信息
        $condition = [];
        $condition['STORE_STATE'] = 1;//门店已启用
        $condition['AVAILABLE_FLG'] = 1;//门店可用
        $store_model = new Store_tab();
        $stores = $store_model->storeListCondition($condition);
        $this->assign('stores',$stores);
        $this->assign('orderListNum',$orderListNum);
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }
    /**
     * 编辑预约单
     */
    public function orderEdit(){
        $order_cd = input('order_cd');
        if(!$order_cd){
            $this->error('未传入有效参数');
        }
        //预约单信息
        $order_model = new Order_tab();
        $orderInfo = $order_model->getOrderInfoByID($order_cd);
        //门店信息
        $condition = [];
        $condition['STORE_STATE'] = 1;//门店已启用
        $condition['AVAILABLE_FLG'] = 1;//门店可用
        $store_model = new Store_tab();
        $stores = $store_model->storeListCondition($condition);
        //门店项目
        $project_model = new Project_tab();
        $projectList = $project_model->getProjectStoreAll($orderInfo['STORE_CD']);
        //门店房间
        $room_model = new Room_tab();
        $roomList = $room_model->roomList1($orderInfo['STORE_CD']);
        //预约单状态
        $order_status_arr = $order_model->getOrderStatusArr();
        $this->assign('order_status_arr',$order_status_arr);
        $this->assign('roomList',$roomList);
        $this->assign('projectList',$projectList);
        $this->assign('stores',$stores);
        $this->assign('orderInfo',$orderInfo);
        return $this->fetch();
    }
    /**
     * 预约单编辑提交
     */
    public function orderEditPost(){
        $post = input();
        foreach($post as $k=>$v){
            if(empty($v)){
                unset($post[$k]);
            }
        }
        if(!empty($post['DIFF_PRICE_AMT'])){
            $price_diff_model = new Price_differences_tab();
            $data = array(
                'DIFF_PRICE_NO' => '030'.time(),
                'ORDER_CD' => $post['ORDER_CD'],
                'USER_ID'  => $post['USER_ID'],
                'STORE_CD' => $post['STORE_CD'],
                'PAY_AMT'  => $post['DIFF_PRICE_AMT'],
                'CREATE_USER' => session('ADMIN_ID'),
                'CREATE_DATE' => date('Y-m-d H:i:s',time()),
                'UPDATE_USER' => session('ADMIN_ID'),
                'UPDATE_DATE' => date('Y-m-d H:i:s',time())
            );
            $result = $price_diff_model->priceDifAdd($data);
            if(!$result){
                $this->error('生成差价单失败！');
            }
        }
        if(!isset($post['AVAILABLE_FLG'])){
            $post['AVAILABLE_FLG'] = 0;
        }
        $order_model = new Order_tab();
        $update_result = $order_model->updateOrederById($post,$post['ORDER_CD']);
        if(!$update_result){
            $this->error('修改预约单失败！');
        }else{
            $this->error('修改预约单成功！',url('admin/order/orderList'));
        }

    }
    /**
     * 设置预约单可用状态
     */
    public function orderSetFlg(){
        $order_cd = input('order_cd');
        if(!empty($order_cd)){
            $order_model = new Order_tab();
           // $result = $order_model->where(['ORDER_CD'=>$order_cd])->delete();
            $orderInfo = $order_model->getOrderInfoByID($order_cd);
            $flg = $orderInfo['AVAILABLE_FLG']?'0':1;
            $result = $order_model->updateOrederById(array('AVAILABLE_FLG'=>$flg),$order_cd);
            if($result){
                $this->redirect(url('admin/order/orderList'));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/order/orderList'));
        }
    }
    /**
     * ajax获取有效房间
     */
    public function getRoomFlag(){
        $store_cd = input('store_cd');
        if(!$store_cd){
            $data['state'] = 'fail';
            $data['msg'] = '未选择店铺！';
            return $data;
        }
        $room_model = new Room_tab();
        $roomList = $room_model->roomList1($store_cd);
        if(!empty($roomList)){
            $data['state'] = 'success';
            $data['list'] = $roomList;
        }else{
            $data['state'] = 'error';
            $data['msg'] = '获取失败！';
        }
        return $data;
    }
    /**
     * ajax获取有效项目
     */
    public function getProjectFlag(){
        $store_cd = input('store_cd');
        if(!$store_cd){
            $data['state'] = 'fail';
            $data['msg'] = '未选择店铺！';
            return $data;
        }
        $project_model = new Project_tab();
        $projectList = $project_model->getProjectStoreAll($store_cd);
        if(!empty($projectList)){
            $data['state'] = 'success';
            $data['list'] = $projectList;
        }else{
            $data['state'] = 'error';
            $data['msg'] = '获取失败！';
        }
        return $data;
    }

}