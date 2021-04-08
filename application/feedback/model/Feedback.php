<?php


namespace app\feedback\model;


use think\Model;

class Feedback extends Model
{
    /*
    public function getStatusAttr($value)
    {
        $data = [
            0 => '未解决',
            1 => '已答复'
        ];
         return $data[$value];
    }*/

    public function setStatusAttr($value)
    {
        if ($value == "on"){
            return 1;
        } else{
            return 0;
        }
    }
}