<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/25
 * Time: 14:25
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;

/**
 * 现金提现
 * Class Cash
 * @package app\admin\controller
 */
class Cash extends AdminBase
{

    /**
     * 提现列表
     * @return mixed
     */
    public function index(){

        return $this->fetch('index');
    }

    /**
     * 现金处理
     */
    public  function add(){

    }
}