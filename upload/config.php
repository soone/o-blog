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
require_once('admin/mysql.php');
require_once('class/ubb.php');
require_once('admin/functions.php');
require_once('admin/class/mysql.php');
require_once('admin/class/chinese.php');

$DB = new DB_MySQL;

//ѡ��
$articleNum		= "200";					//���½�ȡ����

//����MYSQL
$mysql = $DB->connect($mysql_add,$mysql_user,$mysql_pass,$mysql_dbname);

//����O-BLOG
define("BLOG", "INOBLOG");
$var = "2.6";

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
$max_gb_char = intval($config['max_gb_char']);
$date_format = trim($config['date_format']);
$date_format_remark = trim($config['date_format_remark']);
$date_format_gb = trim($config['date_format_gb']);
$banned_username = split_word(trim($config['banned_username']));
$banned_word = split_word(trim($config['banned_word']));
$banned_ip = split_word(trim($config['banned_ip']));
$post_gb_time = intval($config['post_gb_time']);
$search_time = intval($config['search_time']);
$close_blog = intval($config['close_blog']);
$show_viewcount = intval($config['show_viewcount']);
$close_reason = nl2br(trim($config['close_reason']));
$extraname = trim($config['extraname']);
$keep_page_way = trim($config['keep_page_way']);
$archive_folder = trim($config['archive_folder']);
$lastblog_cut_char = intval($config['lastblog_cut_char']);
$servertimezone = trim($config['servertimezone']);
$clienttimezone = trim($config['clienttimezone']);

$static_blog_name = $blogName;

//����ģ����
require_once("admin/class/template/class.smarttemplate.php");
function template($name) {
	global $TemplateName;
	Return new SmartTemplate('templates/'.$TemplateName.'/'.$name.'.htm');
}

//��õ�ǰ����
$week_n = obdate("w",time());
$week = array('������','����һ','���ڶ�','������','������','������','������');
$today = obdate("Y �� n �� j �� $week[$week_n]",time());

?>