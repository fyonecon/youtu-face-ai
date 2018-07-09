<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/6
 * Time: 8:59
 */

/*
 * 人脸必有键：
 *
 * $right_eyebrow
 *
 * face_profile 21
 * left_eye 8
 * right_eye 8
 * left_eyebrow 8
 * right_eyebrow 8
 * mouth 22
 * nose 13
 *
 *
 * */
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

    if(isset($res_api['face_shape'][0]) || $res_api['face_shape'][0]){
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

        $x_offset = ((($x_max - $x_min)*0.618-($y_mid - $y_min)*0.618)/2)/2.2; // x方向向外校正偏移量
        $y_offset = ($x_offset/0.618)/1.5; // y方向向外校正偏移量

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
            'img_url'=>$img_url,
        );

        return array('status'=>1, 'msg'=>'当前图片中人五官的黄金坐标值', 'img_set'=>$data);
    }else{
        return array('status'=>0, 'msg'=>'当前图片中无人的五官');
    }

}

if(!function_exists('is_post')) {
    function is_post()
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
    }
}
if(!function_exists('is_get')){
    function is_get()
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='GET';
    }
}

//指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
//响应类型
header('Access-Control-Allow-Methods: GET,POST,PUT');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type:application/json; charset=utf-8');

$img_url = $_POST['base64_img'];

if (is_post()){

    return  json_encode(youtu_coordinate($img_url), JSON_UNESCAPED_UNICODE);
}else{
    return json_encode(array('status'=>0, 'msg'=>'非法请求'), JSON_UNESCAPED_UNICODE);
}

















