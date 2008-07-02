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

if (!defined('BLOG')) {
    die('Access Denied');
} 

$articleName = "留言";

if ($newgb) {
    if (empty($_POST['username']) || empty($_POST['content'])) {
        ob_exit("您的表单没有填写完整");
    } else {
			if(isset($_COOKIE['ob_post_gb_time']) && $_COOKIE['ob_post_gb_time'] == "enable_post") {
				ob_exit("您两次发表间隔应该大于{$post_gb_time}秒");
			}
			if(trim($_POST['email']) != "") {
				if(!CheckEmail(trim($_POST['email']))) {
					ob_exit("E-mail 格式不正确");
				}
			}
            $username = nl2br(htmlspecialchars($_POST['username']));
            $email = $_POST['email'];
            $content = nl2br(htmlspecialchars($_POST['content']));
            $date = time();
            $gbNum = $DB->fetch_one("SELECT count(*) FROM ".$mysql_prefix."guestbook");
			$newNum = $gbNum + 1;

			$username = checkPost($username);
			$email = checkPost($email);
			$content = checkPost($content);
			
			//过滤
			if(in_array($username,$banned_username)) {
				ob_exit("对不起，管理员禁止使用这个用户名");
			}
			if(in_array(getip(),$banned_ip)) {
				ob_exit("对不起，您已经被管理员禁止发表评论/留言");
			}
			foreach($banned_word as $key=>$val) {
				$content = str_replace($val,"*",$content);
			}
			if(strlen($content) > $max_gb_char) {
				ob_exit("留言字数超出限制");
			}

			$addGbSql = "INSERT INTO ".$mysql_prefix."guestbook (date, content, username, email, ip) VALUES ('".$date ."','".$content."','".$username."','".$email."','".getip()."')";
            if ($DB->query($addGbSql)) {
				if(!$post_gb_time) {
					ob_exit("感谢您的留言","?do=gb");
				} else {
					setcookie("ob_post_gb_time","enable_post",time()+$post_gb_time);
					ob_exit("感谢您的留言","?do=gb");
				}
            } else {
               ob_exit("留言出现错误");
            } 
    } 
} else {
    $gb = $gb_show_num;
    $gbN = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."guestbook`");	// 总留言数
    $pageN = ceil($gbN / $gb);	// 页数
    if (isset($_GET['page'])) {
        $page = $_GET['page'];	// 当前页
    } else {
        $page = 1;
    } 
    $start_item = ($page - 1) * $gb;

    $gbT = template('guestbook');
    $gbR = $DB->query("SELECT * FROM `".$mysql_prefix."guestbook` ORDER BY id DESC LIMIT " . $start_item . " , " . $gb);
    while ($gbRe = $DB->fetch_array($gbR)) {
        $gbA[] = array('name' => $gbRe['username'],
					   'email' => $gbRe['email'],
					   'date' => obdate($date_format_gb,$gbRe['date']),
					   'content' => $gbRe['content'],
					   'reply' => $gbRe['reply']
            );
    } 
    $gbT->assign('gbA', $gbA);
    $cut_page = page($gbN, $gb, $page, "?do=gb");
    $gbT->assign("cutpage", $cut_page);
    $gb_data = $gbT->result();
} 
?>