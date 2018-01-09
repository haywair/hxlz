<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 17:09
 */

namespace app\admin\model;


class Room_tab extends Base
{
    public function roomAdd($data){
        return $this->data($data)->save();
    }
    public function roomList($sid){
        return $this->where(['STORE_CD'=>$sid])->paginate(10,false,['query' => request()->param()]);			
    }
    public function roomList1($sid){
        return $this->where(['STORE_CD'=>$sid])->select();
    }
    public function getRoomListByID($rid){
        return $this->where('ROOM_CD',"in",$rid)->select();
    }
    public function getRoomListBySID($rid,$sid){
        return $this->where('ROOM_CD',"in",$rid)->where('STORE_CD',$sid)->where('AVAILABLE_FLG',1)->select();
    }
    public function getRoomOne($sid,$rid){
        return $this->where(['STORE_CD'=>$sid,'ROOM_CD'=>$rid])->find();
    }
    public function updateRoomByRid($data,$rid){
        return $this->where(['ROOM_CD'=>$rid])->update($data);
    }
    public function updateRoomByRidSid($data,$room_cd,$store_cd){
        return $this->where(['ROOM_CD'=>$room_cd,'STORE_CD'=>$store_cd])->update($data);
    }
	public function getRoomNum($condition){
        return $this->where($condition)->count();
    }
}