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
            <h1 class="page-head-line">员工管理</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Staff</a></li>
                    <li class="active">员工管理</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->

    <div class="row">
        <div class="col-md-12">

            <div id="toolbar">


                <label>员工名</label>
                <input name="nickname" type="text">
                <label>选择门店</label>
                <select name="store_id">
                    <?php if(!$_SESSION['loginMerchant']['pretend']){ echo " <option value=''>所有门店</option>"; } ?>
                    <?php if(is_array($stores)): foreach($stores as $key=>$store): if($store_id == $store['id']): ?><option value="<?php echo ($store["id"]); ?>" selected><?php echo ($store["name"]); ?></option>
                            <?php else: ?>
                            <option value="<?php echo ($store["id"]); ?>"><?php echo ($store["name"]); ?></option><?php endif; endforeach; endif; ?>
                </select>

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
<!-- 编辑员工模态框（Modal） -->
<div class="modal fade" id="editStaff" tabindex="-1" role="dialog"
     aria-labelledby="editStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="editStaffModalLabel">
                    编辑员工
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="editStaffForm" action="" method="post" >
                    <input type="hidden" name="id">
                    <input type="hidden" name="salt">
                    <input type="hidden" name="mid">
                    <input type="hidden" name="store_id">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">账号</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="username" placeholder="账号" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">员工名</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  name="nickname" placeholder="员工名" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">密码</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password"  placeholder="密码" value="">
                        </div>
                    </div>
                   <!-- <div class="form-group">
                        <label  class="col-sm-2 control-label">微信统计</label>
                        <div class="col-sm-8">
                            <select name="type">
                                <option value="0">统计所在门店</option>
                                <option value="1">统计全部门店</option>
                            </select>
                        </div>
                    </div>-->
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
                <button type="button" class="btn btn-primary" onclick="mySubmit('editStaff','/index.php/admin/Staff/edit')">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->

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
    var flag=true;
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);

    }

    function getTab(){
        var url = '/index.php/admin/Staff/staffs';
        $('#tab').bootstrapTable({
            method: 'POST', //这里要设置为get，不知道为什么 设置post获取不了
            url: url,
            cache: false,
            height: 400,
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
                    field: 'nickname',
                    title: '员工名',
                    align: 'center',
                    width: '200',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'username',
                    title: '帐号',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    sortable: true
                },  {
                    field: 'merchantname',
                    title: '所属商家',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    formatter:storeFormat,
                    sortable: true
                },{
                    field: 'store_id',
                    title: '所属门店',
                    align: 'center',
                    width: '100',
                    valign: 'middle',
                    formatter:storeFormat,
                    sortable: true
                },{
                    field: 'openid',
                    title: '绑定情况',
                    align: 'center',
                    width: '50',
                    valign: 'middle',
                    formatter:boundFormat,
                    sortable: true
                } , {
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

        params.store_id=$("[name='store_id']").val();
        params.nickname=$("[name='nickname']").val();
        params.mid=<?php echo ($mid); ?>;
       // alert(JSON.stringify(params));
        return params
    }
    function operateFormatter(value, row, index) {
        return [
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
            editForm("editStaff",row);
        },
        'click .remove': function (e, value, row, index) {
            //alert('You click like action, row: ' + JSON.stringify(row));
           // $("#tab").bootstrapTable('refresh');
            delte("/index.php/admin/staff/delete","mid="+row.mid+"&id="+row.id);
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
                    if(obj=="type"){
                        $editForm.find("[name='" + obj + "']").val(jsonData[obj]);
                    }
                    if(obj=='auths'){
                        $editForm.find("input[name='auths[]']").prop("checked",false);
                       // $editForm.find("input[name='auths']").attr("checked",false);
                        if(jsonData[obj]!=""&&jsonData[obj]!=null){
                            for(var i=0;i<jsonData[obj].length;i++){
                              //  alert(jsonData[obj][i]);
                                $editForm.find("input[value='" + jsonData[obj][i] + "']").prop("checked",true);
                            }
                        }
                    }
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
    function getQrcode(){
        $("#qrcode").attr("src","/index.php/admin/Staff/staffs"+"?qrcode=1&mid="+$("#merchant").val()+"&store_id="+$("#store").val());
        $("#dwnQrcode").attr("src","/index.php/admin/Staff/staffs"+"?download=1&mid="+$("#merchant").val()+"&store_id="+$("#store").val());
    }

    function storeFormat(value){
        $("select[name='store_id']").find("option").each(function () {
            if(value==$(this).val()) {
             value=$(this).html();
            }
        });
        return value;
    }
    function boundFormat(value){
        if(value==null){
            return "<small style='color: #A51715;'>未绑定</small>";
        }else{
            return "<small style='color: #00FF00;'>已绑定</small>";
        }
    }
    function typeFormat(value){

        if(value==0){
            return "<small style='color: #285e8e;'>统计所在门店</small>";
        }
        if(value==1){
            return "<small style='color: #00FF00;'>统计全部门店</small>";
        }
    }

</script>
</body>
</html>