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
//������ MENU ҳ��
if(isset($_GET['frame']) && @$_GET['frame'] == 'left') {
	$FORM->cpheader();
	echo "<script language=JavaScript type=text/javascript>\n";
	echo "function showDiv(objID,imgID)\n";
	echo "{\n";
	echo "if (document.getElementById(objID).style.display == \"none\") {\n";
	echo "document.getElementById(objID).style.display = \"\";\n";
	echo "document.getElementById(imgID).src=\"images/expand.gif\"";
	echo "}else{\n";
	echo "document.getElementById(objID).style.display = \"none\";\n";
	echo "document.getElementById(imgID).src=\"images/collapse.gif\"";
	echo "}\n";
	echo "}\n";
	echo "</script>\n";
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";
	echo "<tr>\n";
	echo "<td align=\"center\"><b>O-blog ".$var;
	echo " �������</b></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	$FORM->tableheader();
	$FORM->makenav("ϵͳ����",array('��������'=>'admin.php?action=config',
						 'PHP��Ϣ'=>'admin.php?action=phpinfo',
                            ),1);
	$FORM->makenav("��־����",array('�����־'=>'admin.php?action=addBlog',
						 '�༭��־'=>'admin.php?action=editBlog',
                            ),1);
	$FORM->makenav("�������",array('��ӷ���'=>'admin.php?action=addSort',
						 '�༭����'=>'admin.php?action=editSort',
                            ),1);
	$FORM->makenav("���ӹ���",array('�������'=>'admin.php?action=addLink',
						 '�༭����'=>'admin.php?action=editLink',
                            ),1);
	$FORM->makenav("����/����",array('���Թ���'=>'admin.php?action=guestbook',
						 '���۹���'=>'admin.php?action=remarkManager',
						 '���˹���'=>'admin.php?action=banned',
                            ),1);
	$FORM->makenav("���±�",array('��Ӽ���'=>'admin.php?action=addNote',
						 '�������'=>'admin.php?action=editNote',
                            ),1);
	$FORM->makenav("�û�����",array('����û�'=>'admin.php?action=addUser',
						 '�༭�û�'=>'admin.php?action=editUser',
                            ),1);
	$FORM->makenav("���ݿ�ѡ��",array('�������ݿ�'=>'admin.php?action=bak',
						 '�ָ����ݿ�'=>'admin.php?action=bakManager',
						 '�Ż����ݿ�'=>'admin.php?action=optimize',
						 '�޸����ݿ�'=>'admin.php?action=repair',
						 'ִ��SQL��ѯ'=>'admin.php?action=runsql',
                            ),1);
	$FORM->makenav("RSS ����",array('RSS ����'=>'admin.php?action=rssImport',
						 'RSS ����'=>'admin.php?action=rssExport',
                            ),1);
	$FORM->makenav("�ļ�����",array('�ϴ��ļ�'=>'admin.php?action=upload',
						 '�ļ�����'=>'admin.php?action=uploadManager',
                            ),1);
	$FORM->makenav("ģ�����",array('�༭ģ��'=>'admin.php?action=selectTemplate',
                            ),1);
	$FORM->makenav("��������",array('�ؽ���̬ҳ��'=>'admin.php?action=rebuild',
						 '����ͨ�����'=>'admin.php?action=editTrackback',
                            ),1);
	$FORM->makenav("�Զ�����",array('�������'=>'admin.php?action=addAutolink',
						 '��������'=>'admin.php?action=editAutolink',
                            ),1);
	$FORM->makenav("������־",array('������¼'=>'admin.php?action=actlog',
						 '��½��¼'=>'admin.php?action=userlog',
                            ),1);
	$FORM->makenav("����Աѡ��",array('�޸�����'=>'admin.php?action=password',
                            ),1);
	$FORM->tablefooter();
	$FORM->cpfooter();
}

