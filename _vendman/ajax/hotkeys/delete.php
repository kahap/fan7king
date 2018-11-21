<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$hk = new Hotkeys();
	$hkNo = $_POST["hkNo"];
	
	$delete = $hk->delete($hkNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>