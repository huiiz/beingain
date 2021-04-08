<?php


namespace app\user\controller;


use app\user\model\User as UserModel;
use think\Controller;

class Logout extends Controller
{
    public function index()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $user->save([
            'token' => ''
        ]);
        return [
            'code' => 0,
            'msg' => '退出登录成功！'
        ];
    }

}