<?php
/*
+--------------------------------------------------------+
| O-BLOG - PHP Blog System                               |
| Copyright (c) 2004 phpblog.cn                          |
| Support : http://www.phpBlog.cn                        |
| Author : shishirui (shirui@gmail.com)                  |
|--------------------------------------------------------+
*/
error_reporting(7);

header("content-Type: text/html; charset=gb2312");

$header = "<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\"><title>O-BLOG 2.6 ��װ����</title><style type=\"text/css\"><!--TABLE {font-family: Georgia, \"Times New Roman\", Times, serif;font-size: 14px;color: #000000;}.header {font-family: Georgia, \"Times New Roman\", Times, serif;font-size: 18px;font-weight: bold;color: #FFFFFF;background-color: #B50000;}--></style><style type=\"text/css\"><!--INPUT {font-family: Georgia, \"Times New Roman\", Times, serif;font-size: 14px;width: 260px;background-color: #B50000;color: #FFFFFF;font-weight: bold;height: 28px;}.itemtitle {font-size: 18px;}--></style></head><body bgcolor=\"#F3F3F3\"><form name=\"form1\" method=\"post\" action=\"install.php?do=action\">  <table width=\"650\" border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"1\" bgcolor=\"#CCCCCC\">    <tr valign=\"top\" bgcolor=\"#666666\"><td height=\"80\" colspan=\"3\" bgcolor=\"#F3F3F3\" class=\"header\"><strong>O-BLOG 2.6 ��װ����</strong>&nbsp;</td></tr><tr><td colspan=\"3\" bgcolor=\"#FFFFFF\">";

$footer = "</td></tr></table></form></body></html>";

function set_writeable($file) {
	if(is_writeable($file)) {
		echo "����ļ����У�$file ���� <strong>��д</strong><br>";
	} else {
		echo "����ļ����У�$file ���� <strong>����д</strong><br>���ڸı�Ȩ�� ���� ";
		if(@chmod($file,0777)) {
		echo "<strong>��д</strong><br>";
		} else {
		echo "<strong>ʧ��,���ֶ����Ĵ��ļ�����Ȩ�ޣ�</strong><br>";
		exit;
		}
	}
}

