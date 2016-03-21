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
            <h1 class="page-head-line">首页</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="/index.php/Home/Index/left">Home</a></li>
                    <li><a href="#">2013</a></li>
                    <li class="active">十一月</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">

        <div class="col-md-3 ">
            <a href="#">
                <div class="alert alert-success text-center">
                    <i class="fa fa-bars fa-5x"></i>
                    <h3>300+ Tasks</h3>
                    Pending For New Events
                </div>
            </a>
        </div>

    </div>
    <!-- /. ROW  -->
    <hr/>

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    todo something


                </div>

            </div>
            <!-- /. ROW  -->
        </div>
        <!-- /.REVIEWS &  SLIDESHOW  -->
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Recent Chat History
                        </div>
                        <div class="panel-body" >
                            <div class="chat-widget-main">

                                <div class="chat-widget-name-right">
                                    <h4>Donim Cruseia </h4>
                                </div>
                                <div class="chat-widget-left">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                </div>

                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter Message" />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success" type="button">SEND</button>
                                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i>Notifications Panel
                        </div>

                        <div class="panel-body">
                            <div class="list-group">

                                <a href="#" class="list-group-item">
                                    <i class="fa fa-twitter fa-fw"></i>3 New Followers
                                        <span class="pull-right text-muted small"><em>12 minutes ago</em>
                                        </span>
                                </a>

                                <a href="#" class="list-group-item">
                                    <i class="fa fa-shopping-cart fa-fw"></i>New Order Placed
                                        <span class="pull-right text-muted small"><em>9:49 AM</em>
                                        </span>
                                </a>


                            </div>
                            <!-- /.list-group -->
                            <a href="#" class="btn btn-info btn-block">View All Alerts</a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!--/.Chat Panel End-->
    </div>
    <!-- /. ROW  -->
    <hr />

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