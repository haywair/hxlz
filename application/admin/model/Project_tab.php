<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 14:27
 */

namespace app\admin\model;


class Project_tab extends Base
{
    public function ProjectAdd($data){
        return $this->data($data)->save();
    }
    public function ProjectList($sid){
        return $this->where(['STORE_CD'=>$sid])->paginate(15,false);
    }
    public function ProjectListAll(){
		$field = 'a.*,CAST(a.PRICE AS FLOAT) as pPRICE,b.STORE_NAME';
        $join = [
          ['STORE_TAB b','a.STORE_CD = b.STORE_CD','LEFT']
        ];
        return $this->alias('a')->field($field)->join($join)->where(['a.AVAILABLE_FLG'=>1])->order('LIST_ORDER asc')->select();
    }
    public function ProjectListCon($condition,$limit=""){
        $field = '*,CAST(PRICE AS FLOAT) as pPRICE';
        return $this->field($field)->where($condition)->order('LIST_ORDER ASC')->limit($limit)->select();
    }
    public function ProjectListLiliao(){
		$field = '*,CAST(PRICE AS FLOAT) as pPRICE';
        return $this->field($field)->where(['TYPE_CD'=>1,'AVAILABLE_FLG'=>1])->order('LIST_ORDER ASC')->select();
    }
    public function ProjectListJiaju(){
		$field = '*,CAST(PRICE AS FLOAT) as pPRICE';
        return $this->field($field)->where(['TYPE_CD'=>2,'AVAILABLE_FLG'=>1])->order('LIST_ORDER ASC')->select();
    }
    public function ProjectListSPA(){
		$field = '*,CAST(PRICE AS FLOAT) as pPRICE';
        return $this->field($field)->where(['TYPE_CD'=>3,'AVAILABLE_FLG'=>1])->order('LIST_ORDER ASC')->select();
    }
    public function getProjectOne($pid){
		$field = '*,CAST(PRICE AS FLOAT) as pPRICE';
        return $this->field($field)->where(['PROJECT_CD'=>$pid])->select();
    }
    public function getProjectOneByID($pid){
        return $this->where(['PROJECT_CD'=>$pid])->find();
    }
    public function getProjectInfoByPID($projectId){
        return $this->where(['PROJECT_ID'=>$projectId])->find();
    }
    /**
     * 获取店铺下所有的可用服务项目
     * @param $sid
     */
    public function getProjectStoreAll($sid){
		$field = '*,CAST(PRICE AS FLOAT) as pPRICE';
        return $this->field($field)->where(['STORE_CD'=>$sid,'AVAILABLE_FLG'=>1])->order('LIST_ORDER ASC')->select();
    }
    public function getStoreProjectOne($pid,$sid){
		$field = '*,CAST(PRICE AS FLOAT) as pPRICE';
        return $this->field($field)->where(['PROJECT_CD'=>$pid,"STORE_CD"=>$sid])->find();
    }
    /**
     * 更新项目信息
     */
    public function updateProjectById($data,$pid){
        return $this->where(['PROJECT_CD'=>$pid])->update($data);
    }
    public function updateProjectByPId($data,$pid){
        return $this->where(['PROJECT_ID'=>$pid])->update($data);
    }
    public function getProjectNum($condition){
        return $this->where($condition)->count();
    }
    /**
     * 按照PROJECT_CD分组查询项目列表
     */
    public function getProjectListGroupPCD($condition=[]){
        $condition['AVAILABLE_FLG'] = 1;
        return $this->field('PROJECT_CD')->where($condition)->group('PROJECT_CD')->select();

    }
    /**
     * 关联查询projectList
     */
    public function ProjectListAllJoin($condition=[]){
        $field = 'a.*,b.STORE_NAME';
        $join = [
            ['STORE_TAB b','a.STORE_CD = b.STORE_CD','LEFT']
        ];
        return $this->alias('a')->field($field)->join($join)->where($condition)->order('LIST_ORDER asc')->select();
    }
}