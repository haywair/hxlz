<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20 0020
 * Time: 15:43
 */

namespace app\admin\controller;

use app\admin\model\Score_rule_tab;
use think\Controller;
use think\Validate;
use think\Request;
class ScoreRule extends Base
{
    /**
     * 积分类型列表
     */
    public function index(){
        $score_model = new Score_rule_tab();
        $condition = array();
        $list = $score_model->getScoreCategoryList($condition);
        $page = $list->render();
        $num = $score_model->getScoreCategoryNum($condition);
        $this->assign('data',$list);
        $this->assign('page',$page);
        $this->assign('num',$num);
        return $this->fetch();
    }
    /**
     * 添加积分类型
     */
    public function add(){
        return $this->fetch();
    }
    /**
     * 添加积分类型提交
     */
    public function addPost(){
        $post = input();
        //验证传输的金额
        $validate = new Validate([
            'RECHARGE_MONEY'  => 'require|number',
            'GIFT_MONEY' => 'number'
        ]);
        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }
        $score_model = new Score_rule_tab();
        $post['CREATE_USER'] = session('user_id');
        $post['CREATE_TIME'] = date('Y-m-d H:i:s',time());
        $post['UPDATE_USER'] = session('user_id');
        $post['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
        $result = $score_model->scoreCategoryAdd($post);
        if(!empty($result)){
            $this->redirect(url('admin/score_category/index'));
        }else{
            $this->error('设置积分类型失败！');
        }


    }
    /**
     * 编辑充值金额
     */
    public function edit(){
        $score_model = new Score_rule_tab();
        $category_id = input('category_id');
        $scoreInfo = $score_model->getScoreCategoryById($category_id);
        $this->assign('scoreInfo',$scoreInfo);
        return $this->fetch();
    }
    /**
     * 编辑充值金额提交
     */
    public function editPost(){
        $post = input();
        //验证传输的金额
        $validate = new Validate([
            'RECHARGE_MONEY'  => 'require|number',
            'GIFT_MONEY' => 'number'
        ]);
        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }
        $score_model = new Score_rule_tab();
        $post['UPDATE_USER'] = session('user_id');
        $post['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
        $categoryId = $post['CATE_ID'];
        unset($post['CATEGORY_ID']);
        $result = $score_model->updateRechargeInfoById($post,$categoryId);
        if(!empty($result)){
            $this->redirect(url('admin/score_category/index'));
        }else{
            $this->error('修改积分类型失败！');
        }
    }
    /**
     * 设置充值金额类型可用状态
     */
    public function setScoreFlg(){
        $category_id  = input('category_id');
        if(!empty($category_id)){
            $score_model = new Score_rule_tab();
            $scoreInfo = $score_model-> getScoreCategoryById($category_id);
            $flg = $scoreInfo['AVAILABLE_FLG']?0:1;
            $result = $score_model->updateRechargeInfoById(array('AVAILABLE_FLG'=>$flg),$category_id);
            if($result){
                $this->redirect(url('admin/score_category/index'));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }

}