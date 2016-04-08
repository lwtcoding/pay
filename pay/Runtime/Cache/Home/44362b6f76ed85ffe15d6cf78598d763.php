<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>订单统计</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <style>
        body{
            background: #d5d5d5;
        }
         tr{
            padding: 0px;
             margin: 0px;
        }
        p{
            padding: 0px;
            margin: 0px;;
        }
        .mytable{
            width: 100%;
        }

        .header{
            padding: 5px;
            background: #f5f5f5;
            border-radius: 10px;
        }
        tr div{
            margin-top:5px;
            padding: 5px;
            background: #f5f5f5;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div style="overflow-x: hidden;padding: 10px 5px;">

    <div class="row">

    </div>
    <!-- /. ROW  -->
    <div class="row">
        <div class="col-md-12 ">
            <div class="header">
                <div>
                    <label>当日收款总数：</label><label style="color: #00FF00"><?php echo ($count['fees']/100); ?></label>元
                </div>
                <div>
                    <label>当日订单数量：</label><label style="color: #00FF00"><?php echo ($count['times']); ?></label>笔
                </div>
            </div>
        </div>
        <div class="col-md-12 ">
            <table class="mytable">
                <tr><td><div style='text-align: center;'><h3>加载中...</h3></div></td></tr>
            </table>
        </div>

    </div>
    <!-- /. ROW  -->

    <hr/>



</div>
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<script language="javascript">
    var pageSize = 10;
    var offset = 0;
    var currentPage=1;
    var flag = true;
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);


        $.ajax({
            url:"/index.php/home/staff/storeStatistics",
            dataType:"json",
            data:"offset="+offset+"&limit="+pageSize+"&sort=id&order=desc",
            type:"post",
            success:function(data){
                console.info(JSON.stringify(data));

                $(".mytable").html("");
                if(data.length<=0){
                    $(".table").append("<tr><td><div style='text-align: center;'><h3>暂无支付订单</h3></div></td></tr>");
                }else{
                    for(var i=0;i<data.length;i++){
                        var fee = data[i].total_fee*0.01;
                            $(".mytable").append("<tr id='"+data[i].id+"' class='orderDetail'><td><div><p><label>订单号：</label>"+data[i].order_no+"</p><p><label>付款时间：</label>"+data[i].time_end+"</p><p><label>订单金额：</label><label style='color: #00FF00'>"+fee+"元</label></p></div></td></tr>");
                    }
                    offset = pageSize*currentPage;
                    currentPage+=1;
                }
            }
        });
        $(window).scroll(
                function() {
                    //$(window).scrollTop()这个方法是当前滚动条滚动的距离
                    //$(window).height()获取当前窗体的高度
                    //$(document).height()获取当前文档的高度
                    var bot = 50; //bot是底部距离的高度
                    if ((bot + $(window).scrollTop()) >= ($(document)
                                    .height() - $(window).height())) {
                        //当底部基本距离+滚动的高度〉=文档的高度-窗体的高度时；
                        //我们需要去异步加载数据了

                        if(flag){
                            flag=false;
                            $.ajax({
                                url:"/index.php/home/staff/storeStatistics",
                                dataType:"json",
                                type:"post",
                                data:"offset="+offset+"&limit="+pageSize+"&sort=id&order=desc",
                                success:function(data){
                                    flag=true;
                                    if(data.length<=0){
                                        if(flag){
                                            $(".mytable").append("<tr><td><div style='text-align: center;'><h3>没有更多订单了</h3></div></td></tr>");
                                            flag=false;
                                        }
                                    }else{
                                        for(var i=0;i<data.length;i++){
                                            var fee = data[i].total_fee*0.01;
                                            $(".mytable").append("<tr id='"+data[i].id+"' class='orderDetail'><td><div><p><label>订单号：</label>"+data[i].order_no+"</p><p><label>付款时间：</label>"+data[i].time_end+"</p><p><label>订单金额：</label><label style='color: #00FF00'>"+fee+"元</label></p></div></td></tr>");
                                        }
                                        offset = pageSize*currentPage;
                                        currentPage+=1;
                                    }

                                }
                            });
                        }
                    }
                });
    }

</script>
</body>
</html>