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

$links = $DB->query("SELECT * FROM `".$mysql_prefix."link` WHERE `linkhidden`=0 ORDER BY `showorder` ASC");
while($linkRe = $DB->fetch_array($links)) {
	$link[] = array(
		"id" => $linkRe['id'],
		"name" => $linkRe['sitename'],
		"url" => $linkRe['linkurl'],
		"alt" => $linkRe['alt'],
	);
}
$linkT = template("link");
$linkT->assign("link",$link);
$link_data = $linkT->result();
unset($links,$link,$linkT,$linkRe);
?>