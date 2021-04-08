<?php


namespace app\home\controller;


use app\home\model\Checkin as CheckinModel;
use app\user\model\User as UserModel;
use think\Controller;

class Checkin extends Controller
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

        $content = $this->request->param('content');
        $ip = $this->request->ip();
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $day = date("Y-m-d");
        $count = CheckinModel::where("uid", $user->getAttr("id"))->where("day", $day)->count();
        if ($count == 0){
            $ncount = CheckinModel::where("day", $day)->count()+1;
            $checkin = new CheckinModel;
            $res = $checkin->save([
                'content' => $content,
                'uid' => $user->getAttr("id"),
                'ip' => $ip,
                'agent' => $agent,
                'day' => $day,
                'username' => $user->getAttr("username")
            ]);
            if ($res){
                return [
                    'code' => 0,
                    'msg' => '打卡成功！',
                    'data' => [
                        'num' => $ncount
                    ]
                ];
            } else{
                return [
                    'code' => -1,
                    'msg' => '打卡出现异常！'
                ];
            }
        } else{
            return [
                'code' => -1,
                'msg' => '今天已打过卡！'
            ];
        }

    }

    public function checkinlist()
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

        $list = CheckinModel::where('uid', $user->getAttr("id"))
            ->order('create_time', 'desc')->hidden(['agent', 'update_time', 'username']);
        if ($list){
            return [
                'code' => 0,
                'msg' => '获取打卡记录成功！',
                'count' => $list->count(),
                'data' => $list->select(),
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取打卡记录失败！',
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
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $checkin = CheckinModel::order('create_time desc')
            ->hidden(['agent', 'update_time']);
        if ($checkin){
            return [
                'code' => 0,
                'msg' => '获取签到列表成功！',
                'count' => $checkin->count(),
                'data' => $checkin->page($page, $limit)->select()
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取签到列表失败！'
            ];
        }

    }
}