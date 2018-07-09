<?php
/**
 * Created by PhpStorm.
 * User: 2652335796@qq.com
 * Date: 2018/7/7
 * Time: 10:14
 */

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
