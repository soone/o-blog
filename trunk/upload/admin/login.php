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
//框架左边 MENU 页面
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
	echo " 控制面板</b></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	$FORM->tableheader();
	$FORM->makenav("系统设置",array('基本设置'=>'admin.php?action=config',
						 'PHP信息'=>'admin.php?action=phpinfo',
                            ),1);
	$FORM->makenav("日志管理",array('添加日志'=>'admin.php?action=addBlog',
						 '编辑日志'=>'admin.php?action=editBlog',
                            ),1);
	$FORM->makenav("分类管理",array('添加分类'=>'admin.php?action=addSort',
						 '编辑分类'=>'admin.php?action=editSort',
                            ),1);
	$FORM->makenav("链接管理",array('添加链接'=>'admin.php?action=addLink',
						 '编辑链接'=>'admin.php?action=editLink',
                            ),1);
	$FORM->makenav("留言/评论",array('留言管理'=>'admin.php?action=guestbook',
						 '评论管理'=>'admin.php?action=remarkManager',
						 '过滤管理'=>'admin.php?action=banned',
                            ),1);
	$FORM->makenav("记事本",array('添加记事'=>'admin.php?action=addNote',
						 '管理记事'=>'admin.php?action=editNote',
                            ),1);
	$FORM->makenav("用户管理",array('添加用户'=>'admin.php?action=addUser',
						 '编辑用户'=>'admin.php?action=editUser',
                            ),1);
	$FORM->makenav("数据库选项",array('备份数据库'=>'admin.php?action=bak',
						 '恢复数据库'=>'admin.php?action=bakManager',
						 '优化数据库'=>'admin.php?action=optimize',
						 '修复数据库'=>'admin.php?action=repair',
						 '执行SQL查询'=>'admin.php?action=runsql',
                            ),1);
	$FORM->makenav("RSS 数据",array('RSS 导入'=>'admin.php?action=rssImport',
						 'RSS 导出'=>'admin.php?action=rssExport',
                            ),1);
	$FORM->makenav("文件管理",array('上传文件'=>'admin.php?action=upload',
						 '文件管理'=>'admin.php?action=uploadManager',
                            ),1);
	$FORM->makenav("模板管理",array('编辑模板'=>'admin.php?action=selectTemplate',
                            ),1);
	$FORM->makenav("辅助功能",array('重建静态页面'=>'admin.php?action=rebuild',
						 '引用通告管理'=>'admin.php?action=editTrackback',
                            ),1);
	$FORM->makenav("自动链接",array('添加链接'=>'admin.php?action=addAutolink',
						 '管理链接'=>'admin.php?action=editAutolink',
                            ),1);
	$FORM->makenav("管理日志",array('操作记录'=>'admin.php?action=actlog',
						 '登陆记录'=>'admin.php?action=userlog',
                            ),1);
	$FORM->makenav("管理员选项",array('修改密码'=>'admin.php?action=password',
                            ),1);
	$FORM->tablefooter();
	$FORM->cpfooter();
}

