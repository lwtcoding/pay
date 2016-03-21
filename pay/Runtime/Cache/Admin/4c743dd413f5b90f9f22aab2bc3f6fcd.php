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
        <?php if($payconfig['wx_status'] != 1 ): ?><div class="col-md-10 col-md-offset-1 ">
                <div class="alert alert-warning">
                    <a href="#" class="close" data-dismiss="alert">
                        &times;
                    </a>
                    <strong>警告！</strong>你的微信支付未配置，可能会影响支付功能。
                </div>
            </div><?php endif; ?>
        <?php if($payconfig['weixin']['ali_status'] != 1 ): ?><div class="col-md-10 col-md-offset-1 ">
                <div class="alert alert-warning">
                    <a href="#" class="close" data-dismiss="alert">
                        &times;
                    </a>
                    <strong>警告！</strong>你的支付宝支付未配置，可能会影响支付功能。
                </div>
            </div><?php endif; ?>
    </div>
    <!-- /. ROW  -->

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 text-center">
                    <a href="#wxpayConfig" data-toggle="modal" data-target="#wxpayConfig">
                        <img width="150px" src="/Public/img/user.png"
                         class="img-circle" alt="微信配置">
                    </a>
                    <p>微信支付</p>
                </div>
                <div class="col-md-3 text-center">
                    <img width="150px" src="/Public/img/user.png"
                         class="img-circle" alt="微信配置">
                    <p>支付宝</p>
                </div>
            </div>

        </div>
        <!-- /.REVIEWS &  SLIDESHOW  -->
    </div>
    <!-- /. ROW  -->
    <hr />
</div>
<!-- wx模态框（Modal） -->
<div class="modal fade" id="wxpayConfig" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    微信支付配置
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" name="wxform" action="/index.php/admin/merchant/saveconfig" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="weixin">
                    <input type="hidden" name="mid" value="<?php echo $_SESSION['loginMerchant']['id'] ?>">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">appid</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="appid" name="appid" value="<?php echo ($config['weixin']['appid']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">appsecret</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="appsecret" placeholder="appsecret" value="<?php echo ($config['weixin']['appsecret']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">mchid</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="mchid" placeholder="商户号" value="<?php echo ($config['weixin']['mchid']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">apikey</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="apikey" placeholder="apikey" value="<?php echo ($config['weixin']['apikey']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">apiclient_cert</label>
                        <div class="col-sm-2">
                            <?php if(empty($config['weixin']['apiclient_cert'])): ?>未上传<?php else: ?>已上传<?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <input type="file" name="apiclient_cert" placeholder="apiclient_cert.pem">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">apiclient_key</label>
                        <div class="col-sm-2">
                            <?php if(empty($config['weixin']['apiclient_key'])): ?>未上传<?php else: ?>已上传<?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <input type="file"  name="apiclient_key" placeholder="apiclient_key.pem">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">rootca.pem</label>
                        <div class="col-sm-2">
                            <?php if(empty($config['weixin']['rootca'])): ?>未上传<?php else: ?>已上传<?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <input type="file" name="rootca" placeholder="rootca.pem">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="javascript:document.wxform.submit()">
                    保存
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="/Public/js/bootstrap.js"></script>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);

        $.ajax
    }
</script>
</body>
</html>