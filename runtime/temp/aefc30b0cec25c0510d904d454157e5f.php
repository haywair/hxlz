<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"E:\phpStudy\WWW\WEB/application/admin\view\room\roomtimeadd.html";i:1514529687;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\header.html";i:1514529678;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\footer.html";i:1514529677;}*/ ?>
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
                <div class="card-box"　style="min-height:300px;">
                    <div class="am-g">
                        <form class="am-form am-text-sm" action="__PATH__admin/room/roomTimeAdd"  method="post">
                            <div class="am-u-md-6">
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right " >选择门店 </label>
                                        <input type="hidden" name="STORE_NAME" id="store_name">
                                        <select name="STORE_CD"  style="width:82%;" id="select-store">
                                            <option value="">请选择门店</option>
                                            <?php if(is_array($store_list) || $store_list instanceof \think\Collection || $store_list instanceof \think\Paginator): if( count($store_list)==0 ) : echo "" ;else: foreach($store_list as $key=>$vo): ?>
                                                <option value="<?php echo $vo['STORE_CD']; ?>"><?php echo $vo['STORE_NAME']; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="time-select"></div>
                                <!--<div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >结束时间</label>

                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >时段价格</label>

                                    </div>
                                </div>-->

                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                <input type="submit" class="am-btn am-btn-primary" value="创建">
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
<script>
    $(function(){
        //获取店铺名称
      $('#select-store').change(function(){
         var store_id = $(this).val();
         if(store_id) {
            var store_name = $('option[value="' + store_id + '"]').text();
            $('#store_name').val(store_name);
         }
         $.ajax({
             type:'post',
             url:'<?php echo url("admin/room/getRoomTime"); ?>',
             data:{store_cd:store_id},
             dataType:'json',
             success:function(result){
                 if(result.state == 'success'){
                    var str = '';
                    $.each(result.time,function(i,item){
                        str += '<div class="am-form-group"><div class="am-g"><label class="am-u-md-2 am-md-text-right am-padding-left-0" >时段</label>';
                        str += '<input class="am-u-md-10 form-control input-time" name="ORDER_START_DATE_TIME[]" readonly value="'+item.start+'">';
                        str += '<span class="span-mark">　~~　</span>';
                        str += '<input class="am-u-md-10 form-control input-time" name="ORDER_END_DATE_TIME[]" readonly value="'+item.end+'">';
                        str += '<span class="span-title"> 　价格　</span>';
                        str += '<input class="am-u-md-10 form-control input-time" name="PRICE[]" placeholder="时段价格">';
                        str += '</div></div>';
                    });
                    $('#time-select').html(str);
                 }else{
                     alert(result.msg);
                 }
             }
         });
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