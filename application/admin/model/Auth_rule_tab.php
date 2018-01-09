<?php
/**
 * 权限规则model
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/4 0004
 * Time: 9:02
 */
namespace app\admin\model;

class Auth_rule_tab extends Base{

    public function getRulesFlg($condition=[]){
        if(!$condition){
            $condition['AVAILABLE_FLG'] = 1;
        }
        return $this->where($condition)->select();
    }

}