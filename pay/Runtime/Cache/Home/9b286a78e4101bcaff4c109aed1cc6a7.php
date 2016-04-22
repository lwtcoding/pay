<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>通莞营销</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="/Public/css/basic.css" rel="stylesheet" />
    <link rel="stylesheet" href="/Public/datepicker/themes/main.css">
    <link rel="stylesheet" href="/Public/datepicker/themes/default.css" id="theme_base">
    <link rel="stylesheet" href="/Public/datepicker/themes/default.date.css" id="theme_date">
    <link rel="stylesheet" href="/Public/datepicker/themes/default.time.css" id="theme_time">
    <!--CUSTOM MAIN STYLES-->
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
    <style>
        body{
            overflow-x: hidden;
        }
        .navbar-fixed-bottom ul{
            list-style: none;
            padding: 3px 0px 3px 0px;
        }
        .navbar-fixed-bottom li{
            text-align: center;
            border-right: 1px solid #8d8d8d;

        }
        .navbar-fixed-bottom li:nth-last-child(1){
            border-right: 0px;
        }
        .navbar-fixed-bottom ul li a{
            font-size: 12px;
        }
        .navbar-fixed-bottom ul li a i{
            display: block;
            font-size: 20px;
        }
        .store-contain{
            position: absolute;right: 0px;height: 70%;top: 15%;width: 100%;left:100%;overflow-y: scroll;
            border-radius: 5px;
            background: #fff;
            overflow-x: hidden;
        }
        .store-contain ul{
            list-style: none;
            padding: 5px;
        }
        .store-contain li{
            padding: 15px;
            margin-top: 5px;
        }
        .store-contain li:nth-child(even){
           background: #d5d5d5;
        }
        .store-contain li:nth-child(odd){
            background: #f5f5f5;
        }
        .clicked{
            color: #5bc0de;
            font-size: 25px;;
        }
    </style>
</head>
<body>
<div id="page-inner" style="overflow-x: hidden;">

    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#"><span><?php echo $_SESSION['loginMerchant']['merchantname'] ?></span></a>

                    </div>

                </div><!-- /.container-fluid -->
            </nav>
        </div>
        <div class="row wrapper page-heading iconList" style="margin-top: 50px;">

                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <div class="alert alert-info">
                        <span style="font-size: 35px;" class="glyphicon glyphicon-search">交易汇总</span>
                        <p><lable>当前门店:</lable><span  id="storename">全部门店</span></p>
                        <p><lable>查询日期:</lable><span id="date-contian"></span></p>
                        <form id="queryForm">
                            <input type="hidden" style="display: none;"  class="datepicker" name="date">
                            <input type="hidden" name="store_id">
                        </form>
                    </div>
                </div>
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <div class="alert alert-success">
                    <div style="font-size: 22px;" >交易笔数：<span class="pull-right">笔</span><span class="pull-right" style="font-size: 20px;" id="count"></span></div>
                    <br/>
                    <div style="font-size: 22px;" >交易金额：<span class="pull-right">元</span><span class="pull-right" style="font-size: 20px;" id="fee" ></span></div>
                </div>

            </div>
        </div>


    </div>
</div>
<div  class="store-contain">
    <p class="text-center"><label>请点击需要选择的门店</label></p>
    <ul>
        <li myid="">全部门店</li>
        <?php for($i=0;$i<count($stores);$i++){ ?>
        <li myid="<?php echo $stores[$i]['id']; ?>"><?php echo $stores[$i]['name']; ?></li>
        <?php } ?>




    </ul>
</div>
<div class="navbar navbar-inverse navbar-fixed-bottom" >
    <ul>
        <li class="col-xs-4"  id="chooseTime">
            <a href="javascript:void(0)"> <i class="glyphicon glyphicon-time"></i>选择日期</a>
        </li>
        <li class="col-xs-4">
            <a href="javascript:void(0)" onclick="showStore()"><i class="glyphicon glyphicon-home"></i>选择门店</a>
        </li>
        <li class="col-xs-4">
            <a href="/index.php/home/merchant/unboundwx"><i class="fa fa-undo"></i>解除绑定</a>
        </li>
    </ul>
</div>

</body>
<script src="/Public/js/jquery-1.10.2.js"></script>
<script src="/Public/datepicker/picker.js"></script>
<script src="/Public/datepicker/picker.date.js"></script>
<script src="/Public/datepicker/translations/zh_CN.js"></script>
<script>
    var nowtime=new Date();
    var month = (nowtime.getMonth()+1)>=10?(nowtime.getMonth()+1):("0"+(nowtime.getMonth()+1));
    var day = nowtime.getDate()>=10?nowtime.getDate():("0"+nowtime.getDate());
    $(function(){
        $("#date-contian").html(nowtime.getFullYear()+"-"+month+"-"+day);
        $(".datepicker").val(nowtime.getFullYear()+"-"+month+"-"+day);
        $(".store-contain").on("mousedown","li", function () {
            $(this).addClass("clicked");
        });
        $(".store-contain").on("mouseout","li", function () {
            $(this).removeClass("clicked");
        });
        $(".store-contain").on("click","li", function () {
            $("#queryForm").find("[name='store_id']").val($(this).attr("myid"));
            $("#storename").html($(this).html());
           submitQueryForm();
            $(".store-contain").animate({left:"100%"},1500);
        });


        var $date=$('.datepicker').pickadate({format: 'yyyy-mm-dd'});
        var picker = $date.pickadate('picker');
        $("#chooseTime").click(function(event){
            picker.open();

            event.stopPropagation();

        });
        $(".datepicker").change(function(){
            $("#date-contian").html($(this).val());
            submitQueryForm();
        });
        submitQueryForm();
    })
    function showStore(){
       if($(".store-contain").offset().left>0) {
           $(".store-contain").animate({left: "0%"}, 1500);
       }else{
           $(".store-contain").animate({left:"100%"},1500);
       }
    }
    function submitQueryForm(){
        var $fee = $("#fee");
        var $count=$("#count");
        var str="计算中..."
        $count.html(str);
        $fee.html(str);
        $.ajax({
            url:"/index.php/home/merchant/statistics",
            method:"post",
            data:$("#queryForm").serialize(),
            dataType:"json",
            success:function(data){

                $count.html(data.c);
                $fee.html(data.fee/100);
            }
        })

    }
</script>
</html>