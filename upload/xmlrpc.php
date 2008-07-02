<?php
/*
+--------------------------------------------------------+
| O-BLOG - PHP Blog System                               |
| Copyright (c) 2004 phpBlog.CN                          |
| Support : http://www.phpBlog.cn                        |
| Author : ShiShiRui (shishirui@163.com)                 |
|--------------------------------------------------------+
| This Script Is Modified From Bo-blog v2.0.1 Sp1.       |
| Thanks You, Bob Shen!                                  |
|--------------------------------------------------------+
*/
error_reporting(7);
require('config.php');

$rawdata = get_http_raw_post_data();

$stringType_o="i4|int|boolean|struct|string|double|base64|dateTime\.iso8601";
$stringType="(".$stringType_o.")";

$rawdata = str_replace("\r", '', $rawdata);
$rawdata = str_replace("\n", '', $rawdata);
$rawdata = str_replace("<![CDATA[", '', $rawdata);
$rawdata = str_replace("]]>", '', $rawdata);
$rawdata = preg_replace_callback("/<struct>(.+?)<\/struct>/is", 'filter_struct', $rawdata);

if (!$rawdata) {
	header("content-Type: text/html; charset=UTF-8");
	die ("读取Post数据失败。可能的原因如下：<ul><li>您在浏览器中直接打开了这个文件，而不是通过XML-RPC协议；</li><li>您的服务器没有打开PHP的always_populate_raw_post_data选项，而且PHP的版本低于4.3.0（以上两个条件至少需要满足其一）。</li><li>其它未知错误。</li></ul>");
}

$nameType = array (
	'blogger.newPost' => array ('appkey', 'blogid', 'username', 'password', 'content', 'publish'),
	'blogger.editPost' => array ('appkey', 'postid', 'username', 'password', 'content', 'publish'),
	'blogger.getUsersBlogs' => array ('appkey', 'username', 'password'),
	'blogger.getUserInfo' => array ('appkey', 'username', 'password'),
	'blogger.getTemplate' => array ('appkey', 'blogid', 'username', 'password', 'templateType'),
	'blogger.setTemplate' => array ('appkey', 'blogid', 'username', 'password', 'template', 'templateType'),
 	'metaWeblog.newPost' => array ('blogid', 'username', 'password', 'struct', 'publish'),
 	'metaWeblog.editPost' => array ('postid', 'username', 'password', 'struct', 'publish'),
 	'metaWeblog.getPost' => array ('postid', 'username', 'password'),
 	'metaWeblog.newMediaObject' => array ('blogid', 'username', 'password', 'struct'),
 	'metaWeblog.getCategories' => array ('blogid', 'username', 'password'),
 	'metaWeblog.getRecentPosts' => array ('blogid', 'username', 'password', 'numberOfPosts')
);
$methodFamily = array('blogger.newPost', 'blogger.editPost', 'blogger.getUsersBlogs', 'blogger.getUserInfo', 	'blogger.getTemplate', 	'blogger.setTemplate', 	'metaWeblog.newPost', 'metaWeblog.editPost', 'metaWeblog.getPost', 'metaWeblog.newMediaObject', 'metaWeblog.getCategories', 'metaWeblog.getRecentPosts');

// Start To Do ...
$methodName = parse_get($rawdata, 'methodName', true);
if (!@in_array($methodName, $methodFamily)) xml_error ("Method ({$methodName}) is not availble.");
$values = parse_get($rawdata, 'value');
$values = parse_walk_array($values, $methodName);

//Get default category, for those editors which don't support Categories
$XMLRPC_sortid = $DB->fetch_one_array("SELECT id FROM {$mysql_prefix}class ORDER BY id ASC");
$defualtcategoryid = $XMLRPC_sortid[0];

$methodName = str_replace('.', '_', $methodName);
call_user_func ($methodName, $values);

// ============================= DEFINE FUNCTIONS =============================

// WO DEFINE SOME XML-RPC FUNCTIONS NOW

