<?php


namespace app\user\controller;


use app\user\model\User as UserModel;
use think\Controller;

class Register extends Controller
{
    public function index()
    {
        $username = $this->request->param('username');
        $email = $this->request->param('email');
        $password = $this->request->param('password');
        $repass = $this->request->param("repass");
        if ($password != $repass){
            return [
                'code'=>-1,
                'msg'=> '两次密码不一致',
            ];
        }
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $password
        ];

        $validate = new \app\user\validate\User;
        $res = $validate->check($data);
        if (!$res){
            return [
                'code'=>-1,
                'msg'=> $validate->getError(),
            ];
        }
        if (UserModel::where("username", $username)->count() != 0){
            return [
                'code' => -1,
                'msg' => '用户名已被注册'
            ];
        }

        $user = UserModel::where('email', $email)->find();
        if(!$user){
            $useradd = new UserModel;
            $result = $useradd->save([
                'username'=>$username,
                'password'=>$password,
                'email'=>$email,
                'nickname'=>$username
            ]);
            if($result){
                return [
                    'code'=> 0,
                    'msg'=>'创建用户成功！'
                ];
            }else{
                return [
                    'code'=>-1,
                    'msg'=>'创建用户失败！'
                ];
            }
        }else{
            return [
                'code'=>-1,
                'msg'=>'该邮箱已被注册！'
            ];
        }
    }
}