<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 9:01
 */

namespace app\api\controller;


use app\admin\model\Order_tab;
use app\admin\model\Room_order_time_tab;
use app\admin\model\Room_tab;
use app\admin\model\Store_tab;
use app\admin\model\Time_set_rule_tab;

class Order extends Base
{
    /**
     * $post = [
     *      'STORE_CD'=>门店编号，
     * ]
     * 根据门店编号获取预约单列表
     * */
    public function getOrderList(){
        $post = input();
        $order = new Order_tab();
        $condition = [];
        $startTime = strtotime(date('Y-m-d',time()));
        $endTime = $startTime+24*3600;
        $condition['STORE_CD'] = $post['STORE_CD'];
        //$condition['ORDER_DATE_TIMESTAMP'] = array('between',$startTime.','.$endTime);
        $condition['ORDER_DATE'] = date('Y-m-d',time());
		$condition['ORDER_STATUS'] = array('lt',7);
        $condition['a.AVAILABLE_FLG'] = 1;
        $data = $order->orderListApi($condition);
        if($data){
            return '{"code":"200","data":'.json_encode($data).'}';
        }else{
            return '{"code":"400","Msg":"unexpected error"}';
        }
    }
    /**
     * $post = [
     *      'ORDER_CD'=>预约单号,
     *      'TEL_NO' =>用户电话,
     *      'ORDER_NO'=>预约码
	 *      'ORDER_STATUS'=>预约状态
     * ]
     * 根据预约单号获取一个预约单数据
     * */
    public function getOrderDetailedOne(){
        $post = input();
        $order = new Order_tab();
        $condition = [];
        if(!empty($post['ORDER_CD'])){
            $condition['ORDER_CD'] = $post['ORDER_CD'];
        }
        if(!empty($post['TEL_NO'])){
            $condition['a.TEL_NO'] = $post['TEL_NO'];
        }
        if(!empty($post['ORDER_NO'])){
            $condition['ORDER_NO'] = $post['ORDER_NO'];
        }
		if(!empty($post['ORDER_STATUS'])){
			$condition['ORDER_STATUS'] = $post['ORDER_STATUS'];
		}
        $data = $order->orderListApi($condition);
        if($data){
            return '{"code":"200","data":'.json_encode($data).'}';
        }else{
            return '{"code":"400","Msg":"unexpected error"}';
        }
    }
	
