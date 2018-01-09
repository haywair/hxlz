<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"E:\phpStudy\WWW\WEB/application/wx\view\order\orderdetails.html";i:1515222029;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="telephone=no" name="format-detection" />
    <title>华夏良子</title>
    <link href="__STATIC__css/HTp-style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="__STATIC__css/jquery.flipster.css">
    <link rel="stylesheet" href="__STATIC__css/weui.min.css">
    <link rel="stylesheet" href="__STATIC__css/jquery-weui.min.css">
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
<form  action="__PATH__wx/order/orderYes"  method="post" id="myform">
    <input type="hidden" name="oid" value="<?php echo $odata['ORDER_CD']; ?>">
    <input type="hidden" name="SELL_PRICE" value="<?php echo $odata['SELL_PRICE']; ?>">
    <input type="hidden" name="MEMBER_PASS" id="member_pass">
    <input type="hidden" name="WX_ORDER_NO" id="wx_order_no" value="<?php echo $odata['WX_ORDER_NO']; ?>">
    <input type="hidden" name="ORDER_AMT" id="order_amt" value="<?php echo $odata['ORDER_AMT']; ?>">
    <input type="hidden" name="CUSTOMER_TOTAL_QTY" id="customer_total_qty" value="<?php echo $odata['CUSTOMER_TOTAL_QTY']; ?>">
