<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/8
 * Time: 14:05
 */

namespace Util;


class FileUtil
{
    public static function list_dir($dir)
    {
        $result = array();
        if (is_dir($dir)) {
            $file_dir = scandir($dir);
            foreach ($file_dir as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                } elseif (is_dir($dir . $file)) {
                    $result = array_merge($result, list_dir($dir . "/" . $file));
                } else {
                    array_push($result, $dir . "/" . $file);
                }
            }
        }
        return $result;
    }
    public static function deldir($dir){
        $datalist =FileUtil::list_dir($dir);
        foreach ($datalist as $val) {
            if (file_exists($val)) {
                unlink($val);
            }
        }
        rmdir($dir);
    }
   public static function zip($zipdir)
    {

        $datalist = FileUtil::list_dir($zipdir);
        $filename = $zipdir . "/" . date("Y-m-d") . ".zip"; //最终生成的文件名（含路径）
        if (!file_exists($filename)) {
//重新生成文件
            $zip = new \ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
            if ($zip->open($filename, \ZIPARCHIVE::CREATE) !== TRUE) {
                exit('无法打开文件，或者文件创建失败');
            }
            foreach ($datalist as $val) {
                if (file_exists($val)) {
                    $zip->addFile($val, basename($val));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                }
            }
            $zip->close();//关闭
        }
        if (!file_exists($filename)) {
            exit("无法找到文件"); //即使创建，仍有可能失败。。。。
        }
//        $filePath = "./download_img/temp.zip";
//        $fileDir = "./download_img/temp/";

        $fp = fopen($filename, "r");
        $file_size = filesize($filename);
        //下载文件需要用到的头
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length:" . $file_size);
        Header("Content-Disposition: attachment; filename=" . basename($filename));
        $buffer = 1024;  //设置一次读取的字节数，每读取一次，就输出数据（即返回给浏览器）
        $file_count = 0; //读取的总字节数
        //向浏览器返回数据
        while (!feof($fp) && $file_count < $file_size) {
            $file_con = fread($fp, $buffer);
            $file_count += $buffer;
            echo $file_con;
        }
        fclose($fp);

        //下载完成后删除压缩包，临时文件夹
        if ($file_count >= $file_size) {
            FileUtil::deldir($zipdir);
            //   exec("rm -rf ".realpath($zipdir));
        }
        /* header("Cache-Control: public");
         header("Content-Description: File Transfer");
         header('Content-disposition: attachment; filename='.basename($filename)); //文件名
         header("Content-Type: application/zip"); //zip格式的
         header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
         header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小
         @readfile($filename);
     }*/
    }
}