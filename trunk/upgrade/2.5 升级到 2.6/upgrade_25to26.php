<?php
/*
+-----------------------------------------------------------------+
| O-BLOG - PHP Blog System                                        |
| Copyright (c) 2004 phpBlog.CN                                   |
| Support : http://www.phpBlog.cn                                 |
| Author : ShiShiRui (shishirui@163.com)                          |
|-----------------------------------------------------------------+
| O-blog 升级程序 For 2.5 -> 2.6                                  |
| 使用方法：先将新版本的文件覆盖旧版本文件(除了admin/mysql.php)   |
            再将此工具传到blog的根目录运行即可                    |
|-----------------------------------------------------------------+
*/
error_reporting(7);

if(!file_exists('config.php')) {
	die("无法找到 config.php 文件。请将次程序传到 O-blog 的根目录再运行。");
}

require('config.php');

print <<<EOT

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>O-blog 升级程序 For 2.5 -> 2.6</title>
<style type="text/css">
<!--
BODY {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 15px;
	font-weight: bold;
}
H1 {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 20px;
	font-weight: bold;
	margin: 0px;
	padding: 3px;
	border-bottom-width: 3px;
	border-bottom-style: solid;
	border-bottom-color: #C60000;
	width: 400px;
}
INPUT {
	font-size: 15px;
	background-color: #C60000;
	color: #FFFFFF;
	font-weight: bold;
	width: 200px;
	padding: 3px;
}
-->
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="upgrade_25to26.php">
  <h1>请输入管理员用户名和密码</h1>
  <br />注意：升级前请备份原始数据。升级开始后请不要重复提交或刷新! <br /><br />
  <label>
  用户名：
  <input name="username" type="text" id="username" />
  </label>
  
  <br />
  密&nbsp;&nbsp;&nbsp;&nbsp;码：
  <label>
  <input name="password" type="password" id="password" />
  </label>
  <br /><br />
  <label>
  <input type="submit" name="Submit" value="开始升级" />
  </label>
  <br />
</form>
</body>
</html>

EOT;

if(isset($_POST['username']) && isset($_POST['password'])) {
	$real_password = $DB->fetch_one("SELECT password FROM {$mysql_prefix}admin WHERE username='".trim($_POST['username'])."'");
	if($real_password != md5(trim($_POST['password']))) {
		die("用户名密码错误，请重试!");
	}

	$auth = "config,doconfig,phpinfo,addBlog,doaddBlog,editBlog,modBlog,domodBlog,delBlog,buildBlog,dosomeBlog,remarkManager,dosomeRemark,delRemark,checkRemark,banRemark,modRemark,domodRemark,addSort,doaddSort,editSort,delSort,modSort,domodSort,dosomeSort,addLink,doaddLink,editLink,delLink,modLink,domodLink,dosomeLink,bak,dobak,import,runsql,dorunsql,optimize,repair,dooptimize,dorepair,rssImport,doRssImport,rssImportSort,rssExport,doRssExport,actlog,cleanActlog,userlog,cleanUserlog,password,updatepassword,guestbook,modgb,domodgb,replygb,doreplygb,delgb,dosomeGb,banned,dobanned,addNote,doaddNote,editNote,viewNote,modNote,domodNote,delNote,uploadManager,bakManager,delFile,rebuild,dobuild,upload,doupload,addUser,doaddUser,editUser,modUser,domodUser,delUser,editTrackback,modTrackback,domodTrackback,delTrackback,dosomeTrackback,addAutolink,editAutolink,doaddAutolink,updateAutolink,delAutolink,selectTemplate,editTemplate,saveTemplate,logout";
	
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `keep_page_way` varchar (20)  DEFAULT 'day' NULL  after `fullarticle`, add column `archive_folder` varchar (100)  DEFAULT 'archives' NULL  after `keep_page_way`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `max_gb_char` int (10)   NULL  after `archive_folder`;";
	$sql[] = "update `{$mysql_prefix}config` set `max_gb_char`='6000';";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `date_format` varchar (100)  DEFAULT 'F j, Y, g:i a' NULL  after `max_gb_char`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `max_image_width` int (10)  DEFAULT '480' NULL  after `date_format`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `banned_username` text   NULL  after `max_image_width`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `banned_word` text   NULL  after `banned_username`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `banned_ip` text   NULL  after `banned_word`;";
	$sql[] = "alter table `{$mysql_prefix}blog` ,add column `draft` int (10)  DEFAULT '0' NULL  after `allow_face`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `post_gb_time` int (10)  DEFAULT '30' NULL  after `banned_ip`;";
	$sql[] = "alter table `{$mysql_prefix}link` ,add column `linkhidden` int (10)  DEFAULT '0' NULL  after `showorder`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `verify_code` int (10)  DEFAULT '0' NULL  after `post_gb_time`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `search_time` int (10)  DEFAULT '60' NULL  after `verify_code`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `date_format_remark` varbinary (100)  DEFAULT 'y-m-d H:m:s' NULL  after `date_format`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `date_format_gb` varchar (100)  DEFAULT 'y-m-d H:m:s' NULL  after `date_format_remark`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `close_blog` int (10)  DEFAULT '0' NULL  after `search_time`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `close_reason` text   NULL  after `close_blog`;";
	$sql[] = "Update `{$mysql_prefix}config` SET `close_reason`='对不起，此BLOG暂时被关闭。\r\n谢谢您的参观。';";
	$sql[] = "alter table `{$mysql_prefix}admin` ,change `email` `email` varchar (100)   NULL , change `homepage` `homepage` varchar (100)   NULL , change `qq` `qq` varchar (30)   NULL , change `msn` `msn` varchar (100)   NULL , change `icq` `icq` varchar (100)   NULL ;";
	$sql[] = "alter table `{$mysql_prefix}admin` ,add column `nickname` varchar (100)   NULL  after `password`;";
	$sql[] = "alter table `{$mysql_prefix}blog` ,add column `author` varchar (100)  DEFAULT 'unknow' NULL  after `filename`;";
	$sql[] = "alter table `{$mysql_prefix}blog` ,add column `viewcount` int (10)  DEFAULT '1' NULL  after `draft`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `superadmin` varchar (255) NULL  after `close_reason`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `show_viewcount` int (10)  DEFAULT '1' NULL  after `close_blog`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `lastblog_cut_char` int (10)  DEFAULT '20' NULL  after `show_viewcount`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `servertimezone` varchar (10)  DEFAULT '+8' NULL  after `lastblog_cut_char`;";
	$sql[] = "alter table `{$mysql_prefix}config` ,add column `clienttimezone` varchar (10)  DEFAULT '+8' NULL  after `servertimezone`;";
	$sql[] = "ALTER TABLE `{$mysql_prefix}blog` ADD INDEX `id` ( `id` );";
	$sql[] = "ALTER TABLE `{$mysql_prefix}blog` ADD INDEX `classid` ( `classid` );";
	$sql[] = "UPDATE `{$mysql_prefix}admin` SET `auth`='{$auth}' WHERE username='".trim($_POST['username'])."'";
	$sql[] = "UPDATE `{$mysql_prefix}config` SET superadmin='".trim($_POST['username'])."'";
	
	foreach($sql as $key=>$val) {
		if(!$DB->query($val)) {
			die("设定管理员出错！");
		}
	}
	die("恭喜！O-blog 升级成功！");
}
?>