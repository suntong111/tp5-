<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/21
 * Time: 13:20
 */

namespace app\common\controller;


use org\Auth;
use think\Controller;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;

/**
 * 后台公用基础控制器
 * Class AdminBase
 * @package app\common\controller
 */
class AdminBase extends Controller
{

protected function _initialize()
{
    parent::_initialize();

    $this->checkAuth();
    $this->getMenu();

    // 输出当前请求控制器（配合后台侧边菜单选中状态）
    $this->assign('controller', Loader::parseName($this->request->controller()));

}

    /**
     *权限检查
     */
protected function checkAuth(){
  if (!Session::has('admin_id')){
       $this->redirect('admin/login/index');
  }
  $module = $this->request->module();
  $controller = $this->request->controller();
  $action     = $this->request->action();

    // 排除权限
    $not_check = ['admin/Index/index', 'admin/AuthGroup/getjson', 'admin/System/clear'];

    if (!in_array($module . '/' . $controller . '/' . $action, $not_check)) {
        $auth     = new Auth();
        $admin_id = Session::get('admin_id');
        if (!$auth->check($module . '/' . $controller . '/' . $action, $admin_id) && $admin_id != 1) {
            $this->error('没有权限');
        }
    }
}

    protected function getMenu(){


        $menu = [];
        $admin_id = Session::get('admin_id');
        $auth = new Auth();

        $auth_rule_list = Db::name('auth_rule')
            ->where('status',1)
            ->order(['sort'=>'DESC','id'=>'Asc'])
            ->select();

        foreach ($auth_rule_list as $value){
            if ($auth->check($value['name'],$admin_id) || $admin_id ==1){
                $menu[] = $value;
            }
        }
        $menu = !empty($menu) ? array2tree($menu) : [];
        $this->assign('menu',$menu);
    }

    public function showResult($code=200, $data=[], $imgMark = 0){
        $result = [
            'status'=>$code,
            'message'=>'OK',
        ];

        $result['message'] = $this->getErrorMessage($code);

        if(!empty($data))
            $result['data'] = $data;

        if(empty($imgMark) || $imgMark == 0){
            header ("Access-Control-Allow-Origin:*");
            header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
            header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
            header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
            header ("Pragma: no-cache"); // HTTP/1.0
            header ("Content-type: text/html; charset=utf-8");
        }

        $result = json_encode($result);
        echo $result;
    }

    public function getErrorMessage($error_code = 0){
        $message = 'OK';

        $error_codes = Config::get("error_codes");
        $error_keys = array_keys($error_codes);

        if ($error_code != 200) {
            if(!in_array($error_code, $error_keys))
                $message = '未知的错误！';
            else
                $message = Config::get("error_codes")[$error_code];
        }
        return $message;
    }
}