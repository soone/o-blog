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

if($newremark) {
	if(empty($_POST['username']) || empty($_POST['content'])) {
		ob_exit("您的表单没有填写完整");
	} else {
		if(isset($_COOKIE['ob_post_gb_time']) && $_COOKIE['ob_post_gb_time'] == "enable_post") {
			ob_exit("您两次发表间隔应该大于{$post_gb_time}秒");
		}
		foreach($banned_word as $key=>$val) {
			if(@strstr($_POST['content'],$val)) {
				ob_exit("对不起，您发表的内容中包含被禁止的词语");
			}
		}
		$inblog	 = intval(@$_POST['inblog']);
		$allow_remark = $DB->fetch_one("SELECT `allow_remark` FROM `".$mysql_prefix."blog` WHERE `id` = ".$inblog);
		if(!$allow_remark) {
			ob_exit("此日志不允许评论");
		}
		if(trim($_POST['email']) != "") {
			if(!CheckEmail(trim($_POST['email']))) {
				ob_exit("E-mail 格式不正确");
			} else {
				$email = $_POST['email'];
			}
		}
		$checkremark = $DB->fetch_one("SELECT `checkremark` FROM `".$mysql_prefix."config`");
		$checkremark = ($checkremark == 1) ? 0 : 1;
		$username = nl2br(htmlspecialchars(checkPost($_POST['username'])));
		$content = nl2br(htmlspecialchars(checkPost($_POST['content'])));
		//记住用户信息
		$cookietime=time()+31536000;
		if ($_POST['remember']) {
			setcookie("remark_name",$username,$cookietime);
			setcookie("remark_email",$email,$cookietime);
		}

		//过滤
		if(in_array($username,$banned_username)) {
			ob_exit("对不起，管理员禁止使用这个用户名");
		}
		if(in_array(getip(),$banned_ip)) {
			ob_exit("对不起，您已经被管理员禁止发表评论/留言");
		}
		if(strlen($content) > $max_gb_char) {
				ob_exit("评论字数超出限制");
		}
		$DB->query("INSERT INTO ".$mysql_prefix."remark (`date`, `content`, `username`, `email`, `inblog`, `ip`, `ischeck`) VALUES ('".time()."', '".$content."', '".$username."', '".$email."', '".$inblog."','".getip()."','".$checkremark."')");

		$blogName = trim($config['blogname']);

		//生成HTML页面
		if($makehtml) {
			require('admin/class/build.php');
			$html = new build;
			$html->makeindex();
			$html->make($id);
		}
		$date = $DB->fetch_one("SELECT `date` FROM `".$mysql_prefix."blog` WHERE `id` = ".$inblog);
		$path = getHtmlPath($inblog);
		$path = ($makehtml) ? $path : "?id=".$inblog;
		if($checkremark) {
			if(!$post_gb_time) {
				ob_exit("感谢您的评论",$path);
			} else {
				setcookie("ob_post_gb_time","enable_post",time()+$post_gb_time);
				ob_exit("感谢您的评论",$path);
			}
			
			
		} else {
			ob_exit("感谢您的评论，但是您的评论需要审核后才能显示",$path);
		}
	}
} else {
	if(isset($_POST['inblog'])) {
		$id = intval($_POST['inblog']);
	}
	$remarks = $DB->query("SELECT * FROM `".$mysql_prefix."remark` WHERE `ischeck` = 1 AND `inblog` = ".$id." ORDER BY id ASC");
	$remarkNum = $DB->num_rows($remarks);
	$orderid = 1;
	while($remarkRe = $DB->fetch_array($remarks)) {
		$remark[] = array("id" => $orderid++,
						"content" => $remarkRe['content'],
						"username" => $remarkRe['username'],
						"date" => obdate($date_format,$remarkRe['date']),
						"email" => $remarkRe['email'],
			);
	}
	if($makehtml) {
		$_COOKIE['remark_name'] = "";
		$_COOKIE['remark_email'] = "";
	}
	$remarkT = template("remark");
	$remarkT->assign("name",$_COOKIE['remark_name']);
	$remarkT->assign("email",$_COOKIE['remark_email']);
	$remarkT->assign("num",$remarkNum);
	$remarkT->assign("remark",$remark);
	$remarkT->assign("inblog",$id);
	$remarkT->assign("blogurl",$blogurl);
	$remarkT->assign("makehtml",$makehtml);

	$trackbacks = $DB->query("SELECT * FROM `".$mysql_prefix."trackback` WHERE `inblog` = ".$id." ORDER BY `trackbackid` ASC");
	$tbnum = $DB->num_rows($trackbacks);
	$tborderid = 1;
	while($trackbackRe = $DB->fetch_array($trackbacks)) {
		$trackback[] = array("id" => $tborderid++,
							"trackbackid" => $trackbackRe['trackbackid'],
							"date" => obdate($date_format,$trackbackRe['adddate']),
							"title" => trim($trackbackRe['title']),
							"url" => trim($trackbackRe['url']),
							"excerpt" => trim($trackbackRe['excerpt']),
							"blogname" => trim($trackbackRe['blogname']),
							"inblog" => trim($trackbackRe['inblog']),
		);
	}
	$remarkT->assign("trackback",$trackback);
	$remarkT->assign("tbnum",$tbnum);

	$remark_data = $remarkT->result();
}
?>