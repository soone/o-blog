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

$archives = $DB->query("SELECT distinct FROM_UNIXTIME(date, '%Y,%c') FROM `".$mysql_prefix."blog` WHERE `draft`=0  ORDER BY date ASC");
$monthname = array('','一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');
while($archive = $DB->fetch_array($archives)) {
	$archive_time = explode(",",$archive[0]);
	$archive_char = $monthname[$archive_time[1]]." ".$archive_time[0];
	$month_z = (strlen($archive_time[1]) == 1) ? "0".$archive_time[1] : $archive_time[1];
	$archive_link = $archive_time[0].$month_z;

	$archive_blog_num = $DB->fetch_one("SELECT count(*) FROM `{$mysql_prefix}blog` WHERE FROM_UNIXTIME(`date`,'%Y%c')='".$archive_time[0].$archive_time[1]."'");

	$archive_t[] = array(
		"archive_char" => $archive_char,
		"archive_link" => $blogurl."index.php?date=".$archive_link,
		"archive_blog_num" => $archive_blog_num
	);
}

$archiveT = template('archive');
$archiveT->assign("archive_t",$archive_t);
$archive_data = $archiveT->result();
unset($archiveT,$archive);
?>