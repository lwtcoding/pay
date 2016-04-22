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
    <link href="/Public/css/sweetalert/sweetalert.css" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<div id="page-inner" >

    <div class="row">

        <div class="col-md-12">
            <div id="preload" ></div>
            <h1 class="page-head-line">折扣设置</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Discount</a></li>
                    <li class="active">discount</li>
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
                <strong>提示！</strong>折扣为100即不打折，90为9折，以此类推。
            </div>
        </div>
        <div class="col-md-12 ">
            <form class="form-horizontal" role="form"  action="/index.php/admin/discount/add"  method="post" >
                <input type="hidden" name="id" value="<?php echo ($discount['id']); ?>">
                <input type="hidden" name="mid" value="<?php echo $_SESSION['loginMerchant']['id']; ?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-4 input-group">

                        <input placeholder="请输入折扣，例如（100）"   type="text" class="form-control" name="discount" value="<?php echo ($discount['discount']); ?>">
                        <span class="input-group-addon">%</span>

                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-4 input-group">
                        <select name="status">
                            <?php if($discount['status']==0){ ?>
                            <option value="0" selected>停用</option>
                            <?php }else{ ?>
                            <option value="0">停用</option>
                            <?php } ?>
                            <?php if($discount['status']==1){ ?>
                            <option value="1" selected>启用</option>
                            <?php }else{ ?>
                            <option value="1">启用</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-4">
                        <button class="btn btn-info form-control" >保存</button>
                    </div>

                    <?php if($submerchants&&$discount){ ?>
                    <div class="col-sm-2 ">
                        <a  href="#" data-toggle="modal" data-target="#shareDiscount" class="btn btn-info form-control" >共享规则</a>
                    </div>
                    <?php } ?>

                </div>

            </form>
        </div>

    </div>
    <!-- /. ROW  -->
</div>
<!-- wx模态框（Modal） -->
<div class="modal fade" id="shareDiscount" tabindex="-1" role="dialog"
     aria-labelledby="shareDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="shareDiscountModalLabel">
                    选择需要配置的子商户
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="shareDiscountForm" action="/index.php/admin/discount/share" method="post" >
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo ($discount['id']); ?>">
                            <?php if(is_array($submerchants)): $i = 0; $__LIST__ = $submerchants;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$merchant): $mod = ($i % 2 );++$i;?><div class="col-md-3">
                                    <input type="checkbox" name="submids[]" value="<?php echo ($merchant['id']); ?>">
                                    <label> <?php echo ($merchant['merchantname']); ?></label>

                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="mySubmit('shareDiscount','/index.php/admin/discount/share')">
                    保存
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="/Public/js/bootstrap.js"></script>
<script src="/Public/js/sweetalert/sweetalert.min.js"></script>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);
        $("")
    }


    function mySubmit(formId,url){
        var params = $("#"+formId+"Form").serialize();
       // alert(params);
        $.ajax({
            url:url,
            data:params,
            dataType:"json",
            type:"post",
            success: function (data) {
               // alert(JSON.stringify(data));
                swal({
                    title: data.message,
                    text: data.data,
                    type: data.result
                }, function () {
                    //window.location.reload();
                });

                $('#'+formId).modal('hide');
            },
            error:function(){
                swal({
                    title: "操作失败！",
                    text: "网络异常",
                    type: "error"
                }, function () {
                    //window.location.reload();
                });
            }
        })
    }
</script>
</body>
</html>