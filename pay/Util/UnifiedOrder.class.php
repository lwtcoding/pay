<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/22
 * Time: 15:42
 */

namespace Util;


class UnifiedOrder
{

    private $params=array();
    private $apikey=array();
    function __construct($appid,$mchid,$apikey) {
        $this->params['appid']=$appid;
        $this->params['mch_id']=$mchid;
        $this->apikey['key']=$apikey;

    }
    public function setParameter($key,$value){
        $this->params[$key]=$value;
    }
    public function createSign(){
        $str="";
        ksort($this->params);
        foreach($this->params as $k=>$v){
            if(empty($v)||trim($v)=="")
                break;
            $str = $str.$k."=".$v."&";
        }
        $str = $str."key=".$this->apikey['key'];
        return strtoupper(md5($str));
    }
    public function getXmlData(){
        return $this->arrayToXml($this->params);
    }
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";
            }
            else {
                $xml .= "<" . $key . ">" . $val ."</" . $key . ">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    public function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
}