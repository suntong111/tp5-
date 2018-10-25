<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 2018/10/24
 * Time: 2:54 PM
 */

namespace app\frontend\controller;

use app\common\controller\ApiController;
use app\common\model\PorderList;

class OrderUser extends ApiController
{
    /**
     * 根据状态获取用户订单
     * @param $union_id
     * @param $status
     * @param $pagenum
     * @param $pagesize
     * */
    public function showorder(){
        $union_id = request()->post('union_id','');
        $status = request()->post('status',-10);
        $pagenum = request()->post('pagenum',0);
        $pagesize = request()->post('pagesize',10);

        if (!isset($union_id) && empty($union_id)){
            $this->showResult(1000);die();
        }

        if ($status == -10){
            $this->showResult(1001);die();
        }

        $res = PorderList::showorder($union_id,$status,$pagenum,$pagesize);

        $this->showResult(200,$res);
    }

}