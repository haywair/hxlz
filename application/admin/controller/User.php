<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 9:02
 */

namespace app\admin\controller;


use app\wx\model\User_tab;
use app\admin\model\Admin_user_tab;
use app\admin\model\Auth_group_tab;
use app\admin\model\Auth_group_access_tab;
use think\Request;

class User extends Base
{
    public function userList(){
        $user = new User_tab();
        $nick_name = input('NICK_NAME');
        $condition = [];
        if(!empty($nick_name)){
            $condition['NICK_NAME'] = array('LIKE','%'.$nick_name.'%');
            $condition['USER_NAME'] = array('LIKE','%'.$nick_name.'%');
            $this->assign('nick_name',$nick_name);
        }
        $data = $user->getUserListCon($condition);
        $page = $data->render();
        $mu = count($data);
        $this->assign('mu',$mu);
        $this->assign('page',$page);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function userDel(){
        $user_id = input('user_id');
        if(!$user_id){
            $this->error('未选取有效用户信息！');
        }
        $user_model = new User_tab();
        $userInfo = $user_model->idGetUserOne($user_id);
        $flg = $userInfo['AVAILABLE_FLG']?'0':1;
        $result = $user_model->updateUserOne($user_id,array('AVAILABLE_FLG'=>$flg));
        if($result){
            $this->redirect(url('admin/user/userList'));
        }else{
            $this->error('修改失败');
        }

    }
    public function adminUserList(){
        $auser_model = new Admin_user_tab();
        $user_login = input('USER_LOGIN');
        $condition = [];
        if(!empty($user_login)){
            $condition['USER_LOGIN'] = array('LIKE','%'.$user_login.'%');
        }
        $data =  $auser_model->getAdminUserListCon($condition);
        $page = $data->render();
        //$mu = count($data);
       // $this->assign('mu',$mu);
        $this->assign('page',$page);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 管理员账号设置状态
     */

    public function adminUserDel(){
        $user_id = input('user_id');
        if(!$user_id){
            $this->error('未选取有效用户信息！');
        }
        $user_model = new Admin_user_tab();
        $userInfo = $user_model->getAdminUserInfo(array('USER_ID'=>$user_id));
        $flg = $userInfo['AVAILABLE_FLG']?'0':1;
        $result = $user_model->updateAdminUser($user_id,array('AVAILABLE_FLG'=>$flg));
        if($result){
            $this->redirect(url('admin/user/adminUserList'));
        }else{
            $this->error('修改失败');
        }

    }
    /**
     * 增加管理员账号
     */
    public function adminUserAdd(){
        $role_model = new Auth_group_tab();
        if(Request::instance()->isPost()){
            $post = input();
            $user = new Admin_user_tab();
            $post['PASSWORD'] = md5($post['PASSWORD']);
            $post['CREATE_TIME'] = date('Y-m-d H:i:s',time());
            $post['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
            $result = $user->adminUserAdd($post);
            if(empty($result)){
                $this->error('创建失败！');
            }else{
                //增加用户权限组关系表数据
                $authGpAccess_model = new Auth_group_access_tab();
                $data = [
                    'UID' => $user->USER_ID,
                    'GROUP_ID'=>$post['GROUP_ID']
                ];
                $resAcess = $authGpAccess_model->authGroupAccessAdd($data);
                $this->redirect(url('admin/user/adminUserList'));
            }
        }else{
            //用户权限组列表
            $condition = [];
            $condition['AVAILABLE_FLG'] = 1;
            $roleList = $role_model->getRoleList($condition);
            $this->assign('roleList',$roleList);
            return $this->fetch();
        }
    }
    /**
     * 修改管理员账号
     */
    public function adminUserEdit(){
        $user = new Admin_user_tab();
        if(Request::instance()->isPost()){
            $post = input();
            if(empty($post['PASSWORD'])){
                unset($post['PASSWORD']);
            }else{
                $post['PASSWORD'] = md5($post['PASSWORD']);
            }

            $post['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
            if($post['user_id']) {
                $user_id = $post['user_id'];
                unset($post['user_id']);
                $result = $user->updateAdminUser($user_id,$post);
            }else{
                $this->error('未获取有效用户信息！');
            }

            if(empty($result)){
                $this->error('修改失败！');
            }else{
                //增加用户权限组关系表数据
                $authGpAccess_model = new Auth_group_access_tab();
                $authGpAccess_model->where(['UID'=>$user_id])->delete();
                $data = [
                    'UID' => $user_id,
                    'GROUP_ID'=>$post['GROUP_ID']
                ];
                $resAcess = $authGpAccess_model->authGroupAccessAdd($data);
                $this->redirect(url('admin/user/adminUserList'));
            }
        }else{
            $user_id = input('USER_ID');
            $role_model = new Auth_group_tab();
            $userInfo = $user->getAdminUserInfo(array('USER_ID'=>$user_id));
            //用户权限组列表
            $condition = [];
            $condition['AVAILABLE_FLG'] = 1;
            $roleList = $role_model->getRoleList($condition);
            $this->assign('roleList',$roleList);
            $this->assign('userInfo',$userInfo);
            return $this->fetch();
        }
    }

}