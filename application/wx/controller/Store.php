<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 8:36
 */

namespace app\wx\controller;


use app\admin\model\Project_tab;
use app\admin\model\Store_tab;
use app\wx\model\User_tab;
use app\admin\model\Project_plan_price_tab;

class Store extends Base
{
    /**
     * 门店列表
     *
     * */
    public function StoreList(){
        $store = new Store_tab();
        $condition = [];
        $city_cd = input('city_cd');
        if(!empty($city_cd)){
            $condition =[
                'CITY_CD'=>$city_cd,
                'AVAILABLE_FLG'=>1
            ];
        }else{
            $condition['AVAILABLE_FLG'] = 1;
        }
        $data = $store->storeListAll(session("latitude"),session("longitude"),$condition);
        $flag=[];
        //排序
        foreach ($data as $k=>$v){
           $v['juli'] = getDistance(session("latitude"),session("longitude"),$v["LATITUDE"],$v['LONGITUDE']);
            $flag[]=$v["juli"];
            $img[] = explode(',',$v['STORE_IMAGE']);
            $v['STORE_IMAGE'] =  $img[$k][0];
        }
        array_multisort($flag, SORT_ASC, $data);
        $this->assign('data',$data);
        return $this->fetch();
    }
    /**
     * 门店详情
     * */
    public function StoreDetailed(){
        $user = new User_tab();
        $store = new Store_tab();
        $project = new Project_tab();
        $userid = $user->openidGetUserOne(session('openid'));
        if($userid['TEL_NO']){
            $sid = input('sid');
            $data = $store->getStoreOne($sid);
            $storeImg = explode(',',$data['STORE_IMAGE']);

            $pdata = $project->getProjectStoreAll($sid);
            $priceat = new Project_plan_price_tab();
            foreach ($pdata as $k=>$value){
                $value['PRICE'] = round($value['PRICE'],2);
                $img = explode(',',$value['PROJECT_IMAGE']);
                $value['PROJECT_IMAGE'] =  $img[0];
					      if (mb_strlen($value['PROJECT_NAME'], 'UTF-8')>=15) {
				    	      $value['PROJECT_NAME'] = mb_substr($value['PROJECT_NAME'], 0, 15, 'UTF-8');
				        }
                $priceatInfo = $priceat->getProjectPriceatInfo($value['PROJECT_ID']);
                if($priceatInfo){
                    if(mb_strlen($priceatInfo['PLAN_NAME'], 'UTF-8')>15) {
                        $pdata[$k]['PRICEAT_NAME'] = mb_substr($priceatInfo['PLAN_NAME'], 0, 15, 'UTF-8');
                    }else {
                        $pdata[$k]['PRICEAT_NAME'] = $priceatInfo['PLAN_NAME'];
                    }
                }
            }
			$this->assign('sid',$sid);
            $this->assign('storeImg',$storeImg);
            $this->assign('pdata',$pdata);
            $this->assign('data',$data);
            return $this->fetch();
        }else{
            $this->redirect(WEB_URL.'/wx/home/binding');
        }

    }
    /**
     * 导航
     */
    public function navigation(){
        //门店经纬度
        $storeLat = input('storeLat');
        $storeLong = input('storeLong');
        $storeCd = input('storeCd');
        //用户经纬度位置
        $userDirect = [
            'userLat' => session('latitude'),
            'userLong' => session('longitude')
        ];
        $this->assign('storeCd',$storeCd);
        $this->assign('storeLat',$storeLat);
        $this->assign('storeLong',$storeLong);
        $this->assign('userDirect',$userDirect);
        return $this->fetch();
    }
    /**
     * 公交
     */
    public function transit(){
        //门店经纬度
        $storeLong = input('longitude');
        $storeLat = input('latitude');
        $storeCd = input('storeCd');
        $disInfo = session('DIS_INFO');
        $storeDisInfo = getInformation($storeLat,  $storeLong);
        //门店信息
        $store_model = new Store_tab();
        $storeInfo = $store_model->getStoreOne($storeCd);
        //用户经纬度位置
        $userDirect = [
            'userLat' => session('latitude'),
            'userLong' => session('longitude')
        ];

        /*print_r($userDirect);
        print_r($disInfo);die();*/
        $this->assign('storeDisInfo',$storeDisInfo);
        $this->assign('disInfo',$disInfo);
        $this->assign('storeLat',$storeLat);
        $this->assign('storeLong',$storeLong);
        $this->assign('userDirect',$userDirect);
        $this->assign('storeInfo',$storeInfo);
        return $this->fetch();
    }
    /**
     * 自驾
     */
    public function drive(){
        //门店经纬度
        $storeLong = input('longitude');
        $storeLat = input('latitude');
        $storeCd = input('storeCd');
        $disInfo = session('DIS_INFO');
        $storeDisInfo = getInformation($storeLat,  $storeLong);
        //用户经纬度位置
        $userDirect = [
            'userLat' => session('latitude'),
            'userLong' => session('longitude')
        ];
        //门店信息
        $store_model = new Store_tab();
        $storeInfo = $store_model->getStoreOne($storeCd);
        /*print_r($userDirect);
        print_r($disInfo);die();*/
        $this->assign('storeDisInfo',$storeDisInfo);
        $this->assign('disInfo',$disInfo);
        $this->assign('storeLat',$storeLat);
        $this->assign('storeLong',$storeLong);
        $this->assign('userDirect',$userDirect);
        $this->assign('storeInfo',$storeInfo);
        return $this->fetch();
    }
}