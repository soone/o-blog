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

// ��ȡ�ͻ���IP
function getip() {
	if (isset($_SERVER)) {
		if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) {
			$realip = $_SERVER[HTTP_X_FORWARDED_FOR];
		} elseif (isset($_SERVER[HTTP_CLIENT_IP])) {
			$realip = $_SERVER[HTTP_CLIENT_IP];
		} else {
			$realip = $_SERVER[REMOTE_ADDR];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")) {
			$realip = getenv( "HTTP_X_FORWARDED_FOR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$realip = getenv("HTTP_CLIENT_IP");
		} else {
			$realip = getenv("REMOTE_ADDR");
		}
	}
	return $realip;
}

// ��ȡ���ݿ��С��λ
function get_real_size($size) {
	$kb = 1024;         // Kilobyte
	$mb = 1024 * $kb;   // Megabyte
	$gb = 1024 * $mb;   // Gigabyte
	$tb = 1024 * $gb;   // Terabyte

	if($size < $kb) {
		return $size." B";
	}else if($size < $mb) {
		return round($size/$kb,2)." KB";
	}else if($size < $gb) {
		return round($size/$mb,2)." MB";
	}else if($size < $tb) {
		return round($size/$gb,2)." GB";
	}else {
		return round($size/$tb,2)." TB";
	}
}

// �û���¼��֤
function checkuser($username, $password) {
	global $DB, $db_prefix, $userinfo;
	$username = htmlspecialchars(trim($username));
	$username = trim($username);
	$userinfo = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."config WHERE username='".$username."' AND password='".$password."'");
	if (empty($userinfo)) {
		return false;
	} else {
		return true;
	}
}

// ��ҳ
function page($num, $perpage, $curr_page, $mpurl) {
	$multipage = '';
	if($num > $perpage) {
		$page = 10;
		$offset = 2;

		$pages = ceil($num / $perpage);
		$from = $curr_page - $offset;
		$to = $curr_page + $page - $offset - 1;
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				if($from < 1) {
					$to = $curr_page + 1 - $from;
					$from = 1;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$to = $page;
					}
				} elseif($to > $pages) {
					$from = $curr_page - $pages + $to;
					$to = $pages;
						if(($to - $from) < $page && ($to - $from) < $pages) {
							$from = $pages - $page + 1;
						}
				}
			}
			$pre_page = ($curr_page > 1) ? $curr_page - 1 : 1;
			$next_page = ($curr_page < $to) ? $curr_page + 1 : $to;
			$multipage .= "<a href=\"".$mpurl."&amp;page=1\" class=\"multi_page\"><b>&laquo;</b></a> <a href=\"".$mpurl."&amp;page=".$pre_page."\" class=\"multi_page\"><b>&#8249;</b></a> ";
			for($i = $from; $i <= $to; $i++) {
				if($i != $curr_page) {
					$multipage .= "<a href=\"".$mpurl."&amp;page=$i\" class=\"multi_page\">$i</a> ";
				} else {
					$multipage .= '<u><b>'.$i.'</b></u> ';
				}
			}
			$multipage .= $pages > $page ? " ... <a href=\"".$mpurl."&amp;page=$pages\" class=\"multi_page\">$pages</a> <a href=\"".$mpurl."&amp;page=$next_page\" class=\"multi_page\"><b>&#8250;</b></a> <a href=\"".$mpurl."&amp;page=$pages\" class=\"multi_page\"><b>&raquo;</b></a>" : " <a href=\"".$mpurl."&amp;page=$next_page\" class=\"multi_page\"><b>&#8250;</b></a> <a href=\"".$mpurl."&amp;page=$pages\" class=\"multi_page\"><b>&raquo;</b></a>";
	}
	return $multipage;
}

//E-mail��ʽ���
function CheckEmail($str)
{
	if (ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)+$", $str))
	{
		return true;
	}
	else
	{
		return false;
	}
}

