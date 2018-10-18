<?php
	session_start();
	include('../model/php_model.php');
	$rc = new API("real_cases");
	$sql = "select * from fb_token where memNo = '".$_SESSION['user']['memNo']."' order by id desc limit 1";
	$data = $rc->getCustomSql($sql);
	$diffDay = (time() - (($data['0']['time'] != "") ? strtotime($data['0']['time']):0))/3600/24;
	if($diffDay > 1){
		$sql1 = "INSERT INTO `fb_token`(`memNo`, `accessToken`, `userID`, `expiresIn`) VALUES ('".$_SESSION['user']['memNo']."','".$_POST['accessToken']."','".$_POST['userID']."','".$_POST['expiresIn']."')";
		$rc->getCustomSql($sql1);
		echo "111";
	}else{

	}
?>