<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/24
 * Time: 21:18
 */

namespace app\admin\validate;


use think\Validate;

class News extends Validate
{
    protected $rule = [
        'title'         => 'require',
        'description'         => 'require',

    ];

    protected $message = [
        'username.require'         => '请输入标题',

        'description.require'         => '请输入描述',
    ];
}