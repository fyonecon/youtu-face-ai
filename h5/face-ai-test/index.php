<?php
/**
 * Created by PhpStorm.
 * User: 2652335796@qq.com
 * Date: 2018/7/6
 * Time: 11:18
 */

?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>人脸融合</title>
    <script src="http://3w.mukzz.pw/tpwx/h5/letter/js/jquery1.10.2.min.js"></script>
    <script src="http://3w.mukzz.pw/tpwx/h5/letter/js/common.js"></script>
    <script src="gaosi.js"></script>
    <style>
        body{background: #ececec;padding: 0;margin: 0;}
        .face-div{text-align: center;padding-top: 20px;padding-bottom: 20px;}
        .face-img{display: block;margin-right: auto;margin-left: auto;width: auto;height: auto;}
        .face-img2{display: block;margin-right: auto;margin-left: auto;width: 300px;margin-bottom: 20px}
        .input-div{margin-top: 400px;}
    </style>
</head>
<body>
    <div id="face-div" class="face-div">人脸融合-自主定义模板-示例一则</div>

    <!--开始-融合区域-->
    <div class="img-box">
        <img class="img-your-face" /> <!--你的脸-->
        <img class="img-other-face" src="test2.png" /> <!--背景图-->
        <img class="img-other-face2" src="test2-2.jpg" /> <!--背景图-全图-->

        <style>

            .img-box{
                position: relative;background: honeydew;border: 1px solid grey;
                width: 300px;
                margin: auto;
            }
            .img-your-face{
                position: absolute;
                z-index: 530;
                width: 68px;
                top: 120px;
                left: 118px;
                box-shadow: 0 0 30px rgba(231,181,138, 0.5);
                transform:rotate(-2deg); /*旋转*/
                /*filter: opacity(80%); !*透明度*!*/
                /*filter: saturate(400%);  !*饱和度*!*/
                /*filter: grayscale(50%); !*灰度*!*/
                filter: saturate(100%) grayscale(30%) opacity(70%); /*复合css渲染*/
            }
            .img-other-face{
                position: absolute;
                z-index: 630;
                width: 300px;
                top: 0;
                left: 0;
            }
            .img-other-face2{
                position: absolute;
                z-index: 430;
                width: 300px;
                top: 0;
                left: 0;
            }

        </style>
    </div>
    <!--结束-融合区域-->

    <div class="input-div">
        <div><image id="img" style="width:100px;" /></div>
        <div><input type="file" id="img-file"></div>
    </div>
    
    <script>

        document.getElementById("img-file").onchange = function (ev) {
            //console.log(ev);
            var that = this;

            var file_img = "";
            new Promise(function(resolve, reject) {

                // 取出file中的文件，并返回文件的base64
                if (that.files && that.files[0]) {
                    var reader = new FileReader();
                    reader.readAsDataURL(that.files[0]); // 直接转成base64
                    reader.onload = function (e) {

                        file_img = e.target.result;
                        //console.log(file_img);
                        console.log("file_img=base64生成成功");

                        resolve();
                    };

                }else {
                    console.log("input_file无文件");
                    reject();
                }

            })
                .then(function (value) {

                    $('#img').attr('src', file_img);

                    $('.input-div').append("<div>正在生成..</div>");

                    var img_url = file_img;

                    var face_url = "http://td.t0fdt.cn/tpai/public/?s=/face/face_date/face_api"; // json的网络地址
                    // 请求用户数据
                    $.ajax({
                        url: face_url,
                        type: "POST",
                        //dataType: "json",//已经默认json
                        //async: true,//已经默认true
                        data:{
                            base64_img: img_url
                        },
                        success: function(data, status){
                            console.log("数据：" + data+"；status："+status);

                            var datas = JSON.parse(data);

                            if (datas.status === 1){
                                var img_set = datas.img_set;
                                make_face_img(img_set);
                            }else {
                                console.log(datas.msg);
                                $('.input-div').append("<div>生成失败！</div>");
                                //alert(data.msg);
                            }

                        },
                        error: function (xhr) {
                            console.log(xhr);
                            $('.input-div').append("<div>生成失败！！</div>");
                        }

                    });


                })


        }



    </script>

    <script>

        // 合成图片，处理图片
        function make_face_img(img_set){
            $('.input-div').append("<div>正在生成...</div>");
            console.log("正在生成...");
            console.log(typeof img_set);

            var x = img_set.x;
            var y = img_set.y;
            var face_w = img_set.face_w;
            var face_h = img_set.face_h;
            var x_offset = img_set.x_offset;
            var y_offset = img_set.y_offset;
            var top_offset = img_set.top_offset;

            var img_url = img_set.img_url;
            console.log(img_url);

            //var x_pixel = x_offset; // 遮挡像素的宽
            //var y_pixel = y_offset; // 遮挡像素的高

            //var gaosi = 10; // 高斯模糊
            //var density = 10; // 高斯像素

            //var img_url = "test2.jpg";

            bg = img_url;

            // 绘制目标画图区域
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');
            // canvas区域
            canvas.width = face_w+x_offset*2;
            canvas.height = face_h+y_offset+top_offset;

            new Promise(function(resolve, reject) { // 初始化
                var img = new Image();
                img.crossOrigin = "anonymous";
                img.src = bg;
                img.onload = function () {
                    x = x-x_offset;
                    y = y-y_offset;
                    // 裁剪图片
                    ctx.drawImage(img, -x, -y, img.width, img.height);

                    console.log("生成完成");

                    resolve()
                }
            })
                // .then(function (value) {// 高斯模糊，top-left
                //     var data = ctx.getImageData(0, 0, x_pixel, y_pixel); // 不管，不管，宽，高
                //     var emptyData = gaussBlur(data, gaosi, density); // 高斯模糊，像素密度
                //     ctx.putImageData(emptyData, 0, 0); // 不管，坐标x，坐标y
                //
                // })
                // .then(function (value) {// 高斯模糊，top-right
                //     var data = ctx.getImageData(0, 0, x_pixel, y_pixel); // 不管，不管，宽，高
                //     var emptyData = gaussBlur(data, gaosi, density); // 高斯模糊，像素密度
                //     ctx.putImageData(emptyData, face_w+x_offset, 0); // 不管，坐标x，坐标y
                //
                // })
                // .then(function (value) {// 高斯模糊，left
                //     var data = ctx.getImageData(0, 0, x_pixel/2, face_h); // 不管，不管，宽，高
                //     var emptyData = gaussBlur(data, gaosi, density); // 高斯模糊，像素密度
                //     ctx.putImageData(emptyData, 0, 0); // 不管，坐标x，坐标y
                //
                // })
                // .then(function (value) {// 高斯模糊，right
                //     var data = ctx.getImageData(0, 0, x_pixel/2, face_h); // 不管，不管，宽，高
                //     var emptyData = gaussBlur(data, gaosi, density); // 高斯模糊，像素密度
                //     ctx.putImageData(emptyData, face_w+x_offset+x_pixel/2, 0); // 不管，坐标x，坐标y
                //
                // })
                // .then(function (value) {// 高斯模糊，bottom-left
                //
                //     var bl_y = 5;
                //     var bl_x = 4;
                //
                //     for (var i=0;i<bl_y+1;i++){
                //
                //         for (var j=0;j<bl_x+1;j++){
                //             if (i===5 && j===3 || i===5 && j===4 || i===4 && j===3 || i===4 && j===4 || i===3 && j===3 || i===3 && j===4 || i===2 && j===4 || i===5 && j===2 || i===4 && j===2|| i===5 && j===1 || i===1 && j===4 || i===4 && j===1|| i===3 && j===2|| i===2 && j===3 || i===1 && j===3){
                //                 console.log("跳过1");
                //             }
                //             else {
                //                 var data = ctx.getImageData(0, 0, x_pixel, y_pixel); // 不管，不管，宽，高
                //                 var emptyData = gaussBlur(data, gaosi, density); // 高斯模糊，像素密度
                //                 ctx.putImageData(emptyData, x_pixel*j, face_h+y_offset-y_pixel*i); // 不管，坐标x，坐标y
                //             }
                //
                //         }
                //
                //     }
                //
                // })
                // .then(function (value) {// 高斯模糊，bottom-right
                //
                //     var bl_y = 4;
                //     var bl_x = 5;
                //
                //     for (var i=0;i<bl_y+1;i++){
                //
                //         for (var j=0;j<bl_x+1;j++){
                //
                //             if(j===5 && i===3 || j===5 && i===4 || j===4 && i===3 || j===4 && i===4 || j===3 && i===3 || j===3 && i===4 || j===2 && i===4 || j===5 && i===2 || j===4 && i===2 || j===5 && i===1 || j===1 && i===4 || j===1 && i===4 || j===4 && i===1|| j===3 && i===2 || j===2 && i===3 || j===1 && i===3){
                //               console.log("跳过2")
                //             }else {
                //                 var data = ctx.getImageData(0, 0, x_pixel, y_pixel); // 不管，不管，宽，高
                //                 var emptyData = gaussBlur(data, gaosi, density); // 高斯模糊，像素密度
                //                 ctx.putImageData(emptyData, face_w+x_offset-x_pixel*i, face_h+y_offset-y_pixel*j); // 不管，坐标x，坐标y
                //             }
                //
                //         }
                //
                //     }
                //
                //
                //
                // })


                .then(function (value) {
                    $('.input-div').append("<div>完成</div>");
                    var bg_base64 = canvas.toDataURL('image/png'); // 最终base64码，png格式为透明背景
                    $(".img-your-face").attr("src", bg_base64);
                    //$("body").append("<img class='face-img' src='"+bg_base64+"' />");

                })
        }

    </script>
















    <script>

        var path = "http://3w.mukzz.pw/tpwx/h5/tests/test.jpg"; // imgurl 就是你的图片路径

        function getUrlBase64(url, callback) {
            var canvas = document.createElement("canvas");   //创建canvas DOM元素
            var ctx = canvas.getContext("2d");
            var img = new Image;
            img.crossOrigin = 'Anonymous';
            img.src = url;
            img.onload = function () {
                canvas.height = img.height;
                canvas.width = img.width;
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height); //参数可自定义
                var ext = img.src.substring(img.src.lastIndexOf(".")+1).toLowerCase();
                var dataURL = canvas.toDataURL("image/" + ext);
                callback.call(this, dataURL); //回掉函数获取Base64编码
                canvas = null;
            };
        }

        getUrlBase64(path, function (base64) {

            return;

            //console.log(base64);//base64编码值
            $("body").append("<img class='face-img2' src='"+base64+"' />");
        });

    </script>




    <script>

        // 获取人脸数据
        $(function () {

            return;

            var img_url = "http://3w.mukzz.pw/tpwx/h5/tests/test.jpg";

            var face_url = "http://td.t0fdt.cn/tpai/public/?s=/face/face_date/face_api"; // json的网络地址
            // 请求用户数据
            $.ajax({
                url: face_url,
                type: "POST",
                //dataType: "json",//已经默认json
                //async: true,//已经默认true
                data:{
                    base64_img: img_url
                },
                success: function(data, status){
                    console.log("数据：" + data+"；status："+status);

                    var datas = JSON.parse(data);

                    if (datas.status === 1){
                        var img_set = datas.img_set;
                        make_face_img(img_set);
                    }else {
                        console.log(datas.msg);
                        //alert(data.msg);
                    }

                },
                error: function (xhr) {
                    console.log(xhr);
                }

            });


        });

    </script>

</body>
</html>