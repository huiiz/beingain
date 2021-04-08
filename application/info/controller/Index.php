<?php


namespace app\info\controller;


use think\Controller;
use app\info\model\Info as InfoModel;

class Index extends Controller
{
    public function index()
    {
        $id = $this->request->param("id");
        $info = InfoModel::where("id", $id)
            ->hidden(["update_time", "delete_time"])->find();

        if ($info){
            $now = time();
            if (strtotime($info->getAttr("last_time")) < $now){
                return [
                    'code' => -1,
                    'msg' => '公告已过期！',
                ];
            }
            $inf = InfoModel::get($id);
            $inf->visit += 1;
            $inf->isAutoWriteTimestamp(false)->save();
            return [
                'code' => 0,
                'msg' => '公告获取成功！',
                'data' => $info,
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '公告获取失败！',
            ];
        }
    }

    public function infolist()
    {
        $now = time();
        $infos = InfoModel::where("last_time", ">", $now)
            ->where("status", 1);

        if ($infos)
        {
            return [
                'code' => 0,
                'msg' => '成功获取公告列表！',
                'count' => $infos->count(),
                'data' => $infos->select()
            ];
        } else{
            return [
                'code' => -1,
                'msg' => '获取公告列表失败！'
            ];
        }

    }
}