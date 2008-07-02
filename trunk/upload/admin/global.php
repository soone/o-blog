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

require_once('class/forms.php');
require_once('functions.php');
require_once('class/mysql.php');
require_once('class/build.php');
require_once('../class/ubb.php');
require_once('class/chinese.php');
require_once('mysql.php');

$var = "2.6";
define("BLOG", "INOBLOG");
$FORM = new cpForms;
$UBB = new Ubb();
$DB = new DB_MySQL;
$mysql = $DB->connect($mysql_add,$mysql_user,$mysql_pass,$mysql_dbname);
$HTML = new build;
$HTML->root = "";
$articleNum = "200";	//���½�ȡ����

$uploadrandomfilename = true;		//�ϴ����ļ����Ƿ����֤�롣true,false

//�����ϴ����ļ�����
$allow_file_type = array("rar","zip","7z","tar.gz","wmv","wma","wav","mp3","mp4","3gp","torrent","rm","rmvb","txt","doc","xls","ppt","wps","jpg","gif","bmp","png","psd","ttf","ai");

//���ò���
$config = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."config`");
$blogurl = trim($config['blogurl']);
$makehtml = intval($config['makehtml']);
$index_show_number = intval($config['index_show_number']);
$discribe = trim($config['blogdescribe']);
$TemplateName = trim($config['template']);
$blogName = trim($config['blogname']);
$showNum = intval($config['lastblog']);
$gb_show_num = intval($config['gb_show_num']);
$fullarticle = intval($config['fullarticle']);
$extraname = trim($config['extraname']);
$tb_name = trim($config['blogname']);
$date_format = trim($config['date_format']);
$date_format_remark = trim($config['date_format_remark']);
$date_format_gb = trim($config['date_format_gb']);
$keep_page_way = trim($config['keep_page_way']);
$archive_folder = trim($config['archive_folder']);
$superadmin = trim($config['superadmin']);
$show_viewcount = intval($config['show_viewcount']);
$servertimezone = trim($config['servertimezone']);
$clienttimezone = trim($config['clienttimezone']);
$lastblog_cut_char = intval($config['lastblog_cut_char']);


$static_blog_name = $blogName;

$userid = @intval($_COOKIE['ob_userid']);
$current_auth_char = @$DB->fetch_one("SELECT `auth` FROM `".$mysql_prefix."admin` WHERE `id`=".$userid);

//Ȩ���б�
$auth[] = array(
	"name" => "��������",
	"action" => "config,doconfig",
	"check" => 0,
);
$auth[] = array(
	"name" => "PHPINFO",
	"action" => "phpinfo",
	"check" => 0,
);
$auth[] = array(
	"name" => "�����־",
	"action" => "addBlog,doaddBlog",
	"check" => 1,
);
$auth[] = array(
	"name" => "�༭��־",
	"action" => "editBlog,modBlog,domodBlog,delBlog,buildBlog,dosomeBlog",
	"check" => 1,
);
$auth[] = array(
	"name" => "���۹���",
	"action" => "remarkManager,dosomeRemark,delRemark,checkRemark,banRemark,modRemark,domodRemark",
	"check" => 1,
);
$auth[] = array(
	"name" => "��ӷ���",
	"action" => "addSort,doaddSort",
	"check" => 1,
);
$auth[] = array(
	"name" => "�༭����",
	"action" => "editSort,delSort,modSort,domodSort,dosomeSort",
	"check" => 1,
);
$auth[] = array(
	"name" => "�������",
	"action" => "addLink,doaddLink",
	"check" => 0,
);
$auth[] = array(
	"name" => "�༭����",
	"action" => "editLink,delLink,modLink,domodLink,dosomeLink",
	"check" => 0,
);
$auth[] = array(
	"name" => "����/�ָ����ݿ�",
	"action" => "bak,dobak,import",
	"check" => 0,
);
$auth[] = array(
	"name" => "ִ��SQL��ѯ",
	"action" => "runsql,dorunsql",
	"check" => 0,
);
$auth[] = array(
	"name" => "�Ż�/�޸�����",
	"action" => "optimize,repair,dooptimize,dorepair",
	"check" => 1,
);
$auth[] = array(
	"name" => "RSS����/����",
	"action" => "rssImport,doRssImport,rssImportSort,rssExport,doRssExport",
	"check" => 0,
);
$auth[] = array(
	"name" => "������¼",
	"action" => "actlog,cleanActlog",
	"check" => 0,
);
$auth[] = array(
	"name" => "��½��¼",
	"action" => "userlog,cleanUserlog",
	"check" => 0,
);
$auth[] = array(
	"name" => "�޸�����",
	"action" => "password,updatepassword",
	"check" => 1,
);
$auth[] = array(
	"name" => "���Թ���",
	"action" => "guestbook,modgb,domodgb,replygb,doreplygb,delgb,dosomeGb",
	"check" => 1,
);
$auth[] = array(
	"name" => "���˹���",
	"action" => "banned,dobanned",
	"check" => 0,
);
$auth[] = array(
	"name" => "��Ӽ���",
	"action" => "addNote,doaddNote",
	"check" => 0,
);
$auth[] = array(
	"name" => "�������",
	"action" => "editNote,viewNote,modNote,domodNote,delNote",
	"check" => 0,
);
$auth[] = array(
	"name" => "�ļ�����",
	"action" => "uploadManager",
	"check" => 0,
);
$auth[] = array(
	"name" => "�����ļ�����",
	"action" => "bakManager",
	"check" => 0,
);
$auth[] = array(
	"name" => "�ļ�/����ɾ��",
	"action" => "delFile",
	"check" => 0,
);
$auth[] = array(
	"name" => "�ؽ���̬ҳ��",
	"action" => "rebuild,dobuild",
	"check" => 0,
);
$auth[] = array(
	"name" => "�ļ��ϴ�",
	"action" => "upload,doupload",
	"check" => 0,
);
$auth[] = array(
	"name" => "����û�",
	"action" => "addUser,doaddUser",
	"check" => 0,
);
$auth[] = array(
	"name" => "�༭�û�",
	"action" => "editUser,modUser,domodUser,delUser",
	"check" => 0,
);
$auth[] = array(
	"name" => "ͨ�����ù���",
	"action" => "editTrackback,modTrackback,domodTrackback,delTrackback,dosomeTrackback",
	"check" => 1,
);
$auth[] = array(
	"name" => "�Զ�����",
	"action" => "addAutolink,editAutolink,doaddAutolink,updateAutolink,delAutolink",
	"check" => 1,
);
$auth[] = array(
	"name" => "ģ��༭",
	"action" => "selectTemplate,editTemplate,saveTemplate",
	"check" => 0,
);
$auth[] = array(
	"name" => "others",
	"action" => "logout",
	"check" => 0,
);

@chdir("../");

require_once("class/template/class.smarttemplate.php");
$TemplateName = $DB->fetch_one("SELECT `template` FROM `".$mysql_prefix."config` ");
function template($name) {
	global $TemplateName;
	Return new SmartTemplate('templates/'.$TemplateName.'/'.$name.'.htm');
}

?>