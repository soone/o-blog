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

//参数设置表单
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
	$keep_page_way = array("day"=>"按日存放","month"=>"按月存放","yeah"=>"按年存放","all"=>"放到一个文件夹 ");
	$FORM->formheader(array("title" => "基本设置","action" => "admin.php?action=doconfig"));
	$FORM->makeinput(array(
				"text"  => "BLOG 名称",
				"note"  => "显示在首页的标题栏和每页顶部",
				"name"  => "blogname",
				"value" => $config['blogname']
            ));
	$FORM->makeinput(array(
				"text"  => "BLOG 描述",
				"note"  => "关于这个Blog的简单介绍",
				"name"  => "blogdescribe",
				"value" => $config['blogdescribe']
            ));
	$FORM->makeinput(array(
				"text"  => "BLOG 的URL地址",
				"note"  => "例如:http://www.phpblog.cn",
				"name"  => "blogurl",
				"value" => $config['blogurl']
            ));
	$FORM->makeinput(array(
				"text"  => "每页日志数",
				"note"  => "前台日志列表中每页显示的日志数",
				"name"  => "indexNum",
				"value" => $config['index_show_number']
            ));
	$FORM->makeinput(array(
				"text"  => "最新日志/评论数",
				"note"  => "前台最新日志和最新评论显示的数量",
				"name"  => "newNum",
				"value" => $config['lastblog']
            ));
	$FORM->makeinput(array(
				"text"  => "每页留言数",
				"note"  => "前台每页显示多少个留言",
				"name"  => "gbNum",
				"value" => $config['gb_show_num']
            ));
	$FORM->makeinput(array(
				"text"  => "评论/留言字符限制数",
				"note"  => "当游客可以发表的评论/留言内容最大字数,默认为6000",
				"name"  => "max_gb_char",
				"value" => $config['max_gb_char']
            ));
	$FORM->makeinput(array(
				"text"  => " 发表评论/留言间隔时间",
				"note"  => "游客发表评论/留言的最少间隔时间。为0时不限制。单位:秒",
				"name"  => "post_gb_time",
				"value" => $config['post_gb_time']
            ));
	$FORM->makeinput(array(
				"text"  => " 最新日志/评论标题截取字符数",
				"note"  => "当您更换模板时，可能需要更改此项。",
				"name"  => "lastblog_cut_char",
				"value" => $config['lastblog_cut_char']
            ));
	$FORM->makeinput(array(
				"text"  => " 搜索间隔时间",
				"note"  => "游客搜索时最少间隔时间。为0时不限制。单位:秒",
				"name"  => "search_time",
				"value" => $config['search_time']
            ));
	$FORM->makeinput(array(
				"text"  => "存放静态文件的目录名",
				"note"  => "默认为\"archives\",更改后程序会生成新文件夹",
				"name"  => "archive_folder",
				"value" => $config['archive_folder']
            ));
	$FORM->makeinput(array(
				"text"  => "日志时间格式",
				"note"  => "通过这里来改变日志的时间显示格式。<a href=\"http://cn.php.net/manual/zh/function.date.php\" target=\"_blank\">语法说明</a>",
				"name"  => "date_format",
				"value" => $config['date_format']
            ));
	$FORM->makeinput(array(
				"text"  => "评论时间格式",
				"note"  => "通过这里来改变评论的时间显示格式。<a href=\"http://cn.php.net/manual/zh/function.date.php\" target=\"_blank\">语法说明</a>",
				"name"  => "date_format_remark",
				"value" => $config['date_format_remark']
            ));
	$FORM->makeinput(array(
				"text"  => "留言时间格式",
				"note"  => "通过这里来改变留言的时间显示格式。<a href=\"http://cn.php.net/manual/zh/function.date.php\" target=\"_blank\">语法说明</a>",
				"name"  => "date_format_gb",
				"value" => $config['date_format_gb']
            ));
	$FORM->makeinput(array(
				"text"  => "图片最大宽度",
				"note"  => "图片超过这个宽度后会被加上滚动条。单位:象素(px)",
				"name"  => "max_image_width",
				"value" => $config['max_image_width']
            ));
	$FORM->makeinput(array(
				"text"  => "超级日志管理员",
				"note"  => "只有这里的用户才可以管理全部日志。<br>其他用户只可以管理自己的日志。用户之间用逗号隔开",
				"name"  => "superadmin",
				"value" => $config['superadmin']
            ));
	$FORM->makeselect(array(
				"text"  => "设定模版",
				"note"  => "选择您要使用的模板，更改后请重建一次静态页面",
				"name"  => "template",
				"option" => $dir,
				"selected" => $config['template']
            ));
	$FORM->makeselect(array(
				"text"  => "静态文件扩展名",
				"note"  => "如果您更改了此项，请重建一次静态页面",
				"name"  => "extraname",
				"option" => $extraname,
				"selected" => $config['extraname']
            ));
	$FORM->makeselect(array(
				"text"  => "静态文件的存放方式",
				"note"  => "定义您的静态文件存放方式",
				"name"  => "keep_page_way",
				"option" => $keep_page_way,
				"selected" => $config['keep_page_way']
            ));
	$FORM->makeselect(array(
				"text"  => "服务器所在时区",
				"note"  => "Blog所在的服务器是放在地球的哪个时区",
				"name"  => "servertimezone",
				"option" => fetch_timezone(),
				"selected" => $config['servertimezone']
            ));
	$FORM->makeselect(array(
				"text"  => "访客所在时区",
				"note"  => "这个Blog主要面向哪个时区的用户",
				"name"  => "clienttimezone",
				"option" => fetch_timezone(),
				"selected" => $config['clienttimezone']
            ));
	$FORM->makeyesno(array("text" => "是否开启评论审核",
		"note" => "开启后评论需要在后台审核之后才能显示",
		"name" => "checkremark",
		"selected" => $config['checkremark']));
	$FORM->makeyesno(array("text" => "是否生成静态页",
		"note" => "开启后生成每篇日志的HTML页面",
		"name" => "makehtml",
		"selected" => $config['makehtml']));
	$FORM->makeyesno(array("text" => "是否显示全文",
		"note" => "开启后在日志列表里显示日志全文",
		"name" => "fullarticle",
		"selected" => $config['fullarticle']));
	$FORM->makeyesno(array("text" => "登陆时是否使用验证码",
		"note" => "只有在服务器支持GD时才可以开启",
		"name" => "verify_code",
		"selected" => $config['verify_code'],
		"disable" => $disable));
	$FORM->makeyesno(array("text" => "是否显示阅读次数",
		"note" => "是否显示阅读次数。开启后会影响页面载入速度。",
		"name" => "show_viewcount",
		"selected" => $config['show_viewcount']));
	$FORM->makeyesno(array("text" => "关闭 Blog",
		"note" => "可以暂时关闭您的 Blog",
		"name" => "close_blog",
		"selected" => $config['close_blog']));
	$FORM->maketextarea(array("text" => "关闭Blog原因","note" => "填写您关闭Blog原因，仅当Blog被关闭时才有效。","name" => "close_reason","value" => strip_tags($config['close_reason'])));
	$FORM->formfooter();
}

//执行参数设置
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
		$FORM->ob_exit("不允许使用 .{$extraname} 的扩展名","");
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
			$FORM->ob_exit("抱歉，系统设定出错","");
		}
	}
	if($makehtml) {
		$HTML->makeindex();
	}
	//删除静态首页
	if($makehtml == 0 || $close_blog == 1) {
		if(file_exists("index.html")) {
			@chmod("index.html",0777);
			@unlink("index.html");
		}
	}
	$FORM->ob_exit("恭喜，系统设定完成<br />您需要手动重建静态首页","");
}

//PHPINFO
if($action == "phpinfo") {
	$dis_func = get_cfg_var("disable_functions");
	$phpinfo=(!eregi("phpinfo", $dis_func)) ? phpinfo() : $FORM->ob_exit("phpinfo()函数已被管理员禁用!请用探针查看","");
	exit();
}

//添加日志界面
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
	$nowtime['text'] = "发表时间";
	$nowtime['note'] = "修改日志的发表时间(服务器所在时区)";

	$FORM->formheader(array("title" => "添加日志","action" => "admin.php?action=doaddBlog","name" => "input"));
	$FORM->makeselect(array(
				"text"  => "选择类别",
				"note"  => "",
				"name"  => "class",
				"option" => $class,
            ),0);
	$FORM->makeinput(array(
				"text"  => "标题",
				"note"  => "日志的标题",
				"name"  => "name",
				"value" => ""
            ),0);
	$FORM->editor(array("text" => "内容","note" => "日志的内容，使用UBB格式的编码"));
	if($makehtml) {
		echo "<tr ".$FORM->getrowbg(2)." nowrap>\n";
		echo "	<td><b>静态文件名</b><br>不填将以日志的ID命名</td>\n";
		echo "	<td><input class=\"formfield\" type=\"text\" name=\"filename\" size=\"35\" maxlength=\"50\" value=\"\"> .".$extraname."</td>\n";
		echo "</tr>\n";
	}
	$FORM->makeinput(array(
				"text"  => "Trackback URL",
				"note"  => "Trackback 引用地址，没有请留空",
				"name"  => "trackbackUrl",
				"value" => "",
				"maxlength" => 200,
            ),0);
	$FORM->maketimeinput($nowtime,2);
	
	echo "<tr ".$FORM->getrowbg(1)." nowrap>\n";
	echo "<td><b>附件上传</b><br>请选择您要上传的附件</td>\n";
	echo "<td>";
	echo "<IFRAME src=\"upload.php?action=upload_form\" frameBorder=0 width=\"100%\" scrolling=no height=23  allowTransparency=\"true\" noresize></IFRAME>";
	echo "</td>\n</tr>\n";

	$FORM->makeyesno(array("text" => "置顶",
		"name" => "top",
		"selected" => 0),2);
	$FORM->makeyesno(array("text" => "评论",
		"name" => "remark",
		"selected" => 1),1);
	$FORM->makeyesno(array("text" => "表情",
		"name" => "allow_face",
		"selected" => 1),2);
	$FORM->makeyesno(array("text" => "草稿",
		"name" => "draft",
		"selected" => 0),1);
	
	$FORM->formfooter();
}

//执行添加日志
if($action == "doaddBlog") {
	if(file_exists("admin/class/pinyin.php")) {
		require_once("admin/class/pinyin.php");
	} elseif(file_exists("class/pinyin.php")) {
		require_once("class/pinyin.php");
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/pinyin.php")) {
		require_once(dirname($_SERVER['PHP_SELF'])."/class/pinyin.php");
	} else {
		$FORM->ob_exit("无法找到文件 ./admin/class/pinyin.php");
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
		$FORM->ob_exit("标题还没有填写呢","");
	}
	if(empty($content)) {
		$FORM->ob_exit("内容还没有填写呢","");
	}
	
	//验证时间
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
		$FORM->ob_exit("时间填写错误!","");
	}
	$date = mktime($nowtime['hour'],$nowtime['minute'],$nowtime['second'],$nowtime['month'],$nowtime['day'],$nowtime['year']);
	$userid = @intval($_COOKIE['ob_userid']);
	$author = @$DB->fetch_one("SELECT `nickname` FROM {$mysql_prefix}admin WHERE `id`={$userid}");
	$author = ($author == '') ? "unknow" : $author;
	
	//入库
	if($DB->query("INSERT INTO `".$mysql_prefix."blog` (date,title,content,trackbackurl,filename,author,classid,top,allow_remark,allow_face,draft) VALUES ('".$date."','".$title."','".$content."','".$trackbackUrl."','".$filename."','".$author."','".$classid."','".$top."','".$allow_remark."','".$allow_face."','".$draft."')")) {
		$insert_id = $DB->insert_id();
		//生成HTML页面
		if($makehtml  && $draft!=1) {
			$HTML->makeindex();
			$HTML->make($insert_id);
		}
		//发送 trackback ping
		if($trackbackUrl != "") {
			$tb_url = $blogurl.getHtmlPath($insert_id);
			if(!ping($trackbackUrl,$title,$tb_url,$content,$tb_name)) {
				$FORM->ob_exit("日志添加成功<br>Trackback Ping 发送失败","");
			}
		}
		$FORM->ob_exit("恭喜，日志添加成功","admin.php?action=editBlog");
	} else {
		$FORM->ob_exit("抱歉，日志添加失败","");
	}
}

