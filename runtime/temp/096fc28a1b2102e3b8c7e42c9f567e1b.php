<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:60:"E:\phpStudy\WWW\WEB/application/wx\view\store\storelist.html";i:1502705739;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;s:56:"E:\phpStudy\WWW\WEB/application/wx\view\public\foot.html";i:1498214442;}*/ ?>

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
<?php if(!(empty($data) || (($data instanceof \think\Collection || $data instanceof \think\Paginator ) && $data->isEmpty()))): ?>
<div class="box1" style="margin-bottom:70px;">
    <dl class="tb-dl">

        <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
        <a href="__PATH__wx/store/StoreDetailed?sid=<?php echo $v['STORE_CD']; ?>">
        <dd>
            <span class="tb-s1"><img style="height:65px;" src="__UPLOADS__<?php echo $v['STORE_IMAGE']; ?>"></span>
            <span class="tb-s2">
                    <p class="tb-p1"><?php echo $v['STORE_NAME']; ?></p>
                    <p class="tb-p3"><?php echo $v['STORE_INFO']; ?></p>
				</span>
            <span class="tb-s3"><p class="tb-pa"><?php echo $v['juli']; ?>km</p></span>
        </dd>
        </a>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </dl>
</div><!--box1 end-->
<?php else: ?>
<div style="background-color:#fff;text-align:center;color:#999;padding-top:30%;font-size:14px;height:400px;width:100%;">
    该城市暂未开通门店，敬请期待......
</div>
<?php endif; ?>
<ul class="foot">
    <li><a href="__PATH__wx/home/home"><p class="f-p1"><img src="__STATIC__images/ftt_03.png"  width="23"></p><p class="f-p2">首页</p></a></li>
    <li><a href="__PATH__wx/order/orderList"><p class="f-p1"><img src="__STATIC__images/lz_13.jpg" width="23"></p><p class="f-p2">订单</p></a></li>
    <li><a href="__PATH__wx/home/getMore"><p class="f-p1"><img src="__STATIC__images/lz_19.jpg" width="23"></p><p class="f-p2">商城</p></a></li>
    <li><a href="__PATH__wx/user/me"><p class="f-p1"><img src="__STATIC__images/lz_21.jpg" width="20"></p><p class="f-p2">会员中心</p></a></li>
</ul><!--foot end-->
</body>
</html>
