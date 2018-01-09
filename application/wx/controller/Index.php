<?php
namespace app\wx\controller;

class Index extends Base
{


    public function index()
    {
		//echo $_GET["echostr"];die();			
        $wx = new \Wechat(self::$options);
		

        //$wx->deleteMenu();

        $ce = array (
            'button' => array (
                0 => array (
                    'type' => 'view',
                    'name' => '预约',
                    'url' => WEB_URL.'/wx/index/OAuth'
                ),
                1 => array (
                    'name' => '会员中心',
                    'sub_button' => array (
                        0 => array (
                            'type' => 'view',
                            'name' => '我的卡包',
                            'url' => WEB_URL.'/wx/user/card',
                        ),
                        1 => array (
                            'type' => 'view',
                            'name' => '我的订单',
                            'url' => WEB_URL.'/wx/order/orderList',
                        ),
                        2 => array (
                            'type' => 'view',
                            'name' => '我的票券',
                            'url' => WEB_URL.'/wx/user/userTicket',
                        ),
                        3 => array (
                            'type' => 'view',
                            'name' => '我的点评',
                            'url' => WEB_URL.'/wx/user/userReview',
                        ),
                        4 => array (
                            'type' => 'view',
                            'name' => '我的资料',
                            'url' => WEB_URL.'/wx/user/me',
                        )
                    ),
                ),
                2 => array (
                    'name' => '后舍',
                    'sub_button' => array (
                        0 => array (
                            'type' => 'view',
                            'name' => '绑定手机号',
                            'url' => WEB_URL.'/wx/home/binding',
                        ),
                        1 => array (
                            'type' => 'view',
                            'name' => '关于我们',
                            'url' => WEB_URL.'/wx/home/introduce',
                        ),
                        2 => array (
                            'type' => 'view',
                            'name' => '联系我们',
                            'url' => WEB_URL.'/wx/home/contact',
                        ),
                    ),
                ),
            ),
        );


         $wx->createMenu($ce);
        $type = $wx->getRev()->getRevEvent();
        //$type2 = $wx->getRevEvent();
        switch ($type['event']){
            case \Wechat::EVENT_SUBSCRIBE:
				$time = 1;
                $newsData = "感谢您关注后舍，我们在此恭候多时诗与远方太远，轻松自在慢生活很近！\r\n“快节奏”侵占了您生活太多的领地，我们只想做您唯一的静土，洗尽铅尘，静谧一舍！\r\n\r\n点击预约 -> 绑定手机号即可开始预约";					
				$first = $wx->text($newsData);				
				$first->reply();
                break;
            case \Wechat::EVENT_UNSUBSCRIBE:
                $newsData = "您好，点击预约 -> 绑定手机号即可开始预约";
                $wx->text($newsData)->reply();
                break;
            default:
                break;
        }
    }
    /**
     * 微信登录验证
     * */
    public function OAuth(){
        $wx = new \Wechat(self::$options);		
        $callback = WEB_URL."/wx/home/index";
        $state = "";		
        $aa = $wx->getOauthRedirect($callback,$state);		
        $this->redirect($aa);
    }
    /*    public function OAuth2(){
            $wx = new \Wechat(self::$options);
            $callback = WEB_URL."/wx/home/index2";
            $state = "";
            $aa = $wx->getOauthRedirect($callback,$state);
            $this->redirect($aa);
        }*/
}
