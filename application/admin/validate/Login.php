<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/21
 * Time: 9:35
 */

namespace app\admin\validate;


use think\Validate;

/**
 * 后台登录验证
 * Class Login
 * @package app\admin\validate
 */
class Login extends Validate
{
  protected $rule = [
    'user'=>'require',
    'psd'=>'require',
  ];

  protected $message = [
    'user.require'=>'请输入用户名',
    'psd.require'=>'请输入密码',
  ];
}