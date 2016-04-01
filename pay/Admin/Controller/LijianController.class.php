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
}