<div class="box1">
    <p class="fk-p1"><span class="fk-s1"><img src="__STATIC__images/fk_03.png" width="12"></span>请在<font class="font4">5分钟</font>内付款，时间结束后则订单失效。</p>
   <!-- <ul class="fk-ul1">
        <li><span class="fk-s2">项目名称：</span><span class="fk-s3" ><?php echo $odata['PROJECT_NAME']; ?></span></li>
        <li><span class="fk-s2">项目时长：</span><span class="fk-s3" ><?php echo $odata['PROJECT_TIME']; ?>分钟</span></li>
        <li><span class="fk-s2">项目描述：</span><span class="fk-s3" ><?php echo $odata['PROJECT_INFO']; ?></span></li>
        <li><span class="fk-s2">门店名称：</span><span class="fk-s3" ><?php echo $odata['STORE_NAME']; ?></span></li>
        <li><span class="fk-s2">房间名称：</span><span class="fk-s3" ><?php echo $odata['ROOM_NAME']; ?></span></li>
        <li><span class="fk-s2">人数：</span><span class="fk-s3"><?php echo $odata['CUSTOMER_TOTAL_QTY']; ?>人</span></li>
        <li><span class="fk-s2">项目价格：</span><span class="fk-s3"><?php echo $pdata['PRICE']; ?>元</span></li>
        <li><span class="fk-s2">预约人：</span><span class="fk-s3"><input type="text" class="fk-int1"  name="CONNECT_USER_NAME"  id="user_name"  value="<?php echo $odata['CONNECT_USER_NAME']; ?>"></span></li>
        <li><span class="fk-s2">预约电话：</span><span class="fk-s3"><input type="text" class="fk-int1" name="CONNECT_TEL_NO" id="tel_no"  value="<?php echo $odata['CONNECT_TEL_NO']; ?>"></span></li>
        <li><span class="fk-s2">预约时间：</span><span class="fk-s3"><?php echo $odata['ORDER_DATE']; ?> <?php echo $odata['OR_START_DATE_TIME']; ?></span></li>
        <li><span class="fk-s2">手机号：</span><span class="fk-s3"><?php echo $odata['TEL_NO']; ?></span></li>
        <li><span class="fk-s2">预约单号：</span><span class="fk-s3"><?php echo $odata['ORDER_CD']; ?></span></li>
    </ul>-->
    <div style="background-color:#fff;font-size:14px;padding:0.5rem 0;color:#666;">
        <p style="padding-left:2rem;height:25px;line-height:25px;border-bottom:1px solid #ddd;">
            <span style="display:inline-block;float:left;">足部全息：<?php echo $odata['SELL_PRICE']; ?>元/人</span>
            <span style="display:inline-block;float:right;margin-right:0.8rem;">时长：<?php echo $pdata['PROJECT_TIME']; ?>min</span>
            <span  style="display:inline-block;clear:both;"></span>
        </p>
        <p style="padding-left:2rem;height:25px;line-height:25px;margin-top:0.5rem;">服务门店：<?php echo $odata['STORE_NAME']; ?></p>
        <p style="padding-left:2rem;height:25px;line-height:25px;">门店地址：<?php echo $sdata['ADDRESS']; ?></p>
        <p style="padding-left:2rem;height:25px;line-height:25px;">门店电话：<?php echo $sdata['OFFICE_TEL']; ?></p>
        <p style="padding-left:2rem;height:25px;line-height:25px;margin-top:1.2rem;">预约时间：<?php echo $odata['ORDER_DATE']; ?> <?php echo $odata['OR_START_DATE_TIME']; ?></p>
        <p style="padding-left:2rem;height:25px;line-height:25px;">预约单号：<?php echo $odata['ORDER_CD']; ?></p>
        <p style="padding-left:2rem;height:25px;line-height:25px;">下单电话：<?php echo $odata['CONNECT_TEL_NO']; ?></p>
        <p style="padding-left:2rem;height:25px;line-height:25px;">到店人数：<?php echo $odata['CUSTOMER_TOTAL_QTY']; ?>人</p>
        <p style="padding-left:2rem;height:25px;line-height:25px;margin-top:1.2rem;">预&nbsp;&nbsp;约&nbsp;&nbsp;人：<input type="text" name="order_person" style="width:6rem;height:18px;border:1px solid #ddd;" id="user_name" value="<?php echo $udata['NICK_NAME']; ?>"> </p>
		<p style="padding-left:2rem;height:25px;line-height:25px;">预约电话：<input  type="text"  style="width:6rem;height:18px;border:1px solid #ddd;" name="order_tel" id="tel_no" value="<?php echo $udata['TEL_NO']; ?>"></p>
    </div>





    <p class="fk-p2" style="margin-bottom:10px;">
		<?php $money = ($pdata['MARKET_PRICE']-$pdata['PRICE'])*$odata['CUSTOMER_TOTAL_QTY']; ?>	
		<a href="#">
			<span class="fk-s6"> <font class="font1"><?php echo $odata['ORDER_AMT']; ?>元</font> </span>
			<span class="fk-s4" style="margin-top:8px"><img src="__STATIC__images/fk_07.png">
			</span>
			<span class="fk-s5">订单金额</span>
			<?php if(!(empty($money) || (($money instanceof \think\Collection || $money instanceof \think\Paginator ) && $money->isEmpty()))): ?>
			<span class="fk-s5" style="margin-bottom:3px;"> (优惠 <font class="font1"><?php echo $money; ?></font>)</span>
			<?php endif; ?>
		</a>
	</p>
<!--
    <p class="fk-p2"><a href="#"><span class="fk-s6"> > </span><span class="fk-s4"><img src="__STATIC__images/fk_11.png"></span><span class="fk-s5">票券支付</span></a></p>
-->
    <!--<p class="fk-p2"><a href="#"><span class="fk-s6"> <input name="PAY_TYPE" type="radio" value="2" class="fk-rd"/> </span><span class="fk-s4"><img src="__STATIC__images/fk_14.png"></span><span class="fk-s5">微信支付</span></a></p>
    <p class="fk-p2"><a href="#"><span class="fk-s6"> <input name="PAY_TYPE" type="radio" value="1"  class="fk-rd"/> </span><span class="fk-s4"><img src="__STATIC__images/fk_17.png"></span><span class="fk-s5">电子卡</span></a></p>-->
    <div class="fk-p2" >
        <img src="__STATIC__images/fk_14.png" style="margin-top:0.2rem;">
        <span style="display:inline-block;float:right;mirgin-right:0.2rem;">
            <span style="display:inline-block;margin-right:0.3rem;">微信支付</span>
            <input name="PAY_TYPE[]" type="checkbox" id="wx-pay" value="2" class="fk-rd"/>
        </span>
    </div>
    <div class="fk-p2" id="e-card">
        <img src="__STATIC__images/fk_17.png" style="margin-top:0.2rem;">
        <span style="display:inline-block;float:right;mirgin-right:0.2rem;">
            <span style="display:inline-block;margin-right:0.3rem;">电子卡</span>
            <input name="PAY_TYPE[]" type="checkbox" id="e-card-pay" value="1" class="fk-rd"/>
        </span>
    </div>
