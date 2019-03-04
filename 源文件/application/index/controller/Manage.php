<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Cookie;
use think\Url;
use think\Db;
use think\Request;
use think\Config;
use think\Validate;

class Manage extends Base {

    // 绑定前置方法
    protected $beforeActionList = [
        'authCheck'
    ];

    public function authCheck() {
        $userId = substr(Session::get('sid'), 45);
        $user = Db::name('user')->where(['id' => $userId])->find();
        if ( !$user['admin'] ) {
            return $this->error("非法请求！");
        }
    }

    public function config() {
        $controller = $this->request->controller();
        $this->assign('controller', $controller);
        $config = Config::get('qcloudcos');
        $this->assign('config', $config);
        $userId = substr(Cookie::get('sid'), 45);
        return $this->view->fetch();
    }

    public function qcloudcos() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];

        $data = $this->request->post();
        // 数据完整性验证
        $rule = [
            'Url'  => 'require',
            'Domain'  => 'require',
            'APPID'  => 'require',
            'Bucket'  => 'require',
            'Region'  => 'require',
            'SecretId'  => 'require',
            'SecretKey'  => 'require',
            'AllowPrefix'  => 'require',
        ];
        $field = [
            'Url'  => 'Url',
            'Domain'  => 'Domain',
            'APPID'  => 'APPID',
            'Bucket'  => 'Bucket',
            'Region'  => 'Region',
            'SecretId'  => 'SecretId',
            'SecretKey'  => 'SecretKey',
            'AllowPrefix'  => 'AllowPrefix',
        ];
        $validate = new Validate($rule, [] , $field);
        if (!$validate->check($data)) {
            $ret['msg'] = $validate->getError();
            return json($ret);
        }
        $config = Config::get('qcloudcos');
        $content = file_get_contents(APP_PATH . '/extra/QcloudCos.php');
        foreach ($data as $key => $value) {
            if ( isset( $config[$key] ) ) {
                $content = str_replace("'$key' => '$config[$key]'", "'$key' => '$value'", $content);
            }
        }
        $rst = file_put_contents(APP_PATH . '/extra/QcloudCos.php', $content);
        if ( $rst ) {
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "保存配置文件错误！";
        }
        return json($ret);
    }

    public function file() {
        $controller = $this->request->controller();
        $this->assign('controller', $controller);
        return $this->view->fetch();
    }

    public function filelist() {
        $ret = [
            'status' => 1,
            'msg' => null,
            'count' => 0,
            'data' => [],
        ];
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        $ret['count'] = Db::name('fileinfo')->where('type', '=', '1')->count();
        $list = Db::name('fileinfo f')
                    ->join('user u', 'f.uid = u.id')
                    ->field(["f.id" => "id", "f.name" => "fileName", "f.ctime" => "crateTime", "f.size" => "size", "f.type" => "type", "f.path" => "path", "u.username" => 'username'])
                    ->order(['id' => "ASC"])
                    ->where('type', '=', '1')
                    ->page($page, $limit)
                    ->select();
        $ret['data'] = $list;
        return json($ret);
    }

    public function user() {
        $controller = $this->request->controller();
        $this->assign('controller', $controller);
        return $this->view->fetch();
    }

    public function userlist() {
        $ret = [
            'status' => 1,
            'msg' => null,
            'count' => 0,
            'data' => [],
        ];
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        $ret['count'] = Db::name('user')->count();
        $ret['data'] = Db::name('user')
                    ->field([ 'id','username', 'nickname', 'phone', 'email', 'ctime', 'status'])
                    ->select();
        return json($ret);
    }

    public function delUser() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $id = $this->request->post('id');
        $rst = Db::name('user')->delete($id);
        if ( $rst !== false ) {
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "删除失败！";
        }
        return json($ret);
    }

    public function passUser() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $id = $this->request->post('id');
        $rst = Db::name('user')
                ->where(['id' => $id])
                ->update(['status' => 1]);
        if ( $rst !== false ) {
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "通过失败！";
        }
        return json($ret);
    }

    public function delSelectUser() {
        $ret = [
            'status' => 0,
            'count' => 0,
            'succ' => array(),
            'failed' => array(),
        ];
        $data = json_decode($this->request->post('item'));
        $ret['count'] = count($data);
        $delList = [];
        foreach ($data as $key => $value) {
            array_push($delList, $value->id);
        }
        $ret['status'] = Db::name('user')->delete($delList);
        return json($ret);
    }

    public function passSelectUser() {
        $ret = [
            'status' => 0,
            'count' => 0,
            'succ' => array(),
            'failed' => array(),
        ];
        $data = json_decode($this->request->post('item'));
        $ret['count'] = count($data);
        $passList = [];
        foreach ($data as $key => $value) {
            array_push($passList, $value->id);
        }
        $ret['status'] = Db::name('user')
                ->where('id', 'in', $passList)
                ->update(['status' => 1]);
        return json($ret);
    }

}
