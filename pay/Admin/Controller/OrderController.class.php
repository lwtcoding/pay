<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/23
 * Time: 16:55
 */

namespace Admin\Controller;

use Think\Controller;
use Util\AuthController;
use Util\CommonUtil;
use Util\WeChatUtil;

class OrderController extends AuthController
{
    public function orders(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
                $db = M('order');
                $pager = array();
                $condition = "1=1 AND is_pay=1";
                $condition .= " AND mid=" . $_SESSION['loginMerchant']['id'];

                if(isset($_POST['store_id'])&&(!(trim($_POST['store_id'])=="")))
                    $condition.=" AND store_id=".$_POST['store_id'];
                if(isset($_POST['order_no'])&&(!(trim($_POST['order_no'])=="")))
                    $condition.=" AND order_no like '%".$_POST['order_no']."%'";
                if(isset($_POST['begin_time'])&&(!(trim($_POST['begin_time'])=="")))
                    $condition.=" AND time_end>=".date("YmdHis",strtotime($_POST['begin_time']));
                if(isset($_POST['end_time'])&&(!(trim($_POST['end_time'])=="")))
                    $condition.=" AND time_end<=".date("YmdHis",strtotime($_POST['end_time']));
                if(isset($_POST['store_id'])&&(!(trim($_POST['store_id'])=="")))
                    $condition.=" AND store_id=".$_POST['store_id'];

                $pager['total'] = $db->where($condition)->count();
                $orders=$db->where($condition)->order(($_POST['sort']==''?'id':$_POST['sort']).' '.$_POST['order'])->limit($_POST['offset'] . "," . $_POST['limit'])->select();
                for($i=0;$i<count($orders);$i++){
                    $orders[$i]['time_end']=date('Y-m-d H:i:s',strtotime(  $orders[$i]['time_end']));
                }
                $pager['rows'] =$orders;
                $this->ajaxReturn($pager);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            if(isset($_SESSION['loginMerchant'])&&!isset($_SESSION['loginMerchant']['pretend'])) {
                $stores = M('store')->field(array('id','mid','name'))->where("mid = '%s'",array($_SESSION['loginMerchant']['id']))->select();
                $this->assign("stores",$stores);
            }
            if(isset($_SESSION['loginStaff'])) {
                $stores = M('store')->field(array('id','mid','name'))->where("id = '%s'",array($_SESSION['loginStaff']['store_id']))->select();
                $this->assign("stores",$stores);
            }
            $this->display();
        }
    }
    public function proxyOrders(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $db = M('order');
            $pager = array();
            $condition = "1=1 AND is_pay=1";
            if((!isset($_POST['mid']))||(trim($_POST['mid'])=="")) {
                if($_SESSION['loginMerchant']['is_proxy']==1){
                    $condition .= " AND pmid=" .$_SESSION['loginMerchant']['id'];
                }
                if(($_SESSION['loginMerchant']['is_proxy']==0)&&($_SESSION['loginMerchant']['parent_id']==0)){
                    $condition .= " AND pmid=" .$_SESSION['loginMerchant']['pid']." AND (parent_id=".$_SESSION['loginMerchant']['id']." OR mid=".$_SESSION['loginMerchant']['id'].")";
                }

            }else{
                if(!($_SESSION['loginMerchant']['id'] == $_POST['mid'])) {
                    $merchant = M('Merchant')->field(array('pid', 'parent_id'))->where("id='%s'", array($_POST['mid']))->find();
                    if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid'])) && (!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                        $this->ajaxReturn(array("result" => 0, "message" => "无权查看", "data" =>null));
                    }
                }
                    $condition .= " AND mid=" . $_POST['mid'];
            }

            if(isset($_POST['order_no'])&&(!(trim($_POST['order_no'])=="")))
                $condition.=" AND order_no like '%".$_POST['order_no']."%'";
            if(isset($_POST['storename'])&&(!(trim($_POST['storename'])=="")))
                $condition.=" AND storename like '%".$_POST['storename']."%'";
            if(isset($_POST['begin_time'])&&(!(trim($_POST['begin_time'])=="")))
                $condition.=" AND time_end>=".date("YmdHis",strtotime($_POST['begin_time']));
            if(isset($_POST['end_time'])&&(!(trim($_POST['end_time'])=="")))
                $condition.=" AND time_end<=".date("YmdHis",strtotime($_POST['end_time']));

            $pager['total'] = $db->where($condition)->count();
            $orders=$db->where($condition)->order(($_POST['sort']==''?'id':$_POST['sort']).' '.$_POST['order'])->limit($_POST['offset'] . "," . $_POST['limit'])->select();
            for($i=0;$i<count($orders);$i++){
                $orders[$i]['time_end']=date('Y-m-d H:i:s',strtotime(  $orders[$i]['time_end']));
            }
            $pager['rows'] =$orders;
            $this->ajaxReturn($pager);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            if($_SESSION['loginMerchant']['is_proxy']==1){
                $submerchants=M('merchant')->field(array('id','merchantname'))->where("pid='%s'",array($_SESSION['loginMerchant']['id']))->select();
                $this->assign("submerchants",$submerchants);
            }
            if(($_SESSION['loginMerchant']['is_proxy']==0)&&($_SESSION['loginMerchant']['parent_id']==0)){
                $submerchants=M('merchant')->field(array('id','merchantname'))->where("pid='%s' AND (parent_id='%s' OR id='%s')",array($_SESSION['loginMerchant']['pid'],$_SESSION['loginMerchant']['id'],$_SESSION['loginMerchant']['id']))->select();
                $this->assign("submerchants",$submerchants);
            }
            $this->display();
        }
    }


    public function  statistics()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $sql = "";
            $conditions = " AND 1=1";
            $conditions .= " AND mid=" . $_SESSION['loginMerchant']['id'];

            if (isset($_POST['store_id']) && (!(trim($_POST['store_id'] == ""))))
                $conditions .= " AND store_id=" . $_POST['store_id'];


            if ($_POST['type'] == 'day') {
                //某天，按小时统计支付笔数和金额
                $sql = "SELECT FROM_UNIXTIME(create_time,'%Y-%m-%d %H') labels,COUNT(id) total,SUM(total_fee)/100 total_fee,SUM(refund_fee)/100 total_refund FROM tgyx_order where is_pay=1" . $conditions . " AND FROM_UNIXTIME(create_time,'%Y%m%d')>=FROM_UNIXTIME(" . strtotime($_POST['begin_date']) . ",'%Y%m%d') AND FROM_UNIXTIME(create_time,'%Y%m%d')<=FROM_UNIXTIME(" . strtotime($_POST['end_date']) . ",'%Y%m%d') GROUP BY labels";

            }
            if ($_POST['type'] == 'month') {
                //某天，按小时统计支付笔数和金额
                $sql = "SELECT FROM_UNIXTIME(create_time,'%Y-%m-%d') labels,COUNT(id) total,SUM(total_fee)/100 total_fee,SUM(refund_fee)/100 total_refund FROM tgyx_order where is_pay=1" . $conditions . " AND FROM_UNIXTIME(create_time,'%Y%m')>=FROM_UNIXTIME(" . strtotime($_POST['begin_date']) . ",'%Y%m') AND FROM_UNIXTIME(create_time,'%Y%m')<=FROM_UNIXTIME(" . strtotime($_POST['end_date']) . ",'%Y%m') GROUP BY labels";
            }
            if ($_POST['type'] == 'year') {
                //某天，按小时统计支付笔数和金额
                $sql = "SELECT FROM_UNIXTIME(create_time,'%Y-%m') labels,COUNT(id) total,SUM(total_fee)/100 total_fee,SUM(refund_fee)/100 total_refund FROM tgyx_order where is_pay=1" . $conditions . " AND FROM_UNIXTIME(create_time,'%Y')>=FROM_UNIXTIME(" . strtotime($_POST['begin_date']) . ",'%Y') AND FROM_UNIXTIME(create_time,'%Y')<=FROM_UNIXTIME(" . strtotime($_POST['end_date']) . ",'%Y') GROUP BY labels";
            }
            $result = M('order')->query($sql);
            $statistics = array(
                'labels' => array(),
                'total' => array(),
                'total_fee' => array(),
                'total_refund' => array(),
            );
            for ($i = 0; $i < count($result); $i++) {
                array_push($statistics['labels'], $result[$i]['labels']);
                array_push($statistics['total'], $result[$i]['total']);
                array_push($statistics['total_fee'], $result[$i]['total_fee']);
                array_push($statistics['total_refund'], $result[$i]['total_refund']);
            }
            $this->ajaxReturn($statistics);

        }

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if(isset($_SESSION['loginMerchant'])&&!isset($_SESSION['loginMerchant']['pretend'])) {
                $stores = M('store')->field(array('id','mid','name'))->where("mid = '%s'",array($_SESSION['loginMerchant']['id']))->select();
                $this->assign("stores",$stores);
            }
            if(isset($_SESSION['loginStaff'])) {
                $stores = M('store')->field(array('id','mid','name'))->where("id = '%s'",array($_SESSION['loginStaff']['store_id']))->select();
                $this->assign("stores",$stores);
            }

            $this->display();
        }
    }
        public function  proxyStatistics(){
            if($_SERVER['REQUEST_METHOD']=="POST"){
                $sql="";
                $conditions=" AND 1=1";

                if((!isset($_POST['mid']))||(trim($_POST['mid'])=="")) {
                    if($_SESSION['loginMerchant']['is_proxy']==1){
                        $conditions .= " AND pmid=" .$_SESSION['loginMerchant']['id'];
                    }
                    if(($_SESSION['loginMerchant']['is_proxy']==0)&&($_SESSION['loginMerchant']['parent_id']==0)){
                        $conditions .= " AND pmid=" .$_SESSION['loginMerchant']['pid']." AND (parent_id=".$_SESSION['loginMerchant']['id']." OR mid=".$_SESSION['loginMerchant']['id'].")";
                    }

                }else{
                    if(!($_SESSION['loginMerchant']['id'] == $_POST['mid'])) {
                        $merchant = M('Merchant')->field(array('pid', 'parent_id'))->where("id='%s'", array($_POST['mid']))->find();
                        if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid'])) && (!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                            $this->ajaxReturn(array("result" => 0, "message" => "无权查看", "data" => null));
                        }
                    }
                    $conditions .= " AND mid=" . $_POST['mid'];
                }

                if ($_POST['type'] == 'day') {
                    //某天，按小时统计支付笔数和金额
                    $sql = "SELECT FROM_UNIXTIME(create_time,'%Y-%m-%d %H') as labels,COUNT(id) as total,SUM(total_fee)/100 as total_fee,SUM(refund_fee)/100 as total_refund FROM tgyx_order where is_pay=1" . $conditions . " AND FROM_UNIXTIME(create_time,'%Y%m%d')>=FROM_UNIXTIME(" . strtotime($_POST['begin_date']) . ",'%Y%m%d') AND FROM_UNIXTIME(create_time,'%Y%m%d')<=FROM_UNIXTIME(" . strtotime($_POST['end_date']) . ",'%Y%m%d') GROUP BY labels";

                }
                if ($_POST['type'] == 'month') {
                    //某天，按小时统计支付笔数和金额
                    $sql = "SELECT FROM_UNIXTIME(create_time,'%Y-%m-%d') as labels,COUNT(id) as total,SUM(total_fee)/100 as total_fee,SUM(refund_fee)/100 as total_refund FROM tgyx_order where is_pay=1" . $conditions . " AND FROM_UNIXTIME(create_time,'%Y%m')>=FROM_UNIXTIME(" . strtotime($_POST['begin_date']) . ",'%Y%m') AND FROM_UNIXTIME(create_time,'%Y%m')<=FROM_UNIXTIME(" . strtotime($_POST['end_date']) . ",'%Y%m') GROUP BY labels";
                }

                $result = M('order')->query($sql);
                $statistics = array(
                    'labels' => array(),
                    'total' => array(),
                    'total_fee' => array(),
                    'total_refund' => array(),
                );
                for ($i = 0; $i < count($result); $i++) {
                    array_push($statistics['labels'], $result[$i]['labels']);
                    array_push($statistics['total'], $result[$i]['total']);
                    array_push($statistics['total_fee'], $result[$i]['total_fee']);
                    array_push($statistics['total_refund'], $result[$i]['total_refund']);
                }
                $this->ajaxReturn($statistics);

            }

            if($_SERVER['REQUEST_METHOD']=="GET"){
                if($_SESSION['loginMerchant']['is_proxy']==1){
                    $submerchants=M('merchant')->field(array('id','merchantname'))->where("pid='%s'",array($_SESSION['loginMerchant']['id']))->select();
                    $this->assign("submerchants",$submerchants);
                }
                if(($_SESSION['loginMerchant']['is_proxy']==0)&&($_SESSION['loginMerchant']['parent_id']==0)){
                    $submerchants=M('merchant')->field(array('id','merchantname'))->where("pid='%s' AND (parent_id='%s' OR id='%s')",array($_SESSION['loginMerchant']['pid'],$_SESSION['loginMerchant']['id'],$_SESSION['loginMerchant']['id']))->select();
                    $this->assign("submerchants",$submerchants);
                }
                $this->display();
            }
    }

    public function refund(){

        if(isset($_POST['this_refund_fee'])&&is_numeric($_POST['this_refund_fee'])){

            $wxconfig = CommonUtil::getMerchantConfig($_POST['mid']);
            $wechatUtil = new WeChatUtil($wxconfig,$_POST['mid']);
            $params = array();
            $params['appid']=$wxconfig['appid'];
            $params['mch_id']=$wxconfig['mchid'];
            if(isset($wxconfig['submchid']))
            $params['sub_mch_id']=$wxconfig['submchid'];
            $params['nonce_str']=CommonUtil::genernateNonceStr(32);
            if(isset($_SESSION['loginStaff'])) {
                $params['op_user_id'] =$_SESSION['loginStaff']['username'];
            }else{
                $params['op_user_id'] =$_SESSION['loginMerchant']['username'];
            }
            $params['out_refund_no']=CommonUtil::createOrderNo();
            $params['out_trade_no']=$_POST['order_no'];
            $params['refund_fee']=$_POST['this_refund_fee']*100;
            $params['total_fee']=$_POST['total_fee']*100;
            $params['sign']=CommonUtil::createSign($params,$wxconfig['apikey']);
            $xmlparams = CommonUtil::arrToXml($params);
            $res=CommonUtil::curl_post_ssl("https://api.mch.weixin.qq.com/secapi/pay/refund",$xmlparams,$wxconfig);
           if(isset($res["error"]))
               $this->ajaxReturn(array("result" => "error", "message" =>"退款失败", "data" => $res["error"]));
            $res = CommonUtil::xmlToArray($res);
            if($res['return_code']=="FAIL")
                $this->ajaxReturn(array("result" => "error", "message" =>"退款失败", "data" =>$res["return_msg"]));
            if($res['return_code']=="SUCCESS"){
                $updatedata=array(
                    "refund_fee"=>$res['refund_fee']
                );
                M('order')->where("id=".$_POST['id'])->save($updatedata);
                $this->ajaxReturn(array("result" => "success", "message" =>"退款成功", "data" =>null));
            }


        }else{
            $this->ajaxReturn(array("result" => "error", "message" => "退款失败", "data" => null));
        }
    }


}