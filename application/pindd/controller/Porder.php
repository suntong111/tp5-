<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/12
 * Time: 16:49
 */

namespace app\pindd\controller;


use app\common\controller\AdminBase;

class Porder extends AdminBase
{

    /**
     * 订单详情
     */
    public function index(){
        $apitype = 'pdd.ddk.order.list.increment.get';

        $get = new Login();
       $order=$get->gett( $apitype);



       var_dump($order);
    }



}