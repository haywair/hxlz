<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"E:\phpStudy\WWW\WEB/application/wx\view\home\binding.html";i:1515139867;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;}*/ ?>
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
				<form id="form1" action="__PATH__wx/home/binding" method="post">
					<li>
						<input type="text" name="phone" id='phone' class="bd-int1" placeholder="请输入您的手机号">
						<input  type="button" id="btnSendCode"  onclick="submitForm();" class="bd-a1" value="获取验证码">
					</li>
				</form>
				<form id="form2" action="__PATH__wx/home/binding" method="post">
					<li><input type="text" name="yan" class="bd-int2" placeholder="请输入验证码"></li>
					<li><input type="text" name="user_name" id="user_name" class="bd-int2" placeholder="请输入您的姓名"></li>
					<li>
						<input type="radio" name="sex" class="bd-int2" checked value="1">男
						<input type="radio" name="sex" class="bd-int2" value="2" style="margin-left:10px;">女
					</li>
					<input type="hidden" name="setpwd" value="1">
			</ul>
	</div><!--box1 end-->
	<div class="foot1">
		 <input type="button"  id="subBtn"  class="ft-a1" value="立即绑定"><!--这里？-->
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
		$.ajax({
			type:'post',
			url:'<?php echo url("wx/home/checkMobile"); ?>',
			data:{phone:phone},
			dataType:'json',
			success:function(result){
				if(result.state == 'success'){
					curCount = count;
					//设置button效果，开始计时
					$("#btnSendCode").attr("disabled", "true");
					$("#btnSendCode").val(curCount + "秒后重新发送");
					InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次

					$.ajax({
						type:"POST",
						url:"http://cs.huaxialiangzi.com/index.php/wx/home/binding",
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
				}else if(result.state == 101){
					alert('此手机号码已经绑定，请勿重复绑定');return false;
				}else{
					alert(result.msg);return false;
				}
			}
		})

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
    //sub处理函数
	$('#subBtn').click(function(){
		var user_name = $('#user_name').val();
		if(!user_name){
			alert('请输入您的姓名');return false;
		}
		$('#form2').submit();
	});
    /*function submit(){
        var user_name = $('#user_name').val();
        if(!user_name){
            alert('请输入您的姓名');return false;
		}
		$('#form2').submit();
        /!*$.ajax({
            type:"POST",
            url:"http://houshe.huaxialiangzi.com/index.php/wx/home/xxxx",
            data: $("#form2").serialize(),
            success:function(data){				
                if(data==2222){
                    console.log(2222);
                }else {
                    console.log(data);
                }

            },
        });*!/
    }*/
</script>
</body>
</html>
