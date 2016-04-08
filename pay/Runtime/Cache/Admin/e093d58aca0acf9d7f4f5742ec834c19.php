<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>chinaz</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- BOOTSTRAP FILEINPUT STYLES-->
    <link href="/Public/css/fileinput.mini.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="/Public/css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css?var=<?php echo time();?>" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<div id="page-inner" >

    <div class="row">

        <div class="col-md-12">
            <div id="preload" ></div>
            <h1 class="page-head-line">页面信息</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Pageinfo</a></li>
                    <li class="active">pageinfo</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 ">
            <div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert">
                    &times;
                </a>
                <strong>提示！</strong>本配置用于支付页面信息展示。
            </div>
        </div>
        <div class="col-md-12 ">

            <form class="form-horizontal" role="form"   method="post" >
                <input type="hidden" name="id" value="<?php echo ($pageinfo['id']); ?>">
                <input type="hidden" name="mid" value="<?php echo $_SESSION['loginMerchant']['id'] ?>">
                <div class="form-group">
                    <input type="hidden" id="logoUrl" name="logo" value="<?php echo ($pageinfo['logo']); ?>">
                    <label  class="col-sm-3 control-label">logo</label>
                    <div class="col-sm-7">
                        <input id="input-1" name="logo_temp[]" type="file" multiple class="file-loading" accept="image/*">
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-3 control-label">企业名称</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" placeholder="企业名称" name="companyname" value="<?php echo ($pageinfo['companyname']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-3 control-label">联系方式</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" placeholder="电话或邮箱" name="phone" value="<?php echo ($pageinfo['phone']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-3 control-label">技术支持</label>
                    <div class="col-sm-7">
                        <input type="text"  class="form-control" placeholder="技术支持" name="info" value="<?php echo ($pageinfo['info']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-7 col-sm-offset-3">
                        <button class="btn btn-info form-control" >提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /. ROW  -->
</div>
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="/Public/js/bootstrap.js"></script>
<!-- BOOTSTRAP FILEINPUT SCRIPTS -->
<script src="/Public/js/fileinput.mini.js"></script>
<!-- BOOTSTRAP FILEINPUT SCRIPTS -->
<script src="/Public/js/fileinput_local_zh.js"></script>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);
      var logoUrl = '/Uploads/'+$('#logoUrl').val();
        $("#input-1").fileinput({
            language: 'zh',
            maxFileCount:1,
            uploadUrl: "/index.php/admin/pageinfo/upload",
            allowedFileExtensions: ["jpg","jpeg", "png", "gif"],
            maxImageWidth: 250,
            maxImageHeight: 250,
            initialPreview: [
                "<img src='"+logoUrl+"' class='file-preview-image' />"
            ]
        });
        $('#input-1').on('fileuploaded', function(event, data, previewId, index) {

            $("#logoUrl").val(data.response);
        });

    }
</script>
</body>
</html>