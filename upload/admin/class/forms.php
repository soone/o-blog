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

class cpForms {
	
	//表单头
	function formheader($arguments=array()) {
		global $_SERVER;
		if ($arguments['enctype']){
			$enctype="enctype=\"".$arguments['enctype']."\"";
		} else {
			$enctype="";
		}
		if (!isset($arguments['method'])) {
			$arguments['method'] = "post";
		}
		if (!isset($arguments['action'])) {
			$arguments['action'] = $_SERVER['PHP_SELF'];
		}
		if (!$arguments['colspan']) {
			$arguments['colspan'] = 2;
		}

		echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\">\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"#FFFFFF\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\">\n";
		echo "<form action=\"".$arguments['action']."\" ".$enctype." method=\"".$arguments['method']."\" name=\"".$arguments['name']."\" ".$arguments['extra'].">\n";
		if ($arguments['title'] != "") {
			echo "<tr id=\"cat\">\n";
			echo "	<td class=\"tblhead\" colspan=\"".$arguments['colspan']."\"><b>".$arguments['title']."</b></td>\n";
			echo "</tr>\n";
		}
	}
	
	//表单尾
	function formfooter($arguments=array()){
		echo "<tr>\n";
			if ($arguments['confirm']==1) {
				$arguments['button']['submit']['type'] = "submit";
				$arguments['button']['submit']['name'] = "submit";
				$arguments['button']['submit']['value'] = "确认";
				$arguments['button']['submit']['accesskey'] = "y";

				$arguments['button']['back']['type'] = "button";
				$arguments['button']['back']['value'] = "取消";
				$arguments['button']['back']['accesskey'] = "r";
				$arguments['button']['back']['extra'] = " onclick=\"history.back(1)\" ";
			} elseif (empty($arguments['button'])) {

				$arguments['button']['submit']['type'] = "submit";
				$arguments['button']['submit']['name'] = "submit";
				$arguments['button']['submit']['value'] = "提交";
				$arguments['button']['submit']['accesskey'] = "y";

				$arguments['button']['reset']['type'] = "reset";
				$arguments['button']['reset']['value'] = "重置";
				$arguments['button']['reset']['accesskey'] = "r";
			}

			if (empty($arguments['colspan'])) {
				$arguments['colspan'] = 2;
			}

			echo "<td colspan=\"".$arguments['colspan']."\" align=\"center\">\n";
			if (isset($arguments) AND is_array($arguments)) {
				foreach ($arguments['button'] AS $k=>$button) {
					if (empty($button['type'])) {
						$button['type'] = "submit";
					}
					echo " <input class=\"button\" accesskey=\"".$button['accesskey']."\" type=\"".$button['type']."\" name=\"".$button['name']."\" value=\"".$button['value']."\" ".$button['extra'].">\n";
				}
			}
			echo "</td></tr>\n";
			echo "</form>\n";
			echo "</table></td>\n";
			echo "</tr>\n";
			echo "</table>\n";

      }
	
	//TABLE头
	function tableheader($arguments=array()) {
		echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\">\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"#FFFFFF\"><table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\">\n";
		if ($arguments['title'] != "") {
			echo "<tr id=\"cat\">\n";
			echo "<td class=\"tblhead\" colspan=\"".$arguments['colspan']."\"><b>".$arguments['title']."</b></td>\n";
			echo "</tr>\n";
		}
	}

	//TABLE头2
	function tableheaderbig($arguments=array()) {
		echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\">\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"#FFFFFF\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"6\">\n";
		if ($arguments['title'] != "") {
			echo "<tr id=\"cat\">\n";
			echo "<td class=\"tblhead\" colspan=\"".$arguments['colspan']."\"><b>".$arguments['title']."</b></td>\n";
			echo "</tr>\n";
		}
		
	}

