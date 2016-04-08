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
        if(isset($_SESSION['loginStaff'])){
            $merchant=M('merchant')->field(array("id"))->where("is_proxy=1")->find();
            $wxconfig = CommonUtil::getMerchantConfig($merchant['id']);
            $wechat = new WeChatUtil($wxconfig, $merchant['id']);
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') >= 0 && empty($_SESSION['openid'])) {

                $wechat->authorize_openid('http://' .$wxconfig['domain'] . $_SERVER['REQUEST_URI']);

            }
            $staff=M('staff')->where("openid=".$_SESSION['openid'])->find();
            if(empty($staff)){
                $this->redirect("login");
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
                $_SESSION['loginStaff']=$data;

                $this->display("index");
                exit();
            }
            $this->error('帐号或密码错误','login');
        }
    }
    public function boundwx()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {

            $mid = intval(trim($_GET['mid']));
            $store_id = intval(trim($_GET['store_id']));
            if ((!(0 < $mid)) || (!(0 < $store_id))) {
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
                $wechat->authorize_openid('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            }
            $loginStaff = cookie('loginStaff');
            cookie("loginStaff", null);
            $loginStaff['openid']=$_SESSION['openid'];
            M('staff')->save($loginStaff);
            $expire=time()+60*60*24*30;
            cookie("loginStaff", $loginStaff,$expire);
            $this->success('绑定成功', "sign");
        }
    }

    /**
     * sign in or sign out
     */
    public function sign(){
       if(empty(cookie("loginStaff")))
           $this->redirect("login");
       $staff = cookie("loginStaff");
        //var_dump($staff);
        if(!($staff['is_sign']==1)){
            $staff['is_sign']=1;
            M('staff')->save($staff);
            cookie("loginStaff", null);
            $expire=time()+60*60*24*30;
            cookie("loginStaff", $staff, $expire);
        }

        $this->display();
    }
    public function signout(){
        if(empty(cookie("loginStaff"))||(!isset(cookie("loginStaff")['store_id'])))
            $this->redirect("login");
        $staff = cookie("loginStaff");

        //var_dump($staff);
        if(!($staff['is_sign']==0)){
            $staff['is_sign']=0;
            M('staff')->save($staff);
            cookie("loginStaff", null);
            $expire=time()+60*60*24*30;
            cookie("loginStaff", $staff, $expire);
        }

        $this->display();
    }

    public function  storeStatistics(){
        if($_SERVER['REQUEST_METHOD']=="POST"){

            $db = M('order');
            $condition = "1=1 AND is_pay=1 AND store_id=".cookie("loginStaff")['store_id'];

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
            if(empty(cookie("loginStaff"))||(!isset(cookie("loginStaff")['store_id'])))
                $this->redirect("sign");
            $condition = "1=1 AND is_pay=1 AND store_id=".cookie("loginStaff")['store_id'];
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
}