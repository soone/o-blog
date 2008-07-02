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

if (!defined('BLOG')) {
	die('Access Denied');
}

if(isset($articleName)) {
	$blogName = $articleName;
}

$classes = $DB->query("SELECT * FROM `".$mysql_prefix."class` ORDER BY `showorder`");
while($class = $DB->fetch_array($classes)) {
	$class_v[] =  "<a href=\"".$blogurl."index.php?do=class&amp;id=".$class['id']."\">".$class['classname']."</a>";
}
$blogClass = @implode(" | ",$class_v);

$headerT = template('header');
$headerT->assign("blogname",$blogName);
$headerT->assign("blogclass",$blogClass);
$headerT->assign("blogvar",$var);
$headerT->assign("blogurl",$blogurl);
$headerT->assign("static_blog_name",$static_blog_name);
$headerT->assign("discribe",$discribe);

$header_data = $headerT->result();
?>