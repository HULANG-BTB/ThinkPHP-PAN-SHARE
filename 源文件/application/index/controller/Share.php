<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Cookie;
use think\Url;
use think\Db;
use think\Request;

class Share extends Base {
    public function index() {
        $controller = $this->request->controller();
        $this->assign('controller', $controller);
        return $this->view->fetch();
    }

    public function list() {
        $ret = [
            'status' => 1,
            'msg' => null,
            'count' => 0,
            'data' => [],
        ];
        $userId = substr(Session::get("sid"),45);
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        // dump($this->request);
        $count = Db::name('share')->where(["uid" => $userId])->count();

        $list = Db::name('share s')
                    ->where(['s.uid' => $userId])
                    ->join('fileinfo f', 's.fid = f.id')
                    ->field(['s.id' => 'id', 'f.name' => 'fileName', 's.etime' => 'status', 's.url' => 'url', 's.password' => 'password'])
                    ->select();

        foreach ($list as $key => $value) {
            if ( $value['status']  >= date("Y-m-d H:i:s", time()) || $value['status'] == '0000-00-00 00:00:00' ) {
                $list[$key]['status'] = true;
            } else {
                $list[$key]['status'] = false;
            }
            $list[$key]['url'] = 'http://' . $_SERVER['HTTP_HOST'] . $list[$key]['url'];
        }

        $ret['count'] = $count;
        $ret['data'] = $list;
        return json($ret);
    }

    public function file() {
        $id = $this->request->param('id');
        $this->assign('fileId', $id);
        return $this->view->fetch();
    }

    public function create() {
        $ret = [
            'status' => 0,
            'msg' => " ",
        ];
        $id = $this->request->post('id');
        $type = $this->request->post('type');
        $time = $this->request->post('time');
        $userId = substr( Session::get('sid'), 45); 
       
        
        $insertData = [
            'uid' => $userId,
            'fid' => $id,
            'password' => null,
            'etime' => 0,
            'url' => '/index/share/down/' . md5($userId . time()),
        ];

        if ( $type != 0 ) {
            $insertData['password'] = substr(md5(time()), 28);
        }

        if ( $time != 0 ) {
            $insertData['etime'] = date( "Y-m-d H:i:s", time() + 24 * 60 * 60 * $time);
        }

        $rst = Db::name('share')->insert($insertData);

        if ( $rst ) {
            $ret['status'] = 1;
            $insertData['url'] = 'http://' . $_SERVER['HTTP_HOST'] . $insertData['url'];
            $ret['msg'] = $insertData;
        }

        return json($ret);
    }

    public function delSelect() {
        $ret = [
            'status' => 0,
            'count' => 0,
            'succ' => array(),
            'failed' => array(),
        ];

        $delSize = 0;
        $list = json_decode($this->request->post('item'));
        $ret['count'] = count($list);
        $userId = substr(Session::get("sid"),45);
        foreach ($list as $key => $value) {
            $rst = DB::name('share')->delete(['id' => $value->id, 'uid' => $userId]);
            if ($rst !== false) {
                $delSize++;
            }
        }
        $ret['status'] = $delSize;
        return json($ret);
    }

    public function delShare() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $data = $this->request->post();
        $userId = substr(Session::get("sid"),45);
        $rst = DB::name('share')->delete(['id' => $data['id'], 'uid' => $userId]);
        if ( $rst !== false ) {
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "删除文件失败";
        }
        return json($ret);
    }

    public function checkpass() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $data = $this->request->post();
        $rst = Db::name('share')->where(['url' => $data['url'], 'password' => $data['password']])->count();
        if ( $rst ) {
            Session::set($data['url'], $data['password']);
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "密码不正确！";
        }
        return json($ret);
    }

    public function fileList() {
        $ret = [
            'status' => 0,
            'msg' => null,
            'count' => 1,
            'data' => [],
        ];
        $url = $this->request->param('url');
        $path = $this->request->get('path');
        $shareInfo = Db::name('share')->where(['url' => $url])->find();
        $fileInfo = Db::name('fileinfo')
                    ->where(['id' => $shareInfo['fid']])
                    ->field(["id" => "id", "name" => "fileName", "ctime" => "crateTime", "size" => "size", "type" => "type", 'path' => 'path', 'uid' => 'uid'])
                    ->find();
        $userId = substr(Session::get("sid"),45);
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');

        if ( $path == '/' ) {
            array_push($ret['data'], $fileInfo);
            $ret['status'] = 1;
            return $ret;
        } else {
            $count = Db::name('fileinfo')
                    ->where(['path' => $fileInfo['path'] . $path, 'uid' => $fileInfo['uid']])
                    ->count();
            $data = Db::name('fileinfo')
                    ->where(['path' => $fileInfo['path'] . $path, 'uid' => $fileInfo['uid']])
                    ->field(["id" => "id", "name" => "fileName", "ctime" => "crateTime", "size" => "size", "type" => "type", 'path' => 'path', 'uid' => 'uid'])
                    ->select();

            $ret['data'] = $data;
            $ret['status'] = 1;
            $ret['count'] = $count;
            return $ret;
        }

    }

    public function down() {
        $controller = $this->request->controller();
        $this->assign('controller', $controller);
        $url = $this->request->url();
        $rst = Db::name('share')->where(['url' => $url])->find();
        $this->assign('url', $url);
        if ( $rst && ($rst['etime'] >= date('Y-m-d H:i:s', time()) || $rst['etime'] == '0000-00-00 00:00:00') ) {
            if ( isset($rst['password']) ) {
                if ( Session::has($url) && Session::get($url) == $rst['password'] ) {
                    return $this->view->fetch('downlist');
                } else {
                    return $this->view->fetch('unlock');
                }
            } else {
                return $this->view->fetch('downlist');
            }
        } else {
            return $this->view->fetch('error');
        }
    }
}
