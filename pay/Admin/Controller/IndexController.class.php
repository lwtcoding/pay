<?php
namespace Admin\Controller;
use Think\Controller;
use Util\BaseAuthController;

class IndexController extends BaseAuthController {
    public function index(){
        $this->assign('merchant',$_SESSION['loginMerchant']);
       $this->display();
    }

    public function left(){
        $this->assign('merchant',$_SESSION['loginMerchant']);
        $this->display();
    }

    public function body(){
        $this->display();
    }

    public function foot(){
        $this->display();
    }
}