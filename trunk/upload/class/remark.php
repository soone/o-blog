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
		ob_exit("���ı�û����д����");
	} else {
		if(isset($_COOKIE['ob_post_gb_time']) && $_COOKIE['ob_post_gb_time'] == "enable_post") {
			ob_exit("�����η�����Ӧ�ô���{$post_gb_time}��");
		}
		foreach($banned_word as $key=>$val) {
			if(@strstr($_POST['content'],$val)) {
				ob_exit("�Բ���������������а�������ֹ�Ĵ���");
			}
		}
		$inblog	 = intval(@$_POST['inblog']);
		$allow_remark = $DB->fetch_one("SELECT `allow_remark` FROM `".$mysql_prefix."blog` WHERE `id` = ".$inblog);
		if(!$allow_remark) {
			ob_exit("����־����������");
		}
		if(trim($_POST['email']) != "") {
			if(!CheckEmail(trim($_POST['email']))) {
				ob_exit("E-mail ��ʽ����ȷ");
			} else {
				$email = $_POST['email'];
			}
		}
		$checkremark = $DB->fetch_one("SELECT `checkremark` FROM `".$mysql_prefix."config`");
		$checkremark = ($checkremark == 1) ? 0 : 1;
		$username = nl2br(htmlspecialchars(checkPost($_POST['username'])));
		$content = nl2br(htmlspecialchars(checkPost($_POST['content'])));
		//��ס�û���Ϣ
		$cookietime=time()+31536000;
		if ($_POST['remember']) {
			setcookie("remark_name",$username,$cookietime);
			setcookie("remark_email",$email,$cookietime);
		}

		//����
		if(in_array($username,$banned_username)) {
			ob_exit("�Բ��𣬹���Ա��ֹʹ������û���");
		}
		if(in_array(getip(),$banned_ip)) {
			ob_exit("�Բ������Ѿ�������Ա��ֹ��������/����");
		}
		if(strlen($content) > $max_gb_char) {
				ob_exit("����������������");
		}
		$DB->query("INSERT INTO ".$mysql_prefix."remark (`date`, `content`, `username`, `email`, `inblog`, `ip`, `ischeck`) VALUES ('".time()."', '".$content."', '".$username."', '".$email."', '".$inblog."','".getip()."','".$checkremark."')");

		$blogName = trim($config['blogname']);

		//����HTMLҳ��
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
				ob_exit("��л��������",$path);
			} else {
				setcookie("ob_post_gb_time","enable_post",time()+$post_gb_time);
				ob_exit("��л��������",$path);
			}
			
			
		} else {
			ob_exit("��л�������ۣ���������������Ҫ��˺������ʾ",$path);
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