<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>注册</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body style="background-color: #E2E2E2;">
<div class="container">
    <div class="row text-center " style="padding-top:100px;">
        <div class="col-md-12">
            <img width="50px" src="/Public/img/logo.png" />
        </div>
    </div>
    <div class="row ">

        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

            <div class="panel-body">
                <form role="form" method="post" action="/index.php/home/index/register">
                    <hr />
                    <h5>注册</h5>
                    <br />
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input type="text" class="form-control" name="username" placeholder="账号" />
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                        <input type="password" class="form-control" name="password"  placeholder="密码" />
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                        <input type="password" class="form-control" name="repassword"  placeholder="确认密码" />
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                        <input type="text" class="form-control" name="merchantname"  placeholder="商家名称" />
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                        <input type="text" class="form-control" name="email"  placeholder="邮箱地址" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary ">注册</button>
                    </div>


                    <hr />
                    Has register ? <a href="register" >click here </a> or go to <a href="index.html">Login</a>
                </form>
            </div>

        </div>


    </div>
</div>

</body>
</html>