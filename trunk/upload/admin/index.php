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



$username=checkPost(trim($_POST['username']));
$password=checkPost(trim($_POST['password']));

if($username && $password) {
	$user = $DB->query("SELECT `username`, `password`, `id` FROM `".$mysql_prefix."admin` WHERE `username` = '".$username."'");
	$userRe = $DB->fetch_array($user);
	$userR = $userRe['username'];
	$passR = $userRe['password'];
	$idR = $userRe['id'];

	//�ж���֤��
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
			$FORM->ob_exit("��Ǹ����֤�����벻��ȷ","index.php");
		}
	}

	//�ж��û���������
	if($userR == $username && $passR == md5($password)) {
		setcookie("ob_login","o-blog",time()+7200);
		setcookie("ob_userid",$idR,time()+7200);
		setcookie("ob_password",$passR,time()+7200);
		setcookie("ob_ip",md5(getip()),time()+7200);
		$FORM->frame($username);
		loginsucceed($username);
	} else {
		loginfaile($username);
		$FORM->ob_exit("��Ǹ���û������������","index.php");
	}
} else {
	$FORM->login();
}

?>
