<?php

namespace app\info\controller;

use app\admin\model\AdminUser as AdminModel;
use app\info\model\Info as InfoModel;
use app\admin\controller\Base;
use think\Db;

class Manage extends Base
{

    public function infolist()
    {
        return $this->fetch();
    }

    public function editinfoform()
    {
        $id = $this->request->param("id");
        $info = InfoModel::get($id);
        $this->assign("info", $info);
        return $this->fetch();
    }

    public function getinfolist()
    {
        $info = new InfoModel;
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $allinfo = $info->order('id desc')->
        page($page,$limit)->
        field("id, title, author, status, visit, create_time, last_time");
        $infos = $allinfo->select();
        return [
            'code' => 0,
            'count' => $allinfo->count(),
            'data' => $infos,
        ];
    }

    public function addinfo()
    {
        return $this->fetch();
    }

    public function add()
    {
        $title = $this->request->param("title");
        $author = $this->request->param("author");
        $status = $this->request->param("status");
        $content = $this->request->param("content");
        $last_time = $this->request->param("last_time") .date(' H:i:s');
        $now = time();
        if (strtotime($last_time) < $now){
            return [
                'code' => -1,
                'msg' => '结束时间不能小于当前时间'
            ];
        }
        $info = new InfoModel;
        $res = $info->save([
            "title" => $title,
            "author" => $author,
            "status" => $status,
            "content" => $content,
            "last_time" => strtotime($last_time),
        ]);
        if ($res){
            return [
                'code'=>0,
                'msg'=>'新增公告成功！'
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'保存失败！'
            ];
        }
    }

    public function editinfo($id)
    {
        $title = $this->request->param("title");
        $author = $this->request->param("author");
        $status = $this->request->param("status");
        $content = $this->request->param("content");
        $last_time = $this->request->param("last_time");
        $info = new InfoModel();
        $res = $info->save([
            "title" => $title,
            "author" => $author,
            "status" => $status,
            "content" => $content,
            "last_time" => strtotime($last_time),
        ], ['id' => $id]);
        if ($res){
            return [
                'code'=>0,
                'msg'=>'修改成功！'
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'修改失败！'
            ];
        }
    }

    // 删除单个
    public function delinfo($id)
    {
        $info= InfoModel::get($id);
        if($info->delete()){
            return [
                'code'=>0,
                'msg'=>'删除成功！'
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'删除失败！'
            ];
        }
    }

    // 删除多个
    public function dellist()
    {
        $data = $this->request->param("data");
        $ls = explode(",", $data);
        $res = InfoModel::destroy($ls);
        if ($res){
            return [
                'code' => 0,
                'msg' => '删除成功'
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '删除失败'
            ];
        }

    }
}