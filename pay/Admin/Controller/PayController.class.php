<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/23
 * Time: 16:55
 */

namespace Admin\Controller;

use Think\Controller;
use Util\AuthController;

class PayController extends AuthController
{
    public function jspay(){



        vendor("phpqrcode.phpqrcode");
       $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 12;//生成图片大小

        $url = "http://lwtcoding.wicp.net/index.php/Home/Pay/wechatPay?mid=".$_GET['mid']."&store_id=".$_GET['store_id'];
        new \QRimage(550, 550);
        //$url = urldecode( $url);

        if ($_GET['download']==1) {
            if((trim($_GET['mid'])=="")||(trim($_GET['store_id'])=="")){
                exit();
            }
            $fname = 'Your-Card-code-image-' . $_GET['mid']."|".$_GET['store_id'] . '.png';
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Type:application/force-download');
            header('Content-type: image/png');
            header('Content-Type:application/download');
            header('Content-Disposition: attachment; filename=' . $fname);
            header('Content-Transfer-Encoding: binary');
            \QRcode::png($url, false, 'H', 10, 1);
        }
        if($_GET['qrcode']==1){
            if((trim($_GET['mid'])=="")||(trim($_GET['store_id'])=="")){
                exit();
            }
            Header('Content-type: image/jpeg');
            \QRcode::png($url,false,$errorCorrectionLevel,$matrixPointSize,2);
        }else{
            $stores = M('store')->field(array('id','mid','name'))->where("mid = '%s'",array($_SESSION['loginMerchant']['id']))->select();
            $this->assign("stores",$stores);
            $this->display();
        }
       /* if($_GET['download']==1) {
            $img = file_get_contents("./Uploads/qrcode/qrcode.png", true);
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: ".filesize("./Uploads/qrcode/qrcode.png"));
            Header("Content-Disposition: attachment; filename=" . "qrcode.png");
        }
        if($_GET['qrcode']==1){

            //生成二维码图片
            if (!is_dir("./Uploads/qrcode")) {
                mkdir("./Uploads/qrcode");
            }
            \QRcode::png($url, "./Uploads/qrcode/qrcode.png", $errorCorrectionLevel, $matrixPointSize, 2);
            //file_get_contents($url,true); 可以读取远程图片，也可以读取本地图片
            $img = file_get_contents("./Uploads/qrcode/qrcode.png", true);
            //使用图片头输出浏览器
            header("Content-Type: image/jpeg;text/html; charset=utf-8");
            echo $img;
            exit;
        }else{
            $stores = M('store')->field(array('id','mid','name'))->where("mid = '%s'",array($_SESSION['loginMerchant']['id']))->select();
            $this->assign("stores",$stores);
            $this->display();
        }*/
    }
}