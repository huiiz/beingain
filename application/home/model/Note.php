<?php


namespace app\home\model;


use think\Model;
use think\model\concern\SoftDelete;

class Note extends Model
{
    use SoftDelete;
}