function get_http_raw_post_data() {
	if (isset($_SERVER['HTTP_RAW_POST_DATA'])) {
		return trim($_SERVER['HTTP_RAW_POST_DATA']);
	} elseif (PHP_OS >= "4.3.0") { 
		return file_get_contents( 'php://input' );
	} else {
		return false;
	}
}

//Parse specific value(s)
function parse_get ($whole_line, $parser, $single=false) {
	$reg= "/<".$parser.">(.+?)<\/".$parser.">/is";
	preg_match_all ($reg, $whole_line, $array_matches);
	if ($single) return $array_matches[1][0];
	else return $array_matches[1];
}

//Turn all values into readable forms
function parse_walk_array ($array, $names) {
	global $stringType, $nameType;
	if (!is_array($nameType[$names])) return;
	$reg= "/<".$stringType.">(.+?)<\/".$stringType.">/is";
	$i=0;
	foreach ($array as $whole_line) {
		$name=$nameType[$names][$i];
		if (is_array($whole_line)) $return[$name]=$whole_line;
		else {
			$try=preg_match($reg, $whole_line, $matches);
			if ($try=0) $return[$name]='';
			else {
				@list($whole, $type, $value)=$matches;
				if ($type!='struct') $return[$name]=$value;
				else $return[$name]=parse_struct($value);
			}
		}
		$i+=1;
		unset ($try, $name, $whole, $type, $value);
	}
	return $return;
}

function filter_struct ($matches) {
	global $stringType;
	$structcontent=$matches[0];
	$structcontent=preg_replace("/<".$stringType.">/is", "<struct-\\1>", $structcontent);
	$structcontent=preg_replace("/<\/".$stringType.">/is", "</struct-\\1>", $structcontent);
	$structcontent=str_replace("<value>", "<struct-value>", $structcontent);
	$structcontent=str_replace("</value>", "</struct-value>", $structcontent);
	$structcontent=str_replace("<struct-struct>", "<struct>", $structcontent);
	$structcontent=str_replace("</struct-struct>", "</struct>", $structcontent);
	return $structcontent;
}

//Now let's deal with struct
function parse_struct ($struct) {
	global $stringType;
	$reg= "/<struct-".$stringType.">(.+?)<\/struct-".$stringType.">/is";
	$all_names=parse_get($struct, 'name');
	$all_values=parse_get($struct, 'struct-value');
	foreach ($all_values as $single_value) {
		$try=preg_match($reg, $single_value, $matches);
		@list($whole, $type, $value)=$matches;
		$result_values[]=$value;
		unset ($whole, $type, $value);
	}
	$all_values=$result_values;
	if (function_exists('array_combine')) {
		$result=array_combine($all_names, $all_values);
	} else {
		for ($i=0; $i<count($all_names); $i++) {
			$key=$all_names[$i];
			$value=$all_values[$i];
			$result[$key]=$value;
		}
	}
	return $result;
}

//Output an error
function xml_error ($error) {
	$xml=<<<eot
<methodResponse>
  <fault>
    <value>
      <struct>
        <member>
          <name>faultCode</name>
          <value><int>500</int></value>
        </member>
        <member>
          <name>faultString</name>
          <value><string>{$error}</string></value>
        </member>
      </struct>
    </value>
  </fault>
</methodResponse> 
eot;
	send_response ($xml);
}

//Generate an XML cluster with certain format
function xml_generate ($body_xml) {
	$xml=<<<eot
<methodResponse>
	<params>
		<param>
			<value>
				{$body_xml}
			</value>
		</param>
	</params>
</methodResponse>
eot;
	return $xml;
}

//Compose a piece of XML
function make_xml_piece ($type, $values) {
	switch ($type) {
		case "array":
			$xml="
					<array>
						<data>";
			foreach ($values as $singlevalue) {
				$xml.="
							<value>
								{$singlevalue}
							</value>";
			}
			$xml.="
						</data>
					</array>";
			break;
		case "struct":
			$xml="
					<struct>";
			while (@list($key, $singlevalue)=@each($values)) {
				$xml.="
						<member>
							<name>{$key}</name>
							<value>{$singlevalue}</value>
						</member>";
			}
			$xml.="
					</struct>";
			break;
		default:
			$xml="<{$type}>{$values}</{$type}>";
		break;
	}
	return $xml;
}

