<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 2018/10/24
 * Time: 2:59 PM
 */

namespace app\common\controller;

use think\Controller;
use think\Config;

class ApiController extends Controller
{
    public function showResult($code=200, $data=[], $imgMark = 0){
        $result = [
            'status'=>$code,
            'message'=>'OK',
        ];

        $result['message'] = $this->getErrorMessage($code);

        if(!empty($data))
            $result['data'] = $data;

        if(empty($imgMark) || $imgMark == 0){
            header ("Access-Control-Allow-Origin:*");
            header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
            header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
            header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
            header ("Pragma: no-cache"); // HTTP/1.0
            header ("Content-type: text/html; charset=utf-8");
        }

        $result = json_encode($result);
        echo $result;
    }

    public function getErrorMessage($error_code = 0){
        $message = 'OK';

        $error_codes = Config::get("error_codes");
        $error_keys = array_keys($error_codes);

        if ($error_code != 200) {
            if(!in_array($error_code, $error_keys))
                $message = '未知的错误！';
            else
                $message = Config::get("error_codes")[$error_code];
        }
        return $message;
    }
}