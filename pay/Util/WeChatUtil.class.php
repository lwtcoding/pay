<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/21
 * Time: 17:06
 */

namespace Util;


class WeChatUtil
{

    public $wxconfig = '';
    private $_mid = '';
    public $error = array();

    function __construct($wxconfig,$mid=0) {
        $this->wxconfig = $wxconfig;
        $this->_mid=$mid;
    }

    public function authorize_openid($redirecturl) {
        if (empty($_GET['code'])) {
            $_SESSION['weixinstate'] = md5(uniqid());
            $oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->wxconfig['appid'] . '&redirect_uri=' . urlencode($redirecturl) . '&response_type=code&scope=snsapi_base&state=' . $_SESSION['weixinstate'] . '#wechat_redirect';

            header('Location: ' . $oauthUrl);
            exit;
        } else if (isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['weixinstate'])) {
            unset($_SESSION['weixin']);
            $jsonrt = $this->https_request('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->wxconfig['appid'] . '&secret=' . $this->wxconfig['appSecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code');

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

        //获取token
         public function getToken() {

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

        public function http($url, $method, $postfields = null, $headers = array(), $debug = false)
        {
            $ci = curl_init();
            /* Curl settings */
            curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ci, CURLOPT_TIMEOUT, 30);
            curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST,1);
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER,false);




            switch ($method) {
                case 'POST':
                    curl_setopt($ci, CURLOPT_POST, true);
                    if (!empty($postfields)) {
                        curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                        $this->postdata = $postfields;
                    }
                    break;
            }
            curl_setopt($ci, CURLOPT_URL, $url);
            curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ci, CURLINFO_HEADER_OUT, true);

            $response = curl_exec($ci);
            $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);

            if ($debug) {
                echo "=====post data======\r\n";
                var_dump($postfields);

                echo '=====info=====' . "\r\n";
                print_r(curl_getinfo($ci));

                echo '=====$response=====' . "\r\n";
                print_r($response);
            }
            curl_close($ci);
            return array($http_code, $response);
        }
        function curlpost($curlPost, $url) //curl post
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }

        function curlget($url) //curl
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);

            $data = curl_exec($ch);

            curl_close($ch);
            return $data;
        }


        function get_utf8_string($content)
        {

            $encoding = mb_detect_encoding($content, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            return mb_convert_encoding($content, 'utf-8', $encoding);
        }

}