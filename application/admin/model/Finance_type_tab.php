<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26 0026
 * Time: 14:28
 */
namespace app\admin\model;

class Finance_type_tab extends Base{
    public function financeTypeAdd($data){
        return $this->data($data)->save();
    }
    public function getFinanceTypeFlg(){
        return $this->where(['AVAILABLE_FLG'=>1])->select();
    }
    public function getTypeInfoById($id){
        return $this->where(['TYPE_ID'=>$id])->find();
    }
    public function updateTypeInfoById($data,$id){
        return $this->where(['TYPE_ID'=>$id])->update($data);
    }
    public function getFinanceTypeList(){
        return $this->paginate(10,false);
    }
}