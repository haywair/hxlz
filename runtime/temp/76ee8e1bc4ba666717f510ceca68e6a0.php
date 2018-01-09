<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:55:"E:\phpStudy\WWW\WEB/application/wx\view\home\index.html";i:1515200405;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;s:56:"E:\phpStudy\WWW\WEB/application/wx\view\public\foot.html";i:1498214442;}*/ ?>
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
    <div class="b-top" >
        <div class="bt-dv"><span class="bt-ds1"><img src="__STATIC__images/lz_03.png" width="12"></span><a href="<?php echo url('wx/home/citySelect'); ?>" id="city_name"></a></div>
        <!-- <img src="__STATIC__images/hx_03.jpg" class="bt-img"> --> <span class="title-spa">华夏良子 线上预约</span>
    </div><!--b-top-->
    <input type="hidden" id="store_cd" >
    <div class="page-swipe">
        <header>
            <div id="slider" class="swipe" style="visibility: visible;">
                <div class="swipe-wrap">
                    <?php if(is_array($cdata) || $cdata instanceof \think\Collection || $cdata instanceof \think\Paginator): $i = 0; $__LIST__ = $cdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <div>
                        <div class="wrap">
                            <div class="image"><img style="height: 230px;" src="__UPLOADS__public/uploads/<?php echo $v['CAROUSEL_PIC']; ?>"></div>
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
    <div class="fjmd" style="font-size:15px;height:100px;">
        <p>
        <!--<span class="xq-s4" style="margin-top:6px"><img src="__STATIC__images/xm_03.png" width="15" height="18px"></span>-->
        <a href="__PATH__wx/store/storeList">其他门店 ></a>
        <!--<span style="color:#111">附近门店：</span><span id="store_name"></span>-->
        <span class="xq-s4" style="margin-top:8px"><img src="__STATIC__images/xm_03.png" width="15" height="18px"></span><span style="color:#111;font-size:14px;">离我最近的门店：<?php echo ceil($sdata[0]['juli']); ?>m</span>
        </p>
        <p style="height:20px;line-height:20px;margin-left:22px">门店：<?php echo $sdata[0]['STORE_NAME']; ?></p>
        <p style="height:20px;line-height:20px;margin-left:22px">地址：<?php echo $sdata[0]['ADDRESS']; ?></p>
        <p style="height:20px;line-height:20px;margin-left:22px">电话：<?php echo $sdata[0]['OFFICE_TEL']; ?></p>
    </div><!--fjmd end-->
    <div id="tabbox" style="margin:10px 0 0 0;">
        <div id="topNav" class="swiper-container" style="width:100%;">
        <ul class="tabs swiper-wrapper" id="tabs">
            <li class="swiper-slide active active-border" data-id="0" id="li-0" ><a href="#" class="active-color">全部</a></li>
            <?php if(is_array($categoryList) || $categoryList instanceof \think\Collection || $categoryList instanceof \think\Paginator): if( count($categoryList)==0 ) : echo "" ;else: foreach($categoryList as $key=>$vo): ?>
                <li class="swiper-slide" id="li-<?php echo $vo['TYPE_ID']; ?>" data-id="<?php echo $vo['TYPE_ID']; ?>"><a href="#"><?php echo $vo['TYPE_NAME']; ?></a></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        </div>
        <script type="text/javascript">
            var mySwiper = new Swiper('#topNav', {
                freeMode: true,
                freeModeMomentumRatio: 0.5,
                slidesPerView: 'auto',

            });

            swiperWidth = mySwiper.container[0].clientWidth
            maxTranslate = mySwiper.maxTranslate();
            maxWidth = -maxTranslate + swiperWidth / 2

            $(".swiper-container").on('touchstart', function(e) {
                e.preventDefault()
            })

            mySwiper.on('tap', function(swiper, e) {

                e.preventDefault()

                slide = swiper.slides[swiper.clickedIndex]
                slideLeft = slide.offsetLeft
                slideWidth = slide.clientWidth
                slideCenter = slideLeft + slideWidth / 2
                // 被点击slide的中心点

                mySwiper.setWrapperTransition(300)

                if (slideCenter < swiperWidth / 2) {

                    mySwiper.setWrapperTranslate(0)

                } else if (slideCenter > maxWidth) {

                    mySwiper.setWrapperTranslate(maxTranslate)

                } else {

                    nowTlanslate = slideCenter - swiperWidth / 2

                    mySwiper.setWrapperTranslate(-nowTlanslate)

                }
                var typeId =  $("#topNav .swiper-slide").eq(swiper.clickedIndex).attr('data-id');
                var store_cd = $('#store_cd').val();
                $("#topNav .active a").removeClass('active-color');
                $("#topNav .active").removeClass('active-border');

                $("#topNav .swiper-slide").eq(swiper.clickedIndex).addClass('active');
                $("#topNav .swiper-slide").eq(swiper.clickedIndex).addClass('active-border');
                $("#li-"+typeId+' a').addClass('active-color');
                $('#project_content').html('<dd style="text-align:center;padding-top:20px;color:#666;">正在加载</dd>');
                $.ajax({
                    url:"<?php echo url('wx/home/ajaxGetProject'); ?>",
                    type:'post',
                    dataType:'json',
                    data:{typeId:typeId,store_cd:store_cd},
                    success:function(result){
                        if(result.state == 'success'){
                            var info = result.info;
                            var str = '';
                            $.each(info,function(i,item){
                                str += '<dd class="ddr"><span class="tb-s1">';
                                str +=  '<a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'"><img style="height: 74px;" src="__UPLOADS__'+item.PROJECT_IMAGE+'"></a></span>';
                                str +=  '<span class="tb-s2 tb-s2r">';
                                str +=  '<p class="tb-p1"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'" style="color:#888;" >'+item.PROJECT_NAME+'</a></p>';
                                str +=  ' <p class="tb-p2r"><font class="font1">'+item.PRICE+'元</font>';
                                if(item.MARKET_PRICE) {
                                    str += ' <span class="market-price">￥' + item.MARKET_PRICE + '</span>';
                                }
                                str +=  '<font class="font2">/'+item.PROJECT_TIME+'分钟</font></p>';
                                if("undefined" != typeof item.PRICEAT_NAME) {
                                    str += '<div class="tb-p3 div-p3"><div class="priceat_name"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'" style="color:#ff9c00;" >' + item.PRICEAT_NAME+'</a></div></div>';
                                }else{
                                    str += '<div class="tb-p3 div-p3"><div class="priceat_name"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'" style="color:#ff9c00;" >项目简介</a></div></div>';
								}
                                str +=  '</span>';
                                //str +=  '<span class="tb-s3r"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'" class="tb-a1r mt18r"><p>立即</p><p>预约</p></a></span>';
								str +=  '<span class="tb-s3r"><a href="__PATH__wx/order/orderPlain?pid='+item.PROJECT_CD+'&store_cd='+item.STORE_CD+'" class="tb-a1r mt18r"><p>立即</p><p>预约</p></a></span>';
                                str +=  '</dd>';
                            })
                            $('#project_content').html(str);
                        }else if(result.state == 101){
                            var str = '<dd style="text-align:center;margin-top:10px;">暂无相关项目</dd>';
                            $('#project_content').html(str);
                        }else{
                            alert(result.msg);
                        }
                    }

                });

            })
        </script>
        <ul class="tab_conbox" id="tab_conbox">
            <li class="tab_con" style="display:block;">
                <dl class="tb-dl" id="project_content" >
                    <dd style="text-align:center;padding-top:20px;color:#666;">正在加载</dd>
                </dl>
            </li>
        </ul>
    </div>
