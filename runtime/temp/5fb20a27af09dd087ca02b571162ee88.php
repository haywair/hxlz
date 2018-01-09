<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:61:"E:\phpStudy\WWW\WEB/application/wx\view\order\orderplain.html";i:1515390214;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="telephone=no" name="format-detection" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>华夏良子</title>
    <link href="__STATIC__css/HTp-style.css" rel="stylesheet" type="text/css" />
    <link href="__STATIC__css/orderPlain.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="__STATIC__css/jquery.flipster.css">
    <link rel="stylesheet" href="__STATIC__css/flipsternavtabs.css">
    <link rel="stylesheet" href="__STATIC__css/weui.min.css">
    <link rel="stylesheet" href="__STATIC__css/jquery-weui.min.css">
    <script type="text/javascript" src="__STATIC__js/jquery.min.js"></script>
    <script type="text/javascript" src="__STATIC__js/jquery-weui.min.js"></script>
</head>
<body>
<form  action="__PATH__wx/order/orderDetails"  method="post" id="myform">
<div class="box1">
    <div class="xq-name" style="margin-top:10px;">
    	<span  style="display:block;font-size:14px;line-height:16px;">
			<span class="xq-p1" style="font-size:16px;color:#666;font-weight:bold;"><?php echo $pdata['PROJECT_NAME']; ?></span>
			<!-- <p class="xq-p2"><?php if(!(empty($pdata['PROJECT_INTRODUCE']) || (($pdata['PROJECT_INTRODUCE'] instanceof \think\Collection || $pdata['PROJECT_INTRODUCE'] instanceof \think\Paginator ) && $pdata['PROJECT_INTRODUCE']->isEmpty()))): ?><?php echo $pdata['PROJECT_INTRODUCE']; endif; ?></p> -->
        </span>
        <span  style="display:block;font-size:14px;line-height:16px;margin-top:8px;">
			<span class="xq-p1" style="float-left;font-size:14px;color:#666;">门店价格:</span>
			<span style="float:right;">
        	<!--<span class="xq-p3"><?php echo $pdata['PROJECT_TIME']; ?>分钟</span>-->
            <span class="xq-p4"><?php echo $pdata['PRICE']; ?>元 <s>￥<?php echo $pdata['MARKET_PRICE']; ?></s></span>
			</span>
        </span>
    </div><!--xq-name end-->
    <div class="tj-dv1">
        <!--<span class="xq-s4"><img src="__STATIC__images/xm_03.png" width="15" height="18px"></span>-->
        <span class="tj-s1"><a href="__PATH__wx/store/storeList" style="font-size:14px;color:#666">门店：<?php echo $sdata['STORE_NAME']; ?></a></span>
    </div><!--tj-dv1 end-->
	<!--<div class="qtmd" style="font-size:14px;">
        <a href="__PATH__wx/store/storeList">其他门店 ></a>
        <font>门店：</font><span><?php echo $sdata['STORE_NAME']; ?></span>
    </div>-->
    <!--加减JS-->
    <script>
        $(function(){
            $(".add").click(function(){
                var t=$(this).parent().find('input[class*=text_box]');
                t.val(parseInt(t.val())+1);
                setTotal();
            })
            $(".min").click(function(){
                var t=$(this).parent().find('input[class*=text_box]');
                t.val(parseInt(t.val())-1);
                if(parseInt(t.val())<0){
                    t.val(0);
                }
                setTotal();
            })
            function setTotal(){
                var s=0;
                $("#tab td").each(function(){
                    s+=parseInt($(this).find('input[class*=text_box]').val())*parseFloat($(this).find('span[class*=price]').text());
                });
                $("#total").html(s.toFixed(2));
            }
            setTotal();

        })
    </script>
    <!--加减JS emd-->

        <input type="hidden" name="sid" value="<?php echo $sdata['STORE_CD']; ?>">
        <input type="hidden" name="pid" id="project_cd" value="<?php echo $pdata['PROJECT_CD']; ?>">
    <div class="tj-dv1" >
        <span class="tj-s1">预约人数</span>
        <span class="tj-s2" >
            <input class="min" name="" type="button" value="-" />
            <input class="text_box" id="text_box" name="CUSTOMER_QTY" type="text" value="1" />
            <input class="add" name="" type="button" value="+" />
        </span>
    </div>
   <!--  <div class="tj-dv1" style="margin-top:-0.5rem;border-top:1px solid #ddd;">
        <span class="tj-s1">技师要求</span>
        <span class="tj-s2" >
            <input type="radio" name="yaoqiu" value="0">男性
            <input type="radio" name="yaoqiu" value="1">女性
        </span>
    </div> -->
    <!--tj-dv1 end-->	
    <div class="container" >
        <p class="xqf-p1">选择房间</p>
        <input type="hidden" name="room_cd" id="room_cd">
        <div id="slide" class="slide" class="index-slide" alt="star">
            <!-- 轮播图片数量可自行增减 -->
            <?php if(is_array($sRoom) || $sRoom instanceof \think\Collection || $sRoom instanceof \think\Paginator): $i = 0; $__LIST__ = $sRoom;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <div class="img" data-room-cd="<?php echo $v['ROOM_CD']; ?>" data-room-name="<?php echo $v['ROOM_NAME']; ?>" data-room-qty="<?php echo $v['SOFA_QTY']; ?>" data-order-p="<?php echo $v['ORDER_PERSON']; ?>"><img  src="<?php if($v['ROOM_IMAGE']): ?>__UPLOADS__<?php echo $v['ROOM_IMAGE']; else: ?>__STATIC__images/1.jpg<?php endif; ?>"/></div>
            <div class="slide-bt"  style="display:none;"></div>			
            <?php endforeach; endif; else: echo "" ;endif; ?>			
            <p class="tj-p1" id="room-name"></p>
			<?php if(!(empty($sRoom) || (($sRoom instanceof \think\Collection || $sRoom instanceof \think\Paginator ) && $sRoom->isEmpty()))): ?>
			<div class="div-orderperson-con">最低可接受<!-- <?php echo $sRoom[0]['ORDER_PERSON']; ?> -->1人预约,最多可容纳<?php echo $sRoom[0]['SOFA_QTY']; ?>人</div>
			<?php endif; ?>
        </div>
    </div>

    <ul class="tab_conbox" id="tab_conbox" >
        <li class="tab_con">
            <div id="tabbox1 " >
                <p class="xqf-p1">选择预约时间</p>
                <div class="selectxq">
                    <input type="hidden" name="ORDER_DATE" id="order_date" value="<?php echo date('Y-m-d',time()); ?>">
                    <dl class="weekbar1 tabs1" id="tabs1">
                        <?php if(is_array($dayTime) || $dayTime instanceof \think\Collection || $dayTime instanceof \think\Paginator): if( count($dayTime)==0 ) : echo "" ;else: foreach($dayTime as $k=>$v): ?>
                            <dd  data-time="<?php echo date('Y-m-d',$v['day']); ?>" data-time-num="<?php echo $v['day']; ?>" class="<?php if($k == '0'): ?>thistab1<?php endif; ?> d-time">
                            <p><?php if($k == '0'): ?>今天<?php else: ?><?php echo $v['week']; endif; ?></p>
                            <p class="d-date"><?php echo date('m/d',$v['day']); ?></p>
                            </dd>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </dl>
                </div>
                <dl class="tab_conbox1 " id="tab_conbox1">
                    <dd class="tab_con1 order-day<?php echo $dayTime[0]['day']; ?>">
                        <input type="hidden" name="TIME_CD" id="time_cd">
						<!--<div style="text-align:center;font-size:14px;color:#666;padding-top:0.8rem;">正式营业后开通预约功能，敬请期待......</div>-->
                         <dl class="weekbar2" id="wek2">
                             <?php if(is_array($timelist) || $timelist instanceof \think\Collection || $timelist instanceof \think\Paginator): if( count($timelist)==0 ) : echo "" ;else: foreach($timelist as $k=>$vo): 
                                    $vtime = strtotime(date('Y-m-d '.$vo['ORDER_START_DATE_TIME']));
                                    if($vtime < strtotime(date('Y-m-d').' 8:00')){
                                        $vtime = strtotime(date('Y-m-d',strtotime("+1 day")).' '.$vo['ORDER_START_DATE_TIME']);
                                    }
                                    $ntime= time();
									$display = "";
									if($ntime > $vtime){
										$display = "display:none;";
									}else if($vo['order_disable']==1){
										$display = "background-color:#c0c1c4;";
									}
                                ?>
                                <!-- <dt <?php if(time() > $vtime or $vo['order_disable'] == 1): ?>style="background-color:#c0c1c4;"<?php endif; ?> data-ntime="<?php echo $ntime; ?>"  data-vtime="<?php echo $vtime; ?>" <?php if($vo['order_disable'] == 1): ?>data-order-disable="<?php echo $vo['order_disable']; ?>"<?php endif; ?>{/if} data-k="<?php echo $k+1; ?>" data-id="<?php echo $vo['TIME_CD']; ?>"  class="order-time<?php echo $vo['TIME_CD']; ?>">
                                    <p class="date-time"><?php echo $vo['ORDER_START_DATE_TIME']; ?></p><p>￥<?php if(empty($vo['PRICE'])): ?><?php echo $pdata['PRICE']; else: ?><?php echo $vo['PRICE']; endif; ?></p>
                                </dt> -->
								<dt style="<?php echo $display; ?>" data-ntime="<?php echo $ntime; ?>"  data-vtime="<?php echo $vtime; ?>" <?php if($vo['order_disable'] == 1): ?>data-order-disable="<?php echo $vo['order_disable']; ?>"<?php endif; ?>{/if} data-k="<?php echo $k+1; ?>" data-id="<?php echo $vo['TIME_CD']; ?>"  class="order-time<?php echo $vo['TIME_CD']; ?>">
                                    <p class="date-time"><?php echo $vo['ORDER_START_DATE_TIME']; ?></p><p>￥<?php if(empty($vo['PRICE'])): ?><?php echo $pdata['PRICE']; else: ?><?php echo $vo['PRICE']; endif; ?></p>
                                </dt>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </dl>
                    </dd>
                    <dd class="tab_con1 order-day<?php echo $dayTime[1]['day']; ?>">
						<!--<div style="text-align:center;font-size:14px;color:#666;padding-top:0.8rem;">正式营业后开通预约功能，敬请期待......</div>-->
                         <dl class="weekbar2" id="wek3">
                            <?php if(is_array($timelist) || $timelist instanceof \think\Collection || $timelist instanceof \think\Paginator): if( count($timelist)==0 ) : echo "" ;else: foreach($timelist as $key=>$vo): ?>
                                <dt data-id="<?php echo $vo['TIME_CD']; ?>" <?php if($vo['order_disable'] == 1): ?>style="background-color:#c0c1c4;" data-order-disable="<?php echo $vo['order_disable']; ?>"<?php endif; ?>  class="order-time<?php echo $vo['TIME_CD']; ?>">
                                    <p class="date-time"><?php echo $vo['ORDER_START_DATE_TIME']; ?></p><p>￥<?php if(empty($vo['PRICE'])): ?><?php echo $pdata['PRICE']; else: ?><?php echo $vo['PRICE']; endif; ?></p>
                                </dt>
                            <?php endforeach; endif; else: echo "" ;endif; ?> 	
                        </dl>
                    </dd>
                    <dd class="tab_con1 order-day<?php echo $dayTime[2]['day']; ?>">
						<!--<div style="text-align:center;font-size:14px;color:#666;padding-top:0.8rem;">正式营业后开通预约功能，敬请期待......</div>-->
                         <dl class="weekbar2" id="wek4">
                            <?php if(is_array($timelist) || $timelist instanceof \think\Collection || $timelist instanceof \think\Paginator): if( count($timelist)==0 ) : echo "" ;else: foreach($timelist as $key=>$vo): ?>
                                <dt data-id="<?php echo $vo['TIME_CD']; ?>" <?php if($vo['order_disable'] == 1): ?>style="background-color:#c0c1c4;" data-order-disable="<?php echo $vo['order_disable']; ?>"<?php endif; ?>  class="order-time<?php echo $vo['TIME_CD']; ?>">
                                    <p class="date-time"><?php echo $vo['ORDER_START_DATE_TIME']; ?></p><p>￥<?php if(empty($vo['PRICE'])): ?><?php echo $pdata['PRICE']; else: ?><?php echo $vo['PRICE']; endif; ?></p>
                                </dt>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </dl>
                    </dd>
                    <dd class="tab_con1 order-day<?php echo $dayTime[3]['day']; ?>">
						<!--<div style="text-align:center;font-size:14px;color:#666;padding-top:0.8rem;">正式营业后开通预约功能，敬请期待......</div>-->
                          <dl class="weekbar2" id="wek5">
                           <?php if(is_array($timelist) || $timelist instanceof \think\Collection || $timelist instanceof \think\Paginator): if( count($timelist)==0 ) : echo "" ;else: foreach($timelist as $key=>$vo): ?>
                                <dt data-id="<?php echo $vo['TIME_CD']; ?>" <?php if($vo['order_disable'] == 1): ?>style="background-color:#c0c1c4;" data-order-disable="<?php echo $vo['order_disable']; ?>"<?php endif; ?>  class="order-time<?php echo $vo['TIME_CD']; ?>">
                                    <p class="date-time"><?php echo $vo['ORDER_START_DATE_TIME']; ?></p><p>￥<?php if(empty($vo['PRICE'])): ?><?php echo $pdata['PRICE']; else: ?><?php echo $vo['PRICE']; endif; ?></p>
                                </dt>
                            <?php endforeach; endif; else: echo "" ;endif; ?> 
                        </dl>
                    </dd>
                    <dd class="tab_con1 order-day<?php echo $dayTime[4]['day']; ?>">
						<!--<div style="text-align:center;font-size:14px;color:#666;padding-top:0.8rem;">正式营业后开通预约功能，敬请期待......</div>-->
                        <dl class="weekbar2" id="wek6">
                             <?php if(is_array($timelist) || $timelist instanceof \think\Collection || $timelist instanceof \think\Paginator): if( count($timelist)==0 ) : echo "" ;else: foreach($timelist as $key=>$vo): ?>
                                <dt data-id="<?php echo $vo['TIME_CD']; ?>" <?php if($vo['order_disable'] == 1): ?>style="background-color:#c0c1c4;" data-order-disable="<?php echo $vo['order_disable']; ?>"<?php endif; ?>  class="order-time<?php echo $vo['TIME_CD']; ?>">
                                    <p class="date-time"><?php echo $vo['ORDER_START_DATE_TIME']; ?></p><p>￥<?php if(empty($vo['PRICE'])): ?><?php echo $pdata['PRICE']; else: ?><?php echo $vo['PRICE']; endif; ?></p>
                                </dt>
                            <?php endforeach; endif; else: echo "" ;endif; ?> 
                        </dl>
                    </dd>
                    <dd class="tab_con1 order-day<?php echo $dayTime[5]['day']; ?>">
						<!--<div style="text-align:center;font-size:14px;color:#666;padding-top:0.8rem;">正式营业后开通预约功能，敬请期待......</div>-->
                         <dl class="weekbar2" id="wek7">
                            <?php if(is_array($timelist) || $timelist instanceof \think\Collection || $timelist instanceof \think\Paginator): if( count($timelist)==0 ) : echo "" ;else: foreach($timelist as $key=>$vo): ?>
                                <dt data-id="<?php echo $vo['TIME_CD']; ?>" <?php if($vo['order_disable'] == 1): ?>style="background-color:#c0c1c4;" data-order-disable="<?php echo $vo['order_disable']; ?>"<?php endif; ?>  class="order-time<?php echo $vo['TIME_CD']; ?>">
                                    <p class="date-time"><?php echo $vo['ORDER_START_DATE_TIME']; ?></p><p>￥<?php if(empty($vo['PRICE'])): ?><?php echo $pdata['PRICE']; else: ?><?php echo $vo['PRICE']; endif; ?></p>
                                </dt>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </dl>
                    </dd>
                    <dd class="tab_con1 order-day<?php echo $dayTime[6]['day']; ?>">
						<!--<div style="text-align:center;font-size:14px;color:#666;padding-top:0.8rem;">正式营业后开通预约功能，敬请期待......</div>-->
                         <dl class="weekbar2" id="wek8">
                            <?php if(is_array($timelist) || $timelist instanceof \think\Collection || $timelist instanceof \think\Paginator): if( count($timelist)==0 ) : echo "" ;else: foreach($timelist as $key=>$vo): ?>
                                <dt data-id="<?php echo $vo['TIME_CD']; ?>" <?php if($vo['order_disable'] == 1): ?>style="background-color:#c0c1c4;" data-order-disable="<?php echo $vo['order_disable']; ?>"<?php endif; ?>  class="order-time<?php echo $vo['TIME_CD']; ?>">
                                    <p class="date-time"><?php echo $vo['ORDER_START_DATE_TIME']; ?></p><p>￥<?php if(empty($vo['PRICE'])): ?><?php echo $pdata['PRICE']; else: ?><?php echo $vo['PRICE']; endif; ?></p>
                                </dt>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </dl>
                    </dd>
                </dl>
            </div>
        </li>

    </ul>


    <script src="__STATIC__js/jquery.flipster.js"></script>
    <script>
        var autoLb = false;          //autoLb=true为开启自动轮播
        var autoLbtime = 1;         //autoLbtime为轮播间隔时间（单位秒）
        var touch = true;           //touch=true为开启触摸滑动
        var slideBt = true;         //slideBt=true为开启滚动按钮
        var slideNub;               //轮播图片数量

        //窗口大小改变时改变轮播图宽高
        $(window).resize(function(){
            $(".slide").height($(".slide").width()*0.56);
        });


        $(function(){
            $(".slide").height($(".slide").width()*0.56);
            slideNub = $(".slide .img").size();             //获取轮播图片数量
            for(i=0;i<slideNub;i++){
                $(".slide .img:eq("+i+")").attr("data-slide-imgId",i);
            }

            //根据轮播图片数量设定图片位置对应的class
            if(slideNub==1){
                for(i=0;i<slideNub;i++){
                    $(".slide .img:eq("+i+")").addClass("img3");
                }
            }
            if(slideNub==2){
                for(i=0;i<slideNub;i++){
                    $(".slide .img:eq("+i+")").addClass("img"+(i+3));
                }
            }
            if(slideNub==3){
                for(i=0;i<slideNub;i++){
                    $(".slide .img:eq("+i+")").addClass("img"+(i+2));
                }
            }
            if(slideNub>3&&slideNub<6){
                for(i=0;i<slideNub;i++){
                    $(".slide .img:eq("+i+")").addClass("img"+(i+1));
                }
            }
            if(slideNub>=6){
                for(i=0;i<slideNub;i++){
                    if(i<5){
                        $(".slide .img:eq("+i+")").addClass("img"+(i+1));
                    }else{
                        $(".slide .img:eq("+i+")").addClass("img5");
                    }
                }
            }

            if(touch){
                k_touch();
            }
            slideLi();
            imgClickFy();
        })

        //右滑动
        function right(){
            var fy = new Array();
            for(i=0;i<slideNub;i++){
                fy[i]=$(".slide .img[data-slide-imgId="+i+"]").attr("class");
            }
            for(i=0;i<slideNub;i++){
                if(i==0){
                    $(".slide .img[data-slide-imgId="+i+"]").attr("class",fy[slideNub-1]);
                }else{
                    $(".slide .img[data-slide-imgId="+i+"]").attr("class",fy[i-1]);
                }
            }
            imgClickFy();
            slideLi();
        }

        //左滑动
        function left(){
            var fy = new Array();
            for(i=0;i<slideNub;i++){
                fy[i]=$(".slide .img[data-slide-imgId="+i+"]").attr("class");
            }
            for(i=0;i<slideNub;i++){
                if(i==(slideNub-1)){
                    $(".slide .img[data-slide-imgId="+i+"]").attr("class",fy[0]);
                }else{
                    $(".slide .img[data-slide-imgId="+i+"]").attr("class",fy[i+1]);
                }
            }
            imgClickFy();
            slideLi();
        }

        //轮播图片左右图片点击翻页
        function imgClickFy(){
            $(".slide .img").removeAttr("onclick");
            $(".slide .img2").attr("onclick","left()");
            $(".slide .img4").attr("onclick","right()");
        }

        //修改当前最中间图片对应按钮选中状态
        function slideLi(){
            var slideList = parseInt($(".slide .img3").attr("data-slide-imgId")) + 1;
          /*  $(".slide-bt span").removeClass("on");
            $(".slide-bt span[data-slide-bt="+slideList+"]").addClass("on");*/
            var room_name = $(".slide .img3").attr('data-room-name');
            var room_cd = $(".slide .img3").attr('data-room-cd');
			var qty = $(".slide .img3").attr('data-room-qty');
			var orderNum = $(".slide .img3").attr('data-order-p');
            var sid = "<?php echo $sid; ?>";
            $('#room-name').html(room_name);
            $('#room_cd').val(room_cd);
			$('.div-orderperson-con').html('最低可接受1人预约,最多可容纳'+qty+'人');

            $('.order-un').css('background-color','');
            $('.order-un').removeAttr('data-ordered');
            getOrderTimeList(sid,room_cd);
        }

        //触摸滑动模块
        function k_touch() {
            var _start = 0, _end = 0, _content = document.getElementById("slide");
            _content.addEventListener("touchstart", touchStart, false);
            _content.addEventListener("touchmove", touchMove, false);
            _content.addEventListener("touchend", touchEnd, false);
            function touchStart(event) {
                var touch = event.targetTouches[0];
                _start = touch.pageX;
            }
            function touchMove(event) {
                var touch = event.targetTouches[0];
                _end = (_start - touch.pageX);
            }

            function touchEnd(event) {
                if (_end < -100) {
                    left();
                    _end=0;
                }else if(_end > 100){
                    right();
                    _end=0;
                }
            }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            jQuery.jqtab = function(tabtit,tab_conbox,shijian) {
                $(tab_conbox).find("li").hide();
                $(tabtit).find("li:first").addClass("thistab").show();
                $(tab_conbox).find("li:first").show();

                $(tabtit).find("li").bind(shijian,function(){
                    $(this).addClass("thistab").siblings("li").removeClass("thistab");
                    var activeindex = $(tabtit).find("li").index(this);
                    $(tab_conbox).children().eq(activeindex).show().siblings().hide();
                    return false;
                });

            };
            /*调用方法如下：*/
            $.jqtab("#tabs","#tab_conbox","mouseenter");

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            jQuery.jqtab1 = function(tabtit1,tab_conbox1,shijian1) {
                $(tab_conbox1).find("dd").hide();
                $(tabtit1).find("dd:first").addClass("thistab1").show();
                $(tab_conbox1).find("dd:first").show();

                $(tabtit1).find("dd").bind(shijian1,function(){
                    $(this).addClass("thistab1").siblings("dd").removeClass("thistab1");
                    var activeindex = $(tabtit1).find("dd").index(this);
                    $(tab_conbox1).children().eq(activeindex).show().siblings().hide();
                    return false;
                });

            };
            /*调用方法如下：*/
            $.jqtab1("#tabs1","#tab_conbox1","click");

        });
    </script>
