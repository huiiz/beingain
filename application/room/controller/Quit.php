<?php


namespace app\room\controller;

use app\room\model\RoomStart;
use app\room\model\RoomJoin;
use app\user\model\User as UserModel;
use think\Controller;

class Quit extends Controller
{
    public function index($user=null)
    {
        if (!$user){
            $token = $this->request->header('token');
            $user = UserModel::where('token', $token)->find();
            if (!$user){
                return [
                    'code' => -1,
                    'msg' => '该用户未登录或登录状态已失效！'
                ];
            }
        }



//        $num = $this->request->param("num");
//        $count = RoomJoin::where('num', $num)->count();

//        $userin = RoomJoin::where('num', $num)->where('uid', $uid)->find();
        $userin = RoomJoin::where('uid', $user->getAttr("id"))->find();

        if (!$userin){
            return [
                'code' => -1,
                'msg' => '用户未加入房间'
            ];
        }
        $num = $userin->num;
        $count = RoomJoin::where('num', $num)->count();

        $res = $userin->delete();
        if ($res){
            if ($count == 1){
                RoomStart::where('num', $num)->find()->delete();
                return [
                    'code' => 0,
                    'msg' => '退出并销毁该房间！'
                ];
            } else{
                return [
                    'code' => 0,
                    'msg' => '退出该房间！'
                ];
            }
        } else{
            return [
                'code' => -1,
                'msg' => '退出房间出现异常'
            ];
        }
    }
}