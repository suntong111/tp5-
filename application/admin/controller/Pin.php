<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/9
 * Time: 10:50
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\Order;
use app\common\model\PorderDetail;
use app\common\model\PorderList;
use think\Config;
use think\Controller;

/**
 * 拼多多商品展示
 * Class Pin
 * @package app\admin\controller
 */
class Pin extends AdminBase
{

    /**
     * 查询商品数据
     */
    public function order(){

    }

    /**
     * 首页展示
     */
    public function index($keyword='',$page=1){
        $map = [];
        if ($keyword){
            $map['username|mobile|email'] = ['like',"%{$keyword}%"];
        }
        $model = new PorderList();
        $user_list =$model ->where($map)->order('id DESC')->paginate(15,false,['page'=>$page]);
        return $this->fetch('index',['user_list'=>$user_list,'keyword'=>$keyword]);
    }

    /**
     * 数据插入
     * */
    public function insertorderdata(){
        $client_id = Config::get('client_id');
        $secret = Config::get('client_secret');

        $param['client_id'] = $client_id;
        $param['type'] =  'pdd.ddk.order.list.increment.get';
        $param['data_type'] = 'JSON';
        $param['timestamp'] = time();
        $param['start_update_time'] = time()-(24*3600);
        $param['end_update_time'] =  time();

        ksort($param);    //  排序
        $str = '';      //  拼接的字符串
        foreach ($param as $k => $v) $str .= $k . $v;
        $sign = strtoupper(md5($secret. $str . $secret));    //  生成签名    MD5加密转大写
        $param['sign'] = $sign;
        $url = 'http://gw-api.pinduoduo.com/api/router';
        $data = $this->curl_post($url,$param);

        if(!isset($data) && empty($data)) return false;

        $res = PorderList::insertdata($data);
        return $res;
    }

    public function curl_post($url,$curlPost){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }



}