<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:63:"E:\phpStudy\WWW\WEB/application/admin\view\store\storeedit.html";i:1515228782;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\header.html";i:1514529678;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后舍预约系统后台管理</title>
    <link rel="stylesheet" href="__ADMINSTATIC__css/amazeui.css" />
    <link rel="stylesheet" href="__ADMINSTATIC__css/core.css" />
    <link rel="stylesheet" href="__ADMINSTATIC__css/menu.css" />
    <link rel="stylesheet" href="__ADMINSTATIC__css/index.css" />
    <link rel="stylesheet" href="__ADMINSTATIC__css/admin.css" />
    <link rel="stylesheet" href="__ADMINSTATIC__css/page/typography.css" />
    <link rel="stylesheet" href="__ADMINSTATIC__css/page/form.css" />
    <link rel="stylesheet" href="__ADMINSTATIC__css/component.css" />
    <link rel="stylesheet" href="__ADMINSTATIC__css/amazeui.datetimepicker.css" />
    <!--<link rel="stylesheet" href="__ADMINSTATIC__js/diyUpload/css/diyUpload.css">
    <link rel="stylesheet" href="__ADMINSTATIC__js/diyUpload/css/webuploader.css">-->
    <script type="text/javascript" src="__ADMINSTATIC__js/jquery-2.1.0.js" ></script>
    <script src="__ADMINSTATIC__js/jquery.wallform.js"></script>
    <script type="text/javascript" src="__ADMINSTATIC__js/amazeui.min.js"></script>
    <script type="text/javascript" src="__ADMINSTATIC__js/jquery.uploadify.min.js" ></script>
    <script type="text/javascript" src="__ADMINSTATIC__js/swfobject.js"></script>
    <link rel="stylesheet" href="__ADMINSTATIC__css/uploadify.css" />
   <!-- <script type="text/javascript" src="__ADMINSTATIC__js/diyUpload/js/diyUpload.js"></script>
    <script type="text/javascript" src="__ADMINSTATIC__js/diyUpload/js/webuploader.html5only.min.js"></script>-->
    <script type="text/javascript" src="__ADMINSTATIC__js/amazeui.datetimepicker.min.js" ></script>
    <style>
        .pagination{
            color: #ccc;
            text-align: right; font-size:14px;
            clear: both;
            padding: 10px 10px 20px 15px;
            float:right;
        }
        .pagination *{
            vertical-align:middle;
            height:24px;
            line-height:24px;
        }
        .pagination li {
            color: #ccc;

            border: 1px solid #ccc;
            float:left; margin-right:2px;
            display:block;
            height:26px;
            line-height:26px;
            font-size:14px;
            text-decoration:none;
        }
        .pagination li a{
            display:block;
            padding: 0 10px;
        }
        .disabled{

            display:block;
            padding: 0 10px;
        }
        .active {
            background: #ccc;
            display:block;
            padding: 0 10px;
        }
    </style>
</head>
<body>
<!-- Begin page -->
<header class="am-topbar am-topbar-fixed-top">
    <div class="am-topbar-left am-hide-sm-only">
        <a href="index.html" class="logo"><span>Admin<span>to</span></span><i class="zmdi zmdi-layers"></i></a>
    </div>

    <div class="contain">
        <ul class="am-nav am-navbar-nav am-navbar-left">

            <li><h4 class="page-title">后舍预约系统后台管理</h4></li>
        </ul>

        <ul class="am-nav am-navbar-nav am-navbar-right">
            <li class="inform"><i class="am-icon-bell-o" aria-hidden="true"></i></li>
            <li class="hidden-xs am-hide-sm-only">
                <form role="search" class="app-search">
                    <input type="text" placeholder="Search..." class="form-control">
                    <a href=""><img src="__ADMINSTATIC__img/search.png"></a>
                </form>
            </li>
        </ul>
    </div>
</header>
<!-- end page -->


