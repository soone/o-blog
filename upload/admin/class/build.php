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

class build {

	var $root = "";
	var $name = "html_article";

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
		echo "<td bgcolor=\"#F3F3F3\"><strong>O-BLOG 返回信息:</strong></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align=\"center\"><br>".$msg."<br><a href=".$url." ".$target.">请点击这里返回</a><br><br></td>\n";
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</body>";
		echo "</html>";
		exit;
	}
	
	function template($name) {
		global $DB,$mysql_prefix;
		$templateName = $DB->fetch_one("SELECT `template` FROM `".$mysql_prefix."config`");
		Return new SmartTemplate($this->root.'templates/'.$templateName.'/'.$name.'.htm');
	}
	function writeHtml($path,$data) {
		if(!$fp = @fopen($path,"wb")) {
			$this->ob_exit("无法生成文件 $path");
		}
		flock($fp, LOCK_EX);
		if(!@fwrite($fp,$data)) {
			flock($fp, LOCK_UN);
			$this->ob_exit("无法写入文件 $path");
		} else {
			flock($fp, LOCK_UN);
			Return true;
		}
	}
	function mDir($dirName) {
		$dirName = dirname($dirName);
		$dirName = str_replace("\\","/",$dirName);
		$dirNames = explode('/', $dirName);
		$total = count($dirNames) ;
		$temp = '';
		for($i=0; $i<$total; $i++) {
			$temp .= $dirNames[$i].'/';
			if (!is_dir($temp)) {
				$oldmask = umask(0);
				if (!@mkdir($temp, 0777)) $this->ob_exit("无法建立目录 $temp"); 
				umask($oldmask);
			}
		}
		return true;
	}
	function make($id) {
		global $DB,$mysql_prefix,$blogurl,$var,$makehtml,$showNum,$blogName,$TemplateName,$discribe,$index_show_number,$fullarticle,$date_format,$static_blog_name,$show_viewcount,$lastblog_cut_char,$servertimezone,$clienttimezone;
		require($this->root.'class/link.php');
		require($this->root.'class/calendar.php');
		require($this->root.'class/lastblog.php');
		require($this->root.'class/lastremark.php');
		require($this->root.'class/sort.php');
		require($this->root.'class/remark.php');
		
		$oneT = $this->template('show_article');
		$ubb = new Ubb();
		$ones = $DB->query("SELECT * FROM `".$mysql_prefix."blog` WHERE `id` = ".$id);
		if($DB->num_rows($ones) == 0) {
			$oneT->assign("id",0);
			$oneT->assign("title","日志不存在或者已经被删除");
		} else {
			while($oneRe = $DB->fetch_array($ones)) {
				$oneRe['content'] = trim($oneRe['content']);

				//替换关键字
				if(file_exists("admin/class/autolink.php")) {
					$linkfilepath = "admin/class/autolink.php";
				} elseif(file_exists("class/autolink.php")) {
					$linkfilepath = "class/autolink.php";
				} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/class/autolink.php")) {
					$linkfilepath = dirname($_SERVER['PHP_SELF'])."/class/autolink.php";
				} elseif(file_exists(dirname($_SERVER['PHP_SELF'])."/admin/class/autolink.php")) {
					$linkfilepath = dirname($_SERVER['PHP_SELF'])."/admin/class/autolink.php";
				} else {
					$FORM->ob_exit("无法找到文件 ./admin/class/autolink.php");
				}

				require($linkfilepath);
				if(count($autolink) != 0) {
					foreach($autolink as $key=>$val) {
						$pattern[] = "/(?<!http:\/\/)(".$val['keyword'].")(?![a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i";
						$replace[] = "[url={$val['url']}]\\1[/url]";
					}
					$oneRe['content'] = preg_replace($pattern,$replace,$oneRe['content']);
					unset($pattern,$replace);
				}

				$oneRe['content'] = str_replace(" ","&nbsp;",$oneRe['content']);
				$oneRe['content'] = str_replace("[separator]","",$oneRe['content']);
				if(intval($oneRe['allow_face'])) {
					$oneRe['content'] = qqface($oneRe['content'],"",$blogurl);
				}
				$date = $oneRe['date'];
				$path = $this->root.getHtmlPath($id);
				$ubb->setString($oneRe['content']);
				$oneRe['content'] = $ubb->parse();
				$oneT->assign("id",$oneRe['id']);
				$oneT->assign("title",$oneRe['title']);
				$oneT->assign("content",$oneRe['content']);
				$oneT->assign("path",$blogurl.$path);
				$oneT->assign("blogurl",$blogurl);
				$articleName = $oneRe['title'];
			}
		}
		require($this->root.'class/header.php');
		$main = $header_data;

		$article_data = $oneT->result();
		$article_data .= $remark_data;
		
		$blogurl = $DB->fetch_one("SELECT `blogurl`  FROM `".$mysql_prefix."config`");
		$blogdescribe = $DB->fetch_one("SELECT `blogdescribe` FROM ".$mysql_prefix."config");
		$discribe = trim($discribe);
		$mainT = template("html_article");
		$mainT->assign("discribe",$blogdescribe);
		$mainT->assign("link",$link_data);
		$mainT->assign("sort",$sort_data);
		$mainT->assign("main",$article_data);
		$mainT->assign("blogurl",$blogurl);
		$main .= $mainT->result();
		$boT = template('bo');
		$main .= $boT->result();
		
		$date = $DB->fetch_one("SELECT `date` FROM ".$mysql_prefix."blog WHERE `id` = ".$id);
		$path = $this->root.getHtmlPath($id);
		$this->mDir($path);
		if($this->writeHtml($path,$main)) {
			Return true;
		} else {
			Return false;
		}
	}
	function del($id) {
		global $DB,$mysql_prefix;
		$path = $this->root.getHtmlPath($id);
		if(file_exists($path)) {
			chmod($path,0777);
			unlink($path);
		}
	}
	
	function makeindex() {
		global $DB,$mysql_prefix,$blogurl,$makehtml,$articleNum,$var,$showNum,$blogName,$TemplateName,$discribe,$index_show_number,$fullarticle,$date_format,$static_blog_name,$show_viewcount,$lastblog_cut_char,$servertimezone,$clienttimezone;
		$inhtml = 1;
		require($this->root.'class/link.php');
		require($this->root.'class/calendar.php');
		require($this->root.'class/lastblog.php');
		require($this->root.'class/lastremark.php');
		require($this->root.'class/archive.php');
		require($this->root.'class/sort.php');
		require($this->root.'class/list.php');
		
		//获得当前日期
		$week_n = obdate("w",time());
		$week = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
		$today = obdate("Y 年 n 月 j 日 $week[$week_n]",time());  
		
		$blogdescribe = $DB->fetch_one("SELECT `blogdescribe` FROM ".$mysql_prefix."config");
		$discribe = trim($discribe);
		$blogName = $static_blog_name;
		require('class/header.php');
		$mainT = template("main");
		$mainT->assign("discribe",$blogdescribe);
		$mainT->assign("calendar",$calendar);
		$mainT->assign("link",$link_data);
		$mainT->assign("lastblog",$lastblog_data);
		$mainT->assign("lastRemark",$lastRemark_data);
		$mainT->assign("archive",$archive_data);
		$mainT->assign("sort",$sort_data);
		$mainT->assign("main",$main);
		$mainT->assign("today",$today);
		$main = $mainT->result();
		$main = $header_data.$main;
		$boT = template('bo');
		$main .= $boT->result();
		
		if($this->writeHtml("index.html",$main)) {
			Return true;
		} else {
			Return false;
		}
	}
	
}
?>