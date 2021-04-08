<?php


namespace app\user\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
      'username' => 'require',
      'email' => 'email|require',
      'password' => 'min:5',
      'phone' => 'mobile'
    ];

    protected $message = [
        'username' => '用户名不得为空',
        'email.require' =>'邮箱不得为空',
        'email.email' => '邮箱格式错误！',
        'password.min' => '密码长度不得低于5位！',
        'phone.mobile' => '不是手机号码！'
    ];
}