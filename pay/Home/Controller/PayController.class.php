<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/21
 * Time: 16:35
 */
namespace Home\Controller;

use Think\Controller;
use Util\CommonUtil;
use Util\WeChatUtil;
class PayController extends Controller
{

    public function wechatPay()
    {

        if($_SERVER['REQUEST_METHOD']=="GET") {

            $mid = intval(trim($_GET['mid']));
            $store_id = intval(trim($_GET['store_id']));
            if ((!(0 < $mid))||(!(0<$store_id))) {
                $this->error('参数出错，没有商家ID或没有门店ID！');
                exit();
            }
            $wxconfig = CommonUtil::getMerchantConfig($mid);
            $wechat = new WeChatUtil($wxconfig, $_GET[mid]);

            if (empty($wxconfig)) {
                exit("获取支付配置失败");
            }


            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') >= 0 && empty($_SESSION['openid'])) {

                $wechat->authorize_openid('http://' .$wxconfig['domain'] . $_SERVER['REQUEST_URI']);

            }

            $wxuserinfo = array();
            if (strpos($user_agent, 'MicroMessenger') >= 0 && !empty($_SESSION['openid'])) {
                $access_token = $wechat->accessToken();
                $wxuserinfo = $wechat->userinfo($access_token, $_SESSION['openid']);
                if (!empty($wxuserinfo)) {
                    $this->fanSave($wxuserinfo, $mid);
                }
            }
            $store=S("store".$store_id);
            if(!$store){
                $store=M('store')->where("id='%s'",array($store_id))->find();
                S("store".$store_id,$store);
            }

            //TODO 满减活动
            $lijian=S("lijian".$_GET['mid']);
            if(empty($lijian)) {
                $lijian = M('lijian')->where("mid = '%s' AND status = 1", array($_GET['mid']))->find();
            }
            $lijianconfig = null;
            if(!empty($lijian))
                $lijianconfig = json_decode($lijian['config'],true);

            $token = md5($store_id.$mid.$store['salt']);
            $pageinfo = M('pageinfo')->where("mid=".$_GET['mid'])->find();
            $this->assign("pageinfo",$pageinfo);

            $this->assign("lijianconfig",json_encode($lijianconfig));
            $this->assign("mid",$mid);
            $this->assign("store_id",$store_id);
            $this->assign("token",$token);
            $this->display();
        }else{
            if ($_POST['total_fee'] && is_numeric($_POST['total_fee']) && (0 < $_POST['mid']) && (0 < $_POST['store_id'])) {
                //TODO 验证
                $store=S("store".$_POST['store_id']);
                if(!$store){
                    $store=M('store')->where("id='%s'",array($_POST['store_id']))->find();
                    S("store".$_POST['store_id'],$store);
                }
                if(!(md5($_POST['store_id'].$_POST['mid'].$store['salt'])==$store['token'])||!($store['token']==$_POST['token'])){
                    $this->ajaxReturn(array('result' => 0, 'msg' => "安全检验失败", 'data' =>$store['token']));
                }
                //TODO 生成订单
                $order = array();
                //TODO 满减活动
                $lijian=S("lijian".$_POST['mid']);
                if(empty($lijian)) {
                    $lijian = M('lijian')->where("mid = '%s' AND status = 1", array($_GET['mid']))->find();
                }
                $lijianconfig = null;
                if(!empty($lijian))
                    $lijianconfig = json_decode($lijian['config'],true);
                $_POST['total_fee']=(int)($_POST['total_fee']*100);
                $order['lijian_fee']=0;
                if(!empty($lijianconfig)){
                    $maxdiscount=0;
                    $maxlimit=-1;
                    for($i=0;$i<count($lijianconfig);$i++){
                        if($_POST['total_fee']>=$lijianconfig[$i]['limit']){
                            if($lijianconfig[$i]['limit']>$maxdiscount){
                                $maxdiscount=$lijianconfig[$i]['limit'];
                                $maxlimit=$i;
                            }
                        }
                    }
                    $_POST['total_fee']=$_POST['total_fee']-$lijianconfig[$maxlimit]['reduce'];
                    $order['lijian_fee']=$lijianconfig[$maxlimit]['reduce'];
                }
                //TODO 查询该用户有无该商户的会员卡，找出最低折扣的会员卡
                $cr = M('wxcoupon_receive')->where("openid='%s' AND mid=%s",array($_SESSION['openid'], $_POST['mid']))->find();
                if(!empty($cr)){
                    $coupon = M('wxcoupon')->field(array('id','discount'))->where("status=1 AND card_id='".$cr['card_id']."'")->find();
                    if(!empty($coupon)) {
                        $_POST['total_fee'] = (int)($_POST['total_fee'] * ($coupon['discount'] / 100));
                        $order['vip_discount'] = $coupon['discount'];
                    }
                }
                //从缓存里取出
                $faninfo = S('fans' . $_SESSION['openid']);
                if (!$faninfo) {
                    $faninfo = M('fans')->where("openid = '%s' and mid = '%s'", array($_SESSION['openid'], $_POST['mid']))->find();
                    S('fans' . $_SESSION['openid'], $faninfo, 600);
                }

                $merchant = S("merchant" . $_POST['mid']);

                if (!$merchant) {
                    $merchant = M('merchant')->where("id = '%s'", array($_POST['mid']))->find();
                    if (!empty($merchant)) {
                        S("merchant" . $_POST['mid'], $merchant);
                    }
                }
                $order_no = CommonUtil::createOrderNo();
                $order['order_no'] = $order_no;
                $order['mid'] = $merchant['id'];
                $order['pmid'] = $merchant['pid'];

                /* $order['pay_way']=$_POST['pay_way'];
                 $order['pay_type']=$_POST['pay_type'];*/
                $order['pay_way'] = "weixin";
                $order['pay_type'] = "JSAPI";
                $order['store_id'] = $_POST['store_id'];
                $order['goods_name'] = $_POST['goods_name']?$_POST['goods_name']:"自定义扫码支付";
                $order['total_fee'] = $_POST['total_fee'];
                $order['create_time'] = time();
                $order['fans_id'] = $faninfo['id'];
                $order['fans_name'] = $faninfo['nickname'];
                $order['openid'] = $faninfo['openid'];
                $order['is_subscribe'] = $faninfo['is_subscribe'] == 1 ? "Y" : "N";
                M('order')->add($order);
                //TODO 调用统一下单
                $wxconfig = CommonUtil::getMerchantConfig($_POST['mid']);
                $wechat = new WeChatUtil($wxconfig, $_GET[mid]);
                $resp = $wechat->unifiedorder($order, $wxconfig);
                if($resp['return_code']=='FAIL') {
                    $this->ajaxReturn(array('result' => 0, 'msg' => "失败", 'data' => null));
                }
                $payParams=array();
                $payParams['appId']=$resp['appid'];
                $payParams['timeStamp']=time();
                $payParams['nonceStr']=CommonUtil::genernateNonceStr(32);
                $payParams['package']="prepay_id=".$resp['prepay_id'];
                $payParams['signType']="MD5";
                $payParams['paySign']=CommonUtil::createSign($payParams,$wxconfig['apikey']);
                $this->ajaxReturn(array('result' => 1, 'msg' => '成功', 'data' => $payParams));
            }else{
                $this->ajaxReturn(array('result' => 0, 'msg' => "失败", 'data' => null));
            }
        }
    }
    public function paySuccess(){
        if($_SERVER["REQUEST_METHOD"]=="POST") {
            $input = $GLOBALS['HTTP_RAW_POST_DATA'];
            //file_put_contents("postdata.txt", $input);
            $resp = CommonUtil::xmlToArray($input);
            if ($resp["return_code"] == "SUCCESS" && $resp["result_code"] == "SUCCESS") {
                $order = M('order')->where("order_no='%s'", array($resp["out_trade_no"]))->find();
                if ((!empty($order))&&(!($order['is_pay']==1))) {
                    $order['wx_order_no'] = $resp['transaction_id'];
                    $order['time_end'] = $resp['time_end'];
                    $order['is_pay'] =1;
                    $order['total_fee'] = $resp['total_fee'];
                    M('order')->save($order);
                    //TODO 更新粉丝信息（积分等）如果关注也调用模版消息
                    $fans = M('fans')->field(array('id','total_fee','openid','is_subscribe'))->where("id='%s'",array($order['fans_id']))->find();
                    $fans['total_fee']=$fans['total_fee']+$resp['total_fee'];
                    M('fans')->save($fans);
                    //TODO 调用模版消息通知收银员（is_recive=1才接收）
                    $staffs = M('staff')->field(array('openid'))->where("store_id = '%s' AND is_sign = 1",array($order['store_id']))->select();
                    for($i=0;$i<count($staffs);$i++){
                        $staffs[$i]['openid'];
                        CommonUtil::muban($staffs[$i]['openid'],"",$order['pay_type'],$order['order_no'],$order['total_fee']);
                    }
                    echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
                }
            }
        }else{
            $this->display();
        }
    }
    private function fanSave($wxuserinfo,$mid){
        $wxuserinfo['mid']=$mid;
        $wxuserinfo['is_subscribe']=$wxuserinfo['subscribe'];
        $wxuserinfo['nickname']=CommonUtil::userTextEncode($wxuserinfo['nickname']);

        $fan_id = M('fans') -> field(array("id")) -> where("openid = '%s' and mid = '%s'",array($wxuserinfo['openid'],$mid)) -> find();
        if(empty($fan_id)){
            M('fans') -> add($wxuserinfo);
        }else{
            M('fans') -> where("id = '%s'",array($fan_id)) -> save($wxuserinfo);
        }
    }



}