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

$do = checkPost(trim($_GET['do']));

if($do == "add") {
	$blogid = intval($_GET['id']);
	if($blogid == 0) {
		exit;
	}
	if(!isset($_COOKIE['ob_has_view_'.$blogid]) || trim($_COOKIE['ob_has_view_'.$blogid]) != 'ob_has_view') {
		if($DB->query("UPDATE {$mysql_prefix}blog SET viewcount=viewcount+1 WHERE id='{$blogid}'")) {
			setcookie("ob_has_view_".$blogid, "ob_has_view",time()+3600);
		}
	}
}

if($do == "get") {
	$blogid = intval($_GET['id']);
	if($blogid == 0) {
		$this_blog_view = 0;
	} else {
		$this_blog_view = $DB->fetch_one("SELECT viewcount FROM {$mysql_prefix}blog WHERE id='{$blogid}'");
	}
	echo "document.write({$this_blog_view});";
}

?>