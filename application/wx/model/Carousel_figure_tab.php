<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 16:27
 */

namespace app\wx\model;


class Carousel_figure_tab extends Base
{
    public function getCarouselList(){
        return $this->where(['STATE'=>1])->select();
    }
}