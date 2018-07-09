<?php
/**
 * Created by PhpStorm.
 * User: 2652335796@qq.com
 * Date: 2018/7/6
 * Time: 16:31
 */

require('./include.php');
use TencentYoutuyun\Youtu;
use TencentYoutuyun\Conf;

// 设置APP 鉴权信息 请在http://open.youtu.qq.com 创建应用

$appid='10138973';
$secretId='xxx';
$secretKey='xxxxx';
$userid='2652335796'; // 可填写QQ号

Conf::setAppInfo($appid, $secretId, $secretKey, $userid,conf::API_YOUTU_END_POINT );

// 人脸检测 调用列子
//$img_url = "http://3w.mukzz.pw/tpwx/h5/tests/test1.jpg";
$img_url = $_GET['img_url'];
$img_url = urldecode($img_url);

$uploadRet = YouTu::faceshapeurl($img_url, 1);

$img = array('img_url'=>$img_url);
$img_api = array_merge($uploadRet, $img);

echo json_encode($img_api, JSON_UNESCAPED_UNICODE);