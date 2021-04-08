<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function email()
    {
        $name = $this->request->param("name");
        $value = $this->request->param("value");
        $data = [
            'name' => $name,
            'value' => $value
        ];
        $res = \think\Db::name("email_setting")->data($data)->insert();
        if ($res){
            return [
                'code' => 0,
                'msg' => '插入成功'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '插入失败'
            ];
        }

    }


}
