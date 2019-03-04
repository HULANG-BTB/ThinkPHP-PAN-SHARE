<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Cookie;
use think\Url;
use think\Db;
use think\Request;
use think\Config;

class File extends Base {
    public function index() {
        $controller = $this->request->controller();
        $this->assign('controller', $controller);
        $userId = substr(Cookie::get('sid'), 45);
        $file_nums = Db::name('fileinfo')->where(['uid' => $userId])->count();
        $share_nums = Db::name('share')->where(['uid' => $userId])->count();
        $this->assign("file_nums", $file_nums);
        $this->assign("share_nums", $share_nums);
        if ( !Cookie::has('path') ) {
            Cookie::set("path", "/");
            $path = "/";
        } else {
            $path = Cookie::get('path');
        }
        $this->assign("path", $path);
        return $this->view->fetch();
    }

    public function list() {
        $ret = [
            'status' => 1,
            'msg' => null,
            'count' => 0,
            'data' => [],
        ];
        $path = $this->request->get('path');
        Cookie::set("path", $path);
        $userId = substr(Session::get("sid"),45);
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        // dump($this->request);
        $count = Db::name('fileinfo')->where(["uid" => $userId, "path" => $path])->count();
        $list = Db::name('fileinfo')
                    ->field(["id" => "id", "name" => "fileName", "ctime" => "crateTime", "size" => "size", "type" => "type"])
                    ->order(['type' => "ASC"])
                    ->where(["uid" => $userId, "path" => $path])
                    ->page($page, $limit)
                    ->select();
        $ret['count'] = $count;
        $ret['data'] = $list;
        return json($ret);
    }

