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
    <link href="/Public/css/app.css?var=<?php echo time() ?>" rel="stylesheet" />
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
                        <a class="navbar-brand" href="#"><span><?php echo $_SESSION['loginStaff']['merchantname'] ?></span></a>
                        <small class="pull-right" style="padding: 0px 10px 0px 0px;color: #f5f5f5;">
                           <ul style="list-style: none;">
                               <li><i class="glyphicon glyphicon-user"></i>:<?php echo $_SESSION['loginStaff']['nickname'] ?></li>
                               <li><span class="badge"><?php if($_SESSION['loginStaff']['is_sign']==1){ echo "已签到"; }else{ echo "未签到"; } ?>
                               </span></li>
                           </ul>
                        </small>
                    </div>

                </div><!-- /.container-fluid -->
            </nav>
        </div>
        <div class="row wrapper page-heading iconList" style="margin-top: 50px;">
            <ul>
                <li class="col-xs-4">
                    <a href="/index.php/home/staff/storeStatistics"><i class="fa fa-inbox animated bounceIn"></i>订单流水</a>
                </li>
               
                <li class="col-xs-4">
                    <a href="/index.php/home/staff/sign"><i class="fa fa-pencil animated bounceIn"></i>签到</a>
                </li>
                <li class="col-xs-4">
                    <a href="/index.php/home/staff/signout"><i class="fa fa-file-text-o animated bounceIn"></i>签退</a>
                </li>
                <li class="col-xs-4">
                    <a href="/index.php/home/staff/unboundwx"><i class="fa fa-undo animated bounceIn"></i>解除绑定</a>
                </li>
                <!--
               <li class="col-xs-4">
                   <a href="/index.php/home/staff/boundwx"><i class="fa fa-money animated bounceIn"></i>绑定微信</a>
               </li>

               <li class="col-xs-4">
                   <a href="/index.php/home/staff/showOrder?order_id=72"><i class="fa  fa-unlock-alt animated bounceIn"></i>修改密码</a>
               </li>
               -->
            </ul>
        </div>


    </div>

</div>
</body>
</html>