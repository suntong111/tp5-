<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/21
 * Time: 10:00
 */

namespace app\common\model;


use think\Model;

/**
 * 管理员模型
 * Class Admin
 * @package app\common\model
 */
class Admin extends Model
{
  protected $insert = ['create_time'];

    /**
     * 创建时间
     * @return false|string
     */
  protected function setCreateTimeAttr(){

      return date('Y-m-d H;i;s');
  }
}