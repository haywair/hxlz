<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"E:\phpStudy\WWW\WEB/application/wx\view\user\card.html";i:1515397674;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\footS.html";i:1501834073;}*/ ?>
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
            
           /*判断首次启用电子卡or实体卡*/
            var cardType = "<?php echo $cardType; ?>";
            if(cardType == 'e-card'){
                liActive = 'li:first';
            }else{
                liActive = 'li:last';
            }
			
			jQuery.jqtab = function(tabtit,tab_conbox,shijian,liAcitve) {
                $(tab_conbox).find("li").hide();
                $(tabtit).find(liAcitve).addClass("thistabq").show();
                $(tab_conbox).find(liAcitve).show();

                $(tabtit).find("li").bind(shijian,function(){
                    $(this).addClass("thistabq").siblings("li").removeClass("thistabq");
                    var activeindex = $(tabtit).find("li").index(this);
                    $(tab_conbox).children().eq(activeindex).show().siblings().hide();
                    return false;
                });

            };
            /*调用方法如下：*/
            $.jqtab("#tabsq","#tab_conboxq","click",liActive);

        });
    </script>
    <div id="tabboxq">
        <ul class="tabsq" id="tabsq">
            <li><a href="#"><p><img src="__STATIC__images/hyk_06.png" width="35px"></p><p class="tbq-p1">电子卡</p></a></li>
            <li><a href="#"><p><img src="__STATIC__images/hyk_03.png" width="35px"></p><p class="tbq-p1">实体卡</p></a></li>
        </ul>
        <ul class="tab_conboxq" id="tab_conboxq">
            <li class="tab_conq">
                <dl class="tbq-dl" >
                    <dd>
                        <span class="tbq-s1" >
                            <p class="tbq-p2" ><a href="<?php echo url('wx/user/cardCharge'); ?>">立即充值</a></p>
                            <p class="tbq-p3"><a href="<?php echo url('wx/user/costList'); ?>">查看明细</a></p>
                            <!--<p class="tbq-p3"><a href="<?php echo url('wx/user/rechargeList'); ?>">充值明细</a></p>-->
                            <!--<p class="tbq-p3" style="margin-top:10px;"><a href="#">购  卡</a></p>-->
                            <p class="tbq-p4 tbq-p4r"><span class="tbq-f1">余额：</span><span class="tbq-p5r">￥<?php echo $data['TOTAL_AMT']; ?></span></p>
                        </span>
                        <span class="tbq-s2"><img src="__STATIC__images/hyk_11.png" style="height:180px;"></span>

                    </dd>
                </dl>
            </li>
            <li class="tab_conq">
                <dl class="tbq-dl">
                    <?php if(!(empty($offlineCards) || (($offlineCards instanceof \think\Collection || $offlineCards instanceof \think\Paginator ) && $offlineCards->isEmpty()))): if(is_array($offlineCards) || $offlineCards instanceof \think\Collection || $offlineCards instanceof \think\Paginator): if( count($offlineCards)==0 ) : echo "" ;else: foreach($offlineCards as $key=>$v): ?>
                    <dd style="position:relative;">
                        <a href="<?php echo url('wx/user/offlinecardcode',['memberCardNo'=>$v['MEMBER_CARD_NO']]); ?>" class="offline-card-code"></a>
                        <span class="tbq-s1">
                            <p class="tbq-p2a"><?php echo $v['STORE_NAME']; ?></p>
                            <p class="tbq-p2"><a  href="<?php echo url('wx/user/offCardCostList',['MEMBER_CARD_NO'=>$v['MEMBER_CARD_NO']]); ?>">
                                查看明细</a>
                            <p class="tbq-p3" style="margin-top:10px;">
								<!-- <a href="<?php echo url('wx/user/unbindOffCard',['OFFLINE_CARD_ID'=>$v['OFFLINE_CARD_ID']]); ?>">解绑</a> -->
								<a class="unbind-card" href="javascript:void(0);" data-card-no="<?php echo $v['OFFLINE_CARD_ID']; ?>">解绑</a>
                            </p>
                           <!-- <p class="tbq-p3"><a href="#"></a></p>-->
                            <p   class="tbq-p4"><font class="tbq-f1">余额：</font><font class="tbq-f2">￥<?php echo $v['TOTAL_AMT']; ?></font></p>
                        </span>
                        <span class="tbq-s2"><img src="__STATIC__images/hyk_11.png"></span>
						<p class="tbq-p4" style="position:absolute;left:10px;bottom:8px;"><font class="tbq-f1">卡号：</font><font class="tbq-f2"><?php echo $v['MEMBER_CARD_NO']; ?></font></p>
                    </dd>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    <button id="bindCard" class="bind-car" style="margin-top:45px;">绑定新卡</button>
                </dl>
            </li>
        </ul>
    </div>
</div><!--box1 end-->
<script type="text/javascript">
    $(function(){
        //绑定新卡
        $('#bindCard').click(function(){
            $.login({
                title: '绑定实体卡',
                text: '请输入实体卡卡号和密码',
                username: '实体卡卡号',  // 默认用户名
                password: '',  // 默认密码
                onOK: function (username, password) {
                    $.ajax({
                        url:'<?php echo url("wx/user/bindOfflineCard"); ?>',
                        type:'post',
                        data:{card_no:username,pwd:password},
                        dataType:'json',
                        success:function(result){							
                            if(result.state == 100){
                                $.alert(result.msg, function() {
                                    window.location.href = "<?php echo url('wx/user/card'); ?>?cardType=offline";
                                });
                            }else{
                                $.alert(result.msg);
                            }
                        }
                    });
                },
                onCancel: function () {
                    //点击取消
                }
            });
            $('#weui-prompt-username').focus(function(){
                $('#weui-prompt-username').attr('placeholder','实体卡卡号');
                $('#weui-prompt-username').val('');
            });
        });
		//解绑卡片
        $('.unbind-card').click(function(){
            var cardId = $(this).attr('data-card-no');
            $.confirm("您确定要解除该卡的绑定状态么？", function() {
                window.location.href="<?php echo url('wx/user/unbindOffCard'); ?>?OFFLINE_CARD_ID="+cardId;
            }, function() {
                //点击取消后的回调函数
            });
        })
    })
</script>
<ul class="foot" style="z-index:1;">
    <li><a href="__PATH__wx/home/home"><p class="f-p1"><img src="__STATIC__images/ftt_03.png"  width="23"></p><p class="f-p2">首页</p></a></li>
    <li><a href="__PATH__wx/order/orderList"><p class="f-p1"><img src="__STATIC__images/lz_13.jpg" width="23"></p><p class="f-p2">订单</p></a></li>
    <li><a href="__PATH__wx/home/getMore"><p class="f-p1"><img src="__STATIC__images/lz_19.jpg" width="23"></p><p class="f-p2">商城</p></a></li>
    <li><a href="__PATH__wx/user/me"><p class="f-p1"><img src="__STATIC__images/lz_21.jpg" width="20"></p><p class="f-p2">会员中心</p></a></li>
</ul><!--foot end-->
</body>
</html>