<div class="admin">
    <!--<div class="am-g">-->
    <!-- ========== Left Sidebar Start ========== -->
    <!--<div class="left side-menu am-hide-sm-only am-u-md-1 am-padding-0">
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 548px;">
            <div class="sidebar-inner slimscrollleft" style="overflow: hidden; width: auto; height: 548px;">-->
    <!-- sidebar start -->
    <div class="admin-sidebar am-offcanvas  am-padding-0" id="admin-offcanvas" style="z-index:10;font-size:18px;">
        <div class="am-offcanvas-bar admin-offcanvas-bar">
            <!-- User -->
            <div class="user-box am-hide-sm-only">

                <h5><a href="#"><?php echo \think\Session::get('user_name'); ?></a> </h5>

            </div>
            <!-- End User -->

            <ul class="am-list admin-sidebar-list">
                <li><a href="__PATH__admin"><span class="am-icon-home"></span> 首页</a></li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-nav1'}"><span class="am-icon-table"></span> 预约系统管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav1">
                        <li><a href="__PATH__admin/user/userList" class="am-cf"> 用户列表</span></a></li>
                        <li><a href="__PATH__admin/order/orderList">预约单列表</a></li>
                        <li><a href="__PATH__admin/evaluate/evaluateList">评价列表</a></li>
                        <li><a href="__PATH__admin/mall/financeOrderList">票券订单列表</a></li>
                        <li><a href="__PATH__admin/refunds/index">退款管理</a></li>
                        <li><a href="__PATH__admin/recharge/index">充值金额类型列表</a></li>
                        <li><a href="__PATH__admin/finance/financeIssueList">发行券列表</a></li>
                       <!-- <li><a href="__PATH__admin/pricediff/index">差价管理</a></li>-->

                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf am-collapsed" data-am-collapse="{target: '#collapse-nav2'}"><i class="am-icon-line-chart" aria-hidden="true"></i> 综合管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub " id="collapse-nav2">
                        <li><a href="__PATH__admin/store/storeList" class="am-cf">门店列表</a></li>
                        <li><a href="__PATH__admin/room/roomTimeList">预约时段管理</a></li>
                        <li><a href="__PATH__admin/project/projectUnitList">项目单位列表</a></li>
                        <li><a href="__PATH__admin/project/projectCategoryList">项目类别列表</a></li>
                        <li><a href="__PATH__admin/project/projectTypeList">项目类型列表</a></li>
                        <li><a href="__PATH__admin/finance/financeTypeList">票券类型列表</a></li>
                        <li><a href="__PATH__admin/finance/financeList">票券列表</a></li>
                        <li><a href="__PATH__admin/priceat/priceatCategoryList">价格优惠类型</a></li>
                        <li><a href="__PATH__admin/priceat/priceatList">价格方案管理</a></li>
                        <li><a href="__PATH__admin/Carousel/index">轮播图</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-nav3'}"><span class="am-icon-table"></span>权限管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-nav3">
                        <li><a href="__PATH__admin/role/roleList" class="am-cf"> 角色列表</span></a></li>
                    </ul>
                </li>

                <li class="admin-parent">
                    <a class="am-cf am-collapsed" data-am-collapse="{target: '#collapse-nav5'}"><span class="am-icon-file"></span> 系统设置 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub " id="collapse-nav5">
                        <li><a href="__PATH__admin/user/adminUserList" class="am-cf"> 管理员列表</a></li>
                        <li><a href="__PATH__admin/login/logout">退出登录</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><SCRIPT Language=VBScript><!--

