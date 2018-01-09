<?php
/**
 * 评价表model
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/2 0002
 * Time: 11:51
 */
namespace app\admin\model;

class Evaluate_tab extends Base{
    public function evaluateAdd($data){
        return $this->data($data)->save();
    }
    public function getEvaluateFlg($condition=[],$limit=""){
        $field = 'a.*,b.USER_NAME,b.TEL_NO,b.NICK_NAME,b.PHOTO_HEAD,c.PROJECT_NAME,c.PROJECT_INFO';
        $join = [
            ['USER_TAB b','a.USER_ID = b.USER_ID'],
            ['PROJECT_TAB c','c.PROJECT_ID = a.PROJECT_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->order('EVALUATE_ID desc')->select();
    }

    public function evaluateList($condition=[],$limit=""){
        $field = 'EVALUATE_ID,a.ORDER_CD,a.CREATE_DATE,a.REMARK,a.AVAILABLE_FLG,b.NICK_NAME,b.TEL_NO,c.PROJECT_NAME,d.STORE_NAME';
        return  $this->alias('a')
                        ->field($field)
                        ->join('USER_TAB b','a.USER_ID = b.USER_ID','INNER')
                        ->join('PROJECT_TAB c','a.PROJECT_ID = c.PROJECT_ID','INNER')
                        ->join('STORE_TAB d','a.STORE_CD = d.STORE_CD','INNER')
                        ->where($condition)->order('CREATE_DATE asc')->paginate(10,false,['query' =>request() ->param()]);

    }

    public function getEvaluateInfoById($id){
        $field = 'a.*,b.USER_NAME,b.TEL_NO,b.NICK_NAME,b.PHOTO_HEAD,c.PROJECT_NAME,c.PROJECT_INFO,d.STORE_NAME';
        $join = [
            ['USER_TAB b','a.USER_ID = b.USER_ID'],
            ['PROJECT_TAB c','c.PROJECT_ID = a.PROJECT_ID'],
            ['STORE_TAB d','d.STORE_CD = a.STORE_CD']
        ];
        return $this->alias('a')->field($field)->join($join)->where(['EVALUATE_ID'=>$id])->find();
    }
    public function updateEvaluateInfoById($data,$id){
        return $this->where(['EVALUATE_ID'=>$id])->update($data);
    }
    public function getEvaluateList($condition=[]){
        $field = 'a.*,b.USER_NAME,b.TEL_NO,b.NICK_NAME,b.PHOTO_HEAD,c.PROJECT_NAME,c.PROJECT_INFO';
        $join = [
            ['USER_TAB b','a.USER_ID = b.USER_ID'],
            ['PROJECT_TAB c','c.PROJECT_ID = a.PROJECT_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->paginate(10,false,['query' =>request() ->param()]);
    }
    public function getEvaluateNum($condition=[]){
        return $this->where($condition)->count();
    }
}