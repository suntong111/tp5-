<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 2018/10/25
 * Time: 2:36 PM
 */

namespace app\common\model;

use think\Db;
use think\Model;

class Invite extends Model
{
    public static function insertdata($from_openid,$to_openid){
        $invite = new self();
        $invite->from_openid = $from_openid;
        $invite->to_openid = $to_openid;
        $invite->status = 1;

        if (!$invite->save()){
            return false;
        }

        return true;
    }

}