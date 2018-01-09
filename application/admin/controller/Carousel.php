<?php
/**
 * 轮播图
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5 0005
 * Time: 8:47
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Carousel_figure_tab;
use think\Request;

class Carousel extends Base{
    /**
     * 轮播图列表
     */
    public function index(){
        $carousel_model = new Carousel_figure_tab();
        $condition = array();
        $carousel_title = input('carousel_title');
        if(!empty($carousel_title)){
            $condition['CAROUSEL_TITLE'] = $carousel_title;
            $this->assign('carousel_title',$carousel_title);
        }
        $list = $carousel_model->getCarouselList($condition);
        $page = $list->render();
        $carouselPicNum = $carousel_model->getCarouselPicNum($condition);
        $this->assign('carouselPicNum',$carouselPicNum);
        $this->assign('list',$list);
        $this->assign('page',$page);
        return $this->fetch();
    }
    /**
     * 增加轮播图
     */
    public function add(){
        if(Request::instance()->isPost()){
            $data = input();
            $carousel_model = new Carousel_figure_tab();
            $file = request()->file('CAROUSEL_PIC');
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            $data['CREATETIME'] = date('Y-m-d H:i:s',time());
            $data['UPDATETIME'] = date('Y-m-d H:i:s',time());
            $data['OPERATE_ID'] = session('ADMIN_ID');
            $data['STATE'] = $data['STATE']?$data['STATE']:0;
            $data['CAROUSEL_PIC'] = $info->getSaveName();
            $res = $carousel_model->carouselPicAdd($data);
            if($res){
                $this->success('添加轮播图成功');
            }else{
                $this->error("添加轮播图失败");
            }
        }else{
            return $this->fetch();
        }
    }
    /**
     * 修改轮播图
     */
    public function edit(){
        $carousel_model = new Carousel_figure_tab();
        if(Request::instance()->isPost()){
            $data = input();
            $data['UPDATETIME'] = date('Y-m-d H:i:s',time());
            $data['OPERATE_ID'] = session('ADMIN_ID');
            $data['STATE'] = $data['STATE']?$data['STATE']:0;
            //图片上传
            $file = request()->file('CAROUSEL_PIC');
            if($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                $data['CAROUSEL_PIC'] = $info->getSaveName();
            }
            $carousel_id = $data['CAROUSEL_ID'];
            unset($data['CAROUSEL_ID']);
            $res = $carousel_model->carouselPicUpdateById($data,$carousel_id);
            if($res){
                $this->success('修改轮播图成功',url('admin/carousel/index'));
            }else{
                $this->error("修改轮播图失败");
            }
        }else{
            $carousel_id = input('carousel_id');
            if(empty($carousel_id)){
                $this->error('请选择要修改的轮播图！');
            }
            $info = $carousel_model->getCarouselPicById($carousel_id);
            if(empty($info)){
                $this->error('无此轮播图信息！');
            }
            $this->assign('info',$info);
            return $this->fetch();
        }

    }
    /**
     * 删除轮播图
     */
    public function carouselDel(){
        $carousel_id = input('carousel_id');
        if(!empty($carousel_id)){
            $carousel_model = new Carousel_figure_tab();
            $result = $carousel_model->where(['CAROUSEL_ID'=>$carousel_id])->delete();
            if($result){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！',url('admin/carousel/index'));
        }
    }
}