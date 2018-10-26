<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/25
 * Time: 15:12
 */

namespace app\jd\controller;


use app\common\controller\ApiController;

class Login extends ApiController
{
    private $appKey = 'a6088c069b2f499c9c6cfcfa88aa04d5';    //  你的Key
    private $appScret = '77f39e8f3dd147ca94c6b446519ee39a';   //  你的Secret


    public function messag($apiUrl='',$param_json = array(),$version='1.0',$get=false){
        $API['app_key'] = $this->appKey;
        $API['method'] = $apiUrl;
        $API['param_json'] = json_encode($param_json);
        $API['format']= 'JSON';
        $API['sign_method'] = 'md5';
        $API['timestamp'] = date('Y-m-d H:i:s',time());
        $API['v'] = $version;
        $API['time'] = strtotime(date('Y-m-d H:i:s', time()));
        ksort($API);    //  排序
        $str = '';      //  拼接的字符串
        foreach ($API as $k=>$v) $str.=$k.$v;
        $sign = strtoupper(md5($this->appScret.$str.$this->appScret));    //  生成签名    MD5加密转大写
        if ($get){
            //  用get方式拼接URL
            $url = "https://router.jd.com/api?";
            foreach ($API as $k=>$v)
                $url .= urlencode($k) . '=' . urlencode($v) . '&';  //  把参数和值url编码
            $url .= 'sign='.$sign;  //  接上签名
            $res = self::curl_get($url);
        }else{
            //  用post方式获取数据
            $url = "https://router.jd.com/api";
            $API['sign'] = $sign;
            $res = self::curl_post($url,$API);
        }
        return $res;
    }

    //  get请求
    private static function curl_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    //  post请求
    private static function curl_post($url,$curlPost){
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