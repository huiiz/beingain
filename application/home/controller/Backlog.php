<?php


namespace app\home\controller;

use app\home\model\Backlog as BacklogModel;
use app\home\model\Collection as CollectionModel;
use app\user\model\User as UserModel;
use think\Controller;

class Backlog extends Controller
{
    public function add()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $user = UserModel::where('token', $token)->find();
        $content = $this->request->param('content');
        $deadline = $this->request->param('deadline');
        $important = $this->request->param('important');
        if (!($content&&$deadline)){
            return [
                'code' => -1,
                'msg' => '不能为空!'
            ];
        }
        $data = [
            'uid' => $user->getAttr("id"),
            'username' => $user->getAttr('username'),
            'content' => $content,
            'deadline' => $deadline,
            'important' => $important?$important:0
        ];
        $backlog = new BacklogModel;
        $res = $backlog->save($data);
        if ($res){
            return [
                'code' => 0,
                'msg' => '新增待办事项成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '新增待办事项失败！'
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
        $id = $this->request->param("id");
        $res = BacklogModel::get($id)->delete();
        if ($res){
            return [
                'code' => 0,
                'msg' => '删除待办事项成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '删除待办事项失败！'
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
        $id = $this->request->param("id");
        if (!$id){
            return [
                'code' => -1,
                'msg' => '不能为空！',
            ];
        }
        $important = $this->request->param("important");
        $content = $this->request->param('content');
        $deadline = $this->request->param('deadline');
        $status = $this->request->param('status');
        $backlog = BacklogModel::get($id);
        if ($important || $important == 0){
            $backlog->important = $important;
        }
        if ($status || $status == 0){
            $backlog->status = $status;
        }
        if ($content){
            $backlog->content = $content;
        }
        if ($deadline){
            $backlog->deadline = $deadline;
        }
        $res = $backlog->save();
        if ($res){
            return [
                'code' => 0,
                'msg' => '修改待办成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '修改待办失败！'
            ];
        }
    }

    public function changestatus()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $id = $this->request->param("id");
        if (!$id){
            return [
                'code' => -1,
                'msg' => '不能为空！',
            ];
        }
        $backlog = BacklogModel::get($id);
        $backlog->status = !$backlog->status;
        $res = $backlog->save();
        if ($res){
            return [
                'code' => 0,
                'msg' => '修改状态成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '修改状态失败！'
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
        $id = $this->request->param("id");
        $backlog = BacklogModel::where("id", $id)
            ->hidden(['delete_time', 'username', 'uid'])->find();
        if ($backlog){
            return [
                'code' => 0,
                'msg' => '获取待办事件成功！',
                'data' => $backlog
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取待办事件失败！'
            ];
        }
    }

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
        $backlog = BacklogModel::where('uid', $user->getAttr("id"))
            ->hidden(['delete_time', 'username', 'uid']);
        if ($backlog){
            return [
                'code' => 0,
                'msg' => '获取待办列表成功！',
                'count' => $backlog->count(),
                'data' => $backlog->select()
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取待办列表失败！'
            ];
        }
    }
}