<?php
namespace Util;
/**
 * Created by lwt.
 * User: Administrator
 * Date: 2016/3/18
 * Time: 8:42
 */
class CommonUtil
{
    public static function genernateNonceStr($num){
        $str = "abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789";
        $len = strlen($str);
        $nonceStr = "";
        for ( $i=0; $i<$num; $i++){
            $index = rand(0, $len-1);
            $nonceStr .= $str[$index];
        }
        return $nonceStr;
    }
    public static function genernateNumNonceStr($num){
        $str = "0123456789";
        $len = strlen($str);
        $nonceStr = "";
        for ( $i=0; $i<$num; $i++){
            $index = rand(0, $len-1);
            $nonceStr .= $str[$index];
        }
        return $nonceStr;
    }
    public static function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                   CommonUtil::deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
    /*public static function uploadCert($file,$path){
        var_dump($file);
        $path = 'upload'.$path.'/';
        var_dump($path);
        if(!is_dir($path)){
            mkdir($path);
        }
        if (($file["size"] < 20000))
        {
            if ($file["error"] < 0)
            {

                if (file_exists($path . $file["name"]))
                {
                    unlink($path.$file['name']);
                }
                else
                {
                    move_uploaded_file($file["tmp_name"],
                        $path . $file["name"]);
                    return $path . $file["name"];
                }
            }
        }

    }*/

    public static function upload($file,$savePath){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小

        $upload->rootPath  =      './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =      $savePath;
        // 上传单个文件
        $info   =   $upload->uploadOne($_FILES[$file]);
        if(!$info) {// 上传错误提示错误信息
           //echo $upload->getError();
        }else{// 上传成功 获取上传文件信息
            return $info['savepath'].$info['savename'];
        }
    }

    public static function getMerchantConfig($mid){
        $merchantDB = M('merchant');
        $payconfigDB = M('payconfig');
        $merchant = $merchantDB -> field(array('id','pid')) -> where("id = '%s'",array($mid)) -> find();


        if(empty($merchant)) {
            return null;
        }

        if(isset($merchant['pid'])) {
            $payconfig = $payconfigDB -> field(array('config')) -> where("mid = '%s'",array($merchant['id'])) -> find();
            $wxpayconfig = json_decode($payconfig['config'], true)['weixin'];
            if($merchant['pid']==0){
                return $wxpayconfig;
            }else {
                $pmerchant = $merchantDB->field(array('id', 'pid'))->where("id = '%s'", array($merchant['pid']))->find();
                $ppayconfig = $payconfigDB -> field(array('config')) -> where("mid = '%s'",array($pmerchant['id'])) -> find();
                $subpayconfig = $wxpayconfig;
                $wxpayconfig = json_decode($ppayconfig['config'], true)['weixin'];
                if ((!empty($subpayconfig['appid'])) && (!(trim($subpayconfig['appid'] == "")))) {
                    $wxpayconfig['subopenid'] = $subpayconfig['appid'];
                }
                if ((!empty($subpayconfig['mchid'])) && (!(trim($subpayconfig['mchid'] == "")))) {
                    $wxpayconfig['submchid'] = $subpayconfig['mchid'];
                }
                return $wxpayconfig;
            }
        }
        return null;
    }

    public static function httpRequest($url, $method = 'GET', $postData = array())
    {
        $data = '';

        if (!empty($url)) {
            try {
                $ch = curl_init();

                if(strpos($url,"https:")>=0){
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30); //30秒超时
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
                if (strtoupper($method) == 'POST') {
                    $curlPost = is_array($postData) ? http_build_query($postData) : $postData;
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
                }
                $data = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $e) {
                $data = null;
            }
        }
        return $data;
    }
    public static function wxHttpsRequest($url, $data = null ,$isfile = false) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        $errorno = curl_errno($curl);
        curl_close($curl);
        if ($errorno) {
            return array('curl' => false, 'errorno' => $errorno);
        } else {
            $res = json_decode($output, 1);
            if ($res['errcode']) {
                return array('errcode' => $res['errcode'], 'errmsg' => $res['errmsg']);
            } else {
                return $res;
            }
        }
    }

    public static function  curl_post_ssl($url, $data, $wxconfig,$second=30,$aHeader=array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        //以下两种方式需选择一种

        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,APP_PATH.'Uploads/'.$wxconfig['apiclient_cert']);
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,APP_PATH.'Uploads/'.$wxconfig['apiclient_key']);
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_CAINFO,APP_PATH.'Uploads/'.$wxconfig['rootca']);

        //第二种方式，两个文件合成一个.pem文件
        //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $result = curl_exec($ch);
        if( $result ){
            curl_close($ch);
            return  $result ;
        }
        else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return array("error"=>$error);
        }
    }

    public static function createOrderNo(){
        return date("YmdHis",time()).self::genernateNumNonceStr(10);
    }

    /**
    把用户输入的文本转义（主要针对特殊符号和emoji表情）
     */
    public static function userTextEncode($str){
        if(!is_string($str))return $str;
        if(!$str || $str=='undefined')return '';

        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
            return addslashes($str[0]);
        },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        return json_decode($text);
    }
    /**
    解码上面的转义
     */
    public static function userTextDecode($str){
        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text); //将两条斜杠变成一条，其他不动
        return json_decode($text);
    }

    public static function createSign($arr,$key){
        $str="";
        ksort($arr);
        foreach($arr as $k=>$v){
            if(empty($v)||trim($v)=="")
                break;
            $str = $str.$k."=".$v."&";
        }
        $str = $str."key=".$key;
        return strtoupper(md5($str));
    }

    public static function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    public static function arrToXml($arr)
    {
        $xml = "<xml>";
       foreach($arr as $k=>$v){

            $xml .= "<".$k.">".$v."</".$k.">";
       }
        $xml .= "</xml>";
        return $xml;
    }
    public static function muban($openid,$detail_url,$pay_type,$order_no,$total_fee){
        $wechat = new WeChatUtil(CommonUtil::getMerchantConfig(5),5);
        $access_token = $wechat->accessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $tpl=array(
            "touser"=>$openid,
            "template_id"=>"6uHqj6b4NM1Y5uz17thbLBHT1MaiFSZTnW_hWP4jy44",
            "url"=>$detail_url,
            "topcolor"=>"#FF0000",
            "data"=>array(
                "first"=>array(
                    "value"=>"订单支付提醒",
                    "color"=>"#173177"),
                "keyword1"=>array(
                    "value"=>"$pay_type",
                    "color"=>"#173177"),
                "keyword2"=>array(
                    "value"=>$order_no,
                    "color"=>"#173177"),
                "keyword3"=>array(
                    "value"=>($total_fee/100)."元",
                    "color"=>"#173177"),
                "keyword4"=>array(
                    "value"=>date('Y-m-d H:i:s'),
                    "color"=>"#173177"),
                "remark"=>array(
                    "value"=>"点击进入后台查询订单流水情况。",
                    "color"=>"#173177")
            )
        );
CommonUtil::httpRequest($url,"POST",json_encode($tpl));
    }
}

