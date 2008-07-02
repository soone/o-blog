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
if($action != "phpinfo" && $action != "logout" && @trim($_POST['saveto']) != "local") {
	$FORM->cpheader();
}

ifAllow();

//�������ñ�
if($action == "config") {
	chdir("admin");
	$configs = $DB->query("SELECT * FROM `".$mysql_prefix."config`");
	$config = $DB->fetch_array($configs);
	if ($handle = @opendir('../templates')) {
		while (false !== ($file = readdir($handle))) {
			if(is_dir('../templates/'.$file) && $file != "." && $file != "..") {
				$dir[$file] = $file;
			}
		}
		closedir($handle);
	}
	if(!function_exists("gd_info")) {
		$config['verify_code'] = 0;
		$disable = 1;
	} else {
		$disable = 0;
	}
	$extraname = array("html"=>"html","htm"=>"htm","shtml"=>"shtml","phtml"=>"phtml","do"=>"do","blog"=>"blog","php"=>"php");
	$keep_page_way = array("day"=>"���մ��","month"=>"���´��","yeah"=>"������","all"=>"�ŵ�һ���ļ��� ");
	$FORM->formheader(array("title" => "��������","action" => "admin.php?action=doconfig"));
	$FORM->makeinput(array(
				"text"  => "BLOG ����",
				"note"  => "��ʾ����ҳ�ı�������ÿҳ����",
				"name"  => "blogname",
				"value" => $config['blogname']
            ));
	$FORM->makeinput(array(
				"text"  => "BLOG ����",
				"note"  => "�������Blog�ļ򵥽���",
				"name"  => "blogdescribe",
				"value" => $config['blogdescribe']
            ));
	$FORM->makeinput(array(
				"text"  => "BLOG ��URL��ַ",
				"note"  => "����:http://www.phpblog.cn",
				"name"  => "blogurl",
				"value" => $config['blogurl']
            ));
	$FORM->makeinput(array(
				"text"  => "ÿҳ��־��",
				"note"  => "ǰ̨��־�б���ÿҳ��ʾ����־��",
				"name"  => "indexNum",
				"value" => $config['index_show_number']
            ));
	$FORM->makeinput(array(
				"text"  => "������־/������",
				"note"  => "ǰ̨������־������������ʾ������",
				"name"  => "newNum",
				"value" => $config['lastblog']
            ));
	$FORM->makeinput(array(
				"text"  => "ÿҳ������",
				"note"  => "ǰ̨ÿҳ��ʾ���ٸ�����",
				"name"  => "gbNum",
				"value" => $config['gb_show_num']
            ));
	$FORM->makeinput(array(
				"text"  => "����/�����ַ�������",
				"note"  => "���οͿ��Է��������/���������������,Ĭ��Ϊ6000",
				"name"  => "max_gb_char",
				"value" => $config['max_gb_char']
            ));
	$FORM->makeinput(array(
				"text"  => " ��������/���Լ��ʱ��",
				"note"  => "�οͷ�������/���Ե����ټ��ʱ�䡣Ϊ0ʱ�����ơ���λ:��",
				"name"  => "post_gb_time",
				"value" => $config['post_gb_time']
            ));
	$FORM->makeinput(array(
				"text"  => " ������־/���۱����ȡ�ַ���",
				"note"  => "��������ģ��ʱ��������Ҫ���Ĵ��",
				"name"  => "lastblog_cut_char",
				"value" => $config['lastblog_cut_char']
            ));
	$FORM->makeinput(array(
				"text"  => " �������ʱ��",
				"note"  => "�ο�����ʱ���ټ��ʱ�䡣Ϊ0ʱ�����ơ���λ:��",
				"name"  => "search_time",
				"value" => $config['search_time']
            ));
	$FORM->makeinput(array(
				"text"  => "��ž�̬�ļ���Ŀ¼��",
				"note"  => "Ĭ��Ϊ\"archives\",���ĺ������������ļ���",
				"name"  => "archive_folder",
				"value" => $config['archive_folder']
            ));
	$FORM->makeinput(array(
				"text"  => "��־ʱ���ʽ",
				"note"  => "ͨ���������ı���־��ʱ����ʾ��ʽ��<a href=\"http://cn.php.net/manual/zh/function.date.php\" target=\"_blank\">�﷨˵��</a>",
				"name"  => "date_format",
				"value" => $config['date_format']
            ));
	$FORM->makeinput(array(
				"text"  => "����ʱ���ʽ",
				"note"  => "ͨ���������ı����۵�ʱ����ʾ��ʽ��<a href=\"http://cn.php.net/manual/zh/function.date.php\" target=\"_blank\">�﷨˵��</a>",
				"name"  => "date_format_remark",
				"value" => $config['date_format_remark']
            ));
	$FORM->makeinput(array(
				"text"  => "����ʱ���ʽ",
				"note"  => "ͨ���������ı����Ե�ʱ����ʾ��ʽ��<a href=\"http://cn.php.net/manual/zh/function.date.php\" target=\"_blank\">�﷨˵��</a>",
				"name"  => "date_format_gb",
				"value" => $config['date_format_gb']
            ));
	$FORM->makeinput(array(
				"text"  => "ͼƬ�����",
				"note"  => "ͼƬ���������Ⱥ�ᱻ���Ϲ���������λ:����(px)",
				"name"  => "max_image_width",
				"value" => $config['max_image_width']
            ));
	$FORM->makeinput(array(
				"text"  => "������־����Ա",
				"note"  => "ֻ��������û��ſ��Թ���ȫ����־��<br>�����û�ֻ���Թ����Լ�����־���û�֮���ö��Ÿ���",
				"name"  => "superadmin",
				"value" => $config['superadmin']
            ));
	$FORM->makeselect(array(
				"text"  => "�趨ģ��",
				"note"  => "ѡ����Ҫʹ�õ�ģ�壬���ĺ����ؽ�һ�ξ�̬ҳ��",
				"name"  => "template",
				"option" => $dir,
				"selected" => $config['template']
            ));
	$FORM->makeselect(array(
				"text"  => "��̬�ļ���չ��",
				"note"  => "����������˴�����ؽ�һ�ξ�̬ҳ��",
				"name"  => "extraname",
				"option" => $extraname,
				"selected" => $config['extraname']
            ));
	$FORM->makeselect(array(
				"text"  => "��̬�ļ��Ĵ�ŷ�ʽ",
				"note"  => "�������ľ�̬�ļ���ŷ�ʽ",
				"name"  => "keep_page_way",
				"option" => $keep_page_way,
				"selected" => $config['keep_page_way']
            ));
	$FORM->makeselect(array(
				"text"  => "����������ʱ��",
				"note"  => "Blog���ڵķ������Ƿ��ڵ�����ĸ�ʱ��",
				"name"  => "servertimezone",
				"option" => fetch_timezone(),
				"selected" => $config['servertimezone']
            ));
	$FORM->makeselect(array(
				"text"  => "�ÿ�����ʱ��",
				"note"  => "���Blog��Ҫ�����ĸ�ʱ�����û�",
				"name"  => "clienttimezone",
				"option" => fetch_timezone(),
				"selected" => $config['clienttimezone']
            ));
	$FORM->makeyesno(array("text" => "�Ƿ����������",
		"note" => "������������Ҫ�ں�̨���֮�������ʾ",
		"name" => "checkremark",
		"selected" => $config['checkremark']));
	$FORM->makeyesno(array("text" => "�Ƿ����ɾ�̬ҳ",
		"note" => "����������ÿƪ��־��HTMLҳ��",
		"name" => "makehtml",
		"selected" => $config['makehtml']));
	$FORM->makeyesno(array("text" => "�Ƿ���ʾȫ��",
		"note" => "����������־�б�����ʾ��־ȫ��",
		"name" => "fullarticle",
		"selected" => $config['fullarticle']));
	$FORM->makeyesno(array("text" => "��½ʱ�Ƿ�ʹ����֤��",
		"note" => "ֻ���ڷ�����֧��GDʱ�ſ��Կ���",
		"name" => "verify_code",
		"selected" => $config['verify_code'],
		"disable" => $disable));
	$FORM->makeyesno(array("text" => "�Ƿ���ʾ�Ķ�����",
		"note" => "�Ƿ���ʾ�Ķ��������������Ӱ��ҳ�������ٶȡ�",
		"name" => "show_viewcount",
		"selected" => $config['show_viewcount']));
	$FORM->makeyesno(array("text" => "�ر� Blog",
		"note" => "������ʱ�ر����� Blog",
		"name" => "close_blog",
		"selected" => $config['close_blog']));
	$FORM->maketextarea(array("text" => "�ر�Blogԭ��","note" => "��д���ر�Blogԭ�򣬽���Blog���ر�ʱ����Ч��","name" => "close_reason","value" => strip_tags($config['close_reason'])));
	$FORM->formfooter();
}

//ִ�в�������
if($action == "doconfig") {
	$blogname = htmlspecialchars(checkPost(trim($_POST['blogname'])));
	$blogdescribe = htmlspecialchars(checkPost(trim($_POST['blogdescribe'])));
	$blogurl = htmlspecialchars(checkPost(trim($_POST['blogurl'])));
	$extraname = checkPost(trim($_POST['extraname']));
	if(substr($blogurl,-1,1) !== "/") {
		$blogurl = $blogurl ."/";
	}
	$indexNum = intval($_POST['indexNum']);
	$newNum = intval($_POST['newNum']);
	$gbNum = intval($_POST['gbNum']);
	$template = checkPost(trim($_POST['template']));
	$checkremark = intval($_POST['checkremark']);
	$makehtml = intval($_POST['makehtml']);
	$fullarticle = intval($_POST['fullarticle']);
	$banextraname = array("php","php3","asp","aspx","cgi","pl","php.bak","php_");
	$keep_page_way = checkPost(trim($_POST['keep_page_way']));
	$archive_folder = checkPost(trim($_POST['archive_folder']));
	$max_gb_char = intval($_POST['max_gb_char']);
	$date_format = checkPost(trim($_POST['date_format']));
	$date_format_remark = checkPost(trim($_POST['date_format_remark']));
	$date_format_gb = checkPost(trim($_POST['date_format_gb']));
	$superadmin = checkPost(trim($_POST['superadmin']));
	$max_image_width = intval($_POST['max_image_width']);
	$post_gb_time =intval($_POST['post_gb_time']);
	$verify_code = intval($_POST['verify_code']);
	$search_time = intval($_POST['search_time']);
	$close_blog = intval($_POST['close_blog']);
	$show_viewcount =intval($_POST['show_viewcount']);
	$close_reason = htmlspecialchars(checkPost(trim($_POST['close_reason'])));
	$lastblog_cut_char = intval($_POST['lastblog_cut_char']);
	$servertimezone = trim($_POST['servertimezone']);
	$clienttimezone = trim($_POST['clienttimezone']);


	if(in_array($extraname,$banextraname)) {
		$FORM->ob_exit("������ʹ�� .{$extraname} ����չ��","");
	}
	$configArray = array("blogname" => $blogname,
		"blogdescribe" => $blogdescribe,
		"blogurl" => $blogurl,
		"index_show_number" => $indexNum,
		"lastblog" => $newNum,
		"gb_show_num" => $gbNum,
		"template" => $template,
		"makehtml" => $makehtml,
		"checkremark" => $checkremark,
		"fullarticle" => $fullarticle,
		"extraname" => $extraname,
		"keep_page_way" => $keep_page_way,
		"archive_folder" => $archive_folder,
		"max_gb_char" => $max_gb_char,
		"date_format" => $date_format,
		"date_format_remark" => $date_format_remark,
		"date_format_gb" => $date_format_gb,
		"max_image_width" => $max_image_width,
		"post_gb_time" => $post_gb_time,
		"verify_code" => $verify_code,
		"search_time" => $search_time,
		"close_blog" => $close_blog,
		"close_reason" => $close_reason,
		"superadmin" => $superadmin,
		"show_viewcount" => $show_viewcount,
		"lastblog_cut_char" => $lastblog_cut_char,
		"servertimezone" => $servertimezone,
		"clienttimezone" => $clienttimezone,

	);
	foreach($configArray as $key=>$val) {
		if(!$DB->query("UPDATE ".$mysql_prefix."config SET `".$key."` ='".$val."'")) {
			$FORM->ob_exit("��Ǹ��ϵͳ�趨����","");
		}
	}
	if($makehtml) {
		$HTML->makeindex();
	}
	//ɾ����̬��ҳ
	if($makehtml == 0 || $close_blog == 1) {
		if(file_exists("index.html")) {
			@chmod("index.html",0777);
			@unlink("index.html");
		}
	}
	$FORM->ob_exit("��ϲ��ϵͳ�趨���<br />����Ҫ�ֶ��ؽ���̬��ҳ","");
}

//PHPINFO
if($action == "phpinfo") {
	$dis_func = get_cfg_var("disable_functions");
	$phpinfo=(!eregi("phpinfo", $dis_func)) ? phpinfo() : $FORM->ob_exit("phpinfo()�����ѱ�����Ա����!����̽��鿴","");
	exit();
}

//�����־����
if($action == "addBlog") {
	$classes = $DB->query("SELECT * FROM `".$mysql_prefix."class`");
	while($classRe = $DB->fetch_array($classes)) {
		$class[$classRe['id']] = $classRe['classname'];
	}
	$nowtime_stamp = time();
	$nowtime = array();
	$nowtime['year'] = obdate("Y",$nowtime_stamp);
	$nowtime['month'] = obdate("m",$nowtime_stamp);
	$nowtime['day'] = obdate("d",$nowtime_stamp);
	$nowtime['hour'] = obdate("H",$nowtime_stamp);
	$nowtime['minute'] = obdate("i",$nowtime_stamp);
	$nowtime['second'] = obdate("s",$nowtime_stamp);
	$nowtime['text'] = "����ʱ��";
	$nowtime['note'] = "�޸���־�ķ���ʱ��(����������ʱ��)";

	$FORM->formheader(array("title" => "�����־","action" => "admin.php?action=doaddBlog","name" => "input"));
	$FORM->makeselect(array(
				"text"  => "ѡ�����",
				"note"  => "",
				"name"  => "class",
				"option" => $class,
            ),0);
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "��־�ı���",
				"name"  => "name",
				"value" => ""
            ),0);
	$FORM->editor(array("text" => "����","note" => "��־�����ݣ�ʹ��UBB��ʽ�ı���"));
	if($makehtml) {
		echo "<tr ".$FORM->getrowbg(2)." nowrap>\n";
		echo "	<td><b>��̬�ļ���</b><br>�������־��ID����</td>\n";
		echo "	<td><input class=\"formfield\" type=\"text\" name=\"filename\" size=\"35\" maxlength=\"50\" value=\"\"> .".$extraname."</td>\n";
		echo "</tr>\n";
	}
	$FORM->makeinput(array(
				"text"  => "Trackback URL",
				"note"  => "Trackback ���õ�ַ��û��������",
				"name"  => "trackbackUrl",
				"value" => "",
				"maxlength" => 200,
            ),0);
	$FORM->maketimeinput($nowtime,2);
	
	echo "<tr ".$FORM->getrowbg(1)." nowrap>\n";
	echo "<td><b>�����ϴ�</b><br>��ѡ����Ҫ�ϴ��ĸ���</td>\n";
	echo "<td>";
	echo "<IFRAME src=\"upload.php?action=upload_form\" frameBorder=0 width=\"100%\" scrolling=no height=23  allowTransparency=\"true\" noresize></IFRAME>";
	echo "</td>\n</tr>\n";

	$FORM->makeyesno(array("text" => "�ö�",
		"name" => "top",
		"selected" => 0),2);
	$FORM->makeyesno(array("text" => "����",
		"name" => "remark",
		"selected" => 1),1);
	$FORM->makeyesno(array("text" => "����",
		"name" => "allow_face",
		"selected" => 1),2);
	$FORM->makeyesno(array("text" => "�ݸ�",
		"name" => "draft",
		"selected" => 0),1);
	
	$FORM->formfooter();
}

