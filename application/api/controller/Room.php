<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14 0014
 * Time: 13:22
 */
namespace app\api\controller;


use app\admin\model\Order_tab;
use app\admin\model\Room_tab;
use app\admin\model\Room_order_time_tab;
use app\admin\model\Time_set_rule_tab;
use app\admin\model\Store_tab;

class Room extends Base
{
    /**
     * $post = [
     *      'STORE_CD'=>门店编号
     * ]
     * 根据门店编号获取房间列表
     * */
    public function getRoomList()
    {
        $post = input();
        $room_model = new Room_tab();
        $data = $room_model->roomList1($post['STORE_CD']);
        if(!empty($post['STORE_CD'])) {
            if ($data) {
                return '{"code":"200","data":' . json_encode($data) . '}';
            } else {
                return '{"code":"400","Msg":"unexpected error"}';
            }
        }else{
            return '{"code":"400","Msg":"请输入门店编号"}';
        }
    }
    /**
     * $post = [
     *      'ROOM_CD'=>房间编号
     *      ‘STORE_CD'=>门店编号
     *      'ORDRE_DATA'=>日期
     * ]
     * 根据房间编号获取房间预约详细列表
     * */
    public function getRoomTimeList()
    {
        $post = input();
        $order_model = new Order_tab();
        $roomTime_model = new Room_order_time_tab();
        if(empty($post['STORE_CD'])){
            return '{"code":"400","Msg":"请输入门店编号"}';die();
        }
        if(empty($post['ROOM_CD'])){
            return '{"code":"400","Msg":"请输入房间编号"}';die();
        }
        $condition = [
           'STORE_CD'=>$post['STORE_CD'],
           'ROOM_CD' =>$post['ROOM_CD'],
           'ORDER_DATA' => array('gt',$post['ORDER_DATA'])
          //  'ORDER_DATA'=>$post['ORDER_DATA']
        ];
        $data = $roomTime_model->roomTimeList($condition);
        foreach($data as $k=>$v){
                $cond['RTIME_CD'] = array('LIKE','%'.$v['RTIME_CD'].'%');
                $cond['STORE_CD'] = $v['STORE_CD'];
                $cond['ROOM_CD'] = $v['ROOM_CD'];
                $cond['ORDER_DATE'] = substr($v['ORDER_DATA'],0,10);
                $cond['ORDER_STATUS'] = 1;
                $orderInfo = $order_model->where($cond)->select();
                if($orderInfo) {
                    $data[$k]['ORDER_CD'] = $orderInfo[0]['ORDER_CD'];
                    $data[$k]['USER_NAME'] = $orderInfo[0]['USER_NAME'];
                    $data[$k]['TEL_NO'] = $orderInfo[0]['TEL_NO'];
                }else{
                    $data[$k]['ORDER_CD'] = '';
                    $data[$k]['USER_NAME'] = '';
                    $data[$k]['TEL_NO'] = '';
                }
        }
        if ($data) {
            return '{"code":"200","data":' . json_encode($data) . '}';
        } else {
            return '{"code":"400","Msg":"unexpected error"}';
        }
    }
    /**
     * $post = [
     *      "ROOM_CD"=>房间编号
     * ]
     * $data
     * 根据房间编号更新房间信息
     * */
    public function updateRoomOne(){
        $post = input();
        if($post['UPDATE_USER']){
            $room_model = new Room_tab();
            $post['UPDATE_DATE']=date("Y-m-d h:i:s",time());
            $data = $room_model->updateRoomByRid($post,$post['ROOM_CD']);
            if($data){
                return '{"code":"200","Msg":"Room modify successfully!"}';
            }else{
                return '{"code":"400","Msg":"unexpected error"}';
            }
        }else{
            return '{"code":"400","Msg":"必须输入变更员编号"}';
        }
    }
    /**
     * $post = [
     *      "STORE_CD"=>房间编号
     * ]
     * $data
     * 获取该门店下有预约的房间信息
     * */
    public function getStoreRoomOrdered(){
        $store_cd = input('STORE_CD');
        if(!empty($store_cd)){
            $rtime_model = new Room_order_time_tab();
            $condition =[
                'STORE_CD' => $store_cd,
                'ORDER_DATA' => date('Y-m-d',time())
            ];
            $group = 'ROOM_CD';
            $roomInfo = $rtime_model->getStoreRoomInfo($condition,$group);
            if(!empty($roomInfo)){
                return '{"code":"200","data":' . json_encode($roomInfo) . '}';
            }else{
                return '{"code":"400","Msg":"unexpected error"}';
            }

        }else{
            return '{"code":"400","Msg":"必须输入门店编号"}';
        }

    }
    /**
     * 执行新增房间预约信息
     */
    public function addRoomOrderTime(){
        $post = input();
        if($post['ROOM_CD'] && $post['STORE_CD'] && $post['ORDER_DATA'] && $post['ORDER_START_DATE_TIME'] &&  $post['TIMES'] &&  $post['ORDER_END_DATE_TIME']) {
            $rtime_model = new Room_order_time_tab();
            $time_model = new Time_set_rule_tab();
            $store_model = new Store_tab();
            $storeInfo = $store_model->getStoreOne($post['STORE_CD']);
            //该门店的房间预约时段信息
            $condition = [
                'AVAILABLE_FLG' => 1,
                'STORE_CD' => $post['STORE_CD']
            ];
            $timelist = $time_model->getTimeList($condition);
            //新增预约房间时段信息
            $start_time = strtotime($post['ORDER_DATA'] . ' ' . $post['ORDER_START_DATE_TIME']);
            $times = intval($post['TIMES']);
            $dataRoom = [];
            foreach ($timelist as $kt => $vt) {
                if ($start_time == strtotime($post['ORDER_DATA'] . ' ' . $vt['ORDER_START_DATE_TIME']) && $times > 0) {
                    if ($start_time > strtotime($post['ORDER_DATA']) && $start_time < strtotime($post['ORDER_DATA'].' '.$storeInfo['STORE_START_TIME'])) {
                        $order_data = date('Y-m-d', (strtotime($post['ORDER_DATA']) + 24 * 3600));
                    } else {
                        $order_data = $post['ORDER_DATA'];
                    }
                    //查询该时段是否被预约
                    $conNum = [
                        'ROOM_CD' => $post['ROOM_CD'],
                        'STORE_CD' => $post['STORE_CD'],
                        'ORDER_DATA' => $order_data,
                        'ORDER_START_DATE_TIME' => $vt['ORDER_START_DATE_TIME'],
                        'ORDER_END_DATE_TIME' => $vt['ORDER_END_DATE_TIME']
                    ];
                    $orderNum = $rtime_model->getRoomNoteNum($conNum);
                    if ($orderNum > 0) {
                        return '{"code":"100","Msg":"' . $vt['ORDER_START_DATE_TIME'] . '时段已被预约"}';
                    }
                    $dataRoom[] = array(
                        'ROOM_CD' => $post['ROOM_CD'],
                        'STORE_CD' => $post['STORE_CD'],
                        'STORE_NAME' => $storeInfo['STORE_NAME'],
                        'ORDER_DATA' => $order_data,
                        'ORDER_START_DATE_TIME' => $vt['ORDER_START_DATE_TIME'],
                        'ORDER_END_DATE_TIME' => $vt['ORDER_END_DATE_TIME'],
                        'CREATE_USER' => 99999999,
                        'CREATE_DATE' => date('Y-m-d H:i:s', time()),
                        'UPDATE_USER' => 99999999,
                        'UPDATE_DATE' => date('Y-m-d H:i:s', time()),
                        'TIME_CD'=>$vt['TIME_CD']
                    );
                    $start_time = strtotime($post['ORDER_DATA'] . ' ' . $vt['ORDER_END_DATE_TIME']);
                    $times--;
                }
            }
            //$result_time = $rtime_model->saveAll($dataRoom);
            $rtime_cds = [];
            foreach($dataRoom as $k=>$kd){
                $result_time = $rtime_model->data($kd,true)->isUpdate(false)->save();
                $rtimeInfo = $rtime_model->where($kd)->find();
                $rtime_cds[$k] = $rtimeInfo['RTIME_CD'];
                if(!$result_time){
                    if($rtime_cds) {
                        foreach ($rtime_cds as $ks => $vs) {
                            $rtime_model->where(['RTIME_CD' => $vs])->delete();
                        }
                    }
                    return '{"code":"100","Msg":"unexpected error"}';
                }
            }
            if (!empty($result_time)) {
                return '{"code":"200","Msg":"Room_order_time successfully!"}';
            }/* else {
                return '{"code":"100","Msg":"unexpected error"}';
            }*/
        }else{
            return '{"code":"400","Msg":"提交信息不完整！"}';
        }
    }
    /**
     * 删除预约时间表记录
     * $post = [
     *      "RTIME_CD"=>房间预约记录编号
     * ]
     *
     */
    public function delRoomOrderTimeByRD(){
        $rtime_cd = input('RTIME_CD');
        if(!empty($rtime_cd)){
            $rtime_model = new Room_order_time_tab();
            $result = $rtime_model->where(['RTIME_CD'=>$rtime_cd])->delete();
            if(!empty($result)){
                return '{"code":"200","Msg":"room_order_time delete successful"}';
            }else{
                return '{"code":"400","Msg":"unexpected error"}';
            }

        }else{
            return '{"code":"400","Msg":"提交信息不完整,请上传你要删除记录的ID值！"}';
        }
    }


}