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

require('global.php');

isLogin();
$action = isset($_GET['action']) ? $_GET['action'] : "";

//判断是否允许用户上传文件
$id = intval($_COOKIE['ob_userid']);
$auth_char = $DB->fetch_one("SELECT `auth` FROM `".$mysql_prefix."admin` WHERE `id`=".$id);
if(strstr($auth_char,"upload") && strstr($auth_char,"doupload")) {
	$allow_user_upload = true;
} else {
	$allow_user_upload = false;
}
if(!$allow_user_upload) {
	echo "<link rel=\"stylesheet\" href=\"images/style.css\" type=\"text/css\">";
	echo "<body style=\"margin-left: 0px;margin-top: 6px;margin-right: 0px;margin-bottom: 0px;background-color: #FFFFFF;\">";
	echo " 对不起，您没有上传附件的权限";
	echo "</body>";
	die();
}

if($action == "upload_form") {
	echo "<link rel=\"stylesheet\" href=\"images/style.css\" type=\"text/css\">";
	echo "<body style=\"margin-left: 0px;margin-top: 1px;margin-right: 0px;margin-bottom: 0px;background-color: #FFFFFF;\">";
	echo "<form action=\"upload.php?action=doupload\" method=\"post\" enctype=\"multipart/form-data\">";
	echo "<input class=\"formfield\" type=\"file\" name=\"attachment\" size=\"30\" />\n";
	echo " <input name=\"submit\" type=\"submit\" value=\"上  传\">";
	echo "</form>";
	echo "</body>";
}

if($action == "doupload") {
	echo "<link rel=\"stylesheet\" href=\"images/style.css\" type=\"text/css\">";
	echo "<body style=\"margin-left: 0px;margin-top: 6px;margin-right: 0px;margin-bottom: 0px;background-color: #FFFFFF;\">";

	if (is_array($_FILES)) {
		$attachment      = $_FILES['attachment']['tmp_name'];
		$attachment_name = $uploadrandomfilename ? getmainfilename($_FILES['attachment']['name'])."_".rand(10000,99999).".".getextension($_FILES['attachment']['name']) : $_FILES['attachment']['name'];
		$attachment_size = $_FILES['attachment']['size'];
    }
	if(file_exists("uploadfiles/".$attachment_name)) {
		echo "<a href=upload.php?action=upload_form>同名的文件已存在，上传失败。 正在返回……</a>";
		echo "<meta http-equiv=\"refresh\" content=\"3;URL=javascript:history.go(-1);\"\">";
		die();
	}
	if (trim($attachment) != "none" and trim($attachment) != "" and trim($attachment_name) != "") {
		if(acceptupload_in_editor()) {
			$fileurl = $blogurl."/uploadfiles/".$attachment_name;
			$filetype = getextension($attachment_name);
			$imgtype=array("jpg","jpeg","gif","png","bmp");
			if(in_array($filetype,$imgtype)){
				$message="[img]".$fileurl."[/img]";
			 }elseif($filetype=="swf"){
				$message="[swf]".$fileurl."[/swf]";
			 }elseif($filetype=="mp3"){
				$message="[mp3]".$fileurl."[/mp3]";
			 }elseif($filetype=="wma"){
				$message="[wma]".$fileurl."[/wma]";
			 }elseif($filetype=="avi"||$filetype=="asf"||$filetype=="asx"||$filetype=="mpeg"){
				$message="[wmp]".$fileurl."[/wmp]";
			 }elseif($filetype=="rm"){
				$message="[rm]".$fileurl."[/rm]";
			 }else{
				$message="[url]".$fileurl."[/url]";
			 }
			 echo "<script>parent.document.input.message.value+=\"".$message."\";</script>";
			 echo "<a href=upload.php?action=upload_form>上传附件成功! 正在返回……</a>";
			 echo "<meta http-equiv=\"refresh\" content=\"3;URL=javascript:history.go(-1);\"\">";
		}
    }
	echo "</form>";
	echo "</body>";
}
?>