//�Ƿ��½
function isLogin() {
	global $FORM,$DB,$mysql_prefix;
	//��֤����
	$ob_login = 0;
	$realpassword_md5 = $DB->fetch_one("SELECT password FROM {$mysql_prefix}admin WHERE id='{$_COOKIE['ob_userid']}'");
	if($realpassword_md5 == trim($_COOKIE['ob_password'])) {
		$ob_login = 1;
	}
	//��֤IP
	if(trim($_COOKIE['ob_ip']) == md5(getip())) {
		$ob_ip_login = 1;
	} else {
		$ob_ip_login = 0;
	}
	if(isset($_COOKIE['ob_login']) && @$_COOKIE['ob_login'] == "o-blog" && $ob_login && $ob_ip_login) {
		Return true;
	} else {
		$FORM->login();
		exit;
	}
}

//ִ�� addslashes
function checkPost($data)
{
	if(!get_magic_quotes_gpc()) {
		return is_array($data)?array_map('rAddSlashes',$data):addslashes($data);
	} else {
		Return $data;
	}
}

//����SQL
function sqldumptable($table, $fp=0) {
	global $DB;
	$tabledump  = "DROP TABLE IF EXISTS $table;\n";
	$tabledump .= "CREATE TABLE $table (\n";
	$firstfield = 1;
	$fields = $DB->query("SHOW FIELDS FROM $table");
	while ($field = $DB->fetch_array($fields)) {
		if (!$firstfield) {
			$tabledump .= ",\n";
		} else {
			$firstfield = 0;
		}
		$tabledump .= "   $field[Field] $field[Type]";
		if (!empty($field["Default"])) {
			$tabledump .= " DEFAULT '$field[Default]'";
		}
		if ($field['Null'] != "YES") {
			$tabledump .= " NOT NULL";
		}
		if ($field['Extra'] != "") {
			$tabledump .= " $field[Extra]";
		}
	}
	$DB->free_result($fields);
	$keys = $DB->query("SHOW KEYS FROM $table");
	while ($key = $DB->fetch_array($keys)) {
		$kname = $key['Key_name'];
		if ($kname != "PRIMARY" and $key['Non_unique'] == 0) {
			$kname="UNIQUE|$kname";
		}
		if(!is_array($index[$kname])) {
			$index[$kname] = array();
		}
		$index[$kname][] = $key['Column_name'];
	}
	$DB->free_result($keys);

	while(list($kname, $columns) = @each($index)) {
		$tabledump .= ",\n";
		$colnames=implode($columns,",");
		if ($kname == "PRIMARY") {
			$tabledump .= "   PRIMARY KEY ($colnames)";
		} else {
			if (substr($kname,0,6) == "UNIQUE") {
				$kname=substr($kname,7);
			}
			$tabledump .= "   KEY $kname ($colnames)";
		}
	}
	$tabledump .= "\n);\n\n";
	if ($fp) {
		fwrite($fp,$tabledump);
	} else {
		echo $tabledump;
	}
	$rows      = $DB->query("SELECT * FROM $table");
	$numfields = mysql_num_fields($rows);
	while ($row = $DB->fetch_array($rows)) {
		$tabledump    = "INSERT INTO $table VALUES(";
		$fieldcounter = -1;
		$firstfield   = 1;
		while (++$fieldcounter<$numfields) {
			if (!$firstfield) {
				$tabledump.=", ";
			} else {
				$firstfield=0;
			}

			if (!isset($row[$fieldcounter])) {
				$tabledump .= "NULL";
			} else {
				$tabledump .= "'".mysql_escape_string($row[$fieldcounter])."'";
			}
		}
		$tabledump .= ");\n";
		if ($fp) {
			fwrite($fp,$tabledump);
		} else {
			echo $tabledump;
		}
	}
	$DB->free_result($rows);
}

// ��¼��̨����
function getlog() {
	global $DB,$mysql_prefix;
	$action = isset($_GET['action']) ? $_GET['action'] : "";
	if (isset($action)) {
		$script = $_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
		$DB->query("INSERT INTO ".$mysql_prefix."adminlog (action,script,date,ip) VALUES ('".htmlspecialchars(trim($action))."','".htmlspecialchars(trim($script))."','".time()."','".getip()."')");
	}
	$all = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."adminlog`");
	if($all > 500) {
		$DB->query("DELETE FROM `".$mysql_prefix."adminlog` ORDER BY `id` ASC LIMIT 50");
	}
}

