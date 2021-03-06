<?php
/**
 * Created by lwt.
 * User: Administrator
 * Date: 2016/3/16
 * Time: 14:06
 */

namespace Admin\Controller;

use Think\Controller;
use Util\AddressUtil;
use Util\AuthController;
use Util\BaseAuthController;
use Util\CommonUtil;

class MerchantController extends BaseAuthController
{
  /*  public function merchants(){
    //limit=10&offset=10&search=1&sort=id&order=desc
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $pager=[];
            $data=[['id'=>1, 'name'=>'hahah'],['id'=>2, 'name'=>'hahah1']];
            $offset = $_POST['offset'];
            if($offset==0)
                $pager['rows']=[['id'=>1, 'name'=>'hahah'],['id'=>2, 'name'=>'hahah1']];
            if($offset==10)
                $pager['rows']=[['id'=>3, 'name'=>'hahah'],['id'=>4, 'name'=>'hahah1']];


            $pager['total']=25;

            $this->ajaxReturn($pager);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $this->display();
        }
    }*/

    public function payconfig(){

        $mid=$_SESSION['loginMerchant']['id'];
        $data = S("config:".$mid);
        if(!$data){
            var_dump($mid);
            $payconfig = M('payconfig');
            $data = $payconfig->where("mid = '%s'",array($mid))->find();
            S("config:".$mid,$data);
            }
        //var_dump(json_decode($data['config'],true));

        $this->assign('payconfig',$data);
        $this->assign('config',json_decode($data['config'],true));
        $this->display();
    }
    public function saveconfig(){


        $config=array();
        $mid=$_POST['mid'];

        if ((!isset($_POST['mid']))||(!($_POST['mid']==$_SESSION['loginMerchant']['id']))) {
            $merchant = M('Merchant')->field(array('pid','parent_id'))->where("id='%s'", array($_POST['mid']))->find();
            if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid']))&&(!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                $this->error("无权修改","payconfig");
            }
        }
      //  $mid=$_SESSION['loginMerchant']['id'];

        $path = 'cert/'.$mid.'/';

        if(isset($_POST['type'])&&$_POST['type']=='weixin'){
            $config[$_POST['type']]=array(
                'appid'=>$_POST['appid'],
                'appsecret'=>$_POST['appsecret'],
                'mchid'=>$_POST['mchid'],
                'apikey'=>$_POST['apikey'],
                'domain'=>$_POST['domain'],
                'apiclient_cert'=>CommonUtil::upload('apiclient_cert', $path),
                'apiclient_key'=> CommonUtil::upload('apiclient_key', $path),
                'rootca'=> CommonUtil::upload('rootca', $path)
            );
            $payconfigModel = M('payconfig');
            $payconfig = $payconfigModel->where("mid=$mid")->limit(0,1)->find();
            $wx_status = $_POST['mchid']==""?0:1;
            if($payconfig){
                //把之前的证书数据赋值给提交过来的表单配置
                $config2=json_decode($payconfig['config'],true);
                if($config[$_POST['type']][ 'apiclient_cert']==null){
                    $config[$_POST['type']][ 'apiclient_cert']=$config2[$_POST['type']][ 'apiclient_cert'];
                }
                if($config[$_POST['type']][ 'apiclient_key']==null){
                    $config[$_POST['type']][ 'apiclient_key']=$config2[$_POST['type']][ 'apiclient_key'];
                }
                if($config[$_POST['type']][ 'rootca']==null){
                    $config[$_POST['type']][ 'rootca']=$config2[$_POST['type']][ 'rootca'];
                }
                //----------------------------------------

                $payconfig['config']=json_encode($config);
                $payconfig['wx_status']=$wx_status;
                //var_dump($payconfig);
                $payconfigModel->where("id='%s'",array( $payconfig['id']))->save($payconfig);
            }else{

                $payconfig_data=array(
                    'config'=>json_encode($config),
                    'mid'=>$mid,
                    'wx_status'=>$wx_status
                );
                $payconfigModel->add($payconfig_data);
            }
            //保存后清除缓存
            $data = S("config:".$mid,null);
        }



