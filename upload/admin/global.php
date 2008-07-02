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
$articleNum = "200";	//文章截取字数

$uploadrandomfilename = true;		//上传的文件名是否加验证码。true,false

//允许上传的文件类型
$allow_file_type = array("rar","zip","7z","tar.gz","wmv","wma","wav","mp3","mp4","3gp","torrent","rm","rmvb","txt","doc","xls","ppt","wps","jpg","gif","bmp","png","psd","ttf","ai");

//配置参数
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

//权限列表
$auth[] = array(
	"name" => "参数设置",
	"action" => "config,doconfig",
	"check" => 0,
);
$auth[] = array(
	"name" => "PHPINFO",
	"action" => "phpinfo",
	"check" => 0,
);
$auth[] = array(
	"name" => "添加日志",
	"action" => "addBlog,doaddBlog",
	"check" => 1,
);
$auth[] = array(
	"name" => "编辑日志",
	"action" => "editBlog,modBlog,domodBlog,delBlog,buildBlog,dosomeBlog",
	"check" => 1,
);
$auth[] = array(
	"name" => "评论管理",
	"action" => "remarkManager,dosomeRemark,delRemark,checkRemark,banRemark,modRemark,domodRemark",
	"check" => 1,
);
$auth[] = array(
	"name" => "添加分类",
	"action" => "addSort,doaddSort",
	"check" => 1,
);
$auth[] = array(
	"name" => "编辑分类",
	"action" => "editSort,delSort,modSort,domodSort,dosomeSort",
	"check" => 1,
);
$auth[] = array(
	"name" => "添加链接",
	"action" => "addLink,doaddLink",
	"check" => 0,
);
$auth[] = array(
	"name" => "编辑链接",
	"action" => "editLink,delLink,modLink,domodLink,dosomeLink",
	"check" => 0,
);
$auth[] = array(
	"name" => "备份/恢复数据库",
	"action" => "bak,dobak,import",
	"check" => 0,
);
$auth[] = array(
	"name" => "执行SQL查询",
	"action" => "runsql,dorunsql",
	"check" => 0,
);
$auth[] = array(
	"name" => "优化/修复数据",
	"action" => "optimize,repair,dooptimize,dorepair",
	"check" => 1,
);
$auth[] = array(
	"name" => "RSS导入/导出",
	"action" => "rssImport,doRssImport,rssImportSort,rssExport,doRssExport",
	"check" => 0,
);
$auth[] = array(
	"name" => "操作记录",
	"action" => "actlog,cleanActlog",
	"check" => 0,
);
$auth[] = array(
	"name" => "登陆记录",
	"action" => "userlog,cleanUserlog",
	"check" => 0,
);
$auth[] = array(
	"name" => "修改密码",
	"action" => "password,updatepassword",
	"check" => 1,
);
$auth[] = array(
	"name" => "留言管理",
	"action" => "guestbook,modgb,domodgb,replygb,doreplygb,delgb,dosomeGb",
	"check" => 1,
);
$auth[] = array(
	"name" => "过滤管理",
	"action" => "banned,dobanned",
	"check" => 0,
);
$auth[] = array(
	"name" => "添加记事",
	"action" => "addNote,doaddNote",
	"check" => 0,
);
$auth[] = array(
	"name" => "管理记事",
	"action" => "editNote,viewNote,modNote,domodNote,delNote",
	"check" => 0,
);
$auth[] = array(
	"name" => "文件管理",
	"action" => "uploadManager",
	"check" => 0,
);
$auth[] = array(
	"name" => "备份文件管理",
	"action" => "bakManager",
	"check" => 0,
);
$auth[] = array(
	"name" => "文件/数据删除",
	"action" => "delFile",
	"check" => 0,
);
$auth[] = array(
	"name" => "重建静态页面",
	"action" => "rebuild,dobuild",
	"check" => 0,
);
$auth[] = array(
	"name" => "文件上传",
	"action" => "upload,doupload",
	"check" => 0,
);
$auth[] = array(
	"name" => "添加用户",
	"action" => "addUser,doaddUser",
	"check" => 0,
);
$auth[] = array(
	"name" => "编辑用户",
	"action" => "editUser,modUser,domodUser,delUser",
	"check" => 0,
);
$auth[] = array(
	"name" => "通告引用管理",
	"action" => "editTrackback,modTrackback,domodTrackback,delTrackback,dosomeTrackback",
	"check" => 1,
);
$auth[] = array(
	"name" => "自动链接",
	"action" => "addAutolink,editAutolink,doaddAutolink,updateAutolink,delAutolink",
	"check" => 1,
);
$auth[] = array(
	"name" => "模板编辑",
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