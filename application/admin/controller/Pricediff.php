<?php
/**
 * 差价管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5 0005
 * Time: 14:37
 */
namespace app\admin\controller;


use app\admin\model\Price_differences_tab;

class Pricediff extends Base{
    /**
     * 差价单列表
     */
    public function index(){
        $pricediff_model = new Price_differences_tab();

        $condition = array();
        $order_cd = input('order_cd');
        //根据订单号查询
        if(!empty($order_cd)){
            $condition['a.ORDER_CD'] = $order_cd;
            $this->assign('order_cd',$order_cd);
        }
        $list = $pricediff_model->getPriceDiffList($condition);
        $page = $list->render();
        //差价单数量
        $priceDiffNum = $pricediff_model->getPriceDiffNum($condition);
        $this->assign('priceDiffNum',$priceDiffNum);
        $this->assign('page',$page);
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 删除差价单
     */
    public function pricediffDel(){
        $diff_price_no = input('diff_price_no');
        if(!empty($diff_price_no)){
            $pricediff_model = new Price_differences_tab();
            $result = $pricediff_model->where(['DIFF_PRICE_NO'=>$diff_price_no])->delete();
            if($result){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！');
            }
        }else{
            $this->error('未传入有效参数！');
        }
    }
    /**
     * ajax设置差价单是否可用
     */
    public function setState(){
        $diff_price_no = input('diff_price_no');
        $diff_state = input('diff_state');
        if(empty($diff_price_no)){
            $data['state'] = 'error';
            $data['msg'] = '未选择需要设置的差价单';
            return $data;
        }
        $available_flg = $diff_state?0:1;
        $pricediff_model = new Price_differences_tab();
        $result =  $pricediff_model->priceDiffUpdate(array('AVAILABLE_FLG'=>$available_flg),$diff_price_no);
        if(!empty($result)){
            $data['state'] = 'success';
            $data['msg'] = '设置成功';
            $data['available_flg'] = $diff_state;
            $data['info'] = $diff_state?'不可用':'可用';
        }else{
            $data['state'] = 'error';
            $data['msg'] = '设置失败';
        }
        return $data;
    }
    /**
     * 差价单详情
     */
    public function priceInfo(){
        $diff_price_no = input('diff_price_no');
        if(empty($diff_price_no)){
            $this->error('未找到差价单');
        }
        $pricediff_model = new Price_differences_tab();
        $pricediff_info = $pricediff_model->getPriceDiffInfoById($diff_price_no);
        if(!empty($pricediff_info)){
            $this->assign('pricediff_info',$pricediff_info);
            return $this->fetch();
        }else{
            $this->error('无此差价单信息！');
        }


    }


}