        $this->success('修改成功','payconfig');
    }

    public function submerchants(){
        //limit=10&offset=10&search=1&sort=id&order=desc

        if($_SERVER['REQUEST_METHOD']=="POST"){

            $db = M('merchant');
            $condition="1=1";
            if(isset($_POST['pid'])&&(!(trim($_POST['pid'])=="")))
                $condition.=" AND pid=".$_POST['pid'];
            if(isset($_POST['parent_id'])&&(!(trim($_POST['parent_id'])==""))) {
                $condition .= " AND (parent_id=" . $_POST['parent_id'];
                $condition .= " OR id=" . $_POST['parent_id'].")";
            }
            $pager=array();
                $pager['total']=$db->where($condition)->count();
                $pager['rows']=$db->field(array("id",'username','merchantname','email','pid','salt','parent_id','province','city','address','phone','industry','pageinfo_status'))->where($condition)->order($_POST['sort'])->limit($_POST['offset'].",".$_POST['limit'])->select();

                for($i=0;$i<count($pager['rows']);$i++){
                        if($pager['rows'][$i]['pid']==0) {
                            $pager['rows'][$i]['type'] ="服务商";
                        }
                        if(($pager['rows'][$i]['pid']>0)&&($pager['rows'][$i]['parent_id']==0)) {
                            $pager['rows'][$i]['type'] ="代理商";

                        }
                        if(($pager['rows'][$i]['pid']>0)&&($pager['rows'][$i]['parent_id']>0)){
                            $pager['rows'][$i]['type'] = "下级商户";
                        }
                    $pager['rows'][$i]['proId']=AddressUtil::getProID($pager['rows'][$i]['province']);
                    $pager['rows'][$i]['cityId']=AddressUtil::getCityID($pager['rows'][$i]['city']);
                    }
            $this->ajaxReturn($pager);

        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            if(!isset($_GET['id'])){
                if($_SESSION['loginMerchant']['is_proxy']==1) {
                    $this->assign("pid",$_SESSION['loginMerchant']['id']);
                    $this->assign("parent_id", 0);
                }else{
                    $this->assign("pid",$_SESSION['loginMerchant']['pid']);
                    $this->assign("parent_id",$_SESSION['loginMerchant']['id']);
                }
            }else{
                //TODO 验证
                if(!($_SESSION['loginMerchant']['id'] == $_GET['id'])) {
                    $merchant = M('Merchant')->field(array('pid', 'parent_id'))->where("id='%s'", array($_GET['id']))->find();
                    if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid'])) && (!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                        $this->display("Error:403");
                        exit();
                    }
                }
                $this->assign("pid",$_GET['pid']);
                $this->assign("parent_id",$_GET['id']);
            }

            $address=AddressUtil::init();
            $this->assign('address',json_encode($address));
            $this->display();
        }
    }

    public function submerchantStore(){
        //limit=10&offset=10&search=1&sort=id&order=desc

        if($_SERVER['REQUEST_METHOD']=="POST"){

            $db = M('merchant');
            $condition="1=1";
            if(isset($_POST['pid'])&&(!(trim($_POST['pid'])=="")))
                $condition.=" AND pid=".$_POST['pid'];
            if(isset($_POST['parent_id'])&&(!(trim($_POST['parent_id'])==""))) {
                $condition .= " AND (parent_id=" . $_POST['parent_id'];
                $condition .= " OR id=" . $_POST['parent_id'].")";
            }
            if(isset($_POST['merchantname'])&&(!(trim($_POST['merchantname'])=="")))
                $condition.=" AND merchantname like '%".$_POST['merchantname']."%'";
            if(isset($_POST['contact'])&&(!(trim($_POST['contact'])=="")))
                $condition.=" AND contact like '%".$_POST['contact']."%'";
            $pager=array();
            $pager['total']=$db->where($condition)->count();
            $pager['rows']=$db->field(array("id",'username','merchantname','email','pid','salt','parent_id','province','city','address','phone'))->where($condition)->order($_POST['sort'])->limit($_POST['offset'].",".$_POST['limit'])->select();

            for($i=0;$i<count($pager['rows']);$i++){
                if($pager['rows'][$i]['pid']==0) {
                    $pager['rows'][$i]['type'] ="服务商";
                }
                if(($pager['rows'][$i]['pid']>0)&&($pager['rows'][$i]['parent_id']==0)) {
                    $pager['rows'][$i]['type'] ="代理商";

                }
                if(($pager['rows'][$i]['pid']>0)&&($pager['rows'][$i]['parent_id']>0)){
                    $pager['rows'][$i]['type'] = "下级商户";
                }

            }
            $this->ajaxReturn($pager);

        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            if(!isset($_GET['id'])){
                if($_SESSION['loginMerchant']['is_proxy']==1) {
                    $this->assign("pid",$_SESSION['loginMerchant']['id']);
                    $this->assign("parent_id", 0);
                }else{
                    $this->assign("pid",$_SESSION['loginMerchant']['pid']);
                    $this->assign("parent_id",$_SESSION['loginMerchant']['id']);
                }
            }else{
                //TODO 验证
                if(!($_SESSION['loginMerchant']['id'] == $_GET['id'])) {
                    $merchant = M('Merchant')->field(array('pid', 'parent_id'))->where("id='%s'", array($_GET['id']))->find();
                    if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid'])) && (!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                        $this->display("Error:403");
                        exit();
                    }
                }
                $this->assign("pid",$_GET['pid']);
                $this->assign("parent_id",$_GET['id']);
            }
            $this->display();
        }
    }
    public function add(){

        if($_SERVER['REQUEST_METHOD']=="POST"){
            $merchant = new \Admin\Model\MerchantModel();
            $account = json_decode(file_get_contents("account.txt"),true);
            $account['username']+=1;
            $username = $account['username'];
            $_POST['username']=$username;
            $_POST['password']=$account['password'];
            $_POST['repassword']="123456";
            $_POST['salt'] = CommonUtil::genernateNonceStr(8);
            //var_dump($merchant);
            if(!$merchant->create($_POST)){
                //var_dump($merchant->getError());
                $this->ajaxReturn(array("result"=>"error","message"=>"添加失败","data"=>$merchant->getError()));
            }else{
                $merchant->add();
                file_put_contents("account.txt",json_encode($account));
                $this->ajaxReturn(array("result"=>"success","message"=>"添加成功","data"=>""));
            }
        }
    }
    public function edit(){
        if ((!($_POST['id']==$_SESSION['loginMerchant']['id']))) {
            $merchant = M('Merchant')->field(array('pid','parent_id'))->where("id='%s'", array($_POST['id']))->find();
            if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid']))&&(!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                $this->ajaxReturn(array("result" =>"error", "message" => "无权查看", "data" => null));
            }
        }

        if($_SERVER['REQUEST_METHOD']=="POST"){

            if(trim($_POST['password']=="")){
                unset($_POST['password']);
            }else{
                if((!($_POST['password']==$_POST['repassword'])))
                    $this->ajaxReturn(array("result"=>"error","message"=>"2次密码不一致","data"=>$_POST['password']==$_POST['repassword']));
                $_POST['password']=md5($_POST['password'].$_POST['salt']);
            }

            $merchant = M('merchant');
            //var_dump($merchant);
            if(!$merchant->create($_POST)){
                //var_dump($merchant->getError());
                $this->ajaxReturn(array("result"=>"error","message"=>"fail","data"=>$merchant->getError()));
            }else{
                $merchant->save();
                $this->ajaxReturn(array("result"=>"success","message"=>"修改成功","data"=>""));
            }
        }
    }

    public function editPayconfig(){
        if($_SERVER['REQUEST_METHOD']=="POST") {
            $config = array();
            $mid = $_POST['mid'];

            if ((!isset($_POST['mid']))||(!($_POST['mid']==$_SESSION['loginMerchant']['id']))) {
                $merchant = M('Merchant')->field(array('pid','parent_id'))->where("id='%s'", array($_POST['mid']))->find();
                if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid']))&&(!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                    $this->ajaxReturn(array("result" => "error", "message" => "无权修改", "data" => $merchant));
                }
            }
            if (isset($_POST['type']) &&( $_POST['type'] == 'weixin')) {
                $config[$_POST['type']] = array(
                    'appid' => $_POST['appid'],
                    'mchid' => $_POST['mchid']
                );
                $db = M('payconfig');
                $payconfig = $db->where("mid=$mid")->find();
                $wx_status = $_POST['mchid'] == "" ? 0 : 1;
                if ($payconfig) {
                    $payconfig['config']=json_encode($config);
                    $payconfig['wx_status']=$wx_status;

                    $db->where("id='%s'", array($payconfig['id']))->save($payconfig);
                    $this->ajaxReturn(array("result" => "success", "message" => "修改成功", "data" =>""));
                } else {
                    $payconfig = array(
                        'config' => json_encode($config),
                        'mid' => $mid,
                        'wx_status' => $wx_status
                    );
                    $db->add($payconfig);
                }
                $this->ajaxReturn(array("result" => "success", "message" => "修改成功", "data" => null));
            }
        }else{
            //get 查询
            $db = M('payconfig');
            $mid = $_GET['mid'];
            $payconfig = $db->where("mid=$mid")->find();
            if($payconfig){
                $configData = json_decode($payconfig['config'],true);
                $configData['weixin']['mid']=$mid;

                $this->ajaxReturn(array("result" => "success", "message" => "查询成功", "data" =>$configData['weixin']));
            }else{
                $payconfig['weixin']['mid']=$mid;
                $this->ajaxReturn(array("result" =>"success", "message" => "无记录", "data" =>$payconfig['weixin']));
            }
        }
        $this->ajaxReturn(array("result" => "error", "message" => "失败", "data" => null));
    }

    public function delete(){
        if ((!($_POST['mid']==$_SESSION['loginMerchant']['id']))) {
            $merchant = M('Merchant')->field(array('pid','parent_id'))->where("id='%s'", array($_POST['mid']))->find();
            if ((!($_SESSION['loginMerchant']['id'] == $merchant['pid']))&&(!($_SESSION['loginMerchant']['id'] == $merchant['parent_id']))) {
                $this->ajaxReturn(array("result" => 0, "message" => "无权查看", "data" => null));
            }
        }
        M('merchant')->where("id='%s'",array($_POST['mid']))->delete();
        $this->ajaxReturn(array("result" => "success", "message" => "删除成功", "data" => null));
    }
}