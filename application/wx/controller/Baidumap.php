<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 15:11
 */

namespace app\wx\controller;

use app\admin\model\Store_tab;
use app\admin\model\Project_tab;
use app\admin\model\Project_plan_price_tab;
use think\Request;

class Baidumap extends Base
{
    //这里先用我的密钥 你记得换
    const AK = '9e9v0Vdhe4HyFS2UCrFcWfAp1Vefsuoe';

    /**
     * API 将前台返回的经纬度做处理 并返回前端位置信息
     * @return string
     */
    public function getArea()
    {
        $latitude = input('lat');
        $longitude = input('long');
        $flag=[];
        $store_model = new Store_tab();
        $project_model = new Project_tab();
        list($latitudeB, $longitudeB) = self::getBaiduCoords($latitude, $longitude);
        $information = self::getInformation($latitudeB, $longitudeB);
        session('latitude',$latitudeB);
        session('longitude',$longitudeB);
        session('DIS_INFO',$information);

        // TODO: 这里要实现用户经纬度和位置信息的存储 并返回店铺名
        $storeList = $store_model->storeListAll(session('latitude'),session('longitude'));
        $flag=[];
        //排序
        foreach ($storeList as $k=>$v){
            $v['juli'] = getDistance(session("latitude"),session("longitude"),$v["LATITUDE"],$v['LONGITUDE']);
            $flag[]=$v["juli"];
            $img[] = explode(',',$v['STORE_IMAGE']);
            $v['STORE_IMAGE'] =  $img[$k][0];
        }
        array_multisort($flag, SORT_ASC, $storeList);
        $projectList = $project_model->getProjectStoreAll($storeList[0]['STORE_CD']);
        $priceat = new Project_plan_price_tab();
        foreach ($projectList as $k=>$value) {
            $value['PRICE'] = round($value['PRICE'],2);
            $value['MARKET_PRICE']= round($value['MARKET_PRICE']);
            $img[$k] = explode(',', $value['PROJECT_IMAGE']);
            $value['PROJECT_IMAGE'] = $img[$k][0];
            //截取项目名称
            if(mb_strlen($value['PROJECT_NAME'], 'UTF-8')>15) {
                $value['PROJECT_NAME'] = mb_substr($value['PROJECT_NAME'], 0, 15, 'UTF-8');
            }
            //截取项目介绍
            if (mb_strlen($value['PROJECT_INTRODUCE'], 'UTF-8')>=11) {
                $value['PROJECT_INTRODUCE'] = mb_substr($value['PROJECT_INTRODUCE'], 0, 11, 'UTF-8').'...';
            }
            $priceatInfo = $priceat->getProjectPriceatInfo($value['PROJECT_ID']);
            //截取项目优惠方案名称
            if($priceatInfo){
                if(mb_strlen($priceatInfo['PLAN_NAME'], 'UTF-8')>15) {
                    $projectList[$k]['PRICEAT_NAME'] = mb_substr($priceatInfo['PLAN_NAME'], 0, 15, 'UTF-8');
                }else {
                    $projectList[$k]['PRICEAT_NAME'] = $priceatInfo['PLAN_NAME'];
                }
            }
        }
        $data = [
            'city_name'=> $information['city'],
            'store_name'=>$storeList[0]['STORE_NAME'],
            'store_cd'=>$storeList[0]['STORE_CD'],
            'projectList'=>$projectList,
            'storeInfo'=>$storeList[0],
            'area'=>[session('latitude'), session('longitude')]
        ];
        return $data;
    }



    /**
     * 根据腾讯坐标获取百度坐标
     * @param float $latitude 纬度
     * @param float $longitude 经度
     * @return array|bool 成功返回[纬度, 经度] 失败返回false
     */
    protected static function getBaiduCoords($latitude, $longitude){
        //将微信经纬度 转换为百度坐标
        $url = 'http://api.map.baidu.com/geoconv/v1/?coords='.$longitude.','.$latitude.'&ak='.self::AK;
        $str = file_get_contents($url);
        $res = json_decode($str,true);

        if ($res['status'] == 0){
            return [$res['result'][0]['y'], $res['result'][0]['x']];
        }else{
            return false;
        }
    }

    /**
     * 根据经纬度获取位置信息
     * @param $latitude
     * @param $longitude
     * @return mixed
     */
    protected static function getInformation($latitude, $longitude)
    {
        $url = "http://api.map.baidu.com/geocoder/v2/?ak=".self::AK."&callback=renderReverse&location=".$latitude.",".$longitude."&output=json";
        $str = file_get_contents($url);
        $str = substr($str,strpos($str,'{'));
        $str = substr($str,0,strlen($str)-1);
        $res=json_decode($str,true);
        $GI['addrName']= $res["result"]["sematic_description"];
        $GI['address'] = $res["result"]["formatted_address"];
        $GI['city']    = $res["result"]["addressComponent"]["city"];
        $GI['district']= $res["result"]["addressComponent"]["district"];
        $GI['province']= $res["result"]["addressComponent"]["province"];
        return $GI;
    }

}