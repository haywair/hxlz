<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26 0026
 * Time: 14:25
 */
namespace app\admin\model;

class Finance_tab extends Base{
    public function financeAdd($data){
        return $this->data($data)->save();
    }
    public function getFinanceFlg($condition=[]){
        $condition['AVAILABLE_FLG'] = 1;
        return $this->where($condition)->select();
    }
    public function getFinanceInfoById($id){
        $field = 'a.*,b.TYPE_NAME';
        $join = [
            ['FINANCE_TYPE_TAB b','a.FINANCE_TYPE = b.TYPE_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where(['a.FINANCE_ID'=>$id])->find();
    }
    public function updateFinanceInfoById($data,$id){
        return $this->where(['FINANCE_ID'=>$id])->update($data);
    }
    public function getFinanceList($condition=[]){
        $field = 'a.*,b.TYPE_NAME';
        $join = [
            ['FINANCE_TYPE_TAB b','a.FINANCE_TYPE = b.TYPE_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->paginate(10,false,['query' =>request() ->param()]);
    }

}