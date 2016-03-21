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

        <div class="col-md-3 ">
            <a href="#">
                <div class="alert alert-success text-center">
                    <i class="fa fa-bars fa-5x"></i>
                    <h3>300+ Tasks</h3>
                    Pending For New Events
                </div>
            </a>
        </div>

    </div>
    <!-- /. ROW  -->
    <hr/>

    <div class="row">
        <div class="col-md-12">

            <div id="toolbar">
                <button data-toggle="modal" data-target="#addModal" class="btn btn-success">
                    <i class="glyphicon glyphicon-plus"></i> 添加门店
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
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    添加门店
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">名字</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" placeholder="请输入名字">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary">
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
<script language="javascript" src="/Public/js/bootstrap-table.js"></script>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);
    }

    function getTab(){
        var url = '/index.php/admin/store/stores';
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
                    title: '资金通道编码',
                    align: 'center',
                    width: '180',
                    valign: 'bottom',
                    sortable: true
                }, {
                    field: 'name',
                    title: '资金退回批次号',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    sortable: true
                }, {
                    field: 'total',
                    title: '总笔数',
                    align: 'center',
                    width: '10',
                    valign: 'top',
                    sortable: true
                }, {
                    field: 'totalMoney',
                    title: '总金额',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    clickToSelect: false
                }, {
                    field: 'operate',
                    title: 'Item Operate',
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
            '<i class="glyphicon glyphicon-heart"></i>',
            '</a>  ',
            '<a class="remove" href="javascript:void(0)" title="Remove">',
            '<i class="glyphicon glyphicon-remove"></i>',
            '</a>'
        ].join('');
    }
    window.operateEvents = {
        'click .like': function (e, value, row, index) {
            alert('You click like action, row: ' + JSON.stringify(row));
            $("#tab").bootstrapTable('resetView');
        },
        'click .remove': function (e, value, row, index) {
            //alert('You click like action, row: ' + JSON.stringify(row));
            $("#tab").bootstrapTable('refresh');
        }
    };
    $(function(){
        getTab();
    })


</script>
</body>
</html>