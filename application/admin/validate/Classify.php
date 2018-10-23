<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/29
 * Time: 8:12
 */

namespace app\admin\validate;


use think\Validate;

class Classify extends Validate
{
  protected $rule = [

      'name'=>'require',


  ];

  protected $message = [

      'name.require'=>'地址不能为空',
  ];

  protected $scene = [
     'add'=>['name']
  ];
}