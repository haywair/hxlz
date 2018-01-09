<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:60:"E:\phpStudy\WWW\WEB/application/wx\view\order\orderlist.html";i:1501295943;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;s:56:"E:\phpStudy\WWW\WEB/application/wx\view\public\foot.html";i:1498214442;}*/ ?>
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
                $(tabtit).find("li:first").addClass("thistabz").show();
                $(tab_conbox).find("li:first").show();

                $(tabtit).find("li").bind(shijian,function(){
                    $(this).addClass("thistabz").siblings("li").removeClass("thistabz");
                    var activeindex = $(tabtit).find("li").index(this);
                    $(tab_conbox).children().eq(activeindex).show().siblings().hide();
                    return false;
                });

            };
            /*调用方法如下：*/
            $.jqtab("#tabsz","#tab_conboxz","click");

        });
    </script>
    <div id="tabboxz">
        <ul class="tabsz" id="tabsz">
            <li><a href="#">全部</a></li>
            <li><a href="#">待付款</a></li>
            <li><a href="#">待使用</a></li>
            <li><a href="#">待评价</a></li>
            <li><a href="#">售后</a></li>
        </ul>
        <ul class="tab_conboxz" id="tab_conboxz">
            <li class="tab_conz">
                <dl class="tb-dl">
				    <?php if(!empty($data)): if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;if(($v['ORDER_STATUS'] != 0) || ($v['ORDER_STATUS'] == 0 and $v['extra_time'] >0 )): ?>
                    <dd>
                        <span class="tb-s1"><a href="#"><img style="height: 65px;" src="__UPLOADS__<?php echo $v['PROJECT_IMAGE']; ?>"></a></span>
                        <span class="tb-s2 tb-s2t">
                            <p class="tb-p1"><?php echo $v['PROJECT_NAME']; ?></p>
                            <p class="tb-p2"><font class="font1"><?php echo $v['SELL_PRICE']; ?>元</font><font class="font2">/<?php echo $v['PROJECT_TIME']; ?>分钟</font></p>
                            <!--<p class="tb-p3"><?php echo $v['PROJECT_INTRODUCE']; ?></p>-->
                            <?php if($v['ORDER_STATUS'] == 0): ?>
                                <p class="tb-p3 nopay-o<?php echo $v['ORDER_CD']; ?> pay-o<?php echo $v['ORDER_CD']; ?>"></p>
                            <?php else: ?>
                                <p class="tb-p3  pay-o<?php echo $v['ORDER_CD']; ?>"><?php echo $v['PROJECT_INTRODUCE']; ?></p>
                            <?php endif; ?>
                        </span>
                        <?php if($v['ORDER_STATUS'] == 0): ?>
                        <span class="tb-s3"><a href="__PATH__wx/order/orderDetails?oid=<?php echo $v['ORDER_CD']; ?>" class="tb-a1y pay-order<?php echo $v['ORDER_CD']; ?>">立即付款</a><br><a href="__PATH__wx/order/delOrderByWx?oid=<?php echo $v['ORDER_CD']; ?>" class="tb-a1z mt10 close-order<?php echo $v['ORDER_CD']; ?>">取消订单</a></span>
                        <?php elseif(($v['ORDER_STATUS'] == 1) or ($v['ORDER_STATUS'] == 7)): ?>
                        <span  class="tb-s3"><a href="<?php echo url('wx/order/viewOrderNO',['order_cd'=>$v['ORDER_CD']]); ?>" class="tb-a1y">查看预约码</a><!-- <br><a href="javascript:void(0);" class="tb-a1z mt10 refund refund<?php echo $v['ORDER_CD']; ?>" data-order-cd="<?php echo $v['ORDER_CD']; ?>">申请退款</a> --></span>
                        <?php elseif($v['ORDER_STATUS'] == 2): ?>
                        <span class="tb-s3"><a href="<?php echo url('wx/order/viewOrderNO',['order_cd'=>$v['ORDER_CD']]); ?>" class="tb-a1y mt18">查看预约码</a></span>
                        <?php elseif($v['ORDER_STATUS'] == 3): ?>
                        <span class="tb-s3"><a href="<?php echo url('wx/order/viewOrderNO',['order_cd'=>$v['ORDER_CD']]); ?>" class="tb-a1y mt18">查看预约码</a></span>
                        <?php elseif($v['ORDER_STATUS'] == 4): ?>
                        <span class="tb-s3"><a href="#" class="tb-a1y mt18">立即评价</a></span>
						<?php elseif($v['ORDER_STATUS'] == 8): ?>
                        <span class="tb-s3"><a href="#" class="tb-a1z mt10">退款审核中</a></span>
						<?php elseif($v['ORDER_STATUS'] == 9): ?>
                        <span class="tb-s3"><a href="#" class="tb-a1z mt10">已退款</a></span>
                        <?php elseif($v['ORDER_STATUS'] == 10): ?>
                        <span class="tb-s3"><a href="#" class="tb-a1y mt18">立即评价</a></span>
                        <?php elseif($v['ORDER_STATUS'] == 11): ?>
                        <span class="tb-s3"><a href="#" class="tb-a1z mt10">已结束</a></span>
                        <?php endif; ?>
                    </dd>
					<?php endif; endforeach; endif; else: echo "" ;endif; else: ?>
						 <div style="height:300px;text-align:center;background-color:#fff;color:#666;font-size:14px;padding-top:100px;">
                            暂无订单信息
                        </div>
					<?php endif; ?>
                </dl>
            </li>

            <li class="tab_conz">
                <dl class="tb-dl">
				    <?php if(!empty($non_payment)): if(is_array($non_payment) || $non_payment instanceof \think\Collection || $non_payment instanceof \think\Paginator): $i = 0; $__LIST__ = $non_payment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <dd>
                        <span class="tb-s1"><a href="#"><img style="height: 65px;" src="__UPLOADS__<?php echo $v['PROJECT_IMAGE']; ?>"></a></span>
                        <span class="tb-s2 tb-s2t">
                            <p class="tb-p1"><?php echo $v['PROJECT_NAME']; ?></p>
                            <p class="tb-p2"><font class="font1"><?php echo $v['SELL_PRICE']; ?>元</font><font class="font2">/<?php echo $v['PROJECT_TIME']; ?>分钟</font></p>
                            <p class="tb-p3 nopay-o<?php echo $v['ORDER_CD']; ?>"><?php echo $v['PROJECT_INTRODUCE']; ?></p>
                        </span>
                        <span class="tb-s3"><a href="__PATH__wx/order/orderDetails?oid=<?php echo $v['ORDER_CD']; ?>" class="tb-a1y pay-order<?php echo $v['ORDER_CD']; ?>">立即付款</a><br><a href="__PATH__wx/order/delOrderByWx?oid=<?php echo $v['ORDER_CD']; ?>" class="tb-a1z mt10 close-order<?php echo $v['ORDER_CD']; ?>">取消订单</a></span>
                    </dd>
                    <?php endforeach; endif; else: echo "" ;endif; else: ?>
						 <div style="height:300px;text-align:center;background-color:#fff;color:#666;font-size:14px;padding-top:100px;">
                            暂无相关订单信息
                        </div>
					<?php endif; ?>					
                </dl>
            </li>
            <li class="tab_conz">
                <dl class="tb-dl">
				    <?php if(!empty($uunused)): if(is_array($uunused) || $uunused instanceof \think\Collection || $uunused instanceof \think\Paginator): $i = 0; $__LIST__ = $uunused;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <dd>
                        <span class="tb-s1"><a href="#"><img style="height: 65px;" src="__UPLOADS__<?php echo $v['PROJECT_IMAGE']; ?>"></a></span>
                        <span class="tb-s2 tb-s2t">
                            <p class="tb-p1"><?php echo $v['PROJECT_NAME']; ?></p>
                            <p class="tb-p2"><font class="font1"><?php echo $v['SELL_PRICE']; ?>元</font><font class="font2">/<?php echo $v['PROJECT_TIME']; ?>分钟</font></p>
                            <p class="tb-p3 pay-o<?php echo $v['ORDER_CD']; ?>"><?php echo $v['PROJECT_INTRODUCE']; ?></p>
                        </span>
                        <span  class="tb-s3"><a href="<?php echo url('wx/order/viewOrderNO',['order_cd'=>$v['ORDER_CD']]); ?>" class="tb-a1y ">查看预约码</a><!-- <br><a href="javascript:void(0);" class="tb-a1z mt10 refund refund<?php echo $v['ORDER_CD']; ?>" data-order-cd="<?php echo $v['ORDER_CD']; ?>">申请退款</a> --></span>
                    </dd>
                    <?php endforeach; endif; else: echo "" ;endif; else: ?>
						 <div style="height:300px;text-align:center;background-color:#fff;color:#666;font-size:14px;padding-top:100px;">
                            暂无相关订单信息
                        </div>
					<?php endif; ?>
                </dl>
            </li>
            <li class="tab_conz">
                <dl class="tb-dl">
				    <?php if(!empty($evaluate)): if(is_array($evaluate) || $evaluate instanceof \think\Collection || $evaluate instanceof \think\Paginator): $i = 0; $__LIST__ = $evaluate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <dd>
                        <span class="tb-s1"><a href="#"><img style="height: 65px;" src="__UPLOADS__<?php echo $v['PROJECT_IMAGE']; ?>"></a></span>
                        <span class="tb-s2 tb-s2t">
                            <p class="tb-p1"><?php echo $v['PROJECT_NAME']; ?></p>
                            <p class="tb-p2"><font class="font1"><?php echo $v['SELL_PRICE']; ?>元</font><font class="font2">/<?php echo $v['PROJECT_TIME']; ?>分钟</font></p>
                            <p class="tb-p3"><?php echo $v['PROJECT_INTRODUCE']; ?></p>
                        </span>
                        <span class="tb-s3"><a href="#" class="tb-a1y mt18">立即评价</a></span>
                    </dd>
                    <?php endforeach; endif; else: echo "" ;endif; else: ?>
						 <div style="height:300px;text-align:center;background-color:#fff;color:#666;font-size:14px;padding-top:100px;">
                            暂无相关订单信息
                        </div>
					<?php endif; ?>
                </dl>
            </li>
			<li class="tab_conz">
                <dl class="tb-dl">
				    <?php if(!empty($service)): if(is_array($service) || $service instanceof \think\Collection || $service instanceof \think\Paginator): $i = 0; $__LIST__ = $service;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <dd>
                        <span class="tb-s1"><a href="#"><img style="height: 65px;" src="__UPLOADS__<?php echo $v['PROJECT_IMAGE']; ?>"></a></span>
                        <span class="tb-s2 tb-s2t">
                            <p class="tb-p1"><?php echo $v['PROJECT_NAME']; ?></p>
                            <p class="tb-p2"><font class="font1"><?php echo $v['SELL_PRICE']; ?>元</font><font class="font2">/<?php echo $v['PROJECT_TIME']; ?>分钟</font></p>
                            <p class="tb-p3"><?php echo $v['PROJECT_INTRODUCE']; ?></p>
                        </span>
                        <span class="tb-s3"><a href="#" class="tb-a1z mt10">
                            <?php if($v['ORDER_STATUS'] == 8): ?>
                            退款审核中
                            <?php elseif($v['ORDER_STATUS'] == 9): ?>
                            已退款
                            <?php endif; ?>
                        </a></span>
                    </dd>
                    <?php endforeach; endif; else: echo "" ;endif; else: ?>
						 <div style="height:300px;text-align:center;background-color:#fff;color:#666;font-size:14px;padding-top:100px;">
                            暂无相关订单信息
                        </div>
					<?php endif; ?>
                </dl>
            </li>
        </ul>
    </div>
