<?php
/**
 * 后台登录和退出
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2 0002
 * Time: 9:54
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin_user_tab;

class Login extends Controller
{
    /**
     * 登录页
     */
    public function index()
    {
        $admin_id = session('ADMIN_ID');
        if (!empty($admin_id)) {//已经登录
            $this->redirect(url("admin/index/index"));
        } else {
           return $this->fetch();
        }
    }

    /**
     * 登录验证
     */

    public function doLogin(){
        /*$captcha = $this->request->param('captcha');
          if (empty($captcha)) {
             $this->error('请输入您的验证码');
         }
         //验证码
         if (!captcha_check($captcha)) {
             $this->error('验证码不正确！');
         }*/
        $name = $this->request->param("username");
        if (empty($name)) {
            $this->error('请输入您的用户名');
        }
        $pass = $this->request->param("password");
        if (empty($pass)) {
            $this->error('请输入您的密码');
        }
        $where['USER_LOGIN'] = $name;
        $model = new Admin_user_tab();
        $result = $model->getAdminUserInfo($where);

        if (!empty($result) && $result['AVAILABLE_FLG'] == 1) {
            if (md5($pass) == $result['PASSWORD']) {
                //登入成功页面跳转
                session('ADMIN_ID', $result["USER_ID"]);
                session('user_name', $result["USER_LOGIN"]);
                $data = array();
                $data['LAST_LOGIN_TIME'] = date('Y-m-d H:i:s',time());
                $model->updateAdminUser($result['USER_ID'],$data);
                cookie("admin_username", $name, 3600 * 24 * 30);
                //$this->success('登录成功', url("Index/index"));
				$this->redirect(url("Index/index"));
            } else {
                $this->error('登录失败');
            }
        } else {
            $this->error('该用户不存在或不可用！');
        }
    }
    /**
     * 后台管理员退出
     */
    public function logout()
    {
        session('ADMIN_ID', null);
        $this->redirect(url('admin/login/index'));
    }
}