    /**
     * $post = [
     *      "ORDER_CD"=>预约单号
     * ]
     * $data
     * 根据预约单号更新预约单
     * */
    public function updateOrderOne(){
        $post = input();
        if($post['UPDATE_USER']){
            $order = new Order_tab();
            $post['UPDATE_DATE']=date("Y-m-d h:i:s",time());
            $data = $order->updateOrederById($post,$post['ORDER_CD']);
            if($data){
                return '{"code":"200","Msg":"Order modify successfully!"}';
            }else{
                return '{"code":"400","Msg":"unexpected error"}';
            }
        }else{
            return '{"code":"400","Msg":"必须输入变更员编号"}';
        }
    }
    /**
     * $post = [
     *      'ORDER_CD'=>预约单号
     *      'UPDATE_USER'=>变更员编号
     * ]
     * 根据预约单号删除一个预约单
     * */
    public function delOrderOne(){
        $post = input();
        if($post['UPDATE_USER']){
            $order = new Order_tab();
            $data = [
                'UPDATE_USER'=>$post['UPDATE_USER'],
                'UPDATE_DATE'=>date("Y-m-d h:i:s",time()),
                'AVAILABLE_FLG'=>0,
            ];

            $res = $order->updateOrederById($data,$post['ORDER_CD']);
            if($res){
                return '{"code":"200","Msg":"Order deleted successfully!"}';
            }else{
                return '{"code":"400","Msg":"unexpected error"}';
            }
        }else{
            return '{"code":"400","Msg":"必须输入变更员编号"}';
        }
    }
/**
 * 新增预约单
 * */
    public function orderAdd(){
        $post = input();
        $order = new Order_tab();
        $post['CREATE_DATE'] = date("Y-m-d h:i:s",time());
        $post['ORDER_CD']="020".time();
        $res = $order->orderAdd($post);
        if($res){
            return '{"code":"200","Msg":"Order creation successful!"}';
        }else{
            return '{"code":"400","Msg":"unexpected error"}';
        }
    }
 /**
  * 查询时段内房间是否被预约
  * $post = [
  *     'ROOM_CD'=>房间编号
  *     'STORE_CD'=>门店编号
  *     'ORDER_DATE'=>预约日期
  *     'ORDER_START_DATE_TIME'=>预约开始时间
  *
  * ]
  */
   public function isRoomTimeOrdered(){
       $post = input();
       if($post['ROOM_CD'] && $post['STORE_CD'] && $post['ORDER_DATE'] && $post['ORDER_START_DATE_TIME']){
           $order_model = new Order_tab();
           $rtime_model = new Room_order_time_tab();
           $room_model = new Room_tab();
           $roomInfo = $room_model->getRoomOne($post['STORE_CD'],$post['ROOM_CD']);
           //查询房间是否可用
           if($roomInfo['AVAILABLE_FLG'] != 1){
               return '{"code":"401","Msg":"该房间不可用！"}';
           }
           //查询时间
           if(strtotime($post['ORDER_DATE'])<time()){
               return '{"code":"403","Msg":"只可预订现时之后的时段！"}';
           }
           //查询房间时间段是否被订
           $condition = [
               'ROOM_CD' => $post['ROOM_CD'],
               'STORE_CD' => $post['STORE_CD'],
               'ORDER_DATA' => $post['ORDER_DATE'],
               'ORDER_START_DATE_TIME' => $post['ORDER_START_DATE_TIME']
           ];
           $rtime_num = $rtime_model->getRoomNoteNum($condition);

           if($rtime_num > 0){
               return '{"code":"402","Msg":"已被预定！"}';
           }else {
               return '{"code":"200","Msg":"Room is not ordered!"}';
           }

       }else{
           return '{"code":"400","Msg":"提交信息不完整！"}';
       }
   }
   /**
    *更新预约单及房间预约时间表
    * $post=[
    *   'ROOM_CD'=>房间编号
    *   'STORE_CD'=>门店编号
    *   'ORDER_DATA'=>预定日期
    *   'ORDER_START_DATE_TIME'=>开始时间
    *   'ORDER_END_DATE_TIME'=>结束时间
    *   'ORDER_CD'=>预约单号
    * ]
    */
   public function updateOrderRoomTime(){
       $post = input();
       if($post['ROOM_CD'] && $post['STORE_CD'] && $post['ORDER_DATA'] && $post['ORDER_START_DATE_TIME'] && $post['TIMES'] && $post['ORDER_END_DATE_TIME'] && $post['ORDER_CD'] && $post['ROOM_NAME']){
           $order_model = new Order_tab();
           $rtime_model = new Room_order_time_tab();
           $time_model = new Time_set_rule_tab();
           $store_model = new Store_tab();
           //门店信息
           $storeInfo = $store_model->getStoreOne($post['STORE_CD']);
           //该门店的房间预约时段信息
           $condition = [
               'AVAILABLE_FLG'=>1,
               'STORE_CD'=>$post['STORE_CD']
           ];
           $timelist = $time_model->getTimeList($condition);
           //原预约单信息
           $orderInfo = $order_model->getOrderInfoByID($post['ORDER_CD']);
           //新增预约房间时段信息
           $start_time = strtotime($post['ORDER_DATA'].' '.$post['ORDER_START_DATE_TIME']);
           $times = intval($post['TIMES']);
           $dataRoom = [];
           foreach($timelist as $kt=>$vt){
               if( $start_time == strtotime($post['ORDER_DATA'].' '.$vt['ORDER_START_DATE_TIME']) && $times > 0){
                   if($start_time>strtotime($post['ORDER_DATA']) && $start_time<strtotime($post['ORDER_DATA'].' '.$storeInfo['STORE_START_TIME'])){
                       $order_data = date('Y-m-d',(strtotime($post['ORDER_DATA'])+24*3600));
                   }else{
                       $order_data = $post['ORDER_DATA'];
                   }
                   //查询该时段是否被预约
                   $conNum =  [
                      'ROOM_CD' => $post['ROOM_CD'],
                      'STORE_CD' => $post['STORE_CD'],
                      'ORDER_DATA' => $order_data,
                      'ORDER_START_DATE_TIME' => $vt['ORDER_START_DATE_TIME'],
                      'ORDER_END_DATE_TIME' => $vt['ORDER_END_DATE_TIME'],
                      'AVAILABLE_FLG'=>1
                   ];
                   $orderNum = $rtime_model->getRoomNoteNum($conNum);
                   if($orderNum > 0){
                       return '{"code":"100","Msg":"'.$vt['ORDER_START_DATE_TIME'].'时段已被预约"}';
                   }
                   $dataRoom[] = array(
                       'ROOM_CD'=>$post['ROOM_CD'],
                       'STORE_CD'=>$post['STORE_CD'],
                       'STORE_NAME'=>$orderInfo['STORE_NAME'],
                       'ORDER_DATA'=>$order_data,
                       'ORDER_START_DATE_TIME'=>$vt['ORDER_START_DATE_TIME'],
                       'ORDER_END_DATE_TIME'=>$vt['ORDER_END_DATE_TIME'],
                       'CREATE_USER'=>$orderInfo['USER_ID'],
                       'CREATE_DATE'=>date('Y-m-d H:i:s',time()),
                       'UPDATE_USER'=>$orderInfo['USER_ID'],
                       'UPDATE_DATE'=>date('Y-m-d H:i:s',time()),
                       'TIME_CD'=>$vt['TIME_CD']
                   );
                   $start_time = strtotime($post['ORDER_DATA'].' '.$vt['ORDER_END_DATE_TIME']);
                   $times--;
               }
           }
           //删除原有预约房间信息记录
           $rtime_cds = explode(',',$orderInfo['RTIME_CD']);
           foreach($rtime_cds as $kr=>$vr){
               $delRoom = $rtime_model->where(['RTIME_CD'=>$vr])->delete();
           }
           //执行新增预约房间
           $rtime_cd = [];
           foreach( $dataRoom as $val){
               $result_time = $rtime_model->data($val,true)->isUpdate(false)->save();
               if(!$result_time){
                   return '{"code":"101","Msg":"房间预约信息更新失败！"}';
               }
               $rtimeInfo = $rtime_model->roomTimeList($val);
               $rtime_cd[] =  $rtimeInfo[0]['RTIME_CD'];
           }

		   //查询同一支付条件的预约单
		   $condition_order = [
				'WX_ORDER_NO'=>$orderInfo['WX_ORDER_NO'],								
				'ORDER_STATUS'=>1
		   ];		   
		   $orderList = $order_model->orderListNpage($condition_order);
           //更新预约单
		   $dataOrder = [];
		   foreach($orderList as $k=>$v){
			   $dataOrder[$k] = array(
				   'ROOM_CD'=>$post['ROOM_CD'],
                   'ROOM_NAME'=>$post['ROOM_NAME'],
				   'ORDER_CD'=>$v['ORDER_CD'],
				   'ORDER_FD'=>$v['ORDER_FD'],
				   'ORDER_DATE' =>  $dataRoom[0]['ORDER_DATA'],
				   'OR_START_DATE_TIME' => $post['ORDER_START_DATE_TIME'],
				   'OR_END_DATE_TIME' => $post['ORDER_END_DATE_TIME'],
				   'RTIME_CD'=> implode(',',$rtime_cd)
				);
		   }
		   $resultOrder = $order_model->saveAll($dataOrder);		  
           if($resultOrder && $delRoom){
               return '{"code":"200","Msg":"Order creation successful!"}';
           }else{
               return '{"code":"400","Msg":"unexpected error"}';
           }
       }else{
           return '{"code":"400","Msg":"提交信息不完整！"}';
       }
   }
   /**
    * 获取预约时间列表
	*$post=[
    *   
    *   'STORE_CD'=>门店编号
    *   ]
    */
   public function getOrderTimeList(){
	   $post = input();
       $time_model = new Time_set_rule_tab();
	   $condition = [
			'STORE_CD'=>$post['STORE_CD'],
			'AVAILABLE_FLG' => 1
	   ];
       $timeList = $time_model->getTimeList($condition);
       if(!empty($timeList)){
           return '{"code":"200","data":'.json_encode($timeList).'}';
       }else{
           return '{"code":"400","Msg":"unexpected error"}';
       }
   }
}