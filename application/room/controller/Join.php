<?php


namespace app\room\controller;

use app\room\controller\Quit;
use app\user\model\User as UserModel;
use think\Controller;
use app\room\model\RoomStart;
use app\room\model\RoomJoin;
use app\user\model\User;

class Join extends Controller
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

        // 加入新房间会退出原先加入的房间
        $quit = new Quit;
        $quit->index($user);

        $num = $this->request->param("num");
        $room = RoomStart::where('num', $num) ->find();
        if (!$room){
            return [
                'code' => -1,
                'msg' => '房间未被创建或已结束！'
            ];
        }

        $max = $room->getAttr('max');
        $count = RoomJoin::where('num', $num)->count();
        if ($count == $max){
            return [
                'code' => -1,
                'msg' => '房间人数达到'.$max.'人，已满员!'
            ];
        }

        $join = new RoomJoin;
        $data = [
            'num' => $num,
            'uid' => $user->getAttr("id")
        ];
        $res = $join->save($data);

        if ($res){
            return [
                'code' => 0,
                'msg' => '加入房间成功！',
                'data' => [
                    'id' => $room->getAttr("id")
                ]
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '加入房间失败！'
            ];
        }
    }

    public function memberlist()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $num = $this->request->param('num');
        $j = RoomJoin::where("num", $num);
        $join = $j->select();
        foreach ($join as &$member){
            $user = User::where('id', $member['uid'])->find();
            $member['headimg'] = $user->headimg;
            $member['nickname'] = $user->nickname;
        }

        return [
            'code' => 0,
            'msg' => '获得成员列表成功',
            'count' => $j->count(),
            'data' => $join
        ];
    }
}
