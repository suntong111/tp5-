<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/29
 * Time: 14:56
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\Order;
use think\Db;

/**
 * 申诉管理
 * Class Appeal
 * @package app\admin\controller
 */
class Appeal extends AdminBase
{

   public function index(){
     $model = Db::name('order')->alias('a')
         ->join('appeal b','a.order_id = b.order_id')
         ->field('a.order_id,a.userName,a.telNumber,a.sellerNme,a.pay_status,b.content,a.form_time')
         ->where('a.pay_status','已支付')
         ->select();


       return $this->fetch('index',['model'=>$model]);
   }



}