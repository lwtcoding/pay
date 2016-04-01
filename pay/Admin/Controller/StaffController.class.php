<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/25
 * Time: 9:18
 */

namespace Admin\Controller;


use Think\Controller;
use Util\AuthConfig;
use Util\AuthController;
use Util\CommonUtil;
use Util\WeChatUtil;

class StaffController extends AuthController
{

    public function staffs(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $db = M('staff');
            $pager = array();
            $condition = "1=1";
            $condition .= " AND mid=" . $_SESSION['loginMerchant']['id'];

            if(isset($_POST['store_id'])&&(!(trim($_POST['store_id'])=="")))
                $condition.=" AND store_id=".$_POST['store_id'];
            if(isset($_POST['nickname'])&&(!(trim($_POST['nickname'])=="")))
                $condition.=" AND nickname like '%".$_POST['nickname']."%'";


            $pager['total'] = $db->where($condition)->count();
            $staffs = $db->field(array('id','username','nickname','mid','auths','store_id','salt'))->where($condition)->order(($_POST['sort']==''?'id':$_POST['sort']).' '.$_POST['order'])->limit($_POST['offset'] . "," . $_POST['limit'])->select();
            for($i=0;$i<count($staffs);$i++){
                $staffs[$i]['auths']=json_decode($staffs[$i]['auths'],true);
            }
            $pager['rows'] =  $staffs;
            $this->ajaxReturn($pager);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $stores = M('store')->field(array('id','mid','name'))->where("mid = '%s'",array($_SESSION['loginMerchant']['id']))->select();
            $this->assign("stores",$stores);
            $this->assign('auths',AuthConfig::$auth);
            $this->display();
        }
    }


    public function add(){

        if($_SERVER['REQUEST_METHOD']=="POST"){
            $db = M('staff');
            $this->validate();
            if(!(empty($db->where("username = '%s'",array($_POST['username']))->find())))
                $this->ajaxReturn(array("result"=>"error","message"=>"用户名（账号）已存在","data"=>null));
            $_POST['salt'] = CommonUtil::genernateNonceStr(8);
           if(isset($_POST['auths'])){
               $_POST['auths']=json_encode($_POST['auths']);
           }
            $_POST['password']=md5($_POST['password'].$_POST['salt']);
            $_POST['store_id']=$_POST['id'];
            unset($_POST['id']);

            //var_dump($merchant);
            if(!$db->create($_POST)){
                //var_dump($merchant->getError());
                $this->ajaxReturn(array("result"=>"error","message"=>"fail","data"=>$db->getError()));
            }else{
                $db->add();
                $this->ajaxReturn(array("result"=>"success","message"=>"添加成功！","data"=>"success"));
            }
        }
    }
    private function validate(){
        if((trim($_POST['nickname'])==""))
            $this->ajaxReturn(array("result"=>"error","message"=>"员工名不能为空","data"=>null));
        if(isset($_POST['password'])&&(strlen($_POST['password'])<4))
            $this->ajaxReturn(array("result"=>"error","message"=>"密码长度不能小于4","data"=>null));
        if(isset($_POST['password'])&&isset($_POST['repassword'])&&(!($_POST['password']==$_POST['repassword'])))
            $this->ajaxReturn(array("result"=>"error","message"=>"确认密码不一致","data"=>null));
        if((trim($_POST['username'])=="")||(strlen($_POST['username'])<6))
            $this->ajaxReturn(array("result"=>"error","message"=>"帐号名称长度不能小于6","data"=>null));
    }
    public function edit(){

        if($_SERVER['REQUEST_METHOD']=="POST"){

            if(isset($_POST['auths'])){
                $_POST['auths']=json_encode($_POST['auths']);
            }
            if(isset($_POST['password'])&&(!(trim($_POST['password'])==""))){
                $_POST['password']=md5($_POST['password'].$_POST['salt']);
            }else{
                unset($_POST['password']);
            }
            $this->validate();
            $db = M('staff');

            //var_dump($merchant);
            if(!$db->create($_POST)){
                //var_dump($merchant->getError());
                $this->ajaxReturn(array("result"=>0,"message"=>"fail","data"=>$db->getError()));
            }else{
                $db->save();
                $this->ajaxReturn(array("result"=>"success","message"=>"编辑成功！","data"=>"success"));
            }
        }
    }

    public function delete(){
            if($_POST['mid']>0&&$_POST['id']>0) {
                if(!($_SESSION['loginMerchant']['id']==$_POST['mid']))
                    $this->ajaxReturn(array("result" => "error", "message" => "无权删除", "data" => null));
                M('staff')->delete($_POST['id']);
                $this->ajaxReturn(array("result" => "success", "message" => "删除成功", "data" => null));
            }else{
                $this->ajaxReturn(array("result" => "error", "message" => "删除失败", "data" => null));
            }
    }


}