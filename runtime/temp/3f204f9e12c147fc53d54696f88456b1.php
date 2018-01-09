<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:64:"E:\phpStudy\WWW\WEB/application/wx\view\goods\goodsdetailed.html";i:1515206408;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;}*/ ?>
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
					
					<?php if(is_array($img[0]) || $img[0] instanceof \think\Collection || $img[0] instanceof \think\Paginator): if( count($img[0])==0 ) : echo "" ;else: foreach($img[0] as $key=>$v): ?>
					<div>
                        <div class="wrap">
                            <div class="image"><img style="height: 230px;" src="__UPLOADS__<?php echo $v; ?>"></div>
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
    <div class="xq-name">
    	<span class="xq-s1" style="float:left;width:100%;">
        	<span class="xq-p1"><?php echo $data['0']['PROJECT_NAME']; ?></span>
		</span>
        <span class="xq-s2" style="display:inline-block;float:left;width:100%;margin-top:0.3rem;padding-bottom:0.4rem;">
        	<!--<span class="xq-p3" style="float:left;"><?php echo $data['0']['PROJECT_TIME']; ?>分钟 <?php echo $data['0']['PRICE']; ?>元 <s>￥<?php echo $data['0']['MARKET_PRICE']; ?></s></span>-->
            <!-- <span class="xq-p4" style="float:left;"><?php echo $data['0']['pPRICE']; ?>元 <s>￥<?php echo $data['0']['MARKET_PRICE']; ?></s></span> -->
            <span class="xq-p3" style="float:left;color:#333;">门店价格</span>
            <span class="xq-p4" style="float:right;margin-top:-0.2rem;color:#FF7D68;"><?php echo round($data['0']['pPRICE']); ?>元<s>￥<?php echo $data['0']['MARKET_PRICE']; ?></s></span>
        </span>
    </div><!--xq-name end-->
    <div class="xq-md">
        <script type="text/javascript">
            $(document).ready(function(){
                $("#xq-a").click(function(){
                    $("#xq-ul").toggle();
                });
            });
        </script>
        <a href="#" class="xq-a1" id="xq-a"><span class="xq-s3" id="xq-s3"> 其他门店 > </span>&nbsp&nbsp理我最近的门店：<?php echo ceil($sdata[0]['juli']); ?>m</a>
        <p style="color:#000;font-size:14px;padding-left:0.8rem;padding-top:0.2rem;"><?php echo $sdata[0]['STORE_NAME']; ?></p>
        <p style="color:#666;font-size:14px;padding-left:1rem;">地址： <?php echo $sdata[0]['ADDRESS']; ?></p>
        <p style="color:#666;font-size:14px;padding-left:1rem;">电话： <?php echo $sdata[0]['OFFICE_TEL']; ?></p>
        <ul class="xq-ul" id="xq-ul">
            <input type="hidden" id="store-cd" value="<?php if(!empty($sid)): ?><?php echo $sid; endif; ?>">
            <?php if(is_array($sdata) || $sdata instanceof \think\Collection || $sdata instanceof \think\Paginator): $i = 0; $__LIST__ = $sdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <li class="store-click" data-id="<?php echo $v['STORE_CD']; ?>">
                <a href="javascript:void(0);">
                    <p class="xqu-p1">
                        <img src="__STATIC__images/xqtt_02.jpg">
                    </p>
                    <p class="xqu-p2"><?php echo $v['STORE_NAME']; ?></p>
                    <p class="xqu-p3">(<?php echo $v['juli']; ?>km)</p>
                </a>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul><!--xq-mdno end-->
    </div><!--xq-md end-->
    <div class="xq-fw">
        <p class="xqf-p1">项目详情</p>
		<div style="padding:0 0.5rem;">
        <p class="xqf-p2">
           <?php echo htmlspecialchars_decode($data[0]['PROJECT_INFO']); ?>
        </p>
		</div>
    </div><!--xq-fw end-->
  <!--  <div class="xq-fw">
        <p class="xqf-p1"><span class="xq-s3"> > </span>服务评价</p>
        <dl class="xqf-dl">
            <dd>
                <div class="xqf-p3">
                    <ul class="xqf-ul1">
                        <li><img src="__STATIC__images/xq_07.jpg"></li>
                        <li><img src="__STATIC__images/xq_07.jpg"></li>
                        <li><img src="__STATIC__images/xq_07.jpg"></li>
                        <li><img src="__STATIC__images/xq_07.jpg"></li>
                        <li><img src="__STATIC__images/xqt_09.jpg"></li>
                    </ul>
                    <span class="xqf-s2">飞翔的企鹅（2017-4-25）</span>
                </div>
                <p class="xqf-p4">技师按摩的很仔细，服务到位。</p>
                <p class="xqf-p5">五十三号技师</p>
            </dd>

        </dl>
    </div>-->
</div><!--box1 end-->
<div class="foot1">
    <a href="javascript:void(0);" class="ft-a1" id="store-bind">下一步</a>
</div>
<script>
    $(function(){
        //选择门店
        $('.store-click').click(function(){
            $('.store-click').css('border','none');
            $(this).css('border','1px solid red');
            var store_cd = $(this).attr('data-id');
            $('#store-cd').val(store_cd);

        });
        //预约跳转
        $('#store-bind').click(function(){
            var store_cd = $('#store-cd').val();
            var pid = "<?php echo $pid; ?>";
            if(!store_cd){
				$('#xq-ul').show();
				alert('请选择您要预约的门店!');
				return false;           
            }
            window.location.href = "__PATH__wx/order/orderPlain?pid="+pid+"&store_cd="+store_cd;
        });
    })
</script>
</body>
</html>