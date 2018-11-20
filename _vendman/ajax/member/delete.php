<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$member = new Member();
	$memNo = $_POST["memNo"];
	
	$delete = $member->delete($memNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>