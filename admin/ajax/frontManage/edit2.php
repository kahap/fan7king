<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$type = $_POST["type"];
	$type2 = isset($_POST[$type."2"])? isset($_POST[$type."2"]) : '';
	
	$fm2 = new Front_Manage2();
	$oldData = $fm2->getAllFM();
	

	$fm2->update($type, $_POST[$type]);
	if (isset($_POST[$type."2"])){
        $fm2->update($type2, $_POST[$type.'2']);
    }
	echo "成功更新！";
	
?>