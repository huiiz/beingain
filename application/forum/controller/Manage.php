<?php


namespace app\forum\controller;

use app\forum\model\ForumPost;
use app\forum\model\ForumComment;
use app\admin\controller\Base;

class Manage extends Base
{
    public function post()
    {
        return $this->fetch();
    }

    public function postlist()
    {
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $post = ForumPost::where('status', 0)->order('id desc');
        if ($post){
            return [
                'code' => 0,
                'msg' => '获得帖子列表成功！',
                'count' => $post->count(),
                'data' => $post->page($page,$limit)->select()
            ];
        } else{
            return [
                'code' => 0,
                'msg' => '获取帖子列表失败！'
            ];
        }
    }

    public function postedit($id)
    {
        $top = $this->request->param('top');
        $post = ForumPost::get($id);
        $post->top = $top;
        $res = $post->isAutoWriteTimestamp(false)->save();
        if ($res){
            return [
                'code' => 0,
                'msg' => '帖子顶置状态修改成功'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '帖子顶置状态修改失败'
            ];
        }
    }

    public function postdel($id)
    {
        $post = ForumPost::get($id);
        $post->status = 1;
        $res = $post->save();
        if ($res){
            ForumComment::where('pid', $id)->delete();
            return [
                'code' => 0,
                'msg' => '删除帖子成功!'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '删除帖子失败!'
            ];
        }
    }

    public function postform()
    {
        $id = $this->request->param('id');
        $post = ForumPost::get($id);
        $this->assign('post', $post);
        return $this->fetch();
    }

    public function comment()
    {
        return $this->fetch();
    }

    public function commentlist()
    {
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $comment = ForumComment::where('status', 0)->order('id desc');
        if ($comment){
            $res = $comment->page($page,$limit)->select();
            foreach ($res as &$v){
                $v["subject"] = ForumPost::get($v['pid'])->subject;
            }
            return [
                'code' => 0,
                'msg' => '获得帖子列表成功！',
                'count' => $comment->count(),
                'data' => $res
            ];
        } else{
            return [
                'code' => 0,
                'msg' => '获取帖子列表失败！'
            ];
        }
    }

    public function commentdel($id)
    {
        $comment = ForumComment::get($id);
        $comment->status = 1;
        $res = $comment->save();
        if ($res){
            return [
                'code' => 0,
                'msg' => '删除评论成功!'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '删除评论失败!'
            ];
        }
    }

    public function commentform()
    {
        $id = $this->request->param('id');
        $comment = ForumComment::get($id);
        $post = ForumPost::get($comment->pid);
        $this->assign('comment', $comment);
        $this->assign('post', $post);
        return $this->fetch();
    }
}