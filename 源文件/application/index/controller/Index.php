<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Cookie;
use think\Url;
use think\Db;
use think\Request;

class Index extends Base {
    public function index() {
        $userId = substr(Cookie::get('sid'), 45);
        $file_nums = Db::name('fileinfo')->where(['uid' => $userId])->count();
        $share_nums = Db::name('share')->where(['uid' => $userId])->count();
        $this->assign("file_nums", $file_nums);
        $this->assign("share_nums", $share_nums);

        $controller = $this->request->controller();
        $this->assign('controller', $controller);


        return $this->view->fetch();
    }
}
