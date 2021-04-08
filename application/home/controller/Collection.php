<?php


namespace app\home\controller;


use app\user\model\User as UserModel;
use think\Controller;
use app\home\model\Collection as CollectionModel;
use app\forum\model\ForumPost;

class Collection extends Controller
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

        $sid = $this->request->param("sid");
        if (!$sid){
            return [
                'code' => -1,
                'msg' => '不能为空'
            ];
        }
        $count = CollectionModel::where("uid", $user->getAttr("id"))
            ->where("sid", $sid)->count();
        if ($count!=0){
            return [
                'code' => -1,
                'msg' => '你已经收藏过！'
            ];
        }
        $formpost = ForumPost::get($sid);
        $content = $formpost->content;
        $imgs = $formpost->img;
        $collection = new CollectionModel;
        $res = $collection->save([
            'sid' => $sid,
            'uid' => $user->getAttr("id"),
            'username' => $user->getAttr("username"),
            'imgs' => $imgs,
            'content' => $content
        ]);
        if ($res){
            // 文章收藏数+1
            $formpost->collection += 1;
            $formpost->isAutoWriteTimestamp(false)->save();
            return [
                'code' => 0,
                'msg' => '添加收藏成功！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '添加收藏失败！'
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

        $sid = $this->request->param("sid");
        if (!$sid){
            return [
                'code' => -1,
                'msg' => '不能为空'
            ];
        }
        $collection = CollectionModel::where("uid", $user->getAttr("id"))
            ->where("sid", $sid)->find();
        if ($collection)
        {
            $res = $collection->delete();
            if ($res){
                // 文章收藏数-1
                $formpost = ForumPost::get($sid);
                $formpost->collection -= 1;
                $formpost->isAutoWriteTimestamp(false)->save();
                return [
                    'code' => 0,
                    'msg' => '取消收藏成功!'
                ];
            } else{
                return [
                    'code' => -1,
                    'msg' => '取消收藏失败!'
                ];
            }
        } else{
            return [
                'code' => -1,
                'msg' => '收藏不存在！'
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

        $collection = CollectionModel::where("uid", $user->getAttr("id"))
            ->order("id desc")->hidden(['delete_time', 'uid', 'username']);
        if ($collection)
        {
            return [
                'code' => 0,
                'msg' => '获取收藏列表成功！',
                'count' => $collection->count(),
                'data' => $collection->select()
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取收藏列表失败'
            ];
        }

    }

}