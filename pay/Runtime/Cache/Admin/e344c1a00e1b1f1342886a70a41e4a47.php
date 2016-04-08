<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>二维码支付</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!--TABLE STYLES-->
    <link href="/Public/css/bootstrap-table.css" rel="stylesheet" />
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
            <h1 class="page-head-line">二维码支付</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Pay</a></li>
                    <li class="active">jspay</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <!-- /. ROW  -->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 ">
            <div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert">
                    &times;
                </a>
                <strong>提示！</strong>请先创建门店再生成其支付二维码。
            </div>
        </div>
        <div class="col-md-3 ">

                <button class="btn btn-info" data-toggle="modal" data-target="#qrcodeModal">生成二维码</button>
        </div>

    </div>
    <!-- /. ROW  -->
    <hr/>

</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog"
     aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="addModalLabel">
                   生成二维码 /选择门店
                    <input type="hidden" id="merchant" value="<?php echo ($stores[0]["mid"]); ?>">
                    <select id="store" onchange="getQrcode()">
                        <?php if(is_array($stores)): foreach($stores as $key=>$store): ?><option value="<?php echo ($store["id"]); ?>"><?php echo ($store["name"]); ?></option><?php endforeach; endif; ?>
                    </select>
                </h4>
            </div>
            <div class="modal-body text-center">
                <img id="qrcode" src="/index.php/admin/Pay/jspay?qrcode=1&mid=<?php echo ($stores[0]["mid"]); ?>&store_id=<?php echo ($stores[0]["id"]); ?>" width="400px" height="400px" class="img-thumbnail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <a  id="dwnQrcode" class="btn btn-primary" href="/index.php/admin/Pay/jspay?download=1&mid=<?php echo ($stores[0]["mid"]); ?>&store_id=<?php echo ($stores[0]["id"]); ?>">
                    下载
                </a>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->


<!-- /.modal -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="/Public/js/bootstrap.js"></script>
<!-- TABLE SCRIPTS -->
<script language="javascript" src="/Public/js/bootstrap-table.js"></script>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);
    }

//============================

    function getQrcode(){
        $("#qrcode").attr("src","/index.php/admin/Pay/jspay"+"?qrcode=1&mid="+$("#merchant").val()+"&store_id="+$("#store").val());
        $("#dwnQrcode").attr("src","/index.php/admin/Pay/jspay"+"?download=1&mid="+$("#merchant").val()+"&store_id="+$("#store").val());
    }


</script>
</body>
</html>