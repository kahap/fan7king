<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$type = $_POST["type"];
	
	$fm2 = new Front_Manage2();
	$oldData = $fm2->getAllFM();
	
	
	$fm2->update($type, $_POST[$type]);
	echo "成功更新！";
	
?>