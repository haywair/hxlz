<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"E:\phpStudy\WWW\WEB/application/wx\view\user\offlinecardcode.html";i:1515395817;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;}*/ ?>
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
<body style="background-color:#dfdfdf;">
    <div class="card-img-container">
        <div class="bar-img">
            <?php echo $barImg; ?>
            <p style="margin-top:10px;"><?php echo $cardNo; ?></p>
        </div>
        <div class="qr-img">
            <?php echo $qrImg; ?>
        </div>
        <div class="rule-area">
			<span>
            <?php echo htmlspecialchars_decode($rule); ?>
			</span>
        </div>
    </div>
</body>

