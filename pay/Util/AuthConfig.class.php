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
            "name"=>"门店管理",
            "value"=>"Store-stores"
        ),
        array(
            "name"=>"添加门店",
            "value"=>"Store-add"
        ),
        array(
            "name"=>"编辑门店",
            "value"=>"Store-edit"
        ),
        array(
            "name"=>"删除门店",
            "value"=>"delete"
        ),
        array(
            "name"=>"查看支付配置",
            "value"=>"Merchant-payconfig"
        ),
        array(
            "name"=>"修改支付配置",
            "value"=>"Merchant-saveconfig"
        ),
        array(
            "name"=>"查看代理商户",
            "value"=>"Merchant-submerchants"
        ),
        array(
            "name"=>"添加商户",
            "value"=>"Merchant-add"
        ),
        array(
            "name"=>"编辑商户",
            "value"=>"Merchant-edit"
        ),
        array(
            "name"=>"编辑子商户配置",
            "value"=>"Merchant-editpayconfig"
        ),
        array(
            "name"=>"删除商户",
            "value"=>"Merchant-delete"
        ),
        array(
            "name"=>"订单管理",
            "value"=>"Order-orders"
        ),
        array(
            "name"=>"代理商订单流水",
            "value"=>"Order-proxyorders"
        ),
        array(
            "name"=>"订单统计",
            "value"=>"Order-statistics"
        ),
        array(
            "name"=>"代理商订单统计",
            "value"=>"Order-proxystatistics"
        ),
        array(
            "name"=>"员工管理",
            "value"=>"Staff-staffs"
        ),
        array(
            "name"=>"添加员工",
            "value"=>"Staff-add"
        ),
        array(
            "name"=>"编辑员工",
            "value"=>"Staff-edit"
        ),
        array(
            "name"=>"删除员工",
            "value"=>"Staff-delete"
        ),
        array(
            "name"=>"二维码收款",
            "value"=>"Pay-jspay"
        ),
        array(
            "name"=>"页面信息配置",
            "value"=>"Pageinfo-pageinfo"
        ),
        array(
            "name"=>"卡券管理",
            "value"=>"Card-cards"
        ),
        array(
            "name"=>"添加卡券",
            "value"=>"Card-add"
        ),
        array(
            "name"=>"立减配置",
            "value"=>"Lijian-lijian"
        ),

    );
    public static $no_auth=array(
        "Index-index",
        "Index-left",
        "Index-body",
        "Index-foot",
        "Order-storestatistics",
        "Card-qrcode",
        "Card-docreatekq",
        "Card-edit",
        "Card-uploadimg",
        "Lijian-add",
        "Pageinfo-upload"
    );
}