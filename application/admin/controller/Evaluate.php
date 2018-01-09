<?php
/**
 * 评价
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7 0007
 * Time: 16:55
 */
namespace app\admin\controller;

use app\admin\model\Evaluate_tab;
use app\admin\model\Setting_tab;
use think\Request;
class Evaluate extends Base{
    /**
     * 评价列表
     */
    public function evaluateList(){
        $tel_no = input('TEL_NO');
        $nick_name = input('NICK_NAME');
        $condition = [];
        if(!empty($tel_no)){
            $condition['b.TEL_NO'] = array('like','%'.$tel_no.'%');
            $this->assign('tel_no',$tel_no);
        }
        if(!empty($nick_name)){
            $condition['b.NICK_NAME'] = array('like','%'.$nick_name.'%');
            $this->assign('nick_name',$nick_name);
        }
        $evaluate_model = new Evaluate_tab();
        $data = $evaluate_model->evaluateList($condition);
        //评价展示状态
        $setting_model = new Setting_tab();
        $settingInfo = $setting_model->getSettingInfo('evaluate');
        if(!empty($settingInfo)){
            $this->assign('settingInfo',$settingInfo);
        }
        $page = $data->render();
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }
    /**
     * 设置评价显示状态
     */
    public function evaluateSetFlg(){
        $evaluate_id = input('evaluate_id');
        if(!empty($evaluate_id)){
            $evaluate_model = new Evaluate_tab();
            // $result = $order_model->where(['ORDER_CD'=>$order_cd])->delete();
            $evaluteInfo = $evaluate_model->getEvaluateInfoById($evaluate_id);
            $flg = $evaluteInfo['AVAILABLE_FLG']?'0':1;
            $result =  $evaluate_model->updateEvaluateInfoById(['AVAILABLE_FLG'=>$flg],$evaluate_id);
            if($result){
                $this->redirect(url('admin/evaluate/evaluateList'));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/evaluate/evaluateList'));
        }
    }
    /**
     * 删除评价信息
     */
    public function delEvaluate(){
        $evaluate_id = input('evaluate_id');
        if(!empty($evaluate_id)){
            $evaluate_model = new Evaluate_tab();
            $result =  $evaluate_model->where(['EVALUATE_ID'=>$evaluate_id])->delete();
            if($result){
                $this->redirect(url('admin/evaluate/evaluateList'));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/evaluate/evaluateList'));
        }
    }
    /**
     * 回复用户评价
     */
    public function reply(){
        if(Request::instance()->isPost()){
            $evaluate_id = input('evaluate_id');
            $content = input('content');
            if($evaluate_id && $content){
                $evaluate_model = new Evaluate_tab();
                $dataEvaluate = [
                    'STORE_REPLY'=> $content,
                ];
                $result = $evaluate_model->updateEvaluateInfoById($dataEvaluate,$evaluate_id);
                if($result){
                    $data['state'] = 'success';
                    $data['msg'] = '回复成功！';
                }else{
                    $data['state'] = 'fail';
                    $data['msg'] = '回复失败！';
                }
            }else{
                $data['state'] = 'fail';
                $data['msg'] = '参数不完整！';
            }
            echo json_encode($data);die();
        }
    }
    //评价详情
    public function evaluateInfo(){
        $evaluate_id = input('evaluate_id');
        $evaluation_model = new Evaluate_tab();
        $evaluateInfo = $evaluation_model->getEvaluateInfoById($evaluate_id);
        $this->assign('evaluateInfo',$evaluateInfo);
        return $this->fetch();
    }
    //设置评论（关闭/开启）
    public function setEvaluateShow(){
        $setting_model = new Setting_tab();
        //评价现展示状态
        $settingInfo = $setting_model->getSettingInfo('evaluate');
        $flg = $settingInfo['VALUE']?0:1;
        $resultUp = $setting_model->updateSetting($flg,'evaluate');
        if(!empty($resultUp)){
           $data['state'] = 'success';
           $data['msg'] = '设置成功';
        }else{
            $data['state'] = 'fail';
            $data['msg'] = '设置失败';
        }
        echo json_encode($data);die();
    }
}