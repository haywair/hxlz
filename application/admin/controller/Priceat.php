<?php
/**
 * 价格优惠方案
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 16:57
 */
namespace app\admin\controller;

use app\admin\model\Project_tab;
use app\admin\model\Store_tab;
use app\admin\model\Project_plan_price_tab;
use app\admin\model\Price_plan_category_tab;
use think\Request;

class Priceat extends Base{
    /**
     * 价格优惠方案列表
     */
    public function priceatList(){
        $priceat_model = new Project_plan_price_tab();
        $list = $priceat_model->getPriceatList();
        //查询优惠门店
        $store_name ='';
        $store_ids = [];
        foreach($list as $k=>$v){
            $projectIds = explode(',',$v['PROJECT_CD']);
            $storeNames = $this->getStoreInfo($projectIds);
            $list[$k]['STORE_NAME'] = $storeNames?$storeNames:'';
        }
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     * 添加价格优惠方案
     */
    public function priceatAdd(){
        $priceat_model = new Project_plan_price_tab();
        if(Request::instance()->isPost()){
            $data = input();
            if(!empty($data['project_ids'])){
                $data['PROJECT_CD'] = implode(',',$data['project_ids']);
                unset($data['project_ids']);
            }else{
                unset($data['project_ids']);
                $this->error('未选择优惠项目！');
            }
            //计算价格优惠率reckonPriceRate($salePrice,$project_cd)
            $data['CREATE_TIME'] = date('Y-m-d H:i:s',time());
            $data['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
            $data['OPERATE_ID'] = session('ADMIN_ID');
            $result = $priceat_model->priceatAdd($data);
            if(!empty($result)){
                $this->success('添加价格优惠方案成功！',url('admin/priceat/priceatList'));
            }else{
                $this->error('添加价格优惠方案失败！');
            }

        }else{
            $category_model = new Price_plan_category_tab();
            $project_model = new Project_tab();
            //获取优惠列表
            $condition = [];
            $condition['AVAILABLE_FLG'] = 1;
            //最高级优惠
            $condition['level'] = 1;
            $categorys_fir = $category_model->getCategoryListCon($condition);
            //次级优惠
            $condition['level'] = 2;
            $categorys_sec = $category_model->getCategoryListCon($condition);
            //第三级优惠
            $condition['level'] = 3;
            $categorys_third = $category_model->getCategoryListCon($condition);
            //第四级优惠
            $condition['level'] = 4;
            $categorys_four = $category_model->getCategoryListCon($condition);
            //项目列表
            $project_list = $project_model->ProjectListAll();
            foreach($project_list as $key=>$val){
                $sql = "select count(*) as num from PROJECT_PLAN_PRICE_TAB where charindex('".$val['PROJECT_ID']."',
                PROJECT_CD)>0 ";
                $result_num = $priceat_model->query($sql);
                if($result_num[0]['num'] > 0){
                    unset($project_list[$key]);
                }
            }
            $this->assign('categorys_fir',$categorys_fir);
            $this->assign('categorys_sec',$categorys_sec);
            $this->assign('categorys_third',$categorys_third);
            $this->assign('categorys_four',$categorys_four);
            $this->assign('project_list',$project_list);
            return $this->fetch();
        }
    }
    /**
     * 编辑价格优惠方案
     */
    public function priceatEdit(){
        $priceat_model = new Project_plan_price_tab();
        if(Request::instance()->isPost()){
            $data = input();
            if(!empty($data['project_ids'])){
                 $data['PROJECT_CD'] = implode(',',$data['project_ids']);
                 unset($data['project_ids']);
            }else{
                 unset($data['project_ids']);
                $this->error('未选择优惠项目！');
            }
            //计算价格优惠率reckonPriceRate($salePrice,$project_cd)

            $data['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
            $data['OPERATE_ID'] = session('ADMIN_ID');
            $plan_cd = $data['PLAN_CD'];
            unset($data['PLAN_CD']);
            $result = $priceat_model->priceatUpdate($data,$plan_cd);
            if(!empty($result)){
                $this->success('修改价格优惠方案成功！',url('admin/priceat/priceatList'));
            }else{
                $this->error('修改价格优惠方案失败！');
            }

        }else{
            $plan_cd = input('PLAN_CD');
            $category_model = new Price_plan_category_tab();
            $project_model = new Project_tab();
            //获取优惠列表
            $condition = [];
            $condition['AVAILABLE_FLG'] = 1;
            //最高级优惠
            $condition['level'] = 1;
            $categorys_fir = $category_model->getCategoryListCon($condition);
            //次级优惠
            $condition['level'] = 2;
            $categorys_sec = $category_model->getCategoryListCon($condition);
            //第三级优惠
            $condition['level'] = 3;
            $categorys_third = $category_model->getCategoryListCon($condition);
            //第四级优惠
            $condition['level'] = 4;
            $categorys_four = $category_model->getCategoryListCon($condition);
            //项目列表
            $project_list = $project_model->ProjectListAll();
            $priceatInfo = $priceat_model->getPriceatInfo($plan_cd);
            $project_cds = explode(',',$priceatInfo['PROJECT_CD']);
           foreach($project_list as $key=>$val){
               if(!in_array($val['PROJECT_ID'],$project_cds)) {
                   $sql = "select count(*) as num from PROJECT_PLAN_PRICE_TAB where charindex('" . $val['PROJECT_ID'] . "',PROJECT_CD)>0 ";
                   $result_num = $priceat_model->query($sql);
                   if ($result_num[0]['num'] > 0) {
                       unset($project_list[$key]);
                   }
               }
            }
            $this->assign('project_cds',$project_cds);
            $this->assign('priceatInfo',$priceatInfo);
            $this->assign('categorys_fir',$categorys_fir);
            $this->assign('categorys_sec',$categorys_sec);
            $this->assign('categorys_third',$categorys_third);
            $this->assign('categorys_four',$categorys_four);
            $this->assign('project_list',$project_list);
            return $this->fetch();
        }
    }
    /**
     * 价格优惠类型列表
     */
    public function priceatCategoryList(){
        $cl_name = input('CL_NAME');
        $category_model = new Price_plan_category_tab();
        $condition = [];
        if(!empty($cl_name)){
            $condition['CL_NAME'] = array('LIKE','%'.$cl_name.'%');
            $this->assign('cl_name',$cl_name);
        }
        $list = $category_model->getCategoryList($condition);
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     * 添加价格优惠类型
     */
    public function priceatCategoryAdd(){
        if(Request::instance()->isPost()){
            $category_model = new Price_plan_category_tab();
            $data = input();
            $dataAd = [];
            switch($data['level']){
                case '1':
                    //最高优先级
                    $dataAd['LEVEL_TYPE'] = $data['level_one_type'];
                    $dataAd['LEVEL_RULE'] = $data['level_one_rule'];
                    //$dataAd['PRICE_AT'] = $data['level_one_at'];
                    break;
                case '2':
                    //次优先级
                    if($data['level_two_type'] == 1){
                        $dataAd['LEVEL_RULE'] = $data['level_two_rule'];
                    }else{
                        $dataAd['LEVEL_RULE'] = $data['week_day'].' '.$data['clock_time'];
                    }
                    $dataAd['LEVEL_TYPE'] = $data['level_two_type'];
                   // $dataAd['PRICE_AT'] = $data['level_two_at'];
                    break;
                case '3':
                    $dataAd['LEVEL_TYPE'] = 1;
                    $dataAd['LEVEL_RULE'] = $data['level_three_rule'];
                   // $dataAd['PRICE_AT'] = $data['level_three_at'];
                    break;
                case '4':
                    if($data['level_four_type'] == 1){
                        $dataAd['LEVEL_RULE'] = $data['level_four_project'];
                    }else{
                        $dataAd['LEVEL_RULE'] = $data['level_four_room'];
                    }
                    $dataAd['LEVEL_TYPE'] = $data['level_four_type'];
                   // $dataAd['PRICE_AT'] = $data['level_four_at'];
                    break;
            }
            $dataAd['LEVEL'] = $data['level'];
            $dataAd['CL_NAME'] = $data['cl_name'];
            $dataAd['CREATE_TIME'] = date('Y-m-d H:i:s',time());
            $dataAd['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
            $dataAd['OPERATE_ID'] = session('ADMIN_ID');
            $result = $category_model->priceCategoryAdd($dataAd);
            if(!empty($result)){
                $this->success('添加成功',url('admin/priceat/priceatCategoryList'));
            }else{
                $this->error('添加失败！');
            }
        }else{
            return $this->fetch();
        }
    }
    /**
     * 修改价格优惠类型
     */
    public function priceatCategoryEdit(){
        $category_model = new Price_plan_category_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $cl_id = '';
            if(!empty($data['cl_id'])){
                $cl_id = $data['cl_id'];
            }else{
                $this->error('请选择您要修改的优惠种类！');
            }
            $dataEd = [];
            switch($data['level']){
                case '1':
                    //最高优先级
                    $dataEd['LEVEL_TYPE'] = $data['level_one_type'];
                    $dataEd['LEVEL_RULE'] = $data['level_one_rule'];
                    //$dataAd['PRICE_AT'] = $data['level_one_at'];
                    break;
                case '2':
                    //次优先级
                    if($data['level_two_type'] == 1){
                        $dataEd['LEVEL_RULE'] = $data['level_two_rule'];
                    }else{
                        $dataEd['LEVEL_RULE'] = $data['week_day'].' '.$data['clock_time'];
                    }
                    $dataEd['LEVEL_TYPE'] = $data['level_two_type'];
                    // $dataAd['PRICE_AT'] = $data['level_two_at'];
                    break;
                case '3':
                    $dataEd['LEVEL_TYPE'] = 1;
                    $dataEd['LEVEL_RULE'] = $data['level_three_rule'];
                    // $dataAd['PRICE_AT'] = $data['level_three_at'];
                    break;
                case '4':
                    if($data['level_four_type'] == 1){
                        $dataEd['LEVEL_RULE'] = $data['level_four_project'];
                    }else{
                        $dataEd['LEVEL_RULE'] = $data['level_four_room'];
                    }
                    $dataEd['LEVEL_TYPE'] = $data['level_four_type'];
                    // $dataAd['PRICE_AT'] = $data['level_four_at'];
                    break;
            }
            $dataEd['LEVEL'] = $data['level'];
            $dataEd['CL_NAME'] = $data['cl_name'];
            $dataEd['UPDATE_TIME'] = date('Y-m-d H:i:s',time());
            $dataEd['OPERATE_ID'] = session('ADMIN_ID');
            $result = $category_model-> priceatCategoryUpdate($dataEd,$cl_id);
            if(!empty($result)){
                $this->success('修改成功',url('admin/priceat/priceatCategoryList'));
            }else{
                $this->error('修改失败！');
            }
        }else{
            $cl_id = input('CL_ID');
            $categoryInfo = $category_model->getPriceatCategoryInfo($cl_id);
            $this->assign('categoryInfo',$categoryInfo);
            return $this->fetch();
        }
    }
    /**
     * ajax设置优惠类别是否可用
     */
    public function setCategoryFlg(){
        $cl_id = input('cl_id');
        $available_flg = input('available_flg');
        if(empty($cl_id)){
            $data['state'] = 'error';
            $data['msg'] = '未选择需要设置的优惠类别';
            return $data;
        }
        $available_flg = $available_flg?0:1;
        $category_model = new Price_plan_category_tab();
        $result =  $category_model->priceatCategoryUpdate(array('AVAILABLE_FLG'=>$available_flg),$cl_id);
        if(!empty($result)){
            $data['state'] = 'success';
            $data['msg'] = '设置成功';
            $data['available_flg'] = $available_flg;
            $data['info'] = $available_flg?'可用':'不可用';
        }else{
            $data['state'] = 'error';
            $data['msg'] = '设置失败';
        }
        return $data;
    }
    /**
     * 删除优惠类别
     */
    public function priceatCategoryDel(){
        $cl_id = input('cl_id');
        if(!empty($cl_id)){
            $category_model = new Price_plan_category_tab();
            $result = $category_model->where(['CL_ID'=>$cl_id])->delete();
            if($result){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/priceat/priceatCategoryList'));
        }
    }
    /**
     * 设置优惠方案状态setPriceatFlg
     */
    public function setPriceatFlg(){
        $plan_cd = input('plan_cd');
        $available_flg = input('available_flg');
        if(empty($plan_cd)){
            $data['state'] = 'error';
            $data['msg'] = '未选择需要设置的优惠方案';
            return $data;
        }
        $available_flg = $available_flg?0:1;
        $priceat_model = new Project_plan_price_tab();
        $result = $priceat_model->priceatUpdate(array('AVAILABLE_FLG'=>$available_flg),$plan_cd);
        if(!empty($result)){
            $data['state'] = 'success';
            $data['msg'] = '设置成功';
            $data['available_flg'] = $available_flg;
            $data['info'] = $available_flg?'可用':'不可用';
        }else{
            $data['state'] = 'error';
            $data['msg'] = '设置失败';
        }
        return $data;
    }
    /**
     * 删除优惠方案
     */
    public function priceatDel(){
        $plan_cd = input('plan_cd');
        if(!empty($plan_cd)){
            $priceat_model = new Project_plan_price_tab();
            $result = $priceat_model->where(['PLAN_CD'=>$plan_cd])->delete();
            if($result){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/priceat/priceatList'));
        }
    }
    /**
     * 计算价格优惠率
     */
    public function reckonPriceRate($salePrice,$project_cd){
        $project_model = new Project_tab();
        $proInfo = $project_model->getProjectOneByID($project_cd);
        if($proInfo['PRICE'] >= $salePrice){
            $rate = round(($salePrice/$proInfo['PRICE']),2);
        }else{
            $rate = 0;
        }
        return $rate;
    }
    /**
     * 查询优惠门店信息
     */
    protected function getStoreInfo($projectIds){
        if(count($projectIds) <= 0){
            return ;
        }
        $project_model = new Project_tab();
        $store_model = new Store_tab();
        $store_name = [];
        $store_ids = [];
        foreach($projectIds as $v){
            $projectInfo = $project_model->getProjectInfoByPID($v);
            if($projectInfo) {
                $storeInfo = $store_model->getStoreOne($projectInfo['STORE_CD']);
                if($storeInfo && !in_array($storeInfo['STORE_CD'],$store_ids)){
                    $store_ids[] = $storeInfo['STORE_CD'];
                    $store_name[] = $storeInfo['STORE_NAME'];
                }
            }else{
                return ;
            }
        }
        $storeNames = implode(',',$store_name);
        return $storeNames?$storeNames:'';

    }

}