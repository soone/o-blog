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

$articleName = "����";

if ($newgb) {
    if (empty($_POST['username']) || empty($_POST['content'])) {
        ob_exit("���ı�û����д����");
    } else {
			if(isset($_COOKIE['ob_post_gb_time']) && $_COOKIE['ob_post_gb_time'] == "enable_post") {
				ob_exit("�����η�����Ӧ�ô���{$post_gb_time}��");
			}
			if(trim($_POST['email']) != "") {
				if(!CheckEmail(trim($_POST['email']))) {
					ob_exit("E-mail ��ʽ����ȷ");
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
			
			//����
			if(in_array($username,$banned_username)) {
				ob_exit("�Բ��𣬹���Ա��ֹʹ������û���");
			}
			if(in_array(getip(),$banned_ip)) {
				ob_exit("�Բ������Ѿ�������Ա��ֹ��������/����");
			}
			foreach($banned_word as $key=>$val) {
				$content = str_replace($val,"*",$content);
			}
			if(strlen($content) > $max_gb_char) {
				ob_exit("����������������");
			}

			$addGbSql = "INSERT INTO ".$mysql_prefix."guestbook (date, content, username, email, ip) VALUES ('".$date ."','".$content."','".$username."','".$email."','".getip()."')";
            if ($DB->query($addGbSql)) {
				if(!$post_gb_time) {
					ob_exit("��л��������","?do=gb");
				} else {
					setcookie("ob_post_gb_time","enable_post",time()+$post_gb_time);
					ob_exit("��л��������","?do=gb");
				}
            } else {
               ob_exit("���Գ��ִ���");
            } 
    } 
} else {
    $gb = $gb_show_num;
    $gbN = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."guestbook`");	// ��������
    $pageN = ceil($gbN / $gb);	// ҳ��
    if (isset($_GET['page'])) {
        $page = $_GET['page'];	// ��ǰҳ
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