function querySQL() {
	global $mysql_prefix;
	
	$sql[] = "SET NAMES 'gbk'";
	$sql[] = "drop table if exists `".$mysql_prefix."admin`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."admin` ( `id` int(10) NOT NULL auto_increment, `username` varchar(15) default 'admin', `password` varchar(40) default '21232f297a57a5a743894a0e4a801fc3', `nickname` varchar(100) default NULL, `auth` text, PRIMARY KEY (`id`)) CHARSET=gbk TYPE=MyISAM;";
	$sql[] = "insert into `".$mysql_prefix."admin` values (1,'admin','21232f297a57a5a743894a0e4a801fc3','����Ա','config,doconfig,phpinfo,addBlog,doaddBlog,editBlog,modBlog,domodBlog,delBlog,buildBlog,dosomeBlog,remarkManager,dosomeRemark,delRemark,checkRemark,banRemark,modRemark,domodRemark,addSort,doaddSort,editSort,delSort,modSort,domodSort,dosomeSort,addLink,doaddLink,editLink,delLink,modLink,domodLink,dosomeLink,bak,dobak,import,runsql,dorunsql,optimize,repair,dooptimize,dorepair,rssImport,doRssImport,rssImportSort,rssExport,doRssExport,actlog,cleanActlog,userlog,cleanUserlog,password,updatepassword,guestbook,modgb,domodgb,replygb,doreplygb,delgb,dosomeGb,banned,dobanned,addNote,doaddNote,editNote,viewNote,modNote,domodNote,delNote,uploadManager,bakManager,delFile,rebuild,dobuild,upload,doupload,addUser,doaddUser,editUser,modUser,domodUser,delUser,editTrackback,modTrackback,domodTrackback,delTrackback,dosomeTrackback,addAutolink,editAutolink,doaddAutolink,updateAutolink,delAutolink,selectTemplate,editTemplate,saveTemplate,logout');";

	$sql[] = "drop table if exists `".$mysql_prefix."adminlog`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."adminlog` ( `id` int(15) unsigned NOT NULL auto_increment, `action` varchar(50) default NULL, `script` varchar(255) default NULL, `date` varchar(20) default NULL, `ip` varchar(20) default NULL, PRIMARY KEY (`id`)) CHARSET=gbk TYPE=MyISAM;";

	$sql[] = "drop table if exists `".$mysql_prefix."blog`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."blog` ( `id` int(10) NOT NULL auto_increment, `date` varchar(20) default NULL, `title` varchar(200) default NULL, `content` text, `trackbackurl` varchar(200) NOT NULL default '', `filename` varchar(100) NOT NULL default '', `author` varchar(100) default 'unknow', `classid` int(10) default '1', `top` int(10) NOT NULL default '0', `allow_remark` int(10) NOT NULL default '1', `allow_face` int(10) unsigned default '1', `draft` int(10) default NULL, `viewcount` int(10) default '1', PRIMARY KEY (`id`), KEY `id` (`id`), KEY `classid` (`classid`)) CHARSET=gbk TYPE=MyISAM;";
	$sql[] = "insert into `".$mysql_prefix."blog` values (1,'1140854346','��ӭʹ�� O-blog 2.6','����һƪϵͳ�Զ�д�����־��������ɾ������','','hello','unknow',2,0,1,1,0,2);";

	$sql[] = "drop table if exists `".$mysql_prefix."class`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."class` ( `id` int(10) NOT NULL auto_increment, `classname` varchar(255) default NULL, `showorder` int(10) NOT NULL default '0', PRIMARY KEY (`id`)) CHARSET=gbk TYPE=MyISAM;";
	$sql[] = "insert into `".$mysql_prefix."class` values (2,'Ĭ�Ϸ���',1);";

	$sql[] = "drop table if exists `".$mysql_prefix."config`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."config` ( `blogname` varchar(100) default NULL, `template` varchar(50) default 'default', `blogdescribe` varchar(255) default NULL, `blogurl` varchar(255) default NULL, `extraname` varchar(100) NOT NULL default 'html', `index_show_number` int(10) default '10', `lastblog` int(10) default NULL, `gb_show_num` int(10) default NULL, `checkremark` int(10) default NULL, `makehtml` int(10) default '1', `fullarticle` int(10) NOT NULL default '0', `keep_page_way` varchar(20) default 'day', `archive_folder` varchar(100) default 'archives', `max_gb_char` int(10) default NULL, `date_format` varchar(100) default 'F j, Y, g:i a', `date_format_remark` varchar(100) binary default 'y-m-d H:m:s', `date_format_gb` varchar(100) default 'y-m-d H:m:s', `max_image_width` int(10) default '480', `banned_username` text, `banned_word` text, `banned_ip` text, `post_gb_time` int(10) default '30', `verify_code` int(10) default NULL, `search_time` int(10) default '60', `close_blog` int(10) default NULL, `show_viewcount` int(10) default '1', `lastblog_cut_char` int(10) default '20', `servertimezone` varchar(10) default '+8', `clienttimezone` varchar(10) default '+8', `close_reason` text, `superadmin` varchar(255) default NULL) CHARSET=gbk TYPE=MyISAM;";
	$sql[] = "insert into `".$mysql_prefix."config` values ('O-blog 2.6','xhtml','Welcome to O-blog','http://shirui/php/ob26/','html',10,10,10,0,1,0,'day','archives',6000,'F j, Y, g:i a','y-m-d H:m:s','y-m-d H:m:s',440,'admin','','',30,0,60,0,0,20,'+8','+8','�Բ��𣬴�BLOG��ʱ���رա�\r\nлл���Ĳιۡ�','admin');";

	$sql[] = "drop table if exists `".$mysql_prefix."guestbook`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."guestbook` ( `id` int(10) unsigned NOT NULL auto_increment, `date` varchar(20) default NULL, `username` varchar(50) default '�ο�', `email` varchar(100) default NULL, `ip` varchar(20) default NULL, `content` text, `reply` text, PRIMARY KEY (`id`)) CHARSET=gbk TYPE=MyISAM;";
	$sql[] = "insert into `".$mysql_prefix."guestbook` values (2,'1141450387','�ο�','','169.254.205.12','����һƪϵͳ���ԣ�������ɾ������','');";

	$sql[] = "drop table if exists `".$mysql_prefix."link`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."link` ( `id` int(5) unsigned NOT NULL auto_increment, `sitename` varchar(100) default NULL, `linkurl` varchar(80) default NULL, `alt` text NOT NULL, `showorder` int(10) unsigned NOT NULL default '0', `linkhidden` int(10) default NULL, PRIMARY KEY (`id`)) CHARSET=gbk TYPE=MyISAM;";
	$sql[] = "insert into `".$mysql_prefix."link` values (1,'O-blog','http://www.phpBlog.cn','O-blog',1,0);";

	$sql[] = "drop table if exists `".$mysql_prefix."loginlog`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."loginlog` ( `id` int(10) NOT NULL auto_increment, `username` varchar(100) NOT NULL default '', `date` varchar(20) NOT NULL default '', `ip` varchar(20) NOT NULL default '', `result` int(10) NOT NULL default '0', PRIMARY KEY (`id`)) CHARSET=gbk TYPE=MyISAM;";

	$sql[] = "drop table if exists `".$mysql_prefix."note`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."note` ( `id` int(10) unsigned NOT NULL auto_increment, `date` varchar(20) default NULL, `title` varchar(200) default NULL, `content` text, PRIMARY KEY (`id`)) CHARSET=gbk TYPE=MyISAM;";
	$sql[] = "insert into `".$mysql_prefix."note` values (1,'1142917992','ϵͳ����','����һƪϵͳ�Զ�д��ļ��£�������ɾ������');";

	$sql[] = "drop table if exists `".$mysql_prefix."remark`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."remark` ( `id` int(100) NOT NULL auto_increment, `username` varchar(50) default '�ο�', `email` varchar(100) default NULL, `content` text, `date` varchar(20) default NULL, `ip` varchar(100) default '127.0.0.1', `inblog` int(10) unsigned default NULL, `ischeck` int(10) unsigned default '1', PRIMARY KEY (`id`)) CHARSET=gbk TYPE=MyISAM;";
	$sql[] = "insert into `".$mysql_prefix."remark` values (1,'ϵͳ����','','����һƪϵͳд������ۣ�������ɾ������','1141302695','169.254.205.12',1,1);";

	$sql[] = "drop table if exists `".$mysql_prefix."trackback`;";
	$sql[] = "CREATE TABLE `".$mysql_prefix."trackback` ( `trackbackid` int(10) NOT NULL auto_increment, `adddate` varchar(200) NOT NULL default '', `title` varchar(200) NOT NULL default '', `url` varchar(200) NOT NULL default '', `excerpt` text NOT NULL, `blogname` varchar(200) NOT NULL default '', `inblog` int(10) NOT NULL default '0', PRIMARY KEY (`trackbackid`)) CHARSET=gbk TYPE=MyISAM;";

	foreach($sql as $key=>$val) {
		if(!mysql_query($val)) {
			die("��������ʧ��:<br>query:<br>".$val."<br>".mysql_error()."<br>");
		}
	}
	Return true;
}

