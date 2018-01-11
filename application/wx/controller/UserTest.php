<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 8:47
 */

namespace app\wx\controller;


use app\admin\model\Evaluate_tab;
use app\admin\model\Finance_type_tab;
use app\admin\model\Project_tab;
use app\wx\model\Card_operat_tab;
use app\admin\model\Finance_tab;
use app\admin\model\Finance_issue_tab;
use app\wx\model\Member_info_tab;
use app\wx\model\User_tab;
use app\admin\model\Recharge_money_category_tab;
use app\admin\model\Offline_card_tab;
use app\admin\model\Store_tab;
use think\Request;
use think\Validate;

class UserTest extends Base
{
    /**
     * 个人中心
     * */
    public function me(){
        $user = new User_tab();
        $data = $user->openidGetUserOne(session('openid'));
        if($data['TEL_NO']){
            $this->assign('data',$data);
            return $this->fetch();
        }else{
            $this->redirect(WEB_URL.'wx/home/binding');
        }
    }
    /**
     * 我的电子卡
     * */
    public function card(){

        if(strlen(session('openid'))>3){
            $user = new User_tab();
            $userid = $user->openidGetUserOne(session('openid'));
            if($userid['TEL_NO']){
                $card = new Member_info_tab();
                $data = $card->getUserCard(session('user_id'));
                $cardType = input('cardType');
                if(empty($cardType)){
                    $cardType = 'e-card';
                }
                //$data['RECEIVE_AMT'] = mb_substr($data['RECEIVE_AMT'], 0, mb_strlen($data['RECEIVE_AMT']) - 2);
                $data['TOTAL_AMT'] = $data['RECEIVE_AMT']+$data['GIVE_AMT'];
                //个人实体卡
                $condOffCard = [
                    'a.USER_ID'=>session('user_id'),
                    'a.AVAILABLE_FLG' => 1
                ];
                $offlineCard_model = new Offline_card_tab();
                $offlineCards = $offlineCard_model->getCardsFlg($condOffCard);
                if(!empty($offlineCards)){
                    foreach($offlineCards as $k=>$v){
                        $returnData = $this->getOfflineCardData($v['MEMBER_CARD_NO']);
                        if(!empty($returnData)){
                            $offlineCards[$k]['TOTAL_AMT'] = $returnData['ReceiveAmt'] + $returnData['GiveAmt'];
                        }
                    }

                }
                $this->assign('offlineCards',$offlineCards);
                $this->assign('cardType',$cardType);
                $this->assign('data',$data);
                return $this->fetch();
            }else{
                $this->redirect(WEB_URL.'wx/home/binding');
            }
        }else{
            $this->redirect(WEB_URL.'/wx/index/OAuth');
        }
    }
    /**
     * 个人资料
     * */
    public function userInfo(){
        if(strlen(session('openid'))>3){
            $user_model = new User_tab();
            $user = new User_tab();
            $userid = $user->openidGetUserOne(session('openid'));
            if($userid['TEL_NO']){
                $error = input('error');
                $error = $error?1:0;
                $this->assign('error',$error);
                $this->assign('userInfo',$userid);
                return $this->fetch();
            }else{
                $this->redirect(WEB_URL.'wx/home/binding');
            }
        }else{
            $this->redirect(WEB_URL."/wx/index/OAuth");
        }
    }
    /**
     * 我的票券
     * */
    public function userTicket(){
       if(strlen(session('openid'))>3){
            $user = new User_tab();
            $userid = $user->openidGetUserOne(session('openid'));
            if($userid['TEL_NO']){
                $financeType_model = new Finance_type_tab();
                $typeList = $financeType_model->getFinanceTypeFlg();
                $this->assign('typeList',$typeList);
                return $this->fetch();
            }else{
                $this->redirect(WEB_URL.'wx/home/binding');
            }
       }else{
            $this->redirect(WEB_URL."/wx/index/OAuth");
       }
    }
    /**
     * ajax动态获取我的票券信息
     */
    public function getFinanceList(){
        if(Request::instance()->isPost()){
            $fType = input('type');
            $fState = input('state');
            if(!empty($fType)){
                $financeIs_model = new Finance_issue_tab();
                $condition = [];
                $condition['a.FINANCE_TYPE'] = $fType;
                $condition['c.AVAILABLE_FLG'] = 1;
                $condition['a.AVAILABLE_FLG'] = 1;
                $condition['USER_ID'] = session('user_id');
                //票券使用状态
                if($fState == 1){//已使用
                    $condition['FINANCE_STATUS'] = $fState;
                }else if($fState == 0){//未使用
                    $condition['FINANCE_STATUS'] = 0;
                    $condition['END_DATE'] = array('egt',date('Y-m-d',time()));
                }else if($fState == 2){//已失效
                    $condition['END_DATE'] = array('lt',date('Y-m-d',time()));
                }
                $financeList = $financeIs_model->getFinanceIssueFlg($condition);
                if(!$financeList){
                    $data['state'] = '102';
                    $data['msg'] = '暂无相关票券信息';
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
     * 票券详情
     */
    public function financeIssueInfo(){
        $issue_id = input('issue_id');
        $financeIssue_model = new Finance_issue_tab();
        $issueInfo = $financeIssue_model->getFinanceIssueInfoById($issue_id);
        $this->assign('issueInfo',$issueInfo);
        return $this->fetch();
    }
    /**
     * 票券二维码
     */
    public function createQrCode()
    {
        include_once '/extend/phpqrcode/phpqrcode.php';
        $issue_id = input('issue_id');
        $value = 'http://www.baidu.com';//二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 5;//生成图片大小
        //生成二维码图片
        $qrcode = new \QRcode();
        $qrcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
        //  ROOT_PATH;die();
        $logo = ROOT_PATH.'public/static/images/logo.png';//准备好的logo图片
        $QR = 'qrcode.png';//已经生成的原始二维码图

        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);

        }
        header("Content-type: image/png");
        imagepng($QR);
    }
    /**
     * 我的评论
     *
     * */
    public function userReview(){
		/*$this->redirect(WEB_URL."/wx/home/getMore");*/
         if(strlen(session('openid'))>3){
            $user = new User_tab();
            $userid = $user->openidGetUserOne(session('openid'));
            if($userid['TEL_NO']){
                $evaluate_model = new Evaluate_tab();
                $condition = [
                    'a.USER_ID'=>session('user_id'),
                    'a.AVAILABLE_FLG'=>1
                ];
                //全部评价
                $evaluateAll = $evaluate_model->getEvaluateFlg($condition);
                foreach($evaluateAll as $kAll=>$vAll){
                    $dataAll = $this->getEvaluateDetailInfo($vAll);
                    $evaluateAll[$kAll] = $dataAll;
                  /*  $condReply = [
                        'a.PARENT_EVALUATE_ID'=>$vAll['EVALUATE_ID'],
                        'a.AVAILABLE_FLG'=>1
                    ];
                    $allReply = $evaluate_model->getEvaluateFlg($condReply);
                    $evaluateAll[$kAll]['REPLYS'] = $allReply;
                    print_r($evaluateAll);*/
                }
                //好评
                $condition['REMARK'] = array('in','3,4,5');
                $evaluateGood = $evaluate_model->getEvaluateFlg($condition);
                foreach($evaluateGood as $kGood=>$vGood){
                    $dataGood = $this->getEvaluateDetailInfo($vGood);
                    $evaluateGood[$kGood] = $dataGood;
                   /* $condReply = [
                        'a.PARENT_EVALUATE_ID'=>$vGood['EVALUATE_ID'],
                        'a.AVAILABLE_FLG'=>1
                    ];
                    $goodReply = $evaluate_model->getEvaluateFlg($condReply);
                    $evaluateAll[$kGood]['REPLYS'] = $goodReply;*/
                }
                //差评
                $condition['REMARK'] = array('in','1,2');
                $evaluateBad = $evaluate_model->getEvaluateFlg($condition);
                foreach($evaluateBad as $kBad=>$vBad){
                    $dataBad = $this->getEvaluateDetailInfo($vBad);
                    $evaluateBad[$kBad] = $dataBad;
                   /* $condReply = [
                        'a.PARENT_EVALUATE_ID'=>$vBad['EVALUATE_ID'],
                        'a.AVAILABLE_FLG'=>1
                    ];
                    $badReply = $evaluate_model->getEvaluateFlg($condReply);
                    $evaluateAll[$kBad]['REPLYS'] = $badReply;*/
                }
                $this->assign('evaluateAll',$evaluateAll);
                $this->assign('evaluateGood',$evaluateGood);
                $this->assign('evaluateBad',$evaluateBad);
                return $this->fetch();
            }else{
                $this->redirect(WEB_URL.'wx/home/binding');
            }
        }else{
            $this->redirect(WEB_URL."/wx/index/OAuth");
        }
    }
    public function unwrap(){
        $user = new User_tab();
        $userid = $user->openidGetUserOne(session('openid'));
        $data = [
            'TEL_NO'=>"",
            'HISTORY_TEL_NO'=>$userid['HISTORY_TEL_NO'].','.$userid['TEL_NO']
        ];
        $res = $user->updateUserOne($userid['USER_ID'],$data);
        if($res){
            $this->redirect(WEB_URL."wx/home/binding");
        }
    }
    /**
     * 绑定实体卡
     */
    public function bindOfflineCard(){
        if(Request::instance()->isPost()){
            $cardNo = input('card_no');
            $pwd = input('pwd');
            //接口获取实体卡信息
            $cardOffline = $this->getOfflineCardData($cardNo);			
            $offCard_model = new Offline_card_tab();
            //输入是否完整或是否已绑定
            if(!$cardNo || !$pwd){
                $data['state'] = 106;
                $data['msg'] = '您输入的信息不完整，请输入卡号和密码！';
                echo json_encode($data);die();
            }else{
                $isBindCon = [
                    'MEMBER_CARD_NO'=>$cardNo
                ];
                $num = $offCard_model->getCardNum($isBindCon);
                if($num > 0){
                    $data['state'] = 107;
                    $data['msg'] = '您已经绑定过该卡，请勿重复绑定！';
                    echo json_encode($data);die();
                }
            }
            if(empty($cardOffline)){
                $data['state'] = 101;
                $data['msg'] = '该会员卡不存在，请核对您的会员卡信息';
            }else if($cardOffline['MemberPass'] != substr(md5($pwd),6,16)){
                $data['state'] = 102;
                $data['msg'] = '您输入的会员卡密码有误，请核对后再次输入！';
            }else if($cardOffline['MemberPass'] == substr(md5($pwd),6,16) && $cardOffline['MemberCardNo'] == $cardNo){
                $saveData = [
                    'USER_ID'=>session('user_id'),
                    'MEMBER_CARD_NO'=>$cardNo,
                    'CREATE_DATE'=>time(),
                    'UPDATE_DATE'=>time(),
                    'AVAILABLE_FLG'=>1,
                    'STORE_NAME'=>$cardOffline['StoreName'],
                    'STORE_CD'  =>$cardOffline['StoreCd']
                ];
                $result = $offCard_model->cardAdd($saveData);
				
                if(!empty($result)){
                    $data['state'] = 100;
                    $data['msg'] = '恭喜您，绑卡成功！';
                }else{
                    $data['state'] = 105;
                    $data['msg'] = '绑卡失败！';
                }
            }else{
                $data['state'] = 104;
                $data['msg'] = '异常错误！';
            }
            echo json_encode($data);die();
        }

    }
    //解绑实体卡
    public function unbindOffCard(){
        $offcard_id = input('OFFLINE_CARD_ID');
        $offcard_model = new Offline_card_tab();
        if(!$offcard_id){
            $this->error('您未选择需要解绑的卡片');
        }else{
            $result = $offcard_model->where(['OFFLINE_CARD_ID'=>$offcard_id])->delete();
            if($result){
                $this->redirect(url('wx/user_test/card'));
            }else{
                $this->error('解绑失败');
            }
        }
    }
    /**
     * 電子卡充值
     */
    public function cardCharge(){
        $card_model = new Member_info_tab();
        $user_id = session('user_id');
        $cardInfo = $card_model->getUserCard(session('user_id'));
        $category_model = new Recharge_money_category_tab();
        $condition = [];
        $condition['AVAILABLE_FLG'] = 1;
        $list = $category_model-> getRechargeListNP($condition);
        //充值订单号
        $recharge_no = time().rand('1000','9999');
        $this->assign('card_no',$cardInfo['MEMBER_CARD_NO']);
        $this->assign('recharge_no',$recharge_no);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 礼品卡卡充值
     */
    public function giftcardCharge(){
        $cardModel = new \app\admin\model\Recharge_money_category_tab();
        $user_id = session('user_id');
        $category_model = new Recharge_money_category_tab();
        $condition = [];
        $condition['AVAILABLE_FLG'] = 1;
        $list = $category_model-> getGiftRechargeListNP($condition);
        //充值订单号
        $recharge_no = time().rand('1000','9999');
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 设置微信电子卡充值金额
     */
    public function ajaxGetRechargeOrder(){
        $post = input();
        //电子卡信息
        $member_card = new Member_info_tab();
        $cardInfo = $member_card->getUserCard(session('user_id'));
        //会员信息
        $user_model = new User_tab();
        $user_info = $user_model->idGetUserOne(session('user_id'));
        //充值金额
        $recharge_money = $post['recharge_money'];
        if(!$recharge_money){
            $data['state'] = 'error';
            $data['msg'] = '无充值金额信息';
            echo json_encode($data);die();
        }
        $type = '电子卡充值';
        $jsApiParameters = $this->wxPayDeal($post['card_no'],$post['recharge_no'],$recharge_money,$type,$user_info['OPENID']);
        if($jsApiParameters){
            $data['state'] = 'success';
            $data['info'] = array(json_decode($jsApiParameters));
        }else{
            $data['state'] = 'error';
            $data['msg'] = '无微信订单信息生成！';
        }
        echo json_encode($data);die();
    }

    /**
     * 设置微信礼品卡卡充值金额
     */
    public function ajaxGetGiftRechargeOrder(){
        $post = input();
        //会员信息
        $user_model = new User_tab();
        $user_info = $user_model->idGetUserOne(session('user_id'));
        //充值金额
        $total_money = $post['total_money'];
        if(!$total_money){
            $data['state'] = 'error';
            $data['msg'] = '无充值金额信息';
            echo json_encode($data);die();
        }
        $type = '购买礼品卡';
        $jsApiParameters = $this->wxPayDeal($post['card_no'],$post['recharge_no'],$total_money,$type,$user_info['OPENID']);
        if($jsApiParameters){
            $data['state'] = 'success';
            $data['info'] = array(json_decode($jsApiParameters));
        }else{
            $data['state'] = 'error';
            $data['msg'] = '无微信订单信息生成！';
        }
        echo json_encode($data);die();
    }

    /**
     * 生成微信订单
     * @param $card_no
     * @param $recharge_no
     * @param $recharge_money
     * @param $type
     * @param $openId
     * @return mixed
     */
    public function wxPayDeal($card_no,$recharge_no,$recharge_money,$type,$openId){
        //微信支付
        ini_set('date.timezone','Asia/Shanghai');
        include_once '/extend/weixinPay/lib/WxPay.Api.php';
        include_once '/extend/weixinPay/pay/log.php';
        include_once '/extend/weixinPay/lib/WxPay.JsApiPay.php';
        //初始化日志
        $logHandler= new \CLogFileHandler(EXTEND_PATH."weixinPay/logs/".date('Y-m-d').'.log');
        $log = \Log::Init($logHandler, 15);
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($card_no);//设置商品或支付单简要描述
        $input->SetOut_trade_no($recharge_no);//设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
        $input->SetTotal_fee(intval($recharge_money*100));//设置订单总金额，单位为分，只能为整数，详见支付金额
        $input->SetGoods_tag($type);//设置标记
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order =  \WxPayApi::unifiedOrder($input);
        $tools = new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($order);
        return $jsApiParameters;
    }
    //充值处理
    public function doRecharge(){
        if(Request::instance()->isPost()){
            $post = input();
            $user_model = new User_tab();
            $card_model = new Member_info_tab();
            $card_operate_model = new Card_operat_tab();
            $cardInfo = $card_model->getUserCard(session('user_id'));
            $userInfo = $user_model->idGetUserOne(session('user_id'));
            if(empty($post['gift_money'])){
                $post['gift_money'] = 0;
            }
            //累积折扣
            $market_rat = ($post['recharge_money']+$cardInfo['RECEIVE_AMT'])/ ($post['recharge_money']+$post['gift_money']+$cardInfo['RECEIVE_AMT']);
            $financial_rat = ($post['recharge_money']+$cardInfo['RECEIVE_AMT'])/($cardInfo['RECEIVE_AMT']+$post['recharge_money']+$post['gift_money']+$cardInfo['GIVE_AMT']);
            //操作表折扣记录
            $disc_market_rat = $post['recharge_money']/($post['recharge_money']+$post['gift_money']);
            if($post['recharge_money'] && $post['recharge_no'] && $post['card_no']){
                //电子卡记录表
                $receive_amt = $post['recharge_money']+$cardInfo['RECEIVE_AMT'];
                $give_amt = $post['gift_money']?($post['gift_money']+$cardInfo['GIVE_AMT']):$cardInfo['GIVE_AMT'];
                $dataCard = [
                    'RECEIVE_AMT' => $receive_amt,
                    'GIVE_AMT' => $give_amt,
                    'TOTAL_CONSUMP_AMT' => $cardInfo['TOTAL_CONSUMP_AMT']+$post['recharge_money'],
                    'TOTAL_GIVE_AMT' => $cardInfo['TOTAL_GIVE_AMT']+$post['gift_money'],
                    'UPDATE_USER' => session('user_id'),
                    'CREATE_DATE' => date('Y-m-d H:i:s',time()),
                    'CARD_DISC_RATE_MARKET'=>$disc_market_rat,
                    'TOTAL_CONSUMP_TIMES' => $cardInfo['TOTAL_CONSUMP_TIMES']+1,
                    'CARD_DISC_RATE_FINANCE'=>$financial_rat
                ];
                $dataOperate = [
                    'CARD_NO' => $post['card_no'],
                    'USER_ID' => session('user_id'),
                    'CARD_OPERAT_TYPE' => '充值',
                    'CARD_TYPE' => $cardInfo['CARD_TYPE'],
                    'MEMBER_NAME' => $userInfo['NICK_NAME'],
                    'MEMBER_TEL' => $userInfo['TEL_NO'],
                    'MEMBER_SEX' => $userInfo['SEX'],
                    'CONSUMP_AMT' => $post['recharge_money'],
                    'GIVE_AMT' => $post['gift_money'],
                    'AFTER_CONSUMP_AMT' => $receive_amt,
                    'AFTER_GIVE_AMT' => $give_amt,
                    'LEFT_AMT' => $receive_amt+$give_amt,
                    'DISC_RATE_MARKET' => $disc_market_rat,
                    'TOTAL_DISC_RATE_MARKET' => $disc_market_rat,
                    'DISC_RATE_FINANCE' => $financial_rat,
                    'TOTAL_DISC_RATE_FINANCE' => $financial_rat,
                    'RELATE_CARD_CD' => $post['recharge_no'],
                    'INVOICE_FLG' => 0,
                    'AVAILABLE_FLG' => 1,
                    'REMARKS'=>'充值操作',
                    'CREATE_USER' => session('user_id'),
                    'UPDATE_USER' => session('user_id'),
                    'CREATE_DATE' => date('Y-m-d H:i:s',time()),
                    'UPDATE_DATE' => date('Y-m-d H:i:s',time())
                ];
                $resultCard = $card_model->updataCard($dataCard,$post['card_no']);
                $resultOperate = $card_operate_model->cardOperatAdd($dataOperate);
                if($resultCard && $resultOperate){
                    $this->redirect(url('wx/user_test/rechargeList'));
                }else{
                    $this->error('充值失败！');
                }
            }else{
                $this->error('传入参数不完整');
            }
        }
    }

    /**
     * 购买礼品卡
     */
    public function doGiftcardCharge(){
        if(Request::instance()->isPost()){
            $post = Request::instance()->param();
            if(!$post['RECHARGE_MONEY'] || !$post['NUM'] || !$post['RECHARGE_NO'] || !$post['GIFT_MONEY']){
                $this->error('参数不完整');
            }
            $user_id = session('user_id');
            $res = (new \app\wx\base\GiftcardCharge())->dealGiftcardCharge($post['RECHARGE_MONEY'],$post['GIFT_MONEY'],$post['NUM'],$user_id,$post['RECHARGE_NO']);
            if($res){
                $this->redirect(url('wx/user_test/card'));
            }else{
                $this->error('购买礼品卡失败！');
            }
        }
    }
    //电子卡充值明细
    public function rechargeList(){
        $card_operate_model = new Card_operat_tab();
        $condition = [
            'USER_ID' => session('user_id'),
            'CARD_OPERAT_TYPE' => '充值',
        ];
        $list = $card_operate_model->getCardOperateList($condition);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //电子卡消费明细
    public function costList(){
        $card_operate_model = new Card_operat_tab();
        $condition = [
            'USER_ID' => session('user_id'),
            'CARD_OPERAT_TYPE' => array('in',['消费','充值']),
            'GIVE_AMT'=>['GT',0]
        ];
        $list = $card_operate_model->getCardOperateList($condition);
        foreach($list as $k=>$v){
            $list[$k]['COST_AMT'] = $v['GIVE_AMT']+$v['CONSUMP_AMT'];
            $list[$k]['CardOperatType'] = $v['CARD_OPERAT_TYPE'];
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    //实体消费明细
    public function offCardCostList(){
        $url = 'http://47.93.240.77:8887/api/QTGroup/GetCardOperat';
		//$url = 'http://47.95.230.158:8888/api/QTGroup/GetCardOperat';
        $memberCardNo = input('MEMBER_CARD_NO');		
        $postData = ['memberCardNo'=>$memberCardNo];
        $jsonStr = json_encode($postData);
        $data = http_post_json($url,$jsonStr);
        list($code_state,$dataEntityS) = $data;		
        $datas = [];
        if($code_state == 200){
            $resData = json_decode($dataEntityS);
            if($resData->Total >=1){
                $datas = object_array($resData->AppendData);				
                foreach($datas as $k=>$v){
                    $datas[$k]['COST_AMT'] = abs($v['ConsumpAmt']+$v['GiveAmt']);
                    $datas[$k]['CREATE_DATE'] = $v['BusinessDate'];				
					switch($v['CardOperatType']){
                        case 1:
                            $datas[$k]['CardOperatType'] = '开卡';
                            break;
                        case 2:
                            $datas[$k]['CardOperatType'] = '充值';
                            break;
                        case 14:
                            $datas[$k]['CardOperatType'] = '刷卡消费';
                            break;
                        case 16:
                            $datas[$k]['CardOperatType'] = '充值退回';
                            break;
                        case 17:
                            $datas[$k]['CardOperatType'] = '消费退回';
                            break;
                    }	
                }
            }
        }

        $this->assign('list',$datas);       
        return $this->fetch('user/costList');
    }
    /**
     * 上传图片处理
     */
    public function uploadImage(){
        $typeArr = array("jpg", "png", "gif");//允许上传文件格式
        $path = ROOT_PATH . 'public/uploads/'.date('Ymd').'/';//上传路径
        if(!is_dir($path)){
            dir(ROOT_PATH . 'public' . DS . 'uploads');
            mkdir(ROOT_PATH . 'public' . DS . 'uploads/'.date('Ymd'));
        }
        if (isset($_POST)) {
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $name_tmp = $_FILES['file']['tmp_name'];
            if (empty($name)) {
                echo json_encode(array("error"=>"您还未选择图片"));
                exit;
            }
            $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型

            if (!in_array($type, $typeArr)) {
                echo json_encode(array("error"=>"清上传jpg,png或gif类型的图片！"));
                exit;
            }
            if ($size > (500 * 1024)) {
                echo json_encode(array("error"=>"图片大小已超过500KB！"));
                exit;
            }

            $pic_name = time() . rand(10000, 99999) . "." . $type;//图片名称
            $pic_url = $path . $pic_name;//上传后图片路径+名称
            $return_pic = 'public/uploads/'.date('Ymd').'/'.$pic_name;
            if (move_uploaded_file($name_tmp, $pic_url)) { //临时文件转移到目标文件夹
                echo json_encode(array("error"=>"0","pic"=>$return_pic,"name"=>$pic_name));
            } else {
                echo json_encode(array("error"=>"上传有误，清检查服务器配置！"));
            }
        }
    }
    /**
     * 修改用户信息
     */
    public function userPost(){
        $post = input();
        //验证
        $rule = [
            'NICK_NAME' => 'require',
            'ID_NO' => 'require',
            'BIRTH_DATE' => 'require'
        ];
        $error = $this->validateInput($rule,$post);
        if($error){
            $this->error($error);
        }

        if(isset($post['PHOTO_HEAD'])){
            $post['PHOTO_HEAD'] = '/'.$post['PHOTO_HEAD'];
        }
        $user_model = new User_tab();
        $result = $user_model->updateUserOne(session('user_id'),$post);
        if(!empty($result)){
            $this->redirect(url('wx/user_test/me'));
        }else{
            $this->redirect(url('wx/user_test/userInfo',['error'=>'修改个人信息失败！']));
        }
    }
    /**
     * 验证表单
     */
    private function validateInput($rule,$data){
        $validate = new Validate($rule);
        if (!$validate->check($data)) {
           return $validate->getError();
        }
    }
    /**
     * 获取实体会员卡信息
     */
    private function getOfflineCardData($memberCardNo){
        $url = 'http://47.93.240.77:8887/api/QTGroup/GetCardInfo';
		// $url = 'http://47.95.230.158:8888/api/QTGroup/GetCardInfo';
        $postData = ['memberCardNo'=>$memberCardNo];
        $jsonStr = json_encode($postData);		
        $dataR = http_post_json($url,$jsonStr);		
        list($code_state,$dataEntityS) = $dataR;		
        $resData = json_decode($dataEntityS);		
        $datas = object_array($resData->AppendData);
        return $datas?$datas[0]:'';
    }
    /**
     * 遍历获取评价的项目信息
     */
    private function getEvaluateDetailInfo($info){
        $pro_model = new Project_tab();
        $evaluate_model = new Evaluate_tab();
        if(!empty($info)){
            $proInfo = $pro_model->getProjectInfoByPID($info['PROJECT_ID']);
            //项目图片
            $imgArr = explode(',',$proInfo['PROJECT_IMAGE']);
            $info['PROJECT_IMAGE'] = $imgArr?('/'.$imgArr[0]):'';
            //回复信息
            $condition = [
                'PARENT_EVALUATE_ID' => $info['EVALUATE_ID'],
                'a.AVAILABLE_FLG' => 1
            ];
            $replyData = $evaluate_model->getEvaluateFlg($condition);
            if(!empty($replyData)){
                $info['REPLYS'] = $replyData;
            }
            return $info;
        }
        return [];
    }

	/**
     * 二维码及条形码
     */
    public function offlinecardcode(){
        if(strlen(session('openid'))>3){
            $cardNo = Request::instance()->param('memberCardNo');
            if(!$cardNo){
                $this->error('未提供有效会员卡号',url('wx/user_test/card'));
            }
            $cardData = (new Offline_card_tab())->getCardInfoByCardNo($cardNo);
            if(!$cardData){
                $this->error('该会员信息不存在',url('wx/user_test/card'));
            }
            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            //条形码
            $barcode = $generator->getBarcode($cardData['MEMBER_CARD_NO'], $generator::TYPE_CODE_128);
            $barImg =  '<img class="barcode" src="data:image/png;base64,' . base64_encode($barcode) . '">';
            //二维码 
            $qrcode = (new \app\wx\base\Membercard())->buildQRcode($cardData['MEMBER_CARD_NO']);
            $qrImg =  '<img class="qrcode" src="data:image/png;base64,' . base64_encode($qrcode) . '">';
            //使用规则
            $storeData = (new Store_tab())->getStoreOne($cardData['STORE_CD']);
            $rule = $storeData['OFFLINE_CARD_RULE'];
            $list = ['barImg'=>$barImg,'qrImg'=>$qrImg,'rule'=>$rule,'cardNo'=>$cardNo];
            $this->assign($list);
            return $this->fetch();
        }else{
            $this->redirect(WEB_URL.'/wx/index/OAuth');
        }
    }
}