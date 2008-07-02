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

header("content-Type: text/html; charset=gb2312");

file_exists("install.php")?die("��������<a href=\"install.php\">install.php</a>��װ��Ȼ��install.phpɾ���ٷ��ʡ�"):null;

require('config.php');
require('class/link.php');
require('class/calendar.php');
require('class/lastblog.php');
require('class/lastremark.php');
require('class/archive.php');
require('class/sort.php');
require('class/list.php');

is_close_blog();

if(isset($_GET['do']) && !empty($_GET['do']))
{
	$ac = checkPost($_GET['do']);
	switch($ac) {
		case 'class':
			$classid = checkPost(intval($_GET['id']));
			$sql = "SELECT * FROM `".$mysql_prefix."blog` WHERE `classid` = ".$classid;
			$sqlCount = "SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `classid` = ".$classid;
			$articleName = $DB->fetch_one("SELECT `classname` FROM `".$mysql_prefix."class` WHERE `id` = ".$classid);
			require('class/list.php');
			break;
		case 'ShowOneDayBlog':
			@$date = checkSQL($_GET['date']);
			require('class/article.php');
			$main = $OneDayBlogData;
			break;
		case 'gb':
			require('class/gb.php');
			$main = $gb_data;
			break;
		case 'newgb':
			$newgb = 1;
			require('class/gb.php');
			break;
		case 'newremark':
			$newremark = 1;
			require('class/remark.php');
			break;
		case 'search':
			$keyword = checkPost(htmlspecialchars(trim($_POST['keyword'])));
			if(empty($keyword)) {
				ob_exit("������ؼ���");
			}
			if(strlen($keyword) < 4) {
				ob_exit("�ؼ��ֲ�������4���ַ�");
			}
			if(isset($_COOKIE['ob_search_lock']) && trim($_COOKIE['ob_search_lock']) == "ob_search_lock") {
				ob_exit("�Բ��������������ļ��ʱ��������{$search_time}��");
			}
			$keyword = str_replace("_","\_",$keyword);
			$keyword = str_replace("%","\%",$keyword);
			$sql = "SELECT * FROM `".$mysql_prefix."blog` WHERE `title` LIKE '%".$keyword."%' OR `content` LIKE '%".$keyword."%'";
			$allCount = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `title` LIKE '%".$keyword."%' OR `content` LIKE '%".$keyword."%'");
			$articleName = "�������: ���� $allCount ����¼";
			
			if($allCount == 0) {
				ob_exit("û���ҵ����������ļ�¼");
			} else {
				//��������ʱ��������
				if($search_time) {
					setcookie("ob_search_lock","ob_search_lock",time()+$search_time);
				}
			}
			$nonepage = 1;
			require('class/list.php');
			break;
	}
}

if(isset($_GET['id']) && @!isset($_GET['do'])) {
	$id = intval($_GET['id']);
	$id = checkPost($id);
	$newremark = 0;
	require('class/article.php');
	require('class/remark.php');
	$main = $main.$remark_data;
}

if(isset($_GET['date']) && !isset($_GET['do'])) {
	$date = trim($_GET['date']);
	$date = checkPost($date);
	if(strlen($date) < 9) {
		if(strlen($date) == 8) {
			$sql = "SELECT * FROM `".$mysql_prefix."blog` WHERE  from_unixtime(date, '%Y%m%d') = $date";
			$sqlCount = "SELECT count(*) FROM `".$mysql_prefix."blog` WHERE  from_unixtime(date, '%Y%m%d') = $date";
			require('class/list.php');
		}
		if(strlen($date) == 6) {
			$sql = "SELECT * FROM `".$mysql_prefix."blog` WHERE  from_unixtime(date, '%Y%m') = $date";
			$sqlCount = "SELECT count(*) FROM `".$mysql_prefix."blog` WHERE  from_unixtime(date, '%Y%m') = $date";
			require('class/list.php');
		}
	} else {
		$date = substr($date,0,8);
		$sql = "SELECT * FROM `".$mysql_prefix."blog` WHERE  from_unixtime(date, '%Y%m%d') = $date";
		$sqlCount = "SELECT count(*) FROM `".$mysql_prefix."blog` WHERE  from_unixtime(date, '%Y%m%d') = $date";
		require('class/list.php');
	}
}

require('class/header.php');
echo $header_data;
$mainT = template("main");
$mainT->assign("discribe","$discribe");
$mainT->assign("calendar","$calendar");
$mainT->assign("link","$link_data");
$mainT->assign("lastblog","$lastblog_data");
$mainT->assign("lastRemark","$lastRemark_data");
$mainT->assign("archive","$archive_data");
$mainT->assign("sort","$sort_data");
$mainT->assign("blogurl","$blogurl");
$mainT->assign("main","$main");
$mainT->assign("today","$today");
$mainT->output('main.htm');
$boT = template('bo');
$boT->output();
?>