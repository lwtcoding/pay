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
use Util\CommonUtil;
use Util\FileUtil;

class PayController extends AuthController
{
    public function jspay()
    {


        vendor("phpqrcode.phpqrcode");
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 12;//生成图片大小

        $url = "http://lwtcoding.wicp.net/index.php/Home/Pay/wechatPay?mid=" . $_GET['mid'] . "&store_id=" . $_GET['store_id'];
        new \QRimage(550, 550);
        //$url = urldecode( $url);

        if ($_GET['download'] == 1) {
            if ((trim($_GET['mid']) == "") || (trim($_GET['store_id']) == "")) {
                exit();
            }
            $fname = 'Your-Card-code-image-' . $_GET['mid'] . "|" . $_GET['store_id'] . '.png';
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
        if ($_GET['qrcode'] == 1) {
            if ((trim($_GET['mid']) == "") || (trim($_GET['store_id']) == "")) {
                exit();
            }
            Header('Content-type: image/jpeg');
            \QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
        } else {
            $stores = M('store')->field(array('id', 'mid', 'name'))->where("mid = '%s'", array($_SESSION['loginMerchant']['id']))->select();
            $this->assign("stores", $stores);
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

    public function batchQrcode()
    {
        //查询商户门店
        if (isset($_POST['searchStore']) && ($_POST['searchStore'] == 1)) {
            $stores = M('store')->field(array('id', 'name'))->where("mid = '%s'", array($_POST['mid']))->select();
            if (!empty($stores)) {
                $this->ajaxReturn(array("result" => "success", "message" => "", "data" => $stores));
            } else {
                $this->ajaxReturn(array("result" => "error", "message" => "此商户无门店", "data" => null));
            }
        }
        if (($_POST['batch'] == 1) && (count($_POST['store_ids']) > 0)) {
            $map['id'] = array('in', $_POST['store_ids']);
            $stores = M("store")->field(array('id', 'mid', 'name', 'merchantname'))->where($map)->select();
            //生成二维码图片
            vendor("phpqrcode.phpqrcode");
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = 12;//生成图片大小
            $tempdir = "./Uploads/" . CommonUtil::genernateNonceStr(7);
            if (!is_dir($tempdir)) {
                mkdir($tempdir);
            }
            for ($i = 0; $i < count($stores); $i++) {
                $filename = $tempdir . "/" . $stores[$i]['merchantname'] . "_" . $stores[$i]['name'] . ".png";
                $filename = iconv('utf-8', 'gb2312', $filename);
                var_dump($filename);
                $url = 'http://'.$_SERVER['HTTP_HOST'] . "/index.php/Home/Pay/wechatPay?mid=" . $stores[$i]['mid'] . "&store_id=" . $stores[$i]['id'];
                var_dump($url);
                \QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
            }
            FileUtil::zip($tempdir);
        } else {
            echo "请选择需要生成二维码的门店！";
        }
    }


}