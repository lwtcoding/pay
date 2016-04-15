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
                    <li><a href="#">Store</a></li>
                    <li class="active">store</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">
        <div class="col-md-12">

            <div id="toolbar">
                <div class="col-md-12">
                    <button onclick="addStaff()" class="btn btn-success">
                        <i class="glyphicon glyphicon-plus"></i> 添加门店
                    </button>
                </div>

                <div  class="col-md-12" style="margin-top: 10px;">

                    <label>选择商户</label>
                    <select id="mid">

                        <?php if(is_array($merchants)): foreach($merchants as $key=>$merchant): if(($merchant["id"] == $mid)): ?><option value="<?php echo ($merchant["id"]); ?>" selected><?php echo ($merchant["merchantname"]); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($merchant["id"]); ?>"><?php echo ($merchant["merchantname"]); ?></option><?php endif; endforeach; endif; ?>
                    </select>
                    <label>门店名称</label><input id="store_name" placeholder="输入要搜索的门店" >
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
<!-- 模态框（Modal） -->
<div class="modal fade" id="addStore" tabindex="-1" role="dialog"
     aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="addModalLabel">
                    添加门店
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="addStoreForm">
                    <input type="text" name="mid" value="<?php echo ($mid); ?>">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">门店名</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" placeholder="请输入名字" name="name">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="mySubmit('addStore','/index.php/admin/store/add')">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->
<!-- 门店编辑模态框（Modal） -->
<div class="modal fade" id="storeEdit" tabindex="-1" role="dialog"
     aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="editModalLabel">
                    编辑门店
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="storeEditForm" action="" method="post" >
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">门店名</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="name" placeholder="门店名" value="" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="mySubmit('storeEdit','/index.php/admin/store/edit')">
                    保存
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->
<!-- 添加员工模态框（Modal） -->
<div class="modal fade" id="addStaff" tabindex="-1" role="dialog"
     aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="addStaffModalLabel">
                    添加员工
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="addStaffForm" action="" method="post" >
                    <input type="hidden" name="mid">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">所属商家</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="merchantname" placeholder="所属商家" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">所属门店</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="name" placeholder="所属门店" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">账号</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="username" placeholder="账号" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">员工名</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="nickname" placeholder="账号" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">密码</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password"  placeholder="密码" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">确认密码</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control"  name="repassword"  placeholder="确认密码" value="">
                        </div>
                    </div>
                   <hr/>
                    <div class="row">
                        <?php if(is_array($auths)): $i = 0; $__LIST__ = $auths;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$auth): $mod = ($i % 2 );++$i;?><div class="col-md-3">
                                <input type="checkbox" name="auths[]" value="<?php echo ($auth['value']); ?>">
                                <label> <?php echo ($auth['name']); ?></label>

                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="mySubmit('addStaff','/index.php/admin/Staff/add')">
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
<script src="/Public/js/sweetalert/sweetalert.min.js"></script>
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
                },{
                    field: 'merchantname',
                    title: '商户名',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'name',
                    title: '门店名',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    sortable: true
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
        params.name=$("#store_name").val();
        params.mid=$("#mid").val();
        return params
    }
    function showFormatter(value, row, index){
        var str="";
        str+="<a class='add' href='/index.php/admin/Staff/staffs?mid=" + row.mid +"&id="+row.id+"' title='查看门店员工'><small class='glyphicon glyphicon-search'>查看员工</small></a>";
        return str;
    }
    function operateFormatter(value, row, index) {
        return [
            '<a class="addStaff" href="javascript:void(0)" title="添加雇员">',
            '<i class="glyphicon glyphicon-user"></i>',
            '</a>  ',
            '<a class="edit" href="javascript:void(0)" title="编辑">',
            '<i class="glyphicon glyphicon-edit"></i>',
            '</a>  ',
            '<a class="remove" href="javascript:void(0)" title="删除">',
            '<i class="glyphicon glyphicon-remove"></i>',
            '</a>'
        ].join('');
    }
    window.operateEvents = {
        'click .addStaff': function (e, value, row, index) {
            // alert('You click edit action, row: ' + JSON.stringify(row));
            //$("#tab").bootstrapTable('resetView');
            editForm("addStaff",row);
        },
        'click .edit': function (e, value, row, index) {
           // alert('You click edit action, row: ' + JSON.stringify(row));
            //$("#tab").bootstrapTable('resetView');
            editForm("storeEdit",row);
        },
        'click .remove': function (e, value, row, index) {
            //alert('You click like action, row: ' + JSON.stringify(row));
           // $("#tab").bootstrapTable('refresh');
            delte("/index.php/admin/store/delete","mid="+row.mid+"&id="+row.id);
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

    function addStaff(){
        var mid = $("#mid").val();
        $("#addStoreForm").find("[name='mid']").val(mid);
        $("#addStore").modal("show");
    }

</script>
</body>
</html>