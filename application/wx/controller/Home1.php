<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 15:03
 */

namespace app\wx\controller;

use app\admin\model\Project_tab;
use app\admin\model\Store_tab;
use app\wx\model\Card_operat_tab;
use app\wx\model\Carousel;
use app\wx\model\Carousel_figure_tab;
use app\wx\model\Member_info_tab;
use app\admin\model\Project_type_tab;
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
        //你可能看不懂= =。但是不要改，你可以选择全删了重写。
        if(strlen(session('openid'))>3){			
            $user = new User_tab();
            $card = new Member_info_tab();
            $openId = $user->openidGetUserOne(session('openid'));
            session('user_id',$openId['USER_ID']);
            $userCard = $card->getUserCard($openId['USER_ID']);
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
                        $yan1 = session('SmsParam');
                        if($yan){
                            if($yan==$yan1){
                                $res = $user->updateTel_no(session('phone'),session('openid'));
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
                                            $cardOP = new Card_operat_tab();
                                            $re = $cardOP->cardOperatAdd($cdata);
                                            if($re){
                                                $this->redirect(WEB_URL."wx/home/setpwd");
                                            }else{
                                                $this->error("电子卡生成失败请手动申请~");
                                            }
                                        }
                                    }else{
                                        $this->redirect(WEB_URL."wx/home/binding");
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
                    //$this->redirect(WEB_URL."wx/user/me");
                    $this->redirect(WEB_URL."wx/home/setpwd");
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
     * 设置电子卡支付密码页
     * */
    public function setpwd(){
        if(Request::instance()->isPost()){
            $pwd = input('pwd');
            if($pwd){
                $card = new Member_info_tab();
                $res = $card->setCardPwd(session('user_id'),md5($pwd));
                if($res){
                    $this->redirect(WEB_URL."wx/home/home");
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
        $dataLiliao = $project->ProjectListLiliao();
        foreach ($dataLiliao as $k=>$value){
            $value['PRICE'] = mb_substr($value['PRICE'], 0, mb_strlen($value['PRICE']) - 5);
            $img[] = explode(',',$value['PROJECT_IMAGE']);
            $value['PROJECT_IMAGE'] =  $img[$k][0];
        }
        $dataJiaju = $project->ProjectListJiaju();
        foreach ($dataJiaju as $k=>$value){
            $value['PRICE'] = mb_substr($value['PRICE'], 0, mb_strlen($value['PRICE']) - 5);
            $img[] = explode(',',$value['PROJECT_IMAGE']);
            $value['PROJECT_IMAGE'] =  $img[$k][0];
        }
        $dataSPA = $project->ProjectListSPA();
        foreach ($dataSPA as $k=>$value){
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
        $this->assign('dataLiliao',$dataLiliao);
        $this->assign('dataJiaju',$dataJiaju);
        $this->assign('dataSPA',$dataSPA);
        return $this->fetch('index');
    }
    /**
     * ajax获取项目列表
     */
    public function ajaxGetProject(){
        if(Request::instance()->isPost()){
            $typeId = input('typeId');
            $project = new Project_tab();
            $type_model = new Project_type_tab();
            if(empty($typeId)){
                $list = $project->ProjectListAll();
            }else{
                $typeInfo = $type_model->getTypeInfoById($typeId);
                $condition = [];
                $condition['AVAILABLE_FLG'] = 1;
                $condition['TYPE_CD'] = $typeInfo['TYPE_CD'];
                $list = $project->ProjectListCon($condition);
            }
            if($list){
                foreach ($list as $k=>$value){
                    $value['PRICE'] = mb_substr($value['PRICE'], 0, mb_strlen($value['PRICE']) - 5);
                    $img[] = explode(',',$value['PROJECT_IMAGE']);
                    $value['PROJECT_IMAGE'] =  $img[$k][0];
                    if(mb_strlen($value['PROJECT_INTRODUCE'],'UTF-8') > 11){
                        $value['PROJECT_INTRODUCE'] = mb_substr($value['PROJECT_INTRODUCE'],0,11,'UTF-8').'...';
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

}