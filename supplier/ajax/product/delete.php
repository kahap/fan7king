<?php
	require_once('../../model/require_login.php');
	
	$pro = new Product();
	$proNo = $_POST["proNo"];
	
	$delete = $pro->delete($proNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>