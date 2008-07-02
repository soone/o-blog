<?php
/*
+--------------------------------------------------------+
| O-BLOG - PHP Blog System                               |
| Copyright (c) 2004 phpBlog.CN                          |
| Support : http://www.phpBlog.cn                        |
| Author : ShiShiRui (shishirui@163.com)                 |
|--------------------------------------------------------+
*/
error_reporting(7);

$x_size=46;
$y_size=20;
$nmsg = rand(1000,9999);
setcookie("ob_verify_code_num",md5($nmsg),time()+300,"/");

$aimg = imagecreate($x_size,$y_size);
$back = imagecolorallocate($aimg, 255, 255, 255);
$border = imagecolorallocate($aimg, 0, 0, 0);
imagefilledrectangle($aimg, 0, 0, $x_size - 1, $y_size - 1, $back);
imagerectangle($aimg, 0, 0, $x_size - 1, $y_size - 1, $border);
imageString($aimg,5,6,2, $nmsg,$border); 
header("Pragma:no-cache");
header("Cache-control:no-cache");
header("Content-type: image/png");
imagepng($aimg);
imagedestroy($aimg);

?> 