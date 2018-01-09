<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"E:\phpStudy\WWW\WEB/application/wx\view\store\storedetailed.html";i:1513413643;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;s:56:"E:\phpStudy\WWW\WEB/application/wx\view\public\foot.html";i:1498214442;}*/ ?>
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
    <div class="page-swipe">
        <header>
            <div id="slider" class="swipe" style="visibility: visible;">
                <div class="swipe-wrap">
                    <?php if(is_array($storeImg) || $storeImg instanceof \think\Collection || $storeImg instanceof \think\Paginator): if( count($storeImg)==0 ) : echo "" ;else: foreach($storeImg as $key=>$vo): ?>
                    <div>
                        <div class="wrap">
                            <div class="image"><img style="height: 230px;" src="__UPLOADS__<?php echo $vo; ?>"></div>
                        </div>
                    </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    
                </div><!--swipe-wrap end-->
            </div><!--slider end-->
            <nav>
                <ul id="position">
                    <li class="on"></li>
                    <li class=""></li>
                    <li class=""></li>
                </ul>
            </nav>
        </header>
        <script Language="JavaScript" src="__STATIC__js/swipe.js"></script>
        <script>
            var slider =
                Swipe(document.getElementById('slider'), {
                    auto: 3000,
                    continuous: true,
                    callback: function(pos) {

                        var i = bullets.length;
                        while (i--) {
                            bullets[i].className = ' ';
                        }
                        bullets[pos].className = 'on';

                    }
                });
            var bullets = document.getElementById('position').getElementsByTagName('li');
        </script>
    </div><!--page-swipe end-->
    <div class="xq-name" style="height:24px">
    	<span class="xq-s1">
        	<p class="xq-p1"><?php echo $data['STORE_NAME']; ?></p>
        </span>
        <span class="xq-s2" style="margin:0">
        	<p class="xq-p3">人均：70元</p>
        </span>
    </div><!--xq-name end-->
    <div class="xq-md" id="direction">
        <a href="#" class="xq-a2"><span class="xq-s3a"> </span><span class="xq-s4"><img src="__STATIC__images/xm_03.png" width="15"></span><span class="xq-s5a"><?php echo $data['ADDRESS']; ?></span></a>
    </div><!--xq-md end-->
    <div class="xq-md">
        <a href="tel:<?php echo $data['OFFICE_TEL']; ?>" class="xq-a2"><span class="xq-s3a"> > </span><span class="xq-s4"><img src="__STATIC__images/xm_07.png" width="15"></span><span class="xq-s5a"><?php echo $data['OFFICE_TEL']; ?></span></a>
    </div><!--xq-md end-->
    <div class="xq-fw">
        <p class="xqf-p1">门店项目</p>
        <dl class="tb-dl">
            <?php if(is_array($pdata) || $pdata instanceof \think\Collection || $pdata instanceof \think\Paginator): $i = 0; $__LIST__ = $pdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <dd class="ddr">
                <span class="tb-s1">
                    <a href="__PATH__wx/goods/goodsDetailed?pid=<?php echo $v['PROJECT_CD']; ?>&sid=<?php echo $sid; ?>"><img style="height: 74px;" src="__UPLOADS__<?php echo $v['PROJECT_IMAGE']; ?>"></a>
                </span>
                <span class="tb-s2 tb-s2r">
                    <p class="tb-p1"><a href="__PATH__wx/goods/goodsDetailed?pid=<?php echo $v['PROJECT_CD']; ?>" style="color:#888;" ><?php echo $v['PROJECT_NAME']; ?></a></p>
                    <p class="tb-p2r"><a href="__PATH__wx/goods/goodsDetailed?pid=<?php echo $v['PROJECT_CD']; ?>" style="color:#ff9c00;"><font class="font1"><?php echo $v['PRICE']; ?>元</font><span class="market-price">￥<?php echo $v['MARKET_PRICE']; ?></span><font class="font2">/<?php echo $v['PROJECT_TIME']; ?>分钟</font></a></p>
                    <?php if(!empty($v['PRICEAT_NAME'])): ?>
                        <div class="tb-p3 div-p3">
                            <div class="priceat_name"><a href="__PATH__wx/goods/goodsDetailed?pid=<?php echo $v['PROJECT_CD']; ?>" style="color:#ff9c00;"><?php echo $v['PRICEAT_NAME']; ?></a></div>
                        </div>
                    <?php endif; if(empty($v['PRICEAT_NAME'])): ?>
                        <div class="tb-p3 div-p3">
                            <div class="priceat_name"><a href="__PATH__wx/goods/goodsDetailed?pid=<?php echo $v['PROJECT_CD']; ?>" style="color:#ff9c00;">项目简介</a></div>
                        </div>
                    <?php endif; ?>
                </span>
                <!--<span class="tb-s3r"><a href="__PATH__wx/goods/goodsDetailed?pid=<?php echo $v['PROJECT_CD']; ?>&sid=<?php echo $sid; ?>"  class="tb-a1r mt18r"><p>立即</p><p>预约</p></a></span>-->
				<span class="tb-s3r"><a href="__PATH__wx/order/orderPlain?pid=<?php echo $v['PROJECT_CD']; ?>&store_cd=<?php echo $data['STORE_CD']; ?>"  class="tb-a1r mt18r"><p>立即</p><p>预约</p></a></span>
            </dd>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </dl>
    </div>
    <!--<div class="xq-fw">
        <p class="xqf-p1">门店票券</p>
        <dl class="tb-dl">
           &lt;!&ndash; <?php if(is_array($pdata) || $pdata instanceof \think\Collection || $pdata instanceof \think\Paginator): $i = 0; $__LIST__ = $pdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <dd>
                <span class="tb-s1"><a href="#"><img style="height: 65px;" src="__UPLOADS__<?php echo $v['PROJECT_IMAGE']; ?>"></a></span>
                <span class="tb-s2">
                    <p class="tb-p1"><?php echo $v['PROJECT_NAME']; ?></p>
                    <p class="tb-p2"><font class="font1"><?php echo $v['PRICE']; ?>元</font><font class="font2">/<?php echo $v['PROJECT_TIME']; ?>分钟</font></p>
                    <p class="tb-p3"><?php echo $v['PROJECT_INTRODUCE']; ?></p>
				</span>
                <span class="tb-s3"><a href="__PATH__wx/goods/goodsDetailed?sid=<?php echo $data['STORE_CD']; ?>&pid=<?php echo $v['PROJECT_CD']; ?>" class="tb-a1 mt18">立即预约</a></span>
            </dd>
            <?php endforeach; endif; else: echo "" ;endif; ?>&ndash;&gt;
        </dl>
    </div>-->
