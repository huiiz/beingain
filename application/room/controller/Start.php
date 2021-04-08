<?php


namespace app\room\controller;


use app\room\model\RoomJoin;
use app\user\model\User as UserModel;
use think\Controller;
use app\room\model\RoomStart;
use app\user\model\User;

class Start extends Controller
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

        $max = $this->request->param('max');
        if ($max < 2){
            return [
                'code' => -1,
                'msg' => '房间人数不得少于2人！'
            ];
        } elseif ($max > 6){
            return [
                'code' => -1,
                'msg' => '房间人数不得多于6人！'
            ];
        }

        // 以时间戳作为房间编号
        $num = time()%1000000;

        $roomstart = new RoomStart;
        $data = [
            'uid' => $user->getAttr("id"),
            'max' => $max,
            'num' => $num
        ];
        $res = $roomstart->save($data);
        if ($res){
            return [
                'code' => 0,
                'msg' => '房间创建成功',
                'data' => [
                    'num' => $num,
                ],
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '房间创建失败'
            ];
        }
    }

    // 获取正在进行的房间列表
    public function roomlist()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $rooms = RoomStart::order('id desc');
        $count = $rooms->count();
        $res = $rooms->hidden(['delete_time'])->select();
        foreach ($res as &$room){
            $num = $room->num;
            $roomcount = RoomJoin::where('num', $num)->count();
            $room['count'] = $roomcount;
            $room['creater'] = User::where('id', $room['uid'])->find()->getAttr('nickname');
        }
        return [
            'code' => 0,
            'msg' => '获得自习室房间列表成功!',
            'count' => $count,
            'data' => $res
        ];

    }
}