<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/18
 * Time: 10:43
 */

namespace Admin\Controller;

use Think\Controller;
use Util\AuthController;
use Util\WeChatUtil;
use Util\CommonUtil;
use Util\AuthConfig;
class StoreController extends AuthController
{
    public function stores(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $db = M('store');
            $pager = array();
            $condition = "1=1";

            $condition .= " AND mid=" . $_POST['mid'];

            if(isset($_POST['name'])&&(!(trim($_POST['name'])=="")))
                $condition.=" AND name like '%".$_POST['name']."%'";
            $pager['total'] = $db->where($condition)->count();
            $pager['rows'] = $db->where($condition)->order(($_POST['sort']==''?'id':$_POST['sort']).' '.$_POST['order'])->limit($_POST['offset'] . "," . $_POST['limit'])->select();
            $this->ajaxReturn($pager);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $auths=AuthConfig::$auth;
            if(!($_SESSION['loginMerchant']['parent_id']==0)){
                $au=array("查看代理商户","添加商户","编辑商户","编辑子商户配置","删除商户","代理商订单流水","代理商订单统计",);
                foreach($auths as $k=>$v){
                    foreach($v as $kk=>$vv){

                        if(in_array($vv,$au)){
                            unset($auths[$k]);
                        }
                    }
                }
            }
            $this->assign('auths',$auths);
            if(!isset($_GET['mid'])){
                $this->assign('mid',$_SESSION['loginMerchant']['id']);
                $merchants=M('merchant')->field(array("id","merchantname"))->where("id='%s' OR parent_id='%s' OR pid='%s'",array($_SESSION['loginMerchant']['id'],$_SESSION['loginMerchant']['id'],$_SESSION['loginMerchant']['id']))->select();
                $this->assign('merchants',$merchants);
            }else{
                //TODO 验证
                if(!($_SESSION['loginMerchant']['id'] == $_GET['mid'])) {
                    $merchant = M('Merchant')->field(array('pid', 'parent_id'))->where("id='%s'", array($_GET['mid']))->find();
                    if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid'])) && (!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                        $this->display("Error:403");
                        exit();
                    }
                }
                $merchants=M('merchant')->field(array("id","merchantname"))->where("id='%s' OR parent_id='%s' OR pid='%s'",array($_GET['mid'],$_GET['mid'],$_GET['mid']))->select();

                $this->assign('merchants',$merchants);
                $this->assign('mid',$_GET['mid']);
            }
            $this->display();
        }
    }
    public function add(){
        $db = M('store');
        $_POST['salt']=CommonUtil::genernateNonceStr(12);
        $merchantname  = M('merchant')->where('id='.$_POST['mid'])->getField('merchantname');
        $_POST['merchantname']=$merchantname;
        if (!$db->create($_POST)){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->ajaxReturn(array("result"=>"error","message"=>"添加失败！","data"=>""));
        }else{
           $id=$db->add();
            $store=array("id"=>$id,"token"=>md5($id.$_POST['mid'].$_POST['salt']));
            $db->save($store);
            $this->ajaxReturn(array("result"=>"success","message"=>"添加成功！","data"=>"success"));
        }
    }

    public function edit(){
        $db = M('store');
        if (!$db->create($_POST)){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->ajaxReturn(array("result"=>"error","message"=>"编辑失败！","data"=>""));
        }else{
            $db->save();
            $this->ajaxReturn(array("result"=>"success","message"=>"编辑成功！","data"=>"success"));
        }
    }

    public function delete(){
        if ((!isset($_POST['mid']))||(!($_POST['mid']==$_SESSION['loginMerchant']['id']))) {
            $pid = M('Merchant')->where("id='%s'", array($_POST['mid']))->getField('pid');
            if (!$_SESSION['loginMerchant']['id'] == $pid) {
                $this->ajaxReturn(array("result"=>"error","message"=>"删除失败！","data"=>"无权删除"));
            }
        }
        M('store')->where("id='%s'",array($_POST['id']))->delete();
        M('staff')->where("store_id='%s'",array($_POST['id']))->delete();
        $this->ajaxReturn(array("result"=>"success","message"=>"删除成功！","data"=>"success"));
    }
}