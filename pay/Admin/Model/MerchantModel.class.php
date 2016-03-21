<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/10
 * Time: 10:57
 */
namespace Admin\Model;
use Think\Model;
use Util\CommonUtil;
class MerchantModel extends Model
{
    protected $_validate = array(
        array('verify','require','验证码必须！'), //默认情况下用正则进行验证
        array('username','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('merchantname','require','商户名称必填'),
        array('email','email','邮箱地址不正确'),
        array('repassword','password','确认密码不正确',0,'confirm') // 验证确认密码是否和密码一致
       // array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
    );

    protected $_auto = array (
        array('statu','1'),  // 新增的时候把status字段设置为1
        array('create_time','time',3,'function'),
        array('wxtoken','createToken',3,'callback'),
        array('password','md5psw',1,'callback')  // 对password字段在新增和编辑的时候使md5函数处理
    );
    public function md5psw(){
        return md5($_POST['password'].$_POST['salt']);
    }
    public function createToken(){
        return CommonUtil::genernateNonceStr(8);
    }
}