<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 2018/10/25
 * Time: 2:19 PM
 */
namespace app\frontend\controller;

use app\common\controller\ApiController;

class Invite extends ApiController
{
    public function inviteuser(){
        $from_openid = request()->post('from_openid','');
        $to_openid = request()->post('to_openid','');

        $res = \app\common\model\Invite::insertdata($from_openid,$to_openid);

        $this->showResult(200,$res);
    }


}