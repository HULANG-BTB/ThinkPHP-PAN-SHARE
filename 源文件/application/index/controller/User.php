<?php
namespace app\index\controller;

use think\Controller;
use think\Validate;
use think\Db;
use think\Cookie;
use think\Session;
use think\Url;
use think\Config;

class User extends Base {

    public function index() {
        $controller = $this->request->controller();
        $this->assign('controller', $controller);
        return $this->view->fetch();
    }

    public function modify() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $rule = [
            'nickname'  => 'require|max:30',
            'phone'  => 'require|regex:1[3-8]{1}[0-9]{9}',
            'email'  => 'require|max:30|email',
        ];
        $field = [
            'nickname'  => '昵称',
            'phone'  => '电话',
            'email'  => '邮件',
        ];
        $validate = new Validate($rule, [] , $field);
        if (!$validate->check($this->request->post())) {
            $ret['msg'] = $validate->getError();
            return json($ret);
        }
        $userId = substr(Session::get('sid'), 45);
        $rst = Db::name('user')->where(['id' => $userId])->update($this->request->post());
        if ($rst) {
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "信息为修改！";
        }
        return json($ret);
    }

    public function avator() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $file = $this->request->file('avator');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'images' . DS . 'avator');
        if ( $info ) {
            $ret['status'] = 1;
            $avatorFile = $info->getSaveName();
            $userId = substr(Session::get('sid'), 45);
            $user = Db::name('user')->where(['id' => $userId])->find();
            Db::name('user')->where(['id' => $userId])->update(['avator' => $avatorFile]);
            unlink( ROOT_PATH . 'public' . DS . 'images' . DS . 'avator' . DS . $user['avator']);
        } else {
            $ret['msg'] = $file->getError();
        }
        return json($ret);
    }

    public function password() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $rule = [
            'password'  => 'require|max:30',
            'newpassword'  => 'require|length:6,20|confirm:renewpassword',
        ];
        $field = [
            'password'  => '原密码',
            'newpassword'  => '新密码',
        ];
        $validate = new Validate($rule, [] , $field);
        if (!$validate->check($this->request->post())) {
            $ret['msg'] = $validate->getError();
            return json($ret);
        }
        $userId = substr(Session::get('sid'), 45);
        $user = Db::name('user')->where(['id' => $userId])->find();
        if ( !$this->pass_check($this->request->post('password'), $user['password']) ) {
                $ret['msg'] = "原密码错误！";
                return json($ret);
        }
        $rst = Db::name('user')->where(['id' => $userId])->update(['password' => $this->pass_encode($this->request->post('newpassword'))]);
        if ( $rst ) {
            $ret['status'] = 1;
            Cookie::set('sid', $this->pass_encode($this->request->post('newpassword')).$user['id']);
            Session::set('sid', $this->pass_encode($this->request->post('newpassword')).$user['id']);
        } else {
            $ret['msg'] = "密码未修改";
        }
        return json($ret);
    }
}
