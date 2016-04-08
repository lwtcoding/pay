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
            <h1 class="page-head-line">满立减设置</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Lijian</a></li>
                    <li class="active">lijian</li>
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
                <strong>警告！</strong>立减金额不能大于条件金额，否则会支付失败。
            </div>
        </div>
        <div class="col-md-12 ">
            <form class="form-horizontal" role="form"  action="/index.php/admin/lijian/add"  method="post" >
                <input type="hidden" name="id" value="<?php echo ($lijian['id']); ?>">
                <input type="hidden" name="mid" value="<?php echo $_SESSION['loginMerchant']['id']; ?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label>企业名称</label>
                        </div>
                        <a class="btn btn-info" onclick="addconfig()">添加规则</a>
                    </div>
                </div>
                <?php if(empty($configs)): ?><div class="form-group">
                    <div class="col-sm-7 col-sm-offset-3 input-group">
                        <span class="input-group-addon">满</span>
                        <input placeholder="0.00元" type="text" class="form-control" name="config[0][limit]">
                        <span class="input-group-addon">减</span>
                        <input placeholder="0.00元" type="text" class="form-control" name="config[0][reduce]">
                    </div>
                </div><?php endif; ?>
                <?php if(!empty($configs)): if(is_array($configs)): $i = 0; $__LIST__ = $configs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i; if(($i == 1)): ?><div class="form-group" >
                                    <div class="col-sm-7 col-sm-offset-3 input-group">
                                        <span class="input-group-addon">满</span>
                                        <input placeholder="0.00元" type="text" class="form-control" name="config[<?php echo ($i-1); ?>][limit]" value="<?php echo ($c['limit']); ?>">
                                        <span class="input-group-addon">减</span>
                                        <input placeholder="0.00元" type="text" class="form-control" name="config[<?php echo ($i-1); ?>][reduce]" value="<?php echo ($c['reduce']); ?>">
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="form-group config" index="<?php echo ($i-1); ?>">
                                    <div class="col-sm-7 col-sm-offset-3 input-group">
                                        <span class="input-group-addon">满</span>
                                        <input placeholder="0.00元" type="text" class="form-control" name="config[<?php echo ($i-1); ?>][limit]" value="<?php echo ($c['limit']); ?>">
                                        <span class="input-group-addon">减</span>
                                        <input placeholder="0.00元" type="text" class="form-control" name="config[<?php echo ($i-1); ?>][reduce]" value="<?php echo ($c['reduce']); ?>">
                                    </div>
                                </div><?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
                <div class="form-group" id="addconfig">
                    <div class="col-sm-2 col-sm-offset-5 ">
                        <label>
                            <input value="1" name="status" type="checkbox" <?php if($lijian['status']==1){ echo checked; } ?>> 启用
                        </label>

                    </div>
                    <div class="col-sm-2 ">
                        <a class="btn btn-danger" onclick="delconfig()">撤销</a>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-4">
                        <button class="btn btn-info form-control" >保存</button>
                    </div>

                    <?php if($submerchants&&$lijian){ ?>
                    <div class="col-sm-2 ">
                        <a  href="#" data-toggle="modal" data-target="#shareLijian" class="btn btn-info form-control" >共享规则</a>
                    </div>
                    <?php } ?>

                </div>

            </form>
        </div>

    </div>
    <!-- /. ROW  -->
</div>
<!-- wx模态框（Modal） -->
<div class="modal fade" id="shareLijian" tabindex="-1" role="dialog"
     aria-labelledby="shareLijianModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="shareLijianModalLabel">
                    选择需要配置的子商户
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="shareLijianForm" action="/index.php/admin/lijian/share" method="post" >
                    <div class="form-group">
                        <input type="text" name="id" value="<?php echo ($lijian['id']); ?>">
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
                <button type="button" class="btn btn-primary" onclick="mySubmit('shareLijian','/index.php/admin/lijian/share')">
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
    }

    function addconfig(){
        var size = $("#addconfig").parent().find(".config").size();
        var index;
    if(size<=0){
        index=1;
    }else{
        index=$("#addconfig").parent().find(".config").last().attr("index");
        index++;
    }
        $("#addconfig").before(
                "<div class='form-group config' index='"+index+"' >"+
                "<div class='col-sm-7 col-sm-offset-3 input-group'>"+
                "<span class='input-group-addon'>满</span>"+
                "<input placeholder='0.00元' type='text' class='form-control' name='config["+index+"][limit]'>"+
                "<span class='input-group-addon'>减</span>"+
                "<input placeholder='0.00元' type='text' class='form-control' name='config["+index+"][reduce]'>"+
                "</div>"+
                "</div>"
        );
    }
    function delconfig(){
        var $configs = $("#addconfig").parent().find(".config");
        if($configs.size()>0){

            $configs.last().remove();
        }
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