</div><!--box1 end-->

<div class="foot1" style="position:relative;">
    <input type="button" class="ft-a1" id="pay-now" value="立即付款">
</div>
</form>
<script>
    $(function(){
        var saled = "<?php echo $saled; ?>";
        $('#pay-now').click(function(){
            var pay_type = '';
            var project_name = "<?php echo $odata['PROJECT_NAME']; ?>";
            var sell_price = "<?php echo $odata['ORDER_AMT']; ?>";
            var wx_order_cd = "<?php echo $odata['WX_ORDER_NO']; ?>";
            var check_num;
            var tel_no = $('#tel_no').val();
            var user_name = $('#user_name').val();
            var phoneCheck= /^1[34578]{1}\d{9}$/;
            $("input[name='PAY_TYPE[]']").each(
                function(){
                    if($(this).attr('checked')){
                        pay_type = $(this).val();
                    }
                }
            );
            if(!tel_no){
                alert('请填写您的预约电话！');return false;
            }else if(!phoneCheck.test(tel_no)){
                alert('手机号码格式不正确！');return false;
            }
            if(!user_name){
                alert('请填写预约人！');return false;
            }
            if(!pay_type){
                alert('请选择您的付款方式！');return false;
            }
            if($("input[type='checkbox']:checked").length == 2){
                check_num = 2;
                member_pass =  $('#member_pass').val();
                getWxOrder(sell_price,project_name,wx_order_cd,check_num,member_pass);
            }else if($("#wx-pay").attr("checked")){
                check_num = 1;
                getWxOrder(sell_price,project_name,wx_order_cd,check_num);
            }else{
                $('#myform').submit();
            }
        });
        //微信支付选择
        $('#wx-pay').click(function(){
            $('input[name="PAY_TYPE[]"]').removeAttr('checked');
            $('#wx-pay').attr('checked','checked');
        });
        //电子卡输入框
        $('#e-card-pay').click(function(){
            var cardPay_can = "<?php echo $cardPay_can; ?>";
            var orderAmt ="<?php echo $orderAmt; ?>";
            $('#wx-pay').removeAttr('checked');
            //参加优惠方案后不可使用微信电子卡支付
            if(saled == 1){
                $('#e-card-pay').removeAttr('checked');
                alert('该项目已进行了优惠，请您选择微信支付');return false;
            }
            if(parseFloat(orderAmt) > parseFloat(cardPay_can)){
                $.confirm("余额不足，您现在是否需要充值？", function() {
                    $('#e-card').attr('checked','checked');
                    window.location.href="<?php echo url('wx/user/cardcharge'); ?>";
                }, function() {
                    //点击取消后的回调函数
                });
                return false;
            }
            $.prompt({
                title: '请输入电子卡密码',
                empty: false, // 是否允许为空
                onOK: function (input) {
                    //点击确认
                    var pay_btn = document.getElementById('pay-now');
                    var password = $('.weui-prompt-input').val();
                    $('#e-card').attr('checked','checked');
                    if(password){
                        $('#member_pass').val(password);
                    }
                },
                onCancel: function () {
                    //点击取消
                    $('#e-card-pay').removeAttr('checked');
                }
            });
            document.getElementById("weui-prompt-input").type="password";
        });
    })
    function getWxOrder(sell_price,project_name,order_cd,check_num,member_pass){
        $.ajax({
            url:'<?php echo url("wx/order/ajaxGetWxOrder"); ?>',
            type:'post',
            data:{sell_price:sell_price,project_name:project_name,order_cd:order_cd,check_num:check_num,member_pass:member_pass},
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
</body>
</html>
