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

$oneT = template('show_article');
$ubb = new Ubb();
$ones = $DB->query("SELECT * FROM `".$mysql_prefix."blog` WHERE `draft`=0 AND `id` = ".$id);
if($DB->num_rows($ones) == 0) {
	$oneT->assign("id",0);
	$oneT->assign("title","日志不存在或者已经被删除");
} else {
	while($oneRe = $DB->fetch_array($ones)) {
		$oneRe['content'] = trim($oneRe['content']);

		//替换关键字
		require('admin/class/autolink.php');
		if(count($autolink) != 0) {
			foreach($autolink as $key=>$val) {
				$pattern[] = "/(?<!http:\/\/)(".$val['keyword'].")(?![a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i";
				$replace[] = "[url={$val['url']}]\\1[/url]";
			}
			$oneRe['content'] = preg_replace($pattern,$replace,$oneRe['content']);
			unset($pattern,$replace);
		}

		$oneRe['content'] = str_replace(" ","&nbsp;",$oneRe['content']);
		if(intval($oneRe['allow_face'])) {
			$oneRe['content'] = qqface($oneRe['content'],"",$blogurl);
		}
		
		$oneRe['content'] = str_replace("[separator]","",$oneRe['content']);
		$date = $oneRe['date'];
		$path = $makehtml ? getHtmlPath($id) : "index.php?id=".$id;
		$ubb->setString($oneRe['content']);
		$oneRe['content'] = $ubb->parse();
		$oneT->assign("id",$oneRe['id']);
		$oneT->assign("title",$oneRe['title']);

		$oneT->assign("content",$oneRe['content']);
		$oneT->assign("path",$blogurl.$path);
		$oneT->assign("blogurl",$blogurl);
		$oneT->assign("show_viewcount",$show_viewcount);
		$articleName = $oneRe['title'];
	}
}
$main = $oneT->result();
?>