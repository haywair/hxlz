<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:62:"E:\phpStudy\WWW\WEB/application/wx\view\home\bindpassword.html";i:1499070740;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;}*/ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="telephone=no" name="format-detection" />
    <title>华夏良子</title>
    <link rel="stylesheet" href="__STATIC__css/weui.min.css">
    <link rel="stylesheet" href="__STATIC__css/jquery-weui.min.css">
    <link rel="stylesheet" href="__STATIC__css/swiper.min.css">
    <link href="__STATIC__css/HTp-style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__STATIC__js/jquery.min.js"></script>
    <script type="text/javascript" src="__STATIC__js/jquery-weui.min.js"></script>
    <script type="text/javascript" src="__STATIC__js/lbnews.js"></script>
    <script type="text/javascript" src="__STATIC__js/swiper.min.js"></script>
</head>
<body>

	<div class="box1">
			<ul class="bd-ul">
				<form id="form1" action="__PATH__wx/home/bindPassword" method="post">
					<li>
						<input type="text" name="phone" id='phone' class="bd-int1" placeholder="请输入您的手机号">
						<input  type="button" id="btnSendCode"  onclick="submitForm();" class="bd-a1" value="获取验证码">
					</li>
				</form>
				<form id="form2" action="__PATH__wx/home/bindPassword" method="post">
					<li><input type="text" name="yan" class="bd-int2" placeholder="请输入验证码"></li>
			</ul>
	</div><!--box1 end-->
	<div class="foot1">
		 <input type="button"  onclick="submit();" class="ft-a1" value="立即修改"><!--这里？-->
	</div>
	</form>
<script>
    var InterValObj; //timer变量，控制时间
    var count = 70; //间隔函数，1秒执行
    var curCount;//当前剩余秒数
    function submitForm(){
    	var phone = $('#phone').val();
		if(!phone){
			alert('请输入您的手机号码');return false;
		}
        curCount = count;
        //设置button效果，开始计时
        $("#btnSendCode").attr("disabled", "true");
        $("#btnSendCode").val(curCount + "秒后重新发送");
        InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次

        $.ajax({
            type:"POST",
            url:"http://houshe.huaxialiangzi.com/index.php/wx/home/bindPassword",
            data: $("#form1").serialize(),
            success:function(data){				
                if(data==2222){
                    console.log(2222);
				}else {
                    console.log(data);
					//alert(data.sub_msg);
				}
            }
		});
    }
    function SetRemainTime() {
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            $("#btnSendCode").removeAttr("disabled");//启用按钮
            $("#btnSendCode").val("重新发送验证码");
        }
        else {
            curCount--;
            $("#btnSendCode").val(curCount + "秒后重新发送");
        }
    }




    //timer处理函数





    function submit(){
        $.ajax({
            type:"POST",
            url:"http://liangzi.xz6699.com/index.php/wx/home/bindPassword",
            data: $("#form2").serialize(),
            success:function(data){				
                if(data==2222){
                    console.log(2222);
                }else {
                    console.log(data);
                }

            },
        });
    }
</script>
</body>
</html>
