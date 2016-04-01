<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/25
 * Time: 10:25
 */

namespace Util;


use Think\Controller;

class AuthController extends BaseAuthController
{
    public function __construct() {
        parent::__construct();
        $auths=array();
        if(isset($_SESSION['loginStaff'])){
            $auths=json_decode($_SESSION['loginStaff']['auths'],true);
        }
        $no_auths=AuthConfig::$no_auth;

        $current_auth=CONTROLLER_NAME."-".ACTION_NAME;

        //&&(!in_array($current_auth,AuthConfig::$no_auth))

        if((!in_array($current_auth,$auths))&&(!in_array($current_auth,$no_auths))&&(!empty($_SESSION['loginMerchant']['pretend']))){
            if(IS_AJAX){
                $this->ajaxReturn(array('result'=>"error",'message'=>"无权限访问",'data'=>"请联系管理员"));
            }
            $this->display('Error:403');

            exit();
        }
    }
}