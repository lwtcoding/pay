<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/28
 * Time: 15:41
 */

namespace Admin\Controller;


use Think\Controller;
use Util\BaseAuthController;
use Util\CommonUtil;

class PageinfoController extends BaseAuthController
{
    public function pageinfo(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            if(isset($_POST['id'])&&(!(trim($_POST['id'])==""))) {
                M('pageinfo')->save($_POST);
            }else{
                M('pageinfo')->add($_POST);
            }
            $this->redirect("pageinfo");
        }else{
            $pageinfo = M('pageinfo')->where("mid=".$_SESSION['loginMerchant']['id'])->find();
            $this->assign("pageinfo",$pageinfo);
            $this->display();
        }
    }

    public function upload(){

        $mid = $_SESSION['loginMerchant']['id'];
        $path = 'pageinfo/'.$mid.'/';
        CommonUtil::deldir("./Uploads/pageinfo/".$mid."/");
        $this->ajaxReturn(CommonUtil::upload("logo_temp",$path));
    }
}