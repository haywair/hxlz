<?php
/**
 * Created by PhpStorm.
 * 权限角色model
 * User: Administrator
 * Date: 2017/8/1 0001
 * Time: 9:26
 */
namespace app\admin\model;


class Auth_group_tab extends Base
{
    public function roleAdd($data){
        return $this->data($data)->save();
    }
    public function getRoleList($condition){
        return $this->where($condition)->paginate(10,false,['query' => request()->param()]);
    }
    public function updateRoleByRid($data,$roleId){
        return $this->where(['ROLE_ID'=>$roleId])->update($data);
    }
    public function getrRoleInfo($roleId){
        $info =  $this->where(['ROLE_ID'=>$roleId])->find();
        if($info){
            return $info->toArray();
        }else{
            return;
        }
    }
}