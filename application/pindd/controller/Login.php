<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/10
 * Time: 14:37
 */

namespace app\pindd\controller;


use app\common\controller\AdminBase;
use think\Controller;

class Login extends Controller
{
    protected $client_id = '0d1031c489a84e24a67350a5351b3d3b';    //  你的client_id
    protected $client_secret = '99842b6f4ed6fd3404477928e01a51ca15087a4a';   //  你的client_secret

    public function gett($apitype)    {
        $param['client_id'] = $this->client_id;
        $param['type'] =  $apitype;
        $param['data_type'] = 'JSON';
        $param['timestamp'] = self::getMillisecond();
        $param['start_update_time'] =mktime(0,0,0,date('m'),date('d')-90,date('Y')) ;
        $param['end_update_time'] =  mktime(0,0,0,date('m'),date('d'),date('Y')-1);

        ksort($param);    //  排序
        $str = '';      //  拼接的字符串
        foreach ($param as $k => $v) $str .= $k . $v;
        $sign = strtoupper(md5($this->client_secret. $str . $this->client_secret));    //  生成签名    MD5加密转大写
        $param['sign'] = $sign;
        $url = 'http://gw-api.pinduoduo.com/api/router';
        return self::curl_post($url, $param);
    }

    //  post请求
    private static function curl_post($url, $curlPost)    {
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
        return $result;    }
        //  获取13位时间戳
    private static function getMillisecond()    {
        list($t1, $t2) = explode(' ', microtime());
        return sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);    }











}