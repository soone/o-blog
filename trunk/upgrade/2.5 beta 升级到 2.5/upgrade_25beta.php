<?php

/***************************************************
 O-blog 2.5 beta �� O-blog 2.5 ��ʽ�� ��������
 Support : http://www.phpBlog.cn
 ===================================================
 ���д��ļ�֮ǰ����ȷ�����Ѿ��������¹�����

 1. ȷ����ԭ���� O-blog �汾Ϊ 2.5 beta
 2. �Ѿ��� O-blog 2.5 ��ʽ����ļ��ϴ������ķ������У�
    ��������ԭ�����ļ�(admin/mysql.php������)
 3. ���ļ���λ���� O-blog �ĸ�Ŀ¼
 4.ȷ�������ļ�(��)�ǿ�д��(777) (WINDOWS ����������һ��)
   ./
   ./cache
   ./archives
   .bak
   ./uploadfiles
***************************************************/

//vars & functions

require_once('admin/mysql.php');

//styles
$header = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'><html><head><meta http-equiv='Content-Type' content='text/html; charset=gb2312'><title>�ޱ����ĵ�</title><style type='text/css'><!--.title {	font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif';	font-size: 11px;	font-weight: bold;	color: #000000;}.content {	font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif';	font-size: 11px;	color: #000000;	line-height: 22px;}.tableborder {	border: 1px solid #666666;}body {	background-color: #EFEFEF;}--></style><style type='text/css'><!--input {	font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif';	font-size: 11px;	height: 20px;	width: 200px;}--></style></head><body><form name='form1' method='post' action='".$_SERVER['PHP_SELF']."?do=upgrade'><table width='70%' border='0' align='center' cellpadding='5' cellspacing='1' bgcolor='#666666'><tr> <td bgcolor='#FFFFFF' class='title'>O-blog ��������<br> <br>For 2.5 beta -&gt; 2.5 ��ʽ��<br> <br> <br> </td></tr><tr> <td bgcolor='#EFEFEF' class='content'>";
$footer = "</td></tr></table></form></body></html>";
$upgradeForm = "<strong>���ǽ�����������֮ǰ���������е��ļ�������</strong><br> <br><strong>���д��ļ�֮ǰ����ȷ�����Ѿ��������¹�����</strong> <ol><li>ȷ����ԭ���� O-blog �汾Ϊ 2.5 beta</li><li>�Ѿ��� O-blog 2.5 ��ʽ����ļ��ϴ������ķ������У���������ԭ�����ļ�(admin/mysql.php������)</li><li>ȷ�ϴ��ļ���λ���� O-blog �ĸ�Ŀ¼</li><li>ȷ�������ļ�(��)�ǿ�д��(777) (WINDOWS ����������һ��)</li></ol><ul><li>./</li><li>./cache</li><li>./archives</li><li>.bak</li><li>./uploadfiles</li></ul>		<strong>ע�⣺��Ҫ�Ĵ��ļ����ļ���! </strong> <p>��ɺ���������ġ���ʼ��������ť��</p><p> <input type='submit' name='Submit' value='��ʼ����'></p>";

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
	die("�޷����ӵ����ݿ�".$footer);
	}

	foreach($sql as $key=>$val) {
		if(!mysql_query($val)) {
			die("ִ������ SQL ����ʧ��:<br><br>".$val.$footer);
		}
	}
	echo "<font color=red>�����ɹ�,��ɾ�����ļ���</font>";

} else {
	echo $upgradeForm;
}

echo $footer;
?>