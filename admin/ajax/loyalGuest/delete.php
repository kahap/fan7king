<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$lg = new Loyal_Guest();
	$lgNo = $_POST["lgNo"];
	
	$delete = $lg->delete($lgNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>