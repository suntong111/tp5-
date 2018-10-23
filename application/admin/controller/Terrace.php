<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/26
 * Time: 17:16
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\Terrace as TerraceModel;

/**
 * 平台管理
 * Class Terrace
 * @package app\admin\controller
 */
class Terrace extends AdminBase
{

   protected $terrace_model;

   protected function _initialize()
   {
       parent::_initialize();
       $this->terrace_model = new TerraceModel();
   }

    /**
     * 平台展示页面
     */
  public function index(){
      $terrace = $this->terrace_model->select();
  return $this->fetch('index',['terrace'=>$terrace]);
  }

    /**
     * 广告新增
     * @return mixed
     */
  public function add(){
    return $this->fetch();
  }

  public function save(){
      if ($this->request->isPost()){
          $data = $this->request->post();
          $validate_result = $this->validate($data, 'Terrace');

          if ($validate_result !== true){
              $this->error($validate_result);
          }else{
              if ($this->terrace_model->save($data)){
                  $this->success('保存成功');
              }else{
                  $this->error('保存失败');
              }
          }
      }
  }

    /**
     *删除
     */
  public function delete($id){
   $terrace = $this->terrace_model->where(['id' => $id])->find();
   if ($terrace->delete()){
       $this->success('删除成功');
   }else{
       $this->error('删除失败');
   }
  }
}