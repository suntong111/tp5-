<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/5
 * Time: 9:15
 */


namespace app\wechat\controller;

use think\Controller;


class Common extends Controller{

public function send($phone){
     $code= mt_rand(10000,99999);
    $result = sendMsg($phone, $code);
 //$result['Code'] = 'OK';
 if ($result['Code'] == 'OK') {
     //存到缓存当中,并且返回json数据给前端
     cache('tel' . $phone, $code, 39);
     return json(['success' => 'ok', 'tel' =>$phone]);

 }
}


}