<?php


namespace app\info\model;


use think\Model;
use think\model\concern\SoftDelete;

class Info extends Model
{
    use SoftDelete;


    public function setStatusAttr($value)
    {
        if ($value == "on"){
            return 1;
        } else{
            return 0;
        }
    }


    public function getLastTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

}