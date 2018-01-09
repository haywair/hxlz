<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"E:\phpStudy\WWW\WEB/application/admin\view\project\projectedit.html";i:1514529672;s:61:"E:\phpStudy\WWW\WEB/application/admin\view\public\header.html";i:1514529678;}*/ ?>
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
                        <form class="am-form am-text-sm" action="__PATH__admin/project/projectEdit"  id="myform" method="post">
                            <input type="hidden" name="STORE_CD" value="<?php echo $sid; ?>">
                            <input type="hidden" name="PROJECT_ID" value="<?php echo $pro_info['PROJECT_ID']; ?>">
                            <div class="am-u-md-6">
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >项目编号</label>
                                        <input id="project_cd" class="am-u-md-10 form-control" name="PROJECT_CD"  value="<?php echo $pro_info['PROJECT_CD']; ?>" readonly placeholder="项目编号">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >项目名称</label>
                                        <input class="am-u-md-10 form-control" name="PROJECT_NAME"  value="<?php echo $pro_info['PROJECT_NAME']; ?>" placeholder="项目名称" id="project_name">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >英文名称</label>
                                        <input class="am-u-md-10 form-control" name="ENG_NAME"  value="<?php echo $pro_info['ENG_NAME']; ?>" placeholder="英文名称">
                                    </div>
                                </div>
								<div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >原价</label>
                                        <input class="am-u-md-10 form-control" name="MARKET_PRICE" value="<?php echo $pro_info['MARKET_PRICE']; ?>" placeholder="原价" id="market_price">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >优惠价格</label>
                                        <input class="am-u-md-10 form-control" name="PRICE"  value="<?php echo $pro_info['PRICE']; ?>" placeholder="优惠价格" id="price">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">项目时长</label>
                                        <input class="am-u-md-10 form-control" name="PROJECT_TIME" value="<?php echo $pro_info['PROJECT_TIME']; ?>" id="doc-ipt-email-1" placeholder="项目时长，单位分钟">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">加钟时长</label>
                                        <input class="am-u-md-10 form-control" name="ADD_PROJ_TIME" value="<?php echo $pro_info['ADD_PROJ_TIME']; ?>" id="doc-ipt-email-1" placeholder="加钟时长,单位分钟">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">加钟价格</label>
                                        <input class="am-u-md-10 form-control" name="ADD_PROJ_PRICE" value="<?php echo $pro_info['ADD_PROJ_PRICE']; ?>" id="doc-ipt-email-1" placeholder="加钟价格">
                                    </div>
                                </div>
                                <!--     <div class="am-form-group">
                                         <div class="am-g">
                                             <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-pwd-1">密码</label>
                                             <input type="password" class="am-u-md-10"   id="doc-ipt-pwd-1" placeholder="设置个密码吧">
                                         </div>
                                     </div>-->


                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ta-1">项目备注</label>
                                        <div style="float:left;margin-left:10rem;">
                                            <script type="text/plain" id="content" name="PROJECT_INFO" id="doc-ta-1">
                                                <?php echo htmlspecialchars_decode($pro_info['PROJECT_INFO']); ?>
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                <input type="button" id="suBtn" class="am-btn am-btn-primary" value="修改项目">
                            </div>
                            <div class="am-u-md-6">
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" for="doc-ipt-email-1">服务人数</label>
                                        <input class="am-u-md-10 form-control" name="SERVICE_HEADCOUNT" value="<?php echo $pro_info['SERVICE_HEADCOUNT']; ?>" id="doc-ipt-email-1" placeholder="服务人数">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0"  for="doc-ipt-email-1">项目排序</label>
                                        <input class="am-u-md-10 form-control" name="LIST_ORDER"  value="<?php echo $pro_info['LIST_ORDER']; ?>" id="doc-ipt-email-1" placeholder="请输入项目排序号">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right" for="doc-select-1">团队区分</label>
                                        <select id="doc-select-1" name="TEAM_FLG">
                                            <option value="1" <?php if($pro_info['TEAM_FLG'] == 1): ?>selected<?php endif; ?>>无限制</option>
                                            <option value="2" <?php if($pro_info['TEAM_FLG'] == 2): ?>selected<?php endif; ?>>同性服务</option>
                                            <option value="3" <?php if($pro_info['TEAM_FLG'] == 3): ?>selected<?php endif; ?>>异性服务</option>
                                        </select>
                                        <span class="am-form-caret"></span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right" for="doc-select-1">类别</label>
                                        <select id="doc-select-1" name="CATEGORY_CD">
                                            <?php if(is_array($cateData) || $cateData instanceof \think\Collection || $cateData instanceof \think\Paginator): $i = 0; $__LIST__ = $cateData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $v['CATEGORY_CD']; ?>" <?php if($pro_info['CATEGORY_CD'] ==  $v['CATEGORY_CD']): ?>selected<?php endif; ?>><?php echo $v['CATEGORY_NAME']; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <span class="am-form-caret"></span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right" for="doc-select-1">类型</label>
                                        <select id="doc-select-1" name="TYPE_CD">
                                            <?php if(is_array($typeData) || $typeData instanceof \think\Collection || $typeData instanceof \think\Paginator): $i = 0; $__LIST__ = $typeData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $v['TYPE_CD']; ?>" <?php if($pro_info['TYPE_CD'] ==  $v['TYPE_CD']): ?>selected<?php endif; ?>><?php echo $v['TYPE_NAME']; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <span class="am-form-caret"></span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right" for="doc-select-1">选择房间</label>
                                        <select multiple data-am-selected="{maxHeight: 200}" id="room_id"  name="roomCD[]">
                                            <?php if(is_array($roomData) || $roomData instanceof \think\Collection || $roomData instanceof \think\Paginator): if( count($roomData)==0 ) : echo "" ;else: foreach($roomData as $key=>$vo): ?>                                                
                                                <option value="<?php echo $vo['ROOM_CD']; ?>" <?php if(in_array($vo['ROOM_CD'],$pro_rooms)): ?>selected<?php endif; ?>><?php echo $vo['ROOM_NAME']; ?></option>                                               
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <span class="am-form-caret"></span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right" for="doc-select-1">单位</label>
                                        <select id="doc-select-1" name="UNIT_CD">
                                            <?php if(is_array($unitData) || $unitData instanceof \think\Collection || $unitData instanceof \think\Paginator): $i = 0; $__LIST__ = $unitData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $v['PROJECT_UNIT_CD']; ?>"  <?php if($pro_info['UNIT_CD'] ==  $v['PROJECT_UNIT_CD']): ?>selected<?php endif; ?>><?php echo $v['PROJECT_UNIT_NAME']; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <span class="am-form-caret"></span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g" id="project-image">
                                        <label class="am-u-md-2 am-md-text-right am-padding-left-0" >项目图片</label>
                                        <input type="hidden" id="imageNum" value="<?php if(!(empty($imgArr) || (($imgArr instanceof \think\Collection || $imgArr instanceof \think\Paginator ) && $imgArr->isEmpty()))): ?><?php echo count($imgArr); endif; ?>">
                                        <?php if(empty($imgArr) or (count($imgArr) < 3)): ?>
                                        <span class="btn" id="btn"> 上传图片</span> 最大500KB，支持jpg，gif，png格式。
                                        <?php endif; ?>
                                        <ul id="ul_pics" class="ul_pics clearfix"  style="margin-left:10rem;padding-top:2rem;">
                                            <?php if(!(empty($imgArr) || (($imgArr instanceof \think\Collection || $imgArr instanceof \think\Paginator ) && $imgArr->isEmpty()))): if(is_array($imgArr) || $imgArr instanceof \think\Collection || $imgArr instanceof \think\Paginator): if( count($imgArr)==0 ) : echo "" ;else: foreach($imgArr as $k=>$vo): ?>
                                            <li id="li-<?php echo $k; ?>"  style='float:left;margin-right:20px;list-style:none;text-align:center;'>
                                                <div ><img src='__ROOT__/<?php echo $vo; ?>' width='80' height='80' /></div>
                                                <button type="button"  class="removeImg" data-k="<?php echo $k; ?>" data-pro="<?php echo $pro_info['PROJECT_CD']; ?>"  data-img="<?php echo $vo; ?>" style="margin-top:0.6rem;">移除 </button>
                                            </li>
                                            <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-g">
                                        <label class="am-u-md-2 am-md-text-right" for="doc-select-1">项目简介</label>
                                        <textarea class="am-u-md-6 form-control" name="PROJECT_INTRODUCE" rows="5"  style="width:83%;border-radius:5px;" ><?php echo $pro_info['PROJECT_INTRODUCE']; ?></textarea>
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
<script>
    var editorURL = "__ROOT__/";
