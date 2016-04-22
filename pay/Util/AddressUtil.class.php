<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/18
 * Time: 15:30
 */

namespace Util;


class AddressUtil
{
    private static $provinces;
    private static $citys;
    private function initData(){
        if(empty(self::$provinces)||empty(self::$citys)) {
            self::$provinces = json_decode(file_get_contents("province.json"), true);
            self::$citys = json_decode(file_get_contents("city.json"), true);
        }
    }
    public static function init(){
        self::initData();

        $address=array(
            "province"=>self::$provinces,
            "city"=>self::$citys,
            );
        return $address;
    }
    public static function getCityID($cityName){
        self::initData();
        $cityID=null;
        for($i=0;$i<count(self::$citys);$i++){
            if($cityName==self::$citys[$i]['name'])
                $cityID=self::$citys[$i]['CityID'];
        }
        return $cityID;
    }
    public static function getProID($cityName){
        self::initData();
        $proID=null;

        for($i=0;$i<count(self::$provinces);$i++){

            if($cityName==self::$provinces[$i]['name'])
                $proID=self::$provinces[$i]['ProID'];
        }
        return $proID;
    }
}