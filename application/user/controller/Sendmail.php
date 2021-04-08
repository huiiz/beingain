<?php


namespace app\user\controller;

use app\user\model\EmailCode;

class Sendmail
{
    public function getcode(){
        $subject='Beingain验证码';
        $code = mt_rand(100000,999999);
        $body="您正在进行修改密码的操作。<br>您的验证码是：". $code ."。<br>如果不是您的操作，请主动忽略此邮件！";
        $toemail=input('email');
        $name="Beingain云自习室";
        $r=send_mail($toemail,$name,$subject,$body,$attachment = null);
        if($r){
            $emailcode = new EmailCode();
            //记录邮件验证码
            if (EmailCode::where("email", $toemail)->find()){
                $emailcode->save([
                    "code" => $code,
                ], [
                    "email" => $toemail,
                ]);
            } else{
                $emailcode->email = $toemail;
                $emailcode->code = $code;
                $emailcode->save();
            }
            return [
                'code' => 0,
                'msg' => '发送成功'
            ];
        }else{
            return [
                'code' => -1,
                'msg' => '发送失败',
            ];
        }

    }
}