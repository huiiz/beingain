<?php


namespace app\home\controller;

use app\home\model\Note as NoteModel;
use app\user\model\User as UserModel;
use think\Controller;

class Note extends Controller
{
    public function getlist()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $notes = NoteModel::where("uid", $user->getAttr("id"))
            ->order("update_time", "desc")
            ->hidden(['delete_time', 'uid', 'username']);
        if ($notes){
            return [
                'code' => 0,
                'msg' => '获取笔记列表成功！',
                'count' => $notes->count(),
                'data' => $notes->select()
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取笔记列表失败！'
            ];
        }

    }

    public function get()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $id = $this->request->param('id');
        $note = NoteModel::where('id', $id)
            ->hidden(['delete_time', 'uid', 'username'])->find();
        if ($note){
            return [
                'code' => 0,
                'msg' => '获取笔记成功',
                'data' => $note
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取笔记失败'
            ];
        }
    }

    public function add()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user) {
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $title = $this->request->param('title');
        $content = $this->request->param('content');
//        $important = $this->request->param('important');
        $data = [
            'title' => $title,
            'content' => $content,
//            'important' => $important,
            'uid' => $user->getAttr("id"),
            'username' => $user->getAttr("username")
        ];
        $note = new NoteModel;
        $res = $note->save($data);
        if ($res){
            return [
                'code' => 0,
                'msg' => '添加笔记成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '添加笔记失败！'
            ];
        }
    }

    public function edit()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $title = $this->request->param('title');
        $content = $this->request->param('content');
//        $important = $this->request->param('important');
        $id = $this->request->param('id');
        $data = [
            'title' => $title,
            'content' => $content,
//            'important' => $important
        ];
        $note = new NoteModel;
        $res = $note->save($data, ['id' => $id]);
        if ($res){
            return [
                'code' => 0,
                'msg' => '笔记修改成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '笔记修改失败！'
            ];
        }
    }

    public function del()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $id = $this->request->param('id');
        $res = NoteModel::where("id",$id)
            ->where("uid", $user->getAttr("id"))->find()->delete();
        if ($res){
            return [
                'code' => 0,
                'msg' => '删除笔记成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '删除笔记失败！'
            ];
        }
    }


}