<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/21
 * Time: 16:35
 */
namespace Home\Controller;

use Think\Controller;
use Util\CommonUtil;
class PayContrller extends Controller
{
    private $MerchantDB;
    public function __construct()
    {
        parent::__construct();
        $this->MerchantDB = M('cashier_payconfig');
    }

    public function autopay()
    {

        $mid = intval(trim($_GET['mid']));

        if (!(0 < $mid)) {
            $this->error('参数出错，没有商家ID！');
            exit();
        }

        $commonUtil = new CommonUtil();
        $wxconfig =$commonUtil::getMerchantConfig($mid);
        if(empty($wxconfig))
            exit("获取支付配置失败");


        if ($this->is_wexin_browser && empty($GLOBALS['_SESSION']['openid'])) {
            $redirecturl = $this->SiteUrl . '/' . $_SERVER['REQUEST_URI'];
            $retrunarr = $wxCardPack->authorize_openid($redirecturl);

        }

        $ordid = trim($_GET['oid']);

        if (!empty($ordid)) {
            $orderInfo = M('cashier_order')->getOneOrder(array('id' => $ordid, 'mid' => $mid));
        }
        else {
            $orderInfo = array();
        }

        $wxuserinfo = array();
        if ($this->is_wexin_browser && !empty($GLOBALS['_SESSION']['openid'])) {
            $access_token = $wxCardPack->getToken();
            $wxuserinfo = $wxCardPack->GetwxUserInfoByOpenid($access_token, $GLOBALS['_SESSION']['openid']);
            $this->fanSave($wxuserinfo, $mid);
        }

        include $this->showTpl();
    }


}