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

        <div class="col-md-12">
            <div id="toolbar">
                <button id="add" class="btn btn-success" data-toggle="modal" data-target="#subAdd" >
                    <i class="glyphicon glyphicon-plus"></i> 添加代理商
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
                        添加代理商
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" id="subAddForm" action="" method="post" >
                        <input type="hidden" name="pid" value="<?php echo $_SESSION['loginMerchant']['id'] ?>">
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
                        添加代理商
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" id="subEditForm" action="" method="post" >
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">账号</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="username" placeholder="账号" value="<?php echo ($config['weixin']['mchid']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">旧密码</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name=""  placeholder="密码" value="<?php echo ($config['weixin']['appid']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">新密码</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name=""  placeholder="确认密码" value="<?php echo ($config['weixin']['appid']); ?>">
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
                        <input type="text" name="mid" >
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

    function getTab(){
        var url = '/index.php/admin/merchant/submerchants';
        $('#tab').bootstrapTable({
            method: 'post', //这里要设置为get，不知道为什么 设置post获取不了
            url: url,
            cache: false,
            height: 400,
            striped: true,
            pagination: true,
            pageList: [10,20],
// contentType: "application/x-www-form-urlencoded",
            pageSize:10,
            pageNumber:1,
            search: true,
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
        return params
    }
    function operateFormatter(value, row, index) {
        return [
            '<a class="like" href="javascript:void(0)" title="Like">',
            '<i class="glyphicon glyphicon-edit"></i>',
            '</a>  ',
            '<a class="edit" href="javascript:void(0)" title="Like">',
            '<i class="glyphicon glyphicon-heart"></i>',
            '</a>  ',
            '<a class="remove" href="javascript:void(0)" title="Remove">',
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
        'click .remove': function (e, value, row, index) {
            //alert('You click like action, row: ' + JSON.stringify(row));
            $("#tab").bootstrapTable('refresh');
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
                alert(JSON.stringify(data));
                $("#tab").bootstrapTable('refresh');
                $('#'+formId).modal('hide');
            },
            error:function(){
                alert("error");
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

    function delte(url,data){
        var params = data;
        $.ajax({
            url:url,
            data:params,
            dataType:"json",
            type:"post",
            success: function (data) {
               // alert(JSON.stringify(data));
                $("#tab").bootstrapTable('refresh');
                $('#submerchant').modal('hide');
            },
            error:function(){
                alert("error");
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
                alert("error");
            }
        })
    }
</script>
</body>
</html>