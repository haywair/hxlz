<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:61:"E:\phpStudy\WWW\WEB/application/wx\view\store\navigation.html";i:1515230818;}*/ ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        body, html {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
        #allmap{width: 100%;height:90%;overflow: hidden;margin:0;font-family:"微软雅黑";}
        .BMap_cpyCtrl{display:none;}
        .anchorBL{display:none;}
    </style>    
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=9e9v0Vdhe4HyFS2UCrFcWfAp1Vefsuoe"></script>
    <script type="text/javascript" src="__STATIC__js/jquery.min.js"></script>
    <title>门店导航</title>
</head>
<body>
<div id="allmap"></div>
<div  style="padding:10px 0 10px 0;text-align:center;">
    <button id="bus">公交</button>
    <button id="drive" style="margin-left:30%;">自驾</button>
</div>
</body>
</html>
<script type="text/javascript">
    //门店经纬坐标
    var storeLat = parseFloat("<?php echo $storeLat; ?>");
    var storeLong = parseFloat("<?php echo $storeLong; ?>");	
    //用户经纬坐标
    var userLat = "<?php echo $userDirect['userLat']; ?>";
    var userLong = "<?php echo $userDirect['userLong']; ?>";
    // 百度地图API功能
    var map = new BMap.Map("allmap");            // 创建Map实例
    var storePoint = new BMap.Point(storeLong,storeLat);
    map.centerAndZoom(storePoint, 15);
    //添加标注
    var marker = new BMap.Marker(storePoint);  // 创建标注
    map.addOverlay(marker);               // 将标注添加到地图中
    marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
	
    //用户坐标点
    var userPoint = new BMap.Point(userLong,userLat);
    var transit = new BMap.TransitRoute(map, {
        renderOptions: {map: map}
    });
    var driving = new BMap.DrivingRoute(map, {renderOptions:{map: map, autoViewport: true}});

    $(function(){
        var latitude = "<?php echo $storeLat; ?>";
        var longitude = "<?php echo $storeLong; ?>";
        var storeCd = "<?php echo $storeCd; ?>";
        //公交
        $('#bus').click(function(){
           // transit.search(userPoint,storePoint);
           window.location.href = "<?php echo url('wx/store/transit'); ?>?latitude="+latitude+'&longitude='+longitude+'&storeCd='+storeCd;
        });
        //自驾
        $('#drive').click(function(){
           // driving.search(userPoint,storePoint);
            window.location.href = "<?php echo url('wx/store/drive'); ?>?latitude="+latitude+'&longitude='+longitude+'&storeCd='+storeCd;
        });
    })
</script>