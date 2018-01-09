<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 11:15
 */

namespace app\admin\controller;


use app\admin\model\Store_tab;
use app\admin\model\Room_tab;
use app\admin\model\Time_set_rule_tab;
use app\admin\model\Project_tab;
use app\admin\model\Room_order_time_tab;
use app\admin\model\Order_tab;
use think\Request;

class Store extends Base
{
    /**门店列表
     *
     * */
    public function storeList(){
        header('Cache-Control:no-cache,must-revalidate');
        header('Pragma:no-cache');
        header("Expires:0");
        $store = new Store_tab();
        $store_name = input('STORE_NAME');
        $condition = [];
        if(!empty($store_name)){
            $condition['STORE_NAME'] = array('LIKE','%'.$store_name.'%');
            $this->assign('store_name',$store_name);
        }
        $data = $store->storeList($condition);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     * 添加门店
     * */
    public function  storeAdd(){
        if(Request::instance()->isPost()){
            $data = input();
            $store = new Store_tab();
            /*$file = request()->file('cccc');
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            $data['STORE_IMAGE'] = str_replace('\\','/',$info->getSaveName());*/
            if(!empty($data['thumb'])) {
                $data['STORE_IMAGE'] = implode(',', $data['thumb']);
                unset($data['thumb']);
            }
            $res = $store->storeAdd($data);
            if($res){
                $this->success('添加门店成功');
            }else{
                $this->error("添加门店失败");
            }
        }else{
            return $this->fetch();
        }

    }
    /**
     * 修改门店
     */
    public function storeEdit(){
        $store_model = new Store_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $storeInfo = $store_model->getStoreOne($data['STORE_CD']);
            //门店图片
            if(!empty($storeInfo['STORE_IMAGE']) && !empty($data['thumb'])) {
                $data['STORE_IMAGE'] = $storeInfo['STORE_IMAGE'] . ',' . implode(',', $data['thumb']);
                unset($data['thumb']);
            }else if(!empty($data['thumb'])){
                $data['STORE_IMAGE'] = implode(',', $data['thumb']);
                unset($data['thumb']);
            }else{
                unset($data['thumb']);
            }
            $store_cd = $data['STORE_CD'];
            $rtime_model = new Room_order_time_tab();
            $time_model = new Time_set_rule_tab();
            $order_model = new Order_tab();
			$data['LATITUDE'] = floatval($data['LATITUDE']);
			$data['LONGITUDE'] = floatval($data['LONGITUDE']);
             //更新门店信息
            $res = $store_model->updateStoreByBySid($data,$store_cd);
            if($data['STORE_NAME']) {
                $resOrder = $order_model->updateOrderByCon(['STORE_NAME' => $data['STORE_NAME']],['STORE_CD'=>$store_cd]);
                $resRoomTime = $rtime_model->updateRoomNoteByCon(['STORE_NAME'=>$data['STORE_NAME']],['STORE_CD'=>$store_cd]);
                $resTime = $time_model->updateTimeInfo(['STORE_NAME'=>$data['STORE_NAME']],['STORE_CD'=>$store_cd]);
            }
            if($res){
                $this->success('修改门店信息成功');
            }else{
                $this->error("修改门店信息失败");
            }
        }else{
            $store_cd = input('store_cd');
            $storeInfo = $store_model->getStoreOne($store_cd);
            if(!empty($storeInfo['STORE_IMAGE'])){
                $imgArr = explode(',',$storeInfo['STORE_IMAGE']);
                $this->assign('imgArr',$imgArr);
            }
            $storeType = $store_model->getStoreType();
            $this->assign('storeType',$storeType);
            $this->assign('storeInfo',$storeInfo);
            return $this->fetch();
        }

    }
    /**
     * 上传图片
     */
    public function ajaxupload(){
        $typeArr = array("jpg", "png", "gif");//允许上传文件格式
        $path = ROOT_PATH . 'public/uploads/'.date('Ymd').'/';//上传路径
        if(!is_dir($path)){
            dir(ROOT_PATH . 'public' . DS . 'uploads');
            mkdir(ROOT_PATH . 'public' . DS . 'uploads/'.date('Ymd'));
        }
        if (isset($_POST)) {
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $name_tmp = $_FILES['file']['tmp_name'];
            if (empty($name)) {
                echo json_encode(array("error"=>"您还未选择图片"));
                exit;
            }
            $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型

            if (!in_array($type, $typeArr)) {
                echo json_encode(array("error"=>"清上传jpg,png或gif类型的图片！"));
                exit;
            }
            if ($size > (500 * 1024)) {
                echo json_encode(array("error"=>"图片大小已超过500KB！"));
                exit;
            }

            $pic_name = time() . rand(10000, 99999) . "." . $type;//图片名称
            $pic_url = $path . $pic_name;//上传后图片路径+名称
            $return_pic = 'public/uploads/'.date('Ymd').'/'.$pic_name;
            if (move_uploaded_file($name_tmp, $pic_url)) { //临时文件转移到目标文件夹
                echo json_encode(array("error"=>"0","pic"=>$return_pic,"name"=>$pic_name));
            } else {
                echo json_encode(array("error"=>"上传有误，清检查服务器配置！"));
            }
        }

    }

