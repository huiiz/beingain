<?php


namespace app\admin\controller;

use app\admin\model\AdminUser as AdminModel;
use think\Controller;

class Forget extends Controller
{
    public function index()
    {
        return view("forget");
    }

    public function check()
    {
        $username = $this->request->param("username");
        $email = $this->request->param("email");
        $phone = $this->request->param("phone");
        $changecode = $this->request->param("changecode");
        if ($changecode != "2020Beingain"){
            return [
                'code' => -1,
                'msg' => "权限码错误！"
            ];
        }
        $admin = AdminModel::where("email", $email)->find();
        if ($admin == null ||
            $admin->getAttr("username") != $username ||
            $admin->getAttr("phone") != $phone){
            return [
                'code' => -1,
                'msg' => "信息不准确！"
            ];
        }else{
            return [
                'code' => 0,
                '信息匹配成功！'
            ];
        }
    }

    public function reset()
    {
        $admin = new AdminModel;

    }

}