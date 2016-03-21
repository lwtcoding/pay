<?php
	namespace Wechat;
	class CommonUtil{

		function accessToken() {
			    $tokenFile = "./access_token.txt";
			    $data = json_decode(file_get_contents($tokenFile));
			    if ($data->expire_time < time() or !$data->expire_time) {
			    $appid = APPID;
			    $appsecret = APPSECRECT;
			    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

			      $res = $this->getJson($url);
			      $access_token = $res['access_token'];
			      if($access_token) {
			        $data['expire_time'] = time() + 7000;
			        $data['access_token'] = $access_token;
			        $fp = fopen($tokenFile, "w");
			        fwrite($fp, json_encode($data));
			        fclose($fp);
			      }
			    } else {
			      $access_token = $data->access_token;
			    }
			     return $access_token;
			  }
			   

			function getJson($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			return json_decode($output, true);
			}
		/*
		*@通过curl方式获取指定的图片到本地
		*@ 完整的图片地址
		*@ 要存储的文件名
		*/
		function getImg($url = "")
		{

			$curl = curl_init($url);
			$filename ="upload/".date("Ymdhis").".jpg";
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			$imageData = curl_exec($curl);
			curl_close($curl);
			$tp = @fopen($filename, 'a');
			fwrite($tp, $imageData);
			fclose($tp);
			return $filename;
		}

		/**
		 * 生成缩略图函数（支持图片格式：gif、jpeg、png和bmp）
		 * @author ruxing.li
		 * @param  string $src      源图片路径
		 * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放）
		 * @param  int    $width    缩略图高度（只指定宽度时进行等比缩放）
		 * @param  string $filename 保存路径（不指定时直接输出到浏览器）
		 * @return bool
		 */
		function mkThumbnail($src, $width = null, $height = null, $filename = null) {
			if (!isset($width) && !isset($height))
				return false;
			if (isset($width) && $width <= 0)
				return false;
			if (isset($height) && $height <= 0)
				return false;

			$size = getimagesize($src);
			if (!$size)
				return false;

			list($src_w, $src_h, $src_type) = $size;
			$src_mime = $size['mime'];
			switch($src_type) {
				case 1 :
					$img_type = 'gif';
					break;
				case 2 :
					$img_type = 'jpeg';
					break;
				case 3 :
					$img_type = 'png';
					break;
				case 15 :
					$img_type = 'wbmp';
					break;
				default :
					return false;
			}

			if (!isset($width))
				$width = $src_w * ($height / $src_h);
			if (!isset($height))
				$height = $src_h * ($width / $src_w);

			$imagecreatefunc = 'imagecreatefrom' . $img_type;
			$src_img = $imagecreatefunc($src);
			$dest_img = imagecreatetruecolor($width, $height);
			imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

			$imagefunc = 'image' . $img_type;
			if ($filename) {
				$imagefunc($dest_img, $filename);
			} else {
				header('Content-Type: ' . $src_mime);
				$imagefunc($dest_img);
			}
			imagedestroy($src_img);
			imagedestroy($dest_img);
			return true;
		}



	}