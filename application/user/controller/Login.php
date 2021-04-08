<?php


namespace app\user\controller;


use app\user\model\UserLog as LogModel;
use app\user\model\User as UserModel;
use think\Controller;

class Login extends Controller
{
    public function index()
    {

        $loginname = $this->request->param('loginname');
        $password = $this->request->param('password');
        $ip = $this->request->ip();
        $agent = $_SERVER['HTTP_USER_AGENT'];

        if (!($loginname && $password) ){
            return [
                'code'=>-1,
                'msg'=>'用户名或密码不能为空！'
            ];
        }

        // 判断是否存在该用户名或者电子邮箱
        $email = UserModel::where("email", $loginname)->find();
        if ($email){
            $user = $email;
        } else{
            $username = UserModel::where("username", $loginname)->find();
            if ($username){
                $user = $username;
            } else{
                return [
                    'code'=>-1,
                    'msg'=>'账号不存在！'
                ];
            }
        }


        if ($user->getAttr("password") == md5($password)){
            if ($user->getAttr("status") != 0){
                return [
                    'code'=>-1,
                    'msg'=>'该账号已被禁用！'
                ];
            }

            $token = self::generateToken();
            $user->token = $token;
            $res = $user->save();
            if ($res){
                $this->login_log($user->id, $ip, $agent);
                return [
                    'code'=>0,
                    'msg'=>'登录成功！',
                    "data"=>[
                        "token"=>$token
                    ]
                ];
            }

        }else{
            return [
                'code'=>-1,
                'msg'=>'密码错误！'
            ];
        }

    }

    protected function login_log($uid, $ip, $agent)
    {
        $data = [
            'uid' => $uid,
            'ip' => $ip,
            'agent' => $agent
        ];
        $log = new LogModel;
        $log->save($data);
    }

    // 获取token
    protected  static  function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }
    // 生成令牌
    protected static function generateToken()
    {
        $randChar = self::getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $tokenSalt = 'GJUHMkojsojsnafftswiwiwDEDeqfshqs12445627672ksoksow';
        return md5($randChar . $timestamp . $tokenSalt);
    }

}