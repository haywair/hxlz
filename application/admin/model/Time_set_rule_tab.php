<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/13 0013
 * Time: 10:23
 */
namespace app\admin\model;


class Time_set_rule_tab extends Base{
    /**
     * 时间类别列表
     */
    public function getTimePageList($condition){
        return $this->where($condition)->paginate(15);
    }
    /**
     * 时间类别列表(无分页)
     */
    public function getTimeList($condition){
        $timestamp = "DATEDIFF(s,'1970-01-01 00:00:00',(CONVERT(varchar(100), GETDATE(), 23)+' '+ORDER_START_DATE_TIME )) as tf,";	
		$timestamp .= '(ORDER_START_DATE_TIME+ORDER_END_DATE_TIME) AS ORDER_ALL_DATE_TIME';	
        $field = '*,'.$timestamp;
        return $this->field($field)->where($condition)->order('TIME_CD asc')->select();
    }
    /**
     * 增加时间类别
     */
    public  function addRoomTime($data){
        return $this->data($data)->save();
    }
    /**
     * 修改时间类别
     */
    public function updateRoomTime($data,$timeID){
        return $this->where(['TIME_CD'=>$timeID])->update($data);
    }
    /**
     * 修改时间规则信息
     */
    public function updateTimeInfo($data,$condition){
        return $this->where($condition)->update($data);
    }
    /**
     * 获取某一预约时间具体信息
     */
    public function getTimeInfo($time_cd){
        return $this->where(['TIME_CD'=>$time_cd])->find();
    }

}
