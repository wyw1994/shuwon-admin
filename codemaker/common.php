<?php
header("Content-type: text/html; charset=utf-8");
// 配置数据库
$database = array();
$database['DB_HOST'] = '127.0.0.1';
$database['DB_NAME'] = 'jinpinguan';
$database['DB_USER'] = 'jinpinguan';
$database['DB_PWD'] = 'jinpinguan';
date_default_timezone_set('Asia/Shanghai');
$mysql_conn = @mysql_connect("{$database['DB_HOST']}", "{$database['DB_USER']}", "{$database['DB_PWD']}") or die("Mysql connect is error.");
mysql_select_db($database['DB_NAME'], $mysql_conn);
$result = mysql_query('show tables', $mysql_conn);
mysql_query('SET NAMES UTF8', $mysql_conn);
// 配置保留字
$reserved_word_arr = array('user_id','created_at','updated_at'); 
