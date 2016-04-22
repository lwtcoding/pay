<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo ($order['merchantname']); ?></title>

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
                        <a class="navbar-brand" href="#"><span><?php echo ($order['merchantname']); ?>(<?php echo ($order['storename']); ?>)</span></a>
                    </div>

                </div><!-- /.container-fluid -->
            </nav>
        </div>
        <div class="row" style="margin-top: 50px;">
            <div class="col-xs-10 col-xs-offset-1">
                <div><lable>付款金额：</lable><span class="pull-right" style="font-size: 20px;">￥<?php echo ($order['total_fee']); ?></span></div>
                <hr/>
                <div><lable>订&nbsp;&nbsp;单&nbsp;&nbsp;号：</lable><span class="pull-right"><?php echo ($order['order_no']); ?></span></div>
                <div><lable>支付时间：</lable><span class="pull-right"><?php echo ($order['time_end']); ?></span></div>
                <div><lable>支付状态：</lable><span class="pull-right"><?php echo ($order['status']); ?></span></div>
            </div>
        </div>
    </div>

</div>
</body>
</html>