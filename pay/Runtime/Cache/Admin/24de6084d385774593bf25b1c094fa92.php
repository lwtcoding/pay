<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>代理订单</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!--TABLE STYLES-->
    <link href="/Public/css/bootstrap-table.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="/Public/css/basic.css?var=<?php echo time() ?>" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css?var=<?php echo time() ?>" rel="stylesheet" />
    <link href="/Public/css/datepicker3.css" rel="stylesheet" />
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
            <h1 class="page-head-line">订单管理</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Order</a></li>
                    <li class="active">proxyorders</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->

    <div class="row">
        <div class="col-md-12">

            <div id="toolbar">
                <label>时间</label>
                <input id="begin_time" name="begin_time" type="text" readonly="readonly">-<input id="end_time" name="end_time" type="text" readonly="readonly">
                <label>订单号</label>
                <input name="order_no" type="text" size="30">
                <label>选择商户</label>
                <select name="mid">
                    <?php if(!$_SESSION['loginMerchant']['pretend']){ echo " <option value=''>所有商户</option>"; } ?>
                    <?php if(is_array($submerchants)): foreach($submerchants as $key=>$submerchant): ?><option value="<?php echo ($submerchant["id"]); ?>"><?php echo ($submerchant["merchantname"]); ?></option><?php endforeach; endif; ?>
                </select>
                <label>门店</label>
                <input name="storename" type="text">
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
<!-- 退款模态框（Modal） -->
<div class="modal fade" id="refund" tabindex="-1" role="dialog"
     aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="refundModalLabel">
                    退款
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="refundForm" action="" method="post" >
                    <input type="hidden" name="id">
                    <input type="hidden" name="mid">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">订单号</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="order_no" placeholder="订单号" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">订单金额</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="total_fee" placeholder="订单金额"  readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">已退款</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="refund_fee"  placeholder="已退款" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">退款金额</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="this_refund_fee"  placeholder="退款金额" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="mySubmit('refund','/index.php/admin/Order/refund')">
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
<script  src="/Public/js/bootstrap-datepicker.js"></script>
<script src="/Public/js/sweetalert/sweetalert.min.js"></script>
<script language="javascript">
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);

        $('#begin_time').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $('#end_time').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            format: "yyyy-mm-dd",
            autoclose: true
        });
    }

    function getTab(){
        var url = '/index.php/admin/order/proxyorders';
        $('#tab').bootstrapTable({
            method: 'POST', //这里要设置为get，不知道为什么 设置post获取不了
            url: url,
            cache: false,
            height: 800,
            striped: true,
            pagination: true,
            pageList: [20],
            toolbar: '#toolbar',
            sortOrder:'desc',
            contentType: "application/x-www-form-urlencoded",
            pageSize:20,
            pageNumber:1,
//            search: true,
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
                    field: 'order_no',
                    title: '订单号',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'merchantname',
                    title: '商户',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'storename',
                    title: '交易门店',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'total_fee',
                    title: '消费金额',
                    align: 'center',
                    width: '80',
                    valign: 'middle',
                    formatter:priceformat,
                    sortable: true
                } ,{
                    field: 'lijian_fee',
                    title: '立减金额',
                    align: 'center',
                    width: '40',
                    valign: 'middle',
                    formatter:priceformat,
                    sortable: true
                } , {
                    field: 'refund_fee',
                    title: '退款金额',
                    align: 'center',
                    width: '40',
                    valign: 'middle',
                    formatter:priceformat,
                    sortable: true
                } ,{
                    field: 'vip_discount',
                    title: '折扣（%）',
                    align: 'center',
                    width: '20',
                    valign: 'middle',
                    sortable: true
                }, {
                    field: 'time_end',
                    title: '日期',
                    align: 'center',
                    width: '180',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'pay_way',
                    title: '渠道',
                    align: 'center',
                    width: '50',
                    valign: 'middle',
                    formatter:paywayformat,
                    sortable: true
                }, {
                    field: 'pay_type',
                    title: '类型',
                    align: 'center',
                    width: '80',
                    valign: 'middle',
                    formatter:paytypeformat,
                    sortable: true
                },  {
                    field: 'is_pay',
                    title: '订单状态',
                    width: '130',
                    align: 'center',
                    formatter: payformat
                }, {
                    field: 'operate',
                    title: '退款',
                    align: 'center',
                    events: operateEvents,
                    formatter: operateFormatter
                }]
        });
    }
    //设置传入参数
    function queryParams(params) {
        params.mid=$("[name='mid']").val();
        params.store_id=$("[name='store_id']").val();
        params.order_no=$("[name='order_no']").val();
        params.begin_time=$("[name='begin_time']").val();
        params.end_time=$("[name='end_time']").val();
        params.storename=$("[name='storename']").val();
       // alert(JSON.stringify(params));
        return params
    }
    function operateFormatter(value, row, index) {
        return [
            '<a class="refund" href="javascript:void(0)" title="退款">',
            '<i class="glyphicon glyphicon-retweet"></i>',
            '</a>  '
        ].join('');
    }
    window.operateEvents = {
        'click .refund': function (e, value, row, index) {
            //alert('You click edit action, row: ' + JSON.stringify(row));
            //$("#tab").bootstrapTable('resetView');
            editForm("refund",row);
        }
    };
    $(function(){
        getTab();
    })
//============================
    function mySubmit(formId,url){
        var params = $("#"+formId+"Form").serialize();

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
                    if(obj=='total_fee'||obj=='refund_fee'){
                        $editForm.find("input[name='" + obj + "']").val(jsonData[obj]/100);
                    }
                }
            }
        }
        $("#"+formId).modal('show');
    }

    function paywayformat(value){
        var type = value;
        if(type=='weixin')
            type='微信'
        return type;
    }
    function paytypeformat(value){
        var type = value;
        if(type=='JSAPI')
                type='<small>二维码支付</small>'
        return type;
    }
    function priceformat(value){
        return value/100;
    }
    function payformat(value){

        return "<small style='padding: 2px 10px;background: #A5DC86;color: #1ab7ea;border-radius: 10px;word-break: keep-all;'>支付成功</small>";
    }

</script>
</body>
</html>