<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5 0005
 * Time: 9:20
 */
namespace app\admin\model;

class Carousel_figure_tab extends Base{
    /**
     * 获取轮播图列表
     */
    public function getCarouselList($condition){
        return $this->where($condition)->paginate(15,false);
    }
    /**
     * 添加轮播图
     */
    public function carouselPicAdd($data){
        return $this->data($data)->save();
    }
    /**
     * 获取轮播图信息
     * @param $id 轮播图id
     */
    public function getCarouselPicById($id){
        return $this->where(['CAROUSEL_ID'=>$id])->find();
    }
    /**
     * 获取轮播图数量
     */
    public function getCarouselPicNum($condition){
        return $this->where($condition)->count();
    }
    /**
     * 修改轮播图
     *  @param $id 轮播图id
     *  @param $data 修改数据
     */
    public function carouselPicUpdateById($data,$id){
        return $this->where(['CAROUSEL_ID'=>$id])->update($data);
    }
}