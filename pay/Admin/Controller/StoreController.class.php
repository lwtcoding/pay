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
            $condition .= " AND mid=" . $_SESSION['loginMerchant']['id'];
            if(isset($_POST['name'])&&(!(trim($_POST['name'])=="")))
                $condition.=" AND name like '%".$_POST['name']."%'";

            $pager['total'] = $db->where($condition)->count();
            $pager['rows'] = $db->where($condition)->order(($_POST['sort']==''?'id':$_POST['sort']).' '.$_POST['order'])->limit($_POST['offset'] . "," . $_POST['limit'])->select();
            $this->ajaxReturn($pager);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $this->assign('auths',AuthConfig::$auth);
            $this->display();
        }
    }
    public function add(){
        $db = M('store');
        $_POST['salt']=CommonUtil::genernateNonceStr(12);

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