<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Cookie;
use think\Url;
use think\Db;
use think\Request;

class Base extends Controller {
    public function _initialize() {
        if ( !Cookie::has('sid') ) {
            Session::set('back_url', $_SERVER['REQUEST_URI']);
            return $this->redirect('index/login/login');
        }
        if ( !Session::has('sid') ) {
            Session::set('back_url', $_SERVER['REQUEST_URI']);
            return $this->redirect('index/login/unlock');
        }
        Session::set('sid', Session::get('sid')); // 更新Sid 时间
        $userId = substr(Session::get('sid'), 45);
        $user = Db::name('user')->where(['id' => $userId])->find();
        $this->assign("user", $user);
    }

    protected function pass_check($password, $ckpassword) {
        $salt = substr($ckpassword, -5);
        $password = sha1(md5($password, $salt)).$salt;
        return $password === $ckpassword;
    }

    protected function pass_encode($password) {
        $salt = substr(md5(time()), -5); // 获取随机salt
        $password = sha1(md5($password, $salt)).$salt;
        return $password; // 返回密码长度 40 + salt长度 5
    }
}
