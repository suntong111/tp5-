<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/30
 * Time: 10:54
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\Order;
use app\common\model\Sku;
use think\Db;

/**
 * 拼单返现
 * Class Caback
 * @package app\admin\controller
 */
class Caback extends AdminBase
{


    public function index(){


     $model = Db::name('order')
         ->alias('a')
         ->join('sku b','a.good_outno = b.good_outno')
         ->field('a.order_id,a.form_time,a.collage_price,b.collagePrice')
         ->where('collage_status','是')
         ->select();

        return $this->fetch('index',['model'=>$model]);
    }

}