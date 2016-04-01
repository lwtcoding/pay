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
           /* else if( $xml->MsgType == "text"){
                $commonUtil = new CommonUtil();
                $access_token = $commonUtil->accessToken();
                $user_info_url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$xml->FromUserName."&lang=zh_CN";
                $responseJson = $w->curlget($user_info_url);
                file_put_contents("post.txt",  $user_info_url);
            }*/
            else if( $xml->MsgType == "event"){
                if($xml->Event=="card_pass_check"){
                    $card_id = $xml->CardId;
                    //TODO 更新卡券状态 1
                    $data = array();
                    $data['status']=1;
                    M('wxcoupon')->where("card_id='%s'",array($card_id))->save($data);
                }
                else if($xml->Event=="card_not_pass_check"){
                    $card_id = $xml->CardId;
                    //TODO 更新卡券状态 2
                    $data = array();
                    $data['status']=2;
                    M('wxcoupon')->where("card_id='%s'",array($card_id))->save($data);
                }
                else if($xml->Event=="user_get_card"){
                    //TODO 减少卡券数量 ，更新领取卡券表
                    $data=array();
                    $data["card_id"] = $xml->CardId;
                    $data["openid"] = $xml->FromUserName;
                    $wxcoupon = M('wxcoupon')->field(array("mid","quantity"))->where("card_id='%s'",array($data["card_id"]))->find();
                    $wxcoupon['quantity']=$wxcoupon['quantity']-1;
                    $data['mid']=$wxcoupon['mid'];
                    M('wxcoupon')->where("card_id='%s'",array($data["card_id"]))->save($wxcoupon['quantity']);

                    M("wxcoupon_receive")->add($data);

                }
            }
        }
    }







    
}