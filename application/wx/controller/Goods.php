<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 9:46
 */

namespace app\wx\controller;


use app\admin\model\Project_tab;
use app\admin\model\Store_tab;
use app\admin\model\Evaluate_tab;
use app\wx\model\User_tab;

class Goods extends Base
{
    /**
     * 项目详情
     * */
    public function goodsDetailed(){
        $user = new User_tab();
        $userid = $user->openidGetUserOne(session("openid"));
        if($userid['TEL_NO']){
            $pid = input('pid');
			$sid = input('sid');
			if(!empty($sid)){
				$this->assign('sid',$sid);
			}
            $project = new Project_tab();
            $data = $project->getProjectOne($pid);
            foreach ($data as $value){
                $value['PRICE'] = round($value['PRICE'],2);
                $img[] = explode(',',$value['PROJECT_IMAGE']);
            }
            $sid = "";
            foreach ($data as $v){
                $sid .= $v['STORE_CD'].",";
            }
            $ssid = explode(',',$sid);
            $store = new Store_tab();
            $sdata = $store->getStore($ssid);
            $flag=[];
            foreach ($sdata as $val){
                $val['juli'] = getDistance(session("latitude"),session("longitude"),$val["LATITUDE"],$val['LONGITUDE']);
                $flag[]=$val["juli"];
				if(mb_strlen($val['ADDRESS'],'utf8') > 15){
					$val['ADDRESS'] = mb_substr($val['ADDRESS'],0,15,'utf8').'...';
				}
            }
            array_multisort($flag, SORT_ASC, $sdata);
            //项目评价
            $evaluate_model = new Evaluate_tab();
            $condition = [
                'a.PROJECT_ID'=> $data[0]['PROJECT_ID'],
                'a.PARENT_EVALUATE_ID' => 0,
                'a.AVAILABLE_FLG' => 1
            ];
            $evaluateAll = $evaluate_model->getEvaluateFlg($condition,25);
            $this->assign('img',$img);
            $this->assign('pid',$pid);
            $this->assign('data',$data);
            $this->assign('sdata',$sdata);
            $this->assign('evaluateAll',$evaluateAll);
            return $this->fetch();
        }else{
            $this->redirect(WEB_URL.'/wx/home/binding');
        }
    }
}