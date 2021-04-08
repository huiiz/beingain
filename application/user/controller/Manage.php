<?php


namespace app\user\controller;


use app\user\model\User as UserModel;
use app\admin\controller\Base;

class Manage extends Base
{
    public function index()
    {
        return $this->fetch("userlist");
    }

    public function getuserlist()
    {
        $userlist = new UserModel;
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $result = $userlist->order('id insc')->page($page,$limit)->field('id, username, headimg, email, gender, create_time, status');
        $count = $result->count();
        return [
            "code" => 0,
            "msg" => "获取成功！",
            "count" => $count,
            "data" => $result->select()
        ];
    }

    public function userform()
    {
        $type = $this->request->param("type");
        if ($type == 'edit'){
            $id = $this->request->param("id");
            $user = UserModel::get($id);
            $this->assign("user", $user);
        }elseif ($type == 'add'){
            $this->assign("user", null);
        }
        $this->assign("type", $type);
        return $this->fetch();
    }

    // 删除多个
    public function dellist()
    {
        $data = $this->request->param("data");
        $ls = explode(",", $data);
        $res = UserModel::destroy($ls);
        if ($res){
            return [
                'code' => 0,
                'msg' => '删除成功'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '删除失败'
            ];
        }
    }

    public function edituser($id)
    {
        $username = $this->request->param("username");
        $email = $this->request->param("email");
        $phone = $this->request->param("phone");
        $headimg = $this->request->param("headimg");
        $gender = $this->request->param("gender");

        $data = [
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'headimg' => $headimg,
            'gender' => $gender
        ];

        $validate = new \app\user\validate\User;
        $res = $validate->check($data);
        if (!$res){
            return [
                'code'=>-1,
                'msg'=> $validate->getError(),
            ];
        }

        $user =  UserModel::get($id);
        $hasusername = UserModel::where("username", $username);
        $hasemail = UserModel::where("email", $email);
        $hasphone = UserModel::where("email", $phone);
        if($username==$user->username || $hasusername->count() == 0){
            if($email==$user->email || $hasemail->count() == 0){
                if ($phone==$user->phone || $hasphone->count() == 0)
                {
                    $res = $user->save($data);
                    if($res){
                        return [
                            'code'=>0,
                            'msg'=>'修改信息成功！'
                        ];
                    }else{
                        return [
                            'code'=>-1,
                            'msg'=>'修改信息失败！'
                        ];
                    }
                }else{
                    return [
                        'code'=>-1,
                        'msg'=>'电话号码不能重复！'
                    ];
                }

            }else{
                return [
                    'code'=>-1,
                    'msg'=>'电子邮箱不能重复！'
                ];
            }
        }else{
            return [
                'code'=>-1,
                'msg'=>'用户名不能重复！'
            ];
        }

    }
}