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
	
	//��ͷ
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
	
	//��β
	function formfooter($arguments=array()){
		echo "<tr>\n";
			if ($arguments['confirm']==1) {
				$arguments['button']['submit']['type'] = "submit";
				$arguments['button']['submit']['name'] = "submit";
				$arguments['button']['submit']['value'] = "ȷ��";
				$arguments['button']['submit']['accesskey'] = "y";

				$arguments['button']['back']['type'] = "button";
				$arguments['button']['back']['value'] = "ȡ��";
				$arguments['button']['back']['accesskey'] = "r";
				$arguments['button']['back']['extra'] = " onclick=\"history.back(1)\" ";
			} elseif (empty($arguments['button'])) {

				$arguments['button']['submit']['type'] = "submit";
				$arguments['button']['submit']['name'] = "submit";
				$arguments['button']['submit']['value'] = "�ύ";
				$arguments['button']['submit']['accesskey'] = "y";

				$arguments['button']['reset']['type'] = "reset";
				$arguments['button']['reset']['value'] = "����";
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
	
	//TABLEͷ
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

	//TABLEͷ2
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

	//TABLEβ
	function tablefooter() {
		echo "</table></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

	//����TD
	function maketd($arguments = array()) {
		echo "<tr ".$this->getrowbg()." nowrap>";
		foreach ($arguments AS $k=>$v) {
			echo "<td>".$v."</td>";
		}
		echo "</tr>\n";
	}
	
	//����INPUT
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
		echo "<td><input type='text' name='year' size='4' value=\"".$arguments['year']."\" maxlength='4' ".$class."> �� - <input type='text' name='month' size='2' value=\"".$arguments['month']."\" maxlength='2' ".$class."> �� - <input type='text' name='day' size='2' value=\"".$arguments['day']."\" maxlength='2' ".$class."> �� -  <input type='text' name='hour' size='2' value=\"".$arguments['hour']."\" maxlength='2' ".$class."> ʱ -  <input type='text' name='minute' size='2' value=\"".$arguments['minute']."\" maxlength='2' ".$class."> ��  -  <input type='text' name='second' size='2' value=\"".$arguments['second']."\" maxlength='2' ".$class."> ��</td>\n";
		echo "</tr>\n";
	}

	//�����ļ��ϴ���
	function makefile($arguments = array()) {
		if(!isset($arguments['size'])) {
			$arguments['size'] = 30;
		}
		echo "<tr ".$this->getrowbg()." nowrap>\n";
		echo "	<td><b>".$arguments['text']."</b><br>".$arguments['note']."</td>\n";
		echo "	<td><input class=\"formfield\" type=\"file\" name=\"".$arguments['name']."\""." size=\"".$arguments['size']."\" ".$arguments['extra']."></td>\n";
		echo "</tr>\n";
	}

	//����TEXTAREA
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

	//���������б�
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

	//�����Ƿǵ�ѡ��ť
	function makeyesno($arguments = array(),$only=0) {
		$arguments['option'] = array('1'=>'��','0'=>'��');
		$this->makeselect($arguments,$only,$disable);
	}

	//����������
	function makehidden($arguments = array()){
		echo "<input type=\"hidden\" name=\"".$arguments['name']."\" value=\"".$arguments['value']."\">\n";
	}

	//˫ɫ������
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

	// ��������ҳ��ҳü
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

	// �����ɹ���ʾҳ��
	function redirect($msg, $url) {
		$this->cpheader();
		echo "<p>".$msg."</p>\n";
		echo "<meta http-equiv=\"refresh\" content=\"1;URL=".$url."\">\n";
		echo "</body>\n</html>";
		exit;
	}

	// ��������ҳ��ҳ��
	function cpfooter() {
		echo "</body>\n</html>";
	}

	// ������ʾ��Ϣ
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
		echo "<td bgcolor=\"#F3F3F3\"><strong>O-BLOG ������Ϣ:</strong></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align=\"center\"><br>".$msg."<br><a href=".$url." ".$target.">�������ﷵ��</a><br><br></td>";
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</body>\n</html>";
		exit;
	}

	// �������
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

	// ��ҳ��
	function makepage($page_char = "", $colspan ,$only) {
		echo "<tr ".$this->getrowbg($only).">";
		echo "<td colspan=\"".$colspan."\" align=\"right\">".$page_char."</td>";
		echo "</tr>\n";
	}
	
	//��Ϊtable�޷���ȷ���flush()Ч�������Ի���DIV��������������̬ҳʱʹ��
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
	
	//��Ϊtable�޷���ȷ���flush()Ч�������Ի���DIV��������������̬ҳʱʹ��
	function div_bo() {
		echo "</div></div>";
	}

	//��½����
	function login() {
		global $DB,$mysql_prefix;
		$show_verify_code = (function_exists("gd_info")) ? 1 : 0;
		if($show_verify_code) {
			if(!$DB->fetch_one("SELECT verify_code FROM {$mysql_prefix}config")) {
				$show_verify_code = 0;
			}
		}
		if($show_verify_code) {
			$verify_char = "<td align=\"right\">��֤��:</td><td><input type=\"text\" name=\"verify_code\"> <img src=\"class/verify.php\"></td></tr>";
		} else {
			$verify_char = "";
		}

		$this->cpheader("����Ա��½");
		echo "<form name=\"form1\" method=\"post\" action=\"index.php?frame=\">";
		echo "<table width=\"300\" border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#CCCCCC\">";
		echo "<tr> ";
		echo "<td bgcolor=\"#FFFFFF\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">";
		echo "<tr> ";
		echo "<td colspan=\"2\" align=\"center\" bgcolor=\"#F3F3F3\"><B>�� �� �� ½</B></td>";
		echo "</tr>";
		echo "<tr> ";
		echo "<td width=\"31%\" align=\"right\">�û�����</td>";
		echo "<td width=\"69%\"><input type=\"text\" name=\"username\"></td>";
		echo "</tr>";
		echo "<tr> ";
		echo "<td align=\"right\">���룺</td>";
		echo "<td><input type=\"password\" name=\"password\"></td>";
		echo "</tr>";
		echo $verify_char;
		echo "<tr align=\"center\"> ";
		echo "<td colspan=\"2\"> ";
		echo "<input type=\"submit\" name=\"Submit\" value=\"�ύ\" class=\"button\"> ";
		echo "<input type=\"reset\" name=\"Submit2\" value=\"����\" class=\"button\">";
		echo "</td>";
		echo "</tr>";
		echo "</table></td>";
		echo "</tr>";
		echo "</table>";
		echo "</form>";
		$this->cpfooter();
	}

	//���ҳ
	function frame() {
		global $var;
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">";
		echo "<html>";
		echo "<head>";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">";
		echo "<title>O-blog ".$var." �������</title>";
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

	//UBB�༭��
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
		var text_input = "����";
		var help_mode = "O-blog ���� - ������Ϣ\n\n�����Ӧ�Ĵ��밴ť���ɻ����Ӧ��˵������ʾ";
		var adv_mode = "O-blog ���� - ֱ�Ӳ���\n\n������밴ť�󲻳�����ʾ��ֱ�Ӳ�����Ӧ����";
		var normal_mode = "O-blog ���� - ��ʾ����\n\n������밴ť������򵼴��ڰ�������ɴ������";
		var email_help = "�����ʼ���ַ\n\n�����ʼ���ַ���ӡ�\n���磺\n[email]support@crossday.com[/email]\n[email=support@crossday.com]Dai Zhikang[/email]";
		var email_normal = "������������ʾ�����֣����������ֱ����ʾ�ʼ���ַ��";
		var email_normal_input = "�������ʼ���ַ��";
		var fontsize_help = "�����ֺ�\n\n����ǩ����Χ���������ó�ָ���ֺš�\n���磺[size=3]���ִ�СΪ 3[/size]";
		var fontsize_normal = "������Ҫ����Ϊָ���ֺŵ����֡�";
		var font_help = "�趨����\n\n����ǩ����Χ���������ó�ָ�����塣\n���磺[font=����]����Ϊ����[/font]";
		var font_normal = "������Ҫ���ó�ָ����������֡�";
		var bold_help = "��������ı�\n\n����ǩ����Χ���ı���ɴ��塣\n���磺[b]www.phpBlog.cn[/b]";
		var bold_normal = "������Ҫ���óɴ�������֡�";
		var italicize_help = "����б���ı�\n\n����ǩ����Χ���ı����б�塣\n���磺[i]www.phpBlog.cn[/i]";
		var italicize_normal = "������Ҫ���ó�б������֡�";
		var quote_help = "��������\n\n����ǩ����Χ���ı���Ϊ����������ʾ��\n���磺[quote]O-blog��Ȩ���� - www.phpBlog.cn[/quote]";
		var quote_normal = "������Ҫ��Ϊ������ʾ�����֡�";
		var color_help = "�����ı���ɫ\n\n����ǩ����Χ���ı���Ϊ�ƶ���ɫ��\n���磺[color=red]����ɫ[/color]";
		var color_normal = "������Ҫ���ó�ָ����ɫ�����֡�";
		var center_help = "���ж���\n\n����ǩ����Χ���ı����ж�����ʾ��\n���磺[align=center]���ݾ���[/align]";
		var center_normal = "������Ҫ���ж�������֡�";
		var link_help = "���볬������\n\n����һ���������ӡ�\n���磺\n[url]http://www.phpblog.cn[/url]\n[url=http://www.phpblog.cn]Crossday ������[/url]";
		var link_normal = "������������ʾ�����֣����������ֱ����ʾ���ӡ�";
		var link_normal_input = "������ URL��";
		var image_help = "����ͼ��\n\n���ı��в���һ��ͼ��\n���磺[img]http://www.phpblog.cn/cdb/images/logo.gif[/img]";
		var image_normal = "������ͼ��� URL��";
		var flash_help = "���� flash\n\n���ı��в��� flash ������\n���磺[swf]http://www.phpblog.cn/cdb/images/banner.swf[/swf]";
		var flash_normal = "������ flash ������ URL��";
		var wmv_help = "����Media player�ļ�����MP3 WMA WMV ASF��\n\n���ı��в��� Media player�ļ���\n���磺[wmv]http://www.zippodiy.com/xxx.mp3[/wmv]";
		var wmv_normal = "������Media player�ļ��� URL��";
		var rm_help = "����RealOne Player�ļ�����rm��\n\n���ı��в���RealOne Player�ļ���\n���磺[rm]http://www.zippodiy.com/xxx.rm[/rm]";
		var rm_normal = "������RealOne Player�ļ��� URL��";
		var code_help = "�������\n\n��������ű�ԭʼ���롣\n���磺[code]echo\"���������ǵ���̳\";[/code]";
		var code_normal = "������Ҫ����Ĵ��롣";
		var list_help = "�����б�\n\n��������������ʾ���Ĺ����б��\n���磺\n[list]\n[*]���б��� #1\n[*]���б��� #2\n[*]���б��� #3\n[/list]";
		var list_normal = "��ѡ���б��ʽ����ĸʽ�б����� \"A\"������ʽ�б����� \"1\"���˴�Ҳ�����ա�";
		var list_normal_error = "�����б��ʽֻ��ѡ������ \"A\" �� \"1\"��";
		var list_normal_input = "�������б���Ŀ���ݣ�������ձ�ʾ��Ŀ������";
		var underline_help = "�����»���\n\n����ǩ����Χ���ı������»��ߡ�\n���磺[u]Crossday ������[/u]";
		var underline_normal = "������Ҫ���»��ߵ����֡�";
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
		<option value=����>����</option>
		<option 
		value=����>����</option>
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
		<option value="" selected>������ɫ</option>
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
		href="javascript:bold()"><IMG height=24 alt=��������ı� 
		src="images/bb_bold.gif" width=24 border=0></A> <A 
		href="javascript:italicize()"><IMG height=24 alt=����б���ı� 
		src="images/bb_italicize.gif" width=24 border=0></A> <A href="javascript:underline()"><IMG height=24 alt=�����»��� 
		src="images/bb_underline.gif" width=24 border=0></A> <A href="javascript:center()"><IMG height=24 alt=���ж��� 
		src="images/bb_center.gif" width=24 border=0></A> <A 
		href="javascript:hyperlink()"><IMG height=24 alt=���볬������ 
		src="images/bb_url.gif" width=24 border=0></A> <A 
		href="javascript:email()"><IMG height=24 alt=�����ʼ���ַ 
		src="images/bb_email.gif" width=24 border=0></A> <A 
		href="javascript:image()"><IMG height=24 alt=����ͼ�� 
		src="images/bb_image.gif" width=24 border=0></A> <A 
		href="javascript:flash()"><IMG height=24 alt="���� flash" 
		src="images/bb_flash.gif" width=24 border=0></A> <A 
		href="javascript:wmv()"><IMG height=24 
		alt="����Media player�ļ�����MP3 WMA WMV ASF��" 
		src="images/bb_mp.gif" width=24 border=0></A> <A 
		href="javascript:rm()"><IMG height=24 
		alt="����RealOne Player�ļ�����rm��" src="images/bb_rm.gif" 
		width=24 border=0></A> <A href="javascript:code()"><IMG 
		height=24 alt=������� src="images/bb_code.gif" width=24 
		border=0></A> <A href="javascript:quote()"><IMG height=24 
		alt=�������� src="images/bb_quote.gif" width=24 
		border=0></A> <A href="javascript:list()"><IMG height=24 
		alt=�����б� src="images/bb_list.gif" width=24 
		border=0></A> <IMG height=24 
		alt=����Ҫ�ضϵ����ݣ��ضϷ������������־�б��в���ʾ src="images/bb_separator.gif" width=24 
		border=0 onmouseover="this.style.cursor='hand';" onclick="AddText('[separator]');">
		<IMG height=24 
		alt=ת�ع���:�ȸ�����ҳ�ϵ����ݣ�Ȼ���˰�ť src="images/bb_zt.gif" width=24 
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

	//ѡ�����е�JS
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

	//�ж��Ƿ�ɾ����JS
	function if_del()
	{
		?>
		<SCRIPT LANGUAGE="JavaScript">
		function ifDel(delurl) {
			var truthBeTold = window.confirm("��ȷ��Ҫɾ����");
			if (truthBeTold) {
				location=delurl;
			}  else  {
				return;
			}
		}
		</SCRIPT>
		<?php
	}

	//�ж��Ƿ����JS
	function if_import()
	{
		?>
		<SCRIPT LANGUAGE="JavaScript">
		function ifImport(delurl) {
			var truthBeTold = window.confirm("��ȷ��Ҫ���뵱ǰ������");
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