function writeAdmin() {
	global $s_add,$s_name,$s_user,$s_pass,$s_prefix;
	$fp = fopen("admin/mysql.php","w") or die("�޷����ļ�");
	$write_data = "<?php"
				."\n"
				."//mysql.php"
				."\n\n"
				."/* Mysql Server */\n"
				."\$mysql_add\t\t\t= "
				."'".$s_add."';"
				."\n\n"
				."/* Mysql User */\n"
				."\$mysql_user\t\t\t= "
				."'".$s_user."';"
				."\n\n"
				."/* Mysql Password */\n"
				."\$mysql_pass\t\t\t= "
				."'".$s_pass."';"
				."\n\n"
				."/* Mysql Database Name */\n"
				."\$mysql_dbname\t\t= "
				."'".$s_name."';"
				."\n\n"
				."/* Mysql Database Table Prefix */\n"
				."\$mysql_prefix\t\t= "
				."'".$s_prefix."';"
				."\n\n?>";
	if(!fwrite($fp, $write_data)) {
		die("�ļ�д��ʧ�ܣ�");
	}
}

function updateAdmin() {
	global $admin_user,$admin_nickname,$admin_pass,$blogurl,$mysql_prefix;
	$admin_pass = md5($admin_pass);
	$adminsql[] = "UPDATE `".$mysql_prefix."admin` SET username= '".$admin_user."',nickname= '".$admin_nickname."', password= '".$admin_pass."'";
	$adminsql[] = "UPDATE `".$mysql_prefix."config` SET blogurl= '".$blogurl."'";
	$adminsql[] = "UPDATE`".$mysql_prefix."config` SET superadmin='".$admin_user."'";
	foreach($adminsql as $key=>$val) {
		if(!mysql_query($val)) {
			die("�趨����Ա����");
		}
	}
	echo "�趨����Ա ���� �ɹ�";
}

