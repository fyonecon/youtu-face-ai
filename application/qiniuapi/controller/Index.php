<?php

namespace app\qiniuapi\controller;

use think\Controller;

use app\qiniuapi\model\Qiniuapi;

//use Qiniu\Auth;
//use Qiniu\Storage\UploadManager;
//use Qiniu\Storage\BucketManager;
//require_once APP_PATH . '/../vendor/qiniu/autoload.php';


class Index extends Controller{


    public function index(){

        echo $this->fetch('test');
        echo '<hr>';

        // 调用七牛云上传示例，返回七牛文件名
//        $file_path = ROOT_PATH."h5/letter/niming.png";
//        $qiniu_bucket = "toudeng";
//        $res = $this->qiniu_upload_api($file_path, $qiniu_bucket);
//        print_r($res['status']); // 最终状态
//        echo '<hr>';
//        print_r($res['content']); // 最终文件名


    }


    /*
     * base64转图片并保存到本地，然后上传到七牛云
     * post方法：http://localhost/wxmail/public/?s=/qiniuapi/index/save_base64_img
     *
     * */
    public function save_base64_img(){

        $base64 = input('base64');
        if (!$base64){
            return array("status"=>0,"content"=>"base64 is null");
        }

        $path = "h5/base64_img"; //文件路径
        $file = "/".date('Ymd',time())."/";

        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            $type = $result[2];

            $new_file = ROOT_PATH.$path.$file;
            if(!file_exists($new_file)){
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0755);
            }

            $img = time().uniqid().".{$type}";

            $new_file = $new_file.$img;

            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){

                $files = ROOT_PATH.$path.$file.$img;

                $qiniuapi = new Qiniuapi(); // 实例化

                $res = $qiniuapi->qiniu_upload_api($files);


                // 执行保存图片地址到数据库



                return json_encode($res); // 格式 {"status":1,"content":"20180702_18-42-52_5b3a01ac8d4f0.png"}

            }else{
                return array("status"=>0, "msg"=>"在服务器本地创建文件失败，原因是：父级目录没有777权限");
            }
        }else{
            array("status"=>0, "msg"=>"不是base64字符串编码的图片");
        }

    }



    /**
     * 通过img的网址，保存到本地然后上传到七牛云
     * $img_url 下载文件地址
     */
    function save_url_img($img_url) {

        if (trim($img_url) == '') {
            return array("status"=>0,"content"=>"img_url is null");
        }

        $path = "h5/url_img"; //文件路径
        $file = "/".date('Ymd',time())."/";

        $pattern = substr(strrchr($img_url, '.'), 1); // 正则文件格式

        $filename = ROOT_PATH.$path.$file;
        if(!file_exists($filename)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($filename, 0755);
        }

        $filename = $filename.time().uniqid().'.'.$pattern;

        // curl下载文件
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $img_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $img = curl_exec($ch);
        curl_close($ch);

        // 保存文件到制定路径
        file_put_contents($filename, $img);

        $qiniuapi = new Qiniuapi(); // 实例化
        $res = $qiniuapi->qiniu_upload_api($filename);

        unset($img, $url);
        return $res;
    }



    // test
    // 直接访问测试：http://xxxxxx/public/?s=/qiniuapi/index/upload
    public function upload(){

        if(request()->isPost()){

            $accessKey = '7We6C5IOMdYGyFv8_sD3Uh1MzXZpSMDf9t3vJVQZ';
            $secretKey = 'JANUchd3uGPff-hRumhUGjuFA6NZw8KVC5QiBb-J';
            $domain = 'http://p9mda7c37.bkt.clouddn.com'; // 临时域名或绑定的域名http://p9mda7c37.bkt.clouddn.com
            $bucket='test'; // 七牛上面的文件夹

            $auth = new Auth($accessKey,$secretKey);
            $token = $auth->uploadToken($bucket);
            $uploadMgr = new UploadManager();

            $files = ROOT_PATH."h5/letter/niming.png"; // 文件服务器中路径
            $pattern = substr(strrchr($files, '.'), 1); // 正则文件格式
            if (!$pattern){
                echo json_encode(array(
                    "status"=>0,
                    "content"=>"pattern is null"
                ));
                exit();
            }
            
            print_r($files);

            $tmpArr = array($files);
            foreach ($tmpArr as $k => $value) {
                $filePath = $value;
                $key = date("Y-m-d")."-".uniqid().".".$pattern; // 文件保存的路径及其文件名
                $res = $uploadMgr->putFile($token, $key, $filePath);

                $link = $domain."/".$key; // 文件直接访问地址
                print_r($link);

                if ($res){ //成功上传
                    echo json_encode(array("status"=>1,"content"=>$res)); // 返回hash和文件名
                }else{
                    echo json_encode(array("status"=>0,"content"=>"qiniu-upload-img is error"));
                }
            }

        }else{
            echo json_encode(array( "status"=>0, "content"=>"Is not post way"));
        }
    }



}