//��¼��½�ɹ�
function loginsucceed($username) {
	global $DB,$mysql_prefix;
	$DB->query("INSERT INTO `".$mysql_prefix."loginlog` (username,date,ip,result) VALUES	('".$username."','".time()."','".getip()."','1')");
	$all = $DB->fetch_one("SELECT count(*) FROM `".$mysql_prefix."loginlog`");
	if($all > 500) {
		$DB->query("DELETE FROM `".$mysql_prefix."loginlog` ORDER BY `id` ASC LIMIT 50");
	}
}

//��¼��½ʧ��
function loginfaile($username) {
	global $DB,$mysql_prefix;
	$DB->query("INSERT INTO `".$mysql_prefix."loginlog` (username,date,ip,result) VALUES	('".$username."','".time()."','".getip()."','0')");
}

//�и��ַ���
function cn_substr($string,$sublen)
{
	if($sublen>=strlen($string))
	{
		return $string;
	}
	$s="";
	for($i=0;$i<$sublen;$i++)
	{
		if(ord($string{$i})>127) 
		{
			$s.=$string{$i}.$string{++$i};
			continue;
		}else{
			$s.=$string{$i};
		continue;
		} 
	}
	return $s."...";
}

// ����ļ���չ��
function getextension($filename) {
	return substr(strrchr($filename, "."), 1);
}

//����ļ����ļ���
function getmainfilename($filename) {
	Return substr($filename,0,strrpos($filename,"."));
}

// �ϴ���������
function acceptupload() {
	global $DB, $FORM, $mysql_prefix, $attachment, $attachment_size, $attachment_name, $options, $allow_file_type;
	@chdir("admin");
	$attachment_name = strtolower($attachment_name);
	$extension       = getextension($attachment_name);

	if (is_uploaded_file($attachment)) {
		if (!in_array($extension, array('gif', 'jpg', 'jpeg', 'png'))) {
			$path = "../uploadfiles/".$attachment_name;
		} else {
			//��ˮӡ
			$path = "../uploadfiles/".$attachment_name;
		}

		if(!in_array($extension,$allow_file_type)) {
			$FORM->ob_exit("�ļ���ʽ������!");
		}

		@move_uploaded_file($attachment, $path);
		@chmod ($path, 0666);
		$attachment = $path;
		
		$filesize=@filesize($attachment);
		if ($filesize != $attachment_size) {
			@unlink($attachment);
			$FROM->ob_exit("�ϴ����������������!");
		}
	}
	Return true;
}

function acceptupload_in_editor() {
	global $DB, $FORM, $mysql_prefix, $attachment, $attachment_size, $attachment_name, $options, $allow_file_type;
	chdir("admin");
	$attachment_name = strtolower($attachment_name);
	$extension       = getextension($attachment_name);

	if (is_uploaded_file($attachment)) {
		if (!in_array($extension, array('gif', 'jpg', 'jpeg', 'png'))) {
			$path = "../uploadfiles/".$attachment_name;
		} else {
			//��ˮӡ
			$path = "../uploadfiles/".$attachment_name;
		}

		if(!in_array($extension,$allow_file_type)) {
			echo "<a href=upload.php?action=upload_form>�ļ���ʽ������! ���ڷ��ء���</a>";
			echo "<meta http-equiv=\"refresh\" content=\"3;URL=javascript:history.go(-1);\"\">";
			die();
		}

		@move_uploaded_file($attachment, $path);
		@chmod ($path, 0666);
		$attachment = $path;
		
		$filesize=@filesize($attachment);
		if ($filesize != $attachment_size) {
			@unlink($attachment);
			echo "<a href=upload.php?action=upload_form>�ϴ����������������! ���ڷ��ء���</a>";
			echo "<meta http-equiv=\"refresh\" content=\"3;URL=javascript:history.go(-1);\"\">";
			die();
		}
	}
	Return true;
}

