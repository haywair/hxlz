<?php
/**
 * 用户角色映射表
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3 0003
 * Time: 16:30
 */
namespace app\admin\model;

class Auth_group_access_tab extends Base{
    //增加角色映射
    public function authGroupAccessAdd($data){
        return $this->data($data)->save();
    }

}