<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo ($pageinfo['companyname']); ?></title>

    <!-- BOOTSTRAP STYLES-->
    <link href="/Public/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/Public/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="/Public/css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="/Public/css/custom.css?var=<?php echo time() ?>" rel="stylesheet" />
    <!-- GOOGLE FONTS
    <link href='http://fonts.useso.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    -->
</head>
<body>
<div id="page-inner" style="overflow-x: hidden;">

    <div class="row">

        <div class="col-md-12">
            <div id="preload" ></div>

        </div>
    </div>
    <!-- /. ROW  -->
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">


                        <a class="navbar-brand" href="#"><span><?php echo ($pageinfo['companyname']); ?></span></a>
                    </div>

                </div><!-- /.container-fluid -->
            </nav>
        </div>
        <div class="col-md-12" style="margin-top: 50px;">
            <img src="/Uploads/<?php echo ($pageinfo['logo']); ?>" class="img-circle center-block" alt="<?php echo ($pageinfo['companyname']); ?>" width="140px" height="140px">
        </div>
        <div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
            <form class="form-horizontal" role="form" id="wxpayForm"  method="post" >
                <input type="hidden" name="mid" value="<?php echo ($mid); ?>" >
                <input type="hidden" name="store_id" value="<?php echo ($store_id); ?>" >
                <input type="hidden" name="token" value="<?php echo ($token); ?>" >
                <div class="form-group"  style="margin-top: 25px; display: none;" >
                    <label  class="col-sm-2 control-label" ></label>
                    <div class="col-sm-8">
                        <label   style="color: #63b359">优惠后金额：</label>
                       <label style="color: #63b359" id="discount"></label>元
                    </div>
                </div>
                <div class="form-group"  style="margin-top: 15px;">
                    <label  class="col-sm-2 control-label"></label>
                    <div class="col-sm-8">
                        <input type="text" id="total_fee" class="form-control" placeholder="金额" name="total_fee" onkeyup="clearNoNum(this)">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 35px;">
                    <div class="col-sm-12 col-xs-12">
                        <a href="javascript:void(0)" class="form-control btn btn-info text-center" onclick="wxpay(this)"  >支付</a>
                    </div>
                </div>
            </form>
            <div class="form-group" style="margin-top: 35px;">
                <div class="col-sm-12 col-xs-12 text-center">
联系电话：<i><a href="tel:<?php echo ($pageinfo['phone']); ?>"><?php echo ($pageinfo['phone']); ?></a></i>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 col-xs-12 text-center">
<span><?php echo ($pageinfo['info']); ?></span>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- JQUERY SCRIPTS -->
<script src="/Public/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="/Public/js/bootstrap.js"></script>
<script language="javascript">
    var lijianconfig =  <?php echo ($lijianconfig); ?> ;
    onload=function(){
        setTimeout(function(){
            document.getElementById("preload").style.display="none";
        },1000);

        $("#total_fee").change(function(){
            if(lijianconfig!=null){
            var total_fee = parseInt($("#total_fee").val()*100);
            var maxdiscount=0;
            var maxlimit=-1;
            for(var i=0;i<lijianconfig.length;i++){
              if(total_fee>=lijianconfig[i]['limit']){
                  if(lijianconfig[i]['limit']>maxdiscount) {
                      maxdiscount=lijianconfig[i]['limit'];
                      maxlimit = i;
                  }
              }
            }
            if(maxlimit>=0){
                total_fee=((total_fee-lijianconfig[maxlimit]['reduce'])/100).toFixed(2);
                $("#discount").html(total_fee);
                $("#discount").parent().parent().css({"display":"block"});
            }else{
                $("#discount").parent().parent().css({"display":"none"});
            }
            }
        });
    }
    function wxpay(self){
        var $paybtn = $(self);
        $paybtn.attr("disabled", true);

        var params = $("#wxpayForm").serialize();

        $.ajax({
            url:"/index.php/home/pay/wechatpay?mid=15&amp;store_id=24",
            data:params,
            dataType:"json",
            type:"post",
            success: function (result) {
                $paybtn.attr("disabled", false);
                if(result.result==1) {
                    WeixinJSBridge.invoke(
                            'getBrandWCPayRequest', {
                                "appId": result.data.appId,     //公众号名称，由商户传入
                                "timeStamp": result.data.timeStamp,       //时间戳，自1970年以来的秒数
                                "nonceStr": result.data.nonceStr, //随机串
                                "package": result.data.package,
                                "signType": result.data.signType,        //微信签名方式：
                                "paySign": result.data.paySign //微信签名
                            },
                            function (res) {

                                if (res.err_msg == "get_brand_wcpay_request:ok") {

                                    window.location.href = "/index.php/home/pay/paySuccess";
                                }     // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
                            }
                    );
                }else{
                    $paybtn.attr("disabled", false);
                    console.info(JSON.stringify(result));
                }
            },
            error: function () {
                $paybtn.attr("disabled", false);
                alert("error")
            }
        })
    }
    function clearNoNum(obj)
    {
        //先把非数字的都替换掉，除了数字和.
        obj.value = obj.value.replace(/[^\d.]/g,"");
        //必须保证第一个为数字而不是.
        obj.value = obj.value.replace(/^\./g,"");
        //保证只有出现一个.而没有多个.
        obj.value = obj.value.replace(/\.{2,}/g,".");
        //保证.只出现一次，而不能出现两次以上
        obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
    }
</script>
</body>
</html>