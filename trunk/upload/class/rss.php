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

function GetIndexShowPage()
{
	global $mysql_prefix;
    $sql = "SELECT `index_show_number` FROM `".$mysql_prefix."config`";
    $result = mysql_query($sql);
    $n_tmp = mysql_fetch_array($result);
    return $index_show_number = $n_tmp['index_show_number'];
} 

function GetURL()
{
	global $mysql_prefix;
	$path = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	$path = str_replace("/rss.php","",$path);
	$path = str_replace("/rss2.php","",$path);
	Return $path;
}

function GetTitle()
{
	global $mysql_prefix;
	$sql = "SELECT `blogname` FROM `".$mysql_prefix."config`";
	$result = mysql_query($sql);
	$re = mysql_fetch_array($result);
	return $re[0];
}

function GetBlog()
{
	global $mysql_prefix;
	$showNum = GetIndexShowPage();
	$sql = "SELECT * FROM `".$mysql_prefix."blog` WHERE `draft`=0 ORDER BY `id` DESC LIMIT 0, ".$showNum;
	if(isset($_GET['classid']) && $_GET['classid'] > 0) {
		$classid = intval($_GET['classid']);
		$sql = "SELECT * FROM `".$mysql_prefix."blog` WHERE `draft`=0 AND `classid`='{$classid}' ORDER BY `id` DESC LIMIT 0, ".$showNum;
	}
	$result = mysql_query($sql);
	$i = 0;
	while($re = mysql_fetch_array($result))
	{
		$blog[$i]['id'] 		= $re['id'];
		$blog[$i]['title']		= $re['title'];
		$blog[$i]['date']		= $re['date'];
		$blog[$i]['content']	= $re['content'];
		$blog[$i]['classid']	= $re['classid'];
		$i++;
	}
	return $blog;
}

function GetBlogNum()
{
	$blog_t =  GetBlog();
	return count($blog_t);
}

$URL		= GetURL();
$title		= GetTitle();
$blog		= GetBlog();
$blognum	= GetBlogNum();

?>