<?php


namespace app\home\model;


use think\Model;
use think\model\concern\SoftDelete;

class Backlog extends Model
{
    use SoftDelete;

    /*public function getStatusAttr($value)
    {
        $data = [
            0 => '未完成',
            1 => '已完成'
        ];
        return $data[$value];
    }*/
}