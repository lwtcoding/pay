<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>支付成功</title>

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
<div id="page-inner" >

    <div class="row">

        <div  class="col-xs-12 col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
            <div class="panel panel-default" style="margin-top: 30%;">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-2 col-lg-2">
                            <h1 class="text-primary"><span class="glyphicon glyphicon-ok-sign"></span></h1>
                        </div><!-- /.col-->
                        <div class="col-xs-10 col-lg-10">
                            <h3>你已成功支付</h3>

                        </div><!-- /.col-->
                    </div><!-- /.row -->
                </div>
            </div>


        </div><!--/.main-->
    </div>

    <!-- /. ROW  -->
    <hr/>



</div>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);
    }
</script>
</body>
</html>