<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"E:\phpStudy\WWW\WEB/application/wx\view\user\costlist.html";i:1510210069;s:56:"E:\phpStudy\WWW\WEB/application/wx\view\public\foot.html";i:1498214442;}*/ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="telephone=no" name="format-detection" />
<title>后舍</title>
<link href="__STATIC__css/HTp-style2.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="__STATIC__css/jquery.flipster.css">
<script type="text/javascript" src="__STATIC__js/jquery.min.js"></script>
</head>

<body style="color:#666;">
<div class="chongzhi-top">
	<a href="javascript:void(0)" onclick="window.history.go(-1)" class="back"><span></span></a>
    <h1>消费记录</h1>
</div>


	<ul style="margin-top:50px">
        <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): if(!empty($vo['COST_AMT'])): ?>
            <li style="background-color:#fff;border-bottom:1px solid #ddd;height:70px;">
                <div class="chongzhi-txt" style="margin:0;">
                    <span class="left-txt">
                      <p class="left-title"><?php if(!(empty($vo['StoreName']) || (($vo['StoreName'] instanceof \think\Collection || $vo['StoreName'] instanceof \think\Paginator ) && $vo['StoreName']->isEmpty()))): ?><?php echo $vo['StoreName']; else: ?>后舍<?php endif; ?></p>
                    </span>
                    <span class="right-img" style="padding-right:5px;">
                        <p  class="right-title">
                            <span class="right-type"><?php echo $vo['CardOperatType']; ?>：</span>
                            <span class="right-amt"><?php echo round($vo['COST_AMT'],2); ?>元</span></p>
                        <p class="right-date"><?php echo mb_substr($vo['CREATE_DATE'],0,10); ?></p>
                    </span>
                </div>
            </li>
            <?php endif; endforeach; endif; else: echo "" ;endif; else: ?>
            <div style="text-align:center;padding-top:15%;font-size:16px;"> 暂无充值记录</div>
        <?php endif; ?>
    </ul>
<ul class="foot">
    <li><a href="__PATH__wx/home/home"><p class="f-p1"><img src="__STATIC__images/ftt_03.png"  width="23"></p><p class="f-p2">首页</p></a></li>
    <li><a href="__PATH__wx/order/orderList"><p class="f-p1"><img src="__STATIC__images/lz_13.jpg" width="23"></p><p class="f-p2">订单</p></a></li>
    <li><a href="__PATH__wx/home/getMore"><p class="f-p1"><img src="__STATIC__images/lz_19.jpg" width="23"></p><p class="f-p2">商城</p></a></li>
    <li><a href="__PATH__wx/user/me"><p class="f-p1"><img src="__STATIC__images/lz_21.jpg" width="20"></p><p class="f-p2">会员中心</p></a></li>
</ul><!--foot end-->
</body>
</html>
