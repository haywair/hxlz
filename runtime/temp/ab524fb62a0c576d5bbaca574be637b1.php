<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:60:"E:\phpStudy\WWW\WEB/application/wx\view\user\userticket.html";i:1496975412;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;s:56:"E:\phpStudy\WWW\WEB/application/wx\view\public\foot.html";i:1498214442;}*/ ?>
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
    <script type="text/javascript">
        $(document).ready(function() {
            jQuery.jqtab = function(tabtit,tab_conbox,shijian) {
                $(tab_conbox).find("li").hide();
                $(tabtit).find("li:first").addClass("thistabw").show();
                $(tab_conbox).find("li:first").show();

                $(tabtit).find("li").bind(shijian,function(){
                    $(this).addClass("thistabw").siblings("li").removeClass("thistabw");
                    var activeindex = $(tabtit).find("li").index(this);
                    $(tab_conbox).children().eq(activeindex).show().siblings().hide();
                    return false;
                });

            };
            /*调用方法如下：*/
            $.jqtab("#tabsw","#tab_conboxw","click");

        });
    </script>
    <div id="tabboxw">
        <ul class="tabsw" id="tabsw">
            <li><a href="#">票券</a></li>
            <li><a href="#">赠券</a></li>
        </ul>
        <ul class="tab_conboxw" id="tab_conboxw">
            <li class="tab_conw">
                <script type="text/javascript">
                    $(document).ready(function() {
                        jQuery.jqtab = function(tabtit,tab_conbox,shijian) {
                            $(tab_conbox).find("dd").hide();
                            $(tabtit).find("dd:first").addClass("thistabe").show();
                            $(tab_conbox).find("dd:first").show();

                            $(tabtit).find("dd").bind(shijian,function(){
                                $(this).addClass("thistabe").siblings("dd").removeClass("thistabe");
                                var activeindex = $(tabtit).find("dd").index(this);
                                $(tab_conbox).children().eq(activeindex).show().siblings().hide();
                                return false;
                            });

                        };
                        /*调用方法如下：*/
                        $.jqtab("#tabse","#tab_conboxe","click");

                    });
                </script>
                <div id="tabboxe">
                    <dl class="tabse" id="tabse">
                        <dd><a href="#">未使用</a></dd>
                        <dd><a href="#">已使用</a></dd>
                        <dd><a href="#">已失效</a></dd>
                    </dl>
                    <dl class="tab_conboxe" id="tab_conboxe">
                        <dd class="tab_cone">
                            <div class="pq-dv1">
                    	<span class="pq-s1">
                        	<p class="pq-p1">券号：06668888888888</p>
                            <p class="pq-p2"><font class="pq-f1">￥20</font><s class="pq-f2">￥40</s></p>
                            <p class="pq-p3">有效期:2017.10.10-1017.12.12</p>
                        </span>
                                <span class="pq-s2"><img src="images/pw_08.jpg"></span>
                            </div><!--pq-dv1 end-->
                            <div class="pq-dv1">
                    	<span class="pq-s1">
                        	<p class="pq-p1">券号：06668888888888</p>
                            <p class="pq-p2"><font class="pq-f1">￥20</font><s class="pq-f2">￥40</s></p>
                            <p class="pq-p3">有效期:2017.10.10-1017.12.12</p>
                        </span>
                                <span class="pq-s2"><img src="images/pw_08.jpg"></span>
                            </div><!--pq-dv1 end-->
                            <div class="pq-dv1">
                    	<span class="pq-s1">
                        	<p class="pq-p1">券号：06668888888888</p>
                            <p class="pq-p2"><font class="pq-f1">￥20</font><s class="pq-f2">￥40</s></p>
                            <p class="pq-p3">有效期:2017.10.10-1017.12.12</p>
                        </span>
                                <span class="pq-s2"><img src="images/pw_08.jpg"></span>
                            </div><!--pq-dv1 end-->
                            <div class="pq-dv1">
                    	<span class="pq-s1">
                        	<p class="pq-p1">券号：06668888888888</p>
                            <p class="pq-p2"><font class="pq-f1">￥20</font><s class="pq-f2">￥40</s></p>
                            <p class="pq-p3">有效期:2017.10.10-1017.12.12</p>
                        </span>
                                <span class="pq-s2"><img src="images/pw_08.jpg"></span>
                            </div><!--pq-dv1 end-->
                        </dd>
                        <dd class="tab_cone">2</dd>
                        <dd class="tab_cone">3</dd>
                    </dl>
                </div>
            </li>
            <li class="tab_conw">2</li>
        </ul>
    </div>
</div><!--box1 end-->
<ul class="foot">
    <li><a href="__PATH__wx/home/home"><p class="f-p1"><img src="__STATIC__images/ftt_03.png"  width="23"></p><p class="f-p2">首页</p></a></li>
    <li><a href="__PATH__wx/order/orderList"><p class="f-p1"><img src="__STATIC__images/lz_13.jpg" width="23"></p><p class="f-p2">订单</p></a></li>
    <li><a href="__PATH__wx/home/getMore"><p class="f-p1"><img src="__STATIC__images/lz_19.jpg" width="23"></p><p class="f-p2">商城</p></a></li>
    <li><a href="__PATH__wx/user/me"><p class="f-p1"><img src="__STATIC__images/lz_21.jpg" width="20"></p><p class="f-p2">会员中心</p></a></li>
</ul><!--foot end-->
</body>
</html>