//����ұ�Ĭ��ҳ��
if(isset($_GET['frame']) && @$_GET['frame'] == 'main') {
	//register_globals
	if (function_exists('ini_get')){
		$onoff = ini_get('register_globals');
	} else {
		$onoff = get_cfg_var('register_globals');
	}
	if ($onoff){
		$onoff="��";
	}else{
		$onoff="�ر�";
	}

	//file_uploads
	if (function_exists('ini_get')){
		$upload = ini_get('file_uploads');
	} else {
		$upload = get_cfg_var('file_uploads');
	}
	if ($upload){
		$upload="����";
	}else{
		$upload="������";
	}

	//stat
	$table = array($mysql_prefix."blog",$mysql_prefix."class",$mysql_prefix."guestbook",$mysql_prefix."remark",$mysql_prefix."link",$mysql_prefix."trackback");
	foreach($table as $key=>$val) {
		$stat[] = $DB->fetch_one("SELECT count(*) FROM `".$val."`");
	}

	//attachments size
	$filesize = "";
	if ($handle = @opendir('uploadfiles')) {
		while (false !== ($file = readdir($handle))) {
			if($file != "." && $file != "..") {
				$filesize += filesize("uploadfiles/".$file);
			}
		}
		closedir($handle);
	}

	//db size
	$res = $DB->query("SHOW TABLE STATUS");
	while ($row = mysql_fetch_array($res))
	{
		@$datasize  += $row['Data_length'];
		@$indexsize  += $row['Index_length'];
	}
	$dbSize = $datasize+$indexsize;
	$userid = intval($_COOKIE['ob_userid']);
	$username = $DB->fetch_one("SELECT `username` FROM `".$mysql_prefix."admin` WHERE `id`=".$userid);
	$FORM->cpheader();
	?>
	<table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr> 
	<td align="center" class="welcome">Welcome to O-Blog</td>
	</tr>
	<tr> 
	<td class="maint1"><br><b>&nbsp;��ӭ��, <?=$username ?><br>&nbsp;���ڵ�ʱ����: <?=obdate("Y-m-d H:i:s",time()) ?></b><br><br></td>
	</tr>
	<tr> 
	<td> <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
	  <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding=4 cellspacing=1 bgcolor="#FFFFFF" class="tableoutline">
        <tr bgcolor="#F3F3F3">
          <td height="20" colspan="2" class=tbhead><strong>��ݷ�ʽ</strong></td>
        </tr>
        <tr>
          <td width="50%" colspan="2"><a href="admin.php?action=addBlog">�����־</a> | <a href="admin.php?action=editBlog">�༭��־</a> | <a href="admin.php?action=guestbook">���Թ���</a> | <a href="admin.php?action=remarkManager">���۹���</a> | <a href="admin.php?action=addLink">�������</a> | <a href="admin.php?action=addNote">��Ӽ���</a> | <a href="admin.php?action=upload">�ļ��ϴ�</a> | <a href="admin.php?action=rebuild">�ؽ�ҳ��</a> | <a href="admin.php?action=selectTemplate">�༭ģ��</a></td>
        </tr>
      </table>
	  <table width="100%" border="0" cellpadding=4 cellspacing=1 bgcolor="#FFFFFF" class="tableoutline">
	<tr bgcolor="#F3F3F3"> 
	<td height="20" colspan="2" class=tbhead><strong>ϵͳ��Ϣ</strong></td>
	</tr>
	<tr> 
	<td width="50%">���������: <?php echo @$_SERVER["SERVER_SOFTWARE"];?> </font>	</td>
	<td width="50%">������ϵͳ: <?php echo defined('PHP_OS') ? PHP_OS : 'δ֪';?></font></td>
	</tr>
	<tr> 
	<td width="50%">PHP �汾: <?php echo @phpversion();?> </font></td>
	<td width="50%">MySQL �汾: <?php echo @mysql_get_server_info();?></font></td>
	</tr>
	<tr> 
	<td width="50%">register_globals: <?php echo @$onoff;?></font></td>
	<td width="50%">�ļ��ϴ�: <?php echo @$upload; ?> </font></td>
	</tr>
	<tr> 
	<td >��������ַ: <?php echo @$_SERVER[SERVER_ADDR];?></font></td>
	<td width="50%">������ʱ��: <?php echo @obdate("T",time()); ?> </font></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#FFFFFF">
	<tr bgcolor="#F3F3F3"> 
	<td height="20" colspan="2" class="tbhead"><strong>������Ϣ</strong></td>
	</tr>
	<tr bgcolor="#FFFFFF" > 
	<td><p>���򿪷�:shishirui/��ɫ</p></td>
	<td>�汾:<?php echo $var; ?></td>
	</tr>
	<tr bgcolor="#FFFFFF" > 
	<td>E-mail:<a href="mailto:shishirui@163.com">shirui@gmail.com</a></td>
	<td width="50%">�ٷ�վ��:<a href="http://www.phpBlog.cn" target="_blank">http://www.phpBlog.cn</a></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#FFFFFF">
	<tr bgcolor="#F3F3F3"> 
	<td height="20" colspan="2" class="tbhead"><strong>ͳ����Ϣ</strong></td>
	</tr>
	<tr bgcolor="#FFFFFF" > 
	<td>��־����:<?php echo $stat[0]; ?></td>
	<td>��������:<?php echo $stat[3]; ?></td>
	</tr>
	<tr bgcolor="#FFFFFF" > 
	<td>��������:<?php echo $stat[2]; ?></td>
	<td width="50%">��������:<?php echo $stat[1]; ?></td>
	</tr>
	<tr bgcolor="#FFFFFF"> 
	<td width="50%" >��������:<?php echo $stat[4]; ?></td>
	<td width="50%" >��������:<?php echo $stat[5]; ?></td>
	</tr>
	<tr bgcolor="#FFFFFF"> 
	<td width="50%" >������С:<?php echo get_real_size($filesize) ?></td>
	<td width="50%" >���ݿ��С:<?php echo get_real_size($dbSize) ?></td>
	</tr>
	</table></td>
	</tr>
	</table></td>
	</tr>
	</table>
<?php
	$FORM->cpfooter();
}

//����ϱ�ҳ��
if(isset($_GET['frame']) && @$_GET['frame'] == 'top') {
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<link rel=\"stylesheet\" href=\"images/style.css\" type=\"text/css\">\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\n";
	echo "<title></title>\n";
	echo "</head>\n";
	echo "<body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"3\" style=\"border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #CCCCCC;\">\n";
	echo "<table width=\"99%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" align=\"center\" style=\"margin-top: 1px;\">\n\n";
	echo "<tr>\n\n";
	echo "<td bgcolor=\"#F3F3F3\"><table width=\"100%\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\">\n\n";
	echo "<tr> \n";
	echo "<td><b>[<a href=\"login.php?frame=main\" target=\"mainFrame\">���������ҳ</a>] [<a href=\"../\" target=\"_blank\">BLOG ��ҳ</a>]</b></td>\n";
	echo "<td align=\"right\"><b>[<a href=\"admin.php?action=logout\" target=\"_parent\">�˳���½</a>]</b></td>\n";
	echo "</tr>\n";
	echo "</table></td>\n\n";
	echo "</tr>\n\n";
	echo "</table>\n\n";
	echo "</body>\n";
	echo "</html>\n";
}
?>