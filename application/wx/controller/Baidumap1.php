<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 15:11
 */

namespace app\wx\controller;


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
        session('latitude',$latitude);
        session('longitude',$longitude);
        list($latitudeB, $longitudeB) = self::getBaiduCoords($latitude, $longitude);
        $information = self::getInformation($latitudeB, $longitudeB);

        // TODO: 这里要实现用户经纬度和位置信息的存储 并返回店铺名

        return $information['city'];
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