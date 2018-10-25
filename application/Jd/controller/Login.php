<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/25
 * Time: 11:24
 */

namespace app\jd\controller;


use app\common\controller\ApiController;

/**
 * 京东授权
 * Class Login
 * @package app\jd\controller
 */
class Login extends ApiController
{
    private $appKey = '96F8924EA36CA74F04696B6CE3CDA4D8';    //  你的Key
    private $appScret = ' 6cceb6f493e642a781efcedb86c710a3';   //  你的Secret

    /**
     * 获取接口数据
     * @param string $apiUrl    要获取的api
     * @param string $param_json   该api需要的参数
     * @param string $version   版本可选为 2.0
     * @param bool $get 是否使用get，默认为post方式
     * @return mixed    京东返回的json格式的数据
     */
    public function GetKelperApiData($apiUrl='',$version='1.0',$get=false){
        $API['app_key'] = $this->appKey;
        $API['method'] = $apiUrl;
        $API['format'] = 'json';
        $API['sign_method'] = 'md5';
        $API['timestamp'] = date('Y-m-d H:i:s',time());
        $API['v'] = $version;
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
    //  获取13位时间戳
    private static function  getMillisecond(){
        list($t1, $t2) = explode(' ', microtime());
        return sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }


}