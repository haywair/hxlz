<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/27 0027
 * Time: 16:53
 */
namespace app\admin\model;

class Finance_issue_tab extends Base{
    public function financeIssueAdd($data){
        return $this->data($data)->save();
    }
    public function getFinanceIssueFlg($condition=[]){
        $field = 'a.*,b.TYPE_NAME,c.FINANCE_NAME,c.SELL_PRICE,c.START_DATE,c.END_DATE';
        $join = [
            ['FINANCE_TYPE_TAB b','a.FINANCE_TYPE = b.TYPE_ID'],
            ['FINANCE_TAB c','a.FINANCE_ID = c.FINANCE_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->select();
    }
    public function getFinanceIssueInfoById($id){
        $field = 'a.*,b.TYPE_NAME,c.FINANCE_NAME,c.SELL_PRICE,c.START_DATE,c.END_DATE,c.FINANCE_INFO';
        $join = [
            ['FINANCE_TYPE_TAB b','a.FINANCE_TYPE = b.TYPE_ID'],
            ['FINANCE_TAB c','a.FINANCE_ID = c.FINANCE_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where(['FINANCE_ISSUE_ID'=>$id])->find();
    }
    public function updateFinanceIssueInfoById($data,$id){
        return $this->where(['FINANCE_ISSUE_ID'=>$id])->update($data);
    }
    public function getFinanceIssueList($condition=[],$order=""){
        $field = 'a.*,b.TYPE_NAME,c.START_DATE,c.END_DATE,c.FINANCE_NAME,c.SELL_PRICE,d.NICK_NAME,d.USER_NAME,d.TEL_NO';
        if(empty($order)){
            $order = 'FINANCE_ISSUE_ID desc';
        }
        $join = [
            ['FINANCE_TYPE_TAB b','a.FINANCE_TYPE = b.TYPE_ID'],
            ['FINANCE_TAB c','a.FINANCE_ID = c.FINANCE_ID'],
            ['USER_TAB d','a.USER_ID = d.USER_ID']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->order($order)->paginate(10,false,['query' =>request() ->param()]);
    }
    public function getFinanceIssueNum($condition=[]){
        return $this->where($condition)->count();
    }
}