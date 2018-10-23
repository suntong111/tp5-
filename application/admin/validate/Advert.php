<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/28
 * Time: 13:31
 */

namespace app\admin\validate;


use think\Validate;

class Advert extends Validate
{

    protected $rule = [
        'phone'=>'require',
        'wechat'=>'require',
        'freestyle'=>'require',
        'money'=>'require'
    ];

    protected $message = [
        'phone.require'=>'手机不能为空' ,
        'wechat.require'=>'地址不能为空',
        'freestyle.require'=>'试用天数不能为空',
        'money.require'=>'钱不能为空'
    ];
}