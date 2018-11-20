<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$ps = new Period_Setting();
	$psNo = $_POST["psNo"];
	
	$delete = $ps->delete($psNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>