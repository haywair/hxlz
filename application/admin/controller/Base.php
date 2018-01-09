<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin_user_tab;
use think\Request;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 8:40
 */
class Base extends Controller
{
    public function _initialize()
    {
        $session_admin_id = session('ADMIN_ID');
        if (!empty($session_admin_id)) {
            $user_model = new Admin_user_tab();
            $user = $user_model->where(['USER_ID' => $session_admin_id])->find();
            if($user){
                //权限认证
                if($user['GROUP_ID'] != 1){
                     $auth = new \Auth\Auth();
                     $request = Request::instance();
                     $action = $request->module() . '/' . $request->controller() . '/' . $request->action();
                     if(!in_array($action,$auth->allowModel) && !$auth->check($action, $session_admin_id)) {
                        // 第一个参数是规则名称,
                        //第二个参数是用户UID
                            $this->error('你没有权限进入该模块',url('admin/index/index'));
                     }
                }
                $this->assign("admin", $user);
            }else{
                $this->error("该用户不存在！", url("admin/login/index"));
            }
        } else {
            if ($this->request->isPost()) {
                $this->error("您还没有登录！", url("admin/login/index"));
            } else {
                header("Location:" . url("admin/login/index"));
                exit();
            }
        }

    }

}