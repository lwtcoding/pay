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
    <link href="/Public/css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="index.html">
            <img src="/Public/img/logo.png" class="img-thumbnail" width="50px" style="border-radius: 90%;background: none;border: none;" />
            通莞营销
        </a>

    </div>

    <div class="header-right">

        <a href="message-task.html" title="New Message"><i class="fa fa-envelope-o fa-2x"></i><span class="badge">50</span></a>
        <a href="message-task.html"  title="New Task"><i class="fa fa-bars fa-2x"><span class="badge">50</span></i></a>
        <a href="/index.php/Home/Index/logout"  title="Logout"><i class="fa fa-exclamation-circle fa-2x"></i></a>

    </div>
</nav>
<!-- /. NAV TOP  -->
<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li>
                <div class="user-img-div">
                    <img src="/Public/img/user.png" class="img-thumbnail" />

                    <div class="inner-text">
                        <?php echo ($merchant["merchantname"]); ?>
                        <br />
                        <small>Last Login : 2 Weeks Ago </small>
                    </div>
                </div>

            </li>


            <li>
                <a class="active-menu" href="/index.php/admin/Index/body"><i class="fa fa-dashboard "></i>控制台</a>
            </li>
            <li>
                <a href="/index.php/admin/Merchant/payconfig"><i class="fa fa-anchor "></i>支付配置</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-desktop "></i>商家管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="/index.php/admin/Merchant/submerchants"><i class="fa fa-code "></i>代理管理</a>
                    </li>
                    <li>
                        <a href="/index.php/admin/Index/body"><i class="fa fa-toggle-on"></i>员工管理</a>
                    </li>
                    <li>
                        <a href="/index.php/admin/Store/stores"><i class="fa fa-bell "></i>门店管理</a>
                    </li>
                    <li>
                        <a href="progress.html"><i class="fa fa-circle-o "></i>权限分配</a>
                    </li>
                    <li>
                        <a href="icons.html"><i class="fa fa-bug "></i>Icons</a>
                    </li>
                    <li>
                        <a href="wizard.html"><i class="fa fa-bug "></i>Wizard</a>
                    </li>
                    <li>
                        <a href="typography.html"><i class="fa fa-edit "></i>Typography</a>
                    </li>
                    <li>
                        <a href="grid.html"><i class="fa fa-eyedropper "></i>Grid</a>
                    </li>


                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-yelp "></i>收银台 <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="invoice.html"><i class="fa fa-coffee"></i>刷卡支付</a>
                    </li>
                    <li>
                        <a href="pricing.html"><i class="fa fa-flash "></i>二维码支付</a>
                    </li>
                    <li>
                        <a href="component.html"><i class="fa fa-key "></i>Components</a>
                    </li>
                    <li>
                        <a href="social.html"><i class="fa fa-send "></i>Social</a>
                    </li>

                    <li>
                        <a href="message-task.html"><i class="fa fa-recycle "></i>Messages & Tasks</a>
                    </li>


                </ul>
            </li>
            <li>
                <a href="table.html"><i class="fa fa-flash "></i>订单流水 </a>

            </li>
            <li>
                <a href="#"><i class="fa fa-bicycle "></i>营销功能 <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                    <li>
                        <a href="form.html"><i class="fa fa-desktop "></i>卡券管理 </a>
                    </li>
                    <li>
                        <a href="form-advance.html"><i class="fa fa-code "></i>满立减</a>
                    </li>


                </ul>
            </li>
            <li>
                <a href="error.html"><i class="fa fa-bug "></i>Error Page</a>
            </li>
            <li>
                <a href="login.html"><i class="fa fa-sign-in "></i>Login Page</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-sitemap "></i>Multilevel Link <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="#"><i class="fa fa-bicycle "></i>Second Level Link</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-flask "></i>Second Level Link</a>
                    </li>
                    <li>
                        <a href="#">Second Level Link<span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="#"><i class="fa fa-plus "></i>Third Level Link</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-comments-o "></i>Third Level Link</a>
                            </li>

                        </ul>

                    </li>
                </ul>
            </li>

            <li>
                <a href="blank.html"><i class="fa fa-square-o "></i>Blank Page</a>
            </li>
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

            <iframe src="index.php/Home/Index/body" frameborder="no" scrolling="no" style="width: 100%;"  id="cwin" ></iframe>
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
    Copyright &copy; 2016.Company name All rights reserved.<a target="_blank" href="#">&#x7F51;&#x9875;&#x6A21;&#x677F;</a>
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