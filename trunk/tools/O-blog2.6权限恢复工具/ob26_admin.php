<?php
/*
+--------------------------------------------------------+
| O-BLOG - PHP Blog System                               |
| Copyright (c) 2004 phpBlog.CN                          |
| Support : http://www.phpBlog.cn                        |
| Author : ShiShiRui (shishirui@163.com)                 |
|--------------------------------------------------------+
| O-blog 管理员权限恢复工具 for O-blog v2.6              |
| 使用方法：将此工具传到blog的根目录然后运行即可         |
|--------------------------------------------------------+
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
<title>O-blog 管理员权限恢复工具 for O-blog v2.6</title>
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
<form id="form1" name="form1" method="post" action="ob26_admin.php">
  <label>
  <h1>请输入要恢复权限用户的用户名和密码</h1>
  <br />
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
  <input type="submit" name="Submit" value="提交" />
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

	$auth = "config,doconfig,phpinfo,addBlog,doaddBlog,editBlog,modBlog,domodBlog,delBlog,buildBlog,dosomeBlog,remarkManager,dosomeRemark,delRemark,checkRemark,banRemark,modRemark,domodRemark,addSort,doaddSort,editSort,delSort,modSort,domodSort,dosomeSort,addLink,doaddLink,editLink,delLink,modLink,domodLink,dosomeLink,bak,dobak,import,optimize,repair,dooptimize,dorepair,rssImport,doRssImport,rssImportSort,rssExport,doRssExport,actlog,cleanActlog,userlog,cleanUserlog,password,updatepassword,guestbook,modgb,domodgb,replygb,doreplygb,delgb,dosomeGb,banned,dobanned,addNote,doaddNote,editNote,viewNote,modNote,domodNote,delNote,uploadManager,bakManager,delFile,rebuild,dobuild,upload,doupload,addUser,doaddUser,editUser,modUser,domodUser,delUser,editTrackback,modTrackback,domodTrackback,delTrackback,dosomeTrackback,selectTemplate,editTemplate,saveTemplate,logout";
	if($DB->query("UPDATE {$mysql_prefix}admin SET `auth`='{$auth}' WHERE username='".trim($_POST['username'])."'")) {
		die("所有权限已经恢复!");
	} else {
		die("权限恢复出错！");
	}
}
?>