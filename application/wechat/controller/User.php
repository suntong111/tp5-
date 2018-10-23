<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/16
 * Time: 15:29
 */

namespace app\wechat\controller;


use app\common\model\PorderDetail;
use think\Controller;

class User extends Controller
{
   public function index(){
       $open_id = input('openid');
       if (!$open_id){
           $this->error('');
       }
   }


   //个人中心订单详情

    public function order($id = ''){
       $order = PorderDetail::where('id',$id)->find();
       if($order){
           return json($order);
       }
    }
}