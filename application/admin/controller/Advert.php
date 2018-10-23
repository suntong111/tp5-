<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/9/28
 * Time: 9:04
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\Advert as AdvertModel;

/**
 * 平台管理
 * Class Advert
 * @package app\admin\controller
 */
class Advert extends AdminBase
{
    protected $advert_model;

    protected function _initialize()
    {
        parent::_initialize();

        $this->advert_model = new AdvertModel();

    }

    /**
     * 平台管理页面
     */
    public function index()
    {
        $advert = $this->advert_model->select();
        return $this->fetch('index', ['advert' => $advert]);
    }

    /**
     * 配置
     */
    public function edit($id)
    {
        $advert = $this->advert_model->find($id);
        return $this->fetch('edit', ['advert' => $advert]);
    }

    /**
     * 更新页面
     */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $validate_result = $this->validate($data, 'Advert');
            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->advert_model->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

}