<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/25
 * Time: 10:25
 */

namespace Util;


use Think\Controller;

class BaseAuthController extends Controller
{
    public function __construct() {
        parent::__construct();
        if(empty($_SESSION['loginStaff'])&&empty($_SESSION['loginMerchant'])){
           
           $this->redirect("/Home/Index/login");
        }
    }
}