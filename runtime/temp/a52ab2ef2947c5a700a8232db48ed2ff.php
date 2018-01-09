<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:60:"E:\phpStudy\WWW\WEB/application/wx\view\user\userreview.html";i:1502863722;s:57:"E:\phpStudy\WWW\WEB/application/wx\view\public\heads.html";i:1514961986;s:56:"E:\phpStudy\WWW\WEB/application/wx\view\public\foot.html";i:1498214442;}*/ ?>
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
                $(tabtit).find("li:first").addClass("thistabr").show();
                $(tab_conbox).find("li:first").show();

                $(tabtit).find("li").bind(shijian,function(){
                    $(this).addClass("thistabr").siblings("li").removeClass("thistabr");
                    var activeindex = $(tabtit).find("li").index(this);
                    $(tab_conbox).children().eq(activeindex).show().siblings().hide();
                    return false;
                });

            };
            /*调用方法如下：*/
            $.jqtab("#tabsr","#tab_conboxr","click");

        });
    </script>
    <div id="tabboxr">
        <ul class="tabsr" id="tabsr">
            <li><a href="#">全部</a></li>
            <li><a href="#">好评</a></li>
            <li><a href="#">差评</a></li>
        </ul>
        <ul class="tab_conboxr" id="tab_conboxr">
            <li class="tab_conr">
                <?php if(!(empty($evaluateAll) || (($evaluateAll instanceof \think\Collection || $evaluateAll instanceof \think\Paginator ) && $evaluateAll->isEmpty()))): ?>
                <dl class="pq-dl">
                    <?php if(is_array($evaluateAll) || $evaluateAll instanceof \think\Collection || $evaluateAll instanceof \think\Paginator): if( count($evaluateAll)==0 ) : echo "" ;else: foreach($evaluateAll as $key=>$v): 
                        $goodStar = $v['REMARK'];
                        $badStar = 5 - $v['REMARK'];
                     ?>
                    <dd>
                        <div class="pq-dv2">
                            <img src="<?php echo $v['PHOTO_HEAD']; ?>">
                        </div><!--pq-dv2 end-->
                        <div class="pq-dv3">
                            <div class="pq-p2">
                                <span class="pq-s3"><?php echo date('Y-m-d H:i:s',$v['CREATE_DATE']); ?></span><?php echo $v['NICK_NAME']; ?>
                            </div>
                                <div class="pq-p3">
                                    <span class="pq-s4">评分</span>
                                    <span class="pq-s5">
                                        <?php if(!(empty($v['REMARK']) || (($v['REMARK'] instanceof \think\Collection || $v['REMARK'] instanceof \think\Paginator ) && $v['REMARK']->isEmpty()))): if(!(empty($goodStar) || (($goodStar instanceof \think\Collection || $goodStar instanceof \think\Paginator ) && $goodStar->isEmpty()))): 
                                                  for($i=1;$i<=$goodStar;$i++){
                                                     echo '<img src="__STATIC__images/xq_07.jpg">';
                                                  }
                                                endif; if(!(empty($badStar) || (($badStar instanceof \think\Collection || $badStar instanceof \think\Paginator ) && $badStar->isEmpty()))): 
                                                  for($t=1;$t<=$badStar;$t++){
                                                     echo '<img src="__STATIC__images/xqt_09.jpg">';
                                                  }
                                                endif; else: $__FOR_START_14502__=0;$__FOR_END_14502__=5;for($i=$__FOR_START_14502__;$i < $__FOR_END_14502__;$i+=1){ ?>
                                                <img src="__STATIC__images/xqt_09.jpg">
                                            <?php } endif; ?>
                                    </span>
                                </div>
                            <div class="pq-p4"><?php echo $v['CONTENT']; ?></div>
                            <div class="pq-p5">
                                <a href="#"><span class="pq-s6"><img src="<?php echo $v['PROJECT_IMAGE']; ?>"></span>
                                    <span class="pq-s6a">
                            	<p class="pq-p1a"><?php echo $v['PROJECT_NAME']; ?></p>
                                <p class="pq-p2a"><?php echo htmlspecialchars_decode($v['PROJECT_INFO']); ?></p>
                            </span></a>
                            </div>
                            <?php if(!(empty($v['STORE_REPLY']) || (($v['STORE_REPLY'] instanceof \think\Collection || $v['STORE_REPLY'] instanceof \think\Paginator ) && $v['STORE_REPLY']->isEmpty()))): ?>
                            <div class="pq-p6">
                                <span class="pq-s7"><img src="__STATIC__images/ly_03.jpg"></span>
                                <span class="pq-s8"><font class="pq-f3">商家回复：</font><font class="pq-f4"><?php echo $v['STORE_REPLY']; ?></font>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div><!--pq-dv3 end-->

                    </dd>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </dl>
                <?php else: ?>
                    <div class="no-review">暂无您的评价记录，马上预约体验吧...</div>
                <?php endif; ?>
            </li>
            <li class="tab_conr">
				<?php if(!(empty($evaluateGood) || (($evaluateGood instanceof \think\Collection || $evaluateGood instanceof \think\Paginator ) && $evaluateGood->isEmpty()))): ?>	
                <dl class="pq-dl">
                    <?php if(is_array($evaluateGood) || $evaluateGood instanceof \think\Collection || $evaluateGood instanceof \think\Paginator): if( count($evaluateGood)==0 ) : echo "" ;else: foreach($evaluateGood as $key=>$v): 
                    $goodStar = $v['REMARK'];
                    $badStar = 5 - $v['REMARK'];
                     ?>
                    <dd>
                        <div class="pq-dv2">
                            <img src="<?php echo $v['PHOTO_HEAD']; ?>">
                        </div><!--pq-dv2 end-->
                        <div class="pq-dv3">
                            <div class="pq-p2">
                                <span class="pq-s3"><?php echo date('Y-m-d H:i:s',$v['CREATE_DATE']); ?></span><?php echo $v['NICK_NAME']; ?>
                            </div>
                            <div class="pq-p3">
                                <span class="pq-s4">评分</span>
                                <span class="pq-s5">
                                        <?php if(!(empty($v['REMARK']) || (($v['REMARK'] instanceof \think\Collection || $v['REMARK'] instanceof \think\Paginator ) && $v['REMARK']->isEmpty()))): if(!(empty($goodStar) || (($goodStar instanceof \think\Collection || $goodStar instanceof \think\Paginator ) && $goodStar->isEmpty()))): 
                                                  for($i=1;$i<=$goodStar;$i++){
                                                     echo '<img src="__STATIC__images/xq_07.jpg">';
                                                  }
                                                endif; if(!(empty($badStar) || (($badStar instanceof \think\Collection || $badStar instanceof \think\Paginator ) && $badStar->isEmpty()))): 
                                                  for($t=1;$t<=$badStar;$t++){
                                                     echo '<img src="__STATIC__images/xqt_09.jpg">';
                                                  }
                                                endif; else: $__FOR_START_1677__=0;$__FOR_END_1677__=5;for($i=$__FOR_START_1677__;$i < $__FOR_END_1677__;$i+=1){ ?>
                                                <img src="__STATIC__images/xqt_09.jpg">
                                            <?php } endif; ?>
                                    </span>
                            </div>
                            <div class="pq-p4"><?php echo $v['CONTENT']; ?></div>
                            <div class="pq-p5">
                                <a href="#"><span class="pq-s6"><img src="<?php echo $v['PROJECT_IMAGE']; ?>"></span>
                                    <span class="pq-s6a">
                            	<p class="pq-p1a"><?php echo $v['PROJECT_NAME']; ?></p>
                                <p class="pq-p2a"><?php echo $v['PROJECT_INFO']; ?></p>
                            </span></a>
                            </div>
                            <?php if(!(empty($v['STORE_REPLY']) || (($v['STORE_REPLY'] instanceof \think\Collection || $v['STORE_REPLY'] instanceof \think\Paginator ) && $v['STORE_REPLY']->isEmpty()))): ?>
                            <div class="pq-p6">
                                <span class="pq-s7"><img src="__STATIC__images/ly_03.jpg"></span>
                                <span  class="pq-s8"><font class="pq-f3">商家回复：</font><font class="pq-f4"><?php echo $v['STORE_REPLY']; ?></font></span>
                            </div>
                            <?php endif; ?>
                        </div><!--pq-dv3 end-->

                    </dd>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </dl>
				<?php else: ?>
                
                <div class="no-review">暂无您的好评记录，马上预约体验吧...</div>
				<?php endif; ?>
            </li>
            <li class="tab_conr">
                <?php if(!(empty($evaluateBad) || (($evaluateBad instanceof \think\Collection || $evaluateBad instanceof \think\Paginator ) && $evaluateBad->isEmpty()))): ?>
                <dl class="pq-dl">
                    <?php if(is_array($evaluateBad) || $evaluateBad instanceof \think\Collection || $evaluateBad instanceof \think\Paginator): if( count($evaluateBad)==0 ) : echo "" ;else: foreach($evaluateBad as $key=>$v): 
                    $goodStar = $v['REMARK'];
                    $badStar = 5 - $v['REMARK'];
                     ?>
                    <dd>
                        <div class="pq-dv2">
                            <img src="<?php echo $v['PHOTO_HEAD']; ?>">
                        </div><!--pq-dv2 end-->
                        <div class="pq-dv3">
                            <div class="pq-p2">
                                <span class="pq-s3"><?php echo date('Y-m-d H:i:s',$v['CREATE_DATE']); ?></span><?php echo $v['NICK_NAME']; ?>
                            </div>
                            <div class="pq-p3">
                                <span class="pq-s4">评分</span>
                                <span class="pq-s5">
                                        <?php if(!(empty($v['REMARK']) || (($v['REMARK'] instanceof \think\Collection || $v['REMARK'] instanceof \think\Paginator ) && $v['REMARK']->isEmpty()))): if(!(empty($goodStar) || (($goodStar instanceof \think\Collection || $goodStar instanceof \think\Paginator ) && $goodStar->isEmpty()))): 
                                                  for($i=1;$i<=$goodStar;$i++){
                                                     echo '<img src="__STATIC__images/xq_07.jpg">';
                                                  }
                                                endif; if(!(empty($badStar) || (($badStar instanceof \think\Collection || $badStar instanceof \think\Paginator ) && $badStar->isEmpty()))): 
                                                  for($t=1;$t<=$badStar;$t++){
                                                     echo '<img src="__STATIC__images/xqt_09.jpg">';
                                                  }
                                                endif; else: $__FOR_START_30596__=0;$__FOR_END_30596__=5;for($i=$__FOR_START_30596__;$i < $__FOR_END_30596__;$i+=1){ ?>
                                                <img src="__STATIC__images/xqt_09.jpg">
                                            <?php } endif; ?>
                                    </span>
                            </div>
                            <div class="pq-p4"><?php echo $v['CONTENT']; ?></div>
                            <div class="pq-p5">
                                <a href="#"><span class="pq-s6"><img src="<?php echo $v['PROJECT_IMAGE']; ?>"></span>
                                    <span class="pq-s6a">
                            	<p class="pq-p1a"><?php echo $v['PROJECT_NAME']; ?></p>
                                <p class="pq-p2a"><?php echo $v['PROJECT_INFO']; ?></p>
                            </span></a>
                            </div>
                            <?php if(!(empty($v['STORE_REPLY']) || (($v['STORE_REPLY'] instanceof \think\Collection || $v['STORE_REPLY'] instanceof \think\Paginator ) && $v['STORE_REPLY']->isEmpty()))): ?>
                            <div class="pq-p6">
                                <span class="pq-s7"><img src="__STATIC__images/ly_03.jpg"></span>
                                <span class="pq-s8"><font class="pq-f3">商家回复：</font><font class="pq-f4"><?php echo $v['STORE_REPLY']; ?></font></span>
                            </div>
                            <?php endif; ?>
                        </div><!--pq-dv3 end-->

                    </dd>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </dl>
                <?php else: ?>
                <div class="no-review">暂无您的差评记录，谢谢您的支持与厚爱...</div>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</div><!--box1 end-->
<ul class="foot">
    <li><a href="__PATH__wx/home/home"><p class="f-p1"><img src="__STATIC__images/ftt_03.png"  width="23"></p><p class="f-p2">首页</p></a></li>
    <li><a href="__PATH__wx/order/orderList"><p class="f-p1"><img src="__STATIC__images/lz_13.jpg" width="23"></p><p class="f-p2">订单</p></a></li>
    <li><a href="__PATH__wx/home/getMore"><p class="f-p1"><img src="__STATIC__images/lz_19.jpg" width="23"></p><p class="f-p2">商城</p></a></li>
    <li><a href="__PATH__wx/user/me"><p class="f-p1"><img src="__STATIC__images/lz_21.jpg" width="20"></p><p class="f-p2">会员中心</p></a></li>
</ul><!--foot end-->
</body>
</html>