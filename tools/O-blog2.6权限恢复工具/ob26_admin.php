<?php
/*
+--------------------------------------------------------+
| O-BLOG - PHP Blog System                               |
| Copyright (c) 2004 phpBlog.CN                          |
| Support : http://www.phpBlog.cn                        |
| Author : ShiShiRui (shishirui@163.com)                 |
|--------------------------------------------------------+
| O-blog ����ԱȨ�޻ָ����� for O-blog v2.6              |
| ʹ�÷��������˹��ߴ���blog�ĸ�Ŀ¼Ȼ�����м���         |
|--------------------------------------------------------+
*/
error_reporting(7);

if(!file_exists('config.php')) {
	die("�޷��ҵ� config.php �ļ����뽫�γ��򴫵� O-blog �ĸ�Ŀ¼�����С�");
}

require('config.php');

print <<<EOT

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>O-blog ����ԱȨ�޻ָ����� for O-blog v2.6</title>
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
  <h1>������Ҫ�ָ�Ȩ���û����û���������</h1>
  <br />
  �û�����
  <input name="username" type="text" id="username" />
  </label>
  
  <br />
  ��&nbsp;&nbsp;&nbsp;&nbsp;�룺
  <label>
  <input name="password" type="password" id="password" />
  </label>
  <br /><br />
  <label>
  <input type="submit" name="Submit" value="�ύ" />
  </label>
  <br />
</form>
</body>
</html>

EOT;

if(isset($_POST['username']) && isset($_POST['password'])) {
	$real_password = $DB->fetch_one("SELECT password FROM {$mysql_prefix}admin WHERE username='".trim($_POST['username'])."'");
	if($real_password != md5(trim($_POST['password']))) {
		die("�û����������������!");
	}

	$auth = "config,doconfig,phpinfo,addBlog,doaddBlog,editBlog,modBlog,domodBlog,delBlog,buildBlog,dosomeBlog,remarkManager,dosomeRemark,delRemark,checkRemark,banRemark,modRemark,domodRemark,addSort,doaddSort,editSort,delSort,modSort,domodSort,dosomeSort,addLink,doaddLink,editLink,delLink,modLink,domodLink,dosomeLink,bak,dobak,import,optimize,repair,dooptimize,dorepair,rssImport,doRssImport,rssImportSort,rssExport,doRssExport,actlog,cleanActlog,userlog,cleanUserlog,password,updatepassword,guestbook,modgb,domodgb,replygb,doreplygb,delgb,dosomeGb,banned,dobanned,addNote,doaddNote,editNote,viewNote,modNote,domodNote,delNote,uploadManager,bakManager,delFile,rebuild,dobuild,upload,doupload,addUser,doaddUser,editUser,modUser,domodUser,delUser,editTrackback,modTrackback,domodTrackback,delTrackback,dosomeTrackback,selectTemplate,editTemplate,saveTemplate,logout";
	if($DB->query("UPDATE {$mysql_prefix}admin SET `auth`='{$auth}' WHERE username='".trim($_POST['username'])."'")) {
		die("����Ȩ���Ѿ��ָ�!");
	} else {
		die("Ȩ�޻ָ�����");
	}
}
?>