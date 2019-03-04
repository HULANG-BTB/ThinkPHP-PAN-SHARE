<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Cookie;
use think\Url;
use think\Db;
use think\Request;

class Download extends Base {
    public function index() {
        $controller = $this->request->controller();
        $this->assign('controller', $controller);
        $userId = substr(Cookie::get('sid'), 45);
        return $this->view->fetch();
    }
}
