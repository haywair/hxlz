<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:52:"E:\phpStudy\WWW\WEB/application/wx\view\user\me.html";i:1515216010;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;s:56:"E:\phpStudy\WWW\WEB/application/wx\view\public\foot.html";i:1498214442;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <div class="gx-top">
        <a href="__PATH__wx/user/userInfo"><span class="gx-s1"><img src="<?php echo $data['PHOTO_HEAD']; ?>"></span></a>
        <span class="gx-s2">
        	<p class="gx-p1">昵称:<?php echo $data['NICK_NAME']; ?></p>
            <p class="gx-p1">手机号:<?php echo $data['TEL_NO']; ?></p>
        </span>
        <span class="gx-s3"><a href="javascript:void(0);" class="gx-a1">解绑</a></span>
    </div><!--gx-top end-->
    <ul class="gx-ul">
        <a href="__PATH__wx/user/card"> <li><span class="gx-s1a"> > </span><span class="gx-s2a mt10"><img src="__STATIC__images/gx_03.png"> </span><span class="gx-s3a">我的会员卡</span></li></a>
        <a href="__PATH__wx/home/getMore<!-- userTicket -->"><li><span class="gx-s1a"> > </span><span class="gx-s2a mt10"><img src="__STATIC__images/gx_06.png"> </span><span class="gx-s3a">我的票务</span></li></a>
        <a href="__PATH__wx/order/orderList"><li><span class="gx-s1a"> > </span><span class="gx-s2a mt10"><img src="__STATIC__images/gx_09.png" style="width:16px;"> </span><span class="gx-s3a">我的订单</span></li></a>
        <a href="__PATH__wx/user/userReview"><li><span class="gx-s1a"> > </span><span class="gx-s2a mt10"><img src="__STATIC__images/gx_12.png" style="width:16px;"> </span><span class="gx-s3a">我的评价</span></li></a>
        <a href="__PATH__wx/home/bindPassword"><li><span class="gx-s1a"> > </span><span class="gx-s2a mt10"><img src="__STATIC__images/gx_03.png"> </span><span class="gx-s3a">重置密码</span></li></a>
    </ul>
</div><!--box1 end-->
<script>
    $(function(){
        //解除绑定
        $('.gx-a1').click(function(){
            $.confirm({
                title: '解绑',
                text: '确定解除绑定？',
                onOK: function () {
                    //点击确认
                    window.location.href = "__PATH__wx/user/unwrap";
                },
                onCancel: function () {
                }
            });
        });
    });
</script>
<ul class="foot">
    <li><a href="__PATH__wx/home/home"><p class="f-p1"><img src="__STATIC__images/ftt_03.png"  width="23"></p><p class="f-p2">首页</p></a></li>
    <li><a href="__PATH__wx/order/orderList"><p class="f-p1"><img src="__STATIC__images/lz_13.jpg" width="23"></p><p class="f-p2">订单</p></a></li>
    <li><a href="__PATH__wx/home/getMore"><p class="f-p1"><img src="__STATIC__images/lz_19.jpg" width="23"></p><p class="f-p2">商城</p></a></li>
    <li><a href="__PATH__wx/user/me"><p class="f-p1"><img src="__STATIC__images/lz_21.jpg" width="20"></p><p class="f-p2">会员中心</p></a></li>
</ul><!--foot end-->
</body>
</html>
