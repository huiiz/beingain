<?php


namespace app\home\model;


use think\Model;

class Checkin extends Model
{
    public function getDayAttr($value)
    {
        $ls = explode("-", $value);
        return $ls[0].'年'.$ls[1].'月'.$ls[2].'日';
    }
}