</div><!--box1 end-->
<script>
    $(function(){
        var storeLat = "<?php echo $data['LATITUDE']; ?>";
        var storeLong = "<?php echo $data['LONGITUDE']; ?>";
        var storeCd = "<?php echo $data['STORE_CD']; ?>";
        $('#direction').click(function(){
            if(storeLat<=0 || storeLong<=0){
                alert('该门店暂未设置门店导航服务！');
                return false;
            }else{
                window.location.href="<?php echo url('wx/store/navigation'); ?>"+'?storeLat='+storeLat+'&storeLong='+storeLong+'&storeCd='+storeCd;
            }
        });
    })
</script>
<ul class="foot">
    <li><a href="__PATH__wx/home/home"><p class="f-p1"><img src="__STATIC__images/ftt_03.png"  width="23"></p><p class="f-p2">首页</p></a></li>
    <li><a href="__PATH__wx/order/orderList"><p class="f-p1"><img src="__STATIC__images/lz_13.jpg" width="23"></p><p class="f-p2">订单</p></a></li>
    <li><a href="__PATH__wx/home/getMore"><p class="f-p1"><img src="__STATIC__images/lz_19.jpg" width="23"></p><p class="f-p2">商城</p></a></li>
    <li><a href="__PATH__wx/user/me"><p class="f-p1"><img src="__STATIC__images/lz_21.jpg" width="20"></p><p class="f-p2">会员中心</p></a></li>
</ul><!--foot end-->
</body>
</html>