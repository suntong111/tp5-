<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/27
 * Time: 15:34
 */

namespace app\admin\validate;


use think\Validate;

class Terrace extends Validate
{

    protected $rule =[
      'type'=>'require',

      'url'=>'require'
    ];

    protected $message = [
      'type.require'=>'不能为空' ,

        'url.require'=>'地址不能为空'
    ];
}