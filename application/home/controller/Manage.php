<?php


namespace app\home\controller;

use app\forum\model\ForumPost;
use app\home\model\Backlog as BacklogModel;
use app\home\model\Checkin as CheckinModel;
use app\home\model\Collection as CollectionModel;
use app\home\model\Note as NoteModel;
use app\admin\controller\Base;

class Manage extends Base
{
    public function checkin()
    {
        return $this->fetch();
    }

    public function checkinlist()
    {
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $checkin = CheckinModel::order('id desc');
        if ($checkin){
            return [
                'code' => 0,
                'msg' => '获得打卡列表成功！',
                'count' => $checkin->count(),
                'data' => $checkin->page($page,$limit)->select()
            ];
        } else{
            return [
                'code' => 0,
                'msg' => '获取打卡列表失败！'
            ];
        }

    }

    public function checkinform()
    {
        $id = $this->request->param('id');
        $checkin = CheckinModel::get($id);
        $this->assign('checkin', $checkin);

        return $this->fetch();
    }

    public function collection()
    {
        return $this->fetch();
    }

    public function collectionlist()
    {
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $collection = CollectionModel::order('id desc');
        if ($collection){
            return [
                'code' => 0,
                'msg' => '获得收藏列表成功！',
                'count' => $collection->count(),
                'data' => $collection->page($page,$limit)->select()
            ];
        } else{
            return [
                'code' => 0,
                'msg' => '获取收藏列表失败！'
            ];
        }

    }

    public function collectionform()
    {
        $id = $this->request->param('id');
        $collection = CollectionModel::get($id);
        $this->assign('collection', $collection);
        $sid = $collection->sid;
        $content = ForumPost::get($sid)->content;
        $this->assign('content', $content);
        return $this->fetch();
    }


    public function note()
    {
        return $this->fetch();
    }

    public function notelist()
    {
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $note = NoteModel::order('id desc');
        if ($note){
            return [
                'code' => 0,
                'msg' => '获得笔记列表成功！',
                'count' => $note->count(),
                'data' => $note->page($page,$limit)->select()
            ];
        } else{
            return [
                'code' => 0,
                'msg' => '获取笔记列表失败！'
            ];
        }

    }

    public function noteform()
    {
        $id = $this->request->param('id');
        $note = NoteModel::get($id);
        $this->assign('note', $note);

        return $this->fetch();
    }

    public function backlog()
    {
        return $this->fetch();
    }

    public function backloglist()
    {
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $backlog = BacklogModel::order('id desc');
        if ($backlog){
            return [
                'code' => 0,
                'msg' => '获得待办事项列表成功！',
                'count' => $backlog->count(),
                'data' => $backlog->page($page,$limit)->select()
            ];
        } else{
            return [
                'code' => 0,
                'msg' => '获取待办事项列表失败！'
            ];
        }

    }

    public function backlogform()
    {
        $id = $this->request->param('id');
        $backlog = BacklogModel::get($id);
        $this->assign('backlog', $backlog);

        return $this->fetch();
    }

}