//Send out the response
function send_response ($xml) {
	$date_p=date('r', time());
	$xml="<?xml version=\"1.0\" ?>\n".$xml;
	$lens=strlen($xml);
	header("HTTP/1.1 200 OK");
	header("Connection: close");
	header("Content-Length: {$lens}");
	header("Content-Type: text/xml");
	header("Date: {$date_p}");
	header("Server: O-Blog v2.x");
	echo ($xml);
	exit();
}

// HERE WO DEFINE SOME USEFUL FUNCTIONS

//Convert the submitted content back to HTML
//I will convert it into ubb code later.
//在这里做的是转换内容，分别trim,checkPost,toUbb,htmlspecialchars
function reduce_entities($str) {
	$str=stripslashes($str);
	$str=html_entity_decode($str, ENT_QUOTES);
	//$str=safe_convert($str, 1);
	$str = html2ubb($str);
	// debug
	$fp = fopen("zzz.php","wb+");
	fwrite($fp,$str);
	fclose($fp);
	
	return $str;
}

//Convert an iso8601 date into unix time format, or vice versa
function get_time_unix ($date, $destination="stamp") {
	if ($destination=="stamp") {
		$year=substr($date, 0, 4);
		$month=substr($date, 4, 2);
		$day=substr($date, 6, 2);
		$hour=substr($date, 9, 2);
		$minute=substr($date, 12, 2);
		$second=substr($date, 15, 2);
		$timestamp=mktime((integer)$hour, (integer)$minute, (integer)$second, (integer)$month, (integer)$day,  (integer)$year);
	} else {
		$timestamp=date("Ymd\TH:i:s\Z", $date);
	}
	return $timestamp;
}

//Encode convert
function utf2gb($char) {
	include_once("admin/class/chinese.php");
	$chs = new Chinese("UTF8","GB2312",$char);
	$char = $chs->ConvertIT();
	Return $char;
}

function gb2utf($char) {
	include_once("admin/class/chinese.php");
	$chs = new Chinese("GB2312","UTF8",$char);
	$char = $chs->ConvertIT();
	Return $char;
}

// WE WILL DEFINE SOME FUNCTIONS TO GET THE DATA FROM MYSQL DATABASE NOW

function checkuser_XMLRPC($username, $password) {
	global $mysql_prefix,$DB;
	$password = md5($password);
	$username = trim($username);
	$userdetail = $DB->fetch_one_array("SELECT * FROM `{$mysql_prefix}admin` WHERE `username`='{$username}' AND  `password`='{$password}'");
	if (!$userdetail) {
		return false;	
	} else {
		return $userdetail;
	}
}

function check_user($username, $password) {
	$userdetail = checkuser_XMLRPC($username, $password);
	if (!$userdetail) {
		xml_error("Authentification failed by the conbination of provided username ({$username}) and password.");
	} else {
		 return $userdetail;
	}
}

//functions of MetawebblogAPI
//We no longer provide the methods that resembles the same function as in bloggerAPI, eg metaWeblog.newPost is supported, but blogger.newPost is not
function blogger_getUsersBlogs ($values) {
	global $blogurl,$blogName;
	$blogName = gb2utf($blogName);
	$userdetail = check_user ($values['username'], $values['password']);
	$value_body = array('url'=>$blogurl, 'blogid'=>$values['appkey'], 'blogName'=>$blogName);
	$array_body[0] = make_xml_piece ("struct", $value_body);
	$xml_content = make_xml_piece("array", $array_body);
	$body_xml = xml_generate($xml_content);
	send_response($body_xml);
}

function blogger_getUserInfo ($values) {
	global $blogurl;
	$userdetail = check_user ($values['username'], $values['password']);
	$xml_content = make_xml_piece ("struct", array('nickname'=>$values['username'], 'userid'=>$userdetail['id'], 'url'=>$blogurl, 'email'=>'none@none.com'));
	$body_xml = xml_generate($xml_content);
	send_response ($body_xml);
}

