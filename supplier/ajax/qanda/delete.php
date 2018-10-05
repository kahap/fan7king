<?php
	require_once('../../model/require_login.php');
	
	$qa = new Que_And_Ans();
	$qaNo = $_POST["qaNo"];
	
	$delete = $qa->delete($qaNo);
	if($delete){
		echo "刪除成功";
	}else{
		echo "刪除失敗";
	}
?>