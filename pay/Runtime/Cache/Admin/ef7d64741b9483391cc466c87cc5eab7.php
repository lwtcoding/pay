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
    <!--TABLE STYLES-->
    <link href="/Public/css/bootstrap-table.css" rel="stylesheet" />
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
            <h1 class="page-head-line">签约商管理</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Merchant</a></li>
                    <li class="active">submerchants</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">

        <div class="col-md-12">
            <div id="toolbar">
                <button id="add" class="btn btn-success" data-toggle="modal" data-target="#subAdd" >
                    <i class="glyphicon glyphicon-plus"></i> 添加商户
                </button>
            </div>
            <table id="tab">
            </table>
            <!-- /. ROW  -->
        </div>
        <!-- /.REVIEWS &  SLIDESHOW  -->
    </div>
    <!-- /. ROW  -->
    <hr />
    <!-- 代理商户添加模态框（Modal） -->
    <div class="modal fade" id="subAdd" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        添加商户
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" id="subAddForm" action="" method="post" >
                        <input type="hidden" name="pid" value="<?php echo ($pid); ?>">
                        <input type="hidden" name="parent_id" value="<?php echo ($parent_id); ?>">
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">账号</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="username" placeholder="账号" value="<?php echo ($config['weixin']['mchid']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">密码</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="password"  placeholder="密码" value="<?php echo ($config['weixin']['appid']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="repassword"  placeholder="确认密码" value="<?php echo ($config['weixin']['appid']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">商家名称</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="merchantname"  placeholder="商家名称" value="<?php echo ($config['weixin']['appid']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">邮箱地址</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="email"  placeholder="邮箱地址" value="<?php echo ($config['weixin']['appid']); ?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <button type="button" class="btn btn-primary" onclick="mySubmit('subAdd','/index.php/admin/merchant/add')">
                        保存
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal -->
    <!-- 代理商户编辑模态框（Modal） -->
    <div class="modal fade" id="subEdit" tabindex="-1" role="dialog"
         aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="editModalLabel">
                        编辑代理商
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" id="subEditForm" action="" method="post" >
                        <input type="hidden" name="id">
                        <input type="hidden" name="salt">
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">账号</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="username" placeholder="账号" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">新密码</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="password"  placeholder="密码" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="repassword"  placeholder="确认密码" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">商家名称</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="merchantname"  placeholder="商家名称" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">邮箱地址</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="email"  placeholder="邮箱地址" value="">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <button type="button" class="btn btn-primary" onclick="mySubmit('subEdit','/index.php/admin/merchant/edit')">
                        保存
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal -->
    <!-- wx支付配置模态框（Modal） -->
    <div class="modal fade" id="wxpayConfig" tabindex="-1" role="dialog"
         aria-labelledby="wxConfigModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="wxConfigModalLabel">
                        微信支付配置
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" id="wxpayConfigForm"  method="post" >
                        <input type="hidden" name="type" value="weixin">
                        <input type="hidden" name="mid" >
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">appid</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" placeholder="appid" name="appid">
                            </div>
                        </div>

                        <div class="form-group">
                            <label  class="col-sm-2 control-label">mchid</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="mchid" placeholder="商户号" >
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <button type="button" class="btn btn-primary" onclick="mySubmit('wxpayConfig','/index.php/admin/merchant/editPayconfig')">
                        保存
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal -->
    <!-- 批量二维码模态框（Modal） -->
    <div class="modal fade" id="batchQrcode" tabindex="-1" role="dialog"
         aria-labelledby="batchQrcodeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="batchQrcodeModalLabel">

                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" id="batchQrcodeForm" name="batchQrcodeForm" action="/index.php/admin/Pay/batchQrcode" method="post" >
                        <input type="hidden" name="batch" value="1">
                        <div class="row" id="stores-container">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <button type="button" class="btn btn-primary" onclick="batchQrcodeForm.submit()">
                        提交
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
<!-- TABLE SCRIPTS -->
<script src="/Public/js/bootstrap-table.js"></script>
<script src="/Public/js/sweetalert/sweetalert.min.js"></script>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);
    }

    function getTab(){
        var url = '/index.php/admin/merchant/submerchants';
        $('#tab').bootstrapTable({
            method: 'post', //这里要设置为get，不知道为什么 设置post获取不了
            url: url,
            cache: false,
            height: 800,
            striped: true,
            pagination: true,
            pageList: [20],
             contentType: "application/x-www-form-urlencoded",
            pageSize:20,
            pageNumber:1,
           // search: true,
            sidePagination:'server',//设置为服务器端分页
            queryParams: queryParams,//参数
            showColumns: true,
            showRefresh: true,
            minimumCountColumns: 2,
            clickToSelect: true,
            smartDisplay:true,
            columns: [
                {
                    field: 'id',
                    title: '编号',
                    align: 'center',
                    width: '180',
                    valign: 'bottom',
                    visible:false,
                    sortable: true
                }, {
                    field: 'merchantname',
                    title: '商户名称',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    sortable: true
                }, {
                    field: 'username',
                    title: '帐号',
                    align: 'center',
                    width: '150',
                    valign: 'top',
                    sortable: true
                }, {
                    field: 'email',
                    title: '邮箱',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    clickToSelect: false
                }, {
                    title: '其他操作',
                    align: 'center',
                    formatter: showFormatter
                }, {
                    field: 'operate',
                    title: '操作',
                    align: 'center',
                    events: operateEvents,
                    formatter: operateFormatter
                }]
        });
    }
    //设置传入参数
    function queryParams(params) {
        params.pid=<?php echo ($pid); ?>;
        params.parent_id=<?php echo ($parent_id); ?>;
        return params
    }
    function showFormatter(value, row, index){
        var str="";
        str+="<a class='add' href='/index.php/admin/Store/stores?mid=" + row.id +"' title='查看下属商户门店'><i class='glyphicon glyphicon-search'>查看门店</i></a>";
        str+="&nbsp;<a class='add' href='javascript:void(0)' onclick=\"batchQrcode('"+row.merchantname+"',"+row.id+")\" title='代制二维码'><i class='glyphicon glyphicon-qrcode'>代制二维码</i></a>";

        if(row.parent_id>0) {
             str+="<a class='add' href='javascript:void(0)' title='查看下属商户' style='display: none;'><i class='glyphicon glyphicon-search'>下属商户</i></a>";
            return str;
        }else{
            str+="<a class='add' href='/index.php/admin/merchant/submerchants?id=" + row.id + "&pid=" + row.pid + "' title='查看下属商户'><i class='glyphicon glyphicon-search'>下属商户</i></a>";
            return str;
        }
    }
    function operateFormatter(value, row, index) {
        return [
            '<a class="like" href="javascript:void(0)" title="支付配置">',
            '<i class="glyphicon glyphicon-edit"></i>',
            '</a>  ',
            '<a class="edit" href="javascript:void(0)" title="修改信息">',
            '<i class="glyphicon glyphicon-user"></i>',
            '</a>  ',
            '<a class="add" href="javascript:void(0)" title="添加下属商户">',
            '<i class="glyphicon glyphicon-plus"></i>',
            '</a>  ',
            '<a class="remove" href="javascript:void(0)" title="删除">',
            '<i class="glyphicon glyphicon-remove"></i>',
            '</a>'
        ].join('');
    }
    window.operateEvents = {
        'click .edit': function (e, value, row, index) {
           // alert('You click edit action, row: ' + JSON.stringify(row));
            editForm('subEdit',row);
            //$("#tab").bootstrapTable('resetView');
        },
        'click .like': function (e, value, row, index) {
            //alert('You click like action, row: ' + JSON.stringify(row));
            //$("#tab").bootstrapTable('resetView');
            editWxPayConfig(row.id);
        },
        'click .add': function (e, value, row, index) {
             //alert('You click edit action, row: ' + JSON.stringify(row));
            fillAddForm('subAdd',row);
            //$("#tab").bootstrapTable('resetView');
        },
        'click .remove': function (e, value, row, index) {
            //alert('You click like action, row: ' + JSON.stringify(row));
            //$("#tab").bootstrapTable('refresh');
            delte("/index.php/admin/merchant/delete","mid="+row.id);
        }
    };
    $(function(){
        getTab();
    })

    //====================================
    function mySubmit(formId,url){
        var params = $("#"+formId+"Form").serialize();
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
                $("#tab").bootstrapTable('refresh');
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

    function editForm(formId,jsonData){
        if(jsonData!=null) {
            var $editForm = $("#" + formId+"Form");
            $editForm[0].reset();
            for (var obj in jsonData) {
                if ($editForm.find("input[name='" + obj + "']")) {
                    $editForm.find("input[name='" + obj + "']").val(jsonData[obj]);
                }
            }
        }
        $("#"+formId).modal('show');
    }
    function fillAddForm(formId,jsonData){

            var $editForm = $("#" + formId+"Form");
            $editForm[0].reset();
            $editForm.find("input[name='pid']").val(jsonData.pid);
            $editForm.find("input[name='parent_id']").val(jsonData.id);
        $("#"+formId).modal('show');
    }

    function delte(url,data){
        var params = data;
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
                $("#tab").bootstrapTable('refresh');
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

    function editWxPayConfig(mid){
        $.ajax({
            url:"/index.php/admin/merchant/editPayconfig",
            data:'mid='+mid,
            dataType:"json",
            type:"get",
            success: function (data) {
                //alert(JSON.stringify(data));

                editForm('wxpayConfig',data.data);
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
    function batchQrcode(merchantname,id){
        $("#batchQrcodeModalLabel").html(merchantname);
        $.ajax({
            url:"/index.php/admin/Pay/batchQrcode",
            data:"searchStore=1&mid="+id,
            type:"post",
            dataType:"json",
            success:function(data){
                if(data.result!="success"){
                    swal({
                        title: data.message,
                        text: data.data,
                        type: data.result
                    }, function () {
                        //window.location.reload();
                    });
                }else {
                    var objs=data=data.data;
                    //TODO 先清空再填充checkbox
                    $("#stores-container").html("");
                    for (var i = 0; i < objs.length; i++) {

                        $("#stores-container").html($("#stores-container").html()+"<div class='col-md-3'>" +
                                "<input type='checkbox' name='store_ids[]' value='"+objs[i].id+"'>" +
                                "<label>"+objs[i].name+"</label>"
                               );
                    }
                    $("#batchQrcode").modal("show");
                }
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
        });

    }
    function showTip(data,time){
        $("#tip").modal("show");

        $("#tipLabel").html(data.message);
        $("#tipContent").html(data.data);
        setTimeout(function(){
            $("#tip").modal("hide");
        },time);
    }
</script>
</body>
</html>