function metaWeblog_newPost ($values) {
	global $DB, $defualtcategoryid, $mysql_prefix, $makehtml;

	$userdetail = check_user ($values['username'], $values['password']);
	$author = $userdetail['nickname'];
	$struct = $values['struct'];

	$struct['title'] = utf2gb($struct['title']);
	$struct['description'] = utf2gb($struct['description']);
	$struct['categories'] = utf2gb($struct['categories']);

	if (!$struct['title']) $title = "Untitled MetaWeblogAPI Entry";
	else $title = htmlspecialchars(checkPost(trim($struct['title'])));

	if (!$struct['description']) xml_error("You MUST provide a decription element in your post.");
	else $content = reduce_entities($struct['description']);

	if ($struct['pubDate']) $struct['dateCreated'] = $struct['pubDate'];

	if ($struct['dateCreated']) $time = get_time_unix($struct['dateCreated']);
	else $time = time();
	
	if ($struct['categories']!='') {
		$struct['categories'] = trim($struct['categories']);
		$category = $DB->fetch_one("SELECT id FROM {$mysql_prefix}class WHERE classname='{$struct['categories']}'");
		$category = ($category == '') ? $defualtcategoryid : $category;
	} else {
		$category = $defualtcategoryid;
	}

	$DB->query("INSERT INTO `".$mysql_prefix."blog`  (date,title,content,trackbackurl,filename,author,classid,top,allow_remark,allow_face,draft) VALUES ('".$time."','".$title."','".$content."','','','".$author."','".$category."','0','1','0','0')");
	$currentid = $DB->insert_id();

	if($makehtml) {
		require_once('admin/class/build.php');
		$html = new build;
		$html->makeindex();
		$html->make($currentid);
	}
	
	$xml_content = make_xml_piece("string", $currentid);
	$body_xml = xml_generate($xml_content);
	send_response ($body_xml);
}

function metaWeblog_editPost ($values) {
	global $DB, $defualtcategoryid, $mysql_prefix, $makehtml;
	$struct = $values['struct'];
	$userdetail = check_user ($values['username'], $values['password']);

	$struct['title'] = utf2gb($struct['title']);
	$struct['description'] = utf2gb($struct['description']);
	$struct['categories'] = utf2gb($struct['categories']);

	$records = $DB->fetch_one_array("SELECT * FROM {$mysql_prefix}blog WHERE id='{$values['postid']}'");
	if ($records['id'] == '') xml_error ("Entry does not exist.");

	if (!$struct['title']) $title = "Untitled MetaWeblogAPI Entry";
	else $title = htmlspecialchars(checkPost(trim($struct['title'])));

	if (!$struct['description']) xml_error("You MUST provide a decription element in your post.");
	else $content=reduce_entities($struct['description']);

	$nowtime = time();
	if ($struct['pubDate']) $struct['dateCreated'] = $struct['pubDate'];
	if ($struct['dateCreated']) $time = get_time_unix($struct['dateCreated']);
	else $time = $records['date'];

	if ($struct['categories']!='') {
		$struct['categories'] = trim($struct['categories']);
		$category = $DB->fetch_one("SELECT id FROM {$mysql_prefix}class WHERE classname='{$struct['categories']}'");
		if ($category == '') $category=$defualtcategoryid;
	} else {
		$category = $records['classid'];
	}
	
	$updateSql = "UPDATE `".$mysql_prefix."blog` SET `classid` = '".$category."',`title` = '".$title."',`content` = '".$content."',`trackbackurl` = '',`filename` = '',`date` = '".$time."',`top` = '".$records['top']."',`allow_remark` = '".$records['`allow_remark`']."',`allow_face` = '".$records['`allow_face`']."',`draft` = '".$records['`draft`']."' WHERE `id` = '".$values['postid']."'";
	$DB->query($updateSql);

	if($makehtml) {
		require_once('admin/class/build.php');
		$html = new build;
		$html->makeindex();
		$html->make($values['postid']);
	}
	
	$xml_content = make_xml_piece("boolean", '1');
	$body_xml = xml_generate($xml_content);
	send_response($body_xml);
}

