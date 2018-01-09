<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/1 0001
 * Time: 8:50
 */
namespace app\admin\controller;

use app\admin\model\Auth_group_tab;
use app\admin\model\Auth_rule_tab;
use think\Validate;
use think\Request;

class Role extends Base{
    //角色列表
    public function roleList(){
        $role_model = new Auth_group_tab();
        $condition = [
            'AVAILABLE_FLG'=>1
        ];
        $roleList = $role_model->getRoleList($condition);
        $page = $roleList->render();
        $this->assign('roleList',$roleList);
        $this->assign('page',$page);
        return $this->fetch();
    }
    //添加角色
    public function roleAdd(){
        return $this->fetch();
    }
    //添加角色处理
    public function roleAddPost(){
        $role_model = new Auth_group_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $rule = [
                'ROLE_NAME' => 'require'
            ];
            $error = $this->validateInput($rule,$data);
            if(!empty($error)){
                $this->error($error);
            }
            $data['CREATE_TIME'] = time();
            $data['CREATE_USER'] = session('ADMIN_ID');
            $data['UPDATE_TIME'] = time();
            $data['UPDATE_USER'] = session('ADMIN_ID');
            $res = $role_model->roleAdd($data);
            if(!empty($res)){
                $this->redirect(url('admin/role/roleList'));
            }else{
                $this->error('添加失败',url('admin/role/roleList'));
            }


        }
    }
    /**
     * 角色编辑
     */
    public function roleEdit(){
        $role_id = input('role_id');
        if(empty($role_id)){
            $this->error('未选定要修改的角色');
        }
        $role_model = new Auth_group_tab();
        $roleInfo = $role_model->getrRoleInfo($role_id);
        $this->assign('roleInfo',$roleInfo);
        return $this->fetch();
    }
    //修改角色处理
    public function roleEditPost(){
        $role_model = new Auth_group_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $rule = [
                'ROLE_NAME' => 'require'
            ];
            $error = $this->validateInput($rule,$data);
            if(!empty($error)){
                $this->error($error);
            }
            $role_id = $data['ROLE_ID'];
            unset($data['ROLE_ID']);
            $data['UPDATE_TIME'] = time();
            $data['UPDATE_USER'] = session('ADMIN_ID');
            $res = $role_model->updateRoleByRid($data,$role_id);
            if(!empty($res)){
                $this->redirect(url('admin/role/roleList'));
            }else{
                $this->error('修改失败',url('admin/role/roleEdit',['role_id'=>$role_id]));
            }


        }
    }
    /**
     * ajax获得权限规则
     */
    public function getRules(){
        if(Request::instance()->isPost()){
            $role_id = input('role_id');
        }

    }
    /**
     * 权限规则分配
     */
    public function roleRules(){
        $role_model = new Auth_group_tab();
        if(Request::instance()->isPost()){
            $post = input();
            $role_id = $post['ROLE_ID'];
            $ruleArr = array_unique($post['RULE']);
            $data = [
                'RULES'=>implode(',',$ruleArr)
            ];
            $result = $role_model->updateRoleByRid($data,$role_id);
            if(!empty($result)){
                $this->success('权限分配成功',url('admin/role/roleList'));
            }else{
                $this->success('权限分配失败',url('admin/role/roleRules',['role_id'=>$role_id]));
            }
        }else{
            $role_id = input('role_id');
            $rule_model = new Auth_rule_tab();
            //角色设置的权限
            $roleInfo = $role_model->getrRoleInfo($role_id);
            $roleRules = explode(',',$roleInfo['RULES']);
            //权限规则列表
            $condRule = [
                'PARENTS' => 0,
                'STATUS' => 1
            ];
            $rules = $rule_model->getRulesFlg($condRule);
            foreach($rules as $k=>$v){
                $conRuleSon = [
                    'PARENTS' => $v['ID'],
                    'STATUS' => 1
                ];
                $ruleSons = $rule_model->getRulesFlg($conRuleSon);
                $rules[$k]['CHILDREN'] = $ruleSons;
            }
            $this->assign('role_id',$role_id);
            $this->assign('roleRules',$roleRules);
            $this->assign('rules',$rules);
            return $this->fetch();
        }
    }
    /**
     * 验证表单
     */
    private function validateInput($rule,$data){
        $validate = new Validate($rule);
        if (!$validate->check($data)) {
            return $validate->getError();
        }
        return false;
    }
}