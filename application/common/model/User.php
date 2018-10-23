<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/21
 * Time: 13:52
 */

namespace app\common\model;


use think\Model;

class User extends Model
{

    protected $insert = [
      'create_time'
    ];

    protected function setCreateTimeAttr(){
        return date('Y-m-d H:i:s');
    }
}