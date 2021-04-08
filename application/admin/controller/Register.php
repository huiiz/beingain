<?php


namespace app\admin\controller;

use app\admin\model\AdminUser as AdminModel;
use think\Controller;


class Register extends Controller
{
    public function index()
    {
        return view('reg');
    }

    // 注册
    public function register()
    {
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $email = $this->request->param('email');
        $phone = $this->request->param('phone');
        $nickname = $this->request->param('nickname');
        $invite = $this->request->param('invite');
        if($invite != "Beingain2020"){
            return [
                'code'=>-1,
                'msg'=>'邀请码错误！请核对邀请码后再进行注册！'
            ];
        }
        if (!($username && $password && $email && $nickname)){
            return [
                'code'=>-1,
                'msg'=>'不能为空'
            ];
        }
        $user = AdminModel::where('username', $username)->find();
        if(!$user){
            if(AdminModel::where('email', $email)->find()){
                return [
                    'code'=>-1,
                    'msg'=>'该邮箱已被其他管理员绑定！'
                ];
            }
            if(AdminModel::where('phone', $phone)->find()){
                return [
                    'code'=>-1,
                    'msg'=>'该电话号码已被其他管理员绑定！'
                ];
            }
            $adminadd = new AdminModel();
            $result = $adminadd->save([
                'username'=>$username,
                'password'=>$password,
                'email'=>$email,
                'phone' => $phone,
                'nickname'=>$nickname
            ]);
            if($result){
                return [
                    'code'=>0,
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
                'msg'=>'账号已被注册！'
            ];
        }


    }
}