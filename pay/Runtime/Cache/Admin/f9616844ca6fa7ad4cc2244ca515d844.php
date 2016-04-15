<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>银联通莞</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="/Public/css/basic.css?var=<?php echo time(); ?>" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
    <div id="wrapper">

        <!DOCTYPE html>
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
    <link href="/Public/css/basic.css?var=<?php echo time(); ?>" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css?var=<?php echo time(); ?>" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0;background: #5bc0de;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="#" style="background: #5bc0de;">
        <img src='/Public/img/logo.png' class='img-thumbnail' width='50px' style='border-radius: 90%;background: none;border: none;' />
            通莞营销
        </a>

    </div>


    <div class='header-right'>

        <a href='#' title='New Message'><i class='fa fa-envelope-o fa-2x'></i><span class='badge'>50</span></a>
        <a href='#'  title='New Task'><i class='fa fa-bars fa-2x'><span class='badge'>50</span></i></a>
        <a href='/index.php/Home/Index/logout'  title='Logout'><i class='fa fa-exclamation-circle fa-2x'></i></a>

    </div>
</nav>
<!-- /. NAV TOP  -->
<nav class="navbar-default navbar-side" role="navigation" >
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <!--
            <li>
                <div class="user-img-div"  >
                    <img src="/Public/img/logo.png" class="img-thumbnail img-circle" />

                    <div class="inner-text">
                        <?php echo ($merchant["merchantname"]); ?>
                        <br />
                        <small> </small>
                    </div>
                </div>

            </li>
-->

            <li>
                <a  href="/index.php/admin/Index/body"><i class="glyphicon glyphicon-home"></i>控制台</a>
            </li>
            <li>
                <a href="javascript:void(0)"><i class="glyphicon glyphicon-wrench"></i>系统配置<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <?php if($_SESSION['loginMerchant']['is_proxy']==1) { ?>
                    <li>
                        <a href="/index.php/admin/Merchant/payconfig"><i class=""></i>支付配置</a>
                    </li>
                    <?php } ?>
                    <li>
                        <a href="/index.php/admin/Pageinfo/pageinfo"><i class=""></i>页面信息</a>
                    </li>
                </ul>
            </li>
            <?php if($_SESSION['loginMerchant']['parent_id']>0) { ?>
            <li>
                <a href="javascript:void(0)"><i class="fa fa-desktop "></i>商家管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="/index.php/admin/Store/stores"><i class=""></i>门店管理</a>
                    </li>
                    <li>
                        <a href="/index.php/admin/Staff/staffs"><i class=""></i>员工管理</a>
                    </li>
                    <li>
                        <a href="/index.php/admin/Order/orders"><i class=" "></i>订单管理</a>
                    </li>
                    <li>
                        <a href="/index.php/admin/Order/statistics"><i class=""></i>交易统计 </a>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php if(($_SESSION['loginMerchant']['is_proxy']==1)||($_SESSION['loginMerchant']['parent_id']==0)) { ?>
            <li>
                <?php if($_SESSION['loginMerchant']['is_proxy']==1) { ?>
                <a href="javascript:void(0)"><i class="fa fa-desktop "></i>代理商管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="/index.php/admin/Merchant/submerchants"><i class=""></i>代理商管理</a>
                    </li>
                    <?php }else{ ?>
                    <a href="javascript:void(0)"><i class="fa fa-desktop "></i>商户管理<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/index.php/admin/Merchant/submerchants"><i class=""></i>商户管理</a>
                        </li>
                        <li>
                            <a href="/index.php/admin/Store/stores"><i class=""></i>门店管理</a>
                        </li>
                        <li>
                            <a href="/index.php/admin/Staff/staffs"><i class=""></i>员工管理</a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="/index.php/admin/Order/proxyorders"><i class=""></i>订单管理</a>
                    </li>
                    <li>
                        <a href="/index.php/admin/Order/proxystatistics"><i class=""></i>交易统计 </a>

                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php if($_SESSION['loginMerchant']['is_proxy']==0) { ?>
            <li>
                <a href="javascript:void(0)"><i class="fa fa-yelp "></i>收银台 <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="/index.php/admin/Pay/jspay"><i class=""></i>二维码支付</a>
                    </li>
                </ul>
            </li>
            <?php } ?>

             <?php if($_SESSION['loginMerchant']['is_proxy']==0) { ?>
            <li>
                <a href="javascript:void(0)"><i class="glyphicon glyphicon-th-list"></i>营销功能 <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="/index.php/admin/Card/cards"><i class=""></i>会员卡管理</a>
                    </li>
                    <li>
                        <a href="/index.php/admin/Card/add"><i class=""></i>添加会员卡 </a>
                    </li>
                    <li>
                        <a href="/index.php/admin/Lijian/lijian"><i class=""></i>满立减</a>
                    </li>
                </ul>
            </li>
           <?php } ?>
        </ul>

    </div>

</nav>
<!-- /. FOOTER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="/Public/js/bootstrap.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="/Public/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="/Public/js/custom.js"></script>

</body>
</html>

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">

            <iframe src="" frameborder="no" scrolling="no" style="width: 100%;"  id="cwin" ></iframe>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

<!DOCTYPE html>
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
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<div id="footer-sec">
    技术支持由银联通莞提供  tel：200-2222222
</div>

</body>
</html>
<!-- /. FOOTER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="/Public/js/bootstrap.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="/Public/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="/Public/js/custom.js"></script>
<script language="JavaScript">
    onload=function(){

        $("#main-menu >li a").click(function(event){
            var url = $(this).attr("href");
            var iframe = document.getElementById("cwin");
            iframe.src = url;
            event.preventDefault();
        });

        var iframe = document.getElementById("cwin");
        iframe.src = '/index.php/Admin/Index/body';

        if (iframe.attachEvent){
        //iframe.attachEvent("onload",AutoResize.call(iframe)); #报错
            iframe.attachEvent("onload", function(){
                AutoResize(iframe);
            });
        } else {

        //iframe.onload = AutoResize.call(iframe);#报错不支持
            iframe.onload = function(){
                AutoResize(iframe)
            };
        }
    }
    function AutoResize(iframe)
    {
        //firefox
        if(iframe.contentWindow)
        {

            iframe.height = iframe.contentWindow.document.documentElement.scrollHeight;
            iframe.width = iframe.contentWindow.document.documentElement.scrollWidth;

        }
        //IE
        else if(iframe.contentDocument) {

            iframe.height = iframe.contentDocument.width;
            iframe.width = iframe.contentDocument.height;
        }
    }

</script>


</body>
</html>