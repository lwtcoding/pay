<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/21
 * Time: 17:06
 */

namespace Util;

use Util\CommonUtil;
use Util\UnifiedOrder;
class WeChatUtil
{

    private $wxconfig = '';
    private $_mid = '';
    private $error = array();

    function __construct($wxconfig,$mid=0) {
        $this->wxconfig = $wxconfig;
        $this->_mid=$mid;
    }

    public function authorize_openid($redirecturl) {
        if (empty($_GET['code'])) {
            $oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->wxconfig['appid'] . '&redirect_uri=' . urlencode($redirecturl) . '&response_type=code&scope=snsapi_base&state=wxpay#wechat_redirect';
            header('Location: ' . $oauthUrl);
            exit;
        } else if (isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] =="wxpay")) {

            $jsonrt = json_decode(CommonUtil::httpRequest('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->wxconfig['appid'] . '&secret=' . $this->wxconfig['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code'),true);
            if ($jsonrt['errcode'] || empty($jsonrt['openid'])) {
                return array('error' => 1, 'msg' => '授权发生错误：' . $jsonrt['errcode']);
            }
            if ($jsonrt['openid']) {
                $_SESSION['openid'] = $jsonrt['openid'];
                return array('error' => 0, 'openid' => $jsonrt['openid']);
            }
        } else {
            return array('error' => 2);
        }
    }


    public function accessToken() {

        $data = json_decode(S('access_token'.$this->_mid));
        if ($data->expire_time < time() or !$data->expire_time) {

            $appid = $this->wxconfig['appid'];
            $appsecret = $this->wxconfig['appsecret'];
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
            $res = json_decode(CommonUtil::httpRequest($url),true);
            $access_token = $res['access_token'];
            if($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                S('access_token'.$this->_mid, json_encode($data));
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }

    public function userinfo($access_token, $openid) {

        $data = json_decode(S('access_token'.$this->_mid));

            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
            $res = json_decode(CommonUtil::httpRequest($url),true);
            if($res['errcode']){
                echo $res['errmsg'];
                return null;
            }
          return $res;


    }

    public function unifiedorder($order,$wxconfig){
        $unifiedOrder = new UnifiedOrder($wxconfig['appid'],$wxconfig['mchid'],$wxconfig['apikey']);
        if(!empty($wxconfig['submchid']))
        $unifiedOrder->setParameter("sub_mch_id",$wxconfig['submchid']);
        $unifiedOrder->setParameter("nonce_str",CommonUtil::genernateNonceStr(32));
        $unifiedOrder->setParameter("body",$order['goods_name']);
        $unifiedOrder->setParameter("out_trade_no",$order['order_no']);
        $unifiedOrder->setParameter("total_fee",$order['total_fee']);
        $unifiedOrder->setParameter("spbill_create_ip",$this->get_real_ip());
        $unifiedOrder->setParameter("notify_url","http://".$wxconfig['domain']."/index.php/Home/Pay/paySuccess");
        $unifiedOrder->setParameter("trade_type",$order['pay_type']);
        $unifiedOrder->setParameter("openid",$order['openid']);
        $unifiedOrder->setParameter("sign", $unifiedOrder->createSign());
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";

        $resp = $unifiedOrder->xmlToArray(CommonUtil::httpRequest($url,"POST",$unifiedOrder->getXmlData()));
        return $resp;
    }

    public function get_real_ip(){
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
        public function valid()
        {

            $echoStr = $_GET["echostr"];
            if ($this->checkSignature()) {
                echo $echoStr;
                exit;
            }
        }

        private function checkSignature()
        {
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];

            $token = $this->token;
            $tmpArr = array($token, $timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);

            if ($tmpStr == $signature) {
                return true;
            } else {
                return false;
            }
        }
        /*     * *****************************************************
            *      微信门店：获取门店列表
            * ***************************************************** */

        public function wxGetPoiList($wxAccessToken) {
            $url = "https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token=" . $wxAccessToken;
            $result =  CommonUtil::httpRequest($url,"POST",'{"begin":0,"limit":50}');
            $result= json_decode($result,true);
            return $result;
        }
        public function wxCardColor($wxAccessToken) {
            $wxColorsList = S('Cache_wxColorsList');
            if (!empty($wxColorsList) && is_array($wxColorsList)) {
                return $wxColorsList;
            }
            $url = "https://api.weixin.qq.com/card/getcolors?access_token=" . $wxAccessToken;
            $result = json_decode(CommonUtil::httpRequest($url),true);
            if (isset($result['colors']) && !empty($result['colors'])) {
                S('Cache_wxColorsList', $result['colors']);
                return $result['colors'];
            }
            return array(
                Array('name' => 'Color010', 'value' => '#63b359'), Array('name' => 'Color020', 'value' => '#2c9f67'),
                Array('name' => 'Color030', 'value' => '#509fc9'), Array('name' => 'Color040', 'value' => '#5885cf'),
                Array('name' => 'Color050', 'value' => '#9062c0'), Array('name' => 'Color060', 'value' => '#d09a45'),
                Array('name' => 'Color070', 'value' => '#e4b138'), Array('name' => 'Color080', 'value' => '#ee903c'),
                Array('name' => 'Color081', 'value' => '#f08500'), Array('name' => 'Color082', 'value' => '#a9d92d'),
                Array('name' => 'Color090', 'value' => '#dd6549'), Array('name' => 'Color100', 'value' => '#cc463d'),
                Array('name' => 'Color101', 'value' => '#cf3e36'), Array('name' => 'Color102', 'value' => '#5E6671'),
            );
        }
        /*     * *****************************************************
         *      微信卡券：上传LOGO - 需要改写动态功能
         * ***************************************************** */
        public function wxCardUpdateImg($wxAccessToken, $imgpath) {
            //$data['buffer']     =  '@D:\\workspace\\htdocs\\yky_test\\logo.jpg';

            $fields['media'] =new \CURLFile(realpath($imgpath));
            $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=" . $wxAccessToken;
            //$result = $this->https_request($url,$data,$header);
            $result =  CommonUtil::wxHttpsRequest($url, $fields, true);
            return $result;
        }
        public function wxCardCreated($wxAccessToken, $jsonData) {
            $url = "https://api.weixin.qq.com/card/create?access_token=" . $wxAccessToken;
            $result =  CommonUtil::httpRequest($url,"POST", $jsonData);
            return json_decode($result,true);
        }
    public function wxCardQrCodeTicket($wxAccessToken, $jsonData) {
        $url = "https://api.weixin.qq.com/card/qrcode/create?access_token=" . $wxAccessToken;
        $result =  CommonUtil::httpRequest($url,"POST", $jsonData);
        return json_decode($result,true);
    }
        function get_utf8_string($content)
        {

            $encoding = mb_detect_encoding($content, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            return mb_convert_encoding($content, 'utf-8', $encoding);
        }

}