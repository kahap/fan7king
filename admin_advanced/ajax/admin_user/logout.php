<?php
	session_start();
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_ajax.php');
	
	unset($_SESSION['adminUserData']);
	
	echo "登出成功！";
?>