//ִ�������־
if($action == "doaddBlog") {
	if(file_exists("admin/class/pinyin.php")) {
		require_once("admin/class/pinyin.php");
	} elseif(file_exists("class/pinyin.php")) {
		require_once("class/pinyin.php");
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/pinyin.php")) {
		require_once(dirname($_SERVER['PHP_SELF'])."/class/pinyin.php");
	} else {
		$FORM->ob_exit("�޷��ҵ��ļ� ./admin/class/pinyin.php");
	}
	
	$classid = $_POST['class'];
	$title = checkPost(trim(htmlspecialchars($_POST['name'])));
	$content = checkPost(htmlspecialchars($_POST['message']));
	$content = str_replace("\t","        ",$content);
	$filename = to_pinyin(checkPost(trim($_POST['filename'])));
	$trackbackUrl = checkPost(trim($_POST['trackbackUrl']));
	$top = intval($_POST['top']);
	$allow_remark = intval($_POST['remark']);
	$allow_face = intval($_POST['allow_face']);
	$draft = intval($_POST['draft']);
	if(empty($title)) {
		$FORM->ob_exit("���⻹û����д��","");
	}
	if(empty($content)) {
		$FORM->ob_exit("���ݻ�û����д��","");
	}
	
	//��֤ʱ��
	$nowtime = array();
	$nowtime['year'] = $_POST['year'];
	$nowtime['month'] = $_POST['month'];
	$nowtime['day'] = $_POST['day'];
	$nowtime['hour'] = $_POST['hour'];
	$nowtime['minute'] = $_POST['minute'];
	$nowtime['second'] = $_POST['second'];
	array_walk($nowtime,"intval");
	array_walk($nowtime,"checkPost");
	$timeok = true;
	if($nowtime['month'] > 12 || $nowtime['month'] < 1) {
		$timeok = false;
	} elseif($nowtime['day'] > 31 || $nowtime['day'] < 1) {
		$timeok = false;
	} elseif($nowtime['hour'] > 24 || $nowtime['hour'] < 0) {
		$timeok = false;
	} elseif($nowtime['minute'] > 59 || $nowtime['minute'] < 0) {
		$timeok = false;
	} elseif($nowtime['second'] > 59 || $nowtime['second'] < 0) {
		$timeok = false;
	} elseif($nowtime['year'] < 0) {
		$timeok = false;
	}
	if(!$timeok) {
		$FORM->ob_exit("ʱ����д����!","");
	}
	$date = mktime($nowtime['hour'],$nowtime['minute'],$nowtime['second'],$nowtime['month'],$nowtime['day'],$nowtime['year']);
	$userid = @intval($_COOKIE['ob_userid']);
	$author = @$DB->fetch_one("SELECT `nickname` FROM {$mysql_prefix}admin WHERE `id`={$userid}");
	$author = ($author == '') ? "unknow" : $author;
	
	//���
	if($DB->query("INSERT INTO `".$mysql_prefix."blog` (date,title,content,trackbackurl,filename,author,classid,top,allow_remark,allow_face,draft) VALUES ('".$date."','".$title."','".$content."','".$trackbackUrl."','".$filename."','".$author."','".$classid."','".$top."','".$allow_remark."','".$allow_face."','".$draft."')")) {
		$insert_id = $DB->insert_id();
		//����HTMLҳ��
		if($makehtml  && $draft!=1) {
			$HTML->makeindex();
			$HTML->make($insert_id);
		}
		//���� trackback ping
		if($trackbackUrl != "") {
			$tb_url = $blogurl.getHtmlPath($insert_id);
			if(!ping($trackbackUrl,$title,$tb_url,$content,$tb_name)) {
				$FORM->ob_exit("��־��ӳɹ�<br>Trackback Ping ����ʧ��","");
			}
		}
		$FORM->ob_exit("��ϲ����־��ӳɹ�","admin.php?action=editBlog");
	} else {
		$FORM->ob_exit("��Ǹ����־���ʧ��","");
	}
}

//�༭��־-�б����
if($action == "editBlog") {
	$classes = $DB->query("SELECT * FROM `".$mysql_prefix."class`");
	while($classRe = $DB->fetch_array($classes)) {
		$class[$classRe['id']] = $classRe['classname'];
	}
	$authorRS = $DB->query("SELECT * FROM `".$mysql_prefix."admin`");
	while($authorRe = $DB->fetch_array($authorRS)) {
		$authors[$authorRe['id']] = $authorRe['nickname'];
	}
	$mun_in_page = 20;
	if(isset($_POST['keyword']) && trim($_POST['keyword'])!='') {
		$keyword = checkPost(trim($_POST['keyword']));
		$searchwhere = checkPost(trim($_POST['searchwhere']));
		$keyword = str_replace("_","\_",$keyword);
		$keyword = str_replace("%","\%",$keyword);
		$sql_list = "SELECT * FROM `".$mysql_prefix."blog` WHERE `{$searchwhere}` LIKE '%{$keyword}%' ORDER BY `date` DESC";
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `{$searchwhere}` LIKE '%{$keyword}%'");
		if($allNum == 0) {
			$FORM->ob_exit("û���ҵ�����������������־");
		}
	} else {
		$current_userinfo = $DB->fetch_one_array("SELECT username,nickname FROM {$mysql_prefix}admin WHERE id='".intval($_COOKIE['ob_userid'])."'");
		$current_username = $current_userinfo['username'];
		$current_nickname = $current_userinfo['nickname'];
		if(strstr($superadmin,$current_username)) {
			//��������Ա
			$is_superadmin = 1;
			$sql_num = "SELECT count(*) FROM `".$mysql_prefix."blog`";
			$allNum = $DB->fetch_one($sql_num);
			$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$page_char = page($allNum,$mun_in_page,$cpage,"admin.php?action=editBlog");
			$startI = $cpage*$mun_in_page-$mun_in_page;
			$sql_list = "SELECT * FROM `".$mysql_prefix."blog` ORDER BY `date` DESC LIMIT ".$startI.",{$mun_in_page}";
		} else {
			//��ͨ����Ա
			$sql_num = "SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `author`='{$current_nickname}'";
			$allNum = $DB->fetch_one($sql_num);
			$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$page_char = page($allNum,$mun_in_page,$cpage,"admin.php?action=editBlog");
			$startI = $cpage*$mun_in_page-$mun_in_page;
			$sql_list = "SELECT * FROM `".$mysql_prefix."blog` WHERE `author`='{$current_nickname}' ORDER BY `date` DESC LIMIT ".$startI.",{$mun_in_page}";
		}
		
		if($allNum == 0) {
			$FORM->ob_exit("Ŀǰ��û����־");
		}
		
	}
	$blogs = $DB->query($sql_list);
	
	if(isset($keyword) && $keyword!= '') {
		$formtitle = "������� [��".$allNum."����¼]";
	} else {
		$formtitle = "ȫ����־ [��".$allNum."����¼] [20��/ҳ]";
	}
	
	$FORM->js_checkall();
	$FORM->if_del();
	$FORM->formheader(array("action" => "admin.php?action=dosomeBlog",
		"title" => $formtitle,
		"colspan" => "7",
		"name" => "form",
	));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>�ö�</b></td>\n";
	echo "<td width=\"9%\"><b>��������</b></td>\n";
	echo "<td width=\"26%\"><b>��־����</b></td>\n";
	echo "<td width=\"14%\"><b>��������</b></td>\n";
	echo "<td width=\"16%\"><b>���ʱ��</b></td>\n";
	echo "<td width=\"22%\"><b>��־����</b></td>\n";
	echo "<td width=\"5%\">\n";
	echo "<input type=\"checkbox\" name=\"chkall\" value=\"on\" class=\"nonebg\" onclick=\"CheckAll(this.form)\"></td>\n";
	echo "</tr>\n";
	while($blog = $DB->fetch_array($blogs)) {
		$top = ($blog['top'] == 1) ? "Yes" : "No";
		$allow_remark = ($blog['allow_remark'] == 1) ? "Yes" : "No";
		$remarkNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark` WHERE `inblog` = ".$blog['id']);
		$trackbackNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."trackback` WHERE `inblog` = ".$blog['id']);
		$classname = $DB->fetch_one("SELECT `classname` FROM `".$mysql_prefix."class` WHERE `id` =".$blog['classid']);
		$draft_char = ($blog['draft']) ? "<font color=\"red\">[�ݸ�]</font> " : "";
		$rtNum = $trackbackNum + $remarkNum;
		$blog_path = $blogurl.getHtmlPath($blog['id']);
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td>".$top."</td>\n";
		echo "<td>".$allow_remark." [".$rtNum."]</td>\n";
		echo "<td align=\"left\"><a href=\"{$blog_path}\" target=\"_blank\">".$draft_char.trim($blog['title'])."</a></td>\n";
		echo "<td>".$classname."</td>\n"; 
		echo "<td>".obdate("y-m-d H:m:s",$blog['date'])."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modBlog&id=".$blog['id']."\">�༭</a>] [<a href=\"#\"  onclick=\"ifDel('admin.php?action=delBlog&id=".$blog['id']."')\">ɾ��</a>] [<a href=\"admin.php?action=buildBlog&id=".$blog['id']."\">����</a>] <br> [<a href=\"admin.php?action=remarkManager&id=".$blog['id']."\">����</a>] [<a href=\"admin.php?action=editTrackback&id=".$blog['id']."\">����</a>]</td>\n";
		echo "<td><input type=\"checkbox\" name=\"blog[".$blog['id']."]\" value=\"1\" class=\"nonebg\"></td>\n";
		echo "</tr>\n";
	}
	$FORM->makepage($page_char,7,0);
	if($is_superadmin) {
		$disable_char = "classid.disabled=true;authors.disabled=true";
		$disable_char2 = "classid.disabled=false;authors.disabled=true";
	} else {
		$disable_char = "classid.disabled=true;";
		$disable_char2 = "classid.disabled=false;";
	}
	echo "<tr class=\"secondalt\">";
	echo "<td colspan=\"7\" align=\"center\">\n";
	echo "<label for=\"top\"><input type=\"radio\" name=\"editall\" id=\"top\" value=\"top\" class=\"graybg\" onclick=\"{$disable_char}\">�ö�</label> ";
	echo "<label for=\"ctop\"><input type=\"radio\" name=\"editall\" id=\"ctop\" value=\"ctop\" class=\"graybg\" onclick=\"{$disable_char}\">ȡ���ö�</label> ";
	echo "<label for=\"del\"><input type=\"radio\" name=\"editall\" id=\"del\" value=\"del\" class=\"graybg\" onclick=\"{$disable_char}\">ɾ��</label> ";
	echo "<label for=\"move\"><input type=\"radio\" name=\"editall\" id=\"move\" value=\"move\" class=\"graybg\" onclick=\"{$disable_char2}\">�ƶ�</label> ";
	echo "<select name=\"classid\" disabled=\"ture\">";
	foreach($class as $key=>$val) {
		echo "<option value=".$key.">".$val."</option>";
	}
	echo "</select>";
	
	if($is_superadmin) {
		echo "<label for=\"author\"><input type=\"radio\" name=\"editall\" id=\"author\" value=\"author\" class=\"graybg\" onclick=\"authors.disabled=false;classid.disabled=true\">ָ������</label> ";
		echo "<select name=\"authors\" disabled=\"ture\">";
		foreach($authors as $key=>$val) {
			echo "<option value=".$val.">".$val."</option>";
		}
		echo "</select>";
	}
	echo "</td></tr>\n";
	$FORM->formfooter(array("colspan" => "7"));

	//������־��
	echo "\n\n<br><form action=\"admin.php?action=editBlog\" method=\"post\"><table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\"><tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" align=\"center\">\n<b>������־:&nbsp;&nbsp;<b>\n";
	echo "\n<select name=\"searchwhere\"><option value=\"title\">����</option><option value=\"content\">����</option></select>\n<input type=\"text\" id=\"keyword\" name=\"keyword\" size=\"30\">\n\n";
	echo "&nbsp;&nbsp;<input type=\"submit\" value=\"����\"></td>\n";
	echo "</table></form>\n";
}

//�޸�һƪ��־����
if($action == "modBlog") {
	$classes = $DB->query("SELECT * FROM `".$mysql_prefix."class`");
	while($classRe = $DB->fetch_array($classes)) {
		$class[$classRe['id']] = $classRe['classname'];
	}
	$blogs = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."blog` WHERE `id` =".trim($_GET['id']));
	$nowtime = array();
	$nowtime['year'] = obdate("Y",$blogs['date']);
	$nowtime['month'] = obdate("m",$blogs['date']);
	$nowtime['day'] = obdate("d",$blogs['date']);
	$nowtime['hour'] = obdate("H",$blogs['date']);
	$nowtime['minute'] = obdate("i",$blogs['date']);
	$nowtime['second'] = obdate("s",$blogs['date']);
	$nowtime['text'] = "ʱ��";
	$nowtime['note'] = "�޸���־�ķ���ʱ��(����������ʱ��)";

	$FORM->formheader(array("title" => "�༭��־","action" => "admin.php?action=domodBlog","name" => "input"));
	$FORM->makeselect(array(
				"text"  => "ѡ�����",
				"note"  => "",
				"name"  => "class",
				"option" => $class,
				"selected" => $blogs['classid']
            ),0);
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "��־�ı���",
				"name"  => "name",
				"value" => $blogs['title']
            ),0);
	$FORM->editor(array("text" => "����","note" => "��־�����ݣ�ʹ��UBB��ʽ�ı���","value" => $blogs['content']));
	if($makehtml) {
		echo "<tr ".$FORM->getrowbg(2)." nowrap>\n";
		echo "	<td><b>��̬�ļ���</b><br>�������־��ID����</td>\n";
		echo "	<td><input class=\"formfield\" type=\"text\" name=\"filename\" size=\"35\" maxlength=\"50\" value=\"".$blogs['filename']."\"> .".$extraname."</td>\n";
		echo "</tr>\n";
	}
	$FORM->makeinput(array(
				"text"  => "Trackback URL",
				"note"  => "Trackback ���õ�ַ��û��������",
				"name"  => "trackbackUrl",
				"value" => $blogs['trackbackurl'],
				"maxlength" => 200,
				"otherelement" => " <label for=\"resend_tb\"><input type=\"checkbox\" name=\"resend_tb\" id=\"resend_tb\" value=\"resend_tb\" class=\"nonebg\" /> �ٴη���?</label>",
            ),0);
	$FORM->makehidden(array("name" => "oldTrackbackUrl","value" => $blogs['trackbackurl']));
	$FORM->maketimeinput($nowtime,2);

	echo "<tr ".$FORM->getrowbg(1)." nowrap>\n";
	echo "<td><b>�����ϴ�</b><br>��ѡ����Ҫ�ϴ��ĸ���</td>\n";
	echo "<td>";
	echo "<IFRAME src=\"upload.php?action=upload_form\" frameBorder=0 width=\"100%\" scrolling=no height=23  allowTransparency=\"true\" noresize></IFRAME>";
	echo "</td>\n</tr>\n";
	
	$FORM->makeyesno(array("text" => "�ö�",
		"name" => "top",
		"selected" => $blogs['top']),2);
	$FORM->makeyesno(array("text" => "����",
		"name" => "remark",
		"selected" => $blogs['allow_remark']),1);
	$FORM->makeyesno(array("text" => "����",
		"name" => "allow_face",
		"selected" => $blogs['allow_face']),2);
	$FORM->makeyesno(array("text" => "�ݸ�",
		"name" => "draft",
		"selected" => $blogs['draft']),1);
	$FORM->makehidden(array("name" => "id","value" => $blogs['id']));
	$FORM->formfooter();
}