</div>


</div><!--box1 end-->

<div class="foot1">
    <input type="button" id="yuyue" class="ft-a1" value="立即预约">
</div>
</form>
<script>
    //判断是否为返回页
    window.onpageshow=function(e){
        var a=e||window.event;
        if(a.persisted){
            window.location.refresh();
        }
    }
    $(function(){
		 //隐藏后4个元素
        var lenNum = ($('.weekbar2').children('dt').length)/7;
        for(var t=lenNum;t>=1;t--){
            if(t>=lenNum-4){
                $($("#wek2").children("dt").get(t)).hide();
                $($("#wek3").children("dt").get(t)).hide();
                $($("#wek4").children("dt").get(t)).hide();
                $($("#wek5").children("dt").get(t)).hide();
                $($("#wek6").children("dt").get(t)).hide();
                $($("#wek7").children("dt").get(t)).hide();
                $($("#wek8").children("dt").get(t)).hide();

            }
        }
        //选择日期
        $('.d-time').click(function(){
            var day = $(this).attr('data-time');
            var rid = $('#room_cd').val();
            var sid = '<?php echo $sid; ?>';
            $('#order_date').val(day);
            $('.order-un').css('background-color','');
            $('.order-un').removeAttr('data-ordered');
            getOrderTimeList(sid,rid);
        });
        //选择状态切换
        $('dt').click(function(){
			var pro_time="<?php echo $pdata['PROJECT_TIME']; ?>";
            //获取父元素id
            var par = $(this).parent().attr('id');
            //当前元素序列
            var index = $("#"+par+' dt').index(this);
            //兄弟元素数量
            var child_num = $('#'+par).children().length;
            //服务时长
            var service_time = "<?php echo $pdata['PROJECT_TIME']; ?>";
            //门店预约间隔时长
            var store_time = "<?php echo $sdata['ORDER_TIME_CELL']; ?>";
            //当前时间
            var ntime = $(this).attr('data-ntime');
            //选项时间
            var vtime = $(this).attr('data-vtime');
            var time_cd = $(this).attr('data-id');
			var error_text = '该项目时长为'+pro_time+'分钟,房间空余时间不足，请选择其他房间或时段进行预约';
            $('dt').removeClass('wb-dt');
            if(ntime && vtime && ntime > vtime){
                alert('已经过去的时间无法选择');
                return false;
            }
            if($(this).attr('data-ordered')){
                alert('该时间段已被预约');
                return false;
            }
            if($(this).attr('data-order-disable')){
                alert('该时段暂不支持预约！');
                return false;
            }

           /* $('dt').removeAttr('id');
            $('#time_cd').val(time_cd);
            $(this).attr('id','wb-dt');*/

            if(parseInt(service_time) > parseInt(store_time)){
                var addNum;
                if(service_time%store_time){
                    addNum = Math.ceil(service_time/store_time);
                }else{
                    addNum = service_time/store_time+1;
                }

               /* if(index+addNum>child_num){
                   alert(error_text);return false;
                }*/
                for(var t=1;t<=addNum-1;t++){
                    var isOrdered = $('#'+par+' dt').eq(index+t).attr('data-ordered');
                    var isDisabled = $('#'+par+' dt').eq(index+t).attr('data-order-disable');
                    var dataId = $('#'+par+' dt').eq(index+t).attr('data-id');
                    if(isOrdered || isDisabled){
                        alert('该项目时长需'+addNum+'个时段连续且可预约！');return false;
                    }else{
                        //$('#'+par+' dt').eq(index+t).addClass('wb-dt');
                        time_cd += ','+dataId;
                    } 
                }
            }

            $('#time_cd').val(time_cd);
            $(this).addClass('wb-dt');
        });
        //点击预约提交
        $('#yuyue').click(function(){
            var order_person = parseInt($('#text_box').val());
            var rid = $('#room_cd').val();
            var sid = '<?php echo $sid; ?>';
            var pcd = $('#project_cd').val();
            var time_cd = $('#time_cd').val();
            var order_date = $('#order_date').val();
            if(!time_cd){
                alert('您还未选择预约时间');
                return false;
            }else{
                $.ajax({
                    type:'post',
                    url:'<?php echo url("wx/order/isRoomTimeOrder"); ?>',
                    data:{rid:rid,sid:sid,time_cd:time_cd,order_date:order_date},
                    dataType:'json',
                    success:function(result){
                       if(result.state == 'error'){
                           console.log(result.msg);return false;
                       }else if(result.state == 101){
                           alert('该时段已被预定');return false;
                       }else{
                           if(rid){
                               $.ajax({
                                   type:'post',
                                   url:'<?php echo url("wx/order/getSofa"); ?>',
                                   data:{rid:rid,sid:sid,pcd:pcd,order_person:order_person},
                                   dataType:'json',
                                   success:function(result){
                                       console.log(result);
                                       if(result.state == 200){
                                           $('#myform').submit();
                                       }else if(result.state == 313){
                                           $.confirm(result.msg, function() {
                                               $('#myform').submit();
                                           }, function() {
                                               return false;
                                           });
                                       }else{
                                           alert(result.msg);
                                           return false;
                                       }
                                   }
                               });
                           }else{
                               alert('您还未选择预约的房间！');
                               return false;
                           }
                       }
                    }
                });
            }
        });
    });

    function getOrderTimeList(store_cd,room_cd){
        $.ajax({
            url:"<?php echo url('wx/order/getRoomOrderTime'); ?>",
            type:'post',
            data:{store_cd:store_cd,room_cd:room_cd},
            dataType:'json',
            success:function(result){
                console.log(result);
                var list = result.list;
                if(result.state == 'success'){
                    console.log(list);
                    $.each(list,function(i,item){
                      $('.order-day'+item.ORDER_DATA+' .order-time'+item.TIME_CD).css('background-color','#c0c1c4');
                      $('.order-day'+item.ORDER_DATA+' .order-time'+item.TIME_CD).attr('data-ordered','ordered');
                      $('.order-day'+item.ORDER_DATA+' .order-time'+item.TIME_CD).addClass('order-un');
                    });
                }

            }

        });

    }
</script>
</body>
</html>