//编辑日志-列表界面
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
			$FORM->ob_exit("没有找到符合搜索条件的日志");
		}
	} else {
		$current_userinfo = $DB->fetch_one_array("SELECT username,nickname FROM {$mysql_prefix}admin WHERE id='".intval($_COOKIE['ob_userid'])."'");
		$current_username = $current_userinfo['username'];
		$current_nickname = $current_userinfo['nickname'];
		if(strstr($superadmin,$current_username)) {
			//超级管理员
			$is_superadmin = 1;
			$sql_num = "SELECT count(*) FROM `".$mysql_prefix."blog`";
			$allNum = $DB->fetch_one($sql_num);
			$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$page_char = page($allNum,$mun_in_page,$cpage,"admin.php?action=editBlog");
			$startI = $cpage*$mun_in_page-$mun_in_page;
			$sql_list = "SELECT * FROM `".$mysql_prefix."blog` ORDER BY `date` DESC LIMIT ".$startI.",{$mun_in_page}";
		} else {
			//普通管理员
			$sql_num = "SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `author`='{$current_nickname}'";
			$allNum = $DB->fetch_one($sql_num);
			$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$page_char = page($allNum,$mun_in_page,$cpage,"admin.php?action=editBlog");
			$startI = $cpage*$mun_in_page-$mun_in_page;
			$sql_list = "SELECT * FROM `".$mysql_prefix."blog` WHERE `author`='{$current_nickname}' ORDER BY `date` DESC LIMIT ".$startI.",{$mun_in_page}";
		}
		
		if($allNum == 0) {
			$FORM->ob_exit("目前还没有日志");
		}
		
	}
	$blogs = $DB->query($sql_list);
	
	if(isset($keyword) && $keyword!= '') {
		$formtitle = "搜索结果 [共".$allNum."条记录]";
	} else {
		$formtitle = "全部日志 [共".$allNum."条记录] [20条/页]";
	}
	
	$FORM->js_checkall();
	$FORM->if_del();
	$FORM->formheader(array("action" => "admin.php?action=dosomeBlog",
		"title" => $formtitle,
		"colspan" => "7",
		"name" => "form",
	));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>置顶</b></td>\n";
	echo "<td width=\"9%\"><b>允许评论</b></td>\n";
	echo "<td width=\"26%\"><b>日志标题</b></td>\n";
	echo "<td width=\"14%\"><b>所属分类</b></td>\n";
	echo "<td width=\"16%\"><b>添加时间</b></td>\n";
	echo "<td width=\"22%\"><b>日志操作</b></td>\n";
	echo "<td width=\"5%\">\n";
	echo "<input type=\"checkbox\" name=\"chkall\" value=\"on\" class=\"nonebg\" onclick=\"CheckAll(this.form)\"></td>\n";
	echo "</tr>\n";
	while($blog = $DB->fetch_array($blogs)) {
		$top = ($blog['top'] == 1) ? "Yes" : "No";
		$allow_remark = ($blog['allow_remark'] == 1) ? "Yes" : "No";
		$remarkNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark` WHERE `inblog` = ".$blog['id']);
		$trackbackNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."trackback` WHERE `inblog` = ".$blog['id']);
		$classname = $DB->fetch_one("SELECT `classname` FROM `".$mysql_prefix."class` WHERE `id` =".$blog['classid']);
		$draft_char = ($blog['draft']) ? "<font color=\"red\">[草稿]</font> " : "";
		$rtNum = $trackbackNum + $remarkNum;
		$blog_path = $blogurl.getHtmlPath($blog['id']);
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td>".$top."</td>\n";
		echo "<td>".$allow_remark." [".$rtNum."]</td>\n";
		echo "<td align=\"left\"><a href=\"{$blog_path}\" target=\"_blank\">".$draft_char.trim($blog['title'])."</a></td>\n";
		echo "<td>".$classname."</td>\n"; 
		echo "<td>".obdate("y-m-d H:m:s",$blog['date'])."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modBlog&id=".$blog['id']."\">编辑</a>] [<a href=\"#\"  onclick=\"ifDel('admin.php?action=delBlog&id=".$blog['id']."')\">删除</a>] [<a href=\"admin.php?action=buildBlog&id=".$blog['id']."\">生成</a>] <br> [<a href=\"admin.php?action=remarkManager&id=".$blog['id']."\">评论</a>] [<a href=\"admin.php?action=editTrackback&id=".$blog['id']."\">引用</a>]</td>\n";
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
	echo "<label for=\"top\"><input type=\"radio\" name=\"editall\" id=\"top\" value=\"top\" class=\"graybg\" onclick=\"{$disable_char}\">置顶</label> ";
	echo "<label for=\"ctop\"><input type=\"radio\" name=\"editall\" id=\"ctop\" value=\"ctop\" class=\"graybg\" onclick=\"{$disable_char}\">取消置顶</label> ";
	echo "<label for=\"del\"><input type=\"radio\" name=\"editall\" id=\"del\" value=\"del\" class=\"graybg\" onclick=\"{$disable_char}\">删除</label> ";
	echo "<label for=\"move\"><input type=\"radio\" name=\"editall\" id=\"move\" value=\"move\" class=\"graybg\" onclick=\"{$disable_char2}\">移动</label> ";
	echo "<select name=\"classid\" disabled=\"ture\">";
	foreach($class as $key=>$val) {
		echo "<option value=".$key.">".$val."</option>";
	}
	echo "</select>";
	
	if($is_superadmin) {
		echo "<label for=\"author\"><input type=\"radio\" name=\"editall\" id=\"author\" value=\"author\" class=\"graybg\" onclick=\"authors.disabled=false;classid.disabled=true\">指派作者</label> ";
		echo "<select name=\"authors\" disabled=\"ture\">";
		foreach($authors as $key=>$val) {
			echo "<option value=".$val.">".$val."</option>";
		}
		echo "</select>";
	}
	echo "</td></tr>\n";
	$FORM->formfooter(array("colspan" => "7"));

	//搜索日志表单
	echo "\n\n<br><form action=\"admin.php?action=editBlog\" method=\"post\"><table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\"><tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" align=\"center\">\n<b>搜索日志:&nbsp;&nbsp;<b>\n";
	echo "\n<select name=\"searchwhere\"><option value=\"title\">标题</option><option value=\"content\">内容</option></select>\n<input type=\"text\" id=\"keyword\" name=\"keyword\" size=\"30\">\n\n";
	echo "&nbsp;&nbsp;<input type=\"submit\" value=\"搜索\"></td>\n";
	echo "</table></form>\n";
}

//修改一篇日志界面
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
	$nowtime['text'] = "时间";
	$nowtime['note'] = "修改日志的发表时间(服务器所在时区)";

	$FORM->formheader(array("title" => "编辑日志","action" => "admin.php?action=domodBlog","name" => "input"));
	$FORM->makeselect(array(
				"text"  => "选择类别",
				"note"  => "",
				"name"  => "class",
				"option" => $class,
				"selected" => $blogs['classid']
            ),0);
	$FORM->makeinput(array(
				"text"  => "标题",
				"note"  => "日志的标题",
				"name"  => "name",
				"value" => $blogs['title']
            ),0);
	$FORM->editor(array("text" => "内容","note" => "日志的内容，使用UBB格式的编码","value" => $blogs['content']));
	if($makehtml) {
		echo "<tr ".$FORM->getrowbg(2)." nowrap>\n";
		echo "	<td><b>静态文件名</b><br>不填将以日志的ID命名</td>\n";
		echo "	<td><input class=\"formfield\" type=\"text\" name=\"filename\" size=\"35\" maxlength=\"50\" value=\"".$blogs['filename']."\"> .".$extraname."</td>\n";
		echo "</tr>\n";
	}
	$FORM->makeinput(array(
				"text"  => "Trackback URL",
				"note"  => "Trackback 引用地址，没有请留空",
				"name"  => "trackbackUrl",
				"value" => $blogs['trackbackurl'],
				"maxlength" => 200,
				"otherelement" => " <label for=\"resend_tb\"><input type=\"checkbox\" name=\"resend_tb\" id=\"resend_tb\" value=\"resend_tb\" class=\"nonebg\" /> 再次发送?</label>",
            ),0);
	$FORM->makehidden(array("name" => "oldTrackbackUrl","value" => $blogs['trackbackurl']));
	$FORM->maketimeinput($nowtime,2);

	echo "<tr ".$FORM->getrowbg(1)." nowrap>\n";
	echo "<td><b>附件上传</b><br>请选择您要上传的附件</td>\n";
	echo "<td>";
	echo "<IFRAME src=\"upload.php?action=upload_form\" frameBorder=0 width=\"100%\" scrolling=no height=23  allowTransparency=\"true\" noresize></IFRAME>";
	echo "</td>\n</tr>\n";
	
	$FORM->makeyesno(array("text" => "置顶",
		"name" => "top",
		"selected" => $blogs['top']),2);
	$FORM->makeyesno(array("text" => "评论",
		"name" => "remark",
		"selected" => $blogs['allow_remark']),1);
	$FORM->makeyesno(array("text" => "表情",
		"name" => "allow_face",
		"selected" => $blogs['allow_face']),2);
	$FORM->makeyesno(array("text" => "草稿",
		"name" => "draft",
		"selected" => $blogs['draft']),1);
	$FORM->makehidden(array("name" => "id","value" => $blogs['id']));
	$FORM->formfooter();
}

//执行修改一篇日志
if($action == "domodBlog") {
	if(file_exists("admin/class/pinyin.php")) {
		require_once("admin/class/pinyin.php");
	} elseif(file_exists("class/pinyin.php")) {
		require_once("class/pinyin.php");
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/pinyin.php")) {
		require_once(dirname($_SERVER['PHP_SELF'])."/class/pinyin.php");
	} else {
		$FORM->ob_exit("无法找到文件 ./admin/class/pinyin.php");
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
		$FORM->ob_exit("标题还没有填写呢","");
	}
	if(empty($content)) {
		$FORM->ob_exit("内容还没有填写呢","");
	}

	//验证时间
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
		$FORM->ob_exit("时间填写错误!","");
	}
	$date = mktime($nowtime['hour'],$nowtime['minute'],$nowtime['second'],$nowtime['month'],$nowtime['day'],$nowtime['year']);
	
	$updateSql = "UPDATE `".$mysql_prefix."blog` SET `classid` = '".$classid."',`title` = '".$title."',`content` = '".$content."',`trackbackurl` = '".$trackbackUrl."',`filename` = '".$filename."',`date` = '".$date."',`top` = '".$top."',`allow_remark` = '".$allow_remark."',`allow_face` = '".$allow_face."',`draft` = '".$draft."' WHERE `id` = ".$id;
	if($DB->query($updateSql)) {
		//生成HTML页面
		if($makehtml && $draft!=1) {
			$HTML->makeindex();
			$HTML->make($id);
		}
		//发送 trackback ping
		if($trackbackUrl != "" AND trim($_POST['resend_tb']) == 'resend_tb') {
			$tb_url = $blogurl.getHtmlPath($id);
			if(!ping($trackbackUrl,$title,$tb_url,$content,$tb_name)) {
				$FORM->ob_exit("日志添加成功<br>Trackback Ping 发送失败","");
			}
		}
		$FORM->ob_exit("恭喜，编辑日志成功","admin.php?action=editBlog");
	} else {
		$FORM->ob_exit("抱歉，编辑日志失败","");
	}
}

//删除一篇日志
if($action == "delBlog") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."blog` WHERE `id`=".$id)) {
		if($DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `inblog`=".$id)) {
			if($DB->query("DELETE FROM `".$mysql_prefix."trackback` WHERE `inblog`=".$id)) {
				if($makehtml) {
					$HTML->del($id);
					$HTML->makeindex();
				}
				$FORM->ob_exit("删除日志成功","");
			} else {
				$FORM->ob_exit("删除 Trackback Pings 失败","");
			}
		} else {
			$FORM->ob_exit("删除评论失败","");
		}
	} else {
		$FORM->ob_exit("删除日志失败","");
	}
}

//生成一篇日志
if($action == "buildBlog") {
	$id = intval($_GET['id']);
	if($HTML->make($id)) {
		$FORM->ob_exit("生成日志成功");
	}
}