</div><!--box1 end-->
<script>
    var InterValObj; //timer变量，控制时间
    var mins;//分钟
    var secs;//秒
    var arr = [];
    var nonPay_o = '<?php echo $dataStime; ?>';
    nonPay_o = JSON.parse(nonPay_o);
    var pay_o = '<?php echo $dataPay; ?>';
    pay_o = JSON.parse(pay_o);
    console.log(pay_o);


    function one(objs,num){
        objs = self.setInterval(function()
        {
            if (num == 0) {
                unWork = self.clearInterval(objs);
            } else {
                num--;
                console.log(num);
            }
        },1000);
    }

    function setTime(objs,curCount,ele,orderCd){
        objs = self.setInterval(function(){
            if (curCount <= 0) {
                console.log(curCount);
                self.clearInterval(objs);//停止计时器
                $("."+ele).html("订单已关闭");
                $('.pay-order'+orderCd).css('visibility','hidden');
                $('.pay-order'+orderCd).attr('href','');
                $('.close-order'+orderCd).html('订单已关闭');
                $('.close-order'+orderCd).attr('href','');
				
            }else{
                curCount--;
                mins = parseInt(curCount/60);
                secs = curCount%60;
                if(curCount > 60){
                    timeStr = mins+'分'+secs+'秒';
                }else if(curCount == 60){
                    timeStr = '60秒';
                }else{
                    timeStr = curCount+'秒';
                }
                $("."+ele).html(timeStr + "后关闭订单");
            }
        },1000);
    }
    //未付款计时器
    if(nonPay_o) {
        $.each(nonPay_o, function (i, item) {

            var ele = 'nopay-o' + item.order_cd;
            var extra_time = item.extra_time;
            var orderCd = item.order_cd;
            setTime(arr[i], extra_time, ele, orderCd);

        });
    }
    //预约号+未消费人数
    if(pay_o) {
        $.each(pay_o, function (k, items) {
            var order_no = items.order_no;
            var orderCd = items.order_cd;
            var noCost_num = items.noCost_num;
            var str;
            if (noCost_num > 0) {
                str = '<span>预约码:' + order_no + '</span><span> 未消费 '+noCost_num+' 人</span>';
            } else {
                str = '<span>预约码:' + order_no + '</span><span> 已消费</span>';
            }
            $('.pay-o' + orderCd).html(str);

        });
    }
    $(function(){
        //申请退款
        $('.refund').click(function(){
            var order_cd = $(this).attr('data-order-cd');            
            $.ajax({
                type:'post',
                url:'<?php echo url("wx/Refund/refundAdd"); ?>',
                data:{ORDER_CD:order_cd},
                dataType:'json',
				success:function(result){
                    if(result.state == 'success'){
                        alert(result.msg);
                        $('.refund'+order_cd).html('退款审核中...');
                    }else{
                        alert(result.msg);return false;
                    }

                }

            });
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