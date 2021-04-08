<?php
/*
 * Author :  HUII
 */

namespace app\forum\controller;


use think\Controller;

class Upimg extends Controller
{
    public function index()
    {
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move( '../public/uploads');
        $img = 'uploads/'.str_replace("\\", "/",$info->getSaveName());
        return [
            'code' => 0,
            'msg' => '上传图片成功',
            'data' => [
                'path' => $img
            ]
        ];
    }
}