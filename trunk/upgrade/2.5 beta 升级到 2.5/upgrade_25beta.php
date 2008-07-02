<?php

/***************************************************
 O-blog 2.5 beta 到 O-blog 2.5 正式版 升级程序
 Support : http://www.phpBlog.cn
 ===================================================
 运行此文件之前，请确认您已经做完以下工作：

 1. 确认您原来的 O-blog 版本为 2.5 beta
 2. 已经将 O-blog 2.5 正式版的文件上传到您的服务器中，
    并覆盖了原来的文件(admin/mysql.php不覆盖)
 3. 此文件的位置在 O-blog 的根目录
 4.确认以下文件(夹)是可写的(777) (WINDOWS 主机跳过这一步)
   ./
   ./cache
   ./archives
   .bak
   ./uploadfiles
***************************************************/

//vars & functions

require_once('admin/mysql.php');

//styles
$header = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'><html><head><meta http-equiv='Content-Type' content='text/html; charset=gb2312'><title>无标题文档</title><style type='text/css'><!--.title {	font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif';	font-size: 11px;	font-weight: bold;	color: #000000;}.content {	font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif';	font-size: 11px;	color: #000000;	line-height: 22px;}.tableborder {	border: 1px solid #666666;}body {	background-color: #EFEFEF;}--></style><style type='text/css'><!--input {	font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif';	font-size: 11px;	height: 20px;	width: 200px;}--></style></head><body><form name='form1' method='post' action='".$_SERVER['PHP_SELF']."?do=upgrade'><table width='70%' border='0' align='center' cellpadding='5' cellspacing='1' bgcolor='#666666'><tr> <td bgcolor='#FFFFFF' class='title'>O-blog 升级程序<br> <br>For 2.5 beta -&gt; 2.5 正式版<br> <br> <br> </td></tr><tr> <td bgcolor='#EFEFEF' class='content'>";
$footer = "</td></tr></table></form></body></html>";
$upgradeForm = "<strong>我们建议您在升级之前，备份所有的文件和数据</strong><br> <br><strong>运行此文件之前，请确认您已经做完以下工作：</strong> <ol><li>确认您原来的 O-blog 版本为 2.5 beta</li><li>已经将 O-blog 2.5 正式版的文件上传到您的服务器中，并覆盖了原来的文件(admin/mysql.php不覆盖)</li><li>确认此文件的位置在 O-blog 的根目录</li><li>确认以下文件(夹)是可写的(777) (WINDOWS 主机跳过这一步)</li></ol><ul><li>./</li><li>./cache</li><li>./archives</li><li>.bak</li><li>./uploadfiles</li></ul>		<strong>注意：不要改此文件的文件名! </strong> <p>完成后，请点击下面的“开始升级”按钮。</p><p> <input type='submit' name='Submit' value='开始升级'></p>";

//querys
$sql[] = "CREATE TABLE `".$mysql_prefix."trackback` (`trackbackid` INT( 10 ) NOT NULL AUTO_INCREMENT ,`adddate` VARCHAR( 200 ) NOT NULL ,`title` VARCHAR( 200 ) NOT NULL ,`url` VARCHAR( 200 ) NOT NULL ,`excerpt` TEXT NOT NULL ,`blogname` VARCHAR( 200 ) NOT NULL ,`inblog` INT( 10 ) NOT NULL ,PRIMARY KEY ( `trackbackid` ) );";
$sql[] = "ALTER TABLE `".$mysql_prefix."blog` ADD `trackbackurl` VARCHAR( 200 ) NOT NULL AFTER `content`;";
$sql[] = "ALTER TABLE `".$mysql_prefix."config` ADD `extraname` VARCHAR( 100 ) DEFAULT 'html' NOT NULL AFTER `blogurl`;";
$sql[] = "ALTER TABLE `".$mysql_prefix."blog` ADD `filename` VARCHAR( 100 ) NOT NULL AFTER `content`;";
$sql[] = "ALTER TABLE `".$mysql_prefix."admin` ADD `auth` TEXT AFTER `remark`;";
$sql[] = "UPDATE `".$mysql_prefix."admin` SET `auth` = 'config,doconfig,phpinfo,addBlog,doaddBlog,editBlog,modBlog,domodBlog,delBlog,buildBlog,dosomeBlog,remarkManager,dosomeRemark,delRemark,checkRemark,banRemark,modRemark,domodRemark,addSort,doaddSort,editSort,delSort,modSort,domodSort,dosomeSort,addLink,doaddLink,editLink,delLink,modLink,domodLink,dosomeLink,bak,dobak,optimize,repair,dooptimize,dorepair,actlog,cleanActlog,userlog,cleanUserlog,password,updatepassword,guestbook,modgb,domodgb,replygb,doreplygb,delgb,addNote,doaddNote,editNote,viewNote,modNote,domodNote,delNote,uploadManager,bakManager,delFile,rebuild,dobuild,upload,doupload,addUser,doaddUser,editUser,modUser,domodUser,delUser,editTrackback,modTrackback,domodTrackback,delTrackback,logout';";
$sql[] = "ALTER TABLE `".$mysql_prefix."admin` ADD `id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;";
$sql[] = "ALTER TABLE `".$mysql_prefix."config` ADD `fullarticle` INT( 10 ) DEFAULT '0' NOT NULL AFTER `makehtml`;";
$sql[] = "ALTER TABLE `".$mysql_prefix."config` ADD `onefolder` INT( 10 ) DEFAULT '0' NOT NULL AFTER `fullarticle` ;";

// start upgrade
echo $header;

if(isset($_GET['do']) AND $_GET['do'] == 'upgrade') {
	
	$mysql = mysql_connect($mysql_add,$mysql_user,$mysql_pass);
	mysql_select_db($mysql_dbname);
	if(!$mysql) {
	die("无法连接到数据库".$footer);
	}

	foreach($sql as $key=>$val) {
		if(!mysql_query($val)) {
			die("执行以下 SQL 命令失败:<br><br>".$val.$footer);
		}
	}
	echo "<font color=red>升级成功,请删除此文件！</font>";

} else {
	echo $upgradeForm;
}

echo $footer;
?>