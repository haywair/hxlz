<?php
/**
 * 票券支付订单model
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7 0007
 * Time: 11:23
 */
namespace app\admin\model;

class Finance_order_tab extends Base{
    public function financeOrderAdd($data){
        return $this->data($data)->save();
    }
    //更新票券支付订单
    public function updateFinanceOrderByCD($data,$fiance_order_id){
        return $this->where(['FINANCE_ORDER_ID'=>$fiance_order_id])->update($data);
    }
    //票券支付订单列表
    public function financeOrderList($condition=[],$order=""){
        $field = 'a.*,b.NICK_NAME,b.TEL_NO,c.STORE_NAME,d.FINANCE_NAME,e.PROJECT_NAME';
        $join = [
            ['USER_TAB b','a.USER_ID = b.USER_ID'],
            ['STORE_TAB c','a.STORE_CD = c.STORE_CD'],
            ['FINANCE_TAB d','a.FINANCE_ID = d.FINANCE_ID'],
            ['PROJECT_TAB e','a.PROJECT_ID = e.PROJECT_ID'],
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->order('CREATE_DATE asc')->paginate(15,
            false,['query' => request()->param()]);
    }
    public function getOrderInfoByID($id){
        $info =  $this->where('FINANCE_ORDER_ID',$id)->find();
        if($info){
            return $info;
        }else{
            return;
        }
    }
    public function financeOrderListNpage($condition,$limit=''){
        return $this->where($condition)->order('CREATE_DATE asc')->limit($limit)->select();
    }
    public function getFinanceOrdersNum($condition){
        return $this->where($condition)->count();
    }
}