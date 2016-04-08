<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>通莞营销</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="/Public/css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css?var=<?php echo time() ?>" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<div id="page-inner" style="overflow-x: hidden;">

    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">


                        <a class="navbar-brand" href="#"><span>通莞营销</span></a>
                    </div>

                </div><!-- /.container-fluid -->
            </nav>
        </div>
        <div class="col-md-12" style="margin-top: 50px;">
            <img src="/Public/img/logo.png" class="img-circle center-block" alt="通莞营销" width="110px" height="110px">
        </div>
        <div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
            <form class="form-horizontal" role="form" action="/index.php/home/staff/login"  method="post" >


                <div class="form-group"  style="margin-top: 15px;">
                    <label  class="col-sm-2 control-label"></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="员工帐号" name="username" >
                    </div>
                </div>
                <div class="form-group"  style="margin-top: 15px;">
                    <label  class="col-sm-2 control-label"></label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" placeholder="密码" name="password" >
                    </div>
                </div>
                <div class="form-group" style="margin-top: 35px;">
                    <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                        <button class="form-control btn btn-info text-center"  >登录</button>
                    </div>
                </div>
            </form>
            <div class="form-group" style="margin-top: 35px;">
                <div class="col-sm-12 col-xs-12 text-center">

                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 col-xs-12 text-center">
<span>技术支持有银联通莞提供</span>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>