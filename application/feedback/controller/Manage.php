<?php


namespace app\feedback\controller;

use app\feedback\model\Feedback;
use app\admin\controller\Base;
use app\feedback\controller\Sendmail;

class Manage extends Base
{
    public function feedlist()
    {
        return $this->fetch();
    }

    public function getlist()
    {
        $feedback = Feedback::order('id desc');
        if ($feedback){
            return [
                'code' => 0,
                'msg' => '获得反馈列表成功！',
                'count' => $feedback->count(),
                'data' => $feedback->select()
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获得反馈列表失败！'
            ];
        }

    }

    public function feedform($id)
    {
        $day = $day = date("Y-m-d");
        $feedback = Feedback::get($id);
        $this->assign('feedback', $feedback);
        $back = $feedback->username.'，你好！<br />你的反馈我们已经收到了。我们将会认真参考你提出的建议！
        感谢你对<b>Beingain云自习室</b>项目的支持！<br />Beingain云自习室<br />'.$day;
        $this->assign('back', $back);
        return $this->fetch();
    }

    public function reply($id)
    {
        $email = $this->request->param('email');
        $back = $this->request->param('back');
        $send=new Sendmail();
        if ($send->feedback($email, $back)){
            $feedback = Feedback::get($id);
            $data = [
                'back' => $back,
                'solved' => 1
            ];
//            $feedback->back = $back;
//            $feedback->status = 1;
            $res = $feedback->save($data);
            if ($res){
                return [
                    'code' => 0,
                    'msg' => '答复反馈成功!'
                ];
            } else{
                return [
                    'code' => -1,
                    'msg' => '答复反馈失败'
                ];
            }
        } else{
            return [
                'code' => -1,
                'msg' => '发送邮件失败！'
            ];
        }
    }
}