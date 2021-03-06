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
            <h1 class="page-head-line">会员卡管理</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Card</a></li>
                    <li class="active">cards</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">
        <div class="col-md-12">

            <div id="toolbar">

                <div  class="col-md-12" style="margin-top: 10px;">
                <label>卡券状态</label>
                   <select id="status">
                       <option value="">全部</option>
                       <option value="0">审核中</option>
                       <option value="1">待投放</option>
                       <option value="2">审核不通过</option>
                       <option value="3">已删除</option>
                   </select>
                </div>
            </div>
            <table id="tab">
            </table>
            <!-- /. ROW  -->
        </div>
        <!-- /.REVIEWS &  SLIDESHOW  -->
    </div>
    <!-- /. ROW  -->
    <hr />
</div>

<!-- 门店编辑模态框（Modal） -->
<div class="modal fade" id="cardEdit" tabindex="-1" role="dialog"
     aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="editModalLabel">
                    修改折扣
                </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <a href="#" class="close" data-dismiss="alert">
                        &times;
                    </a>
                    <strong>警告！</strong>折扣范围1~100，建议不做活动时设为100。
                </div>
                <form class="form-horizontal" role="form" id="cardEditForm" action="" method="post" >
                    <input type="text" name="id">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">卡名</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="card_title" placeholder="卡名" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">折扣</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="discount" placeholder="" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="mySubmit('cardEdit','/index.php/admin/card/edit')">
                    保存
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->
<!-- 模态框（Modal） -->
<div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog"
     aria-labelledby="qrcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="qrcodeModalLabel">
                    生成二维码
                </h4>
            </div>
            <div class="modal-body text-center">
                <img id="qrcode" src="" width="400px" height="400px" class="img-thumbnail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <a  id="dwnQrcode" class="btn btn-primary" href="">
                    下载
                </a>
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
<script src="/Public/js/sweetalert/sweetalert.min.js"></script>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);
    }

    function getTab(){
        var url = '/index.php/admin/card/cards';
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
            sortOrder:'desc',
            toolbar: '#toolbar',
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
                    field: 'card_title',
                    title: '会员卡名称',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    sortable: true
                },  {
                    field: 'quantity',
                    title: '数量',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    sortable: true
                }, {
                    field: 'get_limit',
                    title: '限领取次数',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    sortable: true
                }, {
                    field: 'status',
                    title: '卡券状态',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    formatter:statusFormat,
                    sortable: true
                },{
                    field: 'discount',
                    title: '折扣（%）',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    sortable: true
                },{
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
        params.status=$("#status").val();
        return params
    }
    function operateFormatter(value, row, index) {
        return [
            '<a class="addStaff" href="javascript:void(0)" title="生成二维码">',
            '<i class="glyphicon glyphicon-qrcode"></i>',
            '</a>  ',
            '<a class="edit" href="javascript:void(0)" title="编辑">',
            '<i class="glyphicon glyphicon-edit"></i>',
            '</a>  '
           /* '<a class="remove" href="javascript:void(0)" title="删除">',
            '<i class="glyphicon glyphicon-remove"></i>',
            '</a>'*/
        ].join('');
    }
    window.operateEvents = {
        'click .addStaff': function (e, value, row, index) {
            // alert('You click edit action, row: ' + JSON.stringify(row));
            //$("#tab").bootstrapTable('resetView');
            showQrcode(row.id);
        },
        'click .edit': function (e, value, row, index) {
           //alert('You click edit action, row: ' + JSON.stringify(row));
            //$("#tab").bootstrapTable('resetView');
            editForm("cardEdit",row);
        },
        'click .remove': function (e, value, row, index) {
            //alert('You click like action, row: ' + JSON.stringify(row));
           // $("#tab").bootstrapTable('refresh');
            delte("/index.php/admin/card/delete","mid="+row.mid+"&id="+row.id);
        }
    };
    $(function(){
        getTab();
    })
//============================
    function mySubmit(formId,url){
        var params = $("#"+formId+"Form").serialize();
       // alert(params);
        $.ajax({
            url:url,
            data:params,
            dataType:"json",
            type:"post",
            success: function (data) {
                //alert(JSON.stringify(data));
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
                    title: "网络异常",
                    text: "",
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
                    title: "网络异常",
                    text: "",
                    type: "error"
                }, function () {
                    //window.location.reload();
                });
            }
        })
    }
    function statusFormat(value){

        if(value==0)
            return '<lable style="color: #a1d9f2;">审核中</lable>';
        if(value==1)
            return '<lable style="color: #00FF00;">待投放</lable>';
        if(value==2)
            return '<lable style="color: #9f191f;">审核不通过</lable>';
        if(value==3)
            return '<lable style="color: #9f191f;">(已删除)</lable>';
    }
    function showQrcode(cid){
        $("#qrcode").attr("src","/index.php/admin/card/qrcode?cid="+cid);
        $("#dwnQrcode").attr("href","/index.php/admin/card/qrcode?dwd=1&cid="+cid);
        $("#qrcodeModal").modal("show");
    }


</script>
</body>
</html>