//评论管理界面
if($action == "remarkManager") {
	if(isset($_GET['id'])) {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark` WHERE `inblog` = ".intval($_GET['id']));
		if($allNum == 0) {
			$FORM->ob_exit("当前日志没有评论","");
		}
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=remarkManager&id=".intval($_GET['id']));
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."remark` WHERE `inblog` = ".intval($_GET['id'])." ORDER BY `id` DESC LIMIT ".$startI.",20";
		$formtitle = "评论管理 [共有{$allNum}篇评论] [20条/页]";
	} elseif(isset($_POST['keyword']) && trim($_POST['keyword']) != '') {
		$keyword = checkPost(trim($_POST['keyword']));
		$searchwhere = checkPost(trim($_POST['searchwhere']));
		$keyword = str_replace("_","\_",$keyword);
		$keyword = str_replace("%","\%",$keyword);
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark` WHERE `{$searchwhere}` LIKE  '%{$keyword}%'");
		$listSql = "SELECT * FROM `".$mysql_prefix."remark` WHERE `{$searchwhere}` LIKE '%{$keyword}%' ORDER BY `id` DESC";
		$formtitle = "搜索结果 [共有{$allNum}篇评论]";
		if($allNum == 0) {
			$FORM->ob_exit("没有找到符合搜索条件的评论","");
		}
	} else {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."remark`");
		if($allNum == 0) {
			$FORM->ob_exit("目前还没有评论","");
		}
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=remarkManager");
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."remark` ORDER BY `id` DESC LIMIT ".$startI.",20";
		$formtitle = "评论管理 [共有{$allNum}篇评论] [20条/页]";
	}
	$FORM->js_checkall();
	$FORM->if_del();
	$FORM->formheader(array("title" => $formtitle,"colspan" => "5","action" => "admin.php?action=dosomeRemark"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>审核</b></td>\n";
	echo "<td width=\"30%\"><b>评论人</b></td>\n";
	echo "<td width=\"40%\"><b>内容</b></td>\n";
	echo "<td width=\"20%\"><b>操作</b></td>\n";
	echo "<td width=\"5%\"><input type=\"checkbox\" name=\"chkall\" value=\"on\" class=\"nonebg\" onclick=\"CheckAll(this.form)\"></td>\n";
	echo "</tr>\n";
	$remarks = $DB->query($listSql);
	while($remarkRe = $DB->fetch_array($remarks)) {
		$remarkRe['check'] = ($remarkRe['ischeck'] == 1) ? "是" : "否";
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$remarkRe['check']."</td>\n";
		echo "<td align=\"left\" valign=\"top\"><b>昵称: </b>".$remarkRe['username']."<br><b>E-mail: </b>".$remarkRe['email']."<br><b>IP: </b>".$remarkRe['ip']."</td>\n";
		echo "<td align=\"left\" valign=\"top\">".$remarkRe['content']."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modRemark&id=".$remarkRe['id']."\">编辑</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delRemark&id=".$remarkRe['id']."')\">删除</a>]<br> [<a href=\"admin.php?action=checkRemark&id=".$remarkRe['id']."\">审核</a>] [<a href=\"admin.php?action=banRemark&id=".$remarkRe['id']."\">封锁</a>]</td>\n";
		echo "<td><input type=\"checkbox\" name=\"remark[".$remarkRe['id']."]\" value=\"1\" class=\"nonebg\"></td>\n";
		echo "</tr>\n";
	}

	echo "<tr class=\"secondalt\">";
	echo "<td colspan=\"7\" align=\"center\">\n";
	echo "<label for=\"check\"><input type=\"radio\" name=\"editall\" id=\"check\" value=\"check\" class=\"nonebg\">审核</label> ";
	echo "<label for=\"uncheck\"><input type=\"radio\" name=\"editall\" id=\"uncheck\" value=\"uncheck\" class=\"nonebg\">封锁</label> ";
	echo "<label for=\"del\"><input type=\"radio\" name=\"editall\" id=\"del\" value=\"del\" class=\"nonebg\">删除</label> ";
	echo "</td></tr>\n";

	$FORM->makepage($page_char,5,2);
	$FORM->formfooter(array("colspan" => 5));

	//搜索评论表单
	echo "\n\n<br><form action=\"admin.php?action=remarkManager\" method=\"post\"><table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\"><tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" align=\"center\">\n<b>搜索评论:&nbsp;&nbsp;<b>\n";
	echo "\n<select name=\"searchwhere\"><option value=\"content\">内容</option><option value=\"username\">昵称</option></select>\n<input type=\"text\" id=\"keyword\" name=\"keyword\" size=\"30\">\n\n";
	echo "&nbsp;&nbsp;<input type=\"submit\" value=\"搜索\"></td>\n";
	echo "</table></form>\n";
}

//批量操作评论
if($action == "dosomeRemark") {
	$dowhat = checkPost(trim($_POST['editall']));
	if($dowhat == "") {
		$FORM->ob_exit("请选择一个要执行的操作","");
	}
	$remarks = checkPost($_POST['remark']);
	if(count($remarks) == 0) {
		$FORM->ob_exit("请选择要执行操作的评论","");
	}
	//审核
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
		$FORM->ob_exit("审核评论完成","");
	}
	//封锁
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
		$FORM->ob_exit("封锁评论完成","");
	}
	//删除
	if($dowhat == "del") {	
		foreach($remarks as $key=>$val) {
			$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id` = ".$key);
			if($DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `id` = ".$key)) {
				if($makehtml) {
					$HTML->make($blogid);
				}
			} else {
				$FORM->ob_exit("删除评论失败","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("删除评论完成","");
	}
}

//删除一个评论
if($action == "delRemark") {
	$id = intval($_GET['id']);
	$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id`=".$id);
	if($DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `id`=".$id)) {
		if($makehtml) {
			$HTML->makeindex();
			$HTML->make($blogid);
		}
		$FORM->ob_exit("删除评论成功","");
	} else {
		$FORM->ob_exit("删除评论失败","");
	}
}

//通过审核一个评论
if($action == "checkRemark") {
	$id = intval($_GET['id']);
	$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id`=".$id);
	if($DB->query("UPDATE `".$mysql_prefix."remark` SET `ischeck` =  1 WHERE `id`=".$id)) {
		if($makehtml) {
			$HTML->makeindex();
			$HTML->make($blogid);
		}
		$FORM->ob_exit("评论已审核","");
	} else {
		$FORM->ob_exit("评论审核失败","");
	}
}

//封锁一个评论
if($action == "banRemark") {
	$id = intval($_GET['id']);
	$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id`=".$id);
	if($DB->query("UPDATE `".$mysql_prefix."remark` SET `ischeck` =  0 WHERE `id`=".$id)) {
		if($makehtml) {
			$HTML->makeindex();
			$HTML->make($blogid);
		}
		$FORM->ob_exit("评论已封锁","");
	} else {
		$FORM->ob_exit("评论封锁失败","");
	}
}

//编辑评论界面
if($action == "modRemark") {
	$remark = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."remark` WHERE `id` =".intval($_GET['id']));
	$FORM->formheader(array("title" => "编辑评论","action" => "admin.php?action=domodRemark","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "昵称",
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
	$FORM->maketextarea(array("text" => "内容","name" => "content","value" => strip_tags($remark['content'])));
	$FORM->makehidden(array("name" => "id", "value" => $remark['id']));
	$FORM->formfooter();
}

//执行编辑评论
if($action == "domodRemark") {
	$name = checkPost(trim($_POST['name']));
	$email = checkPost(trim($_POST['email']));
	$content = nl2br(htmlspecialchars(trim($_POST['content'])));
	$id = intval($_POST['id']);
	if(empty($name)) {
		$FORM->ob_exit("昵称还没有填写呢","");
	}
	if(empty($content)) {
		$FORM->ob_exit("内容还没有填写呢","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."remark` SET `username` = '".$name."', `email` = '".$email."', `content` = '".$content."' WHERE `id` = ".$id)) {
		$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."remark` WHERE `id`=".$id);
		if($makehtml) {
			$HTML->make($blogid);
		}
		$FORM->ob_exit("编辑评论成功","");
	} else {
		$FORM->ob_exit("编辑评论失败","");
	}
}

//批量操作日志
if($action == "dosomeBlog") {
	$dowhat = checkPost(trim($_POST['editall']));
	if($dowhat == "") {
		$FORM->ob_exit("请选择一个要执行的操作","");
	}
	$blogs = checkPost($_POST['blog']);
	if(count($blogs) == 0) {
		$FORM->ob_exit("请选择要执行操作的日志","");
	}
	//置顶
	if($dowhat == "top") {
		foreach($blogs as $key=>$val) {
			if(!$DB->query("UPDATE `".$mysql_prefix."blog` SET `top` = 1 WHERE `id` = ".$key)) {
				$FORM->ob_exit("置顶操作发生错误","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("日志置顶成功","");
	}
	//取消置顶
	if($dowhat == "ctop") {	
		foreach($blogs as $key=>$val) {
			if(!$DB->query("UPDATE `".$mysql_prefix."blog` SET `top` = 0 WHERE `id` = ".$key)) {
				$FORM->ob_exit("取消置顶操作发生错误","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("日志取消置顶成功","");
	}
	//删除
	if($dowhat == "del") {	
		foreach($blogs as $key=>$val) {
			if($makehtml) {
				$HTML->del($key);
			}
			if(!$DB->query("DELETE FROM `".$mysql_prefix."blog` WHERE `id` = ".$key)) {
				$FORM->ob_exit("删除日志发生错误","");
			}
			if(!$DB->query("DELETE FROM `".$mysql_prefix."remark` WHERE `inblog`=".$key)) {
				$FORM->ob_exit("删除评论发生错误","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("日志删除成功","");
	}
	//移动
	if($dowhat == "move") {
		foreach($blogs as $key=>$val) {
			if(!$DB->query("UPDATE `".$mysql_prefix."blog` SET `classid` = '".intval($_POST['classid'])."' WHERE `id` = ".$key)) {
				$FORM->ob_exit("移动日志发生错误","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("日志移动成功","");
	}
	//指派作者
	if($dowhat == "author") {
		foreach($blogs as $key=>$val) {
			if(!$DB->query("UPDATE `".$mysql_prefix."blog` SET `author` = '".checkPost(trim($_POST['authors']))."' WHERE `id` = ".$key)) {
				$FORM->ob_exit("指派作者发生错误","");
			}
		}
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("指派作者成功","");
	}
}

//添加分类界面
if($action == "addSort") {
	$default_order = $DB->fetch_one("SELECT max(showorder) FROM {$mysql_prefix}class") + 1;
	$FORM->formheader(array("title" => "添加分类","action" => "admin.php?action=doaddSort","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "排序",
				"note"  => "分类排序号，数字越小排序越靠前",
				"name"  => "order",
				"value"  => $default_order,
            ),0);
	$FORM->makeinput(array(
				"text"  => "名称",
				"note"  => "分类名称",
				"name"  => "name",
				"value"  => "",
            ),0);
	$FORM->formfooter();
}

//执行添加分类
if($action == "doaddSort") {
	if(!is_numeric($_POST['order'])) {
		$FORM->ob_exit("抱歉，您输入的排序号似乎不是数字");
	}
	$order = intval($_POST['order']);
	$name = htmlspecialchars(checkPost(trim($_POST['name'])));
	if(empty($name)) {
		$FORM->ob_exit("名称还没有填写呢","");
	}
	if($DB->query("INSERT INTO `".$mysql_prefix."class` (`classname`,`showorder`) VALUES ('".$name."', '".$order."')")) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("恭喜，分类添加成功","");
	} else {
		$FORM->ob_exit("抱歉，分类添加失败","");
	}
}

//显示编辑分类列表
if($action == "editSort") {
	$sortNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."class`");
	$FORM->if_del();
	$FORM->formheader(array("title" => "分类列表 [共有".$sortNum."个分类]","colspan" => "4","action" => "admin.php?action=dosomeSort"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"10%\"><b>排序</b></td>\n";
	echo "<td width=\"60%\"><b>分类名称</b></td>\n";
	echo "<td width=\"10%\"><b>文章数量</b></td>\n";
	echo "<td width=\"20%\"><b>操作</b></td>\n";
	echo "</tr>\n";
	$sorts = $DB->query("SELECT * FROM `".$mysql_prefix."class` ORDER BY `id` ASC");
	while($sort = $DB->fetch_array($sorts)) {
		$blogNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."blog` WHERE `classid`=".$sort['id']);
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td><input type=\"text\" name=\"sort[".$sort['id']."]\" value=\"".$sort['showorder']."\" size=\"2\"  class=\"formfield\"></td>";
		echo "<td>".$sort['classname']."</td>";
		echo "<td>".$blogNum."</td>";
		echo "<td>[<a href=\"admin.php?action=modSort&id=".$sort['id']."\">编辑</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delSort&id=".$sort['id']."')\">删除</a>]</td>";
		echo "</tr>";
	}
	$FORM->formfooter(array("colspan"=>'4',"button" =>array("submit"=>array("value"=>"更新排序"))));
}

//删除分类
if($action == "delSort") {
	$id = intval($_GET['id']);
	//删除分类下文章的评论和 Trackback Pings
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
			$FORM->ob_exit("删除分类成功","");
		} else {
			$FORM->ob_exit("无法删除分类下的文章","");
		}
	} else {
		$FORM->ob_exit("删除分类失败","");
	}
}

//修改分类
if($action == "modSort") {
	$id = intval($_GET['id']);
	$sort = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."class` WHERE `id` = ".$id);
	$FORM->formheader(array("title" => "编辑分类","action" => "admin.php?action=domodSort","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "排序",
				"note"  => "分类排序号，数字越小排序越靠前",
				"name"  => "order",
				"value"  => $sort['showorder'],
            ),0);
	$FORM->makeinput(array(
				"text"  => "名称",
				"note"  => "分类名称",
				"name"  => "name",
				"value"  => $sort['classname'],
            ),0);
	$FORM->makehidden(array("name" => "id","value" => $id));
	$FORM->formfooter();
}

//执行修改分类
if($action == "domodSort") {
	if(!is_numeric($_POST['order'])) {
		$FORM->ob_exit("抱歉，您输入的排序号似乎不是数字");
	}
	$id = intval($_POST['id']);
	$order = intval($_POST['order']);
	$name = htmlspecialchars(checkPost(trim($_POST['name'])));
	if(empty($name)) {
		$FORM->ob_exit("名称还没有填写呢");
	}
	if($DB->query("UPDATE `".$mysql_prefix."class` SET `showorder` = '".$order."', `classname` = '".$name."' WHERE `id` = ".$id)) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("编辑分类成功","admin.php?action=editSort");
	} else {
		$FORM->ob_exit("编辑分类失败","");
	}
}

//排序分类
if($action == "dosomeSort") {
	$sort = checkPost($_POST['sort']);
	foreach($sort as $key=>$val) {
		$DB->query("UPDATE `".$mysql_prefix."class` SET `showorder` = '".$val."' WHERE `id` = $key");
	}
	if($makehtml) {
			$HTML->makeindex();
	}
	$FORM->ob_exit("分类排序已经更新","");
}

//添加链接界面
if($action == "addLink") {
	$default_order = $DB->fetch_one("SELECT max(showorder) FROM {$mysql_prefix}link") + 1;
	$FORM->formheader(array("title" => "添加链接","action" => "admin.php?action=doaddLink","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "网站名称",
				"note"  => "",
				"name"  => "name",
				"value" => ""
            ),0);
	$FORM->makeinput(array(
				"text"  => "网站地址",
				"note"  => "",
				"name"  => "url",
				"value" => ""
            ),0);
	$FORM->maketextarea(array("text" => "网站描述","name" => "alt","value" => ""));
	$FORM->makeinput(array(
				"text"  => "链接排序",
				"note"  => "",
				"name"  => "order",
				"value" => $default_order
            ),0);
	$FORM->makeyesno(array("text" => "隐藏",
		"note" => "",
		"name" => "linkhidden",
		"selected" => 0));
	$FORM->formfooter();
}

//执行添加链接
if($action == "doaddLink") {
	$name = htmlspecialchars(checkPost(trim($_POST['name'])));
	$url = htmlspecialchars(checkPost(trim($_POST['url'])));
	$alt = htmlspecialchars(checkPost(trim($_POST['alt'])));
	$order = intval($_POST['order']);
	$linkhidden = intval($linkhidden);
	if(empty($name)) {
		$FORM->ob_exit("网站名称还没有填写呢","");
	}
	if(empty($url)) {
		$FORM->ob_exit("网站地址还没有填写呢","");
	}
	if($DB->query("INSERT INTO `".$mysql_prefix."link` (`sitename`,`linkurl`,`alt`,`showorder`,`linkhidden`) VALUES ('".$name."','".$url."','".$alt."','".$order."','".$linkhidden."')")) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("链接添加成功","");
	} else {
		$FORM->ob_exit("链接添加失败","");
	}
}

//编辑链接列表
if($action == "editLink") {
	$linkNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."link`");
	$FORM->if_del();
	$FORM->formheader(array("title" => "编辑链接 [共有".$linkNum."个链接]","colspan" => "5","action" => "admin.php?action=dosomeLink"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"10%\"><b>排序</b></td>\n";
	echo "<td width=\"5%\"><b>隐藏</b></td>\n";
	echo "<td width=\"30%\"><b>网站名称</b></td>\n";
	echo "<td width=\"40%\"><b>网站地址</b></td>\n";
	echo "<td width=\"15%\"><b>操作</b></td>\n";
	echo "</tr>\n";
	$links = $DB->query("SELECT * FROM `".$mysql_prefix."link` ORDER BY `showorder` ASC");
	while($link = $DB->fetch_array($links)) {
		$linkhidden_char = ($link['linkhidden']) ? "是" : "否";
		echo "<tr ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\"><input type=\"text\" name=\"link[".$link['id']."]\" value=\"".$link['showorder']."\" size=\"2\"  class=\"formfield\"></td>";
		echo "<td align=\"center\">".$linkhidden_char."</td>\n";
		echo "<td align=\"center\">".$link['sitename']."</td>\n";
		echo "<td>".$link['linkurl']."</td>\n";
		echo "<td align=\"center\">[<a href=\"admin.php?action=modLink&id=".$link['id']."\">编辑</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delLink&id=".$link['id']."')\">删除</a>]</td>\n";
		echo "</tr>\n";
	}
	$FORM->formfooter(array("colspan"=>'5',"button" =>array("submit"=>array("value"=>"更新排序"))));
}

//删除链接
if($action == "delLink") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."link` WHERE `id`=".$id)) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("链接删除成功","");
	} else {
		$FORM->ob_exit("链接删除失败","");
	}
}

//编辑一个链接界面
if($action == "modLink") {
	$id = intval($_GET['id']);
	$link = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."link` WHERE `id` = ".$id);
	$FORM->formheader(array("title" => "编辑链接","action" => "admin.php?action=domodLink","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "网站名称",
				"note"  => "",
				"name"  => "name",
				"value" => $link['sitename']
            ),0);
	$FORM->makeinput(array(
				"text"  => "网站地址",
				"note"  => "",
				"name"  => "url",
				"value" => $link['linkurl']
            ),0);
	$FORM->maketextarea(array("text" => "网站描述","name" => "alt","value" => $link['alt']));
	$FORM->makeinput(array(
				"text"  => "链接排序",
				"note"  => "",
				"name"  => "order",
				"value" => $link['showorder']
            ),0);
	$FORM->makeyesno(array("text" => "隐藏",
		"note" => "",
		"name" => "linkhidden",
		"selected" => $link['linkhidden']));
	$FORM->makehidden(array("name" => "id", "value" => $id));
	$FORM->formfooter();
}

//执行编辑连接
if($action == "domodLink") {
	$id = intval($_POST['id']);
	$name = htmlspecialchars(checkPost(trim($_POST['name'])));
	$url = htmlspecialchars(checkPost(trim($_POST['url'])));
	$alt = htmlspecialchars(checkPost(trim($_POST['alt'])));
	$order = intval($_POST['order']);
	$linkhidden = intval($_POST['linkhidden']);
	if(empty($name)) {
		$FORM->ob_exit("网站名称还没有填写呢","");
	}
	if(empty($url)) {
		$FORM->ob_exit("网站地址还没有填写呢","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."link` SET `sitename` = '".$name."',`linkurl` = '".$url."',`alt` = '".$alt."',`showorder` = '".$order."',`linkhidden` = '".$linkhidden."' WHERE `id` = ".$id)) {
		if($makehtml) {
			$HTML->makeindex();
		}
		$FORM->ob_exit("链接编辑成功","admin.php?action=editLink");
	} else {
		$FORM->ob_exit("链接编辑失败","");
	}
}

//更新连接排序
if($action == "dosomeLink") {
	$link = checkPost($_POST['link']);
	foreach($link as $key=>$val) {
		$DB->query("UPDATE `".$mysql_prefix."link` SET `showorder` = '".$val."' WHERE `id` = '".$key."'");
	}
	if($makehtml) {
		$HTML->makeindex();
	}
	$FORM->ob_exit("链接排序更新完成","");
}

//备份数据库界面
if($action == "bak") {
	$FORM->formheader(array("title"=> "备份数据库,请选择要备份的表","action" => "admin.php?action=dobak"));
	$tables = mysql_list_tables($mysql_dbname);
	while ($table = $DB->fetch_row($tables)) {
		$cachetables[$table[0]]   = $table[0];
		$tableselected[$table[0]] = 1;
    }
	$DB->free_result($tables);
    $FORM->makeselect(array(
		"text" => "请选择表:",
        "name" => "table[]",
        "option" => $cachetables,
        "selected" => $tableselected,
        "multiple" => 1,
        "size" => 12
    ));
	echo "<tr class=\"secondalt\" nowrap>";
	echo "<td><b>备份方式:</b><br>选择您需要的备份方式</td>";
	echo "<td><label for=\"server\"><input type=\"radio\" name=\"saveto\" id=\"server\" value=\"server\" onclick=\"this.form.path.disabled=false\"  class=\"nonebg\" checked> 备份到服务器</label>";
	echo "<label for=\"local\"><input type=\"radio\" name=\"saveto\" id=\"local\" value=\"local\" onclick=\"this.form.path.disabled=true\"  class=\"nonebg\"> 备份到本地</label></td>";
	echo "</tr>";
	$FORM->makeinput(array(
		"text" => "备份数据到:",
		"note" => "请确保备份文件夹的属性是777",
        "name" => "path",
		"size" => 70,
        "value" => "../bak/o-blog".obdate("Ymd",time())."_".M_random(8).".sql"
	),1);	

	$FORM->formfooter();
}

//备份数据库
if($action == "dobak") {
	if(trim($_POST['saveto']) == 'server') {
		//备份到服务器
		chdir("admin");
		$path = trim($_POST['path']);
		if (file_exists($path)) {
			$FORM->ob_exit("抱歉,文件已经存在,请选择其他文件名.", "");
		}
		if (!is_array($_POST['table']) OR empty($_POST['table'])) {
			$FORM->ob_exit("还未选中任何要${text}的表", "");
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
			$FORM->ob_exit("数据库已经备份到: $path<br>", "");
		} else {
			$FORM->ob_exit("备份生成文件的扩展名必须为.sql", "");
		}
	} else {
		//备份到本地
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


//恢复数据库 - 界面
if($action == "bakManager") {
	chdir("admin");
	$dir = "bak";
	$FORM->js_checkall();
	$FORM->if_import();
    $FORM->formheader(array(
		"title"   => "恢复数据库",
		"action"  => "admin.php?action=delFile",
		"colspan" => "4"
	));
	echo "<tr><td class=\"tblhead\" colspan=\"4\"><b>注意：</b>导入不正确的数据库文件时，有可能毁坏原来的数据。因此建议您在恢复数据时，先备份一次现有的数据。</td></tr>";
	echo "<tr bgcolor=\"#F3F3F3\">\n";
	echo "<td align=\"center\"><b>文件名</b></td>\n";
	echo "<td align=\"center\"><b>文件大小</b></td>\n";
	echo "<td align=\"center\"><b>导入</b></td>\n";
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
			echo "<td align=\"center\">[<a href=\"#\" onclick=\"ifImport('admin.php?action=import&amp;filename={$file}')\" >导入</a>]</td>";
			echo "<td align=\"center\">";
			echo "<input type=\"checkbox\" name=\"file[]\" value=\"../".$dir."/".$file."\" class=\"nonebg\">";
			echo "</td>\n";
			echo "</tr>";
		}
    }
	closedir($handle);
    $FORM->formfooter(array("colspan" => "4","button" =>array("submit"=>array("value"=>"删除"))));
}

//数据库恢复 - 执行导入操作
if($action == "import") {
	@set_time_limit(600);
	$file_name = trim($_GET['filename']);
	$file_path = "bak/".$file_name;
	if(!file_exists($file_path)) {
		$FORM->ob_exit("数据文件不存在","");
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
	$FORM->ob_exit("数据文件恢复成功!","");
}

//优化/修复数据库界面
if ($action == "optimize" || $action == "repair") {
    if ($action == "optimize") {
        $FORM->formheader(array('title' => '优化数据库,请选择要优化的表','action' => 'admin.php?action=dooptimize'));
    } else {
        $FORM->formheader(array('title' => '修复数据库,请选择要修复的表','action' => 'admin.php?action=dorepair'));
    }

    $tables = mysql_list_tables($mysql_dbname);
    if (!$tables) {
        print "DB Error, could not list tables\n";
        print 'MySQL Error: ' . mysql_error();
        $FORM->ob_exit("数据库错误", "");
    }
    while ($table = $DB->fetch_row($tables)) {
        $cachetables[$table[0]] = $table[0];
	    $tableselected[$table[0]] = 1;
    }
    $DB->free_result($tables);
    $FORM->makeselect(array(
		"text"     => "请选择表:",
        "name"     => "table[]",
        "option"   => $cachetables,
        "selected" => $tableselected,
        "multiple" => 1,
        "size"     => 12
    ));
    $FORM->formfooter();
}

//优化/修复数据库
if($action == "dooptimize" || $action == "dorepair") {
	if ($action == "dooptimize") {
        $a    = "OPTIMIZE";
        $text = "优化";
    } else {
        $a    = "REPAIR";
        $text = "修复";
    }
    if (!is_array($_POST['table']) OR empty($_POST['table'])) {
        $FORM->ob_exit("还未选中任何要${text}的表", "");
    }
    $table = array_flip($_POST['table']);
	$FORM->tableheaderbig(array("title" => "优化数据库","colspan" => "1"));
	echo "<tr align=\"left\" bgcolor=\"#FFFFFF\"><td width=\"100%\">\n";
    foreach ($table AS $name => $value) {
		if (isset($value)) {
			echo "正在{$text}表: $name";
			$result = $DB->query("$a TABLE $name");
			if ($result) {
				echo " ................. 完成<br>";
			} else {
				echo " <font color=\"red\"><b>失败</b></font>";
			}
			echo "";
		}
	}
    echo "<p>所有表{$text}完成.</p>";
	echo "</td></tr>\n";
	$FORM->tablefooter();
}

//操作记录列表
if($action == "actlog") {
	$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."adminlog`");
	$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$page_char = page($allNum,30,$cpage,"admin.php?action=actlog");
	$startI = $cpage*30-30;
	$FORM->formheader(array("title" => "后台操作记录 [共有".$allNum."条记录] [30条/页]","colspan" => "5","action" => "admin.php?action=cleanActlog"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>ID</b></td>\n";
	echo "<td width=\"15%\"><b>IP</b></td>\n";
	echo "<td width=\"48%\"><b>页面</b></td>\n";
	echo "<td width=\"17%\"><b>时间</b></td>\n";
	echo "<td width=\"15%\"><b>操作</b></td>\n";
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
	$FORM->formfooter(array("colspan"=>'5',"button" =>array("submit"=>array("value"=>"清空记录"))));
}

//清空操作记录
if($action == "cleanActlog") {
	if($DB->query("TRUNCATE TABLE `".$mysql_prefix."adminlog`")) {
		$FORM->ob_exit("操作记录已经清空","");
	}
}

//登陆记录列表
if($action == "userlog") {
	$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."loginlog`");
	$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$page_char = page($allNum,30,$cpage,"admin.php?action=userlog");
	$startI = $cpage*30-30;
	$FORM->formheader(array("title" => "后台登陆记录 [共有".$allNum."条记录] [30条/页]","colspan" => "5","action" => "admin.php?action=cleanUserlog"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>ID</b></td>\n";
	echo "<td width=\"15%\"><b>IP</b></td>\n";
	echo "<td width=\"35%\"><b>用户名</b></td>\n";
	echo "<td width=\"30%\"><b>时间</b></td>\n";
	echo "<td width=\"15%\"><b>结果</b></td>\n";
	echo "</tr>\n";
	$logs = $DB->query("SELECT * FROM `".$mysql_prefix."loginlog` ORDER BY `id` DESC LIMIT ".$startI.",30");
	while($log = $DB->fetch_array($logs)) {
		$log['result'] = ($log['result'] == 1) ? "成功" : "<font color=\"red\">失败</font>";
		echo "<tr ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$log['id']."</td>";
		echo "<td align=\"center\">".$log['ip']."</td>\n";
		echo "<td align=\"center\">".$log['username']."</td>\n";
		echo "<td align=\"center\">".obdate("y-m-d H:m:s",$log['date'])."</td>\n";
		echo "<td align=\"center\">".$log['result']."</td>\n";
		echo "</tr>\n";
	}
	$FORM->makepage($page_char,5,0);
	$FORM->formfooter(array("colspan"=>'5',"button" =>array("submit"=>array("value"=>"清空记录"))));
}

//清空登陆记录
if($action == "cleanUserlog") {
	if($DB->query("TRUNCATE TABLE `".$mysql_prefix."loginlog`")) {
		$FORM->ob_exit("登陆记录已经清空","");
	}
}

//修改密码界面
if($action == "password") {
	$userid = intval($_COOKIE['ob_userid']);
	$current_nickname = $DB->fetch_one("SELECT `nickname` FROM {$mysql_prefix}admin WHERE `id`={$userid}");
    $FORM->formheader(array("title" => "修改密码:","action" => "admin.php?action=updatepassword"));
	$FORM->makeinput(array(
		"text"  => "昵称",
		"note"  => "显示在前台的用户名，建议和用户名不一样。",
		"name"  => "nickname",
		"value"  => $current_nickname,
	),0);
    $FORM->makeinput(array(
		"text" => "旧密码:",
		"name" => "oldpassword",
		"type" => "password"
	));
    $FORM->makeinput(array(
		"text" => "新密码:",
        "name" => "newpassword",
        "type" => "password"
	));
    $FORM->makeinput(array(
		"text" => "确认新密码:",
		"name" => "comfirpassword",
		"type" => "password"
	));
    $FORM->formfooter();
}

//执行修改密码
if($action == "updatepassword") {
	if (trim($_POST['oldpassword']) == "") {
        $FORM->ob_exit("密码无效","");
    }
    $user = $DB->fetch_one_array("SELECT `username`,`password` FROM `".$mysql_prefix."admin` WHERE `id`=".intval($_COOKIE['ob_userid']));
    if (md5($_POST['oldpassword']) != $user['password']) {
        $FORM->ob_exit("原密码不正确","");
    }
    $_POST['newpassword'] = trim($_POST['newpassword']);
    $_POST['comfirpassword'] = trim($_POST['comfirpassword']);
    if (trim($_POST['newpassword']) == "") {
        $FORM->ob_exit("新密码不能为空","");
    }
	if(strlen($_POST['newpassword']) < 5) {
		$FORM->ob_exit("新密码长度不能小于5位","");
	}
    if ($_POST['newpassword'] != $_POST['comfirpassword']) {
        $FORM->ob_exit("两次输入的新密码不一致","");
    }
	if(trim($_POST['nickname']) == '' || strlen(trim($_POST['nickname'])) > 100) {
		$FORM->ob_exit("昵称为空或太长","");
	}
	$nickname = checkPost(trim($_POST['nickname']));
    $DB->query("UPDATE `".$mysql_prefix."admin` SET password='".md5($_POST['newpassword'])."',nickname='".$nickname."' WHERE `id`=".intval($_COOKIE['ob_userid']));
    $FORM->ob_exit("密码更改成功,请重新登陆","./index.php","_parent");
}

//退出登陆
if($action == "logout") {
	setcookie("ob_login","");
	setcookie("ob_userid","");
	$FORM->ob_exit("您已经退出管理后台","./index.php","_parent");
}

//留言列表
if($action == "guestbook") {
	
	
	if(isset($_POST['keyword']) && checkPost(trim($_POST['keyword'])) != '') {
		$keyword = checkPost(trim($_POST['keyword']));
		$searchwhere = checkPost(trim($_POST['searchwhere']));
		$keyword = str_replace("_","\_",$keyword);
		$keyword = str_replace("%","\%",$keyword);
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."guestbook` WHERE `{$searchwhere}` LIKE '%{$keyword}%'");
		$listSql = "SELECT * FROM `".$mysql_prefix."guestbook` WHERE `{$searchwhere}` LIKE '%{$keyword}%' ORDER BY `id`";
		if($allNum == 0) {
			$FORM->ob_exit("没有找到符合搜索条件的留言","");
		}
	} else {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."guestbook`");
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=guestbook");
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."guestbook` ORDER BY `id` DESC LIMIT ".$startI.",20";
		if($allNum == 0) {
			$FORM->ob_exit("目前还没有留言","");
		}
	}
	if(isset($_POST['keyword']) && checkPost(trim($_POST['keyword'])) != '') {
		$formtitle = "搜索结果 [共有{$allNum}篇留言]";
	} else {
		$formtitle = "留言管理 [共有{$allNum}篇留言] [20条/页]";
	}
	
	$FORM->js_checkall();
	$FORM->if_del();
	$FORM->formheader(array("title" => $formtitle,"colspan" => "5","action" => "admin.php?action=dosomeGb"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>回复</b></td>\n";
	echo "<td width=\"25%\"><b>留言人</b></td>\n";
	echo "<td width=\"48%\"><b>内容</b></td>\n";
	echo "<td width=\"17%\"><b>操作</b></td>\n";
	echo "<td width=\"5%\"><input type=\"checkbox\" name=\"chkall\" value=\"on\" class=\"nonebg\" onclick=\"CheckAll(this.form)\"></td>\n";
	echo "</tr>\n";
	$gbs = $DB->query($listSql);
	while($gb = $DB->fetch_array($gbs)) {
		$isallow = empty($gb['reply']) ? "否" : "是";
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$isallow."</td>\n";
		echo "<td align=\"left\" valign=\"top\"><b>昵称: </b>".$gb['username']."<br><b>E-mail: </b>".$gb['email']."<br><b>IP: </b>".$gb['ip']."</td>\n";
		echo "<td align=\"left\" valign=\"top\">".$gb['content']."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modgb&id=".$gb['id']."\">编辑</a>] [<a href=\"admin.php?action=replygb&id=".$gb['id']."\">回复</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delgb&id=".$gb['id']."')\">删除</a>]</td>\n";
		echo "<td><input type=\"checkbox\" name=\"gb[".$gb['id']."]\" value=\"1\" class=\"nonebg\"></td>\n";
		echo "</tr>\n";
	}

	echo "<tr class=\"secondalt\">";
	echo "<td colspan=\"5\" align=\"center\">\n";
	echo "<label for=\"del\"><input type=\"radio\" name=\"editall\" value=\"del\" id=\"del\" class=\"nonebg\">删除 </label>";
	echo "</td></tr>\n";
	$FORM->makepage($page_char,5,0);
	$FORM->formfooter(array("colspan" => 5));

	//搜索留言表单
	echo "\n\n<br><form action=\"admin.php?action=guestbook\" method=\"post\"><table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\"><tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" align=\"center\">\n<b>搜索留言:&nbsp;&nbsp;<b>\n";
	echo "\n<select name=\"searchwhere\"><option value=\"content\">内容</option><option value=\"username\">昵称</option></select>\n<input type=\"text\" id=\"keyword\" name=\"keyword\" size=\"30\">\n\n";
	echo "&nbsp;&nbsp;<input type=\"submit\" value=\"搜索\"></td>\n";
	echo "</table></form>\n";
}

//批量操作留言
if($action == "dosomeGb") {
	$dowhat = checkPost(trim($_POST['editall']));
	$gb = checkPost($_POST['gb']);
	if($dowhat == "") {
		$FORM->ob_exit("请选择要执行的操作","");
	}
	if(count($gb) == 0) {
		$FORM->ob_exit("请选择要操作的留言","");
	}
	if($dowhat == "del") {
		foreach($gb as $key=>$val) {
			if(!$DB->query("DELETE FROM `".$mysql_prefix."guestbook` WHERE `id` = ".$key)) {
				$FORM->ob_exit("删除留言失败","");
			}
		}
		$FORM->ob_exit("删除留言完成","");
	}
}

//修改留言界面
if($action == "modgb") {
	$gb = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."guestbook` WHERE `id` =".intval($_GET['id']));
	$FORM->formheader(array("title" => "编辑留言","action" => "admin.php?action=domodgb","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "昵称",
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
	$FORM->maketextarea(array("text" => "内容","name" => "content","value" => strip_tags($gb['content'])));
	$FORM->makehidden(array("name" => "id", "value" => $gb['id']));
	$FORM->formfooter();
}

//执行修改留言
if($action == "domodgb") {
	$name = checkPost(trim($_POST['name']));
	$email = checkPost(trim($_POST['email']));
	$content = nl2br(htmlspecialchars(trim($_POST['content'])));
	$id = intval($_POST['id']);
	if(empty($name)) {
		$FORM->ob_exit("昵称还没有填写呢","");
	}
	if(empty($content)) {
		$FORM->ob_exit("内容还没有填写呢","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."guestbook` SET `username` = '".$name."', `email` = '".$email."', `content` = '".$content."' WHERE `id` = ".$id)) {
		$FORM->ob_exit("留言编辑成功","admin.php?action=guestbook");
	} else {
		$FORM->ob_exit("留言编辑失败","");
	}
}

//回复留言界面
if($action == "replygb") {
	$gb = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."guestbook` WHERE `id` =".intval($_GET['id']));
	$FORM->formheader(array("title" => "回复留言","action" => "admin.php?action=doreplygb","name" => "form"));
	echo "<tr ".$FORM->getrowbg()." nowrap>\n";
	echo "<td valign=\"top\"><b>内容</b></td>\n";
	echo "<td>".$gb['content']."</td>\n";
	echo "</tr>\n";
	$FORM->maketextarea(array("text" => "回复","note" => "删除回复请留空","name" => "reply","value" => strip_tags($gb['reply'])));
	$FORM->makehidden(array("name" => "id", "value" => $gb['id']));
	$FORM->formfooter();
}

//执行回复留言
if($action == "doreplygb") {
	$reply = nl2br(htmlspecialchars(trim($_POST['reply'])));
	$id = intval($_POST['id']);
	if($DB->query("UPDATE `".$mysql_prefix."guestbook` SET reply = '".$reply."' WHERE `id` = ".$id)) {
		$FORM->ob_exit("留言回复成功","admin.php?action=guestbook");
	} else {
		$FORM->ob_exit("留言回复失败","");
	}
}

//删除留言
if($action == "delgb") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."guestbook` WHERE `id`=".$id)) {
		$FORM->ob_exit("删除留言成功","");
	} else {
		$FORM->ob_exit("删除留言失败","");
	}
}

//留言/评论过滤
if($action == "banned") {
	$banned = $DB->fetch_one_array("SELECT `banned_username`,`banned_word`,`banned_ip` FROM {$mysql_prefix}config");
	$FORM->formheader(array("title" => "过滤管理","action" => "admin.php?action=dobanned","name" => "form"));
	$FORM->maketextarea(array("text" => "用户名过滤","note" => "禁止用户在留言/评论时使用的用户名。每行一个","name" => "banned_username","value" => $banned['banned_username']));
	$FORM->maketextarea(array("text" => "词语过滤","note" => "禁止用户在留言/评论时使用的词语。每行一个<br>请不要设置过多的词语，以免影响程序效率","name" => "banned_word","value" => $banned['banned_word']));
	$FORM->maketextarea(array("text" => "IP过滤","note" => "禁止拥有此IP的用户发表留言/评论。每行一个","name" => "banned_ip","value" => $banned['banned_ip']));
	$FORM->formfooter();
}

//执行提交留言/评论过滤
if($action == "dobanned") {
	$banned_username = checkPost(trim($_POST['banned_username']));
	$banned_word = checkPost(trim($_POST['banned_word']));
	$banned_ip = checkPost(trim($_POST['banned_ip']));
	if($DB->query("UPDATE {$mysql_prefix}config SET `banned_username`='{$banned_username}', `banned_word`='{$banned_word}', `banned_ip`='{$banned_ip}'")) {
		$FORM->ob_exit("操作成功完成","");
	} else {
		$FORM->ob_exit("操作发生错误","");
	}
}

//添加记事
if($action == "addNote") {
	$FORM->formheader(array("title" => "添加记事","action" => "admin.php?action=doaddNote","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "标题",
				"note"  => "记事的标题",
				"name"  => "name",
				"value" => ""
            ),0);
	$FORM->editor(array("text" => "内容","note" => "记事的内容，使用UBB格式的编码"));
	$FORM->formfooter();
}

//执行添加记事
if($action == "doaddNote") {
	$title = htmlspecialchars(checkPost(trim($_POST['name'])));
	$content = htmlspecialchars(checkPost(trim($_POST['message'])));
	$date = time();
	if(empty($title)) {
		$FORM->ob_exit("标题还没有填写呢","");
	}
	if(empty($content)) {
		$FORM->ob_exit("内容还没有填写呢","");
	}
	if($DB->query("INSERT INTO `".$mysql_prefix."note` (date,title,content) VALUES ('".$date."','".$title."','".$content."')")) {
		$FORM->ob_exit("恭喜，记事添加成功","admin.php?action=editNote");
	} else {
		$FORM->ob_exit("抱歉，记事添加失败","");
	}
}

//管理记事
if($action == "editNote") {
	$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."note`");
	if($allNum == 0) {
		$FORM->ob_exit("目前还没有记事","");
	}
	$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$page_char = page($allNum,20,$cpage,"admin.php?action=editNote");
	$startI = $cpage*20-20;
	$listSql = "SELECT * FROM `".$mysql_prefix."note` ORDER BY `id` DESC LIMIT ".$startI.",20";
	$FORM->if_del();
	$FORM->tableheaderbig(array("title" => "记事管理 [共有{$allNum}条记事] [20条/页]","colspan" => "4"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"5%\"><b>ID</b></td>\n";
	echo "<td width=\"50%\"><b>标题</b></td>\n";
	echo "<td width=\"20%\"><b>日期</b></td>\n";
	echo "<td width=\"25%\"><b>操作</b></td>\n";
	echo "</tr>\n";
	$notes = $DB->query($listSql);
	while($note = $DB->fetch_array($notes)) {
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$note['id']."</td>\n";
		echo "<td align=\"left\" valign=\"top\"><a href=\"admin.php?action=viewNote&id=".$note['id']."\">".$note['title']."</a></td>\n";
		echo "<td align=\"center\" valign=\"top\">".obdate("y-m-d H:m:s",$note['date'])."</td>\n";
		echo "<td>[<a href=\"admin.php?action=viewNote&id=".$note['id']."\">浏览</a>] [<a href=\"admin.php?action=modNote&id=".$note['id']."\">编辑</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delNote&id=".$note['id']."')\">删除</a>]</td>\n";
		echo "</tr>\n";
	}
	$FORM->makepage($page_char,4,0);
	$FORM->tablefooter();
}

//浏览记事
if($action == "viewNote") {
	chdir("admin");
	$id = intval($_GET['id']);
	$note = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."note` WHERE `id` = ".$id);
	$FORM->formheader(array("title" => "标题: ".trim($note['title']),"action" => "admin.php?action=editNote","name" => "input","colspan" => 2));
	$UBB->setString($note['content']);
	$note['content'] = $UBB->parse();
	$note['content'] = qqface($note['content'],"../admin/images/smilies/");
	echo "<tr class=\"secondalt\" nowrap>\n";
	echo "<td valign=\"top\" colspan=\"2\"><b>日期: </b>".obdate("Y-m-d H:m:s",$note['date'])."</td>\n";
	echo "</tr>\n";
	echo "<tr ".$FORM->getrowbg()." nowrap>\n";
	echo "<td valign=\"top\" width=\"10%\"><b>内容: </b></td>\n";
	echo "<td valign=\"top\">".$note['content']."</td>\n";
	echo "</tr>\n";
	$FORM->formfooter(array("colspan"=>'2',"button" =>array("submit"=>array("value"=>"返回"))));
}

//修改记事
if($action == "modNote") {
	$id = intval($_GET['id']);
	$note = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."note` WHERE `id` = ".$id);
	$FORM->formheader(array("title" => "编辑记事","action" => "admin.php?action=domodNote","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "标题",
				"note"  => "记事的标题",
				"name"  => "name",
				"value" => $note['title']
            ),0);
	$FORM->editor(array("text" => "内容","note" => "记事的内容，使用UBB格式的编码","value" => $note['content']));
	$FORM->makehidden(array("name" => "id","value" => $note['id']));
	$FORM->formfooter();
}

//执行修改记事
if($action == "domodNote") {
	$title = htmlspecialchars(checkPost(trim($_POST['name'])));
	$content = htmlspecialchars(checkPost(trim($_POST['message'])));
	$date = time();
	if(empty($title)) {
		$FORM->ob_exit("标题还没有填写呢","");
	}
	if(empty($content)) {
		$FORM->ob_exit("内容还没有填写呢","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."note` SET `date` = '".$date."',`title` = '".$title."',`content` = '".$content."' WHERE `id` = ".intval($_POST['id']))) {
		$FORM->ob_exit("恭喜，编辑记事成功","admin.php?action=editNote");
	} else {
		$FORM->ob_exit("抱歉，编辑记事失败","");
	}
}

//删除记事
if($action == "delNote") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."note` WHERE `id`=".$id)) {
		$FORM->ob_exit("删除记事成功","");
	} else {
		$FORM->ob_exit("删除记事失败","");
	}
}

//文件管理
if($action == "uploadManager") {
	chdir("admin");
	$dir = "uploadfiles";
	$text = "上传";
	$FORM->js_checkall();
    $FORM->formheader(array(
		"title"   => $text."数据管理",
		"action"  => "admin.php?action=delFile",
		"colspan" => "3"
	));
	echo "<tr bgcolor=\"#F3F3F3\">\n";
	echo "<td align=\"center\"><b>文件名</b></td>\n";
	echo "<td align=\"center\"><b>文件大小</b></td>\n";
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
    $FORM->formfooter(array("colspan" => "3","button" =>array("submit"=>array("value"=>"删除"))));
}

//删除文件
if($action == "delFile") {
	chdir("admin");
	$file = checkPost($_POST['file']);
	if (empty($file) OR !is_array($file)) {
        $FORM->ob_exit("未选择附件", "");
    }
	foreach($file as $key=>$val) {
		@chmod ($val, 0777);
		@unlink($val);
	}
	$FORM->ob_exit("删除文件成功", "");
}

//重建静态页面
if($action == "rebuild") {
	$maxID = $DB->fetch_one("SELECT max(id) FROM `".$mysql_prefix."blog`");
	$FORM->formheader(array(
		"title"   => "重建静态页面",
		"action"  => "admin.php?action=dobuild",
		"method"  => "get",
		"colspan" => "2",
	));
	echo "<tr ".$FORM->getrowbg().">";
	echo "<td><b>重建首页</b><br>将在根目录重新生成 index.html<br>这里的静态扩展名不会根据您的设置而改变</td>";
	echo "<td>";
	echo "<input type=\"radio\" name=\"buildwhich\" value=\"index\" class=\"nonebg\" onclick=\"startid.disabled=true;endid.disabled=true;onetimenum.disabled=true\" checked> index.html";
	echo "</td>\n";
	echo "</tr>";
	echo "<tr ".$FORM->getrowbg().">";
	echo "<td><b>重建日志页</b><br>一次重建很多静态页可能要花较长的时间<br>建议每次重建大概50个左右 </td>";
	echo "<td>";
	echo "<input type=\"radio\" name=\"buildwhich\" value=\"blog\" class=\"nonebg\" onclick=\"startid.disabled=false;endid.disabled=false;onetimenum.disabled=false\">";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;开始ID: <input type=\"text\" name=\"startid\" class=\"formfield\" size=\"5\" value=\"1\" disabled> &nbsp;&nbsp;结束ID: <input type=\"text\" name=\"endid\" class=\"formfield\" size=\"5\" value=\"".$maxID."\" disabled>&nbsp;&nbsp;每次重建: <input type=\"text\" name=\"onetimenum\" class=\"formfield\" size=\"5\" value=\"50\" disabled><input type=\"hidden\" name=\"action\" value=\"dobuild\">";
	echo "</td>\n";
	echo "</tr>";
	$FORM->formfooter(array("colspan" => "2"));
}

//执行重建页面
if($action == "dobuild") {
	$buildwhich = trim($_GET['buildwhich']);
	if($buildwhich == "index") {
		if($HTML->makeindex()) {
			$FORM->ob_exit("首页重建成功");
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
		$onetimenum = intval($_GET['onetimenum']);			//每次重建多少个
		$blogs = $DB->query("SELECT `id` FROM `".$mysql_prefix."blog` WHERE `id`<= ".$bigid." AND `id` >= ".$smallid);
		if($DB->num_rows($blogs) == 0) {
			$FORM->ob_exit("没有可以建立的页面");
		}
		while($blogRe = $DB->fetch_array($blogs)) {
			$blogid[] = $blogRe['id'];
		}

		$FORM->div_top(array("title" => "正在重新建立静态页面... 请稍后"));
		$root = "";
		
		$countIndex = 1;
		foreach($blogid as $key=>$val) {
			$date = $DB->fetch_one("SELECT `date` FROM ".$mysql_prefix."blog WHERE `id` = ".$val);	
			$path = $root.getHtmlPath($val);
			if($HTML->make($val)) {
				$result = "完成";
			} else {
				$result = "<font color=\"red\">失败</font>";
			}
			echo "<b>#{$val}:</b> 正在建立页面: {$path} ............................ [{$result}]<br />";
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
				echo "<br /><a href=\"admin.php?buildwhich=blog&startid={$newstartid}&endid={$bigid}&onetimenum={$onetimenum}&toaddcount={$_GET['toaddcount']}&toaddtime={$toaddtime}&action=dobuild\">正在跳转...如果您的浏览器没有自动跳转，请点击这里</a><br />累计耗时 $toaddtime 秒";
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
		echo "<br />建立静态页面完成。累计耗时 $toaddtime 秒";
		echo "<script>topdiv.innerText = \"静态页面建立完成\"</script>";
		$FORM->div_bo();
	}
}

//文件上传界面
if($action == "upload") {
	$maxSize = @getcon("upload_max_filesize");
	$FORM->formheader(array(
		"title"   => "文件上传 [最大文件允许:{$maxSize}]",
		"action"  => "admin.php?action=doupload",
		"colspan" => "2",
		"enctype" => "multipart/form-data",
	));
	$FORM->makefile(array(
		"text" => "[#1] 请选择您要上传的文件",
		"name" => "attachment[0]",
		"size" => "40",
	));
	$FORM->makefile(array(
		"text" => "[#2] 请选择您要上传的文件",
		"name" => "attachment[1]",
		"size" => "40",
	));
	$FORM->makefile(array(
		"text" => "[#3] 请选择您要上传的文件",
		"name" => "attachment[2]",
		"size" => "40",
	));
	$FORM->makefile(array(
		"text" => "[#4] 请选择您要上传的文件",
		"name" => "attachment[3]",
		"size" => "40",
	));
	$FORM->makefile(array(
		"text" => "[#5] 请选择您要上传的文件",
		"name" => "attachment[4]",
		"size" => "40",
	));
	$FORM->formfooter(array("colspan" => "2"));
}

//上传文件
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
				$FORM->ob_exit("附件上传时发生错误","admin.php?action=uploadManager");
			}
		}
	}
	$FORM->ob_exit("附件上传成功","admin.php?action=uploadManager");
}

//添加用户
if($action == "addUser") {
	$FORM->formheader(array("title" => "添加用户","action" => "admin.php?action=doaddUser","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "用户名",
				"note"  => "登陆后台的用户名，只能由字母和数字组成",
				"name"  => "username",
				"value"  => "",
            ),0);
	$FORM->makeinput(array(
				"text"  => "昵称",
				"note"  => "显示在前台的用户名，建议和用户名不一样。",
				"name"  => "nickname",
				"value"  => "",
            ),0);
	$FORM->makeinput(array(
				"text"  => "密码",
				"note"  => "密码不能少于5个字符",
				"name"  => "password1",
				"value"  => "",
				"type" => "password",
            ),0);
	$FORM->makeinput(array(
				"text"  => "确认密码",
				"note"  => "请再输入一次密码",
				"name"  => "password2",
				"value"  => "",
				"type" => "password",
            ),0);
	for($i=0;$i<count($auth);$i++) {
		$check = ($auth[$i]['check']) ? "checked" : "";
		if($auth[$i]['name'] != "others") {
			echo "<tr ".$FORM->getrowbg(0)." nowrap>\n";
			echo "<td><b>[权限]</b> ".$auth[$i]['name']."<br>".$arguments['note']."</td>\n";
			echo "<td><input type=\"checkbox\" name=\"auth[]\" value=\"".$auth[$i]['action']."\" class=\"nonebg\" ".$check."></td>\n";
			echo "</tr>\n";
		} else {
			$FORM->makehidden(array("name" => "auth[]", "value" => $auth[$i]['action']));
		}
	}
	$FORM->formfooter();
}

//执行添加用户
if($action == "doaddUser") {
	$auth = $_POST['auth'];
	for($ii=0; $ii<count($auth); $ii++) {
		$auth[$ii] = checkPost(trim($auth[$ii]));
	}
	$auth_char = @implode(",",$auth);

	if ($_POST['password1'] != $_POST['password2']) {
        $FORM->ob_exit("两次输入的密码不一致","");
    }
	if (trim($_POST['password1']) == "") {
        $FORM->ob_exit("密码不能为空","");
    }
	if(strlen($_POST['password1']) < 5) {
		$FORM->ob_exit("密码长度不能小于5位","");
	}
	if(trim($_POST['nickname']) == '' || strlen(trim($_POST['nickname'])) > 100) {
		$FORM->ob_exit("昵称为空或太长","");
	}

	$username = trim($_POST['username']);
	$password = md5($_POST['password1']);
	$nickname = checkPost(trim($_POST['nickname']));

	if($DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."admin` WHERE `username`='".$username."'") != 0) {
		$FORM->ob_exit("这个用户已存在","");
	}

	if($DB->query("INSERT INTO `".$mysql_prefix."admin` ( `username` , `password` , `auth` , `nickname`) VALUES ('".$username."', '".$password."', '".$auth_char."', '".$nickname."')")) {
		$FORM->ob_exit("用户添加成功","admin.php?action=editUser");
	} else {
		$FORM->ob_exit("用户添加失败","");
	}
}

//编辑用户-列表
if($action == "editUser") {
	$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."admin`");
	$listSql = "SELECT * FROM `".$mysql_prefix."admin` ORDER BY `id` ASC";
	$FORM->if_del();
	$FORM->tableheaderbig(array("title" => "用户管理 [共有{$allNum}个用户]","colspan" => "3"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"20%\"><b>ID</b></td>\n";
	echo "<td width=\"50%\"><b>用户名</b></td>\n";
	echo "<td width=\"30%\"><b>操作</b></td>\n";
	echo "</tr>\n";
	$users = $DB->query($listSql);
	while($user = $DB->fetch_array($users)) {
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$user['id']."</td>\n";
		echo "<td align=\"center\" valign=\"top\">".$user['username']."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modUser&id=".$user['id']."\">编辑</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delUser&id=".$user['id']."')\">删除</a>]</td>\n";
		echo "</tr>\n";
	}
	$FORM->makepage($page_char,3,0);
	$FORM->tablefooter();
}

//修改一个用户
if($action == "modUser") {
	$id = intval($_GET['id']);
	$user = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."admin` WHERE `id`=".$id);
	$FORM->formheader(array("title" => "编辑用户","action" => "admin.php?action=domodUser","name" => "form"));
	$FORM->makeinput(array(
				"text"  => "用户名",
				"note"  => "登陆后台的用户名，只能由字母和数字组成",
				"name"  => "username",
				"value"  => $user['username'],
            ),0);
	$FORM->makeinput(array(
				"text"  => "昵称",
				"note"  => "显示在前台的用户名，建议和用户名不一样。",
				"name"  => "nickname",
				"value"  => $user['nickname'],
            ),0);
	$FORM->makeinput(array(
				"text"  => "密码",
				"note"  => "不改请留空,密码不能少于5个字符",
				"name"  => "password1",
				"value"  => "",
				"type" => "password",
            ),0);
	$FORM->makeinput(array(
				"text"  => "确认密码",
				"note"  => "请再输入一次密码",
				"name"  => "password2",
				"value"  => "",
				"type" => "password",
            ),0);
	
	for($i=0;$i<count($auth);$i++) {
		$check = (strstr($user['auth'],$auth[$i]['action'])) ? "checked" : "";
		if($auth[$i]['name'] != "others") {
			echo "<tr ".$FORM->getrowbg(0)." nowrap>\n";
			echo "<td><b>[权限]</b> ".$auth[$i]['name']."<br>".$arguments['note']."</td>\n";
			echo "<td><input type=\"checkbox\" name=\"auth[]\" value=\"".$auth[$i]['action']."\" class=\"nonebg\" ".$check."></td>\n";
			echo "</tr>\n";
		} else {
			$FORM->makehidden(array("name" => "auth[]", "value" => $auth[$i]['action']));
		}
	}
	$FORM->makehidden(array("name" => "id", "value" => $user['id']));
	$FORM->formfooter();
}

//执行修改一个用户
if($action == "domodUser") {
	$auth = $_POST['auth'];
	for($ii=0; $ii<count($auth); $ii++) {
		$auth[$ii] = checkPost(trim($auth[$ii]));
	}
	$auth_char = @implode(",",$auth);

	if ($_POST['password1'] != $_POST['password2']) {
        $FORM->ob_exit("两次输入的密码不一致","");
    }
	if($_POST['password1'] != "" && strlen($_POST['password1']) < 5) {
		$FORM->ob_exit("密码长度不能小于5位","");
	}
	if(trim($_POST['nickname']) == '' || strlen(trim($_POST['nickname'])) > 100) {
		$FORM->ob_exit("昵称为空或太长","");
	}

	$oldpassword = $DB->fetch_one("SELECT `password` FROM `".$mysql_prefix."admin` WHERE `id`=".intval($_POST['id']));
	$newpassword = ($_POST['password1'] == "") ? $oldpassword : md5($_POST['password1']);
	$username = trim($_POST['username']);
	$password = $newpassword;
	$nickname = trim($_POST['nickname']);

	if($DB->query("UPDATE `".$mysql_prefix."admin` SET `username` = '".$username."',`password`='".$password."',`auth`='".$auth_char."',`nickname`='".$nickname."' WHERE `id`=".intval($_POST['id']))) {
		$FORM->ob_exit("编辑用户成功","");
	} else {
		$FORM->ob_exit("编辑用户失败","");
	}
}

//删除一个用户
if($action == "delUser") {
	$id = intval($_GET['id']);
	if($DB->query("DELETE FROM `".$mysql_prefix."admin` WHERE `id`=".$id)) {
		$FORM->ob_exit("删除用户成功","");
	} else {
		$FORM->ob_exit("删除用户失败","");
	}
}

//Trackback Pings 管理-列表
if($action == "editTrackback") {
	if(isset($_GET['id'])) {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."trackback` WHERE `inblog` = ".intval($_GET['id']));
		if($allNum == 0) {
			$FORM->ob_exit("当前日志没有 Trackback Ping","");
		}
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=editTrackback&id=".intval($_GET['id']));
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."trackback` WHERE `inblog` = ".intval($_GET['id'])." ORDER BY `trackbackid` DESC LIMIT ".$startI.",20";
	} else {
		$allNum = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."trackback`");
		if($allNum == 0) {
			$FORM->ob_exit("目前还没有 Trackback Ping","");
		}
		$cpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page_char = page($allNum,20,$cpage,"admin.php?action=editTrackback");
		$startI = $cpage*20-20;
		$listSql = "SELECT * FROM `".$mysql_prefix."trackback` ORDER BY `trackbackid` DESC LIMIT ".$startI.",20";
	}
	$FORM->js_checkall();
	$FORM->if_del();
	$FORM->formheader(array("title" => "引用通告管理 [共有{$allNum}篇引用通告] [20条/页]","colspan" => "5","action" => "admin.php?action=dosomeTrackback"));
	echo "<tr align=\"center\" bgcolor=\"#F3F3F3\">\n";
	echo "<td width=\"20%\"><b>站点名称</b></td>\n";
	echo "<td width=\"40%\"><b>引用标题</b></td>\n";
	echo "<td width=\"20%\"><b>添加时间</b></td>\n";
	echo "<td width=\"15%\"><b>操作</b></td>\n";
	echo "<td width=\"5%\"><input type=\"checkbox\" name=\"chkall\" value=\"on\" class=\"nonebg\" onclick=\"CheckAll(this.form)\"></td>\n";
	echo "</tr>\n";
	$trackbacks = $DB->query($listSql);
	while($trackbackRe = $DB->fetch_array($trackbacks)) {
		echo "<tr align=\"center\" ".$FORM->getrowbg(0).">\n";
		echo "<td align=\"center\">".$trackbackRe['blogname']."</td>\n";
		echo "<td align=\"left\" valign=\"top\">".$trackbackRe['title']."</td>\n";
		echo "<td align=\"center\">".obdate("y-m-d H:m:s",$trackbackRe['adddate'])."</td>\n";
		echo "<td>[<a href=\"admin.php?action=modTrackback&id=".$trackbackRe['trackbackid']."\">编辑</a>] [<a href=\"#\" onclick=\"ifDel('admin.php?action=delTrackback&trackbackid=".$trackbackRe['trackbackid']."')\">删除</a>]</td>\n";
		echo "<td><input type=\"checkbox\" name=\"trackback[".$trackbackRe['trackbackid']."]\" value=\"1\" class=\"nonebg\"></td>\n";
		echo "</tr>\n";
	}
	
	echo "<tr class=\"secondalt\">";
	echo "<td colspan=\"5\" align=\"center\">\n";
	echo "<label for=\"del\"><input type=\"radio\" name=\"editall\" value=\"del\" id=\"del\" class=\"nonebg\">删除 </label>";
	echo "</td></tr>\n";
	$FORM->makepage($page_char,5,2);
	$FORM->formfooter(array("colspan" => 5));
}

//删除 Trackback Ping
if($action == "delTrackback") {
	$trackbackid = intval($_GET['trackbackid']);
	$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."trackback` WHERE `trackbackid`=".$trackbackid);
	if($DB->query("DELETE FROM `".$mysql_prefix."trackback` WHERE `trackbackid`=".$trackbackid)) {
		if($makehtml) {
			$HTML->makeindex();
			$HTML->make($blogid);
		}
		$FORM->ob_exit("Trackback Pings 删除成功","");
	} else {
		$FORM->ob_exit("Trackback Pings 删除失败","");
	}
}

//批量操作 Trackback
if($action == "dosomeTrackback") {
	$dowhat = checkPost(trim($_POST['editall']));
	$trackback = checkPost($_POST['trackback']);
	if($dowhat == "") {
		$FORM->ob_exit("请选择要执行的操作","");
	}
	if($dowhat == "del") {
		foreach($trackback as $key=>$val) {
			if(!$DB->query("DELETE FROM `".$mysql_prefix."trackback` WHERE `trackbackid` = ".$key)) {
				$FORM->ob_exit("删除引用失败","");
			}
		}
		$FORM->ob_exit("删除引用完成","");
	}
}

//修改一个 Trackback Ping 界面
if($action == "modTrackback") {
	$trackback = $DB->fetch_one_array("SELECT * FROM `".$mysql_prefix."trackback` WHERE `trackbackid` =".intval($_GET['id']));
	$FORM->formheader(array("title" => "编辑引用通告","action" => "admin.php?action=domodTrackback","name" => "input"));
	$FORM->makeinput(array(
				"text"  => "站点名称",
				"note"  => "",
				"name"  => "blog_name",
				"value"  => trim($trackback['blogname']),
            ),0);
	$FORM->makeinput(array(
				"text"  => "引用标题",
				"note"  => "",
				"name"  => "title",
				"value"  => trim($trackback['title']),
            ),0);
	$FORM->makeinput(array(
				"text"  => "引用地址",
				"note"  => "",
				"name"  => "url",
				"value"  => trim($trackback['url']),
				"maxlength" => 100,
            ),0);
	$FORM->maketextarea(array("text" => "引用内容","name" => "excerpt","value" => strip_tags($trackback['excerpt'])));
	$FORM->makehidden(array("name" => "trackbackid", "value" => $trackback['trackbackid']));
	$FORM->formfooter();
}

//执行修改一个 Trackback Ping
if($action == "domodTrackback") {
	$blog_name = checkPost(trim($_POST['blog_name']));
	$title = checkPost(trim($_POST['title']));
	$url = checkPost(trim($_POST['url']));
	$excerpt = nl2br(htmlspecialchars(trim($_POST['excerpt'])));
	$trackbackid = intval($_POST['trackbackid']);
	if(empty($blog_name)) {
		$FORM->ob_exit("站点名称还没有填写呢","");
	}
	if(empty($title)) {
		$FORM->ob_exit("引用标题还没有填写呢","");
	}
	if(empty($url)) {
		$FORM->ob_exit("引用地址还没有填写呢","");
	}
	if(empty($excerpt)) {
		$FORM->ob_exit("引用内容还没有填写呢","");
	}
	if($DB->query("UPDATE `".$mysql_prefix."trackback` SET `blogname` = '".$blog_name."', `title` = '".$title."', `url` = '".$url."', `excerpt` = '".$excerpt."' WHERE `trackbackid` = ".$trackbackid)) {
		$blogid = $DB->fetch_one("SELECT `inblog` FROM `".$mysql_prefix."trackback` WHERE `trackbackid`=".$trackbackid);
		if($makehtml) {
			$HTML->make($blogid);
		}
		$FORM->ob_exit("Trackback Ping 编辑成功","");
	} else {
		$FORM->ob_exit("Trackback Ping 编辑失败","");
	}
}

//模板管理 - 列表
if($action == "selectTemplate") {
	//取得模板目录名
	if ($handle = @opendir('templates')) {
		while (false !== ($file = readdir($handle))) {
			if(is_dir('templates/'.$file) && $file != "." && $file != "..") {
				$dir[$file] = $file;
			}
		}
		unset($file);
		closedir($handle);
	}
	//取得当前编辑的模板目录
	if(isset($_GET['template_dir']) && trim($_GET['template_dir']) != '') {
		$current_template_dir = checkPost(trim($_GET['template_dir']));
	} else {
		$current_template_dir = $DB->fetch_one("SELECT `template` FROM {$mysql_prefix}config");
	}
	//取得当前模板的文件列表
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
	$FORM->formheader(array("title" => "选择模板","action" => "admin.php?action=editTemplate"));
	$FORM->makeselect(array(
		"text"  => "选择需要编辑的模板套系",
		"name"  => "template_dir",
		"option" => $dir,
		"extra" => "onchange=\"selecturl(document.all.template_dir.value)\"",
		"selected" => $current_template_dir,
	));
	$FORM->makeselect(array(
		"text" => "请选择模板文件:",
        "name" => "template_file",
        "option" => $tpl_file,
        "selected" => $tableselected,
        "multiple" => 0,
        "size" => 15
    ));
	$FORM->formfooter();
}

//模板管理 - 编辑模板文件 - 界面
if($action == "editTemplate") {
	$template_dir = checkPost(trim($_POST['template_dir']));
	$template_file = checkPost(trim($_POST['template_file']));
	$template_content = @htmlspecialchars(file_get_contents("templates/".$template_dir."/".$template_file));
	if($template_dir == "" || $template_file == "") {
		$FORM->ob_exit("请选择需要编辑的模板");
	}

	$FORM->formheader(array("title" => "编辑模板文件","action" => "admin.php?action=saveTemplate"));
	$FORM->makeinput(array(
				"text"  => "模板套系",
				"name"  => "template_dir",
				"value" => $template_dir,
            ));
	$FORM->makeinput(array(
				"text"  => "模板文件",
				"name"  => "template_file",
				"value" => $template_file,
            ));
	$FORM->maketextarea(array("text" => "模板文件内容","name" => "template_content","value" => $template_content,"rows" => 24,"cols" => 86,"extra" => "style=\"font-family:Courier New;font-size: 12px;\""));
	$FORM->formfooter();
}

//模板管理 - 保存模板文件
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
		$FORM->ob_exit("编辑模板文件成功");
	}
}

//RSS 2.0 数据导出 - 界面
if($action == "rssExport") {
	$FORM->formheader(array("title"=> "RSS 数据导出","action" => "admin.php?action=doRssExport"));
	echo "<tr><td class=\"tblhead\" colspan=\"2\"><b>注意：</b>这里导出数据的为RSS 2.0文件。数据仅包括日志，在任何支持导入RSS的程序上通用。</td></tr>";
	$FORM->makeinput(array(
		"text" => "每个文件包含记录数:",
		"note" => "每个备份文件最多包含的记录数目",
        "name" => "num_in_file",
		"size" => 40,
        "value" => "50"
	),1);
	$FORM->makeinput(array(
		"text" => "备份数据到:",
		"note" => "请确保备份文件夹的属性是777",
        "name" => "path",
		"size" => 40,
        "value" => "../bak/o-blog".obdate("Ymd",time())."_".M_random(8).".xml"
	),1);	

	$FORM->formfooter();
}

//RSS 2.0 数据导出 - 执行
if($action == "doRssExport") {
	@set_time_limit(600);
	$time_start = @getmicrotime();
	$num_in_file = intval($_POST['num_in_file']);
	$path = checkPost(trim($_POST['path']));
	$path = str_replace("../","",$path);
	
	if($num_in_file <= 0) {
		$FORM->ob_exit("记录数填写有错误");
	}
	$data_header = "<?xml version=\"1.0\" encoding=\"gb2312\" ?>\n<rss version=\"2.0\">\n<channel about=\"{$blogurl}\">\n<title>{$blogName}</title>\n<link>{$blogurl}</link>\n<description>{$discribe}</description>\n<language>zh-cn</language>\n<copyright>O-blog</copyright>\n\n";
	$data_bottom = "</channel>\n</rss>";
	
	$goindex = 0;
	$filename_index = 0;
	$blogNum = $DB->query("SELECT count(*) FROM {$mysql_prefix}blog");
	$blogs = $DB->query("SELECT * FROM {$mysql_prefix}blog ORDER BY id ASC");

	$FORM->div_top(array("title" => "正在导出RSS 2.0 文件..."));
	while($blog = $DB->fetch_array($blogs)) {
		if($goindex == 0) {
			$file_dir = dirname($path);
			$file_name = basename($path);
			$file_extension = getextension($file_name);
			$file_onlyname = str_replace(".".$file_extension,"",$file_name);
			$path_new = $file_dir."/".$file_onlyname."_".$filename_index.".".$file_extension;
			echo "正在导出 ".$path_new." .............................. [成功]<br>";
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
	echo "<br />RSS 2.0 数据导出完成，共耗时 {$time_used} 秒";
	echo "<script>topdiv.innerText = \"RSS 数据导出完成\"</script>";
	$FORM->div_bo();
}

//RSS 2.0 数据导入 - 界面
if($action == "rssImport") {
	chdir("admin");
	$dir = "bak";
	$FORM->js_checkall();
    $FORM->formheader(array(
		"title"   => "RSS 数据导入",
		"action"  => "admin.php?action=delFile",
		"colspan" => "4"
	));
	echo "<tr><td class=\"tblhead\" colspan=\"4\">支持任何标准的 RSS 2.0 数据的导入。导入后，您需要重建静态页。<br />通过RSS导入的日志，只能导入标题、发表时间和内容，其他的数据不会导入。</td></tr>";
	echo "<tr bgcolor=\"#F3F3F3\">\n";
	echo "<td align=\"center\"><b>文件名</b></td>\n";
	echo "<td align=\"center\"><b>文件大小</b></td>\n";
	echo "<td align=\"center\"><b>导入</b></td>\n";
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
			echo "<td align=\"center\">[<a href=\"admin.php?action=rssImportSort&amp;filename={$file}\" >导入</a>]</td>";
			echo "<td align=\"center\">";
			echo "<input type=\"checkbox\" name=\"file[]\" value=\"../".$dir."/".$file."\" class=\"nonebg\">";
			echo "</td>\n";
			echo "</tr>";
		}
    }
	closedir($handle);
    $FORM->formfooter(array("colspan" => "4","button" =>array("submit"=>array("value"=>"删除"))));
}

//RSS 2.0 数据导入 - 选择分类
if($action == "rssImportSort") {
	$filename = trim($_GET['filename']);
	$classes = $DB->query("SELECT * FROM `".$mysql_prefix."class`");
	while($classRe = $DB->fetch_array($classes)) {
		$class[$classRe['id']] = $classRe['classname'];
	}
	$FORM->formheader(array(
		"title"   => "RSS 数据导入 - 选择导入分类",
		"action"  => "admin.php?action=doRssImport",
		"colspan" => "2"
	));
	echo "<tr><td class=\"tblhead\" colspan=\"2\"><b>说明：</b>将要导入的RSS文件为: {$filename}</td></tr>";
	$FORM->makeselect(array(
		"text"  => "选择类别",
		"note"  => "选择您要将RSS文件导入的分类",
		"name"  => "class",
		"option" => $class,
	),0);
	$FORM->makeselect(array(
		"text"  => "选择源RSS文件的编码",
		"note"  => "程序将会把编码转换为O-blog使用的GB2312编码",
		"name"  => "encode",
		"option" => array("GB2312" => "GB2312","UTF-8" => "UTF-8"),
	),0);
	$FORM->makeselect(array(
		"text"  => "选择源RSS文件存储内容的方式",
		"note"  => "程序将把它们转化成UBB存储",
		"name"  => "htmlubb",
		"option" => array("UBB" => "UBB","HTML" => "HTML"),
	),0);
	$FORM->makehidden(array("name" => "filename","value" => $filename));
	$FORM->formfooter();
}

//RSS 2.0 数据导入 - 执行导入数据
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
			$FORM->ob_exit("RSS数据导入出错");
		}
	}
	if($makehtml) {
		$HTML->makeindex();
	}
	$FORM->ob_exit("RSS数据导入完成");
}

