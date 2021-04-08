<?php


namespace app\forum\model;

use think\model\concern\SoftDelete;
use think\Model;

class ForumComment extends Model
{
    use SoftDelete;
}