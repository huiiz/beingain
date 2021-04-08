<?php


namespace app\feedback\controller;

use app\feedback\model\Feedback;
use app\user\model\User as UserModel;
use think\Controller;

class User extends Controller
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

        $subject = $this->request->param('subject');
        $content = $this->request->param('content');
        $data = [
            'uid' => $user->getAttr("id"),
            'username' => $user->getAttr("username"),
            'email' => $user->getAttr("email"),
            'subject' => $subject,
            'content' => $content
        ];
        $feed = new Feedback;
        $res = $feed->save($data);
        if ($res){
            return [
                'code' => 0,
                'msg' => '提交反馈成功!我们会尽快予以答复！'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '提交反馈失败！'
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

        $feeds = Feedback::where('uid', $user->getAttr("id"))->hidden(['uid', 'username', 'email']);
        if ($feeds){
            return [
                'code' => 0,
                'msg' => '获取反馈列表成功！',
                'count' => $feeds->count(),
                'data' => $feeds->select()
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取反馈列表失败！',
            ];
        }
    }
}