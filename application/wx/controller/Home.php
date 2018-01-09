<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 15:03
 */

namespace app\wx\controller;

use app\admin\model\Project_tab;
use app\admin\model\Finance_tab;
use app\admin\model\Finance_issue_tab;
use app\admin\model\Store_tab;
use app\wx\model\Card_operat_tab;
use app\wx\model\Carousel;
use app\wx\model\Carousel_figure_tab;
use app\wx\model\Member_info_tab;
use app\admin\model\Project_type_tab;
use app\admin\model\Order_tab;
use app\admin\model\Project_plan_price_tab;
use app\wx\model\User_tab;
use think\Db;
use think\Request;

class Home extends Base
{
    /**
     * 待改进，判断用户是否已经存入OPENID，如果没有先执行一次OA
     *
     *
     * 微信登录验证存入用户基本信息
     * */
    public function index(){	



	
        $wx = new \Wechat(self::$options);
        $aa = $wx->getOauthAccessToken();
        $access_token = $aa['access_token'];
        $openid = $aa['openid'];
        $data = $wx->getOauthUserinfo($access_token,$openid);
        $user = new User_tab();
        $openId = $user->openidGetUserOne($data['openid']);
        session('user_id',$openId['USER_ID']);
        if(!$openId){
            $dataUser = [
                'OPENID' => $data['openid'],
                'PHOTO_HEAD'=> $data['headimgurl'],
                'NICK_NAME'=> $data['nickname'],
                'SEX'   =>  $data['sex'],
                'CREATE_DATE'=>date("Y-m-d h:i:s",time()),
                'UPDATE_DATE' => date("Y-m-d h:i:s",time()),
            ];
            $res = $user->addUserOpenid($dataUser);
            if($res){
                session('openid',$data['openid']);
                //return $this->fetch();
                $this->redirect(WEB_URL."wx/home/home");
            }

        }else{
            $dataUser = [
                'OPENID' => $data['openid'],
                'PHOTO_HEAD'=> $data['headimgurl'],
                'NICK_NAME'=> $data['nickname'],
                'SEX'   =>  $data['sex'],
                'UPDATE_DATE' => date("Y-m-d h:i:s",time()),
            ];
            $res = $user->updateUserOne($openId['USER_ID'],$dataUser);
            if($res){
                session('openid',$data['openid']);
                //return $this->fetch();
                $this->redirect(WEB_URL."wx/home/home");
            }
        }
    }
    /*    public function index2(){
            $wx = new \Wechat(self::$options);
            $aa = $wx->getOauthAccessToken();
            $access_token = $aa['access_token'];
            $openid = $aa['openid'];
            $data = $wx->getOauthUserinfo($access_token,$openid);
            $user = new User_tab();
            $openId = $user->openidGetUserOne($data['openid']);
            session('openid',$data['openid']);
            if(!$openId){
                $dataUser = [
                    'OPENID' => $data['openid'],
                    'PHOTO_HEAD'=> $data['headimgurl'],
                    'NICK_NAME'=> $data['nickname'],
                    'SEX'   =>  $data['sex'],
                    'CREATE_DATE'=>date("Y-m-d h:i:s",time()),
                    'UPDATE_DATE' => date("Y-m-d h:i:s",time()),
                ];
                $user->addUserOpenid($dataUser);
                $this->redirect(WEB_URL."wx/home/binding");
            }else{
                $this->redirect(WEB_URL."wx/home/binding");
            }
        }*/
    /**
     * 用户手机绑定，生成电子卡
     * */
    public function binding(){
        if(strlen(session('openid'))>3){			
            $user = new User_tab();
            $card = new Member_info_tab();
            $cardOP = new Card_operat_tab();
            $openId = $user->openidGetUserOne(session('openid'));
            session('user_id',$openId['USER_ID']);
            $userCard = $card->getUserCard($openId['USER_ID']);
            $setpwd = input('setpwd');
            //判断用户是否是聊天框中直接点击绑定手机
            /*if(Request::instance()->isGet()){
                if(!session('openid')){
                    //如果是的话就先走一遍微信登录验证
                    $this->redirect(WEB_URL."wx/index/OAuth2");
                }*/
            //判断用户是否已经绑定了手机，没有就跳转到绑定手机页面
            if(!$openId['TEL_NO']){
                //判断是不是POST请求此页面
                if(!Request::instance()->isPost()){
                    //如果不是POST则渲染页面
                    return $this->fetch();
                }else {
                    //如果是POST则进行手机绑定
                    include_once '/extend/alidayuSDK/TopSdk.php';
                    //判断提交的数据是手机号还是验证码
                    if(input('phone')&&input('phone')!=""){
                        $post = input('phone');
                        $SmsParam = rand(1000, 9999);
                        session('SmsParam',$SmsParam);
                        session('phone',$post);
                        $aliyun = new \TopClient();
                        $aliyun->appkey = "24465351";
                        $aliyun->secretKey = "8eef7fc2cc568eaa791b15d46ecb5cc9";
                        $req = new \AlibabaAliqinFcSmsNumSendRequest();
                        $req->setExtend("");
                        $req->setSmsType("normal");
                        $req->setSmsFreeSignName("后舍");
                        $req->setSmsParam("{code:'$SmsParam',product:' 后舍预约 '}");
                        $req->setRecNum("$post");
                        $req->setSmsTemplateCode("SMS_71370775");
                        $res = $aliyun->execute($req);
                        if($res){						
                            return ;
                        }else{							
                            $this->error("验证码发送失败请重新尝试~");
                        }
                    }else{
                        //验证码验证
                        $yan = input('yan');
                        $post = input();
                        $yan1 = session('SmsParam');
                        if(!$post['user_name']){
                            $this->error('请填写您的姓名');
                        }
                      if($yan){
                            if($yan==$yan1){
                                //$res = $user->updateTel_no(session('phone'),session('openid'));
                                $dataUser = [
                                    'TEL_NO'=>session('phone'),
                                    'NICK_NAME'=>$post['user_name'],
                                    'SEX'=>$post['sex']?$post['sex']:1
                                ];
                                $dataOrder = [
                                    'USER_NAME'=>$post['user_name'],
                                    'TEL_NO'=>session('phone')
                                ];
                                $order_model = new Order_tab();
                                $res = $user->updateUserInfo($dataUser,session('openid'));
                                $resOrder = $order_model->updateOrderByUserID($dataOrder,session('user_id'));
                                //手机绑定成功后自动生成电子卡
                                if($res){
                                    $userId = $card->getUserCard(session('user_id'));
                                    if(!$userId){
                                        $userData = $user->openidGetUserOne(session('openid'));
                                        $data  = [
                                            'USER_ID'=>session('user_id'),
                                            'OPEN_CARD_DATE' => date("Y-m-d h:i:s",time()),
                                            'MEMBER_NAME' => $userData['NICK_NAME'],
                                            'MEMBER_CARD_NO' =>"E".date("Ymd",time()).rand(100000, 999999),
                                            'SEX'  => $userData['SEX'],
                                            'RECEIVE_AMT'=>10,
                                            'TEL_NO' =>$userData["TEL_NO"],
                                            'EFFECT_START_DATE' => date("Y-m-d h:i:s",time()),
                                        ];
                                        $res = $card->addElectronic_card($data);
                                        //设置完成电子卡的基本信息后跳转至设置支付密码页面
                                        if($res){
                                            $cdata = [
                                                'CARD_NO'=>$data['MEMBER_CARD_NO'],
                                                'CARD_TYPE'=>1,
                                                'CARD_OPERAT_TYPE'=>"开卡",
                                                'USER_ID'=>session('user_id'),
                                                'MEMBER_NAME'=>$data['MEMBER_NAME'],
                                                'MEMBER_TEL'=>$data['TEL_NO'],
                                                'MEMBER_SEX'=>$data['SEX'],
                                                'CONSUMP_AMT'=>0,
                                                'GIVE_AMT'=>10,
                                                'AFTER_CONSUMP_AMT'=>0,
                                                'AFTER_GIVE_AMT'=>10,
                                                'LEFT_AMT'=>10,
                                                'OPERAT_FEE'=>0,
                                                'REMARKS'=>"开卡操作",
                                            ];
                                            $re = $cardOP->cardOperatAdd($cdata);
                                            if($re){
                                                $this->redirect(WEB_URL."wx/home/setpwd");
                                            }else{
                                                $this->error("电子卡生成失败请手动申请~");
                                            }
                                        }
                                    }else{
                                        $dataCard = [
                                            'MEMBER_NAME'=>$post['user_name'],
                                            'TEL_NO' =>session('phone'),
                                            'SEX'=>$post['sex']?$post['sex']:1
                                        ];
                                        $dataOperate = [
                                            'MEMBER_NAME' => $post['user_name'],
                                            'MEMBER_TEL' => session('phone'),
                                            'MEMBER_SEX' => $post['sex']?$post['sex']:1
                                        ];
                                        $upOperate = $cardOP->updateCardInfoByCon($dataOperate,['USER_ID'=>session('user_id')]);
                                        $upCard = $card->updataCard($dataCard,$userId['MEMBER_CARD_NO']);
                                        $this->redirect(url('wx/home/binding',['setpwd'=>1]));
                                    }
                                }else{
                                    $this->error("手机绑定失败请重新尝试~");
                                }
                           }else{
                                $this->error("验证码不匹配~");
                            }
                        }else{
                            $this->error("验证码不能为空哦~");
                        }
                    }
                }
            }else{
                if($userCard){
                    if(empty($setpwd)) {
                        $this->redirect(WEB_URL . "wx/user/me");
                    }else{
                        $this->redirect(WEB_URL . "wx/home/setpwd");
                    }
                }else{
                    $userData = $user->openidGetUserOne(session('openid'));
                    $data  = [
                        'USER_ID'=>session('user_id'),
                        'OPEN_CARD_DATE' => date("Y-m-d h:i:s",time()),
                        'MEMBER_NAME' => $userData['NICK_NAME'],
                        'MEMBER_CARD_NO' =>"E".date("Ymd",time()).rand(100000, 999999),
                        'RECEIVE_AMT'=>10,
                        'SEX'  => $userData['SEX'],
                        'TEL_NO' =>$userData["TEL_NO"],
                        'EFFECT_START_DATE' => date("Y-m-d h:i:s",time()),
                    ];
                    $res = $card->addElectronic_card($data);
                    //设置完成电子卡的基本信息后跳转至设置支付密码页面
                    if($res){
                        $cdata = [
                            'CARD_NO'=>$data['MEMBER_CARD_NO'],
                            'CARD_TYPE'=>1,
                            'CARD_OPERAT_TYPE'=>"开卡",
                            'USER_ID'=>session('user_id'),
                            'MEMBER_NAME'=>$data['MEMBER_NAME'],
                            'MEMBER_TEL'=>$data['TEL_NO'],
                            'MEMBER_SEX'=>$data['SEX'],
                            'CONSUMP_AMT'=>0,
                            'GIVE_AMT'=>10,
                            'AFTER_CONSUMP_AMT'=>0,
                            'AFTER_GIVE_AMT'=>10,
                            'LEFT_AMT'=>10,
                            'OPERAT_FEE'=>0,
                            'REMARKS'=>"开卡操作",
                        ];
                        $cardOP = new Card_operat_tab();
                        $re = $cardOP->cardOperatAdd($cdata);
                        if($re){
                            $this->redirect(WEB_URL."wx/home/setpwd");
                        }else{
                            $this->error("电子卡生成失败请手动申请~");
                        }
                    }
                }
            }
        }else{
            $this->redirect(WEB_URL."/wx/index/OAuth");
        }
    }

