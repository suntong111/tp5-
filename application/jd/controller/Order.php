<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/25
 * Time: 15:17
 */

namespace app\jd\controller;


use app\common\controller\ApiController;

class Order extends ApiController
{
   public function index(){
       $type=2;
       $pageno=1;
       $page = 20;
       $apiUrl= 'jd.union.open.order.query';
       $get = new Login();
       $order=$get->messag($type,$pageno,$page,$apiUrl);
       return json($order);
   }
}