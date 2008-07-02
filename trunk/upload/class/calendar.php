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

$color3 = "#FFFFFF"; //日历的表格线颜色
$color4 = "#FFFFFF"; //日历的表格背景色
$calendarwidth = ""; //日历表格的宽度，可使用百分比（要带上百分号）或实际数值。

$dates = $DB->query("SELECT `date` FROM `".$mysql_prefix."blog` WHERE `draft`=0");
while($dateR = $DB->fetch_array($dates)) {
	$sdate[] = obdate("Ymd",$dateR['date']);
}

if(isset($_GET['date'])) {
	$date = $_GET['date'];
	if(strlen($date) == 6) {
		$date = $date;
		$y = substr($date,0,4);
		$m = substr($date,4,6);
	} else {
		$date = substr($date,0,8);
		$y = substr($date,0,4);
		$m = substr($date,4,2);
	}
} else {
	$y = obdate("Y",time());
	$m = obdate("n",time());
}

// $y = 2004;			当前年份
// $m = 10;				当前月份
$w = obdate("w", mktime(0, 0, 0, $m, 1, $y));
$tdtoday = obdate("j",time());
$tdt = obdate("t", mktime(0, 0, 0, $m, 1, $y));
$offset = 0;

if ($m < 12) {
    $nm = $m + 1;
	$nm = ($m<10) ? "0".$nm : $nm;
    $ny = $y;
} else {
    $nm = 1;
    $ny = $y + 1;
} 
if ($m < 10) {
    $ssm = "0" . $m;
} else {
    $ssm = $m;
} 

if ($m > 1) {
    $lm = $m-1;
	$lm = ($m<10) ? "0".$lm : $lm;
    $ly = $y;
} else {
    $lm = 12;
    $ly = $y-1;
} 
$m = ($m<10) ? "0".$m : $m;
$nm = ($nm == "010") ? "10" : $nm;
$nm = ($nm == "1") ? "01" : $nm;
$next_month = "<a href=\"index.php?date=". $ny.$nm . "\" class=\"calendarHerder\"> <b>&raquo;</b></a>\n";
$last_month = "<a href=\"index.php?date=". $ly.$lm . "\" class=\"calendarHerder\"><b>&laquo;</b> </a>\n";
$m = str_replace("00","0",$m);
$monthshow = $last_month . "月" . $next_month;
$yearshow = "<a href=\"index.php?date=". ($y-1). $m . "\" class=\"calendarHerder\"><b>&laquo;</b>  </a>\n年" . "<a href=\"index.php?date=".($y + 1). $m . "\" class=\"calendarHerder\"> <b>&raquo;</b></a>\n";
$monthshow = $last_month . "月" . $next_month;
$width1 = floor($calendarwidth / 4);
$width2 = $calendarwidth-2 * $width1;
// $calendar用于存储日历的表格,包括上面的年月
$calendar = ""; 
// 给$calendar变量赋值,只有上面的年月
$m = str_replace("00","0",$m);
$calendar .= 
"<table cellpadding=\"0\" width=\"{$calendarwidth}\" cellspacing=\"0\" border=\"0\" id=\"calendarHerder\">\r\n"
."<tr>\r\n"
."<td width=\"{$width1}\" align=\"center\" class=\"calendarHerder\">\r\n$monthshow\r\n</td>\r\n"
."<td width=\"{$width2}\" align=\"center\" class=\"calendarHerder\">".$y."年".$m."月</td>\r\n"
."<td width=\"{$width1}\" align=\"right\" class=\"calendarHerder\">\r\n$yearshow\r\n</td>\r\n"
."</tr>\r\n"
."<tr>"
."<td height=\"6\"></td>"
."</tr>"
."</table>\r\n"
."<table cellpadding=\"0\" width=\"{$calendarwidth}\" cellspacing=\"0\" bgcolor=\"{$color3}\" class=\"outTable\">\r\n"
."<tr>"
."<td>\r\n"
."<table cellpadding=\"4\" width=\"{$calendarwidth}\" cellspacing=\"1\" id=\"daytable\">\r\n";
// 结束$calendar变量赋值,只有上面的年月
$calendar .= 
"<tr align=\"center\" bgcolor=\"{$color4}\" class=\"calendar\">\r\n"
."<td class=\"weektd\"><div class=\"satsun\">日</div></td>\r\n"
."<td class=\"weektd\">一</td>\r\n"
."<td class=\"weektd\">二</td>\r\n"
."<td class=\"weektd\">三</td>\r\n"
."<td class=\"weektd\">四</td>\r\n"
."<td class=\"weektd\">五</td>\r\n"
."<td class=\"weektd\"><div class=\"satsun\">六</div></td>\r\n"
."</tr>\r\n";
$calendar .= "<tr align=\"center\" bgcolor=\"{$color4}\"  class=\"calendar\">\r\n";

