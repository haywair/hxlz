<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:60:"E:\phpStudy\WWW\WEB/application/wx\view\user\cardcharge.html";i:1513931765;}*/ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="telephone=no" name="format-detection" />
<title>后舍</title>
<link href="__STATIC__css/HTp-style2.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="__STATIC__css/weui.min.css">
<link rel="stylesheet" href="__STATIC__css/jquery-weui.min.css">
<link rel="stylesheet" href="__STATIC__css/jquery.flipster.css">
<script type="text/javascript" src="__STATIC__js/jquery.min.js"></script>
<script type="text/javascript" src="__STATIC__js/jquery-weui.min.js"></script>
<script>
    //调用微信JS api 支付
    function jsApiCall(par)
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            {
                'appId':par.appId,
                'nonceStr':par.nonceStr,
                'package':par.package,
                'paySign':par.paySign,
                'signType':par.signType,
                'timeStamp':par.timeStamp
            },
            function(res){
                if(res.err_msg == "get_brand_wcpay_request:ok"){
                    $('#myform').submit();
                }else if(res.err_msg == "get_brand_wcpay_request:cancel"){
                    return false;
                }else{
                    alert('支付失败！');
                    return false;
                }
            }
        );
    }
    function callpay(parameters)
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall(parameters);
        }
    }
</script>
</head>

<body>
<div class="chongzhi-top">
	<a href="javascript:void(0)" onclick="window.history.go(-1)" class="back"><span></span></a>
    <h1>充值中心</h1>
</div>
<div class="chongzhi-txt">
	<!-- <div class="left-txt">
   	  <p style="font-size:18px; font-weight:500">后舍</p>
      <p style="font-size:14px; color:#999;">济南奥体店</p>
	</div>
    <div class="right-img"><img src="images/logo.png"/></div> -->
</div>
<div class="chongzhi-box">
	<ul>
        <form action="<?php echo url('wx/user/doRecharge'); ?>" method="post" id="myform">
            <input type="hidden" id="recharge_money" name="recharge_money" >
            <input type="hidden" id="recharge_no" name="recharge_no" value="<?php echo $recharge_no; ?>">
            <input type="hidden" id="card_no" name="card_no" value="<?php echo $card_no; ?>">
            <input type="hidden" id="gift_money" name="gift_money" >
        </form>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
    	    <li>
                <div class="but-box"><a  href="#">
                    <div class="but-box" data-money="<?php echo $vo['RECHARGE_MONEY']; ?>" data-gift-money="<?php echo $vo['GIFT_MONEY']; ?>">
                        <div><?php echo $vo['RECHARGE_MONEY']; ?>元</div>
                        <div style="font-size:14px;">送<?php echo $vo['GIFT_MONEY']; ?></div>
                    </div>
                </a></div>
            </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <!-- <li>
            <div class="but-box"><a
                    href="#"><div class="but-box" data-money="" style="line-height:60px;">自定义</div></a></div>
        </li> -->
        <div style="clear:both;"></div>
    </ul>
    <div id="pay-area" style="display:none;padding-left:0.8rem;">您的支付金额为：<span id="pay-money"></span></div>
</div>
<div class="chongzhi-but-2"><span class="chongzhi-but-2" id="pay">充值</span></div>
<script>
    $(function(){
        //选择充值金额
        $('.chongzhi-box ul li .but-box .but-box').click(function(){
            $('#pay-area').hide();
            var recharge_money = $(this).attr('data-money');
            var gift_money = $(this).attr('data-gift-money');
            $('.chongzhi-box ul li .but-box .but-box').css({'background-color':'#fff','color':'#000'});
            $(this).css({'background-color':'#06c1af','color':'#fff'});
            if(recharge_money){
                $('#recharge_money').val(recharge_money);
                $('#gift_money').val(gift_money);
                $('#pay-money').html(recharge_money+'元');
                $('#pay-area').show();
            }else{
                $.prompt({
                    title: '请输入充值金额',
                    empty: false, // 是否允许为空
                    onOK: function (input) {
                        //点击确认
                        var money = $('.weui-prompt-input').val();
                        if(money){
                            $('#recharge_money').val(money);
                            $('#pay-money').html(money+'元');
                            $('#pay-area').show();
                        }
                    },
                    onCancel: function () {
                        //点击取消
                    }
                });
            }

        });
        //充值金额提交
        $('#pay').click(function(){
            var recharge_money = $('#recharge_money').val();
            //var recharge_no = $('#recharge_no').val();
            var card_no = $('#card_no').val();
			var recharge_no = Date.parse(new Date())/1000+parseInt(10000*Math.random()).toString();
            $('#recharge_no').val(recharge_no);
            getWxOrder(recharge_money,card_no,recharge_no);
		});
    });
    function getWxOrder(recharge_money,card_no,recharge_no){
        $.ajax({
            url:'<?php echo url("wx/user/ajaxGetRechargeOrder"); ?>',
            type:'post',
            data:{recharge_no:recharge_no,card_no:card_no,recharge_money:recharge_money},
            dataType:'json',
            success:function(result){
                if(result.state == 'success'){
                    var info = result.info;
                    var param = {};
                    $.each(info,function(i,item){
                        param.appId = item .appId;
                        param.nonceStr = item.nonceStr;
                        param.package = item.package;
                        param.paySign = item.paySign;
                        param.signType = item.signType;
                        param.timeStamp = item.timeStamp;
                    });
                    callpay(param);
                }else if(result.state == 201){
                    $('#e-card-pay').removeAttr('checked');
                    alert(result.msg);return false;
                }else if(result.state == 202){
                    $('#wx-pay').removeAttr('checked');
                    $('#myform').submit();
                }
            }
        });
    }
</script>
<ul class="foot" style="font-size:12px;">
    <li><a href="__PATH__wx/home/home"><p class="f-p1"><img src="__STATIC__images/ftt_03.png"  width="23"></p><p class="f-p2">首页</p></a></li>
    <li><a href="__PATH__wx/order/orderList"><p class="f-p1"><img src="__STATIC__images/lz_13.jpg" width="23"></p><p class="f-p2">订单</p></a></li>
    <li><a href="#"><p class="f-p1"><img src="__STATIC__images/lz_19.jpg" width="23"></p><p class="f-p2">商城</p></a></li>
    <li><a href="__PATH__wx/user/me"><p class="f-p1"><img src="__STATIC__images/lz_21.jpg" width="20"></p><p class="f-p2">会员中心</p></a></li>
</ul><!--foot end-->
</body>
</html>
