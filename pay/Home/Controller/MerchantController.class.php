<?php
/**
 * Created by lwt.
 * User: Administrator
 * Date: 2016/3/16
 * Time: 14:06
 */

namespace Home\Controller;

use Think\Controller;
class MerchantController extends Controller
{
    public function merchants(){
//limit=10&offset=10&search=1&sort=id&order=desc
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $pager=[];
            $data=[['id'=>1, 'name'=>'hahah'],['id'=>2, 'name'=>'hahah1']];
            $offset = $_POST['offset'];
            if($offset==0)
                $pager['rows']=[['id'=>1, 'name'=>'hahah'],['id'=>2, 'name'=>'hahah1']];
            if($offset==10)
                $pager['rows']=[['id'=>3, 'name'=>'hahah'],['id'=>4, 'name'=>'hahah1']];


            $pager['total']=25;

            $this->ajaxReturn($pager);
        }
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $this->display();
        }


    }
}