if(isset($_GET['do']) && $_GET['do'] == 'action') {
	echo $header;
	echo "<b>����ļ�Ŀ¼�Ƿ��д��</b>";
	echo "<hr size=1>";

	set_writeable("./cache");
	set_writeable("./archives");
	set_writeable("./uploadfiles");
	set_writeable("./bak");
	set_writeable("./admin/mysql.php");
	set_writeable("./");
	
	$s_add			= trim($_POST['s_add']);
	$s_name			= trim($_POST['s_name']);
	$s_user			= trim($_POST['s_user']);
	$s_pass			= trim($_POST['s_pass']);
	$s_prefix		= trim($_POST['s_prefix']);
	$blogurl		= trim($_POST['blogurl']);
	$admin_user		= trim($_POST['admin_user']);
	$admin_nickname	= trim($_POST['admin_nickname']);
	$admin_pass		= trim($_POST['admin_pass']);
	$mysql_prefix = $s_prefix;

	echo "<hr size=1>";
	$conn = @mysql_connect($s_add,$s_user,$s_pass)
		or die("<br><b><font color=red>Could not connect: " . mysql_error()."</font></b>");
	if(@mysql_query("CREATE DATABASE ".$s_name)) {
		echo "���ݿ⽨���ɹ� ���� �ɹ�<br>";
	}
	mysql_select_db($s_name) or die("<br><b><font color=red>Can not select database!</font></b>");
	writeAdmin();
	echo "�������ݱ� ���� ";
	if(querySQL()) {
		echo "�ɹ�";
	}
	echo "<hr size=1>";
	updateAdmin();
	
	echo "<hr size=1>";
	if (@chmod('install.php', 0777) && @unlink('install.php')) {
		die("��ϲ������װ�ɹ����������Ϳ���ʹ���ˣ�".$footer);
	} else {
		die("��ϲ������װ�ɹ���<br>������ɾ�� <b>install.php</b> ,���Ϳ���ʹ���ˣ�".$footer);
	}
	
	echo $footer;
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>O-BLOG 2.6 ��װ����</title>
<style type="text/css">
<!--
TABLE {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 14px;
	color: #000000;
}
.header {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 18px;
	font-weight: bold;
	color: #FFFFFF;
	background-color: #B50000;
}
-->
</style>
<style type="text/css">
<!--
INPUT {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 14px;
	width: 260px;
	background-color: #B50000;
	color: #FFFFFF;
	font-weight: bold;
	height: 28px;
}
.itemtitle {
	font-size: 18px;
}
-->
</style>
</head>

<body bgcolor="#F3F3F3">
<form name="form1" method="post" action="install.php?do=action">
  <table width="650" border="0" align="center" cellpadding="10" cellspacing="1" bgcolor="#CCCCCC">
    <tr valign="top" bgcolor="#666666"> 
      <td height="80" colspan="3" bgcolor="#F3F3F3" class="header"><strong>O-BLOG 2.6 ��װ����</strong>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3" bgcolor="#FFFFFF">
            <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr class="itemtitle"> 
            <td width="36%"><strong>һЩ˵��</strong> </td>
            <td width="64%">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2">��ӭʹ�� O-BLOG������һ����ѵ� BLOG ���������������޸ĺʹ�����<br>
              �������ʹ�����������⣬���Ÿ��ң�<a href="mailto:shirui@gmail.com">mailto:shirui@gmail.com</a></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="itemtitle"> 
            <td><strong>�����ļ���Ȩ��</strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2">��linuxϵͳ�����б���װ����ʱ������ftp�������ӵ����������������ļ�����������Ϊ��д(777)��</td>
          </tr>
          <tr> 
            <td colspan="2"><ul>
                <li>��./cache</li>
                <li>��./archives</li>
                <li>��./admin/mysql.php</li>
				<li>��./admin/class/autolink.php</li>
                <li>��./uploadfiles</li>
                <li>��./bak</li>
				<li>��./templates �����ļ����µ������ļ�(��)</li>
				<li>��./</li>
              </ul></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="itemtitle"> 
            <td><strong>���ݿ�����</strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>���ݿ��ַ��</td>
            <td><input name="s_add" type="text" id="s_add2" value="localhost"></td>
          </tr>
          <tr> 
            <td>���ݿ����ƣ�</td>
            <td><input name="s_name" type="text" id="s_name2" value="dbname"></td>
          </tr>
          <tr> 
            <td height="26">���ݿ��û�����</td>
            <td><input name="s_user" type="text" id="s_user3" value="root"></td>
          </tr>
          <tr> 
            <td>���ݿ����룺</td>
            <td><input name="s_pass" type="password" id="s_pass3"></td>
          </tr>
          <tr>
            <td>���ݱ�ǰ׺��</td>
            <td><input name="s_prefix" type="text" id="s_pass4" value="ob_"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="itemtitle"> 
            <td><strong>��������</strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>BLOG��URL��ַ��</td>
            <td><input name="blogurl" type="text" id="blogurl2" value="<?=str_replace("install.php","","http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'])?>"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="itemtitle"> 
            <td><strong>����Ա����</strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>�û�����</td>
            <td><input name="admin_user" type="text" id="admin_user2"></td>
          </tr>
		  <tr> 
            <td>�ǳƣ�</td>
            <td><input name="admin_nickname" type="text" id="admin_nickname2"></td>
          </tr>
          <tr> 
            <td>���룺</td>
            <td><input name="admin_pass" type="password" id="admin_pass2"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr align="center"> 
            <td colspan="2"> <input type="submit" name="Submit" value="����ˣ���ʼ��װ��"> 
              <input type="reset" name="Submit2" value="����"> </td>
          </tr>
        </table>
          </li>
        </ul>
        </td>
    </tr>
  </table>
</form>
</body>
</html>
