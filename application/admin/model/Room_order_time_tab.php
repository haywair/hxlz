<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14 0014
 * Time: 9:26
 */
namespace app\admin\model;

class Room_order_time_tab extends Base{
    /**
     * 房间预约时段列表
     */
    public function roomTimeList($condition){
        $timestamp = "DATEDIFF(s,'1970-01-01 00:00:00',(ORDER_DATA+' '+ORDER_START_DATE_TIME)) as tf";
        $field = '*,'.$timestamp;
        return $this->field($field)->where($condition)->order('tf asc')->select();
    }
    public function roomTimeList2($condition){
        $timestamp = "DATEDIFF(s,'1970-01-01 00:00:00',(a.ORDER_DATA+' '+a.ORDER_START_DATE_TIME)) as tf";
        $field = 'a.*,'.$timestamp.',b.ORDER_CD,b.USER_NAME,b.TEL_NO';
        $join = [
            ['ORDER_TAB b','a.RTIME_CD = b.RTIME_CD']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->order('tf asc')->select();
    }
    /**
     * 增加房间预约信息
     */
    public function roomTimeAdd($data){
        return $this->data($data)->save();
    }
    /**
     * 查询记录数量
     */
    public function getRoomNoteNum($condition){
        return $this->where($condition)->count();
    }
    /**
     * 更改房间预约信息
     */
    public function updateRoomNoteByCon($data,$condition){
        return $this->where($condition)->update($data);
    }
    /**
     * 查询某门店下的房间预约信息
     */
    public function getStoreRoomInfo($condition,$group){
        return $this->field('ROOM_CD,count(*) AS PC')->where($condition)->group($group)->select();
    }
}
