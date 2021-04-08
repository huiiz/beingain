<?php


namespace app\user\controller;

use app\user\controller\Sendmail;
use think\Controller;
use app\user\model\User as UserModel;
use app\user\model\EmailCode;

class Forget extends Controller
{
    public function index()
    {
        $email = $this->request->param("email");
        $code = $this->request->param("code");
        $newpass = $this->request->param("newpass");
        $user = UserModel::where("email", $email)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户不存在'
            ];
        }
        $emailcode = EmailCode::where("email", $email)->find();
        if ($emailcode){
            $emailcode = $emailcode->getAttr("code");
        } else{
            return [
                'code' => -1,
                'msg' => '验证码错误!',
            ];
        }
        if ($code != $emailcode){
            return [
                'code' => -1,
                'msg' => '验证码错误!',
            ];
        }

        if (md5($newpass) == $user->getAttr('password')){
            return [
                'code' => -1,
                'msg' => '新旧密码相同!',
            ];
        }

        $res = $user->save([
            'password' => $newpass,
        ]);
        if ($res){
            return [
                'code' => 0,
                'msg' => '修改密码成功!'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '修改密码失败!',
            ];
        }


    }

    public function getcode()
    {
        $email = $this->request->param("email");
        $send=new Sendmail();
        return $send->getcode($email);
    }

    public function test()
    {
        $email = $this->request->param("email");
        $code = $this->request->param("code");
        $emailcode = EmailCode::where("email", $email)->find()->getAttr("code");


        if ($code == $emailcode){
            return [
                'code' => 0,
                'msg' => '验证码正确!',
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '验证码错误!',
            ];
        }
    }

}