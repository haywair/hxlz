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
use app\admin\model\Store_tab;
use app\wx\model\Card_operat_tab;
use app\wx\model\Member_info_tab;
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
                    $dataGroup[$k]['SELL_PRICE'] = round($datas[0]['ORDER_AMT'],2);
                    if($datas[0]['ORDER_STATUS']==0){
                        $img[$k] = explode(',',$pdata['PROJECT_IMAGE']);
                        $dataGroup[$k]['PROJECT_IMAGE'] =  $img[$k][0];
                        $dataGroup[$k]['PROJECT_INTRODUCE'] = $pdata['PROJECT_INTRODUCE'];
                        //存储计时器
                        $dataStime[] = [
                           'order_cd' =>$datas[0]['ORDER_CD'],
                           'extra_time' => $datas[0]['ORDER_DATE_TIMESTAMP']+5*60-time()
                        ];
                        $non_payment[] = $datas[0];
                    }

                    if($datas[0]['ORDER_STATUS']==1 || $datas[0]['ORDER_STATUS']==7){
                        $img[$k] = explode(',',$pdata['PROJECT_IMAGE']);
                        $dataGroup[$k]['PROJECT_IMAGE'] =  $img[$k][0];
                        $dataGroup[$k]['PROJECT_INTRODUCE'] = $pdata['PROJECT_INTRODUCE'];
                        $unused[] = $datas[0];
                        $con_costed = [
                            'WX_ORDER_NO'=>$datas[0]['WX_ORDER_NO'],
                            'ORDER_STATUS'=>7
                        ];
                        $costedNum = $order->getOrdersNum($con_costed);
                        $dataPay[] = [
                            'order_cd'=>$datas[0]['ORDER_CD'],
                            'order_no' => $datas[0]['ORDER_NO'],
                            'noCost_num'=> $datas[0]['CUSTOMER_TOTAL_QTY']-$costedNum
                        ];
                    }

                    if($datas[0]['ORDER_STATUS']==4||$datas[0]['ORDER_STATUS']==8){
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
            foreach($time_arr as $k=>$v){
                $timeInfo = $time->getTimeInfo($v);
                //预约单表时间
                $order_start_date_time[] =  $timeInfo['ORDER_START_DATE_TIME'];
                $order_end_date_time =  $timeInfo['ORDER_END_DATE_TIME'];
                $time_start = strtotime($post['ORDER_DATE'].' '.$timeInfo['ORDER_END_DATE_TIME']);
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
                    'ORDER_END_DATE_TIME' =>  $timeInfo['ORDER_END_DATE_TIME']
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
            //2人免单
            /*if($post['CUSTOMER_QTY'] == 2){
                $data = $this->changeOrderOne($wx_order_no,$order_no,$udata,$sdata,$post,$rdata,$order_date_arr,$pdata, $order_start_date_time,$order_end_date_time,$rtime_cd);
            }else {
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
                        'ORDER_AMT' => $pdata['PRICE']*$post['CUSTOMER_QTY'],
                        'RTIME_CD' => implode(',', $rtime_cd)
                    ];
                }
            }*/
            $res = $order->saveAll($data);
            if($res){
                $odata = $order->orderListNpage(array('WX_ORDER_NO'=>$wx_order_no),'1');
               // $odata[0]['ORDER_AMT'] = $odata[0]['SELL_PRICE']*$odata[0]['CUSTOMER_TOTAL_QTY'];
               // $odata[0]['MARK_PRICE'] = mb_substr($odata[0]['MARK_PRICE'] , 0, mb_strlen($odata[0]['MARK_PRICE'] ) - 5);
               // $odata[0]['SELL_PRICE'] = mb_substr($odata[0]['SELL_PRICE'] , 0, mb_strlen($odata[0]['SELL_PRICE'] ) - 5);
                $odata[0]['ORDER_AMT'] = round($odata[0]['ORDER_AMT'],2);
                $this->assign('orderAmt',$odata[0]['ORDER_AMT']);
                $this->assign('odata',$odata[0]);
                return $this->fetch();
            }else{
                $this->error('未知原因失败了！请重新下单！');
            }
        }else{
            $odata = $order->getOrderInfoByID($post['oid']);
           // $odata['ORDER_AMT'] = $odata['SELL_PRICE']*$odata['CUSTOMER_TOTAL_QTY'];
           // $odata['MARK_PRICE'] = mb_substr($odata['MARK_PRICE'] , 0, mb_strlen($odata['MARK_PRICE'] ) - 5);
           // $odata['SELL_PRICE'] = mb_substr($odata['SELL_PRICE'] , 0, mb_strlen($odata['SELL_PRICE'] ) - 5);
           // $odata['CUSTOMER_QTY'] = $odata['CUSTOMER_TOTAL_QTY'];
            $odata['ORDER_AMT'] = round($odata['ORDER_AMT'],2);
            $this->assign('orderAmt',$odata['ORDER_AMT']);
            $this->assign('odata',$odata);
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
        //一级优惠
        if(!empty($priceatInfo['LEVEL_ONE'])){
            //优惠类别详情
            $pCateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_ONE']);
            //预约日
            $day = $order_date_arr[0];
            if($pCateInfo['LEVEL_TYPE'] == 1 &&  $day == $pCateInfo['LEVEL_RULE']){//特定日期优惠
                $sell_price = $pdata['PRICE'] - $priceatInfo['LEVEL_ONE_SALE_PRICE'];
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
                }else{
                    $sell_price = $pdata['PRICE'];
                }
            }else{
                $sell_price = $pdata['PRICE'];
            }
        }else if(!empty($priceatInfo['LEVEL_THREE'])){//三级会员优惠
            $sell_price = $pdata['PRICE'] - $priceatInfo['LEVEL_THREE_SALE_PRICE'];
        }else if(!empty($priceatInfo['LEVEL_FOUR'])){//四级优惠
            $pCateInfo = $priceCate_model->getPriceatCategoryInfo($priceatInfo['LEVEL_FOUR']);
            //特定房间优惠
            if($pCateInfo['LEVEL_TYPE'] == 2){
                $roomArr = explode(',',$pCateInfo['LEVEL_RULE']);
                if(in_array($post['ROOM_CD'],$roomArr)){
                    $sell_price = $pdata['PRICE'] - $priceatInfo['LEVEL_FOUR_SALE_PRICE'];
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
                'SELL_PRICE' => $sell_price,
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
        return $data;
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
            }else{
                $is_refund = 1;
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
        return $data;
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
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $getData = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        //parent::write_log('order/order_wx_payment',$getData);
        if (empty($getData['out_trade_no'])){
                $data = array('return_code' => 'FAIL', 'return_msg' =>'参数错误' );
                json_encode($data);
        }
        if (!empty($getData['result_code']) || $getData['result_code'] =='SUCCESS') {
            //LinshiOrderMaster::confirmPayment($getData['order_code&'], $getData['total_fee&'] / 100, '',  $getData['transaction_id']);
            $data = array('return_code' => 'SUCCESS', 'return_msg' => '支付成功');
            json_encode($data);
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
        //微信支付
        if($pay_type_num == 1 && $post['PAY_TYPE'][0] == 2){
            $data = [
                'CONNECT_USER_NAME'=>$post['CONNECT_USER_NAME'],
                'CONNECT_TEL_NO'=>$post['CONNECT_TEL_NO'],
                'PAY_STS'=>1,
                //1是微信
                'PAY_TYPE'=>1,
                'PAY_AMT'=>$post['SELL_PRICE'],
                'ORDER_AMT'=>$post['ORDER_AMT'],
                'ORDER_STATUS'=>1,
                'PAY_DATE_TIME'=>date("Y-m-d h:i:s",time()),
            ];
            $res = $order->updateOrederByWxNO($data,$post['WX_ORDER_NO']);
            //2人免单更新
            $res_two = $this->changeOrderYesTwo($post['WX_ORDER_NO'],$post['PAY_TYPE'][0]);
            //生成预约码并返回页面
            if($res){
                $odata = $order->getOrderInfoByID($post['oid']);
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
                        //2人免单更新
                        $res_two = $this->changeOrderYesTwo($post['WX_ORDER_NO'],$post['PAY_TYPE'][0]);
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
    public function changeOrderYesTwo($wx_order_no,$pay_type){
        $order_model = new Order_tab();
        $condition = [];
        $condition['WX_ORDER_NO'] = $wx_order_no;
        $data = [];
        if($pay_type == 2){
            $data['PAY_AMT'] = 0;
        }else if($pay_type == 1){
            $data['PAY_AMT2'] = 0;
        }
        $orderList = $order_model->orderListNpage($condition);
        foreach($orderList as $key=>$val){
            if($val['CUSTOMER_TOTAL_QTY'] ==2 && $val['IS_REFUND'] == 0){
                $result = $order_model->updateOrederById($data,$val['ORDER_CD']);
            }
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
            $order_person = input('order_person');
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
                //最低预约人数
                $yushu = $roomInfo['SOFA_QTY']%2;
                $quzheng = intval($roomInfo['SOFA_QTY']/2);
                if(!empty($yushu)){
                    $orderPer = intval($yushu)+intval($quzheng);
                }else{
                    $orderPer = $quzheng;
                }

                if($order_person > $roomInfo['SOFA_QTY']){
                    $data['state'] = 102;
                    $data['msg'] = '该房间可最多接纳'.$roomInfo['SOFA_QTY'].'人,请您分单预约';
                }else if($order_person < $orderPer){
                    $data['state'] = 103;
                    $data['msg'] = '该房间最低可接受'.$orderPer.'人预约';
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
                    $start_time = strtotime($v['ORDER_DATA'].' '.$v['ORDER_START_DATE_TIME']);
                    $zero_time = strtotime($v['ORDER_DATA']);
                    if(($zero_time<=$start_time) && ($start_time < strtotime($v['ORDER_DATA'].' '.$storeInfo['STORE_START_TIME']))){
                        $list[$k]['ORDER_DATA'] = strtotime($v['ORDER_DATA'])-24*3600;
                    }else {
                        $list[$k]['ORDER_DATA'] = strtotime($v['ORDER_DATA']);
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
            foreach($time_arr as $v){
                $timeInfo = $time->getTimeInfo($v);
                $condition = [
                    'ROOM_CD' => $post['rid'],
                    'STORE_CD' => $post['sid'],
                    'ORDER_DATA' => $post['order_date'],
                    'ORDER_START_DATE_TIME' => $timeInfo['ORDER_START_DATE_TIME'],
                    'ORDER_END_DATE_TIME' => $timeInfo['ORDER_END_DATE_TIME']
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
       //15分钟前
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
       }

    }
	/**
     * 查看我的预约码
     */
    public function viewOrderNO(){
        $orderCd = input('order_cd');
        $order_model = new Order_tab();
        $orderInfo = $order_model->getOrderInfoByID($orderCd);
        $this->assign('odata', $orderInfo['ORDER_NO']);
        return $this->fetch('order/orderYes');
    }
}