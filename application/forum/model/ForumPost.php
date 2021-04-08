<?php


namespace app\forum\model;


use think\Model;

class ForumPost extends Model
{
    public function setTopAttr($value)
    {
        if ($value == "on"){
            return 1;
        } else{
            return 0;
        }
    }

    public function getLikesAttr($value)
    {
        $likes =  explode(",", $value);
        foreach ($likes as $k => $v){
            $likes[$k] = (int)$v;
        }
        return $likes;
    }
}