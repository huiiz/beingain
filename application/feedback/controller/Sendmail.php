<?php


namespace app\feedback\controller;


class Sendmail
{
    public function feedback()
    {
        $subject='Beingain回复用户反馈';
        $body=input('back');
        $toemail=input('email');
        $name="Beingain云自习室";
        $r=send_mail($toemail,$name,$subject,$body,$attachment = null);
        if($r){
            return true;
        }else{
            return false;
        }
    }
}