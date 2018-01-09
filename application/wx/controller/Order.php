<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 9:23
 */

namespace app\wx\controller;


use app\admin\model\Order_tab;
use app\admin\model\Project_tab;
use app\admin\model\Room_tab;
use app\admin\model\Finance_issue_tab;
use app\admin\model\Store_tab;
use app\wx\model\Card_operat_tab;
use app\wx\model\Member_info_tab;
use app\admin\model\Evaluate_tab;
use app\wx\model\User_tab;
use app\admin\model\Room_order_time_tab;
use app\admin\model\Time_set_rule_tab;
use app\admin\model\Project_plan_price_tab;
use app\admin\model\Price_plan_category_tab;
use Com\JsSdkPay;
use think\Request;

class Order extends Base
{
    /**订单列表
     *
     * */
    public function orderList(){
        if(strlen(session('openid'))>3){
            $user = new User_tab();
            $userid = $user->openidGetUserOne(session('openid'));
            if($userid['TEL_NO']){
                $order = new Order_tab();
                $user = new User_tab();
                $udata = $user->openidGetUserOne(session('openid'));
                $groupBy = 'WX_ORDER_NO';
                $field = 'WX_ORDER_NO';
                $dataGroup = $order->getOrderListByUser($udata['USER_ID'],$field,$groupBy);
                $non_payment = [];
                $unused = [];
                $service = [];
                $evaluate = [];
                $dataStime = [];//存储计时器
                $dataPay = [];//已付款
                foreach ($dataGroup as $k=>$value){
                    $datas = $order->orderListNpage(array('WX_ORDER_NO'=>$value['WX_ORDER_NO']),1);
                    $dataGroup[$k] = $datas[0];
                    $project = new Project_tab();
                    $pdata = $project->getProjectOneByID($datas[0]['PROJECT_CD']);
                    $img[$k] = explode(',',$pdata['PROJECT_IMAGE']);
                    $dataGroup[$k]['PROJECT_IMAGE'] =  $img[$k][0];
                    $dataGroup[$k]['PROJECT_INTRODUCE'] = $pdata['PROJECT_INTRODUCE'];
                    //$dataGroup[$k]['SELL_PRICE'] = $datas[0]['SELL_PRICE']*$datas[0]['CUSTOMER_TOTAL_QTY'];
                     $dataGroup[$k]['SELL_PRICE'] = round($datas[0]['SELL_PRICE'],2);
                    if($datas[0]['ORDER_STATUS']==0){
                        $img[$k] = explode(',', $pdata['PROJECT_IMAGE']);
                        $dataGroup[$k]['PROJECT_IMAGE'] = $img[$k][0];
                        $dataGroup[$k]['PROJECT_INTRODUCE'] = $pdata['PROJECT_INTRODUCE'];
                        //存储计时器
                        $extra_time= $datas[0]['ORDER_DATE_TIMESTAMP'] + 5 * 60 - time();
                        $dataStime[] = [
                            'order_cd' => $datas[0]['ORDER_CD'],
                            'extra_time' => $extra_time
                        ];
                        $dataGroup[$k]['extra_time'] = $extra_time;
                        $data[0]['extra_time'] = $extra_time;
                        $non_payment[] = $datas[0];

                    }

                    if($datas[0]['ORDER_STATUS']==1 || $datas[0]['ORDER_STATUS']==7){
                        $img[$k] = explode(',',$pdata['PROJECT_IMAGE']);
                        $dataGroup[$k]['PROJECT_IMAGE'] =  $img[$k][0];
                        $dataGroup[$k]['PROJECT_INTRODUCE'] = $pdata['PROJECT_INTRODUCE'];
                        $unused[] = $datas[0];
                        $con_costed = [
                            'WX_ORDER_NO'=>$datas[0]['WX_ORDER_NO'],
                            'ORDER_STATUS'=>array('egt',7)
                        ];
                        $costedNum = $order->getOrdersNum($con_costed);
                        $dataPay[] = [
                            'order_cd'=>$datas[0]['ORDER_CD'],
                            'order_no' => $datas[0]['ORDER_NO'],
                            'noCost_num'=> $datas[0]['CUSTOMER_TOTAL_QTY']-$costedNum
                        ];
                    }

                    if($datas[0]['ORDER_STATUS']==4||$datas[0]['ORDER_STATUS']==10){
                        $img[$k] = explode(',',$pdata['PROJECT_IMAGE']);
                        $dataGroup[$k]['PROJECT_IMAGE'] =  $img[$k][0];
                        $dataGroup[$k]['PROJECT_INTRODUCE'] = $pdata['PROJECT_INTRODUCE'];
                        $evaluate[] = $datas[0];
                    }
                    if($datas[0]['ORDER_STATUS']==8||$datas[0]['ORDER_STATUS']==9){
                        $img[$k] = explode(',',$pdata['PROJECT_IMAGE']);
                        $dataGroup[$k]['PROJECT_IMAGE'] =  $img[$k][0];
                        $dataGroup[$k]['PROJECT_INTRODUCE'] = $pdata['PROJECT_INTRODUCE'];
                        $service[] = $datas[0];
                    }
                }
                $this->assign('service',$service);
                $this->assign('non_payment',$non_payment);
                $this->assign('uunused',$unused);
                $this->assign('evaluate',$evaluate);
                $this->assign('data',$dataGroup);
                $this->assign('dataStime',json_encode($dataStime));
                $this->assign('dataPay',json_encode($dataPay));
                return $this->fetch();
            }else{
                $this->redirect(WEB_URL.'/wx/home/binding');
            }
        }else{
            $this->redirect(WEB_URL."/wx/index/OAuth");
        }
    }
    /**
     * 订单基本信息
     * */
    public function orderPlain(){
		 if(strlen(session('openid'))>3){
            $user = new User_tab();
            $userid = $user->openidGetUserOne(session('openid'));
            if($userid['TEL_NO']){
				$sid = input('store_cd');
				$pid = input('pid');
				$project = new Project_tab();
				$store = new Store_tab();
				$room = new Room_tab();
				$sdata = $store->getStoreOne($sid);
				$pdata = $project->getStoreProjectOne($pid,$sid);
				$pdata['PRICE'] = round($pdata['PRICE'],2);
				//预约时间段列表
				$time_model = new Time_set_rule_tab();
				$condition = [
					'AVAILABLE_FLG'=>1,
					'STORE_CD'=>$sid
				];
				$timelist = $time_model->getTimeList($condition);
				foreach($timelist as $key=>$val){
					$timelist[$key]['PRICE'] = intval($val['PRICE']);
					if(floatval($pdata['PRICE'] < $val['PRICE'])){
						$timelist[$key]['order_disable'] = 1;
					}else{
						$timelist[$key]['order_disable'] = '';
					}
				}
				//获取今天开始后的7天
				$dayTime = array();
				for($t = 1;$t<=7;$t++){
					$k = $t-1;
					if($k == 0){
						$dayTime[$k]['day'] = strtotime(date('Y-m-d',time()));
						$dayTime[$k]['week'] = $this->getWeekOrder(date("w"));
					}else{
						$dayTime[$k]['day'] = strtotime(date('Y-m-d',strtotime("+".$k." day")));
						$dayTime[$k]['week'] = $this->getWeekOrder(date('w',strtotime("+".$k." day")));
					}
				}
				$rid = explode(',',$pdata['ROOM_CD']);
				$rRoom = $room->getRoomListBySID($rid,$sid);
				$sRoom = $room->roomList1($sid);		
				//最低预约人数
				foreach($rRoom as $kr=>$vr){
					//最低预约人数
					$yushu = $vr['SOFA_QTY']%2;
					$quzheng = intval($vr['SOFA_QTY']/2);
					if(!empty($yushu)){
						$rRoom[$kr]['ORDER_PERSON'] = intval($yushu)+intval($quzheng);
					}else{
						$rRoom[$kr]['ORDER_PERSON'] = $quzheng;
					}
				}
				$this->assign('dayTime',$dayTime);
				$this->assign('timelist',$timelist);
				$this->assign('sid',$sid);
				$this->assign('sdata',$sdata);
				$this->assign('pdata',$pdata);
				$this->assign('sRoom',$rRoom);
				return $this->fetch();
			}else{
				$this->redirect(WEB_URL.'/wx/home/binding');
			}
		}else{
            $this->redirect(WEB_URL."/wx/index/OAuth");
        }
    }
    /**
     * 订单详情
     * */
    public function orderDetails(){
        $project = new Project_tab();
        $user = new User_tab();
        $store = new Store_tab();
        $room = new Room_tab();
        $order = new Order_tab();
        $time = new Time_set_rule_tab();
        $rtime = new Room_order_time_tab();
        $member_card = new Member_info_tab();
        //获取会员卡余额
        $cardInfo = $member_card->getUserCard(session('user_id'));
        $cardPay_can = $cardInfo['RECEIVE_AMT']+$cardInfo['GIVE_AMT'];
        $this->assign('cardPay_can',$cardPay_can);
        $post = input();
        //获取个人代金券
        $dataIssue = $this->getFinanceIssueData();
        //print_r($dataIssue);die();
        if($dataIssue){
            $this->assign('dataIssue',$dataIssue);
        }
        if(!array_key_exists("oid",$post)){
            $rdata = $room->getRoomOne($post['sid'],$post['room_cd']);
            $pdata = $project->getStoreProjectOne($post['pid'],$post['sid']);
            $sdata = $store->getStoreOne($post['sid']);
            $udata = $user->openidGetUserOne(session('openid'));
            //预约日期
            if(empty($post['ORDER_DATE'])){
                $post['ORDER_DATE'] = date('Y-m-d');
                $order_date = date('Y-m-d');
            }else{
                $order_date = $post['ORDER_DATE'];
            }
            //预约时间点
            if(!empty($post['TIME_CD'])){
               $time_arr = explode(',',$post['TIME_CD']);
            }else{
                $this->error('未设置预约时间点');
            }
			$rtime_cd = [];	
            foreach($time_arr as $k=>$v){
                $timeInfo = $time->getTimeInfo($v);
                //预约单表时间
                $order_start_date_time[] =  $timeInfo['ORDER_START_DATE_TIME'];
                $order_end_date_time =  $timeInfo['ORDER_END_DATE_TIME'];
                $time_start = strtotime($post['ORDER_DATE'].' '.$timeInfo['ORDER_START_DATE_TIME']);
                //跨天预约日期修改
                if($time_start < strtotime($post['ORDER_DATE'].' '.$sdata['STORE_START_TIME'])){
                    $order_date = date('Y-m-d',(strtotime($post['ORDER_DATE'])+24*3600));
                    $order_date_arr[] = $order_date;
                }else{
                    $order_date_arr[] = $post['ORDER_DATE'];
                }
                //查询房间是否预订
                $condition = [
                    'ROOM_CD' => $post['room_cd'],
                    'STORE_CD' => $post['sid'],
                    'ORDER_DATA' => $order_date,
                    'ORDER_START_DATE_TIME' =>  $timeInfo['ORDER_START_DATE_TIME'],
                    'ORDER_END_DATE_TIME' =>  $timeInfo['ORDER_END_DATE_TIME'],
                    'AVAILABLE_FLG'=>1 
                ];
                $num = $rtime->getRoomNoteNum($condition);
                if($num > 0){
                    $this->error('该房间已经被预订了！');
                }

                $data_room_time[] = [
                    'ROOM_CD'=>$post['room_cd'],
                    'STORE_CD'=>$post['sid'],
                    'STORE_NAME'=>$sdata['STORE_NAME'],
                    'ORDER_DATA'=>$order_date,
                    'ORDER_START_DATE_TIME'=> $timeInfo['ORDER_START_DATE_TIME'],
                    'ORDER_END_DATE_TIME'=> $timeInfo['ORDER_END_DATE_TIME'],
                    'CREATE_USER'=>session('user_id'),
                    'CREATE_DATE'=>date('Y-m-d H:i:s',time()),
                    'UPDATE_USER'=>session('user_id'),
                    'UPDATE_DATE'=>date('Y-m-d H:i:s',time()),
                    'TIME_CD'=> $timeInfo['TIME_CD']
                ];
				/* $rtime->ROOM_CD = $post['room_cd'];
                $rtime->STORE_CD = $post['sid'];
                $rtime->STORE_NAME = $sdata['STORE_NAME'];
                $rtime->ORDER_DATA = $order_date;
                $rtime->ORDER_START_DATE_TIME =  $timeInfo['ORDER_START_DATE_TIME'];
                $rtime->ORDER_END_DATE_TIME =  $timeInfo['ORDER_END_DATE_TIME'];
                $rtime->CREATE_USER = session('user_id');
                $rtime->CREATE_DATE = date('Y-m-d H:i:s',time());
                $rtime->UPDATE_USER = session('user_id');
                $rtime->UPDATE_DATE = date('Y-m-d H:i:s',time());
                $rtime->TIME_CD = $timeInfo['TIME_CD'];
				$rtime->AVAILABLE_FLG = 1;
                $rtime->save();
				$rtime_cd[] = $rtime->RTIME_CD; */
            }
            $rtime_cd = [];			
            foreach($data_room_time as $val){
                $result_time = $rtime->data($val,true)->isUpdate(false)->save();
                $rtimeInfo = $rtime->roomTimeList($val);
                $rtime_cd[] =  $rtimeInfo[0]['RTIME_CD'];
            }
			
            //微信订单号
            $wx_order_no = "020".time();
            $order_no = rand(100000,999999);
            $data = array();
            //获取项目优惠方案
            $priceat = new Project_plan_price_tab();
            $priceatInfo = $priceat->getProjectPriceatInfo($pdata['PROJECT_ID']);
            $data = $this->createPriceatPlan($wx_order_no,$order_no,$udata,$sdata,$post,$rdata,$order_date_arr,$pdata, $order_start_date_time,$order_end_date_time,$rtime_cd,$priceatInfo);
            //判断项目是否存在优惠方案
            if(isset($data['SALED']) && $data['SALED'] == 1){
                $this->assign('saled',$data['SALED']);
            }else{
                $this->assign('saled',0);
            }
            $res = $order->saveAll($data['DATAS']);
            if($res){
                $odata = $order->orderListNpage(array('WX_ORDER_NO'=>$wx_order_no),'1');
                $odata[0]['ORDER_AMT'] = round($odata[0]['ORDER_AMT'],2);
				if(isset($sdata['ADDRESS']) && mb_strlen($sdata['ADDRESS'],'utf8')> 16){
					$sdata['ADDRESS'] = mb_substr($sdata['ADDRESS'],0,16,'utf8').'...';
				}
				if(isset($odata[0]['ORDER_DATE'])){
					//$odata[0]['ORDER_DATE'] = getDateweek($odata[0]['ORDER_DATE']);
				}
				$this->assign('pdata',$pdata);
                $this->assign('orderAmt',$odata[0]['ORDER_AMT']);
                $this->assign('odata',$odata[0]);
				$this->assign('sdata',$sdata);
				$this->assign('udata',$udata);
                return $this->fetch();
            }else{
                $this->error('未知原因失败了！请重新下单！');
            }
        }else{
            $odata = $order->getOrderInfoByID($post['oid']);
            $project_model = new Project_tab();
            $priceat_model = new Project_plan_price_tab();
			$store_model = new Store_tab();
			$sdata = $store_model->getStoreOne($odata['STORE_CD']);
			$user = new User_tab();
            $userid = $user->openidGetUserOne(session('openid'));
			if(isset($sdata['ADDRESS']) && mb_strlen($sdata['ADDRESS'],'utf8')> 16){
				$sdata['ADDRESS'] = mb_substr($sdata['ADDRESS'],0,16,'utf8').'...';
			}
			if(isset($odata['ORDER_DATE'])){
				$odata['ORDER_DATE'] = getDateweek($odata['ORDER_DATE']);
			}
            //项目信息
            $projectInfo = $project_model->getStoreProjectOne($odata['PROJECT_CD'],$odata['STORE_CD']);
            //项目优惠方案信息
            $priceatInfo = $priceat_model->getProjectPriceatInfo($projectInfo['PROJECT_ID']);
            $priceatCode = $this->getProjectPriceat($projectInfo['PROJECT_CD'],$projectInfo['STORE_CD']);
            $state = $this->getPriceatStateNum($priceatCode,$odata,$priceatInfo);
            //项目优惠方案标示
            if($state == 'yes'){
                $this->assign('saled',1);
            }else{
                $this->assign('saled',0);
            }
            $odata['ORDER_AMT'] = round($odata['ORDER_AMT'],2);
            $this->assign('orderAmt',$odata['ORDER_AMT']);
            $this->assign('pdata',$projectInfo);
            $this->assign('odata',$odata);
			$this->assign('sdata',$sdata);
			$this->assign('udata',$userid);
            return $this->fetch();
        }
    }
    /**
     * 优惠方案
     */
    public function createPriceatPlan($wx_order_no,$order_no,$udata,$sdata,$post,$rdata,$order_date_arr,$pdata,$order_start_date_time,$order_end_date_time,$rtime_cd,$priceatInfo){
        $priceCate_model = new Price_plan_category_tab();
        //下单价格
        $sell_price = '';
        $data = [];
        //是否优惠
        $info = [];
        $saled = '';
        //一级优惠
        if(!empty($priceatInfo['LEVEL_ONE'])){
            //优惠类别详情
            $pCateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_ONE']);
            //预约日
            $day = $order_date_arr[0];
            if($pCateInfo['LEVEL_TYPE'] == 1 &&  $day == $pCateInfo['LEVEL_RULE']){//特定日期优惠
                $sell_price = $pdata['PRICE'] - $priceatInfo['LEVEL_ONE_SALE_PRICE'];
                $saled = 'saled';
            }else if($pCateInfo['LEVEL_TYPE'] == 3 &&  $post['CUSTOMER_QTY']%2==0){//2人免单
                $data = $this->changeOrderOne($wx_order_no,$order_no,$udata,$sdata,$post,$rdata,$order_date_arr,$pdata,$order_start_date_time,$order_end_date_time,$rtime_cd,$priceatInfo);
                return $data;
            }else{
                $sell_price = $pdata['PRICE'];
            }
        }else if(!empty($priceatInfo['LEVEL_TWO'])){//二级优惠
            $pCateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_TWO']);
            //每月特定日期优惠
            if($pCateInfo['LEVEL_TYPE'] == 1){
                //预约日
                $day = intval(substr($order_date_arr[0],9,2));
                $dayArr = explode(',',$pCateInfo['LEVEL_RULE']);
                if(in_array($day,$dayArr)){
                    $sell_price = $pdata['PRICE'] - $priceatInfo['LEVEL_TWO_SALE_PRICE'];
                    $saled = 'saled';
                }else{
                    $sell_price = $pdata['PRICE'];
                }
            }else{
                $sell_price = $pdata['PRICE'];
            }
        }else if(!empty($priceatInfo['LEVEL_THREE'])){//三级会员优惠
            $sell_price = $pdata['PRICE'] - $priceatInfo['LEVEL_THREE_SALE_PRICE'];
            $saled = 'saled';
        }else if(!empty($priceatInfo['LEVEL_FOUR'])){//四级优惠
            $pCateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_FOUR']);
            //特定房间优惠
            if($pCateInfo['LEVEL_TYPE'] == 2){
                $roomArr = explode(',',$pCateInfo['LEVEL_RULE']);
                if(in_array($post['ROOM_CD'],$roomArr)){
                    $sell_price = $pdata['PRICE'] - $priceatInfo['LEVEL_FOUR_SALE_PRICE'];
                    $saled = 'saled';
                }else{
                    $sell_price = $pdata['PRICE'];
                }
            }else{
                $sell_price = $pdata['PRICE'];
            }
        }else{
            $sell_price = $pdata['PRICE'];
        }

        for ($t = 1; $t <= $post['CUSTOMER_QTY']; $t++) {
            $data[$t] = [
                'ORDER_CD' => '020' . time() . rand(10000, 99999),
                'WX_ORDER_NO' => $wx_order_no,
                'ORDER_NO' => $order_no,
                "USER_ID" => $udata['USER_ID'],
                'USER_NAME' => $udata['NICK_NAME'],
                'TEL_NO' => $udata['TEL_NO'],
                'CONNECT_TEL_NO' => $udata['TEL_NO'],
                'CONNECT_USER_NAME' => $udata['NICK_NAME'],
                'ORDER_TYPE' => 1,
                'CUSTOMER_QTY' => 1,
                'CUSTOMER_TOTAL_QTY' => $post['CUSTOMER_QTY'],
                'PAY_STS' => 0,
                'STORE_CD' => $post['sid'],
                'STORE_NAME' => $sdata['STORE_NAME'],
                'ROOM_CD' => $post['room_cd'],
                'ROOM_NAME' => $rdata['ROOM_NAME'],
                'PROJECT_CD' => $post['pid'],
                'PROJECT_NAME' => $pdata['PROJECT_NAME'],
                'MARK_PRICE' => $pdata['MARKET_PRICE'],
                'SELL_PRICE' => $pdata['PRICE'],
                'PAY_AMT'=>$sell_price,
                'ORDER_STATUS' => 0,
                'ORDER_DATE' => $order_date_arr[0],
                'OR_START_DATE_TIME' => $order_start_date_time[0],
                'OR_END_DATE_TIME' => $order_end_date_time,
                'ORDER_DATE_TIME' => date("Y-m-d h:i:s", time()),
                'ORDER_DATE_TIMESTAMP' => time(),
                'PROJECT_TIME' => $pdata['PROJECT_TIME'],
                'PROJECT_INFO' => $pdata['PROJECT_INTRODUCE'],
                'ORDER_AMT' => $sell_price*$post['CUSTOMER_QTY'],
                'RTIME_CD' => implode(',', $rtime_cd)
            ];
        }
        $info['DATAS'] = $data;
        $info['SALED'] = $saled?1:0;
        return $info;
        /*//下单价格
        $order_amt = '';
        $data = [];
        //是否优惠
        $info = [];
        $saled = 0;
        //一级优惠
        if(!empty($priceatInfo['LEVEL_ONE'])){
            //优惠类别详情
            $pCateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_ONE']);
            //预约日
            $day = $order_date_arr[0];
            if($pCateInfo['LEVEL_TYPE'] == 1 &&  $day == $pCateInfo['LEVEL_RULE']){//特定日期优惠
                $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY']- $priceatInfo['LEVEL_ONE_SALE_PRICE'];
                $saled = 'saled';
            }else if($pCateInfo['LEVEL_TYPE'] == 3 &&  $post['CUSTOMER_QTY']%2==0){//2人免单
                $data = $this->changeOrderOne($wx_order_no,$order_no,$udata,$sdata,$post,$rdata,$order_date_arr,$pdata,$order_start_date_time,$order_end_date_time,$rtime_cd,$priceatInfo);                
                return $data;
            }else{
                $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY'];
            }
        }else if(!empty($priceatInfo['LEVEL_TWO'])){//二级优惠
            $pCateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_TWO']);
            //每月特定日期优惠
            if($pCateInfo['LEVEL_TYPE'] == 1){
                //预约日
                $day = intval(substr($order_date_arr[0],9,2));
                $dayArr = explode(',',$pCateInfo['LEVEL_RULE']);
                if(in_array($day,$dayArr)){
                    $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY']- $priceatInfo['LEVEL_TWO_SALE_PRICE'];
                    $saled = 'saled';
                }else{
                    $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY'];
                }
            }else{
                $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY'];
            }
        }else if(!empty($priceatInfo['LEVEL_THREE'])){//三级会员优惠
            $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY'] - $priceatInfo['LEVEL_THREE_SALE_PRICE'];
            $saled = 'saled';
        }else if(!empty($priceatInfo['LEVEL_FOUR'])){//四级优惠
            $pCateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_FOUR']);
            //特定房间优惠
            if($pCateInfo['LEVEL_TYPE'] == 2){
                $roomArr = explode(',',$pCateInfo['LEVEL_RULE']);
                if(in_array($post['ROOM_CD'],$roomArr)){
                    $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY'] - $priceatInfo['LEVEL_FOUR_SALE_PRICE'];
                    $saled = 'saled';
                }else{
                    $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY'];
                }
            }else{
                $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY'];
            }
        }else{
            $order_amt = $pdata['PRICE']*$post['CUSTOMER_QTY'];
        }

        for ($t = 1; $t <= $post['CUSTOMER_QTY']; $t++) {
            $data[$t] = [
                'ORDER_CD' => '020' . time() . rand(10000, 99999),
                'WX_ORDER_NO' => $wx_order_no,
                'ORDER_NO' => $order_no,
                "USER_ID" => $udata['USER_ID'],
                'USER_NAME' => $udata['NICK_NAME'],
                'TEL_NO' => $udata['TEL_NO'],
                'CONNECT_TEL_NO' => $udata['TEL_NO'],
                'CONNECT_USER_NAME' => $udata['NICK_NAME'],
                'ORDER_TYPE' => 1,
                'CUSTOMER_QTY' => 1,
                'CUSTOMER_TOTAL_QTY' => $post['CUSTOMER_QTY'],
                'PAY_STS' => 0,
                'STORE_CD' => $post['sid'],
                'STORE_NAME' => $sdata['STORE_NAME'],
                'ROOM_CD' => $post['room_cd'],
                'ROOM_NAME' => $rdata['ROOM_NAME'],
                'PROJECT_CD' => $post['pid'],
                'PROJECT_NAME' => $pdata['PROJECT_NAME'],
                'MARK_PRICE' => $pdata['MARKET_PRICE'],
                'SELL_PRICE' => $pdata['PRICE'],
                'ORDER_STATUS' => 0,
                'ORDER_DATE' => $order_date_arr[0],
                'OR_START_DATE_TIME' => $order_start_date_time[0],
                'OR_END_DATE_TIME' => $order_end_date_time,
                'ORDER_DATE_TIME' => date("Y-m-d h:i:s", time()),
                'ORDER_DATE_TIMESTAMP' => time(),
                'PROJECT_TIME' => $pdata['PROJECT_TIME'],
                'PROJECT_INFO' => $pdata['PROJECT_INTRODUCE'],
                'ORDER_AMT' => $order_amt,
                'RTIME_CD' => implode(',', $rtime_cd)
            ];
        }
        $info['DATAS'] = $data;
        $info['SALED'] = $saled?1:0;
        return $info;*/
    }
    /**
     * 二人免单
     */
    public function changeOrderOne($wx_order_no,$order_no,$udata,$sdata,$post,$rdata,$order_date_arr,$pdata,$order_start_date_time,$order_end_date_time,$rtime_cd,$priceatInfo){
        $is_refund = '';
        $data = [];
        for ($t = 1; $t <= $post['CUSTOMER_QTY']; $t++) {
            //大于1人免单，并且不可退款
            $saledPerson = $post['CUSTOMER_QTY']/2;
            if($t > $saledPerson){
                $is_refund = 0;
                $pay_amt = 0;
            }else{
                $is_refund = 1;
                $pay_amt = $pdata['PRICE'];
            }
            $data[$t] = [
                'ORDER_CD' => '020' . time() . rand(10000, 99999),
                'WX_ORDER_NO' => $wx_order_no,
                'ORDER_NO' => $order_no,
                "USER_ID" => $udata['USER_ID'],
                'USER_NAME' => $udata['NICK_NAME'],
                'TEL_NO' => $udata['TEL_NO'],
                'CONNECT_TEL_NO' => $udata['TEL_NO'],
                'CONNECT_USER_NAME' => $udata['NICK_NAME'],
                'ORDER_TYPE' => 1,
                'CUSTOMER_QTY' => 1,
                'CUSTOMER_TOTAL_QTY' => $post['CUSTOMER_QTY'],
                'PAY_STS' => 0,
                'STORE_CD' => $post['sid'],
                'STORE_NAME' => $sdata['STORE_NAME'],
                'ROOM_CD' => $post['room_cd'],
                'ROOM_NAME' => $rdata['ROOM_NAME'],
                'PROJECT_CD' => $post['pid'],
                'PROJECT_NAME' => $pdata['PROJECT_NAME'],
                'MARK_PRICE' => $pdata['MARKET_PRICE'],
                'SELL_PRICE' => $pdata['PRICE'],
                'PAY_AMT' => $pay_amt,
                'ORDER_STATUS' => 0,
                'IS_REFUND'=>$is_refund,
                'ORDER_DATE' => $order_date_arr[0],
                'OR_START_DATE_TIME' => $order_start_date_time[0],
                'OR_END_DATE_TIME' => $order_end_date_time,
                'ORDER_DATE_TIME' => date("Y-m-d h:i:s", time()),
                'ORDER_DATE_TIMESTAMP' => time(),
                'PROJECT_TIME' => $pdata['PROJECT_TIME'],
                'PROJECT_INFO' => $pdata['PROJECT_INTRODUCE'],
                'ORDER_AMT' => $pdata['PRICE']*$post['CUSTOMER_QTY']/2,
                'RTIME_CD' => implode(',', $rtime_cd)
            ];
        }
        $info = [
            'DATAS'=>$data,
            'SALED'=>1
        ];
        return $info;
    }
    /**
     * ajax生成微信支付单
     */
    public function ajaxGetWxOrder(){
            $post = input();
           //微信支付
            ini_set('date.timezone','Asia/Shanghai');
            include_once '/extend/weixinPay/lib/WxPay.Api.php';
            include_once '/extend/weixinPay/pay/log.php';
            include_once '/extend/weixinPay/lib/WxPay.JsApiPay.php';

            //初始化日志
            $logHandler= new \CLogFileHandler(EXTEND_PATH."weixinPay/logs/".date('Y-m-d').'.log');
            $log = \Log::Init($logHandler, 15);
            //电子卡信息
            $member_card = new Member_info_tab();
            $cardInfo = $member_card->getUserCard(session('user_id'));
            //会员信息
            $user_model = new User_tab();
            $user_info = $user_model->idGetUserOne(session('user_id'));
            if($post['check_num'] == 2){//混合支付
                if(md5($post['member_pass']) != $cardInfo['MEMBER_PASS']){
                    $data['state'] = 201;
                    $data['msg'] = '电子卡密码错误!';
                    echo json_encode($data);die();
                }
                if($post['sell_price'] > $cardInfo['RECEIVE_AMT'] ){
                    $price = $post['sell_price'] - $cardInfo['RECEIVE_AMT'];
                }else{
                    $data['state'] = 202;
                    $data['msg'] = '电子卡足以全额支付!';
                    $data['info'] = array('sell_price'=>$post['sell_price'],'receive_amt'=>$cardInfo['RECEIVE_AMT']);
                    echo json_encode($data);die();
                }
            }else{//微信支付价格
                $price = $post['sell_price'];
            }

            if(!$price){
                $data['state'] = 'error';
                $data['msg'] = '无价格信息';
                echo json_encode($data);die();
            }
            $input = new \WxPayUnifiedOrder();
            $input->SetBody($post['project_name']);//设置商品或支付单简要描述
            $input->SetOut_trade_no($post['order_cd']);//设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
            $input->SetTotal_fee(intval($price*100));//设置订单总金额，单位为分，只能为整数，详见支付金额
            $input->SetGoods_tag($post['project_name']);//设置标记
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($user_info['OPENID']);
            $order =  \WxPayApi::unifiedOrder($input);
            $tools = new \JsApiPay();
            $jsApiParameters = $tools->GetJsApiParameters($order);
            $info = json_decode($jsApiParameters);
            if($jsApiParameters){
                $data['state'] = 'success';
                $data['info'] = array($info);
            }else{
                $data['state'] = 'error';
                $data['msg'] = '无微信订单信息生成！';
            }
            echo json_encode($data);die();
    }

