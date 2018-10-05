<?php
	require_once('../../model/require_login.php');
	
	$sup = new Supplier();
	$supNo = $_POST["supNo"];
	
	$delete = $sup->delete($supNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>