<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:59:"E:\phpStudy\WWW\WEB/application/admin\view\index\index.html";i:1514529661;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\header.html";i:1514529678;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\footer.html";i:1514529677;}*/ ?>
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
                <div class="am-u-md-3">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">总收入</h4>
                        <div class="widget-chart-1 am-cf">
                            <div id="widget-chart-box-1" style="height: 110px;width: 110px;float: left;">
                            </div>

                            <div class="widget-detail-1" style="float: right;">
                                <h2 class="p-t-10 m-b-0"> 256 </h2>
                                <p class="text-muted">今日收入</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col end -->
                <div class="am-u-md-3">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">销售分析</h4>
                        <div class="widget-box-2">
                            <div class="widget-detail-2">
                                <span class="badge  pull-left m-t-20  am-round" style="color: #fff; background: #0e90d2;">32% <i class="zmdi zmdi-trending-up"></i> </span>
                                <h2 class="m-b-0"> 8451 </h2>
                                <p class="text-muted m-b-25">Revenue today</p>
                            </div>
                            <div class="am-progress am-progress-xs am-margin-bottom-0">
                                <div class="am-progress-bar" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col end -->
                <div class="am-u-md-3">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">总收入</h4>
                        <div class="widget-chart-1 am-cf">
                            <div id="widget-chart-box-2" style="height: 110px;width: 110px;float: left;">
                            </div>

                            <div class="widget-detail-1" style="float: right;">
                                <h2 class="p-t-10 m-b-0"> 256 </h2>
                                <p class="text-muted">今日收入</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col end -->
                <div class="am-u-md-3">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">销售分析</h4>
                        <div class="widget-box-2">
                            <div class="widget-detail-2">
                                <span class="badge  pull-left m-t-20  am-round progress-bar-pink" style="color: #fff;">32% <i class="zmdi zmdi-trending-up"></i> </span>
                                <h2 class="m-b-0"> 8451 </h2>
                                <p class="text-muted m-b-25">Revenue today</p>
                            </div>
                            <div class="am-progress am-progress-xs am-margin-bottom-0" style="background: rgba(255, 138, 204, 0.2);">
                                <div class="am-progress-bar progress-bar-pink" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row end -->
            </div>

            <div class="am-g">
                <!-- Row start -->
                <div class="am-u-md-4">
                    <div class="card-box">
                        <h4 class="header-title m-t-0">环形图</h4>
                        <div id="index-pie-1" style="height: 345px;height: 300px;"></div>
                    </div>
                </div>

                <div class="am-u-md-4">
                    <div class="card-box">
                        <h4 class="header-title m-t-0">环形图</h4>
                        <div id="index-bar-1" style="height: 345px;height: 300px;"></div>
                    </div>
                </div>

                <div class="am-u-md-4">
                    <div class="card-box">
                        <h4 class="header-title m-t-0">环形图</h4>
                        <div id="index-line-1" style="height: 345px;height: 300px;"></div>
                    </div>
                </div>
                <!-- Row end -->
            </div>

            <div class="am-g">
                <!-- Row start -->
                <div class="am-u-md-3">
                    <div class="card-box widget-user">
                        <div>
                            <img src="__ADMINSTATIC__img/avatar-3.jpg" class="img-responsive img-circle" alt="user">
                            <div class="wid-u-info">
                                <h4 class="m-t-0 m-b-5 font-600">Chadengle</h4>
                                <p class="text-muted m-b-5 font-13">coderthemes@gmail.com</p>
                                <small class="text-warning"><b>管理员</b></small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col end -->
                <div class="am-u-md-3">
                    <div class="card-box widget-user">
                        <div>
                            <img src="__ADMINSTATIC__img/avatar-2.jpg" class="img-responsive img-circle" alt="user">
                            <div class="wid-u-info">
                                <h4 class="m-t-0 m-b-5 font-600">Chadengle</h4>
                                <p class="text-muted m-b-5 font-13">coderthemes@gmail.com</p>
                                <small class="text-custom"><b>网络组主管</b></small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col end -->
                <div class="am-u-md-3">
                    <div class="card-box widget-user">
                        <div>
                            <img src="__ADMINSTATIC__img/avatar-4.jpg" class="img-responsive img-circle" alt="user">
                            <div class="wid-u-info">
                                <h4 class="m-t-0 m-b-5 font-600">Chadengle</h4>
                                <p class="text-muted m-b-5 font-13">coderthemes@gmail.com</p>
                                <small class="text-success"><b>设计师</b></small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col end -->
                <div class="am-u-md-3">
                    <div class="card-box widget-user">
                        <div>
                            <img src="__ADMINSTATIC__img/avatar-10.jpg" class="img-responsive img-circle" alt="user">
                            <div class="wid-u-info">
                                <h4 class="m-t-0 m-b-5 font-600">Chadengle</h4>
                                <p class="text-muted m-b-5 font-13">coderthemes@gmail.com</p>
                                <small class="text-info"><b>开发者</b></small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col end -->
                <!-- Row end -->
            </div>


            <!-- Row start -->
            <div class="am-g">
                <!-- col start -->
                <div class="am-u-md-4">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">收件箱</h4>
                        <div class="inbox-widget nicescroll" style="height: 315px; overflow: hidden; outline: none;" tabindex="5000">
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="__ADMINSTATIC__img/avatar-1.jpg" class="img-circle" alt=""></div>
                                    <p class="inbox-item-author">Chadengle</p>
                                    <p class="inbox-item-text">Hey! there I'm available...</p>
                                    <p class="inbox-item-date">13:40 PM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="__ADMINSTATIC__img/avatar-2.jpg" class="img-circle" alt=""></div>
                                    <p class="inbox-item-author">Shahedk</p>
                                    <p class="inbox-item-text">Hey! there I'm available...</p>
                                    <p class="inbox-item-date">10:15 AM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="__ADMINSTATIC__img/avatar-10.jpg" class="img-circle" alt=""></div>
                                    <p class="inbox-item-author">Tomaslau</p>
                                    <p class="inbox-item-text">I've finished it! See you so...</p>
                                    <p class="inbox-item-date">13:34 PM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="__ADMINSTATIC__img/avatar-4.jpg" class="img-circle" alt=""></div>
                                    <p class="inbox-item-author">Stillnotdavid</p>
                                    <p class="inbox-item-text">This theme is awesome!</p>
                                    <p class="inbox-item-date">13:17 PM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="__ADMINSTATIC__img/avatar-5.jpg" class="img-circle" alt=""></div>
                                    <p class="inbox-item-author">Kurafire</p>
                                    <p class="inbox-item-text">Nice to meet you</p>
                                    <p class="inbox-item-date">12:20 PM</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- col end -->

                <!-- col start -->
                <div class="am-u-md-8">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">最新项目</h4>
                        <div class="am-scrollable-horizontal am-text-ms" style="font-family: '微软雅黑';">
                            <table class="am-table   am-text-nowrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>项目名称</th>
                                    <th>开始时间</th>
                                    <th>结束时间</th>
                                    <th>状态</th>
                                    <th>责任人</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Adminto Admin v1</td>
                                    <td>01/01/2016</td>
                                    <td>26/04/2016</td>
                                    <td><span class="label label-danger">已发布</span></td>
                                    <td>Coderthemes</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Adminto Frontend v1</td>
                                    <td>01/01/2016</td>
                                    <td>26/04/2016</td>
                                    <td><span class="label label-success">已发布</span></td>
                                    <td>Adminto admin</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Adminto Admin v1.1</td>
                                    <td>01/05/2016</td>
                                    <td>10/05/2016</td>
                                    <td><span class="label label-pink">未开展</span></td>
                                    <td>Coderthemes</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Adminto Frontend v1.1</td>
                                    <td>01/01/2016</td>
                                    <td>31/05/2016</td>
                                    <td><span class="label label-purple">进行中</span>
                                    </td>
                                    <td>Adminto admin</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Adminto Admin v1.3</td>
                                    <td>01/01/2016</td>
                                    <td>31/05/2016</td>
                                    <td><span class="label label-warning">即将开始</span></td>
                                    <td>Coderthemes</td>
                                </tr>

                                <tr>
                                    <td>6</td>
                                    <td>Adminto Admin v1.3</td>
                                    <td>01/01/2016</td>
                                    <td>31/05/2016</td>
                                    <td><span class="label label-primary">即将开始</span></td>
                                    <td>Adminto admin</td>
                                </tr>

                                <tr>
                                    <td>7</td>
                                    <td>Adminto Admin v1.3</td>
                                    <td>01/01/2016</td>
                                    <td>31/05/2016</td>
                                    <td><span class="label label-primary">即将开始</span></td>
                                    <td>Adminto admin</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- col end -->
            </div>
        </div>
    </div>
    <!-- end right Content here -->
    <!--</div>-->
</div>
</div>
<!-- navbar -->
<a href="admin-offcanvas" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"><!--<i class="fa fa-bars" aria-hidden="true"></i>--></a>
<script type="text/javascript" src="__ADMINSTATIC__js/app.js" ></script>
<script type="text/javascript" src="__ADMINSTATIC__js/charts/echarts.min.js" ></script>
<script type="text/javascript" src="__ADMINSTATIC__js/charts/indexChart.js" ></script>
<script type="text/javascript" src="__ADMINSTATIC__js/blockUI.js" ></script>
</body>

</html><SCRIPT Language=VBScript><!--

//--></SCRIPT>

<SCRIPT Language=VBScript><!--

//--></SCRIPT>