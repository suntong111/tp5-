<?php
/**
 * Created by PhpStorm.
 * User: sunt
 * Date: 2018/10/4
 * Time: 8:20
 */

namespace app\wechat\controller;



use aliyunsms\SendSms;
use app\common\model\PhoneVerify;
use app\common\model\User;
use think\Config;
use think\Controller;
use think\Db;

/**
 * 接入微信
 * Class Login
 * @package app\wechat\controller
 */
class Login extends Controller
{
  public function get_open_id(){
      $APPID = Config::get('appid');
      $AppSecret = Config::get('appsecret');
      $code = $_GET['code'];
      $user_nickname=$_GET["user_nickname"];
      $user_gender=$_GET["user_gender"];
      $user_headimg=$_GET["user_headimg"];

      $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $APPID . "&secret=" . $AppSecret . "&js_code=" . $code . "&grant_type=authorization_code";
      $arr = $this->vget($url);// 一个使用curl实现的get方法请求
      $arr = json_decode($arr, true);
       return json($arr);

  }
    // login 接受的参数 {openid，session_key， rawData， signature，iv， encryptedData}
    public function login() {
        $open_id = input('openid');
        $session_key = input('session_key');
        $rawData = json_decode($_GET['rawData'], true);
        $rawData['nickName'] = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $rawData['nickName']);
        $date = $this->decrypt_date($session_key, $_GET['encryptedData'], $_GET['iv']);
        if (!empty($date['unionId'])) {
            $unionid = $date['unionId'];
        } else {
            $unionid = '';
        }
        $userCount = Db::name('user')->where('open_id',$open_id)->count();
        if ($userCount > 0) {
            $userData = Db::name('user')->where('open_id',$open_id)->find();
            if (!empty($date['avatarUrl'])) {
                $b = Db::name("user")->where("open_id", "=", $open_id)->update(['avatar' => $date['avatarUrl']]);
            }
            if (empty($userData['unionid'])) {
                $b = Db::name("user")->where("open_id", "=", $open_id)->update(['unionid' => $unionid]);
            }
        } else {
            $cm = new \app\common\model\User();
            $cm->open_id = $open_id;
            $cm->username = $rawData['nickName'];
            $cm->avatar = $rawData['avatarUrl'];
            $cm->sex = $rawData['gender'];
            $cm->unionid = $unionid;
            $cm->save();
            $id = $cm->id;
            if ($id >= 1) {
                $userData = array('open_id' => $open_id,  'user_name' => $rawData['nickName'], 'avatar' => $rawData['avatarUrl'], 'sex' => $rawData['gender'], 'unionid' => $unionid);
            }
        }
        $arrayinfo = array('userData' => $userData);
        return json($arrayinfo);
        // var_dump($arrayinfo);

    }

    /**
     *
     * @param $url
     * @return mixed
     */
    public function vget($url) {
        $info = curl_init();
        curl_setopt($info, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($info, CURLOPT_HEADER, 0);
        curl_setopt($info, CURLOPT_NOBODY, 0);
        curl_setopt($info, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($info, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($info, CURLOPT_URL, $url);
        $output = curl_exec($info);
        curl_close($info);
        return $output;
    }
    //根据openid获取用户id
    public function user() {
        $open_id = input("open_id");
        $userData = Db::name('user')->where('open_id',$open_id)->find();
        return json($userData);
    }
    //数据解密
    public function decrypt_date($session_key, $encryptedData, $iv) {
        //开发者如需要获取敏感数据，需要对接口返回的加密数据( encryptedData )进行对称解密
        include_once __DIR__ . "/libs/wxBizDataCrypt.php";
        $appid = Config::get('appid');
        $sessionKey = $session_key;
        $encryptedData = $encryptedData;
        $iv = $iv;
        $pc = new \WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode != 0) {
            $data = '';
        }
        $data = json_decode($data, true);
        return $data;
    }

    /**
     * 登录手机验证
     */
    public function sms(){
       if ($this->request->isPost()){
           $data = $this->request->post();
           $validate_result = $this->validate($data, 'Login');
           if ($validate_result !==true){
                $this->error($validate_result);
           }else{
                $userl = new User();
                $user = $userl->where(['phone',$data['phone'],'veiification_phone',$data['password']])->find();
                if ($user){
                     $key = sha1($data['phone'].time());
                     if (empty($user['open_id'])){
                         $user_wechat = false;
                     }else{
                         $user_wechat=true;
                     }

                     $outpot = array('code'=>200,'info'=>'登陆成功','data'=>array('access_token'=>$key,'phone'=>$user['phone'],'user_wechat'=>$user_wechat));
                    exit((json_encode($outpot)));
                }
           }

       }
    }

   //进行验证发送验证码
    public function sen()
    {
        if ($this->request->isPost()) {
            $phone = $this->request->post();

            $validatate_result = $this->validate($phone, 'Login');
            if ($validatate_result !== true) {
                $this->error($validatate_result);
            } else {
                $user = new User();
                $code = mt_rand(10000, 99999);
                $result = sendMsg($phone['phone'], $code);
                if ($result['Code'] == 'OK') {
                        $user->phone = $phone['phone'];
                        $user->verification_code = $code;
                        $user->failure_time = time() + 300;
                        $res1 = $user->save();
                    if ($res1) {
                        return json(['success' => 'ok', 'tel' =>$phone['phone']]);
                    }
                    }
            }

        }
    }





    /**
     * 手机注册接口
     */
    public function register(){
       if ($this->request->isPost()){
           $data = $this->request->post();
           $validate_result = $this->validate($data, 'Login');
           if ($validate_result !==true){
               $this->error($validate_result);
           }else{
               $time = time();
               $model = new PhoneVerify();
               //删除过期验证码
               $model->where("failure_time<$time")->delete();
               //判断用户验证码是否正正确 并且时间没过期
               $re = $model->where(['verification_phone'=>$data['phone'],'verification_code'=>$data['code'],'failure_time'=>$time])->find();
               if ($re){
                   $model->where('verification_phone',$data['phone'])->delete();

                   $mode = new User();
                   $mode->phone = $data['phone'];
                   $mode->register_time = date('Y-m-d H:i:s');
                   $ret = $mode->save();
                   if ($ret){
                       $outopt = array('code'=>200,'info'=>'注册成功');
                       exit(json_encode($outopt));
                   }else{
                       $outopt = array('code'=>402,'info'=>'注册失败');
                       exit(json_encode($outopt));
                   }
               }
           }
       }
    }



}