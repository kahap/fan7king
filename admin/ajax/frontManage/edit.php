<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$type = $_POST["type"];
	
	$fm = new Front_Manage();
	$oldData = $fm->getAllFM();

	$fm->update($type, $_POST[$type]);
	echo "成功更新！";
	
?>