<?php
/**
 * Created by lwt.
 * User: Administrator
 * Date: 2016/3/16
 * Time: 14:06
 */

namespace Home\Controller;

use Think\Controller;
use Util\CommonUtil;
use Util\WeChatUtil;

class MerchantController extends Controller
{
    public function index(){
        //TODO 判断session再判断openid

        if(empty($_SESSION['loginMerchant'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') >= 0 && empty($_SESSION['openid'])) {
                $merchant = M('merchant')->field(array("id"))->where("is_proxy=1")->find();
                $wxconfig = CommonUtil::getMerchantConfig($merchant['id']);
                $wechat = new WeChatUtil($wxconfig, $merchant['id']);
                $wechat->authorize_openid('http://' . $wxconfig['domain'] . $_SERVER['REQUEST_URI']);
            }
            $wxmerchant = M('wxmerchant')->where("openid='%s'", array($_SESSION['openid']))->find();
            if (empty($wxmerchant)) {
                $this->redirect("login");
            } else {
                $merchant=M('merchant')->where("id='%s'",array($wxmerchant['mid']))->find();
                $merchant['openid']=$_SESSION['openid'];
                $_SESSION['loginMerchant'] = $merchant;

            }
        }else{
            $stores=M("store")->where("mid='%s'",array($_SESSION['loginMerchant']['id']))->select();
            $this->assign("stores",$stores);
            $this->display();
        }
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $this->display();
        } else {
            $db = M('merchant');
            $data = $db->where("username='%s'", array($_POST['username']))->find();
            if (md5($_POST['password'].$data['salt']) == $data['password']) {
                unset($_SESSION['loginMerchant']);
               $wxmerchant=array(
                   "mid"=>$data['id'],
                   "openid"=>$_SESSION['openid']
                   );
                M('wxmerchant')->add($wxmerchant);
                $data['openid']=$_SESSION['openid'];
                $_SESSION['loginMerchant']=$data;

                $this->redirect("index");
                exit();
            }
            $this->error('帐号或密码错误','login');
        }
    }
    public function unboundwx(){
        M('wxmerchant')->where("mid='%s' AND openid='%s'",array($_SESSION['loginMerchant']['id'],$_SESSION['loginMerchant']['openid']))->delete();
        unset($_SESSION['loginMerchant']);
        $this->redirect("index");
    }

    public function statistics(){
        if(empty($_SESSION['loginMerchant']))
            $this->redirect("index");
        $condition="WHERE 1=1 AND is_pay=1 AND mid=".$_SESSION['loginMerchant']['id'];
        if(isset($_POST['date'])&&(!(trim($_POST['date'])=="")))
            $condition.=" AND FROM_UNIXTIME(create_time,'%Y%m%d')=FROM_UNIXTIME(".strtotime($_POST['date']).",'%Y%m%d')";
        if(isset($_POST['store_id'])&&(!(trim($_POST['store_id'])=="")))
            $condition.=" AND store_id=".$_POST['store_id'];
        $data=M('order')->query("SELECT COUNT(*) AS c,SUM(total_fee) AS fee FROM tgyx_order ".$condition);
        $data=$data[0];
        if(empty($data['fee']))
            $data['fee']=0;

        $this->ajaxReturn($data);
    }
}