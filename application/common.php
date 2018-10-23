<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

//阿里短信函数，$mobile为手机号码，$code为自定义随机数
function sendMsg($mobile,$code){

    //这里的路径EXTEND_PATH就是指tp5根目录下的extend目录，系统自带常量。alisms为我们复制api_sdk过来后更改的目录名称
    require_once EXTEND_PATH.'alisms/vendor/autoload.php';
    Config::load();             //加载区域结点配置

    $accessKeyId = 'LTAIc1NC2iTFox9k';  //阿里云短信获取的accessKeyId

    $accessKeySecret = 'COakiCHLbvuhreEXW1T5qmlTwkDto4';    //阿里云短信获取的accessKeySecret

    //这个个是审核过的模板内容中的变量赋值，记住数组中字符串code要和模板内容中的保持一致
    //比如我们模板中的内容为：你的验证码为：${code}，该验证码5分钟内有效，请勿泄漏！
    $templateParam = array("code"=>$code);           //模板变量替换

    $signName = '拼购返'; //这个是短信签名，要审核通过

    $templateCode = 'SMS_144457090';   //短信模板ID，记得要审核通过的


    //短信API产品名（短信产品名固定，无需修改）
    $product = "Dysmsapi";
    //短信API产品域名（接口地址固定，无需修改）
    $domain = "dysmsapi.aliyuncs.com";
    //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
    $region = "cn-hangzhou";

    // 初始化用户Profile实例
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    // 增加服务结点
    DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
    // 初始化AcsClient用于发起请求
    $acsClient= new DefaultAcsClient($profile);

    // 初始化SendSmsRequest实例用于设置发送短信的参数
    $request = new SendSmsRequest();
    // 必填，设置雉短信接收号码
    $request->setPhoneNumbers($mobile);

    // 必填，设置签名名称
    $request->setSignName($signName);

    // 必填，设置模板CODE
    $request->setTemplateCode($templateCode);

    // 可选，设置模板参数
    if($templateParam) {
        $request->setTemplateParam(json_encode($templateParam));
    }

    //发起访问请求
    $acsResponse = $acsClient->getAcsResponse($request);

    //返回请求结果
    $result = json_decode(json_encode($acsResponse),true);
    return $result;
}









/**
 * 数组层级缩进转换
 * @param $array
 * @param int $pid
 * @param int $level
 * @return array
 */
function array2level($array,$pid=0,$level = 1){
    static $list = [];
    foreach ($array as $v){
        if ($v['pid'] == $pid){
            $v['level'] = $level;
            $list[] = $v;
            array2level($array,$v['id'],$level+1);
        }
    }
    return $list;
}

/**
 * 构建层级
 * @param $array
 * @param string $pid_name
 * @param string $child_key_name
 */
function array2tree(&$array,$pid_name = 'pid',$child_key_name = 'children'){

    $counter = array_children_count($array,$pid_name);
    if (!isset($counter[0]) || $counter[0] == 0){
        return $array;
    }
    $tree = [];
    while (isset($counter[0]) && $counter[0]>0){
        $temp = array_shift($array);
        if (isset($counter[$temp['id']]) && $counter[$temp['id']]>0){
           array_push($array,$temp);
        }else{
            if ($temp[$pid_name] == 0){
                $tree[] = $temp;
            }else{
                $array = array_child_append($array,$temp[$pid_name],$temp,$child_key_name);
            }
        }
        $counter = array_children_count($array,$pid_name);
    }
    return $tree;
}

/**
 * 子元素计算器
 * @param $array
 * @param $pid
 */
function array_children_count($array,$pid)
{
    $counter = [];
    foreach($array as $item){
        $count = isset($counter[$item[$pid]]) ? $counter[$item[$pid]] :0;
        $count++;
        $counter[$item[$pid]] = $count;
    }
    return $counter;
}

/**
 * 元素插入到父级
 * @param $parent
 * @param $pid
 * @param $child
 * @param $child_key_name
 * @return mixed
 */
function array_child_append($parent,$pid,$child,$child_key_name){
    foreach ($parent as &$item){
        if ($item['id'] == $pid){
            if (!isset($item[$child_key_name])){
                $item[$child_key_name] = [];
                $item[$child_key_name][] = $child;
            }
        }
    }
    return $parent;
}

/**
 *错误代码提示
 * @param
 * @param
 * */