//ִ���޸�һƪ��־
if($action == "domodBlog") {
	if(file_exists("admin/class/pinyin.php")) {
		require_once("admin/class/pinyin.php");
	} elseif(file_exists("class/pinyin.php")) {
		require_once("class/pinyin.php");
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/pinyin.php")) {
		require_once(dirname($_SERVER['PHP_SELF'])."/class/pinyin.php");
	} else {
		$FORM->ob_exit("�޷��ҵ��ļ� ./admin/class/pinyin.php");
	}
	
	$classid = $_POST['class'];
	$title = checkPost(trim(htmlspecialchars($_POST['name'])));
	$content = checkPost(trim(htmlspecialchars($_POST['message'])));
	$content = str_replace("\t","        ",$content);
	$filename = to_pinyin(checkPost(trim($_POST['filename'])));
	$trackbackUrl = checkPost(trim($_POST['trackbackUrl']));
	$oldTrackbackUrl = checkPost(trim($_POST['oldTrackbackUrl']));
	$top = intval($_POST['top']);
	$allow_remark = intval($_POST['remark']);
	$allow_face = intval($_POST['allow_face']);
	$id = intval($_POST['id']);
	$draft = intval($_POST['draft']);

	if(empty($title)) {
		$FORM->ob_exit("���⻹û����д��","");
	}
	if(empty($content)) {
		$FORM->ob_exit("���ݻ�û����д��","");
	}

	//��֤ʱ��
	$nowtime = array();
	$nowtime['year'] = $_POST['year'];
	$nowtime['month'] = $_POST['month'];
	$nowtime['day'] = $_POST['day'];
	$nowtime['hour'] = $_POST['hour'];
	$nowtime['minute'] = $_POST['minute'];
	$nowtime['second'] = $_POST['second'];
	array_walk($nowtime,"intval");
	array_walk($nowtime,"checkPost");
	$timeok = true;
	if($nowtime['month'] > 12 || $nowtime['month'] < 1) {
		$timeok = false;
	} elseif($nowtime['day'] > 31 || $nowtime['day'] < 1) {
		$timeok = false;
	} elseif($nowtime['hour'] > 24 || $nowtime['hour'] < 0) {
		$timeok = false;
	} elseif($nowtime['minute'] > 59 || $nowtime['minute'] < 0) {
		$timeok = false;
	} elseif($nowtime['second'] > 59 || $nowtime['second'] < 0) {
		$timeok = false;
	} elseif($nowtime['year'] < 0) {
		$timeok = false;
	}
	if(!$timeok) {
		$FORM->ob_exit("ʱ����д����!","");
	}
	$date = mktime($nowtime['hour'],$nowtime['minute'],$nowtime['second'],$nowtime['month'],$nowtime['day'],$nowtime['year']);
	
	$updateSql = "UPDATE `".$mysql_prefix."blog` SET `classid` = '".$classid."',`title` = '".$title."',`content` = '".$content."',`trackbackurl` = '".$trackbackUrl."',`filename` = '".$filename."',`date` = '".$date."',`top` = '".$top."',`allow_remark` = '".$allow_remark."',`allow_face` = '".$allow_face."',`draft` = '".$draft."' WHERE `id` = ".$id;
	if($DB->query($updateSql)) {
		//����HTMLҳ��
		if($makehtml && $draft!=1) {
			$HTML->makeindex();
			$HTML->make($id);
		}
		//���� trackback ping
		if($trackbackUrl != "" AND trim($_POST['resend_tb']) == 'resend_tb') {
			$tb_url = $blogurl.getHtmlPath($id);
			if(!ping($trackbackUrl,$title,$tb_url,$content,$tb_name)) {
				$FORM->ob_exit("��־��ӳɹ�<br>Trackback Ping ����ʧ��","");
			}
		}
		$FORM->ob_exit("��ϲ���༭��־�ɹ�","admin.php?action=editBlog");
	} else {
		$FORM->ob_exit("��Ǹ���༭��־ʧ��","");
	}
}

//ɾ��һƪ��־
if($action == "delBlog") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."blog` WHERE `id`=".$id)) {
		if($DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `inblog`=".$id)) {
			if($DB->query("DELETE FROM `".$mysql_prefix."trackback` WHERE `inblog`=".$id)) {
				if($makehtml) {
					$HTML->del($id);
					$HTML->makeindex();
				}
				$FORM->ob_exit("ɾ����־�ɹ�","");
			} else {
				$FORM->ob_exit("ɾ�� Trackback Pings ʧ��","");
			}
		} else {
			$FORM->ob_exit("ɾ������ʧ��","");
		}
	} else {
		$FORM->ob_exit("ɾ����־ʧ��","");
	}
}

//����һƪ��־
if($action == "buildBlog") {
	$id = intval($_GET['id']);
	if($HTML->make($id)) {
		$FORM->ob_exit("������־�ɹ�");
	}
}