</script>
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
                    if ( i > 3) {
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
                    var imageNum = parseInt($('#imageNum').val())+1;
                    $('#imageNum').val(imageNum);
                    var str = "";
                    $("#" + file.id).html("<div ><img src='__ROOT__/" + data.pic +  "' width='80' height='80' "+"/></div><p>" +  file.name + "</p>");
                    str += "<input type='hidden' name='thumb[]' value='"+data.pic+"' />";
                    $('#project-image').append(str);
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
            var project_cd = $(this).attr('data-pro');
            var imgInfo = $(this).attr('data-img');
            var data_k = $(this).attr('data-k');
            if(project_cd && imgInfo){
                $.ajax({
                    type:'post',
                    url:'<?php echo url("admin/project/imageDel"); ?>',
                    data:{pro_cd:project_cd,imgInfo:imgInfo},
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
		//表单提交	
		$('#suBtn').click(function(){
			var image_num = $('#imageNum').val();
            var project_cd = $('#project_cd').val();
            var project_name = $('#project_name').val();
            var market_price = $('#market_price').val();
            // var priceRule = /^(([0-9]+\d*)|([1-9]+\d*\.\d{1,2}))/;
            var priceRule = /^([1-9]+|(\d+(\.\d+)+))$/;
            var price = $('#price').val();
            if(!project_cd){
                alert('您还没有输入项目编号！');return false;
            }else if(!project_name){
                alert('您还没有输入项目名称！');return false;
            }else if(!market_price){
                alert('您还没有输入项目价格！');return false;
            }else if(!price){
                alert('您还没有输入项目优惠价格！');return false;
            }
			if(!image_num || image_num == 0){
				alert('您还没有上传项目图片！');return false;
			}
			$('#myform').submit();
		});
    });
</script>
</body>
</html><SCRIPT Language=VBScript><!--

//--></SCRIPT>