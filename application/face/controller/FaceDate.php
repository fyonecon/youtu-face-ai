<?php
/**
 * Created by PhpStorm.
 * User: 2652335796@qq.com
 * Date: 2018/7/7
 * Time: 10:10
 */

namespace app\face\controller;

use think\Controller;
use app\qiniuapi\model\Qiniuapi;
use think\Request;
use think\Db;

class FaceDate extends Controller{

    public function index(){
        echo "0";
    }

    // 处理用户上传数据，返回脸部信息。接口：http://td.t0fdt.cn/tpai/public/?s=/face/face_date/face_api
    public function face_api(){

        //指定允许其他域名访问
        header('Access-Control-Allow-Origin:*');
        //响应类型
        header('Access-Control-Allow-Methods: GET,POST,PUT');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        header('Content-Type:application/json; charset=utf-8');

        if (is_post()){

            //print_r($_REQUEST);

            //$post2 = json_encode($_POST);

            //return json_encode(array('status'=>0, 'msg'=>'fetch问题：'.$post2), JSON_UNESCAPED_UNICODE);

            $base64_img = $_POST['base64_img'];
            //$face_date = youtu_coordinate($base64_img); // 人脸五官范围值
            //return json_encode($face_date, JSON_UNESCAPED_UNICODE); // 最终返回

            $img_array = $this->save_base64_img($base64_img); // 图片存七牛云
            $img_status = $img_array['status'];

            if($img_status === 1){

                $dom = 'http://qiniu.mukzz.pw/';
                $img_name = $img_array['img'];

                $img_url = $dom.$img_name; // 图片的网络地址
                $face_date = youtu_coordinate($img_url); // 人脸五官范围值

                // 生成唯一长字符串id
                $len = rand(20, 29);
                $only_num = time()."only".$this->getRandomString($len);

                $new_array = array(
                    'only_num'=>$only_num,
                );
                $face_date = array_merge($face_date, $new_array);

                $string_face_date = json_encode($face_date, JSON_UNESCAPED_UNICODE); // 数组转字符串，然后保存到数据库
                $db = $this->save_face_date($img_name, $string_face_date, $only_num); // 保存用户数据

                if ($db['status'] === 1){
                     return json_encode($face_date, JSON_UNESCAPED_UNICODE); // 最终返回
                }else{
                    return json_encode(array('status'=>0, 'msg'=>'用户历史生成保存失败。'), JSON_UNESCAPED_UNICODE);
                }

            }else{
                return json_encode(array('status'=>0, 'msg'=>'七牛云出现问题：'.$img_array['msg']), JSON_UNESCAPED_UNICODE);
            }


        }else{
            return json_encode(array('status'=>0, 'msg'=>'非法请求：限制为POST请求。'), JSON_UNESCAPED_UNICODE);
        }

    }
    // 将每次生成的人脸检测数据保存到数据库
    public function save_face_date($img_name, $face_date, $only_num){

        $data = array(
            'original_img'=>$img_name,
            'face_date'=>$face_date,
            'only_id'=>$only_num,
            'create_time'=>time(),
        );

        $res = Db::name('face_date')->insert($data);

        if ($res){
            return array('status'=>1, 'msg'=>'保存成功。');
        }else{
            return array('status'=>0, 'msg'=>'保存失败。');
        }

    }
    /*
     * base64转图片并保存到本地，然后上传到七牛云
     * post方法：http://localhost/wxmail/public/?s=/qiniuapi/index/save_base64_img
     * */
    public function save_base64_img($base64_img){

        $base64 = $base64_img;

        if (!$base64){
            return array("status"=>0,"msg"=>"base64 is null");
        }

        $path = "h5/face-ai/save/base64_img"; //文件路径
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

            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){ // 在服务器创建文件

                $files = ROOT_PATH.$path.$file.$img;

                $qiniuapi = new Qiniuapi(); // 实例化
                $res = $qiniuapi->qiniu_upload_api($files);

                return $res; // 格式 {"status"=>1, "msg"=>"success","img":"20180702_18-42-52_5b3a01ac8d4f0.png"}

            }else{
                return array("status"=>0, "msg"=>"在服务器本地创建文件失败，原因是：父级目录没有777权限");
            }
        }else{
            return array("status"=>0, "msg"=>"不是base64字符串编码的图片");
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

        $path = "h5/face-ai/save/base64_img"; //文件路径
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
    // 获取固定长度的数字字母随机数
    public function getRandomString($len, $chars=null)
    {
        if (is_null($chars)){
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        }
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }


}