//框架右边默认页面
if(isset($_GET['frame']) && @$_GET['frame'] == 'main') {
	//register_globals
	if (function_exists('ini_get')){
		$onoff = ini_get('register_globals');
	} else {
		$onoff = get_cfg_var('register_globals');
	}
	if ($onoff){
		$onoff="打开";
	}else{
		$onoff="关闭";
	}

	//file_uploads
	if (function_exists('ini_get')){
		$upload = ini_get('file_uploads');
	} else {
		$upload = get_cfg_var('file_uploads');
	}
	if ($upload){
		$upload="可以";
	}else{
		$upload="不可以";
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
	<td class="maint1"><br><b>&nbsp;欢迎您, <?=$username ?><br>&nbsp;现在的时间是: <?=obdate("Y-m-d H:i:s",time()) ?></b><br><br></td>
	</tr>
	<tr> 
	<td> <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
	  <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding=4 cellspacing=1 bgcolor="#FFFFFF" class="tableoutline">
        <tr bgcolor="#F3F3F3">
          <td height="20" colspan="2" class=tbhead><strong>快捷方式</strong></td>
        </tr>
        <tr>
          <td width="50%" colspan="2"><a href="admin.php?action=addBlog">添加日志</a> | <a href="admin.php?action=editBlog">编辑日志</a> | <a href="admin.php?action=guestbook">留言管理</a> | <a href="admin.php?action=remarkManager">评论管理</a> | <a href="admin.php?action=addLink">添加链接</a> | <a href="admin.php?action=addNote">添加记事</a> | <a href="admin.php?action=upload">文件上传</a> | <a href="admin.php?action=rebuild">重建页面</a> | <a href="admin.php?action=selectTemplate">编辑模板</a></td>
        </tr>
      </table>
	  <table width="100%" border="0" cellpadding=4 cellspacing=1 bgcolor="#FFFFFF" class="tableoutline">
	<tr bgcolor="#F3F3F3"> 
	<td height="20" colspan="2" class=tbhead><strong>系统信息</strong></td>
	</tr>
	<tr> 
	<td width="50%">服务器软件: <?php echo @$_SERVER["SERVER_SOFTWARE"];?> </font>	</td>
	<td width="50%">服务器系统: <?php echo defined('PHP_OS') ? PHP_OS : '未知';?></font></td>
	</tr>
	<tr> 
	<td width="50%">PHP 版本: <?php echo @phpversion();?> </font></td>
	<td width="50%">MySQL 版本: <?php echo @mysql_get_server_info();?></font></td>
	</tr>
	<tr> 
	<td width="50%">register_globals: <?php echo @$onoff;?></font></td>
	<td width="50%">文件上传: <?php echo @$upload; ?> </font></td>
	</tr>
	<tr> 
	<td >服务器地址: <?php echo @$_SERVER[SERVER_ADDR];?></font></td>
	<td width="50%">服务器时区: <?php echo @obdate("T",time()); ?> </font></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#FFFFFF">
	<tr bgcolor="#F3F3F3"> 
	<td height="20" colspan="2" class="tbhead"><strong>程序信息</strong></td>
	</tr>
	<tr bgcolor="#FFFFFF" > 
	<td><p>程序开发:shishirui/风色</p></td>
	<td>版本:<?php echo $var; ?></td>
	</tr>
	<tr bgcolor="#FFFFFF" > 
	<td>E-mail:<a href="mailto:shishirui@163.com">shirui@gmail.com</a></td>
	<td width="50%">官方站点:<a href="http://www.phpBlog.cn" target="_blank">http://www.phpBlog.cn</a></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#FFFFFF">
	<tr bgcolor="#F3F3F3"> 
	<td height="20" colspan="2" class="tbhead"><strong>统计信息</strong></td>
	</tr>
	<tr bgcolor="#FFFFFF" > 
	<td>日志数量:<?php echo $stat[0]; ?></td>
	<td>评论数量:<?php echo $stat[3]; ?></td>
	</tr>
	<tr bgcolor="#FFFFFF" > 
	<td>留言数量:<?php echo $stat[2]; ?></td>
	<td width="50%">分类数量:<?php echo $stat[1]; ?></td>
	</tr>
	<tr bgcolor="#FFFFFF"> 
	<td width="50%" >链接数量:<?php echo $stat[4]; ?></td>
	<td width="50%" >引用数量:<?php echo $stat[5]; ?></td>
	</tr>
	<tr bgcolor="#FFFFFF"> 
	<td width="50%" >附件大小:<?php echo get_real_size($filesize) ?></td>
	<td width="50%" >数据库大小:<?php echo get_real_size($dbSize) ?></td>
	</tr>
	</table></td>
	</tr>
	</table></td>
	</tr>
	</table>
<?php
	$FORM->cpfooter();
}

//框架上边页面
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
	echo "<td><b>[<a href=\"login.php?frame=main\" target=\"mainFrame\">控制面板首页</a>] [<a href=\"../\" target=\"_blank\">BLOG 首页</a>]</b></td>\n";
	echo "<td align=\"right\"><b>[<a href=\"admin.php?action=logout\" target=\"_parent\">退出登陆</a>]</b></td>\n";
	echo "</tr>\n";
	echo "</table></td>\n\n";
	echo "</tr>\n\n";
	echo "</table>\n\n";
	echo "</body>\n";
	echo "</html>\n";
}
?>