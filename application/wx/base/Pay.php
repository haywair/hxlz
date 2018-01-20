<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20 0020
 * Time: 13:22
 */

namespace app\wx\base;

use app\wx\model\User_tab;
use app\wx\model\Member_info_tab;
use app\admin\model\Gift_card_tab;
use think\Exception;
use think\Controller;
class Pay extends Controller
{
    public function notify_url(){
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
            $payType = mb_substr($data['out_trade_no'],0,1,'utf8');
            switch($payType){
                case 'O':
                case 'E':
                case 'F':
                    break;
                case 'G':
                    (new Gift_card_tab())->where('ORDER_NO',$data['out_trade_no'])->update(['AVAILABLE_FLG'=>1]);
                    break;
            }
            //处理完成之后，告诉微信成功结果
            echo '<xml>
					  <return_code><![CDATA[SUCCESS]]></return_code>
					  <return_msg><![CDATA[OK]]></return_msg>
				  </xml>';
            exit();
        }
    }

    public function dealGiftcard(){

    }
}