<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
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