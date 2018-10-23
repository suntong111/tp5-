<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/4
 * Time: 9:52
 */

namespace app\wechat\validate;


use think\Validate;

class Login extends Validate
{
   protected  $rule = [
       'phone'=>'require',
       ];
   protected $message = [
       'phone.require' =>'手机号码不能为空',
   ];

}