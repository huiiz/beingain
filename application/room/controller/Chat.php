<?php
/*
 * Author :  HUII
 */

namespace app\room\controller;


use app\user\model\User as UserModel;
use think\Controller;
use app\room\model\RoomChat as ChatModel;

class Chat extends Controller
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
        $rid = $this->request->param("rid");
        $content = $this->request->param("content");
        $uid= $user->getAttr("id");
        $username = $user->getAttr("username");
        $chat = new ChatModel;
        $res = $chat->save([
            'rid' => $rid,
            'uid' => $uid,
            'username'=> $username,
            'content' => $content
        ]);
        if ($res){
            return [
                'code'=>0,
                'msg'=>'发言成功'
            ];
        } else{
            return [
                'code'=>-1,
                'msg'=>'发言失败'
            ];
        }
    }

    public function chatlist()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $rid = $this->request->param("rid");
        $chats = ChatModel::where('rid', $rid);
        return [
            'code' => 0,
            'count' => $chats->count(),
            'msg' => '获取聊天列表成功',
            'data' => $chats->select()
        ];

    }

}