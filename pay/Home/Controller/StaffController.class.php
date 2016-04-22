<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/28
 * Time: 11:14
 */

namespace Home\Controller;

use Think\Controller;
use Util\WeChatUtil;
use Util\CommonUtil;
class StaffController extends Controller
{
    /**
     * 操作页面
     */
    public function index(){
        //TODO 判断session再判断openid
        if(empty($_SESSION['loginStaff'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') >= 0 && empty($_SESSION['openid'])) {
                $merchant = M('merchant')->field(array("id"))->where("is_proxy=1")->find();
                $wxconfig = CommonUtil::getMerchantConfig($merchant['id']);
                $wechat = new WeChatUtil($wxconfig, $merchant['id']);
                $wechat->authorize_openid('http://' . $wxconfig['domain'] . $_SERVER['REQUEST_URI']);
            }
            $staff = M('staff')->where("openid='%s'", array($_SESSION['openid']))->find();
            if (empty($staff)) {
                $this->redirect("login");
            } else {
                $_SESSION['loginStaff'] = $staff;

            }
        }else{
            $this->display();
        }
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $this->display();
        } else {
            $db = M('staff');
            $data = $db->where("username='%s'", array($_POST['username']))->find();
            if (md5($_POST['password'].$data['salt']) == $data['password']) {
                unset($_SESSION['loginStaff']);
                if(!empty($data['openid']))
                    $this->error('该帐号已绑定微信号，请尝试其他帐号！','login');
                $data['openid']=$_SESSION['openid'];
                $db->save($data);
                $_SESSION['loginStaff']=$data;

                $this->display("index");
                exit();
            }
            $this->error('帐号或密码错误','login');
        }
    }
  /*  public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $this->display();
        } else {
            $db = M('staff');
            $data = $db->where("username='%s'", array($_POST['username']))->find();
            if (md5($_POST['password'].$data['salt']) == $data['password']) {
                unset($_SESSION['loginStaff']);
                $_SESSION['loginStaff']=$data;

                $this->display("index");
                exit();
            }
            $this->error('帐号或密码错误','login');
        }
    }*/
    public function boundwx()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if(empty($_SESSION['loginStaff'])){
                $this->error('请先登录！','index');
                exit();
            }


            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') >= 0 && empty($_SESSION['openid'])) {
                $wxconfig = CommonUtil::getMerchantConfig($_SESSION['loginStaff']['mid']);
                if (empty($wxconfig)) {
                    exit("获取支付配置失败");
                }
                $wechat = new WeChatUtil($wxconfig, $_SESSION['loginStaff']['mid']);
                $wechat->authorize_openid('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            }
            $loginStaff = $_SESSION['loginStaff'];
            unset($_SESSION['loginStaff']);
            $loginStaff['openid']=$_SESSION['openid'];
            //先清除之前绑定微信的员工号
            M('staff')->where("openid='%s'",array($_SESSION['openid']))->save(array("openid"=>null));
            M('staff')->save($loginStaff);

            $_SESSION['loginStaff']=$loginStaff;
            $this->success('绑定成功', "index");
        }
    }

    /**
     * 注销绑定
     */
    public function unboundwx()
    {
        $_SESSION['loginStaff']['openid']=null;
        M('staff')->save($_SESSION['loginStaff']);
        unset($_SESSION['loginStaff']);
        $this->redirect("index");
    }
    /**
     * sign in or sign out
     */
    public function sign(){
       if(empty($_SESSION['loginStaff']))
           $this->redirect("index");
        if(empty($_SESSION['loginStaff']['openid']))
            $this->error("请先绑定微信，才能接收支付通知！","index");

       $staff =$_SESSION['loginStaff'];
        //var_dump($staff);
        if(!($staff['is_sign']==1)){
            $staff['is_sign']=1;
            M('staff')->save($staff);
            $_SESSION['loginStaff']['is_sign']=1;
        }
        $this->display();
    }
    public function signout(){
        if(empty($_SESSION['loginStaff']))
            $this->redirect("index");
        $staff =$_SESSION['loginStaff'];

        //var_dump($staff);
        if(!($staff['is_sign']==0)){
            $staff['is_sign']=0;
            M('staff')->save($staff);
           $_SESSION['loginStaff']['is_sign']=0;
        }
        $this->display();
    }

    public function  storeStatistics(){
        if($_SERVER['REQUEST_METHOD']=="POST"){

            $db = M('order');
            $condition = "1=1 AND is_pay=1 AND store_id=".$_SESSION['loginStaff']['store_id'];

            if(isset($_POST['order_no'])&&(!(trim($_POST['order_no'])=="")))
                $condition.=" AND order_no like '%".$_POST['order_no']."%'";
            if(isset($_POST['begin_time'])&&(!(trim($_POST['begin_time'])=="")))
                $condition.=" AND time_end>=".date("YmdHis",strtotime($_POST['begin_time']));
            if(isset($_POST['end_time'])&&(!(trim($_POST['end_time'])=="")))
                $condition.=" AND time_end<=".date("YmdHis",strtotime($_POST['end_time']));


            $orders=$db->field(array("id","order_no","total_fee","time_end"))->where($condition)->order(($_POST['sort']==''?'id':$_POST['sort']).' '.$_POST['order'])->limit($_POST['offset'] . "," . $_POST['limit'])->select();
            for($i=0;$i<count($orders);$i++){
                $orders[$i]['time_end']=date('Y-m-d H:i:s',strtotime(  $orders[$i]['time_end']));
            }

            $this->ajaxReturn($orders);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            if(empty($_SESSION['loginStaff']))
                $this->redirect("index");
            $condition = "1=1 AND is_pay=1 AND store_id=".$_SESSION['loginStaff']['store_id'];
            if(isset($_GET['begin_time'])&&(!(trim($_GET['begin_time'])==""))) {
                $condition .= " AND time_end>=" . date("YmdHis", strtotime($_GET['begin_time']));
                $this->assign("begin_time",$_GET['begin_time']);
            }
            if(isset($_GET['end_time'])&&(!(trim($_GET['end_time'])==""))) {
                $condition .= " AND time_end<=" . date("YmdHis", strtotime($_GET['end_time']));
                $this->assign("end_time",$_GET['end_time']);
            }
            if((!isset($_GET['begin_time']))&&(!isset($_GET['time_end'])))
                $condition.=" AND FROM_UNIXTIME(create_time,'%Y%m%d')=FROM_UNIXTIME(".time().",'%Y%m%d')";

            $sql = "SELECT COUNT(id) as times, SUM(total_fee) as fees FROM tgyx_order WHERE ".$condition;
            $count = M('order')->query($sql);
            $count=$count[0];
            if(empty($count['times']))
                $count['times']=0;
            if(empty($count['fees']))
                $count['fees']=0;

            $this->assign("count",$count);
            $this->display();
        }

    }
    public function  showOrder(){
        if(empty($_SESSION['loginStaff']))
            $this->redirect("index");
        $oid = $_GET['order_id'];
        $order=M('order')->where("id='%s'",array($oid))->find();
        if(!empty($order)){
            if(!($order['mid']==$_SESSION['loginStaff']['mid']))
                $this->redirect("index");
        }
        $order['time_end']=date('Y-m-d H:i:s',strtotime(  $order['time_end']));
        $order['total_fee']=$order['total_fee']/100;
        if($order['is_pay']==1){
            $order['status']="支付成功";
        }else{
            $order['status']="未支付";
        }
        $this->assign("order",$order);
        $this->display();
    }
}