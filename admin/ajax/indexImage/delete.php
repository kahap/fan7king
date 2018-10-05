<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$ad = new Advertise();
	$adNo = $_POST["adNo"];
	
	$delete = $ad->delete($adNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>