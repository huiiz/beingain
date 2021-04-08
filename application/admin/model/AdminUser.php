<?php

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class AdminUser extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function setPasswordAttr($value)
    {
        return MD5($value);
    }

    public function getGroupsAttr($value)
    {
        $data = [
            1 => "超级管理员",
            2 => "普通管理员"
        ];

        return $data[$value];
    }
}