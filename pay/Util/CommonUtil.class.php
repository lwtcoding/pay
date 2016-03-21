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

    public static function uploadCert($file,$path){
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

    }

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
        $merchant = $merchantDB -> field(array('id','pid')) -> where("id = '%s'",array($mid)) -> find();
        if(empty($merchant))
            return null;
        if(!empty($merchant['pid'])) {
            $pmerchant = $merchantDB->field(array('id', 'pid'))->where("id = '%s'", array($merchant['pid']))->find();
            $wxpayconfig = json_decode($pmerchant['config']['weixin'],true);
            $subpayconfig = json_decode($merchant['config']['weixin'],true);
            if((!empty($subpayconfig['appid']))&&(!(trim($subpayconfig['appid']=="")))){
                $wxpayconfig['subopenid'] = $subpayconfig['appid'];
            }
            if((!empty($subpayconfig['mchid']))&&(!(trim($subpayconfig['mchid']=="")))){
                $wxpayconfig['submchid'] = $subpayconfig['mchid'];
            }
            return $wxpayconfig;
        }
        return null;
    }
}