    /**
     * 微信支付回调
     */

    public function notify(){               
        $xmlData = file_get_contents('php://input');		
		libxml_disable_entity_loader(true);
		$data = json_decode(json_encode(simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA)),true);			
		ksort($data);
		$buff = '';
		foreach ($data as $k => $v){
			if($k != 'sign'){
				$buff .= $k . '=' . $v . '&';
			}
		}
		$stringSignTemp = $buff . 'key=6bec1f6fc31092f968b8ca7577f8b74d';//key为证书密钥		                               
		$sign = strtoupper(md5($stringSignTemp));		
		//判断算出的签名和通知信息的签名是否一致
		if($sign == $data['sign']){
			//处理完成之后，告诉微信成功结果
			echo '<xml>
					  <return_code><![CDATA[SUCCESS]]></return_code>
					  <return_msg><![CDATA[OK]]></return_msg>
				  </xml>';
			exit();
		}		
    }
    /**
     * 下单成功
     *
     */
    public function orderYes(){

        $post = input();
        $pay_type_num = count($post['PAY_TYPE']);
        $card = new Member_info_tab();
        $user = new User_tab();
        $order = new Order_tab();
        if($pay_type_num == 1 && $post['PAY_TYPE'][0] == 2 ){//微信支付
            $data = [
                'CONNECT_USER_NAME'=>$post['CONNECT_USER_NAME'],
                'CONNECT_TEL_NO'=>$post['CONNECT_TEL_NO'],
                'PAY_STS'=>1,
                //1是微信
                'PAY_TYPE'=>1,
            //  'PAY_AMT'=>$post['SELL_PRICE'],
                'ORDER_AMT'=>$post['ORDER_AMT'],
                'ORDER_STATUS'=>1,
                'PAY_DATE_TIME'=>date("Y-m-d h:i:s",time()),
            ];
            //设置票券支付
            if(!empty($post['FINANCE_ISSUE_ID']) && !empty($post['FINANCE_SELL_PRICE'])){
               //TODO 票券支付
                $data = $this->createFinancePayData($post);
                $post['PAY_TYPE'][0] = 3;//票券支付标示
            }
            $res = $order->updateOrederByWxNO($data,$post['WX_ORDER_NO']);
            //生成预约码并返回页面
            if($res){
                $odata = $order->getOrderInfoByID($post['oid']);
                //2人免单更新
                $res_two = $this->changeOrderYesTwo($post['WX_ORDER_NO'],$post['PAY_TYPE'][0],$odata);
                $this->assign('odata',$odata);
                return $this->fetch();
            }
        }else if($pay_type_num == 1 && $post['PAY_TYPE'][0] == 1){//电子卡支付
            $userid = $user->openidGetUserOne(session('openid'));
            $cdata = $card->getUserCard($userid['USER_ID']);
            if(md5($post['MEMBER_PASS'])==$cdata['MEMBER_PASS']){
                if(($cdata['RECEIVE_AMT']+$cdata['GIVE_AMT'])<$post['ORDER_AMT']){
                    $this->error('您的电子卡余额不足，请充值后使用！',url('wx/order/orderDetails',['oid'=>$post['oid']]));
                }else{
                    $receive_amt = "";
                    $give_amt = "";
                    //剩余赠款和充值金额
                    $pay_receive = $post['ORDER_AMT'] * $cdata['CARD_DISC_RATE_FINANCE'];
                    $pay_give = $post['ORDER_AMT'] - $pay_receive;
                    $receive_amt = $cdata['RECEIVE_AMT']-$pay_receive;
                    $give_amt = $cdata['GIVE_AMT'] - $pay_give;
                    //赠款余额不足
                    if($give_amt<0){
                        $receive_amt = $receive_amt+$give_amt;
                        $pay_give = $pay_give+$give_amt;
                        $pay_receive =  $post['ORDER_AMT']-$pay_give;
                        $give_amt = 0;
                    }
                    //充值余额不足
                    if($receive_amt<0){
                        $give_amt = $give_amt+$receive_amt;
                        $pay_receive = $pay_receive+$receive_amt;
                        $pay_give = $post['ORDER_AMT']-$pay_receive;
                        $receive_amt = 0;
                    }

                    $data = [
                        'RECEIVE_AMT'=>$receive_amt,
                        'GIVE_AMT'=>$give_amt,
                        'TOTAL_CONSUMP_AMT'=>$cdata['TOTAL_CONSUMP_AMT']+$post['ORDER_AMT'],
                        'TOTAL_CONSUMP_TIMES'=>$cdata['TOTAL_CONSUMP_TIMES']+1
                    ];
                    $res = $card->updataCard($data,$cdata['MEMBER_CARD_NO']);
                    if($res){
                        $datao = [
                            'CONNECT_USER_NAME'=>$post['CONNECT_USER_NAME'],
                            'CONNECT_TEL_NO'=>$post['CONNECT_TEL_NO'],
                            'PAY_STS'=>1,
                            //2是电子卡
                            'PAY_TYPE2'=>1,
                          'PAY_AMT2'=>$post['SELL_PRICE'],
                            'ORDER_AMT'=>$post['ORDER_AMT'],
                            'ORDER_STATUS'=>1,
                            'PAY_DATE_TIME'=>date("Y-m-d h:i:s",time()),
                        ];
                        $res_order = $order->updateOrederByWxNO($datao,$post['WX_ORDER_NO']);
                        //更新电子卡和电子卡操作记录
                        if($res_order){
                            $odata = $order->getOrderInfoByID($post['oid']);
                            $ccdata = [
                                'CARD_NO'=>$cdata['MEMBER_CARD_NO'],
                                'CARD_TYPE'=>$cdata['CARD_TYPE'],
                                'CARD_OPERAT_TYPE'=>"消费",
                                'USER_ID'=>session('user_id'),
                                'MEMBER_NAME'=>$cdata['MEMBER_NAME'],
                                'MEMBER_TEL'=>$cdata['TEL_NO'],
                                'MEMBER_SEX'=>$cdata['SEX'],
                                'STORE_CD'=>$odata['STORE_CD'],
                                'GIVE_AMT'=>$pay_give,
                                'CONSUMP_AMT'=>$pay_receive,
                                'AFTER_GIVE_AMT'=>$give_amt,
                                'AFTER_CONSUMP_AMT'=>$receive_amt,
                                'LEFT_AMT'=>$receive_amt+$give_amt,
                                'CREATE_DATE' => date('Y-m-d H:i:s',time()),
                                'REMARKS'=>"消费操作",
                            ];
                            $cardOP = new Card_operat_tab();
                            $r = $cardOP->cardOperatAdd($ccdata);
                            if($r){
                                $this->assign('odata',$odata);
                                return $this->fetch();
                            }
                        }
                    }
                }
            }else{
                $this->error('支付密码输入错误，请重新提交订单~',url('wx/order/orderDetails',['oid'=>$post['oid']]));
            }
        }else if($pay_type_num == 2){//混合支付
            $userid = $user->openidGetUserOne(session('openid'));
            $cdata = $card->getUserCard($userid['USER_ID']);
            $pay_wx = '';
            if(md5($post['MEMBER_PASS'])==$cdata['MEMBER_PASS']){
                if(($cdata['RECEIVE_AMT']+$cdata['GIVE_AMT'])<$post['ORDER_AMT']){
                    $data = [
                        'TOTAL_CONSUMP_AMT'=>$cdata['TOTAL_CONSUMP_AMT']+$cdata['RECEIVE_AMT'],
                        'TOTAL_CONSUMP_TIMES'=>$cdata['TOTAL_CONSUMP_TIMES']+1,
                        'RECEIVE_AMT' => 0,
                        'GIVE_AMT'=>0
                    ];
                    //微信支付金额
                    $pay_wx = $post['ORDER_AMT'] - $cdata['RECEIVE_AMT']-$cdata['GIVE_AMT'];
                    $pay_card = $cdata['RECEIVE_AMT'];
                }else{
                    $receive_amt = "";
                    $give_amt = "";
                    //剩余赠款和充值
                    $pay_receive = $post['ORDER_AMT'] * $cdata['CARD_DISC_RATE_FINANCE'];
                    $pay_give = $post['ORDER_AMT'] - $pay_receive;
                    $receive_amt = $cdata['RECEIVE_AMT']-$pay_receive;
                    $give_amt = $cdata['GIVE_AMT'] - $pay_give;
                    //赠款余额不足
                    if($give_amt<0){
                        $receive_amt = $receive_amt+$give_amt;
                        $pay_give = $pay_give+$give_amt;
                        $pay_receive =  $post['ORDER_AMT']-$pay_give;
                        $give_amt = 0;
                    }
                    //充值余额不足
                    if($receive_amt<0){
                        $give_amt = $give_amt+$receive_amt;
                        $pay_receive = $pay_receive+$receive_amt;
                        $pay_give = $post['ORDER_AMT']-$pay_receive;
                        $receive_amt = 0;
                    }

                    $data = [
                        'RECEIVE_AMT'=>$receive_amt,
                        'GIVE_AMT'=>$give_amt,
                        'TOTAL_CONSUMP_AMT'=>$cdata['TOTAL_CONSUMP_AMT']+$post['ORDER_AMT'],
                        'TOTAL_CONSUMP_TIMES'=>$cdata['TOTAL_CONSUMP_TIMES']+1
                    ];
                    $pay_card = $post['ORDER_AMT'];
                }
                $res = $card->updataCard($data,$cdata['MEMBER_CARD_NO']);
                if($res){
                    //预约单详情
                    $condition_order = ['WX_ORDER_NO'=>$post['WX_ORDER_NO']];
                    $orderList = $order->orderListNpage($condition_order);
                    //预约单更新
                    $dataOrder_update = [];
                    $card_remain = $cdata['RECEIVE_AMT'];
                    for($t=1;$t<=$post['CUSTOMER_TOTAL_QTY'];$t++){
                        $dataOrder_update[$t-1] = [
                            'ORDER_FD' => $orderList[$t-1]['ORDER_FD'],
                            'ORDER_CD' => $orderList[$t-1]['ORDER_CD'],
                            'CONNECT_USER_NAME' => $post['CONNECT_USER_NAME'],
                            'CONNECT_TEL_NO' => $post['CONNECT_TEL_NO'],
                            'PAY_STS' => 1,
                            'ORDER_STATUS' => 1,
                            'ORDER_AMT' => $post['ORDER_AMT'],
                            'PAY_DATE_TIME' => date("Y-m-d h:i:s",time())
                        ];
                        if($card_remain > $post['SELL_PRICE']){
                            $dataOrder_update[$t-1]['PAY_TYPE2'] = 1;
                            $dataOrder_update[$t-1]['PAY_AMT2'] = $post['SELL_PRICE'];
                            $card_remain = $card_remain - $post['SELL_PRICE'];
                        }else if($card_remain > 0 && $card_remain <= $post['SELL_PRICE']){
                            $dataOrder_update[$t-1]['PAY_TYPE4'] = 1;
                            $dataOrder_update[$t-1]['PAY_AMT2'] = $card_remain;
                            $dataOrder_update[$t-1]['PAY_AMT'] = $post['SELL_PRICE'] - $card_remain;
                            $card_remain = 0;
                        }else if($card_remain == 0){
                            $dataOrder_update[$t-1]['PAY_TYPE'] = 1;
                            $dataOrder_update[$t-1]['PAY_AMT'] = $post['SELL_PRICE'];
                        }
                    }
                    $res_order = $order->saveAll($dataOrder_update);
                    if($res_order && $pay_card){
                        $odata = $order->getOrderInfoByID($post['oid']);
                        $ccdata = [
                            'CARD_NO'=>$cdata['MEMBER_CARD_NO'],
                            'CARD_TYPE'=>$cdata['CARD_TYPE'],
                            'CARD_OPERAT_TYPE'=>"消费",
                            'USER_ID'=>session('user_id'),
                            'MEMBER_NAME'=>$cdata['MEMBER_NAME'],
                            'MEMBER_TEL'=>$cdata['TEL_NO'],
                            'MEMBER_SEX'=>$cdata['SEX'],
                            'STORE_CD'=>$odata['STORE_CD'],
                            'GIVE_AMT'=>$pay_give,
                            'CONSUMP_AMT'=>$pay_receive,
                            'AFTER_GIVE_AMT'=>$give_amt,
                            'AFTER_CONSUMP_AMT'=>$receive_amt,
                            'LEFT_AMT'=>$receive_amt+$give_amt,
											      'CREATE_DATE'=> date('Y-m-d H:i:s',time()),
                            'REMARKS'=>"消费操作",
                        ];
                        $cardOP = new Card_operat_tab();
                        $r = $cardOP->cardOperatAdd($ccdata);
                        if($r){
                            $this->assign('odata',$odata);
                            return $this->fetch();
                        }
                    }
                }

            }else{
                $this->error('支付密码输入错误，请重新提交订单~',url('wx/order/orderDetails',['oid'=>$post['oid']]),1);
            }

        }
    }
    /**
     * 2人免单支付处理
     */
    public function changeOrderYesTwo($wx_order_no,$pay_type,$odata){
        $order_model = new Order_tab();
        //获取优惠等级
        $code = $this->getProjectPriceat($odata['PROJECT_CD'],$odata['STORE_CD']);
        //偶数免单
        if($code == 313 && $odata['CUSTOMER_TOTAL_QTY']%2==0){
            $condition = [];
            $condition['WX_ORDER_NO'] = $wx_order_no;
            $data = [];
            if($pay_type == 2){
                $data['PAY_AMT'] = 0;
            }else if($pay_type == 1){
                $data['PAY_AMT2'] = 0;
            }else if($pay_type == 3){
                $data['PAY_AMT'] = 0;
                $data['PAY_AMT3'] = 0;
            }
            $orderList = $order_model->orderListNpage($condition);
            foreach($orderList as $key=>$val){
                if($val['CUSTOMER_TOTAL_QTY']%2 && $val['IS_REFUND'] == 0){
                    $result = $order_model->updateOrederById($data,$val['ORDER_CD']);
                }
            }
        }

    }
    /**
     * 票券支付处理
     */
    public function createFinancePayData($post){
        $financeIssue_model = new Finance_issue_tab();
        if($post['FINANCE_SELL_PRICE'] && $post['FINANCE_ISSUE_ID']){
            //生成代金券更新数组
            $issue_ids = explode(',',$post['FINANCE_ISSUE_ID']);
            $dataFinance = [];
            foreach($issue_ids as $v){
                $dataFinance[] = [
                    'FINANCE_STATUS'=>1,
                    'FINANCE_ISSUE_ID'=>$v
                ];
            }
            //更新代金券
            $resultUpdate = $financeIssue_model->saveAll($dataFinance);
            //生成预约单更新数组
            $data = [
                'CONNECT_USER_NAME'=>$post['CONNECT_USER_NAME'],
                'CONNECT_TEL_NO'=>$post['CONNECT_TEL_NO'],
                'PAY_STS'=>1,
                //1是微信
                'PAY_TYPE'=>1,
                'PAY_TYPE3'=>1,
            //  'PAY_AMT'=>$post['SELL_PRICE'],
                'ORDER_AMT'=>$post['ORDER_AMT'],
                'PAY_FINANCE_TOTAL'=>$post['FINANCE_SELL_PRICE'],
                'FINANCE_ISSUE_IDS' => $post['FINANCE_ISSUE_ID'],
                'ORDER_STATUS'=>1,
                'PAY_DATE_TIME'=>date("Y-m-d h:i:s",time()),
            ];
            return $data;
        }
    }

    /**
     * 删除订单
     */
    public function delOrderOne(){
        $oid = input('oid');
        $order = new Order_tab();
        $res = $order->delOrderOne($oid);
        if($res){
            $this->redirect(WEB_URL."wx/order/orderList");
        }else{
            return "1";
        }
    }
    /**
     * 取消订单
     */
    public function delOrderByWx(){
        $oid = input('oid');
        $order = new Order_tab();
        $rtime = new Room_order_time_tab();
        $orderInfo = $order-> getOrderInfoByID($oid);
        $rtime_cds = explode(',',$orderInfo['RTIME_CD']);
        foreach($rtime_cds as $k=>$v){
            $delRoom = $rtime->where(['RTIME_CD'=>$v])->delete();
        }
        $res = $order->updateOrederByWxNO(array('AVAILABLE_FLG'=>0),$orderInfo['WX_ORDER_NO']);
        if($res){
            $this->redirect(WEB_URL."wx/order/orderList");
        }else{
            return "1";
        }
    }
    /**
     * 获取房间中的沙发数量
     */
    public function getSofa(){
        if(Request::instance()->isPost()){
            $rid = input('rid');
            $sid = input('sid');
            $pcd = input('pcd');
            $order_person = intval(input('order_person'));
            $orderPer = '';
            if(empty($rid) || empty($sid)){
                $data['state'] = 'fail';
                $data['msg'] = '无有效房间信息';
                echo json_encode($data);die();
            }
            $room_model = new Room_tab();
            $roomInfo = $room_model->getRoomOne($sid,$rid);

            if(!empty($roomInfo['SOFA_QTY'])){
                //$data['state'] = 'success';
                //$data['info'] = $roomInfo['SOFA_FREE_QTY'];
                //项目优惠信息
                $priceatCode = $this->getProjectPriceat($pcd,$sid);
                //最低预约人数
                $yushu = $roomInfo['SOFA_QTY']%2;
                $quzheng = intval($roomInfo['SOFA_QTY']/2);
               /*  if(!empty($yushu)){
                    $orderPer = intval($yushu)+intval($quzheng);
                }else{
                    $orderPer = $quzheng;
                } */
				$orderPer = 1;

                if($order_person > $roomInfo['SOFA_QTY']){
                    $data['state'] = 102;
                    $data['msg'] = '该房间可最多接纳'.$roomInfo['SOFA_QTY'].'人,请您分单预约';
                }else if($order_person < $orderPer){
                    $data['state'] = 103;
                    $data['msg'] = '该房间最低可接受'.$orderPer.'人预约';
                }else if($priceatCode == 313 && ($order_person == 1 || $order_person%2 > 0)){
                    $data['state'] = 313;
                    $data['msg'] = '当前已预约'.$order_person.'人，多加1人即可享受活动优惠。要放弃优惠继续付款吗？';
                }else{
                    $data['state'] = 200;
                    $data['msg'] = '恭喜您，你已经达到房间预约要求。';
                }
            }else{
                $data['state'] = 101;
                $data['msg'] = '获取房间信息失败';
            }
            echo json_encode($data);die();
        }
    }
    /**
     * ajax获取已预订的房间时间段
     */
    public function getRoomOrderTime(){
        if(Request::instance()->isPost()){
            $store_cd = input('store_cd');
            $room_cd = input('room_cd');
            $store_model = new Store_tab();
            $storeInfo = $store_model->getStoreOne($store_cd);
            if(!$store_cd || !$room_cd){
                $data['state'] = 'error';
                $data['msg'] = '获取参数不完整！';
                echo json_encode($data);die();
            }
            $rtime = new Room_order_time_tab();
            $condition = [
                'STORE_CD' => $store_cd,
                'ROOM_CD' => $room_cd,
                'AVAILABLE_FLG' => 1
            ];
            $list = $rtime->roomTimeList($condition);
            if(!empty($list)){
                foreach($list as $k=>$v){
                    $start_time[$k] = intval(strtotime(substr($v['ORDER_DATA'],0,10).' '.$v['ORDER_START_DATE_TIME']));
                    $zero_time[$k] = intval(strtotime(substr($v['ORDER_DATA'],0,10)));
				    $list[$k]['ORDER_test'] = $zero_time[$k].'---'.$start_time[$k].'--'.strtotime(substr($v['ORDER_DATA'],0,10).' '.$storeInfo['STORE_START_TIME']);			
                    if(($zero_time[$k]<=$start_time[$k]) && ($start_time[$k] < intval(strtotime(substr($v['ORDER_DATA'],0,10).' '.$storeInfo['STORE_START_TIME'])))){
                        $list[$k]['ORDER_DATA'] = $zero_time[$k]-24*3600;
						$list[$k]['op'] = 1;
                    }else {
						$list[$k]['op'] = 2;
                        $list[$k]['ORDER_DATA'] = strtotime(substr($v['ORDER_DATA'],0,10));
                    }
                    
                }
                $data['state'] = 'success';
                $data['list'] = $list;
            }else{
                $data['state'] = 'error';
                $data['msg'] = '无预定房间信息';
            }
            echo json_encode($data);die();
        }
    }
    /**
     * 获取某时段房间是否预定
     */
    public function isRoomTimeOrder(){
        if(Request::instance()->isPost()){
            $post = input();
            $time = new Time_set_rule_tab();
            $rtime = new Room_order_time_tab();


            $store_model = new Store_tab();
            if(!$post['sid']){
                $data['state'] = 'error';
                $data['msg'] = '未获取到门店信息';
                echo json_encode($data);die();
            }
            $storeInfo = $store_model->getStoreOne($post['sid']);

            if(!$post['time_cd']){
                $data['state'] = 'error';
                $data['msg'] = '未获取预定时间信息';
                echo json_encode($data);die();
            }
            if(!$post['rid'] || !$post['sid'] || !$post['order_date']){
                $data['state'] = 'error';
                $data['msg'] = '无有效房间\店铺\预定日期信息';
                echo json_encode($data);die();
            }

            $time_arr = explode(',',$post['time_cd']);
            $state = '';
            $msg = '';
            $order_date = '';
            foreach($time_arr as $v){
                $timeInfo = $time->getTimeInfo($v);

                $start_time = strtotime($post['order_date'].' '.$timeInfo['ORDER_START_DATE_TIME']);
                $zero_time = strtotime($post['order_date']);
                if(($zero_time<=$start_time) && ($start_time < strtotime($post['order_date'].' '.$storeInfo['STORE_START_TIME']))){
                    $order_date = date('Y-m-d',strtotime($post['order_date'])+24*3600);
                }else {
                    $order_date = $post['order_date'];
                }
                $condition = [
                    'ROOM_CD' => $post['rid'],
                    'STORE_CD' => $post['sid'],
                    'ORDER_DATA' => $order_date,
                    'ORDER_START_DATE_TIME' => $timeInfo['ORDER_START_DATE_TIME'],
                     'ORDER_END_DATE_TIME' => $timeInfo['ORDER_END_DATE_TIME'],
					'AVAILABLE_FLG'=>1
		   ];
                $num = $rtime->getRoomNoteNum($condition);
                if(empty($num)){
                   $state = 'success';
                   $msg = '无预定房间信息';
                }else{
                    $state = 101;
                    $msg .= '时段'.$timeInfo['ORDER_START_DATE_TIME'].'已有 '.$num.' 条房间预定信息<br />';
                    break;
                }
            }
            $data['state'] = $state;
            $data['msg'] = $msg;
            echo json_encode($data);die();
        }
    }
    /**
     * 获取日期的周显示
     */
    public function getWeekOrder($wtime){
        $str = '';
        switch($wtime){
            case '1':
                $str = '周一';
                break;
            case '2':
                $str = '周二';
                break;
            case '3':
                $str = '周三';
                break;
            case '4':
                $str = '周四';
                break;
            case '5':
                $str = '周五';
                break;
            case '6':
                $str = '周六';
                break;
            default:
                $str = '周日';
                break;
        }
        return $str;
    }
    /**
     *定期删除未付款订单
     */
    public function deleteOrderUnPay(){
       $order_model = new Order_tab();
       $rtime_model = new Room_order_time_tab();
       $condition = [];
       //5分钟前
       $startTime = time()-5*60;
       $condition['PAY_STS'] = 0;
       $condition['ORDER_DATE_TIMESTAMP'] = array('lt',$startTime);
       $condition['AVAILABLE_FLG'] = 1;
       $orderList = $order_model->orderListNpage($condition);
       $err_msg = '';
       $dataUp = [];
       foreach($orderList as $key=>$val){
           $dataUp[$key] = [
               'AVAILABLE_FLG'=>0,
               'ORDER_CD'=>$val['ORDER_CD'],
               'ORDER_FD'=>$val['ORDER_FD']
           ];
           $rtime_cds = explode(',',$val['RTIME_CD']);
           foreach($rtime_cds as $k=>$v){
               $delRoom = $rtime_model->where(['RTIME_CD'=>$v])->delete();
           }
       }	   
       $result = $order_model->saveAll($dataUp);
       if(!empty($result)){
           echo 'success';
       }else{
		   echo 'no order deleted';
	   }

    }
	/**
     * 查看我的预约码
     */
    public function viewOrderNO(){
        $orderCd = input('order_cd');
        $order_model = new Order_tab();
        $orderInfo = $order_model->getOrderInfoByID($orderCd);
        $this->assign('odata', $orderInfo);
        return $this->fetch('order/orderYes');
    }
    /**
     * 获取项目的优惠项目信息
     */
    private function getProjectPriceat($pcd,$sid){
        $project_model = new Project_tab();
        $priceat_model = new Project_plan_price_tab();
        $priceCate_model = new Price_plan_category_tab();
        //获取项目信息
        $projectInfo = $project_model->getStoreProjectOne($pcd,$sid);
        //获取项目优惠信息
        $priceatInfo = $priceat_model->getProjectPriceatInfo($projectInfo['PROJECT_ID']);
        if(!empty($priceatInfo)){
            //一级优惠信息
            if($priceatInfo['LEVEL_ONE']){
                $cateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_ONE']);
                $error = intval('31'.$cateInfo['LEVEL_TYPE']);
            }else if($priceatInfo['LEVEL_TWO']){//二级优惠
                $cateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_TWO']);
                $error = intval('32'.$cateInfo['LEVEL_TYPE']);
            }else if($priceatInfo['LEVEL_THREE']){//三级优惠
                $cateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_THREE']);
                $error = intval('33'.$cateInfo['LEVEL_TYPE']);
            }else if($priceatInfo['LEVEL_FOUR']){//四级优惠
                $cateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_FOUR']);
                $error = intval('34'.$cateInfo['LEVEL_TYPE']);
            }else{
                $error = 0;
            }
        }else{
            $error = 301;//无价格优惠
        }
        return $error;
    }
    /**
     * 获取项目是否符合优惠条件
     */
    private function getPriceatStateNum($stateCode,$odata,$priceatInfo){
        $priceCate_model = new Price_plan_category_tab();
        if($stateCode && $odata){
            switch($stateCode){
                case '311':
                    $day = date('Y-m-d');
                    $cateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_ONE']);
                    if($day == $cateInfo['LEVEL_RULE']){
                        return 'yes';
                    }else{
                        return 'no';
                    }
                    break;
                case '313':
                    $res = $odata['CUSTOMER_TOTAL_QTY']%2;
                    return ($res>0)?'no':'yes';
                    break;
                case '321':
                    $day = intval(date('d'));
                    $cateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_TWO']);
                    $days = explode(',',$cateInfo['LEVEL_RULE']);
                    return in_array($day,$days)?'yes':'no';
                    break;
                case '331':
                    return 'yes';
                case '341':
                    $cateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_TWO']);
                    $rooms = explode(',',$cateInfo['LEVEL_RULE']);
                    return in_array($odata['ROOM_CD'],$rooms)?'yes':'no';
                    break;
                case '301':
                    return 'no';
                    break;
            }
        }else{
            return 'no';
        }
    }
    /**
     * 评价
     */
    public function doEvaluate(){
        if(Request::instance()->isPost()){
            $post = input();
            $evaluate_model = new Evaluate_tab();
            $post['USER_ID'] = session('user_id');
            $remarkArr = [$post['STORE_MARK'],$post['PROJECT_MARK'],$post['ROOM_MARK']];
            $remarkArr = array_filter($remarkArr);
            //微信单号
            $wx_order_no = $post['WX_ORDER_NO'];
            unset($post['WX_ORDER_NO']);
            //判断此订单是否已评价
            $condition = [
                'USER_ID'=>session('user_id'),
                'ORDER_CD'=>$post['ORDER_CD']
            ];
            $evaluateNum = $evaluate_model->getEvaluateNum($condition);
            if($evaluateNum > 0){
                $data['state'] = 'fail';
                $data['msg'] = '该订单已评价，请勿重复评价！';
                echo json_encode($data);die();
            }
            if(!empty($remarkArr)){
                $post['REMARK'] = (array_sum($remarkArr))/count($remarkArr);
            }else{
                $post['REMARK'] = 0;
            }
            $post['CREATE_DATE'] = time();
            $result = $evaluate_model->evaluateAdd($post);
            if(!empty($result)){
                //更新订单状态
                $order_model = new Order_tab();
                $resultUpOrder = $order_model->updateOrederByWxNO(['ORDER_STATUS'=>11],$wx_order_no);
                $data['state'] = 'success';
                $data['msg'] = '评价成功！';
            }else{
                $data['state'] = 'fail';
                $data['msg'] = '评价失败！';
            }
            echo json_encode($data);die();

        }else{
            $order_cd = input('order_cd');
            $order_model = new Order_tab();
            $pro_model = new Project_tab();
            $store_model = new Store_tab();
            //预约单信息
            $orderInfo = $order_model->getOrderInfoByID($order_cd);
            //项目信息
            $projectInfo = $pro_model->getStoreProjectOne($orderInfo['PROJECT_CD'],$orderInfo['STORE_CD']);
            //门店信息
            $storeInfo = $store_model->getStoreOne($orderInfo['STORE_CD']);
            //项目图片
            $imgArr = explode(',',$projectInfo['PROJECT_IMAGE']);
            $this->assign('orderInfo',$orderInfo);
            $this->assign('storeInfo',$storeInfo);
            $this->assign('imgArr',$imgArr);
            $this->assign('project_id',$projectInfo['PROJECT_ID']);
            return $this->fetch();
        }
    }
    /**
     * 查询可用优惠券
     * return array
     */
    private function getFinanceIssueData(){
        $financeIssue_model = new Finance_issue_tab();
        $conditionIssue = [
           'a.AVAILABLE_FLG'=>1,
           'a.USER_ID'=>session('user_id'),
           'a.FINANCE_TYPE'=>3,
           'a.FINANCE_STATUS'=>0
        ];
        $conditionIssue['END_DATE'] = array('egt',date('Y-m-d H:i:s',time()));
        $conditionIssue['START_DATE'] = array('elt',date('Y-m-d H:i:s',time()));

       $data = $financeIssue_model->getFinanceIssueFlg($conditionIssue);

        return $data?$data:'';
    }
}