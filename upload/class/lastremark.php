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

$lastRemarks = $DB->query("SELECT * FROM `".$mysql_prefix."remark` WHERE `ischeck`=1 ORDER BY `date` DESC LIMIT 0 , ".$showNum);
while($lastRemarkRe = $DB->fetch_array($lastRemarks)) {
	$path = getHtmlPath($lastRemarkRe['inblog']);
	$path = ($makehtml) ? $path : "index.php?id=".$lastRemarkRe['inblog'];
	$lastRemark[] = array(
		"id" => $lastRemarkRe['inblog'],
		"path" => $path,
		"content" => cn_substr(strip_tags($lastRemarkRe['content']),$lastblog_cut_char),
		"allcontent" => cn_substr(strip_tags($lastRemarkRe['content']),200)
	);
}
$lastRemarkT = template("lastRemark");
$lastRemarkT->assign("lastRemark",$lastRemark);
$lastRemark_data = $lastRemarkT->result();
unset($lastRemarks,$lastRemarkRe,$lastRemarkT)
?>