//执行SQL - 界面
if($action == "runsql") {
	$FORM->formheader(array("title" => "执行SQL查询","action" => "admin.php?action=dorunsql","name" => "form"));
	echo "<tr><td class=\"tblhead\" colspan=\"2\"><b>警告：</b>此功能可能会破坏甚至删除您的数据库中的数据，请谨慎使用。</td></tr>";
	$FORM->maketextarea(array("text" => "SQL查询","name" => "sql","rows" => "20","cols" => "87","value" => "","extra" => "style=\"font-family:Courier New;font-size: 12px;\""));
	$FORM->formfooter();
}

//执行SQL -	执行 
if($action == "dorunsql") {
	$sql = $_POST['sql'];
	if(empty($sql)) {
		$FORM->ob_exit("请输入SQL查询");
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
	$FORM->ob_exit( $sqlerror ? "SQL语句执行出错" : "SQL执行成功完成" );
}

//添加自动链接 - 界面
if($action == "addAutolink") {
	$FORM->formheader(array("title" => "添加自动链接","action" => "admin.php?action=doaddAutolink","name" => "form"));
	echo "<tr><td class=\"tblhead\" colspan=\"2\"><b>说明：</b>当您的日志中出现与下面词语匹配的词时，将会被加上指定的链接。此功能要求./admin/class/autolink.php文件可写(777)</td></tr>";
	$FORM->makeinput(array(
				"text"  => "链接关键字",
				"note"  => "被匹配的关键字，不区分大小写",
				"name"  => "keyword",
				"size"  => 50,
            ));
	$FORM->makeinput(array(
				"text"  => "链接URL",
				"note"  => "关键字指向的URL",
				"name"  => "url",
				"size"  => 50,
            ));
	$FORM->formfooter();
}

//添加自动链接 - 执行
if($action == "doaddAutolink") {
	if(file_exists("admin/class/autolink.php")) {
		$linkfilepath = "admin/class/autolink.php";
	} elseif(file_exists("class/autolink.php")) {
		$linkfilepath = "class/autolink.php";
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
		$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
	} else {
		$FORM->ob_exit("无法找到文件 ./admin/class/autolink.php");
	}
	require_once($linkfilepath);
	
	//增加一个成员
	$_POST['keyword'] = trim($_POST['keyword']);
	$_POST['url'] = trim($_POST['url']);
	$autolink[] = array(
		"keyword" => $_POST['keyword'],
		"url" => $_POST['url'],
	);
	
	//写入文件
	$data = "\$autolink = ".var_export($autolink,TRUE).";";
	$fp = @fopen($linkfilepath,"wb");
	@flock($fp, LOCK_EX);
	@fwrite($fp,"<?php\r\n");
	$addok = @fwrite($fp, $data);
	@fwrite($fp,"\r\n?>");
	@flock($fp, LOCK_UN);
	if($addok) {
		$FORM->ob_exit("添加成功");
	} else {
		$FORM->ob_exit("无法写入设定，请确认autolink.php文件是可写的");
	}
}

//编辑自动链接 - 列表
if($action == "editAutolink") {
	$FORM->js_checkall();
	$FORM->formheader(array(
		"title"   => "管理自动连接",
		"action"  => "admin.php?action=updateAutolink",
		"colspan" => "4"
	));
	echo "<tr><td class=\"tblhead\" colspan=\"4\"><b>说明：</b>当您的日志中出现与下面词语匹配的词时，将会被加上指定的链接。此功能要求./admin/class/autolink.php文件可写(777)</td></tr>";
	echo "<tr bgcolor=\"#F3F3F3\">\n";
	echo "<td align=\"center\"><b>文件字</b></td>\n";
	echo "<td align=\"center\"><b>链接</b></td>\n";
	echo "<td align=\"center\"><b>操作</b></td>\n";
	echo "</tr>\n";
	
	if(file_exists("admin/class/autolink.php")) {
		$linkfilepath = "admin/class/autolink.php";
	} elseif(file_exists("class/autolink.php")) {
		$linkfilepath = "class/autolink.php";
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
		$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
	} else {
		$FORM->ob_exit("无法找到文件 ./admin/class/autolink.php");
	}
	require_once($linkfilepath);

	foreach($autolink as $key=>$val) {
		echo "<tr ".$FORM->getrowbg().">";
		echo "<td align=\"center\"><input class=\"formfield\" type=\"text\" name=\"keyword[]\" size=\"30\" maxlength=\"200\" value=\"{$val['keyword']}\" ></td>";
		echo "<td align=\"center\"><input class=\"formfield\" type=\"text\" name=\"url[]\" size=\"50\" maxlength=\"200\" value=\"{$val['url']}\" ></td>";
		echo "<td align=\"center\">";
		echo "[<a href=\"admin.php?action=delAutolink&id={$key}\">删除</a>]";
		echo "</td>\n";
		echo "</tr>";
	}

    $FORM->formfooter(array("colspan" => "4","button" =>array("submit"=>array("value"=>"更新"))));
}

//更新自动链接 - 执行
if($action == "updateAutolink") {
	if(count($_POST['keyword']) !== count($_POST['url'])) {
		$FORM->ob_exit("更新出现错误，请确认填写的表单是正确的");
	}
	array_walk($_POST['keyword'],"trim");
	array_walk($_POST['url'],"trim");
	foreach($_POST['keyword'] as $key=>$val) {
		$autolink[] = array(
			"keyword" => $val,
			"url" => $_POST['url'][$key],
		);
	}
	//写入文件
	if(file_exists("admin/class/autolink.php")) {
		$linkfilepath = "admin/class/autolink.php";
	} elseif(file_exists("class/autolink.php")) {
		$linkfilepath = "class/autolink.php";
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
		$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
	} else {
		$FORM->ob_exit("无法找到文件 ./admin/class/autolink.php");
	}

	$data = "\$autolink = ".var_export($autolink,TRUE).";";
	$fp = @fopen($linkfilepath,"wb");
	@flock($fp, LOCK_EX);
	@fwrite($fp,"<?php\r\n");
	$addok = @fwrite($fp, $data);
	@fwrite($fp,"\r\n?>");
	@flock($fp, LOCK_UN);
	if($addok) {
		$FORM->ob_exit("更新成功");
	} else {
		$FORM->ob_exit("无法写入设定，请确认autolink.php文件是可写的");
	}
}

//删除自动连接 - 执行
if($action == "delAutolink") {
	if(file_exists("admin/class/autolink.php")) {
		$linkfilepath = "admin/class/autolink.php";
	} elseif(file_exists("class/autolink.php")) {
		$linkfilepath = "class/autolink.php";
	} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
		$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
	} else {
		$FORM->ob_exit("无法找到文件 ./admin/class/autolink.php");
	}
	require_once($linkfilepath);
	$id = intval($_GET['id']);
	unset($autolink[$id]);

	//重新排列数组
	$newautolink = array_values($autolink);
	//写入文件
	$data = "\$autolink = ".var_export($newautolink,TRUE).";";
	$fp = @fopen($linkfilepath,"wb");
	@flock($fp, LOCK_EX);
	@fwrite($fp,"<?php\r\n");
	$addok = @fwrite($fp, $data);
	@fwrite($fp,"\r\n?>");
	@flock($fp, LOCK_UN);
	if($addok) {
		$FORM->ob_exit("删除成功");
	} else {
		$FORM->ob_exit("无法写入设定，请确认autolink.php文件是可写的");
	}
}

$FORM->cpfooter();
getlog();
?>