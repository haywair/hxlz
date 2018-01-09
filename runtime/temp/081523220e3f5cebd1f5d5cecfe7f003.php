<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"E:\phpStudy\WWW\WEB/application/wx\view\store\transit.html";i:1511502697;}*/ ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        body, html {width: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
        #allmap{width: 100%;height:90%;overflow: hidden;margin:0;font-family:"微软雅黑";}
        .BMap_cpyCtrl{display:none;}
        .anchorBL{display:none;}
        #l-map{height:300px;width:100%;}
        #r-result,#r-result table{width:100%;font-size:12px;}
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=9e9v0Vdhe4HyFS2UCrFcWfAp1Vefsuoe"></script>
    <script type="text/javascript" src="__STATIC__js/jquery.min.js"></script>
    <title>门店导航</title>
</head>
<body>
<div  style="padding:10px 0 10px 0;text-align:center;">
    <div id="l-map"></div>
    <div id="r-result"></div>
</div>
</body>
</html>
<script type="text/javascript">
    //门店经纬坐标
    var storeLat = "<?php echo $storeLat; ?>";
    var storeLong = "<?php echo $storeLong; ?>";
    //用户经纬坐标
    var userLat = "<?php echo $userDirect['userLat']; ?>";
    var userLong = "<?php echo $userDirect['userLong']; ?>";
    var disInfo  = "<?php echo $disInfo['address']; ?>"+"<?php echo $disInfo['addrName']; ?>";
	var storeDisInfo = "<?php echo $storeDisInfo['address']; ?>"+"<?php echo $storeDisInfo['addrName']; ?>";
    var map = new BMap.Map("l-map");
    map.centerAndZoom(new BMap.Point(117.1256,36.651216), 12);

    var transit = new BMap.TransitRoute(map, {
        renderOptions: {map: map, panel: "r-result"}
    });
    transit.search(disInfo,storeDisInfo);
</script>