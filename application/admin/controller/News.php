<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/24
 * Time: 20:29
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;

/**
 * 信息展示
 * Class News
 * @package app\admin\controller
 */
class News extends AdminBase
{
    /**
     * 首页
     */
    public function index(){
      $model = new \app\common\model\News();
      $news = $model->order('id DESC')->select();
        return $this->fetch('index',['news'=>$news]);
    }

    public function add(){
        return $this->fetch();
    }

    public function save(){
        if ($this->request->isPost()){
            $data = $this->request->post();
            $validate_result = $this->validate($data, 'News');
            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                  $model = new \app\common\model\News();
                if ($model->allowField(true)->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }


    public function edit($id){
        $mode = new \app\common\model\News();
        $model = $mode->where('id',$id)->find();
        return $this->fetch('edit',['model'=>$model]);
    }

    public function update($id){
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $validate_result = $this->validate($data, 'News');
            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                $model = new \app\common\model\News();
                if ($model->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }
    public function delete($id){
        $model = new \app\common\model\News();
        $mode = $model->find($id);
        if ($mode->delete()){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}