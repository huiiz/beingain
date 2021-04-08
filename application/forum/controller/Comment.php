<?php


namespace app\forum\controller;

use app\forum\model\ForumPost;
use app\forum\model\ForumComment;
use app\user\model\User as UserModel;
use think\Controller;

class Comment extends Controller
{
    public function commentlist()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $pid = $this->request->param("pid");
        $comment = ForumComment::where("pid", $pid)
            ->where("status", 0)
            ->order('position')
            ->hidden(['ip', 'status']);
        return [
            'code' => 0,
            'msg' => '获取评论成功',
            'count' => $comment->count(),
            'data' => $comment->select()
        ];
    }

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

        $pid  = $this->request->param("pid");
        $content = $this->request->param("content");
        $ip = $this->request->ip();
        if (!$content) {
            return [
                'code' => -1,
                'msg' => '不能为空'
            ];
        }

        $position = ForumComment::where("pid", $pid)->count()+1;

        $data = [
            'pid' => $pid,
            'position' => $position,
            'uid' => $user->getAttr("id"),
            'headimg' => $user->getAttr("headimg"),
            'nickname' => $user->getAttr("nickname"),
            'username' => $user->getAttr("username"),
            'ip' => $ip,
            'content' => $content
        ];
        $comment = new ForumComment;
        $res = $comment->save($data);
        if ($res){
            $post = ForumPost::get($pid);
            $post->comment += 1;
            $post->save();
            return [
                'code' => 0,
                'msg' => '新增评论成功！',
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '新增评论失败！'
            ];
        }
    }


    //评论还是不要进行修改操作了。
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
        $content = $this->request->param("content");

        if (!$content){
            return [
                'code' => -1,
                'msg' => '不能为空'
            ];
        }
        $comment = ForumComment::get($id);
        $comment->content = $content;
        $res = $comment->save();
        if ($res){
            return [
                'code' => 0,
                'msg' => '修改成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '修改失败！'
            ];
        }
    }

    public function del()
    {
        // 登录验证
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user){
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $id = $this->request->param("id");
        $comment = ForumComment::get($id);
        if ($user.getAttr("id") != $comment->uid){
            return [
                'code' => -1,
                'msg' => '这条不是你的评论！'
            ];
        }

        $comment->status = 1;
        $res = $comment->save();
        if ($res){
            $post = ForumPost::get($comment->pid);
            $post->comment -= 1;
            $post->isAutoWriteTimestamp(false)->save();
            return [
                'code' => 0,
                'msg' => '删除评论成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '删除评论失败！'
            ];
        }
    }
}