<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>银联通莞</title>

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
                                <form role="form" name="loginform" method="post" action="/index.php/home/index/login">
                                    <hr />
                                    <h5>登录</h5>
                                       <br />
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                                            <input type="text" class="form-control" name="username" placeholder="帐号" />
                                        </div>
                                                                              <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                            <input type="password" class="form-control" name="password"  placeholder="密码" />
                                        </div>
                                    <!--
                                        <div class="form-group">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" /> Remember me
                                            </label>
                                            <span class="pull-right">
                                                   <a href="index.html" >Forget password ? </a> 
                                            </span>
                                        </div>
                                        -->
                                    <div class="form-group">
                                        <label class="checkbox-inline">
                                            <input type="radio" name="type" value="merchant" checked="checked" /> 商家登录
                                        </label>
                                            <span class="pull-right">
                                                  <input type="radio"  name="type" value="staff" /> 员工登录
                                            </span>
                                    </div>
                                    <div class="form-group">

                                            <input type="text" name="verify" placeholder="Your Username " />

                                            <span class="pull-right">
                                                  <img width="100px" id="code" onclick="changeCode()" src="/index.php/home/index/verify">
                                            </span>
                                    </div>
                                     
                                     <button type="submit" class="btn btn-primary ">登录</button>
                                    <hr />
                                    <!--
                                    Not register ? <a href="register" >click here </a> or go to <a href="index.html">Home</a>
                                    -->
                                    </form>
                            </div>
                           
                        </div>
                
                
        </div>
    </div>
    <script language="javascript">
        function changeCode(){
            //IE7+/Firefox默认从缓存加载，路径之后加随机参数强制重新下载
            var d        =new Date();
            var oImg    =document.getElementById('code');
            oImg.src    ="/index.php/home/index/verify?t="+d.toString(38);
        }
    </script>
</body>
</html>