<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 2018/10/23
 * Time: 1:23 PM
 */
namespace app\common\model;

use think\model;

class PorderList extends model
{
    public static function insertdata($data){
        foreach ($data as $val){
            foreach ($val['order_list'] as $v){
                $pl = new self();

                $pl->oreder_receive_time = $v['oreder_receive_time'];
                $pl->custom_parameters = $v['custom_parameters'];
                $pl->type = $v['type'];
                $pl->order_verify_time = $v['order_verify_time'];
                $pl->order_pay_time = $v['order_pay_time'];
                $pl->order_group_success_time = $v['order_group_success_time'];
                $pl->order_modify_at = $v['order_modify_at'];
                $pl->order_status_desc = $v['order_status_desc'];
                $pl->p_id = $v['p_id'];
                $pl->order_status = $v['order_status'];
                $pl->promotion_amount = $v['promotion_amount'];
                $pl->promotion_rate = $v['promotion_rate'];
                $pl->order_create_time = $v['order_create_time'];
                $pl->order_amount = $v['order_amount'];
                $pl->goods_price = $v['goods_price'];
                $pl->goods_quantity = $v['goods_quantity'];
                $pl->goods_thumbnail_url = $v['goods_thumbnail_url'];
                $pl->goods_name = $v['goods_name'];
                $pl->goods_id = $v['goods_id'];
                $pl->order_sn = $v['order_sn'];
                $pl->total_count = '';
                $pl->duo_coupon_amount = '';

                if (!$pl->save()){
                    return false;
                }

                return true;
            }
        }
    }
}