<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 13:52
 */

namespace app\wx\controller;


use think\Controller;
use think\Request;

class Base extends Controller
{
    // 静态化 并全局替换得到3倍以上的性能提升
    protected static $options = array(
        'token'=>'weixin', //填写你设定的key
        'encodingaeskey'=>'sNGJcZopTz2zbXu8eyFG2UEy9FagXNkMa3RuQaxTJGe', //填写加密用的EncodingAESKey
        'appid'=>'wx185a4ed8311601a9', //填写高级调用功能的app id, 请在微信开发模式后台查询		
        'appsecret'=>'ee9d67fcdba11352522fd80e1713eff1' //填写高级调用功能的密钥		
    );

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        include_once '/extend/wechatSDK/wechat.class.php';
        include_once '/extend/wechatSDK/Thinkphp/JsSdkPay.class.php';
        include_once '/extend/wechatSDK/Thinkphp/TPWechat.class.php';
    }

    public function jssdk()
    {

        $Wechat = new \Wechat(self::$options);

        $jssdk = $Wechat->getJsSign(self::getFullUrl());

        $this->assign('js', $jssdk);
    }

    /**
     * 获取完整url
     * @return string
     */
    protected static function getFullUrl()
    {
        return 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
    }

    /**
     * 获取私有成员的方法
     * @return array
     */
    public static function getOptions(){
        return self::$options;
    }
}