<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2 0002
 * Time: 11:06
 */
namespace app\admin\model;


class Admin_user_tab extends Base
{
    public function adminUserAdd($data){
        return $this->data($data)->save();
    }
    public function getAdminUserList(){
        return $this->paginate(10,false);
    }
    public function getAdminUserListCon($condition){
        return $this->where($condition)->paginate(10,false);
    }
    public function getAdminUserFlg(){
        return $this->where(['AVAILABLE_FLG'=>1])->select();
    }
    public function updateAdminUser($id,$data){
        return $this->where(['USER_ID'=>$id])->update($data);
    }
    public function getAdminUserInfo($condition){
        $info =  $this->where($condition)->find();
        if($info){
            return $info->toArray();
        }else{
            return;
        }
    }
}