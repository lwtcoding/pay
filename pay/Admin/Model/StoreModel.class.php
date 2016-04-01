<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/23
 * Time: 14:55
 */

namespace Admin\Model;

use Think\Model;
class StoreModel extends Model
{
    protected $_validate = array(
        array('name','require','门店名必须！') //默认情况下用正则进行验证

    );
}