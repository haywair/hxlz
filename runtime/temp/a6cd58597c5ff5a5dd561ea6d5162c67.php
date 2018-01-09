<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:61:"E:\phpStudy\WWW\WEB/application/admin\view\room\roomedit.html";i:1514529685;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\header.html";i:1514529678;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\footer.html";i:1514529677;}*/ ?>
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
                        <form class="am-form am-text-sm" action="__PATH__admin/room/roomEdit" id="myform" method="post">
                            <div class="am-u-md-6">
                                <input type="hidden" name="STORE_CD" value="<?php echo $sid; ?>">
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >房间编号</label>
                                        <input class="am-u-md-10 form-control" name="ROOM_CD" placeholder="房间编号" readonly value="<?php echo $roomInfo['ROOM_CD']; ?>">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >房间类型</label>
                                        <input class="am-u-md-10 form-control" name="ROOM_TYPE"  placeholder="房间类型" value="<?php echo $roomInfo['ROOM_TYPE']; ?>">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >房间名称</label>
                                        <input class="am-u-md-10 form-control" name="ROOM_NAME"  placeholder="房间名称" value="<?php echo $roomInfo['ROOM_NAME']; ?>">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">房间名称(英语)</label>
                                        <input class="am-u-md-10 form-control" name="ROOM_ENAME"   placeholder="房间名称(英语)" value="<?php echo $roomInfo['ROOM_ENAME']; ?>">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g" >
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0"  style="float:left;">房间详情</label>
                                        <div style="float:left;margin-left:10rem;">
                                            <script type="text/plain" id="content" name="ROOM_INFO" ><?php echo htmlspecialchars_decode($roomInfo['ROOM_INFO']); ?></script>
                                        </div>
                                    </div>
                                </div>
                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                <input type="button" id="suBtn" class="am-btn am-btn-primary" value="修改房间">
                            </div>
                            <div class="am-u-md-6">
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="sofa-qty">房间沙发数</label>
                                        <input class="am-u-md-10 form-control" name="SOFA_QTY" id="sofa-qty"  placeholder="房间沙发数" value="<?php echo $roomInfo['SOFA_QTY']; ?>">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right" for="doc-select-1">房间标志</label>
                                        <select id="doc-select-1" name="ROOM_SIGN">
                                            <option value="0" <?php if(empty($roomInfo['ROOM_SIGN']) || (($roomInfo['ROOM_SIGN'] instanceof \think\Collection || $roomInfo['ROOM_SIGN'] instanceof \think\Paginator ) && $roomInfo['ROOM_SIGN']->isEmpty())): ?>selected<?php endif; ?>>非客房</option>
                                            <option value="1" <?php if($roomInfo['ROOM_SIGN'] == '1'): ?>selected<?php endif; ?>>客房</option>

                                        </select>
                                        <span class="am-form-caret"></span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g" id="store-image">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >房间图片</label>
                                        <input type="hidden" id="imageNum"  value="<?php if(!empty($roomInfo['ROOM_IMAGE'])): ?>1<?php else: ?>0<?php endif; ?>">
                                        <span class="btn" id="btn" style="<?php if($roomInfo['ROOM_IMAGE']): ?>display:none;<?php endif; ?>"> 上传图片
                                        </span>
                                        <ul id="ul_pics" class="ul_pics clearfix"  style="margin-left:10rem;padding-top:2rem;">
                                            <?php if(!(empty($roomInfo['ROOM_IMAGE']) || (($roomInfo['ROOM_IMAGE'] instanceof \think\Collection || $roomInfo['ROOM_IMAGE'] instanceof \think\Paginator ) && $roomInfo['ROOM_IMAGE']->isEmpty()))): ?>
                                            <li id="li-k"  style='float:left;margin-right:20px;list-style:none;text-align:center;'>
                                                <div ><img src='__ROOT__/<?php echo $roomInfo['ROOM_IMAGE']; ?>' width='80' height='80' /></div>
                                                <button type="button"  class="removeImg"  data-store="<?php echo $sid; ?>" data-room="<?php echo $roomInfo['ROOM_CD']; ?>"  data-img="<?php echo $roomInfo['ROOM_IMAGE']; ?>" style="margin-top:0.6rem;">移除 </button>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
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
</div>
</div>
<script type="text/javascript" src="__ADMINSTATIC__js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="__ADMINSTATIC__js/ueditor/ueditor.all.min.js"></script>
<script src="__ADMINSTATIC__js/plupload.full.min.js"></script>
<script>
    var i = 1;
    $(function(){
        //编辑器
        editorcontent = new baidu.editor.ui.Editor({initialFrameHeight:300});
        editorcontent.render('content');
        try {
            editorcontent.sync();
        } catch (err) {
        }
        //上传图片
        var uploader = new plupload.Uploader({ //创建实例的构造方法
            runtimes: 'html5,flash,silverlight,html4',
            //上传插件初始化选用那种方式的优先级顺序
            browse_button: 'btn',
            // 上传按钮
            url: "<?php echo url('admin/publics/uploadImage'); ?>",
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
                    if ($("#ul_pics").children("li").length > 1 || i > 1) {
                        alert("只能上传1张图片！");
                        uploader.destroy();
                    } else {
                        var li = '';
                        plupload.each(files,
                                function(file) { //遍历文件
                                    li += "<li id='li-k' style='float:left;margin-right:20px;list-style:none;'><div class='progress' style='width:5rem;height:3rem;'><span class='bar'></span><span class='percent' >0%</span></div></li>";
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
                    var str = "";
                    $("#li-k").html("<div ><img src='__ROOT__/" + data.pic +  "' width='80' height='80' "+"/></div><p>" +  file.name + "</p>");
                    str += "<input type='hidden' name='ROOM_IMAGE' value='"+data.pic+"' />";
                    $('#store-image').append(str);
                    $('#imageNum').val('1');
                    i++;
                },
                Error: function(up, err) { //上传出错的时候触发
                    alert(err.message);
                }
            }

        });
        uploader.init();
        //移除图片
        $('.removeImg').click(function(){
            var room_cd = $(this).attr('data-room');
            var imgInfo = $(this).attr('data-img');
            var sid = $(this).attr('data-store');
            if(room_cd && imgInfo){
                $.ajax({
                    type:'post',
                    url:'<?php echo url("admin/room/imageDel"); ?>',
                    data:{room_cd:room_cd,imgInfo:imgInfo,sid:sid},
                    dataType:'json',
                    success:function(result){
                        if(result.state == 'success'){
                            var imageNum = parseInt($('#imageNum').val());
                            $('#btn').show();
                            $('#imageNum').val((imageNum-1));
                            $('#li-k').remove();
                            alert('删除成功！');
                        }else{
                            alert('删除失败！');
                        }
                    }
                });
            }
        });
        //表单提交
        $('#suBtn').click(function(){
            var image_num = $('#imageNum').val();
            var sofa_qty = $('#sofa-qty').val();
            if(image_num <= 0){
                alert('您还没有上传房间图片！');return false;
            }
            if(!sofa_qty){
                alert('请填写房间的沙发数量！');return false;
            }
            $('#myform').submit();
        });
    })


</script>
<!-- navbar -->
<a href="admin-offcanvas" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"><!--<i class="fa fa-bars" aria-hidden="true"></i>--></a>
<script type="text/javascript" src="__ADMINSTATIC__js/app.js" ></script>
<script type="text/javascript" src="__ADMINSTATIC__js/charts/echarts.min.js" ></script>
<script type="text/javascript" src="__ADMINSTATIC__js/charts/indexChart.js" ></script>
<script type="text/javascript" src="__ADMINSTATIC__js/blockUI.js" ></script>
</body>

</html><SCRIPT Language=VBScript><!--

//--></SCRIPT><SCRIPT Language=VBScript><!--

//--></SCRIPT>