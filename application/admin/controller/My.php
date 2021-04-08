<?php


namespace app\admin\controller;


use app\admin\model\AdminUser as AdminModel;

class My extends Base
{
    public function info()
    {
        return $this->fetch();
    }

    public function editInfo()
    {
        $email = $this->request->param('email');
        $nickname = $this->request->param('nickname');
        if (!($email && $nickname) ){
            return [
                'code'=>-1,
                'msg'=>'所填项不能为空！'
            ];
        }
        $checkemail = AdminModel::where("email", $email)->find();
        if($checkemail && $checkemail->getAttr("username") != cookie("username")){
            return [
                'code'=>-1,
                'msg'=>'该邮箱已经被别人绑定！'
            ];
        }
        $username = cookie("username");
        $user = AdminModel::where("username", $username)->find();
        $res = $user->save([
            'email' =>  $email,
            'nickname'  =>  $nickname
        ]);
        if($res){
            return [
                'code'=>0,
                'msg'=>'修改成功！'
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'修改失败！'
            ];
        }
    }

    public function password()
    {
        return $this->fetch();
    }

    public function editPassword()
    {
        $oldPassword = $this->request->param("oldPassword");
        $password = $this->request->param("password");
        $repassword = $this->request->param("repassword");
        $username = cookie("username");
        $user = AdminModel::where("username", $username)->find();
        if (md5($oldPassword) == $user->getAttr("password")){
            if ($oldPassword == $password){
                return [
                    'code'=>-1,
                    'msg'=>'新旧密码相同！'
                ];
            }
            elseif ($password != $repassword){
                return [
                    'code'=>-1,
                    'msg'=>'两次输入密码不一致！'
                ];
            }else{
                $user->password = $password;
                $res = $user->save();
                if($res){
                    return [
                        'code'=>0,
                        'msg'=>'修改成功！'
                    ];
                }else{
                    return [
                        'code'=>-1,
                        'msg'=>'修改失败！'
                    ];
                }
            }
        }else{
            return [
                'code'=>-1,
                'msg'=>'旧密码错误！'
            ];
        }
    }
}