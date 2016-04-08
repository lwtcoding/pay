<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/30
 * Time: 8:58
 */

namespace Admin\Controller;


use Util\AuthController;

class LijianController extends AuthController
{
    public function lijian(){
        $db=M('lijian');
        $lijian = $db->where("mid = '%s'",array($_SESSION['loginMerchant']['id']))->find();
        if(!empty($lijian)){
            $configs=json_decode($lijian['config'],true);
            for($i=0;$i<count($configs);$i++){
                if(is_numeric($configs[$i]['limit'])&&is_numeric($configs[$i]['reduce'])){
                    $configs[$i]['limit']=sprintf("%.2f", $configs[$i]['limit']/100);
                    $configs[$i]['reduce']=sprintf("%.2f", $configs[$i]['reduce']/100);
                }else{
                    $this->error("规则数值必须为数字","lijian");
                }
            }
            $this->assign("configs",$configs);
        }
        $this->assign("lijian",$lijian);
        //if 代理商
        if(($_SESSION['loginMerchant']['is_proxy']==0)&&($_SESSION['loginMerchant']['parent_id']==0)){
            $submerchants = M('merchant')->field(array('id','merchantname'))->where("parent_id=".$_SESSION['loginMerchant']['id'])->select();
            $this->assign("submerchants",$submerchants);
        }
        $this->display();
    }

    public function add(){
        $configs=$_POST['config'];
        for($i=0;$i<count($configs);$i++){
             if(is_numeric($configs[$i]['limit'])&&is_numeric($configs[$i]['reduce'])){
                 $configs[$i]['limit']=sprintf("%.2f", $configs[$i]['limit'])*100;
                 $configs[$i]['reduce']=sprintf("%.2f", $configs[$i]['reduce'])*100;
             }else{
                 $this->error("规则数值必须为数字","lijian");
             }
        }
        $_POST['config']=json_encode($configs);
       if(!($_POST['status']==1))
           $_POST['status']=0;

        $db=M('lijian');
        $lijian = $db->where("mid = '%s'",array($_POST['mid']))->find();
        if(empty($lijian)){
                $db->add($_POST);
                S('lijian'.$_POST['mid'],null);
        }else{
            $db->save($_POST);
            S('lijian'.$_POST['mid'],null);
        }
        $this->success("保存成功","lijian");
    }

    /**
     * 把立减规则共享给子商户
     */
    public function share(){

        $db=M('lijian');
        $shareLijian=$db->field(array('config','status'))->where("id = '%s'",array($_POST['id']))->find();
        if(isset($_POST['submids'])){
            for($i=0;$i<count($_POST['submids']);$i++){
                $shareLijian['mid']=$_POST['submids'][$i];
                $lijian = $db->field('id')->where("mid = '%s'",array($_POST['submids'][$i]))->find();
                if(empty($lijian)){
                    $db->add($shareLijian);
                    S('lijian'.$_POST['submids'][$i],null);
                }else{
                    $db->where("id = '%s'",array($lijian['id']))->save($shareLijian);
                    S('lijian'.$_POST['submids'][$i],null);
                }
            }
        }
        $this->ajaxReturn(array("result"=>"success","message"=>"配置成功!","data"=>null));
    }
}