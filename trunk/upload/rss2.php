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
require('config.php');
require('class/rss.php');

header("Content-type:application/xml");

print <<< END
<?xml version="1.0" encoding="gb2312" ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">

<channel about="http://$URL">
<title>$title</title> 
<link>http://$URL</link>
<description>$discribe</description>
<language>zh-cn</language>
<copyright>O-blog</copyright>
END;

for($i = 0;$i<$blognum;$i++)
{
$a[1]	= $blog[$i]['id'];
$a[2]	= $blog[$i]['title'];
$a[3]	= obdate('r',$blog[$i]['date']);
$a[4]	= $blog[$i]['classid'];
$a[5]	= $blog[$i]['content'];
$a[6] = ($makehtml) ? "http://".$URL."/".getHtmlPath($blog[$i]['id']) : "http://".$URL."/index.php?id=".$blog[$i]['id'];
$a[7]	= cn_substr($a[5],200);
$ubb = new Ubb();
$ubb->setString($a[7]);
$a[7] = $ubb->parse();
print <<< END
<item>
<title>$a[2]</title>
<link>$a[6]</link>
<pubDate>$a[3]</pubDate>
<guid>$a[6]</guid>
<description><![CDATA[ $a[7]]]></description>
</item>
END;
}
print <<< END
</channel>
</rss>
END;

?>