<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/28
 * Time: 14:33
 */

namespace app\common\model;


use think\Model;

class TwoClassify extends Model
{

    public function oneClassify(){
        return $this->belongsTo('OneClassify');
    }
}