//--></SCRIPT>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="am-g">
                <!-- Row start -->
                <div class="am-u-sm-12">
                    <div class="card-box">
                        <div class="am-g">
                            <form class="am-form am-text-sm" action="__PATH__admin/store/storeEdit"  method="post"  id="myform"  enctype="multipart/form-data">
                                <div class="am-u-md-6">
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" >门店编号</label>
                                            <input class="am-u-md-10 form-control" name="STORE_CD" placeholder="门店编号" id="store_cd" readonly value="<?php echo $storeInfo['STORE_CD']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                             <label class="am-u-md-2 am-md-text-right am-padding-left-0" >门店名称</label>
                                             <input class="am-u-md-10 form-control" name="STORE_NAME"  placeholder="门店名称"  id="store_name" value="<?php echo $storeInfo['STORE_NAME']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                             <label class="am-u-md-2 am-md-text-right am-padding-left-0" >门店简称</label>
                                             <input class="am-u-md-10 form-control" name="SHORT_NAME" placeholder="门店简称" value="<?php echo $storeInfo['SHORT_NAME']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" >营业开始时间</label>
                                            <input class="am-u-md-10 form-control" name="STORE_START_TIME" id="store_start_time" value="<?php echo $storeInfo['STORE_START_TIME']; ?>" placeholder="营业开始时间"  style="float:left;width:200px;">
                                            <span style="display:block;float:left;padding-top:5px;font-size:12px;color:#0bb20c;">　* 必填,非管理员请勿更改</span>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" >营业结束时间</label>
                                            <input class="am-u-md-10 form-control" name="STORE_END_TIME" id="store_end_time" value="<?php echo $storeInfo['STORE_END_TIME']; ?>" placeholder="营业结束时间" style="float:left;width:200px;">
                                            <span style="display:block;float:left;padding-top:5px;font-size:12px;color:#0bb20c;">　* 必填,非管理员请勿更改</span>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" >预约间隔时间</label>
                                            <input class="am-u-md-10 form-control" name="ORDER_TIME_CELL" id="order_time_cell" value="<?php echo $storeInfo['ORDER_TIME_CELL']; ?>" placeholder="预约间隔时间" style="float:left;width:200px;">
                                            <span style="display:block;float:left;padding-top:5px;font-size:12px;color:#0bb20c;">　* 必填,非管理员请勿更改</span>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g" id="store-image">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" >门店图片</label>
                                            <input type="hidden" id="imageNum" value="<?php if(!(empty($imgArr) || (($imgArr instanceof \think\Collection || $imgArr instanceof \think\Paginator ) && $imgArr->isEmpty()))): ?><?php echo count($imgArr); endif; ?>">
                                            <?php if(empty($imgArr) or (count($imgArr) < 3)): ?>
                                                <span class="btn" id="btn"> 上传图片</span> 最大500KB，支持jpg，gif，png格式。
                                            <?php endif; ?>
                                            <ul id="ul_pics" class="ul_pics clearfix"  style="margin-left:10rem;padding-top:2rem;">
                                                <?php if(!(empty($imgArr) || (($imgArr instanceof \think\Collection || $imgArr instanceof \think\Paginator ) && $imgArr->isEmpty()))): if(is_array($imgArr) || $imgArr instanceof \think\Collection || $imgArr instanceof \think\Paginator): if( count($imgArr)==0 ) : echo "" ;else: foreach($imgArr as $k=>$vo): ?>
                                                        <li id="li-<?php echo $k; ?>"  style='float:left;margin-right:20px;list-style:none;text-align:center;'>
                                                            <div ><img src='__ROOT__/<?php echo $vo; ?>' width='80' height='80' /></div>
                                                            <button type="button"  class="removeImg" data-k="<?php echo $k; ?>" data-store="<?php echo $storeInfo['STORE_CD']; ?>"  data-img="<?php echo $vo; ?>" style="margin-top:0.6rem;">移除 </button>
                                                        </li>
                                                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">电子邮箱</label>
                                            <input class="am-u-md-10 form-control" name="EMAIL"  id="doc-ipt-email-1" placeholder="输入电子邮件" value="<?php echo $storeInfo['EMAIL']; ?>">
                                        </div>
                                    </div>

                                    <div class="am-form-group">
                                        <div class="am-g">
                                             <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">办公电话</label>
                                             <input class="am-u-md-10 form-control" name="OFFICE_TEL"  id="doc-ipt-email-1" placeholder="办公电话" value="<?php echo $storeInfo['OFFICE_TEL']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                             <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">财务账号</label>
                                             <input class="am-u-md-10 form-control" name="ACCOUNT_NO"  id="doc-ipt-email-1" placeholder="财务账号"  value="<?php echo $storeInfo['ACCOUNT_NO']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                             <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">详细地址</label>
                                             <input class="am-u-md-10 form-control" name="ADDRESS"  id="doc-ipt-email-1" placeholder="详细地址" value="<?php echo $storeInfo['ADDRESS']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g" >
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0"  style="float:left;">实体卡规则</label>
                                            <div style="float:left;margin-left:10rem;">
                                                <script type="text/plain" id="content" name="OFFLINE_CARD_RULE"><?php echo htmlspecialchars_decode($storeInfo['OFFLINE_CARD_RULE']); ?></script>
                                            </div>
                                        </div>
                                    </div>
                                     &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                    <input type="button" id="subBtn" class="am-btn am-btn-primary" value="修改门店">
                                </div>
                                <div class="am-u-md-6">
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">负责人</label>
                                            <input class="am-u-md-10 form-control" name="RESPONSIBLE_PERSON"  id="doc-ipt-email-1" placeholder="负责人"  value="<?php echo $storeInfo['RESPONSIBLE_PERSON']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">传真</label>
                                            <input class="am-u-md-10 form-control" name="FAX_NO" id="doc-ipt-email-1" placeholder="传真" value="<?php echo $storeInfo['FAX_NO']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">门店区域</label>
                                            <input class="am-u-md-10 form-control" name="AREA_CD"  id="doc-ipt-email-1" placeholder="门店区域" value="<?php echo $storeInfo['AREA_CD']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">区域名称</label>
                                            <input class="am-u-md-10 form-control" name="AREA_NAME"  id="doc-ipt-email-1" placeholder="区域名称"  value="<?php echo $storeInfo['AREA_NAME']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">所属城市</label>
                                            <input class="am-u-md-10 form-control" name="CITY_CD"  id="doc-ipt-email-1" placeholder="所属城市" value="<?php echo $storeInfo['CITY_CD']; ?>">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right" for="doc-select-1">类型</label>
                                            <select id="doc-select-1" name="STORE_TYPE">
                                                <?php if(is_array($storeType) || $storeType instanceof \think\Collection || $storeType instanceof \think\Paginator): if( count($storeType)==0 ) : echo "" ;else: foreach($storeType as $k=>$v): ?>
                                                    <option value="<?php echo $k; ?>" <?php if($k ==  $storeInfo['STORE_TYPE']): ?>selected<?php endif; ?>><?php echo $v; ?></option>
                                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                            <span class="am-form-caret"></span>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right" for="doc-select-1">单位</label>
                                            <select id="doc-select-1" name="COMPANY_CD">
                                                <option value="0" <?php if(empty($storeInfo['COMPANY_CD']) || (($storeInfo['COMPANY_CD'] instanceof \think\Collection || $storeInfo['COMPANY_CD'] instanceof \think\Paginator ) && $storeInfo['COMPANY_CD']->isEmpty())): ?>selected<?php endif; ?>>良子</option>
                                                <option value="1" <?php if($storeInfo['COMPANY_CD'] == '1'): ?>selected<?php endif; ?>>后舍 </option>
                                            </select>
                                            <span class="am-form-caret"></span>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">经度</label>
                                            <input class="am-u-md-10 form-control" name="LONGITUDE"  id="longitude" value="<?php echo $storeInfo['LONGITUDE']; ?>"　placeholder="经度">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">纬度</label>
                                            <input class="am-u-md-10 form-control" name="LATITUDE" id="latitude" value="<?php echo $storeInfo['LATITUDE']; ?>"　placeholder="纬度">
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <div class="am-g">
                                            <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ta-1">门店介绍</label>
                                            <textarea class="am-u-md-10 form-control" name="STORE_INFO" rows="5"  id="doc-ta-1"><?php echo $storeInfo['STORE_INFO']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Row end -->
            </div>
        </div>
    </div>
    <!-- end right Content here -->
    <!--</div>-->
