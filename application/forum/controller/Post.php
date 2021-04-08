<?php


namespace app\forum\controller;

use app\forum\model\ForumPost;
use app\home\model\Collection;
use app\user\model\User as UserModel;
use think\Controller;

class Post extends Controller
{
    public function postlist()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user) {
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $post = ForumPost::where('status', 0)
            ->order('top desc')
            ->order('update_time desc')
            ->hidden(['ip', 'status', 'delete_time']);
        return [
            'code' => 0,
            'msg' => '获取帖子列表成功！',
            'count' => $post->count(),
            'data' => $post->page($page, $limit)->select()
        ];
    }

    public function get()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user) {
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }
        $id = $this->request->param("id");
        $post = ForumPost::where("id", $id)->hidden(['ip', 'status'])->find();
        if ($post) {
            $post->visit += 1;
            // 关闭自动时间戳
            $post->isAutoWriteTimestamp(false)->save();
            $post->isAutoWriteTimestamp(true);

            return [
                'code' => 0,
                'msg' => '获取帖子成功！',
                'data' => $post
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

        $pid = $this->request->param('pid');
        $content = $this->request->param('content');
        $imgs = $this->request->param('imgs');
        $ip = $this->request->ip();

        if ($content == null || $content == '') {
            return [
                'code' => -1,
                'msg' => '内容不能为空'
            ];
        }
        $data = [
            'pid' => $pid,
            'uid' => $user->getAttr("id"),
            'username' => $user->getAttr("username"),
            'nickname' => $user->getAttr("nickname"),
            'ip' => $ip,
            'headimg' => $user->getAttr("headimg"),
//            'subject' => $subject,
            'content' => $content,
            'img'=> $imgs
        ];
        $post = new ForumPost;
        $res = $post->save($data);
        if ($res) {
            return [
                'code' => 0,
                'msg' => '新增帖子成功！',
            ];
        } else {
            return [
                'code' => -1,
                'msg' => '新增帖子失败！'
            ];
        }
    }


    public function edit()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user) {
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $id = $this->request->param("id");
        $subject = $this->request->param('subject');
        $content = $this->request->param("content");

        if (!($content && $subject)) {
            return [
                'code' => -1,
                'msg' => '不能为空'
            ];
        }

        $post = ForumPost::get($id);
        if ($user->getAttr("id") != $post->uid) {
            return [
                'code' => -1,
                'msg' => '这不是你的文章！'
            ];
        }
        $post->subject = $subject;
        $post->content = $content;
        $res = $post->save();
        if ($res) {
            return [
                'code' => 0,
                'msg' => '修改成功！'
            ];
        } else {
            return [
                'code' => -1,
                'msg' => '修改失败！'
            ];
        }
    }

    public function del()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user) {
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $id = $this->request->param("id");
        $post = ForumPost::get($id);
        Collection::where('sid', $id)->delete();
        if ($user->getAttr("id") != $post->uid) {
            return [
                'code' => -1,
                'msg' => '这不是你的帖子！'
            ];
        }

        $post->status = 1;
        $res = $post->save();
        if ($res) {
            return [
                'code' => 0,
                'msg' => '删除帖子成功！'
            ];
        } else {
            return [
                'code' => -1,
                'msg' => '删除帖子失败！'
            ];
        }
    }

    public function like()
    {
        $token = $this->request->header('token');
        $user = UserModel::where('token', $token)->find();
        if (!$user) {
            return [
                'code' => -1,
                'msg' => '该用户未登录或登录状态已失效！'
            ];
        }

        $id = $this->request->param("id");
        $type = (int)$this->request->param("type");

        $post = ForumPost::where("id", $id)->hidden(['ip', 'status'])->find();
        if ($post) {
            $likes = $post->getAttr("likes");
            foreach ($likes as $k => $v) {
                if ($v == '') {
                    unset($likes[$k]);
                }
            }
            if ($type == 1) {
                array_push($likes, (int)($user->getAttr("id")));
            } elseif ($type == -1) {
                foreach ($likes as $k => $v) {
                    if ((int)$post->getAttr("likes") == $v) {
                        unset($likes[$k]);
                    }
                }
            }
            $likes = array_unique($likes);
            $post->likes = implode(",", $likes);
            // 关闭自动时间戳
            $post->isAutoWriteTimestamp(false)->save();
            $post->isAutoWriteTimestamp(true);

            return [
                'code' => 0,
                'msg' => '操作成功！',
                'data' => $post
            ];
        }
        else{
            return [
                'code' => -1,
                'msg' => '对应id文章不存在'
            ];
        }

    }
}