<?php


namespace app\admin\controller;


use think\Controller;

class Home extends Base
{
    public function index()
    {
        $this->assign("name", "Beingain 云自习室");
        return $this->fetch();
    }
}