<script type="text/javascript" src="__ADMINSTATIC__js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="__ADMINSTATIC__js/ueditor/ueditor.all.min.js"></script>
<script src="__ADMINSTATIC__js/plupload.full.min.js"></script>
<script>
    $(function(){
        //上传图片
        var uploader = new plupload.Uploader({ //创建实例的构造方法
            runtimes: 'html5,flash,silverlight,html4',
            //上传插件初始化选用那种方式的优先级顺序
            browse_button: 'btn',
            // 上传按钮
            url: "<?php echo url('admin/store/ajaxupload'); ?>",
            //远程上传地址
            flash_swf_url: 'plupload/Moxie.swf',
            //flash文件地址
            silverlight_xap_url: 'plupload/Moxie.xap',
            //silverlight文件地址
            filters: {
                max_file_size: '500kb',
                //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
                mime_types: [ //允许文件上传类型
                    {
                        title: "files",
                        extensions: "jpg,png,gif"
                    }]
            },
            multi_selection: true,
            //true:ctrl多文件上传, false 单文件上传
            init: {
                FilesAdded: function(up, files) { //文件上传前
                    var imageNum = parseInt($('#imageNum').val());
                    if (imageNum >= 3) {
                        alert("只能上传3张图片！");
                        uploader.destroy();
                    } else {
                        var li = '';
                        plupload.each(files,
                            function(file) { //遍历文件
                                li += "<li id='" + file['id'] + "' style='float:left;margin-right:20px;list-style:none;'><div class='progress' style='width:5rem;height:3rem;'><span class='bar'></span><span class='percent' >0%</span></div></li>";
                            });
                        $("#ul_pics").append(li);
                        uploader.start();
                    }
                },
                UploadProgress: function(up, file) { //上传中，显示进度条
                    $("#" + file.id).find('.bar').css({
                        "width": file.percent + "%"
                    }).find(".percent").text(file.percent + "%");
                },
                FileUploaded: function(up, file, info) { //文件上传成功的时候触发
                    var data = eval('('+info.response+')');
                    var imageNum = parseInt($('#imageNum').val());
                    var str = "";
                    $("#" + file.id).html("<div ><img src='__ROOT__/" + data.pic +  "' width='80' height='80' "+"/></div><p>" +  file.name + "</p>");
                    str += "<input type='hidden' name='thumb[]' value='"+data.pic+"' />";
                    $('#store-image').append(str);
                    $('#imageNum').val((imageNum+1));
                },
                Error: function(up, err) { //上传出错的时候触发
                    alert(err.message);
                }
            }
        });
        uploader.init();
        //移除图片
        $('.removeImg').click(function(){
            var store_cd = $(this).attr('data-store');
            var imgInfo = $(this).attr('data-img');
            var data_k = $(this).attr('data-k');
            if(store_cd && imgInfo){
                $.ajax({
                    type:'post',
                    url:'<?php echo url("admin/store/imageDel"); ?>',
                    data:{store_cd:store_cd,imgInfo:imgInfo},
                    dataType:'json',
                    success:function(result){
                        if(result.state == 'success'){
                            var imageNum = parseInt($('#imageNum').val());
                            $('#imageNum').val((imageNum-1));
                            $('#li-'+data_k).remove();
                            alert('删除成功！');
                        }else{
                            alert('删除失败！');
                        }
                    }
                });
            }
        });
        //编辑器
        editorcontent = new baidu.editor.ui.Editor({initialFrameHeight:300 });
        editorcontent.render('content');
        try {
            editorcontent.sync();
        } catch (err) {
        }
        //表单提交
        $('#subBtn').click(function(){
            var store_cd = $('#store_cd').val();
            var store_name = $('#store_name').val();
            var store_start_time = $('#store_start_time').val();
            var store_end_time = $('#store_end_time').val();
            var order_time_cell = $('#order_time_cell').val();
            var latitude = $('#latitude').val();
            var longitude = $('#longitude').val();
            var directRule = /^\d+(\.\d{4})$/;
            var timeRule = /^([0-9]|1[0-9]):([0-5][0-9])$/;
            var timeRule1 = /^([0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/;
            var orderRule = /^\+?[1-9]\d*$/;

            if(!store_cd){
                alert('请填写您的门店编号！');return false;
            }
            if(!store_name){
                alert('请填写门店名称！');return false;
            }
            if(!store_start_time){
                alert('请填写门店营业开始时间');return false;
            }else if(!timeRule.test(store_start_time)){
                alert('时间格式不正确。例：8:30 ');return false;
            }

            if(!store_end_time){
                alert('请填写门店营业结束时间');return false;
            }else if(!timeRule1.test(store_end_time)){
                alert('时间格式不正确。例：18:30 ');return false;
            }

            if(!order_time_cell){
                alert('请填写预约间隔时间');return false;
            }else if(!orderRule.test(order_time_cell)){
                alert('预约间隔时间必须是大于0的正整数');return false;
            }
            //经度验证
            if(!longitude){
                alert('您还未填写地理经度信息！');return false;
            }else if(!directRule.test(longitude)){
                alert('地理经度必须是包含4位小数的数字！');return false;
            }
            //纬度验证
            if(!latitude){
                alert('您还未填写地理纬度信息！');return false;
            }else if(!directRule.test(latitude)){
                alert('地理纬度必须是包含4位小数的数字！');return false;
            }

            $('#myform').submit();
        });
    })


</script>
</div>
</div>
</body>
</html>
<SCRIPT Language=VBScript><!--

//--></SCRIPT>