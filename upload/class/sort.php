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

$sorts = $DB->query("SELECT * FROM `".$mysql_prefix."class` ORDER BY `showorder` ASC");
while($sortRe = $DB->fetch_array($sorts)) {
	$number = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `draft`=0 AND  `classid`=".$sortRe['id']);
	$sort[] = array(
		"id" => $sortRe['id'],
		"title" => trim($sortRe['classname']),
		"number" => $number,
		"blogurl" => $blogurl
	);
}
$sortT = template("sort");
$sortT->assign("sort",$sort);
$sort_data = $sortT->result();
unset($sortT,$sort,$sorts,$sortRe);
?>