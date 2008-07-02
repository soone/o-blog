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
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:admin="http://webns.net/mvcb/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns="http://my.netscape.com/rdf/simple/0.9/">

<channel>
<title>$title</title> 
<link>http://$URL</link>
<description>$discribe</description>
<dc:language>zh-cn</dc:language>
<copyright>O-blog</copyright>
</channel>
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
<item rdf:about="$a[6]">
<title>$a[2]</title>
<link>$a[6]</link>
<description><![CDATA[ $a[7]]]></description>
<dc:date>$a[3]</dc:date> 
<guid>$a[6]</guid>
</item>
END;
}
print <<< END
</rdf:RDF>
END;

?>