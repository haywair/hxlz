<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26 0026
 * Time: 9:17
 */
namespace app\admin\controller;

use app\admin\model\Finance_issue_tab;
use app\admin\model\Order_tab;
use app\admin\model\Store_tab;
use app\admin\model\Project_tab;
use app\admin\model\Finance_tab;
use app\admin\model\Finance_type_tab;
use think\Validate;
use think\Request;

class Finance extends Base{
    /**
     * 票券列表
     */
    public function financeList(){

        $condition = [];
        $project_cd = input('project_cd');
       // $condition['a.AVAILABLE_FLG'] = 1;
        //项目编号
        if(!empty($project_cd)){
            $condition['a.PROJECT_CD'] = $project_cd;
            $this->assign('project_cd',$project_cd);
        }
        $finance_model = new Finance_tab();
        $data = $finance_model->getFinanceList($condition);
        $page = $data->render();
        //票券类型
        $type_model = new Finance_type_tab();
        $typeList = $type_model->getFinanceTypeFlg();
        $this->assign('typeList',$typeList);

        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     * 新增票券
     */
    public function financeAdd(){
        //票券类型
        $type_model = new Finance_type_tab();
        $typeList = $type_model->getFinanceTypeFlg();
        //项目分组
        $project_model = new Project_tab();
        $projectList = $project_model->getProjectListGroupPCD();

        foreach($projectList as $k=>$v){
            $projectCD[] = $v['PROJECT_CD'];
            $condition = [
                'PROJECT_CD'=>$v['PROJECT_CD']
            ];
            $proInfo = $project_model->ProjectListCon($condition);
            $projectList[$k]['PROJECT_NAME'] = $proInfo[0]['PROJECT_NAME'];

        }
        //票券编号
        $finance_no = 'HOU'.time().rand(1000,9999);
        $this->assign('projectList',$projectList);
        $this->assign('typeList',$typeList);
        $this->assign('finance_no',$finance_no);
        return $this->fetch();
    }
    /**
     * 票券增加post
     */
    public function financeAddPost(){
        if(Request::instance()->isPost()){
            $data = input();
            $finance_model = new Finance_tab();
            //验证
            $rule = [
                'FINANCE_NAME' => 'require',
                'START_DATE' => 'require',
                'END_DATE' => 'require',
                'FINANCE_NUM' => 'number',
                'TIMES_NUM' => 'number',
                'SELL_PRICE' => 'number'
            ];
            if(!empty($data['thumb'])) {
                $data['FINANCE_IMAGE'] = implode(',', $data['thumb']);
                unset($data['thumb']);
            }
            $this->validateInput($rule,$data);

            $data['CREATE_USER'] = session('ADMIN_ID');
            $data['CREATE_DATE'] = time();
            $data['UPDATE_USER'] = session('ADMIN_ID');
            $data['UPDATE_DATE'] = time();
            $data['REMAIN_NUM'] = $data['FINANCE_NUM'];
            $result = $finance_model-> financeAdd($data);
            if( $result){
                $this->redirect(url('admin/finance/financeList'));
            }else{
                $this->error("添加失败");
            }

        }

    }
    /**
     * 修改票券
     */
    public function financeEdit(){
        $finance_id = input('finance_id');
        if(empty($finance_id)){
            $this->error('请选择您要编辑的票券！');
        }
        //票券类型
        $type_model = new Finance_type_tab();
        $typeList = $type_model->getFinanceTypeFlg();
        //项目分组
        $project_model = new Project_tab();
        $projectList = $project_model->getProjectListGroupPCD();

        foreach($projectList as $k=>$v){
            $projectCD[] = $v['PROJECT_CD'];
            $condition = [
                'PROJECT_CD'=>$v['PROJECT_CD']
            ];
            $proInfo = $project_model->ProjectListCon($condition);
            $projectList[$k]['PROJECT_NAME'] = $proInfo[0]['PROJECT_NAME'];

        }
        //票券信息
        $finance_model = new Finance_tab();
        $financeInfo = $finance_model->getFinanceInfoById($finance_id);
        if(!empty($financeInfo['FINANCE_IMAGE'])){
            $imgArr = explode(',',$financeInfo['FINANCE_IMAGE']);
            $this->assign('imgArr',$imgArr);
        }
        $this->assign('financeInfo',$financeInfo);
        $this->assign('projectList',$projectList);
        $this->assign('typeList',$typeList);
        return $this->fetch();
    }
    /**
     * 修改票券post
     */
    public function financeEditPost(){
        if(Request::instance()->isPost()){
            $data = input();
            //验证
            $rule = [
                'FINANCE_NAME' => 'require',
                'START_DATE' => 'require',
                'END_DATE' => 'require',
                'FINANCE_NUM' => 'number',
                'TIMES_NUM' => 'number',
                'SELL_PRICE' => 'number'
            ];
            $this->validateInput($rule,$data);
            //票券信息
            $finance_model = new Finance_tab();
            $financeInfo = $finance_model->getFinanceInfoById($data['FINANCE_ID']);
            //票券图片
            if(!empty( $financeInfo['FINANCE_IMAGE']) && !empty($data['thumb'])) {
                $data['FINANCE_IMAGE'] = $financeInfo['FINANCE_IMAGE'] . ',' . implode(',', $data['thumb']);
                unset($data['thumb']);
            }else if(!empty($data['thumb'])){
                $data['FINANCE_IMAGE'] = implode(',', $data['thumb']);
                unset($data['thumb']);
            }else{
                unset($data['thumb']);
            }
            $finance_id = $data['FINANCE_ID'];
            unset($data['FINANCE_ID']);
            $data['REMAIN_NUM'] = $data['FINANCE_NUM'];
            $data['UPDATE_USER'] = session('ADMIN_ID');
            $data['UPDATE_DATE'] = time();
            $finance_model = new Finance_tab();
            $result = $finance_model->updateFinanceInfoById($data,$finance_id);
            if( $result){
                $this->redirect(url('admin/finance/financeList'));
            }else{
                $this->error("修改失败");
            }

        }
    }
    /**
     * 删除图片
     */
    public function imageDel(){
        if(Request::instance()->isPost()){
            $finance_id = input('finance_id');
            $imgInfo = input('imgInfo');
            if($finance_id && $imgInfo){
                $finance_model = new Finance_tab();
                $financeInfo = $finance_model->getFinanceInfoById($finance_id);
                $imgArr = explode(',',$financeInfo['FINANCE_IMAGE']);
                if(in_array($imgInfo,$imgArr)){
                    $key = array_search($imgInfo, $imgArr);
                    unset($imgArr[$key]);
                    unlink(ROOT_PATH.'/'.$imgInfo);
                    $imgStr = implode(',',$imgArr);
                    $result = $finance_model->updateFinanceInfoById(array('FINANCE_IMAGE'=>$imgStr),$finance_id);
                    if(!empty($result)){
                        $data['state'] = 'success';
                        $data['msg'] = '删除成功';
                    }else{
                        $data['state'] = 'error';
                        $data['msg'] = '删除失败！';
                    }
                }else{
                    $data['state'] = 'error';
                    $data['msg'] = '此图片不在该票券图片列表中！';
                }
            }else{
                $data['state'] = 'error';
                $data['msg'] = '信息不完整！';
            }
            echo json_encode($data);
        }
    }
    /**
     * 设置票券可用状态
     */
    public function financeSetFlg(){
        $finance_id = input('finance_id');
        if(!empty($finance_id)){
            $finance_model = new Finance_tab();
            $financeInfo = $finance_model->getFinanceInfoById($finance_id);
            $flg = $financeInfo['AVAILABLE_FLG']?0:1;
            $result = $finance_model->updateFinanceInfoById(['AVAILABLE_FLG'=>$flg],$finance_id);
            if($result){
                $this->redirect(url('admin/finance/financeList'));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/finance/financeList'));
        }
    }
    /**
     *删除票券
     */
    public function delFinance(){
        $finance_id = input('finance_id');
        if(!empty($finance_id)){
            $finance_model = new Finance_tab();
            $result = $finance_model->where(['FINANCE_ID'=>$finance_id])->delete();
            if($result){
                $this->redirect(url('admin/finance/financeList'));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/finance/financeList'));
        }
    }
    /**
     * 票券类型列表
     */
    public function financeTypeList(){
        $type_model = new Finance_type_tab();
        $data = $type_model->getFinanceTypeList();
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     *添加票券类型
     */
    public function financeTypeAdd(){
         return $this->fetch();
    }
    /**
     * 添加票券类型处理
     */
    public function financeTypeAddPost(){
        if(Request::instance()->isPost()){
            $data = input();
            if($data['TYPE_NAME']){
                $data1 = [
                    "TYPE_DESCRIPTION"=>$data['TYPE_DESCRIPTION'],
                    "TYPE_NAME"=>$data['TYPE_NAME'],
                    'CREATE_USER'=>session('ADMIN_ID'),
                    'CREATE_DATE'=>time()
                ];
                $type_model = new Finance_type_tab();
                $res = $type_model->financeTypeAdd($data1);
                if($res){
                    $this->redirect(url('admin/finance/financeTypeList'));
                }else{
                    $this->error("添加失败");
                }
            }else{
                $this->error('不可以有空值！');
            }

        }
    }
    /**
     *修改票券类型
     */
    public function financeTypeEdit(){
        $type_model = new Finance_type_tab();
        $id = input('typeId');
        $typeInfo = $type_model->getTypeInfoById($id);
        $this->assign('typeInfo',$typeInfo);
        return $this->fetch();
    }
    /**
     * 添加票券类型处理
     */
    public function financeTypeEditPost()
    {
        if (Request::instance()->isPost()) {
            $type_model = new Finance_type_tab();
            $data = input();
            if ($data['TYPE_NAME']) {
                $data1 = [
                    "TYPE_DESCRIPTION" => $data['TYPE_DESCRIPTION'],
                    "TYPE_NAME" => $data['TYPE_NAME'],
                    'UPDATE_USER' => session('ADMIN_ID'),
                    'UPDATE_DATE' => time()
                ];
                $res = $type_model->updateTypeInfoById($data1, $data['TYPE_ID']);
                if ($res) {
                    $this->redirect(url('admin/finance/financeTypeList'));
                } else {
                    $this->error("修改失败");
                }
            } else {
                $this->error('不可以有空值！');
            }

        }
    }
    /**
     * 删除票券类型
     */
    public function delFinanceType(){
        $typeId = input('typeId');
        if(!empty($typeId)){
            $type_model = new Finance_type_tab();
            $result = $type_model->where('TYPE_ID',$typeId)->delete();
            if($result){
                $this->redirect(url('admin/finance/financeTypeList'));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * 验证表单
     */
    private function validateInput($rule,$data){
        $validate = new Validate($rule);
        if (!$validate->check($data)) {
            dump($validate->getError());
        }
    }
    /**
     * 发行券列表
     */
    public function financeIssueList(){
        $where = array();
        $nick_name = input('nick_name');
        $finance_issue_no = input('finance_issue_no');
        $tel_no = input('tel_no');
        $finance_status = input('finance_status');
        if(!empty($finance_issue_no)){
            $where['a.FINANCE_ISSUE_NO'] = array('LIKE','%'.$finance_issue_no.'%');
            $this->assign('finance_issue_no',$finance_issue_no);
        }
        if(!empty($nick_name)){
            $where['d.NICK_NAME'] = array('LIKE','%'.$nick_name.'%');
            $this->assign('nick_name',$nick_name);
        }
        if(!empty($tel_no)){
            $where['d.TEL_NO'] = array('LIKE','%'.$tel_no.'%');
            $this->assign('tel_no',$tel_no);
        }
        if(!empty($finance_status)){
            $this->assign('finance_status',$finance_status);
            switch($finance_status){
                case '1':
                    $where['FINANCE_STATUS'] = 0;
                    break;
                case '2':
                    $where['FINANCE_STATUS'] = 1;
                    break;
                case '3':
                    $where['a.AVAILABLE_FLG'] = 1;
                    break;
                case '4':
                    $where['a.AVAILABLE_FLG'] = 0;
                    break;
            }
        }
        $financeIssue_model = new Finance_issue_tab();
        $data = $financeIssue_model->getFinanceIssueList($where);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     * 设置发行券可用状态
     */
    public function financeIssueSetFlg(){
        $finance_issue_id = input('finance_issue_id');
        if(!empty($finance_issue_id)){
            $financeIssue_model = new Finance_issue_tab();
            $issueInfo = $financeIssue_model->getFinanceIssueInfoById($finance_issue_id);
            $flg = $issueInfo['AVAILABLE_FLG']?'0':1;
            $result = $financeIssue_model->updateFinanceIssueInfoById(array('AVAILABLE_FLG'=>$flg),$finance_issue_id);
            if($result){
                $this->redirect(url('admin/finance/financeIssueList'));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/finance/financeIssueList'));
        }
    }
    /**
     * 删除发行券
     */
    public function delFinanceIssue(){
        $finance_issue_id = input('finance_issue_id');
        if(!empty($finance_issue_id)){
            $financeIssue_model = new Finance_issue_tab();
            $result = $financeIssue_model->where(['FINANCE_ISSUE_ID'=>$finance_issue_id])->delete();
            if($result){
                $this->redirect(url('admin/finance/financeIssueList'));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/finance/financeIssueList'));
        }
    }


}