<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//curl助手函数
if (!function_exists("http_request")) {
    function http_request($url,$data='')
    {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        if(!empty($data)){
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        $str=curl_exec($ch);
        curl_close($ch);
        return $str;

    }
}

//获取access_token
if (!function_exists("getAccessToken")) {
    function getAccessToken($arr)
    {

        $appid = $arr['appid'];
        $appsecret = $arr['appsecret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";

        //memcache读取和缓存access_token
        $mem = new Memcached();
        $mem->addServer('127.0.0.1', '11211');

        $token = $mem->get($appid);//对appid加密
        if (!empty($token)) {
            //为空说明缓存中有token，输出token
            return $token;
        } else {
            //重新获取token
            $tokenArr = json_decode(http_request($url), 1);
            if (!isset($tokenArr['errcode'])) {
                $token = $tokenArr['access_token'];
                $mem->add($appid, $token,6500);//对appid加密
                //return $mem->get('token');
                return $token;
            }

        }
        //file_put_contents(APPPATH.'token.txt',$token);

    }
}

//获取Memcached对象
if(!function_exists("getMemcached")){
    function getMemcached(){
        $m=new Memcached();
        $m->addServer('127.0.0.1','11211');
        return $m;

    }
}

//合成图片
if (!function_exists("timetostring")) {
    function timetostring($str)
    {
        $cliptime = explode("-", $str);
        $result = "";
        for ($i = 0; $i < count($cliptime); $i++) {
            $result = $result . $cliptime[$i];
        }
        return $result;
    }
}

/**
 * 合成图片并输出到文件
 * $backgroud_path背景图片路径
 * $arr_picinfo需要合成的图片数组,path,posx,posy,width,height
 *
 * $arrtextinfo需要在图片上生成的文字数组:$text数组
 *$text['rgb']= rgb(255,255,255),$text['size'],$text['inclination'],$text['posx'],$text['posy'],$be,$text['font'],$text['text']);
 * $filepath输出图片文件,如果为空，直接输出流
 **/
if (!function_exists("createpic")) {
    function createpic($backgroud_path, $arr_picinfo, $arrtextinfo, $filepath = NULL)
    {
        $pathInfo = pathinfo($backgroud_path);
        switch (strtolower($pathInfo['extension'])) {
            case 'jpg':
            case 'jpeg':
                $imagecreatefromjpeg = 'imagecreatefromjpeg';//创建一个新图像， 返回一图像标识符，代表了从给定的文件名取得的图像
                break;
            case 'png':
                $imagecreatefromjpeg = 'imagecreatefrompng';
                break;
            case 'gif':
            default:
                $imagecreatefromjpeg = 'imagecreatefromstring';//从字符串的图像流创建新图像，指的是图像的原始二进制数据字符串
                break;
        }
        $background = $imagecreatefromjpeg($backgroud_path);

        $pic_list = $arr_picinfo;
        if (!empty($pic_list)) {
            foreach ($pic_list as $pic) {
                $pathInfo = pathinfo($pic['path']);
                switch (strtolower($pathInfo['extension'])) {
                    case 'jpg':
                    case 'jpeg':
                        $imagecreatefromjpeg = 'imagecreatefromjpeg';
                        break;
                    case 'png':
                        $imagecreatefromjpeg = 'imagecreatefrompng';
                        break;
                    case 'gif':
                    default:
                        $imagecreatefromjpeg = 'imagecreatefromstring';
                        break;
                }
                $resource = $imagecreatefromjpeg($pic['path']);
                // $start_x,$start_y copy图片在背景中的位置
                // 0,0 被copy图片的位置
                // $pic_w,$pic_h copy后的高度和宽度
                //imagecopyresized是拷贝部分图像并调整大小，将一幅图像中的一块矩形区域拷贝到另一个图像中。dst_image 和 src_image 分别是目标图像和源图像的标识符。
                imagecopyresized($background, $resource, $pic['posx'], $pic['posy'], 0, 0, $pic['width'], $pic['height'], imagesx($resource), imagesy($resource)); // 最后两个参数为原始图片宽度和高度，倒数两个参数为copy时的图片宽度和高度
            }
        }
        if (!empty($arrtextinfo)) {
            foreach ($arrtextinfo as $text) {
                $be = imagecolorallocate($background, $text['rgb'][0], $text['rgb'][1], $text['rgb'][2]);//文字颜色，为一幅图像分配颜色，返回一个标识符，代表了由给定的 RGB 成分组成的颜色
                //这个函数其实就是给文字分配颜色填充到背景图里去。

                //写字操作 $im为你载入的图片，第二个参数为 字体大小，第三个参数为旋转或倾斜度，第四为 离左边的距离，第五为，离上边的距离，第六为 字体颜色，第七为 字体，路径不能用网址，只能用相对，或绝对路径，第八为 要写入的 文字。
                imagettftext($background, $text['size'], $text['inclination'], $text['posx'], $text['posy'], $be, $text['font'], $text['text']);
            }
        }
        //$filepath为要指定输出图像的路径，没有的话直接输出原始图像流
        if (!empty($filepath)) {
            $rs = imagejpeg($background, $filepath);//输出图象到浏览器或文件。第二个参数如果未设置或为 NULL，将会直接输出原始图象流。
            imagedestroy($background);//释放与 image 关联的内存。image 是由图像创建函数返回的图像标识符
            if ($rs) {
                return $filepath;
            }
        } else {
            //imagejpeg($background);
            return $background;
        }
    }
}
//图片修建函数
if(!function_exists("imagecropper")) {
    function imagecropper($source_path, $target_width, $target_height, $filepath)
    {
        $source_info = getimagesize($source_path);
        $source_width = $source_info[0];
        $source_height = $source_info[1];
        $source_mime = $source_info['mime'];
        $source_ratio = $source_height / $source_width;
        $target_ratio = $target_height / $target_width;

        // 源图过高
        if ($source_ratio > $target_ratio) {
            $cropped_width = $source_width;
            $cropped_height = $source_width * $target_ratio;
            $source_x = 0;
            $source_y = ($source_height - $cropped_height) / 2;
        } // 源图过宽
        elseif ($source_ratio < $target_ratio) {
            $cropped_width = $source_height / $target_ratio;
            $cropped_height = $source_height;
            $source_x = ($source_width - $cropped_width) / 2;
            $source_y = 0;
        } // 源图适中
        else {
            $cropped_width = $source_width;
            $cropped_height = $source_height;
            $source_x = 0;
            $source_y = 0;
        }

        switch ($source_mime) {
            case 'image/gif':
                $source_image = imagecreatefromgif($source_path);
                break;

            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($source_path);
                break;

            case 'image/png':
                $source_image = imagecreatefrompng($source_path);
                break;

            default:
                return false;
                break;
        }

        $target_image = imagecreatetruecolor($target_width, $target_height);//返回一个图像标识符，代表了一幅大小为 x_size 和 y_size 的黑色图像。
        $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);

        // 裁剪
        imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
        // 缩放
        imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);

        header('Content-Type: image/jpeg');
        imagejpeg($target_image, $filepath);

        imagedestroy($source_image);
        imagedestroy($target_image);
        imagedestroy($cropped_image);

    }
    //通过透明度获取的圆形头像
    if(!function_exists("getCircleAvatar")){
        function getCircleAvatar($url,$path){
            list($w, $h) = getimagesize($url);
            //$w = 110;  $h=110; // original size
            $original_path= $url;
            //$dest_path = $path.uniqid().'.png';
            $dest_path = $path;
            $src = imagecreatefromstring(file_get_contents($original_path));//从字符串的图像载入一副图像
            //$src = imagecreatefrompng($original_path);//这里直接从文件创建不能获取需要的图像
            $newpic = imagecreatetruecolor($w,$h);
            imagealphablending($newpic,false);
            $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
            $r=$w/2;
            for($x=0;$x<$w;$x++)
                for($y=0;$y<$h;$y++){
                    $c = imagecolorat($src,$x,$y);
                    $_x = $x - $w/2;
                    $_y = $y - $h/2;
                    if((($_x*$_x) + ($_y*$_y)) < ($r*$r)){
                        imagesetpixel($newpic,$x,$y,$c);
                    }else{
                        imagesetpixel($newpic,$x,$y,$transparent);
                    }
                }
            imagesavealpha($newpic, true);
            imagepng($newpic, $dest_path);
            imagedestroy($newpic);
            imagedestroy($src);
            // unlink($url);
            return $dest_path;
        }
    }



}