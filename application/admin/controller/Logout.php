<?php


namespace app\admin\controller;


class Logout extends Base
{
    // 退出登录
    public function index(){
        session(null);
        cookie('admin_username',null);
        cookie('admin_token',null);
        $this->redirect('/admin/login');
    }
}