    /**
     * 修改密码发送验证码
     */
    public function bindPassword(){
        if(Request::instance()->isPost()){
            //如果是POST则进行手机绑定
            include_once '/extend/alidayuSDK/TopSdk.php';
            //判断提交的数据是手机号还是验证码
            if(input('phone')&&input('phone')!=""){
                $post = input('phone');
                $SmsParam = rand(1000, 9999);
                session('SmsParam',$SmsParam);
                session('phone',$post);
                $aliyun = new \TopClient();
                $aliyun->appkey = "24465351";
                $aliyun->secretKey = "8eef7fc2cc568eaa791b15d46ecb5cc9";
                $req = new \AlibabaAliqinFcSmsNumSendRequest();
                $req->setExtend("");
                $req->setSmsType("normal");
                $req->setSmsFreeSignName("后舍");
                $req->setSmsParam("{code:'$SmsParam',product:' 后舍预约 '}");
                $req->setRecNum("$post");
                $req->setSmsTemplateCode("SMS_71370775");
                $res = $aliyun->execute($req);
                if($res){
                    return ;
                }else{
                    $this->error("验证码发送失败请重新尝试~");
                }
            }else{
                //验证码验证
                $yan = input('yan');
                $post = input();
                $yan1 = session('SmsParam');
                if($yan){
                    if($yan==$yan1){
                        $this->redirect(WEB_URL . "wx/home/setpwd");
                    }else{
                        $this->error("验证码不匹配~");
                    }
                }else{
                    $this->error("验证码不能为空哦~");
                }
            }
        }else{
            return $this->fetch();
        }
    }
    /**
     * 设置电子卡支付密码页
     * */
    public function setpwd(){
        if(session('setpwdpost') == 1){
            session('setpwdpost',null);
            $this->redirect(url('wx/user/me'));
        }
        if(Request::instance()->isPost()){
            $pwd = input('pwd');
            if($pwd){
                $card = new Member_info_tab();
                $res = $card->setCardPwd(session('user_id'),md5($pwd));
                if($res){
                    session('setpwdpost',1);
                    //票券id存在跳转至票券领取页
                    if(session('FINANCE_ID') && session('FINANCE_TYPE')){
                         $finance_id = session('FINANCE_ID');
                         $finance_type = session('FINANCE_TYPE');
                         session('FINANCE_ID',NULL);
                         session('FINANCE_TYPE',NULL);
                         $this->redirect(url("wx/home/getFinance",['finance_id'=>$finance_id,'finance_type'=>$finance_type]));
                    }else{

                        $this->redirect(WEB_URL."wx/home/home");
                    }
                }else{
                    $this->error("密码设置失败，请换个密码试试~");
                }
            }else{
                $this->error("密码不能为空哦~");
            }
        }else{
            return $this->fetch();
        }
    }
    public function home(){
        $project = new Project_tab();
        $dataAll = $project->ProjectListAll();
        $project_type = new Project_type_tab();
        $categoryList = $project_type->getProjectTypeFlg();
        foreach ($dataAll as $k=>$value){
            $value['PRICE'] = mb_substr($value['PRICE'], 0, mb_strlen($value['PRICE']) - 5);
            $img[] = explode(',',$value['PROJECT_IMAGE']);
            $value['PROJECT_IMAGE'] =  $img[$k][0];
        }
        $store = new Store_tab();
        $sdata = $store->storeListAll();
        $flag=[];
        foreach ($sdata as $v){
            $v['juli'] = getDistance(session("latitude"),session("longitude"),$v["LATITUDE"],$v['LONGITUDE']);
            $flag[]=$v["juli"];
			if(mb_strlen($v['ADDRESS'],'utf8') > 15){
				$v['ADDRESS'] = mb_substr($v['ADDRESS'],0,15,'utf8').'...';
			}
        }
        array_multisort($flag, SORT_ASC, $sdata);
        //轮播图
        $carousel = new Carousel_figure_tab();
        $cdata = $carousel->getCarouselList();
		
        $this->jssdk();
        $this->assign('categoryList',$categoryList);
        $this->assign('cdata',$cdata);
        $this->assign('sdata',$sdata);
        $this->assign('dataAll',$dataAll);
        return $this->fetch('index');
    }
    /**
     * ajax获取项目列表
     */
    public function ajaxGetProject(){
        if(Request::instance()->isPost()){
            $typeId = input('typeId');
            $store_cd = input('store_cd');
            $project = new Project_tab();
            $type_model = new Project_type_tab();
            $priceat = new Project_plan_price_tab();
            if(empty($typeId)){
                $list = $project->getProjectStoreAll($store_cd);
            }else{
                $typeInfo = $type_model->getTypeInfoById($typeId);
                $condition = [];
                $condition['AVAILABLE_FLG'] = 1;
                $condition['TYPE_CD'] = $typeInfo['TYPE_CD'];
                $condition['STORE_CD'] = $store_cd;
                $list = $project->ProjectListCon($condition);
            }
            if($list){
                foreach ($list as $k=>$value){
                    $value['PRICE'] = round($value['PRICE']);
                    $value['MARKET_PRICE']= round($value['MARKET_PRICE']);
                    $img[$k] = explode(',',$value['PROJECT_IMAGE']);
                    $value['PROJECT_IMAGE'] =  $img[$k][0];
                    //截取项目名称
                    if(mb_strlen($value['PROJECT_NAME'], 'UTF-8')>15) {
                        $value['PROJECT_NAME'] = mb_substr($value['PROJECT_NAME'], 0, 15, 'UTF-8');
                    }
                    //截取项目介绍
                    if(mb_strlen($value['PROJECT_INTRODUCE'],'UTF-8') > 11){
                        $value['PROJECT_INTRODUCE'] = mb_substr($value['PROJECT_INTRODUCE'],0,11,'UTF-8').'...';
                    }
                    $priceatInfo = $priceat->getProjectPriceatInfo($value['PROJECT_ID']);
                    //截取项目优惠方案名称
                    if($priceatInfo){
                        if(mb_strlen($priceatInfo['PLAN_NAME'], 'UTF-8')>15) {
                            $list[$k]['PRICEAT_NAME'] = mb_substr($priceatInfo['PLAN_NAME'], 0, 15, 'UTF-8');
                        }else {
                            $list[$k]['PRICEAT_NAME'] = $priceatInfo['PLAN_NAME'];
                        }
                    }
                }
                $data['state'] = 'success';
                $data['typeId'] = $typeId;
                $data['info'] = $list;
            }else{
                $data['state'] = 101;
                $data['info'] = '暂无相关项目信息';
            }
            echo json_encode($data);die();

        }else{
            $data['state'] = 'error';
            $data['msg'] = '错误请求';
            echo json_encode($data);die();
        }
    }
    /**
     * 验证手机是否绑定
     */
    public function checkMobile(){
        $phone = input('phone');
        $data = [];
        if(!empty($phone)){
            $user_model = new User_tab();
            $num = $user_model->getUserNumCon(['TEL_NO'=>$phone]);
            if($num == 0){
                $data['state'] = 'success';
                $data['msg'] = '无绑定记录';
                $data['num'] = $num;
            }else if($num > 0){
                $data['state'] = 101;
                $data['msg'] = '该手机号已绑定';
                $data['num'] = $num;
            }

        }else{
            $data['state'] = 'fail';
            $data['msg'] = '参数不完整！';
        }
        echo json_encode($data);die();
    }
	/**
     * 联系我们
     * */
	public function contact(){
		return $this->fetch();
	}
	/**
     * 简介
     * */
	public function introduce(){
		return $this->fetch();
	}
	/**
     * 敬请期待
     * */
	public function getMore(){
		return $this->fetch();
	}
	/**
     * 领取票券
     */
	public function getFinance(){
        if(strlen(session('openid'))>3) {
            $finance_id = input('finance_id');
            $finance_type = input('finance_type');
            //判断用户是否绑定
            $user_model = new User_tab();
            $userInfo = $user_model->openidGetUserOne(session('openid'));

            //未绑定跳转至绑定页面
            if (empty($userInfo['TEL_NO'])) {
                session('FINANCE_ID', $finance_id);
                session('FINANCE_TYPE', $finance_type);
                $this->redirect(url('wx/home/binding'));
            } else {
                $finance_model = new Finance_tab();
                $pro_model = new Project_tab();
                $store_model = new Store_tab();
                //票券信息
                $financeInfo = $finance_model->getFinanceInfoById($finance_id);
                $storeList = [];
                //发行券券号
                $issue_no = 'ISU' . substr(time(), 5, -1) . rand(1000, 9999);
                //项目券
                if (!empty($financeInfo['PROJECT_CD'])) {
                    $condition = [
                        'a.AVAILABLE_FLG' => 1,
                        'PROJECT_CD' => $financeInfo['PROJECT_CD']
                    ];
                    //适用项目
                    $projectList = $pro_model->ProjectListAllJoin($condition);
                    //获取项目所属门店
                    foreach ($projectList as $k => $v) {
                        $storeList[$k]['STORE_CD'] = $v['STORE_CD'];
                        $storeList[$k]['STORE_NAME'] = $v['STORE_NAME'];
                    }
                } else {//通用券
                    $storeList = $store_model->storeListCondition(['AVAILABLE_FLG' => 1]);
                }
                $this->assign('issue_no', $issue_no);
                $this->assign('financeInfo', $financeInfo);
                $this->assign('storeList', $storeList);
                return $this->fetch();
            }
        }else{
            $this->redirect(WEB_URL."/wx/index/OAuth");
        }
    }
    /**
     * 领取代金券处理
     */
    public function doIssueFinance(){
        $finance_id = input('finance_id');
        $finance_type = input('finance_type');
        $issue_no = input('issue_no');
        $finance_model = new Finance_tab();
        $issue_model = new Finance_issue_tab();
        $error = '';
        $financeInfo = $finance_model->getFinanceInfoById($finance_id);
        //用户领取代金券数量
        $financeIssueNum = $issue_model->getFinanceIssueNum(['FINANCE_ID'=>$finance_id,'USER_ID'=>session('user_id')]);
        if($financeInfo['REMAIN_NUM'] <= 0){
            $error = '您来的太晚了，该代金券已经领光光了！';
            //$this->redirect(url('wx/user/error',['error'=>$error]));
            $this->error($error);
        }else if($financeIssueNum>0){
            $error = '您已经领取了该代金券，请勿重复领取！';
            //$this->redirect(url('wx/user/error',['error'=>$error]));
            $this->error($error);
        }else{
            $dataFinance = [
                'REMAIN_NUM' => $financeInfo['REMAIN_NUM'] -1,
                'SALE_NUM' => $financeInfo['SALE_NUM']+1,
                'UPDATE_USER' => session('user_id'),
                'UPDATE_DATE' => time()
            ];
            $data = [
                'FINANCE_ID' => $finance_id,
                'FINANCE_TYPE' => $finance_type,
                'FINANCE_ISSUE_NO' => $issue_no,
                'USER_ID' => session('user_id'),
                'CREATE_USER' => session('user_id'),
                'CREATE_DATE' => time(),
                'UPDATE_USER' => session('user_id'),
                'UPDATE_DATE' => time(),
            ];
            $resFinance = $finance_model->updateFinanceInfoById($dataFinance,$finance_id);
            $error = '领取失败！';
            if(!empty($resFinance)){
                $result = $issue_model->financeIssueAdd($data);
                if(!empty($result)){
                    $this->redirect(url('wx/user/userTicket'));
                }else{
                    $this->error($error);
                }
            }else{
                $this->error($error);
            }

        }
    }

    /**
     * 生成发行券
     */
    private function createIssueFinance($finance_id,$finance_type){
        $state = '';
        //票券信息
        $finance_model = new Finance_tab();
        $financeInfo = $finance_model->getFinanceInfoById($finance_id);
        switch($finance_type){
            case '1':
                break;
            case '3'://代金券
                break;

        }
    }
    /**
     * 城市选择
     */
    public function citySelect(){
        $districtInfo = session('DIS_INFO');
        $this->assign($districtInfo);
        return $this->fetch();
    }

}