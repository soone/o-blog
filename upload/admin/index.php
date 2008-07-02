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

if(isset($_POST['username']) && isset($_POST['password']) && @$_POST['username'] !== "") {
	$user = $DB->query("SELECT `username`, `password`, `id` FROM `".$mysql_prefix."admin` WHERE `username` = '".trim($_POST['username'])."'");
	$userRe = $DB->fetch_array($user);
	$userR = $userRe['username'];
	$passR = $userRe['password'];
	$idR = $userRe['id'];
	$username=addslashes(trim($_POST['username']));
	$password=addslashes(trim($_POST['password']));

	//判断验证码
	$show_verify_code = (function_exists("gd_info")) ? 1 : 0;
	if($show_verify_code) {
		if(!$DB->fetch_one("SELECT verify_code FROM {$mysql_prefix}config")) {
			$show_verify_code = 0;
		}
	}
	if($show_verify_code) {
		$verify_code_input = intval($_POST['verify_code']);
		$real_verify_md5_code = trim($_COOKIE['ob_verify_code_num']);
		if($real_verify_md5_code != md5($verify_code_input)) {
			$FORM->ob_exit("抱歉，验证码输入不正确","index.php");
		}
	}

	//判断用户名和密码
	if($userR == $username && $passR == md5($password)) {
		setcookie("ob_login","o-blog",time()+7200);
		setcookie("ob_userid",$idR,time()+7200);
		setcookie("ob_password",$passR,time()+7200);
		setcookie("ob_ip",md5(getip()),time()+7200);
		$FORM->frame($username);
		loginsucceed($username);
	} else {
		loginfaile($username);
		$FORM->ob_exit("抱歉，用户名或密码错误","index.php");
	}
} else {
	$FORM->login();
}

?>