<?php


namespace app\admin\controller;

use app\admin\model\AdminUser as AdminModel;
use think\Db;

class Admin extends Base
{
    public function adminlist()
    {
        return $this->fetch();
    }

    public function getlist()
    {
        $adminlist = new AdminModel;
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $result = $adminlist->order('id');
        return [
                "code" => 0,
                "msg" => "获取管理员列表成功！",
                "count" => $result->count(),
                "data" => $result->page($page,$limit)->select()
            ];
    }

    public function adminform()
    {
        $type = $this->request->param("type");
        if ($type == "edit"){
            $id = $this->request->param("id");
            $adminuser =  AdminModel::get($id);
            $this->assign("adminuser", $adminuser);
            $this->assign("type", "edit");
        } elseif($type == "add"){
            $this->assign("adminuser", null);
            $this->assign("type", "add");
        }

        return $this->fetch();
    }

    public function editadmin($id)
    {
        $username = $this->request->param("username");
        $email = $this->request->param("email");
        $nickname = $this->request->param("nickname");
        $adminuser =  AdminModel::get($id);
        $hasusername = AdminModel::where("username", $username);
        $hasemail = AdminModel::where("email", $email);
        if($username==$adminuser->username || $hasusername->count() == 0){
            if($email==$adminuser->email || $hasemail->count() == 0){
                $adminuser->username = $username;
                $adminuser->email = $email;
                $adminuser->nickname = $nickname;
                $res = $adminuser->save();
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

    public function del($id)
    {
        $adminuser =  AdminModel::get($id);
        $res = $adminuser->delete();
        if($res){
            return [
                'code'=>0,
                'msg'=>'删除成功！'
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'删除失败！'
            ];
        }
    }

    public function addgroup()
    {
        $id = $this->request->param("id");
        $name = $this->request->param("name");
        $data = [
            "id" => $id,
            "name" => $name,
        ];
        $group = Db::name("admin_group")->data($data)->insert();
        if ($group){
            return [
                "code" => 0,
                "msg" => "成功插入"
            ];
        } else{
            return [
                "code" => -1,
                "msg" => "失败"
            ];
        }
    }

    public function group()
    {
        return $this->fetch();
    }

}