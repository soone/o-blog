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

class DB_MySQL  {

	var $querycount = 0;

	function error() {
		return mysql_error();
	}

	function geterrno() {
		return mysql_errno();
	}

	function insert_id() {
		$id = mysql_insert_id();
		return $id;
	}

	function connect($servername, $dbusername, $dbpassword, $dbname, $usepconnect=0) {
		if($usepconnect) {
			if(!@mysql_pconnect($servername, $dbusername, $dbpassword)) {
				$this->halt("数据库链接失败");
			}
		} else {
			if(!@mysql_connect($servername, $dbusername, $dbpassword)) {
				$this->halt("数据库链接失败");
			}
		}

		mysql_select_db($dbname);
		$this->query('SET NAMES \'gbk\'');
	}

	function select_db($dbname) {
		return mysql_select_db($dbname);
	}

	function query($sql,$type = '') {
		$query = mysql_query($sql);
		if(!$query && $type != 'SILENT') {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querycount++;
		return $query;
	}

	function fetch_array($query) {
		return mysql_fetch_array($query);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_one_array($query) {
		$result = $this->query($query);
		$record = $this->fetch_array($result);
		return $record;
	}

	function fetch_one($query) {
		$record = $this->fetch_one_array($query);
		Return $record[0];
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function free_result($query) {
		$query = mysql_free_result($query);
		return $query;
	}

	function close() {
		return mysql_close();
	}

	function version() {
		return mysql_get_server_info();
	}

	function halt($msg,$sql=""){
		$message = "<html>\n<head>\n";
		$message .= "<meta content=\"text/html; charset=gb2312\" http-equiv=\"Content-Type\">\n";
		$message .= "<STYLE TYPE=\"text/css\">\n";
		$message .=  "body,td,p,pre {\n";
		$message .=  "font-family : Verdana, sans-serif;font-size : 11px;\n";
		$message .=  "}\n";
		$message .=  "</STYLE>\n";
		$message .= "</head>\n";
		$message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";

		$message .= "数据库出错: ".htmlspecialchars($msg)."\n<p>";
		$message .= "<b>Mysql error description</b>: ".$this->error()."\n<br>";
		$message .= "<b>Mysql error number</b>: ".$this->geterrno()."\n<br>";
		$message .= "<b>Date</b>: ".date("Y-m-d @ H:i",time())."\n<br>";
		$message .= "<b>Query</b>: ".$sql."\n<br>";
		$message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n<br>";

		$message .= "</body>\n</html>";
		die($message);
		exit;
	}
}
?>