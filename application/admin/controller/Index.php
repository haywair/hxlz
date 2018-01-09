<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 8:42
 */

namespace app\admin\controller;


use app\wx\model\User_tab;

class Index extends Base
{
    public function index(){
        return $this->fetch();
    }
}