function metaWeblog_getPost ($values) {
	global $DB, $defualtcategoryid, $mysql_prefix, $makehtml, $blogurl;
	$userdetail = check_user($values['username'], $values['password']);
	$records = $DB->fetch_one_array("SELECT * FROM {$mysql_prefix}blog WHERE id='{$values['postid']}'");
	
	if ($records['id'] == '') {
		xml_error ("Entry does not exist.");
	} else {
		$records['title'] = gb2utf($records['title']);
		$records['content'] = gb2utf($records['content']);

		$this_blog_url = $blogurl.getHtmlPath($records['id']);
		$time = get_time_unix($records['date'], 'iso');
		$value_body = array('dateCreated'=>$time, 'userid'=>$userdetail['id'], 'postid'=>$records['id'], 'description'=>htmlspecialchars($records['content']), 'title'=>htmlspecialchars($records['title']), 'link'=>"{$this_blog_url}", 'categories'=>make_xml_piece('array', array("Category {$records['classid']}")));
		$body = make_xml_piece ("struct", $value_body);
		$body_xml = xml_generate($body);
		send_response ($body_xml);
	}
}

function metaWeblog_getRecentPosts ($values) {
	global $DB, $defualtcategoryid, $mysql_prefix, $makehtml, $blogurl;

	$userdetail = check_user ($values['username'], $values['password']);
	
	$recordHd = $DB->query("SELECT * FROM {$mysql_prefix}blog ORDER BY date DESC LIMIT 0,{$values['numberOfPosts']}");
	if($DB->num_rows($recordHd) == 0) {
		xml_error ("Entry does not exist.");
	}

	while($record = $DB->fetch_array($recordHd)) {
		$record['content'] = gb2utf($record['content']);
		$record['title'] = gb2utf($record['title']);

		$time = get_time_unix($record['date'], 'iso');
		$this_blog_url = $blogurl.getHtmlPath($record['id']);
		$value_body = array('dateCreated'=>$time, 'userid'=>$userdetail['id'], 'postid'=>$record['id'], 'description'=>htmlspecialchars($record['content']), 'title'=>htmlspecialchars($record['title']), 'link'=>"{this_blog_url}", 'categories'=>make_xml_piece('array', array("Category {$record['classid']}")));
		$value_bodys[] = make_xml_piece ("struct", $value_body);
	}

	$body = make_xml_piece ("array", $value_bodys);
	$body_xml = xml_generate($body);
	send_response ($body_xml);
}

function metaWeblog_getCategories ($values) {
	global $DB, $defualtcategoryid, $mysql_prefix, $makehtml, $blogurl;
	$userdetail = check_user ($values['username'], $values['password']);
	//Get Categories
	$result = $DB->query("SELECT * FROM {$mysql_prefix}class ORDER BY `showorder` ASC");
	while ($row = $DB->fetch_array($result)) {
		$struct_body[] = make_xml_piece ("struct", array('description'=>"{$row['classname']}", 'htmlUrl'=>"{$blogurl}/index.php?do=class&amp;id={$row['id']}", 'rssUrl'=>"{$blogurl}/rss2.php?classid={$row['id']}"));
	}
	$xml_content = make_xml_piece ("array", $struct_body);
	$body_xml = xml_generate($xml_content);
	$body_xml = gb2utf($body_xml);

	send_response ($body_xml);
}


// GIVE AN ERROR CODE FOR BLOGGERAPI ALIASES 
function blogger_newPost ($values) {
	xml_error ("Sorry, this method is no longer supported. Please use metaWeblog.newPost instead.");
}

function blogger_editPost ($values) {
	xml_error ("Sorry, this method is no longer supported. Please use metaWeblog.editPost instead.");
}

// GIVE AN ERROR CODE FOR UNSUPPORTED METHODS, LIKE TEMPLATE
function blogger_getTemplate ($values) {
	xml_error ("Sorry, this method is not supported yet.");
}

function blogger_setTemplate ($values) {
	xml_error ("Sorry, this method is not supported yet.");
}

function metaWeblog_newMediaObject ($values) {
	xml_error ("Sorry, this method is not supported yet.");
}
?>