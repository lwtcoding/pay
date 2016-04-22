<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/25
 * Time: 10:45
 */

namespace Util;


class AuthConfig
{
    /*public static $auth=array(
        "门店管理"=>"Store-stores",
        "添加门店"=>"Store-add",
        "编辑门店"=>"Store-edit",
        "删除门店"=>"Store-delete",
         "查看支付配置"=>"Merchant-payconfig",
        "修改支付配置"=>"Merchant-saveconfig",
        "查看代理商户"=>"Merchant-submerchants",
        "添加商户"=>"Merchant-add",
        "编辑商户"=>"Merchant-edit",
        "编辑子商户配置"=>"Merchant-editPayconfig",
        "删除商户"=>"Merchant-delete",
        "订单管理"=>"Order-orders",
        "代理商订单流水"=>"Order-proxyOrders",
        "订单统计"=>"Order-statistics",
        "代理商订单统计"=>"Order-proxyStatistics",
        "二维码收款"=>"Pay-jspay",

    );*/
    public static $auth=array(



        array(
            "name"=>"订单管理",
            "value"=>"Order-orders"
        ),

        array(
            "name"=>"订单统计",
            "value"=>"Order-statistics"
        ),

        array(
            "name"=>"代理商订单流水",
            "value"=>"Order-proxyorders"
        ),
        array(
            "name"=>"代理商订单统计",
            "value"=>"Order-proxystatistics"
        ),

        array(
            "name"=>"页面信息配置",
            "value"=>"Pageinfo-pageinfo"
        ),
        array(
            "name"=>"立减配置",
            "value"=>"Lijian-lijian"
        ),
        array(
            "name"=>"折扣配置",
            "value"=>"Discount-discount"
        ),

    );
    public static $no_auth=array(

        "Index-index",
        "Index-left",
        "Index-body",
        "Index-foot",
        "Order-storestatistics",
        "Order-refund",
        "Lijian-add",
        "Lijian-share",
        "Pageinfo-upload",
        "Discount-add",
        "Discount-share",


    );
}