//ǰ̨������Ϣ
function ob_exit($msg, $url="",$target="") {
	if(empty($url)) {
		$url = "javascript:history.go(-1);";
	}
	if(empty($target)) {
		$target = "";
	} else {
		$target = "target=\"".$target."\"";
	}

	echo "<?xml version=\"1.0\" encoding=\"gb2312\"?>";
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
	echo "<head>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\" />";
	echo "<meta http-equiv=\"refresh\" content=\"3;URL=".$url."\">\n";
	echo "<style type=\"text/css\">";
	echo "<!--";
	echo "table {";
	echo "font-family: \"Verdana\", \"Arial\", \"Helvetica\", \"sans-serif\";";
	echo "font-size: 12px;";
	echo "}";
	echo "body {";
	echo "background-color: #F3F3F3;";
	echo "}";
	echo "a:link,a:visit,a:hover,a:active {";
	echo "color: #000000;";
	echo "}";
	echo "-->";
	echo "</style>";
	echo "</head>";
	echo "<body>";
	echo "<table width=\"350\" border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\" class=\"ob\">";
	echo "<tr>";
	echo "<td bgcolor=\"#FFFFFF\"> ";
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"ob\">";
	echo "<tr> ";
	echo "<td bgcolor=\"#F3F3F3\"><strong>O-BLOG ������Ϣ:</strong></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"center\"><br>".$msg."<br><a href=".$url." ".$target.">�������ﷵ��</a><br><br></td>\n";
	echo "</tr>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</body>";
	echo "</html>";
	exit;
}

//��ѯPHP����
function getcon($varName) {
	switch($res = get_cfg_var($varName)) {
		case 0:
		return NO;
		break;
		case 1:
		return YES;
		break;
		default:
		return $res;
		break;
	}
}

//����ַ���
function M_random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

//����ID��ȡ��̬�ļ���·��
function getHtmlPath($id) {
	global $DB,$mysql_prefix,$extraname,$keep_page_way,$archive_folder,$makehtml;
	$data_and_name = $DB->fetch_one_array("SELECT `date`,`filename` FROM ".$mysql_prefix."blog WHERE `id` = ".$id);
	$date = $data_and_name['date'];
	$blogFileName = $data_and_name['filename'];
	$filename = ($blogFileName == "") ? $id : $blogFileName;
	if(!$makehtml) {
		Return "index.php?id=".$id;
	}
	if($keep_page_way == "day") {
		$path = $archive_folder."/".obdate("Y",$date)."/".obdate("m",$date)."/".obdate("d",$date)."/".$filename.".".$extraname;
	} elseif($keep_page_way == "month") {
		$path = $archive_folder."/".obdate("Y",$date)."/".obdate("m",$date)."/".$filename.".".$extraname;
	} elseif($keep_page_way == "year") {
		$path = $archive_folder."/".obdate("Y",$date)."/".$filename.".".$extraname;
	} elseif($keep_page_way == "all") {
		$path = $archive_folder."/".$filename.".".$extraname;
	} else {
		$path = $archive_folder."/".$filename.".".$extraname;
	}
	Return $path;
}

//����Ȩ���ж�
function ifAllow() {
	global $action,$DB,$FORM,$mysql_prefix,$current_auth_char;
	if(@!strstr($current_auth_char,$action)) {
		$FORM->ob_exit("��û��Ȩ��ִ�д˲���","");
	}
}