    public function createDir() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $path = Cookie::get('path');
        $dirname = $this->request->post('dirname');
        $userId = substr(Session::get("sid"),45);
        $isExist = Db::name('fileinfo')->where(["uid" => $userId, "path" => $path, "name" => $dirname])->count();
        if ( $isExist ) {
            $ret['msg'] = "文件夹已存在！";
            return json($ret);
        }
        $rst = Db::name('fileinfo')->insert(["uid" => $userId, "path" => $path, "name" => $dirname, "type" => 0, 'size' => 0, 'cip' => get_client_ipaddress()]);
        if ( $rst ) {
            $ret['status'] = 1;
        }
        return json($ret);
    }

    public function delfile() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $data = $this->request->post();
        $userId = substr(Session::get("sid"),45);
        $path = Cookie::get('path');
        $rst = DB::name('fileinfo')->where(["uid" => $userId, "path" => $path . "/" . $data['fileName']])->count();
        if ($rst) {
            $ret['msg'] = "文件夹不为空！";
            return json($ret);
        }
        $rst = DB::name('fileinfo')->delete(['id' => $data['id']]);
        if ( $rst !== false ) {
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "删除文件失败";
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
        $path = Cookie::get('path');
        if ( $path == "/" )
            $path = "";
        foreach ($list as $key => $value) {
            if ($value->type == 0) {
                $rst = DB::name('fileinfo')->where(["uid" => $userId, "path" => $path . "/" . $value->fileName])->count();
                if ( $rst ) {
                    array_push($ret['failed'], $value->fileName);
                } else {
                    $rst = DB::name('fileinfo')->delete(['id' => $value->id]);
                    if ( $rst !== false ) {
                        array_push($ret['succ'], $value->fileName);
                        $delSize++;
                    } else {
                        array_push($ret['failed'], $value->fileName);
                    }
                }
            } else {
                $rst = DB::name('fileinfo')->delete([ 'id'=> $value->id]);
                if ( $rst !== false ) {
                    array_push($ret['succ'], $value->fileName);
                    $delSize++;
                } else {
                    array_push($ret['failed'], $value->fileName);
                }
            }
        }
        $ret['status'] = $delSize;
        return json($ret);
    }

    public function upload() {
        return $this->view->fetch();
    }

    public function getAuth() {
        $config = Config::get('qcloudcos');
        $policy = [
            'version'=> '2.0',
            'statement'=> [
                [
                    'action'=> [
                        // // 所有操作
                        // 'name/cos:*',
                        // // 列出 Bucket 列表
                        // 'name/cos:GetService',
                        // // Bucket ACL 读写
                        // 'name/cos:GetBucketACL',
                        // 'name/cos:PutBucketACL',
                        // // Object ACL 读写
                        // 'name/cos:GetObjectACL',
                        // 'name/cos:PutObjectACL',
                        // // Policy 权限策略
                        // 'name/cos:PutBucket',
                        // 'name/cos:HeadBucket',
                        // 'name/cos:GetBucket',
                        // 'name/cos:GetBucketObjectVersions',
                        // 'name/cos:DeleteBucket',
                        // 'name/cos:GetBucketLocation',
                        // // Policy 权限策略
                        // 'name/cos:GetBucketPolicy',
                        // 'name/cos:PutBucketPolicy',
                        // 'name/cos:DeleteBucketPolicy',
                        // // Versioning 多版本配置
                        // 'name/cos:PutBucketVersioning',
                        // 'name/cos:GetBucketVersioning',
                        // // CORS 跨域配置
                        // 'name/cos:PutBucketCORS',
                        // 'name/cos:GetBucketCORS',
                        // 'name/cos:DeleteBucketCORS',
                        // // Lifecycle 生命周期
                        // 'name/cos:PutBucketLifecycle',
                        // 'name/cos:GetBucketLifecycle',
                        // 'name/cos:DeleteBucketLifecycle',
                        // // Replication 跨区域复制
                        // 'name/cos:PutBucketReplication',
                        // 'name/cos:GetBucketReplication',
                        // 'name/cos:DeleteBucketReplication',
                        // // Tagging 标签
                        // 'name/cos:PutBucketTagging',
                        // 'name/cos:GetBucketTagging',
                        // 'name/cos:DeleteBucketTagging',
                        // // Referer 防盗链
                        // 'name/cos:GetBucketReferer',
                        // 'name/cos:PutBucketReferer',
                        // 'name/cos:DeleteBucketReferer',
                        // // Origin 源站设置
                        // 'name/cos:GetBucketOrigin',
                        // 'name/cos:PutBucketOrigin',
                        // 'name/cos:DeleteBucketOrigin',
                        // // Website 静态网站
                        // 'name/cos:GetBucketWebsite',
                        // 'name/cos:DeleteBucketWebsite',
                        // 'name/cos:PutBucketWebsite',
                        // // Logging 日志记录
                        // 'name/cos:GetBucketLogging',
                        // 'name/cos:PutBucketLogging',
                        // // Logging 日志记录
                        // 'name/cos:GetBucketNotification',
                        // 'name/cos:PutBucketNotification',
                        // // 删除文件
                        // 'name/cos:DeleteMultipleObjects',
                        // 'name/cos:DeleteObject',
                        // 'name/cos:AbortMultipartUpload',
                        // // 复制文件或分片
                        // 'name/cos:PutObjectCopy',
                        // 'name/cos:UploadPartCopy',
                        // // 取回归档
                        // 'name/cos:PostObjectRestore',
                        // // 读取文件
                        // 'name/cos:HeadObject',
                        // 'name/cos:GetObject',
                        // 'name/cos:OptionsObject',
                        // // 上传操作
                        // 'name/cos:PostObject',
                        // 'name/cos:AppendObject',
                        // 简单上传
                        'name/cos:PutObject',
                        // 分片上传操作
                        'name/cos:InitiateMultipartUpload',
                        'name/cos:ListMultipartUploads',
                        'name/cos:ListParts',
                        'name/cos:UploadPart',
                        'name/cos:CompleteMultipartUpload',
                    ],
                    'effect'=> 'allow',
                    'principal'=> ['qcs'=> ['*']],
                    'resource'=> [
                        'qcs::cos:' . $config['Region'] . ':uid/' . $config['APPID'] . ':prefix//' . $config['APPID'] . '/' . $config['Bucket'] . '/',
                        'qcs::cos:' . $config['Region'] . ':uid/' . $config['APPID'] . ':prefix//' . $config['APPID'] . '/' . $config['Bucket'] . '/' . $config['AllowPrefix']
                    ]
                ]
            ]
        ];
        $policyStr = str_replace('\\/', '/', json_encode($policy));
        $Action = 'GetFederationToken';
        $Nonce = rand(10000, 20000);
        $Timestamp = time() - 1;
        $Method = 'POST';
        $params = [
            'Region'=> 'gz',
            'SecretId'=> $config['SecretId'],
            'Timestamp'=> $Timestamp,
            'Nonce'=> $Nonce,
            'Action'=> $Action,
            'durationSeconds'=> 7200,
            'name'=> 'cos',
            'policy'=> urlencode($policyStr)
        ];

        function json2str($obj, $notEncode = false) {
            ksort($obj);
            $arr = array();
            foreach ($obj as $key => $val) {
                array_push($arr, $key . '=' . ($notEncode ? $val : rawurlencode($val)));
            }
            return join('&', $arr);
        }

        function _hex2bin($data) {
            $len = strlen($data);
            return pack("H" . $len, $data);
        }

        $formatString = $Method . $config['Domain'] . '/v2/index.php?' . json2str($params, 1);
        $sign = hash_hmac('sha1', $formatString, $config['SecretKey']);
        $params['Signature'] = base64_encode(_hex2bin($sign));

        $url = $config['Url'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json2str($params));
        $result = curl_exec($ch);
        if( curl_errno($ch) ) {
            $result = curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($result, 1);
        if (isset($result['data'])) {
            $result = $result['data'];
        }

        $result['Bucket'] = $config['Bucket'] . '-' . $config['APPID'];
        $result['Region'] = $config['Region'];
        $result['Path'] = substr(Session::get('sid'), 45) . Cookie::get('path') . '/';
        return $result;
    }

    public function addFile() {
        $ret = [
            'status' => 0,
            'msg' => null,
        ];
        $config = Config::get('qcloudcos');
        $filename = $this->request->post('filename');
        $size = $this->request->post('size');
        $userId = substr(Session::get('sid'), 45);
        $path = Cookie::get('path');
        $insertData = [
            'uid' => $userId,
            'name' => $filename,
            'path' => $path,
            'type' => 1,
            'size' => $size,
            'url' => $config['Bucket'] . '-' . $config['APPID'] . '.cos.' . $config['Region'] . '.myqcloud.com/' . $userId . $path . '/' . $filename,
            'cip' => get_client_ipaddress(),
        ];
        $rst = Db::name('fileinfo')->insert($insertData);
        if ( $rst ) {
            $ret['status'] = 1;
        } else {
            $ret['msg'] = "文件保存错误";
        }
        return json($ret);
    }

    public function down() {
        $id = $this->request->param('id');
        $rst = Db::name('fileinfo')
                    ->where(['id' => $id])
                    ->find();
        $this->assign('url', $rst['url']);
        return $this->view->fetch();
    }
}