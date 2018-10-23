<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/9
 * Time: 10:50
 */

namespace app\admin\controller;


use app\common\model\Order;
use app\common\model\PorderDetail;
use think\Controller;

/**
 * 拼多多商品展示
 * Class Pin
 * @package app\admin\controller
 */
class Pin extends Controller
{

    /**
     * 查询商品数据
     */
    public function order(){

    }

    /**
     * 首页
     */
    public function index($keyword='',$page=1){
        $map = [];
        if ($keyword){
            $map['username|mobile|email'] = ['like',"%{$keyword}%"];
        }
        $model = new PorderDetail();
        $user_list =$model ->where($map)->order('id DESC')->paginate(15,false,['page'=>$page]);
        return $this->fetch('index',['user_list'=>$user_list,'keyword'=>$keyword]);
    }




}