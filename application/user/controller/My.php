<?php


namespace app\user\controller;

use app\user\model\User as UserModel;
use think\Controller;

class My extends Controller
{
    public function setheadimg()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $headimg = $this->request->param("headimg");
        $res = $user->save([
            'headimg' => $headimg
        ], ['id' => $user->getAttr("id")]);
        if ($res) {
            return [
                'code' => 0,
                'msg' => '修改用户头像成功！'
            ];
        } else {
            return [
                'code' => -1,
                'msg' => '修改用户头像失败！'
            ];
        }
    }


    public function setinfo()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $nickname = $this->request->param("nickname");
        $email = $this->request->param("email");
        $phone = $this->request->param("phone");
        $gender = $this->request->param("gender");
        $wechat = $this->request->param("wechat");
        $signature = $this->request->param("signature");
        $birthday = $this->request->param("birthday");
        $address = $this->request->param("address");
        $qq = $this->request->param("nickname");

        $checkemail = UserModel::where("email", $email)->find();
        if ($checkemail && ($checkemail->getAttr("username") != $user->getAttr("username"))) {
            return [
                'code' => -1,
                'msg' => '该邮箱已经被别人绑定！'
            ];
        }

//        $checkphone = UserModel::where("phone", $phone)->find();
//        if ($phone && $checkphone && ($checkphone->getAttr("username") != $user->getAttr("username"))) {
//            return [
//                'code' => -1,
//                'msg' => '该手机号已经被别人绑定！'
//            ];
//        }
//
//        $checkqq = UserModel::where("qq", $qq)->find();
//        if ($qq && $checkqq && ($checkqq->getAttr("username") != $user->getAttr("qq"))) {
//            return [
//                'code' => -1,
//                'msg' => '该qq已经被别人绑定！'
//            ];
//        }
//
//        $checkwechat = UserModel::where("wechat", $wechat)->find();
//        if ($wechat && $checkwechat && ($checkwechat->getAttr("username") != $user->getAttr("username"))) {
//            return [
//                'code' => -1,
//                'msg' => '该微信已经被别人绑定！'
//            ];
//        }

        $data = [
            "nickname" => $nickname,
            "email" => $email,
            "phone" => $phone,
            "gender" => $gender,
            "wechat" => $wechat,
            "signature" => $signature,
            "birthday" => $birthday,
            "address" => $address,
            "qq" => $qq,
        ];

        $res = $user->save($data);
        if ($res) {
            return [
                'code' => 0,
                'msg' => '修改用户资料成功!',
            ];
        } else {
            return [
                'code' => -1,
                'msg' => '修改用户资料失败！',
            ];
        }

    }


    public function editpassword()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $oldpass = $this->request->param("oldpass");
        $newpass = $this->request->param("newpass");
        $repass = $this->request->param("repass");


        if ($newpass != $repass) {
            return [
                'code' => -1,
                'msg' => '两次密码不相同！',
            ];
        }
        if ($oldpass == $newpass) {
            return [
                'code' => -1,
                'msg' => '新旧密码不能相同！',
            ];
        }

        if ($user->getAttr("password") != md5($oldpass)) {
            return [
                'code' => -1,
                'msg' => '旧密码错误！',
            ];
        }
        $user->password = $newpass;
        $res = $user->save();
        if ($res) {
            return [
                'code' => 0,
                'msg' => '修改密码成功！',
            ];
        } else {
            return [
                'code' => -1,
                'msg' => '修改密码失败！',
            ];
        }
    }

    public function info()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->hidden(["password", "update_time", "delete_time"])->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        if ($user) {
            return [
                'code' => 0,
                'msg' => '获取用户资料成功！',
                'data' => $user
            ];
        } else {
            return [
                'code' => -1,
                'msg' => '查询用户资料失败！'
            ];
        }
    }

    public function upheadimg()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move('../public/uploads');
        if ($info) {
            // 将“\“替换成”/“
            $headimg = str_replace("\\", "/", $info->getSaveName());
            $res = $user->save([
                'headimg' => 'uploads/' . $headimg
            ]);
            if ($res) {
                return [
                    'code' => 0,
                    'msg' => '上传头像成功!'
                ];
            } else {
                return [
                    'code' => -1,
                    'msg' => '上传头像失败!'
                ];
            }

        } else {
            return [
                'code' => -1,
                'msg' => '上传图片失败!'
            ];
        }
    }

}