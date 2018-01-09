<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/24
 * Time: 15:36
 */

namespace app\wx\model;


class User_tab extends Base
{
    public function addUserOpenid($data){
        return $this->save($data);
    }
    /**
     * 根据OPENID获取用户
     * */
    public function openidGetUserOne($data){
        return $this->where(['OPENID'=>$data])->find();
    }
    /**
     * 根据ID获取用户
     * */
    public function idGetUserOne($uid){
        return $this->where(['USER_ID'=>$uid])->find();
    }
    /**
     * 添加用户手机
     * */
    public function updateTel_no($data,$openid){
        return $this->where(['OPENID'=>$openid])->update(['TEL_NO'=>$data]);
    }
    /**
     * 更改用户信息
     * */
    public function updateUserInfo($data,$openid){
        return $this->where(['OPENID'=>$openid])->update($data);
    }
    /**
     * 获取用户列表
     * */
    public function getUserList(){
        return $this->field("USER_ID,NICK_NAME,SEX,USER_NAME,TEL_NO,AVAILABLE_FLG,UPDATE_DATE,PHOTO_HEAD")->order('UPDATE_DATE DESC')->paginate(15,false);
    }
    public function getUserListCon($condition){
        return $this->field("USER_ID,NICK_NAME,USER_NAME,SEX,TEL_NO,AVAILABLE_FLG,UPDATE_DATE,PHOTO_HEAD")->where($condition)->order('UPDATE_DATE DESC')->paginate(15,false,['query' => request()->param()]);
    }
    public function updateUserOne($uid,$data){
        return $this->where(['USER_ID'=>$uid])->update($data);
    }
    /**
     * 获取用户数量
     */
    public function getUserNumCon($condition){
        return $this->where($condition)->count();
    }

}