</div><!--box1 end-->
<!-- 定位部分 -->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
            debug: false,
            appId: '<?php echo $js['appId']; ?>',
            timestamp: <?php echo $js['timestamp']; ?>,
        nonceStr: '<?php echo $js['nonceStr']; ?>',
        signature: '<?php echo $js['signature']; ?>',
        jsApiList: [
        'checkJsApi',
        'openLocation',
        'getLocation'
    ]
    });
    wx.ready(function(){
        wx.checkJsApi({
            jsApiList: [
                'getLocation'
            ],
            success: function (res) {
                if (res.checkResult.getLocation == false) {
                    alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                }
            }
        });
        wx.getLocation({
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
				//alert(latitude+'------'+longitude);
                //将结果传给后端
                var ajaxTimeoutTest = $.ajax({
                    type: 'GET',
					timeout:30000,
                    url : '__PATH__wx/Baidumap/getArea?lat=' + latitude + '&long=' + longitude,
                    success: function (ress) {
                        console.log(ress.storeInfo);
                        console.log(ress.area);
                        $('#store_name').text(ress.store_name);
                        $('#store_cd').val(ress.store_cd);
                        $('#city_name').text(ress.city_name);
                        var str = "";
                        $.each(ress.projectList,function(i,item){
                            str += '<dd class="ddr"><span class="tb-s1"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'&sid='+item.STORE_CD+'">' ;
                            str += '<img style="height: 74px;" src="__UPLOADS__'+item.PROJECT_IMAGE+'"></a></span>';
                            str += '<span class="tb-s2 tb-s2r"><p class="tb-p1"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'&sid='+item.STORE_CD+'" style="color:#888;" >'+item.PROJECT_NAME+'</a></p>';
                            str +=  '<p class="tb-p2r"><font class="font1">'+item.PRICE+'元</font>';
                            if(item.MARKET_PRICE) {
                                str += ' <span class="market-price">￥' + item.MARKET_PRICE + '</span>';
                            }
                            str += '<font class="font2">/'+item.PROJECT_TIME+'分钟</font></p>';
                            if("undefined" != typeof item.PRICEAT_NAME) {
                                str += '<div class="tb-p3 div-p3"><div class="priceat_name"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'&sid='+item.STORE_CD+'" style="color:#ff9c00;" >' + item.PRICEAT_NAME+'</a></div></div>';
                            }else{
                                str += '<div class="tb-p3 div-p3"><div class="priceat_name"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'&sid='+item.STORE_CD+'" style="color:#ff9c00;" >项目简介</a></div></div>';
							}
                            str +=  '</span>';
                            //str += '<span class="tb-s3r"><a href="__PATH__wx/goods/goodsDetailed?pid='+item.PROJECT_CD+'&sid='+item.STORE_CD+'" class="tb-a1r mt18r"><p>立即</p><p>预约</p></a></span></dd>';
							str += '<span class="tb-s3r"><a href="__PATH__wx/order/orderPlain?pid='+item.PROJECT_CD+'&store_cd='+item.STORE_CD+'" class="tb-a1r mt18r"><p>立即</p><p>预约</p></a></span></dd>';
                        });
                        $('#project_content').html(str);
                    },
					error: function(XMLHttpRequest, status, errorThrown){
						//TODO: 处理status， http status code，超时 408
						// 注意：如果发生了错误，错误信息（第二个参数）除了得到null之外，还可能
						//是"timeout", "error", "notmodified" 和 "parsererror"。
						if(status=='timeout'){
				 　　　　　 ajaxTimeoutTest.abort();
				　　　　　  alert("超时");
				　　　　}
					},
					complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
				　　　　if(status=='timeout'){
				 　　　　　 ajaxTimeoutTest.abort();
				　　　　　  alert("超时");
				　　　　}
				　　}
                });

            },
            cancel: function (res) {
                alert('用户拒绝授权获取地理位置');
            }
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
