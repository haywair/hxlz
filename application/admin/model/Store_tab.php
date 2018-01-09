<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 15:39
 */

namespace app\admin\model;


class Store_tab extends Base
{
    private $store_type;
    public function storeAdd($data){
        return $this->data($data)->save();
    }
    public function storeList($condition=[]){
        return $this->where($condition)->paginate(15,false,['query' => request()->param()]);
    }
    public function storeListAll($latitude="",$longitude="",$condition=[]){
        if(!$condition){
            $condition = ['AVAILABLE_FLG'=>1];
        }
		if($latitude && $longitude){
			$distance = ',(2 * 6378.137* ASIN(SQRT(POWER(SIN(PI()*('.$longitude.'-LONGITUDE)/360),2)+COS(PI()*'.$latitude.'/180)* COS(LATITUDE * PI()/180)*POWER(SIN(PI()*('.$latitude.'-LATITUDE)/360),2)))) as juli';
			$field = '*'.$distance;		
			$order = 'juli asc';
		}else{
			$field = '*';
			$order = '';
		}
        return $this->field($field)->where($condition)->order($order)->select();
    }	
    /**
     *根据门店ID批量查询门店
     * */
    public function getStore($ssid){
        return $this->where('STORE_CD',"in",$ssid)->select();
    }
    public function getStoreOne($sid){
        return $this->where(['STORE_CD'=>$sid])->find();
    }
    public function storeListCondition($condition){
        return $this->where($condition)->select();
    }
    public function getStoreType(){
        $this->store_type = array('直营','连锁','其他','后舍');
        return $this->store_type;
    }
    public function updateStoreByBySid($data,$sid){
        return $this->where(['STORE_CD'=>$sid])->update($data);
    }
    public function updateStoreBySid($data,$sid){
        return $this->where(['STORE_ID'=>$sid])->update($data);
    }
    /**
     * 获取门店的预约时间
     */
    public function getStoreOrderTime($store_cd){
        $storeInfo = $this->where(['STORE_CD'=>$store_cd])->find();
        if($storeInfo['STORE_START_TIME'] && $storeInfo['STORE_END_TIME'] && $storeInfo['ORDER_TIME_CELL']){
            $start_time = strtotime(date('Y-m-d ').$storeInfo['STORE_START_TIME']);
            $end_time = strtotime(date('Y-m-d ').$storeInfo['STORE_END_TIME']);
            if($end_time < $start_time){
                $end_time = strtotime(date("Y-m-d",strtotime("+1 day")).$storeInfo['STORE_END_TIME']);
            }
            $order_cell = $storeInfo['ORDER_TIME_CELL']*60;
            $num = ceil(($end_time-$start_time)/$order_cell);
            $extra_time = ($end_time-$start_time)%$order_cell;
            $data = [];
            $times = '';
            if(!empty($extra_time)){
                $times = $num+1;
            }else{
                $times = $num;
            }
            for($i=1;$i<=$times;$i++){
                $start = $start_time+($i-1)*$order_cell;
                $end = ($start_time+$order_cell)+($i-1)*$order_cell;
                $data[$i-1] = [
                    'start' => date('H:i',$start),
                    'end' => date('H:i',$end)
                ];
            }
            if($data){
                return $data;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }
}