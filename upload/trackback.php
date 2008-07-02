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

require('config.php');

header('Content-type: text/xml');

$id = @intval($_GET['id']);
$encode = @checkPost(trim($_GET['encode']));

if(!isset($_POST['url'])){
	$url = trim(checkPost($_GET['url']));
	$title = trim(checkPost($_GET['title']));
	$excerpt = trim(checkPost($_GET['excerpt']));
	$blog_name = trim(checkPost($_GET['blog_name']));
} else {
	$url = trim(checkPost($_POST['url']));
	$title = trim(checkPost($_POST['title']));
	$excerpt = trim(checkPost($_POST['excerpt']));
	$blog_name = trim(checkPost($_POST['blog_name']));
}

$error = 1;

if(empty($url) OR substr($url,0,7)!='http://') {
	$msg='Invalid Parameter!';
} elseif(empty($id)){
	$msg='TrackBack info is missing!';
} elseif(empty($encode)) {
	$msg='Character encoding is null!';
} else {
	
	if(strtolower($encode) == 'utf-8') {
		$Encode = new Chinese("UTF8","GB2312",$title);
		$title = $Encode->ConvertIT();
		unset($Encode);
		$Encode = new Chinese("UTF8","GB2312",$excerpt);
		$excerpt = $Encode->ConvertIT();
		unset($Encode);
		$Encode = new Chinese("UTF8","GB2312",$blog_name);
		$blog_name = $Encode->ConvertIT();
		unset($Encode);
	}
	
	$insert_sql = "INSERT INTO `".$mysql_prefix."trackback` (`adddate` , `title` , `url` , `excerpt` , `blogname` , `inblog` ) VALUES ('".time()."', '".$title."', '".$url."', '".$excerpt."', '".$blog_name."', '".$id."')";
	if($DB->query($insert_sql)) {
		if($makehtml) {
			require('admin/class/build.php');
			$html = new build;
			$html->makeindex();
			$html->make($id);
		}
		$error=0;
	} else {
		$msg='Could not save trackback data, possibly because of mysql database!';
	}
}

echo '<?xml version="1.0" encoding="gb2312"?>';
if($error) {
	echo '<response><error>1</error><message>'.$msg.'</message></response>';
} else {
	echo '<response><error>0</error></response>';
}
?>