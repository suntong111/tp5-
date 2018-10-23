<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/20
 * Time: 17:22
 */

namespace app\admin\controller;


use think\Config;
use think\Controller;
use think\Db;
use think\Session;

class Login extends Controller
{

    /**
     * 后台首页
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 登录验证
     */
  public function login(){
      if ($this->request->isPost()){
          $data = $this->request->only(['user','psd']);
          $validatate_result = $this->validate($data,'Login');

          if ($validatate_result !==true){
              $this->error($validatate_result);
          }else{
              $where['user'] = $data['user'];
              $where['psd'] = md5($data['psd'].Config::get('salt'));
              $admin = Db::name('admin')->field('id,user,status')->where($where)->find();

              if (!empty($admin)){
                  if ($admin['status'] !=1){
                      $this->error('当前用户已被禁用');
                  }else{
                      Session::set('admin_id',$admin['id']);
                      Session::set('admin_name',$admin['user']);
                      Db::name('admin')->update([
                         'last_login_time' =>date('Y-m-d H:i:s',time()),
                          'last_login_ip'=>$this->request->ip(),
                          'id'=>$admin['id']
                      ]);
                      $this->success('登陆成功','admin/index/index');
                  }
              }else{
                  $this->error('用户名或者密码错误');
              }

          }
      }

  }

    /**
     * 退出登录
     */
  public function logout(){
      Session::delete('admin_id');
      Session::delete('admin_name');
      $this->redirect('admin/login/index');
  }
}