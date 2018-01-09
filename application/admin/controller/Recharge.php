<?php
/**
 * 充值金额类型设定
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21 0021
 * Time: 17:32
 */
namespace app\admin\controller;

use app\admin\model\Recharge_money_category_tab;
use think\Validate;
use think\Request;

class Recharge extends Base{
    /**
     * 充值金额类型列表
     */
    public function index(){
        $recharge_model = new Recharge_money_category_tab();
        $condition = array();
        $list = $recharge_model->getRechargeList($condition);
        $page = $list->render();
        $num = $recharge_model->getRechargeCateNum($condition);
        $this->assign('data',$list);
        $this->assign('page',$page);
        $this->assign('num',$num);
        return $this->fetch();
    }
    /**
     * 添加充值金额类型
     */
    public function rechargeAdd(){
        return $this->fetch();
    }
    /**
     * 添加充值金额提交
     */
    public function rechargeAddPost(){
        $post = input();
        //验证传输的金额
        $validate = new Validate([
            'RECHARGE_MONEY'  => 'require|number',
            'GIFT_MONEY' => 'number'
        ]);
        if (!$validate->check($post)) {
           $this->error($validate->getError());
        }
        $recharge_model = new Recharge_money_category_tab();
        $post['CREATE_USER'] = session('user_id');
        $post['CREATE_TIME'] = date('Y-m-d H:i:s',time());
        $post['UPDATE_USER'] = session('user_id');
        $post['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
        $result = $recharge_model->rechargeAdd($post);
        if(!empty($result)){
            $this->redirect(url('admin/recharge/index'));
        }else{
            $this->error('设置充值金额失败！');
        }


    }
    /**
     * 编辑充值金额
     */
    public function rechargeEdit(){
        $recharge_model = new Recharge_money_category_tab();
        $recharge_id = input('recharge_id');
        $rechargeInfo = $recharge_model->getRechargeInfoById($recharge_id);
        $this->assign('rechargeInfo',$rechargeInfo);
        return $this->fetch();
    }
    /**
     * 编辑充值金额提交
     */
    public function rechargeEditPost(){
        $post = input();
        //验证传输的金额
        $validate = new Validate([
            'RECHARGE_MONEY'  => 'require|number',
            'GIFT_MONEY' => 'number'
        ]);
        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }
        $recharge_model = new Recharge_money_category_tab();
        $post['UPDATE_USER'] = session('user_id');
        $post['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
        $rechargeId = $post['RECHARGE_ID'];
        unset($post['RECHARGE_ID']);
        $result = $recharge_model->updateRechargeInfoById($post,$rechargeId);
        if(!empty($result)){
            $this->redirect(url('admin/recharge/index'));
        }else{
            $this->error('修改充值金额失败！');
        }
    }
    /**
     * 设置充值金额类型可用状态
     */
    public function setRechargeFlg(){
        $recharge_id  = input('recharge_id');
        if(!empty($recharge_id)){
            $recharge_model = new Recharge_money_category_tab();
            $rechargeInfo = $recharge_model-> getRechargeInfoById($recharge_id);
            $flg = $rechargeInfo['AVAILABLE_FLG']?0:1;
            $result = $recharge_model->updateRechargeInfoById(array('AVAILABLE_FLG'=>$flg),$recharge_id);
            if($result){
                $this->redirect(url('admin/recharge/index'));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
}