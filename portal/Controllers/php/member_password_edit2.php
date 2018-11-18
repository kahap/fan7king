<?php

	session_start();
	include('../../model/php_model.php');

	if($_SESSION["success_phone"]!=false){
		$member = new Member();
		$member->update_fornMember_password2($_POST,$_SESSION["success_phone"]);
		unset($_SESSION["success_phone"]);
	}
	header("location:../../../?item=login");
	exit();
?>