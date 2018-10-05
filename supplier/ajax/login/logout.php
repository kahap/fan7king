<?php
	session_start();
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	setcookie(session_name(), '', 100);
	session_unset();
	session_destroy();
	$_SESSION = array();
	
	echo "登出成功";
?>