	//TABLE尾
	function tablefooter() {
		echo "</table></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

	//生成TD
	function maketd($arguments = array()) {
		echo "<tr ".$this->getrowbg()." nowrap>";
		foreach ($arguments AS $k=>$v) {
			echo "<td>".$v."</td>";
		}
		echo "</tr>\n";
	}
	
	//生成INPUT
	function makeinput($arguments = array(),$only=0) {
		if (empty($arguments['size'])) {
			$arguments['size'] = 35;
		}
		if (empty($arguments['maxlength'])) {
			$arguments['maxlength'] = 200;
		}
		if ($arguments['html']) {
			$arguments['value'] = htmlspecialchars($arguments['value']);
		}
		if (!empty($arguments['css'])) {
			$class = "class=\"$arguments[css]\"";
		} else {
			$class = "class=\"formfield\"";
		}
		if (empty($arguments['type'])) {
			$arguments['type'] = "text";
		}
		if(!isset($arguments['otherelement'])) {
			$arguments['otherelement'] = "";
		}
		echo "<tr ".$this->getrowbg($only)." nowrap>\n";
		echo "	<td><b>".$arguments['text']."</b><br>".$arguments['note']."</td>\n";
		echo "	<td><input ".$class." type=\"".$arguments['type']."\" name=\"".$arguments['name']."\" size=\"".$arguments['size']."\" maxlength=\"".$arguments['maxlength']."\" value=\"".$arguments['value']."\" ".$arguments['extra'].">".$arguments['otherelement']."</td>\n";
		echo "</tr>\n";
	}

	function maketimeinput($arguments = array(),$only=0) {
		if (!empty($arguments['css'])) {
			$class = "class=\"$arguments[css]\"";
		} else {
			$class = "class=\"formfield\"";
		}
		echo "<tr ".$this->getrowbg($only)." nowrap>\n";
		echo "	<td><b>".$arguments['text']."</b><br>".$arguments['note']."</td>\n";
		echo "<td><input type='text' name='year' size='4' value=\"".$arguments['year']."\" maxlength='4' ".$class."> 年 - <input type='text' name='month' size='2' value=\"".$arguments['month']."\" maxlength='2' ".$class."> 月 - <input type='text' name='day' size='2' value=\"".$arguments['day']."\" maxlength='2' ".$class."> 日 -  <input type='text' name='hour' size='2' value=\"".$arguments['hour']."\" maxlength='2' ".$class."> 时 -  <input type='text' name='minute' size='2' value=\"".$arguments['minute']."\" maxlength='2' ".$class."> 分  -  <input type='text' name='second' size='2' value=\"".$arguments['second']."\" maxlength='2' ".$class."> 秒</td>\n";
		echo "</tr>\n";
	}

	//生成文件上传表单
	function makefile($arguments = array()) {
		if(!isset($arguments['size'])) {
			$arguments['size'] = 30;
		}
		echo "<tr ".$this->getrowbg()." nowrap>\n";
		echo "	<td><b>".$arguments['text']."</b><br>".$arguments['note']."</td>\n";
		echo "	<td><input class=\"formfield\" type=\"file\" name=\"".$arguments['name']."\""." size=\"".$arguments['size']."\" ".$arguments['extra']."></td>\n";
		echo "</tr>\n";
	}

	//生成TEXTAREA
	function maketextarea($arguments = array()){
		if (empty($arguments['cols'])) {
			$arguments['cols'] = 50;
		}
		if (empty($arguments['rows'])) {
			$arguments['rows'] = 7;
		}
		if (!empty($arguments['html'])) {
			$arguments['value'] = htmlspecialchars($arguments['value']);
		}

		echo "<tr ".$this->getrowbg()." nowrap>\n";
		echo "	<td valign=\"top\"><b>".$arguments['text']."</b><br>".$arguments['note']."</td>\n";
		echo "	<td><textarea class=\"formfield\" type=\"text\" name=\"".$arguments['name']."\" cols=\"".$arguments['cols']."\" rows=\"".$arguments['rows']."\" ".$arguments['extra'].">".$arguments['value']."</textarea></td>\n";
		echo "</tr>\n";
	}

	//生成下拉列表
	function makeselect($arguments = array(),$only=0){
		if ($arguments['html'] == 1) {
			$value = htmlspecialchars($value);
		}
		if ($arguments['multiple']==1) {
			$multiple = " multiple";
		}
		if ($arguments['size']>0) {
				$size = "size=".$arguments['size']."";
			}
		if($arguments['disable'] == 1) {
			$disable = "disabled";
		}
		
		echo "<tr ".$this->getrowbg($only).">\n";
		echo "	<td valign=\"top\"><b>".$arguments['text']."</b><br>".$arguments['note']."</td>\n";
		echo "	<td><select name=\"".$arguments['name']."\" ".$multiple." ".$size." ".$disable."  {$arguments[extra]}>\n";
			if (is_array($arguments['option'])) {
				foreach ($arguments['option'] AS $key=>$value) {
					if (!is_array($arguments['selected'])) {
						if ($arguments['selected']==$key) {
							echo "<option value=\"".$key."\" selected class=\"{$arguments[css][$key]}\">".$value."</option>\n";
						} else {
							echo "<option value=\"".$key."\" class=\"{$arguments[css][$key]}\">".$value."</option>\n";
						}

					} elseif (is_array($arguments['selected'])) {
						if ($arguments['selected'][$key]==1) {
							echo "<option value=\"".$key."\" selected class=\"{$arguments[css][$key]}\">".$value."</option>\n";
						} else {
							echo "<option value=\"".$key."\" class=\"{$arguments[css][$key]}\">".$value."</option>\n";
						}
					}
				}
			}

		echo "</select>\n";
		echo "</td></tr>\n";
	}

	//生成是非单选按钮
	function makeyesno($arguments = array(),$only=0) {
		$arguments['option'] = array('1'=>'是','0'=>'否');
		$this->makeselect($arguments,$only,$disable);
	}

	//生成隐藏域
	function makehidden($arguments = array()){
		echo "<input type=\"hidden\" name=\"".$arguments['name']."\" value=\"".$arguments['value']."\">\n";
	}

	//双色背静行
	function getrowbg($only = 0) {
		if($only) {
			if($only == 2) {
				return " class=\"secondalt\"";
			}
			return " class=\"onlyalt\"";
		}
		static $bgcounter = 0;
		if ($bgcounter++%2==0) {
			return " class=\"firstalt\"";
		} else {
			return " class=\"secondalt\"";
		}
	}

	// 控制面版各页面页眉
	function cpheader($pageTitle = "") {
		global $options;
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>".$pageTitle."</title>\n";
		echo "<meta content=\"text/html; charset=gb2312\" http-equiv=\"Content-Type\">\n";
		echo "<link rel=\"stylesheet\" href=\"images/style.css\" type=\"text/css\">\n";
		echo "</head>\n";
		echo "<body leftmargin=\"20\" topmargin=\"20\" marginwidth=\"20\" marginheight=\"20\"  style=\"table-layout:fixed; word-break:break-all\">\n";
	}

	// 操作成功提示页面
	function redirect($msg, $url) {
		$this->cpheader();
		echo "<p>".$msg."</p>\n";
		echo "<meta http-equiv=\"refresh\" content=\"1;URL=".$url."\">\n";
		echo "</body>\n</html>";
		exit;
	}

	// 控制面版各页面页脚
	function cpfooter() {
		echo "</body>\n</html>";
	}

	// 错误提示信息
	function ob_exit($msg, $url="",$target="") {
		$this->cpheader();
		if(empty($url)) {
			$url = "javascript:history.go(-1);";
		}
		if(empty($target)) {
			$target = "";
		} else {
			$target = "target=\"".$target."\"";
		}
		echo "<meta http-equiv=\"refresh\" content=\"3;URL=".$url."\">";
		echo "<table width=\"350\" border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\">";
		echo "<tr>";
		echo "<td bgcolor=\"#FFFFFF\"> ";
		echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">";
		echo "<tr> ";
		echo "<td bgcolor=\"#F3F3F3\"><strong>O-BLOG 返回信息:</strong></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align=\"center\"><br>".$msg."<br><a href=".$url." ".$target.">请点击这里返回</a><br><br></td>";
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</body>\n</html>";
		exit;
	}

	// 产生表格
	function makenav($ctitle = "", $nav=array(), $only = 1) {
		global $current_auth_char;
		static $nc=0;
		$show_nav_title = 0;
		foreach($nav as $link) {
			preg_match("/^admin.php\?action=([^\/]+)/i",$link,$current_action_title);
			if(strstr($current_auth_char,$current_action_title[1])) {
				$show_nav_title = 1;
			}
		}
		if($show_nav_title) {
			echo "<tr class=\"tblhead\" style=\"cursor: hand\" onClick=\"javascript:showDiv('menu_{$nc}','img_{$nc}');\">\n";
			echo "<td class=\"space\"><img src=\"images/expand.gif\" id=\"img_{$nc}\" border=0> <b>".$ctitle."</b></td>\n";
			echo "</tr>\n";
			echo "<tr id=\"menu_{$nc}\"><td><table>";
		}
		foreach ($nav AS $title=>$link)	{
			preg_match("/^admin.php\?action=([^\/]+)/i",$link,$current_action);
			if(strstr($current_auth_char,$current_action[1])) {
				echo "<tr".$this->getrowbg($only).">\n";
				echo "  <td style=\"PADDING-LEFT: 20px;\"><a href=\"$link\" target=\"mainFrame\">".$title."</a></td>\n";
				echo "</tr>\n";
			}
		}
		if($show_nav_title) {
			echo "</table></td></tr>";
		}
		$nc++;
	}

	// 分页格
	function makepage($page_char = "", $colspan ,$only) {
		echo "<tr ".$this->getrowbg($only).">";
		echo "<td colspan=\"".$colspan."\" align=\"right\">".$page_char."</td>";
		echo "</tr>\n";
	}
	
	//因为table无法正确输出flush()效果，所以换用DIV，在批量建立静态页时使用
	function div_top($arg = array()) {
		?>
		<style type="text/css">
		<!--
		#outdiv {
			border: 1px solid #cccccc;
			background-color: #FFFFFF;
			padding: 6px;
		}
		#outdiv #contentdiv {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 11px;
			color: #000000;
			padding: 5px;
		}
		#outdiv #topdiv {
			background-color: #f3f3f3;
			padding: 6px;
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 11px;
			font-weight: bold;
			color: #000000;
		}
		-->
		</style>
		<?php
		echo "<div class=\"outdiv\" id=\"outdiv\"><div class=\"topdiv\" id=\"topdiv\"><strong>{$arg['title']}</strong></div><div class=\"contentdiv\" id=\"contentdiv\">";
	}
	
	//因为table无法正确输出flush()效果，所以换用DIV，在批量建立静态页时使用
	function div_bo() {
		echo "</div></div>";
	}

	//登陆界面
	function login() {
		global $DB,$mysql_prefix;
		$show_verify_code = (function_exists("gd_info")) ? 1 : 0;
		if($show_verify_code) {
			if(!$DB->fetch_one("SELECT verify_code FROM {$mysql_prefix}config")) {
				$show_verify_code = 0;
			}
		}
		if($show_verify_code) {
			$verify_char = "<td align=\"right\">验证码:</td><td><input type=\"text\" name=\"verify_code\"> <img src=\"class/verify.php\"></td></tr>";
		} else {
			$verify_char = "";
		}

		$this->cpheader("管理员登陆");
		echo "<form name=\"form1\" method=\"post\" action=\"index.php?frame=\">";
		echo "<table width=\"300\" border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\">";
		echo "<tr> ";
		echo "<td bgcolor=\"#FFFFFF\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">";
		echo "<tr> ";
		echo "<td colspan=\"2\" align=\"center\" bgcolor=\"#F3F3F3\"><B>管 理 登 陆</B></td>";
		echo "</tr>";
		echo "<tr> ";
		echo "<td width=\"31%\" align=\"right\">用户名：</td>";
		echo "<td width=\"69%\"><input type=\"text\" name=\"username\"></td>";
		echo "</tr>";
		echo "<tr> ";
		echo "<td align=\"right\">密码：</td>";
		echo "<td><input type=\"password\" name=\"password\"></td>";
		echo "</tr>";
		echo $verify_char;
		echo "<tr align=\"center\"> ";
		echo "<td colspan=\"2\"> ";
		echo "<input type=\"submit\" name=\"Submit\" value=\"提交\" class=\"button\"> ";
		echo "<input type=\"reset\" name=\"Submit2\" value=\"重置\" class=\"button\">";
		echo "</td>";
		echo "</tr>";
		echo "</table></td>";
		echo "</tr>";
		echo "</table>";
		echo "</form>";
		$this->cpfooter();
	}

	//框架页
	function frame() {
		global $var;
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">";
		echo "<html>";
		echo "<head>";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">";
		echo "<title>O-blog ".$var." 控制面板</title>";
		echo "</head>";
		echo "<frameset rows=\"*\" cols=\"200,*\" framespacing=\"0\" frameborder=\"NO\" border=\"0\">";
		echo "<frame src=\"login.php?frame=left\" name=\"leftFrame\" scrolling=\"yes\" noresize>";
		echo "<frameset rows=\"26,*\" cols=\"*\" framespacing=\"0\" frameborder=\"NO\" border=\"0\">";
		echo "<frame src=\"login.php?frame=top\" name=\"topFrame\" scrolling=\"NO\" noresize >";
		echo "<frame src=\"login.php?frame=main\" name=\"mainFrame\" scrolling=\"auto\">";
		echo "</frameset>";
		echo "</frameset>";
		echo "<noframes><body>";
		echo "</body></noframes>";
		echo "</html>";
		$this->cpfooter();
	}

	//UBB编辑器
	function editor($arguments = array()) {
		if(!isset($arguments['value'])) {
			$arguments['value'] = "";
		}
		if(!isset($arguments['text'])) {
			$arguments['text'] = "";
		}
		if(!isset($arguments['note'])) {
			$arguments['note'] = "";
		}
		?>
		<tr> 
		<td valign="top">
		<iframe scrolling="no" id="rtf" src="about:blank" MARGINHEIGHT="0" MARGINWIDTH="0" style="width:0px; height:0px;"></iframe>
		<b><?=$arguments['text'] ?></b><br><?=$arguments['note'] ?>
		<br><br><br>
		<table cellpadding="3" cellspacing="1" border="0" align="center">
		<tr><td colspan="3" align="center"><B>Smilies</B></td></tr>
		<tr align='center'><tr><td align="center" valign="top"><img src="images/smilies/cool.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':cool:');"></td>
		<td align="center" valign="top"><img src="images/smilies/mad.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':mad:');"></td>
		<td align="center" valign="top"><img src="images/smilies/lol.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':lol:');"></td>
		</tr><tr><td align="center" valign="top"><img src="images/smilies/smile.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':)');"></td>
		<td align="center" valign="top"><img src="images/smilies/sad.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':(');"></td>
		<td align="center" valign="top"><img src="images/smilies/biggrin.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':D');"></td>
		</tr><tr><td align="center" valign="top"><img src="images/smilies/wink.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(';)');"></td>
		<td align="center" valign="top"><img src="images/smilies/shocked.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':o');"></td>
		<td align="center" valign="top"><img src="images/smilies/tongue.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':P');"></td>
		</tr>
		<tr><td align="center" valign="top"><img src="images/smilies/astonish.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':O');"></td>
		<td align="center" valign="top"><img src="images/smilies/wronged.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':L');"></td>
		<td align="center" valign="top"><img src="images/smilies/sleep.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':|');"></td>
		</tr>
		<tr><td align="center" valign="top"><img src="images/smilies/sheepish.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':*');"></td>
		<td align="center" valign="top"><img src="images/smilies/rage.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':+');"></td>
		<td align="center" valign="top"><img src="images/smilies/happy.gif" border="0" onmouseover="this.style.cursor='hand';" onclick="AddText(':b');"></td>
		</tr>
		<tr></tr></table>
		<script language=JavaScript>
		var text_input = "文字";
		var help_mode = "O-blog 代码 - 帮助信息\n\n点击相应的代码按钮即可获得相应的说明和提示";
		var adv_mode = "O-blog 代码 - 直接插入\n\n点击代码按钮后不出现提示即直接插入相应代码";
		var normal_mode = "O-blog 代码 - 提示插入\n\n点击代码按钮后出现向导窗口帮助您完成代码插入";
		var email_help = "插入邮件地址\n\n插入邮件地址连接。\n例如：\n[email]support@crossday.com[/email]\n[email=support@crossday.com]Dai Zhikang[/email]";
		var email_normal = "请输入链接显示的文字，如果留空则直接显示邮件地址。";
		var email_normal_input = "请输入邮件地址。";
		var fontsize_help = "设置字号\n\n将标签所包围的文字设置成指定字号。\n例如：[size=3]文字大小为 3[/size]";
		var fontsize_normal = "请输入要设置为指定字号的文字。";
		var font_help = "设定字体\n\n将标签所包围的文字设置成指定字体。\n例如：[font=仿宋]字体为仿宋[/font]";
		var font_normal = "请输入要设置成指定字体的文字。";
		var bold_help = "插入粗体文本\n\n将标签所包围的文本变成粗体。\n例如：[b]www.phpBlog.cn[/b]";
		var bold_normal = "请输入要设置成粗体的文字。";
		var italicize_help = "插入斜体文本\n\n将标签所包围的文本变成斜体。\n例如：[i]www.phpBlog.cn[/i]";
		var italicize_normal = "请输入要设置成斜体的文字。";
		var quote_help = "插入引用\n\n将标签所包围的文本作为引用特殊显示。\n例如：[quote]O-blog版权所有 - www.phpBlog.cn[/quote]";
		var quote_normal = "请输入要作为引用显示的文字。";
		var color_help = "定义文本颜色\n\n将标签所包围的文本变为制定颜色。\n例如：[color=red]红颜色[/color]";
		var color_normal = "请输入要设置成指定颜色的文字。";
		var center_help = "居中对齐\n\n将标签所包围的文本居中对齐显示。\n例如：[align=center]内容居中[/align]";
		var center_normal = "请输入要居中对齐的文字。";
		var link_help = "插入超级链接\n\n插入一个超级连接。\n例如：\n[url]http://www.phpblog.cn[/url]\n[url=http://www.phpblog.cn]Crossday 工作室[/url]";
		var link_normal = "请输入链接显示的文字，如果留空则直接显示链接。";
		var link_normal_input = "请输入 URL。";
		var image_help = "插入图像\n\n在文本中插入一幅图像。\n例如：[img]http://www.phpblog.cn/cdb/images/logo.gif[/img]";
		var image_normal = "请输入图像的 URL。";
		var flash_help = "插入 flash\n\n在文本中插入 flash 动画。\n例如：[swf]http://www.phpblog.cn/cdb/images/banner.swf[/swf]";
		var flash_normal = "请输入 flash 动画的 URL。";
		var wmv_help = "插入Media player文件，如MP3 WMA WMV ASF等\n\n在文本中插入 Media player文件。\n例如：[wmv]http://www.zippodiy.com/xxx.mp3[/wmv]";
		var wmv_normal = "请输入Media player文件的 URL。";
		var rm_help = "插入RealOne Player文件，如rm等\n\n在文本中插入RealOne Player文件。\n例如：[rm]http://www.zippodiy.com/xxx.rm[/rm]";
		var rm_normal = "请输入RealOne Player文件的 URL。";
		var code_help = "插入代码\n\n插入程序或脚本原始代码。\n例如：[code]echo\"这里是我们的论坛\";[/code]";
		var code_normal = "请输入要插入的代码。";
		var list_help = "插入列表\n\n插入可由浏览器显示来的规则列表项。\n例如：\n[list]\n[*]；列表项 #1\n[*]；列表项 #2\n[*]；列表项 #3\n[/list]";
		var list_normal = "请选择列表格式：字母式列表输入 \"A\"；数字式列表输入 \"1\"。此处也可留空。";
		var list_normal_error = "错误：列表格式只能选择输入 \"A\" 或 \"1\"。";
		var list_normal_input = "请输入列表项目内容，如果留空表示项目结束。";
		var underline_help = "插入下划线\n\n给标签所包围的文本加上下划线。\n例如：[u]Crossday 工作室[/u]";
		var underline_normal = "请输入要加下划线的文字。";
		</script> <script language=JavaScript src="images/bbcode.js"></script> </td>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr> 
		<td height="1" colspan="2"></td>
		</tr>
		<tr> 
		<td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr> 
		<td height="3"></td>
		</tr>
		<tr> 
		<td><select 
		name=font size=1 onFocus=this.selectedIndex=0 
		onChange=chfont(this.options[this.selectedIndex].value)>
		<option value=宋体>宋体</option>
		<option 
		value=黑体>黑体</option>
		<option value=Arial>Arial</option>
		<option value="Book Antiqua">Book Antiqua</option>
		<option 
		value="Century Gothic">Century Gothic</option>
		<option 
		value="Courier New">Courier New</option>
		<option 
		value=Georgia>Georgia</option>
		<option 
		value=Impact>Impact</option>
		<option 
		value=Tahoma>Tahoma</option>
		<option 
		value="Times New Roman">Times New Roman</option>
		<option 
		value=Verdana selected>Verdana</option>
		</select> <select 
		name=size size=1 onFocus=this.selectedIndex=2 
		onChange=chsize(this.options[this.selectedIndex].value)>
		<option value=-2>-2</option>
		<option 
		value=-1>-1</option>
		<option value=1>1</option>
		<option 
		value=2 selected>2</option>
		<option value=3 >3</option>
		<option value=4>4</option>
		<option value=5>5</option>
		<option value=6>6</option>
		</select> <select name=color 
		size=1 onFocus=this.selectedIndex=1 
		onChange=chcolor(this.options[this.selectedIndex].value)>
		<option value="" selected>字体颜色</option>
		<option value="White" style="background-color:white;color:white;">White</option>
		<option value="Black" style="background-color:black;color:black;">Black</option>
		<option value="Red" style="background-color:red;color:red;">Red</option>
		<option value="Yellow" style="background-color:yellow;color:yellow;">Yellow</option>
		<option value="Pink" style="background-color:pink;color:pink;">Pink</option>
		<option value="Green" style="background-color:green;color:green;">Green</option>
		<option value="Orange" style="background-color:orange;color:orange;">Orange</option>
		<option value="Purple" style="background-color:purple;color:purple;">Purple</option>
		<option value="Blue" style="background-color:blue;color:blue;">Blue</option>
		<option value="Beige" style="background-color:beige;color:beige;">Beige</option>
		<option value="Brown" style="background-color:brown;color:brown;">Brown</option>
		<option value="Teal" style="background-color:teal;color:teal;">Teal</option>
		<option value="Navy" style="background-color:navy;color:navy;">Navy</option>
		<option value="Maroon" style="background-color:maroon;color:maroon;">Maroon</option>
		<option value="LimeGreen" style="background-color:limegreen;color:limegreen;">LimeGreen</option>
		</select> </td>
		</tr>
		<tr> 
		<td height="3"></td>
		</tr>
		<tr> 
		<td><A 
		href="javascript:bold()"><IMG height=24 alt=插入粗体文本 
		src="images/bb_bold.gif" width=24 border=0></A> <A 
		href="javascript:italicize()"><IMG height=24 alt=插入斜体文本 
		src="images/bb_italicize.gif" width=24 border=0></A> <A href="javascript:underline()"><IMG height=24 alt=插入下划线 
		src="images/bb_underline.gif" width=24 border=0></A> <A href="javascript:center()"><IMG height=24 alt=居中对齐 
		src="images/bb_center.gif" width=24 border=0></A> <A 
		href="javascript:hyperlink()"><IMG height=24 alt=插入超级链接 
		src="images/bb_url.gif" width=24 border=0></A> <A 
		href="javascript:email()"><IMG height=24 alt=插入邮件地址 
		src="images/bb_email.gif" width=24 border=0></A> <A 
		href="javascript:image()"><IMG height=24 alt=插入图像 
		src="images/bb_image.gif" width=24 border=0></A> <A 
		href="javascript:flash()"><IMG height=24 alt="插入 flash" 
		src="images/bb_flash.gif" width=24 border=0></A> <A 
		href="javascript:wmv()"><IMG height=24 
		alt="插入Media player文件，如MP3 WMA WMV ASF等" 
		src="images/bb_mp.gif" width=24 border=0></A> <A 
		href="javascript:rm()"><IMG height=24 
		alt="插入RealOne Player文件，如rm等" src="images/bb_rm.gif" 
		width=24 border=0></A> <A href="javascript:code()"><IMG 
		height=24 alt=插入代码 src="images/bb_code.gif" width=24 
		border=0></A> <A href="javascript:quote()"><IMG height=24 
		alt=插入引用 src="images/bb_quote.gif" width=24 
		border=0></A> <A href="javascript:list()"><IMG height=24 
		alt=插入列表 src="images/bb_list.gif" width=24 
		border=0></A> <IMG height=24 
		alt=插入要截断的内容，截断符后的内容在日志列表中不显示 src="images/bb_separator.gif" width=24 
		border=0 onmouseover="this.style.cursor='hand';" onclick="AddText('[separator]');">
		<IMG height=24 
		alt=转载工具:先复制网页上的内容，然后点此按钮 src="images/bb_zt.gif" width=24 
		border=0 onmouseover="this.style.cursor='hand';" onclick="document.getElementById('message').value += trans()">
		<script>rtf.document.designMode="On";</script>
		</td>
		</tr>
		</table></td>
		</tr>
		<tr> 
		<td height="1" colspan="2"></td>
		</tr>
		<tr> 
		<td width="71%">
		<TEXTAREA NAME="message" id="message" COLS="75" ROWS="20" class="myTextArea"  onSelect="javascript: storeCaret(this);" onClick="javascript: storeCaret(this);" onKeyUp="javascript: storeCaret(this);" onKeyDown="javascript: ctlent();"><?=$arguments['value'] ?></TEXTAREA></td>
		<td width="29%"><input name="new_id" type="hidden" id="new_id3" value="<? echo $new_id; ?>"></td>
		</tr>
		</table>
		</td>
		</tr>
		<?php
	}

	//选择所有的JS
	function js_checkall() {
		?>
		<SCRIPT language=JavaScript>
		function CheckAll(form) {
			for (var i=0;i<form.elements.length;i++) {
				var e = form.elements[i];
				if (e.name != 'chkall')
				e.checked = form.chkall.checked;
			}
		}
		</SCRIPT>
		<?php
	}

	//判断是否删除的JS
	function if_del()
	{
		?>
		<SCRIPT LANGUAGE="JavaScript">
		function ifDel(delurl) {
			var truthBeTold = window.confirm("您确定要删除吗？");
			if (truthBeTold) {
				location=delurl;
			}  else  {
				return;
			}
		}
		</SCRIPT>
		<?php
	}

	//判断是否导入的JS
	function if_import()
	{
		?>
		<SCRIPT LANGUAGE="JavaScript">
		function ifImport(delurl) {
			var truthBeTold = window.confirm("您确定要导入当前数据吗？");
			if (truthBeTold) {
				location=delurl;
			}  else  {
				return;
			}
		}
		</SCRIPT>
		<?php
	}
}
?>