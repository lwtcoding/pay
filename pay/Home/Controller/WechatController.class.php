<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/10
 * Time: 9:16
 */

namespace Home\Controller;

use Think\Controller;
use Wechat\Wechat;
use Wechat\CommonUtil;
class WechatController extends Controller
{
    public function index()
    {

        $w = new Wechat('dgyl', true);
        if (isset($_GET["echostr"])) {

            $w->valid();
        } else {

            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

            file_put_contents("post.txt", $postStr);
            $xml = simplexml_load_string($postStr);

            if ($xml->Event == "subscribe") {
                $commonUtil = new CommonUtil();
                $access_token = $commonUtil->accessToken();
                $user_info_url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$xml->FromUserName."&lang=zh_CN";
                $responseStr = $w->curlget($user_info_url);
                file_put_contents("post.txt", $responseStr);

                $textTpl = <<<eot
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image>
<MediaId><![CDATA[%s]]></MediaId>
</Image>
</xml>
eot;

                $text = vsprintf($textTpl, array($xml->FromUserName, $xml->ToUserName,
                    time(), 'thanks'));

                echo $text;
                exit();
            }
            else if( $xml->MsgType == "text"){
                $commonUtil = new CommonUtil();
                $access_token = $commonUtil->accessToken();
                $user_info_url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$xml->FromUserName."&lang=zh_CN";
                $responseJson = $w->curlget($user_info_url);
                file_put_contents("post.txt",  $user_info_url);
            }
        }
    }
    public function getImg(){

                $commonUtil = new CommonUtil();
                /*
                $access_token=$commonUtil->accessToken();


                $w = new Wechat();
                $a = $w->http( "https://api.weixin.qq.com/cgi-bin/user/info?access_token=Ly17TzgCbOBacq5rJWNEe0DNHhX0x5UNw5xGVI4EZlJVg1lDtnIyc3HXX00zNLkqMpA0p8SNexN4e4Onz20GxyNZ6ZsDXETC6gSe71LwQ4RLY92oPdRvcraHdh4esyKUVJLbAHAPDF&openid=ocMacs3nNC6aMryczG_eHO-v3Tz8&lang=zh_CN","GET");
                echo $a[0];
                echo json_decode( $a[1])->headimgurl;
                */
                //$newName = $commonUtil->getImg("http://wx.qlogo.cn/mmopen/icqWyfxQicZyBTukPXaiaxk6FwM4jJ1c7lkyB9dSBxmXAVb0o6db0HsI7tT4NSRzs2TcGGghVfZYtR5mGGmlKZyibaxXmWlAaRGG/0");
                //echo $newName;
                $commonUtil-> mkThumbnail('./upload/20160310034457.jpg', 100, 100, './upload/20160310034457_thumb.jpg');
    }

    public function img(){
        $m1 = imagecreatefromstring(file_get_contents("./upload/src.jpg"));
        $m2 = imagecreatefromstring(file_get_contents("./upload/20160310034457_thumb.jpg"));
        var_dump(imagecopy($m1,$m2, 137, 350, 0, 0, 100, 100));

        imagefttext($m1,10,0,137,462,0,"./upload/simsun.ttc","刘文滔");
        imagejpeg($m1, "./upload/22.jpg");
    }






    
}