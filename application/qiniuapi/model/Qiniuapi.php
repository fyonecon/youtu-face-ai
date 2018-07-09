<?php
/**
 * Created by PhpStorm.
 * User: 2651335796@qq.com
 * Date: 2018/6/1
 * Time: 15:42
 */
namespace app\qiniuapi\model;


/*
 *
 * 用法：
 *     在controller里面加：
 *          use app\qiniuapi\model\Qiniuapi;
 *     在controller函数里面引用：
 *          $qiniuapi = new Qiniuapi(); // 实例化
            $qiniuapi->qiniu_upload_api($filename, $qiniu_bucket); // 运行函数
 *
 * */


use think\Model;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
require_once APP_PATH . '/../vendor/qiniu/autoload.php';

class Qiniuapi extends Model{


    /*
     * 向七牛云上传文件【post Api】，支持【一切有格式】的文件，并返回文件名
     * 接口示例：http://xxxxxx/public/?s=/qiniuapi/index/qiniu_upload_api
     *
     * $file_path：文件服务器中路径，例如：D:\wamp64\www\wxmail\h5/letter/niming.png 写法：ROOT_PATH."h5/letter/niming.png"
     * $qiniu_bucket：七牛bucket
     *
     * */
    public function qiniu_upload_api($file_path){

        if($_REQUEST){

            $accessKey = 'xx';
            $secretKey = 'xxxxx';
            //$domain = 'http://p9mda7c37.bkt.clouddn.com'; // 临时域名或绑定的域名http://p9mda7c37.bkt.clouddn.com
            $qiniu_bucket='toudeng'; // 七牛上面的文件夹

            if(!$file_path || !$qiniu_bucket){
                return array("status"=>0,"msg"=>"file_path or qiniu_buket is null");
            }

            $bucket = $qiniu_bucket;

            $auth = new Auth($accessKey,$secretKey);
            $token = $auth->uploadToken($bucket);
            $uploadMgr = new UploadManager();

            $files = $file_path; // 文件服务器中路径
            $pattern = substr(strrchr($files, '.'), 1); // 正则文件格式
            if (!$pattern){
                return array("status"=>0, "content"=>"pattern is null");
            }
            //print_r($files);
            $tmpArr = array($files);
            foreach ($tmpArr as $k => $value) {
                $filePath = $value;
                $key = date("kd_Y-m-d_H-i-s")."_".uniqid().".".$pattern; // 文件保存的路径及其文件名
                $res = $uploadMgr->putFile($token, $key, $filePath); // 上传

                //$link = $domain."/".$key; // 文件直接访问地址
                //print_r($link);

                if ($res){ //成功上传
                    return array("status"=>1,"msg"=>"qiniu-upload-img is success","img"=>$res[0]['key']); // 返回文件名
                }else{
                    return array("status"=>0,"msg"=>"qiniu-upload-img is error");
                }
            }

        }else{
            return array( "status"=>0, "msg"=>"REQUEST is error");
        }
    }


}