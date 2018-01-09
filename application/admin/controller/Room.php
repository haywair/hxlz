<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 15:30
 */

namespace app\admin\controller;


use app\admin\model\Room_tab;
use app\admin\model\Store_tab;
use app\admin\model\Time_set_rule_tab;
use app\admin\model\Order_tab;
use think\Request;

class Room extends Base
{
    /**
     * 房间列表
     * */
    public function roomList(){
        $sid = input('sid');
        $room = new Room_tab();
        $data = $room->roomList($sid);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('sid',$sid);
        return $this->fetch();
    }
    /**
     * 添加房间
     * */
    public function roomAdd(){

        if(session('roomAdd')){
            session('roomAdd',null);
            $this->redirect(url('admin/room/roomList'));
        }

        if(Request::instance()->isPost()){
            session('roomAdd',1);
            $data = input();
            $room = new Room_tab();
            $data['SOFA_FREE_QTY'] = $data['SOFA_QTY'];
            $res = $room->roomAdd($data);
            if($res){
                $this->success('添加房间成功');
            }else{
                $this->error("添加房间失败");
            }
        }else{
            $sid = input('sid');
            $this->assign('sid',$sid);
            return $this->fetch();
        }
    }
    /**
     * 修改房间
     */
    public function roomEdit(){
        $room_model = new Room_tab();
        $order_model = new Order_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $data['SOFA_FREE_QTY'] = $data['SOFA_QTY'];
            $room_cd = $data['ROOM_CD'];
            $store_cd = $data['STORE_CD'];
            unset($data['ROOM_CD']);
            unset($data['STORE_CD']);
            $result = $room_model->updateRoomByRidSid($data,$room_cd,$store_cd);
            $resultOrder = $order_model->updateOrderByCon(['ROOM_NAME'=>$data['ROOM_NAME']],['ROOM_CD'=>$room_cd,'STORE_CD'=>$store_cd]);
            if(!empty($result)){
                $this->success('修改房间信息成功');
            }else{
                $this->error("修改房间信息失败");
            }
        }else{
            $room_cd = input('room_cd');
            $sid = input('sid');
            $roomInfo = $room_model->getRoomOne($sid,$room_cd);
            if(!empty($roomInfo['ROOM_IMAGE'])){
                $imgArr = explode(',',$roomInfo['ROOM_IMAGE']);
                $this->assign('imgArr',$imgArr);
            }
            $this->assign('sid',$sid);
            $this->assign('roomInfo',$roomInfo);
            return $this->fetch();
        }

    }
    /**
     * 删除图片
     */
    public function imageDel(){
        if(Request::instance()->isPost()){
            $room_cd = input('room_cd');
            $imgInfo = input('imgInfo');
            $sid = input('sid');
            if($room_cd && $imgInfo){
                $room_model = new Room_tab();
                $roomInfo = $room_model->getRoomOne($sid,$room_cd);
                if(!empty($roomInfo['ROOM_IMAGE'])){
                    unlink(ROOT_PATH.'/'.$roomInfo['ROOM_IMAGE']);
                    $result = $room_model->updateRoomByRid(array('ROOM_IMAGE'=>''),$room_cd);
                    if(!empty($result)){
                        $data['state'] = 'success';
                        $data['msg'] = '删除成功';
                    }else{
                        $data['state'] = 'error';
                        $data['msg'] = '删除失败！';
                    }
                }else{
                    $data['state'] = 'error';
                    $data['msg'] = '该房间没有此图片！';
                }
            }else{
                $data['state'] = 'error';
                $data['msg'] = '信息不完整！';
            }
            echo json_encode($data);
        }
    }
    /**
     * 预约时间列表
     */
    public function roomTimeList()
    {
        $times_model = new Time_set_rule_tab();
        $store_name = input('STORE_NAME');
        $store_cd = input('STORE_CD');
        $condition = [];
        //$condition['AVAILABLE_FLG'] = 1;
        if($store_cd){
            $condition['STORE_CD'] = $store_cd;
        }
        if($store_name){
            $condition['STORE_NAME'] = array('LIKE','%'.$store_name.'%');
        }
        $list = $times_model->getTimePageList($condition);
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     * 新增预约时间
     */
    public function roomTimeAdd(){
        if(session('roomATimeAdd')){
            session('roomTimeAdd',null);
            $this->redirect(url('admin/room/roomTimeList'));
        }
        $times_model = new Time_set_rule_tab();
        if(Request::instance()->isPost()){
            $store_model = new Store_tab();
            $data = input();
            $dataAdd = [];
            $times = count($data['ORDER_START_DATE_TIME']);
            $storeInfo = $store_model->getStoreOne($data['STORE_CD']);
            $times_model->where(['STORE_CD'=>$data['STORE_CD']])->delete();
            for($i=0;$i<$times;$i++){
                $dataAdd[$i] = [
                    'STORE_CD' => $data['STORE_CD'],
                    'STORE_NAME' => $storeInfo['STORE_NAME'],
                    'ORDER_START_DATE_TIME' => $data['ORDER_START_DATE_TIME'][$i],
                    'ORDER_END_DATE_TIME' => $data['ORDER_END_DATE_TIME'][$i],
                    'PRICE' => $data['PRICE'][$i]?$data['PRICE'][$i]:0,
                    'CREATE_USER' => session('ADMIN_ID'),
                    'CREATE_DATE' => date('Y-m-d H:i:s',time()),
                    'UPDATE_USER' => session('ADMIN_ID'),
                    'UPDATE_DATE' => date('Y-m-d H:i:s',time())
                ];
            }
            $res_add = $times_model->saveAll($dataAdd);
            session('roomTimeAdd',1);
            if(!empty($res_add)){
                $this->success('增加成功',url('admin/room/roomTimeList'));
            }else{
                $this->error('增加失败！');
            }
        }else{
            $store_model = new Store_tab();
            $store_list = $store_model->storeListAll();
            foreach($store_list as $k=>$v){
                $count = $times_model->where(['STORE_CD'=>$v['STORE_CD']])->count();
                if($count > 0){
                    unset($store_list[$k]);
                }
            }
            $this->assign('store_list',$store_list);
            return $this->fetch();
        }
    }
    /**
     * 预约时间编辑
     */
    public function roomTimeEdit(){
        $times_model = new Time_set_rule_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $data['UPDATE_USER'] = session('ADMIN_ID');
            $data['UPDATE_DATE'] = date('Y-m-d H:i:s',time());
            $time_cd = $data['TIME_CD'];
            unset($data['TIME_CD']);
            $res_add = $times_model->updateRoomTime($data,$time_cd);
            if(!empty($res_add)){
                $this->success('修改成功',url('admin/room/roomTimeList'));
            }else{
                $this->error('修改失败！');
            }
        }else{
            $time_cd = input('time_cd');
            if(empty($time_cd)){
                $this->error('请选择您要编辑的预约时间');
            }
            //店铺信息
            $store_model = new Store_tab();
            $store_list = $store_model->storeListAll();
            $this->assign('store_list',$store_list);
            $timeInfo = $times_model->getTimeInfo($time_cd);
            $this->assign('timeInfo',$timeInfo);
            return $this->fetch();
        }

    }
    /**
     * 设置房间状态
     */
    public function roomSetFlg(){
        $room_cd  = input('room_cd');
        $sid = input('sid');
        if(!empty($room_cd)){
            $room_model = new Room_tab();
            $roomInfo = $room_model->getRoomOne($sid,$room_cd);
            $flg = $roomInfo['AVAILABLE_FLG']?'0':1;
            $result = $room_model->updateRoomByRid(array('AVAILABLE_FLG'=>$flg),$room_cd);
            if($result){
                $this->redirect(url('admin/room/roomList',['sid'=>$sid]));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * 删除房间
     */
    public function delRoom(){
        $room_cd  = input('room_cd');
        $sid = input('sid');
        if(!empty($room_cd)){
            $room_model = new Room_tab();
            $result = $room_model->where(['ROOM_CD'=>$room_cd,'STORE_CD'=>$sid])->delete();
            if($result){
                $this->redirect(url('admin/room/roomList',['sid'=>$sid]));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
	/**
     * 删除预约时间
     */
    public function roomTimeDelete(){
        $time_cd = input('time_cd');
        $time_model = new Time_set_rule_tab();
        if(!$time_cd){
            $this->error('请选择要删除的时间！');
        }
        $result = $time_model->where(['TIME_CD'=>$time_cd])->delete();
        if($result){
            $this->redirect(url('admin/room/roomTimeList'));
        }else{
            $this->error('删除失败！');
        }
    }
    /**
     * 设置时间状态
     */
    public function TimeSetFlg(){
        $time_cd  = input('time_cd');
        if(!empty($time_cd)){
            $time_model = new Time_set_rule_tab();
            $timeInfo = $time_model->getTimeInfo($time_cd);
            $flg = $timeInfo['AVAILABLE_FLG']?'0':1;			
            $result = $time_model->updateRoomTime(array('AVAILABLE_FLG'=>$flg),$time_cd);		
            if($result){
                $this->redirect(url('admin/room/roomTimeList'));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
	 /**
     * 查询房间编号是否已经存在
     */
    public function isSetRoomCd(){
        $room_model = new Room_tab();
        $post = input();
        if(empty($post['room_cd']) || empty($post['sid'])){
            $data['state'] = 100;
            $data['msg'] = '传入参数不完整！';
            echo json_encode($data);die();
        }
        $post['room_cd'] = ltrim($post['room_cd']);
        $condition = [
            'ROOM_CD'=>$post['room_cd'],
            'STORE_CD'=>$post['sid']
        ];
        $num = $room_model->getRoomNum($condition);
        if($num > 0 ){
            $data['state'] = 101;
            $data['msg'] = '该房间编号已经存在！';
        }else{
            $data['state'] = 'success';
            $data['msg'] = 'success';
        }
        echo json_encode($data);die();
    }
    /**
     * 查询并计算门店的预约时间
     */
    public function getRoomTime(){
        if(Request::instance()->isPost()){
            $store_cd = input('store_cd');
            if(!$store_cd){
                $data['state'] = 'fail';
                $data['msg'] = '未传入有效店铺信息';
            }else{
                $store_model = new Store_tab();
                $storeInfo = $store_model->getStoreOrderTime($store_cd);
                if($storeInfo){
                    $data['state'] = 'success';
                    $data['time'] = $storeInfo;
                }else{
                    $data['state'] = 'fail';
                    $data['time'] = '该店铺输入信息不完整，无法获得预约时段信息！';
                }
            }
            echo json_encode($data);die();
        }
    }
}