<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 13:47
 */

namespace app\admin\controller;



use app\admin\model\Project_category_tab;
use app\admin\model\Project_tab;
use app\admin\model\Project_unit_tab;
use app\admin\model\Room_tab;
use app\wx\model\Project_type_tab;
use app\admin\model\Order_tab;
use think\Request;

class Project extends Base
{
    /**
     * 项目列表
     * */
    public function projectList(){
        $sid = input('sid');
        $project = new Project_tab();
        $data = $project->ProjectList($sid);
        $page = $data->render();
        $unit_model = new Project_unit_tab();
        $category_model = new Project_category_tab();
        $type_model = new Project_type_tab();
        $typeList = $type_model->getProjectTypeFlg();
        $categoryList = $category_model->getProjectCateFlg();
        $unitList = $unit_model->getProjectUnitFlg();
        $this->assign('typeList',$typeList);
        $this->assign('categoryList',$categoryList);
        $this->assign('unitList',$unitList);
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('sid',$sid);
        return $this->fetch();
    }
    /**
     * 添加项目
     * */
    public function projectAdd(){
        if(Request::instance()->isPost()){
            $data = input();
            if(!empty($data['thumb'])) {
                $data['PROJECT_IMAGE'] = implode(',', $data['thumb']);
                unset($data['thumb']);
            }
            if(!empty($data['roomCD'])){
                $data['ROOM_CD'] = implode(',',$data['roomCD']);
                unset($data['roomCD']);
            }
            $project = new Project_tab();
            $res = $project->ProjectAdd($data);
            if($res){
                $this->success("项目添加成功");
            }else{
                $this->error("项目添加失败");
            }
        }else{
            $sid = input('sid');
            $cate = new Project_category_tab();
            $cateData = $cate->getProjectCateFlg();
            $unit = new Project_unit_tab();
            $unitData = $unit->getProjectUnitFlg();
            $type = new Project_type_tab();
            $typeData = $type->getProjectTypeFlg();
            $room = new Room_tab();
            $roomData = $room->roomList1($sid);
            $this->assign('sid',$sid);
            $this->assign('cateData',$cateData);
            $this->assign('unitData',$unitData);
            $this->assign('typeData',$typeData);
            $this->assign('roomData',$roomData);
            return $this->fetch();
        }
    }
    /**
     * 项目修改
     */
    public function projectEdit(){
        $project = new Project_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $project = new Project_tab();
            $proInfo = $project->getStoreProjectOne($data['PROJECT_CD'],$data['STORE_CD']);
            //门店图片
            if(!empty($proInfo['PROJECT_IMAGE']) && !empty($data['thumb'])) {
                $data['PROJECT_IMAGE'] = $proInfo['PROJECT_IMAGE'] . ',' . implode(',', $data['thumb']);
                unset($data['thumb']);
            }else if(!empty($data['thumb'])){
                $data['PROJECT_IMAGE'] = implode(',', $data['thumb']);
                unset($data['thumb']);
            }else{
                unset($data['thumb']);
            }
            //项目房间
            if(!empty($data['roomCD'])){
                $data['ROOM_CD'] = implode(',',$data['roomCD']);
                unset($data['roomCD']);
            }
			$data['PRICE'] = round(floatval($data['PRICE']),2);
            $order_model = new Order_tab();
            $pid = $data['PROJECT_ID'];
            unset($data['PROJECT_ID']);
            $res = $project->updateProjectByPId($data,$pid);
            $resultOrder = $order_model->updateOrderByCon(['PROJECT_NAME'=>$data['PROJECT_NAME']],['PROJECT_CD'=>$data['PROJECT_CD'],'STORE_CD'=>$data['STORE_CD']]);
            if($res){
                $this->success("项目修改成功");
            }else{
                $this->error("项目修改失败");
            }
        }else{
            $project_cd = input('project_cd');
            $sid = input('sid');
            $pro_info = $project->getStoreProjectOne($project_cd,$sid);
            $cate = new Project_category_tab();
            $cateData = $cate->getProjectCateFlg();
            $unit = new Project_unit_tab();
            $unitData = $unit->getProjectUnitFlg();
            $type = new Project_type_tab();
            $typeData = $type->getProjectTypeFlg();
            $room = new Room_tab();
            $roomData = $room->roomList1($sid);
            $pro_rooms = explode(',',$pro_info['ROOM_CD']);
            if(!empty($pro_info['PROJECT_IMAGE'])){
                $imgArr = explode(',',$pro_info['PROJECT_IMAGE']);
                $this->assign('imgArr',$imgArr);
            }
			if(!empty($pro_info['PRICE'])){
				$pro_info['PRICE'] = round($pro_info['PRICE'],2);
			}
			if(!empty($pro_info['ADD_PROJ_PRICE'])){		
				$pro_info['ADD_PROJ_PRICE'] = round($pro_info['ADD_PROJ_PRICE'],2);
			}	
            $this->assign('pro_rooms',$pro_rooms);
            $this->assign('sid',$sid);
            $this->assign('cateData',$cateData);
            $this->assign('unitData',$unitData);
            $this->assign('typeData',$typeData);
            $this->assign('roomData',$roomData);
            $this->assign('pro_info',$pro_info);
            return $this->fetch();
        }
    }
    /**
     * 项目单位列表
     * */
    public function projectUnitList(){
        $project = new Project_unit_tab();
        $data = $project->getProjectUnitList();
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     * t添加项目单位
     * */
    public function projectUnitAdd(){
        header('Cache-Control:no-cache,must-revalidate');
        header('Pragma:no-cache');
        header("Expires:0");
        if(session('projectUnitAdd')){
            session('projectUnitAdd',null);
            $this->redirect(url('admin/project/projectUnitList'));
        }
        if(Request::instance()->isPost()){
            $data = input();
            if($data['PROJECT_UNIT_CD']&&$data['PROJECT_UNIT_NAME']){
                $project = new Project_unit_tab();
                $res = $project->projectUnitAdd($data);
                if($res){
                    session('projectUnitAdd',1);
                    $this->success('添加成功');
                }else{
                    $this->error("添加失败");
                }
            }else{
                $this->error("不可以有空值！");
            }
        }else{
            return $this->fetch();
        }
    }
    /**
     * 修改项目单位
     * */
    public function projectUnitEdit(){
        $project = new Project_unit_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $uid = input('PROJECT_UNIT_CD');
            if($data['PROJECT_UNIT_CD']&&$data['PROJECT_UNIT_NAME']){
                $data['UPDATE_USER'] = session('user_id');
                $data['UPDATE_DATE'] = date('Y-m-d H:i:s',time());
                unset($data['PROJECT_UNIT_CD']);
                $res = $project->updateUnitInfoByUID($data,$uid);
                if($res){
                    $this->success('修改成功');
                }else{
                    $this->error("修改失败");
                }
            }else{
                $this->error("不可以有空值！");
            }
        }else{
            $uid = input('uid');
            $unitInfo = $project->getUnitInfoByUID($uid);
            $this->assign('unitInfo',$unitInfo);
            return $this->fetch();
        }
    }
    /**
     * 删除项目单位
     */
    public function delProjectUnit(){
        $uid = input('uid');
        if(!empty($uid)){
            $unit_model = new Project_unit_tab();
            /* $proInfo = $pro_model-> getStoreProjectOne($pro_cd,$sid);
             $flg = $proInfo['AVAILABLE_FLG']?'0':1;*/
            $result = $unit_model->where('PROJECT_UNIT_CD',$uid)->delete();
            if($result){
                $this->redirect(url('admin/project/projectUnitList'));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * 项目类别列表
     * */
    public function projectCategoryList(){
        $project = new Project_category_tab();
        $data = $project->getProjectCateList();
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }
    public function projectCategoryAdd(){
        if(Request::instance()->isPost()){
            $data = input();
            if($data['CATEGORY_CD']&&$data['CATEGORY_NAME']){
                $project = new Project_category_tab();
                $res = $project->projectCateAdd($data);
                if($res){
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('不可以有空值！');
            }
        }else{
            return $this->fetch();
        }
    }

    /**
     * 项目类别修改
     */
    public function projectCategoryEdit(){
        $project = new Project_category_tab();
        if(Request::instance()->isPost()){
            $cid = input('CATEGORY_CD');
            $data = input();
            if($data['CATEGORY_CD']&&$data['CATEGORY_NAME']){
                $data['UPDATE_USER'] = session('user_id');
                $data['UPDATE_DATE'] = date('Y-m-d H:i:s',time());
                unset($data['CATETORY_CD']);
                $res = $project->updateCategoryByCID($data,$cid);
                if($res){
                    $this->success('修改成功');
                }else{
                    $this->error('修改失败');
                }
            }else{
                $this->error('不可以有空值！');
            }
        }else{
            $cid = input('cid');
            $categoryInfo = $project->getCategoryByCID($cid);
            $this->assign('categoryInfo',$categoryInfo);
            return $this->fetch();
        }
    }
    /**
     * 删除项目类别
     */
    public function delProjectCategory(){
        $cid = input('cid');
        if(!empty($cid)){
            $cagegory_model = new Project_category_tab();
            /* $proInfo = $pro_model-> getStoreProjectOne($pro_cd,$sid);
             $flg = $proInfo['AVAILABLE_FLG']?'0':1;*/
            $result = $cagegory_model->where('CATEGORY_CD',$cid)->delete();
            if($result){
                $this->redirect(url('admin/project/projectCategoryList'));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * 项目类型列表
     * */
    public function projectTypeList(){
        $project = new Project_type_tab();
        $data = $project->getProjectTypeList();
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }
    public function projectTypeAdd(){
        if(Request::instance()->isPost()){
            $data = input();
            if($data['TYPE_NAME']&&$data['TYPE_CD']){
                $data1 = [
                    "TYPE_CD"=>$data['TYPE_CD'],
                    "TYPE_NAME"=>$data['TYPE_NAME'],
                    "TYPE_DESCRIPTION"=>$data['TYPE_DESCRIPTION']
                ];
                $project = new Project_type_tab();
                $res = $project->pTypeAdd($data1);
                if($res){
                    $this->success("添加成功");
                }else{
                    $this->error("添加失败");
                }
            }else{
                $this->error('不可以有空值！');
            }

        }else{
            return $this->fetch();
        }
    }

    /**
     * 修改项目类型
     */
    public function projectTypeEdit(){
        $project = new Project_type_tab();
        if(Request::instance()->isPost()){
            $data = input();
            if($data['TYPE_NAME']&&$data['TYPE_CD']){
                $data1 = [
                    "TYPE_CD"=>$data['TYPE_CD'],
                    "TYPE_NAME"=>$data['TYPE_NAME'],
                    "TYPE_DESCRIPTION"=>$data['TYPE_DESCRIPTION'],
                    'UPDATE_USER' => session('user_id'),
                    'UPDATE_DATE' => date('Y-m-d H:i:s',time())
                ];

                $res = $project->updateTypeInfoById($data1,$data['TYPE_ID']);
                if($res){
                    $this->success("修改成功");
                }else{
                    $this->error("修改失败");
                }
            }else{
                $this->error('不可以有空值！');
            }

        }else{
            $id = input('typeId');
            $typeInfo = $project->getTypeInfoById($id);
            $this->assign('typeInfo',$typeInfo);
            return $this->fetch();
        }
    }
    /**
     * 删除图片
     */
    public function imageDel(){
        if(Request::instance()->isPost()){
            $pro_cd = input('pro_cd');
            $imgInfo = input('imgInfo');
            if($pro_cd && $imgInfo){
                $pro_model = new Project_tab();
                $proInfo = $pro_model->getProjectOneByID($pro_cd);
                $imgArr = explode(',',$proInfo['PROJECT_IMAGE']);
                if(in_array($imgInfo,$imgArr)){
                    $key = array_search($imgInfo, $imgArr);
                    unset($imgArr[$key]);
                    unlink(ROOT_PATH.'/'.$imgInfo);
                    $imgStr = implode(',',$imgArr);
                    $result = $pro_model->updateProjectById(array('PROJECT_IMAGE'=>$imgStr),$pro_cd);
                    if(!empty($result)){
                        $data['state'] = 'success';
                        $data['msg'] = '删除成功';
                    }else{
                        $data['state'] = 'error';
                        $data['msg'] = '删除失败！';
                    }
                }else{
                    $data['state'] = 'error';
                    $data['msg'] = '此图片不在该项目图片列表中！';
                }
            }else{
                $data['state'] = 'error';
                $data['msg'] = '信息不完整！';
            }
            echo json_encode($data);
        }
    }
    /**
     * 设置项目状态
     */
    public function projectSetFlg(){
        $pro_cd  = input('pro_cd');
        $sid = input('sid');
        if(!empty($pro_cd)){
            $pro_model = new Project_tab();
            $proInfo = $pro_model-> getStoreProjectOne($pro_cd,$sid);
            $flg = $proInfo['AVAILABLE_FLG']?'0':1;
            $result = $pro_model->updateProjectByPId(array('AVAILABLE_FLG'=>$flg), $proInfo['PROJECT_ID']);
            if($result){
                $this->redirect(url('admin/project/projectList',['sid'=>$sid]));
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * 删除项目
     */
    public function delProject(){
        $pro_cd  = input('pro_cd');
        $sid = input('sid');
        if(!empty($pro_cd)){
            $pro_model = new Project_tab();
            $result = $pro_model->where(['STORE_CD'=>$sid,'PROJECT_CD'=>$pro_cd])->delete();
            if(!empty($result)){
                $this->redirect(url('admin/project/projectList',['sid'=>$sid]));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * 删除项目类型
     */
    public function delProjectType(){
        $typeId = input('typeId');
        if(!empty($typeId)){
            $type_model = new Project_type_tab();
           /* $proInfo = $pro_model-> getStoreProjectOne($pro_cd,$sid);
            $flg = $proInfo['AVAILABLE_FLG']?'0':1;*/
            $result = $type_model->where('TYPE_ID',$typeId)->delete();
            if($result){
                $this->redirect(url('admin/project/projectTypeList'));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * 查询项目编号是否已经存在
     */
    public function isSetProjectCd(){
        $pro_model = new Project_tab();
        $post = input();
        if(empty($post['projectCd']) || empty($post['sid'])){
            $data['state'] = 100;
            $data['msg'] = '传入参数不完整！';
            echo json_encode($data);die();
        }
        $post['projectCd'] = ltrim($post['projectCd']);
        $condition = [
            'PROJECT_CD'=>$post['projectCd'],
            'STORE_CD'=>$post['sid']
        ];
        $num = $pro_model->getProjectNum($condition);
        if($num > 0 ){
            $data['state'] = 101;
            $data['msg'] = '该项目编号已经存在！';
        }else{
            $data['state'] = 'success';
            $data['msg'] = 'success';
        }
        echo json_encode($data);die();
    }
}