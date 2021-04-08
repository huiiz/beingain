<?php


namespace app\admin\controller;

use app\admin\model\EmailSetting;

class System extends Base
{
    public function email()
    {
        $this->assign("secure", $this->getAttr("secure"));
        $this->assign("host", $this->getAttr("host"));
        $this->assign("port", $this->getAttr("port"));
        $this->assign("username", $this->getAttr("username"));
        $this->assign("password", $this->getAttr("password"));
        $this->assign("address", $this->getAttr("address"));
        $this->assign("name", $this->getAttr("name"));
        $this->assign("replyemail", $this->getAttr("replyemail"));
        $this->assign("replyname", $this->getAttr("replyname"));

        return $this->fetch();
    }

    public function setemail()
    {
        $host = $this->request->param("host");
        $port = $this->request->param("port");
        $secure = $this->request->param("secure");
        $address = $this->request->param("address");
        $name = $this->request->param("name");
        $password = $this->request->param("password");
        $data = [
            'secure' => $secure,
            'host' => $host,
            'port' => $port,
            'username' => $address,
            'password' => $password,
            'address' => $address,
            'name' => $name,
        ];
        foreach ($data as $k => $v){
            $this->setAttr($k, $v);
        }
        return [
            'code' => 0,
            'msg' => '修改邮箱设置成功！'
        ];
    }

    public function website()
    {
        return $this->fetch();
    }

    protected function getAttr($name)
    {
        $value = EmailSetting::where("name", $name)->find()->getAttr("value");
        return $value;
    }

    protected function setAttr($name, $value)
    {
        $v = EmailSetting::where("name", $name)->find();
        $v->save([
            "value" => $value,
        ]);
    }
}