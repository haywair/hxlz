<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1 0001
 * Time: 15:04
 */
namespace app\admin\model;


class Order_tab extends Base
{
    private $order_status = array();
    public function orderAdd($data){
        return $this->data($data)->save();
    }
    public function orderList($order=""){
        return $this->order('ORDER_DATE_TIMESTAMP asc')->paginate(15,false);
    }
    public function orderListNpage($condition,$limit=''){
        return $this->where($condition)->order('ORDER_DATE ASC,OR_START_DATE_TIME ASC')->limit($limit)->select();
    }
    /**
     * @param $condition
     * @param string $limit
     * @return array
     * api接口查询
     */
    public function orderListApi($condition,$limit=''){
        $field = 'a.*,b.MEMBER_CARD_NO,b.RECEIVE_AMT,b.GIVE_AMT,b.CARD_DISC_RATE_FINANCE';
        $join = [
            ['MEMBER_INFO_TAB b','a.USER_ID = b.USER_ID','LEFT']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->order('ORDER_DATE ASC,OR_START_DATE_TIME ASC')->limit($limit)->select();
    }
    public function getOrderFlg(){
        return $this->where(['AVAILABLE_FLG'=>1])->select();
    }
    public function getOrdersNum($condition){
        return $this->where($condition)->count();
    }

    public function getOrderInfoByID($id){
        $info =  $this->where('ORDER_CD',$id)->find();
        if($info){
            return $info;
        }else{
            return;
        }
    }
    public function getOrderListByCon($condition){
        return $this->where($condition)->order('ORDER_DATE_TIMESTAMP desc')->paginate(15,false,['query' => request()->param()]);
    }
    public function getOrderListByUser($uid,$field="*",$group=''){
        return $this->field($field)->group($group)->where(['USER_ID'=>$uid,'AVAILABLE_FLG'=>1])->select();
    }
    public function updateOrederById($data,$oid){
        return $this->where(['ORDER_CD'=>$oid])->update($data);
    }

    /**
     * 根据微信单号更改订单状态
     * @param $data
     * @param $wx_order_no
     * @return $this
     */
    public function updateOrederByWxNO($data,$wx_order_no){
        return $this->where(['WX_ORDER_NO'=>$wx_order_no])->update($data);
    }
    public function getOrderStatusArr(){
        $order_status_arr = array('待付款' ,'已付款','已到店','服务中','服务结束','差价待支付','未完全消费','已消费','待退款','已退款','待评价','已评价');
        return $order_status_arr;
    }
    public function delOrderOne($oid){
        return $this->where(['ORDER_CD'=>$oid])->delete();
    }
    public function getOrderListByStore($sid){
        return $this->where(['STORE_CD'=>$sid])->order('ORDER_DATE_TIMESTAMP asc')->select();
    }
    public function updateOrderByUserID($data,$uid){
        return $this->where(['USER_ID'=>$uid])->update($data);
    }
    public function updateOrderByCon($data,$condition){
        return $this->where($condition)->update($data);
    }
}