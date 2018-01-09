<?php
/**
 * Created by PhpStorm.
 * 商城
 * User: Administrator
 * Date: 2017/8/1 0001
 * Time: 10:36
 */
namespace app\wx\controller;

use app\admin\model\Finance_issue_tab;
use app\admin\model\Finance_tab;
use app\admin\model\Project_tab;
use app\admin\model\Store_tab;
use app\admin\model\Finance_order_tab;
use app\admin\model\Evaluate_tab;
use app\wx\model\User_tab;
use think\Request;

class Mall extends Base{
    //商城首页
    public function productList(){
        return $this->fetch();
    }
    /**
     * 商品详情
     */
    public function productInfo(){
        if(strlen(session('openid'))>3) {
            //是否绑定
            $user = new User_tab();
            $userid = $user->openidGetUserOne(session('openid'));
            $unbind = $userid['TEL_NO']?0:1;

            $financeId = input('financeId');
            $finance_model = new Finance_tab();
            $pro_model = new Project_tab();
            $store_model = new Store_tab();
            $financeInfo = $finance_model->getFinanceInfoById($financeId);
            $projectList = $pro_model->ProjectListCon(['PROJECT_CD' => $financeInfo['PROJECT_CD']]);
            $storeIds = "";
            foreach ($projectList as $k => $v) {
                $projectList[$k]['PRICE'] = round($v['PRICE'], 2);
               // $projectList[$k]['IMAGES'] = explode(',', $v['PROJECT_IMAGE']);
                $storeIds = $v['STORE_CD'] . ',';
            }
            if ($projectList) {
                //店铺信息
                $storeList = $store_model->getStore($storeIds);
                $flag = [];
                foreach ($storeList as $val) {
                    $val['juli'] = getDistance(session("latitude"), session("longitude"), $val["LATITUDE"], $val['LONGITUDE']);
                    $flag[] = $val["juli"];
                }
                array_multisort($flag, SORT_ASC, $storeList);
                //项目评价
                $evaluate_model = new Evaluate_tab();
                $condition = [
                    'a.PROJECT_ID' => $projectList[0]['PROJECT_ID'],
                    'a.PARENT_EVALUATE_ID' => 0,
                    'a.AVAILABLE_FLG' => 1
                ];
                $evaluateAll = $evaluate_model->getEvaluateFlg($condition, 25);
                //票券图片
                $img = explode(',',$financeInfo['FINANCE_IMAGE']);
                $this->assign('sdata', $storeList);
                $this->assign('img', $img);
                $this->assign('data', $projectList[0]);
                $this->assign('evaluateAll', $evaluateAll);
            } else {
                $evaluateAll = [];
            }
            $this->assign('unbind',$unbind);
            $this->assign('financeInfo', $financeInfo);
            return $this->fetch();

        }else{
            $this->redirect(WEB_URL.'/wx/index/OAuth');
        }
    }
    /**
     * 商品购买
     */
    public function productBuy(){
        $financeId = input('financeId');
        $store_cd = input('store_cd');
        $finance_model = new Finance_tab();
        $financeInfo = $finance_model->getFinanceInfoById($financeId);
        $this->assign('financeInfo',$financeInfo);
        $this->assign('financeId',$financeId);
        $this->assign('store_cd',$store_cd);
        return $this->fetch();
    }
    /**
     * ajax查询商品列表
     */
    public function getFinanceList(){
        if(Request::instance()->isPost()){
            $fType = input('type');
            if(!empty($fType)){
                $financeIs_model = new Finance_tab();
                $condition = [];
                $condition['FINANCE_TYPE'] = $fType;
                //票券有效期
                if($fType == 2){
                    $condition['START_DATE'] = array('elt',date('Y-m-d H:i:s',time()));
                    $condition['END_DATE'] = array('egt',date('Y-m-d H:i:s',time()));
                }
                $financeList = $financeIs_model-> getFinanceFlg($condition);
                foreach($financeList as $k=>$v){
                    $pro_model = new Project_tab();
                    if($v['PROJECT_CD']){
                        $limit = 1;
                        $project = $pro_model->ProjectListCon(['PROJECT_CD'=>$v['PROJECT_CD']],$limit);
                        $imagArr = explode(',',$project[0]['PROJECT_IMAGE']);
                        $projectDescript = htmlspecialchars_decode($project[0]['PROJECT_INFO']);
                        $financeList[$k]['PROJECT_TIME'] = $project[0]['PROJECT_TIME'];
                        $financeList[$k]['PROJECT_INFO'] = $projectDescript;
                        $financeList[$k]['PROJECT_IMAGE'] = '/'. $imagArr[0];
                    }
                    //票券图片
                    $imageArr = explode(',',$v['FINANCE_IMAGE']);
                    $financeList[$k]['FINANCE_IMAGE'] = $imageArr[0];
                }
                if(!$financeList){
                    $data['state'] = '102';
                    $data['msg'] = '暂无相关商品信息';
                }else{
                    $data['state'] = 'success';
                    $data['info'] = $financeList;
                    $data['msg'] = '获取成功';
                }
            }else{
                $data['state'] = '101';
                $data['msg'] = '未上传有效票券类型信息';
            }
        }else{
            $data['state'] = '100';
            $data['msg'] = '异常请求！';
        }
        echo json_encode($data);die();
    }
    /**
     * 生成票券订单
     */
    public function createFinanceOneOrder(){
       if(Request::instance()->isPost()){
            $post = input();
            $returnData = [];
            //票券信息
            $finance_model = new Finance_tab();
            $financeInfo = $finance_model->getFinanceInfoById($post['finance_id']);
            //项目信息
            $project_model = new Project_tab();
            $projectInfo = $project_model->getStoreProjectOne($financeInfo['PROJECT_CD'],$post['store_cd']);
            //更新票券信息
            $dataFinance = [
                'REMAIN_NUM' => $financeInfo['REMAIN_NUM'] -$post['goods_num'],
                'SALE_NUM' => $financeInfo['SALE_NUM']+$post['goods_num'],
                'UPDATE_USER' => session('user_id'),
                'UPDATE_DATE' => time()
            ];
            $resFinance = $finance_model->updateFinanceInfoById($dataFinance,$post['finance_id']);
            if(!$resFinance){
                $returnData['state'] = '101';
                $returnData['msg'] = '更新票券信息失败！';
                echo json_encode($returnData);die();
            }
            $finance_order_cd = 'FN'.time().rand(1000,9999);
            $data = [
                'FINANCE_ORDER_CD' => $finance_order_cd,
                'FINANCE_NO' => $financeInfo['FINANCE_NO'],
                'FINANCE_ID' => $post['finance_id'],
                'FINANCE_TYPE' => $financeInfo['FINANCE_TYPE'],
                'FINANCE_PRICE' => $financeInfo['SELL_PRICE'],
                'PROJECT_ID' => $projectInfo['PROJECT_ID'],
                'STORE_CD' => $post['store_cd'],
                'PROJECT_TYPE' => $projectInfo['TYPE_CD'],
                'PROJECT_PRICE' => $projectInfo['PRICE'],
                'START_DATE' => $financeInfo['START_DATE'],
                'END_DATE' => $financeInfo['END_DATE'],
                'TIMES_NUM' => $financeInfo['TIMES_NUM'],
                'USER_ID' => session('user_id'),
                'FINANCE_NUM' => $post['goods_num'],
                'PAY_TYPE' => 1,
                'TOTAL_AMT' => $post['totalAmt'],
                'ORDER_STATUS' => 0,
               /* 'FINANCE_ISSUE_ID' => $finance_issue_id,
                'FINANCE_ISSUE_NO' => $finance_issue_no,*/
                'CREATE_USER' => session('user_id'),
                'CREATE_DATE' => time(),
                'UPDATE_USER' => session('user_id'),
                'UPDATE_DATE' => time()

            ];
            $financeOrder_model = new Finance_order_tab();
            $result = $financeOrder_model->financeOrderAdd($data);
            if(!$result){
                $returnData['state'] = '103';
                $returnData['msg'] = '生成票券订单失败！';
                echo json_encode($returnData);die();
            }else{
                $returnData['state'] = '100';
                $returnData['msg'] = '生成票券订单成功！';
                $returnData['info'] = $financeOrder_model->FINANCE_ORDER_ID;
                $returnData['orderCd'] = $finance_order_cd;
                echo json_encode($returnData);die();
            }

       }
    }
    //微信支付商品购买处理
    public function buyPost(){
        if(Request::instance()->isPost()){
            $post = input();
            //生成发行券
            $dataFinanceIssue = $this->createFinanceIssue($post['FINANCE_ID'],$post['FINANCE_TYPE'],$post['SALE_NUM']);
            if(!$dataFinanceIssue){
                $this->redirect(url('wx/mall/productList'));
            }
            //更新票券订单信息
            $financeOrder_model = new Finance_order_tab();
            $dataFinanceIssue['ORDER_STATUS'] = 1;
            $result = $financeOrder_model->updateFinanceOrderByCD($dataFinanceIssue,$post['FINANCE_ORDER_ID']);
            if(!empty($result)){
                $this->redirect(url('wx/user/userTicket'));
            }else{
                $this->redirect(url('wx/mall/productList'));
            }


        }
    }
    private function createFinanceIssue($fiance_id,$finance_type,$sale_num){
        $financeIssue_model = new Finance_issue_tab();
        $dataFinanceIssue = [];
        for($i=0;$i<$sale_num;$i++) {
            $finance_issue_no = 'IST' . substr(time(), 5, -1) . rand(1000, 9999);
            $dataFinanceIssue[$i] = [
                'FINANCE_ID' => $fiance_id,
                'FINANCE_TYPE' => $finance_type,
                'FINANCE_ISSUE_NO' => $finance_issue_no,
                'USER_ID' => session('user_id'),
                'CREATE_USER' => session('user_id'),
                'CREATE_DATE' => time(),
                'UPDATE_USER' => session('user_id'),
                'UPDATE_DATE' => time(),
            ];
        }
        $resultIssue = $financeIssue_model->saveAll($dataFinanceIssue);
        if(!$resultIssue){
           return '';
        }
        $finance_issue_ids = [];
        $finance_issue_nos = [];
        foreach($resultIssue as $k=>$v){
            $finance_issue_ids[$k] = $v['FINANCE_ISSUE_ID'];
            $finance_issue_nos[$k] = $v['FINANCE_ISSUE_NO'];
        }
        $ids = implode(',',$finance_issue_ids);
        $nos = implode(',',$finance_issue_nos);
        $returnData = [
            'FINANCE_ISSUE_NO' => $nos,
            'FINANCE_ISSUE_ID' => $ids
        ];
        return  $returnData;
    }
}