for($i = 1;$i <= $w;$i++) {
    $calendar .= "<td>&nbsp;</td>\r\n";
} 

for($i = 1;$i <= 7 - $w;$i++) {
    if ($i < 10) $ssi = "0" . $i;
    else $ssi = $i;
    $ssi = $i;
    if ($ssi < 10) $ssi = "0" . $ssi;
    else $ssi = $ssi;
    $plusa = "";
    $plusb = "";
	$ssm = str_replace("00","0",$ssm);
    $howplus = $y . $ssm . $ssi;
	//print_r($sdate);
    // 如果当日有日志存在,那么用加上下面的链接
   // if (eregi ($howplus, $allrec)) {
	if(is_array($sdate)) {
		if (in_array($howplus,$sdate)) {
			$plusa = "<a href=\"index.php?date=".$howplus."\" class=\"haveblog\">";
			$plusb = "</a>";
		} 
	}
    // 如果是当日,那么用.riqi这个CSS样式来显示
    if ($i == $tdtoday && $m == obdate("n",time()) && $y == obdate("Y",time())) $calendar .= "<td class=\"riqi\">{$plusa}$i{$plusb}</td>\r\n";
    else $calendar .= "<td>{$plusa}$i{$plusb}</td>\r\n";
} 

$calendar .= "</tr>\r\n";
$calendar .= "<tr align=\"center\"  bgcolor=\"{$color4}\" class=\"calendar\">";

for($i;$i <= $tdt;$i++) {
    $offset += 1;
    if ($i < 10) $ssi = "0" . $i;
    else $ssi = $i;
    $plusa = "";
    $plusb = "";
	$ssm = str_replace("00","0",$ssm);
    $howplus = $y . $ssm . $ssi;
    // 如果当日有日志存在,那么用加上下面的链接
	if(is_array($sdate)) {
		if (in_array($howplus,$sdate)) {
			$plusa = "<a href=\"index.php?date=".$howplus."\" class=\"haveblog\">";
			$plusb = "</a>";
		} 
	}
    if ($i == $tdtoday && $m == obdate("n",time()) && $y == obdate("Y",time())) {
        $calendar .= "<td class=\"riqi\">{$plusa}$i{$plusb}</td>\r\n";
        if ($offset >= 7 && $i != $tdt) {
            $offset = 0;
            $calendar .= "</tr><tr align=\"center\"  bgcolor=\"{$color4}\" class=\"calendar\">";
        } 
        if ($offset >= 7 && $i == $tdt) $offset = 0;
        continue;
    } 
    $calendar .= "<td>{$plusa}$i{$plusb}</td>\r\n";
    if ($offset >= 7 && $i != $tdt) {
        $offset = 0;
        $calendar .= "</tr><tr align=\"center\"  bgcolor=\"{$color4}\" class=\"calendar\">\r\n";
    } 
    if ($offset >= 7 && $i == $tdt) $offset = 0;
} 

$space = 7 - $offset;
if ($space > 0 && $space < 7)

    for($i = 1;$i <= $space;$i++) {
    $calendar .= "<td>&nbsp;</td>\r\n";
} 
$calendar .= "</tr></table></td></tr></table>\r\n";
?>