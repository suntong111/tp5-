<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/11
 * Time: 10:24
 */

namespace app\wechat\controller;


use Aliyun\Core\Config;
use think\Controller;
use think\Db;

/**
 * 生成二维码接口
 * Class Qcrod
 * @package app\wechat\controller
 */
class Qcrod extends Controller
{


    public function getXcxCode(){
        //获取参数值
               $moduleid=$this->request->get('');
               $itemid=$this->request->get('');
               $url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$this->_getAccessToken();
               $data=[
                   'scene'=>'1001',
                   'width'=>430,
                   'auto_color'=>false,
                   ];
               $data=json_encode($data);
               $result = $this->_requestPost($url,$data);
               if (!$result) {
                   return false;
               }
               $fileName=$moduleid."-".$itemid;
               if ($fileName) {            //判断file文件中是否存在数据库当中
                   $isfile=Db::name('code')->where('fileName=:fileName',['fileName'=>$fileName])->select();
                   if(!$isfile){
                       file_put_contents("static/xcxcode/".$fileName.".jpeg", $result);
                       $datafile=['fileName'=>$fileName];
                       Db::name('code')->insert($datafile);
                   }
                   return "static/xcxcode/".$fileName.".jpeg";
               }
    }

    /**
     * 获取access_token
     * @param string $token_file
     * @return bool|string
     */
    private function _getAccessToken($token_file='./access_token') {
        // 考虑过期问题，将获取的access_token存储到某个文件中
              $life_time = 7200;
               if (file_exists($token_file) && time()-filemtime($token_file)<$life_time) {
                   // 存在有效的access_token
                              return file_get_contents($token_file);
               }        // 目标URL：
                   $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".Config::get('appid')."&secret=".Config::get('appsecret');
               //向该URL，发送GET请求
               $result = $this->_requestGet($url);
               if (!$result) {
                   return false;
               }        // 存在返回响应结果
               $result_obj = json_decode($result);
               // 写入
              file_put_contents($token_file, $result_obj->access_token);
              return $result_obj->access_token;
    }



    protected function _requestGet($url, $ssl=true) {
        // curl完成
              $curl = curl_init();
              //设置curl选项
               curl_setopt($curl, CURLOPT_URL, $url);//URL
              $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
              curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//user_agent，请求代理信息
              curl_setopt($curl, CURLOPT_AUTOREFERER, true);//referer头，请求来源
              curl_setopt($curl, CURLOPT_TIMEOUT, 30);//设置超时时间         //SSL相关
               if ($ssl) {
                   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//禁用后cURL将终止从服务端进行验证
                   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//检查服务器SSL证书中是否存在一个公用名(common name)。
                          }
                          curl_setopt($curl, CURLOPT_HEADER, false);//是否处理响应头
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_exec()是否返回响应结果
                // 发出请求
            $response = curl_exec($curl);
            if (false === $response) {
                echo '<br>', curl_error($curl), '<br>';
                return false;
            }
            curl_close($curl);
            return $response;
    }
    protected function _requestPost($url, $data, $ssl=true) {
        //curl完成
                  $curl = curl_init();
                  //设置curl选项
                   curl_setopt($curl, CURLOPT_URL, $url);//URL
                 $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ' 
                    Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
                 curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//user_agent，请求代理信息
                 curl_setopt($curl, CURLOPT_AUTOREFERER, true);//referer头，请求来源
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);//设置超时时间            //SSL相关
                   if ($ssl) {
                       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false
                       );//禁用后cURL将终止从服务端进行验证

                                     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//检查服务器SSL证书中是否存在一个公用名(common name)。
                               }            // 处理post相关选项
                curl_setopt($curl, CURLOPT_POST, true);// 是否为POST请求
                  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);// 处理请求数据            // 处理响应结果
                 curl_setopt($curl, CURLOPT_HEADER, false);//是否处理响应头
                   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_exec()是否返回响应结果             // 发出请求
                  $response = curl_exec($curl);
                  if (false === $response) {
                      echo '<br>', curl_error($curl), '<br>';
                      return false;
                  }
                  curl_close($curl);
                  return $response;    }






}