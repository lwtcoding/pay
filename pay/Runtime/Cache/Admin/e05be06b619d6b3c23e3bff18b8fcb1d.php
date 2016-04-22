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
            <h1 class="page-head-line">门店管理</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Merchant</a></li>
                    <li class="active">门店管理</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">

        <div class="col-md-12">
            <div id="toolbar">
                <label>商家名称</label>
                <input name="merchantname" type="text">
                <label>联系人</label>
                <input name="contact" type="text">
            </div>
            <table id="tab">
            </table>
            <!-- /. ROW  -->
        </div>
        <!-- /.REVIEWS &  SLIDESHOW  -->
    </div>
    <!-- /. ROW  -->

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
        var url = '/index.php/admin/merchant/submerchantStore';
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
                },  {
                    field: 'contact',
                    title: '联系人',
                    align: 'center',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'address',
                    title: '商户地址',
                    align: 'center',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'email',
                    title: '邮箱',
                    align: 'center',
                    valign: 'middle',
                    clickToSelect: false
                }, {
                    field: 'type',
                    title: '商户类型',
                    align: 'center',
                    valign: 'middle',
                    clickToSelect: false
                }, {
                    title: '操作',
                    align: 'center',
                    formatter: showFormatter
                }]
        });
    }
    //设置传入参数
    function queryParams(params) {
        params.pid=<?php echo ($pid); ?>;
        params.parent_id=<?php echo ($parent_id); ?>;
        params.merchantname=$("#toolbar").find("[name='merchantname']").val();
        params.contact=$("#toolbar").find("[name='contact']").val();
        return params
    }
    var is_proxy="<?php echo($_SESSION['loginMerchant']['is_proxy']);?>";

    function showFormatter(value, row, index){
        var str="";
        str+="<a class='add' href='/index.php/admin/Store/stores?mid=" + row.id +"' title='查看下属商户门店'><small class='glyphicon glyphicon-search'>查看门店</small></a>";
        str+="&nbsp;<a class='add' href='javascript:void(0)' onclick=\"batchQrcode('"+row.merchantname+"',"+row.id+")\" title='门店支付二维码'><small class='glyphicon glyphicon-qrcode'>下载二维码</small></a>";

        if(row.parent_id>0||is_proxy==0) {
             str+="<a  style='display: none;'></a>";
            return str;
        }else{
            str+="<a class='add' href='/index.php/admin/merchant/submerchants?id=" + row.id + "&pid=" + row.pid + "' title='查看下属商户'><small class='glyphicon glyphicon-search'>下属商户</small></a>";
            return str;
        }
    }

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
            if(formId=="subEdit"){

                addressInit(jsonData.proId,jsonData.cityId);
            }
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
               // alert(JSON.stringify(data));
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