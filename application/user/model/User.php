<?php


namespace app\user\model;


use think\Model;
use think\model\concern\SoftDelete;
use app\admin\model\EmailSetting;

class User extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function setPasswordAttr($value)
    {
        return MD5($value);
    }

    public function getGenderAttr($value)
    {
        $data = [
            0 => '未知',
            1 => '男',
            2 => '女'
        ];
        return $data[$value];
    }

    public function setGenderAttr($value)
    {
        $data = [
            '男' => 1,
            '女' => 2,
        ];
        return $data[$value];
    }

    public function getStatusAttr($value)
    {
        $data = [
          0 => '正常',
          1 => '禁用'
        ];
        return $data[$value];
    }

    public function getHeadimgAttr($value)
    {
        if ($value){
            return EmailSetting::where('name', 'url')->find()->getAttr("value").$value;
        }
        else{
            return "https://dss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=1066848840,796848454&fm=26&gp=0.jpg";

        }
    }
}