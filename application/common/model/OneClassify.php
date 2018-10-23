<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/28
 * Time: 14:30
 */

namespace app\common\model;


use think\Model;

class OneClassify extends Model
{
   public function twoClassify(){
       return $this->hasMany('TwoClassify','pid','classify_id');
   }

}