    /**
     * 删除图片
     */
    public function imageDel(){
        if(Request::instance()->isPost()){
            $store_cd = input('store_cd');
            $imgInfo = input('imgInfo');
            if($store_cd && $imgInfo){
                $store_model = new Store_tab();
                $storeInfo = $store_model->getStoreOne($store_cd);
                $imgArr = explode(',',$storeInfo['STORE_IMAGE']);
                if(in_array($imgInfo,$imgArr)){
                    $key = array_search($imgInfo, $imgArr);
                    unset($imgArr[$key]);
                    unlink(ROOT_PATH.'/'.$imgInfo);
                    $imgStr = implode(',',$imgArr);
                    $result = $store_model->updateStoreByBySid(array('STORE_IMAGE'=>$imgStr),$store_cd);
                    if(!empty($result)){
                        $data['state'] = 'success';
                        $data['msg'] = '删除成功';
                    }else{
                        $data['state'] = 'error';
                        $data['msg'] = '删除失败！';
                    }
                }else{
                    $data['state'] = 'error';
                    $data['msg'] = '此图片不在该店铺图片列表中！';
                }
            }else{
                $data['state'] = 'error';
                $data['msg'] = '信息不完整！';
            }
            echo json_encode($data);
        }
    }
    /**
     * 设置店铺状态
     */
    public function storeSetFlg(){
        $store_cd  = input('store_cd');
        if(!empty($store_cd)){
            $store_model = new Store_tab();
            $storeInfo = $store_model->getStoreOne($store_cd);
            $flg = $storeInfo['AVAILABLE_FLG']?0:1;
            $result = $store_model->updateStoreBySid(array('AVAILABLE_FLG'=>$flg),$storeInfo['STORE_ID']);
//$storeInfo = $store_model->getStoreOne($store_cd);
			//echo $storeInfo['AVAILABLE_FLG'];die();
            if($result){
               $this->redirect(url('admin/store/storeList'));
				//$this->success('_'.$storeInfo['AVAILABLE_FLG'].'_');
            }else{
                $this->error('设置失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * 删除门店
     */
    public function delStore(){
        $store_cd  = input('store_cd');
        if(!empty($store_cd)){
            $store_model = new Store_tab();
            $room_model = new Room_tab();
            $pro_model= new Project_tab();
            $condition = [];
            $condition['STORE_CD'] = $store_cd;
            $proNum = $pro_model->getProjectNum($condition);
            $roomNum = $room_model->getRoomNum($condition);
            if($proNum > 0 || $roomNum > 0){
                $this->error('该门店还设置有房间和项目，暂不可删除！');
            }
            $result = $store_model->where(['STORE_CD'=>$store_cd])->delete();
            if($result){
                $this->redirect(url('admin/store/storeList'));
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
}