<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/29
 * Time: 9:43
 */

namespace Admin\Controller;


use Util\AuthController;
use Util\BaseAuthController;
use Util\CommonUtil;
use Util\WeChatUtil;

class CardController extends BaseAuthController
{
    private $card_type;
    public function __construct()
    {
        parent::__construct();
        $this->card_type = array(
            array('enname' => 'GENERAL_COUPON', 'zhname' => '优惠券'),
            array('enname' => 'GROUPON', 'zhname' => '团购券'),
            array('enname' => 'DISCOUNT', 'zhname' => '折扣券'),
            array('enname' => 'GIFT', 'zhname' => '礼品券'),
            array('enname' => 'CASH', 'zhname' => '代金券'),
            array('enname' => 'MEMBER_CARD', 'zhname' => '会员卡')
        );
    }

    public function cards(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $db = M('wxcoupon');
            $pager = array();
            $condition = "1=1";
            $condition .= " AND mid=" . $_SESSION['loginMerchant']['id'];
            if(isset($_POST['status'])&&(!(trim($_POST['status'])=="")))
                $condition.=" AND status = ".$_POST['status'];

            $pager['total'] = $db->where($condition)->count();
            $pager['rows'] = $db->field(array("id","card_title","quantity","get_limit","status","discount"))->where($condition)->order(($_POST['sort']==''?'id':$_POST['sort']).' '.$_POST['order'])->limit($_POST['offset'] . "," . $_POST['limit'])->select();
            $this->ajaxReturn($pager);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){

            $this->display();
        }
    }

    public function add(){
        $datestart = date('Y-m-d');
        $dateend = date('Y-m-d', strtotime('+1 month'));
        $typeid = 5;
        $wxcouponSet = M('wxcoupon_common')->where('mid = '.$_SESSION['loginMerchant']['id'])->find();
        $mid = $_SESSION['loginMerchant']['id'];
        $wxconfig = CommonUtil::getMerchantConfig($mid);
        $wechatUtil = new WeChatUtil($wxconfig,$mid);
        $shoplist = unserialize($GLOBALS['_SESSION']['wxshoplist']);

        if (!is_array($shoplist) || empty($shoplist)) {

            $wxShoplist =$wechatUtil->wxGetPoiList($wechatUtil->accessToken());
            $shoplist = array();
            if (isset($wxShoplist['business_list']) && !empty($wxShoplist['business_list'])) {
                foreach ($wxShoplist['business_list'] as $kk => $vv) {
                    $shoplist[$vv['base_info']['poi_id']] = array('sid' => $vv['base_info']['sid'], 'business_name' => $vv['base_info']['business_name'], 'branch_name' => $vv['base_info']['branch_name'], 'poi_id' => $vv['base_info']['poi_id'], 'address' => $vv['base_info']['address']);
                }
            }

            if (!empty($wxShoplist)) {
                $GLOBALS['_SESSION']['wxshoplist'] = serialize($shoplist);
            }
        }

        $wxCardColor = $wechatUtil->wxCardColor($wechatUtil->accessToken());

        $this->assign("shoplist",$shoplist);
        $this->assign("wxCardColor",$wxCardColor);
        $this->assign("datestart",$datestart);
        $this->assign("dateend",$dateend);
        $this->assign("card_type",$this->card_type[$typeid]);
        $this->assign("typeid",$typeid);
        $this->assign("wxcouponSet",$wxcouponSet);
        $this->display();
    }

    public function docreateKq()
    {
        $localArr = array();
        $mid = $_SESSION['loginMerchant']['id'];
        $wxconfig = CommonUtil::getMerchantConfig($mid);
        $wechatUtil = new WeChatUtil($wxconfig,$mid);
        $card_type = array('GENERAL_COUPON', 'GROUPON', 'DISCOUNT', 'GIFT', 'CASH', 'MEMBER_CARD');
        $datas = $this->clear_html($_POST);
        $type = intval($_POST['ctype']);
        $card_typestr = $card_type[$type];
        $keycard_type = strtolower($card_typestr);
        $wxJsonstr['card'] = array(
            'card_type'   => $card_typestr,
            $keycard_type => array()
        );
        $base_info = $datas['base_info'];
        unset($datas['base_info']);
        $base_info['code_type'] = 'CODE_TYPE_QRCODE';
        $base_info['get_limit'] = intval($base_info['get_limit']);
        !(0 < $base_info['get_limit']) && $base_info['get_limit'] = 50;
        $begin_timestamp = (empty($datas['datestart']) ? strtotime(date('Y-m-d')) : strtotime($datas['datestart']));
        $end_timestamp = (empty($datas['dateend']) ? strtotime(date('Y-m-d')) + (30 * 24 * 3600) : strtotime($datas['dateend']));
        $base_info['date_info'] = array('type' => 'DATE_TYPE_FIX_TIME_RANGE', 'begin_timestamp' => $begin_timestamp, 'end_timestamp' => $end_timestamp);
        $localArr = array('mid' => $_SESSION['loginMerchant']['id'], 'card_type' => $type, 'card_title' => $base_info['title'], 'begin_timestamp' => $begin_timestamp, 'end_timestamp' => $end_timestamp);
        $base_info['sku'] = array('quantity' => intval($datas['quantity']));
        $base_info['use_custom_code'] = false;
        $base_info['bind_openid'] = false;
        $base_info['can_share'] = false;
        $base_info['can_give_friend'] = true;
        $base_info['location_id_list'] = !empty($datas['inputpoiid']) ? 'Jsarray[' . implode(',', $datas['inputpoiid']) . ']' : 'Jsarray[0]';
        $base_info['source'] = '银联通莞';
        $this->FiltrationData($base_info);
        if (!empty($base_info['custom_url']) && (strpos($base_info['custom_url'], 'http:') === false) && (strpos($base_info['custom_url'], 'https:') === false)) {
            $base_info['custom_url'] = 'http://' . $base_info['custom_url'];
        }

        if (!empty($base_info['promotion_url']) && (strpos($base_info['promotion_url'], 'http:') === false) && (strpos($base_info['promotion_url'], 'https:') === false)) {
            $base_info['promotion_url'] = 'http://' . $base_info['promotion_url'];
        }

        $postwxJsonstr = '';

        switch ($type) {
            case '0':
                if (empty($datas['default_detail'])) {
                    $this->ajaxReturn(array('error' => 1, 'msg' => '优惠详情须填写'));
                }

                $localArr['quantity'] = $base_info['sku']['quantity'];
                $localArr['get_limit'] = $base_info['get_limit'];
                $localArr['kqcontent'] = serialize($base_info);
                $localArr['kqexpand'] = serialize(array('content' => $datas['default_detail']));
                $wxJsonstr['card'][$keycard_type]['base_info'] = $base_info;
                $wxJsonstr['card'][$keycard_type]['default_detail'] = $datas['default_detail'];
                $postwxJsonstr = $this->ArrayToJsonstr($wxJsonstr);
                break;

            case '1':
                if (empty($datas['deal_detail'])) {
                    $this->ajaxReturn(array('error' => 1, 'msg' => '优惠详情须填写'));
                }

                $localArr['quantity'] = $base_info['sku']['quantity'];
                $localArr['get_limit'] = $base_info['get_limit'];
                $localArr['kqcontent'] = serialize($base_info);
                $localArr['kqexpand'] = serialize(array('content' => $datas['deal_detail']));
                $wxJsonstr['card'][$keycard_type]['base_info'] = $base_info;
                $wxJsonstr['card'][$keycard_type]['deal_detail'] = $datas['deal_detail'];
                $postwxJsonstr = $this->ArrayToJsonstr($wxJsonstr);
                break;

            case '2':
                if (empty($datas['discount']) || ($datas['discount'] < 1) || (10 <= $datas['discount'])) {
                    $this->ajaxReturn(array('error' => 1, 'msg' => '折扣额度只能是大于1且小于10的数字'));
                }

                $localArr['quantity'] = $base_info['sku']['quantity'];
                $localArr['get_limit'] = $base_info['get_limit'];
                $localArr['kqcontent'] = serialize($base_info);
                $localArr['kqexpand'] = serialize(array('discount' => $datas['discount']));
                $wxJsonstr['card'][$keycard_type]['base_info'] = $base_info;
                $wxJsonstr['card'][$keycard_type]['discount'] = 100 - ($datas['discount'] * 10);
                $postwxJsonstr = $this->ArrayToJsonstr($wxJsonstr);
                break;
        }

        switch ($type) {
            case '3':
                if (empty($datas['gift'])) {
                    $this->ajaxReturn(array('error' => 1, 'msg' => '优惠详情须填写'));
                }

                $localArr['quantity'] = $base_info['sku']['quantity'];
                $localArr['get_limit'] = $base_info['get_limit'];
                $localArr['kqcontent'] = serialize($base_info);
                $localArr['kqexpand'] = serialize(array('content' => $datas['gift']));
                $wxJsonstr['card'][$keycard_type]['base_info'] = $base_info;
                $wxJsonstr['card'][$keycard_type]['gift'] = $datas['gift'];
                $postwxJsonstr = $this->ArrayToJsonstr($wxJsonstr);
                break;

            case '4':
                if (empty($datas['reduce_cost']) || !(0.01 < $datas['reduce_cost'])) {
                    $this->ajaxReturn(array('error' => 1, 'msg' => '减免金额必须填写一个大于0.01的数字'));
                }

                $wxJsonstr['card'][$keycard_type]['reduce_cost'] = intval($datas['reduce_cost'] * 100);
                if (empty($datas['least_cost']) || !(0.01 < $datas['least_cost'])) {
                    $wxJsonstr['card'][$keycard_type]['least_cost'] = 0;
                    $datas['least_cost'] = 0;
                }
                else {
                    $wxJsonstr['card'][$keycard_type]['least_cost'] = intval($datas['least_cost'] * 100);
                }

                $localArr['quantity'] = $base_info['sku']['quantity'];
                $localArr['get_limit'] = $base_info['get_limit'];
                $localArr['kqcontent'] = serialize($base_info);
                $localArr['kqexpand'] = serialize(array('reduce_cost' => $datas['reduce_cost'], 'least_cost' => $datas['least_cost']));
                $wxJsonstr['card'][$keycard_type]['base_info'] = $base_info;
                $postwxJsonstr = $this->ArrayToJsonstr($wxJsonstr);
                break;

        }

        switch ($type) {
            case '5':
                if (empty($datas['prerogative'])) {
                    $this->ajaxReturn(array('error' => 1, 'msg' => '特权说明须填写'));
                }

                $discount = intval($datas['discount']);
                if (($discount < 0) || (100 < $discount)) {
                    $this->ajaxReturn(array('error' => 1, 'msg' => '折扣应该在0~100之间的整数'));
                }

                $discount && $wxJsonstr['card'][$keycard_type]['discount'] = $discount;

                if ($datas['date_type'] == 'DATE_TYPE_PERMANENT') {
                    $base_info['date_info'] = array('type' => 'DATE_TYPE_PERMANENT');
                    $localArr['begin_timestamp'] = $localArr['end_timestamp'] = 0;
                }

                $wxJsonstr['card'][$keycard_type]['prerogative'] = $datas['prerogative'];
                $localArr['activate'] = $datas['activate'];

                if ($datas['activate'] == 0) {
                    $wxJsonstr['card'][$keycard_type]['auto_activate'] = true;
                }
                else if ($datas['activate'] == 1) {
                    $wxJsonstr['card'][$keycard_type]['wx_activate'] = true;
                }
                else {
                    $wxJsonstr['card'][$keycard_type]['auto_activate'] = true;
                }

                $wxJsonstr['card'][$keycard_type]['supply_balance'] = false;

                if ($datas['supply_balance']) {
                    if (!empty($datas['balance_url']) && (strpos($datas['balance_url'], 'http:') === false) && (strpos($datas['balance_url'], 'https:') === false)) {
                        $datas['balance_url'] = 'http://' . $datas['balance_url'];
                    }

                    $wxJsonstr['card'][$keycard_type]['supply_balance'] = true;
                    $wxJsonstr['card'][$keycard_type]['balance_url'] = $datas['balance_url'];
                    $wxJsonstr['card'][$keycard_type]['balance_rules'] = $datas['balance_rules'];
                }

                $wxJsonstr['card'][$keycard_type]['supply_bonus'] = false;

                if ($datas['supply_bonus']) {
                    if (!empty($datas['bonus_url']) && (strpos($datas['bonus_url'], 'http:') === false) && (strpos($datas['bonus_url'], 'https:') === false)) {
                        $datas['bonus_url'] = 'http://' . $datas['bonus_url'];
                    }

                    $wxJsonstr['card'][$keycard_type]['supply_bonus'] = true;
                    $wxJsonstr['card'][$keycard_type]['bonus_url'] = $datas['bonus_url'];
                    $wxJsonstr['card'][$keycard_type]['bonus_rules'] = $datas['bonus_rules'];
                    $wxJsonstr['card'][$keycard_type]['bonus_cleared'] = $datas['bonus_cleared'];
                    /*if (empty($datas['bonus_rule']['cost_money_unit']) || ($datas['bonus_rule']['cost_money_unit'] < 1)) {
                        $this->ajaxReturn(array('error' => 1, 'msg' => '消费金额必须大于等于1的整数'));
                    }

                    if (empty($datas['bonus_rule']['increase_bonus']) || ($datas['bonus_rule']['increase_bonus'] < 1)) {
                        $this->ajaxReturn(array('error' => 1, 'msg' => '增加的积分必须大于等于1的整数'));
                    }

                    if (empty($datas['bonus_rule']['max_increase_bonus']) || ($datas['bonus_rule']['max_increase_bonus'] < 1)) {
                        $this->ajaxReturn(array('error' => 1, 'msg' => '积分上限必须大于等于1的整数'));
                    }

                    if (empty($datas['bonus_rule']['init_increase_bonus']) || ($datas['bonus_rule']['init_increase_bonus'] < 0)) {
                        $this->ajaxReturn(array('error' => 1, 'msg' => '初始积分必须大于等于0的整数'));
                    }*/

                    $wxJsonstr['card'][$keycard_type]['bonus_rule'] = array('cost_money_unit' => intval($datas['bonus_rule']['cost_money_unit']), 'increase_bonus' => intval($datas['bonus_rule']['increase_bonus']), 'max_increase_bonus' => intval($datas['bonus_rule']['max_increase_bonus']), 'init_increase_bonus' => intval($datas['bonus_rule']['init_increase_bonus']));
                }

                if (!empty($datas['custom_cell1']['url']) && (strpos($datas['custom_cell1']['url'], 'http:') === false) && (strpos($datas['custom_cell1']['url'], 'https:') === false)) {
                    $datas['custom_cell1']['url'] = 'http://' . $datas['custom_cell1']['url'];
                }

                $wxJsonstr['card'][$keycard_type]['custom_cell1'] = $datas['custom_cell1'];
                $localArr['quantity'] = $base_info['sku']['quantity'];
                $localArr['get_limit'] = $base_info['get_limit'];
                $localArr['kqcontent'] = serialize($base_info);
                $localArr['kqexpand'] = serialize($wxJsonstr['card'][$keycard_type]);
                $wxJsonstr['card'][$keycard_type]['base_info'] = $base_info;

                $postwxJsonstr = $this->ArrayToJsonstr($wxJsonstr);
                break;

            default:
                break;
        }

        $rets = $wechatUtil->wxCardCreated($wechatUtil->accessToken(), $postwxJsonstr);

        if (isset($rets['card_id']) && !empty($rets['card_id'])) {
            $localArr['card_id'] = trim($rets['card_id']);
            $localArr['addtime'] = time();

            $wxcoupon_id = M('wxcoupon')->add($localArr);
            $this->updateMname($base_info['brand_name'], $base_info['logo_url']);
            $this->ajaxReturn(array('error' => 0, 'msg' => 'OK'));
        }
        else {
            $tmpmsg = (isset($rets['errcode']) ? $rets['errcode'] : '');
            isset($rets['errmsg']) && ($tmpmsg = $tmpmsg . '：' . $rets['errmsg']);

            if (!empty($tmpmsg)) {
                $this->ajaxReturn(array('error' => 1, 'msg' => $tmpmsg));
            }
        }

        $this->ajaxReturn(array('error' => 1, 'msg' => '数据保存失败！'));
    }
    private function updateMname($brand_name, $logo_url)
    {
        $wxcouponCDb = M('wxcoupon_common');
        $mset = $wxcouponCDb->where(array('mid='.$_SESSION['loginMerchant']['id']))->find();
        $inserData = array('mname' => $brand_name);

        if (!empty($mset)) {
            if (empty($mset['mname']) || ($mset['mname'] != $brand_name)) {
                $wxcouponCDb->where(array('mid='.$_SESSION['loginMerchant']['id']))->save($inserData);
            }
        }
        else {

            $inserData['mid'] = $_SESSION['loginMerchant']['id'];
            $inserData['wxlogurl'] = $logo_url;
            $wxcouponCDb->add($inserData);
        }
    }

    public function qrcode()
    {
        $id = intval(trim($_GET['cid']));

        $isdwd = (isset($_GET['dwd']) ? intval(trim($_GET['dwd'])) : 0);
        $tmpdata = M('wxcoupon')->field(array('cardurl','cardticket','mid'))->where("id=".$id)->find();

        if(empty($tmpdata['cardurl'])) {
           $tmpdata = $this->wxCardQrCodeTicket();
        }
        if(empty($tmpdata))
            exit();

        vendor("phpqrcode.phpqrcode");
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 12;//生成图片大小
        new \QRimage(350, 350);
        $url = urldecode($tmpdata['cardurl']);

        if (0 < $isdwd) {
            $fname = 'Your-Card-code-image-' . $tmpdata['mid'] . '.png';
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
        else {
            Header('Content-type: image/jpeg');
            \QRcode::png($url,false,$errorCorrectionLevel,$matrixPointSize,2);
        }
    }

    public function wxCardQrCodeTicket()
    {
        $id = intval(trim($_GET['cid']));

        $wxcouponDb = M('wxcoupon');
        $cardinfo = $wxcouponDb->where("id=".$id)->find();
        if (!empty($cardinfo) && !empty($cardinfo['cardurl'])) {
            //$this->dexit(array('error' => 0, 'msg' => $id));
            return null;
        }
        else {
            if (!empty($cardinfo)) {
                $mid = $cardinfo['mid'];
                $wxconfig = CommonUtil::getMerchantConfig($mid);
                $wechatUtil = new WeChatUtil($wxconfig,$mid);
                $postwxJsonstr = '{"action_name":"QR_CARD","action_info":{"card": {"card_id":"' . $cardinfo['card_id'] . '","is_unique_code": false ,"outer_id" : ' .  $cardinfo['mid'] . '}}}';

                $rets = $wechatUtil->wxCardQrCodeTicket($wechatUtil->accessToken(), $postwxJsonstr);
                if (isset($rets['errcode']) && ($rets['errcode'] == 0)) {
                    $cardinfo['cardticket']=$rets['ticket'];
                    $cardinfo['cardurl']=$rets['url'];
                    $wxcouponDb->save($cardinfo);
                    return $cardinfo;
                }
                else {
                    $tmpmsg = (isset($rets['errcode']) ? $rets['errcode'] : '');
                    isset($rets['errmsg']) && ($tmpmsg = $tmpmsg . '：' . $rets['errmsg']);

                    if (!empty($tmpmsg)) {
                        //$this->dexit(array('error' => 1, 'msg' => $tmpmsg));
                        echo $tmpmsg;
                        return null;
                    }

                   // $this->dexit(array('error' => 1, 'msg' => '二维码生成失败！'));
                    echo  '二维码生成失败！';
                    return null;
                }
            }
        }

       // $this->dexit(array('error' => 1, 'msg' => '卡券不存在，不可删除！'));
        echo  '卡券不存在！';
        return null;
    }

    public function edit(){
        if(isset($_POST['id'])){
            M('wxcoupon')->save($_POST);
            $this->ajaxReturn(array("result"=>"success","message"=>"修改成功","data"=>null));
        }
        $this->ajaxReturn(array("result"=>"error","message"=>"修改失败","data"=>"找不到相关卡券"));
    }

    final public function clear_html($array) {
        if (!is_array($array))
            return trim(htmlspecialchars($array, ENT_QUOTES));
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->clear_html($value);
            } else {
                $array[$key] = trim(htmlspecialchars($value, ENT_QUOTES));
            }
        }
        return $array;
    }
    private function ArrayToJsonstr($array)
    {
        $tmpJosnStr = '{';

        foreach ($array as $key => $val) {
            $tmpJosnStr .= '"' . $key . '":';

            if (is_array($val)) {
                $tmpJosnStr .= $this->ArrayToJsonstr($val) . ',';
            } else if (is_numeric($val)) {
                $tmpJosnStr .= $val . ',';
            } else if (is_bool($val)) {
                $tmpJosnStr .= ($val ? 'true,' : 'false,');
            } else if (empty($val)) {
                $tmpJosnStr .= '"",';
            } else if (stripos($val, 'sarray[')) {
                $tmpJosnStr .= str_replace('Jsarray', '', $val) . ',';
            } else {
                $tmpJosnStr .= '"' . $val . '",';
            }
        }

        $tmpJosnStr = rtrim($tmpJosnStr, ',');
        $tmpJosnStr .= '}';
        return $tmpJosnStr;
    }
    private function FiltrationData($array)
    {
        if (empty($array['logo_url'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => 'LOGO图片必须上传'));
        }

        if (empty($array['brand_name'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => '商户名称必须填写'));
        }

        if (empty($array['title'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => '卡券标题必须填写'));
        }

        if (empty($array['color'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => '卡券颜色必须填写'));
        }

        if (empty($array['notice'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => '卡券操作提示必须填写'));
        }

        if (empty($array['description'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => '卡券详情使用须知必须填写'));
        }

        if (!(0 < $array['sku']['quantity'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => '卡券库存必须填写一个大于0的正数'));
        }

        if (!(0 < $array['date_info']['begin_timestamp'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => '卡券有效期开始时间没有填写'));
        }

        if (!(0 < $array['date_info']['end_timestamp'])) {
            $this->ajaxReturn(array('error' => 1, 'msg' => '卡券有效期结束时间没有填写'));
        }

        return true;
    }
    public function uploadImg()
    {
        if (!empty($_FILES)) {
              $mid = $_SESSION['loginMerchant']['id'];
            $path = 'card/'.$mid.'/';
            CommonUtil::deldir("./Uploads/card/".$mid."/");
            $return = CommonUtil::upload("file",$path);
            $image = new \Think\Image();
            $path="./Uploads/".$return;
            $image->open($path);
            // 生成一个居中裁剪为150*150的缩略图并保存为thumb.jpg
            $image->thumb(300, 300,\Think\Image::IMAGE_THUMB_SCALE)->save($path);


            if (isset($return)) {
                $mid = $_SESSION['loginMerchant']['id'];
                $wxconfig = CommonUtil::getMerchantConfig($mid);
                $wechatUtil = new WeChatUtil($wxconfig,$mid);

                $wxlogimg = $wechatUtil->wxCardUpdateImg($wechatUtil->accessToken(), $path);

                if (isset($wxlogimg['url']) && !empty($wxlogimg['url'])) {
                    $wxcouponCDb = M('wxcoupon_common');
                    $mset = $wxcouponCDb->where('mid ='.$_SESSION['loginMerchant']['id'])->find();
                    $inserData = array('logourl' => $return, 'wxlogourl' => $wxlogimg['url']);

                    if (!empty($mset)) {
                        $wxcouponCDb->where('id = '.$mset['id'])->save($inserData);
                    }
                    else {
                        $inserData['mid'] = $_SESSION['loginMerchant']['id'];
                        $wxcouponCDb->add($inserData);
                    }

                    $this->ajaxReturn(array("result"=>1,"message"=>"success","data"=>$inserData));
                }
                else {
                    $tmpmsg = (isset($wxlogimg['errcode']) ? $wxlogimg['errcode'] : '');
                    isset($wxlogimg['errmsg']) && ($tmpmsg = $tmpmsg . ':' . $wxlogimg['errmsg']);

                    if (!empty($tmpmsg)) {
                        $this->ajaxReturn(array("result"=>0,"message"=>$tmpmsg,"data"=>null));
                    }
                }
            }
        }

        $this->ajaxReturn(array("result"=>0,"message"=>"fail","data"=>null));
    }
}