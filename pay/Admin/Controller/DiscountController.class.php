<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/21
 * Time: 17:00
 */

namespace Admin\Controller;


use Think\Controller;
use Util\AuthController;
use Util\BaseAuthController;

class DiscountController extends AuthController
{
    public function discount(){
        $db=M('discount');
        $discount = $db->where("mid = '%s'",array($_SESSION['loginMerchant']['id']))->find();

        $this->assign("discount",$discount);
        //if 代理商
        if(($_SESSION['loginMerchant']['is_proxy']==0)&&($_SESSION['loginMerchant']['parent_id']==0)){
            $submerchants = M('merchant')->field(array('id','merchantname'))->where("parent_id=".$_SESSION['loginMerchant']['id'])->select();
            $this->assign("submerchants",$submerchants);
        }
        $this->display();
    }
    public function add(){

        if(is_numeric($_POST['discount'])&&($_POST['discount']>0)&&($_POST['discount']<=100)) {
            $db = M('discount');
            $discount = $db->where("mid = '%s'", array($_POST['mid']))->find();
            if (empty($discount)) {
                $db->add($_POST);
                S('discount' . $_POST['mid'], null);
            } else {
                $db->save($_POST);
                S('discount' . $_POST['mid'], null);
            }
            $this->success("保存成功", "discount");
        }else{
            $this->error("保存失败，折扣设置不正确", "discount");
        }
    }

    /**
     * 共享给子商户
     */
    public function share(){

        $db=M('discount');
        $shareDiscount=$db->field(array('discount','status'))->where("id = '%s'",array($_POST['id']))->find();
        if(isset($_POST['submids'])){
            for($i=0;$i<count($_POST['submids']);$i++){
                $shareDiscount['mid']=$_POST['submids'][$i];
                $discount = $db->field('id')->where("mid = '%s'",array($_POST['submids'][$i]))->find();
                if(empty($discount)){
                    $db->add($shareDiscount);
                    S('discount'.$_POST['submids'][$i],null);
                }else{
                    $db->where("id = '%s'",array($discount['id']))->save($shareDiscount);
                    S('discount'.$_POST['submids'][$i],null);
                }
            }
        }
        $this->ajaxReturn(array("result"=>"success","message"=>"配置成功!","data"=>null));
    }
}