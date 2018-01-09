<?php
/**
 * 公共处理类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 11:05
 */
namespace app\admin\controller;

use think\controller;
use think\Request;

class Publics extends Base{
    /**
     * 上传图片处理
     */
    public function uploadImage(){
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
}