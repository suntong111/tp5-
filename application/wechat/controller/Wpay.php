<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/6
 * Time: 14:16
 */

namespace app\wechat\controller;


use think\Controller;
use think\Loader;
Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

/**
 * 支付接口封装
 * Class Wpay
 * @package app\wechat\controller
 */
class Wpay extends Controller
{
    /**
     * 支付接口
     */
  public function pay(){
      //初始化值对象（在支付框架中填写appid、商家支付账号，初始化附件支付框架） 1/4
      $input = new \WxPayUnifiedOrder();


      //从小程序端拿（用户提交信息）2/4
      $order= $_GET['order_sn'];   //订单号
      $money=$_GET['fee']*1;       //订单额，×1数据类型转换为整数，不然会报错，单位：分
      $body = $_GET['body'];       //商品描述
      $openid = $_GET['open_id'];  //用户openid


      //服务端处理订单（收钱） 3/4
      $input->SetBody($body);                  //文档提及的参数规范：商家名称-销售商品类目
      $input->SetOut_trade_no("$order"); //订单号应该是由小程序端传给服务端的，在用户下单时即生成，demo中取值是一个生成的时间戳
      $input->SetTotal_fee("$money");    //费用应该是由小程序端传给服务端的，在用户下单时告知服务端应付金额，demo中取值是1，即1分钱
      $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
      $input->SetTrade_type("JSAPI");
      $input->SetOpenid($openid);              //由小程序端传给服务端


      //统一下单 4/4
      $order = \WxPayApi::unifiedOrder($input);        //向微信统一下单，并返回order，它是一个array数组
      header("Content-Type: application/json");  //json化返回给小程序端
      echo json_encode($order);
  }
}