<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/25
 * Time: 9:04
 */

namespace app\index\controller;


use app\common\controller\ApiController;

class News extends ApiController
{
    /**
     * 信息接口
     */
    public function index(){
        $mode = new \app\common\model\News();
        $model = $mode->order('id DESC')->select();
        if (!$model){
            return json(['code'=>403,'message'=>'未找到信息']);
        }
        return json($model);
    }
}