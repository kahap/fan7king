<?php
	session_start();
	header ("content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_ajax.php');
	
	$login = new Login();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	//驗證
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	if($login->validate($name, $pwd)){
		$_SESSION["adminUserData"] = $login->getuserdata();
		$ip = (!empty($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP']:(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
		$sql = "INSERT INTO `loginLog`( `ip`, `userName`) VALUES ('".$ip."','".$name."')";
		$login->loginlog($sql);
		echo true;
	}else{
		echo false;
	}
?>