// 转化接口 1/2
function youtu_api($img_url){ // 处理优图api返回参数

    $img = urlencode($img_url);

    //腾讯优图人脸坐标api
    $url="http://td.t0fdt.cn/tpai/h5/face-ai/api.php?img_url=".$img;

    $ch=curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $resp=curl_exec($ch);
    curl_close($ch);
    $resp=json_decode($resp,true);

    //var_dump($resp);

    return $resp; // 返回数组类型
}
// 计算坐标 2/2
function youtu_coordinate($img_url){ // 处理腾讯优图的人脸坐标，得到最终人五官黄金简便坐标

    //$img_url = 'http://3w.mukzz.pw/tpwx/h5/tests/test1.jpg';

    $res_api = youtu_api($img_url);

    if(isset($res_api['face_shape'][0]) || $res_api['face_shape'][0]){ // 执行人脸五官范围算法

        //$face_profile = $res_api['face_profile']; // 21
        $mouth = $res_api['face_shape'][0]['mouth']; // 22
        $nose = $res_api['face_shape'][0]['nose']; // 13
        $left_eyebrow = $res_api['face_shape'][0]['left_eyebrow']; // 8
        $right_eyebrow = $res_api['face_shape'][0]['right_eyebrow']; // 8

        for ($i=0;$i<count($left_eyebrow);$i++){ // 眉毛x
            $new_x_eyebrow_1[] = $left_eyebrow[$i]['x'];
        }

        for ($i=0;$i<count($right_eyebrow);$i++){ // 眉毛x
            $new_x_eyebrow_2[] = $right_eyebrow[$i]['x'];
        }

        for ($i=0;$i<count($left_eyebrow);$i++){ // 眉毛y
            $new_y_eyebrow_1[] = $left_eyebrow[$i]['y'];
        }

        for ($i=0;$i<count($right_eyebrow);$i++){ // 眉毛y
            $new_y_eyebrow_2[] = $right_eyebrow[$i]['y'];
        }

        for ($i=0;$i<count($mouth);$i++){ // 嘴巴y
            $new_y_mouth[] = $mouth[$i]['y'];
        }

        for ($i=0;$i<count($nose);$i++){ // 鼻子y
            $new_y_nose[] = $nose[$i]['y'];
        }

        sort($new_x_eyebrow_1); // 升序
        sort($new_x_eyebrow_2);
        $eyebrow_min_x = $new_x_eyebrow_1[0]>$new_x_eyebrow_2[0] ? $new_x_eyebrow_2[0] : $new_x_eyebrow_1[0];

        rsort($new_x_eyebrow_1); // 降序
        rsort($new_x_eyebrow_2);
        $eyebrow_max_x = $new_x_eyebrow_1[0]>$new_x_eyebrow_2[0] ? $new_x_eyebrow_1[0] : $new_x_eyebrow_2[0];

        sort($new_y_eyebrow_1); // 升序
        sort($new_y_eyebrow_2);
        $eyebrow_min_y = $new_y_eyebrow_1[0]>$new_y_eyebrow_2[0] ? $new_y_eyebrow_2[0] : $new_y_eyebrow_1[0];

        rsort($new_y_mouth); // 升序
        $mouth_max_y = $new_y_mouth[0];

        rsort($new_y_nose); // 升序
        $nose_max_y = $new_y_nose[0];


        $img_h = $res_api['image_height'];
        $img_w = $res_api['image_width'];

        $img_url = $res_api['img_url'];

        $x_min = $eyebrow_min_x;
        $x_max = $eyebrow_max_x;
        $y_min = $eyebrow_min_y;
        $y_max = $mouth_max_y;

        $y_mid = $nose_max_y;

        $x_offset = ((($x_max - $x_min)*0.618-($y_mid - $y_min)*0.618)/2)*1.2; // x方向向外校正偏移量
        $y_offset = ($x_offset/0.618)*1.2; // y方向向外校正偏移量
        $top_offset = ((($x_max - $x_min)*0.618-($y_mid - $y_min)*0.618)/2); // y方向向上校正偏移量

        $x_offset = floor($x_offset);
        $y_offset = floor($y_offset);

        $face_w = $x_max - $x_min;
        $face_h = $y_max - $y_min;

        $data = array(
            'img_w'=>$img_w, // 图宽
            'img_h'=>$img_h, // 图高
            'face_w'=>$face_w, // 五官部宽
            'face_h'=>$face_h, // 五官部高
            'x'=>$x_min, // 五官部区域开始x
            'y'=>$y_min, // 五官部区域开始y
            //'x_min'=>$x_min,
            //'x_max'=>$x_max,
            //'y_min'=>$y_min,
            //'y_max'=>$y_max,
            'x_offset'=>$x_offset, // 五官x方向向外偏离值，增加五官向外的动态自然过渡区域
            'y_offset'=>$y_offset, // 五官y方向向外偏离值，增加五官向外的动态自然过渡区域
            'top_offset'=>$top_offset, // y方向向上校正偏移量
            'img_url'=>$img_url, // 原始图片地址
        );

        return array('status'=>1, 'msg'=>'当前图片中人五官的黄金坐标值', 'img_set'=>$data);
    }else{
        return array('status'=>0, 'msg'=>'当前图片中无人的五官');
    }

}
