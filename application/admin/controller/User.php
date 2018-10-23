<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/21
 * Time: 13:50
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use \app\common\model\User as UserModel;
use think\Config;

class User extends AdminBase
{
   protected $user_model ;


    public function _initialize()
    {
     parent::_initialize();
     $this->user_model = new UserModel();
    }

    /**
     * 用户管理
     */
    public function index($keyword='',$page=1){
      $map = [];
      if ($keyword){
          $map['username|mobile|email'] = ['like',"%{$keyword}%"];
      }
      $user_list = $this->user_model->where($map)->order('id DESC')->paginate(15,false,['page'=>$page]);
      return $this->fetch('index',['user_list'=>$user_list,'keyword'=>$keyword]);
    }

    /**
     * 添加页面
     * @return mixed
     */
    public function add(){
        return $this->fetch();
    }

    /**
     * 保存用户
     */
    public function save(){
        if ($this->request->isPost()){
            $data = $this->request->post();
            $validate_result = $this->validate($data, 'User');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                $data['password'] = md5($data['password'] . Config::get('salt'));
                if ($this->user_model->allowField(true)->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }


    /**
     * 用户编辑
     * @param $id
     * @return mixed
     */
    public function edit($id){
        $user = $this->user_model->find($id);

        return $this->fetch('edit', ['user' => $user]);
    }

    /**
     * 用户更新
     * @param $id
     */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'User');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                $user           = $this->user_model->find($id);
                $user->id       = $id;
                $user->username = $data['username'];
                $user->mobile   = $data['mobile'];
                $user->email    = $data['email'];
                $user->status   = $data['status'];
                if (!empty($data['password']) && !empty($data['confirm_password'])) {
                    $user->password = md5($data['password'] . Config::get('salt'));
                }
                if ($user->save() !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    public function delete($id){
    if ($this->user_model->destory($id)){
        $this->success('删除成功');
    }else{
        $this->error('删除失败');
    }
}



}