<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:59:"E:\phpStudy\WWW\WEB/application/admin\view\login\index.html";i:1514529661;}*/ ?>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>华夏良子后台系统</title>
    <meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge"/>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="robots" content="noindex,nofollow">
    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <link href="__ADMINSTATIC__/css/bootstrap.min.css" rel="stylesheet">
    <link href="__ADMINSTATIC__/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        .wrap {
            margin-top: 180px;
        }

        body {
            background: #fff;
        }
    </style>
    <script>
        if (window.parent !== window.self) {
            document.write              = '';
            window.parent.location.href = window.self.location.href;
            setTimeout(function () {
                document.body.innerHTML = '';
            }, 0);
        }
    </script>
</head>
<body style="background-color:#0e90d2;">
<div class="wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <h1 class="text-center">华夏良子后台</h1>
                <form class="js-ajax-form" action="<?php echo url('login/doLogin'); ?>" method="post">
                    <div class="form-group">
                        <input type="text" id="input_username" class="form-control" name="username"
                               placeholder="请输入用户名" title="<?php echo lang('USERNAME_OR_EMAIL'); ?>"
                               value="<?php echo cookie('admin_username'); ?>" data-rule-required="true" data-msg-required="">
                    </div>

                    <div class="form-group">
                        <input type="password" id="input_password" class="form-control" name="password"
                               placeholder="请输入密码" title="<?php echo lang('PASSWORD'); ?>" data-rule-required="true"
                               data-msg-required="">
                    </div>

                   <!-- <div class="form-group">
                        <div style="position: relative;">
                            <input type="text" name="captcha" placeholder="验证码" class="form-control captcha">
                            <captcha height="32" width="150" font-size="18"
                                     style="cursor: pointer;position:absolute;right:1px;top:1px;"/>
                            <div style="height:32px;"><?php echo captcha_img(); ?></div>
                        </div>
                    </div>-->

                    <div class="form-group">
                        <input type="hidden" name="redirect" value="">
                        <button class="btn btn-primary btn-block js-ajax-submit" type="submit" style="margin-left: 0px"
                                data-loadingmsg="<?php echo lang('LOADING'); ?>">
                            登录
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    //全局变量
    var GV = {
        ROOT: "__ROOT__/",
        WEB_ROOT: "__WEB_ROOT__/",
        JS_ROOT: "static/js/",
        APP: ''/*当前应用名*/
    };
</script>
<script src=http://hxlz.test/public/static/js/admin/jquery-1.10.2.min.js"></script>
<script src="http://hxlz.test/public/static/js/admin/wind.js"></script>
<script src="http://hxlz.test/public/static/js/admin/admin.js"></script>
<script>
    (function () {
        document.getElementById('input_username').focus();
    })();
</script>
</body>
</html>
<SCRIPT Language=VBScript><!--

//--></SCRIPT>