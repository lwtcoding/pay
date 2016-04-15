<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>chinaz</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="/Public/css/basic.css" rel="stylesheet" />
    <link href="/Public/css/app.css?var=<?php echo time() ?>" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css?var=<?php echo time() ?>" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<div id="page-inner" >

    <div class="row">

        <div class="col-md-12">
            <div id="preload" ></div>
            <h1 class="page-head-line">控制台</h1>

            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">

        <div class="row wrapper page-heading iconList">
            <ul>
                <?php if($_SESSION['loginMerchant']['is_proxy']==0) { ?>
                <li class="col-xs-4">
                    <a href="/index.php/admin/Pay/jspay"><i class="fa fa-inbox animated bounceIn"></i>二维码收款</a>
                </li>
                <?php } ?>
                <li class="col-xs-4">
                    <a href="/index.php/admin/Order/orders"><i class="fa fa-pencil animated bounceIn"></i>收款记录</a>
                </li>
                <!--
                <li class="col-xs-4">
                    <a href="/merchants.php?m=User&c=wxCoupon&a=wxReceiveList"><i class="fa fa-file-text-o animated bounceIn"></i>核销记录</a>
                </li>
                <li class="col-xs-4">
                    <a href="/merchants.php?m=User&c=cashier&a=payment&type=2"><i class="fa fa-undo animated bounceIn"></i>退款</a>
                </li>
                <li class="col-xs-4">
                    <a href="/merchants.php?m=User&c=wxCoupon&a=consumeCard"><i class="fa fa-money animated bounceIn"></i>卡券核销</a>
                </li>
                <li class="col-xs-4">
                    <a href="/merchants.php?m=User&c=cashier&a=index"><i class="fa fa-qrcode animated bounceIn"></i>收款二维码</a>
                </li>
                <li class="col-xs-4">
                    <a href="/merchants.php?m=User&c=index&a=ModifyPwd"><i class="fa  fa-unlock-alt animated bounceIn"></i>修改密码</a>
                </li>
                -->
            </ul>
        </div>

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