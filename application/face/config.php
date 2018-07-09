<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3
 * Time: 9:04
 */
return [

    'datetime_format'=>false,
    //应用调试模式
    'app_debug'=>true,
    'app_trace'=>false,

    //memcache配置
    'memcache'              =>[
        'hostname'  => '127.0.0.1', //开启时，tcp://127.0.0.1 ，关闭时改成udp
        'port'      => 11211,
        'username'  =>'', //账号
        'password'  =>''  //密码
    ],

    //公众号推荐图片目录
    'recommend'=>[
        'dir'=>'recommend',//项目目录
        'back'=>'background',//新版的背景目录地址
        'backimg'=>'back_img',
        'headimgurl'=>'user_headimg',
        'qrcode'=>'user_qrcode',//用户独有二维码目录
        'final'=>'compose'//最终合成目录

    ],

    // 微信授权
    'taskauth'  => [
        //'app_id'    => 'wxdc30a9bb127cc7c5',//福利匣
        //'app_secret'=> '5f16c202e456d87ccbe0b0371ce337c8',
        // 'app_id'    => 'wx48bca3f1e0a2a71b',//含玉书社
        // 'app_secret'=> '32ca4e4fb0a06c8f417ac499d18f1c4d',
        //'host'      => 'www.djfans.net/tp5/public/index.php?s='
        'app_id'    => 'wx546ec637c7491959',//福利堡
        'app_secret'=> 'caed2284c49c2f604911ee8fac2db441',

        // 由于是数据库切换操作app_id，目前只用到host参数
        'host'  =>'wxmail.mukzz.pw/public/?s='

    ],


    // 跨服务器数据库
    /*
     * $res = Db::connect('db_wxmail')->name("official_app")->select();
     * dump($res);
     * */
    'db_wxmail' => [
        // 数据库类型
        'type' => 'mysql',
        // 服务器地址
        'hostname' => '39.108.245.11',//内网ip
        // 数据库名
        'database' => 'wxmail',
        // 数据库用户名
        'username' => 'root',
        // 数据库密码
        'password' => 'root',
        // 数据库编码默认采用utf8
        'charset' => 'utf8mb4',
        // 数据库表前缀
        'prefix' => 'le_',
    ]


];