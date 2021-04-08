<?php


namespace app\home\model;


use think\Model;
use think\model\concern\SoftDelete;

class Collection extends Model
{
    use SoftDelete;
}