//���۹������
if($action == "remarkManager") {
	if(isset($_GET['id'])) {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark` WHERE `inblog` = ".intval($_GET['id']));
		if($allNum == 0) {
			$FORM->ob_exit("��ǰ��־û������","");
		}
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=remarkManager&id=".intval($_GET['id']));
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."remark` WHERE `inblog` = ".intval($_GET['id'])." ORDER BY `id` DESC LIMIT ".$startI.",20";
		$formtitle = "���۹��� [����{$allNum}ƪ����] [20��/ҳ]";
	} elseif(isset($_POST['keyword']) && trim($_POST['keyword']) != '') {
		$keyword = checkPost(trim($_POST['keyword']));
		$searchwhere = checkPost(trim($_POST['searchwhere']));
		$keyword = str_replace("_","\_",$keyword);
		$keyword = str_replace("%","\%",$keyword);
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark` WHERE `{$searchwhere}` LIKE  '%{$keyword}%'");
		$listSql = "SELECT * FROM `".$mysql_prefix."remark` WHERE `{$searchwhere}` LIKE '%{$keyword}%' ORDER BY `id` DESC";
		$formtitle = "������� [����{$allNum}ƪ����]";
		if($allNum == 0) {
			$FORM->ob_exit("û���ҵ�������������������","");
		}
	} else {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark`");
		if($allNum == 0) {
			$FORM->ob_exit("Ŀǰ��û������","");
		}
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=remarkManager");
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."remark` ORDER BY `id` DESC LIMIT ".$startI.",20";
		$formtitle = "���۹��� [����{$allNum}ƪ����] [20��/ҳ]";
	}
	$FORM->js_checkall();
	$FORM->if_del();
	$FORM->formheader(array("title" => $formtitle,"colspan" => "5","action" => "admin.php?action=dosomeRemark"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>���</b></td>\n";
	echo "<td width=\"30%\"><b>������</b></td>\n";
	echo "<td width=\"40%\"><b>����</b></td>\n";
	echo "<td width=\"20%\"><b>����</b></td>\n";
	echo "<td width=\"5%\"><input type=\"checkbox\" name=\"chkall\" value=\"on\" class=\"nonebg\" onclick=\"CheckAll(this.form)\"></td>\n";
	echo "</tr>\n";
	$remarks = $DB->query($listSql);
	while($remarkRe = $DB->fetch_array($remarks)) {
		$remarkRe['check'] = ($remarkRe['ischeck'] == 1) ? "��" : "��";
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$remarkRe['check']."</td>\n";
		echo "<td align=\"left\" valign=\"top\"><b>�ǳ�: </b>".$remarkRe['username']."<br><b>E-mail: </b>".$remarkRe['email']."<br><b>IP: </b>".$remarkRe['ip']."</td>\n";
		echo "<td align=\"left\" valign=\"top\">".$remarkRe['content']."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modRemark&id=".$remarkRe['id']."\">�༭</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delRemark&id=".$remarkRe['id']."')\">ɾ��</a>]<br> [<a href=\"admin.php?action=checkRemark&id=".$remarkRe['id']."\">���</a>] [<a href=\"admin.php?action=banRemark&id=".$remarkRe['id']."\">����</a>]</td>\n";
		echo "<td><input type=\"checkbox\" name=\"remark[".$remarkRe['id']."]\" value=\"1\" class=\"nonebg\"></td>\n";
		echo "</tr>\n";
	}

	echo "<tr class=\"secondalt\">";
	echo "<td colspan=\"7\" align=\"center\">\n";
	echo "<label for=\"check\"><input type=\"radio\" name=\"editall\" id=\"check\" value=\"check\" class=\"nonebg\">���</label> ";
	echo "<label for=\"uncheck\"><input type=\"radio\" name=\"editall\" id=\"uncheck\" value=\"uncheck\" class=\"nonebg\">����</label> ";
	echo "<label for=\"del\"><input type=\"radio\" name=\"editall\" id=\"del\" value=\"del\" class=\"nonebg\">ɾ��</label> ";
	echo "</td></tr>\n";

	$FORM->makepage($page_char,5,2);
	$FORM->formfooter(array("colspan" => 5));

	//�������۱�
	echo "\n\n<br><form action=\"admin.php?action=remarkManager\" method=\"post\"><table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\"><tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" align=\"center\">\n<b>��������:&nbsp;&nbsp;<b>\n";
	echo "\n<select name=\"searchwhere\"><option value=\"content\">����</option><option value=\"username\">�ǳ�</option></select>\n<input type=\"text\" id=\"keyword\" name=\"keyword\" size=\"30\">\n\n";
	echo "&nbsp;&nbsp;<input type=\"submit\" value=\"����\"></td>\n";
	echo "</table></form>\n";
}

//������������
if($action == "dosomeRemark") {
	$dowhat = checkPost(trim($_POST['editall']));
	if($dowhat == "") {
		$FORM->ob_exit("��ѡ��һ��Ҫִ�еĲ���","");
	}
	$remarks = checkPost($_POST['remark']);
	if(count($remarks) == 0) {
		$FORM->ob_exit("��ѡ��Ҫִ�в���������","");
	}
	//���
	if($dowhat == "check") {
		foreach($remarks as $key=>$val) {
			if($DB->query("UPDATE `".$mysql_prefix."remark` SET `ischeck` = 1 WHERE `id` = ".$key)) {
				$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id` = ".$key);
				if($makehtml) {
					$HTML->make($blogid);
				}
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("����������","");
	}
	//����
	if($dowhat == "uncheck") {
		foreach($remarks as $key=>$val) {
			if($DB->query("UPDATE `".$mysql_prefix."remark` SET `ischeck` = 0 WHERE `id` = ".$key)) {
				$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id` = ".$key);
				if($makehtml) {
					$HTML->make($blogid);
				}
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("�����������","");
	}
	//ɾ��
	if($dowhat == "del") {	
		foreach($remarks as $key=>$val) {
			$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id` = ".$key);
			if($DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `id` = ".$key)) {
				if($makehtml) {
					$HTML->make($blogid);
				}
			} else {
				$FORM->ob_exit("ɾ������ʧ��","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("ɾ���������","");
	}
}

//ɾ��һ������
if($action == "delRemark") {
	$id = intval($_GET['id']);
	$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id`=".$id);
	if($DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `id`=".$id)) {
		if($makehtml) {
			$HTML->makeindex();
			$HTML->make($blogid);
		}
		$FORM->ob_exit("ɾ�����۳ɹ�","");
	} else {
		$FORM->ob_exit("ɾ������ʧ��","");
	}
}

//ͨ�����һ������
if($action == "checkRemark") {
	$id = intval($_GET['id']);
	$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id`=".$id);
	if($DB->query("UPDATE `".$mysql_prefix."remark` SET `ischeck` =  1 WHERE `id`=".$id)) {
		if($makehtml) {
			$HTML->makeindex();
			$HTML->make($blogid);
		}
		$FORM->ob_exit("���������","");
	} else {
		$FORM->ob_exit("�������ʧ��","");
	}
}

//����һ������
if($action == "banRemark") {
	$id = intval($_GET['id']);
	$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id`=".$id);
	if($DB->query("UPDATE `".$mysql_prefix."remark` SET `ischeck` =  0 WHERE `id`=".$id)) {
		if($makehtml) {
			$HTML->makeindex();
			$HTML->make($blogid);
		}
		$FORM->ob_exit("�����ѷ���","");
	} else {
		$FORM->ob_exit("���۷���ʧ��","");
	}
}

//�༭���۽���
if($action == "modRemark") {
	$remark = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."remark` WHERE `id` =".intval($_GET['id']));
	$FORM->formheader(array("title" => "�༭����","action" => "admin.php?action=domodRemark","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "�ǳ�",
				"note"  => "",
				"name"  => "name",
				"value"  => $remark['username'],
            ),0);
	$FORM->makeinput(array(
				"text"  => "E-mail",
				"note"  => "",
				"name"  => "email",
				"value"  => trim($remark['email']),
            ),0);
	$FORM->maketextarea(array("text" => "����","name" => "content","value" => strip_tags($remark['content'])));
	$FORM->makehidden(array("name" => "id", "value" => $remark['id']));
	$FORM->formfooter();
}

//ִ�б༭����
if($action == "domodRemark") {
	$name = checkPost(trim($_POST['name']));
	$email = checkPost(trim($_POST['email']));
	$content = nl2br(htmlspecialchars(trim($_POST['content'])));
	$id = intval($_POST['id']);
	if(empty($name)) {
		$FORM->ob_exit("�ǳƻ�û����д��","");
	}
	if(empty($content)) {
		$FORM->ob_exit("���ݻ�û����д��","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."remark` SET `username` = '".$name."', `email` = '".$email."', `content` = '".$content."' WHERE `id` = ".$id)) {
		$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id`=".$id);
		if($makehtml) {
			$HTML->make($blogid);
		}
		$FORM->ob_exit("�༭���۳ɹ�","");
	} else {
		$FORM->ob_exit("�༭����ʧ��","");
	}
}

//����������־
if($action == "dosomeBlog") {
	$dowhat = checkPost(trim($_POST['editall']));
	if($dowhat == "") {
		$FORM->ob_exit("��ѡ��һ��Ҫִ�еĲ���","");
	}
	$blogs = checkPost($_POST['blog']);
	if(count($blogs) == 0) {
		$FORM->ob_exit("��ѡ��Ҫִ�в�������־","");
	}
	//�ö�
	if($dowhat == "top") {
		foreach($blogs as $key=>$val) {
			if(!$DB->query("UPDATE `".$mysql_prefix."blog` SET `top` = 1 WHERE `id` = ".$key)) {
				$FORM->ob_exit("�ö�������������","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("��־�ö��ɹ�","");
	}
	//ȡ���ö�
	if($dowhat == "ctop") {	
		foreach($blogs as $key=>$val) {
			if(!$DB->query("UPDATE `".$mysql_prefix."blog` SET `top` = 0 WHERE `id` = ".$key)) {
				$FORM->ob_exit("ȡ���ö�������������","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("��־ȡ���ö��ɹ�","");
	}
	//ɾ��
	if($dowhat == "del") {	
		foreach($blogs as $key=>$val) {
			if($makehtml) {
				$HTML->del($key);
			}
			if(!$DB->query("DELETE FROM `".$mysql_prefix."blog` WHERE `id` = ".$key)) {
				$FORM->ob_exit("ɾ����־��������","");
			}
			if(!$DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `inblog`=".$key)) {
				$FORM->ob_exit("ɾ�����۷�������","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("��־ɾ���ɹ�","");
	}
	//�ƶ�
	if($dowhat == "move") {
		foreach($blogs as $key=>$val) {
			if(!$DB->query("UPDATE `".$mysql_prefix."blog` SET `classid` = '".intval($_POST['classid'])."' WHERE `id` = ".$key)) {
				$FORM->ob_exit("�ƶ���־��������","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("��־�ƶ��ɹ�","");
	}
	//ָ������
	if($dowhat == "author") {
		foreach($blogs as $key=>$val) {
			if(!$DB->query("UPDATE `".$mysql_prefix."blog` SET `author` = '".checkPost(trim($_POST['authors']))."' WHERE `id` = ".$key)) {
				$FORM->ob_exit("ָ�����߷�������","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("ָ�����߳ɹ�","");
	}
}

//��ӷ������
if($action == "addSort") {
	$default_order = $DB->fetch_one("SELECT max(showorder) FROM {$mysql_prefix}class") + 1;
	$FORM->formheader(array("title" => "��ӷ���","action" => "admin.php?action=doaddSort","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "��������ţ�����ԽС����Խ��ǰ",
				"name"  => "order",
				"value"  => $default_order,
            ),0);
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "��������",
				"name"  => "name",
				"value"  => "",
            ),0);
	$FORM->formfooter();
}

//ִ����ӷ���
if($action == "doaddSort") {
	if(!is_numeric($_POST['order'])) {
		$FORM->ob_exit("��Ǹ���������������ƺ���������");
	}
	$order = intval($_POST['order']);
	$name = htmlspecialchars(checkPost(trim($_POST['name'])));
	if(empty($name)) {
		$FORM->ob_exit("���ƻ�û����д��","");
	}
	if($DB->query("INSERT INTO `".$mysql_prefix."class` (`classname`,`showorder`) VALUES ('".$name."', '".$order."')")) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("��ϲ��������ӳɹ�","");
	} else {
		$FORM->ob_exit("��Ǹ���������ʧ��","");
	}
}

//��ʾ�༭�����б�
if($action == "editSort") {
	$sortNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."class`");
	$FORM->if_del();
	$FORM->formheader(array("title" => "�����б� [����".$sortNum."������]","colspan" => "4","action" => "admin.php?action=dosomeSort"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"10%\"><b>����</b></td>\n";
	echo "<td width=\"60%\"><b>��������</b></td>\n";
	echo "<td width=\"10%\"><b>��������</b></td>\n";
	echo "<td width=\"20%\"><b>����</b></td>\n";
	echo "</tr>\n";
	$sorts = $DB->query("SELECT * FROM `".$mysql_prefix."class` ORDER BY `id` ASC");
	while($sort = $DB->fetch_array($sorts)) {
		$blogNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `classid`=".$sort['id']);
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td><input type=\"text\" name=\"sort[".$sort['id']."]\" value=\"".$sort['showorder']."\" size=\"2\"  class=\"formfield\"></td>";
		echo "<td>".$sort['classname']."</td>";
		echo "<td>".$blogNum."</td>";
		echo "<td>[<a href=\"admin.php?action=modSort&id=".$sort['id']."\">�༭</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delSort&id=".$sort['id']."')\">ɾ��</a>]</td>";
		echo "</tr>";
	}
	$FORM->formfooter(array("colspan"=>'4',"button" =>array("submit"=>array("value"=>"��������"))));
}

//ɾ������
if($action == "delSort") {
	$id = intval($_GET['id']);
	//ɾ�����������µ����ۺ� Trackback Pings
	$delblogids = $DB->fetch_one_array("SELECT `id` FROM `".$mysql_prefix."blog` WHERE `classid`=".$id);
	if(!$delblogids == NULL) {
		foreach($delblogids as $key=>$val) {
			@$DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `inblog`=".$val);
			@$DB->query("DELETE FROM `".$mysql_prefix."trackback` WHERE `inblog`=".$val);
		}
	}
	if($DB->query("DELETE FROM `".$mysql_prefix."class` WHERE `id`=".$id)) {
		if($DB->query("DELETE FROM `".$mysql_prefix."blog` WHERE `classid` = ".$id)) {
			if($makehtml) {
				$HTML->makeindex();
			}
			$FORM->ob_exit("ɾ������ɹ�","");
		} else {
			$FORM->ob_exit("�޷�ɾ�������µ�����","");
		}
	} else {
		$FORM->ob_exit("ɾ������ʧ��","");
	}
}

//�޸ķ���
if($action == "modSort") {
	$id = intval($_GET['id']);
	$sort = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."class` WHERE `id` = ".$id);
	$FORM->formheader(array("title" => "�༭����","action" => "admin.php?action=domodSort","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "��������ţ�����ԽС����Խ��ǰ",
				"name"  => "order",
				"value"  => $sort['showorder'],
            ),0);
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "��������",
				"name"  => "name",
				"value"  => $sort['classname'],
            ),0);
	$FORM->makehidden(array("name" => "id","value" => $id));
	$FORM->formfooter();
}

//ִ���޸ķ���
if($action == "domodSort") {
	if(!is_numeric($_POST['order'])) {
		$FORM->ob_exit("��Ǹ���������������ƺ���������");
	}
	$id = intval($_POST['id']);
	$order = intval($_POST['order']);
	$name = htmlspecialchars(checkPost(trim($_POST['name'])));
	if(empty($name)) {
		$FORM->ob_exit("���ƻ�û����д��");
	}
	if($DB->query("UPDATE `".$mysql_prefix."class` SET `showorder` = '".$order."', `classname` = '".$name."' WHERE `id` = ".$id)) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("�༭����ɹ�","admin.php?action=editSort");
	} else {
		$FORM->ob_exit("�༭����ʧ��","");
	}
}

//�������
if($action == "dosomeSort") {
	$sort = checkPost($_POST['sort']);
	foreach($sort as $key=>$val) {
		$DB->query("UPDATE `".$mysql_prefix."class` SET `showorder` = '".$val."' WHERE `id` = $key");
	}
	if($makehtml) {
			$HTML->makeindex();
	}
	$FORM->ob_exit("���������Ѿ�����","");
}

//������ӽ���
if($action == "addLink") {
	$default_order = $DB->fetch_one("SELECT max(showorder) FROM {$mysql_prefix}link") + 1;
	$FORM->formheader(array("title" => "�������","action" => "admin.php?action=doaddLink","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "��վ����",
				"note"  => "",
				"name"  => "name",
				"value" => ""
            ),0);
	$FORM->makeinput(array(
				"text"  => "��վ��ַ",
				"note"  => "",
				"name"  => "url",
				"value" => ""
            ),0);
	$FORM->maketextarea(array("text" => "��վ����","name" => "alt","value" => ""));
	$FORM->makeinput(array(
				"text"  => "��������",
				"note"  => "",
				"name"  => "order",
				"value" => $default_order
            ),0);
	$FORM->makeyesno(array("text" => "����",
		"note" => "",
		"name" => "linkhidden",
		"selected" => 0));
	$FORM->formfooter();
}

//ִ���������
if($action == "doaddLink") {
	$name = htmlspecialchars(checkPost(trim($_POST['name'])));
	$url = htmlspecialchars(checkPost(trim($_POST['url'])));
	$alt = htmlspecialchars(checkPost(trim($_POST['alt'])));
	$order = intval($_POST['order']);
	$linkhidden = intval($linkhidden);
	if(empty($name)) {
		$FORM->ob_exit("��վ���ƻ�û����д��","");
	}
	if(empty($url)) {
		$FORM->ob_exit("��վ��ַ��û����д��","");
	}
	if($DB->query("INSERT INTO `".$mysql_prefix."link` (`sitename`,`linkurl`,`alt`,`showorder`,`linkhidden`) VALUES ('".$name."','".$url."','".$alt."','".$order."','".$linkhidden."')")) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("������ӳɹ�","");
	} else {
		$FORM->ob_exit("�������ʧ��","");
	}
}

//�༭�����б�
if($action == "editLink") {
	$linkNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."link`");
	$FORM->if_del();
	$FORM->formheader(array("title" => "�༭���� [����".$linkNum."������]","colspan" => "5","action" => "admin.php?action=dosomeLink"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"10%\"><b>����</b></td>\n";
	echo "<td width=\"5%\"><b>����</b></td>\n";
	echo "<td width=\"30%\"><b>��վ����</b></td>\n";
	echo "<td width=\"40%\"><b>��վ��ַ</b></td>\n";
	echo "<td width=\"15%\"><b>����</b></td>\n";
	echo "</tr>\n";
	$links = $DB->query("SELECT * FROM `".$mysql_prefix."link` ORDER BY `showorder` ASC");
	while($link = $DB->fetch_array($links)) {
		$linkhidden_char = ($link['linkhidden']) ? "��" : "��";
		echo "<tr ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\"><input type=\"text\" name=\"link[".$link['id']."]\" value=\"".$link['showorder']."\" size=\"2\"  class=\"formfield\"></td>";
		echo "<td align=\"center\">".$linkhidden_char."</td>\n";
		echo "<td align=\"center\">".$link['sitename']."</td>\n";
		echo "<td>".$link['linkurl']."</td>\n";
		echo "<td align=\"center\">[<a href=\"admin.php?action=modLink&id=".$link['id']."\">�༭</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delLink&id=".$link['id']."')\">ɾ��</a>]</td>\n";
		echo "</tr>\n";
	}
	$FORM->formfooter(array("colspan"=>'5',"button" =>array("submit"=>array("value"=>"��������"))));
}

//ɾ������
if($action == "delLink") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."link` WHERE `id`=".$id)) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("����ɾ���ɹ�","");
	} else {
		$FORM->ob_exit("����ɾ��ʧ��","");
	}
}

//�༭һ�����ӽ���
if($action == "modLink") {
	$id = intval($_GET['id']);
	$link = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."link` WHERE `id` = ".$id);
	$FORM->formheader(array("title" => "�༭����","action" => "admin.php?action=domodLink","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "��վ����",
				"note"  => "",
				"name"  => "name",
				"value" => $link['sitename']
            ),0);
	$FORM->makeinput(array(
				"text"  => "��վ��ַ",
				"note"  => "",
				"name"  => "url",
				"value" => $link['linkurl']
            ),0);
	$FORM->maketextarea(array("text" => "��վ����","name" => "alt","value" => $link['alt']));
	$FORM->makeinput(array(
				"text"  => "��������",
				"note"  => "",
				"name"  => "order",
				"value" => $link['showorder']
            ),0);
	$FORM->makeyesno(array("text" => "����",
		"note" => "",
		"name" => "linkhidden",
		"selected" => $link['linkhidden']));
	$FORM->makehidden(array("name" => "id", "value" => $id));
	$FORM->formfooter();
}

//ִ�б༭����
if($action == "domodLink") {
	$id = intval($_POST['id']);
	$name = htmlspecialchars(checkPost(trim($_POST['name'])));
	$url = htmlspecialchars(checkPost(trim($_POST['url'])));
	$alt = htmlspecialchars(checkPost(trim($_POST['alt'])));
	$order = intval($_POST['order']);
	$linkhidden = intval($_POST['linkhidden']);
	if(empty($name)) {
		$FORM->ob_exit("��վ���ƻ�û����д��","");
	}
	if(empty($url)) {
		$FORM->ob_exit("��վ��ַ��û����д��","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."link` SET `sitename` = '".$name."',`linkurl` = '".$url."',`alt` = '".$alt."',`showorder` = '".$order."',`linkhidden` = '".$linkhidden."' WHERE `id` = ".$id)) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("���ӱ༭�ɹ�","admin.php?action=editLink");
	} else {
		$FORM->ob_exit("���ӱ༭ʧ��","");
	}
}

//������������
if($action == "dosomeLink") {
	$link = checkPost($_POST['link']);
	foreach($link as $key=>$val) {
		$DB->query("UPDATE `".$mysql_prefix."link` SET `showorder` = '".$val."' WHERE `id` = '".$key."'");
	}
	if($makehtml) {
		$HTML->makeindex();
	}
	$FORM->ob_exit("��������������","");
}

//�������ݿ����
if($action == "bak") {
	$FORM->formheader(array("title"=> "�������ݿ�,��ѡ��Ҫ���ݵı�","action" => "admin.php?action=dobak"));
	$tables = mysql_list_tables($mysql_dbname);
	while ($table = $DB->fetch_row($tables)) {
		$cachetables[$table[0]]   = $table[0];
		$tableselected[$table[0]] = 1;
    }
	$DB->free_result($tables);
    $FORM->makeselect(array(
		"text" => "��ѡ���:",
        "name" => "table[]",
        "option" => $cachetables,
        "selected" => $tableselected,
        "multiple" => 1,
        "size" => 12
    ));
	echo "<tr class=\"secondalt\" nowrap>";
	echo "<td><b>���ݷ�ʽ:</b><br>ѡ������Ҫ�ı��ݷ�ʽ</td>";
	echo "<td><label for=\"server\"><input type=\"radio\" name=\"saveto\" id=\"server\" value=\"server\" onclick=\"this.form.path.disabled=false\"  class=\"nonebg\" checked> ���ݵ�������</label>";
	echo "<label for=\"local\"><input type=\"radio\" name=\"saveto\" id=\"local\" value=\"local\" onclick=\"this.form.path.disabled=true\"  class=\"nonebg\"> ���ݵ�����</label></td>";
	echo "</tr>";
	$FORM->makeinput(array(
		"text" => "�������ݵ�:",
		"note" => "��ȷ�������ļ��е�������777",
        "name" => "path",
		"size" => 70,
        "value" => "../bak/o-blog".obdate("Ymd",time())."_".M_random(8).".sql"
	),1);	

	$FORM->formfooter();
}

//�������ݿ�
if($action == "dobak") {
	if(trim($_POST['saveto']) == 'server') {
		//���ݵ�������
		chdir("admin");
		$path = trim($_POST['path']);
		if (file_exists($path)) {
			$FORM->ob_exit("��Ǹ,�ļ��Ѿ�����,��ѡ�������ļ���.", "");
		}
		if (!is_array($_POST['table']) OR empty($_POST['table'])) {
			$FORM->ob_exit("��δѡ���κ�Ҫ${text}�ı�", "");
		}
		$table = array_flip($_POST['table']);
		$extension=strtolower(substr(strrchr($path,"."),1));
		if ($extension == 'sql') {
			$filehandle = fopen($path,"w");
			flock($filehandle, LOCK_EX);
			$sqlinfo = "# O-BLOG Data Dump\n"
				."# \n"
				."# O-BLOG Webiste: http://www.phpBlog.cn\n"
				."# Please visit our website for newest infomation about O-BLOG\n"
				."# -------------------------------------------------------------------\n\n";
			fwrite($filehandle,$sqlinfo);
			$result = $DB->query("SHOW tables");
			while ($currow = $DB->fetch_array($result)) {
				if (isset($table[$currow[0]])) {
					sqldumptable($currow[0], $filehandle);
					fwrite($filehandle,"\n\n\n");
				}
			}
			flock($filehandle, LOCK_UN);
			fclose($filehandle);
			$FORM->ob_exit("���ݿ��Ѿ����ݵ�: $path<br>", "");
		} else {
			$FORM->ob_exit("���������ļ�����չ������Ϊ.sql", "");
		}
	} else {
		//���ݵ�����
		$table = array_flip($_POST['table']);
		$result = $DB->query("SHOW tables");
		$data = "";
		while ($currow = $DB->fetch_array($result)) {
			ob_start();
			if (isset($table[$currow[0]])) {
				sqldumptable($currow[0]);
			}
			$data .= ob_get_contents();
			ob_end_clean();
		}

		$sqlinfo = "# O-BLOG Data Dump\n"
				."# \n"
				."# O-BLOG Webiste: http://www.phpBlog.cn\n"
				."# Please visit our website for newest infomation about O-BLOG\n"
				."# -------------------------------------------------------------------\n\n";

		header('Content-Encoding: none');
		header('Content-Type: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
		header('Content-Disposition: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename="o-blog'.obdate("Ymd",time).'_'.M_random(8).'.sql"');
		header('Content-Length: '.strlen($sqlinfo.$data));
		header('Pragma: no-cache');
		header('Expires: 0');

		
		echo $sqlinfo;
		echo $data;
	}
}


//�ָ����ݿ� - ����
if($action == "bakManager") {
	chdir("admin");
	$dir = "bak";
	$FORM->js_checkall();
	$FORM->if_import();
    $FORM->formheader(array(
		"title"   => "�ָ����ݿ�",
		"action"  => "admin.php?action=delFile",
		"colspan" => "4"
	));
	echo "<tr><td class=\"tblhead\" colspan=\"4\"><b>ע�⣺</b>���벻��ȷ�����ݿ��ļ�ʱ���п��ܻٻ�ԭ�������ݡ���˽������ڻָ�����ʱ���ȱ���һ�����е����ݡ�</td></tr>";
	echo "<tr bgcolor=\"#F3F3F3\">\n";
	echo "<td align=\"center\"><b>�ļ���</b></td>\n";
	echo "<td align=\"center\"><b>�ļ���С</b></td>\n";
	echo "<td align=\"center\"><b>����</b></td>\n";
	echo "<td align=\"center\"><input name=\"chkall\" value=\"on\" type=\"checkbox\" onclick=\"CheckAll(this.form)\" class=\"nonebg\"></td>\n";
	echo "</tr>\n";
	$handle = opendir('../'.$dir);
	$url = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$url = str_replace("/admin/admin.php","",$url);
    while (false !== ($file = readdir($handle))) {
		if(strtolower(getextension($file)) == 'sql') {
			$filesize = get_real_size(@filesize('../'.$dir.'/'.$file));
			echo "<tr ".$FORM->getrowbg().">";
			echo "<td align=\"center\"><a href=\"http://".$url."/".$dir."/".$file."\">{$file}</a></td>";
			echo "<td align=\"center\">{$filesize}</td>";
			echo "<td align=\"center\">[<a href=\"#\" onclick=\"ifImport('admin.php?action=import&amp;filename={$file}')\" >����</a>]</td>";
			echo "<td align=\"center\">";
			echo "<input type=\"checkbox\" name=\"file[]\" value=\"../".$dir."/".$file."\" class=\"nonebg\">";
			echo "</td>\n";
			echo "</tr>";
		}
    }
	closedir($handle);
    $FORM->formfooter(array("colspan" => "4","button" =>array("submit"=>array("value"=>"ɾ��"))));
}

//���ݿ�ָ� - ִ�е������
if($action == "import") {
	@set_time_limit(600);
	$file_name = trim($_GET['filename']);
	$file_path = "bak/".$file_name;
	if(!file_exists($file_path)) {
		$FORM->ob_exit("�����ļ�������","");
	}
	$sqldump_data = file_get_contents($file_path);
	$sqldump = splitsql($sqldump_data);
	foreach($sqldump as $sql) {
		if(trim($sql) != '') {
			$DB->query($sql, 'SILENT');
			if(($sqlerror = $DB->error()) && $DB->geterrno() != 1062) {
				$DB->halt('MySQL Query Error', $sql);
			}
		}
	}
	$FORM->ob_exit("�����ļ��ָ��ɹ�!","");
}

//�Ż�/�޸����ݿ����
if ($action == "optimize" || $action == "repair") {
    if ($action == "optimize") {
        $FORM->formheader(array('title' => '�Ż����ݿ�,��ѡ��Ҫ�Ż��ı�','action' => 'admin.php?action=dooptimize'));
    } else {
        $FORM->formheader(array('title' => '�޸����ݿ�,��ѡ��Ҫ�޸��ı�','action' => 'admin.php?action=dorepair'));
    }

    $tables = mysql_list_tables($mysql_dbname);
    if (!$tables) {
        print "DB Error, could not list tables\n";
        print 'MySQL Error: ' . mysql_error();
        $FORM->ob_exit("���ݿ����", "");
    }
    while ($table = $DB->fetch_row($tables)) {
        $cachetables[$table[0]] = $table[0];
	    $tableselected[$table[0]] = 1;
    }
    $DB->free_result($tables);
    $FORM->makeselect(array(
		"text"     => "��ѡ���:",
        "name"     => "table[]",
        "option"   => $cachetables,
        "selected" => $tableselected,
        "multiple" => 1,
        "size"     => 12
    ));
    $FORM->formfooter();
}

//�Ż�/�޸����ݿ�
if($action == "dooptimize" || $action == "dorepair") {
	if ($action == "dooptimize") {
        $a    = "OPTIMIZE";
        $text = "�Ż�";
    } else {
        $a    = "REPAIR";
        $text = "�޸�";
    }
    if (!is_array($_POST['table']) OR empty($_POST['table'])) {
        $FORM->ob_exit("��δѡ���κ�Ҫ${text}�ı�", "");
    }
    $table = array_flip($_POST['table']);
	$FORM->tableheaderbig(array("title" => "�Ż����ݿ�","colspan" => "1"));
	echo "<tr align=\"left\" bgcolor=\"#FFFFFF\"><td width=\"100%\">\n";
    foreach ($table AS $name => $value) {
		if (isset($value)) {
			echo "����{$text}��: $name";
			$result = $DB->query("$a TABLE $name");
			if ($result) {
				echo " ................. ���<br>";
			} else {
				echo " <font color=\"red\"><b>ʧ��</b></font>";
			}
			echo "";
		}
	}
    echo "<p>���б�{$text}���.</p>";
	echo "</td></tr>\n";
	$FORM->tablefooter();
}

//������¼�б�
if($action == "actlog") {
	$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."adminlog`");
	$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$page_char = page($allNum,30,$cpage,"admin.php?action=actlog");
	$startI = $cpage*30-30;
	$FORM->formheader(array("title" => "��̨������¼ [����".$allNum."����¼] [30��/ҳ]","colspan" => "5","action" => "admin.php?action=cleanActlog"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>ID</b></td>\n";
	echo "<td width=\"15%\"><b>IP</b></td>\n";
	echo "<td width=\"48%\"><b>ҳ��</b></td>\n";
	echo "<td width=\"17%\"><b>ʱ��</b></td>\n";
	echo "<td width=\"15%\"><b>����</b></td>\n";
	echo "</tr>\n";
	$logs = $DB->query("SELECT * FROM `".$mysql_prefix."adminlog` ORDER BY `id` DESC LIMIT ".$startI.",30");
	while($log = $DB->fetch_array($logs)) {
		echo "<tr ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$log['id']."</td>";
		echo "<td align=\"center\">".$log['ip']."</td>\n";
		echo "<td>".$log['script']."</td>\n";
		echo "<td align=\"center\">".obdate("y-m-d H:m:s",$log['date'])."</td>\n";
		echo "<td align=\"center\">".$log['action']."</td>\n";
		echo "</tr>\n";
	}
	$FORM->makepage($page_char,5,0);
	$FORM->formfooter(array("colspan"=>'5',"button" =>array("submit"=>array("value"=>"��ռ�¼"))));
}

//��ղ�����¼
if($action == "cleanActlog") {
	if($DB->query("TRUNCATE TABLE `".$mysql_prefix."adminlog`")) {
		$FORM->ob_exit("������¼�Ѿ����","");
	}
}

//��½��¼�б�
if($action == "userlog") {
	$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."loginlog`");
	$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$page_char = page($allNum,30,$cpage,"admin.php?action=userlog");
	$startI = $cpage*30-30;
	$FORM->formheader(array("title" => "��̨��½��¼ [����".$allNum."����¼] [30��/ҳ]","colspan" => "5","action" => "admin.php?action=cleanUserlog"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>ID</b></td>\n";
	echo "<td width=\"15%\"><b>IP</b></td>\n";
	echo "<td width=\"35%\"><b>�û���</b></td>\n";
	echo "<td width=\"30%\"><b>ʱ��</b></td>\n";
	echo "<td width=\"15%\"><b>���</b></td>\n";
	echo "</tr>\n";
	$logs = $DB->query("SELECT * FROM `".$mysql_prefix."loginlog` ORDER BY `id` DESC LIMIT ".$startI.",30");
	while($log = $DB->fetch_array($logs)) {
		$log['result'] = ($log['result'] == 1) ? "�ɹ�" : "<font color=\"red\">ʧ��</font>";
		echo "<tr ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$log['id']."</td>";
		echo "<td align=\"center\">".$log['ip']."</td>\n";
		echo "<td align=\"center\">".$log['username']."</td>\n";
		echo "<td align=\"center\">".obdate("y-m-d H:m:s",$log['date'])."</td>\n";
		echo "<td align=\"center\">".$log['result']."</td>\n";
		echo "</tr>\n";
	}
	$FORM->makepage($page_char,5,0);
	$FORM->formfooter(array("colspan"=>'5',"button" =>array("submit"=>array("value"=>"��ռ�¼"))));
}

//��յ�½��¼
if($action == "cleanUserlog") {
	if($DB->query("TRUNCATE TABLE `".$mysql_prefix."loginlog`")) {
		$FORM->ob_exit("��½��¼�Ѿ����","");
	}
}

//�޸��������
if($action == "password") {
	$userid = intval($_COOKIE['ob_userid']);
	$current_nickname = $DB->fetch_one("SELECT `nickname` FROM {$mysql_prefix}admin WHERE `id`={$userid}");
    $FORM->formheader(array("title" => "�޸�����:","action" => "admin.php?action=updatepassword"));
	$FORM->makeinput(array(
		"text"  => "�ǳ�",
		"note"  => "��ʾ��ǰ̨���û�����������û�����һ����",
		"name"  => "nickname",
		"value"  => $current_nickname,
	),0);
    $FORM->makeinput(array(
		"text" => "������:",
		"name" => "oldpassword",
		"type" => "password"
	));
    $FORM->makeinput(array(
		"text" => "������:",
        "name" => "newpassword",
        "type" => "password"
	));
    $FORM->makeinput(array(
		"text" => "ȷ��������:",
		"name" => "comfirpassword",
		"type" => "password"
	));
    $FORM->formfooter();
}

//ִ���޸�����
if($action == "updatepassword") {
	if (trim($_POST['oldpassword']) == "") {
        $FORM->ob_exit("������Ч","");
    }
    $user = $DB->fetch_one_array("SELECT `username`,`password` FROM `".$mysql_prefix."admin` WHERE `id`=".intval($_COOKIE['ob_userid']));
    if (md5($_POST['oldpassword']) != $user['password']) {
        $FORM->ob_exit("ԭ���벻��ȷ","");
    }
    $_POST['newpassword'] = trim($_POST['newpassword']);
    $_POST['comfirpassword'] = trim($_POST['comfirpassword']);
    if (trim($_POST['newpassword']) == "") {
        $FORM->ob_exit("�����벻��Ϊ��","");
    }
	if(strlen($_POST['newpassword']) < 5) {
		$FORM->ob_exit("�����볤�Ȳ���С��5λ","");
	}
    if ($_POST['newpassword'] != $_POST['comfirpassword']) {
        $FORM->ob_exit("��������������벻һ��","");
    }
	if(trim($_POST['nickname']) == '' || strlen(trim($_POST['nickname'])) > 100) {
		$FORM->ob_exit("�ǳ�Ϊ�ջ�̫��","");
	}
	$nickname = checkPost(trim($_POST['nickname']));
    $DB->query("UPDATE `".$mysql_prefix."admin` SET password='".md5($_POST['newpassword'])."',nickname='".$nickname."' WHERE `id`=".intval($_COOKIE['ob_userid']));
    $FORM->ob_exit("������ĳɹ�,�����µ�½","./index.php","_parent");
}

//�˳���½
if($action == "logout") {
	setcookie("ob_login","");
	setcookie("ob_userid","");
	$FORM->ob_exit("���Ѿ��˳������̨","./index.php","_parent");
}

//�����б�
if($action == "guestbook") {
	
	
	if(isset($_POST['keyword']) && checkPost(trim($_POST['keyword'])) != '') {
		$keyword = checkPost(trim($_POST['keyword']));
		$searchwhere = checkPost(trim($_POST['searchwhere']));
		$keyword = str_replace("_","\_",$keyword);
		$keyword = str_replace("%","\%",$keyword);
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."guestbook` WHERE `{$searchwhere}` LIKE '%{$keyword}%'");
		$listSql = "SELECT * FROM `".$mysql_prefix."guestbook` WHERE `{$searchwhere}` LIKE '%{$keyword}%' ORDER BY `id`";
		if($allNum == 0) {
			$FORM->ob_exit("û���ҵ�������������������","");
		}
	} else {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."guestbook`");
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=guestbook");
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."guestbook` ORDER BY `id` DESC LIMIT ".$startI.",20";
		if($allNum == 0) {
			$FORM->ob_exit("Ŀǰ��û������","");
		}
	}
	if(isset($_POST['keyword']) && checkPost(trim($_POST['keyword'])) != '') {
		$formtitle = "������� [����{$allNum}ƪ����]";
	} else {
		$formtitle = "���Թ��� [����{$allNum}ƪ����] [20��/ҳ]";
	}
	
	$FORM->js_checkall();
	$FORM->if_del();
	$FORM->formheader(array("title" => $formtitle,"colspan" => "5","action" => "admin.php?action=dosomeGb"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>�ظ�</b></td>\n";
	echo "<td width=\"25%\"><b>������</b></td>\n";
	echo "<td width=\"48%\"><b>����</b></td>\n";
	echo "<td width=\"17%\"><b>����</b></td>\n";
	echo "<td width=\"5%\"><input type=\"checkbox\" name=\"chkall\" value=\"on\" class=\"nonebg\" onclick=\"CheckAll(this.form)\"></td>\n";
	echo "</tr>\n";
	$gbs = $DB->query($listSql);
	while($gb = $DB->fetch_array($gbs)) {
		$isallow = empty($gb['reply']) ? "��" : "��";
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$isallow."</td>\n";
		echo "<td align=\"left\" valign=\"top\"><b>�ǳ�: </b>".$gb['username']."<br><b>E-mail: </b>".$gb['email']."<br><b>IP: </b>".$gb['ip']."</td>\n";
		echo "<td align=\"left\" valign=\"top\">".$gb['content']."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modgb&id=".$gb['id']."\">�༭</a>] [<a href=\"admin.php?action=replygb&id=".$gb['id']."\">�ظ�</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delgb&id=".$gb['id']."')\">ɾ��</a>]</td>\n";
		echo "<td><input type=\"checkbox\" name=\"gb[".$gb['id']."]\" value=\"1\" class=\"nonebg\"></td>\n";
		echo "</tr>\n";
	}

	echo "<tr class=\"secondalt\">";
	echo "<td colspan=\"5\" align=\"center\">\n";
	echo "<label for=\"del\"><input type=\"radio\" name=\"editall\" value=\"del\" id=\"del\" class=\"nonebg\">ɾ�� </label>";
	echo "</td></tr>\n";
	$FORM->makepage($page_char,5,0);
	$FORM->formfooter(array("colspan" => 5));

	//�������Ա�
	echo "\n\n<br><form action=\"admin.php?action=guestbook\" method=\"post\"><table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\"><tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" align=\"center\">\n<b>��������:&nbsp;&nbsp;<b>\n";
	echo "\n<select name=\"searchwhere\"><option value=\"content\">����</option><option value=\"username\">�ǳ�</option></select>\n<input type=\"text\" id=\"keyword\" name=\"keyword\" size=\"30\">\n\n";
	echo "&nbsp;&nbsp;<input type=\"submit\" value=\"����\"></td>\n";
	echo "</table></form>\n";
}

//������������
if($action == "dosomeGb") {
	$dowhat = checkPost(trim($_POST['editall']));
	$gb = checkPost($_POST['gb']);
	if($dowhat == "") {
		$FORM->ob_exit("��ѡ��Ҫִ�еĲ���","");
	}
	if(count($gb) == 0) {
		$FORM->ob_exit("��ѡ��Ҫ����������","");
	}
	if($dowhat == "del") {
		foreach($gb as $key=>$val) {
			if(!$DB->query("DELETE FROM `".$mysql_prefix."guestbook` WHERE `id` = ".$key)) {
				$FORM->ob_exit("ɾ������ʧ��","");
			}
		}
		$FORM->ob_exit("ɾ���������","");
	}
}

//�޸����Խ���
if($action == "modgb") {
	$gb = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."guestbook` WHERE `id` =".intval($_GET['id']));
	$FORM->formheader(array("title" => "�༭����","action" => "admin.php?action=domodgb","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "�ǳ�",
				"note"  => "",
				"name"  => "name",
				"value"  => $gb['username'],
            ),0);
	$FORM->makeinput(array(
				"text"  => "E-mail",
				"note"  => "",
				"name"  => "email",
				"value"  => trim($gb['email']),
            ),0);
	$FORM->maketextarea(array("text" => "����","name" => "content","value" => strip_tags($gb['content'])));
	$FORM->makehidden(array("name" => "id", "value" => $gb['id']));
	$FORM->formfooter();
}

//ִ���޸�����
if($action == "domodgb") {
	$name = checkPost(trim($_POST['name']));
	$email = checkPost(trim($_POST['email']));
	$content = nl2br(htmlspecialchars(trim($_POST['content'])));
	$id = intval($_POST['id']);
	if(empty($name)) {
		$FORM->ob_exit("�ǳƻ�û����д��","");
	}
	if(empty($content)) {
		$FORM->ob_exit("���ݻ�û����д��","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."guestbook` SET `username` = '".$name."', `email` = '".$email."', `content` = '".$content."' WHERE `id` = ".$id)) {
		$FORM->ob_exit("���Ա༭�ɹ�","admin.php?action=guestbook");
	} else {
		$FORM->ob_exit("���Ա༭ʧ��","");
	}
}

//�ظ����Խ���
if($action == "replygb") {
	$gb = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."guestbook` WHERE `id` =".intval($_GET['id']));
	$FORM->formheader(array("title" => "�ظ�����","action" => "admin.php?action=doreplygb","name" => "form"));
	echo "<tr ".$FORM->getrowbg()." nowrap>\n";
	echo "<td valign=\"top\"><b>����</b></td>\n";
	echo "<td>".$gb['content']."</td>\n";
	echo "</tr>\n";
	$FORM->maketextarea(array("text" => "�ظ�","note" => "ɾ���ظ�������","name" => "reply","value" => strip_tags($gb['reply'])));
	$FORM->makehidden(array("name" => "id", "value" => $gb['id']));
	$FORM->formfooter();
}

//ִ�лظ�����
if($action == "doreplygb") {
	$reply = nl2br(htmlspecialchars(trim($_POST['reply'])));
	$id = intval($_POST['id']);
	if($DB->query("UPDATE `".$mysql_prefix."guestbook` SET reply = '".$reply."' WHERE `id` = ".$id)) {
		$FORM->ob_exit("���Իظ��ɹ�","admin.php?action=guestbook");
	} else {
		$FORM->ob_exit("���Իظ�ʧ��","");
	}
}

//ɾ������
if($action == "delgb") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."guestbook` WHERE `id`=".$id)) {
		$FORM->ob_exit("ɾ�����Գɹ�","");
	} else {
		$FORM->ob_exit("ɾ������ʧ��","");
	}
}

//����/���۹���
if($action == "banned") {
	$banned = $DB->fetch_one_array("SELECT `banned_username`,`banned_word`,`banned_ip` FROM {$mysql_prefix}config");
	$FORM->formheader(array("title" => "���˹���","action" => "admin.php?action=dobanned","name" => "form"));
	$FORM->maketextarea(array("text" => "�û�������","note" => "��ֹ�û�������/����ʱʹ�õ��û�����ÿ��һ��","name" => "banned_username","value" => $banned['banned_username']));
	$FORM->maketextarea(array("text" => "�������","note" => "��ֹ�û�������/����ʱʹ�õĴ��ÿ��һ��<br>�벻Ҫ���ù���Ĵ������Ӱ�����Ч��","name" => "banned_word","value" => $banned['banned_word']));
	$FORM->maketextarea(array("text" => "IP����","note" => "��ֹӵ�д�IP���û���������/���ۡ�ÿ��һ��","name" => "banned_ip","value" => $banned['banned_ip']));
	$FORM->formfooter();
}

//ִ���ύ����/���۹���
if($action == "dobanned") {
	$banned_username = checkPost(trim($_POST['banned_username']));
	$banned_word = checkPost(trim($_POST['banned_word']));
	$banned_ip = checkPost(trim($_POST['banned_ip']));
	if($DB->query("UPDATE {$mysql_prefix}config SET `banned_username`='{$banned_username}', `banned_word`='{$banned_word}', `banned_ip`='{$banned_ip}'")) {
		$FORM->ob_exit("�����ɹ����","");
	} else {
		$FORM->ob_exit("������������","");
	}
}

//��Ӽ���
if($action == "addNote") {
	$FORM->formheader(array("title" => "��Ӽ���","action" => "admin.php?action=doaddNote","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "���µı���",
				"name"  => "name",
				"value" => ""
            ),0);
	$FORM->editor(array("text" => "����","note" => "���µ����ݣ�ʹ��UBB��ʽ�ı���"));
	$FORM->formfooter();
}

//ִ����Ӽ���
if($action == "doaddNote") {
	$title = htmlspecialchars(checkPost(trim($_POST['name'])));
	$content = htmlspecialchars(checkPost(trim($_POST['message'])));
	$date = time();
	if(empty($title)) {
		$FORM->ob_exit("���⻹û����д��","");
	}
	if(empty($content)) {
		$FORM->ob_exit("���ݻ�û����д��","");
	}
	if($DB->query("INSERT INTO `".$mysql_prefix."note` (date,title,content) VALUES ('".$date."','".$title."','".$content."')")) {
		$FORM->ob_exit("��ϲ��������ӳɹ�","admin.php?action=editNote");
	} else {
		$FORM->ob_exit("��Ǹ���������ʧ��","");
	}
}

//�������
if($action == "editNote") {
	$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."note`");
	if($allNum == 0) {
		$FORM->ob_exit("Ŀǰ��û�м���","");
	}
	$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$page_char = page($allNum,20,$cpage,"admin.php?action=editNote");
	$startI = $cpage*20-20;
	$listSql = "SELECT * FROM `".$mysql_prefix."note` ORDER BY `id` DESC LIMIT ".$startI.",20";
	$FORM->if_del();
	$FORM->tableheaderbig(array("title" => "���¹��� [����{$allNum}������] [20��/ҳ]","colspan" => "4"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>ID</b></td>\n";
	echo "<td width=\"50%\"><b>����</b></td>\n";
	echo "<td width=\"20%\"><b>����</b></td>\n";
	echo "<td width=\"25%\"><b>����</b></td>\n";
	echo "</tr>\n";
	$notes = $DB->query($listSql);
	while($note = $DB->fetch_array($notes)) {
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$note['id']."</td>\n";
		echo "<td align=\"left\" valign=\"top\"><a href=\"admin.php?action=viewNote&id=".$note['id']."\">".$note['title']."</a></td>\n";
		echo "<td align=\"center\" valign=\"top\">".obdate("y-m-d H:m:s",$note['date'])."</td>\n";
		echo "<td>[<a href=\"admin.php?action=viewNote&id=".$note['id']."\">���</a>] [<a href=\"admin.php?action=modNote&id=".$note['id']."\">�༭</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delNote&id=".$note['id']."')\">ɾ��</a>]</td>\n";
		echo "</tr>\n";
	}
	$FORM->makepage($page_char,4,0);
	$FORM->tablefooter();
}

//�������
if($action == "viewNote") {
	chdir("admin");
	$id = intval($_GET['id']);
	$note = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."note` WHERE `id` = ".$id);
	$FORM->formheader(array("title" => "����: ".trim($note['title']),"action" => "admin.php?action=editNote","name" => "input","colspan" => 2));
	$UBB->setString($note['content']);
	$note['content'] = $UBB->parse();
	$note['content'] = qqface($note['content'],"../admin/images/smilies/");
	echo "<tr class=\"secondalt\" nowrap>\n";
	echo "<td valign=\"top\" colspan=\"2\"><b>����: </b>".obdate("Y-m-d H:m:s",$note['date'])."</td>\n";
	echo "</tr>\n";
	echo "<tr ".$FORM->getrowbg()." nowrap>\n";
	echo "<td valign=\"top\" width=\"10%\"><b>����: </b></td>\n";
	echo "<td valign=\"top\">".$note['content']."</td>\n";
	echo "</tr>\n";
	$FORM->formfooter(array("colspan"=>'2',"button" =>array("submit"=>array("value"=>"����"))));
}

//�޸ļ���
if($action == "modNote") {
	$id = intval($_GET['id']);
	$note = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."note` WHERE `id` = ".$id);
	$FORM->formheader(array("title" => "�༭����","action" => "admin.php?action=domodNote","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "���µı���",
				"name"  => "name",
				"value" => $note['title']
            ),0);
	$FORM->editor(array("text" => "����","note" => "���µ����ݣ�ʹ��UBB��ʽ�ı���","value" => $note['content']));
	$FORM->makehidden(array("name" => "id","value" => $note['id']));
	$FORM->formfooter();
}

//ִ���޸ļ���
if($action == "domodNote") {
	$title = htmlspecialchars(checkPost(trim($_POST['name'])));
	$content = htmlspecialchars(checkPost(trim($_POST['message'])));
	$date = time();
	if(empty($title)) {
		$FORM->ob_exit("���⻹û����д��","");
	}
	if(empty($content)) {
		$FORM->ob_exit("���ݻ�û����д��","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."note` SET `date` = '".$date."',`title` = '".$title."',`content` = '".$content."' WHERE `id` = ".intval($_POST['id']))) {
		$FORM->ob_exit("��ϲ���༭���³ɹ�","admin.php?action=editNote");
	} else {
		$FORM->ob_exit("��Ǹ���༭����ʧ��","");
	}
}

//ɾ������
if($action == "delNote") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."note` WHERE `id`=".$id)) {
		$FORM->ob_exit("ɾ�����³ɹ�","");
	} else {
		$FORM->ob_exit("ɾ������ʧ��","");
	}
}

//�ļ�����
if($action == "uploadManager") {
	chdir("admin");
	$dir = "uploadfiles";
	$text = "�ϴ�";
	$FORM->js_checkall();
    $FORM->formheader(array(
		"title"   => $text."���ݹ���",
		"action"  => "admin.php?action=delFile",
		"colspan" => "3"
	));
	echo "<tr bgcolor=\"#F3F3F3\">\n";
	echo "<td align=\"center\"><b>�ļ���</b></td>\n";
	echo "<td align=\"center\"><b>�ļ���С</b></td>\n";
	echo "<td align=\"center\"><input name=\"chkall\" value=\"on\" type=\"checkbox\" onclick=\"CheckAll(this.form)\" class=\"nonebg\"></td>\n";
	echo "</tr>\n";
	$handle = opendir('../'.$dir);
	$url = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$url = str_replace("/admin/admin.php","",$url);
    while (false !== ($file = readdir($handle))) {
		if($file != "." && $file != ".." && $file != "index.html") {
			$filesize = get_real_size(@filesize('../'.$dir.'/'.$file));
			echo "<tr ".$FORM->getrowbg().">";
			echo "<td align=\"center\"><a href=\"http://".$url."/".$dir."/".$file."\">{$file}</a></td>";
			echo "<td align=\"center\">{$filesize}</td>";
			echo "<td align=\"center\">";
			echo "<input type=\"checkbox\" name=\"file[]\" value=\"../".$dir."/".$file."\" class=\"nonebg\">";
			echo "</td>\n";
			echo "</tr>";
		}
    }
	closedir($handle);
    $FORM->formfooter(array("colspan" => "3","button" =>array("submit"=>array("value"=>"ɾ��"))));
}

//ɾ���ļ�
if($action == "delFile") {
	chdir("admin");
	$file = checkPost($_POST['file']);
	if (empty($file) OR !is_array($file)) {
        $FORM->ob_exit("δѡ�񸽼�", "");
    }
	foreach($file as $key=>$val) {
		@chmod ($val, 0777);
		@unlink($val);
	}
	$FORM->ob_exit("ɾ���ļ��ɹ�", "");
}

//�ؽ���̬ҳ��
if($action == "rebuild") {
	$maxID = $DB->fetch_one("SELECT max(id) FROM `".$mysql_prefix."blog`");
	$FORM->formheader(array(
		"title"   => "�ؽ���̬ҳ��",
		"action"  => "admin.php?action=dobuild",
		"method"  => "get",
		"colspan" => "2",
	));
	echo "<tr ".$FORM->getrowbg().">";
	echo "<td><b>�ؽ���ҳ</b><br>���ڸ�Ŀ¼�������� index.html<br>����ľ�̬��չ����������������ö��ı�</td>";
	echo "<td>";
	echo "<input type=\"radio\" name=\"buildwhich\" value=\"index\" class=\"nonebg\" onclick=\"startid.disabled=true;endid.disabled=true;onetimenum.disabled=true\" checked> index.html";
	echo "</td>\n";
	echo "</tr>";
	echo "<tr ".$FORM->getrowbg().">";
	echo "<td><b>�ؽ���־ҳ</b><br>һ���ؽ��ܶྲ̬ҳ����Ҫ���ϳ���ʱ��<br>����ÿ���ؽ����50������ </td>";
	echo "<td>";
	echo "<input type=\"radio\" name=\"buildwhich\" value=\"blog\" class=\"nonebg\" onclick=\"startid.disabled=false;endid.disabled=false;onetimenum.disabled=false\">";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;��ʼID: <input type=\"text\" name=\"startid\" class=\"formfield\" size=\"5\" value=\"1\" disabled> &nbsp;&nbsp;����ID: <input type=\"text\" name=\"endid\" class=\"formfield\" size=\"5\" value=\"".$maxID."\" disabled>&nbsp;&nbsp;ÿ���ؽ�: <input type=\"text\" name=\"onetimenum\" class=\"formfield\" size=\"5\" value=\"50\" disabled><input type=\"hidden\" name=\"action\" value=\"dobuild\">";
	echo "</td>\n";
	echo "</tr>";
	$FORM->formfooter(array("colspan" => "2"));
}

//ִ���ؽ�ҳ��
if($action == "dobuild") {
	$buildwhich = trim($_GET['buildwhich']);
	if($buildwhich == "index") {
		if($HTML->makeindex()) {
			$FORM->ob_exit("��ҳ�ؽ��ɹ�");
		}
	}
	if($buildwhich == "blog") {
		@set_time_limit(600);
		$time_start = getmicrotime();
		$startid = intval($_GET['startid']);
		$endid = intval($_GET['endid']);
		if($startid > $endid) {
			$bigid = $startid;
			$smallid = $endid;
		} else {
			$bigid = $endid;
			$smallid = $startid;
		}
		$onetimenum = intval($_GET['onetimenum']);			//ÿ���ؽ����ٸ�
		$blogs = $DB->query("SELECT `id` FROM `".$mysql_prefix."blog` WHERE `id`<= ".$bigid." AND `id` >= ".$smallid);
		if($DB->num_rows($blogs) == 0) {
			$FORM->ob_exit("û�п��Խ�����ҳ��");
		}
		while($blogRe = $DB->fetch_array($blogs)) {
			$blogid[] = $blogRe['id'];
		}

		$FORM->div_top(array("title" => "�������½�����̬ҳ��... ���Ժ�"));
		$root = "";
		
		$countIndex = 1;
		foreach($blogid as $key=>$val) {
			$date = $DB->fetch_one("SELECT `date` FROM ".$mysql_prefix."blog WHERE `id` = ".$val);	
			$path = $root.getHtmlPath($val);
			if($HTML->make($val)) {
				$result = "���";
			} else {
				$result = "<font color=\"red\">ʧ��</font>";
			}
			echo "<b>#{$val}:</b> ���ڽ���ҳ��: {$path} ............................ [{$result}]<br />";
			if($countIndex >= $onetimenum) {
				$newstartid = $smallid + $onetimenum;
				$countIndex = 1;
				if(!isset($_GET['toaddcount'])) {
					$_GET['toaddcount'] = 0;
				}
				if(!isset($_GET['toaddtime'])) {
					$_GET['toaddtime'] = 0;
				}
				$_GET['toaddcount'] = $_GET['toaddcount'] + $onetimenum ;
				$time_end = getmicrotime();
				$usetime = $time_end - $time_start;
				$toaddtime = $usetime + $_GET['toaddtime'];
				echo "<br />--------------------------------------------------------------------";
				echo "<br /><a href=\"admin.php?buildwhich=blog&startid={$newstartid}&endid={$bigid}&onetimenum={$onetimenum}&toaddcount={$_GET['toaddcount']}&toaddtime={$toaddtime}&action=dobuild\">������ת...������������û���Զ���ת����������</a><br />�ۼƺ�ʱ $toaddtime ��";
				echo "<meta http-equiv=\"refresh\" content=\"3;URL=admin.php?buildwhich=blog&startid={$newstartid}&endid={$bigid}&onetimenum={$onetimenum}&toaddcount={$_GET['toaddcount']}&toaddtime={$toaddtime}&action=dobuild\" />";
				die();
			}
			$countIndex++;
			flush();
		}

		$time_end = getmicrotime();
		$usetime = $time_end - $time_start;
		$toaddtime = $usetime + $_GET['toaddtime'];
		echo "<br />--------------------------------------------------------------------";
		echo "<br />������̬ҳ����ɡ��ۼƺ�ʱ $toaddtime ��";
		echo "<script>topdiv.innerText = \"��̬ҳ�潨�����\"</script>";
		$FORM->div_bo();
	}
}

//�ļ��ϴ�����
if($action == "upload") {
	$maxSize = @getcon("upload_max_filesize");
	$FORM->formheader(array(
		"title"   => "�ļ��ϴ� [����ļ�����:{$maxSize}]",
		"action"  => "admin.php?action=doupload",
		"colspan" => "2",
		"enctype" => "multipart/form-data",
	));
	$FORM->makefile(array(
		"text" => "[#1] ��ѡ����Ҫ�ϴ����ļ�",
		"name" => "attachment[0]",
		"size" => "40",
	));
	$FORM->makefile(array(
		"text" => "[#2] ��ѡ����Ҫ�ϴ����ļ�",
		"name" => "attachment[1]",
		"size" => "40",
	));
	$FORM->makefile(array(
		"text" => "[#3] ��ѡ����Ҫ�ϴ����ļ�",
		"name" => "attachment[2]",
		"size" => "40",
	));
	$FORM->makefile(array(
		"text" => "[#4] ��ѡ����Ҫ�ϴ����ļ�",
		"name" => "attachment[3]",
		"size" => "40",
	));
	$FORM->makefile(array(
		"text" => "[#5] ��ѡ����Ҫ�ϴ����ļ�",
		"name" => "attachment[4]",
		"size" => "40",
	));
	$FORM->formfooter(array("colspan" => "2"));
}

//�ϴ��ļ�
if($action == "doupload") {
	foreach($_FILES['attachment']['name'] as $key=>$val) {
		if (is_array($_FILES)) {
			$attachment      = $_FILES['attachment']['tmp_name'][$key];
			$attachment_name = $uploadrandomfilename ? getmainfilename($_FILES['attachment']['name'][$key])."_".rand(10000,99999).".".getextension($_FILES['attachment']['name'][$key]) : $_FILES['attachment']['name'][$key];
			$attachment_size = $_FILES['attachment']['size'][$key];
			if(empty($attachment) || empty($attachment_name)) {
				continue;
			}
		}
		if (trim($attachment) != "none" and trim($attachment) != "" and trim($attachment_name) != "") {
			if(!acceptupload()) {
				$FORM->ob_exit("�����ϴ�ʱ��������","admin.php?action=uploadManager");
			}
		}
	}
	$FORM->ob_exit("�����ϴ��ɹ�","admin.php?action=uploadManager");
}

//����û�
if($action == "addUser") {
	$FORM->formheader(array("title" => "����û�","action" => "admin.php?action=doaddUser","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "�û���",
				"note"  => "��½��̨���û�����ֻ������ĸ���������",
				"name"  => "username",
				"value"  => "",
            ),0);
	$FORM->makeinput(array(
				"text"  => "�ǳ�",
				"note"  => "��ʾ��ǰ̨���û�����������û�����һ����",
				"name"  => "nickname",
				"value"  => "",
            ),0);
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "���벻������5���ַ�",
				"name"  => "password1",
				"value"  => "",
				"type" => "password",
            ),0);
	$FORM->makeinput(array(
				"text"  => "ȷ������",
				"note"  => "��������һ������",
				"name"  => "password2",
				"value"  => "",
				"type" => "password",
            ),0);
	for($i=0;$i<count($auth);$i++) {
		$check = ($auth[$i]['check']) ? "checked" : "";
		if($auth[$i]['name'] != "others") {
			echo "<tr ".$FORM->getrowbg(0)." nowrap>\n";
			echo "<td><b>[Ȩ��]</b> ".$auth[$i]['name']."<br>".$arguments['note']."</td>\n";
			echo "<td><input type=\"checkbox\" name=\"auth[]\" value=\"".$auth[$i]['action']."\" class=\"nonebg\" ".$check."></td>\n";
			echo "</tr>\n";
		} else {
			$FORM->makehidden(array("name" => "auth[]", "value" => $auth[$i]['action']));
		}
	}
	$FORM->formfooter();
}

//ִ������û�
if($action == "doaddUser") {
	$auth = $_POST['auth'];
	for($ii=0; $ii<count($auth); $ii++) {
		$auth[$ii] = checkPost(trim($auth[$ii]));
	}
	$auth_char = @implode(",",$auth);

	if ($_POST['password1'] != $_POST['password2']) {
        $FORM->ob_exit("������������벻һ��","");
    }
	if (trim($_POST['password1']) == "") {
        $FORM->ob_exit("���벻��Ϊ��","");
    }
	if(strlen($_POST['password1']) < 5) {
		$FORM->ob_exit("���볤�Ȳ���С��5λ","");
	}
	if(trim($_POST['nickname']) == '' || strlen(trim($_POST['nickname'])) > 100) {
		$FORM->ob_exit("�ǳ�Ϊ�ջ�̫��","");
	}

	$username = trim($_POST['username']);
	$password = md5($_POST['password1']);
	$nickname = checkPost(trim($_POST['nickname']));

	if($DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."admin` WHERE `username`='".$username."'") != 0) {
		$FORM->ob_exit("����û��Ѵ���","");
	}

	if($DB->query("INSERT INTO `".$mysql_prefix."admin` ( `username` , `password` , `auth` , `nickname`) VALUES ('".$username."', '".$password."', '".$auth_char."', '".$nickname."')")) {
		$FORM->ob_exit("�û���ӳɹ�","admin.php?action=editUser");
	} else {
		$FORM->ob_exit("�û����ʧ��","");
	}
}

//�༭�û�-�б�
if($action == "editUser") {
	$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."admin`");
	$listSql = "SELECT * FROM `".$mysql_prefix."admin` ORDER BY `id` ASC";
	$FORM->if_del();
	$FORM->tableheaderbig(array("title" => "�û����� [����{$allNum}���û�]","colspan" => "3"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"20%\"><b>ID</b></td>\n";
	echo "<td width=\"50%\"><b>�û���</b></td>\n";
	echo "<td width=\"30%\"><b>����</b></td>\n";
	echo "</tr>\n";
	$users = $DB->query($listSql);
	while($user = $DB->fetch_array($users)) {
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$user['id']."</td>\n";
		echo "<td align=\"center\" valign=\"top\">".$user['username']."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modUser&id=".$user['id']."\">�༭</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delUser&id=".$user['id']."')\">ɾ��</a>]</td>\n";
		echo "</tr>\n";
	}
	$FORM->makepage($page_char,3,0);
	$FORM->tablefooter();
}

//�޸�һ���û�
if($action == "modUser") {
	$id = intval($_GET['id']);
	$user = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."admin` WHERE `id`=".$id);
	$FORM->formheader(array("title" => "�༭�û�","action" => "admin.php?action=domodUser","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "�û���",
				"note"  => "��½��̨���û�����ֻ������ĸ���������",
				"name"  => "username",
				"value"  => $user['username'],
            ),0);
	$FORM->makeinput(array(
				"text"  => "�ǳ�",
				"note"  => "��ʾ��ǰ̨���û�����������û�����һ����",
				"name"  => "nickname",
				"value"  => $user['nickname'],
            ),0);
	$FORM->makeinput(array(
				"text"  => "����",
				"note"  => "����������,���벻������5���ַ�",
				"name"  => "password1",
				"value"  => "",
				"type" => "password",
            ),0);
	$FORM->makeinput(array(
				"text"  => "ȷ������",
				"note"  => "��������һ������",
				"name"  => "password2",
				"value"  => "",
				"type" => "password",
            ),0);
	
	for($i=0;$i<count($auth);$i++) {
		$check = (strstr($user['auth'],$auth[$i]['action'])) ? "checked" : "";
		if($auth[$i]['name'] != "others") {
			echo "<tr ".$FORM->getrowbg(0)." nowrap>\n";
			echo "<td><b>[Ȩ��]</b> ".$auth[$i]['name']."<br>".$arguments['note']."</td>\n";
			echo "<td><input type=\"checkbox\" name=\"auth[]\" value=\"".$auth[$i]['action']."\" class=\"nonebg\" ".$check."></td>\n";
			echo "</tr>\n";
		} else {
			$FORM->makehidden(array("name" => "auth[]", "value" => $auth[$i]['action']));
		}
	}
	$FORM->makehidden(array("name" => "id", "value" => $user['id']));
	$FORM->formfooter();
}

//ִ���޸�һ���û�
if($action == "domodUser") {
	$auth = $_POST['auth'];
	for($ii=0; $ii<count($auth); $ii++) {
		$auth[$ii] = checkPost(trim($auth[$ii]));
	}
	$auth_char = @implode(",",$auth);

	if ($_POST['password1'] != $_POST['password2']) {
        $FORM->ob_exit("������������벻һ��","");
    }
	if($_POST['password1'] != "" && strlen($_POST['password1']) < 5) {
		$FORM->ob_exit("���볤�Ȳ���С��5λ","");
	}
	if(trim($_POST['nickname']) == '' || strlen(trim($_POST['nickname'])) > 100) {
		$FORM->ob_exit("�ǳ�Ϊ�ջ�̫��","");
	}

	$oldpassword = $DB->fetch_one("SELECT `password` FROM `".$mysql_prefix."admin` WHERE `id`=".intval($_POST['id']));
	$newpassword = ($_POST['password1'] == "") ? $oldpassword : md5($_POST['password1']);
	$username = trim($_POST['username']);
	$password = $newpassword;
	$nickname = trim($_POST['nickname']);

	if($DB->query("UPDATE `".$mysql_prefix."admin` SET `username` = '".$username."',`password`='".$password."',`auth`='".$auth_char."',`nickname`='".$nickname."' WHERE `id`=".intval($_POST['id']))) {
		$FORM->ob_exit("�༭�û��ɹ�","");
	} else {
		$FORM->ob_exit("�༭�û�ʧ��","");
	}
}

//ɾ��һ���û�
if($action == "delUser") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."admin` WHERE `id`=".$id)) {
		$FORM->ob_exit("ɾ���û��ɹ�","");
	} else {
		$FORM->ob_exit("ɾ���û�ʧ��","");
	}
}

//Trackback Pings ����-�б�
if($action == "editTrackback") {
	if(isset($_GET['id'])) {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."trackback` WHERE `inblog` = ".intval($_GET['id']));
		if($allNum == 0) {
			$FORM->ob_exit("��ǰ��־û�� Trackback Ping","");
		}
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=editTrackback&id=".intval($_GET['id']));
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."trackback` WHERE `inblog` = ".intval($_GET['id'])." ORDER BY `trackbackid` DESC LIMIT ".$startI.",20";
	} else {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."trackback`");
		if($allNum == 0) {
			$FORM->ob_exit("Ŀǰ��û�� Trackback Ping","");
		}
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=editTrackback");
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."trackback` ORDER BY `trackbackid` DESC LIMIT ".$startI.",20";
	}
	$FORM->js_checkall();
	$FORM->if_del();
	$FORM->formheader(array("title" => "����ͨ����� [����{$allNum}ƪ����ͨ��] [20��/ҳ]","colspan" => "5","action" => "admin.php?action=dosomeTrackback"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"20%\"><b>վ������</b></td>\n";
	echo "<td width=\"40%\"><b>���ñ���</b></td>\n";
	echo "<td width=\"20%\"><b>���ʱ��</b></td>\n";
	echo "<td width=\"15%\"><b>����</b></td>\n";
	echo "<td width=\"5%\"><input type=\"checkbox\" name=\"chkall\" value=\"on\" class=\"nonebg\" onclick=\"CheckAll(this.form)\"></td>\n";
	echo "</tr>\n";
	$trackbacks = $DB->query($listSql);
	while($trackbackRe = $DB->fetch_array($trackbacks)) {
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$trackbackRe['blogname']."</td>\n";
		echo "<td align=\"left\" valign=\"top\">".$trackbackRe['title']."</td>\n";
		echo "<td align=\"center\">".obdate("y-m-d H:m:s",$trackbackRe['adddate'])."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modTrackback&id=".$trackbackRe['trackbackid']."\">�༭</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delTrackback&trackbackid=".$trackbackRe['trackbackid']."')\">ɾ��</a>]</td>\n";
		echo "<td><input type=\"checkbox\" name=\"trackback[".$trackbackRe['trackbackid']."]\" value=\"1\" class=\"nonebg\"></td>\n";
		echo "</tr>\n";
	}
	
	echo "<tr class=\"secondalt\">";
	echo "<td colspan=\"5\" align=\"center\">\n";
	echo "<label for=\"del\"><input type=\"radio\" name=\"editall\" value=\"del\" id=\"del\" class=\"nonebg\">ɾ�� </label>";
	echo "</td></tr>\n";
	$FORM->makepage($page_char,5,2);
	$FORM->formfooter(array("colspan" => 5));
}

//ɾ�� Trackback Ping
if($action == "delTrackback") {
	$trackbackid = intval($_GET['trackbackid']);
	$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."trackback` WHERE `trackbackid`=".$trackbackid);
	if($DB->query("DELETE FROM `".$mysql_prefix."trackback` WHERE `trackbackid`=".$trackbackid)) {
		if($makehtml) {
			$HTML->makeindex();
			$HTML->make($blogid);
		}
		$FORM->ob_exit("Trackback Pings ɾ���ɹ�","");
	} else {
		$FORM->ob_exit("Trackback Pings ɾ��ʧ��","");
	}
}

//�������� Trackback
if($action == "dosomeTrackback") {
	$dowhat = checkPost(trim($_POST['editall']));
	$trackback = checkPost($_POST['trackback']);
	if($dowhat == "") {
		$FORM->ob_exit("��ѡ��Ҫִ�еĲ���","");
	}
	if($dowhat == "del") {
		foreach($trackback as $key=>$val) {
			if(!$DB->query("DELETE FROM `".$mysql_prefix."trackback` WHERE `trackbackid` = ".$key)) {
				$FORM->ob_exit("ɾ������ʧ��","");
			}
		}
		$FORM->ob_exit("ɾ���������","");
	}
}

//�޸�һ�� Trackback Ping ����
if($action == "modTrackback") {
	$trackback = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."trackback` WHERE `trackbackid` =".intval($_GET['id']));
	$FORM->formheader(array("title" => "�༭����ͨ��","action" => "admin.php?action=domodTrackback","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "վ������",
				"note"  => "",
				"name"  => "blog_name",
				"value"  => trim($trackback['blogname']),
            ),0);
	$FORM->makeinput(array(
				"text"  => "���ñ���",
				"note"  => "",
				"name"  => "title",
				"value"  => trim($trackback['title']),
            ),0);
	$FORM->makeinput(array(
				"text"  => "���õ�ַ",
				"note"  => "",
				"name"  => "url",
				"value"  => trim($trackback['url']),
				"maxlength" => 100,
            ),0);
	$FORM->maketextarea(array("text" => "��������","name" => "excerpt","value" => strip_tags($trackback['excerpt'])));
	$FORM->makehidden(array("name" => "trackbackid", "value" => $trackback['trackbackid']));
	$FORM->formfooter();
}

//ִ���޸�һ�� Trackback Ping
if($action == "domodTrackback") {
	$blog_name = checkPost(trim($_POST['blog_name']));
	$title = checkPost(trim($_POST['title']));
	$url = checkPost(trim($_POST['url']));
	$excerpt = nl2br(htmlspecialchars(trim($_POST['excerpt'])));
	$trackbackid = intval($_POST['trackbackid']);
	if(empty($blog_name)) {
		$FORM->ob_exit("վ�����ƻ�û����д��","");
	}
	if(empty($title)) {
		$FORM->ob_exit("���ñ��⻹û����д��","");
	}
	if(empty($url)) {
		$FORM->ob_exit("���õ�ַ��û����д��","");
	}
	if(empty($excerpt)) {
		$FORM->ob_exit("�������ݻ�û����д��","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."trackback` SET `blogname` = '".$blog_name."', `title` = '".$title."', `url` = '".$url."', `excerpt` = '".$excerpt."' WHERE `trackbackid` = ".$trackbackid)) {
		$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."trackback` WHERE `trackbackid`=".$trackbackid);
		if($makehtml) {
			$HTML->make($blogid);
		}
		$FORM->ob_exit("Trackback Ping �༭�ɹ�","");
	} else {
		$FORM->ob_exit("Trackback Ping �༭ʧ��","");
	}
}

//ģ����� - �б�
if($action == "selectTemplate") {
	//ȡ��ģ��Ŀ¼��
	if ($handle = @opendir('templates')) {
		while (false !== ($file = readdir($handle))) {
			if(is_dir('templates/'.$file) && $file != "." && $file != "..") {
				$dir[$file] = $file;
			}
		}
		unset($file);
		closedir($handle);
	}
	//ȡ�õ�ǰ�༭��ģ��Ŀ¼
	if(isset($_GET['template_dir']) && trim($_GET['template_dir']) != '') {
		$current_template_dir = checkPost(trim($_GET['template_dir']));
	} else {
		$current_template_dir = $DB->fetch_one("SELECT `template` FROM {$mysql_prefix}config");
	}
	//ȡ�õ�ǰģ����ļ��б�
	if ($handle = opendir('templates/'.$current_template_dir)) {
		while (false !== ($file = readdir($handle))) {
			if(getextension($file) == "htm" || getextension($file) == "html" || getextension($file) == "css") {
				$tpl_file[$file] = $file;
			}
		}
		unset($file);
		closedir($handle);
	}

	echo "<script language=\"javascript\">\n";
	echo "function selecturl(url){\n";
	echo "location=\"admin.php?action=selectTemplate&template_dir=\"+url;\n";
	echo "}\n";
	echo "</script>\n";
	$FORM->formheader(array("title" => "ѡ��ģ��","action" => "admin.php?action=editTemplate"));
	$FORM->makeselect(array(
		"text"  => "ѡ����Ҫ�༭��ģ����ϵ",
		"name"  => "template_dir",
		"option" => $dir,
		"extra" => "onchange=\"selecturl(document.all.template_dir.value)\"",
		"selected" => $current_template_dir,
	));
	$FORM->makeselect(array(
		"text" => "��ѡ��ģ���ļ�:",
        "name" => "template_file",
        "option" => $tpl_file,
        "selected" => $tableselected,
        "multiple" => 0,
        "size" => 15
    ));
	$FORM->formfooter();
}

//ģ����� - �༭ģ���ļ� - ����
if($action == "editTemplate") {
	$template_dir = checkPost(trim($_POST['template_dir']));
	$template_file = checkPost(trim($_POST['template_file']));
	$template_content = @htmlspecialchars(file_get_contents("templates/".$template_dir."/".$template_file));
	if($template_dir == "" || $template_file == "") {
		$FORM->ob_exit("��ѡ����Ҫ�༭��ģ��");
	}

	$FORM->formheader(array("title" => "�༭ģ���ļ�","action" => "admin.php?action=saveTemplate"));
	$FORM->makeinput(array(
				"text"  => "ģ����ϵ",
				"name"  => "template_dir",
				"value" => $template_dir,
            ));
	$FORM->makeinput(array(
				"text"  => "ģ���ļ�",
				"name"  => "template_file",
				"value" => $template_file,
            ));
	$FORM->maketextarea(array("text" => "ģ���ļ�����","name" => "template_content","value" => $template_content,"rows" => 24,"cols" => 86,"extra" => "style=\"font-family:Courier New;font-size: 12px;\""));
	$FORM->formfooter();
}

//ģ����� - ����ģ���ļ�
if($action == "saveTemplate") {
	$template_dir = checkPost(trim($_POST['template_dir']));
	$template_file = checkPost(trim($_POST['template_file']));
	//$template_content = stripslashes(str_replace("\x0d\x0a", "\x0a", $_POST['template_content']));
	$template_content = stripslashes($_POST['template_content']);
	$file_path = "templates/".$template_dir."/".$template_file;
	$fp = fopen($file_path,"wb");
	flock($fp, LOCK_EX);
	if(fwrite($fp,$template_content)) {
		flock($fp, LOCK_UN);
		fclose($fp);
		$FORM->ob_exit("�༭ģ���ļ��ɹ�");
	}
}

//RSS 2.0 ���ݵ��� - ����
if($action == "rssExport") {
	$FORM->formheader(array("title"=> "RSS ���ݵ���","action" => "admin.php?action=doRssExport"));
	echo "<tr><td class=\"tblhead\" colspan=\"2\"><b>ע�⣺</b>���ﵼ�����ݵ�ΪRSS 2.0�ļ������ݽ�������־�����κ�֧�ֵ���RSS�ĳ�����ͨ�á�</td></tr>";
	$FORM->makeinput(array(
		"text" => "ÿ���ļ�������¼��:",
		"note" => "ÿ�������ļ��������ļ�¼��Ŀ",
        "name" => "num_in_file",
		"size" => 40,
        "value" => "50"
	),1);
	$FORM->makeinput(array(
		"text" => "�������ݵ�:",
		"note" => "��ȷ�������ļ��е�������777",
        "name" => "path",
		"size" => 40,
        "value" => "../bak/o-blog".obdate("Ymd",time())."_".M_random(8).".xml"
	),1);	

	$FORM->formfooter();
}

//RSS 2.0 ���ݵ��� - ִ��
if($action == "doRssExport") {
	@set_time_limit(600);
	$time_start = @getmicrotime();
	$num_in_file = intval($_POST['num_in_file']);
	$path = checkPost(trim($_POST['path']));
	$path = str_replace("../","",$path);
	
	if($num_in_file <= 0) {
		$FORM->ob_exit("��¼����д�д���");
	}
	$data_header = "<?xml version=\"1.0\" encoding=\"gb2312\" ?>\n<rss version=\"2.0\">\n<channel about=\"{$blogurl}\">\n<title>{$blogName}</title>\n<link>{$blogurl}</link>\n<description>{$discribe}</description>\n<language>zh-cn</language>\n<copyright>O-blog</copyright>\n\n";
	$data_bottom = "</channel>\n</rss>";
	
	$goindex = 0;
	$filename_index = 0;
	$blogNum = $DB->query("SELECT count(*) FROM {$mysql_prefix}blog");
	$blogs = $DB->query("SELECT * FROM {$mysql_prefix}blog ORDER BY id ASC");

	$FORM->div_top(array("title" => "���ڵ���RSS 2.0 �ļ�..."));
	while($blog = $DB->fetch_array($blogs)) {
		if($goindex == 0) {
			$file_dir = dirname($path);
			$file_name = basename($path);
			$file_extension = getextension($file_name);
			$file_onlyname = str_replace(".".$file_extension,"",$file_name);
			$path_new = $file_dir."/".$file_onlyname."_".$filename_index.".".$file_extension;
			echo "���ڵ��� ".$path_new." .............................. [�ɹ�]<br>";
			$fp = fopen($path_new,"wb+");
			fwrite($fp,$data_header);
		}
		$data_body = "<item>\n";
		$data_body .= "<link>".$blogurl.getHtmlPath($blog['id'])."</link>\n";
		$data_body .= "<title>".$blog['title']."</title>\n";
		$data_body .= "<author>".$blog['author']."</author>\n";
		$data_body .= "<pubDate>".obdate("r",$blog['date'])."</pubDate>\n";
		$data_body .= "<guid></guid>\n";
		$data_body .= "<description>\n<![CDATA[\n ".$blog['content']." ]]>\n</description>\n";
		$data_body .= "</item>\n\n";
		fwrite($fp,$data_body);
		$goindex++;
		if($goindex >= $num_in_file) {
			fwrite($fp,$data_bottom);
			$filename_index++;
			$goindex = 0;
			flush();
			sleep(1);
		}
	}
	if(@$blogNum/$num_in_file == 0 || @$blogNum < $num_in_file) {
		fwrite($fp,$data_bottom);
	}
	fclose($fp);
	$time_end = @getmicrotime();
	$time_used= $time_end - $time_start;
	echo "<br />--------------------------------------------------------------------";
	echo "<br />RSS 2.0 ���ݵ�����ɣ�����ʱ {$time_used} ��";
	echo "<script>topdiv.innerText = \"RSS ���ݵ������\"</script>";
	$FORM->div_bo();
}

//RSS 2.0 ���ݵ��� - ����
if($action == "rssImport") {
	chdir("admin");
	$dir = "bak";
	$FORM->js_checkall();
    $FORM->formheader(array(
		"title"   => "RSS ���ݵ���",
		"action"  => "admin.php?action=delFile",
		"colspan" => "4"
	));
	echo "<tr><td class=\"tblhead\" colspan=\"4\">֧���κα�׼�� RSS 2.0 ���ݵĵ��롣���������Ҫ�ؽ���̬ҳ��<br />ͨ��RSS�������־��ֻ�ܵ�����⡢����ʱ������ݣ����������ݲ��ᵼ�롣</td></tr>";
	echo "<tr bgcolor=\"#F3F3F3\">\n";
	echo "<td align=\"center\"><b>�ļ���</b></td>\n";
	echo "<td align=\"center\"><b>�ļ���С</b></td>\n";
	echo "<td align=\"center\"><b>����</b></td>\n";
	echo "<td align=\"center\"><input name=\"chkall\" value=\"on\" type=\"checkbox\" onclick=\"CheckAll(this.form)\" class=\"nonebg\"></td>\n";
	echo "</tr>\n";
	$handle = opendir('../'.$dir);
	$url = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$url = strtolower($url);
	$url = str_replace("/admin/admin.php","",$url);
    while (false !== ($file = readdir($handle))) {
		if(strtolower(getextension($file)) == 'xml') {
			$filesize = get_real_size(@filesize('../'.$dir.'/'.$file));
			echo "<tr ".$FORM->getrowbg().">";
			echo "<td align=\"center\"><a href=\"http://".$url."/".$dir."/".$file."\">{$file}</a></td>";
			echo "<td align=\"center\">{$filesize}</td>";
			echo "<td align=\"center\">[<a href=\"admin.php?action=rssImportSort&amp;filename={$file}\" >����</a>]</td>";
			echo "<td align=\"center\">";
			echo "<input type=\"checkbox\" name=\"file[]\" value=\"../".$dir."/".$file."\" class=\"nonebg\">";
			echo "</td>\n";
			echo "</tr>";
		}
    }
	closedir($handle);
    $FORM->formfooter(array("colspan" => "4","button" =>array("submit"=>array("value"=>"ɾ��"))));
}

//RSS 2.0 ���ݵ��� - ѡ�����
if($action == "rssImportSort") {
	$filename = trim($_GET['filename']);
	$classes = $DB->query("SELECT * FROM `".$mysql_prefix."class`");
	while($classRe = $DB->fetch_array($classes)) {
		$class[$classRe['id']] = $classRe['classname'];
	}
	$FORM->formheader(array(
		"title"   => "RSS ���ݵ��� - ѡ�������",
		"action"  => "admin.php?action=doRssImport",
		"colspan" => "2"
	));
	echo "<tr><td class=\"tblhead\" colspan=\"2\"><b>˵����</b>��Ҫ�����RSS�ļ�Ϊ: {$filename}</td></tr>";
	$FORM->makeselect(array(
		"text"  => "ѡ�����",
		"note"  => "ѡ����Ҫ��RSS�ļ�����ķ���",
		"name"  => "class",
		"option" => $class,
	),0);
	$FORM->makeselect(array(
		"text"  => "ѡ��ԴRSS�ļ��ı���",
		"note"  => "���򽫻�ѱ���ת��ΪO-blogʹ�õ�GB2312����",
		"name"  => "encode",
		"option" => array("GB2312" => "GB2312","UTF-8" => "UTF-8"),
	),0);
	$FORM->makeselect(array(
		"text"  => "ѡ��ԴRSS�ļ��洢���ݵķ�ʽ",
		"note"  => "���򽫰�����ת����UBB�洢",
		"name"  => "htmlubb",
		"option" => array("UBB" => "UBB","HTML" => "HTML"),
	),0);
	$FORM->makehidden(array("name" => "filename","value" => $filename));
	$FORM->formfooter();
}

//RSS 2.0 ���ݵ��� - ִ�е�������
if($action == "doRssImport") {
	@set_time_limit(600);
	$is_utf8 = (trim($_POST['encode']) == "UTF-8") ? 1 : 0;
	$is_html = (trim($_POST['htmlubb']) == "HTML") ? 1 : 0;
	$filename = trim($_POST['filename']);
	$classid = intval($_POST['class']);
	$file_path = "bak/".$filename;
	$xml_data = file_get_contents($file_path);
	if($is_utf8) {
		$EncodeConvert = new Chinese("UTF8","GB2312",$xml_data);
		$xml_data = $EncodeConvert->ConvertIT();
		//$fp = fopen($file_path,"wb+");
		//flock($fp, LOCK_EX);
		//fwrite($fp,$xml_data);
		//flock($fp, LOCK_UN); 
		//fclose($fp);
		//$xml_data = file_get_contents($file_path);
	}
	$rss = rssrollback($xml_data);
	foreach($rss as $key=>$val) {
		if($is_html) {
			$rss[$key]['content'] = html2ubb($rss[$key]['content']);
		}
		$sql = "INSERT INTO {$mysql_prefix}blog (title,date,content,classid,draft) VALUES ('{$rss[$key]['title']}','{$rss[$key]['time']}','{$rss[$key]['content']}','{$classid}','0')";
		if(!$DB->query($sql)) {
			$FORM->ob_exit("RSS���ݵ������");
		}
	}
	if($makehtml) {
		$HTML->makeindex();
	}
	$FORM->ob_exit("RSS���ݵ������");
}

//ִ��SQL - ����
if($action == "runsql") {
	$FORM->formheader(array("title" => "ִ��SQL��ѯ","action" => "admin.php?action=dorunsql","name" => "form"));
	echo "<tr><td class=\"tblhead\" colspan=\"2\"><b>���棺</b>�˹��ܿ��ܻ��ƻ�����ɾ���������ݿ��е����ݣ������ʹ�á�</td></tr>";
	$FORM->maketextarea(array("text" => "SQL��ѯ","name" => "sql","rows" => "20","cols" => "87","value" => "","extra" => "style=\"font-family:Courier New;font-size: 12px;\""));
	$FORM->formfooter();
}

//ִ��SQL -	ִ�� 
if($action == "dorunsql") {
	$sql = $_POST['sql'];
	if(empty($sql)) {
		$FORM->ob_exit("������SQL��ѯ");
	}
	$sqlquery = splitsql($sql);
	foreach($sqlquery as $sql) {
		if(trim($sql) != '') {
			$DB->query(stripslashes($sql), 'SILENT');
			if($sqlerror = $DB->error()) {
				break;
			}
		}
	}
	$FORM->ob_exit( $sqlerror ? "SQL���ִ�г���" : "SQLִ�гɹ����" );
}

//����Զ����� - ����
if($action == "addAutolink") {
	$FORM->formheader(array("title" => "����Զ�����","action" => "admin.php?action=doaddAutolink","name" => "form"));
	echo "<tr><td class=\"tblhead\" colspan=\"2\"><b>˵����</b>��������־�г������������ƥ��Ĵ�ʱ�����ᱻ����ָ�������ӡ��˹���Ҫ��./admin/class/autolink.php�ļ���д(777)</td></tr>";
	$FORM->makeinput(array(
				"text"  => "���ӹؼ���",
				"note"  => "��ƥ��Ĺؼ��֣������ִ�Сд",
				"name"  => "keyword",
				"size"  => 50,
            ));
	$FORM->makeinput(array(
				"text"  => "����URL",
				"note"  => "�ؼ���ָ���URL",
				"name"  => "url",
				"size"  => 50,
            ));
	$FORM->formfooter();
}

//����Զ����� - ִ��
if($action == "doaddAutolink") {
	if(file_exists("admin/class/autolink.php")) {
		$linkfilepath = "admin/class/autolink.php";
	} elseif(file_exists("class/autolink.php")) {
		$linkfilepath = "class/autolink.php";
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
		$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
	} else {
		$FORM->ob_exit("�޷��ҵ��ļ� ./admin/class/autolink.php");
	}
	require_once($linkfilepath);
	
	//����һ����Ա
	$_POST['keyword'] = trim($_POST['keyword']);
	$_POST['url'] = trim($_POST['url']);
	$autolink[] = array(
		"keyword" => $_POST['keyword'],
		"url" => $_POST['url'],
	);
	
	//д���ļ�
	$data = "\$autolink = ".var_export($autolink,TRUE).";";
	$fp = @fopen($linkfilepath,"wb");
	@flock($fp, LOCK_EX);
	@fwrite($fp,"<?php\r\n");
	$addok = @fwrite($fp, $data);
	@fwrite($fp,"\r\n?>");
	@flock($fp, LOCK_UN);
	if($addok) {
		$FORM->ob_exit("��ӳɹ�");
	} else {
		$FORM->ob_exit("�޷�д���趨����ȷ��autolink.php�ļ��ǿ�д��");
	}
}

//�༭�Զ����� - �б�
if($action == "editAutolink") {
	$FORM->js_checkall();
	$FORM->formheader(array(
		"title"   => "�����Զ�����",
		"action"  => "admin.php?action=updateAutolink",
		"colspan" => "4"
	));
	echo "<tr><td class=\"tblhead\" colspan=\"4\"><b>˵����</b>��������־�г������������ƥ��Ĵ�ʱ�����ᱻ����ָ�������ӡ��˹���Ҫ��./admin/class/autolink.php�ļ���д(777)</td></tr>";
	echo "<tr bgcolor=\"#F3F3F3\">\n";
	echo "<td align=\"center\"><b>�ļ���</b></td>\n";
	echo "<td align=\"center\"><b>����</b></td>\n";
	echo "<td align=\"center\"><b>����</b></td>\n";
	echo "</tr>\n";
	
	if(file_exists("admin/class/autolink.php")) {
		$linkfilepath = "admin/class/autolink.php";
	} elseif(file_exists("class/autolink.php")) {
		$linkfilepath = "class/autolink.php";
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
		$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
	} else {
		$FORM->ob_exit("�޷��ҵ��ļ� ./admin/class/autolink.php");
	}
	require_once($linkfilepath);

	foreach($autolink as $key=>$val) {
		echo "<tr ".$FORM->getrowbg().">";
		echo "<td align=\"center\"><input class=\"formfield\" type=\"text\" name=\"keyword[]\" size=\"30\" maxlength=\"200\" value=\"{$val['keyword']}\" ></td>";
		echo "<td align=\"center\"><input class=\"formfield\" type=\"text\" name=\"url[]\" size=\"50\" maxlength=\"200\" value=\"{$val['url']}\" ></td>";
		echo "<td align=\"center\">";
		echo "[<a href=\"admin.php?action=delAutolink&id={$key}\">ɾ��</a>]";
		echo "</td>\n";
		echo "</tr>";
	}

    $FORM->formfooter(array("colspan" => "4","button" =>array("submit"=>array("value"=>"����"))));
}

//�����Զ����� - ִ��
if($action == "updateAutolink") {
	if(count($_POST['keyword']) !== count($_POST['url'])) {
		$FORM->ob_exit("���³��ִ�����ȷ����д�ı�����ȷ��");
	}
	array_walk($_POST['keyword'],"trim");
	array_walk($_POST['url'],"trim");
	foreach($_POST['keyword'] as $key=>$val) {
		$autolink[] = array(
			"keyword" => $val,
			"url" => $_POST['url'][$key],
		);
	}
	//д���ļ�
	if(file_exists("admin/class/autolink.php")) {
		$linkfilepath = "admin/class/autolink.php";
	} elseif(file_exists("class/autolink.php")) {
		$linkfilepath = "class/autolink.php";
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
		$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
	} else {
		$FORM->ob_exit("�޷��ҵ��ļ� ./admin/class/autolink.php");
	}

	$data = "\$autolink = ".var_export($autolink,TRUE).";";
	$fp = @fopen($linkfilepath,"wb");
	@flock($fp, LOCK_EX);
	@fwrite($fp,"<?php\r\n");
	$addok = @fwrite($fp, $data);
	@fwrite($fp,"\r\n?>");
	@flock($fp, LOCK_UN);
	if($addok) {
		$FORM->ob_exit("���³ɹ�");
	} else {
		$FORM->ob_exit("�޷�д���趨����ȷ��autolink.php�ļ��ǿ�д��");
	}
}

//ɾ���Զ����� - ִ��
if($action == "delAutolink") {
	if(file_exists("admin/class/autolink.php")) {
		$linkfilepath = "admin/class/autolink.php";
	} elseif(file_exists("class/autolink.php")) {
		$linkfilepath = "class/autolink.php";
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
		$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
	} else {
		$FORM->ob_exit("�޷��ҵ��ļ� ./admin/class/autolink.php");
	}
	require_once($linkfilepath);
	$id = intval($_GET['id']);
	unset($autolink[$id]);

	//������������
	$newautolink = array_values($autolink);
	//д���ļ�
	$data = "\$autolink = ".var_export($newautolink,TRUE).";";
	$fp = @fopen($linkfilepath,"wb");
	@flock($fp, LOCK_EX);
	@fwrite($fp,"<?php\r\n");
	$addok = @fwrite($fp, $data);
	@fwrite($fp,"\r\n?>");
	@flock($fp, LOCK_UN);
	if($addok) {
		$FORM->ob_exit("ɾ���ɹ�");
	} else {
		$FORM->ob_exit("�޷�д���趨����ȷ��autolink.php�ļ��ǿ�д��");
	}
}

$FORM->cpfooter();
getlog();
?>