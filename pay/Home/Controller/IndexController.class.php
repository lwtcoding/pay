<?php
namespace Home\Controller;
use Think\Controller;
use Util\CommonUtil;
class IndexController extends Controller {
    public function index(){

       $this->display();
    }
    public function login(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $merchant = M('merchant');
            $data=$merchant->where("username='%s'",array($_POST['username']))->find();
            $verify = new \Think\Verify();

           /*if(!$verify->check($_POST['verify'])){
               $this->error('验证码错误','login');
               exit();
           }*/

            if(md5($_POST['password'].$data['salt'])==$data['password']){
                $_SESSION['loginMerchant'] = $data;
                $this->success('登录成功',U("Admin/Index/index"));
                exit();
            }
           // $this->error('帐号或密码错误','login');
            $this->display();
        }else {
            $this->display();
        }
    }
    public function logout(){
            $_SESSION['loginMerchant']=null;
            $this->redirect('login');

    }
    public function register(){

        if($_SERVER['REQUEST_METHOD']=="POST"){
            $_POST['salt'] = CommonUtil::genernateNonceStr(8);

            $merchant = new \Admin\Model\MerchantModel();
            //var_dump($merchant);
           if(!$merchant->create($_POST)){
              var_dump($merchant->getError());
               $this->display();
           }else{
               $merchant->add();
               $this->success('注册成功','login');
           }
        }else {
            $this->display();
        }
    }

    public function verify(){
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }

}