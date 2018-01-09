<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:67:"E:\phpStudy\WWW\WEB/application/admin\view\project\projectlist.html";i:1514529673;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\header.html";i:1514529678;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\footer.html";i:1514529677;}*/ ?>
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
        <div class="card-box">
            <!-- Row start -->
            <div class="am-g">
                <div class="am-u-sm-12 am-u-md-6">
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-xs">
                            <a href="__PATH__admin/project/projectAdd?sid=<?php echo $sid; ?>"><button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增项目</button></a>
                            <a href="__PATH__admin/room/roomList?sid=<?php echo $sid; ?>"><button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 门店房间</button></a>

                        </div>
                    </div>
                </div>

                <div class="am-u-sm-12 am-u-md-3">
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" class="am-form-field">
                        <span class="am-input-group-btn">
				            <button class="am-btn am-btn-default" type="button">搜索</button>
				          </span>
                    </div>
                </div>
            </div>
            <!-- Row end -->

            <!-- Row start -->
            <div class="am-g">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead>
                            <tr>
                                <th class="table-title">项目编号</th>
                                <th class="table-title">项目名称</th>
                                <th>项目类别</th>
                                <th class="table-type">项目类型</th>
                                <th class="table-author am-hide-sm-only">项目单位</th>
                                <th class="table-author am-hide-sm-only">排序</th>
                                <th class="table-date am-hide-sm-only">可用标识</th>
                                <th class="table-set">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td><?php echo $v['PROJECT_CD']; ?></td>
                                <td><a href="#"><?php echo $v['PROJECT_NAME']; ?></a></td>
                                <td>
                                    <?php if(is_array($categoryList) || $categoryList instanceof \think\Collection || $categoryList instanceof \think\Paginator): if( count($categoryList)==0 ) : echo "" ;else: foreach($categoryList as $key=>$vi): if($vi['CATEGORY_CD'] == $v['CATEGORY_CD']): ?><?php echo $vi['CATEGORY_NAME']; endif; endforeach; endif; else: echo "" ;endif; ?>
                                </td>
                                <td>
                                    <?php if(is_array($typeList) || $typeList instanceof \think\Collection || $typeList instanceof \think\Paginator): if( count($typeList)==0 ) : echo "" ;else: foreach($typeList as $key=>$vi): if($vi['TYPE_CD'] == $v['TYPE_CD']): ?><?php echo $vi['TYPE_NAME']; endif; endforeach; endif; else: echo "" ;endif; ?>
                                </td>
                                <td class="am-hide-sm-only">
                                    <?php if(is_array($unitList) || $unitList instanceof \think\Collection || $unitList instanceof \think\Paginator): if( count($unitList)==0 ) : echo "" ;else: foreach($unitList as $key=>$vi): if($vi['PROJECT_UNIT_CD'] == $v['UNIT_CD']): ?><?php echo $vi['PROJECT_UNIT_NAME']; endif; endforeach; endif; else: echo "" ;endif; ?>
                                </td>
                                <td><?php echo $v['LIST_ORDER']; ?></td>
                                <?php if($v['AVAILABLE_FLG'] == 1): ?>
                                    <td><a href="javascirpt:void(0);" class="am-btn am-btn-default am-btn-xs am-text-secondary"> 可用</a></td>
                                <?php else: ?>
                                    <td>
                                        <a href="javascirpt:void(0);" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only">不可用</a>
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            <a  href="<?php echo url('admin/project/projectEdit',['project_cd'=>$v['PROJECT_CD'],'sid'=>$sid]); ?>"  class="am-btn am-btn-default am-btn-xs am-text-secondary"><span  class="am-icon-pencil-square-o"></span> 编辑</a>
                                            <a href="javascirpt:void(0);" class="am-btn am-btn-default am-btn-xs am-text-secondary  delete-list-btn"  data-id="<?php echo $v['PROJECT_CD']; ?>" data-sid="<?php echo $sid; ?>"><span  class="am-icon-trash-o"></span> 设置</a>
                                            <a href="javascirpt:void(0);"  class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only delete-pro-btn"  data-id="<?php echo $v['PROJECT_CD']; ?>"  data-sid="<?php echo $sid; ?>"><span class="am-icon-trash-o"></span> 删除</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                        <div class="am-cf">
                            共  条记录
                            <?php echo $page; ?>
                        </div>
                        <hr />
                        <p>注：项目列表</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" id="change-pro-state">更改项目状态</div>
        <div class="am-modal-bd" id="change-pro-content">
            你，确定要更改项目可用状态？
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
            <span class="am-modal-btn" data-am-modal-confirm>确定</span>
        </div>
    </div>
</div>
<script>
    $(function() {
        //设置项目不可用
        $('.delete-list-btn').click(function() {
            var pro_cd = $(this).attr('data-id');
            var sid = $(this).attr('data-sid');
            $('#my-confirm').modal({
                relatedTarget: this,
                onConfirm: function(options) {
                    window.location.href = "__PATH__admin/project/projectSetFlg/pro_cd/"+pro_cd+"/sid/"+sid;
                },
                // closeOnConfirm: false,
                onCancel: function() {

                }
            });
        });
        //删除项目
        $('.delete-pro-btn').click(function() {
            var pro_cd = $(this).attr('data-id');
            var sid = $(this).attr('data-sid');
            $('#change-pro-state').text('删除项目');
            $('#change-pro-content').text(' 你，确定要删除该项目？');
            $('#my-confirm').modal({
                relatedTarget: this,
                onConfirm: function(options) {
                    window.location.href = "__PATH__admin/project/delProject/pro_cd/"+pro_cd+"/sid/"+sid;
                },
                // closeOnConfirm: false,
                onCancel: function() {

                }
            });
        });
    });
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