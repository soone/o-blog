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

$lastBlogs = $DB->query("SELECT * FROM `".$mysql_prefix."blog` WHERE `draft`=0 ORDER BY `id` DESC LIMIT 0 , ".$showNum);
while($lastBlogRe = $DB->fetch_array($lastBlogs)) {
	$date = $lastBlogRe['date'];
	$path = getHtmlPath($lastBlogRe['id']);
	$path = ($makehtml) ? $path : "index.php?id=".$lastBlogRe['id'];
	$lastBlog[] = array(
		"id" => $lastBlogRe['id'],
		"path" => $path,
		"title" => cn_substr($lastBlogRe['title'],$lastblog_cut_char),
		"alltitle" => $lastBlogRe['title']
	);
}
$lastBlogT = template("lastblog");
$lastBlogT->assign("lastblog",$lastBlog);
$lastblog_data = $lastBlogT->result();
unset($lastBlogT,$lastBlog,$lastBlogs);

?>