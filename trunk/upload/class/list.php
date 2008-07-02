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

$page = isset($_GET['page']) ? checkPost(intval($_GET['page'])) : 1;
if($nonepage) {
	$index_show_number = 999999;
}

$start_item = ($page - 1) * $index_show_number;
if(!isset($sql)) {
	$sql = "SELECT * FROM `".$mysql_prefix."blog` WHERE `draft`=0 ORDER BY `top` DESC, `date` DESC LIMIT ".$start_item." , ".$index_show_number;
} else {
	$sql = $sql." AND `draft`=0 ORDER BY `top` DESC, `date` DESC LIMIT ".$start_item." , ".$index_show_number;
}
if(!isset($sqlCount)) {
	$sqlCount = "SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `draft`=0";
}
$blogNum = $DB->fetch_one($sqlCount);
$blogs = $DB->query($sql);
while($blogRe = $DB->fetch_array($blogs)) {
	$className = $DB->fetch_one("SELECT `classname` FROM `".$mysql_prefix."class` WHERE `id` = ".$blogRe['classid']);
	$remarkNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark` WHERE `ischeck` = 1 AND `inblog` = ".$blogRe['id']);
	$trackbackNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."trackback` WHERE `inblog` = ".$blogRe['id']);
	if(!$fullarticle) {
		if(strstr($blogRe['content'],"[separator]")) {
			$content_arr = explode("[separator]",$blogRe['content']);
			$blogRe['content'] = $content_arr[0]."...";
		} else {
			$blogRe['content'] = cn_substr($blogRe['content'],$articleNum);
		}
	}
	$blogRe['content'] = trim($blogRe['content']);
	//替换关键字
	require('admin/class/autolink.php');
	if(count($autolink) != 0) {
		foreach($autolink as $key=>$val) {
			$pattern[] = "/(?<!http:\/\/)(".$val['keyword'].")(?![a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i";
			$replace[] = "[url=".$val['url']."]\\1[/url]";
		}
		$blogRe['content'] = preg_replace($pattern,$replace,$blogRe['content']);
		unset($pattern,$replace);
	}
	
	$blogRe['content'] = str_replace(" ","&nbsp;",$blogRe['content']);
	if(intval($blogRe['allow_face'])) {
		$blogRe['content'] = qqface($blogRe['content'],"",$blogurl);
	}
	$date = $blogRe['date'];
	$path = getHtmlPath($blogRe['id']);
	$path = ($makehtml) ? $path : "index.php?id=".$blogRe['id'];
	$ubb = new Ubb();
	$ubb->setString($blogRe['content']);
	$blogRe['content'] = $ubb->parse();
	unset($ubb);
	if($blogRe['top']) {
		$blogRe['onlytitle'] = $blogRe['title'];
		$blogRe['title'] = "<span style=\"color:red\">[置顶]</span>".$blogRe['title'];
	} else {
		$blogRe['title'] = $blogRe['title'];
	}
	$blog[] = array(
		"id" => $blogRe['id'],
		"date" => obdate($date_format,$blogRe['date']),
		"title" => $blogRe['title'],
		"onlytitle" => $blogRe['onlytitle'],
		"content" => $blogRe['content'],
		"classid" => $blogRe['classid'],
		"className" => $className,
		"remarkNum" => $remarkNum,
		"trackbackNum" => $trackbackNum,
		"path" => $path,
		"author" => $blogRe['author'],
		);
}
if($blog == NULL) {
	$blog = array(
		"id" => 0,
		"date" => obdate($date_format,time()),
		"title" => "目前还没有日志",
		"content" => "正等着你添加呢 :)",
		"classid" => 1,
		"className" => "默认分类",
		"remarkNum" => 0
		);
}
unset($redirect);
if(isset($inhtml) && $inhtml == 1) {
	$redirect = $blogurl."index.php?";
} else {
	$redirect = $blogurl."index.php"."?".$_SERVER['QUERY_STRING'];
	$redirect = ereg_replace("&page=[0-9]+","",$redirect);
	$redirect = str_replace("do=newremark","",$redirect);
	$redirect = str_replace("&","&amp;",$redirect);
}

$cut_page = page($blogNum, $index_show_number, $page, $redirect);
$blogT = template('articleList');
$blogT->assign("list",$blog);
$blogT->assign("blogurl",$blogurl);
$blogT->assign('page',$cut_page);
$blogT->assign('show_viewcount',$show_viewcount);
$main = $blogT->result();
unset($page,$blogNum,$start_item,$ubb,$blogs,$blog,$blogRe,$blogT,$cut_page,$cutT);
?>