//��ǰ��ϸʱ��
function getmicrotime(){ 
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

//����trackback ping
function ping($host,$title,$url,$excerpt,$blog_name) {
	global $UBB;
	$host = str_replace('http://', '', $host);
	$path = explode('/', $host);
	$host = $path[0];
	unset($path[0]);
	$path = '/' . implode('/', $path);

	$excerpt = clearUbb($excerpt);
	$excerpt = cn_substr($excerpt,252);

	$fp = @fsockopen($host, 80, $errno, $errstr, 30);
	if(!$fp) {
		Return flase;
	}

	$query  = 'title=' . rawurlencode($title);
	$query .= '&url=' . rawurlencode($url);
	$query .= '&excerpt=' . rawurlencode($excerpt);
	$query .= '&blog_name=' . rawurlencode($blog_name);

	$out = 'POST ' . $path . ' HTTP/1.1' . "\r\n";
	$out .= 'Host: ' . $host . "\r\n";
	$out .= 'Connection: close' . "\r\n";
	$out .= 'Content-Length: ' . strlen($query) . "\r\n";
	$out .= 'Content-Type: application/x-www-form-urlencoded; charset=iso-8859-1' . "\r\n\r\n";
	$out .= $query . "\r\n";

	fwrite($fp, $out);
	fclose($fp);
	return true;
}

//��ÿ��һ�����ݴ�ɢ�������� 
function split_word($char) {
	$char = str_replace("\n",",",$char);
	$char = str_replace("\r",",",$char);
	$char = str_replace(",,",",",$char);
	$char = explode(",",$char);
	Return $char;
}

//�ж��Ƿ�ر�blog
function is_close_blog() {
	global $close_blog,$close_reason;
	if($close_blog) {
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
		echo "<html xmlns='http://www.w3.org/1999/xhtml'>";
		echo "<head>";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312' />";
		echo "<title>Blog ��ʱ�ر�</title>";
		echo "<style type='text/css'>";
		echo "<!--";
		echo ".close_blog {";
		echo "font-family: Verdana, Arial, Helvetica, sans-serif;";
		echo "font-size: 12px;";
		echo "text-align: center;";
		echo "margin-right: auto;";
		echo "margin-left: auto;";
		echo "margin-top: 100px;";
		echo "border: 1px solid #999999;";
		echo "background-color: #FFFFFF;";
		echo "padding-top: 20px;";
		echo "padding-right: 8px;";
		echo "padding-bottom: 20px;";
		echo "padding-left: 8px;";
		echo "width: 60%;";
		echo "}";
		echo "body {";
		echo "background-color: #F2F2F2;";
		echo "}";
		echo "-->";
		echo "</style>";
		echo "</head>";
		echo "<body>";
		echo "<div class='close_blog' id='close_blog'>{$close_reason}</div>";
		echo "</body>";
		echo "</html>";
		die();
	}
}

//��SQL�ֳɵ���
function splitsql($sql){
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == "#" ? NULL : $query;
		}
		$num++;
	}
	return($ret);
}

//������������Ϊ���� RSS ����
function rssrollback ($rawdata) {
	$rawdata=str_replace("\r", '', $rawdata);
	$rawdata=str_replace("\n", '', $rawdata);
	$rawdata=str_replace("<![CDATA[", '', $rawdata);
	$rawdata=str_replace("]]>", '', $rawdata);
	preg_match_all("/<item>(.+?)<\/item>/is", $rawdata, $array_match);
	$xmlall=$array_match[1];
	if (!is_array($xmlall)) $array_insert[]=parserss($xmlall);
	else {
		foreach ($xmlall as $xml) {
			$array_insert[]=parserss($xml);
		}
	}
	return $array_insert;
}

function parserss ($xml) {	$count_items=preg_match("/<title>(.+?)<\/title>(.+?)<pubDate>(.+?)<\/pubDate>(.+?)<description>(.+?)<\/description>/is", $xml, $array_match);
	if ($count_items!=0) {
		$title=addslashes($array_match[1]);
		$time=strtotime($array_match[3]);
		if (preg_match("/<content:encoded>(.+?)<\/content:encoded>/is", $xml, $array_match_possible)!=0) $content=addslashes($array_match_possible[1]);
		else $content=addslashes($array_match[5]);
	}
	return array('title'=>$title, 'time'=>$time, 'content'=>$content);
}

// HTML תΪ UBB ����
function html2ubb($str) {
	$str = preg_replace('/\r/',"",$str);
	$str = preg_replace('/on(load|click|dbclick|mouseover|mousedown|mouseup)="[^"]+"/i',"",$str);
	$str = preg_replace('/<script[^>]*?>([\w\W]*?)<\/script>/i',"",$str);
	
	$str = preg_replace('/<a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/i',"\n[url=$1]$2[/url]\n",$str);
	
	$str = preg_replace('/<font[^>]+color=([^ >]+)[^>]*>(.*?)<\/font>/i',"\n[color=$1]$2[/color]\n",$str);
	
	$str = preg_replace('/<img[^>]+src="([^"]+)"[^>]*>/i',"\n[img]$1[/img]\n",$str);
	
	$str = preg_replace('/<p[^>]*?>/i',"\n\n",$str);
	$str = preg_replace('/<([\/]?)b>/i',"[$1b]",$str);
	$str = preg_replace('/<([\/]?)strong>/i',"[$1b]",$str);
	$str = preg_replace('/<([\/]?)u>/i',"[$1u]",$str);
	$str = preg_replace('/<([\/]?)i>/i',"[$1i]",$str);
	
	$str = preg_replace('/&nbsp;/'," ",$str);
	$str = preg_replace('/&amp;/',"&",$str);
	$str = preg_replace('/&quot;/',"\"",$str);
	$str = preg_replace('/&lt;/',"<",$str);
	$str = preg_replace('/&gt;/',">",$str);
	
	$str = preg_replace('/<br>/i',"\n",$str);
	$str = preg_replace('/<br\/>/i',"\n",$str);
	$str = preg_replace('/<br \/>/i',"\n",$str);
	$str = preg_replace('/<[^>]*?>/',"",$str);
	$str = preg_replace('/\[url=([^\]]+)\]\n(\[img\]\1\[\/img\])\n\[\/url\]/',"$2",$str);
	$str = preg_replace('/\n+/',"\n",$str);
	
	return $str;
}

