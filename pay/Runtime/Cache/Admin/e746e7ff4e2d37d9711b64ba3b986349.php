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
    <!--CUSTOM BASIC STYLES-->
    <link href="/Public/css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css?var=<?php echo time() ?>" rel="stylesheet" />
    <link href="/Public/css/datepicker3.css" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<div id="page-inner" >

    <div class="row">

        <div class="col-md-12">
            <div id="preload" ></div>
            <h1 class="page-head-line">统计</h1>
            <div>
                <ol class="breadcrumb">
                    <li><a href="/index.php/Home/Index/left">Admin</a></li>
                    <li><a href="#">Order</a></li>
                    <li class="active">订单统计</li>
                </ol>
            </div>
            <hr/>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">

        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>按天统计</h3>
                    <label>日期</label>
                    <input type="text" id="begin_day" name="begin_date" placeholder="格式：2015-12-15">
                    <input type="text" id="end_day" name="end_date" placeholder="格式：2015-12-15">
                    <label>选择商户</label>
                    <select name="mid">
                        <?php if(!$_SESSION['loginMerchant']['pretend']){ echo " <option value=''>所有商户</option>"; } ?>
                        <?php if(is_array($submerchants)): foreach($submerchants as $key=>$submerchant): ?><option value="<?php echo ($submerchant["id"]); ?>"><?php echo ($submerchant["merchantname"]); ?></option><?php endforeach; endif; ?>
                    </select>
                    <button type="button" id="day" class="btn btn-info search-btn">查询</button>
                </div>
                <div class="panel-body" >
                    <canvas id="dayChart" width="1000" height="400"></canvas>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-1 col-md-offset-5">
                            <div style="height: 10px;width: 10px;border: 1px solid #232323;background: #d5d5d5;"></div><div>交易笔数</div>
                        </div>
                        <div class="col-md-1 ">
                            <div style="height: 10px;width: 10px;border: 1px solid #232323;background:#31b0d5;"></div><div>交易额</div>
                        </div>
                        <div class="col-md-1 ">
                            <div style="height: 10px;width: 10px;border: 1px solid #232323;background:rgba(255,192,203,1);"></div><div>退款金额</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /. ROW  -->
    <hr/>
    <!-- /. ROW  -->
    <div class="row">

        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>按月统计</h3>
                    <label>日期</label>
                    <input type="text" id="begin_month" name="begin_date" placeholder="格式：2015-12-15">
                    <input type="text" id="end_month" name="end_date" placeholder="格式：2015-12-15">
                    <label>选择商户</label>
                    <select name="mid">
                        <?php if(!$_SESSION['loginMerchant']['pretend']){ echo " <option value=''>所有商户</option>"; } ?>
                        <?php if(is_array($submerchants)): foreach($submerchants as $key=>$submerchant): ?><option value="<?php echo ($submerchant["id"]); ?>"><?php echo ($submerchant["merchantname"]); ?></option><?php endforeach; endif; ?>
                    </select>
                    <button type="button" id="month" class="btn btn-info search-btn">查询</button>
                </div>
                <div class="panel-body" >
                    <canvas id="monthChart" width="1000" height="400"></canvas>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-1 col-md-offset-5">
                            <div style="height: 10px;width: 10px;border: 1px solid #232323;background: #d5d5d5;"></div><div>交易笔数</div>
                        </div>
                        <div class="col-md-1 ">
                            <div style="height: 10px;width: 10px;border: 1px solid #232323;background:#31b0d5;"></div><div>交易额</div>
                        </div>
                        <div class="col-md-1 ">
                            <div style="height: 10px;width: 10px;border: 1px solid #232323;background:rgba(255,192,203,1);"></div><div>退款金额</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- /. ROW  -->

</div>
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="/Public/js/bootstrap.js"></script>
<!-- CHART.JS -->
<script src="/Public/js/chart.mini.js"></script>
<script  src="/Public/js/bootstrap-datepicker.js"></script>
<script language="javascript">
    var nowDate;
    onload=function(){
        nowDate = new Date();
        $("#begin_day").val(nowDate.getFullYear()+"-"+(nowDate.getMonth()+1)+"-"+(nowDate.getDate()-1));
        $("#end_day").val(nowDate.getFullYear()+"-"+(nowDate.getMonth()+1)+"-"+nowDate.getDate());
        $("#begin_month").val(nowDate.getFullYear()+"-"+(nowDate.getMonth()));
        $("#end_month").val(nowDate.getFullYear()+"-"+(nowDate.getMonth()+1));

        statistics_by_date(nowDate.getFullYear()+"-"+(nowDate.getMonth()+1)+"-"+(nowDate.getDate()-1),nowDate.getFullYear()+"-"+(nowDate.getMonth()+1)+"-"+nowDate.getDate(),$("[name='mid']").val(),'day');
        statistics_by_date(nowDate.getFullYear()+"-"+nowDate.getMonth(),nowDate.getFullYear()+"-"+(nowDate.getMonth()+1),$("[name='mid']").val(),'month');

        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);

        $(".search-btn").click(function(){

            statistics_by_date( $(this).parent().find("[name='begin_date']").val(),$(this).parent().find("[name='end_date']").val(),$(this).parent().find("[name='mid']").val(),$(this).attr('id'));
        });

        $('#begin_day').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $('#end_day').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $('#begin_month').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            format: "yyyy-mm",
            autoclose: true
        });
        $('#end_month').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            format: "yyyy-mm",
            autoclose: true
        });

    }
    //Get context with jQuery - using jQuery's .get() method.
    var dctx = $("#dayChart").get(0).getContext("2d");
    var mctx = $("#monthChart").get(0).getContext("2d");

    //This will get the first returned node in the jQuery collection.
    var dayChart = new Chart(dctx);
    var monthChart = new Chart(mctx);

    var data = {
        labels : [],
        datasets : [
            {
                fillColor : "rgba(220,220,220,0.5)",
                strokeColor : "rgba(220,220,220,1)",
                pointColor : "rgba(220,220,220,1)",
                pointStrokeColor : "#fff",
                data : []
            },
            {
                fillColor : "rgba(151,187,205,0.5)",
                strokeColor : "rgba(151,187,205,1)",
                pointColor : "rgba(151,187,205,1)",
                pointStrokeColor : "#fff",
                data : []
            },
            {
                fillColor : "rgba(255,192,203,0.5)",
                strokeColor : "rgba(255,192,203,1)",
                pointColor : "rgba(255,192,203,1)",
                pointStrokeColor : "#fff",
                data : []
            }
        ]
    }

    //type day，month，year
    function statistics_by_date(begin_date,end_date,mid,type){

        $.post("/index.php/admin/Order/proxystatistics",{'begin_date':begin_date,'end_date':end_date,'mid':mid,'type':type},function(result){
            data.labels=result.labels;
            data.datasets[0].data=result.total;
            data.datasets[1].data=result.total_fee;
            data.datasets[2].data=result.total_refund;
            if(type=='day')
                dayChart.Line(data);
            if (type=='month')
                monthChart.Line(data);

        });
    }




</script>
</body>
</html>