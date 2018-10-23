<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/28
 * Time: 14:20
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\OneClassify as OneClassifyModel ;
use app\common\model\TwoClassify ;
use think\Db;

/**
 * 分类管理
 * Class Classify
 * @package app\admin\controller
 */
class Classify extends AdminBase
{

    protected $two_calssify_model;
    protected function _initialize()
    {

        parent::_initialize();
        $this->two_calssify_model = new TwoClassify();
        $modell = new OneClassifyModel();
        $classify = $modell->select();
        $this->assign('classify',  $classify);
    }

    /**
     * 栏目首页
     */
    public function index(){
        $model = OneClassifyModel::with('twoClassify')->order('classify_id','ASE')->select();


      return $this->fetch('index',['model'=>$model]);
    }

    /**
     * 新增页面
     * @param string $pid
     * @return mixed
     */
    public function add($pid=''){

        if ($this->request->isPost()){
            $data = $this->request->post();

            $validate_result = $this->validate($data, 'Classify');
            if ($validate_result !== true) {
                $this->error($validate_result);
            }else{
                $model = new TwoClassify();
                $model->pid = $pid;
                $model->name = $data['name'];
                $model->classify_img = $data['classify_img'];
                if ($model->save()) {
                    $this->success('保存成功');
                }

            }
        }

      return $this->fetch('add',['pid'=>$pid]);
    }


    public function delete($id){
        $modell = new OneClassifyModel();
        $terrace = $modell ->where(['Classify_id' => $id])->find();
        if ($terrace->delete()){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }


}