// ʱ��
function fetch_timezone() {
	$timezones = array(
		'-12'	=> '(GMT -12:00)&nbsp;���������, ����ֻ���',
		'-11'	=> '(GMT -11:00)&nbsp;��;��, ��Ħ��Ⱥ��',
		'-10'	=> '(GMT -10:00)&nbsp;������',
		'-9'	=> '(GMT -9:00)&nbsp;����˹��',
		'-8'	=> '(GMT -8:00)&nbsp;(����������׼ʱ��)',
		'-7'	=> '(GMT -7:00)&nbsp;(ɽ��ʱ��)',
		'-6'	=> '(GMT -6:00)&nbsp;(�в�ʱ��), ī�����',
		'-5'	=> '(GMT -5:00)&nbsp;����ʱ��(�����ͼ��ô�), �����, ����',
		'-4'	=> '(GMT -4:00)&nbsp;������ʱ��(���ô�), ������˹, ����˹',
		'-3'	=> '(GMT -3:00)&nbsp;����, ����ŵ˹����˹, ���ζ�',
		'-2'	=> '(GMT -2:00)&nbsp;�д�����',
		'-1'	=> '(GMT -1:00)&nbsp;���ٶ�Ⱥ��, ��ý�Ⱥ��',
		'+0'	=> '(GMT +0:00)&nbsp;��ŷʱ��, �׶�, ��˹��',
		'+1'	=> '(GMT +1:00)&nbsp;CET(��ŷʱ��)',
		'+2'	=> '(GMT +2:00)&nbsp;EET(��ŷʱ��), �Ϸ�',
		'+3'	=> '(GMT +3:00)&nbsp;Ī˹��, �͸��, �����',
		'+4'	=> '(GMT +4:00)&nbsp;��������, ��˹����, �Ϳ�, �ڱ���˹',
		'+5'	=> '(GMT +5:00)&nbsp;Ҷ�����ձ�, ��˹����, ������',
		'+6'	=> '(GMT +6:00)&nbsp;���ײ�, �￨, ���ǲ�����',
		'+7'	=> '(GMT +7:00)&nbsp;����, ����, �żӴ�',
		'+8'	=> '(GMT +8:00)&nbsp;����ʱ��, ��˼, �¼���, ���, ̨��',
		'+9'	=> '(GMT +9:00)&nbsp;����, ����, ����, ����',
		'+10'	=> '(GMT +10:00)&nbsp;(�Ĵ����Ƕ�����׼ʱ��), �ص�',
		'+11'	=> '(GMT +11:00)&nbsp;��ӵ�, ������Ⱥ��',
		'+12'	=> '(GMT +12:00)&nbsp;�¿���, �����, 쳼�'
	);
	return $timezones;
}

// ��ʽ��ʱ��
function obdate($format, $timestamp) {
	global $clienttimezone,$servertimezone;
	$time = $timestamp + ($clienttimezone - $servertimezone) * 3600;
	if ($time < 0) {
		$time = 0;
	}
	return date($format, $time);
}

if(!function_exists("file_get_contents")) {
	function file_get_contents($filename) {
		 if(($contents = file($filename))) {
			  $contents = implode('', $contents);
			  return $contents;
		 } else {
			 return false;
		 }
	}
}

if(!function_exists("file_put_contents")) {
	function file_put_contents($filename,$data) {
		$fp = @fopen($filename,"wb+");
		@fwrite($fp,$data);
		fclose($fp);
	}
}

?>