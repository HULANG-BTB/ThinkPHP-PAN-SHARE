<?php
namespace app\index\controller;

use think\Controller;
use think\Validate;
use think\Db;
use think\Cookie;
use think\Session;
use think\Url;
use think\Config;

class Login extends controller {

    public function _initialize() {
        $this->assign("title", "标题");
        $this->assign("descript", "描述");
        $this->assign("keywords", "关键词");
    }

    private function pass_encode($password) {
        $salt = substr(md5(time()), -5); // 获取随机salt
        $password = sha1(md5($password, $salt)).$salt;
        return $password; // 返回密码长度 40 + salt长度 5
    }

    private function pass_check($password, $ckpassword) {
        $salt = substr($ckpassword, -5);
        $password = sha1(md5($password, $salt)).$salt;
        return $password === $ckpassword;
    }

    public function login() {
        if (Cookie::has('sid') && Session::has('sid') && Cookie::get('sid') === Session::get('sid')) {
            $url = Url::build('index/index/index');
            if ( Session::has('back_url') ) {
                $url = Session::get('back_url');
                Session::delete('back_url');
            }
            return $this->redirect($url);
        }
        return $this->view->fetch();
    }

    public function unlock() {
        if (Cookie::has('sid') && Session::has('sid') && Cookie::get('sid') === Session::get('sid')) {
            $url = Url::build('index/index/index');
            if ( Session::has('back_url') ) {
                $url = Session::get('back_url');
                Session::delete('back_url');
            }
            return $this->redirect($url);
        }
        $userId = substr(Cookie::get('sid'), 45);
        if ( !Db::name('user')->where(['id' => $userId])->count() ) {
                Cookie::delete('sid');
                return $this->redirect("index/login/login");
        }
        $user = Db::name('user')->where(['id' => $userId])->find();
        $this->assign('user', $user);
        return $this->view->fetch();
    }

    public function loginCheck() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $requestData = $this->request->post();
        $remember = $requestData['remember'] == "true" ? 0 : Config::get('cookie.expire');
        unset($requestData['remember']);

        // 数据完整性验证
        $rule = [
            'username'  => 'require|max:30',
            'password'  => 'require|max:30',
        ];
        $field = [
            'username'  => '用户名',
            'password'  => '密码',
        ];
        $validate = new Validate($rule, [] , $field);
        if (!$validate->check($requestData)) {
            $ret['msg'] = $validate->getError();
            return json($ret);
        }

        // 账号验证
        $user = DB::name('user')->where([ 'username' => $requestData['username']])->find();
        if ( !isset($user) ) {
            $ret['msg'] = "用户不存在！";
            return json($ret);
        } else {
            if ( !$this->pass_check($requestData['password'], $user['password']) ) {
                $ret['msg'] = "用户名或密码错误！";
                return json($ret);
            }

            if ( $user['status'] === 0 ) {
                $ret['msg'] = "用户未审核或未通过审核！";
                return json($ret);
            }

        }

        $loginData = [
            'uid' => $user['id'],
            'cip' => get_client_ipaddress(),
        ];
        DB::name('login')->insert($loginData);

        Cookie::set('sid', $user['password'].$user['id'], $remember);
        Session::set('sid', $user['password'].$user['id']);
        $ret['status'] = 1;
        $ret['msg'] = Url::build('index/index/index');
        if (Session::has('back_url')) {
            $ret['msg'] = Session::get('back_url');
            Session::delete('back_url');
        }
        return json($ret);
    }

    public function unlockCheck() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $rule = [
            'password'  => 'require|max:30',
        ];
        $field = [
            'password'  => '密码',
        ];
        $validate = new Validate($rule, [] , $field);
        if (!$validate->check($this->request->post())) {
            $ret['msg'] = $validate->getError();
            return json($ret);
        }
        $userId = substr(Cookie::get('sid'), 45);
        $user = DB::name('user')->where(['id' => $userId])->find();
        $password = $this->request->post('password');
        if ( !$this->pass_check($password, $user['password']) ) {
            $ret['msg'] = "密码错误！";
            return json($ret);
        }
        Session::set('sid', $user['password'].$user['id']);
        $loginData = [
            'uid' => $user['id'],
            'cip' => get_client_ipaddress(),
        ];
        DB::name('login')->insert($loginData);
        Db::name('user')->where(['id' => $user['id']])->update(['lip' => get_client_ipaddress(), 'ltime' => date("Y-m-d H:i:s", time())]);
        $ret['status'] = 1;
        $ret['msg'] = Url::build('index/index/index');
        if (Session::has('back_url')) {
            $ret['msg'] = Session::get('back_url');
            Session::delete('back_url');
        }
        return json($ret);
    }

    public function resetUser() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $rule = [
            'email'  => 'require|email|max:30',
        ];
        $field = [
            'email'  => '邮箱',
        ];
        $validate = new Validate($rule, [] , $field);
        if (!$validate->check($this->request->post())) {
            $ret['msg'] = $validate->getError();
            return json($ret);
        }

        // 执行发送找回邮件操作

        $ret['status'] = 1;
        return json($ret);

    }

    public function Logout() {
        Session::clear();
        Cookie::clear();
        return $this->success('退出成功！');
    }

    public function register() {
        $data = $this->request->post();
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $rule = [
            'username'  => 'require|max:30',
            'nickname'  => 'require|max:30',
            'email'  => 'require|email|max:30',
            'password'  => 'require|length:6,20|max:30',
            'repassword'  => 'require|length:6,20|confirm:password',
        ];
        $field = [
            'username'  => '用户名',
            'nickname'  => '昵称',
            'email'  => '邮箱',
            'password'  => '密码',
        ];
        $validate = new Validate($rule, [] , $field);
        if (!$validate->check($this->request->post())) {
            $ret['msg'] = $validate->getError();
            return json($ret);
        }

        $rst = Db::name('user')
                ->where(['username' => $data['username']])
                ->whereOr(['nickname' => $data['nickname']])
                ->whereOr(['email' => $data['email']])
                ->count();

        if ( $rst ) {
            $ret['msg'] = "用户名、昵称、邮箱已注册或正在审核中！";
            return json($ret);
        }

        unset($data['repassword']);
        $data['password'] = $this->pass_encode($data['password']);
        $data['cip'] = get_client_ipaddress();
        $rst = Db::name('user')->insert($data);
        if ( $rst ) {
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "请联系管理员!";
        }
        return json($ret);
    }

}
